<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
* 11/06/2018 - cjy - permitir sigla nula, se nao for brasil
*
* Versão do Gerador de Código: 1.12.0
*
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

  PaginaSEI::getInstance()->verificarSelecao('uf_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selPais'));

  $objUfDTO = new UfDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'uf_cadastrar':
      $strTitulo = 'Novo Estado';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarUf" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objUfDTO->setNumIdUf(null);

      $numIdPais = PaginaSEI::getInstance()->recuperarCampo('selPais');

      if ($numIdPais!==''){
        $objUfDTO->setNumIdPais($numIdPais);
      }else{
        $objUfDTO->setNumIdPais(null);
      }

      //$objUfDTO->setNumIdUf($_POST['txtCodigo']);
      if ($numIdPais==PaisINT::buscarIdPaisBrasil()) {
        $objUfDTO->setNumCodigoIbge($_POST['txtCodigo']);
      } else {
      	$objUfDTO->setNumCodigoIbge(null);
      }

      $objUfDTO->setStrSigla($_POST['txtSigla']);
      $objUfDTO->setStrNome($_POST['txtNome']);

      if (isset($_POST['sbmCadastrarUf'])) {
        try{
          $objUfRN = new UfRN();
          $objUfDTO = $objUfRN->cadastrarRN0398($objUfDTO);
          PaginaSEI::getInstance()->setStrMensagem('Estado "'.$objUfDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_uf='.$objUfDTO->getNumIdUf().'#ID-'.$objUfDTO->getNumIdUf()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'uf_alterar':
      $strTitulo = 'Alterar Estado';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUf" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_uf'])){
        $objUfDTO->setNumIdUf($_GET['id_uf']);
        $objUfDTO->retTodos();
        $objUfRN = new UfRN();
        $objUfDTO = $objUfRN->consultarRN0400($objUfDTO);
        if ($objUfDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objUfDTO->setNumIdUf($_POST['hdnIdUf']);
        $objUfDTO->setStrSigla($_POST['txtSigla']);
        $objUfDTO->setStrNome($_POST['txtNome']);
        $objUfDTO->setNumCodigoIbge($_POST['txtCodigo']);
        $objUfDTO->setNumIdPais($_POST['selPais']);
      }
      
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objUfDTO->getNumIdUf().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUf'])) {
        try{
          $objUfRN = new UfRN();
          $objUfRN->alterarRN0399($objUfDTO);
          PaginaSEI::getInstance()->setStrMensagem('Estado "'.$objUfDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objUfDTO->getNumIdUf()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'uf_consultar':
      $strTitulo = "Consultar Estado";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_uf'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objUfDTO->setNumIdUf($_GET['id_uf']);
      $objUfDTO->retTodos();
      $objUfRN = new UfRN();
      $objUfDTO = $objUfRN->consultarRN0400($objUfDTO);
      if ($objUfDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  $strItensSelPais = PaisINT::montarSelectNome('null','&nbsp;',$objUfDTO->getNumIdPais());

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
#lblPais {position:absolute;left:0%;top:0%;width:40%;}
#selPais {position:absolute;left:0%;top:6%;width:40%;}

#lblCodigo {position:absolute;left:0%;top:16%;width:15%;}
#txtCodigo {position:absolute;left:0%;top:22%;width:15%;}

#lblSigla {position:absolute;left:0%;top:32%;width:5%;}
#txtSigla {position:absolute;left:0%;top:38%;width:15%;}

#lblNome {position:absolute;left:0%;top:48%;width:50%;}
#txtNome {position:absolute;left:0%;top:54%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  if ('<?=$_GET['acao']?>'=='uf_cadastrar'){
    document.getElementById('selPais').focus();
  } else if ('<?=$_GET['acao']?>'=='uf_consultar'){
    infraDesabilitarCamposAreaDados();
    OnChangePais();
  }
  
  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarCadastroRI0419();
}
function OnChangePais() {
  if ( document.getElementById('selPais').value==<?=PaisINT::buscarIdPaisBrasil()?> ) {
    document.getElementById('txtCodigo').disabled=false;     
  } else {
    document.getElementById('txtCodigo').disabled=true;
    document.getElementById('txtCodigo').value=null;
  }
}
function validarCadastroRI0419() {

  if (!infraSelectSelecionado('selPais')) {
    alert('Selecione um País.');
    document.getElementById('selPais').focus();
    return false;
  }
  
  if ( document.getElementById('selPais').value==<?=PaisINT::buscarIdPaisBrasil()?> ) {
    if ( infraTrim(document.getElementById('txtCodigo').value)=='') {
      alert('Informe o Código do IBGE.');
      document.getElementById('txtCodigo').focus();
      return false;
    }
  } else {
    document.getElementById('txtCodigo').value=null;
  }

  if ( document.getElementById('selPais').value==<?=PaisINT::buscarIdPaisBrasil()?> ) {
    if (infraTrim(document.getElementById('txtSigla').value)=='') {
      alert('Informe a Sigla.');
      document.getElementById('txtSigla').focus();
      return false;
    }
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
<form id="frmUfCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblPais" for="selPais" accesskey="P" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">P</span>aís:</label>
  <select id="selPais" name="selPais" class="infraSelect" onchange="OnChangePais();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelPais?>
  </select>
  
  <label id="lblCodigo" for="txtCodigo" accesskey="C" class="infraLabelOpcional"><span class="infraTeclaAtalho">C</span>ódigo IBGE:</label>
  <input type="text" id="txtCodigo" name="txtCodigo" class="infraText" onkeypress="return infraMascaraNumero(this, event);" value="<?=PaginaSEI::tratarHTML($objUfDTO->getNumCodigoIbge());?>" maxlength="2" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblSigla" for="txtSigla" accesskey="I" class="infraLabelObrigatorio">S<span class="infraTeclaAtalho">i</span>gla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objUfDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,2);" maxlength="2" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objUfDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdUf" name="hdnIdUf" value="<?=$objUfDTO->getNumIdUf();?>" />
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