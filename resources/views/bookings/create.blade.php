@extends('layouts.dashboard')

@section('dashboard-theme', 'adventurer')
@section('dashboard-title', 'حجز مغامرة')

@section('content')
    <x-ui.card>
        <h1 class="mb-4 text-xl font-bold text-ink">حجز مغامرة</h1>
        @if ($adventures->isEmpty())
            <p class="mb-4 text-muted">لا توجد مغامرات متاحة للحجز حاليًا.</p>
            <a href="{{ route('bookings.index') }}" class="text-forest underline focus-visible:outline-2">العودة إلى حجوزاتي</a>
        @else
            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="booking-adventure" class="block font-semibold">المغامرة</label>
                    <select id="booking-adventure" name="adventure_id" required class="w-full rounded-lg border-outline focus-visible:ring-2 focus-visible:ring-forest"
                        @error('adventure_id') aria-invalid="true" aria-describedby="booking-adventure-error" @enderror>
                        <option value="">اختر مغامرة</option>
                        @foreach ($adventures as $adventure)
                            <option value="{{ $adventure->id }}" @selected(old('adventure_id') == $adventure->id)>{{ $adventure->title }}</option>
                        @endforeach
                    </select>
                    @error('adventure_id') <p id="booking-adventure-error" class="text-danger" role="alert">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="booking-participants" class="block font-semibold">عدد المشاركين</label>
                    <input id="booking-participants" name="participants_count" type="number" min="1" value="{{ old('participants_count', 1) }}" required class="w-full rounded-lg border-outline focus-visible:ring-2 focus-visible:ring-forest"
                        @error('participants_count') aria-invalid="true" aria-describedby="booking-participants-error" @enderror>
                    @error('participants_count') <p id="booking-participants-error" class="text-danger" role="alert">{{ $message }}</p> @enderror
                </div>
                <x-ui.button type="submit">تأكيد الحجز</x-ui.button>
                <a href="{{ route('bookings.index') }}" class="inline-flex px-4 py-2 text-forest underline focus-visible:outline-2">إلغاء</a>
            </form>
        @endif
    </x-ui.card>
@endsection
