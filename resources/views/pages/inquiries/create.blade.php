@extends('layouts.app')

@section('title', __('site.request_quote').' | Velmora International')
@section('content')
<section class="relative isolate overflow-hidden bg-forest py-14 text-white md:py-16"><div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_88%_5%,rgba(200,160,74,.22),transparent_28%),linear-gradient(110deg,#0C3526,#14503A)]"></div><div class="premium-grain absolute inset-0 -z-10 opacity-25"></div><div class="container-wide"><p class="eyebrow text-gold">{{ __('site.start_a_request') }}</p><h1 class="mt-3 font-display text-5xl md:text-6xl">{{ __('site.request_quote') }}</h1><p class="mt-4 max-w-2xl leading-7 text-sage">{{ __('site.rfq_intro') }}</p></div></section>
<section class="section-space bg-[#f7f8f5]"><div class="container-wide grid max-w-6xl items-start gap-7 lg:grid-cols-[minmax(0,1fr)_280px]">
    @if (session('reference'))
        <div role="status" class="mb-8 rounded-2xl border border-green-300 bg-green-50 p-6 text-green-900">{{ __('site.success_reference', ['reference' => session('reference')]) }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-xl border border-red-300 bg-red-50 p-5 text-red-900"><p class="font-semibold">{{ __('site.form_errors') }}</p><ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="post" action="{{ route('inquiries.store', ['locale' => app()->getLocale()]) }}" enctype="multipart/form-data" class="relative space-y-8 rounded-[1.7rem] border border-[#e7ebe6] bg-white p-6 shadow-[0_20px_65px_rgba(20,50,38,.07)] md:p-9" x-data="{items:[{product_id:@js($prefill),product_name_text:'',quantity:'',unit:'mt',supply_package_id:@js((string) ($packagePrefill ?? ''))}]}">
        @csrf
        <div class="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true"><label>{{ __('site.honeypot') }} <input name="website" tabindex="-1" autocomplete="off"></label></div>
        <fieldset><legend class="font-display text-2xl text-forest">{{ __('site.your_details') }}</legend>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <label class="text-sm font-medium">{{ __('site.name') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="name" value="{{ old('name') }}" required autocomplete="name"></label>
                <label class="text-sm font-medium">{{ __('site.company_name') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="company" value="{{ old('company') }}" autocomplete="organization"></label>
                <label class="text-sm font-medium">{{ __('site.designation') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="designation" value="{{ old('designation') }}"></label>
                <label class="text-sm font-medium">{{ __('site.email') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
                <label class="text-sm font-medium">{{ __('site.phone') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="phone" value="{{ old('phone') }}" autocomplete="tel"></label>
                <label class="text-sm font-medium">{{ __('site.whatsapp') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="whatsapp" value="{{ old('whatsapp') }}"></label>
                <label class="text-sm font-medium">{{ __('site.country') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="country_code" value="{{ old('country_code') }}" maxlength="2" placeholder="AE" autocomplete="country"></label>
                <label class="text-sm font-medium">{{ __('site.destination_port') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="destination_port" value="{{ old('destination_port') }}"></label>
                <label class="text-sm font-medium sm:col-span-2">{{ __('site.delivery_timeline') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="delivery_timeline" value="{{ old('delivery_timeline') }}"></label>
            </div>
        </fieldset>
        <fieldset><legend class="font-display text-2xl text-forest">{{ __('site.product_requirements') }}</legend>
            <div class="mt-4 space-y-4">
                <template x-for="(item,index) in items" :key="index">
                    <div class="grid gap-3 rounded-2xl bg-cream p-4 sm:grid-cols-2 lg:grid-cols-4">
                        <label class="text-sm font-medium sm:col-span-2 lg:col-span-2">{{ __('site.choose_product') }}
                            <select class="focus-ring mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" :name="'items['+index+'][product_id]'" x-model="item.product_id"><option value="">{{ __('site.choose_product') }}</option>@foreach ($products as $product)<option value="{{ $product->id }}">{{ $product->localized('name') }}</option>@endforeach</select>
                        </label>
                        <label class="text-sm font-medium sm:col-span-2 lg:col-span-2">{{ __('site.product_name_optional') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" :name="'items['+index+'][product_name_text]'" x-model="item.product_name_text" maxlength="255"></label>
                        <label class="text-sm font-medium">{{ __('site.quantity') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" type="number" min="0.001" step="any" :name="'items['+index+'][quantity]'" x-model="item.quantity"></label>
                        <label class="text-sm font-medium">{{ __('site.unit') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" :name="'items['+index+'][unit]'" x-model="item.unit" maxlength="32" placeholder="MT"></label>
                        <label class="text-sm font-medium sm:col-span-2">{{ __('site.package') }}<select class="focus-ring mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" :name="'items['+index+'][supply_package_id]'" x-model="item.supply_package_id"><option value="">{{ __('site.choose_package') }}</option>@foreach (\App\Models\SupplyPackage::query()->where('is_active',true)->orderBy('sort_order')->get() as $package)<option value="{{ $package->id }}">{{ $package->localized('name') }}</option>@endforeach</select></label>
                        <label class="text-sm font-medium sm:col-span-2 lg:col-span-4">{{ __('site.item_notes') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" :name="'items['+index+'][notes]'" maxlength="1000"></label>
                        <button class="text-start text-xs font-semibold text-red-700 underline sm:col-span-2 lg:col-span-4" type="button" x-show="items.length > 1" @click="items.splice(index,1)">{{ __('site.remove_product') }}</button>
                    </div>
                </template>
                <button class="rounded-full border border-forest px-5 py-2.5 text-sm font-semibold text-forest hover:bg-sage" type="button" :disabled="items.length >= 12" @click="items.push({product_id:'',product_name_text:'',quantity:'',unit:'mt',supply_package_id:''})">{{ __('site.add_product') }} +</button>
            </div>
        </fieldset>
        <label class="block text-sm font-medium">{{ __('site.message') }}<textarea class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="message" rows="5" maxlength="5000">{{ old('message') }}</textarea></label>
        <label class="block text-sm font-medium">{{ __('site.attachments') }}<input class="focus-ring mt-2 block w-full rounded-xl border border-mist bg-cream p-3" type="file" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp"></label>
        <label class="flex items-start gap-3 text-sm text-stone"><input class="mt-1 accent-forest" type="checkbox" name="marketing_opt_in" value="1" @checked(old('marketing_opt_in'))><span>{{ __('site.marketing_opt_in') }}</span></label>
        <p class="text-xs text-stone">{{ __('site.privacy_notice') }} <a class="underline" href="{{ route('legal.privacy', ['locale'=>app()->getLocale()]) }}">{{ __('site.privacy') }}</a>.</p>
        <button class="focus-ring rounded-full bg-forest px-8 py-3.5 font-semibold text-white shadow-[0_10px_24px_rgba(20,80,58,.18)] transition hover:-translate-y-0.5 hover:bg-deep" type="submit">{{ __('site.send_request') }} <span aria-hidden="true">→</span></button>
    </form>
    <aside class="space-y-5 lg:sticky lg:top-28">
        <div class="overflow-hidden rounded-[1.6rem] bg-[#102f24] p-6 text-white shadow-[0_18px_50px_rgba(12,53,38,.18)] md:p-7">
            <span class="grid size-12 place-items-center rounded-2xl border border-gold/40 bg-white/5 font-display text-xl text-gold">V</span>
            <p class="mt-6 text-[.68rem] font-semibold uppercase tracking-[.2em] text-gold">{{ __('site.trade_note') }}</p>
            <h2 class="mt-2 font-display text-2xl">{{ __('site.trade_note_title') }}</h2>
            <p class="mt-3 text-sm leading-6 text-sage">{{ __('site.rfq_intro') }}</p>
            <div class="mt-6 space-y-4 border-t border-white/15 pt-5 text-sm">
                <p class="flex gap-3"><span class="text-gold">01</span><span>{{ __('site.step_enquiry') }}</span></p>
                <p class="flex gap-3"><span class="text-gold">02</span><span>{{ __('site.step_confirmation') }}</span></p>
                <p class="flex gap-3"><span class="text-gold">03</span><span>{{ __('site.step_quote') }}</span></p>
            </div>
        </div>
        <div class="rounded-[1.4rem] border border-[#e7ebe6] bg-white p-5">
            <p class="text-xs font-semibold uppercase tracking-[.14em] text-antique">{{ __('site.product_requirements') }}</p>
            <p class="mt-2 text-sm leading-6 text-stone">{{ __('site.buyer_led_specs') }}</p>
        </div>
    </aside>
</div></section>
@endsection
