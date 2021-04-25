#!/usr/bin/env bash

set -e

yum clean all
yum -y  update

# Instalação dos componentes básicos do servidor web apache
yum -y install httpd memcached openssl wget curl unzip gcc java-1.8.0-openjdk libxml2 cabextract xorg-x11-font-utils fontconfig mod_ssl

# Instalação do PHP e demais extenções necessárias para o projeto
yum install -y epel-release yum-utils
yum install -y http://rpms.remirepo.net/enterprise/remi-release-7.rpm
yum-config-manager --enable remi-php73
yum -y update

# Instalação do PHP e demais extenções necessárias para o projeto
yum -y install php php-common php-cli php-pear php-bcmath php-gd php-gmp php-imap php-intl php-ldap php-mbstring php-mysqli \
    php-odbc php-pdo php-pecl-apcu php-pspell php-zlib php-snmp php-soap php-xml php-xmlrpc php-zts php-devel \
    php-pecl-apcu-devel php-pecl-memcache php-calendar php-shmop php-intl php-mcrypt \
    gearmand libgearman libgearman-devel php-pecl-gearman vixie-cron \
    freetds freetds-devel php-mssql \
    git nc gearmand libgearman-dev libgearman-devel mysql

# Configuração do pacote de línguas pt_BR
localedef pt_BR -i pt_BR -f ISO-8859-1

# Instalação do componentes UploadProgress

cd /files
tar -zxvf uploadprogress.tgz
cd uploadprogress
phpize
./configure --enable-uploadprogress
make
make install
echo "extension=uploadprogress.so" > /etc/php.d/uploadprogress.ini
cd -

cp /files/sei.ini /etc/php.d/
cp /files/sei.conf /etc/httpd/conf.d/ 

# Configuração das bibliotecas de fontes utilizadas pelo SEI
cd /files
rpm -Uvh msttcore-fonts-2.0-3.noarch.rpm
rm -f msttcore-fonts-2.0-3.noarch.rpm

#bash /files/install_oracle.sh
# ORACLE oci
mkdir -p /opt/oracle \
cd /opt/oracle

cp /files/oracle-instantclient12.2-basic-12.2.0.1.0-1.x86_64.rpm /opt/oracle
cp /files/oracle-instantclient12.2-devel-12.2.0.1.0-1.x86_64.rpm /opt/oracle
cp /files/oracle-instantclient12.2-sqlplus-12.2.0.1.0-1.x86_64.rpm /opt/oracle

yum install -y oracle-instantclient12.2-basic-12.2.0.1.0-1.x86_64.rpm oracle-instantclient12.2-devel-12.2.0.1.0-1.x86_64.rpm oracle-instantclient12.2-sqlplus-12.2.0.1.0-1.x86_64.rpm

cd -

echo /usr/lib/oracle/12.2/client64/lib > /etc/ld.so.conf.d/oracle-instantclient.conf
ldconfig

# Install Oracle extensions
yum install -y php-dev php-pear build-essential systemtap-sdt-devel 
pecl channel-update pecl.php.net 
export PHP_DTRACE=yes && pecl install oci8-2.2.0 && unset PHP_DTRACE

#echo 'instantclient,/opt/oracle/instantclient_12_1/' | pecl install oci8-2.2.0

echo "extension=oci8.so" > /etc/php.d/oci8.ini 

rm -rf /files

mkdir -p /dados
mkdir -p /controlador

yum -y clean all

exit 0
