#!/bin/bash

set -euo pipefail

APP_DIR="/var/www/ci4-multiblog-cms"
BRANCH="main"

GREEN="\033[1;32m"
RED="\033[1;31m"
YELLOW="\033[1;33m"
NC="\033[0m"

log() {
  echo -e "\n${GREEN}👉 $1${NC}"
}

warn() {
  echo -e "\n${YELLOW}⚠ $1${NC}"
}

error() {
  echo -e "\n${RED}💥 $1${NC}"
}

trap 'error "Deploy failed at line $LINENO"' ERR

run() {
  echo -e "\n${YELLOW}▶ $1${NC}"
  bash -lc "$2"
}

log "START DEPLOY (GIT MODE)"

# 1. UPDATE CODE
run "Updating repository" "
cd $APP_DIR

if [ ! -d .git ]; then
  echo '❌ No git repo found. Clone project first!'
  exit 1
fi

git fetch origin
git reset --hard origin/$BRANCH
git clean -fd

echo '✔ Code updated'
"

# 2. COMPOSER INSTALL
run "Composer install" "
cd $APP_DIR

composer install \
  --no-dev \
  --optimize-autoloader \
  --no-interaction \
  --prefer-dist

echo '✔ Dependencies installed'
"

# 3. ENV SETUP
run "Environment setup" "
cd $APP_DIR

if [ ! -f .env ]; then
  cp env .env
fi

if grep -q 'CI_ENVIRONMENT' .env; then
  sed -i 's/CI_ENVIRONMENT.*/CI_ENVIRONMENT = production/' .env
else
  echo 'CI_ENVIRONMENT = production' >> .env
fi

echo '✔ ENV ready'
"

# 4. DATABASE (PostgreSQL check + migrations)
run "Database setup" "
cd $APP_DIR

DB_NAME=ci4_multiblog

echo 'Checking database...'

DB_EXISTS=\$(sudo -u postgres psql -tAc \"SELECT 1 FROM pg_database WHERE datname='\$DB_NAME'\")

if [ \"\$DB_EXISTS\" != \"1\" ]; then
  echo 'Database not found, creating...'
  sudo -u postgres createdb \$DB_NAME
else
  echo 'Database exists'
fi

echo 'Running migrations...'
php spark migrate --all --force

echo '✔ Database ready'
"

# 5. PERMISSIONS
run "Fix permissions" "
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 775 $APP_DIR/writable
"

# 6. RESTART SERVICES
run "Restart services" "
sudo systemctl restart php8.4-fpm
sudo systemctl restart nginx
"

# 7. HEALTH CHECK
log "Health check..."

HTTP_CODE=\$(curl -s -o /dev/null -w \"%{http_code}\" http://127.0.0.1/ || true)

if [ \"$HTTP_CODE\" = \"200\" ]; then
  log \"DEPLOY SUCCESS ✔ (HTTP $HTTP_CODE)\"
else
  warn \"APP MAY NOT BE HEALTHY (HTTP $HTTP_CODE)\"
fi

log "DONE"