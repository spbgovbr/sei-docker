# SEI-DOCKER

## Containers

Na pasta containers encontram-se as receitas para as imagens docker. Os conteineres já existem de forma pública para você rodar o projeto em sua máquina local ou infra. Não é necessário entrar aqui ou conhecer essa área para rodar o SEI.

Mas caso mesmo assim deseje buildar as imagens por conta própria, modificá-las ou usar o seu próprio registry; basta acessar essa pasta. Nela estão as receitas docker usadas, bem como as automatizações (Makefile) para criar seus próprios conteineres em seu próprio Docker Registry.

Rode ``` make help ``` para uma lista de todos os comandos disponíveis.

Rode ``` make getenv ``` para criar um arquivo envcontainers.env que serve de modelo para rodar todos os outros targets. Altere-o a seu gosto.

Verifique que se vc alterar esse arquivo, deverá replicar essas alteracoes com o novo DOCKER_REGISTRY e DOCKER_CONTAINER_VERSAO_PRODUTO nos envfiles referentes às outras áreas do projeto (dev e infra)


### Pasta tests

Mais sobre os testes [clique aqui](../README.md#testes)
