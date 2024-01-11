<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/11/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
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

  PaginaSEI::getInstance()->verificarSelecao('tipo_conferencia_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTipoConferenciaDTO = new TipoConferenciaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_conferencia_cadastrar':
      $strTitulo = 'Novo Tipo de Conferência';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoConferencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTipoConferenciaDTO->setNumIdTipoConferencia(null);
      $objTipoConferenciaDTO->setStrDescricao($_POST['txtDescricao']);
      $objTipoConferenciaDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTipoConferencia'])) {
        try{
          $objTipoConferenciaRN = new TipoConferenciaRN();
          $objTipoConferenciaDTO = $objTipoConferenciaRN->cadastrar($objTipoConferenciaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Conferência "'.$objTipoConferenciaDTO->getStrDescricao().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_conferencia='.$objTipoConferenciaDTO->getNumIdTipoConferencia().PaginaSEI::getInstance()->montarAncora($objTipoConferenciaDTO->getNumIdTipoConferencia())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_conferencia_alterar':
      $strTitulo = 'Alterar Tipo de Conferência';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoConferencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_conferencia'])){
        $objTipoConferenciaDTO->setNumIdTipoConferencia($_GET['id_tipo_conferencia']);
        $objTipoConferenciaDTO->retTodos();
        $objTipoConferenciaRN = new TipoConferenciaRN();
        $objTipoConferenciaDTO = $objTipoConferenciaRN->consultar($objTipoConferenciaDTO);
        if ($objTipoConferenciaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTipoConferenciaDTO->setNumIdTipoConferencia($_POST['hdnIdTipoConferencia']);
        $objTipoConferenciaDTO->setStrDescricao($_POST['txtDescricao']);
        $objTipoConferenciaDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoConferenciaDTO->getNumIdTipoConferencia())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoConferencia'])) {
        try{
          $objTipoConferenciaRN = new TipoConferenciaRN();
          $objTipoConferenciaRN->alterar($objTipoConferenciaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tipo de Conferência "'.$objTipoConferenciaDTO->getStrDescricao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTipoConferenciaDTO->getNumIdTipoConferencia())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_conferencia_consultar':
      $strTitulo = 'Consultar Tipo de Conferência';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tipo_conferencia'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTipoConferenciaDTO->setNumIdTipoConferencia($_GET['id_tipo_conferencia']);
      $objTipoConferenciaDTO->setBolExclusaoLogica(false);
      $objTipoConferenciaDTO->retTodos();
      $objTipoConferenciaRN = new TipoConferenciaRN();
      $objTipoConferenciaDTO = $objTipoConferenciaRN->consultar($objTipoConferenciaDTO);
      if ($objTipoConferenciaDTO===null){
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
#lblDescricao {position:absolute;left:0%;top:0%;width:70%;}
#txtDescricao {position:absolute;left:0%;top:6%;width:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_conferencia_cadastrar'){
    document.getElementById('txtDescricao').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_conferencia_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
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
<form id="frmTipoConferenciaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoConferenciaDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdTipoConferencia" name="hdnIdTipoConferencia" value="<?=$objTipoConferenciaDTO->getNumIdTipoConferencia();?>" />
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