@extends('layouts.dashboard')

@section('dashboard-theme', 'adventurer')
@section('dashboard-title', 'تعديل الحجز')

@section('content')
    <x-ui.card>
        <h1 class="mb-4 text-xl font-bold text-ink">تعديل حجز {{ $booking->adventure->title }}</h1>
        <form method="POST" action="{{ route('bookings.update', $booking) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label for="booking-participants" class="block font-semibold">عدد المشاركين</label>
                <input id="booking-participants" name="participants_count" type="number" min="1" value="{{ old('participants_count', $booking->participants_count) }}" required class="w-full rounded-lg border-outline focus-visible:ring-2 focus-visible:ring-forest"
                    @error('participants_count') aria-invalid="true" aria-describedby="booking-participants-error" @enderror>
                @error('participants_count') <p id="booking-participants-error" class="text-danger" role="alert">{{ $message }}</p> @enderror
            </div>
            <x-ui.button type="submit">حفظ التعديل</x-ui.button>
            <a href="{{ route('bookings.index') }}" class="inline-flex px-4 py-2 text-forest underline focus-visible:outline-2">إلغاء</a>
        </form>
    </x-ui.card>
@endsection
