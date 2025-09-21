up:
	docker compose up -d --remove-orphans
down:
	docker compose down
build:
	docker compose build
shell:
	docker compose exec app /bin/bash
db-shell:
	docker compose exec postgres /bin/sh
ps:
	docker compose ps