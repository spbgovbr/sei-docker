<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/06/2019 - criado por mga@trf4.gov.br
*
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

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_procedimento_federacao'])){
    $strParametros .= '&id_procedimento_federacao='.$_GET['id_procedimento_federacao'];
  }

  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }
    
  if (isset($_GET['id_acesso_federacao'])){
    $strParametros .= '&id_acesso_federacao='.$_GET['id_acesso_federacao'];
  }

  $bolFlagCancelamentoOK = false;
  switch($_GET['acao']){
      
    case 'acesso_federacao_cancelar':

      $strTitulo = 'Cancelamento de Envio para o SEI Federação';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmSalvar'])) {
        try{
          
          $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
          $objAcessoFederacaoDTO->setStrIdAcessoFederacao($_GET['id_acesso_federacao']);
          $objAcessoFederacaoDTO->setStrMotivoCancelamento($_POST['txaMotivo']);
          $objAcessoFederacaoDTO->setDthCancelamento(InfraData::getStrDataHoraAtual());

	        $objAcessoFederacaoRN = new AcessoFederacaoRN();
          $objAcessoFederacaoRN->cancelarAcesso($objAcessoFederacaoDTO);

	        $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_acesso_federacao']));
	        
          $bolFlagCancelamentoOK = true;
          
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
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
#lblMotivo {position:absolute;left:0%;top:10%;width:25%;}
#txaMotivo {position:absolute;left:0%;top:30%;width:95%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  if ('<?=$bolFlagCancelamentoOK?>'=='1'){
    parent.document.getElementById('ifrVisualizacao').src = '<?=$strLinkRetorno?>';
    self.setTimeout('infraFecharJanelaModal()',200);
  }
  
  document.getElementById('txaMotivo').focus();
}

function validarCadastroRI1297() {
  if (document.getElementById('txaMotivo').value == '') {
    alert('Motivo não informado.');
    document.getElementById('txaMotivo').focus();
    return false;
  }
  return true;
}

function OnSubmitForm() {
  return validarCadastroRI1297();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcessoFederacaoCancelar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('10em');
?>
  <label id="lblMotivo" for="txaMotivo" accesskey="" class="infraLabelObrigatorio">Motivo:</label>
	<textarea id="txaMotivo" name="txaMotivo" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($_POST['txaMotivo'])?></textarea>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>