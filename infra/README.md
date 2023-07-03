# SUPER - Docker


# Projeto de Infraestrutura sob Código para o SUPER

Esse projeto altamente parametrizável permitirá ao administrador de infraestrutura subir um SUPER completo, com todos os componentes necessários para o seu uso imediato em ambiente de DTH.

Nesse momento vamos focar o esforço para um ambiente de Desenvolvimento / Testes / Treinamento ou Primeira Homologação - DTH. 

**Não recomendado para produção.** 

Configurações e ajustes em produção deverão ser observadas todas as recomendações na documentação do TRF, do PEN e as melhores práticas de infraestrutura de TI comercialmente aceitas. Eventualmente dependendo das necessidades dos órgãos e priorizações alinhadas com a comunidade poderemos evoluir a entrega para algo mais próximo de produção. Porém importante frisar que os ativos de segurança, como firewall, filtro de conteúdo, backup entre outros ficam a cargo do órgão responsável. 

**Atenção** 
O código fonte do SEI é propriedade do TRF4. Sob nenhuma hipótese o mesmo deverá ser distribuído, emprestado ou salvo em qualquer lugar que não seja privativo da TI do orgão.

Para maiores informações sobre o código fonte consulte o site do [processoeletronico.gov.br](http://processoeletronico.gov.br)

## Anatomia do Projeto Atualmente

Segue a anatomia do projeto atualmente caso se deseje subir por completo. Cada computadorzinho ai é um conteiner docker e cada tamborzinho é um volume docker. Os links e acessos são responsabilidade do orquestrador escolhido:

*Clique com o botão direito na figura -> copiar endereço da imagem; depois abra em uma nova aba do seu browser:*

![Anatomia do Projeto](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/anatomia_01.jpeg)

## Kubernetes

Além da infra acima em docker-compose existe uma receita para rodar um ambiente de dev em kubernetes.
A receita kubernetes conta com os seguintes recursos:
- **configmap** - mapeamento dos valores env do app
- **secret** - segredos e senhas
- **persistent volume claimns** - pontos de montagem para persistência
- **job de instalacao inicial** - instalador inicial do sistema
- **statefullsets** para o banco e solr
- **deployments** para jod, memcached e sei-app
- **ingress** com https
- gera apenas para mysql

Para gerar a receita kubernetes basta ajustar seu envfile com os valores corretos (url desejada para o sei, nome do orgao, etc).
Em seguida veja tb no envfile os valores para o kubernetes referentes a namespace, classe do storage e tb os recursos que vc vai delegar para cada componente.

Depois basta rodar `make kubernetes_montar_yaml`

Ao publicar a receita atente-se para o fonte do SEI. Como o código fonte do SEI não é publico faz-se necessário mover manualmente os fontes para o pvc vol-sei-fontes

## Serviços que Apresentam Interface

Através de um único comando provisiona-se os serviços listados abaixo. Cada serviço tem um nível de customização envolvido. Iremos entregando maior complexidade ao longo do tempo.

*Clique com o ctrl pressionado (Mac Users com o CMD pressionado)* para abrir uma tela cheia com a interface gráfica do serviço

*URLNAME é o nome que vc define para o seu ambiente no arquivo envlocal.env. Por ex:* **sei.treinamento.orgao.gov.br**

### Balanceador
Interface de estatísticas do balanceador - Acessível com URLNAME/haproxy (usuário e senha: stats)
![Balanceador](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/haproxy.jpg)

### SUPER: 
Acesse com URLNAME ou URLNAME/sei ou URLNAME/sip
![SUPER:](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/super1.jpg)


### Memcached Admin
Interface de administração e acompanhamento do Memcached. Acessível com URLNAME/memcachedadmin
![Memcached Admin](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/memcachedadmin.jpg)

### Solr Admin
Interface de Administração do Solr - acesse com URLNAME/solr
![Solr](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/solradmin.jpg)

### Database Admin
Adminer para administração simples do banco de dados. Acessível com URLNAME/dbadmin
![Database Admin](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/dbadmin.jpg)

### Mail Catcher
Interface para receber e visualizar e-mails em formato html. Acessível com URLNAME/mailadmin
![Mail Admin](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/mailadmin.jpg)

### Ldap Admin
Interface de administração do OpenLdap para cadastro de árvores de Departamentos e Usuários. Acessível com URLNAME/phpldapadmin
![PhpLdapAdmin](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/servicePictures/phpldapadmin.jpg)




**Todos os Servicos:**
- **Balanceador** - além de fazer o proxy https para os serviços adicionais, escala o sei/sip em quantas instâncias se achar necessário
- **Nó(s) de aplicação** - SEI/SIP com https auto escalável
- **Memcached** - serviço de cache em memória RAM
- **Administrador do Memcached** - Administração e acompanhamento do Memcached
- **Solr** - indexador de palavras e termos
- **Administrador do Solr** - Administração e acompanhamento do Solr
- **Banco de Dados** - escolha mysql, sqlserver e Oracle
- **Administrador do Banco de Dados** - Ferramenta adminer, para administrar os schemas, table structures ou simplesmente rodar queries diretamente no banco - ainda nao funciona para o Oracle apenas Mysql e SqlServer
- **MailCatcher** - serviço para receber todos os emails enviados pelo SEI. Acompanha junto uma interface web para visualizar os emails sendo enviados. Ótimo para ambiente de teste ou treinamento de usuários
- **JOD** - serviço para exportar docs do Office para PDF
- **Openldap** - serviço para criar e administrar árvores de departamentos/grupos e usuários
- **Administrador do Ldap** - serviço para administrar o openldap

Cada serviço tem seu próprio nível de customização e o administrador poderá decidir por exemplo se deseja usar o banco de dados provisionado pelo projeto ou usar seu próprio banco de dados.
O mesmo vale para qualquer outro serviço.

**Volumes de Dados**

- arquivos externos
- certificados
- solr-data
- banco de dados
- base de dados openldap
- codigo fonte do SEI
- controlador da instalação

Alguns dos parâmetros externos que é possível informar ao rodar o projeto:

- URLs de acesso para o SUPER e serviços
- http/https
- Certificados (usar um próprio ou mandar criar um auto-assinado)
- Escolha da versão do SEI (inicialmente apenas SEI4)
- Instalação de módulo e sua versão
- Escolha do banco de dados desejado com a base de referência
- além desses há dezenas de outros parâmetros já implementados para alterar o comportamento e sabor do ambiente

## Publicação e Orquestração

Inicialmente a entrega vai focar em mono máquina. Mas está previsto no nosso roadmap entregar também a instalação em várias máquinas. Para isso vamos usar o Rancher/Cattle, Rancher/Kubernetes 

Além disso iremos publicar aqui a nossa esteira de testes em jenkins, que além de instalar um ambiente do zero com os módulos desejados, faz a execução dos testes funcionais que já escrevemos para alguns módulos, usando um cluster com SeleniumGrid.

## Instruções Gerais

[Clique aqui](docs/README.md) para as instruções gerais, orientações iniciais e vídeo tutoriais

## Como Contribuir

Use as issues do projeto para tirar dúvidas, solicitar funcionalidades, etc: https://github.com/spbgovbr/sei-docker/issues

Caso você queira, pode fazer **pull requests** observando o seguinte:
- seguir a "filosofia" docker
- altamente desejável que possa ser aproveitado por toda a comunidade
- se achar necessário, discuta antes na parte de issues as melhorias a serem implementadas

## Testes para o Desenvolvedor

Caso desenvolva algo, não esqueça de rodar os testes para saber se não quebrou algo já existente.
Maiores informações do teste: 
[tests/README.md](tests/README.md)
