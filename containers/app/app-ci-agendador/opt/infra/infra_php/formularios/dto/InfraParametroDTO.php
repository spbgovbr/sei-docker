<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

//require_once 'Infra.php';

class InfraParametroDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'infra_parametro';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Valor',
                                   'valor');

    $this->configurarPK('Nome',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
?>