<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 12/11/2007 - criado por MGA
 *
 */

  try {
    
    require_once dirname(__FILE__).'/SEI.php';
		
		session_start();
		
		
		//InfraDebug::getInstance()->setBolLigado(false);
		//InfraDebug::getInstance()->setBolDebugInfra(false);
		//InfraDebug::getInstance()->limpar();
		
		SessaoSEI::getInstance();
		
  }catch(Exception $e){
  	PaginaSEI::getInstance()->processarExcecao($e);
  }
  
	PaginaSEI::getInstance()->montarDocType();
	PaginaSEI::getInstance()->abrirHtml();
	PaginaSEI::getInstance()->abrirHead();
	PaginaSEI::getInstance()->montarMeta();
	PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema());
	PaginaSEI::getInstance()->montarStyle();
	PaginaSEI::getInstance()->montarJavaScript();
  PaginaSEI::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  
   // infraExibirMenuSistemaEsquema();
    
  }
<?
  PaginaSEI::getInstance()->fecharJavaScript();
	PaginaSEI::getInstance()->fecharHead();
	PaginaSEI::getInstance()->abrirBody('','onload="inicializar();"');
	PaginaSEI::getInstance()->abrirAreaDados('30em');
	PaginaSEI::getInstance()->fecharAreaDados();
	//PaginaSEI::getInstance()->abrirAreaTabela();
	//PaginaSEI::getInstance()->fecharAreaTabela();
	//PaginaSEI::getInstance()->montarAreaDebug();
	PaginaSEI::getInstance()->fecharBody();
	PaginaSEI::getInstance()->fecharHtml();
?>