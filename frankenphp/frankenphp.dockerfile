FROM dunglas/frankenphp

# Developement:
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN install-php-extensions \
    pdo_pgsql \
    xdebug
