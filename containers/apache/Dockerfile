FROM ubuntu:xenial

ENV APACHE_DIR /etc/apache2

RUN apt-get update && apt-get install -y \
    apache2 \
    curl \
    git \
    libapache2-mod-php \
    php \
    php-cli \
    php-curl \
    php-dom \
    php-intl \
    php-json \
    php-ldap \
    php-mbstring \
    php-mcrypt \
    php-redis \
    php-zip \
    && phpenmod mcrypt \
    && apt-get clean

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod a+x /usr/local/bin/composer

WORKDIR $APACHE_DIR
RUN rm -f \
    	sites-enabled/000-default.conf \
    	conf-enabled/serve-cgi-bin.conf \
    	mods-enabled/autoindex.conf \
    	mods-enabled/autoindex.load

RUN ln -s $APACHE_DIR/mods-available/expires.load $APACHE_DIR/mods-enabled/expires.load \
    && ln -s $APACHE_DIR/mods-available/headers.load $APACHE_DIR/mods-enabled/headers.load \
	&& ln -s $APACHE_DIR/mods-available/rewrite.load $APACHE_DIR/mods-enabled/rewrite.load


COPY mocker.local.conf /etc/apache2/sites-enabled/
COPY mocker.testing.conf /etc/apache2/sites-enabled/

RUN rm -rf /var/www/html
COPY ./ /var/www/mocker

WORKDIR /var/www/mocker

EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]