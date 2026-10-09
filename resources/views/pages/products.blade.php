@extends('layouts.app')

@section('title', __('site.products').' | Velmora International')
@section('content')
<section class="bg-forest py-16 text-white md:py-24">
    <div class="container-wide">
        <p class="eyebrow text-gold">{{ __('site.divisions_kicker') }}</p>
        <h1 class="mt-3 font-display text-5xl">{{ __('site.products') }}</h1>
        <p class="mt-4 max-w-2xl leading-7 text-sage">{{ __('site.divisions_body') }}</p>
    </div>
</section>
<section class="section-space">
    <div class="container-wide">
        <form class="mb-8 grid gap-3 rounded-2xl border border-mist bg-white p-4 sm:grid-cols-[1fr_220px_auto]" method="get">
            <label class="sr-only" for="product-search">{{ __('site.search_products') }}</label>
            <input class="focus-ring rounded-xl border border-mist px-4 py-3" id="product-search" type="search" name="q" value="{{ $search }}" placeholder="{{ __('site.search_products') }}">
            <label class="sr-only" for="division-filter">{{ __('site.filter_division') }}</label>
            <select class="focus-ring rounded-xl border border-mist px-4 py-3" id="division-filter" name="division">
                <option value="">{{ __('site.all_divisions') }}</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->slug }}" @selected(request('division') === $division->slug)>{{ $division->localized('name') }}</option>
                @endforeach
            </select>
            <button class="focus-ring rounded-xl bg-forest px-6 py-3 font-semibold text-white hover:bg-deep" type="submit">{{ __('site.search') }}</button>
        </form>
        @if ($products->isNotEmpty())
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @else
            <div class="rounded-2xl border border-mist bg-white p-8 text-stone">{{ __('site.content_preparing') }}</div>
        @endif
    </div>
</section>
@endsection
