SHELL := /bin/bash

ifneq ("$(wildcard envcontainers.env)","")
include envcontainers.env
endif

IMAGEMTMP_FULL=$(DOCKER_REGISTRY)/$(IMAGEMTMP):$(IMAGEMTMP_VERSAO)

define GIT_USERPASSMSG
Var GITUSER_REPO_MODULOS ou GITPASS_REPO_MODULOS nao definida.
Faca o export delas com o seu user do github para baixar os modulos

export GITUSER_REPO_MODULOS=seuusergithub
export GITPASS_REPO_MODULOS=seutokengithubparapull

Depois disso rode o comando novamente..
endef
export GIT_USERPASSMSG


help: ## Mostra essa ajuda. Voce pode usar tab para completar os comandos
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | sed -e 's|^\ ||'


verificar-parametro-imagemtmp: ## target de apoio para verificar se o parametro make IMAGEMTMP foi passado
		@if [ "$(IMAGEMTMP)" == "" ]; then echo "Informe a variavel make: IMAGEMTMP"; exit 1; fi;


verificar-parametro-imagemtmp-versao: ## target de apoio para verificar se o parametro make IMAGEMTMP_VERSAO foi passado
		@if [ "$(IMAGEMTMP_VERSAO)" == "" ]; then echo "Informe a variavel make: IMAGEMTMP_VERSAO"; exit 1; fi;


verificar-parametro-imagemtmp-path: ## target de apoio para verificar se o parametro make IMAGEMTMP_PATH foi passado
		@if [ "$(IMAGEMTMP_PATH)" == "" ]; then echo "Informe a variavel make: IMAGEMTMP_PATH"; exit 1; fi;


## verificar-conteiner-imagem-rodando: target de apoio para verificar se ha conteiner rodando com determinada imagem
verificar-conteiner-imagem-rodando: verificar-parametro-imagemtmp verificar-parametro-imagemtmp-versao 
		@echo "Verificando se a imagem: $(IMAGEMTMP_FULL) esta rodando ou parada no pool"; \
    if test "$$(docker ps -a | grep $(IMAGEMTMP_FULL))"; then \
      echo "Imagem $(IMAGEMTMP_FULL) está sendo usada ou ainda persiste no pool docker stopada."; \
						echo "Pare e remova antes o conteiner. Abandonando execucao..."; \
						echo ""; echo ""; \
      exit 1; \
		fi


verificar-docker-registry: ## target de apoio para verificar se a variavel DOCKER_REGISTRY E VERSAO foram informadas
		@if [ -z "$(DOCKER_REGISTRY)" ]; then echo "Var DOCKER_REGISTRY nao definida"; make envcontainers.env; fi
		@if [ -z "$(DOCKER_CONTAINER_VERSAO_PRODUTO)" ]; then echo "Var DOCKER_CONTAINER_VERSAO_PRODUTO nao definida"; exit 1; fi


verificar-imagem-base-centos: ## target de apoio para verificar se a imagem base do Centos foi informada
		@if [ -z "$(IMAGEM_BASE_CENTOS)" ]; then echo "Var IMAGEM_BASE_CENTOS nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_CENTOS_VERSAO)" ]; then echo "Var IMAGEM_BASE_CENTOS_VERSAO nao definida"; exit 1; fi


verificar-imagem-base-mariadb: ## target de apoio para verificar se a imagem base do mariadb foi informada
		@if [ -z "$(IMAGEM_BASE_MARIADB)" ]; then echo "Var IMAGEM_BASE_MARIADB nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_MARIADB_VERSAO)" ]; then echo "Var IMAGEM_BASE_MARIADB_VERSAO nao definida"; exit 1; fi

		
verificar-imagem-base-sqlserver: ## target de apoio para verificar se a imagem base do sqlserver foi informada
		@if [ -z "$(IMAGEM_BASE_SQLSERVER)" ]; then echo "Var IMAGEM_BASE_SQLSERVER nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_SQLSERVER_VERSAO)" ]; then echo "Var IMAGEM_BASE_SQLSERVER_VERSAO nao definida"; exit 1; fi


