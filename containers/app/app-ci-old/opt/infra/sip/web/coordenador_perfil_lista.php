<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
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

  if (isset($_GET['id_orgao_sistema']) && isset($_GET['id_sistema']) && isset($_GET['id_perfil'])){
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistema',$_GET['id_orgao_sistema']);
    PaginaSip::getInstance()->salvarCampo('selSistema',$_GET['id_sistema']);
    PaginaSip::getInstance()->salvarCampo('selPerfil',$_GET['id_perfil']);
  } else {  
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema','selPerfil','selOrgaoUsuario','hdnIdUsuario','txtUsuario','hdnNomeUsuario'));
  }

  
  switch($_GET['acao']){
    case 'coordenador_perfil_excluir':
		  try{

        $arrObjCoordenadorPerfilDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i=0;$i<count($arrStrId);$i++){
          $arrStrIdComposto = explode('#',$arrStrId[$i]);
          $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
          $objCoordenadorPerfilDTO->setNumIdPerfil($arrStrIdComposto[0]);
          $objCoordenadorPerfilDTO->setNumIdUsuario($arrStrIdComposto[1]);
          $objCoordenadorPerfilDTO->setNumIdSistema($arrStrIdComposto[2]);
          $arrObjCoordenadorPerfilDTO[] = $objCoordenadorPerfilDTO;
        }
        $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
        $objCoordenadorPerfilRN->excluir($arrObjCoordenadorPerfilDTO);

			}catch(Exception $e){
				PaginaSip::getInstance()->processarExcecao($e);
			}
      break;

    case 'coordenador_perfil_listar':
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	$arrComandos = array();
	
	$arrComandos[] = '<button type="submit" id="sbmPesquisar" name="sbmPesquisar" class="infraButton">Pesquisar</button>';
	
	if (SessaoSip::getInstance()->verificarPermissao('coordenador_perfil_cadastrar')){
		$arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_perfil_cadastrar').'\';" class="infraButton" />';
	}
	$objCoordenadorPerfilDTO = new CoordenadorPerfilDTO(true);
	$objCoordenadorPerfilDTO->retTodos();
	
	//ORGAO
	$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
	if ($numIdOrgao!==''){
		$objCoordenadorPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
	}
	
	//SISTEMA
	$numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
	if ($numIdSistema!==''){
		$objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
	}
	
	$numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario');
	if ($numIdOrgaoUsuario!=='null'){
	  $objCoordenadorPerfilDTO->setNumIdOrgaoUsuario($numIdOrgaoUsuario);
	}
	
  $numIdUsuario = PaginaSip::getInstance()->recuperarCampo('hdnIdUsuario');
  if ($numIdUsuario!==''){
    $objCoordenadorPerfilDTO->setNumIdUsuario($numIdUsuario);
  }

  $numIdPerfil = PaginaSip::getInstance()->recuperarCampo('selPerfil');
  if ($numIdPerfil!==''){
    $objCoordenadorPerfilDTO->setNumIdPerfil($numIdPerfil);
  }

  $strSiglaUsuario = PaginaSip::getInstance()->recuperarCampo('txtUsuario');
  $strNomeUsuario = PaginaSip::getInstance()->recuperarCampo('hdnNomeUsuario');
  
	
	PaginaSip::getInstance()->prepararOrdenacao($objCoordenadorPerfilDTO, 'SiglaUsuario', InfraDTO::$TIPO_ORDENACAO_ASC);			

  //die($objCoordenadorPerfilDTO->__toString());

	$objCoordenadorPerfilRN = new CoordenadorPerfilRN();
	$arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listarAdministrados($objCoordenadorPerfilDTO);

	$numRegistros = count($arrObjCoordenadorPerfilDTO);

	if ($numRegistros > 0){
		$bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('coordenador_perfil_excluir');
		//Montar ações múltiplas
		$bolCheck = false;
		if ($bolAcaoExcluir){
			$bolCheck = true;
			$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
			$strLinkExcluir = SessaoSip::getInstance()->assinarLink('coordenador_perfil_lista.php?acao=coordenador_perfil_excluir');
		}

		$arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
		
		$strResultado = '';
		$strResultado .= '<table width="90%" class="infraTable" summary="Tabela de Coordenadores de Perfis cadastrados">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Coordenadores de Perfis',$numRegistros).'</caption>';
		$strResultado .= '<tr>';
		if ($bolCheck) {
			$strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
		}
		//$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO,'Órgão Sistema','SiglaOrgaoSistema',$arrObjCoordenadorPerfilDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO,'Sistema','SiglaSistema',$arrObjCoordenadorPerfilDTO).'</th>';
		//$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO,'Órgão Usuário','SiglaOrgaoUsuario',$arrObjCoordenadorPerfilDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO,'Usuário','SiglaUsuario',$arrObjCoordenadorPerfilDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO,'Perfil','NomePerfil',$arrObjCoordenadorPerfilDTO).'</th>';
		
		$strResultado .= '<th class="infraTh">Ações</th>';
		$strResultado .= '</tr>'."\n";
		for($i = 0;$i < $numRegistros; $i++){
			if ( ($i+2) % 2 ) {
				$strResultado .= '<tr class="infraTrEscura">';
			} else {
				$strResultado .= '<tr class="infraTrClara">';
			}
			if ($bolCheck){
				$strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($i,$arrObjCoordenadorPerfilDTO[$i]->getNumIdPerfil().'#'.$arrObjCoordenadorPerfilDTO[$i]->getNumIdUsuario().'#'.$arrObjCoordenadorPerfilDTO[$i]->getNumIdSistema(),$arrObjCoordenadorPerfilDTO[$i]->getNumIdPerfil()).'</td>';
			}
			//$strResultado .= '<td align="center">'.$arrObjCoordenadorPerfilDTO[$i]->getStrSiglaOrgaoSistema().' / '.$arrObjCoordenadorPerfilDTO[$i]->getStrSiglaSistema().'</td>';
			//$strResultado .= '<td align="center">'.$arrObjCoordenadorPerfilDTO[$i]->getStrSiglaOrgaoUsuario().' / '.$arrObjCoordenadorPerfilDTO[$i]->getStrSiglaUsuario().'</td>';
			
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaSistema()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoOrgaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoOrgaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaOrgaoSistema()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaUsuario()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoOrgaoUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoOrgaoUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaOrgaoUsuario()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrNomePerfil()).'</td>';
			$strResultado .= '<td align="center">';

			if ($bolAcaoExcluir){
				$strResultado .= '<a onclick="acaoExcluir(\''.$arrObjCoordenadorPerfilDTO[$i]->getNumIdPerfil().'#'.$arrObjCoordenadorPerfilDTO[$i]->getNumIdUsuario().'#'.$arrObjCoordenadorPerfilDTO[$i]->getNumIdSistema().'\',\''.PaginaSip::formatarParametrosJavaScript($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaUsuario().' / '.$arrObjCoordenadorPerfilDTO[$i]->getStrNomePerfil()).'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeExcluir().'" title="Excluir Coordenador de Perfil" alt="Excluir Coordenador de Perfil" class="infraImg" /></a>&nbsp;';
			}

			$strResultado .= '</td></tr>'."\n";
		}
		$strResultado .= '</table>';
	}
	$arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" />';
  
  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null','&nbsp;', $numIdOrgao);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null','&nbsp;', $numIdSistema, $numIdOrgao);
  $strItensSelPerfil = PerfilINT::montarSelectSiglaAutorizados('','Todos',$numIdPerfil,$numIdSistema);
  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoUsuario);
  //$strItensSelUsuario = UsuarioINT::montarSelectSigla('','Todos',$numIdUsuario, $numIdOrgaoUsuario);
  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');
	
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Coordenadores de Perfis');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoSistema {position:absolute;left:0%;top:12%;width:25%;}

