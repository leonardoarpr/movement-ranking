ifneq ("$(wildcard .env)","")
  $(info using .env)
  include .env
endif

CONTAINER_NAME=php_app
COMMAND=docker compose


docker-install: docker-build docker-up docker-composer-install

docker-up:
	$(COMMAND) up -d

docker-down:
	$(COMMAND) down

docker-bash:
	docker exec -it $(CONTAINER_NAME) bash

docker-build:
	$(COMMAND) build

docker-composer-install:
	docker exec $(CONTAINER_NAME) composer install --no-interaction --no-scripts

docker-test:
	docker exec -t $(CONTAINER_NAME) composer test

generate-openapi-from-postman:
	npm i postman-to-openapi -g
	p2o docs/postman_collection.json -f docs/openapi.yml -o docs/openapi-options.json
