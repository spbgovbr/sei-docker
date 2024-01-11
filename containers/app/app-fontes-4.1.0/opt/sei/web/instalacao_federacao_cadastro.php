<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/04/2019 - criado por mga
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'instalacao_federacao_cadastrar':
      $strTitulo = 'Enviar Solicitação de Registro';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSolicitarRegistro" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao(null);

      if (isset($_GET['endereco_instalacao'])){
        $objInstalacaoFederacaoDTO->setStrEndereco($_GET['endereco_instalacao']);
      }else{
        $objInstalacaoFederacaoDTO->setStrEndereco($_POST['txtEndereco']);
      }


      if (isset($_POST['sbmSolicitarRegistro'])) {
        try{
          $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
          $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->solicitarRegistro($objInstalacaoFederacaoDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_instalacao_federacao='.$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao().PaginaSEI::getInstance()->montarAncora($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'instalacao_federacao_alterar':
      $strTitulo = 'Alterar Endereço da Instalação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarRegistro" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_instalacao_federacao'])){
        $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);
        $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objInstalacaoFederacaoDTO->retStrEndereco();
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->consultar($objInstalacaoFederacaoDTO);
        if ($objInstalacaoFederacaoDTO==null){
          throw new InfraException("Instalação não encontrada.");
        }
      } else {
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($_POST['hdnIdInstalacaoFederacao']);
        $objInstalacaoFederacaoDTO->setStrEndereco($_POST['txtEndereco']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarRegistro'])) {
        try{
          $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
          $objInstalacaoFederacaoRN->alterarRegistro($objInstalacaoFederacaoDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
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
#lblEndereco {position:absolute;left:0%;top:0%;width:50%;}
#txtEndereco {position:absolute;left:0%;top:40%;width:50%;}
<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  document.getElementById('txtEndereco').focus();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtEndereco').value)=='') {
    alert('Informe o Endereço.');
    document.getElementById('txtEndereco').focus();
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
<form id="frmInstalacaoFederacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblEndereco" for="txtEndereco" accesskey="" class="infraLabelObrigatorio">Endereço:</label>
  <input type="text" id="txtEndereco" name="txtEndereco" class="infraText" value="<?=PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrEndereco());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdInstalacaoFederacao" name="hdnIdInstalacaoFederacao" value="<?=$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
