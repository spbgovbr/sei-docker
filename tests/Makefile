include ../envlocal.env

define MENSAGEM_CONFIRMACAO
Vamos tentar agora subir o SEI de varias formas diferentes para ver se o resultado bate com o esperado. 
Tenha em mente que iremos usar diversos recursos. 
Verifique se nao ha nada rodando em localhost ou nas urls de teste que possam impactar a subida. 
Verifique tb se vc fez backup do seu envlocal.env pois o mesmo sera substituido por referencias padrao

Verifique se os dados estao salvos pois os volumes atuais serao apagados.
Verifique se o codigo fonte esta posicionado no local correto pois o volume de fontes sera reconstruido

Pressione y para continuar [y/n]...
endef
export MENSAGEM_CONFIRMACAO


DIR := ${CURDIR}
COMMMADCOMPOSE = docker-compose -f orquestrators/docker-compose/docker-compose.yml 

CMDMAKE = $(MAKE) -C ../
MENSAGEM = Vamos tentar agora subir o SEI de várias formas diferentes para ver se o resultado bate com o esperado. Tenha em mente que iremos usar diversos recursos. Verifique se nao ha nada rodando em localhost ou nas urls de teste que possam impactar a subida. Pressione y para continuar [y/n]...

ifndef BANCO
BANCO = mysql
endif

ifndef CONFIRMACAO
CONFIRMACAO = n
endif

ifndef MODALIDADE
MODALIDADE = default
endif


CURLCMD = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" $(APP_PROTOCOLO)://$(APP_HOST)/sei
CURLCMD_SIP = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" $(APP_PROTOCOLO)://$(APP_HOST)/sip
CURLCMD_301 = $(CURLCMD) | grep 301
CURLCMDSEI_LOGIN = $(CURLCMD) | grep txtUsuario
CURLCMDSIP_LOGIN = $(CURLCMD_SIP) | grep txtUsuario

CURLHAPROXY = curl -s -L  -u stats:stats --resolve "$(APP_HOST):80:127.0.0.1" http://$(APP_HOST)/haproxy | grep Statistics
CURLSOLR = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" https://$(APP_HOST)/solr | grep Dashboard
CURLDBADMIN = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" https://$(APP_HOST)/dbadmin | grep Adminer
CURLMEMCACHEDADMIN = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" https://$(APP_HOST)/memcachedadmin/ | grep phpMemcachedAdmin
CURLPHPLDAPADMIN = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" https://$(APP_HOST)/phpldapadmin/ | grep phpLDAPadmin
CURLMAILCATCHER = curl -s -k -L --resolve "$(APP_HOST):443:127.0.0.1" https://$(APP_HOST)/mailadmin | grep MailCatcher


ifndef COMPONENTE_PRESENCA
COMPONENTE_PRESENCA = jod
COMPONENTE_PRESENCA_VAR = JOD_PRESENTE
endif



help:   ## Lista de comandos disponiveis e descricao. Voce pode usar TAB para completar os comandos
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


test_confirmation:
ifeq ("$(CONFIRMACAO)",  "n")
	@( read -p "$$MENSAGEM_CONFIRMACAO" sure && case "$$sure" in [yY]) true;; *) false;; esac )
else
	@echo "---"
endif
	
test_lineup_completa: test_confirmation ## Roda todos os testes na sequencia
	@echo "*****************************************************"
	@echo "*****************************************************"
	@echo "Vamos iniciar uma execucao da montagem do ambiente reduzida com url customizada, nas 3 bases de dados. "
	@echo "Em seguida vamos iniciar uma execucao da montagem do ambiente default com localhost, nas 3 bases de dados. "
	@echo "Em seguida vamos iniciar uma execucao da montagem do ambiente completa com url customizada, nas 3 bases de dados. Aguarde ... "
	@echo "*****************************************************"
	@echo "*****************************************************"
	@sleep 5
	
	make CONFIRMACAO=y test_ambientes_reduzida
	make CONFIRMACAO=y test_ambientes_default
	make CONFIRMACAO=y test_ambientes_full


test_ambientes_default: test_confirmation ## Roda o teste subindo ambiente default em localhost nas 3 bases
	@echo "*****************************************************"
	@echo "*****************************************************"
	@echo "Vamos iniciar uma execucao da montagem do ambiente default, nas 3 bases de dados. Aguarde..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	@sleep 5
	
	make CONFIRMACAO=y BANCO=mysql test_ambiente
	make CONFIRMACAO=y BANCO=sqlserver test_ambiente
	make CONFIRMACAO=y BANCO=oracle test_ambiente

