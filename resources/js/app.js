import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

Alpine.plugin(intersect);
window.Alpine = Alpine;

window.chatWidget = ({ productId = null, locale = 'en', text = {} } = {}) => ({
    open: false,
    busy: false,
    name: '',
    email: '',
    phone: '',
    topic: 'product',
    productId: productId ? Number(productId) : null,
    locale,
    text,
    conversationId: null,
    status: 'waiting',
    message: '',
    messages: [],
    error: '',
    poller: null,

    init() {
        window.addEventListener('open-velmora-chat', () => { this.open = true; });
        const savedConversation = window.localStorage.getItem('velmora-chat');
        if (savedConversation) {
            this.conversationId = savedConversation;
            this.refresh();
            this.poller = window.setInterval(() => this.refresh(), 4000);
        }
    },

    async start(offline) {
        this.error = '';
        if (!this.name.trim() || !this.email.trim()) {
            this.error = this.text.emailRequired;
            return;
        }

        this.busy = true;
        try {
            const endpoint = offline ? '/chat/offline' : '/chat/start';
            const payload = {
                name: this.name.trim(),
                email: this.email.trim(),
                phone: this.phone.trim() || null,
                product_id: this.productId,
                page_url: window.location.href,
                locale: this.locale,
            };

            if (offline) {
                payload.message = this.message.trim() || this.text.offlineDefault;
            } else {
                payload.topic = this.topic;
            }

            const response = await this.request(endpoint, payload);
            this.conversationId = response.uuid;
            this.status = response.status;
            window.localStorage.setItem('velmora-chat', this.conversationId);
            await this.refresh();
            if (!this.poller) this.poller = window.setInterval(() => this.refresh(), 4000);
        } catch (error) {
            this.error = error.message || this.text.startError;
        } finally {
            this.busy = false;
        }
    },

    async send() {
        const text = this.message.trim();
        if (!text || !this.conversationId) return;
        this.error = '';
        this.busy = true;
        try {
            await this.request(`/chat/${this.conversationId}/messages`, { message: text }, 'POST');
            this.message = '';
            await this.refresh();
        } catch (error) {
            this.error = error.message;
        } finally {
            this.busy = false;
        }
    },

    async attach(event) {
        const file = event.target.files?.[0];
        if (!file || !this.conversationId) return;
        this.error = '';
        this.busy = true;
        try {
            const formData = new FormData();
            formData.append('attachment', file);
            const response = await fetch(`/chat/${this.conversationId}/attachments`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: formData,
            });
            const body = await response.json();
            if (!response.ok) throw new Error(body.message || this.text.uploadError);
            await this.refresh();
        } catch (error) {
            this.error = error.message;
        } finally {
            event.target.value = '';
            this.busy = false;
        }
    },

    async refresh() {
        if (!this.conversationId) return;
        const lastId = this.messages.at(-1)?.id || 0;
        try {
            const response = await fetch(`/chat/${this.conversationId}/messages?after=${lastId}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (response.status === 404) {
                this.resetConversation();
                return;
            }
            const body = await response.json();
            if (!response.ok) throw new Error(body.message || this.text.loadError);
            this.status = body.status;
            this.messages.push(...body.messages);
            this.$nextTick(() => { if (this.$refs.messageList) this.$refs.messageList.scrollTop = this.$refs.messageList.scrollHeight; });
        } catch (error) {
            this.error = error.message;
        }
    },

    async request(url, payload, method = 'POST') {
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload),
        });
        const body = await response.json();
        if (!response.ok) throw new Error(body.message || this.text.sendError);
        return body;
    },

    resetConversation() {
        window.localStorage.removeItem('velmora-chat');
        this.conversationId = null;
        this.messages = [];
        this.status = 'waiting';
        window.clearInterval(this.poller);
        this.poller = null;
    },

    formatTime(value) {
        return value ? new Intl.DateTimeFormat(this.locale, { hour: '2-digit', minute: '2-digit' }).format(new Date(value)) : '';
    },
});

Alpine.start();
