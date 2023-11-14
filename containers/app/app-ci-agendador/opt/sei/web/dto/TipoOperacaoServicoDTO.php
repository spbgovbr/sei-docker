<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoOperacaoServicoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'StaOperacaoServico');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Descricao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Operacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinConfiguravel');
  }
}
?>