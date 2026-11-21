<section class="bg-surface py-10 md:py-12" aria-labelledby="featured-adventures-heading">
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
        <div class="mb-7 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <p class="landing-eyebrow">أبرز التجارب</p>
                <h2 id="featured-adventures-heading" class="mt-2 font-display text-3xl font-bold text-ink">
                    مغامرات وتجارب الأردن
                </h2>
                <p class="mt-2 text-sm leading-7 text-muted">
                    اكتشف مجموعة من التجارب المستوحاة من طبيعة الأردن وثقافاته المحلية.
                </p>
            </div>
            <a href="{{ route('adventuresAll.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-control bg-brand px-4 text-sm font-bold text-white transition hover:bg-brand-dark">
                عرض كل المغامرات
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($adventures as $adventure)
                <x-adventure.card :adventure="[
                    'id' => $adventure->id,
                    'title' => $adventure->title,
                    'category' => $adventure->category,
                    'schedule' => $adventure->duration_hours . ' ساعات · ' . $adventure->difficulty_level,
                    'organizer' => 'منظم رحلة معتمد',
                    'organizer_initial' => 'م',
                    'rating' => '4.9',
                    'reviews_count' => 24,
                    'price' => number_format($adventure->price, 2),
                    'availability' => $adventure->capacity . ' مقاعد متاحة',
                    'image_alt' => $adventure->title,
                    'image' => $adventure->images->isNotEmpty()
                        ? asset('images/' . $adventure->images->first()->image_path)
                        : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900',
                ]" />
            @empty
                <div class="col-span-full rounded-card border border-outline/30 bg-canvas px-6 py-12 text-center text-muted">
                    لا توجد مغامرات متاحة حاليًا.
                </div>
            @endforelse
        </div>
    </div>
</section>
