<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class InstalacaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'instalacao_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'Cnpj', 'cnpj');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Sigla', 'sigla');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Endereco', 'endereco');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChavePrivada', 'chave_privada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChavePublicaRemota', 'chave_publica_remota');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'ChavePublicaLocal', 'chave_publica_local');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaTipo', 'sta_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaEstado', 'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaAgendamento', 'sta_agendamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DescricaoTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DescricaoEstado');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjInstalacaoFederacaoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Hash');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjOrgaoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'SinalizacaoFederacaoDTO');

    $this->configurarPK('IdInstalacaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