#lblSistema {position:absolute;left:0%;top:30%;width:25%;}
#selSistema {position:absolute;left:0%;top:42%;width:25%;}

#lblOrgaoUsuario {position:absolute;left:30%;top:0%;width:25%;}
#selOrgaoUsuario {position:absolute;left:30%;top:12%;width:25%;}

#lblUsuario {position:absolute;left:30%;top:30%;width:25%;}
#txtUsuario {position:absolute;left:30%;top:42%;width:25%;}
#lblNomeUsuario {position:absolute;left:60%;top:42%;width:30%;}

#lblPerfil {position:absolute;left:60%;top:0%;width:30%;}
#selPerfil {position:absolute;left:60%;top:12%;width:30%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxUsuario = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='coordenador_perfil_selecionar'){
    infraReceberSelecao();
  }
  
  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.carregando = true;
  objAjaxUsuario.prepararExecucao = function(){
    if (!infraSelectSelecionado('selOrgaoUsuario')){
      alert('Selecione Órgão do Usuário.');
      document.getElementById('selOrgaoUsuario').focus();
      return false;
    }
    return 'sigla='+document.getElementById('txtUsuario').value + '&idOrgao='+document.getElementById('selOrgaoUsuario').value;
  };

  objAjaxUsuario.processarResultado = function(id,descricao,complemento){
    
    document.getElementById('lblNomeUsuario').innerHTML = '';
    document.getElementById('hdnNomeUsuario').value = '';
    
    if (id != ''){
    
      document.getElementById('lblNomeUsuario').innerHTML = complemento;
      document.getElementById('hdnNomeUsuario').value = complemento;
        
      if (!this.carregando){
        document.getElementById('frmCoordenadorPerfilLista').submit();
      }
    }
  };
  
  objAjaxUsuario.selecionar('<?=$numIdUsuario;?>','<?=$strSiglaUsuario;?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strNomeUsuario,false)?>');
  objAjaxUsuario.carregando = false;
  
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoExcluir){ ?>
     function acaoExcluir(id,desc){
       if (confirm("Confirma exclusão do Coordenador de Perfil \""+desc+"\"?")){
         document.getElementById('hdnInfraItensSelecionados').value=id;
         document.getElementById('frmCoordenadorPerfilLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmCoordenadorPerfilLista').submit();
       }
     }

     function acaoExclusaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhum Coordenador de Perfil selecionado.');
         return;
       }
       if (confirm("Confirma exclusão dos Coordenadores de Perfis selecionados?")){
         document.getElementById('frmCoordenadorPerfilLista').action='<?=$strLinkExcluir?>';
         document.getElementById('frmCoordenadorPerfilLista').submit();
       }
     }
