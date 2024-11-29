#!/bin/bash
set -e
docker build -t 742195923217.dkr.ecr.eu-west-2.amazonaws.com/memorialisation-base:latest -f Dockerfile.base .
docker compose --file docker-compose.dev.yml build "$@"
docker compose --file docker-compose.dev.yml up "$@"
