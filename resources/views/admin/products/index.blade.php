@extends('layouts.admin')

@section('title', 'Products | Velmora Admin')
@section('content')
<div class="flex flex-wrap items-end justify-between gap-4"><div><p class="eyebrow">Catalog</p><h1 class="mt-2 font-display text-4xl text-forest">Products</h1></div><a class="rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white" href="{{ route('admin.products.create') }}">Add product +</a></div>
<div class="mt-7 overflow-x-auto rounded-2xl border border-mist bg-white">
    <table class="w-full min-w-[640px] text-start text-sm"><thead class="bg-sage text-forest"><tr><th class="p-4 text-start">Product</th><th class="p-4 text-start">Division</th><th class="p-4 text-start">Status</th><th class="p-4 text-end">Action</th></tr></thead><tbody class="divide-y divide-mist">
    @forelse($products as $product)<tr><td class="p-4 font-semibold">{{ $product->localized('name') }}</td><td class="p-4">{{ $product->division->localized('name') }}</td><td class="p-4">{{ $product->trashed() ? 'Archived' : ($product->is_active ? 'Published' : 'Draft') }}</td><td class="p-4 text-end">@unless($product->trashed())<a class="font-semibold text-forest underline" href="{{ route('admin.products.edit',$product) }}">Edit</a>@endunless</td></tr>@empty<tr><td class="p-6 text-stone" colspan="4">No catalog items are currently available.</td></tr>@endforelse
    </tbody></table>
</div><div class="mt-5">{{ $products->links() }}</div>
@endsection
