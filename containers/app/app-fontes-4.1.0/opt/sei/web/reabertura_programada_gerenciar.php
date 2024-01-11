<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
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

  PaginaSEI::getInstance()->prepararSelecao('reabertura_programada_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnSinAgendadas'));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_procedimento'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }


  switch($_GET['acao']){
    case 'reabertura_programada_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjReaberturaProgramadaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
          $objReaberturaProgramadaDTO->setNumIdReaberturaProgramada($arrStrIds[$i]);
          $arrObjReaberturaProgramadaDTO[] = $objReaberturaProgramadaDTO;
        }
        $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();
        $objReaberturaProgramadaRN->excluir($arrObjReaberturaProgramadaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'reabertura_programada_gerenciar':
      $strTitulo = 'Reaberturas Programadas do Processo';

      if ($_GET['acao_origem']=='procedimento_visualizar'){

        $dto = new ReaberturaProgramadaDTO();
        $dto->setNumMaxRegistrosRetorno(1);
        $dto->retNumIdReaberturaProgramada();
        $dto->setDblIdProtocolo($_GET['id_procedimento']);
        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        //$dto->setDthProcessamento(null);

        $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();
        if ($objReaberturaProgramadaRN->consultar($dto)==null){
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=reabertura_programada_registrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']));
          die;
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strSinAgendadas = PaginaSEI::getInstance()->recuperarCampo('hdnSinAgendadas','S');

  $arrComandos = array();
  $bolAcaoGerenciar = SessaoSEI::getInstance()->verificarPermissao('reabertura_programada_gerenciar');
  if ($bolAcaoGerenciar && $strSinAgendadas=='S'){
    $arrComandos[] = '<button type="button" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=reabertura_programada_registrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton">Nova</button>';
  }

  $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();

  if($strSinAgendadas=='S'){
    $objReaberturaProgramadaDTO->setStrSinAgendadas("S");
  }else{
    $objReaberturaProgramadaDTO->setStrSinAgendadas("N");
  }

  $objReaberturaProgramadaDTO->setDblIdProtocolo($_GET['id_procedimento']);

  PaginaSEI::getInstance()->prepararOrdenacao($objReaberturaProgramadaDTO, 'Programada', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objReaberturaProgramadaDTO);

  $arrObjReaberturaProgramadaDTO = array();

  try {
    $objReaberturaProgramadaRN = new ReaberturaProgramadaRN();
    $arrObjReaberturaProgramadaDTO = $objReaberturaProgramadaRN->listarReaberturasUnidade($objReaberturaProgramadaDTO);
  }catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
  }

  PaginaSEI::getInstance()->processarPaginacao($objReaberturaProgramadaDTO);
  $numRegistros = count($arrObjReaberturaProgramadaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('reabertura_programada_consultar');
    $bolAcaoRegistrar = SessaoSEI::getInstance()->verificarPermissao('reabertura_programada_registrar');
    $bolAcaoImprimir = true;
    //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('reabertura_programada_excluir');

    if ($bolAcaoExcluir && $strSinAgendadas=='S'){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=reabertura_programada_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($strSinAgendadas=='S') {
      $strSumarioTabela = 'Tabela de Reaberturas Programadas Agendadas.';
      $strCaptionTabela = 'Reaberturas Programadas Agendadas';
    }else{
      $strSumarioTabela = 'Tabela de Reaberturas Programadas Processadas.';
      $strCaptionTabela = 'Reaberturas Programadas Processadas';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objReaberturaProgramadaDTO, 'Data Programada', 'Programada', $arrObjReaberturaProgramadaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objReaberturaProgramadaDTO,'Sigla','SiglaUsuario',$arrObjReaberturaProgramadaDTO).'</th>'."\n";
    if ($strSinAgendadas=='S') {
      $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReaberturaProgramadaDTO,'Nome','NomeUsuario',$arrObjReaberturaProgramadaDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    }else{
      $strResultado .= '<th class="infraTh"  width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objReaberturaProgramadaDTO, 'Processamento', 'Processamento', $arrObjReaberturaProgramadaDTO).'</th>'."\n";
      $strResultado .= '<th class="infraTh">Resultado</th>'."\n";
    }


    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $objProtocoloDTO = $arrObjReaberturaProgramadaDTO[$i]->getObjProtocoloDTO();
      $strCorProcesso = ' class="'.($objProtocoloDTO->getStrSinAberto() == 'S' ? 'protocoloAberto' : 'protocoloFechado').'"';


      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjReaberturaProgramadaDTO[$i]->getNumIdReaberturaProgramada(),$arrObjReaberturaProgramadaDTO[$i]->getDtaProgramada()).'</td>';
      }
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getDtaProgramada()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getStrSiglaUsuario()).'</a></td>';

      if ($strSinAgendadas=='N'){
        $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getDthProcessamento()).'</td>';
        if ($arrObjReaberturaProgramadaDTO[$i]->getNumIdAtividade()!=null){
          $strResultado .= '<td align="center">Processo reaberto</td>';
        }else{
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getStrErro()).'</td>';
        }
      }else {

        $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getStrNomeUsuario()).'</td>';

        //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjReaberturaProgramadaDTO[$i]->getDthAlteracao()).'</td>';
        $strResultado .= '<td align="center">';

        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjReaberturaProgramadaDTO[$i]->getNumIdReaberturaProgramada());

        if ($bolAcaoConsultar) {
          $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=reabertura_programada_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_reabertura_programada='.$arrObjReaberturaProgramadaDTO[$i]->getNumIdReaberturaProgramada()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Reabertura Programada" alt="Consultar Reabertura Programada" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoRegistrar) {
          $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=reabertura_programada_registrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_reabertura_programada='.$arrObjReaberturaProgramadaDTO[$i]->getNumIdReaberturaProgramada()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Reabertura Programada" alt="Alterar Reabertura Programada" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoExcluir) {
          $strId = $arrObjReaberturaProgramadaDTO[$i]->getNumIdReaberturaProgramada();
          $strProcesso = PaginaSEI::getInstance()->formatarParametrosJavaScript($objProtocoloDTO->getStrProtocoloFormatado());
          $strData = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjReaberturaProgramadaDTO[$i]->getDtaProgramada());
        }

        if ($bolAcaoExcluir) {
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strProcesso.'\',\''.$strData.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Reabertura Programada" alt="Excluir Reabertura Programada" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td></tr>'."\n";
      }
    }
    $strResultado .= '</table>';
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
<?if(0){?><style><?}?>

