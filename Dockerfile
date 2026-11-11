FROM php:8.2-apache

# Install mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project files into Apache web root
COPY ["RestWell project/", "/var/www/html/"]

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Default port
ENV PORT=80
EXPOSE 80

# Dynamically set Apache listening port to $PORT (assigned by Vercel / Cloud runtime)
RUN printf '#!/bin/sh\nset -e\nPORT="${PORT:-80}"\nsed -i "s/Listen .*/Listen $PORT/" /etc/apache2/ports.conf\nsed -i "s/<VirtualHost \\*:[0-9]*>/<VirtualHost \\*:$PORT>/" /etc/apache2/sites-available/000-default.conf\nexec apache2-foreground\n' > /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]
