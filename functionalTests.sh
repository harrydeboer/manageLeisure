#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
$PREFIX docker exec -it manageLeisure php ./vendor/bin/paratest -p4 --configuration phpunitFunctional.xml
