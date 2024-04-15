################################################################################
# Dockerfile de construcao do container APP com os pacotes basicos
################################################################################

ARG IMAGEM_BASE=processoeletronico/app-ci-php8:latest

FROM ${IMAGEM_BASE}

ADD assets /tmp/assets


RUN /tmp/assets/install.sh && \
    rm -rf /tmp/assets

ENTRYPOINT /entrypoint-agendador.sh
