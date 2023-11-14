<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class ConcluirProcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'PrazoReaberturaProgramada');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'DiasReaberturaProgramada');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDiasUteisReaberturaProgramada');
  }
}
?>