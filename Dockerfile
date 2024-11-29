FROM 742195923217.dkr.ecr.eu-west-2.amazonaws.com/memorialisation-base:latest
# Switch in to root while installing New Relic, swaps back to vivedia afterwards
USER root

# Pull in BUILD_REVISION from github (if available)
ARG BUILD_REVISION="${BUILD_REVISION}"

ARG NR_KEY="${NR_KEY}"
ARG NR_APP_REF="${NR_APP_REF}"

RUN if [ ! -z "$NR_KEY" ]; then \
  echo 'deb http://apt.newrelic.com/debian/ newrelic non-free' | tee /etc/apt/sources.list.d/newrelic.list; \
  curl https://download.newrelic.com/548C16BF.gpg | apt-key add - ;\
  apt-get update; \
  apt-get -y install newrelic-php5; \
  fi

RUN if [ ! -z "$NR_KEY" ]; then \
  NR_INSTALL_SILENT=1 newrelic-install install; \
  fi

RUN if [ ! -z "$NR_KEY" ]; then \
  sed -i -e "s/REPLACE_WITH_REAL_KEY/${NR_KEY}/" \
    -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname=\"${NR_APP_REF}\"/" \
    $(php -r "echo(PHP_CONFIG_FILE_SCAN_DIR);")/newrelic.ini; \
  fi


# TODO: local vs development is confusing and the default below is not sane
ARG APP_ENV="development"
ARG NODE_ENV="development"
ENV APP_ENV="${APP_ENV}" \
    NODE_ENV="${NODE_ENV}" \
    USER="vivedia" \
    BUILD_REVISION="${BUILD_REVISION}"

RUN if [ "${APP_ENV}" = "local" ]; then \
  pecl install xdebug && docker-php-ext-enable xdebug; fi

ADD ./docker/php/conf.d/xdebug.ini /tmp/docker-php-ext-xdebug.ini
RUN if [ "${APP_ENV}" = "local" ]; then \
  mv /tmp/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
  else \
  rm /tmp/docker-php-ext-xdebug.ini; fi

# TODO: remove the following RUN if not required.
# as suggested by https://stackoverflow.com/a/71372749/799759
RUN BUILD_REVISION=$BUILD_REVISION        

COPY --chown=vivedia:vivedia package* ./
RUN npm install --also=dev

COPY --chown=vivedia:vivedia . .

# Build assets if not dev
RUN if [ "${APP_ENV}" != "local" ]; then \
  npm run production; fi

# Optimised dependencies etc for production, normal for local dev
RUN if [ "${APP_ENV}" = "local" ]; then \
  composer install; else \
  composer install --optimize-autoloader --no-dev; fi

EXPOSE 8080

CMD ["./run.sh"]
