<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2013 - criado por mga
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

  PaginaSEI::getInstance()->verificarSelecao('veiculo_imprensa_nacional_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'veiculo_imprensa_nacional_cadastrar':
      $strTitulo = 'Novo Veículo da Imprensa Nacional';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarVeiculoImprensaNacional" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional(null);
      $objVeiculoImprensaNacionalDTO->setStrSigla($_POST['txtSigla']);
      $objVeiculoImprensaNacionalDTO->setStrDescricao($_POST['txtDescricao']);

      if (isset($_POST['sbmCadastrarVeiculoImprensaNacional'])) {
        try{
          $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
          $objVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->cadastrar($objVeiculoImprensaNacionalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Veículo da Imprensa Nacional "'.$objVeiculoImprensaNacionalDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_veiculo_imprensa_nacional='.$objVeiculoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional().PaginaSEI::getInstance()->montarAncora($objVeiculoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'veiculo_imprensa_nacional_alterar':
      $strTitulo = 'Alterar Veículo da Imprensa Nacional';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarVeiculoImprensaNacional" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_veiculo_imprensa_nacional'])){
        $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);
        $objVeiculoImprensaNacionalDTO->retTodos();
        $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
        $objVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->consultar($objVeiculoImprensaNacionalDTO);
        if ($objVeiculoImprensaNacionalDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_POST['hdnIdVeiculoImprensaNacional']);
        $objVeiculoImprensaNacionalDTO->setStrSigla($_POST['txtSigla']);
        $objVeiculoImprensaNacionalDTO->setStrDescricao($_POST['txtDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objVeiculoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarVeiculoImprensaNacional'])) {
        try{
          $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
          $objVeiculoImprensaNacionalRN->alterar($objVeiculoImprensaNacionalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Veículo da Imprensa Nacional "'.$objVeiculoImprensaNacionalDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objVeiculoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'veiculo_imprensa_nacional_consultar':
      $strTitulo = 'Consultar Veículo da Imprensa Nacional';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_veiculo_imprensa_nacional'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);
      $objVeiculoImprensaNacionalDTO->setBolExclusaoLogica(false);
      $objVeiculoImprensaNacionalDTO->retTodos();
      $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
      $objVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->consultar($objVeiculoImprensaNacionalDTO);
      if ($objVeiculoImprensaNacionalDTO===null){
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
#lblSigla {position:absolute;left:0%;top:0%;width:15%;}
#txtSigla {position:absolute;left:0%;top:6%;width:15%;}

#lblDescricao {position:absolute;left:0%;top:16%;width:70%;}
#txtDescricao {position:absolute;left:0%;top:22%;width:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='veiculo_imprensa_nacional_cadastrar'){
    document.getElementById('txtSigla').focus();
  } else if ('<?=$_GET['acao']?>'=='veiculo_imprensa_nacional_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a Sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }

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
<form id="frmVeiculoImprensaNacionalCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objVeiculoImprensaNacionalDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,15);" maxlength="15" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objVeiculoImprensaNacionalDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdVeiculoImprensaNacional" name="hdnIdVeiculoImprensaNacional" value="<?=$objVeiculoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional();?>" />
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