test_ambientes_reduzida: test_confirmation ## Roda o teste subindo ambiente reduzido com url custom, nas 3 bases de dados
	@echo "*****************************************************"
	@echo "*****************************************************"
	@echo "Vamos iniciar uma execucao da montagem do ambiente reduzida com url customizada, nas 3 bases de dados. Aguarde..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	@sleep 5
	
	make MODALIDADE=reduzida CONFIRMACAO=y BANCO=mysql test_ambiente
	make MODALIDADE=reduzida CONFIRMACAO=y BANCO=sqlserver test_ambiente
	make MODALIDADE=reduzida CONFIRMACAO=y BANCO=oracle test_ambiente
	
test_ambientes_full: test_confirmation ## Roda o teste subindo ambiente full com todos os componentes com url custom, nas 3 bases de dados
	@echo "*****************************************************"
	@echo "*****************************************************"
	@echo "Vamos iniciar uma execucao da montagem do ambiente completa com url customizada, nas 3 bases de dados. Aguarde..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	@sleep 5
	
	make MODALIDADE=completa CONFIRMACAO=y BANCO=mysql test_ambiente
	make MODALIDADE=completa CONFIRMACAO=y BANCO=sqlserver test_ambiente
	make MODALIDADE=completa CONFIRMACAO=y BANCO=oracle test_ambiente

test_ambiente: test_confirmation ## Roda o teste em um unico ambiente. Deve ser passado parametro para Base e Modalidade
	
	@echo "*****************************************************"
	@echo "*****************************************************"
	@echo "Modalidade dessa Execucao... $(MODALIDADE) ..."
	@echo "Banco de Dados dessa Execucao... $(BANCO) ..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	@echo "Vamos desligar o projeto atual, caso esteja rodando, e apagar os volumes... Aguarde..."
	@$(CMDMAKE) clear
	@$(CMDMAKE) apagar_volumes

	make test_verificar_volumes_nao_existe
	
	@echo "Vamos copiar o envlocal de referencia para o $(BANCO)..."
	@echo "*****************************************************"
	@echo "*****************************************************"
ifeq ("$(MODALIDADE)",  "default")
	cp -f ../envlocal-example-$(BANCO).env ../envlocal.env
else
	cp -f test-envlocal-$(MODALIDADE)-$(BANCO).env ../envlocal.env
endif    
	
	
	@echo "Vamos subir o projeto..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	$(CMDMAKE) setup
	
	@echo "Vamos verificar os volumes..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	make test_verificar_volumes_existe
	
	make test_verificar_app_url
	
	make test_verficar_componentes
	
	make test_verificar_balanceador_urls
	
	@echo "Vamos destruir o sistema..."
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	$(CMDMAKE) clear
	$(CMDMAKE) apagar_volumes
	
	make test_verificar_volumes_nao_existe
	
	@echo "*****************************************************"
	@echo "***VERIFICACAO DE TESTE DO $(BANCO) FINALIZADA**********"
	@echo "*****************************************************"


test_verificar_componente_presenca: 
	@echo "Vamos verificar se criou com ou sem o $(COMPONENTE_PRESENCA)"
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	@var=$$(echo $$(docker ps | grep $(COMPONENTE_PRESENCA))) && \
	if [ "$${$(COMPONENTE_PRESENCA_VAR)}" = "true" ]; then \
	if [ "$$var" = "" ]; then echo '$(COMPONENTE_PRESENCA) nao encontrado. Deveria ter sido encontrado. Saindo do teste'; exit 1; fi; \
	else \
	if [ "$$var" != "" ]; then echo '$(COMPONENTE_PRESENCA) encontrado. Nao deveria ter sido encontrado. Saindo do teste'; exit 1; fi; \
	fi
	
	
	@echo "Verificacao de presenca ou nao do $(COMPONENTE_PRESENCA) com sucesso"
	@echo "*****************************************************"
	@echo "*****************************************************"	

test_verficar_componentes: test_verificar_jod_presenca test_verificar_balanceador_presenca test_verificar_dbadmin_presenca test_verificar_memcachedadmin_presenca test_verificar_mailcatcher_presenca test_verificar_openldap_presenca ## Verifica se os componentes JOD, balanceador, dbadmin, memcachedadmin, mailcatcher e openldap devem ou nao estar presentes

test_verificar_jod_presenca:
	make test_verificar_componente_presenca
	
test_verificar_balanceador_presenca:
	make COMPONENTE_PRESENCA=balanceador COMPONENTE_PRESENCA_VAR=BALANCEADOR_PRESENTE test_verificar_componente_presenca
	
test_verificar_dbadmin_presenca:
	make COMPONENTE_PRESENCA=dbadmin COMPONENTE_PRESENCA_VAR=DBADMIN_PRESENTE test_verificar_componente_presenca

test_verificar_memcachedadmin_presenca:
	make COMPONENTE_PRESENCA=memcachedadmin COMPONENTE_PRESENCA_VAR=MEMCACHEDADMIN_PRESENTE test_verificar_componente_presenca

