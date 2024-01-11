<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->verificarSelecao('novidade_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objNovidadeDTO = new NovidadeDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'novidade_cadastrar':
      $strTitulo = 'Nova Novidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarNovidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objNovidadeDTO->setNumIdNovidade(null);
      $objNovidadeDTO->setStrTitulo($_POST['txtTitulo']);
      $objNovidadeDTO->setStrDescricao($_POST['txaDescricao']);

      if (isset($_POST['sbmCadastrarNovidade'])) {
        try{
          $objNovidadeRN = new NovidadeRN();
          $objNovidadeDTO = $objNovidadeRN->cadastrar($objNovidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Novidade cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_novidade='.$objNovidadeDTO->getNumIdNovidade().PaginaSEI::getInstance()->montarAncora($objNovidadeDTO->getNumIdNovidade())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'novidade_alterar':
      $strTitulo = 'Alterar Novidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarNovidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_novidade'])){
        $objNovidadeDTO->setNumIdNovidade($_GET['id_novidade']);
        $objNovidadeDTO->retTodos();
        $objNovidadeRN = new NovidadeRN();
        $objNovidadeDTO = $objNovidadeRN->consultar($objNovidadeDTO);
        if ($objNovidadeDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objNovidadeDTO->setNumIdNovidade($_POST['hdnIdNovidade']);
        $objNovidadeDTO->setStrTitulo($_POST['txtTitulo']);
        $objNovidadeDTO->setStrDescricao($_POST['txaDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objNovidadeDTO->getNumIdNovidade())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarNovidade'])) {
        try{
          $objNovidadeRN = new NovidadeRN();
          $objNovidadeRN->alterar($objNovidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Novidade alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objNovidadeDTO->getNumIdNovidade())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'novidade_consultar':
      $strTitulo = 'Consultar Novidade';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_novidade'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objNovidadeDTO->setNumIdNovidade($_GET['id_novidade']);
      $objNovidadeDTO->setBolExclusaoLogica(false);
      $objNovidadeDTO->retTodos();
      $objNovidadeRN = new NovidadeRN();
      $objNovidadeDTO = $objNovidadeRN->consultar($objNovidadeDTO);
      if ($objNovidadeDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  $objEditorRN=new EditorRN();
  $objEditorDTO=new EditorDTO();

  $objEditorDTO->setStrNomeCampo('txaDescricao');
  if ($_GET['acao']=='novidade_consultar') {
    $objEditorDTO->setStrSinSomenteLeitura('S');
  } else {
    $objEditorDTO->setStrSinSomenteLeitura('N');
  }

  $retEditor = $objEditorRN->montarSimples($objEditorDTO);

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
#lblTitulo {position:absolute;left:0%;top:0%;}
#txtTitulo {position:absolute;left:0%;top:32%;width:70%;}

#lblDescricao {position:absolute;left:0%;top:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='novidade_cadastrar'){
    document.getElementById('txtTitulo').focus();
  } else if ('<?=$_GET['acao']?>'=='novidade_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  infraEfeitoTabelas();
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtTitulo').value)=='') {
    alert('Informe o Título.');
    document.getElementById('txtTitulo').focus();
    return false;
  }

  if (CKEDITOR.instances.txaDescricao.getData())=='') {
    alert('Informe a Descrição.');
    document.getElementById('txaDescricao').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
echo $retEditor->getStrInicializacao();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmNovidadeCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('7em');
?>
  <label id="lblTitulo" for="txtTitulo" accesskey="T" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">T</span>ítulo:</label>
  <input type="text" id="txtTitulo" name="txtTitulo" class="infraText" value="<?=PaginaSEI::tratarHTML($objNovidadeDTO->getStrTitulo());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">D</span>escrição:</label>



  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  ?>

  <div id="divComandos" style="margin-left:0px;"></div>

      <div id="divEditores" style="overflow: auto;margin-right: 5%;">
        <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'7':'8'?>" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objNovidadeDTO->getStrDescricao());?></textarea>
        <script type="text/javascript">
          <?=$retEditor->getStrEditores();?>
        </script>
      </div>

  <input type="hidden" id="hdnIdNovidade" name="hdnIdNovidade" value="<?=$objNovidadeDTO->getNumIdNovidade();?>" />

  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>