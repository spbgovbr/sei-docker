<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/11/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id: ConjuntoEstilosDTO.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConjuntoEstilosDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'conjunto_estilos';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdConjuntoEstilos',
                                   'id_conjunto_estilos');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinUltimo',
                                   'sin_ultimo');

    $this->configurarPK('IdConjuntoEstilos',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>