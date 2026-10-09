@extends('layouts.admin')

@section('title', 'Inquiry inbox | Velmora Admin')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow">Sales</p><h1 class="mt-2 font-display text-4xl text-forest">Inquiry inbox</h1></div><a class="rounded-full border border-forest px-5 py-3 text-sm font-semibold text-forest hover:bg-sage" href="{{ route('admin.inquiries.export') }}">Export CSV ↓</a></div>
<div class="mt-7 space-y-4">
    @forelse ($inquiries as $inquiry)
        <article class="rounded-2xl border border-mist bg-white p-5 md:p-6">
            <div class="flex flex-wrap justify-between gap-3"><div><h2 class="font-display text-xl text-forest">{{ $inquiry->reference }}</h2><p class="mt-1 text-sm">{{ $inquiry->contact->name }} · <a class="underline" href="mailto:{{ $inquiry->contact->email }}">{{ $inquiry->contact->email }}</a></p><p class="mt-1 text-xs text-stone">{{ $inquiry->contact->company }} · {{ $inquiry->contact->country_code }} · {{ $inquiry->created_at->format('Y-m-d H:i') }}</p></div><span class="h-fit rounded-full bg-sage px-3 py-1 text-xs font-semibold">{{ ucfirst($inquiry->status) }} / {{ ucfirst($inquiry->priority) }}</span></div>
            @if ($inquiry->items->isNotEmpty())<ul class="mt-4 flex flex-wrap gap-2">@foreach ($inquiry->items as $item)<li class="rounded-full border border-mist px-3 py-1 text-xs">{{ $item->product_name_text ?: $item->product?->localized('name') }} @if($item->quantity)· {{ $item->quantity }} {{ $item->unit }}@endif</li>@endforeach</ul>@endif
            @if ($inquiry->destination_port)<p class="mt-3 text-sm"><strong>Destination:</strong> {{ $inquiry->destination_port }}</p>@endif
            @if ($inquiry->message)<p class="mt-3 whitespace-pre-line text-sm leading-6 text-stone">{{ $inquiry->message }}</p>@endif
            <form class="mt-5 grid gap-3 border-t border-mist pt-4 sm:grid-cols-[1fr_1fr_2fr_auto]" method="post" action="{{ route('admin.inquiries.update',$inquiry) }}">@csrf @method('PATCH')
                <select class="rounded-lg border border-mist px-3 py-2 text-sm" name="status">@foreach(['new','contacted','quoted','negotiating','won','lost','spam'] as $status)<option value="{{ $status }}" @selected($inquiry->status===$status)>{{ ucfirst($status) }}</option>@endforeach</select>
                <select class="rounded-lg border border-mist px-3 py-2 text-sm" name="priority">@foreach(['low','normal','high'] as $priority)<option value="{{ $priority }}" @selected($inquiry->priority===$priority)>{{ ucfirst($priority) }} priority</option>@endforeach</select>
                <input class="rounded-lg border border-mist px-3 py-2 text-sm" name="note" placeholder="Add an internal note (optional)">
                <button class="rounded-lg bg-forest px-4 py-2 text-sm font-semibold text-white">Save</button>
            </form>
        </article>
    @empty
        <div class="rounded-2xl border border-mist bg-white p-8 text-stone">No inquiries have been received.</div>
    @endforelse
    <div>{{ $inquiries->links() }}</div>
</div>
@endsection
