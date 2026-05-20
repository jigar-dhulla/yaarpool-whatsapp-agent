#!/usr/bin/env bash
# /opt/yaarpool/deploy.sh — pulled image rollout for production VPS.
# Invoked by GitHub Actions over SSH after a successful publish-image build.

set -euo pipefail

cd /opt/yaarpool

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
