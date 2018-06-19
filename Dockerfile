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

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN composer install --no-dev

EXPOSE 80

CMD ["/sbin/runit-wrapper"]