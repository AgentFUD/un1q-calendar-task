build:
	docker-compose build

up:
	docker-compose up -d

init:
	docker exec -it laravel-app cp .env.example .env
	docker exec -it laravel-app composer install
	docker exec -it laravel-app php artisan key:generate
	docker exec -it laravel-app php artisan migrate

down:
	docker-compose down

shell:
	docker exec -it laravel-app /bin/sh

pint:
	docker exec -it laravel-app ./vendor/bin/pint

test:
	docker exec -it laravel-app php artisan test

