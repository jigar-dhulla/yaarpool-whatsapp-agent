# syntax=docker/dockerfile:1.7

ARG WACLI_VERSION=0.9.2

FROM --platform=$BUILDPLATFORM debian:bookworm-slim AS wacli-fetch
ARG TARGETARCH
ARG WACLI_VERSION
RUN apt-get update \
    && apt-get install -y --no-install-recommends curl ca-certificates \
    && curl -fsSL \
        "https://github.com/openclaw/wacli/releases/download/v${WACLI_VERSION}/wacli-linux-${TARGETARCH}.tar.gz" \
        | tar -xz -C /tmp wacli \
    && chmod +x /tmp/wacli

FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libsqlite3-dev \
        tini \
    && docker-php-ext-install pdo_sqlite pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY --from=wacli-fetch /tmp/wacli /usr/local/bin/wacli

ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /app

ENTRYPOINT ["/usr/bin/tini", "--"]
CMD ["php", "-a"]
