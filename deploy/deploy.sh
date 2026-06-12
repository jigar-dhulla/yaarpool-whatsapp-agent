#!/usr/bin/env bash
# Pulled image rollout for the production VPS.
#
# Wired as the forced command on the GitHub Actions deploy key, so every
# connection with that key runs this script regardless of the requested
# command. The requested command (exposed by sshd as SSH_ORIGINAL_COMMAND)
# carries the git ref to deploy; it defaults to main for manual runs.
# The script first syncs itself and docker-compose.prod.yml from the
# public repo at that ref, then re-execs the fresh copy to roll out.

set -euo pipefail

cd "$(dirname "$(realpath "$0")")"

REPO="jigar-dhulla/yaarpool-whatsapp-agent"
REF="${SSH_ORIGINAL_COMMAND:-main}"

if ! [[ "$REF" =~ ^([0-9a-f]{40}|main)$ ]]; then
    echo "Refusing to deploy unrecognised ref: $REF" >&2
    exit 1
fi

if [[ "${DEPLOY_SYNCED:-0}" != "1" ]]; then
    echo "==> Syncing infra files from $REPO@$REF"
    curl -fsSL "https://raw.githubusercontent.com/$REPO/$REF/docker-compose.prod.yml" -o docker-compose.prod.yml.tmp
    curl -fsSL "https://raw.githubusercontent.com/$REPO/$REF/deploy/deploy.sh" -o deploy.sh.tmp
    mv docker-compose.prod.yml.tmp docker-compose.prod.yml
    mv deploy.sh.tmp deploy.sh
    chmod +x deploy.sh
    DEPLOY_SYNCED=1 SSH_ORIGINAL_COMMAND="$REF" exec bash deploy.sh
fi

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
