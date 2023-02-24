#!/bin/bash

command -v docker-compose >/dev/null 2>&1 || { echo >&2 "docker-compose command is required"; exit 1; }

docker-compose down --remove-orphans
docker-compose up -d database
docker-compose run --rm php php bin/console --env=test doctrine:database:drop --force
docker-compose run --rm php php bin/console --env=test doctrine:database:create
docker-compose run --rm php php bin/console --env=test doctrine:schema:create
docker-compose run --rm php php bin/console --env=test doctrine:fixtures:load --append
docker-compose run --rm php composer test
