@extends('base')
@section('content')
    <nav class="w-full">
        <div class="grid justify-items-end">
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button
                    class="mt-4 mr-4 hover:bg-gray-200 rounded border border-gray-500 text-gray-700 px-4 sm:px-8 py-1 sm:py-3 text-sm">
                    Log out
                </button>
            </form>
        </div>
    </nav>
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="flex bg-red-100 rounded-lg p-4 mb-4 text-sm text-red-700" role="alert">
                        <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            {{ $error }}
                        </div>
                    </div>
                @endforeach
            @endif
            <form class="bg-white shadow-lg rounded px-12 pt-6 pb-8 mb-4" action="{{ route('user.update') }}"
                  method="POST">
                @csrf
                <div
                    class="text-gray-800 text-2xl flex justify-center border-b-2 py-2 mb-4"
                >
                    Update your info
                </div>
                <div class="mb-4">
                    <label
                        class="block text-gray-700 text-sm font-normal mb-2"
                        for="name"
                    >
                        Name
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="name"
                        type="text"
                        id="name"
                        value="{{ $user->name }}"
                        required
                        autofocus
                        placeholder="John Doe"
                    />
                </div>
                <div class="mb-4">
                    <label
                        class="block text-gray-700 text-sm font-normal mb-2"
                        for="email"
                    >
                        Email
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="email"
                        type="email"
                        id="email"
                        value="{{ $user->email }}"
                        required
                        autofocus
                        placeholder="Email"
                    />
                </div>
                <div class="mb-4">
                    <label
                        class="block text-gray-700 text-sm font-normal mb-2"
                        for="phone"
                    >
                        Phone
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="phone"
                        type="text"
                        id="phone"
                        value="{{ $user->phone }}"
                        required
                        autofocus
                        placeholder="+15484984984"
                    />
                </div>
                <div class="flex">
                    <button
                        class="px-4 py-2 rounded text-white inline-block shadow-lg bg-blue-500 hover:bg-blue-600 focus:bg-blue-700"
                        type="submit">Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
