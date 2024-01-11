<?
try{
  require_once dirname(__FILE__).'/SEI.php';

  session_start();
  
	SessaoSEIExterna::getInstance()->validarLink();

	infraTratarErroFatal(SessaoSEIExterna::getInstance(),'controlador_externo.php?acao=infra_erro_fatal_logar');
  
  InfraAjax::decodificarPost();
  
  switch($_GET['acao_ajax']){

    case 'cidade_montar_select_id_cidade_nome':
      $strOptions = CidadeINT::montarSelectIdCidadeNome($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idUf']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   default:

     foreach($SEI_MODULOS as $seiModulo){
       if (($xml = $seiModulo->executar('processarControladorAjaxExterno', $_GET['acao_ajax']))!=null){
         break;
       }
     }

     if ($xml == null){
       throw new InfraException("Aчуo '".$_GET['acao_ajax']."' nуo reconhecida pelo controlador AJAX externo.");
     }
  }

  InfraAjax::enviarXML($xml);

}catch(Throwable $e){
  InfraAjax::processarExcecao($e);
}
?>