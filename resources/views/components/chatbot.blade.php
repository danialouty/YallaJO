<div id="yallajo-chatbot" data-chatbot-endpoint="{{ route('chatbot.chat') }}" dir="rtl" class="landing-scroll-entry fixed {{ request()->routeIs('adventures.create', 'adventures.edit') ? 'chatbot-at-top top-5' : 'bottom-5' }} left-5 z-[70] sm:left-6">
    <section id="chatbot-panel"
             class="chatbot-panel hidden"
             aria-label="مساعد يلا جو الذكي"
             aria-live="polite">
        <div class="chatbot-header">
            <div class="chatbot-avatar" aria-hidden="true">🤖</div>
            <div class="min-w-0">
                <h2 class="font-display text-sm font-bold">مساعد يلا جو</h2>
                <p class="mt-0.5 text-xs text-white/75">مساعدك لاكتشاف المغامرات في الأردن</p>
            </div>
            <button id="chatbot-close" type="button" class="chatbot-close" aria-label="إغلاق المحادثة">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div id="chatbot-messages" class="chatbot-messages">
            <div class="chatbot-message chatbot-message-ai">
                مرحبًا! أنا مساعد يلا جو. شو المغامرة أو المنطقة اللي مهتم تستكشفها؟
            </div>
        </div>

        <form id="chatbot-form" class="chatbot-form" novalidate>
            @csrf
            <label for="chatbot-input" class="sr-only">اكتب رسالتك للمساعد</label>
            <input id="chatbot-input"
                   name="message"
                   type="text"
                   maxlength="1000"
                   autocomplete="off"
                   placeholder="اسأل عن مغامرة..."
                   class="chatbot-input">
            <button id="chatbot-send" type="submit" class="chatbot-send" aria-label="إرسال الرسالة">
                <span aria-hidden="true">➤</span>
            </button>
        </form>
    </section>

    <button id="chatbot-toggle"
            type="button"
            class="chatbot-toggle"
            aria-label="فتح مساعد يلا جو الذكي"
            aria-expanded="false"
            aria-controls="chatbot-panel">
        <span class="chatbot-robot" aria-hidden="true">
            <span class="chatbot-antenna"></span>
            <span class="chatbot-face">
                <span class="chatbot-eye"></span>
                <span class="chatbot-eye"></span>
            </span>
        </span>
    </button>
</div>
