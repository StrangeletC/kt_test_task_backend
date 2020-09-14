#!/bin/bash

echo "Applying migrations..."

docker exec -it kt_test_php php bin/console doctrine:migrations:migrate -n

echo "Loading fixtures..."

docker exec -it kt_test_php php bin/console hautelook:fixtures:load -n
