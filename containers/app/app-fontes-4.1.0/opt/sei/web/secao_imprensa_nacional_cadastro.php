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

  PaginaSEI::getInstance()->verificarSelecao('secao_imprensa_nacional_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  
  if (isset($_GET['id_veiculo_imprensa_nacional'])){
    $strParametros .= '&id_veiculo_imprensa_nacional='.$_GET['id_veiculo_imprensa_nacional'];
  }
  
  PaginaSEI::getInstance()->salvarCamposPost(array('selVeiculoImprensaNacional'));

  $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'secao_imprensa_nacional_cadastrar':
      $strTitulo = 'Nova Seção do Veículo da Imprensa Nacional';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSecaoImprensaNacional" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional(null);
      $objSecaoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);
      $objSecaoImprensaNacionalDTO->setStrNome($_POST['txtNome']);
      $objSecaoImprensaNacionalDTO->setStrDescricao($_POST['txtDescricao']);

      if (isset($_POST['sbmCadastrarSecaoImprensaNacional'])) {
        try{
          $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
          $objSecaoImprensaNacionalDTO = $objSecaoImprensaNacionalRN->cadastrar($objSecaoImprensaNacionalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Seção do Veículo da Imprensa Nacional "'.$objSecaoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_secao_imprensa_nacional='.$objSecaoImprensaNacionalDTO->getNumIdSecaoImprensaNacional().$strParametros.PaginaSEI::getInstance()->montarAncora($objSecaoImprensaNacionalDTO->getNumIdSecaoImprensaNacional())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'secao_imprensa_nacional_alterar':
      $strTitulo = 'Alterar Seção do Veículo da Imprensa Nacional';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarSecaoImprensaNacional" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_secao_imprensa_nacional'])){
        $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($_GET['id_secao_imprensa_nacional']);
        $objSecaoImprensaNacionalDTO->retTodos();
        $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
        $objSecaoImprensaNacionalDTO = $objSecaoImprensaNacionalRN->consultar($objSecaoImprensaNacionalDTO);
        if ($objSecaoImprensaNacionalDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($_POST['hdnIdSecaoImprensaNacional']);
        $objSecaoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);
        $objSecaoImprensaNacionalDTO->setStrNome($_POST['txtNome']);
        $objSecaoImprensaNacionalDTO->setStrDescricao($_POST['txtDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objSecaoImprensaNacionalDTO->getNumIdSecaoImprensaNacional())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarSecaoImprensaNacional'])) {
        try{
          $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
          $objSecaoImprensaNacionalRN->alterar($objSecaoImprensaNacionalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Seção do Veículo da Imprensa Nacional "'.$objSecaoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objSecaoImprensaNacionalDTO->getNumIdSecaoImprensaNacional())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'secao_imprensa_nacional_consultar':
      $strTitulo = 'Consultar Seção do Veículo da Imprensa Nacional';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_secao_imprensa_nacional'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($_GET['id_secao_imprensa_nacional']);
      $objSecaoImprensaNacionalDTO->setBolExclusaoLogica(false);
      $objSecaoImprensaNacionalDTO->retTodos();
      $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
      $objSecaoImprensaNacionalDTO = $objSecaoImprensaNacionalRN->consultar($objSecaoImprensaNacionalDTO);
      if ($objSecaoImprensaNacionalDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
  $objVeiculoImprensaNacionalDTO->retStrSigla();
  $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);
  
  $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
  $objVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->consultar($objVeiculoImprensaNacionalDTO);
  
  if ($objVeiculoImprensaNacionalDTO==null){
    throw new InfraException('Veículo não encontrado.');
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
#lblVeiculoImprensaNacional {position:absolute;left:0%;top:0%;width:25%;}
#txtVeiculoImprensaNacional {position:absolute;left:0%;top:6%;width:25%;}

#lblNome {position:absolute;left:0%;top:16%;width:40%;}
#txtNome {position:absolute;left:0%;top:22%;width:40%;}

#lblDescricao {position:absolute;left:0%;top:32%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:38%;width:80%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='secao_imprensa_nacional_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='secao_imprensa_nacional_consultar'){
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
<form id="frmSecaoImprensaNacionalCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblVeiculoImprensaNacional" for="txtVeiculoImprensaNacional" accesskey="" class="infraLabelObrigatorio">Veículo da Imprensa Nacional:</label>
  <input type="text" id="txtVeiculoImprensaNacional" name="txtVeiculoImprensaNacional" value="<?=PaginaSEI::tratarHTML($objVeiculoImprensaNacionalDTO->getStrSigla())?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objSecaoImprensaNacionalDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objSecaoImprensaNacionalDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdSecaoImprensaNacional" name="hdnIdSecaoImprensaNacional" value="<?=$objSecaoImprensaNacionalDTO->getNumIdSecaoImprensaNacional();?>" />
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