FROM php:8.2-apache

# 1. Instalar extensiones de PHP y herramientas del sistema (Añadido Soporte MySQLi y MariaDB/MySQL)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl gd pdo pdo_mysql pdo_pgsql pgsql mysqli

# 2. Instalar Composer copiando el binario oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Activar el módulo rewrite de Apache
RUN a2enmod rewrite

# 4. Cambiar el DocumentRoot de Apache a la carpeta /public de CodeIgniter
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Setear el directorio de trabajo antes de copiar y ejecutar comandos
WORKDIR /var/www/html

# 5. Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# 6. Instalar las dependencias de Composer
RUN composer install --no-interaction --optimize-autoloader

# 7. Dar permisos correctos a las carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html