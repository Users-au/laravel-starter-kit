@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <h1 class="mt-8 text-2xl font-medium text-gray-900">
                    Welcome to your Laravel application!
                </h1>

                <p class="mt-6 text-gray-500 leading-relaxed">
                    You have successfully authenticated with Usersau OAuth. This is your dashboard where you can manage your application.
                </p>
            </div>

            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                <div>
                    <div class="flex items-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h2 class="ml-3 text-xl font-semibold text-gray-900">
                            Documentation
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        Laravel has wonderful documentation covering every aspect of the framework. Whether you are a newcomer or have prior experience with Laravel, we recommend reading our documentation from beginning to end.
                    </p>

                    <p class="mt-4 text-sm">
                        <a href="https://laravel.com/docs" class="inline-flex items-center font-semibold text-indigo-700">
                            Explore the documentation

                            <svg viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </p>
                </div>

                <div>
                    <div class="flex items-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                            <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <h2 class="ml-3 text-xl font-semibold text-gray-900">
                            Usersau Integration
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        This application is integrated with Usersau OAuth for seamless authentication. Users can sign in with their Usersau accounts and access your application securely.
                    </p>

                    <p class="mt-4 text-sm">
                        <a href="https://usersau.com/docs" class="inline-flex items-center font-semibold text-indigo-700">
                            Learn about Usersau

                            <svg viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </p>
                </div>

                <div>
                    <div class="flex items-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                            <path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5v12a2 2 0 002 2 2 2 0 002-2V3zM13 3h2a2 2 0 012 2v12a4 4 0 01-4 4 4 4 0 01-4-4V5a2 2 0 012-2z"></path>
                        </svg>
                        <h2 class="ml-3 text-xl font-semibold text-gray-900">
                            User Information
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        You are logged in as <strong>{{ Auth::user()->name }}</strong> with email <strong>{{ Auth::user()->email }}</strong>.
                    </p>

                    <p class="mt-4 text-sm">
                        <a href="{{ route('usersau.account') }}" class="inline-flex items-center font-semibold text-indigo-700">
                            Manage Account

                            <svg viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </p>
                </div>

                <div>
                    <div class="flex items-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500">
                            <path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                        </svg>
                        <h2 class="ml-3 text-xl font-semibold text-gray-900">
                            Tailwind CSS
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        This starter kit is built with Tailwind CSS, a utility-first CSS framework for rapidly building custom user interfaces. The design is responsive and works on all devices.
                    </p>

                    <p class="mt-4 text-sm">
                        <a href="https://tailwindcss.com/docs" class="inline-flex items-center font-semibold text-indigo-700">
                            Explore Tailwind CSS

                            <svg viewBox="0 0 20 20" class="ml-1 w-5 h-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 