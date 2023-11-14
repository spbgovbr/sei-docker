<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/07/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class VisualizarProcessoFederacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'InstalacaoFederacaoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProcedimentoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdInstalacaoFederacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdProcedimentoFederacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdDocumentoFederacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdProcedimentoFederacaoAnexado');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinProtocolos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'PagProtocolos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'MaxProtocolos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RegProtocolos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'TotProtocolos');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAndamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'PagAndamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'MaxAndamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RegAndamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'TotAndamentos');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL, 'AtualizarArvore');
  }
}
