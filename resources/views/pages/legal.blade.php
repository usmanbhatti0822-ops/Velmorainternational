@extends('layouts.app')

@section('title', ($page === 'privacy' ? __('site.privacy') : __('site.terms')).' | Velmora International')
@section('robots', 'noindex,follow')
@section('content')
<section class="section-space"><article class="container-wide max-w-3xl rounded-2xl border border-mist bg-white p-7 md:p-10">
    <p class="eyebrow">{{ __('site.legal') }}</p>
    <h1 class="mt-3 font-display text-4xl text-forest">{{ $page === 'privacy' ? __('site.privacy_draft_title') : __('site.terms_draft_title') }}</h1>
    <p class="mt-6 leading-8 text-stone">{{ $page === 'privacy' ? __('site.privacy_draft_body') : __('site.terms_draft_body') }}</p>
</article></section>
@endsection
