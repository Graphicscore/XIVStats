#!/bin/bash

set +H

echo "Checking dependencies...."

sudo apt-get -y install httpie jq

echo "Requesting Webservice Token..."
PORTAINER_TOKEN=$(http --ignore-stdin POST "$PORTAINER_AUTH_URL" Username="$PORTAINER_USER" Password="$PORTAINER_PASSWORD" | jq -r '.jwt')
PORTAINER_TOKEN_HEADER="Authorization: Bearer $PORTAINER_TOKEN"

echo "Pull latest image..."
#pull image
http --ignore-stdin POST "$PORTAINER_DOCKER_URL/images/create?fromImage=ghcr.io%2Fgraphicscore%2Fxivstats:latest" "$PORTAINER_TOKEN_HEADER"

echo "Request information about currently running container..."
#get info about currently running ffxivstats container and save json response
CONTAINER_JSON=$(http GET "$PORTAINER_DOCKER_URL/containers/$CONTAINER_NAME/json" "$PORTAINER_TOKEN_HEADER")

echo "Stopping currently running container..."
#stop currently running container
http --ignore-stdin POST "$PORTAINER_DOCKER_URL/containers/$CONTAINER_NAME/stop" "$PORTAINER_TOKEN_HEADER"

echo "Renaming old container ..."
#rename old container
http --ignore-stdin POST "$PORTAINER_DOCKER_URL/containers/$CONTAINER_NAME/rename?name=$CONTAINER_NAME-old" "$PORTAINER_TOKEN_HEADER"

echo "Performing jq magic to generate json payload for container recreation..."
#Get Config objects content and use that as a base for the target json
TARGET_JSON=$(echo $CONTAINER_JSON | jq ".Config")
#Copy HostConfig object from container json to target json
TARGET_JSON=$(echo $TARGET_JSON | jq --argjson hostconfig "$(echo $CONTAINER_JSON | jq '.HostConfig')" '. + {HostConfig: $hostconfig}')
#Add name to target json
TARGET_JSON=$(echo $TARGET_JSON | jq --arg name "$CONTAINER_NAME" '. + {name:$name}')
#Add Network config to json
TARGET_JSON=$(echo $TARGET_JSON  | jq ". + {"NetworkingConfig": {}}")
TARGET_JSON=$(echo $TARGET_JSON | jq --argjson netconfig "$(echo $CONTAINER_JSON | jq '.NetworkSettings.Networks')" '.NetworkingConfig=(.NetworkingConfig + {"EndpointsConfig":$netconfig})')

echo "Re-Creating container ..."
echo $TARGET_JSON | http POST "$PORTAINER_DOCKER_URL/containers/create?name=$CONTAINER_NAME" "$PORTAINER_TOKEN_HEADER"

echo "Starting container ..."
http --ignore-stdin POST "$PORTAINER_DOCKER_URL/containers/$CONTAINER_NAME/start" "$PORTAINER_TOKEN_HEADER"

echo "Deleting old container ..."
http --ignore-stdin DELETE "$PORTAINER_DOCKER_URL/containers/$CONTAINER_NAME-old?force=true&v=1" "$PORTAINER_TOKEN_HEADER"

echo "OK Deploy done!"