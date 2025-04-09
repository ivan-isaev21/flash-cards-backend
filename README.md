# Flash cards (Anki подобный  DDD implementation)

##  First Install

1) `git clone git@github.com:ivan-isaev21/flash-cards-backend.git`
2) `cd flash-cards-backend`
3) `cp .env.example .env`
4) Change credentials in `.env` file if you need
5) `docker-compose build`
6) `docker-compose up  -d`
7)  `docker exec -it flash-cards-backend bash`
8)  `composer install`
9)  `php artisan migrate --seed`
10) `exit`
11) `sudo chmod -R 777 storage`
12) `sudo chmod -R 777 bootstrap/cache/`

## Updating

1) `cd flash-cards-backend`
2) `git pull`
3) `docker exec -it flash-cards-microservice-php-fpm bash`
4) `composer install` if you need
5) `php artisan migrate`