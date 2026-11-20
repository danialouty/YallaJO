<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Booking;

class PaymentController extends Controller
{
    
    public function index()
    {
        $payments = Payment::all();
         return response()->json($payments);
    }

    
    public function create()
    {
        //
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

        $payment = Payment::create([
        'booking_id' => $booking->id,
        'total_amount' => $booking->total_amount,
        'method' => $validated['method'],
        'status' => 'Pending',
    ]);

    return response()->json([
        'message' => 'Payment created successfully',
        'payment' => $payment
    ], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       $payment = Payment::findOrFail($id);

       return response()->json($payment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
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

    return response()->json([
        'message' => 'Payment updated successfully',
        'payment' => $payment
    ]);

    }




    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

    // Delete the payment
    $payment->delete();

    // Return response
    return response()->json([
        'message' => 'Payment deleted successfully'
    ]);
    }

    public function showByBooking($bookingId)
    {
    $payments = Payment::where('booking_id', $bookingId)->get();

    return response()->json($payments);
     }
}
