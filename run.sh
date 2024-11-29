#!/bin/bash
set -e

if [ -n "$ECS_CONTAINER_METADATA_URI_V4" ]; then
    echo "Running in ECS, building caches..."

    # Cache config when hosted
    ./artisan config:cache

    # Cache routes when hosted  (TODO: Errors)
    # ./artisan route:cache

    # Cache views when hosted
    ./artisan view:cache

    # ./artisan optimize
else
    echo "Running locally, not building caches..."
fi

exec php -S 0.0.0.0:8080 -t public
