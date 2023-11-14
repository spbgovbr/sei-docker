<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
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

  PaginaSEI::getInstance()->prepararSelecao('mapeamento_assunto_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selTabelaAssuntosDestino','txtPalavrasPesquisaMapeamentoAssuntos'));

  if (isset($_POST['hdnFlag'])) {
    PaginaSEI::getInstance()->salvarCampo('chkSinAssuntosNaoMapeados', $_POST['chkSinAssuntosNaoMapeados']);
  }

  $strParametros = '';
  if(isset($_GET['id_tabela_assuntos_origem'])){
    $strParametros .= '&id_tabela_assuntos_origem='.$_GET['id_tabela_assuntos_origem'];
  }

  switch($_GET['acao']){
    case 'mapeamento_assunto_gerenciar':
      try{

        $arrObjMapeamentoAssuntoDTO = array();
        foreach(array_keys($_POST) as $strKeyPost){
          if (substr($strKeyPost,0,10) == 'txtAssunto'){
            $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
            $objMapeamentoAssuntoDTO->setNumIdAssuntoOrigem(substr($strKeyPost,10));
            $objMapeamentoAssuntoDTO->setNumIdAssuntoDestino($_POST['hdnIdAssunto'.substr($strKeyPost,10)]);
            $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoDestino($_POST['selTabelaAssuntosDestino']);
            $arrObjMapeamentoAssuntoDTO[] = $objMapeamentoAssuntoDTO;
          }
        }

        $objMapeamentoAssuntoRN = new MapeamentoAssuntoRN();
        $objMapeamentoAssuntoRN->gerenciar($arrObjMapeamentoAssuntoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'mapeamento_assunto_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Mapeamento de Assunto','Selecionar Mapeamentos de Assuntos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='mapeamento_assunto_cadastrar'){
        if (isset($_GET['id_assunto_origem']) && isset($_GET['id_assunto_destino'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_assunto_origem'].'-'.$_GET['id_assunto_destino']);
        }
      }
      break;

    case 'mapeamento_assunto_listar':
      $strTitulo = 'Mapeamentos de Assuntos';

      if (isset($_POST['sbmSalvar'])){

      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
  $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoOrigem($_GET['id_tabela_assuntos_origem']);

  if (PaginaSEI::getInstance()->recuperarCampo('selTabelaAssuntosDestino')!=$_GET['id_tabela_assuntos_origem']) {
    $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoDestino(PaginaSEI::getInstance()->recuperarCampo('selTabelaAssuntosDestino'));
  }else{
    $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoDestino(null);
  }

  $objMapeamentoAssuntoDTO->setStrPalavrasPesquisa(PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaMapeamentoAssuntos'));
  $objMapeamentoAssuntoDTO->setStrSinAssuntosNaoMapeados(PaginaSEI::getInstance()->getCheckbox(PaginaSEI::getInstance()->recuperarCampo('chkSinAssuntosNaoMapeados')));

  PaginaSEI::getInstance()->prepararOrdenacao($objMapeamentoAssuntoDTO, 'CodigoEstruturadoAssuntoOrigem', InfraDTO::$TIPO_ORDENACAO_ASC);

  if ($objMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoDestino()!=null){

    PaginaSEI::getInstance()->prepararPaginacao($objMapeamentoAssuntoDTO,100);

    $objMapeamentoAssuntoRN = new MapeamentoAssuntoRN();
    $arrObjMapeamentoAssuntoDTO = $objMapeamentoAssuntoRN->pesquisar($objMapeamentoAssuntoDTO);

    PaginaSEI::getInstance()->processarPaginacao($objMapeamentoAssuntoDTO);

  }else{
    $arrObjMapeamentoAssuntoDTO = array();
  }


  $numRegistros = InfraArray::contar($arrObjMapeamentoAssuntoDTO);

  if ($numRegistros > 0){

    $bolAcaoGerenciar = SessaoSEI::getInstance()->verificarPermissao('mapeamento_assunto_gerenciar');
    if ($bolAcaoGerenciar){
      $arrComandos[] = '<button type="button" accesskey="S" id="btnSalvar" value="Salvar" onclick="gerenciar();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strLinkGerenciar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=mapeamento_assunto_gerenciar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
    }

    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('mapeamento_assunto_excluir');

    $strResultado = '';
    $strAjaxVariaveis = '';
    $strAjaxInicializar = '';

    $strSumarioTabela = 'Tabela de Assuntos para Mapeamento.';
    $strCaptionTabela = 'Assuntos para Mapeamento';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';

    $strResultado .= '<th width="45%" class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objMapeamentoAssuntoDTO,'Assunto Origem','CodigoEstruturadoAssuntoOrigem',$arrObjMapeamentoAssuntoDTO).'</th>'."\n";
    $strResultado .= '<th width="45%" class="infraTh">Assunto Destino</th>'."\n";

    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $numIdAssuntoOrigem = $arrObjMapeamentoAssuntoDTO[$i]->getNumIdAssuntoOrigem();
      $numIdAssuntoDestino = $arrObjMapeamentoAssuntoDTO[$i]->getNumIdAssuntoDestino();

      if ($arrObjMapeamentoAssuntoDTO[$i]->getStrSinAtivoAssuntoOrigem()=='S'){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      $strResultado .= '<td>'.PaginaSEI::tratarHTML(AssuntoINT::formatarCodigoDescricaoRI0568($arrObjMapeamentoAssuntoDTO[$i]->getStrCodigoEstruturadoAssuntoOrigem(),$arrObjMapeamentoAssuntoDTO[$i]->getStrDescricaoAssuntoOrigem())).'</td>';
      $strResultado .= '<td> <input type="text" id="txtAssunto'.$numIdAssuntoOrigem.'" name="txtAssunto'.$numIdAssuntoOrigem.'" class="infraText" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" style="width:99.5%" /><input type="hidden" id="hdnIdAssunto'.$numIdAssuntoOrigem.'" name="hdnIdAssunto'.$numIdAssuntoOrigem.'" class="infraText" value="" /></td>';

      $strResultado .= '</tr>'."\n";

      $strAjaxVariaveis .= 'var objAutoCompletarAssunto'.$numIdAssuntoOrigem.';'."\n";

      $strAjaxInicializar .= '  objAutoCompletarAssunto'.$numIdAssuntoOrigem.' = new infraAjaxAutoCompletar(\'hdnIdAssunto'.$numIdAssuntoOrigem.'\',\'txtAssunto'.$numIdAssuntoOrigem.'\', linkAutoCompletar);'."\n".
      '  objAutoCompletarAssunto'.$numIdAssuntoOrigem.'.prepararExecucao = function(){'."\n".
      '    return \'id_tabela_assuntos='.$objMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoDestino().'&palavras_pesquisa=\'+document.getElementById(\'txtAssunto'.$numIdAssuntoOrigem.'\').value;'."\n".
      '  }'."\n".
      '  objAutoCompletarAssunto'.$numIdAssuntoOrigem.'.processarResultado = function(){'."\n".
      '    bolAlteracao = true;'."\n".
      '  }'."\n\n";

      //processarResultado

      if ($numIdAssuntoDestino!=null){
        $strAjaxInicializar .= '  objAutoCompletarAssunto'.$numIdAssuntoOrigem.'.selecionar(\''.$numIdAssuntoDestino.'\',\''.PaginaSEI::getInstance()->formatarParametrosJavaScript(AssuntoINT::formatarCodigoDescricaoRI0568($arrObjMapeamentoAssuntoDTO[$i]->getStrCodigoEstruturadoAssuntoDestino(),$arrObjMapeamentoAssuntoDTO[$i]->getStrDescricaoAssuntoDestino())).'\');'."\n\n";
      }

    }

    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_tabela_assuntos_origem'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
  $objTabelaAssuntosDTO->retStrNome();
  $objTabelaAssuntosDTO->retStrSinAtual();

  if (!PaginaSEI::getInstance()->isBolPaginaSelecao()) {
    $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos_origem']);
  }else{
    $objTabelaAssuntosDTO->setStrSinAtual('S');
  }

  $objTabelaAssuntosRN = new TabelaAssuntosRN();
  $objTabelaAssuntosDTOOrigem = $objTabelaAssuntosRN->consultar($objTabelaAssuntosDTO);

  $strItensSelTabelaAssuntosDestino = TabelaAssuntosINT::montarSelectNomeMapeamento('null','&nbsp;',$objMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoDestino(),$objMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoOrigem());

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
#lblTabelaAssuntosOrigem {position:absolute;left:0%;top:0%;width:40%;}
#txtTabelaAssuntosOrigem {position:absolute;left:0%;top:11%;width:40%;}

#lblTabelaAssuntosDestino {position:absolute;left:0%;top:28%;width:40%;}
#selTabelaAssuntosDestino {position:absolute;left:0%;top:39%;width:40%;}

#lblPalavrasPesquisaMapeamentoAssuntos {position:absolute;left:0%;top:56%;width:70%;}
#txtPalavrasPesquisaMapeamentoAssuntos {position:absolute;left:0%;top:67%;width:70%;}

#divSinAssuntosNaoMapeados {position:absolute;left:0%;top:85%;}

table input.infraText {font-size:1em}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="text/javascript">

<?=$strAjaxVariaveis?>

var bolAlteracao = false;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='mapeamento_assunto_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  var linkAutoCompletar = '<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_mapeamento')?>';

<?=$strAjaxInicializar?>

  bolAlteracao = false;

  //infraEfeitoTabelas();
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Mapeamento de Assunto \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMapeamentoAssuntoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMapeamentoAssuntoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Mapeamento de Assunto selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Mapeamentos de Assuntos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMapeamentoAssuntoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMapeamentoAssuntoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoGerenciar){ ?>

function gerenciar(){

  if (!infraSelectSelecionado('selTabelaAssuntosDestino')){
    alert('Selecione a tabela de assuntos de destino.')
    document.getElementById('selTabelaAssuntosDestino').focus();
    return false;
  }

  document.getElementById('frmMapeamentoAssuntoLista').target = '_self';
  document.getElementById('frmMapeamentoAssuntoLista').action = '<?=$strLinkGerenciar?>';
  document.getElementById('frmMapeamentoAssuntoLista').submit();

  infraExibirAviso();
}

<? } ?>

function OnSubmitForm() {

  if (bolAlteracao && !confirm('Existem alterações que não foram salvas.\n\nDeseja continuar?')){
    return false;
  }

  return true;
}


//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMapeamentoAssuntoLista" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('17em');
  ?>

  <label id="lblTabelaAssuntosOrigem" class="infraLabelObrigatorio">Tabela Origem:</label>
  <input type="text" id="txtTabelaAssuntosOrigem" name="txtTabelaAssuntosOrigem" readonly="readonly" class="infraText infraReadOnly" value=" <?=PaginaSEI::tratarHTML($objTabelaAssuntosDTOOrigem->getStrNome())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblTabelaAssuntosDestino" for="selTabelaAssuntosDestino" accesskey="" class="infraLabelObrigatorio">Tabela Destino:</label>
  <select id="selTabelaAssuntosDestino" name="selTabelaAssuntosDestino" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelTabelaAssuntosDestino?>
  </select>

  <label id="lblPalavrasPesquisaMapeamentoAssuntos" for="txtPalavrasPesquisaMapeamentoAssuntos" class="infraLabelOpcional">Palavras para Pesquisa:</label>
  <input type="text" id="txtPalavrasPesquisaMapeamentoAssuntos" name="txtPalavrasPesquisaMapeamentoAssuntos" value="<?=PaginaSEI::tratarHTML($objMapeamentoAssuntoDTO->getStrPalavrasPesquisa())?>" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <div id="divSinAssuntosNaoMapeados" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinAssuntosNaoMapeados" name="chkSinAssuntosNaoMapeados" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objMapeamentoAssuntoDTO->getStrSinAssuntosNaoMapeados())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <label id="lblSinAssuntosNaoMapeados" for="chkSinAssuntosNaoMapeados" accesskey="" class="infraLabelCheckbox" >Exibir apenas assuntos sem mapeamento definido</label>
  </div>

  <input type="hidden" name="hdnFlag" value="1" />
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