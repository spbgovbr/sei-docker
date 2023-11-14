# Changelog
Este subprojeto adere ao [Versionamento Semântico](https://semver.org/spec/v2.0.0.html) e também é baseado no [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/)
 

## 0.3
### Added
- Método `hint`, que permite a inclusão de uma dica em diversos componentes
- Método `randomId()`, para permitir a geração de IDs aleatórias
- Na classe `UI`:
    - `hidden` (Input hidden)
- Classe `TRF4\UI\Unserialize`, que permite a recuperação de valores complexos.
- Extensão para o Twig (`TRF4\UI\Twig\Extension`)  que permite a criação de um form com validação

### Changed
- Diversos métodos agora possuem efeito nulo, tornando-os mais flexíveis  
    - Métodos `checked` e `selected` agora podem receber o valor false
    - Método `value` agora pode receber `null`ou uma `string` vazia
    - Método `required` agora pode receber um segundo parâmetro, booleano, permitindo que essa condição seja opcionalmente atribuída
- `InputText` pode ter label nulo 

### Removed
- Removendo classes e métodos depreciados na versão 0.2

### Fixed
- Corrigida a barra de intervalo do multiRange que em alguns casos excedia a posição dos handles
- O parâmetro de placeholder de uma dependência de um select agora é utilizado. Antes, apenas o placeholder do select filho era utilizado.
- Tag `<span>` de select com dependência não era fechado
- Agora ao name do select múltiplo é sufixado `[]` para permitir a correta recuperação dos valores



## 0.2.2
### Fixed
- Date: valor vazio agora retorna uma string vazia.
- Date Interval: Não era permitido a configuração do subcomponente wrapper 



## 0.2.1
### Fixed   
- `UI::inputNumeroProcesso`. Causava erro ao ser renderizado via twig por usar  método `getId()` ao invés de `getAttrId()`.
- Método `value` em componentes `date` e `datetime` 



## 0.2
### Added
- Classe de presets (`UI\Preset`) contendo os seguintes componentes:
  - CPF
  - CNPJ
  - NumeroProcesso
- Novos componentes:
    - `InputNumber`
    - `FileUpload` (experimental)
    - `Range`
    - `MultiRange`
- Novos comportamentos:
    - Selects agora possuem o método `activatedBy`, permitindo que um select seja ativado/desativado conforme o estado de um checkbox.
   
### Changed
- `TRF4\UI\Util\AjaxCallback` agora é `TRF4\UI\Util\AjaxCallback`
- Checkboxes BS4 criados isoladamente (fora de `CheckboxGroup`) agora possuem no atributo de classe `form-group`
- Melhorando documentação(showcase) / adicionando modo noturno
- Selects múltiplos agora são criados por meio de `UI::multiSelect` ao invés de `UI::select(...)->multiple()`

### Deprecated
- Na classe `UI`:
    - `dateTimePeriodo`; use `dateTimeInterval`
    - `datePeriodo`; use `dateInterval`
    - `inputCPF`; use `UI\Preset::cpf`
    - `inputCNPJ`; use `UI\Preset::cnpj`
    - `inputNumeroProcesso`; use `UI\Preset::numeroProcesso`
    - `inputMask`; use o método `mask` de `\TRF4\UI\InputText`
- UIConfig; use UI\Config

### Removed
- Método `iconButton` da classe `UI` (solução ainda não desenvolvida - ver https://git.trf4.jus.br/infra_php/infra_php_fontes/issues/27)



## 0.1.0
### Added
- Classe `UI` com os seguintes componentes:
    - Button
    - Checkbox
    - CheckboxGroup
    - RadioGroup
    - Date
    - DateTime
    - InputText
    - Select
    - Textarea
