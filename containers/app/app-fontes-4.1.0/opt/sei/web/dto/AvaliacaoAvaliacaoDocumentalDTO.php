<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
*
* 19/10/2018 - criado por cjy
*
* Vers�o do Gerador de C�digo: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvaliacaoAvaliacaoDocumentalDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaAvaliacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Descricao');
  }
}