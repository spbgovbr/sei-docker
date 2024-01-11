<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/06/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'acao_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdAcaoFederacao', 'id_acao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdOrgaoFederacao', 'id_orgao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUnidadeFederacao', 'id_unidade_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdUsuarioFederacao', 'id_usuario_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdProcedimentoFederacao', 'id_procedimento_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdDocumentoFederacao', 'id_documento_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Geracao', 'dth_geracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Acesso', 'dth_acesso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'StaTipo', 'sta_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjParametroAcaoFederacaoDTO');

    $this->configurarPK('IdAcaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
