################################################################################
# Dockerfile de construcao do container APP com os pacotes basicos
################################################################################

ARG IMAGEM_BASE=supergovbr/base-app:latest

FROM ${IMAGEM_BASE}

ADD assets /tmp/assets

RUN /tmp/assets/install.sh && \ 
    rm -rf /tmp/assets

RUN sed -i 's+^post_max_size = 8M+post_max_size = 120M+g;s+^upload_max_filesize = 2M+upload_max_filesize = 100M+g;s+^short_open_tag = Off+short_open_tag = On+g;s+;mysqli.allow_local_infile = On+mysqli.allow_local_infile = On+g' /etc/php.ini &&    sed -i 's+pid = /run/php-fpm/php-fpm.pid+pid = /run/httpd/php-fpm.pid+g' /etc/php-fpm.conf &&    sed -i 's+listen = /run/php-fpm/www.sock+listen = 9000+g' /etc/php-fpm.d/www.conf &&    sed -i 's+SetHandler "proxy:unix:/run/php-fpm/www.sock|fcgi://localhost"+SetHandler "proxy:fcgi://127.0.0.1:9000"+g' /etc/httpd/conf.d/php.conf &&    sed -i 's+UserDir disabled+UserDir html+g;s+tory "/home/\*/public_html">+tory "/var/*/html">+g' /etc/httpd/conf.d/userdir.conf &&    echo "alias ll='ls -l'" >> /etc/bashrc &&    DOTHT_NUM=$(grep -n '^<Files ".ht' /etc/httpd/conf/httpd.conf | awk -F: '{print $1+1}')         sed -i "${DOTHT_NUM}s+Require all denied+Require all granted+g;s+#ServerName www.example.com:80+ServerName localhost+g" /etc/httpd/conf/httpd.conf &&    ln -sf /dev/stdout /var/log/httpd/access_log && ln -s /dev/stderr /var/log/httpd/error_log
RUN sed -i 's/;clear_env = no/clear_env = no/g' /etc/php-fpm.d/www.conf

EXPOSE 8000
CMD ["/command.sh"]
