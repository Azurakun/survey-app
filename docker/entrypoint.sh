#!/bin/sh
set -e

# Copy .env if not exists
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
    php artisan key:generate --force
fi

# Ensure sqlite DB file exists
INITIAL_SETUP=false
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    INITIAL_SETUP=true
fi
chown -R www-data:www-data /var/www/html/database

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and optimization
php artisan storage:link || true
php artisan migrate --force || true

# Only seed on first initial setup
if [ "$INITIAL_SETUP" = "true" ]; then
    php artisan db:seed --force || true
fi

php artisan view:clear || true
php artisan config:clear || true

# Start Supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
