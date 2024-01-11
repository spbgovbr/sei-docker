################################################################################
# Dockerfile de construcao do container APP com os pacotes basicos
################################################################################

LABEL io.k8s.display-name="SEI - construcao do container APP com os pacotes basicos" \
      io.k8s.description="Imagem para construcao do container APP com os pacotes basicos do SEI." \
      maintainer="Sandro Tanaka <tanaka@redhat.com>" \
      vendor="Red Hat Inc" \
      sei-version="4.X.0"

ARG IMAGEM_BASE=supergovbr/base-centos8:latest
#ARG IMAGEM_BASE=quay.io/stanaka-redhat/sei-redhat-base-ubi:4.0.0

FROM ${IMAGEM_BASE}

ARG IMAGEM_APP_PACOTEMYSQL_PRESENTE=false
ARG IMAGEM_APP_PACOTESQLSERVER_PRESENTE=false
ARG IMAGEM_APP_PACOTEORACLE_PRESENTE=true

ENV TERM="xterm" \
    LANG="pt_BR.ISO-8859-1"

ADD assets /tmp/assets

RUN /tmp/assets/copy-packages.sh && \
    /tmp/assets/install.sh && \
    rm -rf /tmp/assets