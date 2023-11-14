<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('grupo_serie_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objGrupoSerieDTO = new GrupoSerieDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'grupo_serie_cadastrar':
      $strTitulo = 'Novo Grupo de Tipos de Documento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoSerie" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objGrupoSerieDTO->setNumIdGrupoSerie(null);
      $objGrupoSerieDTO->setStrNome($_POST['txtNome']);
      $objGrupoSerieDTO->setStrDescricao($_POST['txaDescricao']);
      $objGrupoSerieDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarGrupoSerie'])) {
        try{
          $objGrupoSerieRN = new GrupoSerieRN();
          $objGrupoSerieDTO = $objGrupoSerieRN->cadastrarRN0775($objGrupoSerieDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Tipos de Documento "'.$objGrupoSerieDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo_serie='.$objGrupoSerieDTO->getNumIdGrupoSerie().'#ID-'.$objGrupoSerieDTO->getNumIdGrupoSerie()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_serie_alterar':
      $strTitulo = 'Alterar Grupo de Tipos de Documento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarGrupoSerie" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_grupo_serie'])){
        $objGrupoSerieDTO->setNumIdGrupoSerie($_GET['id_grupo_serie']);
        $objGrupoSerieDTO->retTodos();
        $objGrupoSerieRN = new GrupoSerieRN();
        $objGrupoSerieDTO = $objGrupoSerieRN->consultarRN0777($objGrupoSerieDTO);
        if ($objGrupoSerieDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objGrupoSerieDTO->setNumIdGrupoSerie($_POST['hdnIdGrupoSerie']);
        $objGrupoSerieDTO->setStrNome($_POST['txtNome']);
        $objGrupoSerieDTO->setStrDescricao($_POST['txaDescricao']);
        $objGrupoSerieDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objGrupoSerieDTO->getNumIdGrupoSerie().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoSerie'])) {
        try{
          $objGrupoSerieRN = new GrupoSerieRN();
          $objGrupoSerieRN->alterarRN0776($objGrupoSerieDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Tipos de Documento "'.$objGrupoSerieDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objGrupoSerieDTO->getNumIdGrupoSerie()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_serie_consultar':
      $strTitulo = "Consultar Grupo de Tipos de Documento";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_grupo_serie'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objGrupoSerieDTO->setNumIdGrupoSerie($_GET['id_grupo_serie']);
      $objGrupoSerieDTO->retTodos();
      $objGrupoSerieRN = new GrupoSerieRN();
      $objGrupoSerieDTO = $objGrupoSerieRN->consultarRN0777($objGrupoSerieDTO);
      if ($objGrupoSerieDTO===null){
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

#lblDescricao {position:absolute;left:0%;top:16%;width:95%;}
#txaDescricao {position:absolute;left:0%;top:22%;width:95%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='grupo_serie_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='grupo_serie_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarCadastroRI0799();
}

function validarCadastroRI0799() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txaDescricao').focus();
    return false;
  }

  return true;
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoSerieCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoSerieDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="e" class="infraLabelObrigatorio">D<span class="infraTeclaAtalho">e</span>scrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="3" class="infraTextarea" onkeypress="return infraMascaraTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objGrupoSerieDTO->getStrDescricao());?></textarea>

  <input type="hidden" id="hdnIdGrupoSerie" name="hdnIdGrupoSerie" value="<?=$objGrupoSerieDTO->getNumIdGrupoSerie();?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>