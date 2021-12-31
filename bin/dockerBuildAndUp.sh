#!/bin/bash
set -e

docker-compose -f docker-compose.yml -f docker-compose.live.yml -f docker-compose.{APP_ENV}.yml build --no-cache
docker-compose -f docker-compose.yml -f docker-compose.live.yml -f docker-compose.{APP_ENV}.yml up -d --remove-orphans
docker cp /var/www/letsencrypt manageLeisure:/etc
docker-compose -f docker-compose.yml -f docker-compose.live.yml -f docker-compose.{APP_ENV}.yml up -d --remove-orphans
