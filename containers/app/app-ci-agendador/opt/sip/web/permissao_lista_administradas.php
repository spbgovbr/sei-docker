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
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);
  
  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema','selSistema','selOrgaoUnidade','selUnidade','selOrgaoUsuario','txtUsuario','hdnIdUsuario', 'hdnSiglaUsuario', 'hdnNomeUsuario', 'selPerfil'));

  switch($_GET['acao']){
    case 'permissao_excluir':
		  try{

				$arrObjPermissaoDTO = array();
				$arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
				for ($i=0;$i<count($arrStrIds);$i++){
					$arrStrIdComposto = explode('-',$arrStrIds[$i]);
					$objPermissaoDTO = new PermissaoDTO();
					$objPermissaoDTO->setNumIdPerfil($arrStrIdComposto[0]);
					$objPermissaoDTO->setNumIdSistema($arrStrIdComposto[1]);
					$objPermissaoDTO->setNumIdUsuario($arrStrIdComposto[2]);
					$objPermissaoDTO->setNumIdUnidade($arrStrIdComposto[3]);
					$arrObjPermissaoDTO[] = $objPermissaoDTO;
				}
				$objPermissaoRN = new PermissaoRN();
				$objPermissaoRN->excluir($arrObjPermissaoDTO);

			}catch(Exception $e){
				PaginaSip::getInstance()->processarExcecao($e);
			}

			header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
			die;

    case 'permissao_listar_administradas':
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
	
	$arrComandos = array();
	
	$arrComandos[] = '<button type="submit" id="sbmPesquisar" name="sbmPesquisar" class="infraButton">Pesquisar</button>';
	
	if (SessaoSip::getInstance()->verificarPermissao('permissao_cadastrar')){
		$arrComandos[] = '<input type="button" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_cadastrar&acao_origem=permissao_listar_administradas&acao_retorno=permissao_listar_administradas').'\';" class="infraButton" />';
	}

	$objPermissaoDTO = new PermissaoDTO();
	$objPermissaoDTO->retStrNomeUsuario();
	$objPermissaoDTO->retStrSiglaUsuario();
	$objPermissaoDTO->retStrSiglaOrgaoUsuario();
	$objPermissaoDTO->retStrDescricaoOrgaoUsuario();
	$objPermissaoDTO->retStrSiglaSistema();
	$objPermissaoDTO->retStrSiglaOrgaoSistema();
	$objPermissaoDTO->retStrSiglaUnidade();
	$objPermissaoDTO->retStrDescricaoUnidade();
	$objPermissaoDTO->retStrSiglaOrgaoUnidade();
	$objPermissaoDTO->retStrDescricaoOrgaoUnidade();
	$objPermissaoDTO->retStrNomePerfil();
	$objPermissaoDTO->retDtaDataFim();
	$objPermissaoDTO->retDtaDataInicio();
  $objPermissaoDTO->retStrSinSubunidades();


	//ORGAO SISTEMA
	$numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema',SessaoSip::getInstance()->getNumIdOrgaoSistema());
	if ($numIdOrgaoSistema!==''){
	  $objPermissaoDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
	  $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema','null');
	} else {
 		$strDesabilitarSistema = 'disabled="disabled"';
	  $numIdSistema = '';
	}

	if ($numIdSistema !== ''){
		$objPermissaoDTO->setNumIdSistema($numIdSistema);
	}else{
		$objPermissaoDTO->setNumIdSistema(null);
	}
		
	if ($objPermissaoDTO->getNumIdSistema()==null){
		$strDesabilitarUnidade = 'disabled="disabled"';
		//$strDesabilitarUsuario = 'disabled="disabled"';
		$strDesabilitarPerfil = 'disabled="disabled"';
	}
	
	//ORGAO UNIDADE
	$numIdOrgaoUnidade = PaginaSip::getInstance()->recuperarCampo('selOrgaoUnidade','null');
	if ($numIdOrgaoUnidade!=='null'){
	  $objPermissaoDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);
    $numIdUnidade = PaginaSip::getInstance()->recuperarCampo('selUnidade');
	}else{
	  $numIdUnidade = 'null';
	}

	if ($numIdUnidade!='' && $numIdUnidade!=='null'){
		$objPermissaoDTO->setNumIdUnidade($numIdUnidade);
	}
	

  $numIdUsuario = PaginaSip::getInstance()->recuperarCampo('hdnIdUsuario');
  $strSiglaUsuario = PaginaSip::getInstance()->recuperarCampo('hdnSiglaUsuario');
  $strNomeUsuario = PaginaSip::getInstance()->recuperarCampo('hdnNomeUsuario');

	//ORGAO USUARIO
	$numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario','null');
	if ($numIdOrgaoUsuario!=='null'){
	  $objPermissaoDTO->setNumIdOrgaoUsuario($numIdOrgaoUsuario);
	}else{
	  $numIdUsuario = '';
	  $strSiglaUsuario = '';
	}

	if ($numIdUsuario!=''){
		$objPermissaoDTO->setNumIdUsuario($numIdUsuario);
	}
	
	//PERFIL
  $numIdPerfil = PaginaSip::getInstance()->recuperarCampo('selPerfil','null');
  if ($numIdPerfil!='' && $numIdPerfil!='null') {
    $objPermissaoDTO->setNumIdPerfil($numIdPerfil);
  }

	//die($objPermissaoDTO->__toString());
	
	PaginaSip::getInstance()->prepararOrdenacao($objPermissaoDTO, 'SiglaUsuario', InfraDTO::$TIPO_ORDENACAO_ASC);			

	PaginaSip::getInstance()->prepararPaginacao($objPermissaoDTO);

	$objPermissaoRN = new PermissaoRN();
	$arrObjPermissaoDTO = $objPermissaoRN->listarAdministradas($objPermissaoDTO);

	PaginaSip::getInstance()->processarPaginacao($objPermissaoDTO);

	$numRegistros = count($arrObjPermissaoDTO);


	if ($numRegistros > 0){
		$bolAcaoCopiar = SessaoSip::getInstance()->verificarPermissao('permissao_copiar');
		$bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('permissao_consultar');
		$bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('permissao_alterar');
		$bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('permissao_excluir');
		
		//Montar ações múltiplas
		$bolCheck = false;
		if ($bolAcaoCopiar){
			$bolCheck = true;
			$arrComandos[] = '<input type="button" id="btnCopiar" value="Copiar" onclick="acaoCopiaMultipla();" class="infraButton" />';
			$strLinkCopiar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_copiar&acao_origem=permissao_listar_administradas&acao_retorno=permissao_listar_administradas');
		}
		
		if ($bolAcaoExcluir){
			$bolCheck = true;
			$arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
			$strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_excluir&acao_origem=permissao_listar_administradas');
		}

		
		$arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';
		
		$strResultado = '';
		$strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Permissões Administradas cadastradas">'."\n";
		$strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Permissões Administradas',$numRegistros).'</caption>';
		$strResultado .= '<tr>';
		if ($bolCheck) {
			$strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>';
		}
		
		//$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO,'Sistema','SiglaSistema',$arrObjPermissaoDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO,'Usuário','SiglaUsuario',$arrObjPermissaoDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO,'Unidade','SiglaUnidade',$arrObjPermissaoDTO).'</th>';
		$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objPermissaoDTO,'Perfil','NomePerfil',$arrObjPermissaoDTO).'</th>';
		$strResultado .= '<th class="infraTh" width="15%">Ações</th>';
		$strResultado .= '</tr>'."\n";

		$n = 0;
		
		$dtaAtual = InfraData::getStrDataAtual();
		
		for($i = 0;$i < $numRegistros; $i++){
			
      if (InfraData::compararDatas($arrObjPermissaoDTO[$i]->getDtaDataInicio(),$dtaAtual)<0 || ($arrObjPermissaoDTO[$i]->getDtaDataFim()!=null && InfraData::compararDatas($dtaAtual,$arrObjPermissaoDTO[$i]->getDtaDataFim())<0)){
          $strResultado .= '<tr class="trVermelha">';
      }else{

        if ($arrObjPermissaoDTO[$i]->getStrSinSubunidades()=='S'){
          $strResultado .= '<tr class="trLaranja">';
        }else {

          if (($i + 2) % 2) {
            $strResultado .= '<tr class="infraTrEscura">';
          } else {
            $strResultado .= '<tr class="infraTrClara">';
          }
        }
      }
			

			if ($bolCheck){
				$strResultado .= '<td valign="top">'.PaginaSip::getInstance()->getTrCheck($n++,$arrObjPermissaoDTO[$i]->getNumIdPerfil().'-'.$arrObjPermissaoDTO[$i]->getNumIdSistema().'-'.$arrObjPermissaoDTO[$i]->getNumIdUsuario().'-'.$arrObjPermissaoDTO[$i]->getNumIdUnidade(),$arrObjPermissaoDTO[$i]->getStrSiglaSistema().'/'.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema().' - '.$arrObjPermissaoDTO[$i]->getStrSiglaUsuario().'/'.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario().' - '.$arrObjPermissaoDTO[$i]->getStrSiglaUnidade().'/'.$arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade().' - '.$arrObjPermissaoDTO[$i]->getStrNomePerfil()).'</td>';
			}else{
			  $strResultado .= '<td valign="top">&nbsp;</td>';
			}
			
			/*
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaSistema()).'</a>';
			$strResultado .= '/';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoSistema()).'</a>';
			*/
			
			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaUsuario()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUsuario()).'</a>';
			$strResultado .= '</td>';

			$strResultado .= '<td align="center">';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaUnidade()).'</a>';
			$strResultado .= '&nbsp;/&nbsp;';
			$strResultado .= '<a alt="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrSiglaOrgaoUnidade()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjPermissaoDTO[$i]->getStrNomePerfil()).'</td>';
			$strResultado .= '<td align="center">';

      if ($bolAcaoCopiar || $bolAcaoExcluir){
        $strId = $arrObjPermissaoDTO[$i]->getNumIdPerfil().'-'.$arrObjPermissaoDTO[$i]->getNumIdSistema().'-'.$arrObjPermissaoDTO[$i]->getNumIdUsuario().'-'.$arrObjPermissaoDTO[$i]->getNumIdUnidade();
        $strDescricao = PaginaSip::formatarParametrosJavaScript($arrObjPermissaoDTO[$i]->getStrSiglaUsuario().' / '.$arrObjPermissaoDTO[$i]->getStrSiglaSistema().' / '.$arrObjPermissaoDTO[$i]->getStrNomePerfil());
      }

			if ($bolAcaoCopiar){
				$strResultado .= '<a onclick="acaoCopiar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeClonar().'" title="Copiar Permissão" alt="Copiar Permissão" class="infraImg" /></a>&nbsp;';
			}
			
			if ($bolAcaoConsultar){
				$strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_consultar&acao_origem=permissao_listar_administradas&acao_retorno=permissao_listar_administradas&id_perfil='.$arrObjPermissaoDTO[$i]->getNumIdPerfil().'&id_sistema='.$arrObjPermissaoDTO[$i]->getNumIdSistema().'&id_usuario='.$arrObjPermissaoDTO[$i]->getNumIdUsuario().'&id_unidade='.$arrObjPermissaoDTO[$i]->getNumIdUnidade()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeConsultar().'" title="Consultar Permissão" alt="Consultar Permissão" class="infraImg" /></a>&nbsp;';
			}

			if ($bolAcaoAlterar){
				$strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=permissao_alterar&acao_origem=permissao_listar_administradas&acao_retorno=permissao_listar_administradas&id_perfil='.$arrObjPermissaoDTO[$i]->getNumIdPerfil().'&id_sistema='.$arrObjPermissaoDTO[$i]->getNumIdSistema().'&id_usuario='.$arrObjPermissaoDTO[$i]->getNumIdUsuario().'&id_unidade='.$arrObjPermissaoDTO[$i]->getNumIdUnidade()).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeAlterar().'" title="Alterar Permissão" alt="Alterar Permissão" class="infraImg" /></a>&nbsp;';
			}

			if ($bolAcaoExcluir){
				$strResultado .= '<a onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeExcluir().'" title="Excluir Permissão" alt="Excluir Permissão" class="infraImg" /></a>&nbsp;';
			}

			$strResultado .= '</td></tr>'."\n";
		}
		$strResultado .= '</table>';
	}
	$arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAutorizados('null','&nbsp;',$numIdOrgaoSistema);	
  if ($numIdOrgaoSistema!=='null'){
    $strItensSelSistema = SistemaINT::montarSelectSiglaAutorizados('null','&nbsp;', $numIdSistema, $numIdOrgaoSistema);
  }
  
  $strItensSelOrgaoUnidade = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoUnidade);	
  
  
  //$strItensSelUnidade = UnidadeINT::montarSelectSigla('null','&nbsp;',$numIdUnidade, $numIdOrgaoUnidade);
  $strItensSelUnidade = UnidadeINT::montarSelectSiglaAutorizadas('null','&nbsp;',$numIdUnidade, $numIdOrgaoUnidade, $numIdSistema);
  
  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('null','&nbsp;',$numIdOrgaoUsuario);
  
  if ($numIdSistema!=='null'){
    $strItensSelPerfil = PerfilINT::montarSelectSiglaAutorizados('null','&nbsp;',$numIdPerfil, $numIdSistema, $numIdUnidade);
  }
  
  
 	$strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');   

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Permissões Administradas');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblOrgaoSistema {position:absolute;left:0%;top:0%;width:21%;}
#selOrgaoSistema {position:absolute;left:0%;top:20%;width:21%;}

