#FROM 331057292956.dkr.ecr.eu-west-2.amazonaws.com/proprli/terminal-vue:staging as node
#
## Install dependencies using npm
#RUN npm install
#
##Build the app
#RUN npm run build-staging

#==================== Setting up stage ====================
# Create image based on the official nginx - Alpine image
FROM 331057292956.dkr.ecr.eu-west-2.amazonaws.com/core/nginx:1.11.10

COPY public /var/www/public

#COPY --from=node /proprli-vue/dist/ /var/www/public/

#COPY --from=node /proprli-vue/dist/index.html /var/www/resources/views/spa.blade.php

COPY /public/index.html /var/www/resources/views/spa.blade.php

RUN rm -f /etc/nginx/sites-available/default.conf

COPY deployment/microservices/staging_nginx/sites/default.staging.conf /etc/nginx/sites-available/default.conf
COPY deployment/microservices/staging_nginx/sites/control.staging.conf /etc/nginx/sites-available/control.conf
COPY deployment/microservices/staging_nginx/sites/mq.staging.conf /etc/nginx/sites-available/mq.conf
COPY deployment/microservices/staging_nginx/sites/mail.staging.conf /etc/nginx/sites-available/mail.conf

COPY deployment/microservices/default-generic-nginx.conf /etc/nginx/nginx.conf

RUN ln -sf /var/www/storage/app/public /var/www/public/storage

# Ensure certain directories used by the fpm process have correct permissions

RUN ln -sf /var/www/storage/app/public /var/www/public/storage
