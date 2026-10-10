@extends('layouts.app')

@section('title', 'Velmora International | From sourcing to shipment')
@section('description', 'Explore Velmora International product divisions and request a tailored export quotation.')

@section('content')
<section class="hero-stage relative isolate overflow-hidden bg-[#102f24] text-white">
    <div class="absolute inset-0 -z-20 bg-[url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=2200&q=90')] bg-cover bg-center opacity-35" aria-hidden="true"></div>
    <div class="absolute inset-0 -z-10 bg-[linear-gradient(90deg,rgba(10,37,27,.98)_0%,rgba(12,53,38,.88)_43%,rgba(12,53,38,.22)_100%)]" aria-hidden="true"></div>
    <div class="premium-grain pointer-events-none absolute inset-0 -z-10 opacity-25" aria-hidden="true"></div>
    <div class="hero-glow pointer-events-none absolute -end-28 -top-40 size-[34rem] rounded-full bg-gold/10 blur-3xl" aria-hidden="true"></div>
    <div class="container-wide relative grid min-h-[660px] items-center gap-10 py-14 sm:py-20 lg:grid-cols-[1.1fr_.9fr] lg:gap-12 lg:py-24">
        <div class="reveal-up">
            <p class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/[.06] px-4 py-2 text-[.68rem] font-semibold uppercase tracking-[.2em] text-gold backdrop-blur-sm"><span class="hero-live-dot size-2 rounded-full bg-gold"></span>{{ __('site.hero_eyebrow') }}</p>
            <h1 class="hero-title mt-6 max-w-3xl font-display text-[clamp(3.2rem,7vw,6.2rem)] leading-[1.02] tracking-[-.035em]">{{ __('site.hero_title') }}</h1>
            <p class="mt-7 max-w-2xl text-base leading-8 text-sage md:text-lg">{{ __('site.hero_body') }}</p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a class="button-lift focus-ring rounded-full bg-gold px-7 py-3.5 font-semibold text-deep shadow-[0_12px_36px_rgba(200,160,74,.2)] transition hover:-translate-y-0.5 hover:bg-white" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }} <span aria-hidden="true">↗</span></a>
                <a class="button-lift focus-ring rounded-full border border-white/35 bg-white/5 px-7 py-3.5 font-semibold backdrop-blur-sm transition hover:bg-white/15" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.browse_divisions') }}</a>
            </div>
            <div class="mt-11 flex flex-wrap gap-x-7 gap-y-3 border-t border-white/15 pt-6 text-xs font-medium tracking-wide text-sage md:text-sm">
                <span class="hero-proof"><span class="me-2 text-gold">◆</span>{{ __('site.any_quantity') }}</span>
                <span class="hero-proof"><span class="me-2 text-gold">◆</span>{{ __('site.buyer_led_specs') }}</span>
                <span class="hero-proof"><span class="me-2 text-gold">◆</span>{{ __('site.direct_enquiries') }}</span>
            </div>
        </div>
        <div class="hero-art relative mx-auto flex min-h-[300px] w-full max-w-[27rem] items-end justify-center sm:min-h-[390px] lg:min-h-[450px] lg:max-w-none" aria-hidden="true">
            <div class="hero-orbit hero-orbit-one absolute end-1 top-1 size-[min(82vw,370px)] rounded-full border border-white/20"></div>
            <div class="hero-orbit hero-orbit-two absolute end-5 top-5 size-[min(68vw,310px)] rounded-full border border-gold/35"></div>
            <div class="hero-image-card relative mb-4 w-[min(100%,23rem)] rotate-[-2deg] overflow-hidden rounded-[1.7rem] border border-white/25 bg-white/10 p-2 shadow-[0_40px_100px_rgba(0,0,0,.4)] backdrop-blur-sm sm:mb-7 sm:w-[min(100%,25rem)]">
                <img class="hero-image h-[250px] w-full rounded-[1.3rem] object-cover sm:h-[320px] lg:h-[390px]" src="https://images.unsplash.com/photo-1494412519320-aa613dfb7738?auto=format&fit=crop&w=1100&q=90" alt="" fetchpriority="high">
                <div class="absolute inset-x-2 bottom-2 rounded-b-[1.3rem] bg-gradient-to-t from-[#0c3526]/95 via-[#0c3526]/70 to-transparent p-5 pt-16 sm:p-6 sm:pt-20">
                    <p class="text-[.68rem] font-semibold uppercase tracking-[.22em] text-gold">{{ __('site.trade_note') }}</p>
                    <p class="mt-2 font-display text-2xl">{{ __('site.trade_note_title') }}</p>
                </div>
            </div>
            <div class="hero-float reveal-up reveal-delay absolute -start-1 bottom-7 max-w-52 rounded-2xl border border-white/20 bg-[#123d2d]/90 p-4 shadow-xl backdrop-blur sm:-start-5 sm:bottom-16 sm:max-w-56">
                <p class="text-[.65rem] uppercase tracking-[.18em] text-gold">{{ __('site.divisions_kicker') }}</p>
                <p class="mt-2 font-display text-2xl">{{ $divisions->count() }} {{ app()->getLocale() === 'ar' ? 'فئات توريد' : 'sourcing categories' }}</p>
                <p class="mt-1 text-xs text-sage">{{ __('site.buyer_led_specs') }}</p>
            </div>
            <div class="hero-stamp absolute end-0 top-8 grid size-[5.5rem] place-items-center rounded-full border border-gold/55 bg-[#102f24]/75 text-center text-[.55rem] font-semibold uppercase leading-4 tracking-[.12em] text-gold shadow-xl backdrop-blur sm:end-2 sm:top-4 sm:size-[6.5rem]"><span>Velmora<br>International<br><span class="text-white/75">Trade</span></span></div>
        </div>
    </div>
