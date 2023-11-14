<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProcessoSobrestadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloProcedimentoFormatado');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeTipoProcedimento');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Motivo');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH,'Data');
  	 
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaUsuario');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeUsuario');
  	 
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimentoVinculado');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloProcedimentoFormatadoVinculado');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeTipoProcedimentoVinculado');
  	 
  }
}
?>