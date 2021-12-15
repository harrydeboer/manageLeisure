#!/bin/bash
PARENT_PATH=$( cd "$(dirname "${BASH_SOURCE[0]}")" || exit ; pwd -P )
PARENT_DIR="$(basename "$PARENT_PATH")"
PUBLIC_DIR=${PARENT_PATH}/public/
RANDOM_NAME=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 100).php
echo "<?php opcache_reset(); echo 'OPcache reset!' . PHP_EOL; ?>" > "${PUBLIC_DIR}""${RANDOM_NAME}"
docker cp "${PUBLIC_DIR}""${RANDOM_NAME}" manageLeisure:/var/www/html/public/"${RANDOM_NAME}"

if [[ $PARENT_DIR = "staging.manageleisure.com" ]]; then
  curl https://staging.manageleisure.com/"${RANDOM_NAME}"
elif [[ $PARENT_DIR = "manageleisure.com" ]]; then
  curl https://manageleisure.com/"${RANDOM_NAME}"
elif [[ $PARENT_DIR = "manageLeisure" ]]; then
  curl http://manageleisure/"${RANDOM_NAME}"
fi
rm "${PUBLIC_DIR}""${RANDOM_NAME}"
docker exec -it manageLeisure rm /var/www/html/public/"${RANDOM_NAME}"
