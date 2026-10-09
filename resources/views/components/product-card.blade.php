<article class="group overflow-hidden rounded-[1.4rem] border border-mist/80 bg-white shadow-[0_12px_38px_rgba(23,53,39,.055)] transition duration-500 hover:-translate-y-1.5 hover:shadow-[0_24px_55px_rgba(23,53,39,.13)]">
    <a class="block" href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->slug]) }}">
        <div class="relative grid h-60 place-items-center overflow-hidden bg-gradient-to-br from-cream to-sage">
            @if ($product->images->first())
                @php($productImagePath = $product->images->first()->path)
                <img class="size-full object-cover transition duration-700 group-hover:scale-110" src="{{ str_starts_with($productImagePath, 'https://') ? $productImagePath : \Illuminate\Support\Facades\Storage::disk('public')->url($productImagePath) }}" alt="{{ $product->images->first()->localized('alt') }}" loading="lazy">
            @else
                <span class="font-display text-5xl text-forest/20" aria-hidden="true">{{ mb_substr($product->localized('name'), 0, 1) }}</span>
            @endif
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/35 to-transparent"></div>
            <span class="absolute start-4 top-4 rounded-full border border-white/70 bg-white/90 px-3.5 py-1.5 text-[.68rem] font-semibold uppercase tracking-[.12em] text-forest backdrop-blur">{{ $product->division->localized('name') }}</span>
            <span class="absolute bottom-4 end-4 grid size-10 translate-y-2 place-items-center rounded-full bg-white text-forest opacity-0 shadow-lg transition duration-300 group-hover:translate-y-0 group-hover:opacity-100" aria-hidden="true">↗</span>
        </div>
        <div class="p-5 md:p-6">
            <h3 class="font-display text-[1.45rem] text-forest">{{ $product->localized('name') }}</h3>
            <p class="mt-2 line-clamp-2 min-h-12 text-sm leading-6 text-stone">{{ $product->localized('short_description') }}</p>
            <div class="mt-5 flex items-center justify-between border-t border-mist/80 pt-4 text-sm">
                <span class="font-semibold text-forest">{{ __('site.view_details') }}</span><span class="text-antique" aria-hidden="true">↗</span>
            </div>
        </div>
    </a>
</article>
