#General Information
This application is based on Laravel\Lumen. 
As ORM is used Doctrine, as it more flexible and written with using best practises and patterns


# How To run

## Create docker network
docker network create lumen_test

## Run docker containers
docker-compose up

## Apply migrations
php artisan doctrine:migrations:migrate

## Bulk upload commands
php artisan stock:upload --filepath <filepath> //filepath option is not required

php artisan product:upload --filepath <filepath> //filepath option is not required
