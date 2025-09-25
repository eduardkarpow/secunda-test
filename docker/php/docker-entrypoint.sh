#!/bin/bash

# Wait for MySQL to be ready (optional but recommended)
# You might need to adjust the sleep time or use a more robust check
sleep 15

# Run migrations
echo "Running Laravel migrations..."
php artisan migrate --force

# Run seeders (if needed)
echo "Running Laravel seeders..."
php artisan db:seed --force

# Execute the main container command (e.g., PHP-FPM)
exec "$@"