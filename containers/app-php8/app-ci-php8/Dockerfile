################################################################################
# Dockerfile de construcao do container APP com os pacotes basicos
################################################################################

ARG IMAGEM_BASE=processoeletronico/base-app-php8:latest

FROM ${IMAGEM_BASE}

ENV LANG="pt_BR.ISO-8859-1"

ADD assets /tmp/assets

ARG GITPASS_REPO_MODULOS
ARG GITUSER_REPO_MODULOS

RUN /tmp/assets/install.sh && \
    rm -rf /tmp/assets

EXPOSE 80
EXPOSE 443