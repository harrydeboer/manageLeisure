#!/bin/bash
if [[ ${OSTYPE} == 'msys' ]]; then
  PREFIX=winpty
else
  PREFIX=""
fi
NUMBER_OF_CPUS=$(nproc)
if [[ ${NUMBER_OF_CPUS} -lt 4 ]]; then
  PROCESSES=$NUMBER_OF_CPUS
else
  PROCESSES=4
fi
$PREFIX docker exec -it manageLeisure php ./vendor/bin/paratest -p$PROCESSES --configuration phpunitFunctional.xml
