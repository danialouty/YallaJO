<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adventure;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'in:Pending,Accepted,Cancelled'],
        ], [
            'search.*' => 'أدخل قيمة بحث صالحة.',
            'status.in' => 'اختر حالة صالحة.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.bookings.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = Booking::select('bookings.*')
            ->with(['adventure:id,title,status', 'adventurer.user:id,name'])
            ->withExists('payment')
            ->addSelect(['linked_review_id' => Review::select('id')->whereColumn('booking_id', 'bookings.id')->limit(1)])
            ->latest('id');

        if (filled($filters['search'] ?? null)) {
            $query->whereKey((int) $filters['search']);
        }

        if (filled($filters['status'] ?? null)) {
            $query->where('status', $filters['status']);
        }

        return view('admin.bookings.index', [
            'records' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
        ]);
    }

    public function show(string $booking): View
    {
        $booking = Booking::with(['adventure:id,title,status', 'adventurer.user:id,name'])->findOrFail($booking);
        $payments = Payment::where('booking_id', $booking->id)->latest('id')->paginate(10);

        $canChange = $this->canChange($booking);
        $canEdit = $canChange && $booking->adventure?->status === 'Active';

        return view('admin.bookings.show', compact('booking', 'payments', 'canChange', 'canEdit'));
    }

    public function edit(string $booking): View|RedirectResponse
    {
        $booking = Booking::with('adventure:id,status')->findOrFail($booking);
        if (! $this->canChange($booking) || $booking->adventure?->status !== 'Active') {
            return redirect()->route('admin.bookings.show', $booking)->with('error', 'التعديل متاح للحجز المعلّق لمغامرة نشطة دون دفعة أو تقييم.');
        }

        return view('admin.bookings.form', compact('booking'));
    }

    public function update(Request $request, string $booking): RedirectResponse
    {
        $data = $request->validate([
            'participants_count' => ['required', 'integer', 'between:1,2147483647'],
        ]);
        $record = Booking::findOrFail($booking);

        DB::transaction(function () use ($record, $data) {
            $adventure = Adventure::query()->lockForUpdate()->findOrFail($record->adventure_id);
            $current = Booking::query()->lockForUpdate()->findOrFail($record->id);
            if (! $this->canChange($current) || $current->adventure_id !== $adventure->id || $adventure->status !== 'Active') {
                throw ValidationException::withMessages(['participants_count' => 'لا يمكن تعديل هذا الحجز في حالته الحالية.']);
            }

            $reserved = $adventure->reservedBookings()->whereKeyNot($current->id)->sum('participants_count');
            $participants = (int) $data['participants_count'];
            if ($participants > $adventure->capacity - $reserved) {
                throw ValidationException::withMessages(['participants_count' => 'عدد المشاركين يتجاوز المقاعد المتاحة.']);
            }

            $current->update([
                'participants_count' => $participants,
                'total_amount' => Booking::calculateTotal($current->unit_price, $participants),
            ]);
        });

        return redirect()->route('admin.bookings.show', $record)->with('success', 'حُفظ الحجز وأُعيد حساب إجماليه.');
    }

    public function cancel(Request $request, string $booking): RedirectResponse
    {
        $request->validate(
            ['current_password' => ['required', 'current_password']],
            ['current_password.required' => 'يجب تأكيد كلمة مرورك لإلغاء الحجز.',
             'current_password.current_password' => 'كلمة المرور غير صحيحة.'],
        );

        $cancelled = DB::transaction(function () use ($booking) {
            $record = Booking::query()->lockForUpdate()->findOrFail($booking);
            if (! $this->canChange($record)) {
                return false;
            }
            $record->update(['status' => 'Cancelled', 'cancelled_at' => now()]);

            return true;
        });

        $redirect = redirect()->route('admin.bookings.show', $booking);

        return $cancelled
            ? $redirect->with('success', 'أُلغي الحجز وبقي سجله محفوظًا.')
            : $redirect->with('error', 'الإلغاء متاح للحجز المعلّق الذي لا يرتبط بدفعة أو تقييم.');
    }

    private function canChange(Booking $booking): bool
    {
        return $booking->status === 'Pending'
            && ! Payment::where('booking_id', $booking->id)->exists()
            && ! Review::where('booking_id', $booking->id)->exists();
    }
}
