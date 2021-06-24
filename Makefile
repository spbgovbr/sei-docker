
include envlocal.env

ifeq ("$(MAKEFILE_MODO_VERBOSE)",  "true")
SHELL = sh -xv
endif

EXISTE_LOCAL_DB := $(shell docker volume ls | grep $(VOLUME_DB) )
EXISTE_LOCAL_FONTES := $(shell docker volume ls | grep $(VOLUME_FONTES) )
EXISTE_LOCAL_ARQUIVOS_EXTERNOS := $(shell docker volume ls | grep $(VOLUME_ARQUIVOSEXTERNOS) )
EXISTE_LOCAL_SOLR := $(shell docker volume ls | grep $(VOLUME_SOLR) )
EXISTE_LOCAL_OPENLDAP_SLAPD := $(shell docker volume ls | grep $(VOLUME_OPENLDAP_SLAPD) )
EXISTE_LOCAL_OPENLDAP_DB := $(shell docker volume ls | grep $(VOLUME_OPENLDAP_DB) )
EXISTE_LOCAL_CONTROLADOR_INSTALACAO := $(shell docker volume ls | grep $(VOLUME_CONTROLADOR_INSTALACAO) )
EXISTE_LOCAL_CERTS := $(shell docker volume ls | grep $(VOLUME_CERTS) )


qtd := "2"

DIR := ${CURDIR}
COMMMADCOMPOSE = docker-compose -f orquestrators/docker-compose/docker-compose.yml 

help:   ## Lista de comandos disponiveis e descricao. Voce pode usar TAB para completar os comandos
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


criar_volumes: ## Cria todos os volumes necessarios ao projeto. As vezes eh necessario apagar os volumes antes, para apagar make apagar_volumes
	make criar_volume_fontes 
	make criar_volume_certs 
	make criar_volume_banco 
	make criar_volume_arquivos_externos 
	make criar_volume_solr 
	make criar_volume_openldap 
	make criar_volume_controlador_instalacao


criar_volume_fontes: ## Cria o volume docker com os fontes que serao consumidos pelo projeto
ifneq ("$(EXISTE_LOCAL_FONTES)",  "")
	@echo "Ja existe um volume de Fontes. Voce pode apaga-lo com o comando make apagar_volume_fontes"
else
	docker run --rm -v $(LOCALIZACAO_FONTES_SEI):/source -v $(VOLUME_FONTES):/opt -w /source alpine sh -c "cp -R infra sei sip /opt/"
endif


criar_volume_certs: ## Cria o volume docker com os certs que serao consumidos pelo projeto
ifneq ("$(EXISTE_LOCAL_CERTS)",  "")
	@echo "Ja existe um volume de Certificados. Voce pode apaga-lo com o comando make apagar_volume_certs"
else
	docker run --rm -v $(LOCALIZACAO_CERTS):/source -v $(VOLUME_CERTS):/destino -w /source --entrypoint="" processoeletronico/sei4-haproxydc:1.0.0 sh -c "echo ''; echo ''; echo ''; if [[ -f cert0.pem ]] ; then echo 'Cert para o balanceador ja existe, pulando.';echo ''; echo ''; echo '';  else echo 'Cert para o balanceador nao existe. Copiando auto assinado default...' ; cp /mycertexample/cert0.pem /destino ; echo 'Copiado';echo ''; echo ''; echo '';  fi"
	docker run --rm -v $(LOCALIZACAO_CERTS):/source -v $(VOLUME_CERTS):/destino -w /source --entrypoint="" processoeletronico/sei4-haproxydc:1.0.0 sh -c "if [ ! -f seiapp/sei-ca.pem ] || [ ! -f seiapp/sei.crt ] || [ ! -f seiapp/sei.key ]; then echo ''; echo ''; echo ''; echo 'CA, cert ou key nao existe para o SEI. O conteiner de app vai criar um auto assinado. Nao se preocupe caso esteja usando um cert valido no balanceador'; echo ''; echo ''; echo '';  else echo ''; echo ''; echo ''; echo 'CA, cert e key encontradas para o SEI, pulando.';  echo ''; echo ''; echo '';  fi"
	
endif

criar_volume_banco: ## Cria o volume docker para o banco de dados que serao consumidos pelo projeto
	
ifneq ("$(EXISTE_LOCAL_DB)",  "")
	@echo "Ja existe um volume de banco de dados. Voce pode apaga-lo com o comando make apagar_volume_banco"
