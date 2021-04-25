#!/bin/bash

# ENVIRONMENT VARIABLES
# $APP_PROTOCOLO
# $APP_HOST
# $APP_ORGAO
# $APP_ORGAO_DESCRICAO
# $APP_NOMECOMPLEMENTO
# $APP_DB_TIPO
# $APP_DB_PORTA
# $APP_DB_SIP_USERNAME
# $APP_DB_SIP_PASSWORD
# $APP_DB_SEI_USERNAME
# $APP_DB_SEI_PASSWORD
# $APP_DB_ROOT_USERNAME
# $APP_DB_ROOT_PASSWORD

set -e

if [ -z "$APP_PROTOCOLO" ] || \
   [ -z "$APP_HOST" ] || \
   [ -z "$APP_ORGAO" ] || \
   [ -z "$APP_ORGAO_DESCRICAO" ] || \
   [ -z "$APP_NOMECOMPLEMENTO" ] || \
   [ -z "$APP_DB_TIPO" ] || \
   [ -z "$APP_DB_PORTA" ] || \
   [ -z "$APP_DB_SEI_BASE" ] || \
   [ -z "$APP_DB_SIP_BASE" ] || \
   [ -z "$APP_DB_SIP_USERNAME" ] || \
   [ -z "$APP_DB_SIP_PASSWORD" ] || \
   [ -z "$APP_DB_SEI_USERNAME" ] || \
   [ -z "$APP_DB_SEI_PASSWORD" ] || \
   [ -z "$APP_DB_ROOT_USERNAME" ] || \
   [ -z "$APP_DB_ROOT_PASSWORD" ]; then
    echo "Informe as seguinte variÃ¡veis de ambiente no seu docker-compose ou no container:"
    echo "APP_PROTOCOLO=$APP_PROTOCOLO"
    echo "APP_HOST=$APP_HOST"
    echo "APP_ORGAO=$APP_ORGAO"
    echo "APP_ORGAO_DESCRICAO=$APP_ORGAO_DESCRICAO"
    echo "APP_NOMECOMPLEMENTO=$APP_NOMECOMPLEMENTO"
    echo "APP_DB_TIPO=$APP_DB_TIPO (deve ser MySql, Sqlserver ou Oracle)"
    echo "APP_DB_PORTA=$APP_DB_PORTA"
    echo "APP_DB_SIP_BASE=$APP_DB_SIP_BASE"
    echo "APP_DB_SEI_BASE=$APP_DB_SEI_BASE"
    echo "APP_DB_SIP_USERNAME=$APP_DB_SIP_USERNAME"
    echo "APP_DB_SIP_PASSWORD=$APP_DB_SIP_PASSWORD"
    echo "APP_DB_SEI_USERNAME=$APP_DB_SEI_USERNAME"
    echo "APP_DB_SEI_PASSWORD=$APP_DB_SEI_PASSWORD"
    echo "APP_DB_ROOT_USERNAME=$APP_DB_ROOT_USERNAME"
    echo "APP_DB_ROOT_PASSWORD=$APP_DB_ROOT_PASSWORD"

    exit 1
fi


APP_HOST_URL=$APP_PROTOCOLO://$APP_HOST

echo "127.0.0.1 $APP_HOST" >> /etc/hosts

# Direciona logs para saida padrÃ£o para utilizar docker logs
ln -sf /dev/stdout /var/log/httpd/access_log
ln -sf /dev/stdout /var/log/httpd/ssl_access_log
ln -sf /dev/stdout /var/log/httpd/ssl_request_log
ln -sf /dev/stderr /var/log/httpd/error_log
ln -sf /dev/stderr /var/log/httpd/ssl_error_log

# baixar os fontes do sei
cd /opt
#rm *
#git checkout $APP_SEI_VERSAO
touch putsourcefileshere.empty


# AtribuiÃ§Ã£o dos parÃ¢metros de configuraÃ§Ã£o do SEI
if [ -f /opt/sei/config/ConfiguracaoSEI.php ] && [ ! -f /opt/sei/config/ConfiguracaoSEI.php~ ]; then
    mv /opt/sei/config/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php~
fi

if [ ! -f /opt/sei/config/ConfiguracaoSEI.php ]; then
    cp /files/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php
fi

# AtribuiÃ§Ã£o dos parÃ¢metros de configuraÃ§Ã£o do SIP
if [ -f /opt/sip/config/ConfiguracaoSip.php ] && [ ! -f /opt/sip/config/ConfiguracaoSip.php~ ]; then
    mv /opt/sip/config/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php~
fi

if [ ! -f /opt/sip/config/ConfiguracaoSip.php ]; then
    cp /files/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php
fi

# Ajustes de permissÃµes diversos para desenvolvimento do SEI
#chmod +x /opt/sei/bin/wkhtmltopdf-amd64
chmod +x /opt/sei/bin/pdfboxmerge.jar
mkdir -p /opt/sei/temp
mkdir -p /opt/sip/temp
chmod -R 777 /opt/sei/temp
chmod -R 777 /opt/sip/temp
chmod -R 777 /dados

echo "Testing for Atualizador..."
#/sei-ci/docker/files/sei/wait-for-it.sh -s -t 600 http-atualizador:8181 -- echo "Atualizador Finalizado. Procedendo..."

