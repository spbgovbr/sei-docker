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


echo "***************************************************"
echo "***************************************************"
echo "**INICIANDO CONFIGURACOES BASICAS DO APACHE E SEI**"
echo "***************************************************"
echo "***************************************************"

if [ -z "$APP_FONTES_GIT_PATH" ] || \
   [ -z "$APP_FONTES_GIT_PRIVKEY_BASE64" ] || \
   [ -z "$APP_FONTES_GIT_CHECKOUT" ]; then
    echo "Vamos tentar usar o codigo fonte fornecido na pasta /opt (via volume)."
else
    echo "Vamos tentar baixar o fonte do git com os parametros fornecidos"

    cd /tmp
    echo -n "$APP_FONTES_GIT_PRIVKEY_BASE64" | base64 -d > /tmp/lhave.key
    chmod 500 lhave.key

    echo '#!/bin/bash' > /tmp/gitwrap.sh
    echo 'ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no -i /tmp/lhave.key "$@"' >> /tmp/gitwrap.sh
    chmod +x gitwrap.sh

    echo "Fazendo o clone dos fontes. Aguarde..."
    export GIT_SSH=/tmp/gitwrap.sh
    git clone $APP_FONTES_GIT_PATH
    export GIT_SSH=ssh

    echo "Fazendo a copia dos fontes. Aguarde..."
    cd super
    git checkout $APP_FONTES_GIT_CHECKOUT
    cp -R src/* /opt/
    cd /
    rm -rf /tmp/super /tmp/lhave.key
fi

APP_HOST_URL=$APP_PROTOCOLO://$APP_HOST

echo "127.0.0.1 $APP_HOST" >> /etc/hosts

# Direciona logs para saida padrão para utilizar docker logs
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

# verificar se config esta correto

if [ ! -f /opt/sei/config/ConfiguracaoSEI.php ]; then
    \cp -r /sei/files/conf/ConfiguracaoSEI.php /opt/sei/config/
fi

if [ ! -f /opt/sip/config/ConfiguracaoSip.php ]; then
    \cp -r /sei/files/conf/ConfiguracaoSip.php /opt/sip/config/
fi

DIFERENCA=$(diff /sei/files/conf/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php | wc -l)
if [ ! "$DIFERENCA" == "0" ]; then
    mv /opt/sei/config/ConfiguracaoSEI.php /opt/sei/config/ConfiguracaoSEI.php.$(date '+%Y-%m-%d_%H-%M-%S')
    \cp -r /sei/files/conf/ConfiguracaoSEI.php /opt/sei/config/
fi

DIFERENCA=$(diff /sei/files/conf/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php | wc -l)
if [ ! "$DIFERENCA" == "0" ]; then
    mv /opt/sip/config/ConfiguracaoSip.php /opt/sip/config/ConfiguracaoSip.php.$(date '+%Y-%m-%d_%H-%M-%S')
    \cp -r /sei/files/conf/ConfiguracaoSip.php /opt/sip/config/
fi

# Ajustes de permissões diversos para desenvolvimento do SEI
chmod +x /opt/sei/bin/pdfboxmerge.jar
mkdir -p /opt/sei/temp
mkdir -p /opt/sip/temp
chmod -R 777 /opt/sei/temp
chmod -R 777 /opt/sip/temp

# Ver issue #19
if [ "$APP_DB_TIPO" = "SqlServer" ]; then
    ln -s /opt2/microsoft /opt/microsoft
fi

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

# Atualização do endereço de host da aplicação
echo "Atualizando Banco de Dados com as Configuracoes Iniciais..."
php -r "
    require_once '/opt/sip/web/Sip.php';
    \$conexao = BancoSip::getInstance();
    \$conexao->abrirConexao();
    \$conexao->executarSql(\"update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO'\");
    \$conexao->executarSql(\"update sistema set pagina_inicial='$APP_HOST_URL/sip' where sigla='SIP'\");
    \$conexao->executarSql(\"update sistema set pagina_inicial='$APP_HOST_URL/sei/inicializar.php', web_service='$APP_HOST_URL/sei/controlador_ws.php?servico=sip' where sigla='SEI'\");
";

php -r "
    require_once '/opt/sei/web/SEI.php';
    \$conexao = BancoSEI::getInstance();
    \$conexao->abrirConexao();
    \$conexao->executarSql(\"update orgao set sigla='$APP_ORGAO', descricao='$APP_ORGAO_DESCRICAO'\");
";

echo "***************************************************"
echo "***************************************************"
echo "**GERACAO DE CERTIFICADO PARA O APACHE*************"
echo "***************************************************"
echo "***************************************************"

# Gera certificados caso necessário para desenvolvimento
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
    echo "Arquivos de cert encontrados vamos tentar utilizá-los..."
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

echo "***************************************************"
echo "***************************************************"
echo "**ATUALIZACAO DE SEQUENCES*************************"
echo "***************************************************"
echo "***************************************************"

if [ ! -f /sei/controlador-instalacoes/instalado.ok ]; then

    echo "Vamos fazer o apache se apropriar dos dados externos... Aguarde"
    chown -R apache:apache /sei/arquivos_externos_sei/

fi

echo "Atualizar sequences! todo ajeitar a base de ref e retirar isso"
# copiado do sei-vagrant do guilhermao
# Atualizar os endereços de host definidos para na inicialização e sincronização de sequências
php -r "
    require_once '/opt/sip/web/Sip.php';
    \$conexao = BancoSip::getInstance();
    \$conexao->setBolScript(true);
    \$objScriptRN = new ScriptRN();
    \$objScriptRN->atualizarSequencias();
"

echo "atualizar sequences do SEI"
# Atualizar os endereços de host definidos para na inicialização e sincronização de sequências
php -r "
    require_once '/opt/sei/web/SEI.php';
    \$conexao = BancoSEI::getInstance();
    \$conexao->setBolScript(true);
    \$objScriptRN = new ScriptRN();
    \$objScriptRN->atualizarSequencias();
"
echo "Finalizacao de atualizacao de sequences"

echo "***************************************************"
echo "***************************************************"
echo "**RODAR ARQUIVOS DE ATUALIZACAO DO SIP e SEI*******"
echo "***************************************************"
echo "***************************************************"

VERSAO_ENCONTRADA=$(php -r "require_once '/opt/sip/web/Sip.php'; echo SIP_VERSAO;")
if [ -f /sei/controlador-instalacoes/atualizacao-sip-$VERSAO_ENCONTRADA.ok ]; then
    echo "Arquivos da atualização para o SIP já rodaram nessa versao: $VERSAO_ENCONTRADA . Pulando para a proxima etapa"
else
    set +e
    echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php /opt/sip/scripts/atualizar_versao_sip.php > /tmp/result.txt
    erro=$?
    set -e
    cat /tmp/result.txt
    if [ ! "$erro" == "0" ]; then
        set +e
        result=$(cat /tmp/result.txt | grep -E "(JA ESTA INSTALADA|NAO CORRESPONDE COM A ULTIMA VERSAO)")
        set -e
        if [ "$result" == "" ]; then
          set -e
          echo "Erro ao atualizar o SIP. Abandonando a execucao..."
          exit 1
        fi
    fi
    touch /sei/controlador-instalacoes/atualizacao-sip-$VERSAO_ENCONTRADA.ok
fi

VERSAO_ENCONTRADA=$(php -r "require_once '/opt/sei/web/SEI.php'; echo SEI_VERSAO;")
if [ -f /sei/controlador-instalacoes/atualizacao-sei-$VERSAO_ENCONTRADA.ok ]; then
    echo "Arquivos da atualização para o SEI já rodaram nessa versao: $VERSAO_ENCONTRADA . Pulando para a proxima etapa"
else
    set +e
    echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php /opt/sei/scripts/atualizar_versao_sei.php > /tmp/result.txt
    erro=$?
    set -e
    cat /tmp/result.txt
    if [ ! "$erro" == "0" ]; then
        set +e
        result=$(cat /tmp/result.txt | grep -E "(JA ESTA INSTALADA|NAO CORRESPONDE COM A ULTIMA VERSAO)")
        set -e
        if [ "$result" == "" ]; then
          set -e
          echo "Erro ao atualizar o SEI. Abandonando a execucao..."
          exit 1
        fi
    fi
    touch /sei/controlador-instalacoes/atualizacao-sei-$VERSAO_ENCONTRADA.ok
fi

echo "***************************************************"
echo "***************************************************"
echo "**RODAR ARQUIVOS DE ATUALIZACAO DE RECURSO*********"
echo "***************************************************"
echo "***************************************************"

VERSAO_ENCONTRADA=$(php -r "require_once '/opt/sip/web/Sip.php'; echo SIP_VERSAO;")
if [ -f /sei/controlador-instalacoes/atualizacao-sip-$VERSAO_ENCONTRADA-recurso.ok ]; then
    echo "Arquivos da atualização de recurso para o SIP já rodaram nessa versao: $VERSAO_ENCONTRADA . Pulando para a proxima etapa"
else
    set +e
    echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php /opt/sip/scripts/atualizar_recursos_sei.php > /tmp/result.txt
    erro=$?
    set -e
    cat /tmp/result.txt
    if [ ! "$erro" == "0" ]; then
        set +e
        result=$(cat /tmp/result.txt | grep -E "(JA ESTA INSTALADA|NAO CORRESPONDE COM A ULTIMA VERSAO)")
        set -e
        if [ "$result" == "" ]; then
          set -e
          echo "Erro ao atualizar o SIP. Abandonando a execucao..."
          exit 1
        fi
    fi
    touch /sei/controlador-instalacoes/atualizacao-sip-$VERSAO_ENCONTRADA-recurso.ok
fi

echo "***************************************************"
echo "***************************************************"
echo "**CONFIGURANDO LDAP********************************"
echo "***************************************************"
echo "***************************************************"
if [ "$OPENLDAP_PRESENTE" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/openldap.ok ]; then

        echo "Vamos tentar criar a conexao ao Ldap no SIP..."

        php /sei/files/scripts-e-automatizadores/openldap/sip-config-openldap.php

        echo "Atualizar sequences! todo ajeitar a base de ref e retirar isso"
        # copiado do sei-vagrant do guilhermao
        # Atualizar os endereços de host definidos para na inicialização e sincronização de sequências
        php -r "
            require_once '/opt/sip/web/Sip.php';
            \$conexao = BancoSip::getInstance();
            \$conexao->setBolScript(true);
            \$objScriptRN = new ScriptRN();
            \$objScriptRN->atualizarSequencias();
        "

        echo "atualizar sequences do SEI"
        # Atualizar os endereços de host definidos para na inicialização e sincronização de sequências
        php -r "
            require_once '/opt/sei/web/SEI.php';
            \$conexao = BancoSEI::getInstance();
            \$conexao->setBolScript(true);
            \$objScriptRN = new ScriptRN();
            \$objScriptRN->atualizarSequencias();
        "
        echo "Finalizacao de atualizacao de sequences"

        echo ""
    else

        echo "Arquivo de controle do Ldap encontrado pulando configuracao do Ldap"
        echo "Caso tenha problema ao logar, como esquecimento de senha, sete a VAR OPENLDAP_DESLIGAR_NO_ORGAO_0=true e OPENLDAP_PRESENTE=false"
        echo "Isso vai forcar o instalador a desligar o Ldap no Orgao 0"

    fi

else

    echo "Variavel OPENLDAP_PRESENTE nao setada para true, pulando configuracao..."

    echo "Verificando se eh para desligar a autenticacao via openldap no orgao 0"
    if [ "$OPENLDAP_DESLIGAR_NO_ORGAO_0" == "true" ]; then
        echo "Variavel OPENLDAP_DESLIGAR_NO_ORGAO_0 igual a true, vamos desligar o OpenLdap no Orgao 0, "
        echo "ATENCAO: USUARIO E SENHA TERAO O MESMO VALOR !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
        echo "ATENCAO: USUARIO E SENHA TERAO O MESMO VALOR !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"

        php /sei/files/scripts-e-automatizadores/openldap/sip-config-openldap-desligar.php

        echo "Atualizar sequences! todo ajeitar a base de ref e retirar isso"
        # copiado do sei-vagrant do guilhermao
        # Atualizar os endereços de host definidos para na inicialização e sincronização de sequências
        php -r "
            require_once '/opt/sip/web/Sip.php';
            \$conexao = BancoSip::getInstance();
            \$conexao->setBolScript(true);
            \$objScriptRN = new ScriptRN();
            \$objScriptRN->atualizarSequencias();
        "

        echo "atualizar sequences do SEI"
        # Atualizar os endereços de host definidos para na inicialização e sincronização de sequências
        php -r "
            require_once '/opt/sei/web/SEI.php';
            \$conexao = BancoSEI::getInstance();
            \$conexao->setBolScript(true);
            \$objScriptRN = new ScriptRN();
            \$objScriptRN->atualizarSequencias();
        "
        echo "Finalizacao de atualizacao de sequences"


    else
        echo "Variavel OPENLDAP_DESLIGAR_NO_ORGAO_0 diferente de true. Sendo assim nao vamos desligar o OpenLdap no Orgao 0. "
        echo "Caso tenha problema ao logar, como esquecimento de senha, sete a VAR OPENLDAP_DESLIGAR_NO_ORGAO_0=true e OPENLDAP_PRESENTE=false"
        echo "Isso vai forcar o instalador a desligar o Ldap no Orgao 0"
    fi


fi

# necessario para testarmos ambientes com a data retroagida
git config --global http.sslVerify false


echo "***************************************************"
echo "***************************************************"
echo "*INICIANDO CONFIGURACOES DO SERVICO****************"
echo "*PROTOCOLO DIGITAL*********************************"
echo "***************************************************"

if [ "$SERVICO_PD_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-servicoPD.ok ]; then

        echo "Rodando script do PD..."
        php /sei/files/scripts-e-automatizadores/misc/enableservicePD.php
        echo "Script do PD finalizado verifique se houve erros acima..."
        touch /sei/controlador-instalacoes/instalado-servicoPD.ok

    else

        echo "Arquivo de controle do Servico do Protocolo Digital encontrado, provavelmente ja foi instalado, pulando configuracao do servico"

    fi

else

    echo "Variavel SERVICO_PD_INSTALAR nao setada para true, pulando configuracao..."

fi


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
            echo "Sincronizando nova versão do módulo de estatísticas"

            cd /sei-modulos/mod-sei-estatisticas
            git pull

            cp -Rf /sei-modulos/mod-sei-estatisticas /opt/sei/web/modulos/

            cd /opt/sei/web/modulos/mod-sei-estatisticas
            git checkout $MODULO_ESTATISTICAS_VERSAO
            echo "Versao do Governanca é agora: $MODULO_ESTATISTICAS_VERSAO"

            cd /opt/sei/

            sed -i "s#/\*novomodulo\*/#'MdEstatisticas' => 'mod-sei-estatisticas', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php
            sed -i "s#/\*extramodulesconfig\*/#'MdEstatisticas' => array('url' => '$MODULO_ESTATISTICAS_URL','sigla' => '$MODULO_ESTATISTICAS_SIGLA','chave' => '$MODULO_ESTATISTICAS_CHAVE'), /\*extramodulesconfig\*/#g" config/ConfiguracaoSEI.php

            cp /sei/files/scripts-e-automatizadores/modulos/mod-sei-estatisticas/sei_gov_configurar_ambiente.php /opt/sei/scripts
            php -c /etc/php.ini /opt/sei/scripts/sei_gov_configurar_ambiente.php

            touch /sei/controlador-instalacoes/instalado-modulo-estatisticas.ok

        fi

    else

        echo "Arquivo de controle do Modulo de Estatisticas encontrado, provavelmente ja foi instalado, pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_ESTATISTICAS_INSTALAR nao setada para true, pulando configuracao..."

