<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('tipo_procedimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_procedimento_cadastrar':
      $strTitulo = 'Novo Tipo de Processo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoProcedimento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$_GET['id_tipo_procedimento']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
			

		  $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);

		  $arrObjRelTipoProcedimentoAssuntoDTO = array();
			for($x = 0;$x<count($arrOpcoes);$x++){
			  $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
			  $objRelTipoProcedimentoAssuntoDTO->setNumIdAssunto($arrOpcoes[$x]);
			  $objRelTipoProcedimentoAssuntoDTO->setNumSequencia($x);
			  $arrObjRelTipoProcedimentoAssuntoDTO[] = $objRelTipoProcedimentoAssuntoDTO;
			}
			$objTipoProcedimentoDTO->setArrObjRelTipoProcedimentoAssuntoDTO($arrObjRelTipoProcedimentoAssuntoDTO);

      $objTipoProcedimentoDTO->setArrObjTipoProcedRestricaoDTO(OrgaoINT::processarRestricaoOrgaoUnidade('TipoProcedRestricaoDTO'));
			      
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento(null);
      $objTipoProcedimentoDTO->setStrNome($_POST['txtNome']);
      $objTipoProcedimentoDTO->setStrDescricao($_POST['txaDescricao']);
      $objTipoProcedimentoDTO->setStrStaGrauSigiloSugestao($_POST['selGrauSigilo']);
      $objTipoProcedimentoDTO->setNumIdHipoteseLegalSugestao($_POST['selHipoteseLegal']);
      $objTipoProcedimentoDTO->setStrSinInterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInterno']));
      $objTipoProcedimentoDTO->setStrSinOuvidoria(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinOuvidoria']));
      $objTipoProcedimentoDTO->setStrSinIndividual(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinIndividual']));

      $arrObjNivelAcessoPermitidoDTO = array();

      $strSinSigilosoPermitido = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinSigilosoPermitido']);
      if ($strSinSigilosoPermitido=='S'){
      	$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
      	$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_SIGILOSO);
      	$arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO; 
      }
      
      $strSinRestritoPermitido = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRestritoPermitido']);
      if ($strSinRestritoPermitido=='S'){
      	$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
      	$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_RESTRITO);
      	$arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO; 
      }
      
      $strSinPublicoPermitido = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPublicoPermitido']);
      if ($strSinPublicoPermitido=='S'){
      	$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
      	$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_PUBLICO);
      	$arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO; 
      }
      
      $objTipoProcedimentoDTO->setArrObjNivelAcessoPermitidoDTO($arrObjNivelAcessoPermitidoDTO);
      
      $objTipoProcedimentoDTO->setStrStaNivelAcessoSugestao($_POST['rdoNivelAcessoSugestao']);
      $objTipoProcedimentoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTipoProcedimento'])) {
        try{
          $objTipoProcedimentoRN = new TipoProcedimentoRN();
          $objTipoProcedimentoDTO = $objTipoProcedimentoRN->cadastrarRN0265($objTipoProcedimentoDTO); 
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Processo cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_procedimento='.$objTipoProcedimentoDTO->getNumIdTipoProcedimento().'#ID-'.$objTipoProcedimentoDTO->getNumIdTipoProcedimento()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_procedimento_alterar':
      $strTitulo = 'Alterar Tipo de Processo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoProcedimento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_procedimento'])){
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
        $objTipoProcedimentoDTO->retTodos();
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);
        if ($objTipoProcedimentoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
        
        $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
        $objNivelAcessoPermitidoDTO->retStrStaNivelAcesso();
        $objNivelAcessoPermitidoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
        
        $objNivelAcessoPermitidoRN = new NivelAcessoPermitidoRN();
        $arrObjNivelAcessoPermitidoDTO = $objNivelAcessoPermitidoRN->listar($objNivelAcessoPermitidoDTO);

        $strSinSigilosoPermitido = 'N';
        $strSinRestritoPermitido = 'N';
        $strSinPublicoPermitido = 'N';
        foreach($arrObjNivelAcessoPermitidoDTO as $objNivelAcessoPermitidoDTO){
        	if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_SIGILOSO){
        		$strSinSigilosoPermitido = 'S';
        	}else if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_RESTRITO){
            $strSinRestritoPermitido = 'S';		
        	}else if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_PUBLICO){
            $strSinPublicoPermitido = 'S';		
        	}
        }
        
      } else {
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_POST['hdnIdTipoProcedimento']);
        $objTipoProcedimentoDTO->setStrNome($_POST['txtNome']);
        $objTipoProcedimentoDTO->setStrDescricao($_POST['txaDescricao']);
        $objTipoProcedimentoDTO->setStrStaGrauSigiloSugestao($_POST['selGrauSigilo']);
        $objTipoProcedimentoDTO->setNumIdHipoteseLegalSugestao($_POST['selHipoteseLegal']);
        $objTipoProcedimentoDTO->setStrStaNivelAcessoSugestao($_POST['rdoNivelAcessoSugestao']);
        $objTipoProcedimentoDTO->setStrSinAtivo('S');
        $objTipoProcedimentoDTO->setStrSinInterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInterno']));
        $objTipoProcedimentoDTO->setStrSinOuvidoria(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinOuvidoria']));
        $objTipoProcedimentoDTO->setStrSinIndividual(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinIndividual']));
	      $arrObjNivelAcessoPermitidoDTO = array();
	
	      $strSinSigilosoPermitido = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinSigilosoPermitido']);
	      if ($strSinSigilosoPermitido=='S'){
	      	$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
	      	$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_SIGILOSO);
	      	$arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO; 
	      }
	      
	      $strSinRestritoPermitido = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRestritoPermitido']);
	      if ($strSinRestritoPermitido=='S'){
	      	$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
	      	$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_RESTRITO);
	      	$arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO; 
	      }
	      
	      $strSinPublicoPermitido = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPublicoPermitido']);
	      if ($strSinPublicoPermitido=='S'){
	      	$objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
	      	$objNivelAcessoPermitidoDTO->setStrStaNivelAcesso(ProtocoloRN::$NA_PUBLICO);
	      	$arrObjNivelAcessoPermitidoDTO[] = $objNivelAcessoPermitidoDTO; 
	      }
	      
	      $objTipoProcedimentoDTO->setArrObjNivelAcessoPermitidoDTO($arrObjNivelAcessoPermitidoDTO);

			  $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);
			  
			  //print_r($arrOpcoes);die;
	
			  $arrObjRelTipoProcedimentoAssuntoDTO = array();
				for($x = 0;$x<count($arrOpcoes);$x++){
				  $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
				  $objRelTipoProcedimentoAssuntoDTO->setNumIdAssunto($arrOpcoes[$x]);
				  $objRelTipoProcedimentoAssuntoDTO->setNumSequencia($x);
				  $arrObjRelTipoProcedimentoAssuntoDTO[] = $objRelTipoProcedimentoAssuntoDTO;
				}
				$objTipoProcedimentoDTO->setArrObjRelTipoProcedimentoAssuntoDTO($arrObjRelTipoProcedimentoAssuntoDTO);

        $objTipoProcedimentoDTO->setArrObjTipoProcedRestricaoDTO(OrgaoINT::processarRestricaoOrgaoUnidade('TipoProcedRestricaoDTO'));
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objTipoProcedimentoDTO->getNumIdTipoProcedimento().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoProcedimento'])) {
        try{
          $objTipoProcedimentoRN = new TipoProcedimentoRN();
          $objTipoProcedimentoRN->alterarRN0266($objTipoProcedimentoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Processo alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objTipoProcedimentoDTO->getNumIdTipoProcedimento()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_procedimento_consultar':
      $strTitulo = "Consultar Tipo de Processo";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_tipo_procedimento'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
      $objTipoProcedimentoDTO->retTodos();
      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);
      if ($objTipoProcedimentoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      
      $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
      $objNivelAcessoPermitidoDTO->retStrStaNivelAcesso();
      $objNivelAcessoPermitidoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());

      $objNivelAcessoPermitidoRN = new NivelAcessoPermitidoRN();
      $arrObjNivelAcessoPermitidoDTO = $objNivelAcessoPermitidoRN->listar($objNivelAcessoPermitidoDTO);

      $strSinSigilosoPermitido = 'N';
      $strSinRestritoPermitido = 'N';
      $strSinPublicoPermitido = 'N';
      foreach($arrObjNivelAcessoPermitidoDTO as $objNivelAcessoPermitidoDTO){
      	if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_SIGILOSO){
      		$strSinSigilosoPermitido = 'S';
      	}else if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_RESTRITO){
      		$strSinRestritoPermitido = 'S';
      	}else if ($objNivelAcessoPermitidoDTO->getStrStaNivelAcesso()==ProtocoloRN::$NA_PUBLICO){
      		$strSinPublicoPermitido = 'S';
      	}
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_selecionar&tipo_selecao=2&id_object=objLupaAssuntos');
  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');
  $strItensSelAssuntos = RelTipoProcedimentoAssuntoINT::conjuntoPorCodigoRI0556(null,null,null,$objTipoProcedimentoDTO->getNumIdTipoProcedimento());
  $strItensSelGrauSigilo = ProtocoloINT::montarSelectGrauSigilo('null','&nbsp;', $objTipoProcedimentoDTO->getStrStaGrauSigiloSugestao());
  $strItensSelHipoteseLegal = HipoteseLegalINT::montarSelectNomeBaseLegal('null','&nbsp;',$objTipoProcedimentoDTO->getNumIdHipoteseLegalSugestao(),$objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao());
  $strLinkAjaxHipoteseLegal = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=hipotese_legal_select_nome_base_legal');
  
  
  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  
  $numHabilitarHipoteseLegal = $objInfraParametro->getValor('SEI_HABILITAR_HIPOTESE_LEGAL');
  $strDisplayHipoteseLegal = 'display:none;';
  if ($numHabilitarHipoteseLegal){
    $strDisplayHipoteseLegal = '';
  }

  $numHabilitarGrauSigilo = $objInfraParametro->getValor('SEI_HABILITAR_GRAU_SIGILO');
  $strDisplayGrauSigilo = 'display:none;';
  if ($numHabilitarGrauSigilo && $objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao()==ProtocoloRN::$NA_SIGILOSO){
    $strDisplayGrauSigilo = '';
  }

  OrgaoINT::montarRestricaoOrgaoUnidade($objTipoProcedimentoDTO->getNumIdTipoProcedimento(), null, $strCssRestricao, $strHtmlRestricao, $strJsGlobalRestricao, $strJsInicializarRestricao);

  $arrObjSinalizacaoDTO = InfraArray::indexarArrInfraDTO(TipoProcedimentoRN::listarValoresSinalizacao(),'StaSinalizacao');

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

