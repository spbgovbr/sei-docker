<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/09/2015 - criado por mga
 *
 **/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('grupo_contato_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selGrupo','optInstitucional','optContato','hdnTipoSelect'));

  switch($_GET['acao']){

    case 'grupo_contato_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Contatos','Selecionar Grupos de Contatos');
      break;
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strTipoPadrao = 'U';
  $strExibirOpcoes = 'visibility:hidden';
  if ( SessaoSEI::getInstance()->verificarPermissao('grupo_contato_institucional_selecionar')){
    $strTipoPadrao = 'I';
    $strExibirOpcoes = '';
  }

  $strTipoSelect = PaginaSEI::getInstance()->recuperarCampo('hdnTipoSelect',$strTipoPadrao);
  if (PaginaSEI::getInstance()->recuperarCampo('selGrupo') !== null){
    $numIdGrupoContato = PaginaSEI::getInstance()->recuperarCampo('selGrupo');
  }
  if ($_GET['id_grupo'] !== null && $_GET['acao_origem'] == 'grupo_contato_cadastrar'){
    $numIdGrupoContato = $_GET['id_grupo'];
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="button" onclick="pesquisar();" accesskey="P" id="btnPesquisar" name="btnPesquisar" value="Pesquisar" class="infraButton" style="width:8em"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('grupo_contato_cadastrar');
  if ($bolAcaoCadastrar && $strTipoSelect=='U'){
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_contato_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }

  if ($_GET['acao'] == 'grupo_contato_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
  $objRelGrupoContatoDTO->retNumIdContato();
  $objRelGrupoContatoDTO->retStrSiglaContato();
  $objRelGrupoContatoDTO->retStrNomeContato();

  $objRelGrupoContatoDTO->setStrPalavrasPesquisa($_POST['txtPalavrasPesquisaGrupoContato']);
  $objRelGrupoContatoDTO->setNumIdGrupoContato($numIdGrupoContato);

  PaginaSEI::getInstance()->prepararOrdenacao($objRelGrupoContatoDTO, 'NomeContato', InfraDTO::$TIPO_ORDENACAO_ASC);

  PaginaSEI::getInstance()->prepararPaginacao($objRelGrupoContatoDTO,500);

  $objRelGrupoContatoRN = new RelGrupoContatoRN();
  $arrObjRelGrupoContatoDTO = $objRelGrupoContatoRN->pesquisar($objRelGrupoContatoDTO);
  PaginaSEI::getInstance()->processarPaginacao($objRelGrupoContatoDTO);



  $numRegistros = InfraArray::contar($arrObjRelGrupoContatoDTO);

  $bolCheck = true;

  /*
  if ($bolAcaoImprimir){
    $bolCheck = true;
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
  }
  */

  if ($_GET['acao'] == 'grupo_contato_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Grupos.';
    $strCaptionTabela = 'Grupos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';

    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }

    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objRelGrupoContatoDTO,'Nome','NomeContato',$arrObjRelGrupoContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjRelGrupoContatoDTO[$i]->getNumIdContato(),ContatoINT::formatarNomeSiglaRI1224($arrObjRelGrupoContatoDTO[$i]->getStrNomeContato(),$arrObjRelGrupoContatoDTO[$i]->getStrSiglaContato())).'</td>';
      }

      $strResultado .= '<td align="left" valign="top">'.PaginaSEI::tratarHTML($arrObjRelGrupoContatoDTO[$i]->getStrNomeContato()).'</td>';
      $strResultado .= '<td width="5%" align="center" valign="top">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjRelGrupoContatoDTO[$i]->getNumIdContato());

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  $strCheckedInstitucional = '';
  $strCheckedContato = '';

  if ($strTipoSelect=='I') {

    $strCheckedInstitucional = 'checked="checked"';
    $strItensGrupo = GrupoContatoINT::montarSelectNomeInstitucional('null','&nbsp;',$numIdGrupoContato);
  }else{
    $strCheckedContato = 'checked="checked"';
    $strItensGrupo = GrupoContatoINT::montarSelectNomeContato('null','&nbsp;',$numIdGrupoContato);
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

  #lblGrupo{position:absolute;left:0%;top:0%;}
  #selGrupo{position:absolute;left:0%;top:20%;width:50%;}

  #divOptInstitucional{position:absolute; left:55%; top:20%;<?=$strExibirOpcoes?>}
  #divOptContato{position:absolute; left:75%; top:20%;<?=$strExibirOpcoes?>}

  #lblPalavrasPesquisaGrupoContato{position:absolute;left:0%;top:50%;}
  #txtPalavrasPesquisaGrupoContato{position:absolute;left:0%;top:70%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='grupo_contato_selecionar'){
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  }else{
  //document.getElementById('btnFechar').focus();
  setTimeout("document.getElementById('btnFechar').focus()", 50);
  }


  infraEfeitoTabelas();
  }

  function carregarSelect(tipo){
  document.getElementById('hdnTipoSelect').value=tipo;
  if (document.getElementById('selGrupo').options.length){
  document.getElementById('selGrupo').options[0].selected = true;
  }
  document.getElementById('frmGrupoSelecao').submit();
  }

  function tratarDigitacao(ev){
  if (infraGetCodigoTecla(ev) == 13){
  document.getElementById('frmGrupoSelecao').submit();
  }
  return true;
  }

  function pesquisar(){
  document.getElementById('hdnPesquisar').value = '1';
  document.getElementById('frmGrupoSelecao').submit();
  }

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>

  <form id="frmGrupoSelecao" method="post"	action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('10em');
    ?>

    <div id="divOptInstitucional" class="infraDivRadio">
      <input type="radio" name="rdoGrupo" id="optInstitucional"	value="optInstitucionalEnviado" onclick="carregarSelect('I');" <?=$strCheckedInstitucional?> class="infraRadio" />
      <label id="lblInstitucional" accesskey="I" for="optInstitucional"	class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><span class="infraTeclaAtalho">I</span>nstitucional</label>
    </div>

    <div id="divOptContato" class="infraDivRadio">
      <input type="radio" name="rdoGrupo" id="optContato" value="optContatoEnviado" onclick="carregarSelect('U');" <?=$strCheckedContato?> class="infraRadio" />
      <label id="lblContato" accesskey="U" for="optContato" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><span class="infraTeclaAtalho">U</span>nidade</label>
    </div>

    <input type="hidden" name="hdnTipoSelect" id="hdnTipoSelect" value="<?=$strTipoSelect;?>" />
    <div>
      <label id="lblGrupo" for="selGrupo" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
      <select id="selGrupo" name="selGrupo" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensGrupo?>
      </select>
    </div>

    <label id="lblPalavrasPesquisaGrupoContato" for="txtPalavrasPesquisaGrupoContato" accesskey="" class="infraLabelOpcional">Palavras-chave para pesquisa:</label>
    <input type="text" id="txtPalavrasPesquisaGrupoContato" name="txtPalavrasPesquisaGrupoContato" class="infraText" value="<?=$_POST['txtPalavrasPesquisaGrupoContato']?>" onkeypress="return tratarDigitacao(event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <input type="hidden" id="hdnPesquisar" name="hdnPesquisar" value="<?=$_POST['hdnPesquisar']?>" />

    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

  </form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>