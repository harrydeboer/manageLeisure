#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
$PREFIX docker exec -it myLife php ./bin/phpunit --configuration phpunitFunctional.xml
