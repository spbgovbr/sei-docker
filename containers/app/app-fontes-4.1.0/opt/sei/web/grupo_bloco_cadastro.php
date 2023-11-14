<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->verificarSelecao('grupo_bloco_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('pagina_simples', 'arvore', 'id_procedimento'));

  if (isset($_GET['pagina_simples'])) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $objGrupoBlocoDTO = new GrupoBlocoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'grupo_bloco_cadastrar':
      $strTitulo = 'Novo Grupo de Bloco';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoBloco" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getTipoPagina()!=InfraPagina::$TIPO_PAGINA_SIMPLES){
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      $objGrupoBlocoDTO->setNumIdGrupoBloco(null);
      $objGrupoBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objGrupoBlocoDTO->setStrNome($_POST['txtNome']);
      $objGrupoBlocoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarGrupoBloco'])) {
        try{
          $objGrupoBlocoRN = new GrupoBlocoRN();
          $objGrupoBlocoDTO = $objGrupoBlocoRN->cadastrar($objGrupoBlocoDTO);

          if (PaginaSEI::getInstance()->getAcaoRetorno()!='grupo_bloco_listar'){
            $bolOk = true;
          }else {
            PaginaSEI::getInstance()->adicionarMensagem('Grupo de Bloco "'.$objGrupoBlocoDTO->getStrNome().'" cadastrado com sucesso.');
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo_bloco='.$objGrupoBlocoDTO->getNumIdGrupoBloco().PaginaSEI::getInstance()->montarAncora($objGrupoBlocoDTO->getNumIdGrupoBloco())));
            die;
          }
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_bloco_alterar':
      $strTitulo = 'Alterar Grupo de Bloco';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarGrupoBloco" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_grupo_bloco'])){
        $objGrupoBlocoDTO->setNumIdGrupoBloco($_GET['id_grupo_bloco']);
        $objGrupoBlocoDTO->setBolExclusaoLogica(false);
        $objGrupoBlocoDTO->retTodos();
        $objGrupoBlocoRN = new GrupoBlocoRN();
        $objGrupoBlocoDTO = $objGrupoBlocoRN->consultar($objGrupoBlocoDTO);
        if ($objGrupoBlocoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objGrupoBlocoDTO->setNumIdGrupoBloco($_POST['hdnIdGrupoBloco']);
        $objGrupoBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objGrupoBlocoDTO->setStrNome($_POST['txtNome']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoBlocoDTO->getNumIdGrupoBloco())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoBloco'])) {
        try{
          $objGrupoBlocoRN = new GrupoBlocoRN();
          $objGrupoBlocoRN->alterar($objGrupoBlocoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Grupo de Bloco "'.$objGrupoBlocoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoBlocoDTO->getNumIdGrupoBloco())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_bloco_consultar':
      $strTitulo = 'Consultar Grupo de Bloco';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_grupo_bloco'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objGrupoBlocoDTO->setNumIdGrupoBloco($_GET['id_grupo_bloco']);
      $objGrupoBlocoDTO->setBolExclusaoLogica(false);
      $objGrupoBlocoDTO->retTodos();
      $objGrupoBlocoRN = new GrupoBlocoRN();
      $objGrupoBlocoDTO = $objGrupoBlocoRN->consultar($objGrupoBlocoDTO);
      if ($objGrupoBlocoDTO===null){
        throw new InfraException("Registro não encontrado.");
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
<?if(0){?><style><?}?>

#lblNome {position:absolute;left:0%;top:0%;width:75%;}
#txtNome {position:absolute;left:0%;top:40%;width:75%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){

  <?if ($bolOk){?>
    var sel = window.parent.document.getElementById('selGrupoBloco');
    infraSelectAdicionarOption(sel,'<?=PaginaSEI::tratarHTML($objGrupoBlocoDTO->getStrNome())?>','<?=$objGrupoBlocoDTO->getNumIdGrupoBloco()?>');
    infraSelectSelecionarItem(sel,'<?=$objGrupoBlocoDTO->getNumIdGrupoBloco()?>');
    self.setTimeout('infraFecharJanelaModal()',200);

  <?}else{?>

    if ('<?=$_GET['acao']?>'=='grupo_bloco_cadastrar'){
      document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='grupo_bloco_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas(true);
  <?}?>
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoBlocoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoBlocoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdGrupoBloco" name="hdnIdGrupoBloco" value="<?=$objGrupoBlocoDTO->getNumIdGrupoBloco();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
