<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
  
  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);
	
  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema','selPerfil','txtNomeRecurso'));

	if (isset($_POST['hdnFlag'])){
	  PaginaSip::getInstance()->salvarCampo('chkRecursosPerfil',(isset($_POST['chkRecursosPerfil']) ? $_POST['chkRecursosPerfil'] : ''));
	  PaginaSip::getInstance()->salvarCampo('chkVisualizarDescricao',(isset($_POST['chkVisualizarDescricao']) ? $_POST['chkVisualizarDescricao'] : ''));
	}
  
  $arrComandos = array();

  $objPerfilRN = new PerfilRN();
  
  switch($_GET['acao']){
    case 'perfil_montar':
      $strTitulo = 'Montar Perfil';
      $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';  
      $arrComandos[] = '<input type="submit" name="sbmMontarPerfil" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirDiv(\'divInfraAreaTabela\');" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

			if (isset($_POST['sbmMontarPerfil'])) {
				
			  $objPerfilDTO = new PerfilDTO();
			  
				$objPerfilDTO->setNumIdSistema($_POST['selSistema']);
        $objPerfilDTO->setNumIdPerfil($_POST['selPerfil']);

         
        //obtem todos os recursos exibidos
        $arrIdRecursos = explode(',',$_POST['hdnIdRecursos']);
        
        $arrObjRelPerfilRecursoDTO = array();
				foreach($arrIdRecursos as $numIdRecurso){
					$objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
					$objRelPerfilRecursoDTO->setNumIdRecurso($numIdRecurso);
					$objRelPerfilRecursoDTO->setStrSinPerfil('N');
					$arrObjRelPerfilRecursoDTO[] = $objRelPerfilRecursoDTO;
				}
        
        
        //obtem todos os itens de menu exibidos
        $arrIdItensMenu = explode(',',$_POST['hdnIdItensMenu']);
        $arrObjRelPerfilItemMenuDTO = array();
				foreach($arrIdItensMenu as $strIdComposto){
					$arrIdComposto = explode('-',$strIdComposto);
					$objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
					$objRelPerfilItemMenuDTO->setNumIdRecurso($arrIdComposto[0]);
					$objRelPerfilItemMenuDTO->setNumIdMenu($arrIdComposto[1]);
					$objRelPerfilItemMenuDTO->setNumIdItemMenu($arrIdComposto[2]);
					$objRelPerfilItemMenuDTO->setStrSinPerfil('N');
					$arrObjRelPerfilItemMenuDTO[] = $objRelPerfilItemMenuDTO;
				}
        
				$arrPosts = array_keys($_POST);
				
        //marca recursos selecionados
				foreach($arrPosts as $strPost){
					if (substr($strPost,0,strlen('chkRecurso_'))=='chkRecurso_'){
					  foreach($arrObjRelPerfilRecursoDTO as $objRelPerfilRecursoDTO){
					    if ($objRelPerfilRecursoDTO->getNumIdRecurso()==$_POST[$strPost]){
					      $objRelPerfilRecursoDTO->setStrSinPerfil('S');
					      break;
					    }
					  }
					}
				}
        $objPerfilDTO->setArrObjRelPerfilRecursoDTO($arrObjRelPerfilRecursoDTO);

        //marca itens de menu selecionados
				foreach($arrPosts as $strPost){
					if (substr($strPost,0,strlen('chkMenu_'))=='chkMenu_'){
						$arrIdComposto = explode('-',$_POST[$strPost]);
						foreach($arrObjRelPerfilItemMenuDTO as $objRelPerfilItemMenuDTO){
						  if ($objRelPerfilItemMenuDTO->getNumIdRecurso()==$arrIdComposto[0] && $objRelPerfilItemMenuDTO->getNumIdMenu()==$arrIdComposto[1] && $objRelPerfilItemMenuDTO->getNumIdItemMenu()==$arrIdComposto[2]){
						    $objRelPerfilItemMenuDTO->setStrSinPerfil('S');
						    break;
						  }
						}
					}
				}
        $objPerfilDTO->setArrObjRelPerfilItemMenuDTO($arrObjRelPerfilItemMenuDTO);
				
        $objPerfilRN->montar($objPerfilDTO);
        
				PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }

      $objPerfilDTO = new PerfilDTO();
      
			//Consultar dados
			if (isset($_GET['id_sistema']) && isset($_GET['id_perfil'])){
			  
			
				$objPerfilDTO->retNumIdOrgaoSistema();
				$objPerfilDTO->retNumIdSistema();
				$objPerfilDTO->retNumIdPerfil();
				$objPerfilDTO->setNumIdSistema($_GET['id_sistema']);
				$objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);
				
				$objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);
			}else{
      
				//ORGAO
				$numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
				if ($numIdOrgaoSistema!==''){
					$objPerfilDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
				} else {
					$objPerfilDTO->setNumIdOrgaoSistema(null);
				}
	
				//SISTEMA
				$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
				if ($numIdSistema!==''){
					$objPerfilDTO->setNumIdSistema($numIdSistema);
				}else{
					$objPerfilDTO->setNumIdSistema(null);
				}
				
				//PERFIL
				$numIdPerfil = PaginaSip::getInstance()->recuperarCampo('selPerfil');
				if ($numIdPerfil!==''){
					$objPerfilDTO->setNumIdPerfil($numIdPerfil);
				}else{
					$objPerfilDTO->setNumIdPerfil(null);
				}
				
        $strNomePesquisa = PaginaSip::getInstance()->recuperarCampo('txtNomeRecurso');
        if ($strNomePesquisa!==''){
          $objPerfilDTO->setStrNomeRecurso($strNomePesquisa);
        }
			}	
			
    	$bolListar=false;
    	$numMenus = 0;
    	//Verifica se o perfil pertence ao sistema 
    	if ($objPerfilDTO->getNumIdSistema()!=null && $objPerfilDTO->getNumIdPerfil()!=null){  

    	  if ($objPerfilRN->contar($objPerfilDTO)>0){
    	    
    	    $objMenuDTO = new MenuDTO();
    	    $objMenuDTO->setBolExclusaoLogica(false);
    	    $objMenuDTO->setNumIdSistema($numIdSistema);
    	    
    	    $objMenuRN = new MenuRN();
    	    $numMenus = $objMenuRN->contar($objMenuDTO);
    	    
    	    $bolListar = true;
    	  }
    	}
			
    	$objPerfilDTO->setStrSinVisualizarProprios(PaginaSip::getInstance()->getCheckBox(PaginaSip::getInstance()->recuperarCampo('chkRecursosPerfil')));
    	$objPerfilDTO->setStrSinVisualizarDescricao(PaginaSip::getInstance()->getCheckBox(PaginaSip::getInstance()->recuperarCampo('chkVisualizarDescricao')));
    	
    	
			$numIndiceCheckRecurso = 0;
			$numIndiceCheckMenu = 0;

      $strIdRecursos = '';
      $strIdItensMenu = '';
			
      if ($bolListar){  
        
        PaginaSip::getInstance()->prepararPaginacao($objPerfilDTO,100);
        
        $arrObjRecursoDTO = $objPerfilRN->listarMontar($objPerfilDTO);
        
        PaginaSip::getInstance()->processarPaginacao($objPerfilDTO);
        
				$numRegistros = count($arrObjRecursoDTO);
			
				if ($numRegistros > 0){
					
				  
					$strResultado = '';
					$strResultado .= '<table width="90%" class="infraTable" summary="Tabela de Recursos">'."\n";
					$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Recursos', $numRegistros).'</caption>';
					$strResultado .= '<tr>';
					$strResultado .= '<th class="infraTh" valign="top" width="25%"><div style="padding:.2em;">Nome</div></th>';
					
					if ($objPerfilDTO->getStrSinVisualizarDescricao()=='S'){
					  $strResultado .= '<th class="infraTh" valign="top" width="25%"><div style="padding:.2em;">Descrição</div></th>';
					}
					  
					$strResultado .= '<th class="infraTh" width="15%" valign="top"><div style="padding:.2em;"><div style="float:left;"><a onclick="selecaoMultiplaRecursos();" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'">&nbsp;<img src="'.PaginaSip::getInstance()->getIconeCheck().'"id="imgInfraCheckRecursos" title="Selecionar Tudo" alt="Selecionar Tudo" class="infraImg" /></a></div><div style="float:left;display:inline;padding:.2em;">&nbsp;Perfil</div></div></th>';
					$strResultado .= '<th class="infraTh" valign="top"><div style="padding:.2em;"><div style="float:left;"><a onclick="selecaoMultiplaMenus();" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'">&nbsp;<img src="'.PaginaSip::getInstance()->getIconeCheck().'" id="imgInfraCheckMenus" title="Selecionar Tudo" alt="Selecionar Tudo" class="infraImg" /></a></div><div style="float:left;display:inline;padding:.2em;">&nbsp;Menu</div></div></th>';
					$strResultado .= '</tr>'."\n";
					
					for($i = 0;$i < $numRegistros; $i++){

            if ($strIdRecursos != ''){
              $strIdRecursos .= ',';
            }
            
            $strIdRecursos .= $arrObjRecursoDTO[$i]->getNumIdRecurso();

					  if ($arrObjRecursoDTO[$i]->getStrSinAtivo()=='S'){
						  if ( ($i+2) % 2 ) {
							  $strResultado .= '<tr class="infraTrEscura">';
						  } else {
							  $strResultado .= '<tr class="infraTrClara">';
						  }
            }else{
              $strResultado .= '<tr class="trVermelha">';
            }
						
						$strResultado .= '<td valign="top">'.PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrNome()).'</td>';
						
						if ($objPerfilDTO->getStrSinVisualizarDescricao()=='S'){
						  $strResultado .= '<td valign="top">'.PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrDescricao()).'</td>';
						}
						
						$strResultado .= '<td valign="top"><input '.(($arrObjRecursoDTO[$i]->getStrSinPerfil()=='S') ? ' checked="checked"' : '').' type="checkbox" id="chkRecurso_'.$numIndiceCheckRecurso.'" name="chkRecurso_'.$numIndiceCheckRecurso.'" onclick="selecionarMenu(this);" class="infraCheckbox" value="'.$arrObjRecursoDTO[$i]->getNumIdRecurso().'" title="'.PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrNome()).'" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'" /></td>';
						$numIndiceCheckRecurso++;
						
						
						$arrItensDeMenuDoRecurso = $arrObjRecursoDTO[$i]->getArrObjItemMenuDTO();
						
						$strResultado .= '<td valign="top">';
						if (count($arrItensDeMenuDoRecurso)>0){
						  
							$strCheckMenus = '';
							//Se o recurso tem itens de menu associados monta check para cada um
							for($j=0;$j<count($arrItensDeMenuDoRecurso);$j++){
							  
  						  if ($strIdItensMenu != ''){
  						    $strIdItensMenu .= ',';
  						  }
  						  
  						  $strIdItemMenu = $arrItensDeMenuDoRecurso[$j]->getNumIdRecurso().'-'.$arrItensDeMenuDoRecurso[$j]->getNumIdMenu().'-'.$arrItensDeMenuDoRecurso[$j]->getNumIdItemMenu();
  						  $strIdItensMenu .= $strIdItemMenu;
							  
								if ($strCheckMenus!=''){
									$strCheckMenus .= '<br />';
								}
								$strCheckMenus .= '<input '.(($arrItensDeMenuDoRecurso[$j]->getStrSinPerfil()=='S') ? ' checked="checked"' : '').' '.(($arrObjRecursoDTO[$i]->getStrSinPerfil()=='N') ? ' disabled="disabled"' : '').' type="checkbox" id="chkMenu_'.$numIndiceCheckMenu.'" name="chkMenu_'.$numIndiceCheckMenu.'" class="infraCheckbox" value="'.$strIdItemMenu.'" title="'.PaginaSip::tratarHTML($arrObjRecursoDTO[$i]->getStrNome()).'" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'" />';
								
								if ($numMenus > 1){
								  $strCheckMenus .=  ' ['.PaginaSip::tratarHTML($arrItensDeMenuDoRecurso[$j]->getStrNomeMenu()).']';
								}
								
								$strCheckMenus .=  ' '.PaginaSip::tratarHTML($arrItensDeMenuDoRecurso[$j]->getStrRamificacao());
								$numIndiceCheckMenu++;
							}
							$strResultado .= $strCheckMenus;
						} else {
							$strResultado .= '&nbsp;';
						}
						$strResultado .= '</td>';
						$strResultado .= '</tr>'."\n";
					}
				}
				$strResultado .= '</table>';
      }
      

      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem=perfil_montar'.PaginaSip::getInstance()->montarAncora($objPerfilDTO->getNumIdPerfil().'-'.$objPerfilDTO->getNumIdSistema())).'\';" class="infraButton" />';
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	$strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;',$objPerfilDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;',$objPerfilDTO->getNumIdSistema(), $objPerfilDTO->getNumIdOrgaoSistema());
  $strItensSelPerfil = PerfilINT::montarSelectNome('null','&nbsp;',$objPerfilDTO->getNumIdPerfil(), $objPerfilDTO->getNumIdSistema());

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Montar Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoSistema {position:absolute;left:0%;top:15%;width:25%;}

