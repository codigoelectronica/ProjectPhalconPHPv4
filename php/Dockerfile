#Usaremos como imagen base la de php, para este caso la 7.2.8-fpm
FROM php:7.4-apache

MAINTAINER Código Electrónica <codigoelectronica@gmail.com>

#Instalamos las dependencias de php
RUN apt-get update && apt-get install -y libpq-dev && \
        docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
        docker-php-ext-install pdo pdo_pgsql

#Creamos la variable de la version de phalcon que vamos a usar
ARG PSR_VERSION=0.6.1

ARG PHALCON_VERSION=4.1.2
ARG PHALCON_DEVTOOLS_VERSION=4.1.1
ARG PHALCON_EXT_PATH=php7/64bits

#Como realizaremos varias descargas, nos ubicamos en la carpeta tmp de la imagen de PHP
WORKDIR /var/tmp

#Realizamos la instalación de composer para el manejo de las dependencias
RUN curl -sS https://getcomposer.org/installer | php && \
	mv composer.phar /usr/local/bin/composer

#Install Source Dependancias
RUN apt-get -y install unzip

# Compile PSR
RUN set -xe && \
        curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
        tar xzf v${PSR_VERSION}.tar.gz && \
        cd php-psr-${PSR_VERSION} && \
        phpize && \
        ./configure && \
        make && \
        make test && \
        make install && cd modules && echo "extension=psr.so" > /usr/local/etc/php/conf.d/psr.ini && \
        cd .. && rm -rf v${PSR_VERSION}.tar.gz php-psr-${PSR_VERSION} 

#Descargamos la libreria de phalcon con su herramienta phalcon dev tools
RUN set -xe && \
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf v${PHALCON_VERSION}.tar.gz && cd cphalcon-${PHALCON_VERSION}/build && ./install && \
        echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini && \
        cd ../.. && rm -rf v${PHALCON_VERSION}.tar.gz cphalcon-${PHALCON_VERSION}
        # Insall Phalcon Devtools, see https://github.com/phalcon/phalcon-devtools/
#Copiar php.ini
COPY php.ini /usr/local/etc/php/conf.d

# Enable Apache modules
RUN a2enmod headers rewrite

WORKDIR /usr/local/phalcon-devtools
RUN set -xe && curl -LO https://github.com/phalcon/phalcon-devtools/archive/v${PHALCON_DEVTOOLS_VERSION}.tar.gz && \
        tar xzf v${PHALCON_DEVTOOLS_VERSION}.tar.gz &&  \
        cd phalcon-devtools-${PHALCON_DEVTOOLS_VERSION} && composer install && ln -s $(pwd)/phalcon /usr/bin/phalcon && \
        chmod ugo+x /usr/bin/phalcon && alias phalcon=/usr/local/phalcon-devtools/phalcon-devtools-${PHALCON_DEVTOOLS_VERSION}/phalcon


#Nos posicionamos en el directorio que deseamos trabajar
RUN chown -R www-data:www-data /var/www/html
WORKDIR /var/www/html