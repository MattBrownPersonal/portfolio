#!/bin/bash
set -e
cd "$(dirname "$0")"
COMMIT_HASH=$(git rev-parse HEAD)
COMMIT_SHORT_HASH=$(git rev-parse --short HEAD)
COMMIT_TIMESTAMP=$(git show -s --date=format:'%Y-%m-%dT%H:%M:%S' --format=%cd ${COMMIT_HASH})

echo "COMMIT_HASH:       ${COMMIT_HASH}"
echo "COMMIT_SHORT_HASH: ${COMMIT_SHORT_HASH}"
echo "COMMIT_TIMESTAMP:  ${COMMIT_TIMESTAMP}"

cat << EOF > ./version.yml
---
commit_hash: ${COMMIT_HASH}
commit_short_hash: ${COMMIT_SHORT_HASH}
commit_timestamp: ${COMMIT_TIMESTAMP}
EOF
