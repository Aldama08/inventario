FROM php:8.2-apache

# 1. Instalar extensiones de PHP necesarias para CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl gd pdo pdo_mysql

# 2. Activar el módulo rewrite de Apache (crucial para las rutas de CI4)
RUN a2enmod rewrite

# 3. Cambiar el DocumentRoot de Apache a la carpeta /public de CodeIgniter
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Copiar los archivos del proyecto al contenedor
COPY . /var/www/html

# 5. Dar permisos correctos a las carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html/writable

WORKDIR /var/www/html