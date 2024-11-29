#!/bin/bash
while true; do
    echo "Checking health..."
    healthy=1
    for container_id in $(docker compose ps -q)
    do
        container_name=$(docker inspect --format='{{.Name}}' ${container_id})
        health_status=$(docker inspect --format "{{json .State.Health.Status }}" ${container_id})
        if [[ "${health_status}" != '"healthy"' ]]; then
            healthy=0
        fi
        echo "  ${container_name}: ${health_status}"
    done
    if [[ "${healthy}" -eq "1" ]]; then
        echo
        echo "Stack healthy."
        exit 0
    fi
    docker compose logs --tail=10 | sed 's/^/    /'
    sleep 5
done
