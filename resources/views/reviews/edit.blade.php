@extends('layouts.site')

@section('title', 'تعديل التقييم | ' . $adventure->title . ' | YallaJO')

@section('content')
    <section class="bg-canvas py-8 sm:py-14" aria-labelledby="edit-review-heading">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <header class="mb-6">
                <h1 id="edit-review-heading" class="font-display text-3xl font-bold text-ink sm:text-4xl">
                    تعديل التقييم
                </h1>
                <p class="mt-2 text-lg text-muted [overflow-wrap:anywhere]">{{ $adventure->title }}</p>
            </header>

            <x-ui.card variant="outline" padding="lg">
                @include('reviews.partials.edit-form')
            </x-ui.card>
        </div>
    </section>
@endsection
