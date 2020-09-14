# Test task for kt.team 👨‍💻

Application address [http://localhost:8001/](http://localhost:8001/)

API documentation address [http://localhost:8001/api/docs](http://localhost:8001/api/docs)

Postman API collection placed in file `kt_test.postman_collection.json`

## Run project

Build and start docker container:
```shell script
docker-compose up -d
```

Wait few minutes after composer install dependencies. After the installation is complete, the composer service stops.
```
kt_test_php_composer | Executing script cache:clear [OK]
kt_test_php_composer | Executing script assets:install public
kt_test_php_composer |  [OK]
kt_test_php_composer exited with code 0
```

Run init script

(Windows)

```bash
.\init.bat
```

(Linux)

```bash
 ./init.sh
```

----

*Or run commands manually:*

After composer install dependencies run migrations:

```shell script
docker exec -it kt_test_php php bin/console doctrine:migrations:migrate -n
```

And load fixtures

```
docker exec -it kt_test_php php bin/console hautelook:fixtures:load -n
```

----

Now application ready on [http://localhost:8001/](http://localhost:8001/)

## Commands
Run phpcs

```shell script
.\vendor\bin\phpcs
```

Run tests

```shell script
.\vendor\bin\simple-phpunit
```

## Elasticsearch

Synchronization with the database is implemented **but is not used in the API due to unsolved problem problem with normalization**  

For creating index for Task

```shell
php bin/console elasticsearchClient:createIndex
```

And the indexes can be viewed here [http://localhost:9200/_cat/indices?v](http://localhost:9200/_cat/indices?v) . After Logstash synced with Postgres search results can be viewed here [http://localhost:9200/task/_search?pretty](http://localhost:9200/task/_search?pretty)
