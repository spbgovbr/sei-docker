<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 18/05/2011 - criado por mga
*
* Versгo do Gerador de Cуdigo: 1.31.0
*
* Versгo no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PesquisaProtocoloSolrDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }
  
  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PalavrasChave');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinTramitacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinProcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDocumentosGerados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDocumentosRecebidos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinConsiderarDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'NumIdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdContato');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinInteressado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinRemetente');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDestinatario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdAssinante');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Descricao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Observacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdAssunto');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidadeGeradora');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTipoProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Numero');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeArvore');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DIN,'ValorInicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DIN,'ValorFim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuarioGerador1');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuarioGerador2');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUsuarioGerador3');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'InicioPaginacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_BOL,'Arvore');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'StaTipoData');//"Autuaзгo/Cadastro" ("C") ou "Inclusгo no Sistema" ("I") ou Ambas ("A")

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'ResultadoPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'LinkPublicacao');
  }
}
?>