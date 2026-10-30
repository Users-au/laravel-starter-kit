<section class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4">{{ __('Account preferences') }}</h3>
    @if(session('status'))<p role="status">{{ session('status') }}</p>@endif
    @if($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
    @if($interfacePreferences)
    <form method="POST" action="{{ route('account.preferences') }}" class="grid gap-4">
        @csrf @method('PATCH')
        <input type="hidden" name="expected_version" value="{{ $interfacePreferences['version'] }}">
        <label>{{ __('Language tag') }}<input class="block border rounded w-full dark:bg-gray-800" name="locale" required value="{{ $interfacePreferences['locale'] ?? 'en' }}" list="locales"></label>
        <datalist id="locales"><option value="en"><option value="zh"><option value="de"><option value="fr"><option value="es"><option value="ja"></datalist>
        <label>{{ __('Timezone') }}<input class="block border rounded w-full dark:bg-gray-800" name="timezone" required value="{{ $interfacePreferences['timezone'] ?? 'UTC' }}"></label>
        <label>{{ __('Theme') }}<select name="theme" class="block border rounded w-full dark:bg-gray-800">
            @foreach(['system' => __('System'), 'light' => __('Light'), 'dark' => __('Dark')] as $value => $label)
                <option value="{{ $value }}" @selected($interfacePreferences['theme'] === $value)>{{ $label }}</option>
            @endforeach
        </select></label>
        <button class="rounded bg-indigo-600 text-white px-4 py-2" type="submit">{{ __('Save preferences') }}</button>
    </form>
    @else
        <p>{{ __('Preferences are temporarily unavailable. Sign in again or retry.') }}</p>
    @endif
</section>
