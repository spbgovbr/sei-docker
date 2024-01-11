<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2010 - criado por alexandre_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

try {
	require_once dirname(__FILE__).'/SEI.php';

	session_start();

	//////////////////////////////////////////////////////////////////////////////
	//InfraDebug::getInstance()->setBolLigado(false);
	//InfraDebug::getInstance()->setBolDebugInfra(false);
	//InfraDebug::getInstance()->limpar();
	//////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

	if(strpos($_GET['acao'],'grupo_email_institucional')===0){
		$strInstitucional = ' Institucional';
		$strInstitucionais= ' Institucionais';
		$strRadical= 'grupo_email_institucional';
		$strStaTipo=GrupoEmailRN::$TGE_INSTITUCIONAL;
	} else {
		$strInstitucional = '';
		$strInstitucionais= '';
		$strRadical= 'grupo_email';
		$strStaTipo=GrupoEmailRN::$TGE_UNIDADE;
	}
	PaginaSEI::getInstance()->prepararSelecao($strRadical.'_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	switch($_GET['acao']){
		case $strRadical.'_excluir':
			try{
				$arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
				$arrObjGrupoEmailDTO = array();
				for ($i=0;$i<count($arrStrIds);$i++){
					$objGrupoEmailDTO = new GrupoEmailDTO();
					$objGrupoEmailDTO->setNumIdGrupoEmail($arrStrIds[$i]);
					$arrObjGrupoEmailDTO[] = $objGrupoEmailDTO;
				}				
				$objGrupoEmailRN = new GrupoEmailRN();
				$objGrupoEmailRN->excluir($arrObjGrupoEmailDTO);
				PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
			}catch(Exception $e){
				PaginaSEI::getInstance()->processarExcecao($e);
			}
			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
			die;

		case $strRadical.'_desativar':
			try{
				$arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
				$arrObjGrupoEmailDTO = array();
				for ($i=0;$i<count($arrStrIds);$i++){
					$objGrupoEmailDTO = new GrupoEmailDTO();
					$objGrupoEmailDTO->setNumIdGrupoEmail($arrStrIds[$i]);
					$arrObjGrupoEmailDTO[] = $objGrupoEmailDTO;
				}
				$objGrupoEmailRN = new GrupoEmailRN();
				$objGrupoEmailRN->desativar($arrObjGrupoEmailDTO);
				PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
			}catch(Exception $e){
				PaginaSEI::getInstance()->processarExcecao($e);
			}
			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
			die;

		case $strRadical.'_reativar':
			$strTitulo = 'Reativar Grupos de E-mail'.$strInstitucionais;
			if ($_GET['acao_confirmada']=='sim'){
				try{
					$arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
					$arrObjGrupoEmailDTO = array();
					for ($i=0;$i<count($arrStrIds);$i++){
						$objGrupoEmailDTO = new GrupoEmailDTO();
						$objGrupoEmailDTO->setNumIdGrupoEmail($arrStrIds[$i]);
						$arrObjGrupoEmailDTO[] = $objGrupoEmailDTO;
					}
					$objGrupoEmailRN = new GrupoEmailRN();
					$objGrupoEmailRN->reativar($arrObjGrupoEmailDTO);
					PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
				}catch(Exception $e){
					PaginaSEI::getInstance()->processarExcecao($e);
				}
				header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
				die;
			}
			break;

		case $strRadical.'_selecionar':
			$strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de E-mail'.$strInstitucional,'Selecionar Grupos de E-mail'.$strInstitucionais);

			//Se cadastrou alguem
			if ($_GET['acao_origem']==$strRadical.'_cadastrar'){
				if (isset($_GET['id_grupo_email'])){
					PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_email']);
				}
			}
			break;

		case $strRadical.'_listar':
			$strTitulo = 'Grupos de E-mail'.$strInstitucionais;
			break;

		default:
			throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
	}

	$arrComandos = array();

	if ($_GET['acao'] == $strRadical.'_selecionar'){
		$arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
	}

	if ($_GET['acao'] == $strRadical.'_listar' || $_GET['acao'] == $strRadical.'_selecionar' ) {
		$bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao($strRadical . '_cadastrar');
		if ($bolAcaoCadastrar) {
			$arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strRadical . '_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
		}
	}
	$objGrupoEmailDTO = new GrupoEmailDTO();
	$objGrupoEmailDTO->retNumIdGrupoEmail();
	$objGrupoEmailDTO->retStrNome();
	$objGrupoEmailDTO->retStrDescricao();

	$objGrupoEmailDTO->setStrStaTipo($strStaTipo);

	if ($strStaTipo == GrupoEmailRN::$TGE_UNIDADE) {
		$objGrupoEmailDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
	}

  if ($_GET['acao'] == $strRadical.'_reativar'){
    //Lista somente inativos
    $objGrupoEmailDTO->setBolExclusaoLogica(false);
    $objGrupoEmailDTO->setStrSinAtivo('N');
  }

	$objGrupoEmailDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
	
	//PaginaSEI::getInstance()->prepararPaginacao($objGrupoEmailDTO);

	$objGrupoEmailRN = new GrupoEmailRN();

	$arrObjGrupoEmailDTO = $objGrupoEmailRN->listar($objGrupoEmailDTO);

	//PaginaSEI::getInstance()->processarPaginacao($objGrupoEmailDTO);

	$numRegistros = count($arrObjGrupoEmailDTO);

	if ($numRegistros > 0){

		$bolCheck = false;

		if ($_GET['acao']==$strRadical.'_selecionar'){
			$bolAcaoReativar = false;
			$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_consultar');
			$bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_alterar');
			$bolAcaoImprimir = false;
			$bolAcaoExcluir = false;
			$bolAcaoDesativar = false;
			$bolCheck = true;

		} else if ($_GET['acao']==$strRadical.'_reativar'){
			$bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_reativar');
			$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_consultar');
			$bolAcaoAlterar = false;
			$bolAcaoImprimir = false;
			$bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_excluir');
			$bolAcaoDesativar = false;
    }else{
			$bolAcaoReativar = false;
			$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_consultar');
			$bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_alterar');
			$bolAcaoImprimir = false;
			$bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_excluir');
			$bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_desativar');
			}

			if ($bolAcaoExcluir){
				$bolCheck = true;
				$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
				$strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_excluir&acao_origem='.$_GET['acao']);
			}

      if ($bolAcaoDesativar){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
        $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_desativar&acao_origem='.$_GET['acao']);
      }

      if ($bolAcaoReativar){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
        $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
      }

			if ($bolAcaoImprimir){
				$bolCheck = true;
				$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

			}

			$strResultado = '';

    if ($_GET['acao']!=$strRadical.'_reativar'){
      $strSumarioTabela = 'Tabela de Grupos de E-mail'.$strInstitucionais.'.';
      $strCaptionTabela = 'Grupos de E-mail'.$strInstitucionais.'.';
    }else{
      $strSumarioTabela = 'Tabela de Grupos de E-mail'.$strInstitucionais.' Inativos.';
      $strCaptionTabela = 'Grupos de E-mail'.$strInstitucionais.' Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
			$strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
			$strResultado .= '<tr>';
			if ($bolCheck) {
				$strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
			}
			$strResultado .= '<th class="infraTh" width="30%">Nome</th>'."\n";
			$strResultado .= '<th class="infraTh" width="50%">Descrição</th>'."\n";
			$strResultado .= '<th class="infraTh">Ações</th>'."\n";
			$strResultado .= '</tr>'."\n";
			$strCssTr='';
			for($i = 0;$i < $numRegistros; $i++){

				$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
				$strResultado .= $strCssTr;

				if ($bolCheck){
					$strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail(),$arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail()).'</td>';
				}
				$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoEmailDTO[$i]->getStrNome()).'</td>';
        $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoEmailDTO[$i]->getStrDescricao()).'</td>';
				$strResultado .= '<td align="center">';

				$strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail());

				if ($bolAcaoConsultar){
					$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_email='.$arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de E-mail" alt="Consultar Grupo de E-mail" class="infraImg" /></a>&nbsp;';
				}

				if ($bolAcaoAlterar){
					$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_email='.$arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de E-mail" alt="Alterar Grupo de E-mail" class="infraImg" /></a>&nbsp;';
				}

				if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
					$strId = $arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail();
					$strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoEmailDTO[$i]->getStrNome());
				}

        if ($bolAcaoDesativar){
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Grupo de E-mail" alt="Desativar Grupo de E-mail" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoReativar){
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Grupo de E-mail" alt="Reativar Grupo de E-mail" class="infraImg" /></a>&nbsp;';
        }


				if ($bolAcaoExcluir){
					$strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de E-mail" alt="Excluir Grupo de E-mail" class="infraImg" /></a>&nbsp;';
				}

				$strResultado .= '</td></tr>'."\n";
			}
			$strResultado .= '</table>';
	}
	if ($_GET['acao'] == $strRadical.'_selecionar'){
		$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
	}else{
		$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
	}

}catch(Exception $e){
	PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  <? if ($_GET['acao']==$strRadical.'_selecionar'){ ?>
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  <?}else{?>
    document.getElementById('btnFechar').focus();
  <?}?>

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo de E-mail \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoEmailLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoEmailLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de E-mail selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos de E-mail selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoEmailLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoEmailLista').submit();
  }
}
<? } ?>


<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de E-mail \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoEmailLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoEmailLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de E-mail selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos de E-mail selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoEmailLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoEmailLista').submit();
  }
}
<? } ?>


<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de E-mail \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoEmailLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoEmailLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de E-mail selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos de E-mail selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoEmailLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoEmailLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoEmailLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros, true);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
<br />
<br />  
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>