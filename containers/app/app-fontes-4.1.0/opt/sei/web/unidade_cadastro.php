<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/04/2008 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
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

  PaginaSEI::getInstance()->verificarSelecao('unidade_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objUnidadeDTO = new UnidadeDTO();

  $strDesabilitar = '';
  $strEmailAcoes = 'false, false, false';
  
  $arrComandos = array();

  switch($_GET['acao']){
    
    case 'unidade_alterar':
            	
      $strTitulo = 'Alterar Unidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUnidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_unidade'])){
        $objUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);
        $objUnidadeDTO->retTodos();
        $objUnidadeDTO->retStrNomeContato();
        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
        if ($objUnidadeDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
        
				$objEmailUnidadeDTO = new EmailUnidadeDTO();
				$objEmailUnidadeDTO->retTodos();
				$objEmailUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
        $objEmailUnidadeDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objEmailUnidadeDTO->setOrdStrEmail(InfraDTO::$TIPO_ORDENACAO_ASC);

				$objEmailUnidadeRN = new EmailUnidadeRN();
								
				$objEmailUnidadeDTO = $objEmailUnidadeRN->listar($objEmailUnidadeDTO);
			
				$arrEnderecosEletronicos = array();
				
				foreach($objEmailUnidadeDTO as $objEmailUnidadeDTOBanco){
					$arrEnderecosEletronicos[] = array($objEmailUnidadeDTOBanco->getNumIdEmailUnidade(),$objEmailUnidadeDTOBanco->getStrEmail(),$objEmailUnidadeDTOBanco->getStrDescricao());
				}
					
				$strEnderecosEletronicos = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrEnderecosEletronicos);
					
	        
      } else {
        $objUnidadeDTO->setNumIdUnidade($_GET['id_unidade_alteracao']);
        $objUnidadeDTO->setNumIdOrgao($_GET['id_orgao']);
        $objUnidadeDTO->setNumIdContato($_GET['id_contato']);
        $objUnidadeDTO->setStrIdOrigem($_GET['id_origem']);
        $objUnidadeDTO->setStrSigla($_POST['txtSiglaContatoAssociado']);
        $objUnidadeDTO->setStrDescricao($_POST['txtNomeContatoAssociado']);
        $objUnidadeDTO->setStrCodigoSei($_POST['txtCodigoSei']);
        $objUnidadeDTO->setStrSinMailPendencia(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinMailPendencia']));
        $objUnidadeDTO->setStrSinArquivamento(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinArquivamento']));
        $objUnidadeDTO->setStrSinOuvidoria(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinOuvidoria']));
        $objUnidadeDTO->setStrSinProtocolo(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProtocolo']));
        $objUnidadeDTO->setStrSinEnvioProcesso(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEnvioProcesso']));
        $objUnidadeDTO->setStrSinAtivo('S');
        
        $arr = array_reverse(PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnEnderecosEletronicos']));
    	
	    	$arrEnderecosEletronicos = array();

        $numSequencia = 0;
	    	foreach($arr as $linha){
	    		$objEmailUnidadeDTO = new EmailUnidadeDTO();
	    		$objEmailUnidadeDTO->setNumIdEmailUnidade(null);
	    		$objEmailUnidadeDTO->setStrEmail($linha[1]);
	    		$objEmailUnidadeDTO->setStrDescricao($linha[2]);
          $objEmailUnidadeDTO->setNumSequencia($numSequencia++);
	    		$arrEnderecosEletronicos[] = $objEmailUnidadeDTO;
	    	}
	    	
	    	$objUnidadeDTO->setArrObjEmailUnidadeDTO($arrEnderecosEletronicos);
	    	
	    	$strEnderecosEletronicos = $_POST['hdnEnderecosEletronicos'];
                
      }
      
      $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objUnidadeDTO->getNumIdUnidade().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUnidade'])) {
        try{
          $objUnidadeRN = new UnidadeRN();
          $objUnidadeRN->alterarRN0132($objUnidadeDTO);
          //PaginaSEI::getInstance()->setStrMensagem('Unidade "'.$objUnidadeDTO->getNumIdUnidade().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objUnidadeDTO->getNumIdUnidade()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'unidade_consultar':
      $strTitulo = "Consultar Unidade";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_unidade'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);
      $objUnidadeDTO->retTodos();
      $objUnidadeDTO->retStrNomeContato();
      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
      
      $objEmailUnidadeDTO = new EmailUnidadeDTO();
			$objEmailUnidadeDTO->retTodos();
			$objEmailUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
      $objEmailUnidadeDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objEmailUnidadeDTO->setOrdStrEmail(InfraDTO::$TIPO_ORDENACAO_ASC);

			$objEmailUnidadeRN = new EmailUnidadeRN();
			$objEmailUnidadeDTO = $objEmailUnidadeRN->listar($objEmailUnidadeDTO);
		
			$arrEnderecosEletronicos = array();
			foreach($objEmailUnidadeDTO as $objEmailUnidadeDTOBanco){
				$arrEnderecosEletronicos[] = array($objEmailUnidadeDTOBanco->getNumIdEmailUnidade(),$objEmailUnidadeDTOBanco->getStrEmail(),$objEmailUnidadeDTOBanco->getStrDescricao());
			}
			$strEnderecosEletronicos = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrEnderecosEletronicos);
      
      if ($objUnidadeDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  if($_GET['acao'] != 'unidade_consultar'){
  	$strEmailAcoes = 'true, true, true';
  }else{
    $strDisplayCadastroEmail = 'display:none;';
  }

  $arrObjSinalizacaoDTO = InfraArray::indexarArrInfraDTO(UnidadeRN::listarValoresSinalizacao(),'StaSinalizacao');

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
#divCadastroEnderecosEletronicos {height:5em;<?=$strDisplayCadastroEmail?>}
#lblEmail {position:absolute;left:0%;top:0%;width:30%;}
#txtEmail {position:absolute;left:0%;top:40%;width:30%;}
 
#lblDescricaoEmail {position:absolute;left:32%;top:0%;width:47%;}
#txtDescricaoEmail {position:absolute;left:32%;top:40%;width:47%;}
 
#sbmGravarEmail {position:absolute;left:82%;top:35%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objTabelaenderecosEletronicos = null;

function inicializar(){

  if ('<?=$_GET['acao']?>'=='unidade_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  objTabelaEnderecosEletronicos = new infraTabelaDinamica('tblEnderecosEletronicos','hdnEnderecosEletronicos', <?=$strEmailAcoes?>);
  objTabelaEnderecosEletronicos.inserirNoInicio = false;
  objTabelaEnderecosEletronicos.alterar = function(arr){
    document.getElementById('hdnIdEmail').value = arr[0];
    document.getElementById('txtEmail').value = arr[1];
    document.getElementById('txtDescricaoEmail').value = arr[2];
  };
  objTabelaEnderecosEletronicos.gerarEfeitoTabela=true;

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarFormRI0544();
}

function validarFormRI0544() {
  return true;
}

function transportarEmail(){

  //VALIDAÇÕES
		
	if (infraTrim(document.getElementById('txtEmail').value)=='') {
		alert('E-mail não informado.');
		document.getElementById('txtEmail').focus();
		return false;
	}
	
	if (infraTrim(document.getElementById('txtDescricaoEmail').value)=='') {
		alert('Descricao de E-mail não informado.');
		document.getElementById('txtDescricaoEmail').focus();
		return false;
	}
		
	if (!infraValidarEmail(infraTrim(document.getElementById('txtEmail').value))){
	
		alert('E-mail Inválido.');
		document.getElementById('txtEmail').focus();
		return false;
	
	}
	

  var id = ((document.getElementById('hdnIdEmail').value!='') ? document.getElementById('hdnIdEmail').value : 'NOVO' + (new Date()).getTime());
	var email = document.getElementById('txtEmail').value;
	var descricaoEmail = document.getElementById('txtDescricaoEmail').value;
	
  objTabelaEnderecosEletronicos.adicionar([id, email, descricaoEmail]);
  
  //depois de incluir limpa os input
  document.getElementById('txtEmail').value = '';
	document.getElementById('txtDescricaoEmail').value = '';
	document.getElementById('hdnIdEmail').value = '';
	document.getElementById('txtEmail').focus();
}

function transportarEmailEnter(event){
    if (event.keyCode==13){
      transportarEmail();
      return false;
    }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUnidadeCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_unidade_alteracao='.$objUnidadeDTO->getNumIdUnidade().'&id_orgao='.$objUnidadeDTO->getNumIdOrgao().'&id_contato='.$objUnidadeDTO->getNumIdContato().'&id_origem='.$objUnidadeDTO->getStrIdOrigem())?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
ContatoINT::montarContatoAssociado(true, $objUnidadeDTO->getNumIdUnidade(), true, $objUnidadeDTO->getStrCodigoSei(), true, $objUnidadeDTO->getStrIdOrigem(), false, $objUnidadeDTO->getNumIdContato(), $objUnidadeDTO->getStrSigla(), $objUnidadeDTO->getStrDescricao(), null, true,'frmUnidadeCadastro');
?>
<div id="divCadastroEnderecosEletronicos" class="infraAreaDados">
  
  <label id="lblEmail" for="txtEmail" accesskey="" class="infraLabelOpcional">E-mail:</label>
  <input type="text" id="txtEmail" name="txtEmail" class="infraText" value="" onkeypress="infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricaoEmail" for="txtDescricaoEmail" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricaoEmail" name="txtDescricaoEmail" class="infraText" value="" onkeypress="return transportarEmailEnter(event);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
	<input type="button" id="sbmGravarEmail" name="sbmGravarEmail"  class="infraButton" value="Adicionar E-mail" onclick="transportarEmail();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
</div>	
 
<div id="divTabelaEnderecosEletronicos" class="infraAreaTabela">

	<table  id="tblEnderecosEletronicos" name="tblEnderecosEletronicos" width="97%" class="infraTable">
		<tr>
		  <!--  <th style="display:none;">ID</th> -->
		  <th style="display:none;">ID</th>
			<th class="infraTh" width="33%">E-mail</th>
			<th class="infraTh"  width="50%" align="left">Descrição</th>
			<th class="infraTh">Ações</th>
		</tr>
  </table>

  <input type="hidden" id="hdnIdEmail" name="hdnIdEmail" value=""/>
  <input type="hidden" id="hdnEnderecosEletronicos" name="hdnEnderecosEletronicos" value="<?=$strEnderecosEletronicos;?>"/>
  
</div>
  <br />
  <br />

  <div id="divSinEnvioProcesso" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinEnvioProcesso" name="chkSinEnvioProcesso" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objUnidadeDTO->getStrSinEnvioProcesso())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinEnvioProcesso" for="chkSinEnvioProcesso" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[UnidadeRN::$TS_ENVIO_PROCESSOS]->getStrDescricao())?></label>
  </div>

  <div id="divSinMailPendencia" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinMailPendencia" name="chkSinMailPendencia" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objUnidadeDTO->getStrSinMailPendencia())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinMailPendencia" for="chkSinMailPendencia" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[UnidadeRN::$TS_ENVIAR_EMAIL]->getStrDescricao())?></label>
  </div>

  <div id="divSinArquivamento" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinArquivamento" name="chkSinArquivamento" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objUnidadeDTO->getStrSinArquivamento())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinArquivamento" for="chkSinArquivamento" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[UnidadeRN::$TS_ARQUIVAMENTO]->getStrDescricao())?></label>
  </div>

  <div id="divSinOuvidoria" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinOuvidoria" name="chkSinOuvidoria" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objUnidadeDTO->getStrSinOuvidoria())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinOuvidoria" for="chkSinOuvidoria" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[UnidadeRN::$TS_OUVIDORIA]->getStrDescricao())?></label>
  </div>

  <div id="divSinProtocolo" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinProtocolo" name="chkSinProtocolo" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objUnidadeDTO->getStrSinProtocolo())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinProtocolo" for="chkSinProtocolo" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[UnidadeRN::$TS_PROTOCOLO]->getStrDescricao())?></label>
  </div>

  <br>
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>