<? } ?>

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	document.getElementById('selPerfil').value='null';
	obj.form.submit();
}


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Coordenadores de Perfis','onload="inicializar();"');
?>
<form id="frmCoordenadorPerfilLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('coordenador_perfil_lista.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSip::getInstance()->montarBarraLocalizacao('Coordenadores de Perfis');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('15em');
  ?>
  
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span> do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>
	
  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelSistema?>
  </select>
  
  <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelOpcional">Órgã<span class="infraTeclaAtalho">o</span> do Usuário:</label>
  <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
  <?=$strItensSelOrgaoUsuario?>
  </select>
	
  <label id="lblUsuario" for="txtUsuario" accesskey="u" class="infraLabelOpcional"><span class="infraTeclaAtalho">U</span>suário:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=$strSiglaUsuario?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />
  <label id="lblNomeUsuario" class="infraLabelOpcional"></label>

  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$numIdUsuario?>" />
  <input type="hidden" id="hdnNomeUsuario" name="hdnNomeUsuario" value="<?=$strNomeUsuario?>" />
  
  <label id="lblPerfil" for="selPerfil" accesskey="P" class="infraLabelOpcional"><span class="infraTeclaAtalho">P</span>erfil:</label>
  <select id="selPerfil" name="selPerfil" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelPerfil?>
  </select>

  <?
  PaginaSip::getInstance()->fecharAreaDados();
  PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  //PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>