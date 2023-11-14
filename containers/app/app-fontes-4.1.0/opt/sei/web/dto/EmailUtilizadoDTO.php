<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/08/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailUtilizadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'email_utilizado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdEmailUtilizado',
                                   'id_email_utilizado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Email',
                                   'email');

    $this->configurarPK('IdEmailUtilizado',InfraDTO::$TIPO_PK_NATIVA);

  }
}
?>