while [ ! -f /controlador/instalado.ok ]
do
    echo "Aguardando conteiner atualizador instalar e atualizar o SEI e mÃdulos"
    sleep 5 
done

echo "Atualizador finalizado procedendo com a subida do apache..."

#atualizar
/usr/sbin/httpd -DFOREGROUND &
sleep 3

echo "Apache no ar..."

#copia modulos se houver
#mkdir -p /opt/sei/web/modulos
#rm -rf /opt/sei/web/modulos/*
#cp -a /opt/sei-fontes-modulos/* /opt/sei/web/modulos/


#***************************************************************************************
# install Estatistica
#***************************************************************************************
if [ ! -z "$MODULO_ESTATISTICAS_INSTALAR" ]; then
    
    echo "Vamos iniciar o tratamento de instalacao do modulo de estatisticas"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    
    if [ "$MODULO_ESTATISTICAS_INSTALAR" = "true" ]; then

        if [ -z "$MODULO_ESTATISTICAS_VERSAO" ] || \
           [ -z "$MODULO_ESTATISTICAS_URL" ] || \
	       [ -z "$MODULO_ESTATISTICAS_SIGLA" ] || \
	       [ -z "$MODULO_ESTATISTICAS_CHAVE" ]; then
            echo "Informe as seguinte variáveis de ambiente no seu docker-compose ou no container:"
            echo "MODULO_ESTATISTICAS_VERSAO, MODULO_ESTATISTICAS_URL, MODULO_ESTATISTICAS_SIGLA, MODULO_ESTATISTICAS_CHAVE"

            exit 1
        fi
        
        echo "Verificando existencia do modulo de estatísticas"
        if [ -d "/opt/sei/web/modulos/mod-sei-estatisticas" ]; then
            echo "Ja existe um diretorio para o modulo de estatisticas. Vamos assumir que o codigo la esteja integro"
        
        else
            echo "Copiando o módulo de estatísticas"
            cp -Rf /sei-modulos/mod-sei-estatisticas /opt/sei/web/modulos/
        fi

        cd /opt/sei/web/modulos/mod-sei-estatisticas
        git checkout $MODULO_ESTATISTICAS_VERSAO
        echo "Versao do Governanca é agora: $MODULO_ESTATISTICAS_VERSAO"

        cd /opt/sei/
        
        sed -i "s#/\*novomodulo\*/#'MdEstatisticas' => 'mod-sei-estatisticas', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php
        sed -i "s#/\*extramodulesconfig\*/#'MdEstatisticas' => array('url' => '$MODULO_ESTATISTICAS_URL','sigla' => '$MODULO_ESTATISTICAS_SIGLA','chave' => '$MODULO_ESTATISTICAS_CHAVE'), /\*extramodulesconfig\*/#g" config/ConfiguracaoSEI.php
        
    else
        echo "Pulando instalacao do modulo de estatisticas, valor existe mas esta diferente de true"
    fi
    
    echo "Fim do tratamento de instalacao do modulo de estatisticas"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"

fi


#***************************************************************************************
# install WSSEI
#***************************************************************************************
if [ ! -z "$MODULO_WSSEI_INSTALAR" ]; then
    
    echo "Vamos iniciar o tratamento de instalacao do modulo wssei"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    
    if [ "$MODULO_WSSEI_INSTALAR" = "true" ]; then

        if [ -z "$MODULO_WSSEI_VERSAO" ] || \
           [ -z "$MODULO_WSSEI_URL_NOTIFICACAO" ] || \
	       [ -z "$MODULO_WSSEI_ID_APP" ] || \
	       [ -z "$MODULO_WSSEI_CHAVE" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_WSSEI_VERSAO, MODULO_WSSEI_URL_NOTIFICACAO, MODULO_WSSEI_ID_APP, MODULO_WSSEI_CHAVE"

            exit 1
        fi
        
        echo "Verificando existencia do modulo wssei"
        if [ -d "/opt/sei/web/modulos/mod-wssei" ]; then
            echo "Ja existe um diretorio para o modulo wssei. Vamos assumir que o codigo la esteja integro"
        
        else
            echo "Copiando o módulo wssei"
            cp -Rf /sei-modulos/mod-wssei /opt/sei/web/modulos/
        fi
        

        cd /opt/sei/web/modulos/mod-wssei
        git checkout $MODULO_WSSEI_VERSAO
        echo "Versao do WSSEI é agora: $MODULO_WSSEI_VERSAO" 

        cd /opt/sei/
        sed -i "s#/\*novomodulo\*/#'MdWsSeiRest' => 'mod-wssei/', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php
        sed -i "s#/\*extramodulesconfig\*/#'WSSEI' => array('UrlServicoNotificacao' => '$MODULO_WSSEI_URL_NOTIFICACAO', 'IdApp' => '$MODULO_WSSEI_ID_APP', 'ChaveAutorizacao' => '$MODULO_WSSEI_CHAVE', 'TokenSecret' => '504CE1E9-8913-488F-AB3E-EDDABC065B0B'  ), /\*extramodulesconfig\*/#g" config/ConfiguracaoSEI.php

        

    else
        echo "Pulando instalacao do modulo wssei, valor existe mas esta diferente de true"
    fi
    
    echo "Fim do tratamento de instalacao do modulo wssei"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    echo "------------------------------------------------------------------"
    
fi


tail -f /dev/null

