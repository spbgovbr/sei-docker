<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/11/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id: ConjuntoEstilosItemDTO.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConjuntoEstilosItemDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'conjunto_estilos_item';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdConjuntoEstilosItem',
                                   'id_conjunto_estilos_item');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdConjuntoEstilos',
                                   'id_conjunto_estilos');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Formatacao',
                                   'formatacao');

    $this->configurarPK('IdConjuntoEstilosItem',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdConjuntoEstilos', 'conjunto_estilos', 'id_conjunto_estilos');
  }
}
?>