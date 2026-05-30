
FROM php:8.2-apache
 
# Copia todos os arquivos para o servidor
COPY . /var/www/html/
 
# Railway usa a variável PORT dinamicamente
RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf \
    && sed -i 's/VirtualHost \*:80/VirtualHost *:${PORT}/' /etc/apache2/sites-enabled/000-default.conf
 
# Permissões
RUN chown -R www-data:www-data /var/www/html
 
EXPOSE ${PORT}
 
CMD PORT=${PORT:-80} apache2-foreground
 