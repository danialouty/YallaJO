document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('chatbot-toggle');
    const panel = document.getElementById('chatbot-panel');
    const closeButton = document.getElementById('chatbot-close');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    const sendButton = document.getElementById('chatbot-send');
    const chatbotRoot = document.getElementById('yallajo-chatbot');
    const endpoint = chatbotRoot?.dataset.chatbotEndpoint;

    if (!toggle || !panel || !form || !input || !messages) return;

    function openChat() {
        panel.classList.remove('hidden');
        toggle.classList.add('active');
        toggle.setAttribute('aria-expanded', 'true');
        input.focus();
    }

    function closeChat() {
        panel.classList.add('hidden');
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.focus();
    }

    toggle.addEventListener('click', () => {
        if (panel.classList.contains('hidden')) openChat();
        else closeChat();
    });

    closeButton?.addEventListener('click', closeChat);

    form.addEventListener('submit', async event => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        const userMessage = document.createElement('div');
        userMessage.className = 'chatbot-message chatbot-message-user';
        userMessage.textContent = message;
        messages.appendChild(userMessage);

        input.value = '';
        input.disabled = true;
        sendButton.disabled = true;

        const loading = document.createElement('div');
        loading.className = 'chatbot-message chatbot-message-loading';
        loading.textContent = 'المساعد يفكر...';
        messages.appendChild(loading);
        messages.scrollTop = messages.scrollHeight;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            loading.remove();

            const aiMessage = document.createElement('div');
            aiMessage.className = 'chatbot-message chatbot-message-ai';

            if (!response.ok) {
                aiMessage.textContent = data.error || 'تعذر الوصول إلى المساعد حاليًا.';
            } else {
                aiMessage.textContent = data.reply || 'لم يصلني رد واضح، حاول مرة أخرى.';
            }

            messages.appendChild(aiMessage);
        } catch {
            loading.remove();
            const aiMessage = document.createElement('div');
            aiMessage.className = 'chatbot-message chatbot-message-ai';
            aiMessage.textContent = 'تعذر الاتصال بخدمة المساعد. تأكد أن Ollama يعمل.';
            messages.appendChild(aiMessage);
        } finally {
            input.disabled = false;
            sendButton.disabled = false;
            input.focus();
            messages.scrollTop = messages.scrollHeight;
        }
    });
});
