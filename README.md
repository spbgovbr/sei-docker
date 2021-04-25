# SEI - Docker


# Projeto de Infraestrutura sob Código para o SEI

Esse projeto altamente parametrizável permitirá ao administrador de infraestrutura subir um SEI completo, com todos os componentes necessários para o seu uso imediato em ambiente de DTH.

Nesse momento vamos focar o esforço para um ambiente de Desenvolvimento / Testes / Treinamento ou Primeira Homologação - DTH. 

**Não recomendado para produção.** 

Configurações e ajustes em produção deverão ser observadas todas as recomendações na documentação do TRF, do PEN e as melhores práticas de infraestrutura de TI comercialmente aceitas. Eventualmente dependendo das necessidades dos órgãos e priorizações alinhadas com a comunidade poderemos evoluir a entrega para algo mais próximo de produção. Porém importante frisar que os ativos de segurança, como firewall, filtro de conteúdo, backup entre outros ficam a cargo do órgão responsável. 

**Atenção** 
O código fonte do SEI é propriedade do TRF4. Sob nenhuma hipótese o mesmo deverá ser distribuído, emprestado ou salvo em qualquer lugar que não seja privativo da TI do orgão.

Para maiores informações sobre o código fonte consulte o site do [processoeletronico.gov.br](http://processoeletronico.gov.br)

## Serviços

Através de um único comando poderão ser provisionados os serviços listados abaixo. Primeiro iremos prover o básico, portanto nem todos os serviços estarão disponíveis inicialmente. Iremos entregando maior complexidade ao longo do tempo.

**Servicos:**
- nó(s) de aplicação
- balanceador
- memcached
- solr
- banco de dados
- banco de dados de auditoria
- jod
- agendador
- openldap
- servidor de emails


Cada serviço tem seu próprio nível de customização e o administrador poderá decidir por exemplo se deseja usar o banco de dados provisionado pelo projeto de provisionamento ou usar seu próprio banco de dados.
O mesmo vale para qualquer outro serviço.

**Recursos dinâmicos**
Volumes de dados: 
- arquivos externos
- certificados
- solr-data
- banco de dados
- banco de dados de auditoria
- base de dados openldap

Alguns dos parâmetros externos que será possível informar ao rodar o projeto:

- URLs de acesso para o SEI e serviços, como administração do Solr
- http/https
- Certificados (usar um próprio ou mandar criar um auto-assinado)
- Escolha da versão do SEI (inicialmente apenas SEI4)
- Instalação de módulo e sua versão
- Escolha do banco de dados desejado com a base de referência

## Publicação e Orquestração

Inicialmente a entrega vai focar em mono máquina. Mas está previsto no nosso roadmap entregar também a instalação em várias máquinas. Para isso vamos usar o Rancher/Cattle, Rancher/Kubernetes 

Além disso iremos publicar aqui a nossa esteira de testes em jenkins, que além de instalar um ambiente do zero com os módulos desejados, faz a execução dos testes funcionais que já escrevemos para alguns módulos, usando um cluster com SeleniumGrid.

## Instruções para subir o SEI

[Clique aqui](Instrucoes.md) para as instruções iniciais de como rodar o projeto

## Informações Adicionais
Caso esteja procurando o projeto antigo para o SEI3 que havia aqui pule para a branch [SEI3x-Docker-Antigo](https://github.com/spbgovbr/sei-docker/tree/SEI3x-Docker-Antigo)