verificar-imagem-base-oracle: ## target de apoio para verificar se a imagem base do oracle foi informada
		@if [ -z "$(IMAGEM_BASE_ORACLE)" ]; then echo "Var IMAGEM_BASE_ORACLE nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_ORACLE_VERSAO)" ]; then echo "Var IMAGEM_BASE_ORACLE_VERSAO nao definida"; exit 1; fi


verificar-imagem-base-app-agendador: ## target de apoio para verificar se a imagem base do app-agendador foi informada
		@if [ -z "$(IMAGEM_BASE_APP_AGENDADOR)" ]; then echo "Var IMAGEM_BASE_APP_AGENDADOR nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_APP_AGENDADOR_VERSAO)" ]; then echo "Var IMAGEM_BASE_APP_AGENDADOR_VERSAO nao definida"; exit 1; fi


verificar-imagem-base-app: ## target de apoio para verificar se a imagem base do app foi informada
		@if [ -z "$(IMAGEM_BASE_APP)" ]; then echo "Var IMAGEM_BASE_APP nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_APP_VERSAO)" ]; then echo "Var IMAGEM_BASE_APP_VERSAO nao definida"; exit 1; fi		


verificar-imagem-base-haproxy: ## target de apoio para verificar se a imagem base do haproxy foi informada
		@if [ -z "$(IMAGEM_BASE_HAPROXY)" ]; then echo "Var IMAGEM_BASE_HAPROXY nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_HAPROXY_VERSAO)" ]; then echo "Var IMAGEM_BASE_HAPROXY_VERSAO nao definida"; exit 1; fi


verificar-imagem-base-openldap: ## target de apoio para verificar se a imagem base do openldap foi informada
		@if [ -z "$(IMAGEM_BASE_OPENLDAP)" ]; then echo "Var IMAGEM_BASE_OPENLDAP nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_OPENLDAP_VERSAO)" ]; then echo "Var IMAGEM_BASE_OPENLDAP_VERSAO nao definida"; exit 1; fi		


verificar-imagem-base-phpmemcachedadmin: ## target de apoio para verificar se a imagem base do phpmemcachedadmin foi informada
		@if [ -z "$(IMAGEM_BASE_PHPMEMCACHEDADMIN)" ]; then echo "Var IMAGEM_BASE_PHPMEMCACHEDADMIN nao definida"; make envcontainers.env; fi
		@if [ -z "$(IMAGEM_BASE_PHPMEMCACHEDADMIN_VERSAO)" ]; then echo "Var IMAGEM_BASE_PHPMEMCACHEDADMIN_VERSAO nao definida"; exit 1; fi		


verificar-git-parameters: ## target de apoio para verificar se usuario e token git foram passados. Necessario para a imagem app-ci
		@if [ -z "$(GITUSER_REPO_MODULOS)" ]; then echo "$$GIT_USERPASSMSG"; exit 1; fi
		@if [ -z "$(GITPASS_REPO_MODULOS)" ]; then echo "$$GIT_USERPASSMSG"; exit 1; fi


getenv: ## target de apoio para copiar o arquivo envcontainers.env baseado no modelo envcontaners.env.modelo
		@cp -f envcontainers.env.modelo envcontainers.env
		@echo "Arquivo envcontainers.env criado com valores default.";
		@echo "Abra o arquivo e faca as alteracoes para o seu ambiente."; 

envcontainers.env: ## target de apoio para verificar se existe arquivo envcontaners.env
		@echo "Nao encontrado arquivo env para as operacoes de containeres."
		@echo "Ha um arquivo modelo (envcontainers.env.modelo) que vc pode usar para preencher com suas informacoes."
		@echo "Basta rodar make getenv"
		@echo "Em seguida, altere nesse arquivo as informacoes para o seu ambiente"
		exit 1



#*****************************************************
#*******ERASE TARGETS*********************************
#*****************************************************

