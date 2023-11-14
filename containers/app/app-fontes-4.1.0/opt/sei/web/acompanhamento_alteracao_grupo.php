<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/08/2018 - criado por mga
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

  $arrComandos = array();

  switch($_GET['acao']){
    case 'acompanhamento_alterar_grupo':

      $strTitulo = 'Alterar Grupo dos Acompanhamentos Especiais';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAcompanhamentoGrupo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if ($_GET['acao_origem']=='acompanhamento_listar'){
        $arrIdAcompanhamento = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else{
        $arrIdAcompanhamento = explode(',',$_POST['hdnIdAcompanhamento']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrIdAcompanhamento)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarAcompanhamentoGrupo'])) {
        try{

          $objAcompanhamentoDTO = new AcompanhamentoDTO();
          $objAcompanhamentoDTO->setNumIdAcompanhamento($arrIdAcompanhamento);
          $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($_POST['selGrupoAcompanhamento']);

          $objAcompanhamentoRN = new AcompanhamentoRN();
          $objAcompanhamentoRN->alterarGrupo($objAcompanhamentoDTO);

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrIdAcompanhamento)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelGrupoAcompanhamento = GrupoAcompanhamentoINT::montarSelectIdGrupoAcompanhamentoRI0012('null','&nbsp;', $_POST['selGrupoAcompanhamento'], SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  if (SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_cadastrar')) {
    $strImgNovoGrupoAcompanhamento = '<img id="imgNovoGrupoAcompanhamento" onclick="cadastrarGrupoAcompanhamento();" src="'.PaginaSEI::getInstance()->getIconeMais().'" alt="Novo Grupo de Acompanhamento" title="Novo Grupo de Acompanhamento" class="infraImg" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"/>';
    $strLinkNovoGrupoAcompanhamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&pagina_simples=1');
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
#lblSelGrupoAcompanhamento {position:absolute;left:0%;top:0%;width:50%;}
#selGrupoAcompanhamento {position:absolute;left:0%;top:40%;width:50%;}
#imgNovoGrupoAcompanhamento {position:absolute;left:50.5%;top:45%;}

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

function cadastrarGrupoAcompanhamento(){
  infraAbrirJanelaModal('<?=$strLinkNovoGrupoAcompanhamento?>',700,250);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcompanhamentoAlterarGrupo" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->montarAreaValidacao();
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblSelGrupoAcompanhamento" for="selGrupoAcompanhamento" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
  <select id="selGrupoAcompanhamento" name="selGrupoAcompanhamento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelGrupoAcompanhamento?>
  </select>
  <?=$strImgNovoGrupoAcompanhamento?>

  <input type="hidden" id="hdnIdAcompanhamento" name="hdnIdAcompanhamento" value="<?=implode(',',$arrIdAcompanhamento);?>" />

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