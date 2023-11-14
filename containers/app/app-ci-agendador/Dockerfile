################################################################################
# Dockerfile de construcao do container APP com os pacotes basicos
################################################################################

ARG IMAGEM_BASE=quay.io/stanaka-redhat/super-redhat-app-ci:1.0

FROM ${IMAGEM_BASE}

ADD assets /tmp/assets

ADD opt /opt

RUN chmod -R 777 /opt

RUN /tmp/assets/install.sh && \
    rm -rf /tmp/assets

ENTRYPOINT /entrypoint-agendador.sh
