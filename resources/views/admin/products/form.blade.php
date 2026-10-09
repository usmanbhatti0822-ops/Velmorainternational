@extends('layouts.admin')

@section('title', ($product->exists ? 'Edit product' : 'Add product').' | Velmora Admin')
@section('content')
<div class="mx-auto max-w-4xl"><a class="text-sm font-semibold text-antique underline" href="{{ route('admin.products') }}">← Products</a><h1 class="mt-3 font-display text-4xl text-forest">{{ $product->exists ? 'Edit product' : 'Add product' }}</h1>
    @if ($errors->any())<div role="alert" class="mt-5 rounded-xl border border-red-300 bg-red-50 p-4 text-red-900"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @php($isEdit = $product->exists)
    <form class="mt-7 space-y-7 rounded-2xl border border-mist bg-white p-6 md:p-9" method="post" action="{{ $isEdit ? route('admin.products.update',$product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf @if($isEdit) @method('PATCH') @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-medium">Division<select class="mt-2 w-full rounded-xl border border-mist bg-white px-3 py-3" name="division_id" required><option value="">Choose division</option>@foreach($divisions as $division)<option value="{{ $division->id }}" @selected(old('division_id',$product->division_id)===$division->id)>{{ $division->localized('name') }}</option>@endforeach</select></label>
            <label class="text-sm font-medium">URL slug<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="slug" value="{{ old('slug',$product->slug) }}" required></label>
            <label class="text-sm font-medium">Name (English)<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="name_en" value="{{ old('name_en',$product->localized('name','en')) }}" required></label>
            <label class="text-sm font-medium">Name (Arabic)<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="name_ar" value="{{ old('name_ar',$product->localized('name','ar')) }}" dir="rtl"></label>
            <label class="text-sm font-medium">Short description (English)<textarea class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="short_description_en" rows="3">{{ old('short_description_en',$product->localized('short_description','en')) }}</textarea></label>
            <label class="text-sm font-medium">Short description (Arabic)<textarea class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="short_description_ar" rows="3" dir="rtl">{{ old('short_description_ar',$product->localized('short_description','ar')) }}</textarea></label>
            <label class="text-sm font-medium sm:col-span-2">Description (English)<textarea class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="description_en" rows="5">{{ old('description_en',$product->localized('description','en')) }}</textarea></label>
            <label class="text-sm font-medium sm:col-span-2">Description (Arabic)<textarea class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="description_ar" rows="5" dir="rtl">{{ old('description_ar',$product->localized('description','ar')) }}</textarea></label>
            <label class="text-sm font-medium">Origin<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="origin" value="{{ old('origin',$product->origin) }}"></label>
            <label class="text-sm font-medium">Minimum order quantity<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" type="number" min="0" step="any" name="moq_value" value="{{ old('moq_value',$product->moq_value) }}"></label>
            <label class="text-sm font-medium">MOQ unit<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" name="moq_unit" value="{{ old('moq_unit',$product->moq_unit) }}"></label>
            <label class="text-sm font-medium">Lead time (days)<input class="mt-2 w-full rounded-xl border border-mist px-3 py-3" type="number" min="1" name="lead_time_days" value="{{ old('lead_time_days',$product->lead_time_days) }}"></label>
        </div>
        <fieldset><legend class="font-semibold text-forest">Specifications</legend><p class="mt-1 text-xs text-stone">Leave unused rows blank. Add verified values only.</p>
            <div class="mt-3 grid gap-3">@for($i=0;$i<6;$i++) @php($spec=$product->specs[$i] ?? null)<div class="grid gap-2 sm:grid-cols-4"><input class="rounded-lg border border-mist px-3 py-2 text-sm" name="specs[{{ $i }}][key_en]" placeholder="Name (English)" value="{{ old("specs.$i.key_en",$spec?->localized('spec_key','en')) }}"><input class="rounded-lg border border-mist px-3 py-2 text-sm" name="specs[{{ $i }}][value_en]" placeholder="Value (English)" value="{{ old("specs.$i.value_en",$spec?->localized('spec_value','en')) }}"><input class="rounded-lg border border-mist px-3 py-2 text-sm" name="specs[{{ $i }}][key_ar]" placeholder="الاسم (عربي)" value="{{ old("specs.$i.key_ar",$spec?->localized('spec_key','ar')) }}" dir="rtl"><input class="rounded-lg border border-mist px-3 py-2 text-sm" name="specs[{{ $i }}][value_ar]" placeholder="القيمة (عربي)" value="{{ old("specs.$i.value_ar",$spec?->localized('spec_value','ar')) }}" dir="rtl"></div>@endfor</div>
        </fieldset>
        <fieldset class="grid gap-5 md:grid-cols-2"><legend class="font-semibold text-forest">Supply options</legend>
            <div class="space-y-2">@foreach($packages as $package)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="supply_packages[]" value="{{ $package->id }}" @checked($product->supplyPackages->contains($package->id))>{{ $package->localized('name') }}</label>@endforeach</div>
            <div class="space-y-2">@foreach($packagingOptions as $option)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="packaging_options[]" value="{{ $option->id }}" @checked($product->packagingOptions->contains($option->id))>{{ $option->localized('name') }}</label>@endforeach</div>
        </fieldset>
        <label class="block text-sm font-medium">Product images (JPG, PNG, WebP; max 10 MB each)<input class="mt-2 block w-full rounded-xl border border-mist bg-cream p-3" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple></label>
        @if($product->images->isNotEmpty())<div class="flex flex-wrap gap-3">@foreach($product->images as $image)<img class="size-20 rounded-lg object-cover" src="{{ str_starts_with($image->path, 'https://') ? $image->path : \Illuminate\Support\Facades\Storage::disk('public')->url($image->path) }}" alt="{{ $image->localized('alt') }}">@endforeach</div>@endif
        <div class="flex flex-wrap gap-6"><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$product->is_active))>Published</label><label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$product->is_featured))>Featured on home page</label></div>
        <button class="rounded-full bg-forest px-7 py-3 font-semibold text-white">Save product</button>
    </form>
</div>
@endsection
