<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EnviarProcessoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Atividades');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinManterAberto');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinRemoverAnotacoes');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinEnviarEmailNotificacao');
  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'AtividadesOrigem');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'PrazoRetornoProgramado');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'DiasRetornoProgramado');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDiasUteisRetornoProgramado');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'PrazoReaberturaProgramada');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'DiasReaberturaProgramada');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDiasUteisReaberturaProgramada');

  }
}
?>