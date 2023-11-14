<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/07/2021 - criado por mgb29
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_edital_eliminacao','id_edital_eliminacao_conteudo'));

  $objEditalEliminacaoRN = new EditalEliminacaoRN();

  $arrComandos = array();

  $bolAcesso = false;
  $bolProcessou = false;

  switch($_GET['acao']){
    //excluir padrao
    //eliminar o conteudo de um edital de eliminacao (que na pratica é um processo) executado após usuário informar senha, no popup
    case 'edital_eliminacao_eliminar':

      $strTitulo = 'Eliminar Processos do Edital';

      $arrComandos[] = '<button type="submit" accesskey="" name="sbmEliminar" id="sbmEliminar" value="Eliminar" class="infraButton">Eliminar</button>';

      $arrIdEditalEliminacaoConteudo = array();
      if ($_GET['acao_origem']=='edital_eliminacao_conteudo_listar') {
        $arrIdEditalEliminacaoConteudo = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else if (trim($_GET['id_edital_eliminacao_conteudo'])!=''){
        $arrIdEditalEliminacaoConteudo = explode(',',$_GET['id_edital_eliminacao_conteudo']);
      }

      if (PaginaSEI::getInstance()->getAcaoRetorno()=='edital_eliminacao_listar') {
        $strAncora = PaginaSEI::montarAncora($_GET['id_edital_eliminacao']);
      }else{
        $strAncora = PaginaSEI::montarAncora($arrIdEditalEliminacaoConteudo);
      }

      $arrComandos[] = '<button type="button" accesskey="V" id="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strAncora).'\'" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';


      //array que contem os processos convertidos para edital de eliminacao conteudo
      $arrObjEditalEliminacaoConteudoDTO = array();
      //itera pelos ids
      for ($i=0;$i<count($arrIdEditalEliminacaoConteudo);$i++){
        //cria dto
        $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($arrIdEditalEliminacaoConteudo[$i]);
        //adiciona no array
        $arrObjEditalEliminacaoConteudoDTO[] = $objEditalEliminacaoConteudoDTO;
      }
      //seta o dto do edital de eliminacao com o id do edital de eliminacao e os processos selecionados na tela
      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO->setNumIdEditalEliminacao($_GET['id_edital_eliminacao']);
      //seta o array
      $objEditalEliminacaoDTO->setArrObjEditalEliminacaoConteudoDTO($arrObjEditalEliminacaoConteudoDTO);
      $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();

      if (PaginaSEI::getInstance()->getAcaoRetorno()=='edital_eliminacao_conteudo_listar' && count($arrIdEditalEliminacaoConteudo) == 0) {
        throw new InfraException('Nenhum processo do edital informado para eliminação.');
      }

      if (isset($_POST['sbmEliminar'])) {
        try {
          $objInfraSip = new InfraSip(SessaoSEI::getInstance());
          $objInfraSip->autenticar(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(),
            null,
            SessaoSEI::getInstance()->getStrSiglaUsuario(),
            $_POST['pwdSenha']);

          $bolAcesso = true;

        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }

      }else if ($_GET['acesso'] == '1') {

        PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
        try {
          //chama eliminacao
          $objEditalEliminacaoRN->eliminarEdital($objEditalEliminacaoDTO);
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        //finaliza barra de progresso
        PaginaSEI::getInstance()->finalizarBarraProgresso2(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&processou=1'), false);
        die;

      }else if ($_GET['processou'] == '1'){
        $bolProcessou = true;
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
  $objEditalEliminacaoDTO->retStrEspecificacao();
  $objEditalEliminacaoDTO->retDtaPublicacao();
  $objEditalEliminacaoDTO->setNumIdEditalEliminacao($_GET['id_edital_eliminacao']);
  $objEditalEliminacaoDTO = $objEditalEliminacaoRN->consultar($objEditalEliminacaoDTO);
  $strEditalEspecificacao = $objEditalEliminacaoDTO->getStrEspecificacao();
  $strEditalPublicacao = $objEditalEliminacaoDTO->getDtaPublicacao();

  $strItensSelProcedimentos = '';
  if (count($arrIdEditalEliminacaoConteudo)){

    $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
    $objEditalEliminacaoConteudoDTO->retDblIdProcedimentoAvaliacaoDocumental();
    $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($_GET['id_edital_eliminacao']);
    $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($arrIdEditalEliminacaoConteudo, InfraDTO::$OPER_IN);

    $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
    $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);

    $strItensSelProcedimentos = ProcedimentoINT::conjuntoCompletoFormatadoRI0903(InfraArray::converterArrInfraDTO($arrObjEditalEliminacaoConteudoDTO,'IdProcedimentoAvaliacaoDocumental'));
  }


  $objEditalEliminacaoErroDTO = new EditalEliminacaoErroDTO();
  $objEditalEliminacaoErroDTO->retNumIdEditalEliminacaoErro();
  $objEditalEliminacaoErroDTO->retDthErro();
  $objEditalEliminacaoErroDTO->retStrTextoErro();
  $objEditalEliminacaoErroDTO->retStrProtocoloProcedimentoFormatado();
  $objEditalEliminacaoErroDTO->setNumIdEditalEliminacao($_GET['id_edital_eliminacao']);

  if (count($arrIdEditalEliminacaoConteudo)){
    $objEditalEliminacaoErroDTO->setNumIdEditalEliminacaoConteudo($arrIdEditalEliminacaoConteudo, InfraDTO::$OPER_IN);
  }

  $objEditalEliminacaoErroDTO->setOrdDthErro(InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objEditalEliminacaoErroDTO);

  $objEditalEliminacaoErroRN = new EditalEliminacaoErroRN();
  $arrObjEditalEliminacaoErroDTO = $objEditalEliminacaoErroRN->listar($objEditalEliminacaoErroDTO);

  PaginaSEI::getInstance()->processarPaginacao($objEditalEliminacaoErroDTO);
  $numRegistros = count($arrObjEditalEliminacaoErroDTO);

  if ($bolProcessou){
    if ($numRegistros == 0) {
      PaginaSEI::getInstance()->setStrMensagem('Eliminação finalizada sem erros.', InfraPagina::$TIPO_MSG_AVISO);
    }else{
      PaginaSEI::getInstance()->setStrMensagem('Eliminação finalizada com erros.', InfraPagina::$TIPO_MSG_ERRO);
    }
  }

  if ($numRegistros > 0){

    $bolCheck = false;

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Erros de Eliminação.';
    $strCaptionTabela = 'Erros de Eliminação';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">Data/Hora</th>'."\n";

    $strResultado .= '<th class="infraTh">Texto</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEditalEliminacaoErroDTO[$i]->getNumIdEditalEliminacaoErro(),$arrObjEditalEliminacaoErroDTO[$i]->getDthErro()).'</td>';
      }
      $strResultado .= '<td valign="top">';
      $strResultado .= '<b>Data:</b><br />'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoErroDTO[$i]->getDthErro()).'<br/><br />';
      $strResultado .= '<b>Processo:</b><br />'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoErroDTO[$i]->getStrProtocoloProcedimentoFormatado()).'<br/><br />';
      $strResultado .= '</td>';

      $strLog =  $arrObjEditalEliminacaoErroDTO[$i]->getStrTextoErro();
      $strLog = PaginaSEI::tratarHTML($strLog);
      $strLog = str_replace('\n', '',$strLog);
      $strLog = str_replace("\n", '<br />',$strLog);
      $strLog = str_replace('&lt;br /&gt;','<br />',$strLog);
      $strResultado .= '<td valign="top">'.$strLog.'</td>';

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  $strIdEditalEliminacaoConteudo = implode(',', $arrIdEditalEliminacaoConteudo);

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
<?if(0){?><style><?}?>

#sbmEliminar {<?=$bolAcesso || $bolProcessou?'display:none;':''?>}

#lblEditalEspecificacao {position: absolute;left:0%;top:0%;}
#txtEditalEspecificacao {position: absolute;left:0%;top:40%;width:60%;}

#lblEditalPublicacao {position: absolute;left:62%;top:0%;}
#txtEditalPublicacao {position: absolute;left:62%;top:40%;width:15%;}

#divProcedimentos {<?= $strItensSelProcedimentos=='' ? 'display:none;' : '' ?>}
#lblProcedimentos {position: absolute;left:0%;top:0%;}
#selProcedimentos {position: absolute;left:0%;top:17%;width:77%;}

#divSenha {<?=$bolAcesso || $bolProcessou?'display:none;':''?>}
#lblSenha {position: absolute;left:0%;top:0%;}
#pwdSenha {position: absolute;left:0%;top:40%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});


function inicializar() {
  <? if ($bolAcesso){ ?>
    infraAbrirBarraProgresso(document.getElementById('frmEditalEliminacaoProcessar'), '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_edital_eliminacao_conteudo='.$strIdEditalEliminacaoConteudo.'&acesso=1')?>', 800, 100);
  <? }else{ ?>
    self.setTimeout('document.getElementById(\'pwdSenha\').focus()',100);
  <?}?>
}

function OnSubmitForm() {
  <? if (!$bolAcesso && !$bolProcessou){ ?>
    if (infraTrim(document.getElementById('pwdSenha').value)==''){
      alert('Informe a Senha.');
      document.getElementById('pwdSenha').focus();
      return false;
    }
  <? } ?>
  return true;
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEditalEliminacaoProcessar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_edital_eliminacao_conteudo='.$strIdEditalEliminacaoConteudo)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>

  <div id="divEdital" class="infraAreaDados" style="height:5em;">
    <label id="lblEditalEspecificacao" for="txtEditalEspecificacao" accesskey="" class="infraLabelOpcional">Edital:</label>
    <input type="text" id="txtEditalEspecificacao" name="txtEditalEspecificacao" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($strEditalEspecificacao)?>" />

    <label id="lblEditalPublicacao" for="txtEditalPublicacao" accesskey="" class="infraLabelOpcional">Publicação:</label>
    <input type="text" id="txtEditalPublicacao" name="txtEditalPublicacao" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($strEditalPublicacao)?>" />
  </div>

  <div id="divProcedimentos" class="infraAreaDados" style="height:10em;">
    <label id="lblProcedimentos" for="selProcedimentos" class="infraLabelOpcional">Processos:</label>
    <select id="selProcedimentos" name="selProcedimentos" size="4" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelProcedimentos?>
    </select>
  </div>

  <div id="divSenha" class="infraAreaDados" style="height:5em;">
    <label id="lblSenha" for="pwdSenha" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>enha:</label>
    <?= InfraINT::montarInputPassword('pwdSenha', '', 'tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"') ?>
  </div>

  <?

  if ($numRegistros) {
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
  }

  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
