FROM php:8.2-apache

RUN a2dismod mpm_event mpm_worker 2>/dev/null; \
    a2enmod mpm_prefork php8.2 2>/dev/null; \
    true

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]