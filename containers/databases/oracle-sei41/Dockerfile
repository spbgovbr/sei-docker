################################################################################
# Dockerfile de construção do container Oracle utilizado pelo SEI
#
# Container preparado e configurado para uso em desenvolvimento e testes
# construa antes a imagem base
################################################################################

ARG IMAGEM_BASE=processoeletronico/base-oracle11g:latest

FROM ${IMAGEM_BASE}

ARG GIT_DB_REF=https://github.com/spbgovbr/sei-db-ref-executivo/raw/master

ENV TERM xterm \
    ORACLE_ALLOW_REMOTE=true \
    NLS_LANG=PORTUGUESE_BRAZIL.WE8MSWIN1252

ADD ${GIT_DB_REF}/oracle/v4.1.0/sei_4_1_0_BD_Ref_Exec.dmp \
    ${GIT_DB_REF}/oracle/v4.1.0/sip_4_1_0_BD_Ref_Exec.dmp \
    assets/pre-install.sql \
    assets/sei-config.sql \
    assets/sip-config.sql \
    assets/install.sh \
    /tmp/

RUN bash /tmp/install.sh
