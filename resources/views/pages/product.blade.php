@extends('layouts.app')

@section('title', $product->localized('seo_title') ?: $product->localized('name').' | Velmora International')
@section('description', $product->localized('seo_description') ?: $product->localized('short_description'))
@section('chat-product-id', $product->id)
@section('content')
<section class="border-b border-mist bg-white py-6">
    <div class="container-wide text-sm text-stone">
        <a class="hover:text-forest" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.products') }}</a>
        <span class="mx-2">/</span><a class="hover:text-forest" href="{{ route('divisions.show', ['locale' => app()->getLocale(), 'division' => $product->division->slug]) }}">{{ $product->division->localized('name') }}</a>
        <span class="mx-2">/</span><span class="text-charcoal">{{ $product->localized('name') }}</span>
    </div>
</section>
<section class="section-space">
    <div class="container-wide grid gap-12 lg:grid-cols-[1fr_1fr]">
        <div>
            <div class="grid min-h-[350px] place-items-center overflow-hidden rounded-[2rem] bg-gradient-to-br from-sage to-cream">
                @if ($product->images->isNotEmpty())
                    @php($productImagePath = $product->images->first()->path)
                    <img class="size-full max-h-[540px] object-cover" src="{{ str_starts_with($productImagePath, 'https://') ? $productImagePath : \Illuminate\Support\Facades\Storage::disk('public')->url($productImagePath) }}" alt="{{ $product->images->first()->localized('alt') }}">
                @else
                    <span class="font-display text-8xl text-forest/20" aria-hidden="true">{{ mb_substr($product->localized('name'), 0, 1) }}</span>
                @endif
            </div>
            @if ($product->images->count() > 1)
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach ($product->images->skip(1) as $image)
                        <img class="aspect-square rounded-xl object-cover" src="{{ str_starts_with($image->path, 'https://') ? $image->path : \Illuminate\Support\Facades\Storage::disk('public')->url($image->path) }}" alt="{{ $image->localized('alt') }}" loading="lazy">
                    @endforeach
                </div>
            @endif
        </div>
        <div>
            <p class="eyebrow">{{ $product->division->localized('name') }}</p>
            <h1 class="mt-3 font-display text-4xl text-forest md:text-5xl">{{ $product->localized('name') }}</h1>
            <p class="mt-5 text-lg leading-8 text-stone">{{ $product->localized('short_description') }}</p>
            @if ($product->localized('description'))
                <div class="prose mt-5 max-w-none leading-7 text-charcoal">{{ $product->localized('description') }}</div>
            @endif
            <dl class="mt-7 grid gap-4 rounded-2xl border border-mist bg-white p-5 sm:grid-cols-2">
                @if ($product->origin)
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-stone">{{ __('site.origin') }}</dt><dd class="mt-1 font-medium">{{ $product->origin }}</dd></div>
                @endif
                @if ($product->moq_value)
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-stone">{{ __('site.minimum_quantity') }}</dt><dd class="mt-1 font-medium">{{ $product->moq_value }} {{ $product->moq_unit }}</dd></div>
                @endif
                @if ($product->lead_time_days)
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-stone">{{ __('site.lead_time') }}</dt><dd class="mt-1 font-medium">{{ $product->lead_time_days }} {{ __('site.days') }}</dd></div>
                @endif
                @if (! $product->origin && ! $product->moq_value && ! $product->lead_time_days)
                    <div class="sm:col-span-2 text-sm text-stone">{{ __('site.ask_product_details') }}</div>
                @endif
            </dl>
            <div class="mt-7 flex flex-wrap gap-3">
                <a class="focus-ring rounded-full bg-forest px-6 py-3 font-semibold text-white hover:bg-deep" href="{{ route('inquiries.create', ['locale' => app()->getLocale(), 'product' => $product->id]) }}">{{ __('site.request_quote') }}</a>
                <button class="focus-ring rounded-full border border-forest px-6 py-3 font-semibold text-forest hover:bg-sage" type="button" @click="$dispatch('open-velmora-chat', {productId: {{ $product->id }}})">{{ __('site.chat_about_product') }}</button>
            </div>
        </div>
    </div>
</section>

@if ($product->specs->isNotEmpty() || $product->packagingOptions->isNotEmpty() || $product->supplyPackages->isNotEmpty())
<section class="section-space bg-white">
    <div class="container-wide grid gap-10 lg:grid-cols-2">
        @if ($product->specs->isNotEmpty())
            <div><h2 class="font-display text-3xl text-forest">{{ __('site.specifications') }}</h2><dl class="mt-5 divide-y divide-mist overflow-hidden rounded-2xl border border-mist">
                @foreach ($product->specs as $spec)
                    <div class="grid grid-cols-2 gap-4 bg-white p-4 even:bg-cream"><dt class="font-medium text-stone">{{ $spec->localized('spec_key') }}</dt><dd class="text-end font-semibold">{{ $spec->localized('spec_value') }}</dd></div>
                @endforeach
            </dl></div>
        @endif
        <div class="space-y-8">
            @if ($product->packagingOptions->isNotEmpty())
                <div><h2 class="font-display text-3xl text-forest">{{ __('site.packaging') }}</h2><ul class="mt-4 flex flex-wrap gap-2">@foreach ($product->packagingOptions as $option)<li class="rounded-full bg-sage px-4 py-2 text-sm text-forest">{{ $option->localized('name') }}</li>@endforeach</ul></div>
            @endif
            @if ($product->supplyPackages->isNotEmpty())
                <div><h2 class="font-display text-3xl text-forest">{{ __('site.available_supply') }}</h2><ul class="mt-4 flex flex-wrap gap-2">@foreach ($product->supplyPackages as $package)<li class="rounded-full border border-mist px-4 py-2 text-sm">{{ $package->localized('name') }}</li>@endforeach</ul></div>
            @endif
        </div>
    </div>
</section>
@endif
@if ($relatedProducts->isNotEmpty())
<section class="section-space"><div class="container-wide"><h2 class="font-display text-3xl text-forest">{{ __('site.related_products') }}</h2><div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">@foreach ($relatedProducts as $relatedProduct)@include('components.product-card', ['product' => $relatedProduct])@endforeach</div></div></section>
@endif
@endsection
