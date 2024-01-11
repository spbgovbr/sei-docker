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

  switch($_GET['acao']){

    case 'assinatura_listar':
      $strTitulo = 'Assinaturas';
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objAssinaturaDTO = new AssinaturaDTO();
  $objAssinaturaDTO->retNumIdAssinatura();
  $objAssinaturaDTO->retStrSiglaUsuario();
  $objAssinaturaDTO->retStrNomeUsuario();
  $objAssinaturaDTO->retStrTratamento();
  $objAssinaturaDTO->retStrSiglaUnidade();
  $objAssinaturaDTO->retStrDescricaoUnidade();
  $objAssinaturaDTO->retDthAberturaAtividade();
  $objAssinaturaDTO->setDblIdDocumento($_GET['id_documento']);
  $objAssinaturaDTO->setOrdDthAberturaAtividade(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objAssinaturaRN = new AssinaturaRN();
  $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

  $numRegistros = count($arrObjAssinaturaDTO);

  if ($numRegistros > 0){

    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de '.$strTitulo;
    $strCaptionTabela = $strTitulo;

    $strResultado .= '<table id="tblAndamentos" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%"  style="display:none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh">Nome</th>'."\n";
    $strResultado .= '<th class="infraTh">Tratamento</th>'."\n";
    $strResultado .= '</tr>'."\n";
    
    $strCssTr='';
    
    foreach($arrObjAssinaturaDTO as $objAssinaturaDTO){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      
      $strResultado .= '<td valign="top" style="display:none">'.PaginaSEI::getInstance()->getTrCheck($i,$objAssinaturaDTO->getNumIdAssinatura(),$objAssinaturaDTO->getDthAberturaAtividade()).'</td>';
			$strResultado .= "\n".'<td align="center" valign="top">';
		  $strResultado .= substr($objAssinaturaDTO->getDthAberturaAtividade(),0,16);
			$strResultado .= '</td>';
			
			$strResultado .= "\n".'<td align="center"  valign="top">';
		  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAssinaturaDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAssinaturaDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAssinaturaDTO->getStrSiglaUnidade()).'</a>';
			$strResultado .= '</td>';
			
			$strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= PaginaSEI::tratarHTML($objAssinaturaDTO->getStrSiglaUsuario());
			$strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= PaginaSEI::tratarHTML($objAssinaturaDTO->getStrNomeUsuario());
      $strResultado .= '</td>';

      
      $strResultado .= '<td align="center" valign="top">'.$objAssinaturaDTO->getStrTratamento().'</td>';
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
<form id="frmAssinaturaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
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