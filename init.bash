#!/bin/bash

set -xe

if test ! -f "./init.bash"; then
    echo "Work directory '$PWD' is wrong!"
    exit
fi

composer self-update
composer install --no-interaction --no-dev --no-cache --no-ansi --no-autoloader --no-scripts --prefer-dist
composer dump-autoload --no-interaction --optimize

mkdir -p ./runtime
find ./runtime -type f -exec chmod 0664 {} \;
find ./runtime -type d -exec chmod 0775 {} \;

mkdir -p ./storage \
    ./storage/framework \
    ./storage/framework/logs \
    ./storage/framework/cache \
    ./storage/framework/views \
    ./storage/framework/sessions \
;

find ./storage -type f -exec chmod 0664 {} \;
find ./storage -type d -exec chmod 0775 {} \;

mkdir -p ./bootstrap/cache
find ./bootstrap/cache -type f -exec chmod 0664 {} \;
find ./bootstrap/cache -type d -exec chmod 0775 {} \;

php artisan view:clear
php artisan cache:clear
php artisan config:clear
rm ./bootstrap/cache/config.php || true
php artisan config:cache

php artisan route:clear
php artisan route:cache

set +xe
