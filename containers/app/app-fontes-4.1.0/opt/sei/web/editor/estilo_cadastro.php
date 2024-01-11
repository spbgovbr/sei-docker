<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: estilo_cadastro.php 10035 2015-06-09 15:10:40Z mga $
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('estilo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objEstiloDTO = new EstiloDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'estilo_cadastrar':
      $strTitulo = 'Novo Estilo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEstilo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objEstiloDTO->setNumIdEstilo(null);
      $objEstiloDTO->setStrNome($_POST['txtNome']);
      $objEstiloDTO->setStrFormatacao($_POST['txaFormatacao']);

      if (isset($_POST['sbmCadastrarEstilo'])) {
        try{
          $objEstiloRN = new EstiloRN();
          $objEstiloDTO = $objEstiloRN->cadastrar($objEstiloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Estilo "'.$objEstiloDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_estilo='.$objEstiloDTO->getNumIdEstilo().PaginaSEI::getInstance()->montarAncora($objEstiloDTO->getNumIdEstilo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'estilo_alterar':
      $strTitulo = 'Alterar Estilo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarEstilo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_estilo'])){
        $objEstiloDTO->setNumIdEstilo($_GET['id_estilo']);
        $objEstiloDTO->retTodos();
        $objEstiloRN = new EstiloRN();
        $objEstiloDTO = $objEstiloRN->consultar($objEstiloDTO);
        if ($objEstiloDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objEstiloDTO->setNumIdEstilo($_POST['hdnIdEstilo']);
        $objEstiloDTO->setStrNome($_POST['txtNome']);
        $objEstiloDTO->setStrFormatacao($_POST['txaFormatacao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objEstiloDTO->getNumIdEstilo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarEstilo'])) {
        try{
          $objEstiloRN = new EstiloRN();
          $objEstiloRN->alterar($objEstiloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Estilo "'.$objEstiloDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objEstiloDTO->getNumIdEstilo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'estilo_consultar':
      $strTitulo = 'Consultar Estilo';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_estilo'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objEstiloDTO->setNumIdEstilo($_GET['id_estilo']);
      $objEstiloDTO->setBolExclusaoLogica(false);
      $objEstiloDTO->retTodos();
      $objEstiloRN = new EstiloRN();
      $objEstiloDTO = $objEstiloRN->consultar($objEstiloDTO);
      if ($objEstiloDTO===null){
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
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:6%;width:50%;}

#lblFormatacao {position:absolute;left:0%;top:16%;width:75%;}
#txaFormatacao {position:absolute;left:0%;top:22%;width:75%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='estilo_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='estilo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaFormatacao').value)=='') {
    alert('Informe a Formatação.');
    document.getElementById('txaFormatacao').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstiloCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="n" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objEstiloDTO->getStrNome())?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblFormatacao" for="txaFormatacao" accesskey="t" class="infraLabelObrigatorio">Forma<span class="infraTeclaAtalho">t</span>ação:</label>
  <textarea id="txaFormatacao" name="txaFormatacao" rows="10"  class="infraTextarea" style="font-family: Courier, 'Courier New', monospace" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objEstiloDTO->getStrFormatacao())?></textarea>

  <input type="hidden" id="hdnIdEstilo" name="hdnIdEstilo" value="<?=$objEstiloDTO->getNumIdEstilo();?>" />
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