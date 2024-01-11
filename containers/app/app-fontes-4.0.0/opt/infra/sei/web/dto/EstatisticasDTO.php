<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasDTO extends InfraDTO {

  private $numTipoFkUnidade = null;
  private $numTipoFkProcedimento = null;
  private $numTipoFkTipoProcedimento = null;
  private $numTipoFkDocumento = null;	
	
	public function __construct(){
	  $this->numTipoFkUnidade = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkProcedimento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkTipoProcedimento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkDocumento = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }  
  
  public function getStrNomeTabela() {
  	 return 'estatisticas';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdEstatisticas',
                                   'id_estatisticas');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProcedimento',
                                   'id_procedimento');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdDocumento',
                                   'id_documento');    

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoProcedimento',
                                   'id_tipo_procedimento');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

//    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
//                                   'IdBloco',
//                                   'id_bloco');
//
//    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
//                                   'IdUsuarioAtribuicao',
//                                   'id_usuario_atribuicao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Ano',
                                   'ano');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Mes',
                                   'mes');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'TempoAberto',
                                   'tempo_aberto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Abertura',
                                   'dth_abertura');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                    'Conclusao',
                                    'dth_conclusao');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Snapshot',
                                   'dth_snapshot');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'Quantidade',
                                   'quantidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
					                                   'IdOrgaoUnidade',
					                                   'id_orgao',
					    															 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'SiglaUnidade',
					                                   'sigla',
					    															 'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'DescricaoUnidade',
					                                   'descricao',
					    															 'unidade');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'SiglaOrgaoUnidade',
					                                   'sigla',
					    															 'orgao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'DescricaoOrgaoUnidade',
					                                   'descricao',
					    															 'orgao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
					                                   'IdProcedimentoProcedimento',
					                                   'id_procedimento',
					    															 'procedimento');

    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'ProtocoloFormatadoProcedimento',
					                                   'p.protocolo_formatado',
					    															 'protocolo p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaOuvidoriaProcedimento',
                                              'sta_ouvidoria',
                                              'procedimento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'NomeTipoProcedimento',
					                                   'nome',
					    															 'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'SiglaUsuario',
					                                   'sigla',
					    															 'usuario');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'NomeUsuario',
					                                   'nome',
					    															 'usuario');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
					                                   'IdSerieDocumento',
					                                   'id_serie',
					    															 'documento');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'NomeSerie',
					                                   's.nome',
					    															 'serie s');   
     
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
					                                   'IdDocumentoDocumento',
					                                   'id_documento',
					    															 'documento');    
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'ProtocoloFormatadoDocumento',
					                                   'd.protocolo_formatado',
					    															 'protocolo d');    

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
					                                   'StaProtocoloDocumento',
					                                   'd.sta_protocolo',
					    															 'protocolo d');    

		$this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjUnidadeDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjTipoProcedimentoDTO');
    /*
    
		$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdOrgao');
		*/
		
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasGERADOS');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasTRAMITACAO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasFECHADOS');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasABERTOS');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasTEMPO');		
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasDOCUMENTOSGERADOS');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasDOCUMENTOSRECEBIDOS');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EstatisticasDESEMPENHO');
    
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasGerados');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasTramitacao');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasFechados');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasAbertos');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasTempo');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasDocumentosGerados');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasDocumentosRecebidos');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdEstatisticasDesempenho');
		$this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'TotalTramitacao');
    
    
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade',$this->getNumTipoFkUnidade());
    $this->configurarFK('IdOrgaoUnidade', 'orgao', 'id_orgao');
    $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento',$this->getNumTipoFkProcedimento());
    $this->configurarFK('IdDocumento', 'documento', 'id_documento',$this->getNumTipoFkDocumento());
    $this->configurarFK('IdDocumentoDocumento', 'protocolo d', 'd.id_protocolo');
    $this->configurarFK('IdSerieDocumento', 'serie s', 's.id_serie');
    $this->configurarFK('IdProcedimentoProcedimento', 'protocolo p', 'p.id_protocolo');
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento',$this->getNumTipoFkTipoProcedimento());
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
  }

  public function getNumTipoFkUnidade(){
    return $this->numTipoFkUnidade;
  }
  
  public function setNumTipoFkUnidade($numTipoFkUnidade){
    $this->numTipoFkUnidade = $numTipoFkUnidade;
  }
  
  public function getNumTipoFkProcedimento(){
    return $this->numTipoFkProcedimento;
  }
  
  public function setNumTipoFkProcedimento($numTipoFkProcedimento){
    $this->numTipoFkProcedimento = $numTipoFkProcedimento;
  }  

  public function getNumTipoFkTipoProcedimento(){
    return $this->numTipoFkTipoProcedimento;
  }
  
  public function setNumTipoFkTipoProcedimento($numTipoFkTipoProcedimento){
    $this->numTipoFkTipoProcedimento = $numTipoFkTipoProcedimento;
  }
  
	public function getNumTipoFkDocumento(){
    return $this->numTipoFkDocumento;
  }
  
	public function setNumTipoFkDocumento($numTipoFkDocumento){
    $this->numTipoFkDocumento = $numTipoFkDocumento;
  } 
  
}
?>