
php app/console dizda:backup:start --env=prod

git stash
git pull --force

php composer.phar self-update
php composer.phar update
php composer.phar dump-autoload --optimize

php app/console doctrine:database:create
php app/console doctrine:schema:create

# Add test data to DB
#php app/console doctrine:fixtures:load --append

#php app/console fos:user:create --super-admin admin

php app/console cache:clear
php app/console cache:clear --env=prod --no-debug

chown www-data:www-data -R app/cache
chown www-data:www-data -R app/logs

php app/console assets:install web --symlink

php app/console assetic:dump
php app/console assetic:dump --env=prod --no-debug

chmod 777 -R app/cache
chmod 777 -R app/logs

php app/console doctrine:schema:update --force --dump-sql