php -d xdebug.mode=coverage bin/phpunit --coverage-clover clover.xml
php vendor/bin/php-coverage-badger clover.xml docs/coverage.svg
