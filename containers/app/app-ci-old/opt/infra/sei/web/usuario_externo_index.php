<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 27/04/2007 - criado por MGA
 *
 */

  try {
    
    require_once dirname(__FILE__).'/SEI.php';
		
		session_start();
		
		
		//InfraDebug::getInstance()->setBolLigado(false);
		//InfraDebug::getInstance()->setBolDebugInfra(false);
		//InfraDebug::getInstance()->limpar();
		
		SessaoSEIExterna::getInstance()->validarLink();
		
  }catch(Exception $e){
  	PaginaSEIExterna::getInstance()->processarExcecao($e);
  }
  
	PaginaSEIExterna::getInstance()->montarDocType();
	PaginaSEIExterna::getInstance()->abrirHtml();
	PaginaSEIExterna::getInstance()->abrirHead();
	PaginaSEIExterna::getInstance()->montarMeta();
	PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema());
	PaginaSEIExterna::getInstance()->montarStyle();
	PaginaSEIExterna::getInstance()->montarJavaScript();
  PaginaSEIExterna::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  
    infraExibirMenuSistemaEsquema();
    
  }
<?
  PaginaSEIExterna::getInstance()->fecharJavaScript();
	PaginaSEIExterna::getInstance()->fecharHead();
	PaginaSEIExterna::getInstance()->abrirBody('','onload="inicializar();"');
	PaginaSEIExterna::getInstance()->abrirAreaDados('30em');
	PaginaSEIExterna::getInstance()->fecharAreaDados();
	//PaginaSEIExterna::getInstance()->abrirAreaTabela();
	//PaginaSEIExterna::getInstance()->fecharAreaTabela();
	//PaginaSEIExterna::getInstance()->montarAreaDebug();
	PaginaSEIExterna::getInstance()->fecharBody();
	PaginaSEIExterna::getInstance()->fecharHtml();
?>