#lblNome {position:absolute;left:0%;top:0%;}
#txtNome {position:absolute;left:0%;top:40%;width:79.5%;}

#lblDescricao {position:absolute;left:0%;top:0%;}
#txaDescricao {position:absolute;left:0%;top:30%;width:79.5%;}

#lblAssuntos {position:absolute;left:0%;top:5%;}
#txtAssunto {position:absolute;left:0%;top:20%;width:79.5%;}
#selAssuntos {position:absolute;left:0%;top:40%;width:80%;}
#divOpcoesAssuntos {position:absolute;left:81%;top:40%;}

#fldNivelAcessoPermitido {position:absolute;left:0%;top:5%;height:75%;width:30%;}
#divSinSigilosoPermitido {position:absolute;left:35%;top:30%;}
#divSinRestritoPermitido {position:absolute;left:35%;top:50%;}
#divSinPublicoPermitido {position:absolute;left:35%;top:70%;}

#fldNivelAcessoSugestao {position:absolute;left:34%;top:5%;height:75%;width:44%;}
#divOptSigilosoSugestao {position:absolute;left:35%;top:30%;}
#divOptRestritoSugestao {position:absolute;left:35%;top:50%;}
#divOptPublicoSugestao {position:absolute;left:35%;top:70%;}

#divGrauSigilo {<?=$strDisplayGrauSigilo?>}
#lblGrauSigilo {position:absolute;left:0%;top:0%;}
#selGrauSigilo {position:absolute;left:0%;top:40%;width:35%;}

