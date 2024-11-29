#!/bin/bash
set -e

export AWS_PROFILE=tccs
CLUSTER=Vivedia-Staging
CONTAINER=vivedia-mem-portal
AWS_REGION=eu-west-2

TASK_ARN=""

echo "Cluster: ${CLUSTER}, Container: ${CONTAINER}"

echo "Looking for task..."
TASK=$(aws ecs list-tasks --cluster $CLUSTER --output json)
readarray -t task_arns < <(echo "${TASK}" | jq -r '.taskArns[]')

for arn in "${task_arns[@]}"; do
    TD=$(aws ecs describe-tasks --cluster $CLUSTER --tasks "$arn" --output json)
    NAME=$(echo "${TD}" | jq -r '.tasks[0].containers[0].name')
    STATUS=$(echo "${TD}" | jq -r '.tasks[0].containers[0].lastStatus')
    if [[ "$STATUS" == "RUNNING" ]]; then
        if [[ "${NAME}" == "${CONTAINER}" ]]; then
            TASK_ARN=$(echo "${TD}" | jq -r '.tasks[0].containers[0].taskArn')
            echo "Task found for ${CONTAINER}, ARN: ${TASK_ARN} ($STATUS)"
        fi
    fi
done

if [[ "${TASK_ARN}" == "" ]]; then
    echo "Running Task for ${CONTAINER} not found in the cluster"
    exit 1
fi

aws ecs execute-command \
    --region $AWS_REGION \
    --cluster $CLUSTER \
    --task "$TASK_ARN" \
    --container $CONTAINER \
    --command "/bin/bash" \
    --interactive
