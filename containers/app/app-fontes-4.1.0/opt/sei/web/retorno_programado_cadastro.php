<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2010 - criado por jonatas_db
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
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();
  
  PaginaSEI::getInstance()->verificarSelecao('retorno_programado_selecionar');
  
  //SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objRetornoProgramadoDTO = new RetornoProgramadoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'retorno_programado_alterar':
    	
      $strTitulo = 'Alterar Retorno Programado';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarRetornoProgramado" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_retorno_programado'])){
        $objRetornoProgramadoDTO->setNumIdRetornoProgramado($_GET['id_retorno_programado']);
        $objRetornoProgramadoDTO->retNumIdRetornoProgramado();
        $objRetornoProgramadoDTO->retStrSiglaUsuario();
        $objRetornoProgramadoDTO->retStrProtocoloFormatadoProtocolo();
        $objRetornoProgramadoDTO->retStrSiglaUnidadeEnvio();
        $objRetornoProgramadoDTO->retDtaProgramada();
        $objRetornoProgramadoRN = new RetornoProgramadoRN();
        $objRetornoProgramadoDTO = $objRetornoProgramadoRN->consultar($objRetornoProgramadoDTO);
        
        if ($objRetornoProgramadoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
      	$objRetornoProgramadoDTO->setStrProtocoloFormatadoProtocolo($_POST['hdnProtocoloFormatado']);
        $objRetornoProgramadoDTO->setNumIdRetornoProgramado($_POST['hdnIdRetornoProgramado']);
        $objRetornoProgramadoDTO->setDtaProgramada($_POST['txtProgramada']);
      }
			
     $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objRetornoProgramadoDTO->getNumIdRetornoProgramado())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarRetornoProgramado'])) {
        try{
          $objRetornoProgramadoRN = new RetornoProgramadoRN();
          $objRetornoProgramadoRN->alterar($objRetornoProgramadoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Retorno Programado alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objRetornoProgramadoDTO->getNumIdRetornoProgramado())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'retorno_programado_consultar':
      $strTitulo = 'Consultar Retorno Programado';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_retorno_programado'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objRetornoProgramadoDTO->setNumIdRetornoProgramado($_GET['id_retorno_programado']);
      $objRetornoProgramadoDTO->setBolExclusaoLogica(false);
      $objRetornoProgramadoDTO->retStrProtocoloFormatadoProtocolo();
      $objRetornoProgramadoDTO->retStrSiglaUsuario();
		  $objRetornoProgramadoDTO->retStrSiglaUnidade();
		  $objRetornoProgramadoDTO->retTodos();
      $objRetornoProgramadoRN = new RetornoProgramadoRN();
      $objRetornoProgramadoDTO = $objRetornoProgramadoRN->consultar($objRetornoProgramadoDTO);
      
      if ($objRetornoProgramadoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
#lblProtocoloFormatado 	{position:absolute;left:0%;top:0%;width:25%;}
#txtProtocoloFormatado	{position:absolute;left:0%;top:6%;width:25%;}

#lblProgramada {position:absolute;left:0%;top:16%;width:10%;}
#txtProgramada {position:absolute;left:0%;top:22%;width:10%;}
#imgCalProgramada {position:absolute;left:11%;top:23%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='retorno_programado_cadastrar' || '<?=$_GET['acao']?>'=='retorno_programado_alterar'){
    document.getElementById('txtProgramada').focus();
  } else if ('<?=$_GET['acao']?>'=='retorno_programado_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  infraEfeitoTabelas();
}

function validarCadastro() {

  if (document.getElementById('txtProgramada').value == '') {
    alert('Informe a Data Programada.');
    document.getElementById('txtProgramada').focus();
    return false;
  }
  
  if (!infraValidarData(document.getElementById('txtProgramada'))){
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmRetornoProgramadoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('28em');
?>
  <label id="lblProtocoloFormatado" for="txtProtocoloFormatado" accesskey="" class="infraLabelObrigatorio">Protocolo:</label>
  <input type="text" id="txtProtocoloFormatado" name="txtProtocoloFormatado" class="infraSelect" value="<?=PaginaSEI::tratarHTML($objRetornoProgramadoDTO->getStrProtocoloFormatadoProtocolo());?>" <?=$strDesabilitar?>/>
  
  <label id="lblProgramada" for="txtProgramada" accesskey="P" class="infraLabelObrigatorio">Data <span class="infraTeclaAtalho">P</span>rogramada:</label>
  <input type="text" id="txtProgramada" name="txtProgramada" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objRetornoProgramadoDTO->getDtaProgramada());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalProgramada" title="Selecionar Data Programada" alt="Selecionar Data Programada" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtProgramada',this);" />

  <input type="hidden" id="hdnIdRetornoProgramado" name="hdnIdRetornoProgramado" value="<?=$objRetornoProgramadoDTO->getNumIdRetornoProgramado();?>" />
  <input type="hidden" id="hdnProtocoloFormatado" name="hdnProtocoloFormatado" value="<?=$objRetornoProgramadoDTO->getStrProtocoloFormatadoProtocolo();?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>