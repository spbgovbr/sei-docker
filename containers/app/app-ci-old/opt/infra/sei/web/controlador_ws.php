<?php
  require_once dirname(__FILE__).'/SEI.php';
  
	session_start();
	
	SessaoSEI::getInstance(false);
	
	$strArq = null;
	
  switch ($_GET['servico']) {

    case 'sei':
      $strArq .= 'ws/sei.wsdl';
      break;

    case 'federacao':
      $strArq .= 'ws/federacao.wsdl';
      break;

    case 'sip':
      $strArq .= 'ws/sei_sip.wsdl';
      break;    

    case 'assinador':
      $strArq .= 'ws/assinador.wsdl';
      break;

    default:
    	
      foreach($SEI_MODULOS as $objModulo){
        if (($strArq = $objModulo->executar('processarControladorWebServices', $_GET['servico']))!=null){
          break;
        }
      }

    	if ($strArq == null){
        die('Serviço ['.$_GET['servico'].'] inválido.');
    	}
  }

  InfraPagina::montarHeaderDownload($strArq);

  $handle = fopen($strArq, "r");
  $strWsdl = fread($handle, filesize($strArq));
  fclose($handle);
  
  $strServidor = ConfiguracaoSEI::getInstance()->getValor('SEI','URL');


  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){
    $strServidor = str_replace('http://','https://',$strServidor);
  }else{
    $strServidor = str_replace('https://','http://',$strServidor);
  }

    
  echo str_replace('[servidor]', $strServidor, $strWsdl);
?>