#lblSistema {position:absolute;left:0%;top:50%;width:21%;}
#selSistema {position:absolute;left:0%;top:70%;width:21%;}

#lblOrgaoUnidade {position:absolute;left:23%;top:0%;width:21%;}
#selOrgaoUnidade {position:absolute;left:23%;top:20%;width:21%;}

#lblUnidade {position:absolute;left:23%;top:50%;width:21%;}
#selUnidade {position:absolute;left:23%;top:70%;width:21%;}

#lblOrgaoUsuario {position:absolute;left:46%;top:0%;width:21%;}
#selOrgaoUsuario {position:absolute;left:46%;top:20%;width:21%;}

#lblUsuario {position:absolute;left:46%;top:50%;width:20.5%;}
#txtUsuario {position:absolute;left:46%;top:70%;width:20.5%;}
#lblNomeUsuario {position:absolute;left:69%;top:70%;width:25%;}

#lblPerfil {position:absolute;left:69%;top:0%;width:30%;}
#selPerfil {position:absolute;left:69%;top:20%;width:30%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objAjaxUsuario = null;

function inicializar(){

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

    if (id != ''){
      document.getElementById('hdnSiglaUsuario').value = descricao;
      document.getElementById('hdnNomeUsuario').value = complemento;
      document.getElementById('lblNomeUsuario').innerHTML = complemento;

      if (!this.carregando){
        document.getElementById('frmPermissaoListaAdministradas').submit();
      }
    }else{
      document.getElementById('hdnSiglaUsuario').value = '';
      document.getElementById('hdnNomeUsuario').value = '';
      document.getElementById('lblNomeUsuario').innerHTML = '';
    }
  };
  
  
  objAjaxUsuario.selecionar('<?=$numIdUsuario;?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strSiglaUsuario,false);?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strNomeUsuario,false)?>');
  objAjaxUsuario.carregando = false;
  
  infraEfeitoTabelas();
}



