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
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('lugar_localizador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objLugarLocalizadorDTO = new LugarLocalizadorDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'lugar_localizador_cadastrar':
      $strTitulo = 'Novo Lugar de Localizador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarLugarLocalizador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objLugarLocalizadorDTO->setNumIdLugarLocalizador(null);
      $objLugarLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objLugarLocalizadorDTO->setStrNome($_POST['txtNome']);
      $objLugarLocalizadorDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarLugarLocalizador'])) {
        try{
          $objLugarLocalizadorRN = new LugarLocalizadorRN();
          $objLugarLocalizadorDTO = $objLugarLocalizadorRN->cadastrarRN0651($objLugarLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Lugar de Localizador "'.$objLugarLocalizadorDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_lugar_localizador='.$objLugarLocalizadorDTO->getNumIdLugarLocalizador().'#ID-'.$objLugarLocalizadorDTO->getNumIdLugarLocalizador()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'lugar_localizador_alterar':
      $strTitulo = 'Alterar Lugar de Localizador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarLugarLocalizador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_lugar_localizador'])){
        $objLugarLocalizadorDTO->setNumIdLugarLocalizador($_GET['id_lugar_localizador']);
        $objLugarLocalizadorDTO->retTodos();
        $objLugarLocalizadorRN = new LugarLocalizadorRN();
        $objLugarLocalizadorDTO = $objLugarLocalizadorRN->consultarRN0653($objLugarLocalizadorDTO);
        if ($objLugarLocalizadorDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objLugarLocalizadorDTO->setNumIdLugarLocalizador($_POST['hdnIdLugarLocalizador']);
        $objLugarLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objLugarLocalizadorDTO->setStrNome($_POST['txtNome']);
        $objLugarLocalizadorDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objLugarLocalizadorDTO->getNumIdLugarLocalizador().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarLugarLocalizador'])) {
        try{
          $objLugarLocalizadorRN = new LugarLocalizadorRN();
          $objLugarLocalizadorRN->alterarRN0652($objLugarLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Local de Localizador "'.$objLugarLocalizadorDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objLugarLocalizadorDTO->getNumIdLugarLocalizador()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'lugar_localizador_consultar':
      $strTitulo = "Consultar Lugar de Localizador";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_lugar_localizador'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objLugarLocalizadorDTO->setNumIdLugarLocalizador($_GET['id_lugar_localizador']);
      $objLugarLocalizadorDTO->retTodos();
      $objLugarLocalizadorRN = new LugarLocalizadorRN();
      $objLugarLocalizadorDTO = $objLugarLocalizadorRN->consultarRN0653($objLugarLocalizadorDTO);
      if ($objLugarLocalizadorDTO===null){
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

#lblNome {position:absolute;left:0%;top:0%;width:60%;}
#txtNome {position:absolute;left:0%;top:6%;width:60%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='lugar_localizador_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='lugar_localizador_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0662();
}

function validarFormRI0662() {
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
<form id="frmLugarLocalizadorCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objLugarLocalizadorDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdLugarLocalizador" name="hdnIdLugarLocalizador" value="<?=$objLugarLocalizadorDTO->getNumIdLugarLocalizador();?>" />
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