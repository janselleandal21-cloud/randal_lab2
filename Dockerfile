FROM php:8.2-apache
COPY . /var/www/html/
COPY php.ini /usr/local/etc/php/php.ini
RUN chown -R www-data:www-data /var/www/html/ && chmod -R 755 /var/www/html/