else
	docker volume create $(VOLUME_DB)
endif

criar_volume_arquivos_externos: ## Cria o volume docker para os arquivos externos
	
ifneq ("$(EXISTE_LOCAL_ARQUIVOS_EXTERNOS)",  "")
	@echo "Ja existe um volume de Arquivos Externos. Voce pode apaga-lo com o comando make apagar_volume_arquivos_externos"
else
	docker volume create $(VOLUME_ARQUIVOSEXTERNOS)
endif

criar_volume_solr: ## Cria o volume docker para os dados do solr
	
ifneq ("$(EXISTE_LOCAL_SOLR)",  "")
	@echo "Ja existe um volume para o Solr. Voce pode apaga-lo com o comando make apagar_volume_solr"
else
	docker volume create $(VOLUME_SOLR)
endif

criar_volume_openldap_slapd: ## Cria o volume docker para a base do openldap slapd
	
ifneq ("$(EXISTE_LOCAL_OPENLDAP_SLAPD)",  "")
	@echo "Ja existe um volume de openldap slapd. Voce pode apaga-lo com o comando make apagar_volume_openldap_slapd"
else
	docker volume create $(VOLUME_OPENLDAP_SLAPD)
endif

criar_volume_openldap_db: ## Cria os volumes docker para a base do openldap db
	
ifneq ("$(EXISTE_LOCAL_OPENLDAP_DB)",  "")
	@echo "Ja existe um volume de openldap db. Voce pode apaga-lo com o comando make apagar_volume_openldap_db"
else
	docker volume create $(VOLUME_OPENLDAP_DB)
endif

criar_volume_openldap: criar_volume_openldap_slapd criar_volume_openldap_db

criar_volume_controlador_instalacao: ## Cria o volume para controlar a instalacao do SEI e modulos
	
ifneq ("$(EXISTE_LOCAL_CONTROLADOR_INSTALACAO)",  "")
	@echo "Ja existe um volume de controlador da instalacao. Voce pode apaga-lo com o comando make apagar_volume_controlador_instalacao"
else
	docker volume create $(VOLUME_CONTROLADOR_INSTALACAO)
endif


build_docker_compose: ## Construa o docker-compose.yml baseado no arquivo envlocal.env
	rm -f orquestrators/docker-compose/docker-compose.yml
	
	envsubst < orquestrators/docker-compose/docker-compose-template.yml > orquestrators/docker-compose/docker-compose.yml
	@echo "Agora vamos iniciar uma serie de substituicoes de variaveis para montar o docker-compose.yml"
	@echo "O comandos sed nao aparecem aqui na tela."
	@echo "Caso deseje que eles aparecam ative no envlocal.env o modo Debug"
	@sleep 3


ifeq ("$(APP_PORTA_80_MAP_EXPOR)",  "true")
	@sed -i'' -e "s|#ports:|ports:|" orquestrators/docker-compose/docker-compose.yml
	
ifneq ("$(APP_PORTA_80_MAP_EXPOR)",  "")
	@sed -i'' -e "s|#    - $(APP_PORTA_80_MAP)|    - $(APP_PORTA_80_MAP)|" orquestrators/docker-compose/docker-compose.yml
else
    @sed -i'' -e "|#    - $(APP_PORTA_80_MAP)|d" orquestrators/docker-compose/docker-compose.yml
endif
	@sed -i'' -e "s|nada|nada|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(APP_PORTA_443_MAP_EXPOR)",  "true")
	@sed -i'' -e "s|#ports:|ports:|" orquestrators/docker-compose/docker-compose.yml
	
ifneq ("$(APP_PORTA_443_MAP_EXPOR)",  "")
	@sed -i'' -e "s|#    - $(APP_PORTA_443_MAP)|    - $(APP_PORTA_443_MAP)|" orquestrators/docker-compose/docker-compose.yml
else
    @sed -i'' -e "|#    - $(APP_PORTA_443_MAP)|d" orquestrators/docker-compose/docker-compose.yml
endif
	@sed -i'' -e "s|nada|nada|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(APP_PROTOCOLO)",  "https")
	@sed -i'' -e "s|#- EXCLUDE_PORTS=80|- EXCLUDE_PORTS=80|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#- EXTRA_ROUTE_SETTINGS=ssl verify none|- EXTRA_ROUTE_SETTINGS=ssl verify none|" orquestrators/docker-compose/docker-compose.yml
