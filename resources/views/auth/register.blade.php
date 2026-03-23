@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[70vh]">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md mt-10">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Student Registration</h2>

            @if ($errors->any())
                <div class="mb-4 text-red-500 text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>

                <div class="mb-4">
                    <label for="batch_token" class="block text-gray-700 text-sm font-bold mb-2">Batch Token</label>
                    <input type="text" name="batch_token" id="batch_token" value="{{ old('batch_token') }}" required
                        placeholder="ABCD1234EF"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                    <p class="text-xs text-gray-500 mt-1">Provided by your administrator.</p>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-indigo-300">
                </div>

                <div class="flex items-center justify-between mb-4">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring focus:border-indigo-300">
                        Register
                    </button>
                </div>

                <p class="text-sm text-center text-gray-600 mt-4">
                    Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log in
                        here</a>
                </p>
            </form>
        </div>
    </div>
@endsection