FROM ghcr.io/immediatemediaco/php:8.2-fpm AS app

ARG COMPOSER_AUTH="{}"

ENV APP_ENV=prod COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data app/composer.* app/symfony.lock ./

RUN set -xe; \
    [ ! -d ~/.composer ] && mkdir ~/.composer; \
    echo $COMPOSER_AUTH >> ~/.composer/auth.json; \
    apk --update add --no-cache git; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress --no-suggest --ignore-platform-reqs --no-interaction; \
    composer clear-cache;

COPY --chown=www-data:www-data app ./

RUN composer dump-autoload -a; \
    composer run-script --no-dev post-install-cmd; \
    composer dump-env prod; \
    chmod -R 777 var;

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Version information needs to be accessible at the container level
ARG APP_VERSION=dev \
    LAST_COMMIT_DATE="Thu Jan 1 00:00:00 1970 +0000" \
    BUILD_START_TIME=0
ENV APP_VERSION=$APP_VERSION \
    LAST_COMMIT_DATE=$LAST_COMMIT_DATE \
    BUILD_START_TIME=$BUILD_START_TIME

USER www-data

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM app AS app_dev

USER root
