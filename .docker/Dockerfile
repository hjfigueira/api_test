FROM php:8.3-fpm

RUN apt update

RUN apt install libzip-dev -y

RUN docker-php-ext-install zip

RUN docker-php-ext-configure zip

RUN chown www-data -R storage/

#RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
#    php -r "if (hash_file('sha384', 'composer-setup.php') === 'edb40769019ccf227279e3bdd1f5b2e9950eb000c3233ee85148944e555d97be3ea4f40c3c2fe73b22f875385f6a5155') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
#    php composer-setup.php
#    php -r "unlink('composer-setup.php');"