#!/bin/bash

command -v docker-compose >/dev/null 2>&1 || { echo >&2 "docker-compose command is required"; exit 1; }

docker-compose up -d
