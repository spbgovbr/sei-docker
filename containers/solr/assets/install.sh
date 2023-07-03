#!/usr/bin/env sh

set -e

# Instalação do pacote Java JDK e utilitários utilizados no provisionamento
#apk update && apk add lsof curl bash openjdk8-jre
yum -y update && yum -y install lsof curl wget java-1.8.0-openjdk

# Instalação de pacote de fontes do windows
rpm -Uvh /tmp/msttcore-fonts-2.0-3.noarch.rpm

# Configuração do pacote de línguas pt_BR
localedef pt_BR -i pt_BR -f ISO-8859-1

# join splited solr
cat /tmp/solr-8.2.0.tgz.part* > /tmp/solr-8.2.0.tgz

mv /tmp/solr8sei/* /tmp/

useradd solr

chmod +x /tmp/sei-solr-8.2.0.sh

# Instalação do Apache Sol2
sh /tmp/sei-solr-8.2.0.sh

mv /tmp/command.sh /

yum clean all
rm -rf /var/cache/yum

# Configuração de permissões de execução no script de inicialização do container
chmod +x /command.sh

exit 0
