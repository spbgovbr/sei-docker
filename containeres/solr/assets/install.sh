#!/usr/bin/env sh

set -e

# Instalação do pacote Java JDK e utilitários utilizados no provisionamento
#apk update && apk add lsof curl bash openjdk8-jre
yum -y update && yum -y install lsof curl wget java-1.8.0-openjdk

# Instalação de pacote de fontes do windows
rpm -Uvh /tmp/assets/msttcore-fonts-2.0-3.noarch.rpm

# Configuração do pacote de línguas pt_BR
localedef pt_BR -i pt_BR -f ISO-8859-1

# Download do Solr, versão8.2.00
SOLR_URL=https://archive.apache.org/dist/lucene/solr/8.2.0/solr-8.2.0.tgz
curl $SOLR_URL -o /tmp/solr-8.2.0.tgz

mv /tmp/assets/solr8sei/* /tmp/

useradd solr

chmod +x /tmp/sei-solr-8.2.0.sh

# Instalação do Apache Sol2
sh /tmp/sei-solr-8.2.0.sh

mv /tmp/assets/entrypoint.sh /

# Remover arquivos temporário
ls -lh /tmp

rm -rf /tmp/assets && \
rm -rf /tmp/log4j.properties && \
rm -rf /tmp/sei-cores-8.2.0 && \
rm -rf /tmp/sei-solr-8.2.0.sh && \
rm -rf /tmp/solr-8.2.0 && \
rm -rf /tmp/solr-8.2.0.tgz && \
rm -rf /tmp/solr.service

yum clean all

# Configuração de permissões de execução no script de inicialização do container
chmod +x /entrypoint.sh

exit 0
