<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SituacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'situacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdSituacao','id_situacao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Nome','nome');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Descricao','descricao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'SinAtivo','sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelSituacaoUnidadeDTO');

    $this->configurarPK('IdSituacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>