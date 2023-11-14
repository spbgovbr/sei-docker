<?
try{
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();
  
  SessaoSEI::getInstance(false);
  
  SessaoPublicacoes::getInstance()->validarLink();
  
  InfraAjax::decodificarPost();
  
  $xml = null;
  
  switch($_GET['acao_ajax']){
    
    case 'montar_unidades_pesquisa':
      if ($_POST['idOrgao']!=''){
        $arrOrgaos = explode(',',$_POST['idOrgao']);
      }else{
        $arrOrgaos = null;
      }      
      $strOptions = UnidadeINT::montarSelectSiglaDescricaoPesquisaPublicacao($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$arrOrgaos);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;   
      
    case 'montar_series_pesquisa':
      if ($_POST['idOrgao']!=''){
        $arrOrgaos = explode(',',$_POST['idOrgao']);
      }else{
        $arrOrgaos = null;
      }      
      $strOptions = SerieINT::montarSelectNomeDescricaoPesquisaPublicacao($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$arrOrgaos);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'secao_imprensa_nacional_montar_select_nome':
      $strOptions = SecaoImprensaNacionalINT::montarSelectNome($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idVeiculoImprensaNacional']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;
      
      
   default:
      throw new InfraException("Aчуo '".$_GET['acao_ajax']."' nуo reconhecida pelo controlador AJAX.");
  }

  InfraAjax::enviarXML($xml);

}catch(Throwable $e){
  InfraAjax::processarExcecao($e);
}
?>