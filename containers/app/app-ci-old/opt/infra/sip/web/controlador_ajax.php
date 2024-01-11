<?
try{
  require_once dirname(__FILE__).'/Sip.php';
  
  session_start();

  InfraAjax::decodificarPost();

  switch($_GET['acao_ajax']){

   case 'sistema_montar_select_sigla_administrados':
       $strOptions = SistemaINT::montarSelectSiglaAdministrados($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idOrgaoSistema']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  
    
   case 'sistema_montar_select_sigla_autorizados':  
       $strOptions = SistemaINT::montarSelectSiglaAutorizados($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idOrgaoSistema']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  

   case 'sistema_montar_select_sigla_sip':  
       $strOptions = SistemaINT::montarSelectSiglaSip($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idOrgaoSistema']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  
      
   case 'unidade_montar_select_sigla_autorizadas':  
       $strOptions = UnidadeINT::montarSelectSiglaAutorizadas($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idOrgaoUnidade'], $_POST['idSistema']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  

   case 'unidade_montar_select_sigla':  
       $strOptions = UnidadeINT::montarSelectSigla($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idOrgaoUnidade']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  
      
   case 'perfil_montar_select_sigla_autorizados':  
       $strOptions = PerfilINT::montarSelectSiglaAutorizados($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'], $_POST['idSistema'], $_POST['idUnidade']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  

   case 'perfil_montar_select_nome':  
       $strOptions = PerfilINT::montarSelectNome($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'], $_POST['idSistema']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  

   case 'rel_perfil_recurso_montar_select_nome_recurso':  
       $strOptions = RelPerfilRecursoINT::montarSelectNomeRecurso(null,null,null,$_POST['idPerfil'],'',$_POST['idSistema']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;  
      
    case 'usuario_auto_completar_sigla_nome':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarSiglaNome($_POST['sigla'],$_POST['idOrgao']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO,'IdUsuario','Sigla','Nome');
      break;

    case 'usuario_auto_completar':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletar($_POST['sigla'],$_POST['idOrgao']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO,'IdUsuario','Sigla');
      break;

    case 'recurso_auto_completar_nome':
      $arrObjRecursoDTO = RecursoINT::autoCompletarNome($_POST['nome'],$_POST['idSistema']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjRecursoDTO,'IdRecurso','Nome');
      break;

    case 'unidade_ramificacao_auto_completar':
      $arrObjRelHierarquiaUnidadeDTO = RelHierarquiaUnidadeINT::autoCompletarRamificacao($_POST['sigla'],$_POST['id_hierarquia']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjRelHierarquiaUnidadeDTO,'IdUnidade','SiglaUnidade');
      break;
    
    default:

      foreach($SIP_MODULOS as $objModulo){
        if (($xml = $objModulo->processarControladorAjax($_GET['acao_ajax']))!=null){
          break;
        }
      }

      if ($xml == null){
        throw new InfraException("Aчуo '".$_GET['acao_ajax']."' nуo reconhecida pelo controlador AJAX.");
      }
  }
  
  InfraAjax::enviarXML($xml);

}catch(Throwable $e){
  InfraAjax::processarExcecao($e);
}
?>