#divHipoteseLegal {<?=$strDisplayHipoteseLegal?>}
#lblHipoteseLegal {position:absolute;left:0%;top:0%;}
#selHipoteseLegal {position:absolute;left:0%;top:40%;width:80%;}

<?=$strCssRestricao?>

<? if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){ ?>
#divSinSigilosoPermitido {top:15%;}
#divSinRestritoPermitido {top:40%;}
#divSinPublicoPermitido {top:65%;}

#divOptSigilosoSugestao {top:15%;}
#divOptRestritoSugestao {top:40%;}
#divOptPublicoSugestao {top:65%;}

<? }
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objLupaAssuntos = null;
var objAutoCompletarAssuntoRI1223 = null;
var objAjaxHipoteseLegal = null;

<?=$strJsGlobalRestricao?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_procedimento_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_procedimento_consultar'){
    infraDesabilitarCamposAreaDados();
  }
  
  objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssunto','txtAssunto','<?=$strLinkAjaxAssuntoRI1223?>');
  objAutoCompletarAssuntoRI1223.limparCampo = true;

  objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtAssunto').value;
  };
  
  objAutoCompletarAssuntoRI1223.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaAssuntos.adicionar(id,descricao,document.getElementById('txtAssunto'));
    }
  };

  objLupaAssuntos = new infraLupaSelect('selAssuntos','hdnAssuntos','<?=$strLinkAssuntosSelecao?>');
  
  objAjaxHipoteseLegal = new infraAjaxMontarSelect('selHipoteseLegal','<?=$strLinkAjaxHipoteseLegal?>');
	objAjaxHipoteseLegal.prepararExecucao = function(){
	  var staNivelAcesso = null;
	  if (document.getElementById('optSigilosoSugestao').checked){
	    staNivelAcesso = '<?=ProtocoloRN::$NA_SIGILOSO?>';
	  }else if (document.getElementById('optRestritoSugestao').checked){
	    staNivelAcesso = '<?=ProtocoloRN::$NA_RESTRITO?>';
	  }else if (document.getElementById('optPublicoSugestao').checked){
	    staNivelAcesso = '<?=ProtocoloRN::$NA_PUBLICO?>';
	  }
    return infraAjaxMontarPostPadraoSelect('null','','null') + '&staNivelAcesso=' + staNivelAcesso;
	};

  <?=$strJsInicializarRestricao?>

  formatarExibicaoNivelAcesso();
  
  infraEfeitoTabelas();
}

