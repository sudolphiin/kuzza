#!/bin/bash
# One-time fix: set storage and bootstrap/cache so BOTH your user and www-data can write.
# Owner = you (so artisan/composer work from CLI), group = www-data (so the web server can write).
# Run with: sudo ./fix-storage-once.sh

set -e
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Same logic as fix-permissions.sh: owner = user who ran sudo, group = www-data
OWNER="${SUDO_USER:-$USER}"
if [ "$OWNER" = "root" ]; then
  OWNER="www-data"
fi
GROUP="www-data"

echo "Setting ownership to $OWNER:$GROUP (you + web server can write)..."
chown -R "$OWNER:$GROUP" storage bootstrap/cache

echo "Setting directory permissions (2775 with setgid)..."
find storage bootstrap/cache -type d -exec chmod 2775 {} \;

echo "Setting file permissions (664)..."
find storage bootstrap/cache -type f -exec chmod 664 {} \;

echo "Ensuring subdirs exist..."
mkdir -p storage/framework/{sessions,views,cache/data}
mkdir -p storage/logs
mkdir -p storage/app/public
chown -R "$OWNER:$GROUP" storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 2775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;

echo "Done. $OWNER and www-data can both write to storage and bootstrap/cache."
