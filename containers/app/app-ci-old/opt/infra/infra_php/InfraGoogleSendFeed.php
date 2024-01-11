<?php
 class InfraGoogleSendFeed{
 	static function Send( $strHost , $strContent ){
		$xmlContent = simplexml_load_string( $strContent );
 		$strTipo = (string)$xmlContent->header->feedtype;
  	$strName = (string)$xmlContent->header->datasource;
 		
 		$postfields = array(
                        	  'datasource' => $strName
							, 'feedtype'   => $strTipo
							, 'data'       => $xmlContent->asXML()
                    	);
		$urlPagina = 'http://'.$strHost.':19900/xmlfeed';
		
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL        , $urlPagina);
    curl_setopt($ch, CURLOPT_POST       , false);
    curl_setopt($ch, CURLOPT_POSTFIELDS , $postfields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    if (curl_exec($ch)===false){
      throw new Exception('Erro enviando feed ao Google Search Appliance (GSA): '.curl_error($ch));
    }
    curl_close($ch);		
		
	}
}
?>