function OnSubmitForm() {

  if (validarFormRI0288()){
    //infraExibirAviso(false);
    return true;
  }
  return false;
}

function validarFormRI0288() {

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  /*
  if (infraTrim(document.getElementById('txaDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txaDescricao').focus();
    return false;
  }
  */

  if (!document.getElementById('chkSinSigilosoPermitido').checked && !document.getElementById('chkSinRestritoPermitido').checked && !document.getElementById('chkSinPublicoPermitido').checked){
    alert('Selecione os níveis de acesso permitidos.');
    return false;
  }

  if (!document.getElementById('optSigilosoSugestao').checked && !document.getElementById('optRestritoSugestao').checked && !document.getElementById('optPublicoSugestao').checked){
    alert('Selecione o nível de acesso sugerido.');
    return false;
  }
  
  return true;
}

function formatarExibicaoNivelAcesso(){

  if (!document.getElementById('chkSinSigilosoPermitido').checked){
    document.getElementById('optSigilosoSugestao').checked = false;
    document.getElementById('optSigilosoSugestao').disabled = true;
    document.getElementById('spnSigilosoSugestao').disabled = true;
  }else{
    document.getElementById('optSigilosoSugestao').disabled = false;
    document.getElementById('spnSigilosoSugestao').disabled = false;
  }
  
  if (!document.getElementById('chkSinRestritoPermitido').checked){
    document.getElementById('optRestritoSugestao').checked = false;
    document.getElementById('optRestritoSugestao').disabled = true;
    document.getElementById('spnRestritoSugestao').disabled = true;
  }else{
    document.getElementById('optRestritoSugestao').disabled = false;
    document.getElementById('spnRestritoSugestao').disabled = false;
  }
  
  if (!document.getElementById('chkSinPublicoPermitido').checked){
    document.getElementById('optPublicoSugestao').checked = false;
    document.getElementById('optPublicoSugestao').disabled = true;
    document.getElementById('spnPublicoSugestao').disabled = true;
  }else{
    document.getElementById('optPublicoSugestao').disabled = false;
    document.getElementById('spnPublicoSugestao').disabled = false;
  }
  
}

