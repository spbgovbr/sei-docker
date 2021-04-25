FROM centos:7

ENV TERM xterm

ADD https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/msttcore-fonts-2.0-3.noarch.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/oracle-instantclient12.2-basic-12.2.0.1.0-1.x86_64.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/oracle-instantclient12.2-devel-12.2.0.1.0-1.x86_64.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/oracle-instantclient12.2-sqlplus-12.2.0.1.0-1.x86_64.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/uploadprogress.tgz \
    files/conf/sei.ini \
    files/conf/sei.conf \
    files/install.sh /files/

RUN bash /files/install.sh 

COPY files/scripts-e-automatizadores/clone-modules.sh \
     files/conf/ConfiguracaoSEI.php \
     files/conf/ConfiguracaoSip.php \
     files/scripts-e-automatizadores/wait-for-it.sh \
     files/scripts-e-automatizadores/entrypoint.sh \
     files/scripts-e-automatizadores/entrypoint-atualizador.sh \
     files/scripts-e-automatizadores/sei_gov_configurar_ambiente.php /files/

RUN cp /files/entrypoint.sh / && \
    cp /files/entrypoint-atualizador.sh /  && \
    bash /files/clone-modules.sh

VOLUME /controlador
EXPOSE 80
EXPOSE 443

