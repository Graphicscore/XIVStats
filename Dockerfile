FROM jtreminio/php-nginx:7.4

ENV DB_HOST=db
ENV DB_DATABASE=database
ENV DB_USER=user
ENV DB_PASSWORD=

#Copy Webdata
COPY . /app
#Copy Docker stuff
COPY ./docker/create_template_ini.sh /app/create_template_ini.sh
COPY ./docker/docker_entrypoint.sh /app/docker_entrypoint.sh
#Copy nginx host
COPY ./docker/default.vhost /etc/nginx/sites-enabled/default
WORKDIR /app
RUN chmod 755 create_template_ini.sh

EXPOSE 8080

CMD ["sh","-c","/app/docker_entrypoint.sh"]