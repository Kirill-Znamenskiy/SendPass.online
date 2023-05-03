FROM php:8.1-fpm

ARG ENV
ENV ENV $ENV
ARG WRK_USER_ID=1111
ARG WRK_GROUP_ID=1111
ENV WRK_USER_ID $WRK_USER_ID
ENV WRK_GROUP_ID $WRK_GROUP_ID

RUN set -xe \
    && apt-get update \
    && apt-get install --no-install-recommends --no-install-suggests -y \
        git \
        less \
        procps \
        anacron \
    && apt-get remove --purge --auto-remove -y \
    && rm -rf /var/lib/apt-get/lists/* /var/lib/apt/lists/* /tmp/* /usr/share/man/?? /usr/share/man/??_* \
    # finish
    && set +xe

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN set -xe \
    # create work user and group
    && addgroup --gid "${WRK_GROUP_ID}" wrkgroup \
    && adduser \
        --disabled-password \
        --shell "/sbin/nologin" \
        --home "/nonexistent" \
        --no-create-home \
        --uid "${WRK_USER_ID}" \
        --ingroup wrkgroup \
        --gecos "" \
        wrkuser \
    # create work directory
    && mkdir -p /wrkdir \
    && chown wrkuser:wrkgroup -R /wrkdir \
    \
    # use one of default php.ini configuration (production or development)
    && cp "$PHP_INI_DIR/php.ini-$( [ "$ENV" = "PROD" ] && echo "production" || echo "development" )" "$PHP_INI_DIR/php.ini" \
    \
    # remove all predefined php-fpm confs
    && rm -f /usr/local/etc/php-fpm.d/* \
    # finish
    && set +xe

COPY ./docker-compose/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf



## copy composer.(json|lock) files for dependencies layer caching
#COPY --chown=wrkuser:wrkgroup ./composer.* ./
## install composer dependencies (autoloader MUST be generated later!)
#RUN set -xe \
#    && composer install --no-interaction --no-dev --no-cache --no-ansi --no-autoloader --no-scripts --prefer-dist \
#    && set +xe
#
## copy application sources into image (completely)
#COPY --chown=wrkuser:wrkgroup . .
#
#
#RUN set -xe \
#    # generate composer autoloader and trigger scripts
#    && composer dump-autoload --no-interaction --optimize \
#    \
#    && chmod u+x ./artisan \
#    && chmod 0775 -R \
#    \
#    && find ./runtime -type d -exec chmod 0775 {} \; \
#    && find ./runtime -type f -exec chmod 0664 {} \; \
#    \
#    && find ./storage -type d -exec chmod 0775 {} \; \
#    && find ./storage -type f -exec chmod 0664 {} \; \
#    \
#    && find ./bootstrap/cache -type d -exec chmod 0775 {} \; \
#    && find ./bootstrap/cache -type f -exec chmod 0664 {} \; \
#    \
#    && ./artisan config:clear \
#    # // force config:clear
#    && rm ./bootstrap/cache/config.php \
#    && ./artisan config:cache \
#    \
#    && ./artisan route:clear \
#    # not able closures routes, only controller based routes!
#    && ./artisan route:cache \
#    && set +xe


WORKDIR /wrkdir
USER wrkuser:wrkgroup
ENTRYPOINT ["/bin/sh", "-xc"]
#CMD [ "/bin/sleep", "777d" ]
#CMD [ "/usr/local/sbin/php-fpm", "-F" ]
CMD [ "set -xe \
    && cd /wrkdir/sites/SendPass.online \
    \
    && mkdir -p ./runtime \
    && find ./runtime -type f -exec chmod 0664 {} \; \
    && find ./runtime -type d -exec chmod 0775 {} \; \
    \
    && mkdir -p ./storage \
        ./storage/framework \
        ./storage/framework/logs \
        ./storage/framework/cache \
        ./storage/framework/views \
        ./storage/framework/sessions \
    \
    && find ./storage -type f -exec chmod 0664 {} \; \
    && find ./storage -type d -exec chmod 0775 {} \; \
    \
    && mkdir -p ./bootstrap/cache \
    && find ./bootstrap/cache -type f -exec chmod 0664 {} \; \
    && find ./bootstrap/cache -type d -exec chmod 0775 {} \; \
    \
    && ./artisan config:clear \
    && rm ./bootstrap/cache/config.php || true \
    && ./artisan config:cache \
    \
    && ./artisan route:clear \
    && ./artisan route:cache \
    \
    && /usr/local/sbin/php-fpm -F \
    && set +xe \
" ]
