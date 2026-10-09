@extends('layouts.app')

@section('title', __('site.catalog_title').' | Velmora International')
@section('content')
<section class="bg-forest py-16 text-white md:py-24"><div class="container-wide"><p class="eyebrow text-gold">{{ __('site.catalog') }}</p><h1 class="mt-3 font-display text-5xl">{{ __('site.catalog_title') }}</h1><p class="mt-5 max-w-3xl leading-7 text-sage">{{ __('site.catalog_body') }}</p></div></section>
<section class="section-space"><div class="container-wide">
    @if ($catalogs->isNotEmpty())
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($catalogs as $catalog)
                <article class="rounded-2xl border border-mist bg-white p-6">
                    <div class="grid size-12 place-items-center rounded-xl bg-sage text-xl text-forest" aria-hidden="true">▤</div>
                    <h2 class="mt-4 font-display text-2xl text-forest">{{ $catalog->localized('title') }}</h2>
                    @if ($catalog->division)<p class="mt-2 text-sm text-stone">{{ $catalog->division->localized('name') }}</p>@endif
                    <form class="mt-5 space-y-3" method="get" action="{{ route('catalog.download', ['locale'=>app()->getLocale(), 'catalog'=>$catalog]) }}">
                        @if ($catalog->requires_email)
                            <label class="block text-sm font-medium">{{ __('site.download_email') }}<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-3 py-2.5" type="email" name="email" required></label>
                        @endif
                        <button class="rounded-full bg-forest px-5 py-2.5 text-sm font-semibold text-white">{{ __('site.download') }} ↓</button>
                    </form>
                </article>
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-mist bg-white p-8"><p class="text-stone">{{ __('site.catalog_empty') }}</p><div class="mt-5 flex flex-wrap gap-3">@foreach ($divisions as $division)<a class="rounded-full bg-sage px-4 py-2 text-sm font-medium text-forest" href="{{ route('divisions.show', ['locale'=>app()->getLocale(), 'division'=>$division->slug]) }}">{{ $division->localized('name') }}</a>@endforeach</div></div>
    @endif
</div></section>
@endsection
