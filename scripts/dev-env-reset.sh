#!/bin/bash
set -e

echo "!!--WARNING--!!"
echo "This will reset your development environment"
echo "All local S3 files will be removed"
echo "Database will be wiped and reseeded"

read -p "Continue? (y/N): " confirm && [[ $confirm == [yY] || $confirm == [yY][eE][sS] ]] || exit 1

awslocal s3 rm --recursive s3://vivedia-bucket
docker exec memorialisation-web ./artisan migrate:fresh
docker exec memorialisation-web ./artisan db:seed
echo .
echo "Development environment reset"
echo "It is recommended that you also clear your browser data, cookies and local storage"