#!/usr/bin/env bash

SCRIPT_DIR="$(dirname "$0")"
ROOT="$(cd "$SCRIPT_DIR/../../../../" || exit;pwd)"
APP_ROOT="$(cd "$ROOT" || exit;pwd)"

PG_CONTAINER="taygeta/pgpro:14"
PG_USER="admin"
PG_USER_PASS="somepswd"
PG_DB="app_db"
PG_DATA_PATH="/var/lib/pgpro/std-14/data"

DOCKER_VOLUME="database_data"

echo "Creating: DB user"

if [ "$(docker volume inspect $DOCKER_VOLUME 2>/dev/null)" == "[]" ]; then
  PG_CONTAINER_ID="$(docker run -itd -v $DOCKER_VOLUME:$PG_DATA_PATH $PG_CONTAINER)"
  sleep 2 # wait PG warming-up

  SQL="CREATE USER $PG_USER WITH ENCRYPTED PASSWORD '$PG_USER_PASS'"
  docker exec -it -u postgres "$PG_CONTAINER_ID" psql -c "$SQL"
  echo "DB user created - OK"

  SQL="CREATE DATABASE $PG_DB"
  docker exec -it -u postgres "$PG_CONTAINER_ID" psql -c "$SQL"
  echo "Database created - OK"

  SQL="GRANT ALL PRIVILEGES ON DATABASE $PG_DB TO $PG_USER"
  docker exec -it -u postgres "$PG_CONTAINER_ID" psql -c "$SQL"
  echo "DB user granted - OK"

  docker stop "$PG_CONTAINER_ID" >/dev/null
  docker rm "$PG_CONTAINER_ID" >/dev/null

  echo "DB initialized - OK"
else
  echo "Skipped: previous DB docker volume discovered as [$DOCKER_VOLUME]"
fi

echo "Copying: .env.local"

FILE="$APP_ROOT/.env.local"
if [ ! -f "$FILE" ]; then
  cp "$SCRIPT_DIR/../../env/.env.local" "$APP_ROOT"
  echo "Copied - OK"
else
  echo "Skipped: previous .env.local discovered"
fi
