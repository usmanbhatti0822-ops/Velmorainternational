@extends('layouts.app')

@section('title', $division->localized('seo_title') ?: $division->localized('name').' | Velmora International')
@section('description', $division->localized('seo_description') ?: $division->localized('summary'))
@section('content')
<section class="bg-forest py-16 text-white md:py-24">
    <div class="container-wide">
        <a class="text-sm text-sage underline underline-offset-4" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.all_products') }}</a>
        <h1 class="mt-5 font-display text-5xl">{{ $division->localized('name') }}</h1>
        <p class="mt-4 max-w-2xl leading-7 text-sage">{{ $division->localized('description') }}</p>
        <a class="mt-7 inline-flex rounded-full bg-gold px-6 py-3 font-semibold text-deep" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }}</a>
    </div>
</section>
<section class="section-space">
    <div class="container-wide">
        @if ($products->isNotEmpty())
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @else
            <div class="rounded-2xl border border-mist bg-white p-8">
                <h2 class="font-display text-2xl text-forest">{{ __('site.products_coming') }}</h2>
                <p class="mt-3 text-stone">{{ __('site.content_preparing') }}</p>
                <a class="mt-5 inline-flex font-semibold text-forest underline decoration-gold underline-offset-4" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.ask_availability') }} →</a>
            </div>
        @endif
    </div>
</section>
@endsection
