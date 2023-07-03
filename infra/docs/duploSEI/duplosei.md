# Como Subir 2 Instâncias do SEI Independentes na Mesma VM/Máquina

Siga esse tutorial para subir 2 SEI independentes na mesma VM. Cada um respondendo por um nome diferente.

Importante seguir exatamente como está descrito inclusive obedecendo os nomes sugeridos.

Uma vez que você subir da forma proposta e entender o contexto poderá modificar para o seu gosto.

Verifique se não há nada rodando, ou outras instâncias que porventura vc testou nessa VM.

## Passo a Passo:

Adicione isso ao seu /etc/hosts
```
127.0.0.1 meusei1.com.br meusei2.com.br
``` 
crie uma pasta vazia e siga para ela
``` cd minhapasta ``` 
 
``` git clone https://github.com/spbgovbr/sei-docker ```

``` cd sei-docker/infra ```

``` git checkout DuploSEI ```

``` cd ../.. ```

``` cp -R super-docker super-docker2 ```

``` cd super-docker/infra ```

``` vim envlocal.env ```

Encontre as linhas referentes e altere-as para exatamente esse valor:
``` 
export BALANCEADOR_PRESENTE=false
export JOD_PRESENTE=false
export APP_HOST=meusei1.com.br 
```

Sobe logo o primeiro SEI
``` make setup ```


``` 
cd .. 
cd super-docker2/infra
```
Encontre as linhas referentes e altere-as para exatamente esse valor:
```
export VOLUME_DB=local-storage-db2
export VOLUME_DB_MOUNT=local-storage-db2
export VOLUME_ARQUIVOSEXTERNOS=local-arquivosexternos-storage2
export VOLUME_ARQUIVOSEXTERNOS_MOUNT=local-arquivosexternos-storage2
export VOLUME_FONTES=local-fontes-storage2
export VOLUME_FONTES_MOUNT=local-fontes-storage2
export VOLUME_CERTS=local-certs-storage2
export VOLUME_CERTS_MOUNT=local-certs-storage2
export VOLUME_SOLR=local-volume-solr2
export VOLUME_OPENLDAP_SLAPD=local-openldap-slapd-storage2
export VOLUME_OPENLDAP_SLAPD_MOUNT=local-openldap-slapd-storage2
export VOLUME_OPENLDAP_DB=local-openldap-db-storage2
export VOLUME_OPENLDAP_DB_MOUNT=local-openldap-db-storage2
export VOLUME_CONTROLADOR_INSTALACAO=local-controlador-instalacao-storage2
export VOLUME_CONTROLADOR_INSTALACAO_MOUNT=local-controlador-instalacao-storage2
export BALANCEADOR_PRESENTE=false
export JOD_PRESENTE=false
export APP_HOST=meusei2.com.br
```

``` make criar_volumes ```

``` make build_docker_compose ```

``` cd orquestrators/docker-compose ```

```  mkdir temp ```

``` 
cd temp 
cp ../docker-compose.yml .
```

``` docker-compose up -d ```

Espere um pouco para subir o segundo SEI.
Neste momento vc terá 8 volumes e vários conteineres rodando

Vamos agora subir um proxy para vc acessar os SEIs
Crie uma pasta vazia nos seus projetos e siga para ela
``` 
mkdir ~/meuproxy
cd ~/meuproxy
 ```

Copie o seguinte conteúdo para o arquivo docker-compose.yml dentro dessa nova pasta
``` 
version: '2'
services:
    ha:
        image: processoeletronico/sei4-haproxydc:1.0.0
        environment:
            - EXTRA_FRONTEND_SETTINGS_80=acl is_root path -i /, acl is_sei1 hdr(host) -i meusei1.com.br, acl is_sei2 hdr(host) -i meusei2.com.br, redirect code 301 location http://meusei1.com.br/sei/ if is_root is_sei1, redirect code 301 location http://meusei2.com.br/sei/ if is_root is_sei2
            - EXTRA_FRONTEND_SETTINGS_443=acl is_root path -i /, acl is_sei1 hdr(host) -i meusei1.com.br, acl is_sei2 hdr(host) -i meusei2.com.br, redirect code 301 location https://meusei1.com.br/sei/ if is_root is_sei1, redirect code 301 location https://meusei2.com.br/sei/ if is_root is_sei2
            - CERT_FOLDER=/mycertexample
            - ADDITIONAL_SERVICES=docker-compose:app,temp:app
        ports:
            - 80:80
            - 443:443
            - 1936:1936
        networks:
            - docker-compose_default
            - temp_default
        volumes:
            - /var/run/docker.sock:/var/run/docker.sock
networks:
    docker-compose_default:
        external: true
    temp_default:
        external: true
 ```

Suba o proxy
``` docker-compose up -d ```

Espere um pouquinho para o proxy subir.
Acesse em:

- localhost:1936
Deve aparecer uma tela como abaixo:
![meuproxy](https://raw.githubusercontent.com/spbgovbr/sei-docker-binarios/main/docs/images/duplosei/haproxy.jpg)

Verifique se os backends do proxy estao verdinhos. Sinal que subiu corretamente. Ver figura acima.

- o primeiro SUPER estará disponível em: https://meusei1.com.br/
- o segundo SUPER estará disponível em: https://meusei2.com.br/
- conforme figura abaixo:
![seis](https://github.com/spbgovbr/sei-docker-binarios/raw/main/docs/images/duplosei/seisladoalado.jpg)


## Dúvidas

Basta enviar para a área de issues do projeto.