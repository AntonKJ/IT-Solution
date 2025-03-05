IMAGE_NAME := app
VERSION ?= $(strip $(shell grep '"version": ' composer.json \
	| cut -d ':' -f2 | cut -d '"' -f2))
COMPOSER_VER := "latest"

comma := ,
# Checks two given strings for equality.
eq = $(if $(or $(1),$(2)),$(and $(findstring $(1),$(2)),\
                                $(findstring $(2),$(1))),1)


# Up/Down application

down: docker.down

up: docker.up


# Stop project in Dockerized development environment
# and remove all related containers.
#
# Usage:
#	make docker.down

docker.down:
	docker-compose down --rmi=local -v



# Run project in Dockerized development environment.
#
# Usage:
#	make docker.up [rebuild=(no|yes)]

docker.up: docker.down
	docker-compose up --abort-on-container-exit \
		$(if $(call eq,$(rebuild),yes),--build,)



# Resolve all project dependencies.
#
# Usage:
#	make deps [dev=(yes|no)]

deps: | deps.composer
ifeq ($(wildcard $(PWD)/public/version),)
	printf "$(VERSION)" > public/version
endif



# Resolve PHP Composer project dependencies.
#
# Optional 'cmd' parameter may be used for handy usage of docker-wrapped
# composer, for example: make deps.composer cmd='update dating/core'
#
# Usage:
#	make deps.composer [cmd=(install|<composer-cmd>)]
#	                   [dev=(yes|no)]
#	                   [dockerized=(yes|no)]

composer-cmd = $(if $(call eq,$(cmd),),install,$(cmd))

deps.composer:
ifneq ($(dockerized),no)
	docker run --rm --network=host -v "$(PWD)":/app -w /app \
	           -e CI_SERVER='$(CI_SERVER)' \
		composer:$(COMPOSER_VER) \
			make deps.composer cmd='$(composer-cmd)' dev='$(dev)' dockerized=no
else
	composer $(composer-cmd) \
		--no-interaction \
		$(if $(call eq,$(CI_SERVER),yes),\
			--no-progress,) \
		$(if $(and $(call eq,$(dev),no),\
		           $(call eq,$(word 1,$(composer-cmd)),install)),\
			--no-dev --optimize-autoloader,)
endif



# Build all project artifacts from sources.
#
# Usage:
#	make build

build: build.docker



# Build project Docker image.
#
# Usage:
#	make build.docker [target=(debug|runtime)] [no-cache=(no|yes)]

build.docker:
	docker build --network=host --force-rm \
		$(if $(call eq,$(no-cache),yes),--no-cache,) \
		$(if $(call eq,$(target),),,--target=$(target)) \
		--build-arg VERSION=$(VERSION) \
		-t $(IMAGE_NAME):dev \
		./



# Release project on GitHub.
#
# Usage:
#   make release

release:
	git tag -d latest
	git tag latest
	git push origin latest --force



.PHONY: build build.docker \
        deps deps.composer \
        docker.down docker.up \
        down release up