test_verificar_mailcatcher_presenca:
	make COMPONENTE_PRESENCA=mailcatcher COMPONENTE_PRESENCA_VAR=MAIL_CATCHER_PRESENTE test_verificar_componente_presenca

test_verificar_openldap_presenca:
	make COMPONENTE_PRESENCA=openldap COMPONENTE_PRESENCA_VAR=OPENLDAP_PRESENTE test_verificar_componente_presenca


	

test_verificar_app_url: ## verifica se a url do sei responde a pagina de login
	
	@echo "Vamos verificar se o SEI responde a pagina inicial"
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	@echo "Tentando acessar o login do SEI"
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	@for i in {1..15}; do echo 'Tentando acessar...'; var=$$(echo $$($(CURLCMDSEI_LOGIN))); if [ "$$var" != "" ]; then echo 'Pagina respondeu com tela de login'; break; else echo 'Aguardando resposta ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLCMDSEI_LOGIN)));  if [ "$$var" = "" ]; then echo 'Nao respondeu tela de login. Saindo do teste'; exit 1; fi
	
	@echo "Tentando acessar o login do SIP"
	@echo "*****************************************************"
	@echo "*****************************************************"
	@for i in {1..15}; do echo 'Tentando acessar...'; var=$$(echo $$($(CURLCMDSIP_LOGIN))); if [ "$$var" != "" ]; then echo 'Pagina respondeu com tela de login'; break; else echo 'Aguardando resposta ...'; fi; sleep 10; done
	@var=$$(echo $$($(CURLCMDSIP_LOGIN)));  if [ "$$var" = "" ]; then echo 'Nao respondeu tela de login. Saindo do teste'; exit 1; fi
	
	@echo "SEI respondeu a pagina inicial"
	@echo "*****************************************************"
	@echo "*****************************************************"


test_verificar_balanceador_urls: ## verifica se as urls fornecidas no balanceador estao ativas e funcionando
	@echo "Caso haja um balanceador vamos averiguar os seus backends..."
	
ifeq ("$(BALANCEADOR_PRESENTE)",  "true")
	
	@for i in {1..3}; do echo 'Tentando acessar haproxy...'; var=$$(echo $$($(CURLHAPROXY))); if [ "$$var" != "" ]; then echo 'Pagina haproxy respondeu com sucesso'; break; else echo 'Aguardando resposta haproxy ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLHAPROXY)));  if [ "$$var" = "" ]; then echo 'HaProxy nao respondeu tela inicial. Saindo do teste'; exit 1; fi
	
	@for i in {1..3}; do echo 'Tentando acessar solr admin...'; var=$$(echo $$($(CURLSOLR))); if [ "$$var" != "" ]; then echo 'Pagina solr respondeu com sucesso'; break; else echo 'Aguardando resposta solr ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLSOLR)));  if [ "$$var" = "" ]; then echo 'SOLR nao respondeu tela inicial. Saindo do teste'; exit 1; fi

ifeq ("$(DBADMIN_PRESENTE)",  "true")
	@for i in {1..3}; do echo 'Tentando acessar dbadmin...'; var=$$(echo $$($(CURLDBADMIN))); if [ "$$var" != "" ]; then echo 'Pagina Admner respondeu com sucesso'; break; else echo 'Aguardando resposta adminer ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLDBADMIN)));  if [ "$$var" = "" ]; then echo 'Adminer nao respondeu tela inicial. Saindo do teste'; exit 1; fi
endif

ifeq ("$(MEMCACHEDADMIN_PRESENTE)",  "true")
	@for i in {1..3}; do echo 'Tentando acessar memcachedadmin...'; var=$$(echo $$($(CURLMEMCACHEDADMIN))); if [ "$$var" != "" ]; then echo 'Pagina Memcachedadmin respondeu com sucesso'; break; else echo 'Aguardando resposta Memcachedadmin ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLMEMCACHEDADMIN)));  if [ "$$var" = "" ]; then echo 'Memcachedadmin nao respondeu tela inicial. Saindo do teste'; exit 1; fi
endif

ifeq ("$(OPENLDAP_PRESENTE)",  "true")
	@for i in {1..3}; do echo 'Tentando acessar PHPLDAPADMIN...'; var=$$(echo $$($(CURLPHPLDAPADMIN))); if [ "$$var" != "" ]; then echo 'Pagina PHPLDAPADMIN respondeu com sucesso'; break; else echo 'Aguardando resposta PHPLDAPADMIN ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLPHPLDAPADMIN)));  if [ "$$var" = "" ]; then echo 'PHPLDAPADMIN nao respondeu tela inicial. Saindo do teste'; exit 1; fi
endif

