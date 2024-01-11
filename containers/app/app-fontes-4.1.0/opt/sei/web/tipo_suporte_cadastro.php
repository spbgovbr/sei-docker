<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
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

  PaginaSEI::getInstance()->verificarSelecao('tipo_suporte_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTipoSuporteDTO = new TipoSuporteDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_suporte_cadastrar':
      $strTitulo = 'Novo Tipo de Suporte';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoSuporte" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTipoSuporteDTO->setNumIdTipoSuporte(null);
      $objTipoSuporteDTO->setStrNome($_POST['txtNome']);
      $objTipoSuporteDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTipoSuporte'])) {
        try{
          $objTipoSuporteRN = new TipoSuporteRN();
          $objTipoSuporteDTO = $objTipoSuporteRN->cadastrarRN0631($objTipoSuporteDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Suporte "'.$objTipoSuporteDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_suporte='.$objTipoSuporteDTO->getNumIdTipoSuporte().'#ID-'.$objTipoSuporteDTO->getNumIdTipoSuporte()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_suporte_alterar':
      $strTitulo = 'Alterar Tipo de Suporte';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoSuporte" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_suporte'])){
        $objTipoSuporteDTO->setNumIdTipoSuporte($_GET['id_tipo_suporte']);
        $objTipoSuporteDTO->retTodos();
        $objTipoSuporteRN = new TipoSuporteRN();
        $objTipoSuporteDTO = $objTipoSuporteRN->consultarRN0633($objTipoSuporteDTO);
        if ($objTipoSuporteDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTipoSuporteDTO->setNumIdTipoSuporte($_POST['hdnIdTipoSuporte']);
        $objTipoSuporteDTO->setStrNome($_POST['txtNome']);
        $objTipoSuporteDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objTipoSuporteDTO->getNumIdTipoSuporte().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoSuporte'])) {
        try{
          $objTipoSuporteRN = new TipoSuporteRN();
          $objTipoSuporteRN->alterarRN0632($objTipoSuporteDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Suporte "'.$objTipoSuporteDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objTipoSuporteDTO->getNumIdTipoSuporte()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_suporte_consultar':
      $strTitulo = "Consultar Tipo de Suporte";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_tipo_suporte'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTipoSuporteDTO->setNumIdTipoSuporte($_GET['id_tipo_suporte']);
      $objTipoSuporteDTO->retTodos();
      $objTipoSuporteRN = new TipoSuporteRN();
      $objTipoSuporteDTO = $objTipoSuporteRN->consultarRN0633($objTipoSuporteDTO);
      if ($objTipoSuporteDTO===null){
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

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_suporte_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_suporte_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0650();
}

function validarFormRI0650() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  return true;
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoSuporteCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoSuporteDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdTipoSuporte" name="hdnIdTipoSuporte" value="<?=$objTipoSuporteDTO->getNumIdTipoSuporte();?>" />
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