<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateBookingValidationRequest;
use App\Models\Booking;
use App\Models\Adventure;
use App\Models\Adventurer;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateBookingValidationRequest;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $adventurer = Adventurer::firstOrCreate(['user_id' => $userId]);
        $bookings = Booking::with('adventure')->where('adventurer_id', $adventurer->id)->latest()->get();
        $adventures = Adventure::all();
        return view('bookings.index', [
            'data' => $bookings,
            'adventures' => $adventures,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adventures = Adventure::all();
        return view('bookings.create', ['adventures' => $adventures]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookingValidationRequest $request)
    {
        $adventure = Adventure::find($request->adventure_id);
        $userId = Auth::user()->id;
        $adventurer = Adventurer::where('user_id', '=', $userId)->first();

        $counter = Booking::where('adventure_id', '=', $adventure->id)->where('adventurer_id', '=', $adventurer->id)->count();
        if ($counter > 0) {
            return redirect()->back()->with('error', 'Sorry, you have already booked this adventure.');
        }
        $unitPrice = $adventure->price;
        $commissionRate = 0.10;
        $totalAmount = $unitPrice * $request->participants_count;

        $dataToInsert = [
            'adventure_id' => $adventure->id,
            'adventurer_id' => $adventurer->id,
            'participants_count' => $request->participants_count,
            'unit_price' => $unitPrice,
            'commission_rate' => $commissionRate,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'capacity'=>$adventure->capacity ?? $request->participants_count,
        ];

        Booking::create($dataToInsert);
        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userId = Auth::id();
        $adventurer = Adventurer::firstOrCreate(['user_id' => $userId]);

        $booking = Booking::with('adventure')
            ->where('adventurer_id', $adventurer->id)
            ->findOrFail($id);

        return view('bookings.show', ['data' => $booking]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Booking::findOrFail($id);
        return view('bookings.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingValidationRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'participants_count' => $request->participants_count,
            'total_amount' => $request->participants_count * $booking->unit_price
        ]);
        return redirect()->route('bookings.index')->with('success', 'booking updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Booking::findOrFail($id);
        $data->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }
}
