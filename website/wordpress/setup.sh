#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR"

if [[ ! -f .env ]]; then
  cp .env.example .env
  echo "Created .env from .env.example"
fi

echo "Starting WordPress stack..."
docker compose up -d

echo "Waiting for WordPress to become ready..."
for i in $(seq 1 60); do
  if curl -sf "http://localhost:${WORDPRESS_PORT:-8080}" >/dev/null 2>&1; then
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
docker exec "$CONTAINER" bash -lc '
  cd /var/www/html
  if ! wp core is-installed --allow-root 2>/dev/null; then
    wp core install \
      --url="http://localhost:8080" \
      --title="SealBridge Supply" \
      --admin_user="admin" \
      --admin_password="sealbridge_admin" \
      --admin_email="admin@sealbridgesupply.local" \
      --skip-email \
      --allow-root
    wp theme activate sealbridge --allow-root
    wp rewrite structure "/%postname%/" --allow-root
  fi
'

echo "Seeding pages, products, applications, and navigation..."
docker exec "$CONTAINER" bash -lc '
  php /var/www/html/wp-content/themes/sealbridge/tools/seed-secondary-pages.php
'

cat <<EOF

SealBridge Supply local site is ready.

  Site:       http://localhost:8080
  Admin:      http://localhost:8080/wp-admin
  phpMyAdmin: http://localhost:8081

  Admin user: admin
  Admin pass: sealbridge_admin

Re-run seed anytime:
  docker compose exec wordpress php wp-content/themes/sealbridge/tools/seed-secondary-pages.php
EOF
