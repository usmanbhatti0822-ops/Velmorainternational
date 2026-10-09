@extends('layouts.admin')

@section('title', 'Contact messages | Velmora Admin')
@section('content')
<p class="eyebrow">Sales</p><h1 class="mt-2 font-display text-4xl text-forest">Contact messages</h1>
<div class="mt-7 space-y-4">@forelse($messages as $message)<article class="rounded-2xl border border-mist bg-white p-5"><div class="flex flex-wrap justify-between gap-3"><div><h2 class="font-semibold">{{ $message->name }} — {{ $message->subject ?: 'Website contact' }}</h2><a class="text-sm underline" href="mailto:{{ $message->email }}">{{ $message->email }}</a></div><time class="text-xs text-stone">{{ $message->created_at->format('Y-m-d H:i') }}</time></div><p class="mt-4 whitespace-pre-line leading-7 text-stone">{{ $message->message }}</p></article>@empty<div class="rounded-2xl border border-mist bg-white p-8 text-stone">No contact messages received.</div>@endforelse<div>{{ $messages->links() }}</div></div>
@endsection
