<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 26/08/2021 - criado por MGB29
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class RH {

  private static $instance = null;
  
 	public static function getInstance() { 
	    if (self::$instance == null) { 
        self::$instance = new RH();
	    } 
	    return self::$instance; 
	} 
 	 
  public function getWebService($strServico){
	  
    $objWS = null;

    if (ConfiguracaoSEI::getInstance()->isSetValor('RH', $strServico) && !InfraString::isBolVazia(ConfiguracaoSEI::getInstance()->getValor('RH', $strServico))) {

      $strWSDL = ConfiguracaoSEI::getInstance()->getValor('RH', $strServico);

      try {
        if (!@file_get_contents($strWSDL)) {
          throw new InfraException('Falha na leitura do arquivo WSDL ('.$strWSDL.')');
        }

        $objWS = new SoapClient($strWSDL, array('encoding' => 'ISO-8859-1'));

      } catch (Exception $e) {
        throw new InfraException('Falha na conexão com o sistema de RH.', $e);
      }
    }
	  
	  return $objWS;
  }
}
?>