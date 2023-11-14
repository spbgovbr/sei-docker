<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
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

  PaginaSEI::getInstance()->verificarSelecao('campo_pesquisa_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selPesquisa'));

  $objCampoPesquisaDTO = new CampoPesquisaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'campo_pesquisa_cadastrar':
      $strTitulo = 'Nov ';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCampoPesquisa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objCampoPesquisaDTO->setNumIdCampoPesquisa(null);
      $objCampoPesquisaDTO->setNumChave($_POST['txtChave']);
      $objCampoPesquisaDTO->setStrValor($_POST['txtValor']);
      $numIdPesquisa = PaginaSEI::getInstance()->recuperarCampo('selPesquisa');
      if ($numIdPesquisa!==''){
        $objCampoPesquisaDTO->setNumIdPesquisa($numIdPesquisa);
      }else{
        $objCampoPesquisaDTO->setNumIdPesquisa(null);
      }


      if (isset($_POST['sbmCadastrarCampoPesquisa'])) {
        try{
          $objCampoPesquisaRN = new CampoPesquisaRN();
          $objCampoPesquisaDTO = $objCampoPesquisaRN->cadastrar($objCampoPesquisaDTO);
          PaginaSEI::getInstance()->adicionarMensagem(' "'.$objCampoPesquisaDTO->getNumIdCampoPesquisa().'" cadastrad com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_campo_pesquisa='.$objCampoPesquisaDTO->getNumIdCampoPesquisa().PaginaSEI::getInstance()->montarAncora($objCampoPesquisaDTO->getNumIdCampoPesquisa())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'campo_pesquisa_alterar':
      $strTitulo = 'Alterar ';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCampoPesquisa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_campo_pesquisa'])){
        $objCampoPesquisaDTO->setNumIdCampoPesquisa($_GET['id_campo_pesquisa']);
        $objCampoPesquisaDTO->retTodos();
        $objCampoPesquisaRN = new CampoPesquisaRN();
        $objCampoPesquisaDTO = $objCampoPesquisaRN->consultar($objCampoPesquisaDTO);
        if ($objCampoPesquisaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objCampoPesquisaDTO->setNumIdCampoPesquisa($_POST['hdnIdCampoPesquisa']);
        $objCampoPesquisaDTO->setNumChave($_POST['txtChave']);
        $objCampoPesquisaDTO->setStrValor($_POST['txtValor']);
        $objCampoPesquisaDTO->setNumIdPesquisa($_POST['selPesquisa']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCampoPesquisaDTO->getNumIdCampoPesquisa())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCampoPesquisa'])) {
        try{
          $objCampoPesquisaRN = new CampoPesquisaRN();
          $objCampoPesquisaRN->alterar($objCampoPesquisaDTO);
          PaginaSEI::getInstance()->adicionarMensagem(' "'.$objCampoPesquisaDTO->getNumIdCampoPesquisa().'" alterad com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCampoPesquisaDTO->getNumIdCampoPesquisa())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'campo_pesquisa_consultar':
      $strTitulo = 'Consultar ';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_campo_pesquisa'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objCampoPesquisaDTO->setNumIdCampoPesquisa($_GET['id_campo_pesquisa']);
      $objCampoPesquisaDTO->setBolExclusaoLogica(false);
      $objCampoPesquisaDTO->retTodos();
      $objCampoPesquisaRN = new CampoPesquisaRN();
      $objCampoPesquisaDTO = $objCampoPesquisaRN->consultar($objCampoPesquisaDTO);
      if ($objCampoPesquisaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

 // $strItensSelPesquisa = PesquisaINT::montarSelect???????('null','&nbsp;',$objCampoPesquisaDTO->getNumIdPesquisa());

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
#lblChave {position:absolute;left:0%;top:0%;width:25%;}
#txtChave {position:absolute;left:0%;top:40%;width:25%;}

#lblValor {position:absolute;left:0%;top:0%;width:95%;}
#txtValor {position:absolute;left:0%;top:40%;width:95%;}

#lblPesquisa {position:absolute;left:0%;top:0%;width:25%;}
#selPesquisa {position:absolute;left:0%;top:40%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='campo_pesquisa_cadastrar'){
    document.getElementById('txtChave').focus();
  } else if ('<?=$_GET['acao']?>'=='campo_pesquisa_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
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
<form id="frmCampoPesquisaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblChave" for="txtChave" accesskey="" class="infraLabelOpcional">chave:</label>
  <input type="text" id="txtChave" name="txtChave" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objCampoPesquisaDTO->getNumChave());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblValor" for="txtValor" accesskey="" class="infraLabelOpcional">valor:</label>
  <input type="text" id="txtValor" name="txtValor" class="infraText" value="<?=PaginaSEI::tratarHTML($objCampoPesquisaDTO->getStrValor());?>" onkeypress="return infraMascaraTexto(this,event,4000);" maxlength="4000" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblPesquisa" for="selPesquisa" accesskey="" class="infraLabelOpcional">id_pesquisa:</label>
  <select id="selPesquisa" name="selPesquisa" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelPesquisa?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdCampoPesquisa" name="hdnIdCampoPesquisa" value="<?=$objCampoPesquisaDTO->getNumIdCampoPesquisa();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
