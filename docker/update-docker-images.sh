#! /usr/bin/env bash

docker pull postgres:14.1-alpine
docker pull php:8-fpm-alpine
docker pull php:8-alpine
docker pull nginx:stable-alpine
docker pull alpine:latest

docker-compose build

CONTAINERS=$(docker ps -a -q -f status=exited)
if [ -n "$CONTAINERS" ]; then
    docker rm -v $CONTAINERS
fi

IMAGES=$(docker images -f "dangling=true" -q)
if [ -n "$IMAGES" ]; then
    docker rmi $IMAGES
fi