## erase-conteiner-generic: target generico para apagar imagem passada por parametro
erase-conteiner-generic: verificar-conteiner-imagem-rodando
		@if test -z "$$(docker images -q $(IMAGEMTMP_FULL))"; then \
		    echo "Imagem $(IMAGEMTMP_FULL) nao existe. Pulando para o proximo passo"; \
		else \
		    echo "Apagando a imagem: $(IMAGEMTMP_FULL)"; \
		    docker rmi $(IMAGEMTMP_FULL); \
		fi;



erase-conteiner-base-centos: ## apagar a imagem base-centos
		@make IMAGEMTMP=base-centos7 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-memcached: ## apagar a imagem memcached
		@make IMAGEMTMP=memcached IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-jod: ## apagar a imagem jod
		@make IMAGEMTMP=jod IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-mailcatcher: ## apagar a imagem mailcatcher
		@make IMAGEMTMP=mailcatcher IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-base-mariadb: ## apagar a imagem base-mariadb
		@make IMAGEMTMP=base-mariadb10.5 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-mariadb: ## apagar a imagem mariadb
		@make IMAGEMTMP=mariadb10.5 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-base-sqlserver: ## apagar a imagem base-sqlserver
		@make IMAGEMTMP=base-sqlserver2017 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-sqlserver: ## apagar a imagem sqlserver
		@make IMAGEMTMP=sqlserver2017 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-base-oracle: ## apagar a imagem base-oracle
		@make IMAGEMTMP=base-oracle11g IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-oracle: ## apagar a imagem oracle
		@make IMAGEMTMP=oracle11g IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-solr: ## apagar a imagem solr
		@make IMAGEMTMP=solr8.2.0 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-base-app: ## apagar a imagem base-app
		@make IMAGEMTMP=base-app IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-app-dev: ## apagar a imagem app-dev
		@make IMAGEMTMP=app-dev IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-app-ci-agendador: ## apagar a imagem app-ci-agendador
		@make IMAGEMTMP=app-ci-agendador IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-app-ci: ## apagar a imagem app-ci
		@make IMAGEMTMP=app-ci IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-haproxy-base: ## apagar a imagem haproxy-base
		@make IMAGEMTMP=haproxy-base IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-haproxy: ## apagar a imagem haproxy
		@make IMAGEMTMP=haproxy IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-openldap-base: ## apagar a imagem openldap-base
		@make IMAGEMTMP=openldap-base IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-openldap: ## apagar a imagem openldap
		@make IMAGEMTMP=openldap IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-phpmemcachedadmin-base: ## apagar a imagem phpmemcachedadmin-base
		@make IMAGEMTMP=phpmemcachedadmin-base IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-phpmemcachedadmin: ## apagar a imagem phpmemcachedadmin
		@make IMAGEMTMP=phpmemcachedadmin IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-phpldapadmin: ## apagar a imagem phpldapadmin
		@make IMAGEMTMP=phpldapadmin IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic


erase-conteiner-dbadminer: ## apagar a imagem dbadminer
		@make IMAGEMTMP=dbadminer IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) erase-conteiner-generic

## erase-conteiners-local: target que chama todos os outros targets para apagar as imagens do projeto
erase-conteiners-local: erase-conteiner-app-ci-agendador erase-conteiner-app-ci erase-conteiner-app-dev erase-conteiner-base-app erase-conteiner-solr erase-conteiner-memcached erase-conteiner-jod erase-conteiner-mailcatcher erase-conteiner-mariadb erase-conteiner-base-mariadb erase-conteiner-sqlserver erase-conteiner-base-sqlserver erase-conteiner-oracle erase-conteiner-base-oracle erase-conteiner-base-centos erase-conteiner-phpmemcachedadmin erase-conteiner-phpmemcachedadmin-base erase-conteiner-openldap erase-conteiner-openldap-base erase-conteiner-haproxy erase-conteiner-haproxy-base erase-conteiner-phpldapadmin erase-conteiner-dbadminer


#*****************************************************
#*******BUILD CONTAINERS TARGETS**********************
#*****************************************************

