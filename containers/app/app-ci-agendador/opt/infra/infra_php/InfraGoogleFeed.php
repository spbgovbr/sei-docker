<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4™ REGI√O
 * 
 * 17/05/2011 - criado por MGA
 *
 * @package infra_php
 */

abstract class InfraGoogleFeed implements InfraIFeed{

	private $_strName;
	private $_objFeedXml;
	
	
	public function __construct(){
		$this->limpar();
	}
	
	public abstract function getStrServidor();
	public abstract function getStrFonte();
	
	public function limpar(){
		$this->_strName = $this->getStrFonte();
		unset($this->_objFeedXml);
		$this->_objFeedXml = null;
	}
	
	public function adicionar(InfraFeedDTO $objInfraFeedDTO){
		
		if ($this->_objFeedXml == null){
			$this->_objFeedXml = new InfraGoogleFeedXML( );
			$this->_objFeedXml->setFeed( $this->_strName , 'incremental' );
			$this->_objFeedXml->addGroup( 'add' );
		}
		
		$this->_objFeedXml->addRecord( $objInfraFeedDTO->getStrUrl() , $objInfraFeedDTO->getStrMimeType() , '' , false );
	
		if( $objInfraFeedDTO->getArrMetaTags() ){
			$this->_objFeedXml->addMetadata( );
			foreach ( $objInfraFeedDTO->getArrMetaTags() as $strKey => $strValue ){
			  
			  $strValue = trim($strValue);
			  
				if( $strValue != '' ){
				  if (substr($strKey,0,4) == 'dta_'){
				    $this->_objFeedXml->addMetatag( $strKey , $this->formatarDta($strValue));
				  }else{
				    $this->_objFeedXml->addMetatag( $strKey , $strValue );
				  }
				}
			}
		}
		
		if( $objInfraFeedDTO->getBinConteudo()!==null ) {
			 $this->_objFeedXml->addCdata64(base64_encode($objInfraFeedDTO->getBinConteudo()));
		}
	}
	
	public function remover(InfraFeedDTO $objInfraFeedDTO){
		
		if ($this->_objFeedXml == null){
			$this->_objFeedXml = new InfraGoogleFeedXML( );
			$this->_objFeedXml->setFeed( $this->_strName , 'incremental' );
			$this->_objFeedXml->addGroup( 'delete' );
		}
		
		$this->_objFeedXml->addRecord( $objInfraFeedDTO->getStrUrl() , $objInfraFeedDTO->getStrMimeType() , '' , false );
	}
	
  public function indexar(){
  	
  	if ($this->_objFeedXml != null){
  		
			$xmlContent = simplexml_load_string( $this->getStrFeed() );
	 		$strTipo = (string)$xmlContent->header->feedtype;
	  	$strName = (string)$xmlContent->header->datasource;
	 		
	 		$postfields = array(
	                        	  'datasource' => $strName
								, 'feedtype'   => $strTipo
								, 'data'       => $xmlContent->asXML()
	                    	);
			$urlPagina = $this->getStrServidor();
			
			//print_r($postfields);die;
			
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL        , $urlPagina);
	    curl_setopt($ch, CURLOPT_POST       , false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS , $postfields);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FAILONERROR, true);
	    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml","Charset: UTF-8"));
	
	    //die($urlPagina);
	    
	    if (curl_exec($ch)===false){
	      throw new InfraException('Erro enviando feed ao Google Search Appliance (GSA): '.curl_error($ch));
	    }
	    curl_close($ch);

	    $this->limpar();
  	}	
	}

	function getStrFeed(){
		$ret = null;
		if ($this->_objFeedXml!=null){
		  $ret = $this->_objFeedXml->getXMLFeed( );
		}
		return $ret;
	}
	
	public function formatarDta($dta){
		// Passa a data para o formato do GSA
		// De: 21/07/2010 Para: 2010-07-21
		return substr($dta,6,4).'-'.substr($dta,3,2).'-'.substr($dta,0,2);
	}	
}
?>