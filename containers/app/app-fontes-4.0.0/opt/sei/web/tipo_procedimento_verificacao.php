<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/10/2012 - CRIADO POR MGA
*
*
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
  
  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'tipo_procedimento_individual_verificar':
      
      $strTitulo = 'Verificação de Tipos de Processo Individuais';
      
      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_POST['selTipoProcedimento']);
      
      $arrComandos[] = '<button type="submit" id="sbmVerificar" name="sbmVerificar" value="Verificar" class="infraButton">Verificar</button>';
      
      InfraDebug::getInstance()->limpar();
      
      if (isset($_POST['sbmVerificar'])){
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $arrRet = $objTipoProcedimentoRN->verificarProcessosIndividuais($objTipoProcedimentoDTO);
        
        $numRegistros = InfraArray::contar($arrRet);
        
        if ($numRegistros > 0){
          $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirDiv(\'divInfraAreaTabela\');" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
          
          
          //////////
          
          $strResultado = '';

          $strSumarioTabela = 'Tabela de Avisos.';
          $strCaptionTabela = 'Avisos';
      
          $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //60
          $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
          $strResultado .= '<tr>';
          $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
          $strResultado .= '</tr>'."\n";
          $strCssTr='';
          for($i = 0;$i < $numRegistros; $i++){
      
            $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
            $strResultado .= $strCssTr;
            $strResultado .= '<td align="left">'.$arrRet[$i].'</td>';
            $strResultado .= '</tr>'."\n";
          }
          $strResultado .= '</table>';
        }          
      }
      
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
	
  
  $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNomeIndividuais('null','&nbsp;',$objTipoProcedimentoDTO->getNumIdTipoProcedimento());
  
}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - Indexação');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
#lblTipoProcedimento {position:absolute;left:0%;top:0%;}
#selTipoProcedimento {position:absolute;left:0%;top:40%;width:70%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function OnSubmitForm() {
  return validarForm();
}

function validarForm() { 
  
  if (!infraSelectSelecionado('selTipoProcedimento')){
    alert('Selecione um Tipo de Procedimento.');
    document.getElementById('selTipoProcedimento').focus();
    return false;
  }

  infraExibirAviso(true);
  
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo);
?>
<form id="frmProcedimentoVerificacao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao('Importar Sistema');
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelObrigatorio">Tipo:</label> 
  <select id="selTipoProcedimento" name="selTipoProcedimento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelTipoProcedimento?>
  </select>
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>