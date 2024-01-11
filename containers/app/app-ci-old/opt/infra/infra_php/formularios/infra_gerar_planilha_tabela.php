<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2011 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

try {

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'infra_gerar_planilha_tabela':
			PaginaInfra::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
      $strTitulo = '';
      
      if(isset($_POST['hdnInfraDadosTabela'])){      	
				header("Content-Type: text/csv;");
				header("Content-Disposition: attachment;filename=planilha.csv;");
				
      	echo $_POST['hdnInfraDadosTabela'];
      	die();      	
      }
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('hdnInfraDadosTabela').value = window.opener.document.getElementById('infraDivImpressao').innerTEXT;
  document.getElementById('frmInfraGerarPlanilha').submit();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraGerarPlanilha" method="post" onsubmit="return true;" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaInfra::getInstance()->montarAreaValidacao();
PaginaInfra::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblInfraPlanilha" for="txtInfraPlanilha" accesskey="" class="infraLabelObrigatorio">Planilha gerada com sucesso.</label>
  
  <input type="hidden" id="hdnInfraDadosTabela" name="hdnInfraDadosTabela" class="infraText" value="" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  //PaginaInfra::getInstance()->montarAreaDebug();
  //PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>