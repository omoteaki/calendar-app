FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get install -y tzdata apache2 php php-mysql libfreetype6-dev libpng-dev libmcrypt-dev
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN touch /etc/ssl/certs/apache-selfsigned.crt /etc/ssl/private/apache-selfsigned.key

COPY apache2/ssl-params.conf /etc/apache2/conf-available

RUN a2enmod ssl
RUN a2ensite default-ssl
RUN a2enconf ssl-params

EXPOSE 80
EXPOSE 443
CMD ["apachectl", "-D", "FOREGROUND"]