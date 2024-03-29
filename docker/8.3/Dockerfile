FROM php:8-apache

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y nodejs npm libonig-dev libzip-dev libpq-dev unzip mariadb-client postgresql-client  git vim\
    && docker-php-ext-install mbstring zip bcmath pdo_mysql pdo_pgsql mysqli \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && npm install -D prettier \
    && apt-get install -y default-mysql-client \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# a2emod rewrite をして apache に rewrite モジュールを追加
# これをしないと Laravel でルート以外にアクセスできない
RUN a2enmod rewrite
# a2ensite コマンドでシンボリックリンクを sites-enabled に作成する
RUN a2ensite 000-default
