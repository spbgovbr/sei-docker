<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/04/2014 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
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

  PaginaSEI::getInstance()->prepararSelecao('unidade_selecionar_reabertura_processo');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
    
  switch($_GET['acao']){
    case 'unidade_selecionar_reabertura_processo':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Unidade para Reabertura','Selecionar Unidades para Reabertura');
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objProcedimentoDTO = new ProcedimentoDTO();
  $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  
  PaginaSEI::getInstance()->prepararPaginacao($objProcedimentoDTO);
  
  $objUnidadeRN = new UnidadeRN();
  $arrObjUnidadeDTO = $objUnidadeRN->listarReaberturaProcesso($objProcedimentoDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objProcedimentoDTO);
  $numRegistros = count($arrObjUnidadeDTO);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Unidades de Tramitação do Processo.';
    $strCaptionTabela = 'Unidades de Tramitação do Processo';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Sigla</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Órgão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Situação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    $n = 0;
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">';
      if ($arrObjUnidadeDTO[$i]->getStrSinProcessoAberto()=='N'){
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($n,$arrObjUnidadeDTO[$i]->getNumIdUnidade(),UnidadeINT::formatarSiglaDescricao($arrObjUnidadeDTO[$i]->getStrSigla(),$arrObjUnidadeDTO[$i]->getStrDescricao()));
      }else{
        $strResultado .= '&nbsp;';
      }
      $strResultado .= '</td>';
      
      $strResultado .= '<td align="center" width="15%">'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrSiglaOrgao()).'</a></td>';
      
      $strResultado .= '<td align="center">';
      if ($arrObjUnidadeDTO[$i]->getStrSinProcessoAberto()=='S'){
        $strResultado .= 'Aberto';
      }else{
        $strResultado .= 'Concluído';
      }
      $strResultado .= '</td>';
      
      $strResultado .= '<td align="center">';
      if ($arrObjUnidadeDTO[$i]->getStrSinProcessoAberto()=='N'){
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($n,$arrObjUnidadeDTO[$i]->getNumIdUnidade());
      }else{
        $strResultado .= '&nbsp;';
      }
      $strResultado .= '</td></tr>'."\n";
      
      if ($arrObjUnidadeDTO[$i]->getStrSinProcessoAberto()=='N'){
        $n++;
      }
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  infraEfeitoTabelas();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUnidadeReabertura" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>