</section>

<section class="relative border-y border-mist/80 bg-gradient-to-b from-white to-cream/70 py-7 md:py-9" aria-label="{{ __('site.buyer_benefits') }}">
    <div class="container-wide grid gap-3 md:grid-cols-3">
        @foreach ([
            ['title' => 'site.buyer_types', 'detail' => 'site.buyer_types_detail', 'number' => '01'],
            ['title' => 'site.flexible_supply', 'detail' => 'site.flexible_supply_detail', 'number' => '02'],
            ['title' => 'site.tailored_quotations', 'detail' => 'site.tailored_quotations_detail', 'number' => '03'],
        ] as $benefit)
            <article class="scroll-reveal group flex items-center gap-4 rounded-2xl border border-transparent bg-white/80 px-5 py-4 shadow-[0_8px_28px_rgba(20,80,58,.045)] transition duration-300 hover:-translate-y-1 hover:border-sage hover:bg-white hover:shadow-[0_16px_36px_rgba(20,80,58,.1)] md:px-6">
                <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-sage/70 font-display text-sm font-semibold tracking-widest text-forest transition group-hover:bg-forest group-hover:text-gold" aria-hidden="true">{{ $benefit['number'] }}</span>
                <span class="min-w-0">
                    <span class="block font-display text-lg font-semibold text-forest">{{ __($benefit['title']) }}</span>
                    <span class="mt-1 block text-sm leading-5 text-stone">{{ __($benefit['detail']) }}</span>
                </span>
            </article>
        @endforeach
    </div>
</section>

<section class="section-space">
    <div class="container-wide">
        <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div class="max-w-2xl"><p class="eyebrow">{{ __('site.divisions_kicker') }}</p><h2 class="mt-3 font-display text-4xl text-forest md:text-5xl">{{ __('site.divisions_title') }}</h2><p class="mt-4 leading-7 text-stone">{{ __('site.divisions_body') }}</p></div>
            <a class="font-semibold text-forest underline decoration-gold decoration-2 underline-offset-4" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.view_all_products') }} <span aria-hidden="true">→</span></a>
        </div>
        @if ($divisions->isNotEmpty())
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($divisions as $division)
                    <a href="{{ route('divisions.show', ['locale' => app()->getLocale(), 'division' => $division->slug]) }}" class="scroll-reveal group focus-ring overflow-hidden rounded-2xl border border-mist bg-white shadow-[0_8px_30px_rgba(20,80,58,.05)] transition hover:-translate-y-1 hover:shadow-[0_16px_36px_rgba(20,80,58,.12)]">
                        <div class="relative grid h-56 place-items-center overflow-hidden bg-gradient-to-br from-sage via-cream to-white">
                            @if ($division->cover_image)
                                <img class="absolute inset-0 size-full object-cover transition duration-700 group-hover:scale-110" src="{{ str_starts_with($division->cover_image, 'https://') ? $division->cover_image : \Illuminate\Support\Facades\Storage::disk('public')->url($division->cover_image) }}" alt="" loading="lazy">
                            @else
                                <span class="font-display text-6xl text-forest/20" aria-hidden="true">{{ mb_substr($division->localized('name'), 0, 1) }}</span>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0c3526]/55 via-transparent to-transparent"></div>
                            <span class="absolute bottom-4 start-4 rounded-full border border-white/40 bg-white/90 px-3 py-1.5 text-xs font-semibold text-forest backdrop-blur">{{ __('site.view_division') }}</span>
                        </div>
                        <div class="flex items-start justify-between gap-4 p-5 md:p-6">
                            <div><h3 class="font-display text-2xl text-forest">{{ $division->localized('name') }}</h3><p class="mt-2 line-clamp-2 text-sm leading-6 text-stone">{{ $division->localized('summary') }}</p></div>
                            <span class="mt-1 text-xl text-antique transition group-hover:translate-x-1" aria-hidden="true">↗</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="mt-8 rounded-2xl border border-mist bg-white p-8 text-stone">{{ __('site.content_preparing') }}</p>
        @endif
    </div>
</section>

