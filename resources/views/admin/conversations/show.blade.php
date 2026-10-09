@extends('layouts.admin')

@section('title', 'Conversation | Velmora Admin')
@section('content')
<meta http-equiv="refresh" content="6">
<a class="text-sm font-semibold text-antique underline" href="{{ route('admin.conversations') }}">← Chat inbox</a>
<div class="mt-4 grid items-start gap-6 lg:grid-cols-[1fr_300px]">
    <section class="overflow-hidden rounded-2xl border border-mist bg-white">
        <header class="flex flex-wrap items-center justify-between gap-4 border-b border-mist p-5"><div><h1 class="font-display text-2xl text-forest">{{ $conversation->contact->name }}</h1><p class="mt-1 text-sm text-stone">{{ $conversation->contact->email }} · {{ ucfirst($conversation->status) }}</p></div>
            <form method="post" action="{{ route('admin.conversations.update',$conversation) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="{{ $conversation->status === 'closed' ? 'active' : 'closed' }}"><button class="rounded-full border border-mist px-4 py-2 text-sm font-semibold">{{ $conversation->status === 'closed' ? 'Reopen' : 'Close conversation' }}</button></form>
        </header>
        <div class="max-h-[55vh] space-y-4 overflow-y-auto bg-cream/60 p-5">
            @forelse($conversation->messages as $message)
                <article class="max-w-[88%] rounded-2xl p-4 {{ $message->is_internal ? 'ms-auto border border-gold/50 bg-[#fff8e9]' : ($message->sender_type === 'agent' ? 'ms-auto bg-forest text-white' : 'me-auto bg-white shadow-sm') }}">
                    <p class="mb-1 text-[.65rem] font-bold uppercase tracking-wider {{ $message->sender_type === 'agent' && ! $message->is_internal ? 'text-gold' : 'text-antique' }}">{{ $message->is_internal ? 'Internal note' : ucfirst($message->sender_type) }} · {{ $message->created_at->format('M j, H:i') }}</p>
                    <p class="whitespace-pre-wrap break-words text-sm leading-6">{{ $message->body }}</p>
                    @foreach($message->attachments as $attachment)<a class="mt-2 block text-sm underline" href="{{ route('chat.attachments.show',['uuid'=>$conversation->uuid,'attachment'=>$attachment->id]) }}" target="_blank" rel="noopener">{{ $attachment->original_name }} ({{ number_format($attachment->size/1024,0) }} KB)</a>@endforeach
                </article>
            @empty<p class="text-sm text-stone">No messages yet.</p>@endforelse
        </div>
        @if($conversation->status !== 'closed')
            <form class="space-y-3 border-t border-mist p-5" method="post" action="{{ route('admin.conversations.reply',$conversation) }}" enctype="multipart/form-data">@csrf
                <label class="sr-only" for="chat-reply">Reply</label><textarea id="chat-reply" class="w-full rounded-xl border border-mist px-4 py-3 text-sm" name="body" rows="3" maxlength="2000" placeholder="Write a reply or an internal note..."></textarea>
                <div class="flex flex-wrap items-center justify-between gap-3"><div class="flex flex-wrap items-center gap-4 text-xs text-stone"><label class="flex items-center gap-2"><input type="checkbox" name="is_internal" value="1"> Internal note only</label><label>Attach file<input class="ms-2 max-w-48" type="file" name="attachment" accept=".jpg,.jpeg,.png,.webp,.pdf,.docx,.xlsx"></label></div><button class="rounded-full bg-forest px-6 py-2.5 text-sm font-semibold text-white">Send</button></div>
            </form>
        @endif
    </section>
    <aside class="space-y-4 rounded-2xl border border-mist bg-white p-5">
        <h2 class="font-display text-xl text-forest">Visitor details</h2>
        <dl class="space-y-3 text-sm"><div><dt class="text-xs uppercase text-stone">Email</dt><dd><a class="break-all underline" href="mailto:{{ $conversation->contact->email }}">{{ $conversation->contact->email }}</a></dd></div><div><dt class="text-xs uppercase text-stone">Phone</dt><dd>{{ $conversation->contact->phone ?: 'Not provided' }}</dd></div><div><dt class="text-xs uppercase text-stone">Company</dt><dd>{{ $conversation->contact->company ?: 'Not provided' }}</dd></div><div><dt class="text-xs uppercase text-stone">Country</dt><dd>{{ $conversation->country_code ?: 'Not provided' }}</dd></div><div><dt class="text-xs uppercase text-stone">Product</dt><dd>{{ $conversation->product?->localized('name') ?: 'General enquiry' }}</dd></div><div><dt class="text-xs uppercase text-stone">Page</dt><dd class="break-all">{{ $conversation->page_url ?: 'Not provided' }}</dd></div><div><dt class="text-xs uppercase text-stone">Started</dt><dd>{{ $conversation->started_at?->format('M j, Y H:i') }}</dd></div></dl>
        <a class="inline-flex rounded-full border border-forest px-4 py-2 text-sm font-semibold text-forest" href="{{ route('inquiries.create',['locale'=>'en']) }}">Convert to quote request ↗</a>
    </aside>
</div>
@endsection
