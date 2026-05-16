#!/bin/sh
set -e
cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if ! grep -qE '^APP_KEY=base64:.+' .env 2>/dev/null; then
    php artisan key:generate --force
fi

php /var/www/html/docker/patch-env-from-compose.php

export DB_HOST="${DB_HOST:-mysql}"
export DB_PORT="${DB_PORT:-3306}"
export DB_DATABASE="${DB_DATABASE:-mini_crm}"
export DB_USERNAME="${DB_USERNAME:-mini_crm}"
export DB_PASSWORD="${DB_PASSWORD:-mini_crm}"

i=0
while [ "$i" -lt 60 ]; do
    if php -r 'try { $h=getenv("DB_HOST")?: "mysql"; $p=getenv("DB_PORT")?: "3306"; $d=getenv("DB_DATABASE")?: "mini_crm"; $u=getenv("DB_USERNAME")?: "mini_crm"; $w=getenv("DB_PASSWORD")?: ""; new PDO("mysql:host=$h;port=$p;dbname=$d", $u, $w, [PDO::ATTR_TIMEOUT => 2]); exit(0); } catch (Throwable $e) { exit(1); }' 2>/dev/null; then
        break
    fi
    i=$((i + 1))
    sleep 2
done

if [ "$i" -eq 60 ]; then
    echo "MySQL not ready." >&2
    exit 1
fi

if [ "${RUN_MIGRATE:-0}" = "1" ]; then
    php artisan migrate --force
fi

exec "$@"
