FROM centos:7

ENV TERM xterm

ADD https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/msttcore-fonts-2.0-3.noarch.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/oracle-instantclient12.2-basic-12.2.0.1.0-1.x86_64.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/oracle-instantclient12.2-devel-12.2.0.1.0-1.x86_64.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/oracle-instantclient12.2-sqlplus-12.2.0.1.0-1.x86_64.rpm \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/uploadprogress.tgz /sei/instaladores/
    #https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/libsodium-1.0.18-stable.tar.gz /sei/instaladores/
    

ADD files/ /sei/files/
    
RUN bash /sei/files/install.sh

RUN cp /sei/files/scripts-e-automatizadores/entrypoint.sh / && \
    cp /sei/files/scripts-e-automatizadores/entrypoint-atualizador.sh /  && \
    bash /sei/files/scripts-e-automatizadores/clone-modules.sh

EXPOSE 80
EXPOSE 443
