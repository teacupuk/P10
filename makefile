# --- Makefile for Laravel Docker Project ---

PHP_SERVICE = p10-web
DB_SERVICE = p10-db

.PHONY: up down build rebuild fresh restart logs shell dbshell artisan

## Start all containers
up:
	docker-compose up -d

## Stop all containers
down:
	docker-compose down

## Build only the Laravel app container (reuses MySQL image)
build:
	docker-compose build $(PHP_SERVICE)
	docker-compose up -d

## Rebuild the Laravel app container without cache
rebuild:
	docker-compose build --no-cache $(PHP_SERVICE)
	docker-compose up -d

## Remove all containers, volumes, and rebuild fresh
fresh:
	docker-compose down -v
	docker-compose build --no-cache $(PHP_SERVICE)
	docker-compose up -d

## Restart only the Laravel app container
restart:
	docker-compose restart $(PHP_SERVICE)

## Show logs from Laravel app
logs:
	docker-compose logs -f $(PHP_SERVICE)

## Shell into Laravel app container
shell:
	docker exec -it $(PHP_SERVICE) bash

## Shell into MySQL container
dbshell:
	docker exec -it $(DB_SERVICE) bash

## Run Laravel artisan commands
artisan:
	docker exec -it $(PHP_SERVICE) php /var/www/html/artisan

migrate:
	docker exec -it $(PHP_SERVICE) php /var/www/html/artisan migrate

migrate-fresh:
	docker exec -it $(PHP_SERVICE) php /var/www/html/artisan migrate:fresh
