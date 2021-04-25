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
    echo "Informe as seguinte variáveis de ambiente no seu docker-compose ou no container:"
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

# Direciona logs para saida padrão para utilizar docker logs
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


# Atribuição dos parâmetros de configuração do SEI
if [ -f /opt/sei/config/ConfiguracaoSEI.php ] && [ ! -f /opt/sei/config/ConfiguracaoSEI.php~ ]; then
    mv /opt/sei/config/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php~
fi

if [ ! -f /opt/sei/config/ConfiguracaoSEI.php ]; then
    cp /files/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php
fi

# Atribuição dos parâmetros de configuração do SIP
if [ -f /opt/sip/config/ConfiguracaoSip.php ] && [ ! -f /opt/sip/config/ConfiguracaoSip.php~ ]; then
    mv /opt/sip/config/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php~
fi

if [ ! -f /opt/sip/config/ConfiguracaoSip.php ]; then
    cp /files/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php
fi

# Ajustes de permissões diversos para desenvolvimento do SEI
#chmod +x /opt/sei/bin/wkhtmltopdf-amd64
chmod +x /opt/sei/bin/pdfboxmerge.jar
mkdir -p /opt/sei/temp
mkdir -p /opt/sip/temp
chmod -R 777 /opt/sei/temp
chmod -R 777 /opt/sip/temp
chmod -R 777 /dados

echo "Testing for Atualizador..."
#/sei-ci/docker/files/sei/wait-for-it.sh -s -t 600 http-atualizador:8181 -- echo "Atualizador Finalizado. Procedendo..."

echo "Testing for available DB..."
/files/wait-for-it.sh -s -t 60 db:$APP_DB_PORTA -- echo "Database Port Online... Teste novamente em 5 segs..."

sleep 5
/files/wait-for-it.sh -s -t 60 db:$APP_DB_PORTA -- echo "Database Port Online... Procedendo..."

# Atualização do endereço de host da aplicação
echo "Atualizando Banco de Dados com as Configuracoes Iniciais..."
if [ "$APP_DB_TIPO" == "MySql" ]; then
    echo "Atualizando MySql..."
    MYSQL_CMD="mysql --host db --user $APP_DB_ROOT_USERNAME --password=$APP_DB_ROOT_PASSWORD"
    $MYSQL_CMD -e "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" sip
    $MYSQL_CMD -e "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" sei
    $MYSQL_CMD -e "update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP';" sip
    $MYSQL_CMD -e "update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI';" sip
fi

if [ "$APP_DB_TIPO" == "Oracle" ]; then
    echo "Atualizando Oracle..."
    echo "Aguardando Oracle ficar pronto..."
    sleep 30
    echo "alter user sip identified by sip_user;" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@db
    echo "alter user sei identified by sei_user;" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@db
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@db
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" | sqlplus64 sei/sei_user@db
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP';" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@db
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI';" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@db
fi

if [ "$APP_DB_TIPO" == "SqlServer" ]; then
    echo "Atualizando SqlServer..."
    sleep 10
    echo "use sip" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S db -U $APP_DB_ROOT_USERNAME -P $APP_DB_ROOT_PASSWORD


    echo "use sei" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S db -U $APP_DB_SEI_USERNAME -P $APP_DB_SEI_PASSWORD

    echo "use sip" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S db -U $APP_DB_ROOT_USERNAME -P $APP_DB_ROOT_PASSWORD

    echo "use sip" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S db -U $APP_DB_ROOT_USERNAME -P $APP_DB_ROOT_PASSWORD
fi



#atualizar
#/usr/sbin/httpd -DFOREGROUND &
#sleep 3

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
    echo "------------------------------------------------------------------"
    
    if [ "$MODULO_ESTATISTICAS_INSTALAR" = "true" ]; then

        if [ -z "$MODULO_ESTATISTICAS_VERSAO" ] || \
           [ -z "$MODULO_ESTATISTICAS_URL" ] || \
	       [ -z "$MODULO_ESTATISTICAS_SIGLA" ] || \
	       [ -z "$MODULO_ESTATISTICAS_CHAVE" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_ESTATISTICAS_VERSAO, MODULO_ESTATISTICAS_URL, MODULO_ESTATISTICAS_SIGLA, MODULO_ESTATISTICAS_CHAVE"

            exit 1
        fi
        
        echo "Verificando existencia do modulo de estatisticas"
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
        
        cp /files/sei_gov_configurar_ambiente.php /opt/sei/scripts
        php -c /etc/php.ini /opt/sei/scripts/sei_gov_configurar_ambiente.php

    else
        echo "Pulando instalacao do modulo de estatisticas, valor existe mas esta diferente de true"
    fi
    
    echo "Fim do tratamento de instalacao do modulo de estatisticas"
    echo "------------------------------------------------------------------"
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

        
        TMPFILE=/opt/sei/web/modulos/mod-wssei/scripts/sei_atualizar_versao_modulo_wssei.php
        if test -f "$TMPFILE"; then
        
            # mover os scripts e executar
            cp /opt/sei/web/modulos/mod-wssei/scripts/sei_atualizar_versao_modulo_wssei.php /opt/sei/scripts
        
            echo "Vou rodar o script de atualizacao do modulo"
            php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_wssei.php
        fi
        

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


touch /controlador/instalado.ok

echo "Atualizador chegou ao final... Fechando e liberando para o modulo de aplicacao"


