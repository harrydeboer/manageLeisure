#!/bin/bash
set -e

./dockerBuildAndUp.sh
PREFIX="docker exec -t manageLeisure"
docker exec -t --user=www-data manageLeisure composer install --no-dev --no-progress --prefer-dist
$PREFIX php bin/console cache:clear
./opcacheReset.sh
until $PREFIX php bin/console doctrine:migrations:migrate --no-interaction
do
  echo "Try again"
  sleep 1
done
docker system prune -f
