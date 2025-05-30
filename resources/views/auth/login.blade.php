@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600 text-base">Please sign in to continue</p>
            </div>

            <!-- Login Button -->
            <div class="space-y-6">
                <a href="{{ route('usersau.login') }}" 
                   class="group relative w-full flex justify-center py-4 px-6 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition duration-200 hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-6">
                        <svg class="h-6 w-6 text-blue-200 group-hover:text-blue-100" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    Continue
                    <span class="absolute right-0 inset-y-0 flex items-center pr-6">
                        <svg class="h-5 w-5 text-blue-200 group-hover:text-blue-100 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </span>
                </a>
            </div>

            <!-- Status Messages -->
            @if (session('status'))
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start space-x-3">
                    <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-yellow-700">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <p class="text-center text-sm text-gray-500">
                    Copyright &copy; {{ date('Y') }} {{ config('app.name') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 