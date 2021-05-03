
include envlocal.env

EXISTE_LOCAL_DB := $(shell docker volume ls | grep $(VOLUME_DB) )
EXISTE_LOCAL_FONTES := $(shell docker volume ls | grep $(VOLUME_FONTES) )
EXISTE_LOCAL_ARQUIVOS_EXTERNOS := $(shell docker volume ls | grep $(VOLUME_ARQUIVOSEXTERNOS) )
EXISTE_LOCAL_SOLR := $(shell docker volume ls | grep $(VOLUME_SOLR) )

qtd := "2"

DIR := ${CURDIR}
COMMMADCOMPOSE = docker-compose -f orquestrators/docker-compose/docker-compose.yml 

help:   ## Lista de comandos disponiveis e descricao. Voce pode usar TAB para completar os comandos
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


criar_volumes: criar_volume_fontes criar_volume_certs criar_volume_banco criar_volume_arquivos_externos criar_volume_solr

criar_volume_fontes: ## Monte o volume docker com os fontes que serao consumidos pelo projeto
	docker run --rm -v $(LOCALIZACAO_FONTES_SEI):/source -v $(VOLUME_FONTES):/opt -w /source alpine sh -c "cp -R infra sei sip /opt/"

criar_volume_certs: ## Monte o volume docker com os certs que serao consumidos pelo projeto
	docker run --rm -v ${CURDIR}/orquestrators/docker-compose/cert0.pem:/cert0.pem -v $(LOCALIZACAO_CERTS):/source -v $(VOLUME_CERTS):/certs -w /source alpine sh -c "cp /cert0.pem /certs/"
	

criar_volume_banco: ## Monte o volume docker com os fontes que serao consumidos pelo projeto
	
ifneq ("$(EXISTE_LOCAL_DB)",  "")
	@echo "Ja existe um volume de banco de dados. Voce pode apaga-lo com o comando make apagar_volume_banco"
else
	docker volume create $(VOLUME_DB)
endif

criar_volume_arquivos_externos: ## Monte o volume docker para os arquivos externos
	
ifneq ("$(EXISTE_LOCAL_ARQUIVOS_EXTERNOS)",  "")
	@echo "Ja existe um volume de Arquivos Externos. Voce pode apaga-lo com o comando make apagar_volume_arquivos_externos"
else
	docker volume create $(VOLUME_ARQUIVOSEXTERNOS)
endif

criar_volume_solr: ## Monte o volume docker para os dados do solr
	
ifneq ("$(EXISTE_LOCAL_SOLR)",  "")
	@echo "Ja existe um volume para o Solr. Voce pode apaga-lo com o comando make apagar_volume_solr"
else
	docker volume create $(VOLUME_SOLR)
endif


build_docker_compose: ## Construa o docker-compose.yml baseado no arquivo envlocal.env
	rm -f orquestrators/docker-compose/docker-compose.yml
	
	envsubst < orquestrators/docker-compose/docker-compose-template.yml > orquestrators/docker-compose/docker-compose.yml


ifeq ("$(APP_PORTA_80_MAP_EXPOR)",  "true")
	sed -i'' -e "s|#ports:|ports:|" orquestrators/docker-compose/docker-compose.yml
	
ifneq ("$(APP_PORTA_80_MAP_EXPOR)",  "")
	sed -i'' -e "s|#    - $(APP_PORTA_80_MAP)|    - $(APP_PORTA_80_MAP)|" orquestrators/docker-compose/docker-compose.yml
else
    sed -i'' -e "|#    - $(APP_PORTA_80_MAP)|d" orquestrators/docker-compose/docker-compose.yml
endif
	sed -i'' -e "s|nada|nada|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(APP_PORTA_443_MAP_EXPOR)",  "true")
	sed -i'' -e "s|#ports:|ports:|" orquestrators/docker-compose/docker-compose.yml
	
ifneq ("$(APP_PORTA_443_MAP_EXPOR)",  "")
	sed -i'' -e "s|#    - $(APP_PORTA_443_MAP)|    - $(APP_PORTA_443_MAP)|" orquestrators/docker-compose/docker-compose.yml
else
    sed -i'' -e "|#    - $(APP_PORTA_443_MAP)|d" orquestrators/docker-compose/docker-compose.yml
endif
	sed -i'' -e "s|nada|nada|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(APP_PROTOCOLO)",  "https")
	sed -i'' -e "s|#- EXCLUDE_PORTS=80|- EXCLUDE_PORTS=80|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#- EXTRA_ROUTE_SETTINGS=ssl verify none|- EXTRA_ROUTE_SETTINGS=ssl verify none|" orquestrators/docker-compose/docker-compose.yml
