@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Login</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border px-3 py-2 rounded" required>
                @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
                @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded">Login</button>
        </form>

        <p class="mt-4 text-sm text-gray-600">Hubungi admin untuk membuat akun baru</p>
    </div>
</div>
@endsection
