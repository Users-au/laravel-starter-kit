@extends('layouts.app')

@section('title', 'Login')

@section('content')
<x-authentication-card>
    <div class="mb-4 text-sm text-gray-600 text-center">
        Welcome! Please sign in with your Usersau account to continue.
    </div>

    <div class="flex items-center justify-center">
        <a href="{{ route('usersau.login') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full justify-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            Sign in with Usersau
        </a>
    </div>

    @if (session('status'))
        <div class="mt-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 rounded p-3">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 font-medium text-sm text-red-600 bg-red-100 border border-red-400 rounded p-3">
            {{ session('error') }}
        </div>
    @endif
</x-authentication-card>
@endsection 