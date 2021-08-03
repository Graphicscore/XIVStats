FROM php:7.4-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "xiv_stats.php", ">", "/target/xiv_stats_static.html"]