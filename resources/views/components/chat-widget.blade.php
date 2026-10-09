<div class="fixed bottom-5 end-5 z-50" x-data="chatWidget({productId: @yield('chat-product-id', 'null'), locale: '{{ app()->getLocale() }}', text: {emailRequired: @js(__('site.chat_email_required')), startError: @js(__('site.chat_start_error')), loadError: @js(__('site.chat_load_error')), uploadError: @js(__('site.chat_upload_error')), sendError: @js(__('site.chat_send_error')), offlineDefault: @js(__('site.chat_offline_default'))}})" @open-velmora-chat.window="open = true; productId = $event.detail?.productId || productId" @click.outside="open = false" @keydown.escape.window="open = false">
    <section x-cloak x-show="open" x-transition.opacity class="chat-panel-enter mb-4 flex h-[min(72vh,38rem)] w-[min(calc(100vw-2rem),24rem)] flex-col overflow-hidden rounded-[1.6rem] border border-mist/80 bg-white shadow-[0_28px_90px_rgba(12,53,38,.28)]" role="dialog" aria-modal="false" aria-label="{{ __('site.chat_title') }}">
        <header class="relative flex items-center justify-between overflow-hidden bg-forest px-5 py-5 text-white">
            <div class="premium-grain pointer-events-none absolute inset-0 opacity-30" aria-hidden="true"></div>
            <div class="relative flex items-center gap-3">
                <span class="relative grid size-11 place-items-center rounded-2xl border border-white/15 bg-white/10 text-lg text-gold">✦<span class="chat-dot-pulse absolute bottom-0 end-0 size-2.5 rounded-full border-2 border-forest bg-[#81cd97]"></span></span>
                <div><h2 class="font-display text-lg">{{ __('site.chat_title') }}</h2><p class="mt-1 flex items-center gap-2 text-xs text-sage" x-text="status === 'closed' ? '{{ __('site.chat_closed') }}' : '{{ __('site.chat_online') }}'"></p></div>
            </div>
            <button class="grid size-9 place-items-center rounded-full text-xl hover:bg-white/10" type="button" @click="open = false" aria-label="{{ __('site.close') }}">×</button>
        </header>
        <template x-if="!conversationId">
            <div class="flex-1 overflow-y-auto p-5">
                <p class="text-sm leading-6 text-stone">{{ __('site.chat_welcome') }}</p>
                <div x-show="error" x-text="error" role="alert" class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-800"></div>
                <label class="mt-5 block text-sm font-medium">{{ __('site.name') }}<input x-model="name" class="focus-ring mt-2 w-full rounded-lg border border-mist px-3 py-2.5" name="chat-name" autocomplete="name" required></label>
                <label class="mt-3 block text-sm font-medium">{{ __('site.email') }}<input x-model="email" class="focus-ring mt-2 w-full rounded-lg border border-mist px-3 py-2.5" type="email" name="chat-email" autocomplete="email" required></label>
                <label class="mt-3 block text-sm font-medium">{{ __('site.phone') }}<input x-model="phone" class="focus-ring mt-2 w-full rounded-lg border border-mist px-3 py-2.5" name="chat-phone" autocomplete="tel"></label>
                <label class="mt-3 block text-sm font-medium">{{ __('site.topic') }}<select x-model="topic" class="focus-ring mt-2 w-full rounded-lg border border-mist bg-white px-3 py-2.5"><option value="product">{{ __('site.chat_topic_product') }}</option><option value="pricing">{{ __('site.chat_topic_pricing') }}</option><option value="samples">{{ __('site.chat_topic_samples') }}</option><option value="private-label">{{ __('site.chat_topic_private_label') }}</option><option value="other">{{ __('site.chat_topic_other') }}</option></select></label>
                <label class="mt-3 block text-sm font-medium">{{ __('site.message') }}<textarea x-model="message" class="focus-ring mt-2 w-full rounded-lg border border-mist px-3 py-2.5" maxlength="2000" rows="3"></textarea></label>
                <p class="mt-4 text-xs leading-5 text-stone">{{ __('site.privacy_notice') }} <a class="underline" href="{{ route('legal.privacy',['locale'=>app()->getLocale()]) }}">{{ __('site.privacy') }}</a>.</p>
                <div class="mt-5 grid gap-2">
                    <button class="rounded-full bg-forest px-4 py-3 text-sm font-semibold text-white disabled:opacity-50" type="button" @click="start(false)" :disabled="busy">{{ __('site.start_chat') }}</button>
                    <button class="rounded-full border border-forest px-4 py-3 text-sm font-semibold text-forest disabled:opacity-50" type="button" @click="start(true)" :disabled="busy">{{ __('site.leave_message') }}</button>
                </div>
            </div>
        </template>
        <template x-if="conversationId">
            <div class="flex min-h-0 flex-1 flex-col">
                <div class="flex-1 space-y-3 overflow-y-auto p-4" x-ref="messageList" aria-live="polite" aria-relevant="additions">
                    <template x-for="message in messages" :key="message.id">
                        <div class="max-w-[88%] rounded-2xl px-3.5 py-2.5 text-sm leading-6" :class="message.sender === 'visitor' ? 'ms-auto bg-forest text-white' : 'me-auto bg-sage text-charcoal'">
                            <p class="whitespace-pre-wrap break-words" x-text="message.body"></p>
                            <template x-for="attachment in message.attachments || []" :key="attachment.url"><a class="mt-2 block underline" :href="attachment.url" x-text="attachment.name" target="_blank" rel="noopener"></a></template>
                            <time class="mt-1 block text-[.65rem] opacity-70" x-text="formatTime(message.created_at)"></time>
                        </div>
                    </template>
                </div>
                <div x-show="error" x-text="error" role="alert" class="mx-4 rounded-lg bg-red-50 p-2 text-xs text-red-800"></div>
                <form class="border-t border-mist p-3" @submit.prevent="send()">
                    <div class="flex items-end gap-2">
                        <label class="sr-only" for="chat-message-input">{{ __('site.chat_input') }}</label>
                        <textarea id="chat-message-input" x-model="message" class="focus-ring min-h-11 max-h-28 flex-1 resize-y rounded-xl border border-mist px-3 py-2 text-sm" rows="1" maxlength="2000" :placeholder="'{{ __('site.chat_input') }}'" :disabled="status === 'closed'"></textarea>
                        <button class="grid size-11 shrink-0 place-items-center rounded-xl bg-forest text-white disabled:opacity-50" type="submit" :disabled="busy || !message.trim() || status === 'closed'" aria-label="{{ __('site.chat_send') }}">↑</button>
                    </div>
                    <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-xs text-stone"><span aria-hidden="true">＋</span>{{ __('site.attach_file') }}<input class="sr-only" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.docx,.xlsx" @change="attach($event)"></label>
                </form>
            </div>
        </template>
    </section>
    <button class="chat-launcher-pulse focus-ring flex items-center gap-2.5 rounded-full bg-forest px-5 py-3.5 font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-deep" type="button" @click="open = !open" :aria-expanded="open.toString()" aria-label="{{ __('site.chat_title') }}">
        <span class="grid size-7 place-items-center rounded-full bg-white/10 text-gold" aria-hidden="true">✦</span><span>{{ __('site.chat_title') }}</span><span class="ms-1 text-lg leading-none transition-transform" :class="open ? 'rotate-45' : ''" aria-hidden="true">+</span>
    </button>
</div>
