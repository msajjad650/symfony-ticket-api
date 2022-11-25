# symfony-ticket-api

1. Clone project
```
git clone https://github.com/msajjad650/symfony-ticket-api.git
```

2. after cloning goto project directory and do composer install
```
cd symfony-ticket-api
composer install
```

3. Goto .env file and update database strings
```
DATABASE_URL="mysql://root@127.0.0.1:3306/airlineapi?serverVersion=14&charset=utf8"
```

4. Create database
```
php bin/console doctrine:database:create
```

5. Run migrations
```
php bin/console doctrine:migrations:migrate
```

6. Run project
```
symfony server:start
```

For all the apis documentation follow the link below
https://documenter.getpostman.com/view/6489657/2s8YsrzuG4

API Lists
1. Create Ticket
2. Get Single Ticket
3. Cancel Ticket
4. Change Seat
