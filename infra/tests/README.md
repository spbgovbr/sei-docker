# Testes do Projeto

Testes para o desenvolvedor desse projeto validar inicialmente se as suas alterações quebraram algo que já estava implementado anteriormente.

Esses testes foram elaborados para simular diferentes configurações no arquivo envlocal.env.
O teste sobe o projeto e roda validações na instância que está no ar para verificar se está tudo ok.

## Sabores das Instâncias Verificadas
As instâncias são variadas alterando-se o arquivo envlocal.env

- Instalação reduzida
	- sem balanceador
	- app sobe na url https://meusei.orgao.gov.br
	- memcached, solr e jod

- Instalacao default
	- balanceador
	- app sobem em localhost
	- memcached, solr e jod

- Instalação completa
	- balanceador
	- app sobe na url https://meusei2.orgao.gov.br
	- memcached, solr, jod, dbadminer, phpmemcached, openldap, phpmemcachedadmin


## Itens Verificados

Seguem alguns itens que o teste verifica para cada instalação:
- verificação de volumes se estao presentes ou ausentes
- sobe o projeto do zero obedecendo
- verifica se a url esta respondendo
- verificar se o balanceador esta respondendo em seus backends
- verificar se os componentes estao presentes
- escalona a aplicacao e verifica componentes e balanceador
- roda um teste em Selenium para logar e criar processo com doc interno e externo

## Instâncias Testadas

O teste completo, roda na sequência as seguintes instâncias e testa cada uma delas:
- instalação reduzida - mysql
- instalação reduzida - sqlserver
- instalação reduzida - oracle
- instalacao default - mysql
- instalacao default - sqlserver
- instalacao default - oracle
- instalacao completa - mysql
- instalacao completa - sqlserver
- instalacao completa - oracle
- instalação reduzida apenas http - mysql
- instalação reduzida  apenas http - sqlserver
- instalação reduzida apenas http - oracle
- instalacao default apenas http - mysql

## Como Rodar

Basta seguir para a pasta tests.

``` 
cd tests
make help
``` 
informa os comandos disponíveis para o teste. Caso deseje testar apenas partes da aplicação.

Um teste completo basta rodar:
``` 
make test_lineup_completa
```

O comando acima desencadeia o processo de teste completo criando e testando cada instância/sabor. 

---

Caso deseje rodar os teste em apenas uma instância, pode rodar da seguinte forma:
``` 
make MODALIDADE=completa BANCO=oracle test_ambiente
```

O comando acima vai rodar os testes apenas em um ambiente, na modalidade completa e no oracle

outro exemplo:

``` 
make MODALIDADE=default BANCO=mysql test_ambiente
```

Nesse caso acima, vai rodar os testes apenas na instalação default no mysql

