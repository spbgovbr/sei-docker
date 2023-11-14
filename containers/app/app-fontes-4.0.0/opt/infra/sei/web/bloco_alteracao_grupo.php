<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/08/2019 - criado por mga
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples','id_procedimento'));

  PaginaSEI::getInstance()->salvarCamposPost(array('selGrupoBloco'));

  $arrComandos = array();

  switch($_GET['acao']){
    case 'bloco_alterar_grupo':

      $strTitulo = 'Alterar Grupo de Blocos';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarBlocoGrupo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if ($_GET['acao_origem']=='bloco_assinatura_listar' || $_GET['acao_origem']=='bloco_reuniao_listar' || $_GET['acao_origem']=='bloco_interno_listar'){
        $arrIdBloco = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else{
        $arrIdBloco = explode(',',$_POST['hdnIdBloco']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrIdBloco)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarBlocoGrupo'])) {
        try{


          $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
          $objRelBlocoUnidadeDTO->setNumIdBloco($arrIdBloco);
          $objRelBlocoUnidadeDTO->setNumIdGrupoBloco($_POST['selGrupoBloco']);

          $objBlocoRN = new BlocoRN();
          $objBlocoRN->alterarGrupo($objRelBlocoUnidadeDTO);

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrIdBloco)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelGrupoBloco = GrupoBlocoINT::montarSelectUnidade('null','&nbsp;', $_POST['selGrupoBloco']);

  if (SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_cadastrar')) {
    $strImgNovoGrupoBloco = '<img id="imgNovoGrupoBloco" onclick="cadastrarGrupoBloco();" src="'.PaginaSEI::getInstance()->getIconeMais().'" alt="Novo Grupo de Bloco" title="Novo Grupo de Bloco" class="infraImg" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"/>';
    $strLinkNovoGrupoBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&pagina_simples=1');
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
#lblSelGrupoBloco {position:absolute;left:0%;top:0%;width:50%;}
#selGrupoBloco {position:absolute;left:0%;top:10%;width:50%;}
#imgNovoGrupoBloco {position:absolute;left:50.5%;top:10.5%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
}

function OnSubmitForm() {
  return true;
}

function cadastrarGrupoBloco(){
  infraAbrirJanelaModal('<?=$strLinkNovoGrupoBloco?>',700,450);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBlocoAlterarGrupo" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->montarAreaValidacao();
  PaginaSEI::getInstance()->abrirAreaDados('20em');
  ?>
  <label id="lblSelGrupoBloco" for="selGrupoBloco" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
  <select id="selGrupoBloco" name="selGrupoBloco" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelGrupoBloco?>
  </select>
  <?=$strImgNovoGrupoBloco?>

  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="<?=implode(',',$arrIdBloco);?>" />

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