.PHONEY: all build clean

all: dev-env production

dev-env: 
	docker compose up -d

shell: build
	docker compose run --rm wordpress bash

production:
	docker build -t antiqueclocks .

clean:
	@echo "removing docker images containers networks and volumes"
	docker compose down --remove-orphans --rmi all -v