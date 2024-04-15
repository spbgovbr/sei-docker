################################################################################
# Dockerfile de construcao do container Solr utilizado pelo SEI
#
# Container preparado e configurado para uso em desenvolvimento e testes
################################################################################

ARG IMAGEM_BASE=processoeletronico/base-rocky93:latest

FROM ${IMAGEM_BASE}

ENV TERM xterm

ADD assets/ \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/solr-9.4.0.tgz.partaa \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/solr-9.4.0.tgz.partab \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/solr-9.4.0.tgz.partac \
    https://github.com/spbgovbr/sei-docker-binarios/raw/main/pacoteslinux/msttcore-fonts-2.0-3.noarch.rpm \
    /tmp/

RUN ls -lh /tmp && sh /tmp/install.sh && rm -rf /tmp/*

USER solr

EXPOSE 8983
CMD ["/command.sh"]
