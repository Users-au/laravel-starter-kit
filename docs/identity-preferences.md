# Account identity and preferences

Auth is authoritative for locale (a BCP 47 language tag), IANA timezone and light/dark/system theme. SDK 2.1 + provider 2 request openid/profile/email/preferences:read/preferences:write and transport all fourteen standard profile claims. Email, phone and address remain independently scoped. Missing optional values are omitted.

The application keeps the state/CSRF/session-safe UsersauController override. Its callback validates sub and issuer, creates the local user, dispatches UserProfileSynchronized and regenerates the session. Upgrade Auth and its tenant migration first; run this app's own migrations before using the new cache columns. Older grants need a fresh sign-in; refresh cannot add scopes. The SDK and provider source releases must exist before Composer update.

usersau_profile contains allowlisted claims; interface_preferences and usersau_issuer cache confirmed state. They are local copies, not a second authority. Event import, backend reads/writes and row locks reject older versions or another issuer. Changing USERSAU_HOST for an existing database requires an explicit identity migration. OAuth tokens stay hidden backend model attributes, never in browser props, logs or URLs.

GET/PATCH /account/preferences uses the authenticated session and web CSRF. PATCH accepts only locale/timezone/theme plus expected_version. A conflict returns 409 with the canonical snapshot; 401/403 requires reauthorization; outages retain the last confirmed appearance and report explicit save failures. The browser never talks to the provider with a client secret.

The server applies locale and prepaint theme; dates use the saved timezone without changing UTC storage. The preferences panel includes six example translations; applications extend translation catalogs for the rest of their UI. A language tag such as en-AU remains intact in the account while the demo catalog selects its base language.

Run composer install, php artisan migrate, npm ci, npm run build and composer test using PHP 8.5.12 / a supported Node runtime. Deploy the provider first, then this consumer and obtain new grants. No shared-saas dependency or central/tenant migrations belong in this standalone starter.
