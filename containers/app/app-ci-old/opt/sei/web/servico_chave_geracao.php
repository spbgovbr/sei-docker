<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/09/2019 - criado por mga
*
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrComandos = array();
  
  switch($_GET['acao']){
    
    case 'servico_gerar_chave_acesso':
    	
    	$strTitulo = 'Geração de Chave de Acesso';

      $objServicoDTO = new ServicoDTO();
      $objServicoDTO->setNumIdServico($_GET['id_servico']);

      $objServicoRN = new ServicoRN();
      $objServicoDTO = $objServicoRN->gerarChaveAcesso($objServicoDTO);

      //$arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

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
#lblAviso {color:red;font-size:14px;}
#lblChaveAcesso {}
#txtChaveAcesso {width:80%;}
#btnCopiar {}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if (window.opener != null){
    var imgChaveAcesso = window.opener.document.getElementById('imgChaveAcesso<?=$_GET['id_servico']?>');
    if (imgChaveAcesso != null){
      imgChaveAcesso.src = '<?=Icone::SISTEMA_SERVICO_COM_CHAVE?>';
    }
  }
}

function OnSubmitForm() {
  return true;
}

function copiar() {
  var copyText = document.getElementById("txtChaveAcesso");
  copyText.select();
  document.execCommand("copy");
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmServicoGerarChave" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('10em');
?>
  
 	<label id="lblAviso" class="infraLabelOpcional">Uma nova chave de acesso foi gerada e estará disponível para cópia somente neste momento.</label>
  <br><br>
  <label id="lblChaveAcesso" for="txtChaveAcesso" class="infraLabelOpcional">Chave:&nbsp;</label>
  <input type="text" id="txtChaveAcesso" name="txtChaveAcesso" readonly="readonly" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($objServicoDTO->getStrChaveCompleta());?>"></input>&nbsp;
  <button type="button" id="btnCopiar" onclick="copiar()" value="Copiar" class="infraButton" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Copiar</button>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
//PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>