################################################################################
# Dockerfile de construcao do container Solr utilizado pelo SEI e pelo SIP 
#
# Container preparado e configurado para uso em desenvolvimento e testes
################################################################################

#TODO: Migrar para container oficial do Java 8 (8-jdk-alpine)
FROM centos:centos7
LABEL maintainer="Guilherme Andrade Del Cantoni <guilherme.cantoni@economia.gov.br>, Marcelo Linhares Castro <marlinhares@gmail.com>"

ENV TERM xterm

ADD https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/msttcore-fonts-2.0-3.noarch.rpm \
    assets /tmp/assets/

RUN sh /tmp/assets/install.sh

EXPOSE 8983
CMD ["/entrypoint.sh"]
