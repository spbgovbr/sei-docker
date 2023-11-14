<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadAvaliacaoCpadAvaliacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaCpadAvaliacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Descricao');
  }
}
