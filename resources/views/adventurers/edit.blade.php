@extends('layouts.site')

@section('title', 'تعديل الملف الشخصي | YallaJO')

@section('content')
    <section class="bg-canvas py-10 sm:py-16">
        <div class="mx-auto max-w-2xl px-4 sm:px-6">
            <h1 class="mb-6 font-display text-3xl font-bold text-ink">
                تعديل الملف الشخصي
            </h1>

            <x-ui.card padding="lg">
                @include('adventurers.partials.form', [
                    'adventurer' => $adventurer,
                    'formAction' => route('adventurer.update'),
                    'formMethod' => 'PATCH',
                    'submitLabel' => 'حفظ التعديلات',
                    'cancelUrl' => route('adventurer.show'),
                ])
            </x-ui.card>
        </div>
    </section>
@endsection
