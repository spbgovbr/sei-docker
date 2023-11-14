<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 02/05/2008 - criado por mga
 *
 * Versão do Gerador de Código: 1.16.0
 *
 * Versão no CVS: $Id$
 */

try {
  //require_once 'Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->prepararSelecao('infra_log_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  PaginaInfra::getInstance()->salvarCamposPost(array('selTipos'));

  $strParametros = '';
  if (isset($_GET['auto_atualizar'])) {
    $strParametros .= '&auto_atualizar=' . $_GET['auto_atualizar'];
  }

  PaginaInfra::getInstance()->salvarCamposPost(array('txtDthInicialLog', 'txtDthFinalLog', 'txtIpLog', 'txtTextoLog'));

  switch ($_GET['acao']) {
    case 'infra_log_excluir':
      try {
        $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
        $arrObjInfraLogDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objInfraLogDTO = new InfraLogDTO();
          $objInfraLogDTO->setNumIdInfraLog($arrStrIds[$i]);
          $arrObjInfraLogDTO[] = $objInfraLogDTO;
        }
        $objInfraLogRN = new InfraLogRN();
        $objInfraLogRN->excluir($arrObjInfraLogDTO);
        PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaInfra::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoInfra::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'].'&acao_origem='.$_GET['acao'] . $strParametros));
      die;

    case 'infra_log_selecionar':
      $strTitulo = PaginaInfra::getInstance()->getTituloSelecao('Selecionar Logs', 'Selecionar Logs');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'infra_log_cadastrar') {
        if (isset($_GET['id_infra_log'])) {
          PaginaInfra::getInstance()->adicionarSelecionado($_GET['id_infra_log']);
        }
      }
      break;

    case 'infra_log_listar':
      $strTitulo = 'Logs';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  $objInfraLog = PaginaInfra::getInstance()->getObjInfraLog();

  $bolTipos = false;
  if ($objInfraLog != null && $objInfraLog->isBolTratarTipos()) {
    $bolTipos = true;
  }

  $objInfraLogDTO = new InfraLogDTO();
  $objInfraLogDTO->retNumIdInfraLog();
  $objInfraLogDTO->retDthLog();
  $objInfraLogDTO->retStrTextoLog();
  $objInfraLogDTO->retStrIp();

  if ($bolTipos) {
    $objInfraLogDTO->retStrStaTipo();

    $arrTipos = PaginaInfra::getInstance()->recuperarCampo('selTipos',array_keys(InfraLog::getArrTipos()));

    if (!is_array($arrTipos)) {
      $arrTipos = array($arrTipos);
    }

    $objInfraLogDTO->setStrStaTipo($arrTipos);
  }


  $dthInicial = PaginaInfra::getInstance()->recuperarCampo('txtDthInicialLog');
  if (!InfraString::isBolVazia($dthInicial)){
    $objInfraLogDTO->setDthInicial($dthInicial);
  }

  $dthFinal = PaginaInfra::getInstance()->recuperarCampo('txtDthFinalLog');
  if (!InfraString::isBolVazia($dthFinal)){
    $objInfraLogDTO->setDthFinal($dthFinal);
  }

  $strIp = PaginaInfra::getInstance()->recuperarCampo('txtIpLog');
  if (!InfraString::isBolVazia($strIp)){
    $objInfraLogDTO->setStrIp($strIp);
  }

  $strTextoLog = PaginaInfra::getInstance()->recuperarCampo('txtTextoLog');
  if (!InfraString::isBolVazia($strTextoLog)){
    $objInfraLogDTO->setStrTextoLog($strTextoLog);
  }

  //PaginaInfra::getInstance()->prepararOrdenacao($objInfraLogDTO, 'IdInfraLog', InfraDTO::$TIPO_ORDENACAO_DESC);
  $objInfraLogDTO->setOrdNumIdInfraLog(InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaInfra::getInstance()->prepararPaginacao($objInfraLogDTO);

  $arrObjInfraLogDTO = array();

  if ($_GET['acao_origem']=='infra_log_listar' || $_GET['acao_origem']=='infra_log_excluir') {
    try {
      $objInfraLogRN = new InfraLogRN();
      $arrObjInfraLogDTO = $objInfraLogRN->pesquisar($objInfraLogDTO);
    } catch (Exception $e) {
      PaginaInfra::getInstance()->processarExcecao($e);
    }
  }

  PaginaInfra::getInstance()->processarPaginacao($objInfraLogDTO);
  $numRegistros = count($arrObjInfraLogDTO);

  $arrTiposInfraLog = InfraLog::getArrTipos();

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='infra_log_selecionar'){
      $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_log_consultar');
      $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_log_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolCheck = true;
    }else{
      $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_log_consultar');
      $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_log_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_log_excluir');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_log_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Logs.';
    $strCaptionTabela = 'Logs';

    $strResultado .= '<table id="tblInfraLog" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="3%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    }

    //if ($bolTipos) {
    //  $strResultado .= '<th class="infraTh" width="10%">' . PaginaInfra::getInstance()->getThOrdenacao($objInfraLogDTO, 'Tipo', 'StaTipo', $arrObjInfraLogDTO) . '</th>' . "\n";
    //}

    $strResultado .= '<th class="infraTh" width="15%">Identificação</th>'."\n";
    $strResultado .= '<th class="infraTh">Texto</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="10%">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraLogDTO,'IP','Ip',$arrObjInfraLogDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrEscura">')?'<tr class="infraTrClara">':'<tr class="infraTrEscura">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" align="center">'.PaginaInfra::getInstance()->getTrCheck($i,$arrObjInfraLogDTO[$i]->getNumIdInfraLog(),$arrObjInfraLogDTO[$i]->getDthLog()).'</td>';
      }

      $strResultado .= '<td valign="top" align="left"><b>Data:</b><br />'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraLogDTO[$i]->getDthLog()).'<br/><br />';

      if ($bolTipos) {
        $strResultado .= '<b>Tipo:</b><br />'.($arrObjInfraLogDTO[$i]->getStrStaTipo()!=null?$arrTiposInfraLog[$arrObjInfraLogDTO[$i]->getStrStaTipo()]:'&nbsp;').'<br/><br />';
      }

      if (!InfraString::isBolVazia($arrObjInfraLogDTO[$i]->getStrIp())) {
        $strResultado .= '<b>IP:</b><br />' . PaginaInfra::getInstance()->tratarHTML($arrObjInfraLogDTO[$i]->getStrIp()) . '</td>';
      }


      $strLog =  $arrObjInfraLogDTO[$i]->getStrTextoLog();
      $strLog = PaginaInfra::getInstance()->tratarHTML($strLog);
      $strLog = str_replace('\n', '',$strLog);
      $strLog = str_replace("\n", '<br />',$strLog);
      $strLog = str_replace('&lt;br /&gt;','<br />',$strLog);
      $strResultado .= '<td valign="top">'.$strLog.'</td>';

      //$strResultado .= '<td valign="top">'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraLogDTO[$i]->getStrIp()).'</td>';

      $strResultado .= '<td  valign="top" align="center">';
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_log_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_infra_log='.$arrObjInfraLogDTO[$i]->getNumIdInfraLog()).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeConsultar().'" title="Consultar Log" alt="Consultar Log" class="infraImg" /></a> ';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_log_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_infra_log='.$arrObjInfraLogDTO[$i]->getNumIdInfraLog()).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeAlterar().'" title="Alterar Log" alt="Alterar Log" class="infraImg" /></a> ';
      }

      if ($bolAcaoExcluir){
        $strId = $arrObjInfraLogDTO[$i]->getNumIdInfraLog();
        $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript($arrObjInfraLogDTO[$i]->getDthLog());
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeExcluir().'" title="Excluir Log" alt="Excluir Log" class="infraImg" /></a> ';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'infra_log_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  if ($bolTipos) {
    $strOptionsTipo = '';
    foreach ($arrTiposInfraLog as $strStaTipo => $strDescricaoTipo) {
      $strOptionsTipo .= '<option value="' . $strStaTipo . '"'.(in_array($strStaTipo, $arrTipos)?' selected="selected"':'').'>' . InfraPagina::tratarHTML($strDescricaoTipo) . '</option>' . "\n";
    }
  }

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo.(is_numeric($objInfraLogDTO->getNumTotalRegistros())?' ('.$objInfraLogDTO->getNumTotalRegistros().')':''));
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>

  #lblTipos {position:absolute;left:0%;top:5%;}
  #selTipos, .multipleSelect  {position:absolute;left:11%;top:0%;width:15%;}

  #lblDthInicial {position:absolute;left:0%;top:25%;}
  #txtDthInicialLog {position:absolute;left:11%;top:20%;width:16%;}
  #imgCalDthInicial {position:absolute;left:28%;top:20%;}

  #lblDthFinal {position:absolute;left:31.5%;top:23%;}
  #txtDthFinalLog {position:absolute;left:34%;top:20%;width:16%;}
  #imgCalDthFinal {position:absolute;left:51%;top:20%;}

  #lblTextoLog {position:absolute;left:0%;top:45%;}
  #txtTextoLog {position:absolute;left:11%;top:40%;width:60%;}

  #lblIp {position:absolute;left:0%;top:65%;}
  #txtIpLog {position:absolute;left:11%;top:60%;width:16%;}

  #divSinAutoAtualizar {position:absolute;left:11%;top:82%;}

  #tblInfraLog {
  table-layout: fixed;
  width: 100%;
  }

  #tblInfraLog  td {
  word-wrap: break-word;         /* All browsers since IE 5.5+ */
  overflow-wrap: break-word;     /* Renamed property in CSS3 draft spec */
  }

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
  //<script>

  <?if ($bolTipos) {?>
  $( document ).ready(function() {
    $("#selTipos").multipleSelect({
      filter: false,
      minimumCountSelected: 1,
      allSelected: 'Todos',
      selectAll: true,
      width: '15.9%'
    });
  });

  <?}?>

  function inicializar(){

    if ('<?=$_GET['acao']?>'=='infra_log_selecionar'){
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
    }else{
      document.getElementById('btnFechar').focus();
    }



    if ('<?=$_GET['auto_atualizar']?>'=='S' || '<?=$_POST['chkSinAutoAtualizar']?>'=='on'){
      infraAdicionarEvento(window,'mousedown',autoAtualizar);
      infraAdicionarEvento(window,'keydown',autoAtualizar);
      infraAdicionarEvento(window,'scroll',autoAtualizar);
      document.getElementById('chkSinAutoAtualizar').checked = true;
      autoAtualizar();
    }

    infraEfeitoImagens();
    //infraEfeitoTabelas();


  }


  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
    if (confirm("Confirma exclusão do Log \""+desc+"\"?")){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmInfraLogLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmInfraLogLista').submit();
    }
  }

  function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum Log selecionado.');
      return;
    }
    if (confirm("Confirma exclusão dos Logs selecionados?")){
      document.getElementById('hdnInfraItemId').value='';
      document.getElementById('frmInfraLogLista').action='<?=$strLinkExcluir?>';
      document.getElementById('frmInfraLogLista').submit();
    }
  }
  <? } ?>

  function validarForm(){

    <?if ($bolTipos) {?>
    if ($("#selTipos").multipleSelect("getSelects").length==0) {
      alert('Nenhum Tipo selecionado.');
      return false;
    }
    <?}?>

    if (infraTrim(document.getElementById('txtDthInicialLog').value)!=''){
      if (!infraValidarDataHora(document.getElementById('txtDthInicialLog'))){
        document.getElementById('txtDthInicialLog').focus();
        return false;
      }
    }

    if (infraTrim(document.getElementById('txtDthFinalLog').value)!=''){
      if (!infraValidarDataHora(document.getElementById('txtDthFinalLog'))){
        document.getElementById('txtDthFinalLog').focus();
        return false;
      }
    }

    return true;
  }

  var timer = null;

  function autoAtualizar(){

    //faz reset em mousedown, keydown e scroll
    if (timer != null){
      clearInterval(timer);
      timer = null;
    }

    if (document.getElementById('chkSinAutoAtualizar').checked && timer==null){
      timer = setInterval(function() {
        window.location.href = window.location;
      }, 60000);
    }
  }

  function configurarAutoAtualizar(){
    if (document.getElementById('chkSinAutoAtualizar').checked){
      window.location = '<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&auto_atualizar=S')?>';
    }else{
      window.location = '<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&auto_atualizar=N')?>';
    }
  }
  //</script>
