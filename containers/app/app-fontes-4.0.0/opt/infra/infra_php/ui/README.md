[![coverage report](https://git.trf4.jus.br/infra_php/infra_php_fontes/badges/master/coverage.svg)](https://git.trf4.jus.br/infra_php/infra_php_fontes/commits/master)

# Introdução

Esse projeto serve como um showcase dos componentes utilizáveis por diferentes sistemas do TRF4, além de servir para implementar o exposto em http://eproc.gitpages.trf4.jus.br/eproc2/design-system.

Exemplos podem ser vistos [aqui](https://ui-showcase-desenv.k8s-des.trf4.jus.br/)

Objetivos:
* [x] minimizar o impacto da mudança do estilo de um sistema.
    > Ex.: ao migrar do estilo "Infra" para "Bootstrap", seria necessário refatorar todos os botões, trocando as classes `infraButton` por `btn`, no mínimo.
* [x] simplificar a criação ou configuração de componentes de interface do sistema.

Hoje, estão sendo suportados os seguintes estilos:
- Infra
- Bootstrap4

### - código, + resultado

Para ser mais concisa e legível, todos os componentes seguem o padrão `Builder`, ou seja, pode-se encadear diversos métodos para configurar um componente. Exemplo:
```php
UI::button('Meu botão')
    ->primary()
    ->class('abc')
    ->id('xyz')
    ->what('ever');
```

Isso produzirá o seguinte html, no caso do estilo bootstrap:

```html
<button 
    class="my-btn-class btn btn-primary" 
    id="btn-id">
    Meu botão
</button>
```


Além disso, o método `__toString()` foi sobrescrito para executar o método `render()` de um componente. 
Todas as invocações da tabela abaixo, por exemplo, teriam o mesmo resultado

| linguagem | chamada | 
| --------- | ------- |
| twig      | `{{ ui.button('meu botão').primary() }}` | 
| php       | `<?php echo UI::button('meu botão')->primary(); ?>`| 
| php       | `<?= UI::button('meu botão')->primary(); ?> `| 
| php       | `<?= UI::button('meu botão')->primary()->render(); ?> `| 




# Conceitos

### Componente
É um conjunto de elementos que deve prover uma interação padronizada aos usuários, possivelmente configurável.
Ex.:
- Área de upload de arquivos (fileUploader)
- Select
- Campo de texto (inputText)

### Subcomponentes
São as peças que compõem um componente. Um select, por exemplo, é composto por:
- label (rótulo do select)
- select (o campo principal)
- wrapper (o elemento que agrupa o label e o select)

### Subcomponente principal

É o subcomponente configurado por padrão quando executado um método qualquer de um componente.

Ex:
Ao chamar o método `class('abc')` no componente `select`, será aplicada a classe 'abc' ao elemento `<select>`.

### Renderer
Os componentes são abstratos - são apenas conceitos
O renderer é a classe responsável por concretizar isso, ou seja, construir um componente usando o estilo por ela determinado.
Hoje existem dois renderers:
- Infra
- Bootstrap4 
   
  
  
  
  

  
  
  
# Utilização

Os componentes estão elencados na classe `UI`.

### Personalizações específicas

Cada componente possui suas próprias personalizações. Para isso, veja a documentação de cada classe de componente.

Exemplos:
- button possui o método `primary()`;
- campos de formulário possuem o método `required()`

### Configurando elemento principal

Para definir qualquer atributo dos elementos de um componente, pode-se invocar métodos inexistentes (mágicos).
A conversão funciona da seguinte forma: 
- nome do método = nome do atributo
- valor do método = valor do atributo
- Métodos em `camelCase` são convertidos para `kebab-case`

Exemplos:

| método                  | resultado HTML      |
| ------                  | --------------      |
| `->class('no-padding')` | class="no-padding"  |
| `->dataTestUrl('abc')`  | data-test-url="abc" |


### Configurando outros subcomponentes

Para definir atributos de outros subcomponentes como `label` e `wrapper`, pode ser usada uma sintaxe especial por meio da qual, __chamando um método com o mesmo nome do subcomponente__:
- o **primeiro** parâmetro é o __nome__ do atributo
- o **segundo** parâmetro é o __valor__ desse atributo

Por exemplo:
```php
UI::button(...)
  ->primary()
  ->_wrapper('style', 'color:"red"') // Se "wrapper" fosse o subcomponente principal, seria o equivalente a chamar `$wrapper->style('color:"red"')
```




# Instalação/configuração

> Para um exemplo de projeto usando webpack, veja https://git.trf4.jus.br/design-system/ui-client

### Assets de cada renderer

Conforme o estilo/renderer desejado, o processo será diferente, conforme detalhado abaixo:

#### Bootstrap4

1 - Instale as seguintes bibliotecas do npm, que podem ser vistas em `showcase/package.json`: 
```
npm add bootstrap@^4.3.1 \
        bootstrap-select@^1.13.12 \
        imask@^5.2.1 \
        jquery@^3 \
        material-icons@^0.3.1 \
        moment@^2.24.0 \
        moment-timezone@^0.5.27 \
        tempusdominus-bootstrap-4@^5.1.2
```

2 - Compile os seguintes arquivos: 
- js: `vendor\trf4\infra\ui\showcase\resources\renderers\bootstrap4\js\main.js`
- scss: `vendor\trf4\infra\ui\showcase\resources\renderers\bootstrap4\sass\main.scss`

> Caso já utilize alguma dessas bibliotecas, também é possível copiar o conteúdo desses arquivos e adequá-los a seu processo de build

3 - Inclua os arquivos compilados em seu template
4 - Tente usar a lib. Ex.:

```php 
<?php

use InfraUI as UI;
UI::config(new TRF4\UI\Renderer\Bootstrap4);

//includes de css e js

echo UI::datetime('Data/hora', 'minha_data2');

```


#### Infra

Inclua os assets usados pela infra, como demonstrado no arquivo `resources\views\templates\infra-app.blade.php`.
```html
<!-- ....  -->
<link href="/infra_css/infra-global-esquema.css" rel="stylesheet" type="text/css" media="all">
<!-- ....  -->
<script type="text/javascript" charset="iso-8859-1" src="/infra_js/InfraUtil.js"></script>
<!-- ....  -->
```




  
# Showcase
Como prezamos por código limpo e reutilizável, todos os exemplos documentados na página "showcase" são gerados em runtime a partir dos casos de teste declarados nesta aplicação. 
Isso garante que os exemplos de uso de componentes documentados funcionem __conforme a implementação de seus testes__.

Detalhes técnicos:
* Os casos de teste estão localizados em [`/tests/Unit/Showcaseable`](/tests/Unit/Showcaseable).
* A hierarquia de diretórios/classes desse diretório tem relação direta com a estrutura gerada no showcase, ou seja: cada diretório é um menu, cada subdiretório é um submenu e cada classe é um item de menu ou submenu. 
* Para que os testes sejam exibidos no showcase, eles devem ser filhos da classe [`tests/Showcaser.php`](tests/Showcaser.php)
  
  
  

# Contribuição

Caso exista algum componente que se considere necessário a este projeto, abra uma issue informando:
- descrição breve do componente
- imagem de exemplo (opcional)
- configurações possíveis (ex.: o botão pode ser primário ou secundário)

Caso queira fazer um merge request, é obrigatório:
- seguir o padrão de código do projeto, inclusive PSRs 1 e 2 e [12](https://www.php-fig.org/psr/psr-12/)
- métodos devem ser em inglês
- Arquivos devem ter line-endings LF e serem em [UTF-8](http://utf8everywhere.org/)
- As propriedades das classes de componentes devem ser em `lowerCamelCase`; e **apenas** aquelas que representarem [subcomponentes](#subcomponentes) devem ser prefixas por `_`
- Comportamentos que devem ser documentados e exibidos na documentação devem ser registrados na forma de casos de teste do [Showcase](#Showcase).
- Outros casos de teste excepcionais (tratamento de exceções, por exemplo) devem ser criados _fora_ do diretório `Showcaseable`

 

# Executando em sua máquina

1. Clone o projeto
2. Copie o arquivo `.env.example`, nomeando-o como  `.env`
3. Caso use docker, execute `docker-compose up -d`
4. Execute:
    1. caso não use Docker, `make build-dev`
    2. caso use Docker, `docker-compose exec workspace make build-dev`
5. Para subir o servidor, caso NÃO possua docker, execute `php artisan serve` 
6. Acesse `http://127.0.0.1:${APACHE_PORT}`

> A variável ${APACHE_PORT} está definido no arquivo .env
   
## TODOs
* [ ] Documentar dependências por componentes específicos (ex.: alguns da infra usam jUI - a versão correspondente do BS é só CSS e JS)
