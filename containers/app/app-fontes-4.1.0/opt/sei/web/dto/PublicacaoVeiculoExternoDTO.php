<?
/**
* TRIBUNAL REGImprensaOficialNAL FEDERAL DA 4ª REGIÃO
*
* 25/11/2008 - criado por mga
*
* Versão do Gerador de Código: 1.25.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoVeiculoExternoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return null;
  }

  public function montar() {    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoOrgao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdTipoDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeTipoDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdVeiculoPublicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeVeiculoPublicacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NumeroDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ConteudoDocumento');    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdDocumento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdDocumentoPai');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'DataDisponibilizacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdVeiculoImprensaOficial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SiglaVeiculoImprensaOficial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoVeiculoImprensaOficial');    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'DataPublicacaoVeiculoImprensaOficial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdSecaoPublicacaoVeiculoImprensaOficial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'NomeSecaoPublicacaoVeiculoImprensaOficial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'PaginaPublicacaoVeiculoImprensaOficial');    
  }
}
?>