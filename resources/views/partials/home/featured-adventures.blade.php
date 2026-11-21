<section class="bg-surface py-16 md:py-20" aria-labelledby="featured-adventures-heading">
    <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
        <div class="mb-8 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex rounded-full bg-forest/10 px-3 py-1.5 text-xs font-semibold text-forest">
                    رحلات مختارة بعناية
                </span>
                <h2 id="featured-adventures-heading" class="mt-4 font-display text-3xl font-bold text-ink">
                    مغامرات وتجارب لأول مرة
                </h2>
                <p class="mt-3 leading-8 text-muted">
                    اكتشف مجموعة من التجارب المستوحاة من طبيعة الأردن وثقافاته المحلية.
                </p>
            </div>
            <a href="{{ route('adventuresAll.index') }}" class="font-semibold text-brand hover:underline">
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
