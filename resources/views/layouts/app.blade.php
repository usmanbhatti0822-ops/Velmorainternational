<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Velmora International')</title>
    <meta name="description" content="@yield('description', 'Explore Velmora International product divisions, export supply options and request a tailored quotation.')">
    <meta name="robots" content="@yield('robots', 'index,follow')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Velmora International')">
    <meta property="og:description" content="@yield('description', 'Explore our product divisions and request a tailored export quotation.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @if (request()->route())
        @php
            $languageParameters = array_merge(request()->route()->parameters(), ['locale' => 'en']);
            $arabicParameters = array_merge(request()->route()->parameters(), ['locale' => 'ar']);
        @endphp
        <link rel="alternate" hreflang="en" href="{{ route(request()->route()->getName(), $languageParameters) }}">
        <link rel="alternate" hreflang="ar" href="{{ route(request()->route()->getName(), $arabicParameters) }}">
        <link rel="alternate" hreflang="x-default" href="{{ route(request()->route()->getName(), $languageParameters) }}">
    @endif
    <meta name="theme-color" content="#14503A">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-cream font-sans text-charcoal antialiased">
    <a class="focus-ring sr-only z-50 rounded bg-white p-3 focus:not-sr-only focus:fixed focus:start-4 focus:top-4" href="#main-content">{{ __('site.skip') }}</a>
    <header x-data="{mobileMenuOpen:false}" class="sticky top-0 z-40 border-b border-mist/80 bg-cream/95 backdrop-blur">
        <div class="container-wide flex min-h-20 items-center justify-between gap-5">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="flex items-center gap-3" aria-label="Velmora International home">
                <span class="grid size-11 place-items-center rounded-xl bg-forest font-display text-xl font-bold text-gold">V</span>
                <span class="leading-tight"><span class="block font-display text-lg font-semibold tracking-[.12em] text-forest">VELMORA</span><span class="text-[.65rem] font-medium uppercase tracking-[.19em] text-stone">International</span></span>
            </a>
            <nav class="hidden items-center gap-7 text-sm font-medium text-charcoal lg:flex" aria-label="{{ __('site.primary_navigation') }}">
                <a class="transition hover:text-antique" href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('site.home') }}</a>
                <a class="transition hover:text-antique" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.products') }}</a>
                <a class="transition hover:text-antique" href="{{ route('packages', ['locale' => app()->getLocale()]) }}">{{ __('site.packages') }}</a>
                <a class="transition hover:text-antique" href="{{ route('about', ['locale' => app()->getLocale()]) }}">{{ __('site.about') }}</a>
                <a class="transition hover:text-antique" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('site.contact') }}</a>
            </nav>
            <div class="flex items-center gap-3">
                @php
                    $switchLocale = app()->getLocale() === 'en' ? 'ar' : 'en';
                    $switchUrl = request()->route() ? route(request()->route()->getName(), array_merge(request()->route()->parameters(), ['locale' => $switchLocale])) : url('/'.$switchLocale);
                @endphp
                <a class="focus-ring rounded-full border border-mist px-3 py-2 text-xs font-semibold text-forest transition hover:bg-sage" href="{{ $switchUrl }}" lang="{{ $switchLocale }}">{{ $switchLocale === 'ar' ? 'العربية' : 'English' }}</a>
                <a class="focus-ring hidden rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-deep sm:inline-flex" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }}</a>
                <button class="focus-ring grid size-10 place-items-center rounded-lg border border-mist text-forest lg:hidden" type="button" aria-label="{{ __('site.open_menu') }}" :aria-expanded="mobileMenuOpen.toString()" @click="mobileMenuOpen = !mobileMenuOpen" @keydown.escape.window="mobileMenuOpen = false" aria-controls="mobile-menu">☰</button>
            </div>
        </div>
        <nav id="mobile-menu" x-cloak x-show="mobileMenuOpen" class="container-wide border-t border-mist py-4 lg:hidden" aria-label="{{ __('site.mobile_navigation') }}">
            <div class="grid gap-3 text-sm font-medium">
                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.products') }}</a>
                <a href="{{ route('packages', ['locale' => app()->getLocale()]) }}">{{ __('site.packages') }}</a>
                <a href="{{ route('about', ['locale' => app()->getLocale()]) }}">{{ __('site.about') }}</a>
                <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('site.contact') }}</a>
                <a href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }}</a>
            </div>
        </nav>
    </header>

    <main id="main-content">
        @yield('content')
    </main>

    @php($certificationsEnabled = (bool) data_get(\App\Models\Setting::query()->where('key', 'certifications_visible')->first()?->value, 'enabled', false))
    <footer class="bg-deep text-white">
        <div class="container-wide grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
            <div class="max-w-sm">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="font-display text-xl font-semibold tracking-[.14em] text-gold">VELMORA</a>
                <p class="mt-4 text-sm leading-7 text-sage">{{ __('site.footer_intro') }}</p>
            </div>
            <div>
                <h2 class="font-semibold">{{ __('site.explore') }}</h2>
                <ul class="mt-4 space-y-3 text-sm text-sage">
                    <li><a class="hover:text-gold" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('site.products') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('packages', ['locale' => app()->getLocale()]) }}">{{ __('site.packages') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('catalog', ['locale' => app()->getLocale()]) }}">{{ __('site.catalog') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('markets', ['locale' => app()->getLocale()]) }}">{{ __('site.markets') }}</a></li>
                </ul>
            </div>
            <div>
                <h2 class="font-semibold">{{ __('site.company') }}</h2>
                <ul class="mt-4 space-y-3 text-sm text-sage">
                    <li><a class="hover:text-gold" href="{{ route('about', ['locale' => app()->getLocale()]) }}">{{ __('site.about') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('quality', ['locale' => app()->getLocale()]) }}">{{ __('site.quality') }}</a></li>
                    @if ($certificationsEnabled)<li><a class="hover:text-gold" href="{{ route('certifications', ['locale' => app()->getLocale()]) }}">{{ __('site.certifications') }}</a></li>@endif
                    <li><a class="hover:text-gold" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('site.contact') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('inquiries.create', ['locale' => app()->getLocale()]) }}">{{ __('site.request_quote') }}</a></li>
                </ul>
            </div>
            <div>
                <h2 class="font-semibold">{{ __('site.legal') }}</h2>
                <ul class="mt-4 space-y-3 text-sm text-sage">
                    <li><a class="hover:text-gold" href="{{ route('legal.privacy', ['locale' => app()->getLocale()]) }}">{{ __('site.privacy') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('legal.terms', ['locale' => app()->getLocale()]) }}">{{ __('site.terms') }}</a></li>
                    <li><a class="hover:text-gold" href="{{ route('admin.login') }}">{{ __('site.staff_portal') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/15">
            <div class="container-wide flex flex-col justify-between gap-3 py-5 text-xs text-sage sm:flex-row">
                <p>© {{ now()->year }} Velmora International. {{ __('site.rights') }}</p>
                <p>{{ __('site.content_note') }}</p>
            </div>
        </div>
    </footer>

    @include('components.chat-widget')
    @if (config('velmora.whatsapp'))
        @php($whatsappNumber = preg_replace('/\D+/', '', (string) config('velmora.whatsapp')))
        @if ($whatsappNumber !== '')
            <a class="focus-ring fixed bottom-5 start-5 z-40 grid size-14 place-items-center rounded-full bg-[#25D366] text-2xl text-white shadow-lg" href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('site.whatsapp_open') }}">◉</a>
        @endif
    @endif
    <aside x-data="{visible:localStorage.getItem('velmora-cookie-note') !== 'dismissed'}" x-cloak x-show="visible" class="fixed bottom-5 start-5 z-40 max-w-sm rounded-2xl border border-mist bg-white p-4 shadow-xl" role="status" aria-label="{{ __('site.consent_note') }}">
        <div class="flex items-start gap-4"><p class="text-sm leading-6 text-stone">{{ __('site.cookie_text') }}</p><button class="focus-ring shrink-0 rounded-full bg-forest px-3 py-2 text-xs font-semibold text-white" @click="localStorage.setItem('velmora-cookie-note','dismissed'); visible=false">{{ __('site.cookie_dismiss') }}</button></div>
    </aside>
</body>
</html>
