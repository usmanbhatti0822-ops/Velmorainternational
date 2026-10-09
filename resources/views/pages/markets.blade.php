@extends('layouts.app')

@section('title', __('site.markets').' | Velmora International')
@section('content')
<section class="bg-forest py-16 text-white md:py-24"><div class="container-wide"><p class="eyebrow text-gold">{{ __('site.markets') }}</p><h1 class="mt-3 font-display text-5xl">{{ __('site.markets_title') }}</h1><p class="mt-5 max-w-3xl text-lg leading-8 text-sage">{{ __('site.markets_body') }}</p></div></section>
<section class="section-space"><div class="container-wide grid gap-8 lg:grid-cols-[1fr_.8fr]">
    <div class="grid min-h-80 place-items-center rounded-[2rem] border border-mist bg-[radial-gradient(ellipse_at_50%_50%,#E3EEE7_0%,#FAF7F0_72%)] p-8 text-center"><div><div class="mx-auto grid size-14 place-items-center rounded-full bg-forest text-gold" aria-hidden="true">✦</div><p class="mt-5 max-w-lg font-display text-2xl text-forest">{{ __('site.markets_note') }}</p></div></div>
    <div class="self-center"><h2 class="font-display text-3xl text-forest">{{ __('site.destination_port') }}</h2><p class="mt-4 leading-7 text-stone">{{ __('site.markets_body') }}</p><a class="mt-6 inline-flex rounded-full bg-forest px-6 py-3 font-semibold text-white" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('site.contact') }}</a></div>
</div></section>
@endsection
