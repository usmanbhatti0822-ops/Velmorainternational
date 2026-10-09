@extends('layouts.app')

@section('title', __('site.quality').' | Velmora International')
@section('content')
<section class="bg-forest py-16 text-white md:py-24"><div class="container-wide"><p class="eyebrow text-gold">{{ __('site.process_kicker') }}</p><h1 class="mt-3 font-display text-5xl">{{ __('site.quality_title') }}</h1><p class="mt-5 max-w-3xl text-lg leading-8 text-sage">{{ __('site.quality_body') }}</p></div></section>
<section class="section-space"><div class="container-wide">
    <ol class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach (explode('|', __('site.quality_steps')) as $index => $step)
            <li class="rounded-2xl border border-mist bg-white p-6"><span class="font-display text-4xl text-gold">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span><h2 class="mt-4 font-display text-xl text-forest">{{ $step }}</h2></li>
        @endforeach
    </ol>
    <p class="mt-8 max-w-3xl text-sm leading-6 text-stone">{{ __('site.shipping_terms_note') }}</p>
</div></section>
@endsection
