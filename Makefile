QUIET := @
ARGS=$(filter-out $@, $(MAKECMDGOALS))

.DEFAULT_GOAL=help
.PHONY=help
app_container=app
queue_container=app
app_container=queue
db_container=postgresql

init-db: ## Проинициализировать БД
	$(QUIET) chmod 774 ./config/postgrespro/bin/initdb.sh
	$(QUIET) ./config/postgrespro/bin/initdb.sh

cmp-install: ## Установить пакеты
	$(QUIET) docker-compose run --rm --no-deps webapp composer install

cmp-update: ## Обновить пакеты
	$(QUIET) docker-compose run --rm --no-deps webapp composer update ${ARGS}

shell-php: ## Войти в php-контейнер
	docker exec -it app /bin/sh

db:
	docker exec -it ${db_container} bash

rab:
	docker exec -it ${queue_container} bash

entity:
	php bin/console make:entity

mi-generate:
	php bin/console doctrine:migrations:generate

migrate:
	php bin/console --no-interaction doctrine:migrations:migrate

books:
	php bin/console load:books

phpstan:
	vendor/bin/phpstan analyse

tests:
	php vendor/bin/phpunit
