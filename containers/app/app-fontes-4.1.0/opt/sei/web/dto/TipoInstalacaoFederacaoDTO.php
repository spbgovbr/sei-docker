<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoInstalacaoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Descricao');
  }
}
