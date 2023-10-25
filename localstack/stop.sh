#!/bin/bash

cd "$(dirname "$0")"

# 1. localstack(docker-compose)停止
docker-compose down