endif
ifeq ("$(APP_PROTOCOLO)",  "http")
	@sed -i'' -e "s|#- EXCLUDE_PORTS=443|- EXCLUDE_PORTS=443|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(DBADMIN_PRESENTE)",  "true")
	@sed -i'' -e "s|#dbadmin: #servicedbadmin|dbadmin: #servicedbadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_DBADMIN} #servicedbadmin|    image: ${DOCKER_IMAGE_DBADMIN} #servicedbadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    environment: #servicedbadmin|    environment: #servicedbadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - VIRTUAL_HOST=http://${APP_HOST}/dbadmin\*,https://${APP_HOST}/dbadmin\* #servicedbadmin|        - VIRTUAL_HOST=http://${APP_HOST}/dbadmin*,https://${APP_HOST}/dbadmin* #servicedbadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - FORCE_SSL=true #servicedbadmin|        - FORCE_SSL=true #servicedbadmin|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(MEMCACHEDADMIN_PRESENTE)",  "true")
    
	@sed -i'' -e "s|#memcachedadmin: #servicememcachedadmin|memcachedadmin: #servicememcachedadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_MEMCACHEDADMIN} #servicememcachedadmin|    image: ${DOCKER_IMAGE_MEMCACHEDADMIN}  #servicememcachedadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    environment: #servicememcachedadmin|    environment: #servicememcachedadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - VIRTUAL_HOST=http://${APP_HOST}/memcachedadmin\*,https://${APP_HOST}/memcachedadmin\* #servicememcachedadmin|        - VIRTUAL_HOST=http://${APP_HOST}/memcachedadmin*,https://${APP_HOST}/memcachedadmin* #servicememcachedadmin|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - FORCE_SSL=true #servicememcachedadmin|        - FORCE_SSL=true #servicememcachedadmin|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(JOD_PRESENTE)",  "true")
	@sed -i'' -e "s|#jod: #servicejod|jod: #servicejod|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_JOD} #servicejod|    image: ${DOCKER_IMAGE_JOD} #servicejod|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#- jod:jod #servicejod|- jod:jod #servicejod|g" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(MAIL_CATCHER_PRESENTE)",  "true")
	@sed -i'' -e "s|#mail: #servicemail|mail: #servicemail|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_MAIL} #servicemail|    image: ${DOCKER_IMAGE_MAIL} #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e 's|#    command: \["mailcatcher", "--no-quit", "--foreground", "--ip=0.0.0.0", "--smtp-port=25", "--http-port=80"\] #servicemail|    command: ["mailcatcher", "--no-quit", "--foreground", "--ip=0.0.0.0", "--smtp-port=25", "--http-port=80"] #servicemail|'	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    expose: #servicemail|    expose: #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - 25 #servicemail|        - 25 #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - 80 #servicemail|        - 80 #servicemail|"	 orquestrators/docker-compose/docker-compose.yml	
	@sed -i'' -e "s|#    environment: #servicemail|    environment: #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - VIRTUAL_HOST=http://${APP_HOST}/mailadmin\*,https://${APP_HOST}/mailadmin\*,http://${APP_HOST}/assets\*,https://${APP_HOST}/assets\*,http://${APP_HOST}/messages\*,https://${APP_HOST}/messages\* #servicemail|        - VIRTUAL_HOST=http://${APP_HOST}/mail*,https://${APP_HOST}/mail*,http://${APP_HOST}/assets*,https://${APP_HOST}/assets*,http://${APP_HOST}/messages*,https://${APP_HOST}/messages* #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - FORCE_SSL=true #servicemail|        - FORCE_SSL=true #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - EXCLUDE_PORTS=25,1080,1025 #servicemail|        - EXCLUDE_PORTS=25,1080,1025 #servicemail|"	 orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e 's|#        - EXTRA_SETTINGS=http-request set-path "%\[path\\,regsub(^/mailadmin\\,/)\]" #servicemail|        - EXTRA_SETTINGS=http-request set-path "%[path\\,regsub(^/mailadmin\\,/)]" #servicemail|'	 orquestrators/docker-compose/docker-compose.yml

endif

