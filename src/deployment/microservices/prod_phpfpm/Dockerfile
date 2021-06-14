FROM 331057292956.dkr.ecr.eu-west-2.amazonaws.com/core/phpfpm:7.4.13

#COPY composer.lock composer.json /var/www/
#COPY database /var/www/database

# NOTE: aws keys must not be included on this file
# NOTE: perhaps we should create a different Dockerfile for the aws cli containers so that we don't overload the php container
RUN apk update && apk upgrade && \
     apk -Uuv add python3 py-pip && \
     pip install awscli && \
     apk add openjdk8 && \
     apk add zip && \
     aws configure set aws_access_key_id AKIAU2FENM2OEGZBO5DT && \
     aws configure set aws_secret_access_key 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 && \
     aws configure set default_region_name eu-west-2 && \
     aws configure set default_output_format json && \
     mkdir -p /tmp/s3downloader


# Copy the application into the container, the .dockerignore file will prevent certain things being copied

COPY . /var/www

WORKDIR /var/www

RUN chmod +x artisan

# Install the necessary dependancies

RUN composer install --no-dev


# Ensure certain directories used by the fpm process have correct permissions

RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache \
        /tmp/s3downloader

RUN chmod -R 775 /var/www/storage

# Make sure application can view modules

RUN composer dump-autoload


# Make sure queue restart

RUN php artisan cache:clear
RUN php artisan queue:restart


COPY deployment/microservices/prod_phpfpm/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
