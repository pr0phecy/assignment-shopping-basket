DOCKER_COMPOSE=docker compose -p shopping_basket_project -f docker-compose.yml
DOCKER_APP=${DOCKER_COMPOSE} exec -u "1000:1000" app

.PHONY: help
help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: start
start: ## Start the Docker containers, and start serve artisan
	${DOCKER_COMPOSE} up -d
	${DOCKER_APP} php artisan serve --host=0.0.0.0 --port=8000

.PHONY: down
down: ## Stop the Docker containers
	${DOCKER_COMPOSE} down

.PHONY: build
build: ## Stop the Docker containers
	${DOCKER_COMPOSE} up --build -d

.PHONY: restart
restart: ## Restart the Docker containers
	${DOCKER_COMPOSE} down && ${DOCKER_COMPOSE} up -d

.PHONY: setup
setup: ## Set up the application (install dependencies, run migrations, and seed the database)
	${DOCKER_COMPOSE} up -d
	${DOCKER_APP} composer install
	${DOCKER_APP} php artisan migrate --force
	${DOCKER_APP} php artisan db:seed --force

.PHONY: shell
shell: ## Enter the app container shell
	${DOCKER_APP} bash

.PHONY: migrate
migrate: ## Run database migrations
	${DOCKER_APP} php artisan migrate --force

.PHONY: phpfixer
phpfixer: ## Run PHP-CS-Fixer globally, because adding composer packages is not allowed
	php-cs-fixer fix --config=.php-cs-fixer.php --allow-risky=yes
