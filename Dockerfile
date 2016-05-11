# Dockerfile for autobuild example 
FROM ubuntu:14.04
MAINTAINER Jim Witte <jwitte@illinois.edu>

EXPOSE 80 443

# Let the container know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

ADD ./foreground.sh /etc/apache2/foreground.sh

RUN apt-get update && \
	apt-get -y install mysql-client pwgen python-setuptools curl git unzip apache2 php5 \
		php5-gd libapache2-mod-php5 postfix wget supervisor php5-pgsql curl libcurl3 \
		libcurl3-dev php5-curl php5-xmlrpc php5-intl php5-mysql git-core && \
	cd /tmp && \
	git clone https://github.com/jimwitte/hammurabi.git && \
	mv /tmp/hammurabi/* /var/www/html/ && \
	rm /var/www/html/index.html && \
	chown -R www-data:www-data /var/www/html && \
	chmod +x /etc/apache2/foreground.sh

# Enable SSL
# RUN a2enmod ssl && a2ensite default-ssl # if using proxy, don't need actually secure connection

VOLUME ["/var/www/html"]

CMD ["/etc/apache2/foreground.sh"]


