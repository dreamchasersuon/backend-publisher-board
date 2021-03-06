# backend-publisher-board
Backend for https://github.com/dreamchasersuon/publisher-board

## Prepare

Assume you have installed Docker and docker-compose.
Add alias to /etc/hosts: 
1. `nano /etc/hosts`;
2. Add this line `127.0.0.1 symfony.localhost`.

## Startup

Clone this project `git clone https://github.com/dreamchasersuon/backend-publisher-board.git`.
Go to the project directory `cd backend-publisher-board`.

1. `make start`;
2. `make install_deps`;
3. `make migrate`;
4. `make fixtures` if need fake data.

Run `make down` to close all containers.

## Available Scripts

In the project directory, you can run:

### `make help`
show available `make` commands
### `make start`
start docker development environment (need to shut down another containers if ran)
### `make build`
build docker container
### `make install_deps`
install required project dependencies
### `make migrate`
apply database migrations
### `make fixtures`
apply database fixtures
### `make prune`
clean all inactive containers and cache
### `make test`
start functional tests
### `make down`
shut down containers


