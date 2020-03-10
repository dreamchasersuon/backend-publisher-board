help:
	@echo '  build    				build container'
	@echo '  start_dev  			start development container'
	@echo '  start_test     		start testing container'
	@echo '  install_deps   		install required dependencies'
	@echo '  migrate    			apply database migrations'
	@echo '  fixtures    			apply database fixtures'
	@echo '  prune    				cleanup inactive containers and cache.'

start_dev:
	docker-compose up

start_test:
	docker-compose up -f docker-compose.test.yml

build:
	docker-compose build

install_deps:
	docker-compose exec php composer install

migrate:
	docker-compose run php bin/console doctrine:migrations:migrate

fixtures:
	docker-compose run php bin/console doctrine:fixtures:load

prune:
	docker system prune -af
