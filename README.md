Cressinator
===========

A web application to store and view data of environmental sensors.

## Requirements

  * [Composer](https://getcomposer.org/)
  * [Symfony CLI](https://symfony.com/download)
  * PHP >= 7.2

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

Add user:

    ./bin/console user:create

Start test server:

    symfony server:start

## Examples

### Add Group

    curl -d "group[name]=foo" -X POST http://127.0.0.1:8000/groups/add

### Add Source

    curl -d "source[name]=foo&source[unit]=bar&source[group]=1" -X POST http://127.0.0.1:8000/sources/add

### Add Measurement

    curl -d "measurement[value]=foo&measurement[source]=1" -X POST http://127.0.0.1:8000/measurements/add

### Upload Recording

    curl -F "recording[file]=@/path/to/file.jpg" -F "recording[group]=1" http://127.0.0.1:8000/recordings/add
