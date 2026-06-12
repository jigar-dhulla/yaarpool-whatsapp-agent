#!/usr/bin/env bash
# Pulled image rollout for the production VPS.
# Synced and invoked by GitHub Actions over SSH after a successful publish-image build.

set -euo pipefail

cd "$(dirname "$(realpath "$0")")"

export COMPOSE_FILE=docker-compose.prod.yml

echo "==> Validating compose file"
docker compose config --quiet

echo "==> Pulling latest images"
docker compose pull --quiet

echo "==> Running migrations"
docker compose run --rm app php artisan migrate --force

echo "==> Recreating services"
docker compose up -d --remove-orphans

echo "==> Pruning dangling images older than 24h"
docker image prune -f --filter "until=24h" >/dev/null

echo "==> Status"
docker compose ps