## build-conteiner-generic: target generico para construir imagens passadas por parametro
build-conteiner-generic: verificar-docker-registry verificar-parametro-imagemtmp-path
		@make IMAGEMTMP=$(IMAGEMTMP) IMAGEMTMP_VERSAO=$(IMAGEMTMP_VERSAO) verificar-conteiner-imagem-rodando
		docker build --no-cache $(BUILD_ARGS) -t $(IMAGEMTMP_FULL) $(IMAGEMTMP_PATH)
		@if [ "$(IMAGEMTMP_VERSAO)" != "test" ]; then \
		docker tag $(IMAGEMTMP_FULL) $(DOCKER_REGISTRY)/$(IMAGEMTMP):latest; \
		fi;


build-conteiner-base-centos: ## construir imagem base-centos
		@make IMAGEMTMP=base-centos7 \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=base-centos \
					build-conteiner-generic


build-conteiner-memcached: ## construir imagem memcached
		@make IMAGEMTMP=memcached \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=memcached \
					build-conteiner-generic


## build-conteiner-jod: construir imagem jod
build-conteiner-jod: verificar-imagem-base-centos
		@make IMAGEMTMP=jod \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=jod \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_CENTOS):$(IMAGEM_BASE_CENTOS_VERSAO) \
					build-conteiner-generic


build-conteiner-mailcatcher: ## construir imagem mailcatcher
		@make IMAGEMTMP=mailcatcher \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=mailcatcher \
					build-conteiner-generic


build-conteiner-base-mariadb: ## construir imagem base-mariadb
		@make IMAGEMTMP=base-mariadb10.5 \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=databases/mariadb-base \
					build-conteiner-generic


## build-conteiner-mariadb construir imagem mariadb
build-conteiner-mariadb: verificar-imagem-base-mariadb
		@make IMAGEMTMP=mariadb10.5 \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=databases/mariadb \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_MARIADB):$(IMAGEM_BASE_MARIADB_VERSAO) \
					build-conteiner-generic


build-conteiner-base-sqlserver:  ## construir imagem base-sqlserver
		@make IMAGEMTMP=base-sqlserver2017 \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=databases/sqlserver-base \
					build-conteiner-generic


## build-conteiner-sqlserver construir imagem sqlserver
build-conteiner-sqlserver: verificar-imagem-base-sqlserver
		@make IMAGEMTMP=sqlserver2017 \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=databases/sqlserver \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_SQLSERVER):$(IMAGEM_BASE_SQLSERVER_VERSAO) \
					build-conteiner-generic


build-conteiner-base-oracle: ## construir imagem base-oracle
		@make IMAGEMTMP=base-oracle11g \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=databases/oracle-base \
					build-conteiner-generic


## build-conteiner-oracle construir imagem oracle
build-conteiner-oracle: verificar-imagem-base-oracle
		@make IMAGEMTMP=oracle11g \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=databases/oracle \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_ORACLE):$(IMAGEM_BASE_ORACLE_VERSAO) \
					build-conteiner-generic


## build-conteiner-solr construir imagem solr
build-conteiner-solr: verificar-imagem-base-centos
		@make IMAGEMTMP=solr8.2.0 \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=solr \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_CENTOS):$(IMAGEM_BASE_CENTOS_VERSAO) \
					build-conteiner-generic


## build-conteiner-base-app construir imagem base-app
build-conteiner-base-app: verificar-imagem-base-centos
		@make IMAGEMTMP=base-app \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=app/app-base \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_CENTOS):$(IMAGEM_BASE_CENTOS_VERSAO) \
					build-conteiner-generic


## build-conteiner-app-dev construir imagem app-dev
build-conteiner-app-dev: verificar-imagem-base-app
		@make IMAGEMTMP=app-dev \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=app/app-dev \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_APP):$(IMAGEM_BASE_APP_VERSAO) \
					build-conteiner-generic


