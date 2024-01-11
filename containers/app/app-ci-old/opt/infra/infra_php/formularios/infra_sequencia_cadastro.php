<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

try {
  //require_once 'Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->verificarSelecao('infra_sequencia_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  $objInfraSequenciaDTO = new InfraSequenciaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'infra_sequencia_cadastrar':
      $strTitulo = 'Nova Sequência';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarInfraSequencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objInfraSequenciaDTO->setStrNome($_POST['txtNome']);
      $objInfraSequenciaDTO->setDblQtdIncremento($_POST['txtQtdIncremento']);
      $objInfraSequenciaDTO->setDblNumAtual($_POST['txtNumAtual']);
      $objInfraSequenciaDTO->setDblNumMaximo($_POST['txtNumMaximo']);

      if (isset($_POST['sbmCadastrarInfraSequencia'])) {
        try{
          $objInfraSequenciaRN = new InfraSequenciaRN();
          $objInfraSequenciaDTO = $objInfraSequenciaRN->cadastrar($objInfraSequenciaDTO);
          PaginaInfra::getInstance()->setStrMensagem('Sequência "'.$objInfraSequenciaDTO->getStrNome().'" cadastrada com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&nome='.$objInfraSequenciaDTO->getStrNome().PaginaInfra::getInstance()->montarAncora($objInfraSequenciaDTO->getStrNome())));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'infra_sequencia_alterar':
      $strTitulo = 'Alterar Sequência';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarInfraSequencia" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['nome'])){
        $objInfraSequenciaDTO->setStrNome($_GET['nome']);
        $objInfraSequenciaDTO->retTodos();
        $objInfraSequenciaRN = new InfraSequenciaRN();
        $objInfraSequenciaDTO = $objInfraSequenciaRN->consultar($objInfraSequenciaDTO);
        if ($objInfraSequenciaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objInfraSequenciaDTO->setStrNome($_POST['hdnNome']);
        $objInfraSequenciaDTO->setDblQtdIncremento($_POST['txtQtdIncremento']);
        $objInfraSequenciaDTO->setDblNumAtual($_POST['txtNumAtual']);
        $objInfraSequenciaDTO->setDblNumMaximo($_POST['txtNumMaximo']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($objInfraSequenciaDTO->getStrNome())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarInfraSequencia'])) {
        try{
          $objInfraSequenciaRN = new InfraSequenciaRN();
          $objInfraSequenciaRN->alterar($objInfraSequenciaDTO);
          PaginaInfra::getInstance()->setStrMensagem('Sequência "'.$objInfraSequenciaDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($objInfraSequenciaDTO->getStrNome())));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'infra_sequencia_consultar':
      $strTitulo = 'Consultar Sequência';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($_GET['nome'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objInfraSequenciaDTO->setStrNome($_GET['nome']);
      $objInfraSequenciaDTO->setBolExclusaoLogica(false);
      $objInfraSequenciaDTO->retTodos();
      $objInfraSequenciaRN = new InfraSequenciaRN();
      $objInfraSequenciaDTO = $objInfraSequenciaRN->consultar($objInfraSequenciaDTO);
      if ($objInfraSequenciaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
#lblNome {position:absolute;left:0%;top:0%;width:25%;}
#txtNome {position:absolute;left:0%;top:6%;width:25%;}

#lblQtdIncremento {position:absolute;left:0%;top:16%;width:25%;}
#txtQtdIncremento {position:absolute;left:0%;top:22%;width:25%;}

#lblNumAtual {position:absolute;left:0%;top:32%;width:25%;}
#txtNumAtual {position:absolute;left:0%;top:38%;width:25%;}

#lblNumMaximo {position:absolute;left:0%;top:48%;width:25%;}
#txtNumMaximo {position:absolute;left:0%;top:54%;width:25%;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_sequencia_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='infra_sequencia_alterar'){
    document.getElementById('txtQtdIncremento').focus();
  } else if ('<?=$_GET['acao']?>'=='infra_sequencia_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoImagens();
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtQtdIncremento').value)=='') {
    alert('Informe o Incremento.');
    document.getElementById('txtQtdIncremento').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtNumAtual').value)=='') {
    alert('Informe o Valor Atual.');
    document.getElementById('txtNumAtual').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtNumMaximo').value)=='') {
    alert('Informe o Valor Máximo.');
    document.getElementById('txtNumMaximo').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraSequenciaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaInfra::getInstance()->montarAreaValidacao();
PaginaInfra::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSequenciaDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />

  <label id="lblQtdIncremento" for="txtQtdIncremento" accesskey="I" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">I</span>ncremento:</label>
  <input type="text" id="txtQtdIncremento" name="txtQtdIncremento" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSequenciaDTO->getDblQtdIncremento());?>" onkeypress="return infraMascaraNumero(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <label id="lblNumAtual" for="txtNumAtual" accesskey="A" class="infraLabelObrigatorio">V<span class="infraTeclaAtalho">a</span>lor Atual:</label>
  <input type="text" id="txtNumAtual" name="txtNumAtual" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSequenciaDTO->getDblNumAtual());?>" onkeypress="return infraMascaraNumero(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <label id="lblNumMaximo" for="txtNumMaximo" accesskey="M" class="infraLabelObrigatorio">Valor <span class="infraTeclaAtalho">M</span>áximo:</label>
  <input type="text" id="txtNumMaximo" name="txtNumMaximo" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSequenciaDTO->getDblNumMaximo());?>" onkeypress="return infraMascaraNumero(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnNome" name="hdnNome" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraSequenciaDTO->getStrNome());?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  //PaginaInfra::getInstance()->montarAreaDebug();
  //PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>