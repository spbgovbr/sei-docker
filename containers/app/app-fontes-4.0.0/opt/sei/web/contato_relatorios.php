<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/03/2008 - criado por mga
*
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

  $arrComandos = array();

  PaginaSEI::getInstance()->salvarSelecao($_GET['acao'],$_GET['acao_origem']);

  $arrIdContatos = array();
  $strIdContatos = "";
  if (isset($_POST['hdnContatos'])){
    $arrIdContatos = explode(",", $_POST['hdnContatos'] );
    $strIdContatos = $_POST['hdnContatos'];
  }else{
    $arrIdContatos = PaginaSEI::getInstance()->getArrStrItensSelecionados();
    $strIdContatos = implode(",", $arrIdContatos);
  }
  switch($_GET['acao']){
    case 'contato_gerar_relatorios':
      $strTitulo = 'Relatórios de Contatos';
      $arrComandos[] = '<input type="submit" name="btnGerar" value="Gerar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton" />';

      if (isset($_POST['btnGerar'])) {

        $objContatoRN = new ContatoRN();
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->retTodos(true);
        $objContatoDTO->setNumIdContato($arrIdContatos, InfraDTO::$OPER_IN);
        $arrObjContatoDTO = $objContatoRN->listarComEndereco($objContatoDTO);

        $arrColunasRelatorioDTO_Todas = InfraArray::indexarArrInfraDTO(ContatoINT::montarTodasColunasRelatorio(), 'ColunaAtributo');
        $arrColunasRelatorioDTO_Selecionadas = array();
        $arrSelColunas = $_POST['selColunas'];
        foreach ($arrSelColunas as $selColuna){
          $arrColunasRelatorioDTO_Selecionadas[] = $arrColunasRelatorioDTO_Todas[$selColuna];
        }

        InfraPagina::montarHeaderDownload($_POST['hdnTipoRelatorioDescricao'] . '.csv', 'attachment');
        echo ContatoINT::montarConteudoExcel($arrObjContatoDTO, $arrColunasRelatorioDTO_Selecionadas);
        die;
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelTipoRelatorio = ContatoINT::montarSelectTipoRelatorio('null','&nbsp;',$_POST['selTipoRelatorio']);
  //$strItensSelColunas = ContatoINT::montarSelectColunasRelatorio($_POST['selColunas']);

  $strLinkAjaxTipoRelatorio = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=tipo_relatorio_montar_select_colunas');


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

#lblTipoRelatorio {position:absolute;left:0%;top:0%;;width:20%;}
#selTipoRelatorio {position:absolute;left:0%;top:8%;width:20%;}
#lblColunas {position:absolute;left:0%;top:20%;width:20%;}
#selColunas {position:absolute;left:0%;top:28%;width:20%;height:70%;}
#divColunasRelatorio {position:absolute;left:21%;top:28%;}

<?
PaginaSEI::getInstance()->fecharStyle();

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  var objColunasRelatorio = null;
  var objAjaxTipoRelatorio = null;

  function inicializar(){
    objAjaxTipoRelatorio = new infraAjaxMontarSelect('selColunas','<?=$strLinkAjaxTipoRelatorio?>');

    objAjaxTipoRelatorio.prepararExecucao = function(){
      return infraAjaxMontarPostPadraoSelect('null','','null') + '&strTipoRelatorio=' + document.getElementById('selTipoRelatorio').value;
    };

    objColunasRelatorio = new infraLupaSelect('selColunas','hdnColunasRelatorio','');
  }

  function trocarTipoRelatorio(){
    objAjaxTipoRelatorio.executar();
  }

  function OnSubmitForm(){
    if (!infraSelectSelecionado('selTipoRelatorio')) {
      alert('Selecione um Tipo de Relatório.');
      document.getElementById('selTipoRelatorio').focus();
      return false;
    }else{
      $('#hdnTipoRelatorioDescricao').val($('#selTipoRelatorio').find(":selected").text());
    }

    if (!infraSelectSelecionado('selColunas')) {
      alert('Selecione pelo menos uma Coluna.');
      document.getElementById('selColunas').focus();
      return false;
    }else{
      $('#selColunas option').prop('selected', true);
    }
  }



<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmContatoRelatorio" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <input type="hidden" id="hdnColunasRelatorio" name="hdnColunasRelatorio" value="<?=$_POST['hdnColunasRelatorio']?>" />
    <input type="hidden" id="hdnTipoRelatorioDescricao" name="hdnTipoRelatorioDescricao" value="" />
    <input type="hidden" id="hdnContatos" name="hdnContatos" value="<?=$strIdContatos?>" />

    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('25em');
    ?>

    <div id="divRelatorio" class="infraAreaDados" style="height:25em;" >
      <label id="lblTipoRelatorio" for="selTipoRelatorio" class="infraLabelObrigatorio">Tipo:</label>
      <select onchange="trocarTipoRelatorio()" id="selTipoRelatorio" name="selTipoRelatorio" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelTipoRelatorio?>
      </select>
      <label id="lblColunas" for="selColunas" class="infraLabelObrigatorio">Colunas:</label>
      <select id="selColunas" name="selColunas[]" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strItensSelColunas?>
      </select>
      <div id="divColunasRelatorio">
        <img id="imgRemoverColunasRelatorio" onclick="objColunasRelatorio.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Colunas" title="Remover Colunas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <br />
        <img id="imgAssuntosColunasRelatorio" onclick="objColunasRelatorio.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Coluna" title="Mover Acima Coluna" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgAssuntosColunasRelatorio" onclick="objColunasRelatorio.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Coluna" title="Mover Abaixo Coluna" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>

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