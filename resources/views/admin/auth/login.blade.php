@extends('layouts.admin')

@section('title', 'Staff sign in | Velmora')
@section('content')
<div class="mx-auto max-w-md rounded-3xl border border-mist bg-white p-7 shadow-sm md:p-9">
    <p class="eyebrow">Staff access</p><h1 class="mt-3 font-display text-3xl text-forest">Sign in to Velmora</h1>
    @if ($errors->any())<div role="alert" class="mt-5 rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-900">{{ $errors->first() }}</div>@endif
    <form class="mt-7 space-y-5" method="post" action="{{ route('admin.login.store') }}">
        @csrf
        <label class="block text-sm font-medium">Work email<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
        <label class="block text-sm font-medium">Password<input class="focus-ring mt-2 w-full rounded-xl border border-mist px-4 py-3" type="password" name="password" autocomplete="current-password" required></label>
        <label class="flex items-center gap-2 text-sm text-stone"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <button class="w-full rounded-full bg-forest px-6 py-3 font-semibold text-white hover:bg-deep" type="submit">Sign in</button>
    </form>
</div>
@endsection
