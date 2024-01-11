<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcesso','selTipoDocumento','selLocalizador'));
  
  switch($_GET['acao']){

    case 'arquivamento_eliminacao_listar':
      $strTitulo = 'Documentos para Eliminação';
      break;

    case 'arquivamento_eliminar':

      try{
        //dto especifico para eliminar um documento preparado para eliminacao
        $objArquivamentoEliminacaoDTO = new ArquivamentoEliminacaoDTO();
        //pede senha, que deve ser a do usuario logado
        $objArquivamentoEliminacaoDTO->setStrSenha($_POST['pwdSenha']);
        //documentos preparados (checkboxs)
        $objArquivamentoEliminacaoDTO->setArrObjArquivamentoDTO(InfraArray::gerarArrInfraDTO('ArquivamentoDTO','IdProtocolo',PaginaSEI::getInstance()->getArrStrItensSelecionados()));
        //desarquiva para eliminacao
        $objArquivamentoRN = new ArquivamentoRN();
        $objArquivamentoRN->desarquivarParaEliminacao($objArquivamentoEliminacaoDTO);

        //PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e, true);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  //dto para buscar documentos arquivados preparados para eliminacao
  $objArquivamentoDTO = new ArquivamentoDTO();

  if ($_GET['acao_origem']=='edital_eliminacao_arquivados_listar'){
    $numIdTipoProcesso = '';
    $numIdTipoDocumento = '';
    $numIdLocalizador = $_GET['id_localizador'];
  }else {
    $numIdTipoProcesso = PaginaSEI::getInstance()->recuperarCampo('selTipoProcesso');
    $numIdTipoDocumento = PaginaSEI::getInstance()->recuperarCampo('selTipoDocumento');
    $numIdLocalizador = PaginaSEI::getInstance()->recuperarCampo('selLocalizador');
  }

  if ($numIdTipoProcesso !== '') {
    $objArquivamentoDTO->setNumIdTipoProcedimentoProcedimento($numIdTipoProcesso);
  }

  if ($numIdTipoDocumento !== '') {
    $objArquivamentoDTO->setNumIdSerieDocumento($numIdTipoDocumento);
  }

  if ($numIdLocalizador !== '') {
    $objArquivamentoDTO->setNumIdLocalizador($numIdLocalizador);
  }

  //paginacao
  PaginaSEI::getInstance()->prepararPaginacao($objArquivamentoDTO,500);
  //lista os documentos preparados para eliminacao
  $objArquivamentoRN = new ArquivamentoRN();
  $arrObjArquivamentoDTO = $objArquivamentoRN->listarParaEliminacao($objArquivamentoDTO);
  //paginacao
  PaginaSEI::getInstance()->processarPaginacao($objArquivamentoDTO);
  //a tabela, links e acoes nao tem muitas especificidades
  $numRegistros = count($arrObjArquivamentoDTO);
  if ($numRegistros > 0){

    $arrObjTipoArquivamentoSituacaoDTO = InfraArray::indexarArrInfraDTO($objArquivamentoRN->listarValoresTipoArquivamentoSituacao(),'StaArquivamento');

    $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
    $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
    $bolAcaoEliminar = SessaoSEI::getInstance()->verificarPermissao('arquivamento_eliminar');

    if($bolAcaoEliminar) {
      $arrComandos[] = '<button type="button"  id="btnEliminar" value="Desarquivar para Eliminação" onclick="acaoEliminacaoMultipla();" class="infraButton">Desarquivar para Eliminação</button>';
      $strLinkEliminar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_eliminar&acao_origem='.$_GET['acao']);
    }

    $strResultado = '';

    $strCaptionTabela = 'Documentos';
    $strSumarioTabela = 'Documentos para Desarquivamento';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" >Processo</th>'."\n";
    $strResultado .= '<th class="infraTh" >Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" >Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Número</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Estado</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Localizador</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $n = 0;
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">';
      if ($bolAcaoEliminar && $arrObjArquivamentoDTO[$i]->getStrStaArquivamento()!=ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO) {
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++, $arrObjArquivamentoDTO[$i]->getDblIdProtocolo(), $arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento())."\n";
      }else{
        $strResultado .= '&nbsp;';
      }
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoProcedimentoTrabalhar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjArquivamentoDTO[$i]->getDblIdProcedimentoDocumento().'&id_documento='.$arrObjArquivamentoDTO[$i]->getDblIdProcedimentoDocumento()).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoProcedimento()).'</a>';
      }else{
        $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoProcedimento());
      }
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoDocumentoVisualizar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo()) .'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" >'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento()).'</a>';
      }else{
        $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrProtocoloFormatado());
      }
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeSerieDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNumeroDocumento());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      $strResultado .= PaginaSEI::tratarHTML($arrObjTipoArquivamentoSituacaoDTO[$arrObjArquivamentoDTO[$i]->getStrStaArquivamento()]->getStrDescricao());
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';

      if ($arrObjArquivamentoDTO[$i]->getNumIdLocalizador()!=null) {
        $strCorLocalizador = '';
        if ($arrObjArquivamentoDTO[$i]->getStrStaEstadoLocalizador() == LocalizadorRN::$EA_ABERTO) {
          $strCorLocalizador = 'style="color:green;"';
        } else {
          $strCorLocalizador = 'style="color:red;"';
        }
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&acao_origem='.$_GET['acao'].'&id_localizador='.$arrObjArquivamentoDTO[$i]->getNumIdLocalizador()).'" target="_blank" class="linkFuncionalidade" '.$strCorLocalizador.' tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.$arrObjArquivamentoDTO[$i]->getStrNomeTipoLocalizador().'">'.LocalizadorINT::montarIdentificacaoRI1132($arrObjArquivamentoDTO[$i]->getStrSiglaTipoLocalizador(),$arrObjArquivamentoDTO[$i]->getNumSeqLocalizadorLocalizador()).'</a>';
      }else{
        $strResultado .= '&nbsp;';
      }
      $strResultado .= '</td>';

      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoEliminar && $arrObjArquivamentoDTO[$i]->getStrStaArquivamento()!=ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO) {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($arrObjArquivamentoDTO[$i]->getDblIdProtocolo()).'" onclick="acaoEliminacao(\''.$arrObjArquivamentoDTO[$i]->getDblIdProtocolo().'\',\''.$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::AVALIACAO_ELIMINAR.'" title="Desarquivar para Eliminação" alt="Desarquivar para Eliminação" class="infraImg" /></a>&nbsp;';
      }
      $strResultado .= '</td>';

      $strResultado .= '</tr>';

    }
    $strResultado .= '</table>';
  }

  $strSelTipoProcedimento = ArquivamentoINT::montarSelectTiposProcedimentoParaEliminacao('', 'Todos', $numIdTipoProcesso);
  $strSelTipoDocumento = ArquivamentoINT::montarSelectSeriesParaEliminacao('','Todos',$numIdTipoDocumento);
  $strSelLocalizador = ArquivamentoINT::montarSelectLocalizadoresParaEliminacao('','Todos',$numIdLocalizador);

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
#lblTipoProcesso {position:absolute;left:0%;top:0%;width:38%;}
#selTipoProcesso {position:absolute;left:0%;top:40%;width:38%;}

