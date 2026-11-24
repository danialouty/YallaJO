
<footer
    class="w-full bg-ink text-white {{ request()->routeIs('home') ? 'landing-reveal' : '' }}"
    aria-label="تذييل الموقع"
>
    <div
        class="mx-auto max-w-[1360px]
               px-4 py-12 sm:px-6 lg:px-12"
    >

        <div
            class="grid grid-cols-1 gap-10
                   md:grid-cols-2 lg:grid-cols-4"
        >

            {{-- 1. About YallaJO --}}
            <section
                class="space-y-4"
                aria-labelledby="footer-about"
            >
                <h2
                    id="footer-about"
                    class="font-display text-xl font-bold"
                >
                    يلا جو

                    <span
                        lang="en"
                        dir="ltr"
                        class="inline-block text-base text-white/80"
                    >
                        YallaJO
                    </span>
                </h2>

                <p class="text-sm leading-7 text-white/80">
                    منصة أردنية تساعد عشاق المغامرات
                    على اكتشاف الأنشطة والتجارب
                    في مختلف مناطق المملكة.
                </p>

                <p class="text-sm text-white/70">
                    اكتشف الأردن بطريقة مختلفة.
                </p>
            </section>


            {{-- 2. Featured Destinations --}}
            <section
                class="space-y-4"
                aria-labelledby="footer-destinations"
            >
                <h2
                    id="footer-destinations"
                    class="font-display text-lg font-bold"
                >
                    وجهات مميزة في الأردن
                </h2>

                <ul class="space-y-3 text-sm text-white/80">

                    <li>وادي رم — المغامرات الصحراوية</li>

                    <li>وادي الموجب — المسارات المائية</li>

                    <li>محمية ضانا — الطبيعة والجبال</li>

                    <li>العقبة — الغوص والأنشطة البحرية</li>

                    <li>عجلون — الغابات والمسارات الجبلية</li>

                </ul>
            </section>


            {{-- 3. Community & Safety --}}
            <section
                class="space-y-4"
                aria-labelledby="footer-community"
            >
                <h2
                    id="footer-community"
                    class="font-display text-lg font-bold"
                >
                    المجتمع والسلامة
                </h2>

                <ul class="space-y-3 text-sm text-white/80">

                    <li>
                        @if (Route::has('communities.index'))
                            <a
                                href="{{ route('communities.index') }}"
                                class="hover:text-white hover:underline"
                            >
                                المجتمعات المحلية
                            </a>
                        @else
                            <span>المجتمعات المحلية</span>
                        @endif
                    </li>

                    <li>إرشادات السلامة في المغامرات</li>

                    <li>دليل المرشدين المحليين</li>

                    <li>الحفاظ على الطبيعة والبيئة</li>

                    <li>الأسئلة الشائعة والدعم</li>

                </ul>
            </section>


            {{-- 4. Social links --}}
            <section
                class="space-y-4"
                aria-labelledby="footer-contact"
            >
                <h2
                    id="footer-contact"
                    class="font-display text-lg font-bold"
                >
                    تواصل معنا عبر
                </h2>

                <p class="text-sm leading-7 text-white/80">
                    تابع يلا جو واكتشف آخر المغامرات والأنشطة في الأردن.
                </p>

                <nav class="flex flex-wrap gap-3" aria-label="حسابات يلا جو على مواقع التواصل">
                    <a href="https://www.instagram.com/yallajo/" target="_blank" rel="noopener noreferrer" class="footer-social-link">Instagram</a>
                    <a href="https://www.facebook.com/yallajo/" target="_blank" rel="noopener noreferrer" class="footer-social-link">Facebook</a>
                    <a href="https://www.tiktok.com/@yallajo" target="_blank" rel="noopener noreferrer" class="footer-social-link">TikTok</a>
                </nav>
            </section>

        </div>


        {{-- Footer Bottom --}}
        <div
            class="mt-10 flex flex-col gap-4
                   border-t border-white/20 pt-6
                   text-xs text-white/70
                   md:flex-row md:items-center
                   md:justify-between"
        >

            <p>
                © {{ now()->year }} يلا جو —
                جميع الحقوق محفوظة.
            </p>

            <div class="flex flex-wrap gap-4">

                @if (Route::has('privacy'))
                    <a
                        href="{{ route('privacy') }}"
                        class="hover:text-white hover:underline"
                    >
                        سياسة الخصوصية
                    </a>
                @else
                    <span>سياسة الخصوصية</span>
                @endif

                @if (Route::has('terms'))
                    <a
                        href="{{ route('terms') }}"
                        class="hover:text-white hover:underline"
                    >
                        شروط الاستخدام
                    </a>
                @else
                    <span>شروط الاستخدام</span>
                @endif

            </div>

        </div>

    </div>
</footer>
