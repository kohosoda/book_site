FROM php:8.3-apache

# コンテナ内の作業ディレクトリを設定
WORKDIR /var/www/html

# 必要なシステムパッケージをインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js と npm をインストール
RUN curl -SL https://deb.nodesource.com/setup_lts.x | bash
RUN apt-get install -y nodejs && \
  npm install -g npm@latest

# Apacheの設定を変更
RUN a2enmod rewrite
COPY ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# npm の依存関係をインストール
RUN composer install
RUN npm install
RUN npm run dev

# スクリプトの実行権限を付与
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80