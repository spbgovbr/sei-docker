<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->verificarSelecao('anotacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  foreach($_GET as $strChave => $strValor){
    if (substr($strChave,0,3)=='id_'){
      $strParametros .= '&'.$strChave.'='.$strValor;
    }
  }

  $strDesabilitar = '';
  $bolExiste = false;
  $arrComandos = array();

  switch($_GET['acao']){
    case 'anotacao_registrar':
      $strTitulo = 'Anotações';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmRegistrarAnotacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAnotacaoRN = new AnotacaoRN();

      $objAnotacaoDTO = new AnotacaoDTO();

      if (isset($_GET['id_protocolo'])) {
        $arrIdProtocolo = array($_GET['id_protocolo']);
      }else if (isset($_GET['id_procedimento'])) {
        $arrIdProtocolo = array($_GET['id_procedimento']);
      }else if ($_GET['acao_origem']=='procedimento_controlar'){
        $arrItensControleProcesso = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
        $arrIdProtocolo = $arrItensControleProcesso;
      }else{
        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      if ($_GET['id_acompanhamento']!='') {
        $strAncora = $_GET['id_acompanhamento'];
      }else if ($_GET['id_item_sessao_julgamento']!='') {
        $strAncora = $_GET['id_item_sessao_julgamento'];
      }else if ($_GET['id_protocolo']!=''){
        $strAncora = $_GET['id_protocolo'];
      }else{
        $strAncora = $arrIdProtocolo;
      }

      if (!PaginaSEI::getInstance()->isBolArvore()) {
        $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'].$strParametros) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
      }

      $objAnotacaoDTO->setDthAnotacao($_POST['txtDthAnotacao']);
      $objAnotacaoDTO->setStrSiglaUsuario($_POST['txtSiglaUsuario']);
      $objAnotacaoDTO->setStrNomeUsuario($_POST['txtNomeUsuario']);
      $objAnotacaoDTO->setStrDescricao($_POST['txaDescricao']);
      $objAnotacaoDTO->setStrSinPrioridade(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPrioridade']));
      $objAnotacaoDTO->setDblIdProtocolo($arrIdProtocolo);
      
      if (!isset($_POST['txaDescricao'])){
      	
      	$objProtocoloDTO = new ProtocoloDTO();
      	$objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrProtocoloFormatado();
      	$objProtocoloDTO->retStrStaNivelAcessoGlobal();
      	$objProtocoloDTO->setDblIdProtocolo($objAnotacaoDTO->getDblIdProtocolo(),InfraDTO::$OPER_IN);

      	$objProtocoloRN = new ProtocoloRN();
      	$arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO),'IdProtocolo');

      	$arr = array();
      	foreach($arrObjProtocoloDTO as $objProtocoloDTO){
      		
	        $dto = new AnotacaoDTO();
          $dto->retDthAnotacao();
          $dto->retStrSiglaUsuario();
          $dto->retStrNomeUsuario();
          $dto->retNumIdAnotacao();
          $dto->retDthAnotacao();
	        $dto->retStrDescricao();
	        $dto->retStrSinPrioridade();
	        $dto->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
	        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
	        
	        if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
	        	$dto->setStrStaAnotacao(AnotacaoRN::$TA_INDIVIDUAL);
	        	$dto->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
	        }else{
	        	$dto->setStrStaAnotacao(AnotacaoRN::$TA_UNIDADE);
	        }
	        $dto->setNumMaxRegistrosRetorno(1);
          $dto->setOrdDthAnotacao(InfraDTO::$TIPO_ORDENACAO_DESC);
	        
	        $dto = $objAnotacaoRN->consultar($dto);
	        
	        if ($dto != null){
	          $arr[] = $dto;	
	        }
      	}

      	if (count($arr)){
          $bolExiste = true;
        }

        if (count($arrObjProtocoloDTO)==1){
          $strTitulo .= ' '.$arrObjProtocoloDTO[$arrIdProtocolo[0]]->getStrProtocoloFormatado();
        }
      	
      	$arr = array_unique($arr);
      	
        if (InfraArray::contar($arr)==1){
          $objAnotacaoDTO->setDthAnotacao($arr[0]->getDthAnotacao());
          $objAnotacaoDTO->setStrSiglaUsuario($arr[0]->getStrSiglaUsuario());
          $objAnotacaoDTO->setStrNomeUsuario($arr[0]->getStrNomeUsuario());
          $objAnotacaoDTO->setStrDescricao($arr[0]->getStrDescricao());
          $objAnotacaoDTO->setStrSinPrioridade($arr[0]->getStrSinPrioridade());
        }
      }
      

      //die($objAnotacaoDTO->__toString());
      
      if (isset($_POST['sbmRegistrarAnotacao'])) {
        try{
          
          $objAnotacaoRN->registrar($objAnotacaoDTO);
          //PaginaSEI::getInstance()->setStrMensagem('Anotação registrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($strAncora)));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
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
#divUsuario {max-width:1200px;<?=(!$bolExiste || count($arrIdProtocolo)>1)?'display:none;':''?>}
#lblSiglaUsuario {position:absolute;left:0%;top:0%;width:20%;}
#txtSiglaUsuario {position:absolute;left:0%;top:40%;width:20%;}

#lblNomeUsuario {position:absolute;left:21%;top:0%;width:48%;}
#txtNomeUsuario {position:absolute;left:21%;top:40%;width:48%;}

#lblDthAnotacao {position:absolute;left:70%;top:0%;width:20%;}
#txtDthAnotacao {position:absolute;left:70%;top:40%;width:20%;}

#lblDescricao {position:absolute;left:0%;top:0%;}
#txaDescricao {position:absolute;left:0%;top:13%;width:90%;}

#divSinPrioridade {position:absolute;left:0%;top:85%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  document.getElementById('txaDescricao').focus();
}

function OnSubmitForm() {

  if (infraTrim(document.getElementById('txaDescricao').value)=='' && document.getElementById('chkSinPrioridade').checked){
    alert('Descrição não informada.');
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
<form id="frmAnotacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>
  <div id="divUsuario" class="infraAreaDados" style="height:5em;">
    <label id="lblSiglaUsuario" accesskey="" class="infraLabelOpcional">Sigla:</label>
    <input type="text" id="txtSiglaUsuario" name="txtSiglaUsuario" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objAnotacaoDTO->getStrSiglaUsuario())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblNomeUsuario" accesskey="" class="infraLabelOpcional">Nome:</label>
    <input type="text" id="txtNomeUsuario" name="txtNomeUsuario" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objAnotacaoDTO->getStrNomeUsuario())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDthAnotacao" accesskey="" class="infraLabelOpcional">Data/Hora:</label>
    <input type="text" id="txtDthAnotacao" name="txtDthAnotacao" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML(substr($objAnotacaoDTO->getDthAnotacao(),0,16))?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  </div>

  <div id="divDescricao" class="infraAreaDados" style="height:15em;">
    <label id="lblDescricao" for="txaDescricao" class="infraLabelOpcional">Descrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="5" onkeypress="return infraLimitarTexto(this,event,500);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAnotacaoDTO->getStrDescricao());?></textarea>

    <div id="divSinPrioridade" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinPrioridade" name="chkSinPrioridade" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAnotacaoDTO->getStrSinPrioridade())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <label id="lblSinPrioridade" for="chkSinPrioridade" accesskey="" class="infraLabelCheckbox">Prioridade</label>
    </div>
  </div>

  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=implode(',',$arrIdProtocolo);?>" />

  
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>