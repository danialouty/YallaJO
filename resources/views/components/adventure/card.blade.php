
@props([
    'adventure',
])

<x-ui.card
    padding="none"
    class="landing-featured-card group flex h-full flex-col overflow-hidden
           transition-all duration-300
           hover:-translate-y-1 hover:shadow-xl"
>
    <article class="flex h-full flex-col">

        {{-- Adventure Image --}}
        <div
            class="relative aspect-[4/3]
                   overflow-hidden bg-canvas"
        >
            <img
                src="{{ $adventure['image'] }}"
                alt="{{ $adventure['image_alt'] }}"
                loading="lazy"
                decoding="async"
                class="h-full w-full object-cover
                       transition-transform duration-500
                       group-hover:scale-105"
            >

            {{-- Adventure Category --}}
            <span
                class="absolute right-3 top-3
                       adventure-tag rounded-full
                       px-3 py-1.5 text-xs
                       font-semibold text-white"
            >
                {{ $adventure['category'] }}
            </span>

            {{-- Decorative Favorite Icon --}}
            <span
                class="absolute left-3 top-3
                       flex size-9 items-center justify-center
                       rounded-full bg-white/95
                       text-xl text-ink shadow-sm"
                aria-hidden="true"
            >
                ♡
            </span>

            {{-- Availability Preview --}}
            <span
                class="absolute bottom-3 left-3
                       rounded-full bg-white/95
                       px-3 py-1.5 text-xs
                       font-semibold text-brand"
            >
                {{ $adventure['availability'] }}
            </span>
        </div>


        {{-- Adventure Content --}}
        <div class="flex flex-1 flex-col p-4">

            {{-- Schedule Information --}}
            <p class="mb-3 text-xs leading-6 text-muted">
                {{ $adventure['schedule'] }}
            </p>

            {{-- Adventure Title --}}
            <h3
                class="mb-4 line-clamp-2 min-h-12
                       font-display text-lg
                       font-bold leading-6 text-ink"
            >
                {{ $adventure['title'] }}
            </h3>

            {{-- Organizer --}}
            <div class="flex items-center gap-2">

                <span
                    class="flex size-8 shrink-0 items-center
                           justify-center rounded-full
                           bg-brand/10 text-sm
                           font-bold text-brand"
                    aria-hidden="true"
                >
                    {{ $adventure['organizer_initial'] }}
                </span>

                <span
                    class="min-w-0 flex-1 truncate
                           text-xs text-muted"
                >
                    {{ $adventure['organizer'] }}
                </span>

            </div>

            {{-- Rating --}}
            <div
                class="mt-3 flex items-center gap-1
                       text-sm"
                aria-label="تقييم تجريبي"
            >
                <span class="text-accent" aria-hidden="true">
                    ★
                </span>

                <span class="font-bold text-ink" dir="ltr">
                    {{ $adventure['rating'] }}
                </span>

                <span class="text-xs text-muted" dir="ltr">
                    ({{ $adventure['reviews_count'] }})
                </span>
            </div>

            {{-- Price & Action --}}
            <div
                class="mt-auto flex items-end
                       justify-between gap-3
                       border-t border-outline/30
                       pt-4"
            >
                <div>
                    <p class="text-xs text-muted">
                        السعر للتذكرة
                    </p>

                    <p
                        class="mt-1 font-display
                               text-xl font-bold text-brand"
                    >
                        {{ $adventure['price'] }}

                        <span class="text-sm">
                            دينار
                        </span>
                    </p>
                </div>

                @if (!empty($adventure['id']))
                    <a href="{{ route('adventures.profile', $adventure['id']) }}"
                       class="inline-flex min-h-11 items-center justify-center rounded-control bg-brand px-4 py-2 text-sm font-bold text-white transition hover:bg-brand-dark">
                        احجز الآن
                    </a>
                @else
                    <span class="inline-flex min-h-11 items-center justify-center rounded-control bg-outline/20 px-4 py-2 text-sm text-muted">
                        احجز الآن
                    </span>
                @endif
            </div>

        </div>

    </article>
</x-ui.card>
