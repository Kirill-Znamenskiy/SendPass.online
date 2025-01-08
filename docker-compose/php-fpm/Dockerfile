FROM php:8.1-fpm AS base

ARG BUILD_ENV
ENV BUILD_ENV=$BUILD_ENV

ARG BUILD_WRK_USER_ID
ARG BUILD_WRK_GROUP_ID
ENV BUILD_WRK_USER_ID=$BUILD_WRK_USER_ID
ENV BUILD_WRK_GROUP_ID=$BUILD_WRK_GROUP_ID

ARG BUILD_GIT_SHOW_VERSION
ENV BUILD_GIT_SHOW_VERSION=$BUILD_GIT_SHOW_VERSION

RUN set -xe \
    && apt-get update \
    && apt-get install -y --no-install-recommends --no-install-suggests \
        git \
        less \
        sudo \
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
    && addgroup --gid "${BUILD_WRK_GROUP_ID}" wrkgroup \
    && adduser \
        --disabled-password \
        --shell "/sbin/nologin" \
        --home "/wrkdir" \
        --no-create-home \
        --uid "${BUILD_WRK_USER_ID}" \
        --ingroup wrkgroup \
        --gecos "" \
        wrkuser \
    # create work directory
    && mkdir -p /wrkdir \
    && chown wrkuser:wrkgroup -R /wrkdir \
    \
    # use one of default php.ini configuration (production or development)
    && cp "$PHP_INI_DIR/php.ini-$( [ "$BUILD_ENV" = "PROD" ] && echo "production" || echo "development" )" "$PHP_INI_DIR/php.ini" \
    \
    # remove all predefined php-fpm confs
    && rm -f /usr/local/etc/php-fpm.d/* \
    # finish
    && set +xe

COPY ./docker-compose/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN set -xe \
    && sed -i "s|{LISTEN_LINE}|listen = ./runtime/php-fpm.$BUILD_ENV.sock|g" /usr/local/etc/php-fpm.d/www.conf \
    && set +xe


WORKDIR /wrkdir
USER root:root
ENTRYPOINT ["/bin/sh", "-xec"]
CMD [ "true \
    && chown wrkuser:wrkgroup -R /wrkdir \
    && ln -svf /run/secrets/env-prod-env /wrkdir/sites/SendPass.online/.env \
    && sleep infinity \
" ]


############################################################################
FROM base AS work
WORKDIR /wrkdir
USER wrkuser:wrkgroup
ENTRYPOINT ["/bin/sh", "-xec"]
#CMD [ "/bin/sleep 777d" ]
#CMD [ "/usr/local/sbin/php-fpm", "-F" ]
CMD [ "true \
    && cd /wrkdir/sites/SendPass.online \
    && bash init.bash \
    \
    && php-fpm -F \
" ]