ifeq ("$(OPENLDAP_PRESENTE)",  "true")
	@sed -i'' -e "s|#ldapadmin: #serviceldap|ldapadmin: #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_OPENLDAP_PHPLDAPADMIN} #serviceldap|    image: ${DOCKER_IMAGE_OPENLDAP_PHPLDAPADMIN} #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    environment: #serviceldap|    environment: #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - PHPLDAPADMIN_LDAP_CLIENT_TLS=false #serviceldap|        - PHPLDAPADMIN_LDAP_CLIENT_TLS=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - PHPLDAPADMIN_LDAP_HOSTS=openldap #serviceldap|        - PHPLDAPADMIN_LDAP_HOSTS=openldap #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - PHPLDAPADMIN_HTTPS=false #serviceldap|        - PHPLDAPADMIN_HTTPS=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - PHPLDAPADMIN_TRUST_PROXY_SSL=true #serviceldap|        - PHPLDAPADMIN_TRUST_PROXY_SSL=true #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - VIRTUAL_HOST=http://${APP_HOST}/phpldapadmin\*,https://${APP_HOST}/phpldapadmin\* #serviceldap|        - VIRTUAL_HOST=http://${APP_HOST}/phpldapadmin*,https://${APP_HOST}/phpldapadmin* #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - EXCLUDE_PORTS=443 #serviceldap|        - EXCLUDE_PORTS=443 #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - FORCE_SSL=true #serviceldap|        - FORCE_SSL=true #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    links: #serviceldap|    links: #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - openldap:openldap #serviceldap|        - openldap:openldap #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#openldap: #serviceldap|openldap: #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_OPENLDAP} #serviceldap|    image: ${DOCKER_IMAGE_OPENLDAP} #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    environment: #serviceldap|    environment: #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - KEEP_EXISTING_CONFIG=false #serviceldap|        - KEEP_EXISTING_CONFIG=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_ADMIN_PASSWORD=${OPENLDAP_ADMIN_PASSWORD} #serviceldap|        - LDAP_ADMIN_PASSWORD=${OPENLDAP_ADMIN_PASSWORD} #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_BACKEND=mdb #serviceldap|        - LDAP_BACKEND=mdb #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_BASE_DN= #serviceldap|        - LDAP_BASE_DN= #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_CONFIG_PASSWORD=configldap #serviceldap|        - LDAP_CONFIG_PASSWORD=configldap #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_DOMAIN=pen.gov.br #serviceldap|        - LDAP_DOMAIN=pen.gov.br #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_LOG_LEVEL=256 #serviceldap|        - LDAP_LOG_LEVEL=256 #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_ORGANISATION=Processo Eletronico Nacional #serviceldap|        - LDAP_ORGANISATION=Processo Eletronico Nacional #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_READONLY_USER=false #serviceldap|        - LDAP_READONLY_USER=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_REMOVE_CONFIG_AFTER_SETUP=true #serviceldap|        - LDAP_REMOVE_CONFIG_AFTER_SETUP=true #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_REPLICATION=false #serviceldap|        - LDAP_REPLICATION=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_RFC2307BIS_SCHEMA=false #serviceldap|        - LDAP_RFC2307BIS_SCHEMA=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_SSL_HELPER_PREFIX=ldap #serviceldap|        - LDAP_SSL_HELPER_PREFIX=ldap #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - LDAP_TLS=false #serviceldap|        - LDAP_TLS=false #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    volumes_from: #serviceldap|    volumes_from: #serviceldap|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - storage-openldap #serviceldap|        - storage-openldap #serviceldap|" orquestrators/docker-compose/docker-compose.yml

endif


ifeq ("$(BALANCEADOR_PRESENTE)",  "true")
	@sed -i'' -e "s|#balanceador: #servicebal|balanceador: #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    image: ${DOCKER_IMAGE_BALANCEADOR} #servicebal|    image: ${DOCKER_IMAGE_BALANCEADOR} #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    links: #servicebal|    links: #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - app #servicebal|        - app #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - solr #servicesolr #servicebal|        - solr #servicesolr #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    environment: #servicebal|    environment: #servicebal|" orquestrators/docker-compose/docker-compose.yml	
	@sed -i'' -e "s|#        - EXTRA_FRONTEND_SETTINGS_80=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root #servicebal|        - EXTRA_FRONTEND_SETTINGS_80=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root #servicebal|" orquestrators/docker-compose/docker-compose.yml	
	@sed -i'' -e "s|#        - EXTRA_FRONTEND_SETTINGS_443=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root #servicebal|        - EXTRA_FRONTEND_SETTINGS_443=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root #servicebal|" orquestrators/docker-compose/docker-compose.yml	
	@sed -i'' -e "s|#        - CERT_FOLDER=/certs #servicebal|        - CERT_FOLDER=/certs #servicebal|" orquestrators/docker-compose/docker-compose.yml	
	@sed -i'' -e "s|#    volumes_from: #servicebal|    volumes_from: #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - storage-certs #servicebal|        - storage-certs #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#    volumes: #servicebal|    volumes: #servicebal|" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|#        - /var/run/docker.sock:/var/run/docker.sock #servicebal|        - /var/run/docker.sock:/var/run/docker.sock #servicebal|g" orquestrators/docker-compose/docker-compose.yml
	@sed -i'' -e "s|nada|nada|" orquestrators/docker-compose/docker-compose.yml
	
	