/* #frmReaberturaProgramadaGerenciar{max-width: 1200px;} */

#ancFiltro {position:absolute;left:0%;top:0%;}

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
function acaoExcluir(id,processo,data){
  if (confirm("Confirma exclusão da Reabertura Programada para " + data + "?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmReaberturaProgramadaGerenciar').action='<?=$strLinkExcluir?>';
    document.getElementById('frmReaberturaProgramadaGerenciar').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Reabertura Programada selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Reaberturas Programadas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmReaberturaProgramadaGerenciar').action='<?=$strLinkExcluir?>';
    document.getElementById('frmReaberturaProgramadaGerenciar').submit();
  }
}
<? } ?>

function filtrarReaberturas(sinAgendadas){
  document.getElementById('hdnSinAgendadas').value = sinAgendadas;

  if (sinAgendadas == 'S'){
    document.getElementById('hdnInfraCampoOrd').value = 'Programada';
    document.getElementById('hdnInfraTipoOrd').value = 'ASC';
  }else{
    document.getElementById('hdnInfraCampoOrd').value = 'Processamento';
    document.getElementById('hdnInfraTipoOrd').value = 'DESC';
  }

  document.getElementById('frmReaberturaProgramadaGerenciar').submit();
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmReaberturaProgramadaGerenciar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('3em');
  ?>
  <a id="ancFiltro" href="#" class="ancoraPadraoPreta" onclick="filtrarReaberturas('<?=$strSinAgendadas=='S'?'N':'S'?>')" ><?=$strSinAgendadas == 'S' ? "Ver processadas" : "Ver agendadas" ?></a>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <input type="hidden" id="hdnSinAgendadas" name="hdnSinAgendadas" value="<?=$strSinAgendadas?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
