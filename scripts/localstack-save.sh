#!/bin/bash
set -e
cd "$(dirname "$0")"

# uses the localstack-bucket.sh script in the same folder to save and restore your projects S3 buckets.
# set parameters in the .env
# only runs if APP_ENV=local


source ../.env

if [[ $APP_ENV == "local" ]]; then
    echo "Attempting to syncronise changes from \"$LOCALSTACK_BUCKET_NAME\" to \"$LOCALSTACK_TEMP_STOREAGE_PATH\"  "
    ./localstack-bucket.sh "save" "$LOCALSTACK_CONTAINER_NAME" "$LOCALSTACK_BUCKET_NAME" "$LOCALSTACK_TEMP_STOREAGE_PATH"
else
    echo "Non local/dev environment, Aborted"
fi 


