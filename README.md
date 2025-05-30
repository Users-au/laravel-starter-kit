# Laravel Usersau Starter Kit (Blade Templates)

A Laravel starter kit with Usersau OAuth integration, built with traditional Blade templates and Tailwind CSS.

## Features

- 🔐 **Usersau OAuth Authentication** - Complete OAuth integration with Usersau
- 🎨 **Modern UI** - Built with Tailwind CSS and Alpine.js
- 📄 **Blade Templates** - Traditional server-side rendering with Laravel's Blade engine
- 🛡️ **Laravel Sanctum** - API authentication support
- 📱 **Responsive Design** - Mobile-first responsive design
- ⚡ **Alpine.js** - Lightweight JavaScript framework for interactive components

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL database

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd laravel-usersau-starter-kit
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp env.example .env
   php artisan key:generate
   ```

5. **Configure your database**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Configure Usersau OAuth**
   Update your `.env` file with Usersau credentials:
   ```env
   USERSAU_CLIENT_ID=your_client_id
   USERSAU_CLIENT_SECRET=your_client_secret
   USERSAU_REDIRECT_URI=http://localhost:8000/auth/usersau/callback
   USERSAU_HOST=https://usersau.com
   ```

7. **Run database migrations**
   ```bash
   php artisan migrate
   ```

8. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

## Usersau OAuth Setup

### 1. Register Your Application

1. Go to [Usersau Developer Console](https://usersau.com/developers)
2. Create a new OAuth application
3. Set the redirect URI to: `http://localhost:8000/auth/usersau/callback`
4. Note down your Client ID and Client Secret

### 2. Configure Environment Variables

Add the following to your `.env` file:

```env
USERSAU_CLIENT_ID=your_client_id_here
USERSAU_CLIENT_SECRET=your_client_secret_here
USERSAU_REDIRECT_URI=http://localhost:8000/auth/usersau/callback
USERSAU_HOST=https://usersau.com
```

### 3. Available Routes

The following routes are automatically registered by the Usersau package:

- `usersau.login` - Redirect to Usersau for authentication
- `usersau.register` - Redirect to Usersau for registration
- `usersau.logout` - Logout from Usersau
- `usersau.account` - Redirect to Usersau account management
- Callback route - Handles OAuth callback from Usersau

## Usage

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

- **Backend**: Laravel 10, Laravel Sanctum
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Authentication**: Usersau OAuth 2.0
- **Build Tools**: Vite

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please contact [support@example.com](mailto:support@example.com) or create an issue in the repository. 