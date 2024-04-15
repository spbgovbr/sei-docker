#!/bin/bash

set -e

yum update -y
dnf install -y https://dl.fedoraproject.org/pub/epel/epel-release-latest-9.noarch.rpm
dnf install -y https://rpms.remirepo.net/enterprise/remi-release-9.rpm
dnf install -y https://dl.fedoraproject.org/pub/epel/epel-next-release-latest-9.noarch.rpm

dnf module install -y php:remi-8.2
yum install --skip-broken -y httpd memcached openssl wget zip unzip gcc \
                             java-1.8.0-openjdk libxml2 cabextract fontconfig mod_ssl vim

dnf install --skip-broken -y php php-cli php-common php-mysql php-pear php-bcmath php-gd php-gmp php-imap php-intl     php-ldap     php-mbstring     php-odbc     php-pdo     php-pecl-apcu     php-pspell     php-zlib     php-snmp     php-soap     php-xml     php-xmlrpc     php-zts     php-devel     php-pecl-apcu-devel     php-pecl-memcache     php-calendar     php-shmop     php-intl     php-mcrypt     php-zip     php-pecl-zip
dnf install -y php-pecl-gearman
dnf install --skip-broken -y libgearman libgearman-devel php-sodium  git gearmand libgearman-dev libgearman-devel

yum install -y xorg-x11-fonts-75dpi

mkdir -p /run/php-fpm

yum -y install glibc-locale-source diffutils
localedef pt_BR -i pt_BR -f ISO-8859-1
localedef pt_BR.ISO-8859-1 -i pt_BR -f ISO-8859-1
localedef pt_BR.ISO8859-1 -i pt_BR -f ISO-8859-1

dnf install -y --nogpgcheck https://mirrors.rpmfusion.org/free/el/rpmfusion-free-release-$(rpm -E %rhel).noarch.rpm
dnf install -y https://mirrors.rpmfusion.org/nonfree/el/rpmfusion-nonfree-release-$(rpm -E %rhel).noarch.rpm
dnf install -y ffmpeg


cd /tmp/assets/pacotes

# Instalação do componentes UploadProgress
tar -zxvf uploadprogress-2.0.2.tgz
cd uploadprogress-2.0.2
phpize
./configure --enable-uploadprogress
make
make install
echo "extension=uploadprogress.so" > /etc/php.d/uploadprogress.ini
cd -

# fonts libraries
rpm -Uvh msttcore-fonts-2.0-3.noarch.rpm

# wkhtml
rpm -Uvh wkhtmltox-0.12.6.1-2.almalinux9.x86_64.rpm

cp /tmp/assets/sei.ini /etc/php.d
cp /tmp/assets/sei.conf /etc/httpd/conf.d/

#mkdir -p /sei/certs/seiapp
#cd /sei/certs/seiapp
#openssl genrsa -out sei-ca-key.pem 2048
#openssl req -x509 -new -nodes -key sei-ca-key.pem -days 10000 -out sei-ca.pem -subj "/CN=sei-dev"
#openssl genrsa -out sei.key 2048
#openssl req -new -key sei.key -out sei.csr -subj "/CN=myname"
#openssl x509 -req -days 365 -CA sei-ca.pem -CAkey sei-ca-key.pem -in sei.csr  -out sei.crt
#cat /sei/certs/seiapp/sei-ca.pem >> /etc/ssl/certs/cacert.pem
#cp sei.crt /etc/pki/tls/certs/sei.crt
#cp sei-ca.pem /etc/pki/tls/certs/sei-ca.pem
#cp sei.key /etc/pki/tls/private/sei.key
#cat sei.crt sei.key >> /etc/pki/tls/certs/sei.pem
#cp sei-ca.pem /etc/pki/ca-trust/source/anchors/
#openssl genrsa -out localhost.key 2048
#openssl req -new -key localhost.key  -out localhost.csr -subj "/CN=myname"
#openssl x509 -req -days 365 -in localhost.csr  -signkey localhost.key  -out localhost.crt
#cp localhost.key /etc/pki/tls/private/
#cp localhost.crt /etc/pki/tls/certs/
#
#update-ca-trust extract
#update-ca-trust enable
#
#mkdir -p /opt/sei/temp
#mkdir -p /opt/sip/temp
#mkdir /dados
#chown apache /dados
#chown apache /opt/sei/temp /opt/sip/temp