ifeq ("$(DBADMIN_PRESENTE)",  "true")
	@sed -i'' -e "s|#        - dbadmin #servicedbadmin #servicebal|        - dbadmin #servicedbadmin #servicebal|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(MEMCACHEDADMIN_PRESENTE)",  "true")
	@sed -i'' -e "s|#        - memcachedadmin #servicememcachedadmin #servicebal|        - memcachedadmin #servicememcachedadmin #servicebal|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(MAIL_CATCHER_PRESENTE)",  "true")
	@sed -i'' -e "s|#        - mail #servicemail #servicebal|        - mail #servicemail #servicebal|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(OPENLDAP_PRESENTE)",  "true")
	@sed -i'' -e "s|#        - ldapadmin #serviceldap #servicebal|        - ldapadmin #serviceldap #servicebal|" orquestrators/docker-compose/docker-compose.yml
endif



ifeq ("$(BALANCEADOR_PORTA_80_MAP_EXPOR)",  "true")
	@sed -i'' -e "s|#    ports: #servicebal|    ports: #servicebal|" orquestrators/docker-compose/docker-compose.yml
ifneq ("$(BALANCEADOR_PORTA_80_MAP_EXPOR)",  "")
	@sed -i'' -e "s|#        - $(BALANCEADOR_PORTA_80_MAP) #servicebal|        - $(BALANCEADOR_PORTA_80_MAP) #servicebal|" orquestrators/docker-compose/docker-compose.yml
else
    @sed -i'' -e "|#        - $(BALANCEADOR_PORTA_80_MAP) #servicebal|d" orquestrators/docker-compose/docker-compose.yml
endif
endif

ifeq ("$(BALANCEADOR_PORTA_443_MAP_EXPOR)",  "true")
	@sed -i'' -e "s|#    ports: #servicebal|    ports: #servicebal|" orquestrators/docker-compose/docker-compose.yml
ifneq ("$(BALANCEADOR_PORTA_443_MAP_EXPOR)",  "")
	@sed -i'' -e "s|#        - $(BALANCEADOR_PORTA_443_MAP) #servicebal|        - $(BALANCEADOR_PORTA_443_MAP) #servicebal|" orquestrators/docker-compose/docker-compose.yml
else
    @sed -i'' -e "|#        - $(BALANCEADOR_PORTA_443_MAP) #servicebal|d" orquestrators/docker-compose/docker-compose.yml
endif
endif


endif



run: ## roda na sequencia build_docker_compose e up -d

ifeq ("$(EXISTE_LOCAL_DB)",  "")
	@echo "Nao existe volume para o banco, rode antes o comando make criar_volume_banco ou make criar_volumes"

else ifeq ("$(EXISTE_LOCAL_FONTES)",  "")
	@echo "Nao existe volume para os fontes, rode antes o comando make criar_volume_fontes - Verifique antes a variavel LOCALIZACAO_FONTES_SEI no seu env-local.env"
	@echo "A mesma tem que apontar para o diretorio de fontes do SEI (infra, sei, sip)"

else ifeq ("$(EXISTE_LOCAL_ARQUIVOS_EXTERNOS)",  "")
	@echo "Nao existe volume para os arquivos externos, rode antes o comando make criar_volume_arquivos_externos ou make criar_volumes"

else ifeq ("$(EXISTE_LOCAL_SOLR)",  "")
	@echo "Nao existe volume para o SOLR, rode antes o comando make criar_volume_solr ou make criar_volumes"

