<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Booking;

class PaymentController extends Controller
{
    
    public function index()
    {
        $payments = Payment::with('booking')->get();

        return view('payments.index', compact('payments'));
    }

    
    public function create()
    {
    $bookings = Booking::all();

    return view('payments.create', compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'method' => 'required|in:Visa,Cash',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        Payment::create([
            'booking_id' => $booking->id,
            'total_amount' => $booking->total_amount,
            'method' => $validated['method'],
            'status' => 'Pending',
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'تم إنشاء الدفعة بنجاح');
    }

    // Display one payment
    public function show($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        return view('payments.show', compact('payment'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);

        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    $payment = Payment::findOrFail($id);

    $validated = $request->validate([
        'method' => 'sometimes|required|in:Visa,Cash',
        'status' => 'sometimes|required|in:Pending,Canceled,Confirmed',  
          ]);

    $payment->update($validated);

    if ($payment->status === 'Confirmed' && $payment->paid_at === null) {
    $payment->update([
        'paid_at' => now()
    ]);
    }

    return redirect()->route('payments.index')
        ->with('success', 'تم تعديل الدفعة بنجاح');

    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح');
    }

    public function showByBooking($bookingId)
    {
    $booking = Booking::findOrFail($bookingId);

        $payments = Payment::where('booking_id', $booking->id)->get();

        return view('payments.index', compact('payments'));
}
}