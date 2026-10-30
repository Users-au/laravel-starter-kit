<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-950">
    <div>
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-gray-400" />
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-900 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div> 