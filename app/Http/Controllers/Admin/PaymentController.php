<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private const STATUSES = ['Pending' => 'قيد الانتظار', 'Confirmed' => 'مؤكدة', 'Canceled' => 'ملغاة'];

    private const METHODS = ['Cash' => 'نقدًا', 'Visa' => 'فيزا'];

    public function index(Request $request): View|RedirectResponse
    {
        Gate::authorize('viewAny', Payment::class);

        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'in:Pending,Confirmed,Canceled'],
            'method' => ['nullable', 'in:Cash,Visa'],
        ], [
            'search.integer' => 'أدخل رقم دفعة أو حجز صالحًا.',
            'search.min' => 'أدخل رقم دفعة أو حجز صالحًا.',
            'status.in' => 'اختر حالة دفع صالحة.',
            'method.in' => 'اختر طريقة دفع صالحة.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.payments.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = Payment::query()->latest('id');

        if (filled($filters['search'] ?? null)) {
            $search = (int) $filters['search'];
            $query->where(fn ($payments) => $payments->where('id', $search)->orWhere('booking_id', $search));
        }

        foreach (['status', 'method'] as $field) {
            if (filled($filters[$field] ?? null)) {
                $query->where($field, $filters[$field]);
            }
        }

        return view('admin.payments.index', [
            'payments' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
            'statuses' => self::STATUSES,
            'methods' => self::METHODS,
        ]);
    }

    public function show(Payment $payment): View
    {
        Gate::authorize('view', $payment);
        $payment->load('booking.adventure');

        return view('admin.payments.show', [
            'payment' => $payment,
            'statusLabel' => self::STATUSES[$payment->status] ?? $payment->status,
            'methodLabel' => self::METHODS[$payment->method] ?? $payment->method,
        ]);
    }
}
