// Installation
docker compose up -d

// Login inside docker container
docker exec -it test-app bash   
cd project

// Run calculate commission command
php bin/console calculate-commissions   

// Run unit tests 
./vendor/bin/phpunit tests
