# Dockerfile for autobuild example 
FROM ubuntu:14.04
MAINTAINER Jim Witte <jwitte@illinois.edu>

EXPOSE 80 443

# Let the container know that there is no tty
ENV DEBIAN_FRONTEND noninteractive

ADD ./app /var/www/html
ADD ./foreground.sh /etc/apache2/foreground.sh

RUN apt-get update && \
	apt-get -y install  apache2 php5 && \
	rm /var/www/html/index.html && \
	chown -R www-data:www-data /var/www/html && \
	chmod +x /etc/apache2/foreground.sh

# Enable SSL
# RUN a2enmod ssl && a2ensite default-ssl # if using proxy, don't need actually secure connection


CMD ["/etc/apache2/foreground.sh"]


