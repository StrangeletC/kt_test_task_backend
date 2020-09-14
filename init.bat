@ECHO OFF

ECHO "Applying migrations..."

docker exec -it kt_test_php php bin/console doctrine:migrations:migrate -n

ECHO "Loading fixtures..."

docker exec -it kt_test_php php bin/console hautelook:fixtures:load -n
