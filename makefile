env=dev
docker-os=
compose=docker-compose -f docker-compose.yml

export compose env docker-os

.PHONY: start
start: erase vendors build up ## clean current environment, recreate dependencies and spin up again

.PHONY: stop
stop: ## stop environment
		$(compose) stop $(s)

.PHONY: rebuild
rebuild: start ## same as start

.PHONY: vendors
vendors: ## install vendors
		docker run --rm --interactive --volume ${PWD}:/app composer install

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		$(compose) build

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		$(compose) stop
		docker-compose rm -v -f

.PHONY: composer-update
composer-update: ## Update project dependencies
		$(compose) run --rm wordpress sh -lc 'COMPOSER_MEMORY_LIMIT=-1 /var/www/html composer update'

.PHONY: up
up: ## spin up environment
		$(compose) up -d

.PHONY: unit
unit: ## execute project unit tests
		$(compose) exec -T wordpress sh -lc "cd wp-content/plugins/hexagonal-reviews && ./vendor/bin/codecept run unit"

.PHONY: acceptance
acceptance: ## run acceptance tests
		$(compose) exec -T wordpress sh -lc "docker-php-ext-install pdo_mysql"
		$(compose) exec -T wordpress sh -lc "cd wp-content/plugins/hexagonal-reviews && ./vendor/bin/codecept run acceptance"

.PHONY: cs
cs: ## run wordpress code sniffer on src
		$(compose) exec -T wordpress sh -lc "cd wp-content/plugins/hexagonal-reviews && ./vendor/bin/phpcs --config-set installed_paths /var/www/html/wp-content/plugins/hexagonal-reviews/vendor/wp-coding-standards/wpcs && ./vendor/bin/phpcs --standard=WordPress ./src hexagonal-reviews.php"

.PHONY: bf
bf: ## run wordpress code beautifier on src
		$(compose) exec -T wordpress sh -lc "cd wp-content/plugins/hexagonal-reviews && ./vendor/bin/phpcs --config-set installed_paths /var/www/html/wp-content/plugins/hexagonal-reviews/vendor/wp-coding-standards/wpcs && /usr/local/bin/php -d memory_limit=512M ./vendor/bin/phpcbf --standard=WordPress ./src hexagonal-reviews.php"

.PHONY: jslint
jslint: ## run wordpress code beautifier on src
		$(compose) run --rm jslint /app

.PHONY: buildplugin
buildplugin: ## makes the build
		rm -fr build
		mkdir -p build/hexagonal-reviews
		cp -R ./languages build/hexagonal-reviews/languages
		cp -R ./src build/hexagonal-reviews/src
		cp -R ./hexagonal-reviews.php build/hexagonal-reviews
		cp -R ./composer.json build/hexagonal-reviews
		cp -R ./index.php build/hexagonal-reviews
		cp -R ./readme.txt build/hexagonal-reviews
		cp -R ./assets build/hexagonal-reviews/assets

.PHONY: plugin81
plugin81: buildplugin ## compiles to php 8.1 and generates plugin
		cp rector81.php build
		-docker run --rm -v ${PWD}/build:/project rector/rector:latest process hexagonal-reviews/src --config rector81.php
		docker run --rm --interactive --tty --volume ${PWD}/build/hexagonal-reviews:/app composer install --no-dev
		rm build/rector81.php

.PHONY: plugin80
plugin80: buildplugin ## compiles to php 8.0 and generates plugin
		cp rector80.php build
		-docker run --rm -v ${PWD}/build:/project rector/rector:latest process hexagonal-reviews/src --config rector80.php
		docker run --rm --interactive --tty --volume ${PWD}/build/hexagonal-reviews:/app composer install --no-dev
		rm build/rector80.php

.PHONY: plugin74
plugin74: buildplugin ## compiles to php 7.4 and generates plugin
		cp rector74.php build
		-docker run --rm -v ${PWD}/build:/project rector/rector:latest process hexagonal-reviews/src --config rector74.php
		docker run --rm --interactive --tty --volume ${PWD}/build/hexagonal-reviews:/app composer install --no-dev
		rm build/rector74.php

.PHONY: plugin
plugin: buildplugin ## makes production build inside build folder in PHP 7.2
		docker run --rm --interactive --tty --volume ${PWD}/build/hexagonal-reviews:/app composer install --no-dev

.PHONY: plugin70
plugin70: buildplugin ## compiles to php 7.0 and generates plugin
		cp rector70.php build
		-docker run --rm -v ${PWD}/build:/project rector/rector:latest process hexagonal-reviews/src --config rector70.php
		docker run --rm --interactive --tty --volume ${PWD}/build/hexagonal-reviews:/app composer install --no-dev
		rm build/rector70.php

.PHONY: shell
shell: ## gets inside wordpress container
		$(compose) exec wordpress bash

.PHONY: logs
logs: ## look for 's' service logs, make s=php logs
		$(compose) logs -f $(s)

.PHONY: dump
dump: ## look for 's' service logs, make s=php logs
		$(compose) exec -T db sh -lc "mysqldump -uroot -psomewordpress wordpress" > ${PWD}/tests/_data/dump.sql

.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'