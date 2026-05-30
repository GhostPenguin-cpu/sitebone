
FROM ubuntu:22.04
 
ENV DEBIAN_FRONTEND=noninteractive
 
RUN apt-get update && apt-get install -y \
    php8.1 \
    php8.1-cli \
    libapache2-mod-php8.1 \
    apache2 \
    && rm -rf /var/lib/apt/lists/*
 
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
 
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
 
EXPOSE 80
 
CMD ["apachectl", "-D", "FOREGROUND"]
 