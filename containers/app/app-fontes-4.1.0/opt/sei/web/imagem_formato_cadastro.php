<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/04/2014 - criado por mga
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

  PaginaSEI::getInstance()->verificarSelecao('imagem_formato_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objImagemFormatoDTO = new ImagemFormatoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'imagem_formato_cadastrar':
      $strTitulo = 'Novo Formato de Imagem Permitido';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarImagemFormato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objImagemFormatoDTO->setNumIdImagemFormato(null);
      $objImagemFormatoDTO->setStrFormato($_POST['txtFormato']);
      $objImagemFormatoDTO->setStrDescricao($_POST['txtDescricao']);
      $objImagemFormatoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarImagemFormato'])) {
        try{
          $objImagemFormatoRN = new ImagemFormatoRN();
          $objImagemFormatoDTO = $objImagemFormatoRN->cadastrar($objImagemFormatoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Formato de Imagem Permitido "'.$objImagemFormatoDTO->getStrFormato().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_imagem_formato='.$objImagemFormatoDTO->getNumIdImagemFormato().PaginaSEI::getInstance()->montarAncora($objImagemFormatoDTO->getNumIdImagemFormato())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'imagem_formato_alterar':
      $strTitulo = 'Alterar Formato de Imagem Permitido';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarImagemFormato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_imagem_formato'])){
        $objImagemFormatoDTO->setNumIdImagemFormato($_GET['id_imagem_formato']);
        $objImagemFormatoDTO->retTodos();
        $objImagemFormatoRN = new ImagemFormatoRN();
        $objImagemFormatoDTO = $objImagemFormatoRN->consultar($objImagemFormatoDTO);
        if ($objImagemFormatoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objImagemFormatoDTO->setNumIdImagemFormato($_POST['hdnIdImagemFormato']);
        $objImagemFormatoDTO->setStrFormato($_POST['txtFormato']);
        $objImagemFormatoDTO->setStrDescricao($_POST['txtDescricao']);
        $objImagemFormatoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objImagemFormatoDTO->getNumIdImagemFormato())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarImagemFormato'])) {
        try{
          $objImagemFormatoRN = new ImagemFormatoRN();
          $objImagemFormatoRN->alterar($objImagemFormatoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Formato de Imagem Permitido "'.$objImagemFormatoDTO->getStrFormato().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objImagemFormatoDTO->getNumIdImagemFormato())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'imagem_formato_consultar':
      $strTitulo = 'Consultar Formato de Imagem Permitido';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_imagem_formato'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objImagemFormatoDTO->setNumIdImagemFormato($_GET['id_imagem_formato']);
      $objImagemFormatoDTO->setBolExclusaoLogica(false);
      $objImagemFormatoDTO->retTodos();
      $objImagemFormatoRN = new ImagemFormatoRN();
      $objImagemFormatoDTO = $objImagemFormatoRN->consultar($objImagemFormatoDTO);
      if ($objImagemFormatoDTO===null){
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
#lblFormato {position:absolute;left:0%;top:0%;width:10%;}
#txtFormato {position:absolute;left:0%;top:6%;width:10%;}

#lblDescricao {position:absolute;left:0%;top:16%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:22%;width:80%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='imagem_formato_cadastrar'){
    document.getElementById('txtFormato').focus();
  } else if ('<?=$_GET['acao']?>'=='imagem_formato_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtFormato').value)=='') {
    alert('Informe o Formato.');
    document.getElementById('txtFormato').focus();
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
<form id="frmImagemFormatoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblFormato" for="txtFormato" accesskey="" class="infraLabelObrigatorio">Formato:</label>
  <input type="text" id="txtFormato" name="txtFormato" class="infraText" value="<?=PaginaSEI::tratarHTML($objImagemFormatoDTO->getStrFormato());?>" onkeypress="return infraMascaraTexto(this,event,10);" maxlength="10" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objImagemFormatoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdImagemFormato" name="hdnIdImagemFormato" value="<?=$objImagemFormatoDTO->getNumIdImagemFormato();?>" />
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