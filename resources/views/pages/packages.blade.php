@extends('layouts.app')

@section('title', __('site.packages').' | Velmora International')
@section('content')
<section class="relative isolate overflow-hidden bg-forest py-16 text-white md:py-24">
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_85%_10%,rgba(200,160,74,.22),transparent_34%),linear-gradient(115deg,#0C3526_15%,#14503A_75%,#1d6348)]"></div>
    <div class="container-wide"><p class="eyebrow text-gold">{{ __('site.supply_kicker') }}</p><h1 class="mt-3 font-display text-5xl md:text-6xl">{{ __('site.packages_title') }}</h1><p class="mt-4 max-w-2xl leading-7 text-sage">{{ __('site.packages_body') }}</p><p class="mt-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-sm text-sage"><span class="text-gold" aria-hidden="true">✦</span>{{ __('site.package_details_hint') }}</p></div>
</section>
<section class="section-space"><div class="container-wide">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($packages as $package)
            <article class="group flex flex-col rounded-2xl border border-mist bg-white p-6 shadow-[0_8px_30px_rgba(20,80,58,.05)] transition duration-300 hover:-translate-y-1 hover:border-sage hover:shadow-[0_18px_40px_rgba(20,80,58,.1)] md:p-7">
                <div class="grid size-12 place-items-center rounded-2xl bg-sage text-xl text-forest transition group-hover:bg-forest group-hover:text-gold" aria-hidden="true">✦</div>
                <h2 class="mt-5 font-display text-2xl text-forest">{{ $package->localized('name') }}</h2>
                <p class="mt-3 leading-7 text-stone">{{ $package->localized('description') }}</p>
                <p class="mt-4 text-sm font-semibold leading-6 text-antique">{{ $package->localized('audience') }}</p>
                <p class="mt-5 border-t border-mist pt-4 text-xs leading-5 text-stone">{{ $package->localized('min_qty_note') }}</p>
                <dialog class="m-auto max-h-[90dvh] w-[calc(100%-2rem)] max-w-xl overflow-y-auto rounded-3xl border border-mist bg-white p-0 text-charcoal shadow-2xl backdrop:bg-deep/60 backdrop:blur-sm" aria-labelledby="package-dialog-title-{{ $package->id }}">
                    <div class="relative isolate overflow-hidden bg-forest px-6 py-7 text-white md:px-8 md:py-8">
                        <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_85%_0%,rgba(200,160,74,.28),transparent_42%),linear-gradient(120deg,#0C3526,#14503A)]"></div>
                        <div class="flex items-start justify-between gap-5">
                            <div>
                                <p class="eyebrow text-gold">{{ __('site.package_dialog_eyebrow') }}</p>
                                <h2 id="package-dialog-title-{{ $package->id }}" class="mt-3 font-display text-3xl md:text-4xl">{{ $package->localized('name') }}</h2>
                            </div>
                            <form method="dialog">
                                <button class="focus-ring grid size-10 shrink-0 place-items-center rounded-full border border-white/25 bg-white/10 text-xl text-white transition hover:bg-white/20" value="close" aria-label="{{ __('site.close') }}">×</button>
                            </form>
                        </div>
                        <p class="mt-4 max-w-lg leading-7 text-sage">{{ $package->localized('description') }}</p>
                    </div>
                    <div class="p-6 md:p-8">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <section class="rounded-2xl border border-mist bg-cream/70 p-5">
                                <span class="grid size-9 place-items-center rounded-xl bg-white text-forest shadow-sm" aria-hidden="true">◎</span>
                                <h3 class="mt-4 text-xs font-bold uppercase tracking-wider text-forest">{{ __('site.package_audience_title') }}</h3>
                                <p class="mt-2 text-sm leading-6 text-stone">{{ $package->localized('audience') }}</p>
                            </section>
                            <section class="rounded-2xl border border-mist bg-cream/70 p-5">
                                <span class="grid size-9 place-items-center rounded-xl bg-white text-forest shadow-sm" aria-hidden="true">↗</span>
                                <h3 class="mt-4 text-xs font-bold uppercase tracking-wider text-forest">{{ __('site.package_quantity_title') }}</h3>
                                <p class="mt-2 text-sm leading-6 text-stone">{{ $package->localized('min_qty_note') }}</p>
                            </section>
                        </div>
                        <h3 class="mt-7 font-display text-xl text-forest">{{ __('site.package_confirm_title') }}</h3>
                        <ul class="mt-4 space-y-3">
                            <li class="flex items-start gap-3 rounded-xl border border-mist/80 p-3 text-sm leading-6 text-stone"><span class="grid size-6 shrink-0 place-items-center rounded-full bg-sage text-xs font-bold text-forest" aria-hidden="true">1</span>{{ __('site.package_confirm_product') }}</li>
                            <li class="flex items-start gap-3 rounded-xl border border-mist/80 p-3 text-sm leading-6 text-stone"><span class="grid size-6 shrink-0 place-items-center rounded-full bg-sage text-xs font-bold text-forest" aria-hidden="true">2</span>{{ __('site.package_confirm_format') }}</li>
                            <li class="flex items-start gap-3 rounded-xl border border-mist/80 p-3 text-sm leading-6 text-stone"><span class="grid size-6 shrink-0 place-items-center rounded-full bg-sage text-xs font-bold text-forest" aria-hidden="true">3</span>{{ __('site.package_confirm_delivery') }}</li>
                        </ul>
                        <div class="mt-7 flex flex-col-reverse gap-3 border-t border-mist pt-5 sm:flex-row sm:justify-between">
                            <form method="dialog">
                                <button class="focus-ring inline-flex w-full items-center justify-center gap-2 rounded-full border border-mist px-5 py-3 text-sm font-semibold text-forest transition hover:border-forest hover:bg-cream sm:w-auto" value="back"><span class="rtl:rotate-180" aria-hidden="true">←</span>{{ __('site.package_dialog_back') }}</button>
                            </form>
                            <a class="focus-ring inline-flex w-full items-center justify-center gap-2 rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-deep sm:w-auto" href="{{ route('inquiries.create', ['locale' => app()->getLocale(), 'package' => $package->slug]) }}">{{ __('site.package_request_quote') }} <span aria-hidden="true">↗</span></a>
                        </div>
                    </div>
                </dialog>
                <button class="focus-ring mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border border-forest/20 px-5 py-3 text-sm font-semibold text-forest transition hover:border-forest hover:bg-sage/60" type="button" aria-haspopup="dialog" onclick="this.previousElementSibling.showModal()">
                    {{ __('site.package_show_details') }} <span aria-hidden="true">↗</span>
                </button>
            </article>
        @endforeach
    </div>
    <div class="mt-10 rounded-2xl bg-sage p-7 md:p-10"><h2 class="font-display text-3xl text-forest">{{ __('site.ordering_title') }}</h2><p class="mt-3 max-w-3xl leading-7 text-stone">{{ __('site.ordering_body') }}</p><p class="mt-4 text-sm text-stone">{{ __('site.shipping_terms_note') }}</p><a class="mt-6 inline-flex rounded-full bg-forest px-6 py-3 font-semibold text-white" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }}</a></div>
</div></section>
@endsection