fi


echo "***************************************************"
echo "***************************************************"
echo "**CONFIGURANDO MODULO WSSUPER************************"
echo "***************************************************"
echo "***************************************************"
if [ "$MODULO_WSSUPER_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-wssuper.ok ]; then

        if [ -z "$MODULO_WSSUPER_VERSAO" ] || \
           [ -z "$MODULO_WSSUPER_URL_NOTIFICACAO" ] || \
	       [ -z "$MODULO_WSSUPER_ID_APP" ] || \
	       [ -z "$MODULO_WSSUPER_CHAVE" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_WSSUPER_VERSAO, MODULO_WSSUPER_URL_NOTIFICACAO, MODULO_WSSUPER_ID_APP, MODULO_WSSUPER_CHAVE"

        else

            echo "Sincronizando nova versão do módulo de WSSEI"
            rm -rf /opt/sei/web/modulos/mod-wssei/
            cd /sei-modulos/mod-wssei
            git pull

            cp -Rf /sei-modulos/mod-wssei /opt/sei/web/modulos/
            cd /opt/sei/web/modulos/mod-wssei/
            git checkout $MODULO_WSSUPER_VERSAO
            echo "Versao do WSSEI é agora: $MODULO_WSSUPER_VERSAO"

            \cp envs/mysql.env .env
            \cp envs/modulo.env .modulo.env
            make clean
            make dist
            cd ..
            mv mod-wssei mod-wssei.old

            cd mod-wssei.old/dist/

            files=( *.zip )
            f="${files[0]}"

            mkdir -p temp
            cd temp
            mv ../$f .

            yes | unzip $f

            yes | cp -Rf sei sip /opt/

            cd /opt/sei/
            sed -i "s#/\*novomodulo\*/#'MdWsSeiRest' => 'wssei/', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

            cd /opt/sei/config/mod-wssei/
            cp -f /opt/sei/web/modulos/mod-wssei.old/src/config/ConfiguracaoMdWSSEI.php .
            sed -i "s#MOD_WSSEI_URL_SERVICO_NOTIFICACAO#MODULO_WSSUPER_URL_NOTIFICACAO#g" ConfiguracaoMdWSSEI.php
            sed -i "s#MOD_WSSEI_ID_APP#MODULO_WSSUPER_ID_APP#g" ConfiguracaoMdWSSEI.php
            sed -i "s#MOD_WSSEI_CHAVE_AUTORIZACAO#MODULO_WSSUPER_CHAVE#g" ConfiguracaoMdWSSEI.php
            sed -i "s#MOD_WSSEI_TOKEN_SECRET#MODULO_WSSUPER_TOKEN_SECRET#g" ConfiguracaoMdWSSEI.php
            
            cd /opt/sei/scripts/mod-wssei/
            echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei_atualizar_versao_modulo_wssei.php

            rm -rf /opt/sei/web/modulos/mod-wssei.old
            touch /sei/controlador-instalacoes/instalado-modulo-wssuper.ok

        fi

    else

        echo "Arquivo de controle do Modulo WSSUPER encontrado pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_WSSUPER_INSTALAR nao setada para true, pulando configuracao..."

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

          echo "Sincronizando nova versão do módulo de resposta"
          rm -rf /opt/sei/web/modulos/mod-sei-resposta/
          cd /sei-modulos/mod-sei-resposta
          git pull

          cp -Rf /sei-modulos/mod-sei-resposta /opt/sei/web/modulos/
          cd /opt/sei/web/modulos/mod-sei-resposta/
          git checkout $MODULO_RESPOSTA_VERSAO
          echo "Versao do Resposta é agora: $MODULO_RESPOSTA_VERSAO"

          \cp envs/mysql.env .env
          \cp envs/modulo.env .modulo.env
          make clean
          make dist
          cd ..
          mv mod-sei-resposta mod-sei-resposta.old

          cd mod-sei-resposta.old/dist/

          files=( *.zip )
          f="${files[0]}"

          mkdir -p temp
          cd temp
          mv ../$f .

          yes | unzip $f

          yes | cp -Rf sei sip /opt/

          cd /opt/sei/
          sed -i "s#/\*novomodulo\*/#'MdRespostaIntegracao' => 'mod-sei-resposta', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

          cd /opt/sip/scripts/mod-sei-resposta/

          echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php sip_atualizar_versao_modulo_resposta.php

          cd /opt/sei/scripts/mod-sei-resposta/
          echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei_atualizar_versao_modulo_resposta.php

          if [ ! -z "$MODULO_RESPOSTA_SISTEMA_ID" ]; then
              echo "Vamos chamar a configuracao do mod-sei-resposta"
              php /sei/files/scripts-e-automatizadores/modulos/mod-sei-resposta/mod-sei-resposta.php
          fi

          rm -rf /opt/sei/web/modulos/mod-sei-resposta.old
          touch /sei/controlador-instalacoes/instalado-modulo-resposta.ok

        fi

    else

        echo "Arquivo de controle do Modulo RESPOSTA encontrado pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_RESPOSTA_INSTALAR nao setada para true, pulando configuracao..."

fi

echo "***************************************************"
echo "***************************************************"
echo "**CONFIGURANDO MODULO GESTAO DOC*******************"
echo "***************************************************"
echo "***************************************************"
if [ "$MODULO_GESTAODOCUMENTAL_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-gestaodocumental.ok ]; then

        if [ -z "$MODULO_GESTAODOCUMENTAL_VERSAO" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_GESTAODOCUMENTAL_VERSAO"

        else

            rm -rf /opt/sei/web/modulos/mod-gestao-documental/ /opt/sei/web/modulos/gestao-documental/
                echo "Copiando o módulo gestao documental"
                cp -Rf /sei-modulos/mod-gestao-documental /opt/sei/web/modulos/

                cd /opt/sei/web/modulos/mod-gestao-documental/
		git pull

                git checkout $MODULO_GESTAODOCUMENTAL_VERSAO
                echo "Versao do Gestao Documental eh agora: $MODULO_GESTAODOCUMENTAL_VERSAO"

                make clean
                make dist
                cd ..
                mv mod-gestao-documental mod-gestao-documental.old

                cd mod-gestao-documental.old/dist/

                files=( *.zip )
                f="${files[0]}"

                mkdir -p temp
                cd temp
                mv ../$f .

                yes | unzip $f

                yes | cp -Rf sei sip /opt/

                cd /opt/sei/
                sed -i "s#/\*novomodulo\*/#'MdGestaoDocumentalIntegracao' => 'gestao-documental', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

                cd /opt/sip/scripts/mod-gestao-documental/

                echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php sip_atualizar_versao_modulo_documental.php

                cd /opt/sei/config/mod-gestao-documental/
                cp ConfiguracaoMdGestaoDocumental.exemplo.php ConfiguracaoMdGestaoDocumental.php

                cd /opt/sei/scripts/mod-gestao-documental/
                echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei_atualizar_versao_modulo_documental.php

                rm -rf /opt/sei/web/modulos/mod-gestao-documental.old

                touch /sei/controlador-instalacoes/instalado-modulo-gestaodocumental.ok


        fi

    else

        echo "Arquivo de controle do Modulo GESTAO DOCUMENTAL encontrado pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_GESTAODOCUMENTAL_INSTALAR nao setada para true, pulando configuracao..."

fi


echo "***************************************************"
echo "***************************************************"
echo "*INICIANDO CONFIGURACOES DO MODULO LOGIN UNICO*****"
echo "***************************************************"
echo "***************************************************"

if [ "$MODULO_LOGINUNICO_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-loginunico.ok ]; then

        if [ -z "$MODULO_LOGINUNICO_VERSAO" ] || \
           [ -z "$MODULO_LOGINUNICO_CLIENTID" ] || \
           [ -z "$MODULO_LOGINUNICO_SECRET" ] || \
           [ -z "$MODULO_LOGINUNICO_URLPROVIDER" ] || \
           [ -z "$MODULO_LOGINUNICO_REDIRECTURL" ] || \
           [ -z "$MODULO_LOGINUNICO_URLLOGOUT" ] || \
           [ -z "$MODULO_LOGINUNICO_SCOPE" ] || \
           [ -z "$MODULO_LOGINUNICO_URLSERVICOS" ] || \
           [ -z "$MODULO_LOGINUNICO_URLREVALIDACAO" ] || \
           [ -z "$MODULO_LOGINUNICO_CIENTIDVALIDACAO" ] || \
           [ -z "$MODULO_LOGINUNICO_SECRETVALIDACAO" ] || \
           [ -z "$MODULO_LOGINUNICO_ORGAO" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_LOGINUNICO_VERSAO, MODULO_LOGINUNICO_CLIENTID, MODULO_LOGINUNICO_SECRET, MODULO_LOGINUNICO_URLPROVIDER, MODULO_LOGINUNICO_REDIRECTURL"
            echo "MODULO_LOGINUNICO_URLLOGOUT, MODULO_LOGINUNICO_SCOPE, MODULO_LOGINUNICO_URLSERVICOS, MODULO_LOGINUNICO_URLREVALIDACAO"
            echo "MODULO_LOGINUNICO_CIENTIDVALIDACAO, MODULO_LOGINUNICO_SECRETVALIDACAO, MODULO_LOGINUNICO_ORGAO, VAR_LOGINUNICO_REDIRECT_URL"

        else

          echo "Verificando existencia do modulo de loginunico"
          if [ -d "/opt/sei/web/modulos/loginunico" ]; then
              echo "Ja existe um diretorio para o modulo de loginunico. Vamos assumir que o codigo la esteja integro"

          else
              rm -rf /opt/sei/web/modulos/mod-sei-loginunico/ /opt/sei/web/modulos/loginunico/
              echo "Copiando o módulo de loginunico"
              cp -Rf /sei-modulos/mod-sei-loginunico /opt/sei/web/modulos/

              cd /opt/sei/web/modulos/mod-sei-loginunico/
              git checkout $MODULO_LOGINUNICO_VERSAO
              echo "Versao do LoginÚnico é agora: $MODULO_LOGINUNICO_VERSAO"

              cp envs/mysql.env .env
              cp envs/modulo.env .modulo.env
              make clean
              make dist
              cd ..
              mv mod-sei-loginunico mod-sei-loginunico.old

              cd mod-sei-loginunico.old/dist/

              files=( *.zip )
              f="${files[0]}"

              mkdir -p temp
              cd temp
              mv ../$f .

              yes | unzip $f

              yes | cp -Rf sei sip /opt/

              cd /opt/sei/
              sed -i "s#/\*novomodulo\*/#'LoginUnicoIntegracao' => 'loginunico', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

              cd /opt/sei/config/mod-loginunico/
              #cp ConfiguracaoModLoginUnico.exemplo.php ConfiguracaoModLoginUnico.php
              #nao quero o config de exemplo vou usar o config com as vars de ambiente e mudar o nome das vars
              cp /opt/sei/web/modulos/mod-sei-loginunico.old/src/config/ConfiguracaoModLoginUnico.php .
              sed -i "s#LOGIN_UNICO_CLIENT_ID#MODULO_LOGINUNICO_CLIENTID#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_SECRET#MODULO_LOGINUNICO_SECRET#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_URL_PROVIDER#MODULO_LOGINUNICO_URLPROVIDER#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_REDIRECT_URL#MODULO_LOGINUNICO_REDIRECTURL#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_URL_LOGOUT#MODULO_LOGINUNICO_URLLOGOUT#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_URL_SERVICOS#MODULO_LOGINUNICO_URLSERVICOS#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_REVALIDACAO_CLIENT_ID#MODULO_LOGINUNICO_CIENTIDVALIDACAO#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_REVALIDACAO_SECRET#MODULO_LOGINUNICO_SECRETVALIDACAO#g" ConfiguracaoModLoginUnico.php
              sed -i "s#LOGIN_UNICO_REVALIDACAO_URL#MODULO_LOGINUNICO_URLREVALIDACAO#g" ConfiguracaoModLoginUnico.php

              cd /opt/sip/scripts/mod-loginunico/

              echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php sip_atualizar_versao_modulo_loginunico.php

              cd /opt/sei/scripts/mod-loginunico/
              echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei_atualizar_versao_modulo_loginunico.php

              rm -rf /opt/sei/web/modulos/mod-sei-loginunico.old
              touch /sei/controlador-instalacoes/instalado-modulo-loginunico.ok
          fi

        fi

    else

        echo "Arquivo de controle do Modulo de LoginUnico encontrado, provavelmente ja foi instalado, pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_LOGINUNICO_INSTALAR nao setada para true, pulando configuracao..."

fi


echo "***************************************************"
echo "***************************************************"
echo "INICIANDO CONFIGURACOES DO MODULO ASSINATURA AVANCADA*****"
echo "***************************************************"
echo "***************************************************"

if [ "$MODULO_ASSINATURAVANCADA_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-assinaturavancada.ok ]; then

        if [ -z "$MODULO_ASSINATURAVANCADA_VERSAO" ] || \
           [ -z "$MODULO_ASSINATURAVANCADA_CLIENTID" ] || \
           [ -z "$MODULO_ASSINATURAVANCADA_SECRET" ] || \
           [ -z "$MODULO_ASSINATURAVANCADA_URLPROVIDER" ] || \
           [ -z "$MODULO_ASSINATURAVANCADA_URL_SERVICOS" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_ASSINATURAVANCADA_VERSAO, MODULO_ASSINATURAVANCADA_CLIENTID, MODULO_LOGINUNICO_SECRET, MODULO_LOGINUNICO_URLPROVIDER, MODULO_ASSINATURAVANCADA_URL_SERVICOS"

        else

                echo "Copiando o módulo de assinatura avancada"
                rm -rf /opt/sei/web/modulos/mod-sei-assinatura-avancada /opt/sei/web/modulos/assinatura-avancada
                cd /opt/sei/web/modulos
                cp -R /sei-modulos/mod-sei-assinatura-avancada .
                cd mod-sei-assinatura-avancada/
                git checkout $MODULO_ASSINATURAVANCADA_VERSAO
                echo "Versao do LoginUnico eh agora: $MODULO_ASSINATURAVANCADA_VERSAO"

                cp envs/mysql.env .env
                cp envs/modulo.env .modulo.env
                make all
                cd ..
                mv mod-sei-assinatura-avancada mod-sei-assinatura-avancada.old

                cd mod-sei-assinatura-avancada.old/dist/

                files=( *.zip )
                f="${files[0]}"

                mkdir -p temp
                cd temp
                mv ../$f .

                yes | unzip $f

                yes | cp -Rf sei sip /opt/

                cd /opt/sei/
                sed -i "s#/\*novomodulo\*/#'AssinaturaAvancadaIntegracao' => 'assinatura-avancada', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

                cd /opt/sei/config/mod-assinatura-avancada/
                #cp ConfiguracaoModAssinaturaAvancada.exemplo.php ConfiguracaoModAssinaturaAvancada.php
                #nao quero o config de exemplo vou usar o config com as vars de ambiente e mudar o nome das vars
                cp /opt/sei/web/modulos/mod-sei-assinatura-avancada.old/src/config/ConfiguracaoModAssinaturaAvancada.php .
                sed -i "s#ASSINATURA_URL_PROVIDER#MODULO_ASSINATURAVANCADA_URLPROVIDER#g" ConfiguracaoModAssinaturaAvancada.php
                sed -i "s#ASSINATURA_CLIENT_ID#MODULO_ASSINATURAVANCADA_CLIENTID#g" ConfiguracaoModAssinaturaAvancada.php
                sed -i "s#ASSINATURA_SECRET#MODULO_ASSINATURAVANCADA_SECRET#g" ConfiguracaoModAssinaturaAvancada.php
                sed -i "s#ASSINATURA_URL_SERVICOS#MODULO_ASSINATURAVANCADA_URL_SERVICOS#g" ConfiguracaoModAssinaturaAvancada.php


                cd /opt/sip/scripts/mod-assinatura-avancada/

                echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php sip_atualizar_versao_modulo_assinatura.php

                cd /opt/sei/scripts/mod-assinatura-avancada/
                echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei_atualizar_versao_modulo_assinatura.php

                rm -rf /opt/sei/web/modulos/mod-sei-assinatura-avancada.old

                touch /sei/controlador-instalacoes/instalado-modulo-assinaturavancada.ok

        fi

    else

        echo "Arquivo de controle do Modulo de Assinatura Avancada encontrado, provavelmente ja foi instalado, pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_ASSINATURAVANCADA_INSTALAR nao setada para true, pulando configuracao..."

fi

echo "***************************************************"
echo "***************************************************"
echo "*INICIANDO CONFIGURACOES DO MODULO PEN BARRAMENTO**"
echo "***************************************************"
echo "***************************************************"

if [ "$MODULO_PEN_INSTALAR" == "true" ]; then

    if [ ! -f /sei/controlador-instalacoes/instalado-modulo-pen.ok ]; then

        if [ -z "$MODULO_PEN_VERSAO" ] || \
           [ -z "$MODULO_PEN_WEBSERVICE" ] || \
	         [ -z "$MODULO_PEN_CERTIFICADO_BASE64" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_PEN_VERSAO, MODULO_PEN_WEBSERVICE, MODULO_PEN_CERTIFICADO"

        else

                echo "Sincronizando nova versão do módulo pen"
                rm -rf /opt/sei/web/modulos/mod-sei-pen /opt/sei/web/modulos/pen

                cd /sei-modulos/mod-sei-pen
                git pull

                cd /opt/sei/web/modulos
                cp -R /sei-modulos/mod-sei-pen mod-sei-pen

                cd mod-sei-pen
                git checkout $MODULO_PEN_VERSAO
                echo "Versao do PEN agora: $MODULO_PEN_VERSAO"
                
                make clean
                make dist
                cd dist
                files=( *.zip )
                f="${files[0]}"
                mkdir -p temp
                cp $f temp/
                cd temp/
                yes | unzip $f
                cp -Rf sei/* /opt/sei/
                cp -Rf sip/* /opt/sip/

                cd /opt/sei/web/modulos
                mv mod-sei-pen mod-sei-pen.old

                cd /opt/sei/config/mod-pen/
                mv ./ConfiguracaoModPEN.exemplo.php ConfiguracaoModPEN.php
                sed -i "s#\"SenhaCertificado\" => \"\"#'SenhaCertificado' => \"$MODULO_PEN_CERTIFICADO_SENHA\"#g" ConfiguracaoModPEN.php
                sed -i "s#\"WebService\" => \"\"#'WebService' => \"$MODULO_PEN_WEBSERVICE\"#g" ConfiguracaoModPEN.php

                # adiciona o certificado
                cd /opt/sei/config/mod-pen
                echo -n $MODULO_PEN_CERTIFICADO_BASE64 | base64 -d > certificado.pem
                echo "certificado copiado"
                cat certificado.pem

                # adiciona config
                cd /opt/sei
                sed -i "s#/\*novomodulo\*/#'PENIntegracao' => 'pen', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

                cd /opt
                echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php sip/scripts/mod-pen/sip_atualizar_versao_modulo_pen.php
                echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei/scripts/mod-pen/sei_atualizar_versao_modulo_pen.php
                
                rm -rf /opt/sei/web/modulos/mod-sei-pen.old
                
                echo "Iniciar Configuracao automatica do modulo"
                source /sei/files/scripts-e-automatizadores/modulos/mod-sei-pen/mod-sei-pen.sh

                touch /sei/controlador-instalacoes/instalado-modulo-pen.ok

        fi

    else

        echo "Arquivo de controle do Modulo PEN encontrado, provavelmente ja foi instalado, pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_PEN_INSTALAR nao setada para true, pulando configuracao..."

fi

echo "*****************************************************"
echo "*****************************************************"
echo "*INICIANDO CONFIGURACOES DO MODULO DE PETICIONAMENTO*"
echo "*****************************************************"
echo "*****************************************************"

if [ "$MODULO_PETICIONAMENTO_INSTALAR" == "true" ]; then

    if [ -f /sei/controlador-instalacoes/instalado-modulo-peticionamento.ok ]; then

        if [ -z "$MODULO_PETICIONAMENTO_VERSAO" ] || \
           [ -z "$MODULO_PETICIONAMENTO_URL" ]; then
            echo "Informe as seguinte variaveis de ambiente no container:"
            echo "MODULO_PETICIONAMENTO_VERSAO, MODULO_PETICIONAMENTO_URL"

        else

            echo "Verificando existencia do modulo de PETICIONAMENTO"
            if [ -d "/opt/sei/web/modulos/peticionamento" ]; then
                echo "Ja existe um diretorio para o modulo de PETICIONAMENTO. Vamos assumir que o codigo la esteja integro"

            else
                echo "Copiando o modulo de PETICIONAMENTO"
                cp -Rf /sei-modulos/peticionamento/* /opt
            fi

            cd /opt/sei/

            sed -i "s#/\*novomodulo\*/#'PeticionamentoIntegracao' => 'peticionamento', /\*novomodulo\*/#g" config/ConfiguracaoSEI.php

            cd /opt
            echo -ne "$APP_DB_SIP_USERNAME\n$APP_DB_SIP_PASSWORD\n" | php sip/scripts/sip_atualizar_versao_modulo_peticionamento.php
            echo -ne "$APP_DB_SEI_USERNAME\n$APP_DB_SEI_PASSWORD\n" | php sei/scripts/sei_atualizar_versao_modulo_peticionamento.php

            touch /sei/controlador-instalacoes/instalado-modulo-peticionamento.ok

        fi

    else

        echo "Arquivo de controle do Modulo de peticionamento encontrado, provavelmente ja foi instalado, pulando configuracao do modulo"

    fi

else

    echo "Variavel MODULO_PETICIONAMENTO_INSTALAR nao setada para true, pulando configuracao..."

fi



touch /sei/controlador-instalacoes/instalado.ok

echo "***************************************************"
echo "Atualizador chegou ao final..."
echo "Verifique nas mensagens acima se ocorreu tudo certo."
echo "Talvez haja alguma observacao importante"
echo "Fechando e liberando para o modulo de aplicacao"
echo "***************************************************"

