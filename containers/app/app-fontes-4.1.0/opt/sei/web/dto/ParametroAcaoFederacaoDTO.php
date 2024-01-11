<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/07/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ParametroAcaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'parametro_acao_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdAcaoFederacao', 'id_acao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Valor', 'valor');

    $this->configurarPK('IdAcaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('Nome',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdAcaoFederacao', 'acao_federacao', 'id_acao_federacao');
  }
}
