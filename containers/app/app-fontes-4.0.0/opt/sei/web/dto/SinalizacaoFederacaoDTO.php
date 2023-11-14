<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class SinalizacaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'sinalizacao_federacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao', 'id_instalacao_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'IdProtocoloFederacao', 'id_protocolo_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Sinalizacao', 'dth_sinalizacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'StaSinalizacao', 'sta_sinalizacao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProtocolo');

    $this->configurarPK('IdInstalacaoFederacao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdProtocoloFederacao',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
