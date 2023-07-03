# Vídeo Tutoriais


Os vídeos abaixo foram gravados por nossa equipe para auxiliar no entendimento do ecossistema desse projeto.

Neles, citamos diversas dicas tanto do projeto em si, mas também de docker, infra e SUPER.
Vale a pena gastar o tempo assistindo a todos eles principalmente se você é da área de infra, arquiteto, ou tenha pouco conhecimento no SUPER. 

Eles irão abrir a sua mente para um projeto mais amplo na entrada em produção.

Por favor, leve em consideração que não somos editores profissinais de conteúdo, por essa razão encare os vídeos mais como um bate-papo entre *"técnicos que falam tecnicês"*.

Assista-os de preferência na ordem disposta abaixo.

## Subir Infra Reduzida Rapidamente

Neste vídeo demonstramos a subida do SUPER rapidamente com orientações básicas usando o arquivo default de configuração:
- sobe o sei em localhost com https
- Mostra a interface de estatísticas do balanceador
- outras orientações básicas para  rodar o projeto

[Clique aqui](https://www.youtube.com/watch?v=FwPp9lZiHuM) para abrir o vídeo.


## Subir Infra Completa Rapidamente

Aqui vamos demonstrar uma subida com todos os componentes disponíveis no projeto  até a data de 15/05/2021.

- Clona o projeto
- Altera arquivo de ambiente com customizações
- Sobe o SUPER com:
- https
- domain name de sua preferência
- Nome e Sigla do Orgao de sua preferência
- Descrição e Nome Complemento para o ambiente de sua preferência
- balanceador com interface de estatísticas
- memcached admin
- solr admin
- mailCatcher
- phpldapadmin
- dbadmin
- todos os serviços sendo acessados na mesma url base (Domain Name) pelo balanceador mudando apenas o path ( Ex: https:///meusei.gov.br/haproxy, https:///meusei.gov.br/memcachedadmin, https:///meusei.gov.br/sei, etc)
- muitas dicas e orientações

[Clique aqui](https://www.youtube.com/watch?v=MpTLtDlSVLw) para abrir o vídeo.

## Subir Infra Reduzida Usando SqlServer ou Oracle

O projeto aceita o uso do Mysql, SqlServer ou Oracle. Aqui demonstramos como subir o ambiente usando SqlServer e Oracle em conteineres.

[Clique aqui](https://www.youtube.com/watch?v=IgEiR5CZEEs) para abrir o vídeo.

## Organização e Estruturação do Projeto

Aqui falamos de aspectos técnicos diversos do projeto. Tecnologias e organização das informações.
Importante assistir para entender o escopo geral dos ativos bem como a anatomia da solução entregue.

[Clique aqui](https://www.youtube.com/watch?v=rczbANlWVRY) para abrir o vídeo.

## Subir Infra Reduzida com Algumas Customizações

Aqui é uma subida mais demorada pois explicamos características pertinentes aos ambientes.
- localhost com https
- como mudar o nome do ambiente de localhost para outro Domain Name sem perder os dados cadastrados
- Solr - administrar, forçar perda e recriar/reindexar 
- Scale da aplicação
- outros

[Clique aqui](https://www.youtube.com/watch?v=HjZfryu0sco) para abrir o vídeo.

## Subir Infra Completa com Customizações

Complementando o vídeo anterior, vamos agora incrementar os componentes abrangendo todos eles.

Passamos pela explicação técnica dos mesmos e vamos além explicando novos pontos e situações como: desativar o OpenLdap e voltar a config antiga automaticamente; como cadastrar usuários na árvore do Ldap; como destruir todo o ambiente e outros assuntos diversos importantes

[Clique aqui](https://www.youtube.com/watch?v=m5wXBPDMVQQ) para abrir o vídeo.


---

Voltar para [Readme Principal](../README.md)

Voltar para [Readme Instruções](README.md)