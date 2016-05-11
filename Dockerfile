# Dockerfile for autobuild example 
FROM ubuntu:14.04
MAINTAINER Jim Witte <jwitte@illinois.edu>

EXPOSE 80 443

# Let the container know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

VOLUME ["/var/www/html"]
ADD ./app /var/www/html

ADD ./foreground.sh /etc/apache2/foreground.sh

RUN apt-get update && \
	apt-get -y install mysql-client pwgen python-setuptools curl git unzip apache2 php5 \
		php5-gd libapache2-mod-php5 postfix wget supervisor php5-pgsql curl libcurl3 \
		libcurl3-dev php5-curl php5-xmlrpc php5-intl php5-mysql git-core && \
	rm /var/www/html/index.html && \
	chown -R www-data:www-data /var/www/html && \
	mkdir /var/www/html/views/configs && \
	mkdir /var/www/html/views/cache && \
	mkdir /var/www/html/views/templates_c && \
	chmod a+w /var/www/html/views/cache -R && \
	chmod a+w /var/www/html/views/templates_c -R && \
	chmod +x /etc/apache2/foreground.sh

# Enable SSL
# RUN a2enmod ssl && a2ensite default-ssl # if using proxy, don't need actually secure connection


CMD ["/etc/apache2/foreground.sh"]


