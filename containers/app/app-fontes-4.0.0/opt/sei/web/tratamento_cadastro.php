<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
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

  PaginaSEI::getInstance()->verificarSelecao('tratamento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if (isset($_GET['cargo'])){
    $strParametros .= '&cargo='.$_GET['cargo'];
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $objTratamentoDTO = new TratamentoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  $bolOk = false;

  switch($_GET['acao']){
    case 'tratamento_cadastrar':
      $strTitulo = 'Novo Tratamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTratamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getTipoPagina()!=InfraPagina::$TIPO_PAGINA_SIMPLES){
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      $objTratamentoDTO->setNumIdTratamento(null);
      $objTratamentoDTO->setStrExpressao($_POST['txtExpressao']);
      $objTratamentoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTratamento'])) {
        try{
          $objTratamentoRN = new TratamentoRN();
          $objTratamentoDTO = $objTratamentoRN->cadastrarRN0315($objTratamentoDTO);

          if (isset($_GET['cargo'])){
           $bolOk = true;
          }else {
            PaginaSEI::getInstance()->setStrMensagem('Tratamento "' . $objTratamentoDTO->getStrExpressao() . '" cadastrado com sucesso.');
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_tratamento=' . $objTratamentoDTO->getNumIdTratamento() . '#ID-' . $objTratamentoDTO->getNumIdTratamento()));
            die;
          }
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tratamento_alterar':
      $strTitulo = 'Alterar Tratamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTratamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tratamento'])){
        $objTratamentoDTO->setNumIdTratamento($_GET['id_tratamento']);
        $objTratamentoDTO->retTodos();
        $objTratamentoRN = new TratamentoRN();
        $objTratamentoDTO = $objTratamentoRN->consultarRN0317($objTratamentoDTO);
        if ($objTratamentoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTratamentoDTO->setNumIdTratamento($_POST['hdnIdTratamento']);
        $objTratamentoDTO->setStrExpressao($_POST['txtExpressao']);
        $objTratamentoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objTratamentoDTO->getNumIdTratamento().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTratamento'])) {
        try{
          $objTratamentoRN = new TratamentoRN();
          $objTratamentoRN->alterarRN0316($objTratamentoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tratamento "'.$objTratamentoDTO->getStrExpressao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objTratamentoDTO->getNumIdTratamento()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tratamento_consultar':
      $strTitulo = "Consultar Tratamento";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_tratamento'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTratamentoDTO->setNumIdTratamento($_GET['id_tratamento']);
      $objTratamentoDTO->retTodos();
      $objTratamentoRN = new TratamentoRN();
      $objTratamentoDTO = $objTratamentoRN->consultarRN0317($objTratamentoDTO);
      if ($objTratamentoDTO===null){
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
#lblExpressao {position:absolute;left:0%;top:0%;width:60%;}
#txtExpressao {position:absolute;left:0%;top:6%;width:60%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  <?if ($bolOk){?>
    var sel = window.parent.document.getElementById('selTratamento');
    infraSelectAdicionarOption(sel,'<?=PaginaSEI::tratarHTML($objTratamentoDTO->getStrExpressao())?>','<?=$objTratamentoDTO->getNumIdTratamento()?>');
    infraSelectSelecionarItem(sel,'<?=$objTratamentoDTO->getNumIdTratamento()?>');
    self.setTimeout('infraFecharJanelaModal()',200);
  <?}else{?>

    if ('<?=$_GET['acao']?>'=='tratamento_cadastrar'){
      document.getElementById('txtExpressao').focus();
    } else if ('<?=$_GET['acao']?>'=='tratamento_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas();
  <?}?>
}

function OnSubmitForm() {
  return validarFormRI0331();
}

function validarFormRI0331() {
  if (infraTrim(document.getElementById('txtExpressao').value)=='') {
    alert('Informe a Expressão.');
    document.getElementById('txtExpressao').focus();
    return false;
  }

  return true;
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTratamentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblExpressao" for="txtExpressao" accesskey="E" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">E</span>xpressão:</label>
  <input type="text" id="txtExpressao" name="txtExpressao" class="infraText" value="<?=PaginaSEI::tratarHTML($objTratamentoDTO->getStrExpressao());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdTratamento" name="hdnIdTratamento" value="<?=$objTratamentoDTO->getNumIdTratamento();?>" />
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