<? if ($bolAcaoExcluir){ ?>
     function acaoExcluir(id,desc){
       if (confirm("Confirma exclusão da Permissão \""+desc+"\"?")){
         document.getElementById('hdnInfraItensSelecionados').value=id;
         document.getElementById('frmPermissaoListaAdministradas').action='<?=$strLinkExcluir?>';
         document.getElementById('frmPermissaoListaAdministradas').submit();
       }
     }

     function acaoExclusaoMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhuma Permissão selecionada.');
         return;
       }
       if (confirm("Confirma exclusão das Permissões selecionadas?")){
         document.getElementById('frmPermissaoListaAdministradas').action='<?=$strLinkExcluir?>';
         document.getElementById('frmPermissaoListaAdministradas').submit();
       }
     }
<? } ?>

<? if ($bolAcaoCopiar){ ?>
     function acaoCopiar(id,desc){
			 document.getElementById('hdnInfraItensSelecionados').value=id;
			 document.getElementById('frmPermissaoListaAdministradas').action='<?=$strLinkCopiar?>';
			 document.getElementById('frmPermissaoListaAdministradas').submit();
     }

     function acaoCopiaMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhuma Permissão selecionada.');
         return;
       }
			 document.getElementById('frmPermissaoListaAdministradas').action='<?=$strLinkCopiar?>';
			 document.getElementById('frmPermissaoListaAdministradas').submit();
     }
