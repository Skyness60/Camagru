up:
	docker-compose up -d --build
	@if [ ! -d "vendor" ]; then \
		echo "📦 Installing Composer dependencies..."; \
		docker-compose run --rm composer install; \
	else \
		echo "✅ Composer dependencies already installed."; \
	fi
	docker-compose run --rm composer dump-autoload

composer_re:
	docker-compose run --rm composer install --no-interaction --prefer-dist
	docker-compose run --rm composer dump-autoload

test:
	docker-compose exec php ./vendor/bin/phpunit || true

down:
	docker-compose down -v

logs:
	docker-compose logs -f

ps:
	docker-compose ps

sh:
	docker-compose exec php sh

db:
	docker-compose exec db mariadb -u${MYSQL_USER} -p${MYSQL_PASSWORD}

fullclean:
	docker-compose down -v --remove-orphans
	docker system prune -f
	docker volume prune -f
	docker rmi -f $$(docker images -q)

re:
	docker-compose down -v --remove-orphans
	docker-compose up -d --build

.PHONY: up test down logs ps sh db fullclean re