ifeq ("$(MAIL_CATCHER_PRESENTE)",  "true")
	@for i in {1..3}; do echo 'Tentando acessar MAILCATCHER...'; var=$$(echo $$($(CURLMAILCATCHER))); if [ "$$var" != "" ]; then echo 'Pagina MAILCATCHER respondeu com sucesso'; break; else echo 'Aguardando resposta MAILCATCHER ...'; fi; sleep 10;  done
	@var=$$(echo $$($(CURLMAILCATCHER)));  if [ "$$var" = "" ]; then echo 'MAILCATCHER nao respondeu tela inicial. Saindo do teste'; exit 1; fi
endif

else

	@echo "Balanceador nao encontrado..."
	
endif


test_escalonar: ## escalona o app para 2 para 1 depois para 3 e volta para 1
	@echo "Vamos escalonar a aplicacao para verificar o comportamento"
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	$(CMDMAKE) scale


test_verificar_volumes_existe: ## Verifica volumes existentes
	@echo "Vamos verificar se os volumes existem"
	@echo "*****************************************************"
	@echo "*****************************************************"
	@var=$$(echo $$(docker volume ls | grep arquivosexternos-storage)) && if [ "$$var" = "" ]; then echo 'Volume arquivosexternos nao encontrado. Saindo do teste'; exit 1; else echo 'Volume arquivosexternos encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep certs-storage)) && if [ "$$var" = "" ]; then echo 'Volume certs nao encontrado. Saindo do teste'; exit 1; else echo 'Volume certs encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep controlador-instalacao-storage)) && if [ "$$var" = "" ]; then echo 'Volume controlador-instalacao-storage nao encontrado. Saindo do teste'; exit 1; else echo 'Volume controlador-instalacao-storage encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep fontes-storage)) && if [ "$$var" = "" ]; then echo 'Volume fontes-storage nao encontrado. Saindo do teste'; exit 1; else echo 'Volume fontes-storage encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep openldap-db-storage)) && if [ "$$var" = "" ]; then echo 'Volume openldap-db-storage nao encontrado. Saindo do teste'; exit 1; else echo 'Volume openldap-db-storage encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep openldap-slapd-storage)) && if [ "$$var" = "" ]; then echo 'Volume openldap-slapd-storage nao encontrado. Saindo do teste'; exit 1; else echo 'Volume openldap-slapd-storage encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep storage-db)) && if [ "$$var" = "" ]; then echo 'Volume storage-db nao encontrado. Saindo do teste'; exit 1; else echo 'Volume storage-db encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep volume-solr)) && if [ "$$var" = "" ]; then echo 'Volume volume-solr nao encontrado. Saindo do teste'; exit 1; else echo 'Volume volume-solr encontrado'; fi
	
	@echo "Verificacao de volumes realizada com sucesso"
	@echo "*****************************************************"
	@echo "*****************************************************"
	
test_verificar_volumes_nao_existe: ## Verifica se os volumes nao existem indicando a delecao
	@echo "Vamos verificar se os volumes nao existem"
	@echo "*****************************************************"
	@echo "*****************************************************"
	@var=$$(echo $$(docker volume ls | grep arquivosexternos-storage)) && if [ "$$var" != "" ]; then echo 'Volume arquivosexternos encontrado. Saindo do teste'; exit 1; else echo 'Volume arquivosexternos nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep certs-storage)) && if [ "$$var" != "" ]; then echo 'Volume certs encontrado. Saindo do teste'; exit 1; else echo 'Volume certs nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep controlador-instalacao-storage)) && if [ "$$var" != "" ]; then echo 'Volume controlador-instalacao-storage encontrado. Saindo do teste'; exit 1; else echo 'Volume controlador-instalacao-storage nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep fontes-storage)) && if [ "$$var" != "" ]; then echo 'Volume fontes-storage encontrado. Saindo do teste'; exit 1; else echo 'Volume fontes-storage nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep openldap-db-storage)) && if [ "$$var" != "" ]; then echo 'Volume openldap-db-storage encontrado. Saindo do teste'; exit 1; else echo 'Volume openldap-db-storage nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep openldap-slapd-storage)) && if [ "$$var" != "" ]; then echo 'Volume openldap-slapd-storage encontrado. Saindo do teste'; exit 1; else echo 'Volume openldap-slapd-storage nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep storage-db)) && if [ "$$var" != "" ]; then echo 'Volume storage-db encontrado. Saindo do teste'; exit 1; else echo 'Volume storage-db nao encontrado'; fi
	
	@var=$$(echo $$(docker volume ls | grep volume-solr)) && if [ "$$var" != "" ]; then echo 'Volume volume-solr encontrado. Saindo do teste'; exit 1; else echo 'Volume volume-solr nao encontrado'; fi
	
	@echo "Verificacao de volumes ausentes realizada com sucesso"
	@echo "*****************************************************"
	@echo "*****************************************************"
	
	

