<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/09/2008 - criado por mga
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->setBolEcho(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);
  
  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema','selOrgaoUnidade','selUnidade','selPerfil','txaUsuarios'));

  $arrComandos = array();

  switch($_GET['acao']){
    case 'permissao_atribuir_em_bloco':
      $strTitulo = 'Atribuir Permissões em Bloco';
      
      $arrComandos[] = '<button type="submit" name="sbmAtribuir" value="Atribuir" class="infraButton">Atribuir</button>';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      
      $objPermissaoDTO = new PermissaoDTO();
      
			//ORGAO
			$numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
			if ($numIdOrgaoSistema!==''){
				$objPermissaoDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
			} else {
				$objPermissaoDTO->setNumIdOrgaoSistema(null);
			}

			//SISTEMA
			$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
			if ($numIdSistema!==''){
				$objPermissaoDTO->setNumIdSistema($numIdSistema);
			}else{
				$objPermissaoDTO->setNumIdSistema(null);
			}
			
			//ORGAO UNIDADE
			$numIdOrgaoUnidade = PaginaSip::getInstance()->recuperarCampo('selOrgaoUnidade',SessaoSip::getInstance()->getNumIdOrgaoSistema());
			if ($numIdOrgaoUnidade!==''){
				$objPermissaoDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);
			}else{
				$objPermissaoDTO->setNumIdOrgaoUnidade(null);
			}

			//UNIDADE
			$numIdUnidade = PaginaSip::getInstance()->recuperarCampo('selUnidade');
			if ($numIdUnidade!==''){
				$objPermissaoDTO->setNumIdUnidade($numIdUnidade);
			}else{
				$objPermissaoDTO->setNumIdUnidade(null);
			}

			//PERFIL
			$numIdPerfil = PaginaSip::getInstance()->recuperarCampo('selPerfil');
			if ($numIdPerfil!==''){
				$objPermissaoDTO->setNumIdPerfil($numIdPerfil);
			}else{
				$objPermissaoDTO->setNumIdPerfil(null);
			}
      
			$strUsuarios = PaginaSip::getInstance()->recuperarCampo('txaUsuarios');
			
      $objPermissaoRN = new PermissaoRN();
      
      if (isset($_POST['sbmAtribuir'])){
        
  			$arrObjUsuarioDTO = array();
  			if ($strUsuarios!=''){
  			  $arr1 = explode("\n",$strUsuarios);
  			  
  			  foreach($arr1 as $usuario){
  			    if (trim($usuario)!=''){
    			    $arr2 = explode('/',$usuario);
    			    $objUsuarioDTO = new UsuarioDTO();
    			    $objUsuarioDTO->setStrSigla($arr2[0]);
    			    $objUsuarioDTO->setStrSiglaOrgao($arr2[1]);
    			    if (isset($arr2[2])){
    			      $objUsuarioDTO->setStrIdOrigem($arr2[2]);
    			    }else{
    			      $objUsuarioDTO->setStrIdOrigem(null);
    			    }
    			    
    			    if (isset($arr2[3])){
    			      $objUsuarioDTO->setStrNome($arr2[3]);
    			    }else{
    			      $objUsuarioDTO->setStrNome(null);
    			    }
    			    
    			    $arrObjUsuarioDTO[] = $objUsuarioDTO;
  			    }
  			  }
  			}
  			
  			$objPermissaoDTO->setArrObjUsuarioDTO($arrObjUsuarioDTO);
        
  			try{
  			  
  			  InfraDebug::getInstance()->limpar();
  			  
          //PaginaSip::getInstance()->adicionarMensagem('Desabilitado',PaginaSip::$TIPO_MSG_AVISO);
          $arrObjPermissaoDTO = $objPermissaoRN->atribuirPermissoesBloco($objPermissaoDTO);
          
          if (count($arrObjPermissaoDTO)){
            PaginaSip::getInstance()->setStrMensagem('Número de permissões cadastradas: '.count($arrObjPermissaoDTO), InfraPagina::$TIPO_MSG_AVISO);
          }else{
            PaginaSip::getInstance()->setStrMensagem('Nenhuma nova permissão cadastrada.', InfraPagina::$TIPO_MSG_AVISO);
          }
          
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	$strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAutorizados('null','&nbsp;',$objPermissaoDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAutorizados('null','&nbsp;',$objPermissaoDTO->getNumIdSistema(), $objPermissaoDTO->getNumIdOrgaoSistema());
  
	$strItensSelOrgaoUnidade = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$objPermissaoDTO->getNumIdOrgaoUnidade());	
	
	//$strItensSelUnidade = UnidadeINT::montarSelectSigla('null','&nbsp;',$objPermissaoDTO->getNumIdUnidade(), $objPermissaoDTO->getNumIdOrgaoUnidade(), $objPermissaoDTO->getNumIdSistema() );
	$strItensSelUnidade = UnidadeINT::montarSelectSiglaAutorizadas('null','&nbsp;',$objPermissaoDTO->getNumIdUnidade(), $objPermissaoDTO->getNumIdOrgaoUnidade(), $objPermissaoDTO->getNumIdSistema() );
			
  $strLinkAjaxUnidades = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_montar_select_sigla_autorizadas');
  
  //$strItensSelPerfil = PerfilINT::montarSelectNome('null','&nbsp;',$objPermissaoDTO->getNumIdPerfil(), $objPermissaoDTO->getNumIdSistema());
  $strItensSelPerfil = PerfilINT::montarSelectSiglaAutorizados('null','&nbsp;',$objPermissaoDTO->getNumIdPerfil(), $objPermissaoDTO->getNumIdSistema(),$objPermissaoDTO->getNumIdUnidade());
  
  
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoSistema {position:absolute;left:0%;top:6%;width:25%;}

#lblSistema {position:absolute;left:0%;top:16%;width:40%;}
#selSistema {position:absolute;left:0%;top:22%;width:40%;}

#lblOrgaoUnidade {position:absolute;left:0%;top:32%;width:25%;}
#selOrgaoUnidade {position:absolute;left:0%;top:38%;width:25%;}

#lblUnidade {position:absolute;left:0%;top:48%;width:40%;}
#selUnidade {position:absolute;left:0%;top:54%;width:40%;}

#lblPerfil {position:absolute;left:0%;top:64%;width:40%;}
#selPerfil {position:absolute;left:0%;top:70%;width:40%;}

#lblUsuarios {position:absolute;left:45%;top:0%;width:50%;}
#txaUsuarios {position:absolute;left:45%;top:6%;width:50%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxUnidades = null;


function inicializar(){

  //COMBO DE UNIDADES
  objAjaxUnidades = new infraAjaxMontarSelectDependente('selOrgaoUnidade','selUnidade','<?=$strLinkAjaxUnidades?>');
  objAjaxUnidades.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoUnidade='+document.getElementById('selOrgaoUnidade').value + '&idSistema='+document.getElementById('selSistema').value;
  }
}

function OnSubmitForm() {
  return true;
}

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	trocarSistema(obj);
}

