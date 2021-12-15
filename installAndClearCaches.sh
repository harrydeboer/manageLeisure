#!/bin/bash
docker-compose -f docker-compose.yml -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans
docker cp /home/letsencrypt manageLeisure:/etc/letsencrypt
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans
PREFIX="docker exec -t manageLeisure"
docker exec -t --user=www-data manageLeisure composer install --no-dev --no-progress --prefer-dist
$PREFIX php bin/console cache:clear
./opcacheReset.sh
until $PREFIX php bin/console doctrine:migrations:migrate
do
  echo "Try again"
  sleep 1
done
docker system prune -f
