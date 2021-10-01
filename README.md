#General Information

# How To run

## Create docker network
docker network create lumen_test

## Run docker containers
docker-compose up

## Apply migrations
php artisan doctrine:migrations:migrate

## Bulk upload commands
php artisan stock:upload --filepath <filepath>

php artisan product:upload --filepath <filepath>
