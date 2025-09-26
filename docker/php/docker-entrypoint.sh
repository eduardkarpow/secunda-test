#!/bin/bash

sleep 15

echo "Running Laravel migrations..."
php artisan migrate --force

echo "Running Laravel seeders..."
php artisan db:seed --force

exec "$@"