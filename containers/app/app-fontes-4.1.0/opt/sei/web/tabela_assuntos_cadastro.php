<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
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

  PaginaSEI::getInstance()->verificarSelecao('tabela_assuntos_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTabelaAssuntosDTO = new TabelaAssuntosDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tabela_assuntos_cadastrar':
      $strTitulo = 'Nova Tabela de Assuntos';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTabelaAssuntos" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTabelaAssuntosDTO->setNumIdTabelaAssuntos(null);
      $objTabelaAssuntosDTO->setStrNome($_POST['txtNome']);
      $objTabelaAssuntosDTO->setStrDescricao($_POST['txaDescricao']);
      $objTabelaAssuntosDTO->setStrSinAtual('N');

      if (isset($_POST['sbmCadastrarTabelaAssuntos'])) {
        try{
          $objTabelaAssuntosRN = new TabelaAssuntosRN();
          $objTabelaAssuntosDTO = $objTabelaAssuntosRN->cadastrar($objTabelaAssuntosDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tabela de Assuntos "'.$objTabelaAssuntosDTO->getStrNome().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tabela_assuntos='.$objTabelaAssuntosDTO->getNumIdTabelaAssuntos().PaginaSEI::getInstance()->montarAncora($objTabelaAssuntosDTO->getNumIdTabelaAssuntos())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tabela_assuntos_alterar':
      $strTitulo = 'Alterar Tabela de Assuntos';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTabelaAssuntos" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tabela_assuntos'])){
        $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);
        $objTabelaAssuntosDTO->retTodos();
        $objTabelaAssuntosRN = new TabelaAssuntosRN();
        $objTabelaAssuntosDTO = $objTabelaAssuntosRN->consultar($objTabelaAssuntosDTO);
        if ($objTabelaAssuntosDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_POST['hdnIdTabelaAssuntos']);
        $objTabelaAssuntosDTO->setStrNome($_POST['txtNome']);
        $objTabelaAssuntosDTO->setStrDescricao($_POST['txaDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTabelaAssuntosDTO->getNumIdTabelaAssuntos())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTabelaAssuntos'])) {
        try{
          $objTabelaAssuntosRN = new TabelaAssuntosRN();
          $objTabelaAssuntosRN->alterar($objTabelaAssuntosDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tabela de Assuntos "'.$objTabelaAssuntosDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTabelaAssuntosDTO->getNumIdTabelaAssuntos())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tabela_assuntos_consultar':
      $strTitulo = 'Consultar Tabela de Assuntos';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tabela_assuntos'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);
      $objTabelaAssuntosDTO->setBolExclusaoLogica(false);
      $objTabelaAssuntosDTO->retTodos();
      $objTabelaAssuntosRN = new TabelaAssuntosRN();
      $objTabelaAssuntosDTO = $objTabelaAssuntosRN->consultar($objTabelaAssuntosDTO);
      if ($objTabelaAssuntosDTO===null){
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

#lblDescricao {position:absolute;left:0%;top:16%;width:70%;}
#txaDescricao {position:absolute;left:0%;top:22%;width:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="text/javascript">

function inicializar(){
  if ('<?=$_GET['acao']?>'=='tabela_assuntos_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='tabela_assuntos_consultar'){
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

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTabelaAssuntosCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTabelaAssuntosDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'2':'3'?>" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objTabelaAssuntosDTO->getStrDescricao())?></textarea>

  <input type="hidden" id="hdnIdTabelaAssuntos" name="hdnIdTabelaAssuntos" value="<?=$objTabelaAssuntosDTO->getNumIdTabelaAssuntos();?>" />
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