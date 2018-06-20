FROM phpearth/php:7.2-nginx

ARG BUILD_DATE
ARG VCS_REF

LABEL org.label-schema.build-date=$BUILD_DATE \
      org.label-schema.vcs-url="https://github.com/pskuza/rss-server.git" \
      org.label-schema.vcs-ref=$VCS_REF \
      org.label-schema.schema-version="1.0" \
      org.label-schema.vendor="pskuza" \
      org.label-schema.name="rss-server" \
      org.label-schema.description="Simple rss server to setup your own feed." \
      org.label-schema.url="https://github.com/pskuza/rss-server"


ENV \
    # When using Composer, disable the warning about running commands as root/super user
    COMPOSER_ALLOW_SUPERUSER=1 \
    # Persistent runtime dependencies
    DEPS="php7.2-pdo \
          php7.2-pdo_sqlite"

RUN set -x \
    && apk add --no-cache $DEPS

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY src/server.php /var/www/html/src/server.php
COPY index.php /var/www/html
COPY composer.json /var/www/html
COPY rss.db /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN composer install -d /var/www/html/ --no-dev

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["/sbin/runit-wrapper"]