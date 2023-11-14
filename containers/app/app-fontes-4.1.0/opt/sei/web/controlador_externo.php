<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
*
* 26/04/2012 - criado por MGA
*
*/

try {

	require_once dirname(__FILE__).'/SEI.php';

	session_start();

  SessaoSEIExterna::getInstance()->validarLink();

  ManutencaoSEI::validarInterface();

  global $SEI_MODULOS;

	infraTratarErroFatal(SessaoSEIExterna::getInstance(),'controlador_externo.php?acao=infra_erro_fatal_logar');

	switch($_GET['acao']) {

		case 'usuario_externo_principal':
			require_once 'usuario_externo_index.php';
			break;
	  
		case 'usuario_externo_logar':
			require_once 'usuario_externo_login.php';
			break;
	  
		case 'usuario_externo_sair':
			SessaoSEIExterna::getInstance()->sair();
			break;

		case 'usuario_externo_gerar_senha':
			require_once 'usuario_externo_geracao_senha.php';
			break;
			
		case 'usuario_externo_avisar_cadastro':	
		case 'usuario_externo_enviar_cadastro':
			require_once 'usuario_externo_formulario.php';
			break;
			
		case 'usuario_externo_controle_acessos':
			require_once 'usuario_externo_controle.php';
			break;
		  
		case 'usuario_externo_alterar_senha':
			require_once 'usuario_externo_alteracao_senha.php';
			break;

    case 'usuario_externo_documento_assinar':
      require_once 'usuario_externo_documento_assinar.php';
      break;

		case 'usuario_externo_assinar':
			require_once 'usuario_externo_assinar.php';
			break;
			
    case 'documento_conferir':
      require_once 'documento_validacao_externa.php';
      break;

    case 'ouvidoria':
      require_once 'formulario_ouvidoria.php';
      break;

    case 'usuario_externo_upload_documento':
    case 'usuario_externo_incluir_documento':
      require_once 'usuario_externo_upload.php';
      break;

		/*	
		case 'usuario_externo_ajuda':
			require_once 'usuario_externo_ajuda.php';
			break;
		*/
				
		default:
			foreach($SEI_MODULOS as $seiModulo){
				if ($seiModulo->executar('processarControladorExterno', $_GET['acao'])!=null){
					return;
				}
			}
			
		  if (!InfraControlador::processar($_GET['acao'], PaginaSEIExterna::getInstance(), SessaoSEIExterna::getInstance(), BancoSEI::getInstance())){
			  throw new InfraException('Aчуo \''.$_GET['acao'].'\' nуo reconhecida pelo controlador externo.');
		  }
  }

}catch(Throwable $e){
  PaginaSEIExterna::getInstance()->processarExcecao($e);
}
?>