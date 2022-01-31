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
    echo "Informe as seguinte variaveis de ambiente no seu docker-compose ou no container:"
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


echo "***************************************************"
echo "***************************************************"
echo "**INICIANDO CONFIGURACOES BASICAS DO APACHE E SEI**"
echo "***************************************************"
echo "***************************************************"

APP_HOST_URL=$APP_PROTOCOLO://$APP_HOST

echo "127.0.0.1 $APP_HOST" >> /etc/hosts

# Direciona logs para saida padrao para utilizar docker logs
ln -sf /dev/stdout /var/log/httpd/access_log
ln -sf /dev/stdout /var/log/httpd/ssl_access_log
ln -sf /dev/stdout /var/log/httpd/ssl_request_log
ln -sf /dev/stderr /var/log/httpd/error_log
ln -sf /dev/stderr /var/log/httpd/ssl_error_log

# vefificar se existe codigo fonte
if [ ! -f /opt/sei/web/SEI.php ] || [ ! -f /opt/sip/web/Sip.php ] ; then
  echo "Codigo fonte do sei  ou sip nao encontrado ou sem permissao. Abandonando subida..."
  sleep 10
  exit 1
fi

# Atribuicao dos parametros de configuracao do SEI
if [ -f /opt/sei/config/ConfiguracaoSEI.php ] && [ ! -f /opt/sei/config/ConfiguracaoSEI.php~ ]; then
    mv /opt/sei/config/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php~
fi

if [ ! -f /opt/sei/config/ConfiguracaoSEI.php ]; then
    \cp -r /sei/files/conf/ConfiguracaoSEI.php /opt/sei/config/
fi

# Atribuicao dos parametros de configuracao do SIP
if [ -f /opt/sip/config/ConfiguracaoSip.php ] && [ ! -f /opt/sip/config/ConfiguracaoSip.php~ ]; then
    mv /opt/sip/config/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php~
fi

if [ ! -f /opt/sip/config/ConfiguracaoSip.php ]; then
    \cp -r /sei/files/conf/ConfiguracaoSip.php /opt/sip/config/
fi

# Ajustes de permissoes diversos para desenvolvimento do SEI
chmod +x /opt/sei/bin/pdfboxmerge.jar
mkdir -p /opt/sei/temp
mkdir -p /opt/sip/temp
chmod -R 777 /opt/sei/temp
chmod -R 777 /opt/sip/temp



while [ ! -f /sei/controlador-instalacoes/instalado.ok ]
do
    echo "Aguardando conteiner atualizador instalar e atualizar o SEI e modulos"
    sleep 5 
done


set +e

RET=1
while [ ! "$RET" == "0" ]
do
    echo ""
    echo "Esperando a base de dados ficar disponivel... Vamos tentar chama-la ...."
    sleep 3
    
    php -r "
    require_once '/opt/sip/web/Sip.php';    
    \$conexao = BancoSip::getInstance();
    \$conexao->abrirConexao();
    \$conexao->executarSql('select sigla from sistema');"
    
    RET=$?   
    
done  

set -e

echo "***************************************************"
echo "***************************************************"
echo "UPDATE NA BASE DE DADOS - ORGAO E SISTEMA**********"
echo "***************************************************"
echo "***************************************************"

# Atualizacao do endereco de host da aplicacao
echo "Atualizando Banco de Dados com as Configuracoes Iniciais..."
if [ "$APP_DB_TIPO" == "MySql" ]; then
    echo "Atualizando MySql..."
    MYSQL_CMD="mysql --host $APP_DB_HOST --user $APP_DB_ROOT_USERNAME --password=$APP_DB_ROOT_PASSWORD"
    $MYSQL_CMD -e "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" sip
    $MYSQL_CMD -e "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" sei
    $MYSQL_CMD -e "update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP';" sip
    $MYSQL_CMD -e "update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI';" sip
fi

if [ "$APP_DB_TIPO" == "Oracle" ]; then
    echo "Atualizando Oracle..."
    echo "alter user sip identified by sip_user;" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@$APP_DB_HOST
    echo "alter user sei identified by sei_user;" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@$APP_DB_HOST
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@$APP_DB_HOST
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO';" | sqlplus64 sei/sei_user@$APP_DB_HOST
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP';" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@$APP_DB_HOST
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI';" | sqlplus64 $APP_DB_ROOT_USERNAME/$APP_DB_ROOT_PASSWORD@$APP_DB_HOST
fi

if [ "$APP_DB_TIPO" == "SqlServer" ]; then
    echo "Atualizando SqlServer..."
    echo "use sip" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S $APP_DB_HOST -U $APP_DB_ROOT_USERNAME -P $APP_DB_ROOT_PASSWORD


    echo "use sei" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S $APP_DB_HOST -U $APP_DB_SEI_USERNAME -P $APP_DB_SEI_PASSWORD

    echo "use sip" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S $APP_DB_HOST -U $APP_DB_ROOT_USERNAME -P $APP_DB_ROOT_PASSWORD

    echo "use sip" > /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    echo "update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI'" >> /tmp/update.tmp
    echo "go" >> /tmp/update.tmp
    cat /tmp/update.tmp | tsql -S $APP_DB_HOST -U $APP_DB_ROOT_USERNAME -P $APP_DB_ROOT_PASSWORD
