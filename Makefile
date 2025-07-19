ifneq ("$(wildcard .env)","")
  $(info using .env)
  include .env
endif

CONTAINER_NAME=php_app
COMMAND=docker compose


docker-install: docker-build docker-up docker-composer-install

docker-up:
	$(COMMAND) up

docker-down:
	$(COMMAND) down

docker-bash:
	docker exec -it $(CONTAINER_NAME) sh

docker-build:
	$(COMMAND) build

docker-composer-install: docker-up
	docker exec $(CONTAINER_NAME) composer install --no-interaction --no-scripts

docker-test: docker-up
	docker exec -t $(CONTAINER_NAME) composer test

docker-test-coverage:
	docker exec $(CONTAINER_NAME) composer test-coverage-html

docker-migrate-refresh:
	docker exec $(CONTAINER_NAME) php artisan migrate:refresh --seed

docker-coverage-html: docker-up
	docker exec -t $(CONTAINER_NAME) composer test-coverage-html

generate-openapi-from-postman:
	npm i postman-to-openapi -g
	p2o docs/postman_collection.json -f docs/openapi.yml -o docs/openapi-options.json

--sonar-network-create:
	@if (docker network ls | grep 'sonar-net'); then \
		echo 'sonarqube network already up.. connecting'; \
	else \
	  	echo 'creating sonarqube network'; \
		docker network create sonar-net; \
  	fi

sonar-prune:
	@docker container stop sonar
	@docker network rm sonar-net

docker-test-coverage-sonar: docker-up
	docker exec -e XDEBUG_MODE=coverage $(CONTAINER_NAME) ./vendor/bin/phpunit --coverage-xml ./reports/pipe-php --testdox --coverage-text --colors=never  --log-junit ./build/phpunit.xml --coverage-clover ./build/coverage.xml -d memory_limit=-1 --coverage-cobertura=coverage.cobertura.xml
