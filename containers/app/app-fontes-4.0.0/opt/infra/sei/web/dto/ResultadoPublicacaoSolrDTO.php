<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/05/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ResultadoPublicacaoSolrDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }
  
  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdPublicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdPublicacaoLegado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProtocoloAgrupador');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgaoResponsavel');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidadeResponsavel');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSerie');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Numero');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloFormatadoPesquisa');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ProtocoloDocumentoFormatado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Documento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Publicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'NumeroPublicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdVeiculoPublicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Resumo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdVeiculoIO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'PublicacaoIO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSecaoIO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PaginaIO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Snippet');
  }
}
?>