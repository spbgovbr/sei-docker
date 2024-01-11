<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class HierarquiaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return "hierarquia";
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdHierarquia',
                                   'id_hierarquia');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');
                                   
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'DataInicio',
                                   'dta_inicio');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'DataFim',
                                   'dta_fim');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->configurarPK('IdHierarquia',InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>