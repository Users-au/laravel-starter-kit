<div align="center">

# Users.au Laravel Starter Kit

**A Laravel + Blade starting point for hosted authentication.**

[Quick start](#installation) · [OAuth setup](#usersau-oauth-setup) · [Screenshots](#usersau-in-action) · [Users.au](https://www.users.au)

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](composer.json) [![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)](#requirements) [![MIT license](https://img.shields.io/badge/license-MIT-blue?style=flat-square)](https://opensource.org/licenses/MIT) [![Issues](https://img.shields.io/github/issues/Users-au/laravel-starter-kit?style=flat-square)](https://github.com/Users-au/laravel-starter-kit/issues) [![PRs welcome](https://img.shields.io/badge/PRs-welcome-brightgreen?style=flat-square)](https://github.com/Users-au/laravel-starter-kit/pulls)

</div>

Start a Laravel application with Users.au OAuth, a Blade interface and a
protected dashboard. Customize the product while Users.au hosts the sign-in
experience.

| 🧩 Laravel application | 🎨 Blade interface | 🔐 Users.au integration |
| --- | --- | --- |
| Laravel 12, Sanctum and local user records. | Tailwind CSS and Alpine.js components. | Login, registration, account management and coordinated logout. |

> [!TIP]
> Adding authentication to an existing Laravel app? Use the
> [Laravel client](https://github.com/Users-au/laravel-client). Prefer React and
> Next.js? Explore the [Next.js starter](https://github.com/Users-au/nextjs-starter-kit).

## Users.au in action

Users.au hosts the authentication experience on your identity domain. Enable the
sign-in methods available to your project and connect them to your application
through OAuth. These official product screenshots show the hosted experience.

<table>
  <tr><th>🔑 Branded sign-in & social providers</th><th>🛡️ Multi-factor authentication</th></tr>
  <tr>
    <td width="50%"><a href="docs/images/usersau-social.png"><img src="docs/images/usersau-social.png" alt="Users.au branded sign-in with Google, Facebook and GitHub options" width="440"></a></td>
    <td width="50%"><a href="docs/images/usersau-mfa.png"><img src="docs/images/usersau-mfa.png" alt="Users.au authenticator-code challenge with recovery-code option" width="440"></a></td>
  </tr>
</table>

<details>
<summary><strong>👆 Preview the passkey experience</strong></summary>

![Users.au passkey sign-in with a Touch ID prompt](docs/images/usersau-passkey.png)

</details>

[Explore Users.au](https://www.users.au) · [Create a project](https://my.users.au) · [Image sources](docs/images/README.md)

## Security upgrades and verification

This starter targets Laravel 12 and Sanctum 4. Laravel 10 and 11 have reached
end of security support; see the [official support policy](https://laravel.com/docs/12.x/releases#support-policy).
The classic Laravel application layout is retained; you do not need to rewrite
`bootstrap/app.php` when upgrading an application derived from this starter.

For an existing app, back up its database and `.env`, preserve `APP_KEY`, and
review the [Laravel 11](https://laravel.com/docs/11.x/upgrade) and
[Laravel 12](https://laravel.com/docs/12.x/upgrade) upgrade guides before applying
these changes. Install the committed `composer.lock` and `package-lock.json`,
then run `php artisan migrate` against the intended database. The starter
already includes the personal access token migration required by Sanctum.
Never run `migrate:fresh` against an existing application's data.

`App\Http\Controllers\UsersauController` supplies the application's callback,
account and logout handlers through a container binding, while the published
Users.au client continues to register its routes and login/registration entry
points. This avoids resolving Laravel's auth facade as a container service,
which conflicts with the Sanctum guard. Successful callbacks validate OAuth
state, regenerate the session, and keep tokens out of browser responses. Logout
requires a CSRF-protected POST, invalidates the local session and rotates its
CSRF token before redirecting to the identity service. The buttons submit
directly without a confirmation screen; the legacy GET logout URL returns 405. OAuth failures return a generic message on the login page.

Run the same checks as CI:

```bash
composer validate --strict
composer check-platform-reqs
composer audit --locked
npm ci
npm audit
npm run build
composer test
```

The integration suite uses SQLite and a fake OAuth HTTP transport with the real
Socialite provider; it needs no live credentials. CI runs on PHP 8.2, 8.4 and 8.5,
builds the production assets, and checks configuration, route and view caching.
Weekly audits and Dependabot updates help detect new advisories.

Tailwind 3's dependency chain still selects older `postcss-selector-parser`
versions. The npm override selects patched `^7.1.7` for that chain, with the
existing forms/typography plugins verified by the production build. Recheck
this override when those packages adopt a patched parser themselves. Do not
suppress advisories or disable Composer's security blocking to install an
outdated dependency tree.

## Requirements

- PHP 8.2–8.5
- Composer 2.9 or later
- Node.js 22.23.4+ or 24.22.1+ within those LTS majors, with npm (`nvm use`)
- MySQL/PostgreSQL database (SQLite is used by the integration tests)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Users-au/laravel-starter-kit.git
   cd laravel-starter-kit
   ```

### Automatic installation steps:

**Run the installation script**
   ```bash
   bash ./install.sh
   ```

   The script creates `.env` only if missing, installs the committed lockfiles,
   and preserves an existing application key. It stops if installation or the
   asset build fails. Back up an existing application before upgrading.

   This script will:
   - Install PHP dependencies using Composer
   - Install Node.js dependencies and build assets
   - Set up `.env` from `.env.example` and generate a key only when missing

   > **Note**: Ensure you have `bash` installed on your system to run the script.

### Manual installation steps:

1. **Set up the environment and install PHP dependencies**
   ```bash
   test -f .env || cp .env.example .env
   composer install
   ```

1. **Install Node.js dependencies**
   ```bash
   nvm use
   npm ci
   npm run build
   # or for development
   npm run dev
   ```
   
1. **Environment setup**
   ```bash
   # Only for a NEW application with an empty APP_KEY. Never rotate an existing key.
   php artisan key:generate
   ```
   
### Database Setup

1. **Set up the database**
   - Create a new database in MySQL
   - Update your `.env` file with the database connection details:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=   
   ```

1. **Run database migrations**
   ```bash
   php artisan migrate
   ```

1. **Start the development server**
   ```bash
   php artisan serve
   ```

## Usersau OAuth Setup

### 1. Register Your Application

1. Go to [Usersau Developer Console](https://my.users.au)
1. Go to / Create a new Project and go to Integration > OAuth Management
1. Create a new OAuth application, fill in the redirect URI as: `http://localhost:8000/auth/usersau/callback`
1. View your application details and **copy the Client ID and Client Secret**. 

### 2. Configure Environment Variables

Add the following to your `.env` file:

```env
USERSAU_CLIENT_ID=your_client_id_here
USERSAU_CLIENT_SECRET=your_client_secret_here
USERSAU_REDIRECT_URI=http://localhost:8000/auth/usersau/callback
USERSAU_HOST=https://your-subdomain.users.au
```

`USERSAU_HOST` is the identity service origin; `USERSAU_REDIRECT_URI` is the
callback on your application. This starter uses the Laravel client package;
check its [scope requirements](https://github.com/Users-au/laravel-client#configuration)
against your OAuth registration.

## Visit

After completing the setup, you can visit your application at `http://localhost:8000` to see the login page.

## Usage

### Available Routes

The following routes are automatically registered by the Usersau package:

- `usersau.login` - Redirect to Usersau for authentication
- `usersau.register` - Redirect to Usersau for registration
- `usersau.logout` - URL for a CSRF-protected POST logout form (GET returns 405)
- `usersau.account` - Redirect to Usersau account management
- Callback route - Handles OAuth callback from Usersau

### Authentication Flow

1. Users click "Sign in with Usersau" on the login page
2. They are redirected to Usersau for authentication
3. After successful authentication, they are redirected back to your app
4. User data is automatically synced with your local database

### User Model

The User model includes the following Usersau-related fields:

- `usersau_id` - Usersau user ID
- `usersau_access_token` - OAuth access token
- `usersau_refresh_token` - OAuth refresh token

### Customization

#### Redirect URLs

You can customize redirect URLs in `config/usersau.php`:

```php
return [
    'after_login_url' => '/dashboard',
    'after_logout_url' => '/',
    'after_register_url' => '/welcome',
    // ...
];
```

#### Blade Components

The starter kit includes reusable Blade components:

- `<x-authentication-card>` - Authentication wrapper
- `<x-application-logo>` - Application logo component

#### Adding Interactive Features

Use Alpine.js for simple interactions:

```html
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open">Content</div>
</div>
```

## File Structure

```
laravel-starter-kit/
├── app/
│   └── Models/
│       └── User.php                 # User model with Usersau fields
├── config/
│   ├── services.php                 # Usersau OAuth configuration
│   └── usersau.php                  # Usersau package configuration
├── database/
│   └── migrations/
│       └── *_add_usersau_columns_to_users_table.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php        # Main layout
│   │   │   └── navigation.blade.php # Navigation layout
│   │   ├── components/
│   │   │   ├── authentication-card.blade.php
│   │   │   └── application-logo.blade.php
│   │   ├── auth/
│   │   │   └── login.blade.php      # Login page
│   │   ├── dashboard.blade.php      # Dashboard page
│   │   └── welcome.blade.php        # Welcome page
│   ├── css/
│   │   └── app.css                  # Main CSS file
│   └── js/
│       ├── app.js                   # Main JavaScript file
│       └── bootstrap.js             # JavaScript bootstrap
└── routes/
    └── web.php                      # Web routes
```

## Technology Stack

- **Backend**: Laravel 12, Laravel Sanctum
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Authentication**: Usersau OAuth 2.0
- **Build Tools**: Vite

## Choose your integration

| Project | Use it when you want to… |
| --- | --- |
| [Next.js starter](https://github.com/Users-au/nextjs-starter-kit) | Start a Next.js app with server-managed OAuth sessions and a protected dashboard. |
| [**Laravel starter — this repo**](https://github.com/Users-au/laravel-starter-kit) | Start a Laravel app with Blade templates and Users.au integration. |
| [Laravel client](https://github.com/Users-au/laravel-client) | Add local user synchronization and authentication routes to a Laravel app. |
| [Socialite provider](https://github.com/Users-au/socialite-provider) | Connect an existing Laravel Socialite authentication flow to Users.au. |
| [Frontend client](https://github.com/Users-au/frontend-client) | Use public-client OAuth with PKCE in JavaScript, Vue or React. |

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please contact [opensource@users.au](mailto:opensource@users.au) or create an issue in the repository.
