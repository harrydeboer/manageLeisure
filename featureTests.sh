#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
$PREFIX docker exec -it myLife php ./vendor/bin/phpunit --configuration phpunitFeature.xml
