#!/bin/bash

set -e

mv /tmp/assets/scripts-e-automatizadores/command.sh \
   /tmp/assets/scripts-e-automatizadores/ConfiguracaoSip.php \
   /tmp/assets/scripts-e-automatizadores/ConfiguracaoSEI.php \
   /

chmod +x /command.sh

yum -y update


dnf install --nogpgcheck https://mirrors.rpmfusion.org/free/el/rpmfusion-free-release-8.noarch.rpm -y
dnf install --nogpgcheck https://mirrors.rpmfusion.org/nonfree/el/rpmfusion-nonfree-release-8.noarch.rpm -y
# Instalação de ferramentas utilitárias e dependências do SUPER
yum install -y libmcrypt httpd memcached openssl wget curl unzip gcc java-1.8.0-openjdk \
               libxml2 crontabs mysql net-tools vim git nc ffmpeg


# Instalação do XDebug, versão 3
pecl install xdebug-3.0.4

# Configuração de permissão do diretório de arquivos
mkdir -p /var/sei/arquivos
chmod -R 777 /var/sei/arquivos

# Configuração dos serviços de background do Cron
mkdir /var/log/sei
sed -i '/session    required   pam_loginuid.so/c\#session    required   pam_loginuid.so' /etc/pam.d/crond

mkdir -p /etc/cron.d/sei

cp /tmp/assets/conf/info.php /var/www/html/
cp /tmp/assets/conf/sei.ini /etc/php.d/
cp /tmp/assets/conf/sei.conf /etc/httpd/conf.d/
cp /tmp/assets/conf/deflate.conf /etc/httpd/conf.d/
cp /tmp/assets/conf/cron.conf /etc/cron.d/
cp /tmp/assets/conf/xdebug.ini /etc/php.d/

# Remover arquivos temporários
yum clean all
rm -rf /var/cache/yum
