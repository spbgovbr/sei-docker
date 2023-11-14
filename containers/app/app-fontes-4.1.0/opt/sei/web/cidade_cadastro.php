<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
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

  PaginaSEI::getInstance()->verificarSelecao('cidade_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUf','selPais'));

  $objCidadeDTO = new CidadeDTO();

  $strDesabilitar = '';

  $arrComandos = array();



  switch($_GET['acao']){
    case 'cidade_cadastrar':
      $strTitulo = 'Nova Cidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objCidadeDTO->setNumIdCidade(null);

      $numIdPais = PaginaSEI::getInstance()->recuperarCampo('selPais');
      if ($numIdPais!==''){
        $objCidadeDTO->setNumIdPais($numIdPais);
      }else{
        $objCidadeDTO->setNumIdPais(null);
      }

      $numIdUf = PaginaSEI::getInstance()->recuperarCampo('selUf');
      if ($numIdUf!==''){
        $objCidadeDTO->setNumIdUf($numIdUf);
      }else{
        $objCidadeDTO->setNumIdUf(null);
      }

      $objCidadeDTO->setNumCodigoIbge($_POST['txtCodigo']);
      $objCidadeDTO->setStrNome($_POST['txtNome']);
      $objCidadeDTO->setStrSinCapital(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCapital']));
      $objCidadeDTO->setDblLatitude($_POST['txtLatitude']);
      $objCidadeDTO->setDblLongitude($_POST['txtLongitude']);

      if (isset($_POST['sbmCadastrarCidade'])) {
        try{
          $objCidadeRN = new CidadeRN();
          $objCidadeDTO = $objCidadeRN->cadastrarRN0407($objCidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Cidade "'.$objCidadeDTO->getStrNome().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_cidade='.$objCidadeDTO->getNumIdCidade().'#ID-'.$objCidadeDTO->getNumIdCidade()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cidade_alterar':
      $strTitulo = 'Alterar Cidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';
      
      if (isset($_GET['id_cidade'])){
        $objCidadeDTO->setNumIdCidade($_GET['id_cidade']);
        $objCidadeDTO->retTodos();
        $objCidadeRN = new CidadeRN();
        $objCidadeDTO = $objCidadeRN->consultarRN0409($objCidadeDTO);
        if ($objCidadeDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objCidadeDTO->setNumIdCidade($_POST['hdnIdCidade']);
        $objCidadeDTO->setNumIdUf($_POST['selUf']);
        $objCidadeDTO->setNumIdPais($_POST['selPais']);
        $objCidadeDTO->setNumCodigoIbge($_POST['txtCodigo']);
        $objCidadeDTO->setStrNome($_POST['txtNome']);
        $objCidadeDTO->setStrSinCapital(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCapital']));
        $objCidadeDTO->setDblLatitude($_POST['txtLatitude']);
        $objCidadeDTO->setDblLongitude($_POST['txtLongitude']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objCidadeDTO->getNumIdCidade().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCidade'])) {
        try{
          $objCidadeRN = new CidadeRN();
          $objCidadeRN->alterarRN0408($objCidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Cidade "'.$objCidadeDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objCidadeDTO->getNumIdCidade()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cidade_consultar':
      $strTitulo = "Consultar Cidade";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_cidade'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objCidadeDTO->setNumIdCidade($_GET['id_cidade']);
      $objCidadeDTO->retTodos();
      $objCidadeRN = new CidadeRN();
      $objCidadeDTO = $objCidadeRN->consultarRN0409($objCidadeDTO);
      if ($objCidadeDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  $strItensSelPais = PaisINT::montarSelectNome('null','&nbsp;',$objCidadeDTO->getNumIdPais());
  $strLinkAjaxUf = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=uf_montar_select_sigla_nome');
  $strItensSelUf = UfINT::montarSelectSiglaNome('null','&nbsp;',$objCidadeDTO->getNumIdUf(),$objCidadeDTO->getNumIdPais());

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

#lblUf {position:absolute;left:0%;top:16%;width:40%;}
#selUf {position:absolute;left:0%;top:22%;width:40%;}

#lblCodigo {position:absolute;left:0%;top:32%;width:20%;}
#txtCodigo {position:absolute;left:0%;top:38%;width:10%;}

#lblNome {position:absolute;left:0%;top:48%;width:50%;}
#txtNome {position:absolute;left:0%;top:54%;width:40%;}

#divSinCapital {position:absolute;left:0%;top:64%;}

#lblLatitude {position:absolute;left:0%;top:73%;width:19%;}
#txtLatitude {position:absolute;left:0%;top:79%;width:19%;}

#lblLongitude {position:absolute;left:21%;top:73%;width:19%;}
#txtLongitude {position:absolute;left:21%;top:79%;width:19%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  
  if ('<?=$_GET['acao']?>'=='cidade_cadastrar'){
    document.getElementById('selPais').focus();
  } else if ('<?=$_GET['acao']?>'=='cidade_consultar'){
    infraDesabilitarCamposAreaDados();
  }
  
  if (document.getElementById('selPais').value==<?=PaisINT::buscarIdPaisBrasil()?> ) {
    document.getElementById('txtCodigo').disabled=false;
  } else {
    document.getElementById('txtCodigo').disabled=true;
    document.getElementById('txtCodigo').value=null;
  }
    //Ajax para carregar as cidades na escolha do estado
  objAjaxIdCidade = new infraAjaxMontarSelectDependente('selPais','selUf','<?=$strLinkAjaxUf?>');
  objAjaxIdCidade.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','null') + '&idPais='+document.getElementById('selPais').value;
  }
  objAjaxIdCidade.processarResultado = function(){
    if ( document.getElementById('selPais').value==<?=PaisINT::buscarIdPaisBrasil()?> ) {
      document.getElementById('txtCodigo').disabled=false;
    } else {
      document.getElementById('txtCodigo').disabled=true;
      document.getElementById('txtCodigo').value=null;
    }
  }

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarCadastroRI0420();
}
function validarCadastroRI0420() {
  if (!infraSelectSelecionado('selPais')) {
    alert('Selecione um País.');
    document.getElementById('selPais').focus();
    return false;
  } else {
    if (document.getElementById('selPais').value==<?=PaisINT::buscarIdPaisBrasil()?>) {
      if (!infraSelectSelecionado('selUf')) {
        alert('Selecione um Estado.');
        document.getElementById('selUf').focus();
        return false;
      }

      if (infraTrim(document.getElementById('txtCodigo').value)=='') {
        alert('Informe o Código do IBGE.');
        document.getElementById('txtCodigo').focus();
        return false;
      }
    } else {
      document.getElementById('txtCodigo').value=null;
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
<form id="frmCidadeCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblPais" for="selPais" accesskey="P" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">P</span>aís:</label>
  <select id="selPais" name="selPais" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelPais?>
  </select>

  <label id="lblUf" for="selUf" class="infraLabelOpcional">Estado:</label>
  <select id="selUf" name="selUf" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelUf?>
  </select>

  <label id="lblCodigo" for="txtCodigo" accesskey="I" class="infraLabelOpcional">Código <span class="infraTeclaAtalho">I</span>BGE:</label>
  <input type="text" id="txtCodigo" name="txtCodigo" class="infraText" value="<?=PaginaSEI::tratarHTML($objCidadeDTO->getNumCodigoIbge());?>" onkeypress="return infraMascaraNumero(this,event);" maxlength="7" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objCidadeDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <div id="divSinCapital" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinCapital" name="chkSinCapital" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objCidadeDTO->getStrSinCapital())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinCapital" for="chkSinCapital" accesskey="" class="infraLabelCheckbox">Capital</label>
  </div>
  <label id="lblLatitude" for="txtLatitude" accesskey="l" class="infraLabelOpcional"><span class="infraTeclaAtalho">L</span>atitude:</label>
  <input type="text" id="txtLatitude" name="txtLatitude" onkeypress="return infraMascaraDecimais(this, '', ',', event, 6, 8, true)" class="infraText" value="<?=PaginaSEI::tratarHTML($objCidadeDTO->getDblLatitude());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblLongitude" for="txtLongitude" accesskey="o" class="infraLabelOpcional">L<span class="infraTeclaAtalho">o</span>ngitude:</label>
  <input type="text" id="txtLongitude" name="txtLongitude" onkeypress="return infraMascaraDecimais(this, '', ',', event, 6, 8, true)" class="infraText" value="<?=PaginaSEI::tratarHTML($objCidadeDTO->getDblLongitude());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdCidade" name="hdnIdCidade" value="<?=$objCidadeDTO->getNumIdCidade();?>" />
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