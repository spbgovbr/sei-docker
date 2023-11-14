<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/05/2008 - criado por fbv
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
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
 
  SessaoSEI::getInstance()->validarLink();
     
  PaginaSEI::getInstance()->verificarSelecao('localizador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoLocalizador','selTipoSuporte','selLugarLocalizador','selStaEstado'));

  $objLocalizadorDTO = new LocalizadorDTO();

  $strDesabilitar = ''; 
  
  $arrComandos = array();
 
  switch($_GET['acao']){
    case 'localizador_cadastrar':
      $strTitulo = 'Novo Localizador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarLocalizador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objLocalizadorDTO->setNumIdLocalizador(null);
      $objLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $numIdTipoLocalizador = PaginaSEI::getInstance()->recuperarCampo('selTipoLocalizador');
      if ($numIdTipoLocalizador!==''){
        $objLocalizadorDTO->setNumIdTipoLocalizador($numIdTipoLocalizador);
      }else{
        $objLocalizadorDTO->setNumIdTipoLocalizador(null);
      }

      $numIdTipoSuporte = PaginaSEI::getInstance()->recuperarCampo('selTipoSuporte');
      if ($numIdTipoSuporte!==''){
        $objLocalizadorDTO->setNumIdTipoSuporte($numIdTipoSuporte);
      }else{
        $objLocalizadorDTO->setNumIdTipoSuporte(null);
      }

      $numIdLugarLocalizador = PaginaSEI::getInstance()->recuperarCampo('selLugarLocalizador');
      if ($numIdLugarLocalizador!==''){
        $objLocalizadorDTO->setNumIdLugarLocalizador($numIdLugarLocalizador);
      }else{
        $objLocalizadorDTO->setNumIdLugarLocalizador(null);
      }

      $objLocalizadorDTO->setStrComplemento($_POST['txtComplemento']);
      $strStaEstado = PaginaSEI::getInstance()->recuperarCampo('selStaEstado');
      if ($strStaEstado!==''){
        $objLocalizadorDTO->setStrStaEstado($strStaEstado);
      }else{
        $objLocalizadorDTO->setStrStaEstado(null);
      }

      $objLocalizadorDTO->setStrSiglaTipoLocalizador($_POST['txtSeqSiglaTipo']);
      $objLocalizadorDTO->setNumSeqLocalizador($_POST['txtSeqLocalizador']);
      $objLocalizadorDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarLocalizador'])) {
        try{
          $objLocalizadorRN = new LocalizadorRN();
          $objLocalizadorDTO = $objLocalizadorRN->cadastrarRN0617($objLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Localizador "'.$_POST['txtSeqSiglaTipo'].'-'.$_POST['txtSeqLocalizador'].'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_localizador='.$objLocalizadorDTO->getNumIdLocalizador().'#ID-'.$objLocalizadorDTO->getNumIdLocalizador()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'localizador_alterar':
      $strTitulo = 'Alterar Localizador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarLocalizador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_localizador'])){
        $objLocalizadorDTO->setNumIdLocalizador($_GET['id_localizador']);
        $objLocalizadorDTO->retTodos(true);
        $objLocalizadorRN = new LocalizadorRN();
        $objLocalizadorDTO = $objLocalizadorRN->consultarRN0619($objLocalizadorDTO);
        if ($objLocalizadorDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objLocalizadorDTO->setNumIdLocalizador($_POST['hdnIdLocalizador']);
        $objLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objLocalizadorDTO->setNumIdTipoLocalizador($_POST['selTipoLocalizador']);
        $objLocalizadorDTO->setNumIdTipoSuporte($_POST['selTipoSuporte']);
        $objLocalizadorDTO->setNumIdLugarLocalizador($_POST['selLugarLocalizador']);
        $objLocalizadorDTO->setStrComplemento($_POST['txtComplemento']);
        $objLocalizadorDTO->setStrStaEstado($_POST['selStaEstado']);
        $objLocalizadorDTO->setStrSiglaTipoLocalizador($_POST['txtSeqSiglaTipo']);
        $objLocalizadorDTO->setNumSeqLocalizador($_POST['txtSeqLocalizador']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objLocalizadorDTO->getNumIdLocalizador().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarLocalizador'])) {
        try{
          $objLocalizadorRN = new LocalizadorRN();
          $objLocalizadorRN->alterarRN0618($objLocalizadorDTO);
          PaginaSEI::getInstance()->setStrMensagem('Localizador "'.$_POST['txtSeqSiglaTipo'].'-'.$_POST['txtSeqLocalizador'].'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objLocalizadorDTO->getNumIdLocalizador()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'localizador_consultar':
      $strTitulo = "Consultar Localizador";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_localizador'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objLocalizadorDTO->setNumIdLocalizador($_GET['id_localizador']);
      $objLocalizadorDTO->setBolExclusaoLogica(false);
      $objLocalizadorDTO->retTodos(true);
      $objLocalizadorRN = new LocalizadorRN();
      $objLocalizadorDTO = $objLocalizadorRN->consultarRN0619($objLocalizadorDTO);
      if ($objLocalizadorDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelTipoLocalizador = TipoLocalizadorINT::montarSelectNomeRI0676('null','&nbsp;',$objLocalizadorDTO->getNumIdTipoLocalizador());
  $strItensSelTipoSuporte = TipoSuporteINT::montarSelectNomeRI0677('null','&nbsp;',$objLocalizadorDTO->getNumIdTipoSuporte());
  $strItensSelLugarLocalizador = LugarLocalizadorINT::montarSelectNomeRI0678('null','&nbsp;',$objLocalizadorDTO->getNumIdLugarLocalizador());
  $strItensSelStaEstado = LocalizadorINT::montarSelectStaEstadoRI0681('null','&nbsp;',$objLocalizadorDTO->getStrStaEstado());

  $strLinkAjaxLocalizadorRI0683 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=localizador_RI0683');
  
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
#lblTipoLocalizador {position:absolute;left:0%;top:0%;width:50%;}
#selTipoLocalizador {position:absolute;left:0%;top:4%;width:50%;}

#lblSeqLocalizador {position:absolute;left:0%;top:11%;width:5%;}
#txtSeqSiglaTipo   {position:absolute;left:0%;top:15%;width:20%;}
#txtSeqLocalizador {position:absolute;left:22%;top:15%;width:5%;}

#lblComplemento {position:absolute;left:0%;top:22%;width:60%;}
#txtComplemento {position:absolute;left:0%;top:26%;width:60%;}

#lblTipoSuporte {position:absolute;left:0%;top:33%;width:20%;}
#selTipoSuporte {position:absolute;left:0%;top:37%;width:40%;}

#lblLugarLocalizador {position:absolute;left:0%;top:44%;width:20%;}
#selLugarLocalizador {position:absolute;left:0%;top:48%;width:40%;}

#lblStaEstado {position:absolute;left:0%;top:55%;width:20%;}
#selStaEstado {position:absolute;left:0%;top:59%;width:20%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
var objAjaxLocalizadorRI0683 = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='localizador_cadastrar'){
    document.getElementById('selTipoLocalizador').focus();
  } else if ('<?=$_GET['acao']?>'=='localizador_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  objAjaxLocalizadorRI0683 = new infraAjaxComplementar('selTipoLocalizador','<?=$strLinkAjaxLocalizadorRI0683?>');
  objAjaxLocalizadorRI0683.limparCampo = false;
  objAjaxLocalizadorRI0683.prepararExecucao = function(){
    
    return 'idTipoLocalizador='+document.getElementById('selTipoLocalizador').value;
  }
  objAjaxLocalizadorRI0683.processarResultado = function(arr){
  
    document.getElementById('txtSeqSiglaTipo').value = '';
    document.getElementById('txtSeqLocalizador').value = '';
	  if (arr!=null){ 
	     if (arr['SiglaTipoLocalizador']!=undefined){
         document.getElementById('txtSeqSiglaTipo').value = arr['SiglaTipoLocalizador'];
	     }
	     if (arr['SeqLocalizador'] != undefined ){
         document.getElementById('txtSeqLocalizador').value = arr['SeqLocalizador'];
	     }
	  }  
  }
  
  if ('<?=$_GET['acao']?>'=='localizador_cadastrar' && '' == '<?=$_POST['txtSeqLocalizador']?>'){
    objAjaxLocalizadorRI0683.executar();
  }
  

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0675();
}

function validarFormRI0675() {

  if (!infraSelectSelecionado('selTipoLocalizador')) {
    alert('Selecione um Tipo de Localizador.');
    document.getElementById('selTipoLocalizador').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtSeqLocalizador').value)=='') {
    alert('Informe a Sequência da Identificação.');
    document.getElementById('txtSeqLocalizador').focus();
    return false;
  }

  if (!infraSelectSelecionado('selTipoSuporte')) {
    alert('Selecione um Tipo de Suporte.');
    document.getElementById('selTipoSuporte').focus();
    return false;
  }

  if (!infraSelectSelecionado('selLugarLocalizador')) {
    alert('Selecione um Lugar de Localizador.');
    document.getElementById('selLugarLocalizador').focus();
    return false;
  }

  if (!infraSelectSelecionado('selStaEstado')) {
    alert('Selecione um Estado para o Localizador.');
    document.getElementById('selStaEstado').focus();
    return false;
  }

  return true;
  
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmLocalizadorCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('45em');
?>
  <label id="lblTipoLocalizador" for="selTipoLocalizador" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <select id="selTipoLocalizador" name="selTipoLocalizador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelTipoLocalizador?>
  </select>
  
  <label id="lblSeqLocalizador" for="txtSeqLocalizador" accesskey="" class="infraLabelObrigatorio">Identificação:</label>
  <input type="text" id="txtSeqSiglaTipo" name="txtSeqSiglaTipo" class="infraText" value="<?=PaginaSEI::tratarHTML($objLocalizadorDTO->getStrSiglaTipoLocalizador());?>" readonly="true" />
  <input type="text" id="txtSeqLocalizador" name="txtSeqLocalizador" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objLocalizadorDTO->getNumSeqLocalizador());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblComplemento" for="txtComplemento" accesskey="" class="infraLabelOpcional">Complemento:</label>
  <input type="text" id="txtComplemento" name="txtComplemento" class="infraText" value="<?=PaginaSEI::tratarHTML($objLocalizadorDTO->getStrComplemento());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblTipoSuporte" for="selTipoSuporte" accesskey="" class="infraLabelObrigatorio">Suporte:</label>
  <select id="selTipoSuporte" name="selTipoSuporte" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelTipoSuporte?>
  </select>
  
  <label id="lblLugarLocalizador" for="selLugarLocalizador" accesskey="" class="infraLabelObrigatorio">Localização:</label>
  <select id="selLugarLocalizador" name="selLugarLocalizador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelLugarLocalizador?>
  </select>

  <label id="lblStaEstado" for="selStaEstado" accesskey="" class="infraLabelObrigatorio">Estado:</label>
  <select id="selStaEstado" name="selStaEstado" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaEstado?>
  </select>

  <input type="hidden" id="hdnIdLocalizador" name="hdnIdLocalizador" value="<?=$objLocalizadorDTO->getNumIdLocalizador();?>" />
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