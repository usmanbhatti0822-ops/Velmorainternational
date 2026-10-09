@extends('layouts.app')

@section('title', __('site.certifications').' | Velmora International')
@section('content')
<section class="section-space"><div class="container-wide"><p class="eyebrow">{{ __('site.company') }}</p><h1 class="mt-3 font-display text-5xl text-forest">{{ __('site.certifications') }}</h1>
    @if ($certifications->isNotEmpty())
        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">@foreach($certifications as $certification)<article class="rounded-2xl border border-mist bg-white p-6">@if($certification->logo_path)<img class="mb-4 h-16 object-contain" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($certification->logo_path) }}" alt="">@endif<h2 class="font-display text-xl text-forest">{{ $certification->localized('name') }}</h2>@if($certification->issuer)<p class="mt-2 text-sm text-stone">{{ $certification->issuer }}</p>@endif @if($certification->file_path)<a class="mt-4 inline-flex font-semibold text-forest underline" href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($certification->file_path) }}">{{ __('site.view_certificate') }}</a>@endif</article>@endforeach</div>
    @else<p class="mt-5 text-stone">{{ __('site.certification_empty') }}</p>@endif
</div></section>
@endsection
