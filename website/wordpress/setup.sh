#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

if [[ -f .env ]]; then
  # shellcheck disable=SC1091
  set -a
  source .env
  set +a
fi

LOCAL_URL="http://localhost:${WORDPRESS_PORT:-8080}"

if [[ ! -f .env ]]; then
  cp .env.example .env
  echo "Created .env from .env.example"
fi

echo "Starting WordPress stack..."
docker compose up -d

echo "Waiting for WordPress to become ready..."
for i in $(seq 1 60); do
  if curl -sf "$LOCAL_URL" >/dev/null 2>&1; then
    break
  fi
  sleep 2
done

CONTAINER="$(docker compose ps -q wordpress)"
if [[ -z "$CONTAINER" ]]; then
  echo "WordPress container not found." >&2
  exit 1
fi

# Install WP-CLI if missing.
docker exec "$CONTAINER" bash -c '
  if ! command -v wp >/dev/null 2>&1; then
    curl -sSLo /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x /usr/local/bin/wp
  fi
'

# Run WordPress install only on first boot.
docker exec "$CONTAINER" bash -lc "
  cd /var/www/html
  if ! wp core is-installed --allow-root 2>/dev/null; then
    wp core install \
      --url=\"$LOCAL_URL\" \
      --title=\"SealBridge Supply\" \
      --admin_user=\"admin\" \
      --admin_password=\"sealbridge_admin\" \
      --admin_email=\"admin@sealbridgesupply.local\" \
      --skip-email \
      --allow-root
    wp theme activate sealbridge --allow-root
    wp rewrite structure \"/%postname%/\" --allow-root
  fi
"

echo "Aligning site URL for local development..."
docker exec "$CONTAINER" bash -lc "
  cd /var/www/html
  CURRENT_URL=\$(wp option get siteurl --allow-root 2>/dev/null || true)
  if [[ \"\$CURRENT_URL\" != \"$LOCAL_URL\" ]]; then
    wp option update siteurl \"$LOCAL_URL\" --allow-root
    wp option update home \"$LOCAL_URL\" --allow-root
    wp search-replace \"https://sealbridgesupply.com\" \"$LOCAL_URL\" --all-tables --allow-root || true
    wp search-replace \"http://sealbridgesupply.com\" \"$LOCAL_URL\" --all-tables --allow-root || true
    wp rewrite flush --allow-root
  fi
"

echo "Seeding pages, products, applications, and navigation..."
docker exec "$CONTAINER" bash -lc '
  cd /var/www/html
  if ! wp post list --post_type=product --format=count --allow-root 2>/dev/null | grep -qv "^0$"; then
    php /var/www/html/wp-content/themes/sealbridge/tools/seed-secondary-pages.php
  else
    echo "Existing product content detected; skipping seed."
  fi
'

cat <<EOF

SealBridge Supply local site is ready.

  Site:       $LOCAL_URL
  Admin:      $LOCAL_URL/wp-admin
  phpMyAdmin: http://localhost:8081

  Admin user: admin
  Admin pass: sealbridge_admin

Re-run seed anytime:
  docker compose exec wordpress php wp-content/themes/sealbridge/tools/seed-secondary-pages.php
EOF
