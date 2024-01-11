#!/bin/bash

set -e

# Ajustes de permissões diversos para desenvolvimento do SUPER
chmod +x /opt/sei/bin/pdfboxmerge.jar
mkdir -p /opt/sip/temp
mkdir -p /opt/sei/temp
chmod -R 777 /opt/sei/temp
chmod -R 777 /opt/sip/temp
chmod -R 777 /var/sei/arquivos

cp /tmp/opt/* /opt

cp /tmp/assets/conf/sei.ini /etc/php.d/
cp /tmp/assets/conf/sei.conf /etc/httpd/conf.d/ 

cp /tmp/assets/scripts-e-automatizadores/entrypoint.sh /
cp /tmp/assets/scripts-e-automatizadores/entrypoint-atualizador.sh /

#/tmp/assets/scripts-e-automatizadores/clone-modules.sh

mkdir -p /sei/controlador-instalacoes/ /sei/arquivos_externos_sei/ /sei/certs
mkdir -p /sei/files/conf /sei/files/scripts-e-automatizadores/modulos
mv /tmp/assets/conf/ConfiguracaoSEI.php /sei/files/conf
mv /tmp/assets/conf/ConfiguracaoSip.php /sei/files/conf
mv /tmp/assets/scripts-e-automatizadores/openldap /tmp/assets/scripts-e-automatizadores/misc /sei/files/scripts-e-automatizadores/
mv /tmp/assets/scripts-e-automatizadores/modulos/* /sei/files/scripts-e-automatizadores/modulos/
