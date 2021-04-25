# Instruções Iniciais Básicas

Vamos liberar rapidamente as instruções resumidas para você já subir o projeto em seu ambiente.

Ao longo do tempo iremos gravar vídeos explicativos e melhorar a doc aqui.

## Pré-requisitos
- linux ou MacOs
- docker
- docker-compose
- o projeto usa também makefile e comandos como envsubst, portanto os pacotes para esses comandos devem estar instalados, geralmente eles já vem por default
- e o principal: **Código Fonte do SEI4.0**


## Passo a passo de Instalação

Todas as configurações do SEI que irá subir, ficam no arquivo envlocal.env

Inicialmente sugerimos que vc verifique a variável:
LOCALIZACAO_FONTES_SEI
leia a orientação que ali aparece e posicione o código fonte no local adequado.

Em seguida vc estará pronto para subir o sei em localhost.
Depois de subir em localhost e testar, pare o projeto, apague tudo com o comando make adequado; abra o envlocal.env novamente e altere ali a chave que indica qual o nome da url do SEI e coloque a url desejada. Lembre q o seu DNS ou seu /etc/hosts devem apontar para a máquina onde vc está subindo o novo SEI.

Verifique se não há nada rodando na porta 80 ou 443 senao ele não irá subir. Na próxima entrega será entregue com o balanceador e a possibilidade de escalar o app.

## Makefile

Após posicionar corretamente o fonte do SEI4 vc poderá usar o Makefile para montar a receita para o seu orquestrador preferido. Agora apenas está disponível o docker-compose.

Vamos agora subir o SEI completo em 3 passos:

- 1. Baixar/clonar o projeto do SEI
```
git clone https://github.com/spbgovbr/sei-docker.git
#vá para a pasta do projeto
cd sei-docker
```

- 2. Montar o Volume Docker com os Fontes
```
make montar_volume_fontes
```

- 3. Subir o projeto
```
make run
```
O make run acima, vai ler o seu envlocal.env e vai montar um docker-compose.yml adequado para rodar o SEI. 
A partir dai ele já vai baixar as imagens dos componentes necessários e rodar o SEI.

## Comandos Adicionais

Se você conhecer o docker-compose poderá usar seus conhecimentos para orquestrar o projeto a partir dele.
O make cria o docker-compose.yml no diretorio orquestrator/docker-compose

Ou vc pode usar o make como abaixo.
Existem alguns comandos necessários, por exemplo para vc destruir tudo e começar do zero, caso encontre algum problema.

Rodando make ou make help teremos a disposição os comandos existentes, leia cada um deles e tente entender o que cada um faz. Essa lista irá crescer ao longo do tempo:
```
➜  sei-docker git:(master) make help
help:    Lista de comandos disponiveis e descricao. Voce pode usar TAB para completar os comandos
montar_volume_fontes:  Monte o volume docker com os fontes que serao consumidos pelo projeto
build_docker_compose:  Construa o docker-compose.yml baseado no arquivo envlocal.env
run:  roda na sequencia build_docker_compose e up -d
stop:  docker-compose stop e docker-compose rm -f
logs:  docker-compose logs -f pressione ctrol+c para sair
clear:  para o projeto e remove tds os volumes criados
clear_volume_fontes:  Monte o volume docker com os fontes que serao consumidos pelo projeto
```