#lblTipoDocumento {position:absolute;left:40%;top:0%;width:38%;}
#selTipoDocumento {position:absolute;left:40%;top:40%;width:38%;}

#lblLocalizador {position:absolute;left:80%;top:0%;width:20%;}
#selLocalizador {position:absolute;left:80%;top:40%;width:20%;}

#lblSenha {position:absolute;left:0%;top:0%;}
#pwdSenha {position:absolute;left:0%;top:40%;width:20%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});

function validarUsuarioSenha(){

  if (infraTrim(document.getElementById('pwdSenha').value)==''){
    alert('Senha não informada.');
    self.setTimeout('document.getElementById(\'pwdSenha\').focus()',100);
    return false;
  }

  return true;
}

function inicializar(){
  self.setTimeout('document.getElementById(\'pwdSenha\').focus()',100);
  infraEfeitoTabelas();
}

function acaoEliminacaoMultipla(){
  if (!validarUsuarioSenha()) {
    return;
  }

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }
  if (confirm("Confirma desarquivamento para eliminação dos documentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmEliminacaoLista').action='<?=$strLinkEliminar?>';
    document.getElementById('frmEliminacaoLista').submit();
  }
}

function acaoEliminacao(id,desc){
  if (!validarUsuarioSenha()) {
    return;
  }

  if (confirm("Confirma eliminação do documento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmEliminacaoLista').action='<?=$strLinkEliminar?>';
    document.getElementById('frmEliminacaoLista').submit();
  }
}

function tratarSenha(ev){
  if (event.keyCode==13){
  return false;
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEliminacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblTipoProcesso" for="selTipoProcesso" class="infraLabelOpcional">Tipo Processo:</label>
  <select id="selTipoProcesso" name="selTipoProcesso" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strSelTipoProcedimento?>
  </select>

  <label id="lblTipoDocumento" for="selTipoDocumento" class="infraLabelOpcional">Tipo Documento:</label>
  <select id="selTipoDocumento" name="selTipoDocumento" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strSelTipoDocumento?>
  </select>

  <label id="lblLocalizador" for="selLocalizador" class="infraLabelOpcional">Localizador:</label>
  <select id="selLocalizador" name="selLocalizador" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strSelLocalizador?>
  </select>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblSenha" for="pwdSenha" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>enha:</label>
  <?= InfraINT::montarInputPassword('pwdSenha', '', 'onkeypress="return tratarSenha(this,event);" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"') ?>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>