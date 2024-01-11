<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/08/2017 - criado por mga@trf4.jus.br
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

  $strDesabilitar = '';

  $arrComandos = array();

  $bolFlagOK = false;
  $strLinkRetorno = '';

  switch($_GET['acao']){

    case 'procedimento_configurar_detalhe':

      $strTitulo = 'Configurar Nível de Detalhe';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


      if ($_GET['acao_origem']=='procedimento_controlar'){

        $objPainelControleRN = new PainelControleRN();
        $objPainelControleDTO = $objPainelControleRN->carregarConfiguracoes();

      }else{

        $objPainelControleDTO = new PainelControleDTO();
        $objPainelControleDTO->setStrSinNivelAtribuicao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAtribuicao']));
        $objPainelControleDTO->setStrSinNivelAnotacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAnotacao']));
        $objPainelControleDTO->setStrSinNivelTipoProcesso(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinTipoProcesso']));
        $objPainelControleDTO->setStrSinNivelInteressados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInteressados']));
        $objPainelControleDTO->setStrSinNivelEspecificacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEspecificacao']));
        $objPainelControleDTO->setStrSinNivelPrioritarios(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinNivelPrioritarios']));
        $objPainelControleDTO->setStrSinNivelObservacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObservacao']));
        $objPainelControleDTO->setStrSinNivelControlePrazo(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinControlePrazo']));
        $objPainelControleDTO->setStrSinNivelRetornoDevolver(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRetornoDevolver']));
        $objPainelControleDTO->setStrSinNivelRetornoAguardando(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRetornoAguardando']));
        $objPainelControleDTO->setStrSinNivelUltimaMovimentacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUltimaMovimentacao']));
        $objPainelControleDTO->setStrSinNivelMarcadores(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinMarcadores']));
      }

      if (isset($_POST['sbmSalvar'])) {
        try{

          $objPainelControleRN = new PainelControleRN();
          $objPainelControleRN->salvarConfiguracoes($objPainelControleDTO);
          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_acesso_externo']));

          $bolFlagOK = true;

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

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$bolFlagOK?>'=='1'){
    window.parent.location = '<?=$strLinkRetorno?>';
    self.setTimeout('infraFecharJanelaModal()',200);
  }
}

function OnSubmitForm() {
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAcessoExternoCancelar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>

    <div id="divAtribuicao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinAtribuicao" name="chkSinAtribuicao" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelAtribuicao())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinAtribuicao" for="chkSinAtribuicao" accesskey="" class="infraLabelCheckbox">Atribuição</label>
    </div>

    <div id="divAnotacao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinAnotacao" name="chkSinAnotacao" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelAnotacao())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinAnotacao" for="chkSinAnotacao" accesskey="" class="infraLabelCheckbox">Anotação</label>
    </div>

    <div id="divTipoProcesso" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinTipoProcesso" name="chkSinTipoProcesso" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelTipoProcesso())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinTipoProcesso" for="chkSinTipoProcesso" accesskey="" class="infraLabelCheckbox">Tipo do Processo</label>
    </div>

    <div id="divEspecificacao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinEspecificacao" name="chkSinEspecificacao" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelEspecificacao())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinEspecificacao" for="chkSinEspecificacao" accesskey="" class="infraLabelCheckbox">Especificação</label>
    </div>

    <div id="divPrioritarios" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinNivelPrioritarios" name="chkSinNivelPrioritarios" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelPrioritarios())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinNivelPrioritarios" for="chkSinNivelPrioritarios" accesskey="" class="infraLabelCheckbox">Prioridade</label>
    </div>

    <div id="divInteressados" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinInteressados" name="chkSinInteressados" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelInteressados())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinInteressados" for="chkSinInteressados" accesskey="" class="infraLabelCheckbox">Interessados</label>
    </div>

    <div id="divObservacao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinObservacao" name="chkSinObservacao" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelObservacao())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinObservacao" for="chkSinObservacao" accesskey="" class="infraLabelCheckbox">Observação</label>
    </div>

    <div id="divControlePrazo" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinControlePrazo" name="chkSinControlePrazo" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelControlePrazo())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinControlePrazo" for="chkSinControlePrazo" accesskey="" class="infraLabelCheckbox">Controle de Prazo</label>
    </div>

    <div id="divRetornoDevolver" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinRetornoDevolver" name="chkSinRetornoDevolver" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelRetornoDevolver())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinRetornoDevolver" for="chkSinRetornoDevolver" accesskey="" class="infraLabelCheckbox">Para Devolver</label>
    </div>

    <div id="divRetornoAguardando" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinRetornoAguardando" name="chkSinRetornoAguardando" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelRetornoAguardando())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinRetornoAguardando" for="chkSinRetornoAguardando" accesskey="" class="infraLabelCheckbox">Aguardando Retorno</label>
    </div>

    <div id="divUltimaMovimentacao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinUltimaMovimentacao" name="chkSinUltimaMovimentacao" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelUltimaMovimentacao())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinUltimaMovimentacao" for="chkSinUltimaMovimentacao" accesskey="" class="infraLabelCheckbox">Última Movimentação na Unidade</label>
    </div>

    <div id="divMarcadores" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinMarcadores" name="chkSinMarcadores" class="infraCheckbox" onchange="validarNumeroCriterios(this)" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinNivelMarcadores())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinMarcadores" for="chkSinMarcadores" accesskey="" class="infraLabelCheckbox">Marcadores</label>
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