fi

echo "***************************************************"
echo "***************************************************"
echo "**GERACAO DE CERTIFICADO PARA O APACHE*************"
echo "***************************************************"
echo "***************************************************"

# Gera certificados caso necessario para desenvolvimento
if [ ! -d "/sei/certs/seiapp" ]; then
    echo "Diretorio /sei/certs/seiapp nao encontrado, criando ..."
    mkdir -p /sei/certs/seiapp
fi

echo "Verificando se certificados existem no diretorio /certs...."
if [ ! -f /sei/certs/seiapp/sei-ca.pem ] || [ ! -f /sei/certs/seiapp/sei.crt ]; then
    echo "Arquivos de cert nao encontrados criando auto assinados ..."
    
    cd /sei/certs/seiapp

    echo "Criando CA"  
    openssl genrsa -out sei-ca-key.pem 2048
    openssl req -x509 -new -nodes -key sei-ca-key.pem \
        -days 10000 -out sei-ca.pem -subj "/CN=sei-dev"
    
    echo "Criando certificados para o dominio: $APP_HOST"
    openssl genrsa -out sei.key 2048
    openssl req -new -nodes -key sei.key \
        -days 10000 -out sei.csr -subj "/CN=$APP_HOST"
    openssl x509 -req -in sei.csr -CA sei-ca.pem \
        -CAkey sei-ca-key.pem -CAcreateserial \
        -out sei.crt -days 10000 -extensions v3_req

    cat /sei/certs/seiapp/sei-ca.pem >> /etc/ssl/certs/cacert.pem
    echo "Adicionada nova CA ao TrustStore\n"
else
    echo "Arquivos de cert encontrados vamos tentar utiliza-los..."
fi

cd /sei/certs/seiapp
cp sei.crt /etc/pki/tls/certs/sei.crt
cp sei-ca.pem /etc/pki/tls/certs/sei-ca.pem
cp sei.key /etc/pki/tls/private/sei.key 
cat sei.crt sei.key >> /etc/pki/tls/certs/sei.pem

echo "Incluindo TrustStore no sistema"
#cp /icpbrasil/*.crt /etc/pki/ca-trust/source/anchors/
cp sei-ca.pem /etc/pki/ca-trust/source/anchors/
update-ca-trust extract
update-ca-trust enable



echo "Atualizador finalizado procedendo com a subida do apache..."

#atualizar
/usr/sbin/httpd -DFOREGROUND &
sleep 3

echo "Apache no ar"

echo "***************************************************"
echo "***************************************************"
echo "*INICIANDO CONFIGURACOES DO MODULO DE ESTATISTICAS*"
echo "***************************************************"
echo "***************************************************"

