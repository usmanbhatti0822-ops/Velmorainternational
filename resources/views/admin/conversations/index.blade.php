@extends('layouts.admin')

@section('title', 'Chat inbox | Velmora Admin')
@section('content')
<meta http-equiv="refresh" content="8">
<div class="flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow">Customer service</p><h1 class="mt-2 font-display text-4xl text-forest">Chat inbox</h1><p class="mt-2 text-sm text-stone">This inbox refreshes every few seconds when WebSockets are not configured.</p></div>
    <form method="get"><label class="sr-only" for="chat-status">Filter by status</label><select id="chat-status" class="rounded-full border border-mist bg-white px-4 py-2.5 text-sm" name="status" onchange="this.form.submit()"><option value="">All conversations</option>@foreach(['waiting','active','offline','closed'] as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></form>
</div>
<div class="mt-7 overflow-x-auto rounded-2xl border border-mist bg-white">
    <table class="w-full min-w-[650px] text-sm"><thead class="bg-sage text-forest"><tr><th class="p-4 text-start">Visitor</th><th class="p-4 text-start">Context</th><th class="p-4 text-start">Status</th><th class="p-4 text-start">Last activity</th><th class="p-4"></th></tr></thead><tbody class="divide-y divide-mist">
    @forelse($conversations as $conversation)<tr><td class="p-4"><p class="font-semibold">{{ $conversation->contact->name }}</p><p class="text-xs text-stone">{{ $conversation->contact->email }}</p>@if($conversation->unread_agent_count)<span class="mt-1 inline-flex rounded-full bg-gold/30 px-2 py-0.5 text-xs">{{ $conversation->unread_agent_count }} unread</span>@endif</td><td class="p-4">{{ $conversation->product?->localized('name') ?? 'General enquiry' }}<p class="mt-1 max-w-52 truncate text-xs text-stone">{{ $conversation->page_url }}</p></td><td class="p-4">{{ ucfirst($conversation->status) }}</td><td class="p-4">{{ $conversation->last_message_at?->diffForHumans() }}</td><td class="p-4 text-end"><a class="font-semibold text-forest underline" href="{{ route('admin.conversations.show',$conversation) }}">Open</a></td></tr>@empty<tr><td class="p-6 text-stone" colspan="5">No conversations found.</td></tr>@endforelse
    </tbody></table>
</div><div class="mt-5">{{ $conversations->links() }}</div>
@endsection