## build-conteiner-app-ci construir imagem app-ci
build-conteiner-app-ci: verificar-imagem-base-app verificar-git-parameters
		@make IMAGEMTMP=app-ci \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=app/app-ci \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_APP):$(IMAGEM_BASE_APP_VERSAO)\ --build-arg\ GITUSER_REPO_MODULOS=$(GITUSER_REPO_MODULOS)\ --build-arg\ GITPASS_REPO_MODULOS=$(GITPASS_REPO_MODULOS) \
					build-conteiner-generic


## build-conteiner-app-ci-agendador construir imagem app-ci-agendador
build-conteiner-app-ci-agendador: verificar-imagem-base-app-agendador
		@make IMAGEMTMP=app-ci-agendador \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=app/app-ci-agendador \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_APP_AGENDADOR):$(IMAGEM_BASE_APP_AGENDADOR_VERSAO) \
					build-conteiner-generic


## build-conteiner-haproxy-base construir imagem haproxy-base
build-conteiner-haproxy-base:
		@make IMAGEMTMP=haproxy-base \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=haproxy/haproxy-base \
					build-conteiner-generic


## build-conteiner-haproxy construir imagem haproxy
build-conteiner-haproxy: verificar-imagem-base-haproxy
		@make IMAGEMTMP=haproxy \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=haproxy/haproxy \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_HAPROXY):$(IMAGEM_BASE_HAPROXY_VERSAO) \
					build-conteiner-generic


build-conteiner-openldap-base: ## construir imagem openldap-base
		@make IMAGEMTMP=openldap-base \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=openldap/openldap-base \
					build-conteiner-generic


## build-conteiner-openldap construir imagem openldap
build-conteiner-openldap: verificar-imagem-base-openldap
		@make IMAGEMTMP=openldap \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=openldap/openldap \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_OPENLDAP):$(IMAGEM_BASE_OPENLDAP_VERSAO) \
					build-conteiner-generic


build-conteiner-phpmemcachedadmin-base:  ## construir imagem phpmemcachedadmin-base
		@make IMAGEMTMP=phpmemcachedadmin-base \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=phpmemcachedadmin/phpmemcachedadmin-base \
					build-conteiner-generic


## build-conteiner-phpmemcachedadmin construir imagem phpmemcachedadmin
build-conteiner-phpmemcachedadmin: verificar-imagem-base-phpmemcachedadmin
		@make IMAGEMTMP=phpmemcachedadmin \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=phpmemcachedadmin/phpmemcachedadmin \
					BUILD_ARGS=--build-arg\ IMAGEM_BASE=$(IMAGEM_BASE_PHPMEMCACHEDADMIN):$(IMAGEM_BASE_PHPMEMCACHEDADMIN_VERSAO) \
					build-conteiner-generic


build-conteiner-phpldapadmin: ## construir imagem phpldapadmin
		@make IMAGEMTMP=phpldapadmin \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=phpldapadmin \
					build-conteiner-generic

build-conteiner-dbadminer: ## construir imagem dbadminer
		@make IMAGEMTMP=dbadminer \
		      IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) \
					IMAGEMTMP_PATH=dbadminer \
					build-conteiner-generic


## build-conteiners: roda todos os outros targets de build na sequencia
build-conteiners: verificar-git-parameters build-conteiner-base-centos build-conteiner-memcached build-conteiner-jod build-conteiner-mailcatcher build-conteiner-base-mariadb build-conteiner-mariadb build-conteiner-base-sqlserver build-conteiner-sqlserver build-conteiner-base-oracle build-conteiner-oracle build-conteiner-solr build-conteiner-base-app build-conteiner-app-dev build-conteiner-app-ci build-conteiner-app-ci-agendador build-conteiner-phpmemcachedadmin-base build-conteiner-phpmemcachedadmin build-conteiner-openldap-base build-conteiner-openldap build-conteiner-haproxy-base build-conteiner-haproxy build-conteiner-phpldapadmin build-conteiner-dbadminer

#*****************************************************
#*******PUBLISH CONTAINERS TARGETS********************
#*****************************************************

