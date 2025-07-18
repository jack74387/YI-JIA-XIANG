.PHONY: up down build migrate logs init

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

migrate:
	docker-compose exec backend php artisan migrate --seed

logs:
	docker-compose logs -f

init: build up migrate
	@echo "🚀 Docker 部署完成，請瀏覽 http://localhost:5173" 