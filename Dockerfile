FROM matriphe/alpine-php:fpm-5.6
MAINTAINER Kukua Team <dev@kukua.cc>

ENV TIMEZONE Europe/Amsterdam
RUN apk add --update tzdata && \
	cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && \
	echo "${TIMEZONE}" > /etc/timezone && \
	sed -i -e "s/user\s*=\s*nobody/user = 1000/g" /etc/php5/php-fpm.conf && \
	sed -i -e "s/group\s*=\s*nobody/group = 1000/g" /etc/php5/php-fpm.conf && \
	sed -i "s|;date.timezone =.*|date.timezone = ${TIMEZONE}|" /etc/php5/php.ini

RUN apk --update add wget curl git php5-phar php5-dom && rm /var/cache/apk/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
