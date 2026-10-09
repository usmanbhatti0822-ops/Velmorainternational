<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Velmora Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f6f3] font-sans text-charcoal antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[260px_minmax(0,1fr)]">
        <aside class="relative overflow-hidden bg-[#102f24] text-white">
            <div class="premium-grain pointer-events-none absolute inset-0 opacity-20" aria-hidden="true"></div>
            <div class="relative flex items-center justify-between gap-4 px-5 py-5 lg:block lg:px-6 lg:py-7">
                <a class="flex items-center gap-3" href="{{ route('admin.dashboard') }}">
                    <span class="grid size-11 place-items-center rounded-2xl border border-gold/40 bg-white/5 font-display text-xl font-bold text-gold">V</span>
                    <span><span class="block font-display text-lg font-semibold tracking-[.14em]">VELMORA</span><span class="mt-0.5 block text-[.62rem] uppercase tracking-[.24em] text-sage/70">Trade workspace</span></span>
                </a>
                <span class="rounded-full border border-white/15 px-3 py-1 text-[.65rem] uppercase tracking-[.16em] text-gold lg:mt-5 lg:inline-flex">Admin portal</span>
            </div>
            @auth
                <nav class="relative flex gap-2 overflow-x-auto px-4 pb-4 text-sm lg:mt-8 lg:block lg:space-y-1 lg:overflow-visible lg:px-4" aria-label="Admin navigation">
                    <p class="hidden px-3 pb-2 text-[.62rem] font-semibold uppercase tracking-[.2em] text-white/40 lg:block">Workspace</p>
                    <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl bg-white/10 px-3 py-2.5 text-white lg:w-full" href="{{ route('admin.dashboard') }}"><span class="text-gold">◫</span>Dashboard</a>
                    @if (in_array(auth()->user()->role, ['super_admin','sales'], true))
                        <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sage/80 hover:bg-white/10 hover:text-white lg:w-full" href="{{ route('admin.inquiries') }}"><span class="text-gold">↗</span>Inquiries</a>
                        <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sage/80 hover:bg-white/10 hover:text-white lg:w-full" href="{{ route('admin.contact-messages') }}"><span class="text-gold">✉</span>Contact messages</a>
                    @endif
                    @if (in_array(auth()->user()->role, ['super_admin','content_manager'], true))
                        <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sage/80 hover:bg-white/10 hover:text-white lg:w-full" href="{{ route('admin.products') }}"><span class="text-gold">◇</span>Products</a>
                        <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sage/80 hover:bg-white/10 hover:text-white lg:w-full" href="{{ route('admin.content') }}"><span class="text-gold">▤</span>Content</a>
                        <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sage/80 hover:bg-white/10 hover:text-white lg:w-full" href="{{ route('admin.settings') }}"><span class="text-gold">⚙</span>Settings</a>
                    @endif
                    @if (in_array(auth()->user()->role, ['super_admin','sales','chat_agent'], true))
                        <a class="admin-sidebar-link flex shrink-0 items-center gap-3 rounded-xl px-3 py-2.5 text-sage/80 hover:bg-white/10 hover:text-white lg:w-full" href="{{ route('admin.conversations') }}"><span class="text-gold">◌</span>Chat inbox</a>
                    @endif
                </nav>
                <div class="relative hidden border-t border-white/10 px-6 py-5 lg:block">
                    <p class="text-[.62rem] font-semibold uppercase tracking-[.2em] text-white/40">Signed in as</p>
                    <p class="mt-2 truncate text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="mt-1 text-xs capitalize text-sage/60">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    <form class="mt-5" method="post" action="{{ route('admin.logout') }}">@csrf<button class="w-full rounded-xl border border-white/15 px-4 py-2.5 text-start text-xs font-semibold text-sage transition hover:border-gold/50 hover:text-white">Sign out <span class="float-end">↗</span></button></form>
                </div>
            @endauth
        </aside>
        <div class="min-w-0">
            <header class="flex min-h-[4.5rem] items-center justify-between border-b border-[#e5e9e4] bg-white/85 px-5 backdrop-blur md:px-8">
                <div><p class="text-xs text-stone">Velmora International <span class="mx-1 text-mist">/</span> <span class="font-medium text-forest">@yield('section', 'Workspace')</span></p></div>
                @auth
                    <div class="flex items-center gap-3">
                        <a class="hidden rounded-full border border-mist px-4 py-2 text-xs font-semibold text-forest transition hover:bg-cream sm:inline-flex" href="{{ route('home', ['locale'=>'en']) }}" target="_blank" rel="noopener">View website ↗</a>
                        <span class="grid size-9 place-items-center rounded-full bg-sage font-display text-sm font-semibold text-forest">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        <span class="hidden text-sm font-semibold text-charcoal sm:block">{{ auth()->user()->name }}</span>
                    </div>
                @endauth
            </header>
            <main class="mx-auto max-w-[1440px] px-5 py-8 md:px-8 md:py-10">
                @if (session('status'))<div role="status" class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-green-900">{{ session('status') }}</div>@endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
