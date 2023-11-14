<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2011 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('controle_interno_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objControleInternoDTO = new ControleInternoDTO();

  $strDesabilitar = '';

  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'controle_interno_cadastrar':
      $strTitulo = 'Novo Critério de Controle Interno';
      
      $arrComandos[] = '<button type="button" accesskey="S" name="btnCadastrarControleInterno" value="Salvar" onclick="salvar();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objControleInternoDTO->setNumIdControleInterno(null);
      $objControleInternoDTO->setStrDescricao($_POST['txtDescricao']);

      if ($_GET['executar']=='1'){
        
        PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo,null,false);
        
        try{
      
          $objControleInternoRN = new ControleInternoRN();
		      $objControleInternoDTO->setArrObjRelControleInternoUnidade(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']));
		      $objControleInternoDTO->setArrObjRelControleInternoOrgao(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']));
		      $objControleInternoDTO->setArrObjRelControleInternoTipoProc(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']));
		      $objControleInternoDTO->setArrObjRelControleInternoSerie(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']));
		      
          $objControleInternoDTO = $objControleInternoRN->cadastrar($objControleInternoDTO);
                
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        
        PaginaSEI::getInstance()->finalizarBarraProgresso2(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_controle_interno='.$objControleInternoDTO->getNumIdControleInterno().PaginaSEI::getInstance()->montarAncora($objControleInternoDTO->getNumIdControleInterno())));
      }
      
      /*
      if ($_POST['hdnFlagSalvar']=='1') {
        try{
          
          $objControleInternoRN = new ControleInternoRN();
		      $objControleInternoDTO->setArrObjRelControleInternoUnidade(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']));
		      $objControleInternoDTO->setArrObjRelControleInternoOrgao(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']));
		      $objControleInternoDTO->setArrObjRelControleInternoTipoProc(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']));
		      $objControleInternoDTO->setArrObjRelControleInternoSerie(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']));
		      
          $objControleInternoDTO = $objControleInternoRN->cadastrar($objControleInternoDTO);
          //$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_controle_interno='.$objControleInternoDTO->getNumIdControleInterno().PaginaSEI::getInstance()->montarAncora($objControleInternoDTO->getNumIdControleInterno()));
          die;  
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      */
      break;

    case 'controle_interno_alterar':
      $strTitulo = 'Alterar Critério de Controle Interno';
      
      $arrComandos[] = '<button type="button" accesskey="S" name="btnAlterarControleInterno" value="Salvar" onclick="salvar();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_controle_interno'])){
        $objControleInternoDTO->setNumIdControleInterno($_GET['id_controle_interno']);
        $objControleInternoDTO->retTodos();
        $objControleInternoRN = new ControleInternoRN();
        $objControleInternoDTO = $objControleInternoRN->consultar($objControleInternoDTO);
        if ($objControleInternoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objControleInternoDTO->setNumIdControleInterno($_POST['hdnIdControleInterno']);
        $objControleInternoDTO->setStrDescricao($_POST['txtDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objControleInternoDTO->getNumIdControleInterno())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if ($_GET['executar']=='1'){
        PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo,null,false);
        try{
      
          $objControleInternoRN = new ControleInternoRN();
			    $objControleInternoDTO->setArrObjRelControleInternoUnidade(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']));
			    $objControleInternoDTO->setArrObjRelControleInternoOrgao(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']));
			    $objControleInternoDTO->setArrObjRelControleInternoTipoProc(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']));
			    $objControleInternoDTO->setArrObjRelControleInternoSerie(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']));
			    
          $objControleInternoRN->alterar($objControleInternoDTO);
                  
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        PaginaSEI::getInstance()->finalizarBarraProgresso2(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objControleInternoDTO->getNumIdControleInterno())));
      }
        
      /*  
      if ($_POST['hdnFlagSalvar']=='1') {
        try{
          
          $objControleInternoRN = new ControleInternoRN();
			    $objControleInternoDTO->setArrObjRelControleInternoUnidade(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']));
			    $objControleInternoDTO->setArrObjRelControleInternoOrgao(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']));
			    $objControleInternoDTO->setArrObjRelControleInternoTipoProc(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']));
			    $objControleInternoDTO->setArrObjRelControleInternoSerie(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']));
			    
          $objControleInternoRN->alterar($objControleInternoDTO);
          
          //$strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objControleInternoDTO->getNumIdControleInterno()));
          
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      */
      break;

    case 'controle_interno_consultar':
      $strTitulo = 'Consultar Critério de Controle Interno';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_controle_interno'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objControleInternoDTO->setNumIdControleInterno($_GET['id_controle_interno']);
      $objControleInternoDTO->setBolExclusaoLogica(false);
      $objControleInternoDTO->retTodos();
      $objControleInternoRN = new ControleInternoRN();
      $objControleInternoDTO = $objControleInternoRN->consultar($objControleInternoDTO);
      if ($objControleInternoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkUnidadeSelecao 	= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades');
  $strLinkOrgaoSelecao 		= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_selecionar&tipo_selecao=2&id_object=objLupaOrgaos');
  $strLinkTipoProcedimentoSelecao 		= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTiposProcedimento');
  $strLinkSerieSelecao 		= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar&tipo_selecao=2&id_object=objLupaSeries');
  
  $strItensSelUnidade 		= ControleInternoINT::montarSelectUnidades(null,null,null, $objControleInternoDTO->getNumIdControleInterno());
  $strItensSelOrgao				= ControleInternoINT::montarSelectOrgaos(null,null,null, $objControleInternoDTO->getNumIdControleInterno());
  $strItensSelTipoProcedimento				= ControleInternoINT::montarSelectTiposProcedimento(null,null,null, $objControleInternoDTO->getNumIdControleInterno());
  $strItensSelSerie				= ControleInternoINT::montarSelectSeries(null,null,null, $objControleInternoDTO->getNumIdControleInterno());

  $strDisplayDados = '';

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
#divDados {<?=$strDisplayDados?>}
#lblDescricao {position:absolute;left:0%;top:0%;width:50%;}
#txtDescricao {position:absolute;left:0%;top:3%;width:50%;}

#lblUnidades 	{position:absolute;left:0%;top:8%;width:50%;}
#txtUnidade 	{position:absolute;left:0%;top:11%;width:50%;}
#selUnidades 	{position:absolute;left:0%;top:11%;width:50.5%;}
#imgLupaUnidades {position:absolute;left:51%;top:11%;}
#imgExcluirUnidades {position:absolute;left:51%;top:14.5%;}

#lblOrgaos 		{position:absolute;left:0%;top:24%;width:50%;}
#selOrgaos		{position:absolute;left:0%;top:27%;width:50.5%;}
#imgLupaOrgaos {position:absolute;left:51%;top:27%;}
#imgExcluirOrgaos {position:absolute;left:51%;top:30.5%;}

#lblTiposProcedimento 		{position:absolute;left:0%;top:40%;width:50%;}
#selTiposProcedimento		{position:absolute;left:0%;top:43%;width:50.5%;}
#imgLupaTiposProcedimento {position:absolute;left:51%;top:43%;}
#imgExcluirTiposProcedimento {position:absolute;left:51%;top:46.5%;}

#lblSeries 		{position:absolute;left:0%;top:67%;width:50%;}
#selSeries		{position:absolute;left:0%;top:70%;width:50.5%;}
#imgLupaSeries {position:absolute;left:51%;top:70%;}
#imgExcluirSeries {position:absolute;left:51%;top:73.5%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  if ('<?=$_GET['acao']?>'=='controle_interno_cadastrar'){
    document.getElementById('txtDescricao').focus();
  } else if ('<?=$_GET['acao']?>'=='controle_interno_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    if (document.getElementById('btnCancelar')!=null){
      document.getElementById('btnCancelar').focus();
    }else if (document.getElementById('btnVoltar')!=null){
      document.getElementById('btnVoltar').focus();
    }
  }
  
  
	objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadeSelecao?>');
	objLupaOrgaos 	= new infraLupaSelect('selOrgaos','hdnOrgaos','<?=$strLinkOrgaoSelecao?>');
	objLupaTiposProcedimento 	= new infraLupaSelect('selTiposProcedimento','hdnTiposProcedimento','<?=$strLinkTipoProcedimentoSelecao?>');
	objLupaSeries 	= new infraLupaSelect('selSeries','hdnSeries','<?=$strLinkSerieSelecao?>');

  infraEfeitoImagens();
  infraEfeitoTabelas();
}

function validarCadastroRI0012() {

    
  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (document.getElementById('selUnidades').options.length==0) {
    alert('Informe pelo menos uma Unidade de Controle.');
    document.getElementById('selUnidades').focus();
    return false;
  }

  if (document.getElementById('selOrgaos').options.length==0) {
    alert('Informe pelo menos um Órgão para Controle.');
    document.getElementById('selOrgaos').focus();
    return false;
  }

  if (document.getElementById('selTiposProcedimento').options.length==0 && document.getElementById('selSeries').options.length==0) {
    alert('Informe pelo menos um Tipo de Procedimento ou Documento para Controle.');
    document.getElementById('selTiposProcedimento').focus();
    return false;
  }
  return true;
}

function salvar() {
  if (validarCadastroRI0012()){
    if (confirm('ATENÇÃO: esta operação pode ser demorada.\n\nConfirma aplicação do critério?')){
	    infraAbrirBarraProgresso(document.getElementById('frmControleInternoCadastro'), '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'].'&executar=1')?>', 600, 250);
	  }
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmControleInternoCadastro" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
<div id="divDados" class="infraAreaDados" style="height:60em;">

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objControleInternoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

 	<label id="lblUnidades" for="selUnidades" class="infraLabelObrigatorio">Unidades de Controle:</label>
  <select id="selUnidades" name="selUnidades" size="4" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  	<?=$strItensSelUnidade?>
  </select>
  <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

 	<label id="lblOrgaos" for="selOrgaos" class="infraLabelObrigatorio">Órgãos Controlados:</label>
  <select id="selOrgaos" name="selOrgaos" size="4" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  	<?=$strItensSelOrgao?>
  </select>
  <img id="imgLupaOrgaos" onclick="objLupaOrgaos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Órgãos" title="Selecionar Órgãos" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirOrgaos" onclick="objLupaOrgaos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Órgãos Selecionados" title="Remover Órgãos Selecionados" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

 	<label id="lblTiposProcedimento" for="selTiposProcedimento" class="infraLabelObrigatorio">Tipos de Processo Controlados:</label>
  <select id="selTiposProcedimento" name="selTiposProcedimento" size="8" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
		<?=$strItensSelTipoProcedimento?>  
  </select>
  <img id="imgLupaTiposProcedimento" onclick="objLupaTiposProcedimento.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Tipos de Processo" title="Selecionar Tipos de Processo" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirTiposProcedimento" onclick="objLupaTiposProcedimento.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipos de Processo Selecionados" title="Remover Tipos de Processo Selecionados" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

 	<label id="lblSeries" for="selSeries" class="infraLabelObrigatorio">Tipos de Documento Controlados:</label>
  <select id="selSeries" name="selSeries" size="8" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
		<?=$strItensSelSerie?>  
  </select>
  <img id="imgLupaSeries" onclick="objLupaSeries.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Tipos de Documento" title="Selecionar Tipos de Documento" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirSeries" onclick="objLupaSeries.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipos de Documento Selecionados" title="Remover Tipos de Documento Selecionados" class="infraImgNormal" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdControleInterno" name="hdnIdControleInterno" value="<?=$objControleInternoDTO->getNumIdControleInterno();?>" />
  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />
  <input type="hidden" id="hdnOrgaos" name="hdnOrgaos" value="<?=$_POST['hdnOrgaos']?>" />
  <input type="hidden" id="hdnTiposProcedimento" name="hdnTiposProcedimento" value="<?=$_POST['hdnTiposProcedimento']?>" />
  <input type="hidden" id="hdnSeries" name="hdnSeries" value="<?=$_POST['hdnSeries']?>" />
</div>  
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>