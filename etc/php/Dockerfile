FROM devilbox/php-fpm:8.1-mods

ARG NEW_UID=1000
ARG NEW_GID=1000

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
