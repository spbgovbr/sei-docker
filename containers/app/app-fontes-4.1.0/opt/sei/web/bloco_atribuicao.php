<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
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

  $objBlocoAtribuirDTO = new BlocoAtribuirDTO();

  $arrComandos = array();
  switch($_GET['acao']){
    
    case 'bloco_atribuir':
      $strTitulo = 'Atribuir Bloco';

      if ($_GET['acao_origem']!='bloco_atribuir'){
        $objBlocoAtribuirDTO->setNumIdUsuarioAtribuicao(null);
        $arrNumIdBloco = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else{
        $objBlocoAtribuirDTO->setNumIdUsuarioAtribuicao($_POST['selAtribuicao']);
        $arrNumIdBloco = explode(',',$_POST['hdnIdBloco']);
      }

      if (count($arrNumIdBloco)==1){
        $strTitulo .= ' '.$arrNumIdBloco[0];
      }

      if (isset($_POST['sbmSalvar'])){
        try{

          $objBlocoAtribuirDTO->setArrObjBlocoDTO(InfraArray::gerarArrInfraDTO('BlocoDTO','IdBloco',$arrNumIdBloco));

          $objBlocoRN = new BlocoRN();
          $objBlocoRN->atribuir($objBlocoAtribuirDTO);

        	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($arrNumIdBloco)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }            
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';     
     	$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].PaginaSEI::montarAncora($arrNumIdBloco)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelAtribuicao = UsuarioINT::montarSelectPorUnidadeRI0811('null','&nbsp;',$objBlocoAtribuirDTO->getNumIdUsuarioAtribuicao(),SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  $arrNumIdBloco = implode(',',$arrNumIdBloco);

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
#lblAtribuicao {position:absolute;left:0%;top:0%;width:50%;}
#selAtribuicao {position:absolute;left:0%;top:6%;width:50%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('selAtribuicao').focus();
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm(){
  return true;
}
 
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBlocoAtribuir" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>

 	<label id="lblAtribuicao" for="selAtribuicao" class="infraLabelOpcional">Atribuir para:</label>
  <select id="selAtribuicao" name="selAtribuicao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelAtribuicao?>
  </select>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="<?=$arrNumIdBloco;?>" />

</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>