## publish-container-generic: target generico para publicar imagem passada via parametro
publish-container-generic: verificar-docker-registry verificar-parametro-imagemtmp
		@if test -z "$$(docker images -q $(IMAGEMTMP_FULL))"; then \
		    echo "Imagem $(IMAGEMTMP_FULL) nao existe. Rode o build respectivo antes"; \
				exit 1; \
		else \
		    echo "Publicando a imagem: $(IMAGEMTMP_FULL)"; \
				echo "Caso nao publique verifique se vc esta logado no registry informado no seu env"; echo ""; \
				set -e; \
		    docker push $(IMAGEMTMP_FULL); \
				if [ "$(IMAGEMTMP_VERSAO)" != "test" ]; then \
    				docker push $(DOCKER_REGISTRY)/$(IMAGEMTMP):latest; \
				fi; \
		fi;


publish-container-base-centos: ## publicar imagem base-centos
		@make IMAGEMTMP=base-centos7 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-memcached: ## publicar imagem memcached
		@make IMAGEMTMP=memcached IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-jod: ## publicar imagem jod
		@make IMAGEMTMP=jod IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-mailcatcher: ## publicar imagem mailcatcher
		@make IMAGEMTMP=mailcatcher IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-base-mariadb: ## publicar imagem base-mariadb
		@make IMAGEMTMP=base-mariadb10.5 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-mariadb: ## publicar imagem mariadb
		@make IMAGEMTMP=mariadb10.5 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-base-sqlserver: ## publicar imagem base-sqlserver
		@make IMAGEMTMP=base-sqlserver2017 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-sqlserver: ## publicar imagem sqlserver
		@make IMAGEMTMP=sqlserver2017 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-base-oracle: ## publicar imagem base-oracle
		@make IMAGEMTMP=base-oracle11g IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-oracle: ## publicar imagem oracle
		@make IMAGEMTMP=oracle11g IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-solr: ## publicar imagem solr
		@make IMAGEMTMP=solr8.2.0 IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-base-app: ## publicar imagem base-app
		@make IMAGEMTMP=base-app IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-app-dev: ## publicar imagem app-dev
		@make IMAGEMTMP=app-dev IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-app-ci: ## publicar imagem app-ci
		@make IMAGEMTMP=app-ci IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-app-ci-agendador: ## publicar imagem app-ci-agendador
		@make IMAGEMTMP=app-ci-agendador IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-phpmemcachedadmin-base: ## publicar imagem phpmemcachedadmin-base
		@make IMAGEMTMP=phpmemcachedadmin-base IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-phpmemcachedadmin: ## publicar imagem phpmemcachedadmin
		@make IMAGEMTMP=phpmemcachedadmin IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-openldap-base: ## publicar imagem openldap-base
		@make IMAGEMTMP=openldap-base IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-openldap: ## publicar imagem openldap
		@make IMAGEMTMP=openldap IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-haproxy-base: ## publicar imagem haproxy-base
		@make IMAGEMTMP=haproxy-base IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-haproxy: ## publicar imagem haproxy
		@make IMAGEMTMP=haproxy IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-phpldapadmin: ## publicar imagem phpldapadmin
		@make IMAGEMTMP=phpldapadmin IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


publish-container-dbadminer: ## publicar imagem dbadminer
		@make IMAGEMTMP=dbadminer IMAGEMTMP_VERSAO=$(DOCKER_CONTAINER_VERSAO_PRODUTO) publish-container-generic


## publish-containers: executa todos os targets de publicacao de imagem na sequencia
publish-containers: publish-container-base-centos publish-container-memcached publish-container-jod publish-container-mailcatcher publish-container-base-mariadb publish-container-mariadb publish-container-base-sqlserver publish-container-sqlserver publish-container-base-oracle publish-container-oracle publish-container-solr publish-container-base-app publish-container-app-dev publish-container-app-ci publish-container-app-ci-agendador publish-container-phpmemcachedadmin-base publish-container-phpmemcachedadmin publish-container-openldap-base publish-container-openldap publish-container-haproxy-base publish-container-haproxy publish-container-phpldapadmin publish-container-dbadminer
