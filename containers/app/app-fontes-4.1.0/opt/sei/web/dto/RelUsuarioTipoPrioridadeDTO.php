<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/04/2023 - criado por cas84
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioTipoPrioridadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_usuario_tipo_prioridade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTipoPrioridade', 'id_tipo_prioridade');

    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdTipoPrioridade',InfraDTO::$TIPO_PK_INFORMADO);

  }
}
