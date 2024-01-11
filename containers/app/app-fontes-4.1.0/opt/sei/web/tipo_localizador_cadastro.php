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
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('tipo_localizador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTipoLocalizadorDTO = new TipoLocalizadorDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_localizador_cadastrar':
      $strTitulo = 'Novo Tipo de Localizador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoLocalizador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTipoLocalizadorDTO->setNumIdTipoLocalizador(null);
      $objTipoLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objTipoLocalizadorDTO->setStrSigla($_POST['txtSigla']);
      $objTipoLocalizadorDTO->setStrNome($_POST['txtNome']);
      $objTipoLocalizadorDTO->setStrDescricao($_POST['txaDescricao']);
      $objTipoLocalizadorDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTipoLocalizador'])) {
        try{
          $objTipoLocalizadorRN = new TipoLocalizadorRN();
          $objTipoLocalizadorDTO = $objTipoLocalizadorRN->cadastrarRN0605($objTipoLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Localizador "'.$objTipoLocalizadorDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_localizador='.$objTipoLocalizadorDTO->getNumIdTipoLocalizador().'#ID-'.$objTipoLocalizadorDTO->getNumIdTipoLocalizador()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_localizador_alterar':
      $strTitulo = 'Alterar Tipo de Localizador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoLocalizador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_localizador'])){
        $objTipoLocalizadorDTO->setNumIdTipoLocalizador($_GET['id_tipo_localizador']);
        $objTipoLocalizadorDTO->retTodos();
        $objTipoLocalizadorRN = new TipoLocalizadorRN();
        $objTipoLocalizadorDTO = $objTipoLocalizadorRN->consultarRN0607($objTipoLocalizadorDTO);
        if ($objTipoLocalizadorDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTipoLocalizadorDTO->setNumIdTipoLocalizador($_POST['hdnIdTipoLocalizador']);
        $objTipoLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objTipoLocalizadorDTO->setStrSigla($_POST['txtSigla']);
        $objTipoLocalizadorDTO->setStrNome($_POST['txtNome']);
        $objTipoLocalizadorDTO->setStrDescricao($_POST['txaDescricao']);
        $objTipoLocalizadorDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objTipoLocalizadorDTO->getNumIdTipoLocalizador().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoLocalizador'])) {
        try{
          $objTipoLocalizadorRN = new TipoLocalizadorRN();
          $objTipoLocalizadorRN->alterarRN0606($objTipoLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Localizador "'.$objTipoLocalizadorDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objTipoLocalizadorDTO->getNumIdTipoLocalizador()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_localizador_consultar':
      $strTitulo = "Consultar Tipo de Localizador";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_tipo_localizador'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTipoLocalizadorDTO->setNumIdTipoLocalizador($_GET['id_tipo_localizador']);
      $objTipoLocalizadorDTO->retTodos();
      $objTipoLocalizadorRN = new TipoLocalizadorRN();
      $objTipoLocalizadorDTO = $objTipoLocalizadorRN->consultarRN0607($objTipoLocalizadorDTO);
      if ($objTipoLocalizadorDTO===null){
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

#lblSigla {position:absolute;left:0%;top:0%;width:20%;}
#txtSigla {position:absolute;left:0%;top:6%;width:20%;}

#lblNome {position:absolute;left:0%;top:16%;width:60%;}
#txtNome {position:absolute;left:0%;top:22%;width:60%;}

#lblDescricao {position:absolute;left:0%;top:32%;}
#txaDescricao {position:absolute;left:0%;top:38%;width:80%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_localizador_cadastrar'){
    document.getElementById('txtSigla').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_localizador_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0641();
}

function validarFormRI0641() {
  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a Sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }
  
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
<form id="frmTipoLocalizadorCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoLocalizadorDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,20);" maxlength="20" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoLocalizadorDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="3" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objTipoLocalizadorDTO->getStrDescricao());?></textarea>
  
  <input type="hidden" id="hdnIdTipoLocalizador" name="hdnIdTipoLocalizador" value="<?=$objTipoLocalizadorDTO->getNumIdTipoLocalizador();?>" />
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