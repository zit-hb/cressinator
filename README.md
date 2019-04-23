Cressinator
===========

A web application to store and view data of environmental sensors.

# Work In Progress

## Development

Create copy `.env.local` of `.env` and modify to your needs:

    cp .env .env.local

Install dependencies:

    composer install

Create database:

    ./bin/console doctrine:database:create

Initialize database:

    ./bin/console doctrine:migrations:migrate

Load test data:

    ./bin/console doctrine:fixtures:load

Start test server:

    ./bin/console server:run
