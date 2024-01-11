<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/10/2018 - criado por cjy
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

  PaginaSEI::getInstance()->verificarSelecao('categoria_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objCategoriaDTO = new CategoriaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'categoria_cadastrar':
      $strTitulo = 'Nova Categoria';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCategoria" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objCategoriaDTO->setNumIdCategoria(null);
      $objCategoriaDTO->setStrNome($_POST['txtNome']);
      $objCategoriaDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarCategoria'])) {
        try{
          $objCategoriaRN = new CategoriaRN();
          $objCategoriaDTO = $objCategoriaRN->cadastrar($objCategoriaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Categoria "'.$objCategoriaDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_categoria='.$objCategoriaDTO->getNumIdCategoria().PaginaSEI::getInstance()->montarAncora($objCategoriaDTO->getNumIdCategoria())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'categoria_alterar':
      $strTitulo = 'Alterar Categoria';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCategoria" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_categoria'])){
        $objCategoriaDTO->setNumIdCategoria($_GET['id_categoria']);
        $objCategoriaDTO->retTodos();
        $objCategoriaRN = new CategoriaRN();
        $objCategoriaDTO = $objCategoriaRN->consultar($objCategoriaDTO);
        if ($objCategoriaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objCategoriaDTO->setNumIdCategoria($_POST['hdnIdCategoria']);
        $objCategoriaDTO->setStrNome($_POST['txtNome']);
        $objCategoriaDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCategoriaDTO->getNumIdCategoria())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCategoria'])) {
        try{
          $objCategoriaRN = new CategoriaRN();
          $objCategoriaRN->alterar($objCategoriaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Categoria "'.$objCategoriaDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCategoriaDTO->getNumIdCategoria())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'categoria_consultar':
      $strTitulo = 'Consultar Categoria';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_categoria'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objCategoriaDTO->setNumIdCategoria($_GET['id_categoria']);
      $objCategoriaDTO->setBolExclusaoLogica(false);
      $objCategoriaDTO->retTodos();
      $objCategoriaRN = new CategoriaRN();
      $objCategoriaDTO = $objCategoriaRN->consultar($objCategoriaDTO);
      if ($objCategoriaDTO===null){
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
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:40%;width:50%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='categoria_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='categoria_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

  function validarCadastro() {
    if (infraTrim(document.getElementById('txtNome').value)=='') {
      alert('Informe a Categoria.');
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
<form id="frmCategoriaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelOpcional">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objCategoriaDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdCategoria" name="hdnIdCategoria" value="<?=$objCategoriaDTO->getNumIdCategoria();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
//  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