if [ "$MODULO_ESTATISTICAS_INSTALAR" == "true" ]; then
    
    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-estatisticas.ok ]; then
    
        if [ -z "$MODULO_ESTATISTICAS_VERSAO" ] || \
           [ -z "$MODULO_ESTATISTICAS_URL" ] || \
	       [ -z "$MODULO_ESTATISTICAS_SIGLA" ] || \
	       [ -z "$MODULO_ESTATISTICAS_CHAVE" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_ESTATISTICAS_VERSAO, MODULO_ESTATISTICAS_URL, MODULO_ESTATISTICAS_SIGLA, MODULO_ESTATISTICAS_CHAVE"

        else
            
            echo "Verificando existencia do modulo de estatisticas"
            if [ -d "/opt/sei/web/modulos/mod-sei-estatisticas" ]; then
                echo "Ja existe um diretorio para o modulo de estatisticas. Vamos assumir que o codigo la esteja integro"
        
            else
                echo "Copiando o modulo de estatisticas"
                cp -Rf /sei-modulos/mod-sei-estatisticas /opt/sei/web/modulos/
            fi
        

            cd /opt/sei/web/modulos/mod-sei-estatisticas
            git checkout $MODULO_ESTATISTICAS_VERSAO
            echo "Versao do Governanca eh agora: $MODULO_ESTATISTICAS_VERSAO"

            cd /opt/sei/
        
            sed -i "s#/\*novomodulo\*/#'MdEstatisticas' => 'mod-sei-estatisticas', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php
            sed -i "s#/\*extramodulesconfig\*/#'MdEstatisticas' => array('url' => '$MODULO_ESTATISTICAS_URL','sigla' => '$MODULO_ESTATISTICAS_SIGLA','chave' => '$MODULO_ESTATISTICAS_CHAVE'), /\*extramodulesconfig\*/#g" config/ConfiguracaoSEI.php
        
            
        fi
        
    else
    
        echo "Arquivo de controle do Modulo de Estatisticas encontrado, provavelmente ja foi instalado, pulando configuracao do modulo"
    
    fi

else
    
    echo "Variavel MODULO_ESTATISTICAS_INSTALAR nao setada para true, pulando configuracao..."
    
fi


echo "***************************************************"
echo "***************************************************"
echo "**CONFIGURANDO MODULO WSSEI************************"
echo "***************************************************"
echo "***************************************************"
if [ "$MODULO_WSSEI_INSTALAR" == "true" ]; then
    
    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-wssei.ok ]; then
        
        if [ -z "$MODULO_WSSEI_VERSAO" ] || \
           [ -z "$MODULO_WSSEI_URL_NOTIFICACAO" ] || \
	       [ -z "$MODULO_WSSEI_ID_APP" ] || \
	       [ -z "$MODULO_WSSEI_CHAVE" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_WSSEI_VERSAO, MODULO_WSSEI_URL_NOTIFICACAO, MODULO_WSSEI_ID_APP, MODULO_WSSEI_CHAVE"

        else
            
            echo "Verificando existencia do modulo wssei"
            if [ -d "/opt/sei/web/modulos/mod-wssei" ]; then
                echo "Ja existe um diretorio para o modulo wssei. Vamos assumir que o codigo la esteja integro"
        
            else
                echo "Copiando o modulo wssei"
                cp -Rf /sei-modulos/mod-wssei /opt/sei/web/modulos/
            fi
        

            cd /opt/sei/web/modulos/mod-wssei
            git checkout $MODULO_WSSEI_VERSAO
            echo "Versao do WSSEI eh agora: $MODULO_WSSEI_VERSAO"

            cd /opt/sei/
            sed -i "s#/\*novomodulo\*/#'MdWsSeiRest' => 'mod-wssei/', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php
            sed -i "s#/\*extramodulesconfig\*/#'WSSEI' => array('UrlServicoNotificacao' => '$MODULO_WSSEI_URL_NOTIFICACAO', 'IdApp' => '$MODULO_WSSEI_ID_APP', 'ChaveAutorizacao' => '$MODULO_WSSEI_CHAVE', 'TokenSecret' => '504CE1E9-8913-488F-AB3E-EDDABC065B0B'  ), /\*extramodulesconfig\*/#g" config/ConfiguracaoSEI.php

            
        fi
        
    else
    
        echo "Arquivo de controle do Modulo WSSEI encontrado pulando configuracao do modulo"
    
    fi

else
    
    echo "Variavel MODULO_WSSEI_INSTALAR nao setada para true, pulando configuracao..."
    
fi

echo "***************************************************"
echo "***************************************************"
echo "**CONFIGURANDO MODULO RESPOSTA*********************"
echo "***************************************************"
echo "***************************************************"
if [ "$MODULO_RESPOSTA_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-resposta.ok ]; then

        if [ -z "$MODULO_RESPOSTA_VERSAO" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_RESPOSTA_VERSAO"

        else

            echo "Verificando existencia do modulo resposta"
            if [ -d "/opt/sei/web/modulos/mod-sei-resposta" ]; then
                echo "Ja existe um diretorio para o modulo resposta. Vamos assumir que o codigo la esteja integro"

            else
                echo "Copiando o modulo resposta"
                cp -Rf /sei-modulos/mod-sei-resposta /opt/sei/web/modulos/
            fi


            cd /opt/sei/web/modulos/mod-sei-resposta
            git checkout $MODULO_RESPOSTA_VERSAO
            echo "Versao do WSSEI é agora: $MODULO_RESPOSTA_VERSAO"

            cd /opt/sei/
            sed -i "s#/\*novomodulo\*/#'MdRespostaIntegracao' => 'mod-sei-resposta/', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php


            TMPFILE_SEI=/opt/sei/web/modulos/mod-sei-resposta/sei_atualizar_versao_modulo_sei_resposta.php
            if test -f "$TMPFILE_SEI"; then

                # mover os scripts e executar
                cp /opt/sei/web/modulos/mod-sei-resposta/sei_atualizar_versao_modulo_sei_resposta.php /opt/sei/scripts

                echo "Vou rodar o script de atualizacao do modulo no SEI"
                php -c /etc/php.ini /opt/sei/scripts/sei_atualizar_versao_modulo_sei_resposta.php
            fi

            TMPFILE_SIP=/opt/sei/web/modulos/mod-sei-resposta/sip_atualizar_versao_modulo_sei_resposta.php
            if test -f "$TMPFILE_SIP"; then

                # mover os scripts e executar
                cp /opt/sei/web/modulos/mod-sei-resposta/sip_atualizar_versao_modulo_sei_resposta.php /opt/sip/scripts

                echo "Vou rodar o script de atualizacao do modulo no SIP"
                php -c /etc/php.ini /opt/sip/scripts/sip_atualizar_versao_modulo_sei_resposta.php
            fi

            touch /sei/controlador-instalacoes/instalado-modulo-resposta.ok

        fi

    else

        echo "Arquivo de controle do Modulo RESPOSTA encontrado pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_RESPOSTA_INSTALAR nao setada para true, pulando configuracao..."

fi


touch /sei/controlador-instalacoes/instalado.ok

echo "***************************************************"
echo "Entrypoint chegou ao final..."
echo "Apache Liberado para uso"
echo "***************************************************"


tail -f /dev/null