else ifeq ("$(EXISTE_LOCAL_CONTROLADOR_INSTALACAO)",  "")
	@echo "Nao existe volume para o Controlador de Instalacao, rode antes o comando make criar_volume_controlador_instalacao ou make criar_volumes"

else ifeq ("$(EXISTE_LOCAL_CERTS)",  "")
	@echo "Nao existe volume para os Certificados, rode antes o comando make criar_volume_certs ou make criar_volumes"

else ifeq ("$(EXISTE_LOCAL_OPENLDAP_SLAPD)",  "")
	@echo "Nao existe volume para os OpenldapSlapd, rode antes o comando make criar_volume_openldap_slapd ou make criar_volumes"

else ifeq ("$(EXISTE_LOCAL_OPENLDAP_DB)",  "")
	@echo "Nao existe volume para os Openldap Database, rode antes o comando make criar_volume_openldap_db ou make criar_volumes"

else
	make build_docker_compose
	$(COMMMADCOMPOSE) up -d --remove-orphans
endif


setup: ## executa criar_volumes e run na sequencia
	make criar_volumes 
	make run

scale: ## escala os nohs de aplicacao do SEI para 2. Caso vc queira mais de 2 basta usar o comando make qtd=3 scale, substituindo o 3 pelo numero desejado, ou o comando docker-compose scale app=x na pasta orquestrators/docker-compose

	@echo "escala os nohs de aplicacao do SEI para 2. Caso vc queira mais de 2 basta usar o comando make qtd=3 scale, substituindo o 3 pelo numero desejado, ou o comando docker-compose scale app=3 na pasta orquestrators/docker-compose"
	
	
ifeq ("$(BALANCEADOR_PRESENTE)",  "true")
	$(COMMMADCOMPOSE) scale app=$(qtd)
else
	echo "Scale nao efetuado. Precisa de um balanceador"
endif


stop: ## docker-compose stop e docker-compose rm -f
	$(COMMMADCOMPOSE) stop
	$(COMMMADCOMPOSE) rm -f

logs: ## docker-compose logs -f pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f

logs_app: ## docker-compose logs -f app pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f app

logs_app-atualizador: ## docker-compose logs -f app-atualizador pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f app-atualizador

logs_balanceador: ## docker-compose logs -f balanceador pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f balanceador
	
logs_openldap: ## docker-compose logs -f openldap pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f openldap

logs_solr: ## docker-compose logs -f solr pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f solr

clear: ## pahra o projeto e remove tds os conteineres, redes criados. Nao remove os volumes
	make stop
	$(COMMMADCOMPOSE) down -v --remove-orphans

apagar_volumes: ## Apaga todos os volumes do projeto ATENCAO TODOS OS DADOS DE BASE E ARQUIVOS SERAO DELETADOS
	make apagar_volume_fontes 
	make apagar_volume_certs
	make apagar_volume_banco 
	make apagar_volume_arquivos_externos 
	make apagar_volume_solr
	make apagar_volume_openldap
	make apagar_volume_controlador_instalacao


apagar_volume_fontes: ## Apaga o volume docker com os fontes que serao consumidos pelo projeto
	docker volume rm $(VOLUME_FONTES) || true

apagar_volume_certs: ## Apaga o volume docker com os certs que serao consumidos pelo projeto
	docker volume rm $(VOLUME_CERTS) || true
	
apagar_volume_banco: ## Apagar volume do banco
	docker volume rm $(VOLUME_DB) || true

apagar_volume_arquivos_externos: ## Apagar volume Arquivos Externos
	docker volume rm $(VOLUME_ARQUIVOSEXTERNOS) || true

apagar_volume_solr: ## Apagar volume Solr
	docker volume rm $(VOLUME_SOLR) || true

apagar_volume_openldap: ## Apagar volumes OpenLDAP
	docker volume rm $(VOLUME_OPENLDAP_SLAPD) || true
	docker volume rm $(VOLUME_OPENLDAP_DB) || true

apagar_volume_controlador_instalacao: ## Apagar volume controlador da instalacao
	docker volume rm $(VOLUME_CONTROLADOR_INSTALACAO) || true

clear_all: #Apaga tanto os containers quanto os volumes, iniciando do zero o ambiente
	make clear
	make apagar_volumes

bash_app: #Acessa o container de app, caso for diferente do app_1, passar id=X
	@[ "${id}" ] && docker exec -it docker-compose_app_$(id) bash || ( docker exec -it docker-compose_app_1 bash ;exit 1 )

	
