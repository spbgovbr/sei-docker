<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EmailINT extends InfraINT {

  
  public static function formatarNomeEmailRI0960($strSiglaOrgao,$strNome, $strEmail){
  	
  	if($strSiglaOrgao != null){
  		
  			$strSiglaOrgao = $strSiglaOrgao."/";
  		
  	}
  	
    $str = $strSiglaOrgao.$strNome.' &lt;';
    
    if (trim($strEmail)==''){
      $str .= 'e-mail não cadastrado';
    }else{
      $str .= $strEmail;
    }
    $str .= '&gt;';
    
    return $str;
  }
  
}
?>