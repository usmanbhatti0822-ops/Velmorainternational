@extends('layouts.admin')

@section('title', 'Settings | Velmora Admin')
@section('content')
<p class="eyebrow">Content controls</p><h1 class="mt-2 font-display text-4xl text-forest">Settings</h1>
<form class="mt-7 max-w-3xl rounded-2xl border border-mist bg-white p-6 md:p-8" method="post" action="{{ route('admin.settings.update') }}">@csrf @method('PATCH')
    <h2 class="font-display text-2xl text-forest">Certifications visibility</h2>
    <p class="mt-2 leading-6 text-stone">Certification listings stay hidden by default. Only enable this after the client approves the certificates and supporting files.</p>
    <label class="mt-5 flex items-center gap-3 font-medium"><input class="size-5 accent-forest" type="checkbox" name="certifications_visible" value="1" @checked($certificationsVisible)>Show approved certifications on the public website</label>
    <button class="mt-6 rounded-full bg-forest px-6 py-3 font-semibold text-white">Save setting</button>
</form>
<section class="mt-8 max-w-3xl rounded-2xl border border-mist bg-white p-6 md:p-8"><h2 class="font-display text-2xl text-forest">Certifications in the system</h2><ul class="mt-4 divide-y divide-mist">@forelse($certifications as $certification)<li class="flex flex-wrap items-center justify-between gap-3 py-3"><span>{{ $certification->localized('name') }}</span><form class="flex items-center gap-3" method="post" action="{{ route('admin.certifications.visibility',$certification) }}">@csrf @method('PATCH')<label class="flex items-center gap-2 text-sm"><input type="hidden" name="is_visible" value="0"><input type="checkbox" name="is_visible" value="1" @checked($certification->is_visible)>Approved / visible</label><button class="rounded-full border border-mist px-3 py-1 text-xs font-semibold">Save</button></form></li>@empty<li class="py-4 text-sm text-stone">No certifications have been entered.</li>@endforelse</ul></section>
<section class="mt-8 max-w-3xl rounded-2xl border border-mist bg-white p-6 md:p-8"><h2 class="font-display text-2xl text-forest">Add an approved certification record</h2><p class="mt-2 text-sm text-stone">Records are created hidden. Upload only current client-approved certificates.</p>
    <form class="mt-5 space-y-3" method="post" action="{{ route('admin.certifications.store') }}" enctype="multipart/form-data">@csrf
        <div class="grid gap-3 sm:grid-cols-2"><input class="rounded-lg border border-mist px-3 py-2" name="name_en" placeholder="Name (English)" required><input class="rounded-lg border border-mist px-3 py-2" name="name_ar" placeholder="Name (Arabic)" dir="rtl"><input class="rounded-lg border border-mist px-3 py-2 sm:col-span-2" name="issuer" placeholder="Issuer"><label class="text-xs">Certificate file (PDF or image)<input class="mt-1 block w-full" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.webp"></label><label class="text-xs">Logo (optional)<input class="mt-1 block w-full" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp"></label></div><button class="rounded-full bg-forest px-5 py-2 text-sm font-semibold text-white">Save hidden certification</button>
    </form>
</section>
@endsection
