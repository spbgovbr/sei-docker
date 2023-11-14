<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* ??/??/?? - criado por ??????
*
*/

try {
  
  //require_once 'Infra.php';
   
  session_start();
   
  SessaoInfra::getInstance()->validarLink();

  //InfraDebug::getInstance()->setBolLigado(true);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();

  if ($_GET['acao'] == 'infra_erro_fatal_logar'){
    $objInfraException = new InfraException($_POST['txaInfraErroFatal']);
    PaginaInfra::getInstance()->processarExcecao($objInfraException);
    die;
  }
      
} catch(Exception $e) {}

echo $_POST['txaInfraErroFatal'];  
?>