function alterarNivelAcessoSugerido(){

  <?if ($numHabilitarGrauSigilo){?>
    if (document.getElementById('optSigilosoSugestao').checked){
      document.getElementById('divGrauSigilo').style.display='block';
    }else{
      document.getElementById('selGrauSigilo').options[0].selected = true;
      document.getElementById('divGrauSigilo').style.display='none';
    }
  <?}?>

  <?if ($numHabilitarHipoteseLegal){?>
   objAjaxHipoteseLegal.executar();
  <?}?>
}


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoProcedimentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divNome" class="infraAreaDados" style="height:5em;">
    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoProcedimentoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" />
  </div>  

  <div id="divDescricao" class="infraAreaDados" style="height:7em;">
    <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="2" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" ><?=PaginaSEI::tratarHTML($objTipoProcedimentoDTO->getStrDescricao());?></textarea>
  </div>

  <div id="divAssuntos" class="infraAreaDados" style="height:13em;">
    <label id="lblAssuntos" for="selAssuntos" accesskey="" class="infraLabelOpcional">Sugestão de Assuntos:</label>
    <input type="text" id="txtAssunto" name="txtAssunto" class="infraText"  />
    <select id="selAssuntos" name="selAssuntos" size="4" multiple="multiple" class="infraSelect">
    	<?=$strItensSelAssuntos?>
    </select>
    <div id="divOpcoesAssuntos">
      <img id="imgLupaAssuntos" onclick="objLupaAssuntos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Assunto" title="Localizar Assunto" class="infraImg" />
      <img id="imgAssuntosAcima" onclick="objLupaAssuntos.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Assunto Selecionado" title="Mover Acima Assunto Selecionado" class="infraImg" />
      <br />
      <img id="imgExcluirAssuntos" onclick="objLupaAssuntos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Assuntos" title="Remover Assuntos" class="infraImg" />
      <img id="imgAssuntosAbaixo" onclick="objLupaAssuntos.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Assunto Selecionado" title="Mover Abaixo Assunto Selecionado" class="infraImg" />
    </div>
    <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" value="" />
  </div>

  <?=$strHtmlRestricao?>

  <div id="divNivelAcessoPermitido" class="infraAreaDados" style="height:14em;">
    <fieldset id="fldNivelAcessoPermitido" class="infraFieldset">
    	<legend class="infraLegend">Níveis de Acesso Permitidos</legend>
    	
    	  <div id="divSinSigilosoPermitido" class="infraDivCheckbox">
  			<input type="checkbox" name="chkSinSigilosoPermitido" id="chkSinSigilosoPermitido" class="infraCheckbox" onclick="formatarExibicaoNivelAcesso();" <?=PaginaSEI::getInstance()->setCheckbox($strSinSigilosoPermitido)?> />
  	    <label id="lblSinSigilosoPermitido" for="chkSinSigilosoPermitido" class="infraLabelRadio" >Sigiloso</label>
  	    </div>
    	
    	  <div id="divSinRestritoPermitido" class="infraDivCheckbox">
  			<input type="checkbox" name="chkSinRestritoPermitido" id="chkSinRestritoPermitido" class="infraCheckbox" onclick="formatarExibicaoNivelAcesso();" <?=PaginaSEI::getInstance()->setCheckbox($strSinRestritoPermitido)?> />
  	    <label id="lblSinRestritoPermitido" for="chkSinRestritoPermitido" class="infraLabelRadio" >Restrito</label>
  	    </div>
  	    
  	    <div id="divSinPublicoPermitido" class="infraDivCheckbox">
  	    <input type="checkbox" name="chkSinPublicoPermitido" id="chkSinPublicoPermitido" class="infraCheckbox" onclick="formatarExibicaoNivelAcesso();" <?=PaginaSEI::getInstance()->setCheckbox($strSinPublicoPermitido)?> />
  	    <label id="lblSinPublicoPermitido" for="chkSinPublicoPermitido" class="infraLabelRadio" >Público</label>
  	    </div>
  	    
    </fieldset>       
   
    <fieldset id="fldNivelAcessoSugestao" class="infraFieldset">
    	<legend class="infraLegend">Nível de Acesso Sugerido (Serviços e Módulos)</legend>
    	
    	  <div id="divOptSigilosoSugestao" class="infraDivRadio"> 
    			<input type="radio" name="rdoNivelAcessoSugestao" id="optSigilosoSugestao" onclick="alterarNivelAcessoSugerido()" value="<?=ProtocoloRN::$NA_SIGILOSO?>" <?=($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao()==ProtocoloRN::$NA_SIGILOSO?'checked="checked"':'')?> class="infraRadio"/>
    	    <span id="spnSigilosoSugestao"><label id="lblSigilosoSugestao" for="optSigilosoSugestao" class="infraLabelRadio" >Sigiloso</label></span>
  	    </div>
    	
        <div id="divOptRestritoSugestao" class="infraDivRadio">	  
    			<input type="radio" name="rdoNivelAcessoSugestao" id="optRestritoSugestao" onclick="alterarNivelAcessoSugerido()" value="<?=ProtocoloRN::$NA_RESTRITO?>" <?=($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao()==ProtocoloRN::$NA_RESTRITO?'checked="checked"':'')?> class="infraRadio"/>
    	    <span id="spnRestritoSugestao"><label id="lblRestritoSugestao" for="optRestritoSugestao" class="infraLabelRadio" >Restrito</label></span>
  	    </div>
  	    
  	    <div id="divOptPublicoSugestao" class="infraDivRadio">
    	    <input type="radio" name="rdoNivelAcessoSugestao" id="optPublicoSugestao" onclick="alterarNivelAcessoSugerido()" value="<?=ProtocoloRN::$NA_PUBLICO?>" <?=($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao()==ProtocoloRN::$NA_PUBLICO?'checked="checked"':'')?> class="infraRadio"/>
    	    <span id="spnPublicoSugestao"><label id="lblPublicoSugestao" for="optPublicoSugestao" class="infraLabelRadio" >Público</label></span>
  	    </div>
  	    
    </fieldset>  
  </div>  

  <div id="divGrauSigilo" class="infraAreaDados" style="height:5em;">
    <label id="lblGrauSigilo" for="selGrauSigilo" accesskey="" class="infraLabelOpcional">Sugestão de Grau de Sigilo:</label>
    <select id="selGrauSigilo" name="selGrauSigilo" class="infraSelect" >
    <?=$strItensSelGrauSigilo?>
    </select>
  </div>
  
  <div id="divHipoteseLegal" class="infraAreaDados" style="height:5em;">
    <label id="lblHipoteseLegal" for="selHipoteseLegal" accesskey="" class="infraLabelOpcional">Sugestão de Hipótese Legal:</label>
    <select id="selHipoteseLegal" name="selHipoteseLegal" class="infraSelect" >
    <?=$strItensSelHipoteseLegal?>
    </select>
  </div>
  <br>

  <div id="divSinOuvidoria" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinOuvidoria" name="chkSinOuvidoria" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objTipoProcedimentoDTO->getStrSinOuvidoria())?>  />
    <label id="lblSinOuvidoria" for="chkSinOuvidoria" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[TipoProcedimentoRN::$TS_EXCLUSIVO_OUVIDORIA]->getStrDescricao())?></label>
  </div>

  <div id="divSinIndividual" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinIndividual" name="chkSinIndividual" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objTipoProcedimentoDTO->getStrSinIndividual())?>  />
    <label id="lblSinIndividual" for="chkSinIndividual" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[TipoProcedimentoRN::$TS_PROCESSO_UNICO]->getStrDescricao())?></label>
  </div>

  <div id="divSinInterno" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinInterno" name="chkSinInterno" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objTipoProcedimentoDTO->getStrSinInterno())?>  />
    <label id="lblSinInterno" for="chkSinInterno" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[TipoProcedimentoRN::$TS_INTERNO_SISTEMA]->getStrDescricao())?></label>
  </div>
  <br>


  <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento" value="<?=$objTipoProcedimentoDTO->getNumIdTipoProcedimento();?>" />
  <input type="hidden" id="hdnAssuntos" name="hdnAssuntos" value="<?=$_POST['hdnAssuntos']?>" />

  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>