FROM php:8.1-fpm AS base

ARG ENV
ENV ENV $ENV
ARG WRK_USER_ID=1111
ARG WRK_GROUP_ID=1111
ENV WRK_USER_ID $WRK_USER_ID
ENV WRK_GROUP_ID $WRK_GROUP_ID

ARG GIT_SHOW_VERSION
ENV GIT_SHOW_VERSION $GIT_SHOW_VERSION

RUN set -xe \
    && apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
        git \
        less \
        procps \
        anacron \
        libpq-dev \
    \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && apt-get remove -y --purge --auto-remove \
    && rm -rf /var/lib/apt-get/lists/* /var/lib/apt/lists/* /tmp/* /usr/share/man/?? /usr/share/man/??_* \
    && set +xe


COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN set -xe \
    # create work user and group
    && addgroup --gid "${WRK_GROUP_ID}" wrkgroup \
    && adduser \
        --disabled-password \
        --shell "/sbin/nologin" \
        --home "/wrkdir" \
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

RUN set -xe \
    && sed -i "s/{LISTEN_LINE}/listen = ./runtime/php-fpm.$ENV.sock/" /usr/local/etc/php-fpm.d/www.conf \
    && set +xe


WORKDIR /wrkdir
USER root:root
ENTRYPOINT ["/bin/sh", "-xec"]
CMD [ "/bin/true \
    && chown wrkuser:wrkgroup -R /wrkdir \
    && cd /wrkdir/sites/SendPass.online \
    && su -s /bin/bash wrkuser init.bash \
    \
    && /bin/sleep 777d \
" ]

############################################################################
FROM base AS work
WORKDIR /wrkdir
USER wrkuser:wrkgroup
ENTRYPOINT []
#CMD [ "/bin/sleep 777d" ]
CMD [ "/usr/local/sbin/php-fpm", "-F" ]
#CMD [ "/bin/true \
#    && chown wrkuser:wrkgroup -R /wrkdir \
#    && cd /wrkdir/sites/SendPass.online \
#    && su -s /bin/bash wrkuser init.bash \
#    \
#    && php-fpm -F \
#" ]
