################################################################################
# Dockerfile de construcao do container APP com os pacotes basicos
################################################################################

ARG IMAGEM_BASE=processoeletronico/base-app:latest

FROM ${IMAGEM_BASE}

ADD assets /tmp/assets

RUN /tmp/assets/install.sh && \ 
    rm -rf /tmp/assets

EXPOSE 8000
CMD ["/command.sh"]