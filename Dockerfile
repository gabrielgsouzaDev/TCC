
FROM php:8.2-apache

# Copia todo o projeto para a raiz do Apache
COPY . /var/www/html/

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Ajusta permissões (importante para evitar Forbidden)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expõe a porta usada pelo Render
EXPOSE 10000
