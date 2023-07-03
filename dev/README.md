# SEI-DOCKER

## DEV - Ambiente de Desenvolvimento

Na pasta dev há um Automatizador (Makefile) pronto para subir uma instância do SEI escolhendo a base de dados e com o xdebug habilitado. Apropriada para subir um ambiente local montando o código fonte do SEI. Desta forma você pode usar o seu editor / debugger preferido na edição do código.

Nessa modalidade o projeto disponibiliza para o desenvolvedor os seguintes componentes:
	- app (serviço apache para o SEI)
	- database (mariadb, sqlserver ou oracle)
	- memcached
	- jod
	- solr
	- mailcatcher (servidor smtp e mailcatcher para visualizar os emails enviados)



## Pré-Requisitos

Para utilizar esse projeto você precisa de:
- código fonte do SEI
- docker
- docker-compose

## Início Rápido

Os comandos abaixo sobem o ambiente em mysql:

```
git clone https://github.com/spbgovbr/sei-docker
cd dev
export SEI_PATH=~/sei/FonteSEI
make up
```

o comando ``` export SEI_PATH=~/sei/FonteSEI ``` deve apontar para o caminho do código fonte do SEI (pasta onde encontra-se as pastas: sei sip infra)

Após a finalização do make up você pode acessar o SEI pelo seu browser através do seguinte endereço:

- http://localhost:8000/sei
- http://localhost:8000/sip

  Usuário e senha: teste



## Mais Orientações Básicas

### Sobre o arquivo env.env

Nesse arquivo encontra-se as configurações que serão utilizadas pelo docker-compose para subir o SEI. Caso o mesmo não exista será feita uma cópia do arquivo envs/env-mysql.env

Caso deseje, por exemplo, subir o SEI com uma base diferente de mysql, basta rodar ``` make base=sqlserver config ``` para fazer a cópia do arquivo envs/env-sqlserver.env. O mesmo para ``` make base=oracle config ``` ou ``` make base=mysql config ``` 

Não esqueça de antes de alterar de base, rodar um make destroy para destruir o ambiente.

### Localização dos dados do banco de dados

Os dados do banco de dados ficam em volumes docker de mesmo nome.
Atenção, caso você execute make destroy os volumes também serão excluídos. Caso rode o make up novamente eles serão reconstruídos.

### Verificar localização do fonte do SEI

Você pode informar a localização do fonte tanto pelo arquivo env.env ou exportando a variável SEI_PATH como indicado acima pelo Início Rápido

### Verificar se há portas ocupadas no host
Antes de rodar, verifique se há algum serviço ocupando alguma das portas exigidas pelo docker-compose e desligue-o se for o caso: 
- 8000 (default para o SEI)
- 1080 serviço de smtp e mailcatcher
- 11211 para o memcached
- 3306, 1433 e 1521 para o banco, mysql, sqlserver e oracle respectivamente
- 8983 para o solr

### Verificar comandos Make disponíveis

rode ``` make help ``` para uma lista de todos os comandos disponíveis

### Comandos docker-compose

o modo de utilização indicado é usando o comando make pois ele automatiza o processo de subir, parar e destruir o SEI. 

Porém nada impede que se use os comandos existentes do docker-compose.

Por ex: ``` docker-compose --env-file env.env logs -f ``` para exibir os logs de todos os serviços ou 

``` docker-compose --env-file env.env logs -f httpd ``` para exibir os logs apenas do nó de aplicação


### Pasta tests

Essa pasta é para quem for alterar algo no projeto. 
Ela vai rodar uma série de testes para garantir que o Makefile do projeto esteja correto.

``` make tests-all-bases ```

Vai subir o SEI em todas as bases de dados disponíveis, tentar conectar no mesmo e rodar um teste  simples em Selenium para criar processo e documentos.

Devido a natureza da implementação docker, os testes em Selenium vão rodar apenas no linux quando se usa localhost. No MacOs por exemplo os testes não rodam. Portanto ao rodar os testes use o SO Linux.

Mais sobre os testes [clique aqui](../README.md#testes)


## Informações Adicionais para Desenvolvedores

O componente chamado db, apresentado logo após o provisionamento do ambiente, se refere ao serviço de banco de dados escolhido durante o provisionamento do ambiente, podendo ser o MySQL (padrão, Oracle ou SQLServer. A base poderá ser acesso por qualquer utilitário de conexão á banco de dados. Este serviço estará com os 2 bancos de dados utilizados pelo SEI (sei e sip) e poderá ser acessados com os seguintes usuários:

##### MySQL
    Usuário Root do MySQL: login:root / senha:P@ssword
    Usuário da Base de Dados do SEI: login: sei_user / senha: sei_user
    Usuário da Base de Dados do SIP: login: sip_user / senha: sip_user

    Ex: mysql -h 127.0.0.1 -u root -p sei

##### Oracle
    Usuário Root do Oracle: login:sys / senha:P@ssword
    Usuário de sistema do Oracle: login:system / senha:P@ssword
    Usuário da Base de Dados do SEI: login: sei_user / senha: sei_user
    Usuário da Base de Dados do SIP: login: sip_user / senha: sip_user

    Ex: sqlplus sys/P@ssword as sysdba

##### SQLServer
    Usuário Root do SQLServer: login:sa / senha:yourStrong(!)Password
    Usuário da Base de Dados do SEI: login: sei_user / senha: sei_user
    Usuário da Base de Dados do SIP: login: sip_user / senha: sip_user

    Ex: tsql -S 127.0.0.1 -U sa -P 'yourStrong(!)Password'


* **Apache Solr** O Apache Solr também estará disponível para testes e poderá ser acessado pelo endereço: http://localhost:8983/solr

* **Memcached** Servidor de cache utilizado pela aplicação SEI e SIP http://localhost:11211

* **Serviço SMTP para visualizar e-mails enviados** O ambiente de desenvolvimento possui um serviço SMTP próprio para disparar os e-mails do sistema. Para visualizar os e-mails enviados acesse: http://localhost:1080

