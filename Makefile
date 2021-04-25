
include envlocal.env

EXISTE_LOCAL_DB := $(shell docker volume ls | grep $(VOLUME_DB) )
EXISTE_LOCAL_FONTES := $(shell docker volume ls | grep $(VOLUME_FONTES) )
EXISTE_LOCAL_ARQUIVOS_EXTERNOS := $(shell docker volume ls | grep $(VOLUME_ARQUIVOSEXTERNOS) )
EXISTE_LOCAL_SOLR := $(shell docker volume ls | grep $(VOLUME_SOLR) )

DIR := ${CURDIR}
COMMMADCOMPOSE = docker-compose -f orquestrators/docker-compose/docker-compose.yml 

help:   ## Lista de comandos disponiveis e descricao. Voce pode usar TAB para completar os comandos
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


criar_volumes: criar_volume_fontes criar_volume_banco criar_volume_arquivos_externos criar_volume_solr

criar_volume_fontes: ## Monte o volume docker com os fontes que serao consumidos pelo projeto
	docker run --rm -v $(LOCALIZACAO_FONTES_SEI):/source -v $(VOLUME_FONTES):/opt -w /source alpine sh -c "cp -R infra sei sip /opt/"
	

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

stop: ## docker-compose stop e docker-compose rm -f
	make build_docker_compose
	$(COMMMADCOMPOSE) stop
	$(COMMMADCOMPOSE) rm -f

logs: ## docker-compose logs -f pressione ctrol+c para sair
	$(COMMMADCOMPOSE) logs -f

clear: ## para o projeto e remove tds os volumes criados
	make stop
	$(COMMMADCOMPOSE) down -v

apagar_volumes: 
	make apagar_volume_fontes 
	make apagar_volume_banco 
	make apagar_volume_arquivos_externos 
	make apagar_volume_solr


apagar_volume_fontes: ## Monte o volume docker com os fontes que serao consumidos pelo projeto
	docker volume rm $(VOLUME_FONTES)
	
apagar_volume_banco: ## Apagar volume do banco
	docker volume rm $(VOLUME_DB)

apagar_volume_arquivos_externos: ## Apagar volume Arquivos Externos
	docker volume rm $(VOLUME_ARQUIVOSEXTERNOS)

apagar_volume_solr: ## Apagar volume Solr
	docker volume rm $(VOLUME_SOLR)