<? } ?>

function trocarOrgaoSistema(obj){
	document.getElementById('selSistema').value='null';
	obj.form.submit();
}

function trocarOrgaoUnidade(obj){
	document.getElementById('selUnidade').value='null';
	obj.form.submit();
}


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Permissões Administradas','onload="inicializar();"');
?>
<form id="frmPermissaoListaAdministradas" method="post" action="<?=SessaoSip::getInstance()->assinarLink('permissao_lista_administradas.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSip::getInstance()->montarBarraLocalizacao('Permissões Administradas');
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('10em');
  ?>
  
  <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelOrgaoSistema?>
  </select>
	
  <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarSistema?>>
  <?=$strItensSelSistema?>
  </select>

  <label id="lblOrgaoUnidade" for="selOrgaoUnidade" accesskey="g" class="infraLabelOpcional">Ór<span class="infraTeclaAtalho">g</span>ão da Unidade:</label>
  <select id="selOrgaoUnidade" name="selOrgaoUnidade" onchange="trocarOrgaoUnidade(this);" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarUnidade?>>
  <?=$strItensSelOrgaoUnidade?>
  </select>
	
  <label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelOpcional"><span class="infraTeclaAtalho">U</span>nidade:</label>
  <select id="selUnidade" name="selUnidade" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarUnidade?>>
  <?=$strItensSelUnidade?>
  </select>

  <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelOpcional">Órgã<span class="infraTeclaAtalho">o</span> do Usuário:</label>
  <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarUsuario?>>
  <?=$strItensSelOrgaoUsuario?>
  </select>
	
  <label id="lblUsuario" for="txtUsuario" accesskey="i" class="infraLabelOpcional">Usuár<span class="infraTeclaAtalho">i</span>o:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarUsuario?> />
  <label id="lblNomeUsuario" class="infraLabelOpcional"></label>

  <label id="lblPerfil" for="selPerfil" accesskey="P" class="infraLabelOpcional"><span class="infraTeclaAtalho">P</span>erfil:</label>
  <select id="selPerfil" name="selPerfil" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarPerfil?>>
  <?=$strItensSelPerfil?>
  </select>

  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$numIdUsuario?>" />
  <input type="hidden" id="hdnSiglaUsuario" name="hdnSiglaUsuario" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" />
  <input type="hidden" id="hdnNomeUsuario" name="hdnNomeUsuario" value="<?=PaginaSip::tratarHTML($strNomeUsuario)?>" />
  
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>