@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col items-start gap-4">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md w-full shadow-inner">
                <p class="text-lg">Welcome, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
                <p class="text-md mt-2 text-gray-600 dark:text-gray-300">Email: {{ Auth::user()->email }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
