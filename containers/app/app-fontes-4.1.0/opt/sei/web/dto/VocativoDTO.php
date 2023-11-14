<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class VocativoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'vocativo';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdVocativo',
                                   'id_vocativo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Expressao',
                                   'expressao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->configurarPK('IdVocativo', InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>