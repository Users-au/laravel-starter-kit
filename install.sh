#!/bin/bash

echo "🚀 Laravel Usersau Starter Kit Installation"
echo "==========================================="

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    exit 1
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Creating environment file..."
    cp env.example .env
else
    echo "⚠️  .env file already exists, skipping..."
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

echo ""
echo "✅ Installation completed!"
echo ""
echo "Next steps:"
echo "1. Configure your database in .env file"
echo "2. Configure Usersau OAuth credentials in .env file:"
echo "   - USERSAU_CLIENT_ID=your_client_id"
echo "   - USERSAU_CLIENT_SECRET=your_client_secret"
echo "   - USERSAU_REDIRECT_URI=http://localhost:8000/auth/usersau/callback"
echo "   - USERSAU_HOST=https://usersau.com"
echo "3. Run: php artisan migrate"
echo "4. Run: npm run dev (for development) or npm run build (for production)"
echo "5. Run: php artisan serve"
echo ""
echo "🎉 Happy coding!" 