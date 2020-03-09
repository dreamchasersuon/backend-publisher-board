# backend-publisher-board
Backend for https://github.com/dreamchasersuon/publisher-board

## Prepare

Add alias to /etc/hosts: 
`nano /etc/hosts`
add this line '127.0.0.1 symfony.localhost'

Install dependencies:
`docker-compose exec php composer install`

## Available Scripts

In the project directory, you can run:

### `docker-compose up -d`
start docker in backgorund mode
### `docker-compose run php bin/console doctrine:migrations:migrate`
apply migrations to database