function trocarSistema(obj){
	document.getElementById('selPerfil').value='null';
	obj.form.submit();
}


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmDesenvolvimento" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('32em');
?>
<label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
<select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
<?=$strItensSelOrgaoSistema?>
</select>

<label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
<select id="selSistema" name="selSistema" onchange="trocarSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
<?=$strItensSelSistema?>
</select>

<label id="lblOrgaoUnidade" for="selOrgaoUnidade" accesskey="" class="infraLabelObrigatorio">Órgão da Unidade:</label>
<select id="selOrgaoUnidade" name="selOrgaoUnidade" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
<?=$strItensSelOrgaoUnidade?>
</select>

<label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>nidade:</label>
<select id="selUnidade" name="selUnidade" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
<?=$strItensSelUnidade?>
</select>

<label id="lblPerfil" for="selPerfil" accesskey="P" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">P</span>erfil:</label>
<select id="selPerfil" name="selPerfil" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
<?=$strItensSelPerfil?>
</select>

<label id="lblUsuarios" for="txaUsuarios" class="infraLabelObrigatorio">Sigla do Usuário/Sigla do Órgão:</label>
<textarea id="txaUsuarios" name="txaUsuarios" class="infraTextarea" rows="14"><?=PaginaSip::tratarHTML($strUsuarios)?></textarea>

<?
PaginaSip::getInstance()->fecharAreaDados();
//PaginaSip::getInstance()->montarAreaDebug();
//PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>