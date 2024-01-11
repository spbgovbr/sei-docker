<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class RecursoPadraoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {

     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgaoSistema');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSistema');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Entidade');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'CaminhoBase');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoCadastrar');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoAlterar');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoConsultar');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoListar');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoSelecionar');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoExcluir');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoDesativar');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAcaoReativar');     
  }
}
?>