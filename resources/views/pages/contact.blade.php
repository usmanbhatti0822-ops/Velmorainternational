@extends('layouts.app')

@section('title', __('site.contact_title').' | Velmora International')
@section('content')
<section class="bg-forest py-16 text-white md:py-24"><div class="container-wide"><p class="eyebrow text-gold">{{ __('site.contact') }}</p><h1 class="mt-3 font-display text-5xl">{{ __('site.contact_title') }}</h1><p class="mt-5 max-w-3xl leading-7 text-sage">{{ __('site.contact_body') }}</p></div></section>
<section class="section-space"><div class="container-wide grid gap-10 lg:grid-cols-[.8fr_1.2fr]">
    <aside class="rounded-2xl bg-sage p-7"><h2 class="font-display text-2xl text-forest">{{ __('site.contact_details') }}</h2><p class="mt-4 leading-7 text-stone">{{ __('site.contact_details_pending') }}</p><a class="mt-6 inline-flex font-semibold text-forest underline decoration-gold underline-offset-4" href="{{ route('inquiries.create', ['locale'=>app()->getLocale()]) }}">{{ __('site.request_quote') }} →</a></aside>
    <div>
        @if (session('sent'))<div role="status" class="mb-5 rounded-xl border border-green-300 bg-green-50 p-4 text-green-900">{{ __('site.contact_sent') }}</div>@endif
        @if ($errors->any())<div role="alert" class="mb-5 rounded-xl border border-red-300 bg-red-50 p-4 text-red-900">{{ __('site.form_errors') }} {{ $errors->first() }}</div>@endif
        <form method="post" action="{{ route('contact.store', ['locale'=>app()->getLocale()]) }}" class="space-y-4 rounded-2xl border border-mist bg-white p-6 md:p-8">
            @csrf
            <div class="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true"><label>{{ __('site.honeypot') }} <input name="website" tabindex="-1" autocomplete="off"></label></div>
            <label class="block text-sm font-medium">{{ __('site.name') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label class="block text-sm font-medium">{{ __('site.email') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
            <label class="block text-sm font-medium">{{ __('site.phone') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="phone" value="{{ old('phone') }}"></label>
            <label class="block text-sm font-medium">{{ __('site.subject') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="subject" value="{{ old('subject') }}"></label>
            <label class="block text-sm font-medium">{{ __('site.message') }}<textarea class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" name="message" rows="5" maxlength="5000" required>{{ old('message') }}</textarea></label>
            <p class="text-xs text-stone">{{ __('site.privacy_notice') }} <a class="underline" href="{{ route('legal.privacy', ['locale'=>app()->getLocale()]) }}">{{ __('site.privacy') }}</a>.</p>
            <button class="rounded-full bg-forest px-7 py-3 font-semibold text-white hover:bg-deep" type="submit">{{ __('site.send_message') }}</button>
        </form>
    </div>
</div></section>
@endsection
