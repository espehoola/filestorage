.DEFAULT_GOAL := help
.PHONY: i install up build-up exec c_u stop down optimize tests docs

i:
	make install

install:
	cp ./.env.example ./.env
	make up
	chmod -R 777 storage
	mkdir -p 777 storage/data
	docker exec file_storage_php bash -c "composer install"
	docker exec file_storage_php bash -c "php artisan migrate"
	docker exec file_storage_php bash -c "php artisan key:generate"

up:
	docker-compose pull
	docker-compose up -d

build-up:
	docker-compose pull
	docker-compose up -d --force-recreate --build

exec:
	docker-compose exec php bash

docs:
	docker-compose exec php php artisan l5-swagger:generate

c_u:
	make composer_update

composer_update:
	docker-compose exec php composer update

stop:
	docker-compose stop

down:
	docker-compose down -v

optimize:
	docker-compose exec php php artisan optimize

tests:
	docker-compose exec php ./vendor/bin/phpunit

help:
	@echo 'Использование: make [КОМАНДА]'
	@echo 'Команды:'
	@echo '       i - Установить сервисы виртуального окружения'
	@echo '      up - Включить сервисы виртуального окружения'
	@echo 'build-up - Пересобрать и запустить сервисы виртуального окружения'
	@echo '    down - Выключить сервисы виртуального окружения'
	@echo '     c_u - Обновить composer'
	@echo '    docs - Обновить документацию'
	@echo 'optimize - Очистить кэш'
	@echo '    exec - Командная строка контейнера'
	@echo '   tests - Запуск тестов'
	@echo '    help - Вызывает эту справку'
