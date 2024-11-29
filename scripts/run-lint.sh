#!/bin/bash
set -e
cd "$(dirname "$0")"

docker exec -e PHP_CS_FIXER_IGNORE_ENV=1 memorialisation-web ./vendor/bin/php-cs-fixer $@
