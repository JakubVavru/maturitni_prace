FROM ubuntu:20.04

LABEL maintainer="Guba"

RUN apt-get update \
    && apt-get install -y \
    apt-utils \
    build-essential \
    curl \
    git \
    zip \
    unzip \
    wget \
    mysql-client
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get install -y software-properties-common

RUN apt-get update && apt-get install -y apt-utils build-essential curl git zip unzip wget mysql-server

RUN apt-add-repository -y ppa:ondrej/php
RUN apt-get update \
    && apt-get install -y \
    nginx \
    php8.1 php8.1-fpm php8.1-mysql php8.1-curl php8.1-gd php8.1-intl \
    php8.1-mbstring php8.1-soap php8.1-xml php8.1-zip php8.1-bcmath \
    php8.1-imagick php8.1-xdebug php8.1-opcache

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --filename composer --install-dir=/bin \
    && php -r "unlink('composer-setup.php');"

COPY sites-available /etc/nginx/sites-available

COPY webroot /usr/share/nginx/webroot

WORKDIR /usr/share/nginx/webroot
RUN ["composer", "update"]


RUN ["chown", "-R", "www-data:www-data", "/usr/share/nginx/webroot"]

EXPOSE 80

STOPSIGNAL SIGQUIT

COPY import.sql /tmp/import.sql
ADD init_db.sh /tmp/init_db.sh
RUN ["chmod", "+x", "/tmp/init_db.sh"]
RUN /tmp/init_db.sh

ENTRYPOINT service mysql start && service php8.1-fpm start && nginx -g "daemon off;"
# docker build -t nazevimage ./
# docker run -it --name moptestcontainer -p 8080:80 moptestimage