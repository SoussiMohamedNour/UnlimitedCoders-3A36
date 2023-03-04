#!/usr/bin/env bash

set -xe

echo "########################"
echo "Deleting and Creating an new Database"
echo "########################"

php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

