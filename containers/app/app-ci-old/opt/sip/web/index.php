<?
  try {
    
    require_once dirname(__FILE__).'/Sip.php';
		
		session_start();
		
		
		//InfraDebug::getInstance()->setBolLigado(false);
		//InfraDebug::getInstance()->setBolDebugInfra(true);
		//InfraDebug::getInstance()->limpar();
		
    SessaoSip::getInstance();
    
    if (isset($_GET['infra_sip']) && $_GET['infra_sip']=='true'){
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=principal'));
      die;
    }
			
  }catch(Exception $e){
  	PaginaSip::getInstance()->processarExcecao($e);
  }
  
	PaginaSip::getInstance()->montarDocType();
	PaginaSip::getInstance()->abrirHtml();
	PaginaSip::getInstance()->abrirHead();
	PaginaSip::getInstance()->montarMeta();
	PaginaSip::getInstance()->montarTitle('Sistema de Permissões');
	PaginaSip::getInstance()->montarStyle();
	PaginaSip::getInstance()->montarJavaScript();
	PaginaSip::getInstance()->fecharHead();
	PaginaSip::getInstance()->abrirBody();
	PaginaSip::getInstance()->abrirAreaDados('30em');
	PaginaSip::getInstance()->fecharAreaDados();
	PaginaSip::getInstance()->abrirAreaTabela();
	
	//echo $strPermissoes.'<br />';
	//echo '<br />'; 
	//echo $strMenu.'<br />';
	
	//echo $strObjectId;
	//echo $str;
	PaginaSip::getInstance()->fecharAreaTabela();
	
	//PaginaSip::getInstance()->montarAreaDebug();
	PaginaSip::getInstance()->fecharBody();
	PaginaSip::getInstance()->fecharHtml();
?>
