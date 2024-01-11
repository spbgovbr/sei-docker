<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
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

  $strParametros = '';
  if (isset($_GET['sequencial_original'])){
      $strParametros .= "&sequencial_original=".$_GET['sequencial_original'];
  }
  
  $objNumeracaoDTO = new NumeracaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    
    case 'numeracao_ajustar':
      $strTitulo = 'Ajustar Numeração';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAjustarNumeracao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_numeracao'])){
        $objNumeracaoDTO->setNumIdNumeracao($_GET['id_numeracao']);
        $objNumeracaoDTO->retNumIdNumeracao();
        $objNumeracaoDTO->retNumSequencial();
        $objNumeracaoRN = new NumeracaoRN();
        $objNumeracaoDTO = $objNumeracaoRN->consultar($objNumeracaoDTO);
        if ($objNumeracaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objNumeracaoDTO->setNumIdNumeracao($_POST['hdnIdNumeracao']);
        $objNumeracaoDTO->setNumSequencial($_POST['txtSequencial']);
      }
      
      $objNumeracaoDTO->setNumSequencialOriginal($_GET['sequencial_original']);

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objNumeracaoDTO->getNumIdNumeracao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAjustarNumeracao'])) {
        try{
          
          $objNumeracaoRN = new NumeracaoRN();
          $objNumeracaoRN->ajustar($objNumeracaoDTO);
          
          PaginaSEI::getInstance()->adicionarMensagem('Numeração "'.$objNumeracaoDTO->getNumSequencial().'" ajustada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objNumeracaoDTO->getNumIdNumeracao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
#lblSequencial {position:absolute;left:0%;top:0%;width:25%;}
#txtSequencial {position:absolute;left:0%;top:40%;width:25%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  document.getElementById('txtSequencial').focus();  
}

function validarAjuste() {
  if (infraTrim(document.getElementById('txtSequencial').value)=='') {
    alert('Informe o Sequencial.');
    document.getElementById('txtSequencial').focus();
    return false;
  }
  
  return true;
}

function OnSubmitForm() {
  return validarAjuste();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmNumeracaoAjuste" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblSequencial" for="txtSequencial" accesskey="" class="infraLabelObrigatorio">Sequencial:</label>
  <input type="text" id="txtSequencial" name="txtSequencial" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objNumeracaoDTO->getNumSequencial());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdNumeracao" name="hdnIdNumeracao" value="<?=$objNumeracaoDTO->getNumIdNumeracao();?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>