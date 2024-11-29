#!/bin/bash

# Save and Restore localstack S3 file buckets.

set -e

function display_help() {
    echo 
    echo "usage: $0 <action> <contatiner-name> <bucket-name> <path>"
    echo "actions: save restore;"
    echo 
    echo "Save and restore a localstack s3 bucket from a running container"
}

function container_running() {
    NAME=$1
}

function do_save() {
    BUCKET_NAME=$1
    STORAGE_PATH=$2
    if [ ! -d "$STORAGE_PATH" ]; then
        mkdir -p "$STORAGE_PATH"
    fi
    awslocal s3 sync "s3://$BUCKET_NAME" "$STORAGE_PATH" --delete
}

function do_restore() {
    STORAGE_PATH=$2
    if [ ! -d "$STORAGE_PATH" ]; then
        echo "folder \"$STORAGE_PATH\" does not exist, restore abandoned"
        exit 2
    fi
    awslocal s3 sync "$STORAGE_PATH" "s3://$BUCKET_NAME" --delete
}

function check_param() {
    CHECK=$1
    NAME=$2
    if [ -z $CHECK ]; then
        echo ":<$NAME> not supplied"
        display_help
        exit 1
    fi 
}

command -v awslocal >/dev/null 2>&1 || { echo >&2 "awslocal is required but is not available.  Aborting."; exit 3; }

ACTION=$1
check_param "$ACTION" "action"

CONTAINER_NAME=$2
check_param "$CONTAINER_NAME" "container-name"

BUCKET_NAME=$3
check_param "$BUCKET_NAME" "bucket-name"

STORAGE_PATH=$4
check_param "$STORAGE_PATH" "path"

docker ps --filter name="$CONTAINER_NAME" --filter status=running --format "{{.Names}}" | grep "$CONTAINER_NAME" >/dev/null 2>&1 || { echo >&2 "container \"$CONTAINER_NAME\" is not currently running.  Aborting."; exit 4; }

awslocal s3 ls "s3://$BUCKET_NAME" >/dev/null 2>&1 || { echo >&2 "bucket name \"$BUCKET_NAME\" not found.  Aborting."; exit 6; }

case $ACTION in
    "save") do_save "$BUCKET_NAME" "$STORAGE_PATH" ;;
    "restore") do_restore "$BUCKET_NAME" "$STORAGE_PATH" ;;
    *)  echo ":unrecognised <action>"
        display_help ;;
esac
