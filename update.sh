
php bin/console dizda:backup:start --env=prod

git stash
git pull --force

php composer.phar self-update
php composer.phar update
php composer.phar dump-autoload --optimize

php bin/console cache:clear
php bin/console cache:clear --env=prod --no-debug

chown www-data:www-data -R app/cache
chown www-data:www-data -R app/logs

php bin/console assets:install web --symlink

chmod 777 -R app/cache
chmod 777 -R app/logs

php bin/console doctrine:schema:update --force --dump-sql > migration.log
