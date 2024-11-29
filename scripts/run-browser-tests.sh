#!/bin/bash
#
# some suggested parameters to pass to this shell script
# FYI they are all PHPUnit parameters
# --testdox   it will print out the name of each test as it executes
# --stop-on-error   will abort testing after an error occurs
# --stop-on-failure   will abort testing after a test fails
# --order-by=random   randomise the order that tests are run and print the random seed
# --random-order-seed=1689087175   allows you to set the random seed for runs
# --group=vm260   will only run the tests marked with the @group vm260
set -e
cd "$(dirname "$0")"

docker exec memorialisation-web ./artisan dusk:chrome-driver 90.0.4430.24
docker exec memorialisation-web ./artisan dusk $@
