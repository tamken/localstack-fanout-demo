#!/bin/bash

cd "$(dirname "$0")"

# 1. localstack起動
docker-compose up -d

# 2. sns/sqsセットアップ
sleep 3
bash ./sh/setup.sh
