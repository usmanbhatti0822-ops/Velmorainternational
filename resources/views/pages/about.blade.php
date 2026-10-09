@extends('layouts.app')

@section('title', __('site.about').' | Velmora International')
@section('description', __('site.about_body'))
@section('content')
<section class="bg-forest py-16 text-white md:py-24"><div class="container-wide"><p class="eyebrow text-gold">{{ __('site.about_kicker') }}</p><h1 class="mt-3 max-w-3xl font-display text-5xl leading-tight md:text-6xl">{{ __('site.about_title') }}</h1><p class="mt-6 max-w-3xl text-lg leading-8 text-sage">{{ __('site.about_body') }}</p></div></section>
<section class="section-space"><div class="container-wide grid gap-10 lg:grid-cols-2">
    <div><p class="eyebrow">{{ __('site.about_kicker') }}</p><h2 class="mt-3 font-display text-3xl text-forest">{{ __('site.what_we_do') }}</h2><p class="mt-4 leading-7 text-stone">{{ __('site.about_body') }}</p></div>
    <div class="rounded-2xl border border-gold/40 bg-white p-7"><div class="grid size-12 place-items-center rounded-xl bg-sage font-display text-xl text-forest">V</div><h2 class="mt-5 font-display text-2xl text-forest">{{ __('site.approved_content') }}</h2><p class="mt-3 leading-7 text-stone">{{ __('site.about_note') }}</p></div>
</div></section>
<section class="section-space bg-white"><div class="container-wide"><h2 class="font-display text-3xl text-forest">{{ __('site.divisions_title') }}</h2><p class="mt-3 max-w-2xl text-stone">{{ __('site.divisions_body') }}</p><a class="mt-6 inline-flex font-semibold text-forest underline decoration-gold underline-offset-4" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.view_all_products') }} →</a></div></section>
@endsection
