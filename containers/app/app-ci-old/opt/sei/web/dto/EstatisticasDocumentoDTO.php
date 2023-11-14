<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/07/2008 - criado por mga
*
* Versão do Gerador de Código: 1.21.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasDocumentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'documento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumento',
                                   'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProcedimento',
                                   'id_procedimento');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
                                              'InclusaoProtocolo',
                                              'dta_inclusao',
                                              'protocolo');

  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaProtocoloProtocolo',
                                              'sta_protocolo',
                                              'protocolo');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUnidadeGeradoraProtocolo',
                                              'id_unidade_geradora',
                                              'protocolo');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUnidadeGeradoraProtocolo',
                                              'id_orgao',
                                              'unidade');

  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidadeGeradoraProtocolo',
                                              'sigla',
                                              'unidade');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidadeGeradoraProtocolo',
                                              'sigla',
                                              'orgao');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');
  	 
  	 $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                        	    'IdTipoProcedimentoProcedimento',
                                        	    'id_tipo_procedimento',
                                        	    'procedimento');
  	 
    $this->configurarPK('IdDocumento',InfraDTO::$TIPO_PK_INFORMADO);

    
    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdDocumento', 'protocolo', 'id_protocolo');
    $this->configurarFK('IdUnidadeGeradoraProtocolo', 'unidade', 'id_unidade');
    $this->configurarFK('IdOrgaoUnidadeGeradoraProtocolo', 'orgao', 'id_orgao');
    $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento');
    
  }
  
}  
?>