#lblSistema {position:absolute;left:0%;top:40%;width:25%;}
#selSistema {position:absolute;left:0%;top:55%;width:25%;}

#lblPerfil {position:absolute;left:33%;top:0%;width:40%;}
#selPerfil {position:absolute;left:33%;top:15%;width:40%;}

#lblNomeRecurso {position:absolute;left:33%;top:40%;width:39%;}
#txtNomeRecurso {position:absolute;left:33%;top:55%;width:39%;}

#divRecursosPerfil {position:absolute;left:0%;top:80%;}

#divVisualizarDescricao {position:absolute;left:33%;top:80%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  
  if (!validarForm()){
    return false;
  }
  
  return true;
}

function validarForm(){
  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
    alert('Selecione um Órgão do Sistema.');
    document.getElementById('selOrgaoSistema').focus();
    return false;
  }
	
  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
    alert('Selecione um Sistema.');
    document.getElementById('selSistema').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selPerfil'))) {
    alert('Selecione um Perfil.');
    document.getElementById('selPerfil').focus();
    return false;
  }
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

function selecionarMenu(obj){
	for (m=0; m < <?=$numIndiceCheckMenu?>; m++) {
	  boxMenu = document.getElementById('chkMenu_'+m);
	  if (boxMenu.title==obj.title){
			if (obj.checked==false){
					boxMenu.checked = false;
					boxMenu.disabled = true;
			}else{
					boxMenu.disabled = false;
			}
			//break;
		}
	}

}

