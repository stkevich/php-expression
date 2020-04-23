SHELL := /usr/bin/env bash
upd:
	export UID && docker-compose up -d

upb:
	export UID && docker-compose up --build

up:
	export UID && docker-compose up

down:
	docker-compose down -v

bash:
	export UID && docker exec -it blog_backend bash

tail:
	docker-compose logs -f
