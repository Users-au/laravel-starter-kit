#!/bin/bash

set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")"

echo "🚀 Laravel Usersau Starter Kit Installation"
echo "==========================================="

# Check if composer is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or later first."
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    exit 1
fi

# Create the environment before Composer boots the application.
if [ ! -f .env ]; then
    echo "📝 Creating environment file..."
    cp .env.example .env
    chmod 600 .env
fi

# Install the reviewed, locked dependencies.
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm ci
npm run build

# Never rotate an existing application's encryption key on a repeated install.
task_has_app_key=$(php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo empty(config("app.key")) ? "no" : "yes";
')
if [ "$task_has_app_key" = "no" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --no-interaction
else
    echo "🔑 Preserving the existing application key."
fi

echo ""
echo "✅ Installation completed!"
echo ""
echo "Next steps:"
echo "1. Configure your database in .env file"
echo "2. Configure Usersau OAuth credentials in .env file:"
echo "   - USERSAU_CLIENT_ID=your_client_id"
echo "   - USERSAU_CLIENT_SECRET=your_client_secret"
echo "   - USERSAU_REDIRECT_URI=http://localhost:8000/auth/usersau/callback"
echo "   - USERSAU_HOST=https://your-subdomain.users.au"
echo "3. Run: php artisan migrate"
echo "4. Run: npm run dev (for development) or npm run build (for production)"
echo "5. Run: php artisan serve"
echo ""
echo "🎉 Happy coding!"
