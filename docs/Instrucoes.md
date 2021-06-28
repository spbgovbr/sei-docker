# Instruções Iniciais Básicas


## Pré-requisitos
- linux ou MacOs
- docker
- docker-compose
- o projeto usa também makefile e comandos como envsubst, portanto os pacotes para esses comandos devem estar instalados, geralmente eles já vem por default
- e o principal: **Código Fonte do SEI4.0**

*PS: versões antigas do docker ou docker-compose fatalmente irão dar erro*

## Como funciona esse projeto?

Muito simples, ele é um automatizador de ambiente virtual docker.
1. Define-se parametros gerais no arquivo envlocal.env
2. Makefile cria os volumes docker necessários
3. Makefile cria a receita do orquestrador referente ao projeto
4. Makefile roda o orquestrador desejado
5. A partir dai vc pode usar o orquestrador para acompanhar a execução ou usar os próprios comandos incorporados ao Makefile para controlar o projeto

## Leia Isso Antes de Rodar

Depois de clonar o projeto, vá até o dir raiz. Digite e leia o resultado dos seguintes comandos:
```
make 
ou 
make help
```
Vai mostrar a ajuda para cada comando make que você pode efetuar.

Todas as configurações do SEI que irá subir, ficam no arquivo envlocal.env

Inicialmente sugerimos que vc verifique a variável:
LOCALIZACAO_FONTES_SEI
leia a orientação que ali aparece e posicione o código fonte no local adequado.

**IMPORTANTE: A recomendação inicial é que você não seja um doido e já saia modificando o arquivo envlocal.env a revelia.**

Suba primeiro com a sugestão ofertada. Depois usando o make, desligue o projeto e remova os volumes. Altere um parâmetro no envlocal.env e suba novamente. Teste as alterações e o resto do ambiente. Desligue e limpe os volumes novamente, altere os parâmetros e suba até compreender todo o ecossistema e definir o que você vai deixar rodando.

Depois de subir em localhost e testar, pare o projeto, apague tudo com o comando make adequado; abra o envlocal.env novamente e altere ali a chave que indica qual o nome da url do SEI e coloque a url desejada. Lembre q o seu DNS ou seu /etc/hosts devem apontar para a máquina onde vc está subindo o novo SEI.

Verifique se não há nada rodando na porta 80 ou 443 senao ele não irá subir. 

Para rodar todo o projeto com um único comando:
```
make setup
```
O make setup se desmembra em diversos outros comandos. Recomendado que vc os conheça para desenvolver um bom trabalho.

Parar todo o projeto:
```
make clear
```

Limpar todos os volumes (aqui perde a persistência dos dados, cuidado):
```
make apagar_volumes
```

PS: Importante: Recomendamos sempre antes de rodar o make run ou make setup, caso tenha alterado alguma configuração no envlocal.env que rode antes o comando make clear. Isso é importante para você evitar ficar com serviços orfãos rodando e atrapalhando tudo. Isso acontece quando se altera o envlocal tirando serviços que antes estavam no ar. Não se preocupe pois o make clear não apaga dados, apenas conteineres e redes.



## Makefile

Após posicionar corretamente o fonte do SEI4 vc poderá usar o Makefile para montar a receita para o seu orquestrador preferido. Agora apenas está disponível o docker-compose.

Vamos agora subir o SEI completo em 3 passos:

- 1. Baixar/clonar o projeto do SEI
```
git clone https://github.com/spbgovbr/sei-docker.git
#vá para a pasta do projeto
cd sei-docker
```

- 2. Posicionar o volume dos Fontes do SEI4
```
ver arquivo envlocal.env e posicionar de acordo
```

- 3. Subir o projeto
```
make setup
```
O make setup acima, vai ler o seu envlocal.env e vai montar um docker-compose.yml adequado para rodar o SEI. 
A partir dai ele já vai baixar as imagens dos componentes necessários e rodar toda a infra disponibilizada.

## Comandos Adicionais

Se você conhecer o docker-compose poderá usar seus conhecimentos para orquestrar o projeto a partir dele.
O make cria o docker-compose.yml no diretorio orquestrator/docker-compose

Ou vc pode usar o make como abaixo.
Existem alguns comandos necessários, por exemplo para vc destruir tudo e começar do zero, caso encontre algum problema.

Rodando make ou make help teremos a disposição os comandos existentes, leia cada um deles e tente entender o que cada um faz. Essa lista irá crescer ao longo do tempo:
```
➜  sei-docker git:(master) make help
help:    Lista de comandos disponiveis e descricao. Voce pode usar TAB para completar os comandos
criar_volumes:  Cria todos os volumes necessarios ao projeto. As vezes eh necessario apagar os volumes antes, para apagar make apagar_volumes
criar_volume_fontes:  Cria o volume docker com os fontes que serao consumidos pelo projeto
criar_volume_certs:  Cria o volume docker com os certs que serao consumidos pelo projeto
criar_volume_banco:  Cria o volume docker com os fontes que serao consumidos pelo projeto
criar_volume_arquivos_externos:  Cria o volume docker para os arquivos externos
criar_volume_solr:  Cria o volume docker para os dados do solr
criar_volume_openldap_slapd:  Cria o volume docker para a base do openldap slapd
criar_volume_openldap_db:  Cria os volumes docker para a base do openldap db
criar_volume_controlador_instalacao:  Cria o volume para controlar a instalacao do SEI e modulos
build_docker_compose:  Construa o docker-compose.yml baseado no arquivo envlocal.env
run:  roda na sequencia build_docker_compose e up -d
setup:  executa criar_volumes e run na sequencia
scale:  escala os nohs de aplicacao do SEI para 2. Caso vc queira mais de 2 basta usar o comando make qtd=3 scale, substituindo o 3 pelo numero desejado, ou o comando docker-compose scale app=x na pasta orquestrators/docker-compose
stop:  docker-compose stop e docker-compose rm -f
logs:  docker-compose logs -f pressione ctrol+c para sair
logs_app:  docker-compose logs -f app pressione ctrol+c para sair
logs_app-atualizador:  docker-compose logs -f app-atualizador pressione ctrol+c para sair
logs_balanceador:  docker-compose logs -f balanceador pressione ctrol+c para sair
logs_openldap:  docker-compose logs -f openldap pressione ctrol+c para sair
logs_solr:  docker-compose logs -f solr pressione ctrol+c para sair
clear:  pahra o projeto e remove tds os conteineres, redes criados. Nao remove os volumes
apagar_volumes:  Apaga todos os volumes do projeto ATENCAO TODOS OS DADOS DE BASE E ARQUIVOS SERAO DELETADOS
apagar_volume_fontes:  Monte o volume docker com os fontes que serao consumidos pelo projeto
apagar_volume_certs:  Monte o volume docker com os fontes que serao consumidos pelo projeto
apagar_volume_banco:  Apagar volume do banco
apagar_volume_arquivos_externos:  Apagar volume Arquivos Externos
apagar_volume_solr:  Apagar volume Solr
apagar_volume_openldap:  Apagar volumes OpenLDAP
apagar_volume_controlador_instalacao:  Apagar volume controlador da instalacao
```

## Vídeo Tutoriais

- [Clique aqui](VideoTutoriais.md) para abrir a ajuda com os vídeos tutoriais

---

## Como subir 2 instâncias (2 SEIs diferentes) na mesma vm

Siga esse [tutorial aqui](duploSEI/duplosei.md)


## Testes do Desenvolvedor

Leia [isso](../tests/README.md)

---
Voltar para [Readme Principal](../README.md)