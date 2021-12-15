#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
$PREFIX docker exec -it manageLeisure php ./bin/phpunit --configuration phpunitUnit.xml
