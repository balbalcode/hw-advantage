#!/bin/sh

echo "Installing composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "Installing npm dependencies..."
npm install --legacy-peer-deps

echo "Building assets..."
npm run build

echo "Caching config..."
php artisan config:cache
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
