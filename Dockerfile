
FROM php:8.2-apache
 
COPY . /var/www/html/
 
RUN chown -R www-data:www-data /var/www/html
 
ENV PORT=80
EXPOSE 80
 
CMD ["apache2-foreground"]
 