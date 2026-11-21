
@php
    $featuredAdventures = [
        [
            'title' => 'مسار سيق وادي الموجب والشلال',
            'category' => 'مسارات مائية • متوسط',
            'schedule' => 'المدة: 5 ساعات • السبت القادم',
            'organizer' => 'سيف المجالي (مرشد معتمد)',
            'organizer_initial' => 'س',
            'rating' => '4.9',
            'reviews_count' => 128,
            'price' => 25,
            'availability' => 'متبقي 4 مقاعد فقط!',
            'image_alt' => 'مغامرة مائية داخل وادي الموجب',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBRKrCz8Rqzg5rsna59B08-KjLtNfc5pNKaJ433rnS7-VzAUfPkGd2ETmh9YEacspJ9L9TFE4iEsjV79eYb4T84wX2YKaGG6JUJFBCYSsHy9NsUzsnrn2_NvCS2d4w920GlzZxoF85p7YKT7VbpAiV9177XwecUoT-8stwXEbDyY_NshlF3fQX93J_EsthtR66blWiYGZW67C29Y3u3Twx-NhS3ysq7GKqKIR0sD9Q',
        ],
        [
            'title' => 'قطف الزيتون في عجلون مع غداء طابون',
            'category' => 'فعالية ثقافية',
            'schedule' => 'يوم كامل مع عائلات محلية • الجمعة',
            'organizer' => 'جمعية سيدات كفرنجة',
            'organizer_initial' => 'ج',
            'rating' => '5.0',
            'reviews_count' => 94,
            'price' => 18,
            'availability' => 'متبقي 8 مقاعد',
            'image_alt' => 'بساتين الزيتون في عجلون',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAlaF5EvCBSjSs8edoz8X3dSK9yAtjbN8uGRXu3SBpdmZrQvlge3cwy2q8Y-GpwOO7B10yPcwEGU_zQIFch3gRjoGUS4pvpDKqZlZuxBPt4GLWVAxe2ThGnMt4fJdXjW364HzKbPmEPCqplzrHxZoDnO6TdBEO2JSjJzyi_9xHwHxpZrixNmP-mnxn3_YScGf-XubnC1PHRZbMkqf116iVqXyAdKapNq-uMzQC0D2g',
        ],
        [
            'title' => 'تجربة الغطس واستكشاف المرجان في العقبة',
            'category' => 'رياضات بحرية • للمبتدئين',
            'schedule' => 'شامل المعدات والتدريب • الأحد',
            'organizer' => 'أكاديمية العقبة للغوص',
            'organizer_initial' => 'ع',
            'rating' => '4.8',
            'reviews_count' => 210,
            'price' => 45,
            'availability' => 'متبقي مقعدين فقط!',
            'image_alt' => 'الشعاب المرجانية في العقبة',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6QlIOHV68ctXqjKwqAx1R8h72S41ulqvLYgHQiRQkdiQCPgpnG2lYL5C2p_BCTmuYZHet7raZKIXptW_twGjR6ViRgrkWkt1UQzuJYijwef9QaIr2LkY3GED91Yq1tb_H_0by6csXKj12yB-TS_Ncjy9WjA8NbZuGhU4qssrklXxYspKkMPDB9xJL0bho955ii2sS5kGx4Zivc2nvO8gdF9i8vS93izBcYK4z29M',
        ],
        [
            'title' => 'تخييم ورصد النجوم في وادي رم',
            'category' => 'تخييم وفلك • للجميع',
            'schedule' => 'ليلة كاملة + عشاء زرب • نهاية الأسبوع',
            'organizer' => 'مخيم بدو رم مع الفلكي زيد',
            'organizer_initial' => 'ز',
            'rating' => '4.9',
            'reviews_count' => 315,
            'price' => 55,
            'availability' => 'متبقي 5 مقاعد',
            'image_alt' => 'مخيم صحراوي في وادي رم',
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA9-sj5qnkInt-u_qhQO88o7OCcLx92Xv3y0Eb1WUYPQOL4EKDW514ZbEJBt4KSLDZR_GpoDFijbm96nEC2ZSMvDfzxGBfqEQR1JJ9KtFszEB3vnNV_SQuffuBrmQPYfMkMTbRhLgZf3ZN1SfCqxCCIttqLDNtF7GsvMwz1aZBAjKr9pFWgbLfjgiCnE-iMN-z0ZKA_jj-uytpTWdCpg4ypOeUqJ52jv3rpKit6rfU',
        ],
    ];
@endphp

<section
    class="bg-surface py-16 md:py-20"
    aria-labelledby="featured-adventures-heading"
    data-featured-adventures
>
    <div
        class="mx-auto max-w-[1360px]
               px-4 sm:px-6 lg:px-12"
    >

        {{-- Section Header --}}
        <div
            class="mb-8 flex flex-col gap-5
                   md:flex-row md:items-end
                   md:justify-between"
        >
            <div class="max-w-2xl">

                <span
                    class="inline-flex rounded-full
                           bg-forest/10 px-3 py-1.5
                           text-xs font-semibold text-forest"
                >
                    أحدث الرحلات المتاحة
                </span>

                <h2
                    id="featured-adventures-heading"
                    class="mt-4 font-display text-3xl
                           font-bold text-ink"
                >
                    مغامرات وتجارب لأول مرة
                </h2>

                <p class="mt-3 leading-8 text-muted">
                    اكتشف مجموعة من المغامرات والتجارب
                    المستوحاة من الطبيعة الأردنية.
                </p>

                <p class="mt-2 text-xs text-accent">
                    بيانات تجريبية من تصميم Stitch
                    للمعاينة فقط.
                </p>

            </div>

            @if (Route::has('adventures.index'))
                <a
                    href="{{ route('adventures.index') }}"
                    class="font-semibold text-brand
                           hover:underline"
                >
                    عرض كل المغامرات
                </a>
            @else
                <span class="text-sm text-muted">
                    استكشاف جميع المغامرات قريبًا
                </span>
            @endif

        </div>


        {{-- Adventures Grid --}}
        <div
            class="grid grid-cols-1 gap-6
                   sm:grid-cols-2 lg:grid-cols-4"
        >
            @foreach ($featuredAdventures as $adventure)

                <x-adventure.card
                    :adventure="$adventure"
                />

            @endforeach
        </div>

    </div>
</section>