function selecaoMultiplaRecursos() {
  infraCheckRecursos = document.getElementById('imgInfraCheckRecursos');

  for (i=0; i < <?=$numIndiceCheckRecurso?>; i++) {
    boxRecurso = document.getElementById('chkRecurso_'+i);
		if (!boxRecurso.disabled){
			if (infraCheckRecursos.title == 'Selecionar Tudo') {
				boxRecurso.checked = true;
			} else {
				boxRecurso.checked = false;
			}
		}
		selecionarMenu(boxRecurso);
  }
  if (infraCheckRecursos.title == 'Selecionar Tudo') {
    infraCheckRecursos.title = 'Remover Seleção';
    infraCheckRecursos.alt = 'Remover Seleção';
  }
  else {  
    infraCheckRecursos.title = 'Selecionar Tudo';
    infraCheckRecursos.alt = 'Selecionar Tudo';
  }
  
}


function selecaoMultiplaMenus() {
  infraCheckMenus = document.getElementById('imgInfraCheckMenus');

  for (i=0; i < <?=$numIndiceCheckMenu?>; i++) {
    boxMenu = document.getElementById('chkMenu_'+i);
		if (!boxMenu.disabled){
			if (infraCheckMenus.title == 'Selecionar Tudo') {
				boxMenu.checked = true;
			} else {
				boxMenu.checked = false;
			}
		}
  }
  if (infraCheckMenus.title == 'Selecionar Tudo') {
    infraCheckMenus.title = 'Remover Seleção';
    infraCheckMenus.alt = 'Remover Seleção';
  }
  else {  
    infraCheckMenus.title = 'Selecionar Tudo';
    infraCheckMenus.alt = 'Selecionar Tudo';
  }
  
}


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPerfilMontar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('perfil_montar.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('13em');
?>
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="trocarSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelSistema?>
  </select>

  <label id="lblPerfil" for="selPerfil" accesskey="P" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">P</span>erfil:</label>
  <select id="selPerfil" name="selPerfil" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
  <?=$strItensSelPerfil?>
  </select>
	
  <label id="lblNomeRecurso" for="txtNomeRecurso" accesskey="o" class="infraLabelOpcional">Recurs<span class="infraTeclaAtalho">o</span>:</label>
  <input type="text" id="txtNomeRecurso" name="txtNomeRecurso" class="infraText" value="<?=PaginaSip::tratarHTML($strNomePesquisa)?>" maxlength="50" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
  <div id="divRecursosPerfil" class="infraDivCheckbox">
    <input type="checkbox" id="chkRecursosPerfil" name="chkRecursosPerfil" onclick="this.form.submit();" class="infraCheckbox" <?=PaginaSip::getInstance()->setCheckBox($objPerfilDTO->getStrSinVisualizarProprios())?> <?=$strDesabilitar?>/>
	  <label id="lblRecursosPerfil" for="chkRecursosPerfil" class="infraLabelCheckbox">Visualizar Somente Recursos do Perfil</label>
  </div>
  
  <div id="divVisualizarDescricao" class="infraDivCheckbox">
  	<input type="checkbox" id="chkVisualizarDescricao" name="chkVisualizarDescricao" onclick="this.form.submit();" class="infraCheckbox" <?=PaginaSip::getInstance()->setCheckBox($objPerfilDTO->getStrSinVisualizarDescricao())?> <?=$strDesabilitar?>/>
	  <label id="lblVisualizarDescricao" for="chkVisualizarDescricao" class="infraLabelCheckbox">Visualizar Descrição do Recurso</label>
	</div>
	
	<input type="hidden" id="hdnFlag" name="hdnFlag" value="1" />
	<input type="hidden" id="hdnIdRecusos" name="hdnIdRecursos" value="<?=$strIdRecursos?>" />
	<input type="hidden" id="hdnIdItensMenu" name="hdnIdItensMenu" value="<?=$strIdItensMenu?>" />
	
<?
  PaginaSip::getInstance()->fecharAreaDados();
	//echo $strResultado;
	PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>