#!/usr/bin/env bash
shopt -s expand_aliases

DEFAULT_REGION=eu-west-2
LOCALSTACK_HOST=localhost

alias awslocal="AWS_ACCESS_KEY_ID=test AWS_SECRET_ACCESS_KEY=test AWS_DEFAULT_REGION=${DEFAULT_REGION:-$AWS_DEFAULT_REGION} aws --endpoint-url=http://${LOCALSTACK_HOST:-localhost}:4566"

# This command will not wipe out any contents in an exisiting bucket.
if awslocal s3 ls s3://vivedia-bucket; then
    echo "Bucket already exists"
else
    echo "Creating bucket"
     awslocal s3 mb s3://vivedia-bucket
fi
