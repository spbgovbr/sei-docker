<?php
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	PaginaSEI::getInstance()->salvarCamposPost(array('q'));

	$strResultado = '';

  switch($_GET['acao']){

    case 'base_conhecimento_pesquisar':
    	
      $strTitulo = 'Base de Conhecimento';

			$q = PaginaSEI::getInstance()->recuperarCampo('q');

			if (isset($_POST['q'])){
				try{

					$objPesquisaBaseConhecimentoSolrDTO = new PesquisaBaseConhecimentoSolrDTO();
					$objPesquisaBaseConhecimentoSolrDTO->setStrPalavrasChave($q);
					$objPesquisaBaseConhecimentoSolrDTO->setNumInicioPaginacao($_POST['hdnInicio']);
					$strResultado = SolrBaseConhecimento::executar($objPesquisaBaseConhecimentoSolrDTO);

				}catch(Exception $e){
				  SeiSolrUtil::tratarErroPesquisa(PaginaSEI::getInstance(), $e);
				}
			}

			break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if (SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_cadastrar')){
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" style="width:10em;" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
  }

  if (SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_listar')){
	  $arrComandos[] = '<button type="button" accesskey="M" id="btnMinhaBase" value="MinhaBase" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">M</span>inha Base</button>';
  }

  $strLinkAjuda = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_solr_ajuda&acao_origem='.$_GET['acao']);

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

#lblPesquisa 	{position:absolute;left:0%;top:0%;}
#txtPesquisa 	{position:absolute;left:0%;top:40%;width:50%;}
#ancAjuda {position:absolute;left:51%;top:40%;}

.linkAnexo 		{color:#006600;}
.linkUnidade 	{color:#006600;}

#divInfraAreaTabela tr.infraTrClara td {padding:.3em;}
#divInfraAreaTabela table.infraTable {border-spacing:0;}

td.pesquisaSnippet {
  padding-bottom:2em !important;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  infraEfeitoTabelas();
  document.getElementById('txtPesquisa').focus();
}

function navegar(inicio) {
	document.getElementById('hdnInicio').value = inicio;
	if (typeof(window.onSubmitForm)=='function' && !window.onSubmitForm()) {
	  return;
	}
	document.getElementById('frmPesquisaBaseConhecimento').submit();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar(); "');
?>

<form id="frmPesquisaBaseConhecimento" name="frmPesquisaBaseConhecimento" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblPesquisa" class="infraLabelOpcional" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Palavras-chave:</label>
  <input type="text" name="q" id="txtPesquisa"  maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" class="infraText" value="<?=PaginaSEI::tratarHTML($q)?>"/>
  <a id="ancAjuda" href="<?=$strLinkAjuda?>" target="janAjuda" title="Ajuda para Pesquisa" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
  
  <input id="partialfields" name="partialfields" type="hidden" value="" />

<?  
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
	echo '<div id="conteudo" style="width:99%;" class="infraAreaTabela">';
	echo $strResultado;
	echo '</div>';
  PaginaSEI::getInstance()->montarAreaDebug();
?>
	<input type="hidden" id="hdnInicio" name="hdnInicio" value="0" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>