<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmInfraLogLista" method="post" onsubmit="return validarForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
    <?
    PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaInfra::getInstance()->abrirAreaDados('15em','style="overflow:visible;"');
    ?>

    <?if ($bolTipos) {?>
      <label id="lblTipos" class="infraLabelObrigatorio">Tipo:</label>
      <select style="display: none" multiple id="selTipos" name="selTipos[]" class="infraSelect multipleSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
        <?=$strOptionsTipo?>
      </select>
    <?}?>

    <label id="lblDthInicial" for="txtDthInicialLog" accesskey="" class="infraLabelOpcional" >Período:</label>
    <input type="text" id="txtDthInicialLog" name="txtDthInicialLog" onkeypress="return infraMascara(this, event,'##/##/#### ##:##')" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($dthInicial)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
    <img src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" id="imgCalDthInicial" title="Selecionar Data/Hora Inicial" alt="Selecionar Data/Hora Inicial" class="infraImg" onclick="infraCalendario('txtDthInicialLog',this,true,'<?=InfraData::getStrDataAtual().' 00:00'?>');" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

    <label id="lblDthFinal" for="txtDthFinalLog" accesskey="" class="infraLabelOpcional" >a</label>
    <input type="text" id="txtDthFinalLog" name="txtDthFinalLog" onkeypress="return infraMascara(this, event,'##/##/#### ##:##')" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($dthFinal)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
    <img src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" id="imgCalDthFinal" title="Selecionar Data/Hora Final" alt="Selecionar Data/Hora Final" class="infraImg" onclick="infraCalendario('txtDthFinalLog',this,true,'<?=InfraData::getStrDataAtual().' 23:59'?>');" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

    <label id="lblTextoLog" for="txtTextoLog" accesskey="T" class="infraLabelOpcional"><span class="infraTeclaAtalho">T</span>exto:</label>
    <input type="text" id="txtTextoLog" name="txtTextoLog" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strTextoLog)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

    <label id="lblIp" for="txtIpLog" accesskey="" class="infraLabelOpcional">IP:</label>
    <input type="text" id="txtIpLog" name="txtIpLog" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strIp)?>" onkeypress="return infraMascaraNumero(this,event,16,'.');" maxlength="16" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

    <div id="divSinAutoAtualizar" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinAutoAtualizar" name="chkSinAutoAtualizar" class="infraCheckbox" onclick="configurarAutoAtualizar();"  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
      <label id="lblSinAutoAtualizar" for="chkSinAutoAtualizar" accesskey="" class="infraLabelCheckbox">Atualizar automaticamente a cada minuto</label>
    </div>

    <?
    PaginaInfra::getInstance()->fecharAreaDados();
    PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    PaginaInfra::getInstance()->montarAreaDebug();
    PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>