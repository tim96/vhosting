
php composer.phar self-update
php composer.phar update
php composer.phar dump-autoload --optimize

php app/console cache:clear
php app/console cache:clear --env=prod --no-debug

php app/console assets:install web --symlink
php app/consoel assets:install

php app/console assetic:dump
php app/console assetic:dump --env=prod --no-debug

php app/console doctrine:schema:update --force --dump-sql
