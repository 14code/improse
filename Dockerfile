FROM php:7.4-cli-alpine
RUN apk --no-cache update && apk --no-cache upgrade
RUN docker-php-ext-install exif \
   && docker-php-ext-enable exif
COPY . /app
WORKDIR /app
CMD [ "php", "-S", "0.0.0.0:8080", "-t" ,"./public/" ]
