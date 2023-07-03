#!/usr/bin/env sh

# Configuração do pacote de línguas pt_BR
localedef pt_BR -i pt_BR -f ISO-8859-1

# Instalação do utilitários necessários para o provisionamento
yum -y install unzip java-1.7.0-openjdk wget unzip libreoffice libreoffice-headless

# Instalação da api de serviços de conversão de documentos
unzip /jodconverter-tomcat-2.2.2.zip -d /opt
rm -rf /jodconverter-tomcat-2.2.2.zip

# Remover arquivos temporários
yum clean all
rm -rf /var/cache/yum

# Configuração de permissões de execução no script de inicialização do container
chmod +x /entrypoint.sh

exit 0
