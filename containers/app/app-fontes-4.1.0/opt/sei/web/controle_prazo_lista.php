<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selAno'));

  switch($_GET['acao']){
    case 'controle_prazo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjControlePrazoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objControlePrazoDTO = new ControlePrazoDTO();
          $objControlePrazoDTO->setNumIdControlePrazo($arrStrIds[$i]);
          $arrObjControlePrazoDTO[] = $objControlePrazoDTO;
        }
        $objControlePrazoRN = new ControlePrazoRN();
        $objControlePrazoRN->excluir($arrObjControlePrazoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'controle_prazo_listar':
      $strTitulo = 'Controles de Prazos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objControlePrazoDTO = new ControlePrazoDTO();

  $selAno = PaginaSEI::getInstance()->recuperarCampo('selAno');
  if ($selAno!==''){
    $objControlePrazoDTO->setNumAno($selAno);
  }else{
    $objControlePrazoDTO->setNumAno(substr(InfraData::getStrDataAtual(),6,4));
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objControlePrazoDTO, 'Prazo', InfraDTO::$TIPO_ORDENACAO_ASC);

  PaginaSEI::getInstance()->prepararPaginacao($objControlePrazoDTO);

  $objControlePrazoRN = new ControlePrazoRN();
  $arrObjControlePrazoDTO = $objControlePrazoRN->listarCompleto($objControlePrazoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objControlePrazoDTO);

  $numRegistros = count($arrObjControlePrazoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='controle_prazo_selecionar'){
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_consultar');
      $bolAcaoDefinir = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_definir');
      $bolAcaoExcluir = false;
      $bolCheck = true;
     }else{
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_consultar');
      $bolAcaoDefinir = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_definir');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_excluir');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_excluir&acao_origem='.$_GET['acao']);
    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Controles de Prazos.';
    $strCaptionTabela = 'Controles de Prazos';

    $strResultado .= '<table id="tblControlePrazo" width="100%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objControlePrazoDTO,'Processo','IdProtocolo',$arrObjControlePrazoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objControlePrazoDTO,'Usuário','SiglaUsuario',$arrObjControlePrazoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objControlePrazoDTO,'Data Programada','Prazo',$arrObjControlePrazoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Prazo Restante</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objControlePrazoDTO,'Data Conclusão','Conclusao',$arrObjControlePrazoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" >Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $arrObjControlePrazoDTO[$i]->setNumDias(InfraData::compararDatas(InfraData::getStrDataAtual(),$arrObjControlePrazoDTO[$i]->getDtaPrazo()));

      //if(!InfraString::isBolVazia($arrObjControlePrazoDTO[$i]->getDtaConclusao())){
      //  $strCssTr = '<tr class="trConcluido">';
      //}else
      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjControlePrazoDTO[$i]->getNumIdControlePrazo(),$arrObjControlePrazoDTO[$i]->getNumIdControlePrazo()).'</td>';
      }

      if ($arrObjControlePrazoDTO[$i]->getStrSinAberto() == 'S') {
        $strCorProcesso = ' class="protocoloAberto"';
      } else {
        $strCorProcesso = ' class="protocoloFechado"';
      }

      $strResultado .= '<td align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjControlePrazoDTO[$i]->getDblIdProtocolo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getStrNomeTipoProcedimento()).'" '.$strCorProcesso.'>'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getStrProtocoloFormatado()).'</a></td>'."\n";
      $strResultado .= '<td align="center">    <a alt="'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '';

      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getDtaPrazo()).'</td>';

      $strResultado .= '<td align="center" ';
      if ($arrObjControlePrazoDTO[$i]->getNumDias() < 0) {
        $strResultado .= 'class="tdVermelha"';
      }
      $strResultado .= '>'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getNumDias()).'</td>';


      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjControlePrazoDTO[$i]->getDtaConclusao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjControlePrazoDTO[$i]->getNumIdControlePrazo());

//      if ($bolAcaoConsultar){
//        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_controle_prazo='.$arrObjControlePrazoDTO[$i]->getNumIdControlePrazo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Controle de Prazos" alt="Consultar Controle de Prazos" class="infraImg" /></a>&nbsp;';
//      }

      if ($bolAcaoDefinir){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=controle_prazo_definir&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_controle_prazo='.$arrObjControlePrazoDTO[$i]->getNumIdControlePrazo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Controle de Prazos" alt="Alterar Controle de Prazos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strId = $arrObjControlePrazoDTO[$i]->getNumIdControlePrazo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjControlePrazoDTO[$i]->getStrProtocoloFormatado());
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Controle de Prazos" alt="Excluir Controle de Prazos" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'controle_prazo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strAnos = ControlePrazoINT::montarSelectAnos($selAno);

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


  tr.processoAtrasado {background-color: #F59F9F}

  #lblSelAno {position:absolute;left:0%;top:0%;}
  #selAno {position:absolute;left:0%;top:38%;}

  .trConcluido{background-color: #42c5f4;}
<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  infraEfeitoTabelas(true);
}


<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Controle de Prazos do processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmControlePrazoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmControlePrazoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Controle de Prazos selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Controles de Prazos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmControlePrazoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmControlePrazoLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmControlePrazoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
  <div id="divGeral" class="infraAreaDados" style="height: 5em">
    <label id="lblSelAno" for="selAno" accesskey="" class="infraLabelOpcional">Ano:</label>
    <select id="selAno" name="selAno" class="infraSelect" onchange="this.form.submit()" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
      <?= $strAnos ?>
    </select>
  </div>
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
