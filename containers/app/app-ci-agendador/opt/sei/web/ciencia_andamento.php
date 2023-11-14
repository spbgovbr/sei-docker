<?php
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/10/2011 - criado por mga
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
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }

  if (isset($_GET['id_procedimento_anexado'])){
    $strParametros .= '&id_procedimento_anexado='.$_GET['id_procedimento_anexado'];
  }

  switch($_GET['acao']){

    case 'protocolo_ciencia_listar':
      $strTitulo = 'Ciências';
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();


  $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
  $objProcedimentoHistoricoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
  
  if (!isset($_GET['id_procedimento_anexado']) && !isset($_GET['id_documento'])){
  	$objProcedimentoHistoricoDTO->setNumIdTarefa(array(TarefaRN::$TI_PROCESSO_CIENCIA,TarefaRN::$TI_PROCESSO_ANEXADO_CIENCIA,TarefaRN::$TI_DOCUMENTO_CIENCIA));
  }elseif (isset($_GET['id_procedimento_anexado'])){
    $objProcedimentoHistoricoDTO->setDblIdProcedimentoAnexado($_GET['id_procedimento_anexado']);
    $objProcedimentoHistoricoDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_ANEXADO_CIENCIA);
  }elseif (isset($_GET['id_documento'])){
    $objProcedimentoHistoricoDTO->setDblIdDocumento($_GET['id_documento']);
    $objProcedimentoHistoricoDTO->setNumIdTarefa(TarefaRN::$TI_DOCUMENTO_CIENCIA);
  }
  
  PaginaSEI::getInstance()->prepararPaginacao($objProcedimentoHistoricoDTO,100);
  $objProcedimentoRN = new ProcedimentoRN();
  $objProcedimentoDTORet = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
  PaginaSEI::getInstance()->processarPaginacao($objProcedimentoHistoricoDTO);
  
  $arrObjAtividadeDTO = $objProcedimentoDTORet->getArrObjAtividadeDTO();
  
  $numRegistros = count($arrObjAtividadeDTO);

  if ($numRegistros > 0){

    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de '.$strTitulo;
    $strCaptionTabela = $strTitulo;

    $strResultado .= '<table id="tblAndamentos" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%"  style="display:none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '</tr>'."\n";
    
    $strCssTr='';
    
    foreach($arrObjAtividadeDTO as $objAtividadeDTO){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      
      $strResultado .= '<td valign="top" style="display:none">'.PaginaSEI::getInstance()->getTrCheck($i,$objAtividadeDTO->getNumIdAtividade(),$objAtividadeDTO->getDthAbertura()).'</td>';
			$strResultado .= "\n".'<td align="center" valign="top">';
		  $strResultado .= substr($objAtividadeDTO->getDthAbertura(),0,16);
			$strResultado .= '</td>';
			
			$strResultado .= "\n".'<td align="center"  valign="top">';
		  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= "\n".'<td align="center"  valign="top">';
		  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuarioOrigem()).'</a>';
			$strResultado .= '</td>';
      
      $strResultado .= '<td align="left" valign="top">'.$objAtividadeDTO->getStrNomeTarefa().'</td>';
      $strResultado .= '</tr>'."\n";
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
#tblAndamentos td{
padding:.2em;
}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();

}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar(); "');
?>
<form id="frmProtocoloCienciaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?

  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>