FROM php:8.3.10-apache

# Definindo o diretório de trabalho padrão
WORKDIR /var/www/html

# Instalando as dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurando e instalando extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip intl xml opcache mysqli pdo pdo_mysql curl

RUN a2enmod rewrite

# Configurar o DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Copiando o Composer para a imagem
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiando o código-fonte para o diretório de trabalho
COPY . /var/www/html

RUN rm -rf /var/www/html/vendor

RUN chown -R www-data:www-data /var/www/html

RUN composer clear-cache

# Instalando dependências do Composer
USER www-data
RUN composer install --no-dev --optimize-autoloader

USER root

# Expondo a porta 80 para o servidor Apache
EXPOSE 80