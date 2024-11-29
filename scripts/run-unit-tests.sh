#!/bin/bash
set -e
cd "$(dirname "$0")"
../aws/create_bucket.sh
docker exec memorialisation-web ./artisan migrate --database=mysql_testing --force
docker exec memorialisation-web ./artisan dusk:chrome-driver --detect
docker exec memorialisation-web ./vendor/bin/phpunit $@
docker exec memorialisation-web ./vendor/bin/coverage-check clover.xml 12
