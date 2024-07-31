FROM phpswoole/swoole:4.6-php7.4-alpine

# set timezone
ENV TZ=Asia/Shanghai
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/g' /etc/apk/repositories

#更新&下载必要程序
RUN apk update && apk add --no-cache --virtual .build-deps \
    g++ \
    git \
    vim \
    gcc  \
    autoconf \
    make  \
    curl  \
    libffi-dev \
    libpng-dev \
    libzip-dev \
    libmcrypt-dev \
    freetype-dev \
    libjpeg-turbo-dev


#下载php扩展
RUN docker-php-ext-install bcmath gd mysqli pdo pdo_mysql zip opcache sockets \
  && pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

RUN pecl install mongodb && rm -rf /tmp/pear && docker-php-ext-enable mongodb

##安装rabbitmq
RUN apk add --no-cache rabbitmq-c rabbitmq-c-dev \
  && pecl install amqp &&  rm -rf /tmp/pear && docker-php-ext-enable amqp

#清理无用缓存
RUN rm -rf /tmp/php-*

#安装 Composer
ENV COMPOSER_HOME /root/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH $COMPOSER_HOME/vendor/bin:$PATH
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
WORKDIR /var/www