endif
ifeq ("$(APP_PROTOCOLO)",  "http")
	sed -i'' -e "s|#- EXCLUDE_PORTS=443|- EXCLUDE_PORTS=443|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(BALANCEADOR_PRESENTE)",  "true")
	sed -i'' -e "s|#balanceador:|balanceador:|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#    image: dockercloud/haproxy|    image: dockercloud/haproxy|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#    links:|    links:|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#        - app|        - app|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#        - solr|        - solr|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#    environment:|    environment:|" orquestrators/docker-compose/docker-compose.yml	
	sed -i'' -e "s|#        - EXTRA_FRONTEND_SETTINGS_80=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root|        - EXTRA_FRONTEND_SETTINGS_80=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root|" orquestrators/docker-compose/docker-compose.yml	
	sed -i'' -e "s|#        - EXTRA_FRONTEND_SETTINGS_443=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root|        - EXTRA_FRONTEND_SETTINGS_443=use_backend stats if { path_beg -i /haproxy }, acl is_root path -i /, redirect code 301 location http://${APP_HOST}/sei/ if is_root|" orquestrators/docker-compose/docker-compose.yml	
	sed -i'' -e "s|#        - CERT_FOLDER=/certs|        - CERT_FOLDER=/certs|" orquestrators/docker-compose/docker-compose.yml	
	sed -i'' -e "s|#    volumes_from:|    volumes_from:|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#        - storage-certs|        - storage-certs|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#    volumes:|    volumes:|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#        - /var/run/docker.sock:/var/run/docker.sock|        - /var/run/docker.sock:/var/run/docker.sock|g" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|nada|nada|" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(JOD_PRESENTE)",  "true")
	sed -i'' -e "s|#jod: #servicejod|jod: #servicejod|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#    image: ${DOCKER_IMAGE_JOD} #servicejod|    image: ${DOCKER_IMAGE_JOD} #servicejod|" orquestrators/docker-compose/docker-compose.yml
	sed -i'' -e "s|#- jod:jod #servicejod|- jod:jod #servicejod|g" orquestrators/docker-compose/docker-compose.yml
endif

ifeq ("$(BALANCEADOR_PRESENTE)",  "true")

ifeq ("$(BALANCEADOR_PORTA_80_MAP_EXPOR)",  "true")
	sed -i'' -e "s|#    ports:|    ports:|" orquestrators/docker-compose/docker-compose.yml
ifneq ("$(BALANCEADOR_PORTA_80_MAP_EXPOR)",  "")
	sed -i'' -e "s|#        - $(BALANCEADOR_PORTA_80_MAP)|        - $(BALANCEADOR_PORTA_80_MAP)|" orquestrators/docker-compose/docker-compose.yml
else
    sed -i'' -e "|#        - $(BALANCEADOR_PORTA_80_MAP)|d" orquestrators/docker-compose/docker-compose.yml
endif
endif

ifeq ("$(BALANCEADOR_PORTA_443_MAP_EXPOR)",  "true")
	sed -i'' -e "s|#    ports:|    ports:|" orquestrators/docker-compose/docker-compose.yml
ifneq ("$(BALANCEADOR_PORTA_443_MAP_EXPOR)",  "")
	sed -i'' -e "s|#        - $(BALANCEADOR_PORTA_443_MAP)|        - $(BALANCEADOR_PORTA_443_MAP)|" orquestrators/docker-compose/docker-compose.yml
else
    sed -i'' -e "|#        - $(BALANCEADOR_PORTA_443_MAP)|d" orquestrators/docker-compose/docker-compose.yml
endif
endif

endif


run: ## roda na sequencia build_docker_compose e up -d

ifeq ("$(EXISTE_LOCAL_DB)",  "")
	@echo "Nao existe volume para o banco, rode antes o comando make criar_volume_banco"

else ifeq ("$(EXISTE_LOCAL_FONTES)",  "")
	@echo "Nao existe volume para os fontes, rode antes o comando make criar_volume_fontes - Verifique antes a variavel LOCALIZACAO_FONTES_SEI no seu env-local.env"
	@echo "A mesma tem que apontar para o diretorio de fontes do SEI (infra, sei, sip)"

else ifeq ("$(EXISTE_LOCAL_ARQUIVOS_EXTERNOS)",  "")
	@echo "Nao existe volume para os arquivos externos, rode antes o comando make criar_volume_arquivos_externos"

else ifeq ("$(EXISTE_LOCAL_SOLR)",  "")
	@echo "Nao existe volume para o SOLR, rode antes o comando make criar_volume_solr"

else
	make build_docker_compose
	$(COMMMADCOMPOSE) up -d
endif

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

logs_app-atualizador: ## docker-compose logs -f app pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f app-atualizador

clear: ## para o projeto e remove tds os volumes criados
	make stop
	$(COMMMADCOMPOSE) down -v

apagar_volumes: 
	make apagar_volume_fontes 
	make apagar_volume_certs
	make apagar_volume_banco 
	make apagar_volume_arquivos_externos 
	make apagar_volume_solr


apagar_volume_fontes: ## Monte o volume docker com os fontes que serao consumidos pelo projeto
	docker volume rm $(VOLUME_FONTES) || true

apagar_volume_certs: ## Monte o volume docker com os fontes que serao consumidos pelo projeto
	docker volume rm $(VOLUME_CERTS) || true
	
apagar_volume_banco: ## Apagar volume do banco
	docker volume rm $(VOLUME_DB) || true

apagar_volume_arquivos_externos: ## Apagar volume Arquivos Externos
	docker volume rm $(VOLUME_ARQUIVOSEXTERNOS) || true

apagar_volume_solr: ## Apagar volume Solr
	docker volume rm $(VOLUME_SOLR) || true


