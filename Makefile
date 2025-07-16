up:
	docker-compose up -d --build

composer:
	docker-compose run --rm composer install

composer-update:
	docker-compose run --rm composer update

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

.PHONY: up down logs ps sh db fullclean re