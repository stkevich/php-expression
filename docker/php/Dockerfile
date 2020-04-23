FROM php:7.4-fpm

WORKDIR /code

RUN apt-get update -y 
RUN apt-get install -y libxml2-dev libfreetype6-dev libjpeg62-turbo-dev git net-tools
RUN apt-get clean -y
RUN docker-php-ext-install bcmath pcntl
RUN docker-php-ext-enable pcntl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini


RUN yes | pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY ./xdebug/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini