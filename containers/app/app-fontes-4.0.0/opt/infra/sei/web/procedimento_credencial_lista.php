<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/02/2011 - criado por mga
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objAtribuirDTO = new AtribuirDTO();
  
  switch($_GET['acao']){
  	
  	/*
    case 'procedimento_atribuicao_trocar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVocativoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVocativoDTO = new VocativoDTO();
          $objVocativoDTO->setNumIdVocativo($arrStrIds[$i]);
          $arrObjVocativoDTO[] = $objVocativoDTO;
        }
        $objVocativoRN = new VocativoRN();
        $objVocativoRN->excluirRN0311($arrObjVocativoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
    */
    
    case 'procedimento_credencial_listar':
      $strTitulo = 'Processos com Credencial na Unidade';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $numRegistros = 0;


  $objPesquisaSigilosoDTO = new PesquisaSigilosoDTO();
  $objPesquisaSigilosoDTO->retStrProtocoloFormatado();
  $objPesquisaSigilosoDTO->retStrDescricao();
  $objPesquisaSigilosoDTO->retStrNomeTipoProcedimento();
  $objPesquisaSigilosoDTO->retDtaGeracao();
  $objPesquisaSigilosoDTO->setStrSinAbertosFechados('S');
  $objPesquisaSigilosoDTO->setStrSinAnotacoes('S');
  $objPesquisaSigilosoDTO->setStrSinObservacoes('S');

  $objPesquisaSigilosoDTO->setStrSinFiltroProtocolo('S');
  $objPesquisaSigilosoDTO->setStrSinFiltroTipoProcedimento('S');
  $objPesquisaSigilosoDTO->setStrSinFiltroInteressado('S');
  $objPesquisaSigilosoDTO->setStrSinFiltroObservacoes('S');
  $objPesquisaSigilosoDTO->setStrSinFiltroPeriodoAutuacao('S');

  ProcedimentoINT::montarCamposPesquisaSigiloso($objPesquisaSigilosoDTO,$strCssSigilosos,$strJsSigilosos,$strJsInicializarSigilosos,$strJsValidarSigilosos,$strHtmlSigilosos);

  PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaSigilosoDTO, 'Geracao', InfraDTO::$TIPO_ORDENACAO_DESC);

  PaginaSEI::getInstance()->prepararPaginacao($objPesquisaSigilosoDTO);

  try{
    $objProcedimentoRN = new ProcedimentoRN();
    $arrObjProcedimentoDTO = $objProcedimentoRN->pesquisarSigilososCredencialUnidade($objPesquisaSigilosoDTO);
  }catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
  }

  PaginaSEI::getInstance()->processarPaginacao($objPesquisaSigilosoDTO);
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnPesquisar" onclick="limpar();" value="Limpar" class="infraButton"><span class="infraTeclaAtalho">L</span>impar</button>';

  $numRegistros =  count($arrObjProcedimentoDTO);

  if ($numRegistros > 0){

  	$bolAcaoTransferir = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_transferir');
    $bolAcaoRegistrarAnotacao = SessaoSEI::getInstance()->verificarPermissao('anotacao_registrar');
  	
    if ($bolAcaoTransferir){
      $arrComandos[] = '<button type="button" accesskey="T" id="btnTransferir" value="Transferir" onclick="acaoTransferenciaMultipla();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransferir</button>';
      $strLinkTransferir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_transferir&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }

    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos.';
    $strCaptionTabela = 'Processos';

    $strResultado .= '<table width="91%" id="tblProcessosDetalhado" class="infraTable tabelaProcessos" summary="'.$strSumarioTabela.'">'."\n"; //81
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th colspan="2" class="infraTh" width="25%">Processo</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaSigilosoDTO,'Autuação','Geracao',$arrObjProcedimentoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaSigilosoDTO,'Tipo','NomeTipoProcedimento',$arrObjProcedimentoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh">Observações da Unidade</th>' . "\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      //$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      //$strResultado .= $strCssTr;
      
    	$strResultado .= '<tr class="infraTrClara">';

    	$strCorProcesso = ' class="'.($arrObjProcedimentoDTO[$i]->getStrSinAberto()=='S'?'protocoloAberto':'protocoloFechado').'"';

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjProcedimentoDTO[$i]->getDblIdProcedimento(),$arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</td>';
      $strResultado .= '<td align="center">'.AnotacaoINT::montarIconeAnotacao($arrObjProcedimentoDTO[$i]->getObjAnotacaoDTO(),$bolAcaoRegistrarAnotacao, $arrObjProcedimentoDTO[$i]->getDblIdProcedimento()).'</td>';
      $strResultado .= '<td align="center"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjProcedimentoDTO[$i]->getDblIdProcedimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" '.PaginaSEI::montarTitleTooltip($arrObjProcedimentoDTO[$i]->getStrDescricaoProtocolo(),$arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).' '.$strCorProcesso.'>'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</a></td>'."\n";
      $strResultado .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getDtaGeracaoProtocolo()).'</td>'."\n";
      $strResultado .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'</td>'."\n";

      $strResultado .= '<td align="left" valign="top">';
      if ($arrObjProcedimentoDTO[$i]->getObjObservacaoDTO() == null){
        $strResultado .= '&nbsp;';
      }else{
        $strResultado .= PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getObjObservacaoDTO()->getStrDescricao());
      }
      $strResultado .= '</td>';
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
//  $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento'])).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_outros');

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
<?=$strCssSigilosos;?>


table.tabelaProcessos {
  background-color: white;
  border: 0 solid white;
  border-spacing: 0;
}

tr.infraTrClara td {
  border-bottom: 1px dotted #666;
  padding: .3em;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarUsuario = null;
  <?=$strJsSigilosos;?>


<?if ($bolAcaoTransferir) {?>

  function acaoTransferenciaMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum processo selecionado.');
      return;
    }
    infraAbrirJanela('<?=$strLinkTransferir?>','janelaTransferencia',700,250,'location=0,status=1,resizable=1,scrollbars=1');

    document.getElementById('hdnInfraItemId').value='';
    var frm=document.getElementById('frmProcedimentoCredencialLista');
    frm.target='janelaTransferencia';
    var act=frm.action;
    frm.action='<?=$strLinkTransferir?>';
    frm.submit();
    frm.action=act;
    frm.target='_self';
  }
<?}?>

function inicializar(){

  <?=$strJsInicializarSigilosos;?>

  infraEfeitoTabelas();
}

function abrirProcesso(link){
  document.getElementById('divInfraBarraComandosSuperior').style.visibility = 'hidden';
  document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
  infraOcultarMenuSistemaEsquema();
  document.getElementById('frmProcedimentoCredencialLista').action = link;
  document.getElementById('frmProcedimentoCredencialLista').submit();
}

function onSubmitForm(){
  <?=$strJsValidarSigilosos;?>
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoCredencialLista" onsubmit="return onSubmitForm()" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

  PaginaSEI::getInstance()->abrirAreaDados();
  echo $strHtmlSigilosos;
  PaginaSEI::getInstance()->fecharAreaDados();

  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>