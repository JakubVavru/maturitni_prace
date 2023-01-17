FROM ubuntu:20.04
LABEL maintainer="Guba"

RUN apt-get update && apt-get install -y \
    apt-utils \
    build-essential \
    curl \
    git \
    zip \
    unzip \
    wget \
    python3 \
    python3-pip \
    python3-setuptools \
    python3-dev \
    mysql-client

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get install -y software-properties-common
RUN apt-add-repository ppa:ondrej/php -y \
    && apt-get update \
    && apt-get install -y \
    nginx \
    php8.1 php8.1-fpm php8.1-mysql php8.1-curl php8.1-gd php8.1-intl \
    php8.1-mbstring php8.1-soap php8.1-xml php8.1-zip php8.1-bcmath php8.1-imagick php8.1-xdebug php8.1-opcache

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

COPY .docker/conf/nginx /etc/nginx

COPY webroot /usr/share/nginx/webroot

EXPOSE 80