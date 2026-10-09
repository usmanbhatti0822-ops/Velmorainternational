@extends('layouts.admin')

@section('title', 'Dashboard | Velmora Admin')
@section('section', 'Overview')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-5">
    <div><p class="eyebrow">Trade workspace <span class="mx-1 text-gold">/</span> Overview</p><h1 class="mt-2 font-display text-4xl tracking-tight text-forest md:text-5xl">Good to see you, {{ auth()->user()->name }}</h1><p class="mt-3 text-sm text-stone">A considered view of your catalog and buyer conversations.</p></div>
    <a class="inline-flex items-center gap-2 rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white shadow-[0_10px_24px_rgba(20,80,58,.18)] transition hover:-translate-y-0.5 hover:bg-deep" href="{{ route('home', ['locale'=>'en']) }}" target="_blank" rel="noopener">Preview website <span aria-hidden="true">↗</span></a>
</div>
<div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([['New inquiries',$newInquiries,'↗','New buyer requests'],['Open conversations',$openConversations,'◌','Chats needing attention'],['Unread chat messages',$unreadMessages,'✉','Messages waiting to be read'],['Published products',$productsCount,'◇','Live catalog listings']] as [$label,$value,$icon,$hint])
        <article class="group rounded-[1.35rem] border border-[#e7ebe6] bg-white p-5 shadow-[0_8px_28px_rgba(20,50,38,.035)] transition hover:-translate-y-1 hover:shadow-[0_18px_38px_rgba(20,50,38,.08)] md:p-6">
            <div class="flex items-center justify-between"><p class="text-sm font-medium text-stone">{{ $label }}</p><span class="grid size-10 place-items-center rounded-xl bg-sage/70 text-lg text-forest transition group-hover:bg-forest group-hover:text-gold">{{ $icon }}</span></div>
            <p class="mt-6 font-display text-4xl text-forest">{{ number_format($value) }}</p><p class="mt-2 text-xs text-stone/80">{{ $hint }}</p>
        </article>
    @endforeach
</div>
<div class="mt-8 grid gap-5 xl:grid-cols-2">
    <section class="rounded-[1.5rem] border border-[#e7ebe6] bg-white p-5 shadow-[0_8px_28px_rgba(20,50,38,.035)] md:p-7">
        <div class="flex items-center justify-between gap-3"><div><p class="eyebrow">Buyer activity</p><h2 class="mt-1 font-display text-2xl text-forest">Recent inquiries</h2></div><a class="rounded-full border border-mist px-4 py-2 text-xs font-semibold text-forest transition hover:border-gold" href="{{ route('admin.inquiries') }}">View all <span aria-hidden="true">→</span></a></div>
        <ul class="mt-5 divide-y divide-[#edf0ec]">@forelse ($recentInquiries as $inquiry)<li class="flex items-center justify-between gap-4 py-4"><div class="flex min-w-0 items-center gap-3"><span class="grid size-10 shrink-0 place-items-center rounded-full bg-cream font-display font-semibold text-forest">{{ mb_substr($inquiry->contact->name, 0, 1) }}</span><div class="min-w-0"><p class="truncate text-sm font-semibold">{{ $inquiry->contact->name }}</p><p class="mt-1 text-xs text-stone">{{ $inquiry->reference }} · {{ $inquiry->created_at->diffForHumans() }}</p></div></div><span class="shrink-0 rounded-full bg-sage/80 px-3 py-1 text-[.68rem] font-semibold capitalize text-forest">{{ $inquiry->status }}</span></li>@empty<li class="py-8 text-center text-sm text-stone">No inquiries received yet.</li>@endforelse</ul>
    </section>
    <section class="rounded-[1.5rem] border border-[#e7ebe6] bg-white p-5 shadow-[0_8px_28px_rgba(20,50,38,.035)] md:p-7">
        <div class="flex items-center justify-between gap-3"><div><p class="eyebrow">Live support</p><h2 class="mt-1 font-display text-2xl text-forest">Recent chats</h2></div><a class="rounded-full border border-mist px-4 py-2 text-xs font-semibold text-forest transition hover:border-gold" href="{{ route('admin.conversations') }}">Open inbox <span aria-hidden="true">→</span></a></div>
        <ul class="mt-5 divide-y divide-[#edf0ec]">@forelse ($recentConversations as $conversation)<li class="flex items-center justify-between gap-4 py-4"><div class="flex min-w-0 items-center gap-3"><span class="grid size-10 shrink-0 place-items-center rounded-full bg-sage/70 font-display font-semibold text-forest">{{ mb_substr($conversation->contact->name, 0, 1) }}</span><div class="min-w-0"><a class="block truncate text-sm font-semibold hover:text-antique" href="{{ route('admin.conversations.show',$conversation) }}">{{ $conversation->contact->name }}</a><p class="mt-1 text-xs text-stone">{{ $conversation->last_message_at?->diffForHumans() ?? 'Conversation started' }}</p></div></div><span class="shrink-0 rounded-full border border-mist px-3 py-1 text-[.68rem] font-semibold capitalize text-stone">{{ $conversation->status }}</span></li>@empty<li class="py-8 text-center text-sm text-stone">No conversations yet.</li>@endforelse</ul>
    </section>
</div>
@endsection
