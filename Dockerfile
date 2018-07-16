FROM phpearth/php:7.1-nginx

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
    DEPS="php7.1-pdo \
          php7.1-pdo_sqlite \
          php7.1-apcu"

RUN set -x \
    && apk add --no-cache $DEPS

COPY docker/ /

COPY src/server.php /var/www/html/src/server.php
COPY src/posts.php /var/www/html/src/posts.php
COPY index.php /var/www/html
RUN sed -i 's#\$rss.*#\$rss = new server("/data/rss.db");#' /var/www/html/index.php
COPY rss.db /var/www/html
COPY composer.json /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN COMPOSER_CACHE_DIR=/dev/null composer install -d /var/www/html/ --no-dev

RUN chown -R www-data /var/www/html
RUN mkdir /data
RUN chown -R www-data /data

EXPOSE 80

VOLUME /data

CMD ["/sbin/runit-wrapper"]