<section class="section-space bg-white">
    <div class="container-wide grid items-center gap-12 lg:grid-cols-[.9fr_1.1fr]">
        <div class="scroll-reveal relative min-h-72 overflow-hidden rounded-[2rem] bg-[url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=85')] bg-cover bg-center p-8 shadow-[0_25px_70px_rgba(20,80,58,.14)] md:min-h-[430px]">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0c3526]/85 via-[#0c3526]/10 to-transparent"></div>
            <div class="absolute start-8 top-8 size-28 rounded-full border border-white/35"></div>
            <div class="absolute end-10 top-12 size-40 rounded-full border border-gold/70"></div>
            <div class="absolute bottom-8 start-8 max-w-sm rounded-2xl border border-white/30 bg-white/90 p-5 backdrop-blur">
                <p class="eyebrow">{{ __('site.process_kicker') }}</p><p class="mt-2 font-display text-2xl text-forest">{{ __('site.process_title') }}</p>
            </div>
        </div>
        <div class="scroll-reveal scroll-delay-1">
            <p class="eyebrow">{{ __('site.process_kicker') }}</p>
            <h2 class="mt-3 font-display text-4xl text-forest md:text-5xl">{{ __('site.process_title') }}</h2>
            <p class="mt-5 leading-7 text-stone">{{ __('site.process_body') }}</p>
            <ol class="mt-8 space-y-5">
                @foreach (['step_enquiry', 'step_confirmation', 'step_quote', 'step_shipment'] as $index => $step)
                    <li class="flex items-center gap-4"><span class="grid size-10 shrink-0 place-items-center rounded-full border border-gold bg-cream font-semibold text-forest">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span><span class="font-semibold">{{ __('site.'.$step) }}</span></li>
                @endforeach
            </ol>
            <a class="mt-8 inline-flex rounded-full bg-forest px-6 py-3 font-semibold text-white transition hover:bg-deep" href="{{ route('quality', ['locale' => app()->getLocale()]) }}">{{ __('site.how_we_work') }} <span class="ms-2" aria-hidden="true">→</span></a>
        </div>
    </div>
</section>

<section class="section-space">
    <div class="container-wide">
        <div class="max-w-2xl"><p class="eyebrow">{{ __('site.supply_kicker') }}</p><h2 class="mt-3 font-display text-4xl text-forest md:text-5xl">{{ __('site.packages_title') }}</h2><p class="mt-4 leading-7 text-stone">{{ __('site.packages_body') }}</p></div>
        <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages as $package)
                <article class="scroll-reveal rounded-2xl border border-mist bg-white p-6 transition hover:-translate-y-1 hover:border-gold/70 hover:shadow-[0_18px_40px_rgba(20,80,58,.12)]">
                    <div class="grid size-11 place-items-center rounded-xl bg-sage text-xl text-forest" aria-hidden="true">✦</div>
                    <h3 class="mt-5 font-display text-2xl text-forest">{{ $package->localized('name') }}</h3>
                    <p class="mt-2 text-sm leading-6 text-stone">{{ $package->localized('description') }}</p>
                    <p class="mt-4 text-xs font-medium text-antique">{{ $package->localized('min_qty_note') }}</p>
                </article>
            @endforeach
        </div>
        <div class="mt-8"><a class="font-semibold text-forest underline decoration-gold decoration-2 underline-offset-4" href="{{ route('packages', ['locale' => app()->getLocale()]) }}">{{ __('site.view_all_packages') }} →</a></div>
    </div>
</section>

@if ($products->isNotEmpty())
<section class="section-space bg-sage/60">
    <div class="container-wide">
        <div class="max-w-2xl"><p class="eyebrow">{{ __('site.featured_kicker') }}</p><h2 class="mt-3 font-display text-4xl text-forest">{{ __('site.featured_title') }}</h2><p class="mt-4 text-stone">{{ __('site.featured_body') }}</p></div>
        <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-space">
    <div class="scroll-reveal premium-grain relative isolate overflow-hidden rounded-[2rem] bg-forest px-7 py-12 text-white shadow-[0_30px_80px_rgba(20,80,58,.2)] md:px-14 md:py-16">
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_82%_5%,rgba(200,160,74,.25),transparent_28%),linear-gradient(115deg,#0C3526,#14503A)]"></div>
        <div class="grid items-center gap-8 md:grid-cols-[1fr_auto]">
            <div class="max-w-3xl"><p class="eyebrow text-gold">{{ __('site.next_step') }}</p><h2 class="mt-3 font-display text-4xl md:text-5xl">{{ __('site.cta_title') }}</h2><p class="mt-4 max-w-2xl leading-7 text-sage">{{ __('site.cta_body') }}</p></div>
            <a class="button-lift focus-ring inline-flex justify-center rounded-full bg-gold px-7 py-3.5 font-semibold text-deep transition hover:bg-antique hover:text-white" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }} <span class="ms-2" aria-hidden="true">↗</span></a>
        </div>
    </div>
</section>
@endsection
