# Test task for kt.team 👨‍💻

Application address [http://localhost:8001/](http://localhost:8001/)

## Commands
Run phpcs

```shell script
.\vendor\bin\phpcs
```

Run tests

```shell script
.\vendor\bin\simple-phpunit
```

## Manual
For build and start docker container:
```shell script
docker-compose up -d
```

After composer install dependencies run migrations:

```shell script
docker exec -it kt_test_task_backend_php_1 php bin/console doctrine:migrations:migrate -n
```

And load fixtures

```
docker exec -it kt_test_task_backend_php_1 php bin/console hautelook:fixtures:load -n
```

Now application ready on [http://localhost:8001/](http://localhost:8001/)
