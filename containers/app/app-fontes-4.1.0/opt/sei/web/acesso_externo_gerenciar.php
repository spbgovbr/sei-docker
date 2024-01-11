<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/05/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
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


  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();
  
  switch($_GET['acao']){
  	
  	case 'acesso_externo_disponibilizar':
  		
  		$strTitulo = 'Disponibilização de Acesso Externo';
  		
  		try{

      	$objAcessoExternoDTO = new AcessoExternoDTO();
				$objAcessoExternoDTO->setStrEmailUnidade($_POST['selEmailUnidade']);
        $objAcessoExternoDTO->setStrSinInclusao('N');

				if (!InfraString::isBolVazia($_POST['hdnIdParticipante'])){
				  $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_INTERESSADO);
				  $objAcessoExternoDTO->setNumIdParticipante($_POST['hdnIdParticipante']);
				}else if (!InfraString::isBolVazia($_POST['hdnIdUsuarioExterno'])){
				  $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_USUARIO_EXTERNO);
				  $objAcessoExternoDTO->setNumIdUsuarioExterno($_POST['hdnIdUsuarioExterno']);
          $objAcessoExternoDTO->setStrSinInclusao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInclusao']));
				}else{
				  $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_DESTINATARIO_ISOLADO);
				  $objAcessoExternoDTO->setNumIdContatoParticipante($_POST['hdnIdContato']);
				  $objAcessoExternoDTO->setStrNomeContato($_POST['txtDestinatario']);
				}

        $objAcessoExternoDTO->setDblIdProtocoloAtividade($_GET['id_procedimento']);
     	  $objAcessoExternoDTO->setStrEmailDestinatario($_POST['hdnEmailDestinatario']);
				$objAcessoExternoDTO->setStrSenha($_POST['pwdSenha']);		      	
     		$objAcessoExternoDTO->setStrMotivo($_POST['txaMotivo']);
    		$objAcessoExternoDTO->setNumDias($_POST['txtDias']);

        $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnProtocolos']);
        $arrObjRelAcessoExtProtocoloDTO = array();
        foreach($arr as $dblIdProtocolo){
          $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
          $objRelAcessoExtProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);
          $arrObjRelAcessoExtProtocoloDTO[] = $objRelAcessoExtProtocoloDTO;
        }
        $objAcessoExternoDTO->setArrObjRelAcessoExtProtocoloDTO($arrObjRelAcessoExtProtocoloDTO);
    		
        $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']);
        $arrObjRelAcessoExtSerieDTO = array();
        foreach($arr as $numIdSerie){
          $objRelAcessoExtSerieDTO = new RelAcessoExtSerieDTO();
          $objRelAcessoExtSerieDTO->setNumIdSerie($numIdSerie);
          $arrObjRelAcessoExtSerieDTO[] = $objRelAcessoExtSerieDTO;
        }
        $objAcessoExternoDTO->setArrObjRelAcessoExtSerieDTO($arrObjRelAcessoExtSerieDTO);

        if (isset($_POST['hdnFlag'])) {
          $objAcessoExternoRN = new AcessoExternoRN();
          $objAcessoExternoDTO = $objAcessoExternoRN->cadastrar($objAcessoExternoDTO);
          PaginaSEI::getInstance()->setStrMensagem(PaginaSEI::getInstance()->formatarParametrosJavaScript('Disponibilização de Acesso Externo enviada.'."\n\n".'Verifique posteriormente a caixa postal da unidade para certificar-se de que não ocorreram problemas na entrega.'),PaginaSEI::$TIPO_MSG_AVISO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora($objAcessoExternoDTO->getNumIdAcessoExterno())));
          die;
        }

  		}catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e, true);
  		}
    
    case 'acesso_externo_gerenciar':
      $strTitulo = 'Gerenciar Disponibilizações de Acesso Externo';
	    break;
	
	    default:
	      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  if ($_POST['hdnSeries']!=''){
    $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnSeries']);
    $strSeriesSel = SerieINT::montarSelectAcessoExterno(null,null,null,$arr);
  }else{
    $strSeriesSel = "";
  }

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $numHabilitarInclusaoDocumentos = $objInfraParametro->getValor('SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO');

  $strLinkAjaxUsuarioExterno = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_externo_auto_completar_contato');
  $strLinkAjaxUsuarioTodos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_acesso_externo');
  $strLinkAjaxUsuarioDados = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=acesso_externo_dados_destinatario');

  $objAcessoExternoDTO = new AcessoExternoDTO();
  $objAcessoExternoDTO->setDblIdProtocoloAtividade($_GET['id_procedimento']);
  
	$objAcessoExternoRN = new AcessoExternoRN();	 
	$arrObjAcessoExternoDTO = $objAcessoExternoRN->listarDisponibilizacoes($objAcessoExternoDTO);
	
  $numRegistros = count($arrObjAcessoExternoDTO);

  $bolAcaoDisponibilizar = SessaoSEI::getInstance()->verificarPermissao('acesso_externo_disponibilizar');
  $bolAcaoCancelarDisponibilizacao = SessaoSEI::getInstance()->verificarPermissao('acesso_externo_cancelar');
  	
  if ($bolAcaoDisponibilizar){
    //$arrComandos[] ='<button type="button" name="btnDisponibilizar" id="btnDisponibilizar" onclick="disponibilizar();" accesskey="D" value="Disponibilizar" class="infraButton"><span class="infraTeclaAtalho">D</span>isponibilizar</button>';
    $strLinkDisponibilizar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_disponibilizar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
  }
  
  if ($numRegistros > 0){
  	
    if ($bolAcaoCancelarDisponibilizacao){
    	//$arrComandos[] = '<button type="submit" accesskey="a" name="sbmCancelarLiberacao" id="sbmCancelarLiberacao" onclick="acaoCassacaoMultipla();" value="Cancelar Liberação" class="infraButton">C<span class="infraTeclaAtalho">a</span>ssar</button>';
      //$strLinkCancelarDisponibilizacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_cancelar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
    }
  	
    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Disponibilizações de Acesso Externo.';
    $strCaptionTabela = 'Disponibilizações de Acesso Externo';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display:none;">'.PaginaSEI::getInstance()->getThCheck('','Infra','style="display:none;"').'</th>'."\n";
    $strResultado .= '<th class="infraTh">Destinatário</th>'."\n";
    //$strResultado .= '<th class="infraTh">E-mail</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Disponibilização</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Validade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Visualização</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Cancelamento</th>'."\n";

  /*  if ($numHabilitarInclusaoDocumentos == '1') {
      $strResultado .= '<th class="infraTh" width="12%">Inclusão de Documentos</th>'."\n";
    }*/

    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    //$strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    $n = 0;
    foreach($arrObjAcessoExternoDTO as $objAcessoExternoDTO){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= "\n".'<td valign="top" style="display:none;">';
      $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++,$objAcessoExternoDTO->getNumIdAcessoExterno(),$objAcessoExternoDTO->getStrSiglaContato().'/'.$objAcessoExternoDTO->getStrSiglaUnidade(),'N','Infra','style="visibility:hidden;"');
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center"  valign="top">'.PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrNomeContato()).'<br/>'.PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrEmailDestinatario()).'</td>';

      $strResultado .= "\n".'<td align="center"  valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrSiglaUnidade()).'</a>';
      $strResultado .= '</td>'."\n";

      $strResultado .= '<td align="center" valign="top">'.substr($objAcessoExternoDTO->getDthAberturaAtividade(),0,16).'</td>'."\n";

      //$strResultado .= "\n".'<td align="center"  valign="top">'.PaginaSEI::tratarHTML($objAcessoExternoDTO->getStrEmailDestinatario()).'</td>';
      $strResultado .= "\n".'<td align="center"  valign="top">'.$objAcessoExternoDTO->getDtaValidade().'</td>';

			$strResultado .= '<td align="center" valign="top">'.substr($objAcessoExternoDTO->getDthVisualizacao(),0,16).'</td>'."\n";

			$strResultado .= '<td align="center" valign="top">';
			if ($objAcessoExternoDTO->getDthCancelamento()!=null){
			  $strResultado .= substr($objAcessoExternoDTO->getDthCancelamento(),0,16);
			}else{
			  $strResultado .= '&nbsp;';
			}
			$strResultado .= '</td>'."\n";

/*			if ($numHabilitarInclusaoDocumentos == '1') {
        $strResultado .= '<td align="center" valign="top">';
        if ($objAcessoExternoDTO->getStrSinInclusao() == "S") {
          $strResultado .= "Sim";
        } else {
          $strResultado .= '&nbsp;';
        }
        $strResultado .= '</td>'."\n";
      }*/

			$strResultado .= '<td align="center" valign="top">';

      $strDetalhes = '';
      $strOnClick = '';
      $arrObjRelAcessoExtProtocoloDTO = $objAcessoExternoDTO->getArrObjRelAcessoExtProtocoloDTO();

      if (InfraArray::contar($arrObjRelAcessoExtProtocoloDTO) == 0){
        $strDetalhes = 'Visualização integral do processo';
        $strIcone = Icone::ACESSO_EXTERNO_INTEGRAL;
      }else{
        $strDetalhes = 'Para disponibilização de documentos (clique aqui para ver a relação)';
        $strOnClick = 'onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);visualizarDetalhes(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_protocolo_detalhe&acao_origem='.$_GET['acao'].'&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno().'&id_procedimento='.$objAcessoExternoDTO->getDblIdProtocoloAtividade()).'\')"';
        $strIcone = Icone::ACESSO_EXTERNO_PARCIAL;
      }

      $strResultado .= '<a href="javascript:void(0)" '.$strOnClick.' '.PaginaSEI::montarTitleTooltip($strDetalhes) . '><img src="'.$strIcone.'" class="infraImg" /></a>';

      if ($numHabilitarInclusaoDocumentos == '1'){
        if ($objAcessoExternoDTO->getStrSinInclusao() == "S") {
          $strResultado .= '<a href="javascript:void(0)" onclick="visualizarSeries(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_acesso_ext_serie_detalhar&acao_origem='.$_GET['acao'].'&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno()).'\')" '.PaginaSEI::montarTitleTooltip("Permitida inclusão de documentos (clique aqui para ver a relação)") . '><img src="'.Icone::ACESSO_EXTERNO_INCLUSAO.'" class="infraImg" /></a>';
        }else{
          //$strResultado .= '<a href="javascript:void(0)" '.PaginaSEI::montarTitleTooltip("Não permitida inclusão de documentos") . '><img src="'.Icone::ACESSO_EXTERNO_SEM_INCLUSAO.'" class="infraImg" /></a>';
        }
      }

		  if ($bolAcaoCancelarDisponibilizacao && $objAcessoExternoDTO->getStrSinAtivo()=='S'){
		    $strResultado .= '<a href="#ID-'.$objAcessoExternoDTO->getNumIdAcessoExterno().'"  onclick="acaoCancelarDisponibilizacao(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_cancelar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros.'&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeRemover().'" title="Cancelar Disponibilização de Acesso Externo" alt="Cancelar Disponibilização de Acesso Externo" class="infraImg" /></a>';
      }else{
      	$strResultado .= '&nbsp;';
      }
			$strResultado .= '</td>';
			
      
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strItensSelEmailUnidade = EmailUnidadeINT::montarSelectEmail('null','&nbsp;',$_POST['selEmailUnidade']);
  $strItensSelIdParticipante = ParticipanteINT::montarSelectInteressados('null','&nbsp;',$_POST['selIdParticipante'],$_GET['id_procedimento']);

  $strDisplayInclusao = ($numHabilitarInclusaoDocumentos == '1') ? '' : 'display:none;';

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

#lblEmailUnidade {position:absolute;left:0%;top:5%;}
#selEmailUnidade {position:absolute;left:0%;top:40%;width:45%;}

#lblDestinatario {position:absolute;left:0%;top:0%;width:45%;}
#txtDestinatario {position:absolute;left:0%;top:20%;width:45%;}

#divUsuariosExternos {position:absolute;left:47%;top:22%;width:45%;}

#lblEmailDestinatario {position:absolute;left:0%;top:50%;width:45%;}
#txtEmailDestinatario {position:absolute;left:0%;top:70%;width:45%;}

#lblMotivo {position:absolute;left:0%;top:0%;}
#txaMotivo {position:absolute;left:0%;top:22%;width:90%;}

#fldTipo {position:absolute;left:0%;top:5%;height:75%;width:44%;min-width:300px;}
#divOptIntegral {position:absolute;left:10%;top:<?=(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'10%':'30%')?>;}
#divOptParcial {position:absolute;left:10%;top:<?=(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'50%':'60%')?>;}

#fldInclusao {position:absolute;left:0%;top:5%;height:75%;width:44%;min-width:300px;<?=$strDisplayInclusao?>}
#divInclusao {position:absolute;left:10%;top:<?=(PaginaSEI::getInstance()->isBolAjustarTopFieldset()?'30%':'45%')?>;}

#lblProtocolos {position:absolute;left:0%;top:0%;}
#selProtocolos {position:absolute;left:0%;top:18%;width:90%;}
#divOpcoesProtocolos {position:absolute;left:91%;top:20%;}

#lblSeries {position:absolute;left:0%;top:0%;}
#selSeries {position:absolute;left:0%;top:18%;width:90%;}
#divOpcoesSeries {position:absolute;left:91%;top:20%;}

#lblDias {position:absolute;left:0%;top:5%;width:20%;}
#txtDias {position:absolute;left:0%;top:43%;width:15%;}

#lblSenha {position:absolute;left:21%;top:5%;}
#pwdSenha {position:absolute;left:21%;top:43%;width:20%;}

#btnDisponibilizar {position:absolute;left:0%;top:20%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarDestinatario = null;
var objAjaxComplementarDestinatario = null;
var objLupaProtocolos = null;
var objLupaSeries = null;

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});
function inicializar(){

  objAjaxComplementarDestinatario = new infraAjaxComplementar(null,'<?=$strLinkAjaxUsuarioDados?>');
  objAjaxComplementarDestinatario.prepararExecucao = function(){
    return 'id_procedimento=<?=$_GET['id_procedimento']?>&id_contato='+document.getElementById('hdnIdContato').value;
  };
  objAjaxComplementarDestinatario.processarResultado = function(arr){
    if (arr!=null){

      if (arr['IdParticipante'] != undefined){
        document.getElementById('hdnIdParticipante').value = arr['IdParticipante'];
      }

      if (arr['IdUsuarioExterno'] != undefined){
        document.getElementById('hdnIdUsuarioExterno').value = arr['IdUsuarioExterno'];
        document.getElementById('chkSinInclusao').disabled = false;
        document.getElementById('txtEmailDestinatario').disabled = true;
        $("#txtEmailDestinatario").attr('class', 'infraText infraReadOnly');
      }else{
        document.getElementById('chkSinInclusao').disabled = true;
        document.getElementById('txtEmailDestinatario').disabled = false;
        $("#txtEmailDestinatario").attr('class', 'infraText');
      }
    
      if (arr['Email'] != undefined){
        document.getElementById('txtEmailDestinatario').value = arr['Email'];
        document.getElementById('txaMotivo').focus();
      }else{
        document.getElementById('txtEmailDestinatario').focus();
      }
    }

  };

  objAutoCompletarDestinatario = new infraAjaxAutoCompletar('hdnIdContato','txtDestinatario','<?=$strLinkAjaxUsuarioTodos?>');
  //objAutoCompletarDestinatario.maiusculas = true;
  //objAutoCompletarDestinatario.mostrarAviso = true;
  //objAutoCompletarDestinatario.tempoAviso = 1000;
  //objAutoCompletarDestinatario.tamanhoMinimo = 3;
  objAutoCompletarDestinatario.limparCampo = false;
  //objAutoCompletarDestinatario.bolExecucaoAutomatica = false;

  objAutoCompletarDestinatario.prepararExecucao = function(){
    return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtDestinatario').value);
  };
  
  //processarResultado(id,descricao,complemento)
  objAutoCompletarDestinatario.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objAjaxComplementarDestinatario.executar();
    }else{
      limparDestinatario();
    }
  }
  
  <? if ($_GET['acao']=='acesso_externo_disponibilizar'){ ?>
    objAutoCompletarDestinatario.selecionar('<?=$_POST['hdnIdContato']?>','<?=$_POST['txtDestinatario']?>');
  <? } ?>

  objLupaProtocolos	= new infraLupaSelect('selProtocolos','hdnProtocolos','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_externo_protocolo_selecionar&tipo_selecao=2&id_object=objLupaProtocolos&id_procedimento='.$_GET['id_procedimento'])?>');
  objLupaSeries	= new infraLupaSelect('selSeries','hdnSeries','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=serie_selecionar_acesso_externo&tipo_selecao=2&id_object=objLupaSeries')?>');

  document.getElementById('selEmailUnidade').focus();
  infraEfeitoTabelas();

  trocarTipo();
  trocarInclusaoDocumentos();
}

<? if ($bolAcaoCancelarDisponibilizacao){ ?>
function acaoCancelarDisponibilizacao(link){
  infraAbrirJanelaModal(link, 650,250);
}

function acaoCancelamentoLiberacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma disponibilização de acesso externo selecionada.');
    return;
  }
  acaoCancelarDisponibilizacao(null);
}
<? } ?>

function visualizarDetalhes(link){
  infraAbrirJanelaModal(link,700,400);
}

function visualizarSeries(link){
  infraAbrirJanelaModal(link,700,400);
}

<? if ($bolAcaoDisponibilizar){ ?>

function disponibilizar(){

	if (document.getElementById('selEmailUnidade').value == 'null' || document.getElementById('selEmailUnidade').value == '') {
    alert('E-mail da unidade não informado.');
    document.getElementById('selEmailUnidade').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDestinatario').value) == ''){
    alert('Destinatário não informado.');
    document.getElementById('txtDestinatario').focus();
    return false;
  }
      
  if (infraTrim(document.getElementById('txtEmailDestinatario').value) == '') {
    alert('E-mail do destinatário não informado.');
    document.getElementById('txtEmailDestinatario').focus();
    return false;
  }
  
  if (!infraValidarEmail(infraTrim(document.getElementById('txtEmailDestinatario').value))){
    alert('Endereço eletrônico "'+ document.getElementById('txtEmailDestinatario').value + '" inválido.');
    document.getElementById('txtEmailDestinatario').focus();  
    return false;
  }
  
  document.getElementById('hdnEmailDestinatario').value = document.getElementById('txtEmailDestinatario').value;
  
  
  if (document.getElementById('txaMotivo').value == '') {
    alert('Motivo não informado.');
    document.getElementById('txaMotivo').focus();
    return false;
  }

  if (!document.getElementById('optIntegral').checked && !document.getElementById('optParcial').checked){
    alert('Selecione o Tipo do acesso externo.');
    return false;
  }

  if ($("#hdnIdUsuarioExterno").val() == '' && $("#chkSinInclusao").is(":checked")) {
    alert('Sinalizador de inclusão de documentos é permitido apenas para usuários externos.');
    $('#txtDestinatario').focus();
    return false;
  }

  if (document.getElementById('optParcial').checked && document.getElementById('selProtocolos').options.length==0) {
    alert('Nenhum protocolo selecionado para disponibilização.');
    document.getElementById('selProtocolos').focus();
    return false;
  }

  if (document.getElementById('chkSinInclusao').checked && document.getElementById('selSeries').options.length==0) {
    alert('Nenhum tipo de documento selecionado para inclusão.');
    document.getElementById('selSeries').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDias').value) == '') {
    alert('Validade do acesso não informada.');
    document.getElementById('txtDias').focus();
    return false;
  }

  if (document.getElementById('txtDias').value <= 0){
    alert('Validade do acesso deve ser de pelo menos um dia.');
    document.getElementById('txtDias').focus();
    return false;
  }

  /*
  if (document.getElementById('txtDias').value > 60){
    alert('Validade do acesso não pode ser superior a 60 dias.');
    document.getElementById('txtDias').focus();
    return false;
  }
  */
  
  if (document.getElementById('pwdSenha').value == '') {
    alert('Senha não informada.');
    document.getElementById('pwdSenha').focus();
    return false;
  }

  document.getElementById('hdnFlag').value = '1';
  document.getElementById('frmAcessoExternoGerenciar').target = '_self';
  document.getElementById('frmAcessoExternoGerenciar').action = '<?=$strLinkDisponibilizar?>';
	document.getElementById('frmAcessoExternoGerenciar').submit();
}

<? } ?>

function OnSubmitForm(){
  return true;
}

function trocarTipo(){
  if (!document.getElementById('optParcial').checked){
    document.getElementById('divRestricao').style.display = 'none';
    document.getElementById('selProtocolos').options.length = 0;
  }else{
    document.getElementById('divRestricao').style.display = '';
  }
}
function trocarInclusaoDocumentos(){
  if (!document.getElementById('chkSinInclusao').checked){
    document.getElementById('divTiposDocumento').style.display = 'none';
  }else{
    document.getElementById('divTiposDocumento').style.display = '';
  }
}

function trocarFiltroUsuariosExternos(){

  if($("#chkUsuariosExternos").is(":checked")){
    objAutoCompletarDestinatario.ajaxTarget = '<?=$strLinkAjaxUsuarioExterno?>';
  }else{
    objAutoCompletarDestinatario.ajaxTarget = '<?=$strLinkAjaxUsuarioTodos?>';
  }

  objAutoCompletarDestinatario.limpar();

  limparDestinatario();

  document.getElementById('txtDestinatario').value = '';
  document.getElementById('txtDestinatario').focus();
}

function limparDestinatario(){
  document.getElementById('hdnIdParticipante').value = '';
  document.getElementById('hdnIdUsuarioExterno').value = '';
  document.getElementById('txtEmailDestinatario').value = '';
  document.getElementById('txtEmailDestinatario').disabled = false;
  document.getElementById('chkSinInclusao').checked = false;
  document.getElementById('chkSinInclusao').disabled = true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcessoExternoGerenciar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>

  <div id="divRemetente" class="infraAreaDados" style="height:6em;">
    <label id="lblEmailUnidade" for="selEmailUnidade" accesskey="" class="infraLabelObrigatorio">E-mail da Unidade:</label>
    <select id="selEmailUnidade" name="selEmailUnidade" class="infraSelect"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelEmailUnidade?>
    </select>
  </div>

  <div id="divDestinatario" class="infraAreaDados" style="height:10em;">
    <label id="lblDestinatario" for="txtDestinatario" class="infraLabelObrigatorio">Destinatário:</label>
    <input type="text" id="txtDestinatario" name="txtDestinatario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdContato" name="hdnIdContato" class="infraText" value="" />

    <div id="divUsuariosExternos">
      <input type="checkbox" id="chkUsuariosExternos" name="chkUsuariosExternos"  onchange="trocarFiltroUsuariosExternos()" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkUsuariosExternos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
      <label id="lblUsuariosExternos" for="chkUsuariosExternos" accesskey="" class="infraLabelCheckbox">Filtrar somente usuários externos</label>
    </div>

    <label id="lblEmailDestinatario" for="txtEmailDestinatario" accesskey="" class="infraLabelObrigatorio">E-mail do Destinatário:</label>
    <input type="text" id="txtEmailDestinatario" name="txtEmailDestinatario" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtEmailDestinatario'])?>" onkeypress="infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnEmailDestinatario" name="hdnEmailDestinatario" class="infraText" value="" />
  </div>

  <div id="divMotivo" class="infraAreaDados" style="height:9em;">
    <label id="lblMotivo" for="txaMotivo" class="infraLabelObrigatorio">Motivo:</label>
    <textarea id="txaMotivo" name="txaMotivo" rows="3" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($_POST['txaMotivo'])?></textarea>
  </div>

  <div id="divTipo" class="infraAreaDados" style="height:10em;">
    <fieldset id="fldTipo" class="infraFieldset">
      <legend class="infraLegend">Tipo</legend>

      <div id="divOptIntegral" class="infraDivRadio">
        <input type="radio" name="rdoTipo" id="optIntegral" onchange="trocarTipo()" value="I" <?=($_POST['rdoTipo']=='I'?'checked="checked"':'')?> class="infraRadio"/>
        <span id="spnIntegral"><label id="lblIntegral" for="optIntegral" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Acompanhamento integral do processo</label></span>
      </div>

      <div id="divOptParcial" class="infraDivRadio">
        <input type="radio" name="rdoTipo" id="optParcial" onchange="trocarTipo()" value="P" <?=($_POST['rdoTipo']=='P'?'checked="checked"':'')?> class="infraRadio"/>
        <span id="spnParcial"><label id="lblParcial" for="optParcial" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Disponibilização de documentos</label></span>
      </div>

    </fieldset>

  </div>


  <div id="divRestricao" class="infraAreaDados" style="height:11em;">
    <label id="lblProtocolos" for="selProtocolos" class="infraLabelOpcional">Protocolos disponibilizados (clique na lupa para selecionar):</label>
    <select id="selProtocolos" name="selProtocolos" multiple="multiple" size="5" class="infraSelect" ></select>
    <div id="divOpcoesProtocolos">
      <img id="imgLupaProtocolos" onclick="objLupaProtocolos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Protocolos" title="Selecionar Protocolos" class="infraImg"  />
      <br />
      <img id="imgExcluirProtocolos" onclick="objLupaProtocolos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Protocolos Selecionados" title="Remover Protocolos Selecionados" class="infraImgNormal"  />
    </div>
    <input type="hidden" id="hdnProtocolos" name="hdnProtocolos" value="<?=$_POST['hdnProtocolos']?>" />
  </div>

  <div id="divInclusaoDocumentos" class="infraAreaDados" style="height:10em;<?=$strDisplayInclusao?>">

    <fieldset id="fldInclusao" class="infraFieldset">
      <legend class="infraLegend">Somente para usuários externos</legend>

      <div id="divInclusao" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinInclusao" name="chkSinInclusao"  onchange="trocarInclusaoDocumentos()" class="infraCheckbox" disabled="disabled" <?=PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInclusao']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        <label id="lblSinInclusao" for="chkSinInclusao" accesskey="" class="infraLabelCheckbox">Permitir inclusão de documentos</label>
      </div>

    </fieldset>

  </div>

  <div id="divTiposDocumento" class="infraAreaDados" style="height:11em;">
    <label id="lblSeries" for="selSeries" class="infraLabelOpcional">Tipos de documentos liberados para inclusão (clique na lupa para selecionar):</label>
    <select id="selSeries" name="selSeries" multiple="multiple" size="5" class="infraSelect" >
      <?=$strSeriesSel?>
    </select>
    <div id="divOpcoesSeries">
      <img id="imgLupaSeries" onclick="objLupaSeries.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Tipos de Documentos" title="Selecionar Tipos de Documentos" class="infraImg"  />
      <br />
      <img id="imgExcluirSeries" onclick="objLupaSeries.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipos de Documentos Selecionados" title="Remover Tipos de Documentos Selecionados" class="infraImgNormal"  />
    </div>
    <input type="hidden" id="hdnSeries" name="hdnSeries" value="<?=$_POST['hdnSeries']?>" />
  </div>


  <div id="divValidadeSenha" class="infraAreaDados" style="height:5em;">
    <label id="lblDias" for="txtDias" class="infraLabelObrigatorio">Validade (dias):</label>
    <input type="text" id="txtDias" name="txtDias" class="infraText" value="<?=PaginaSEI::tratarHTML($_POST['txtDias'])?>" onkeypress="return infraMascaraNumero(this,event);" maxlength="4" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblSenha" for="pwdSenha" accesskey="" class="infraLabelObrigatorio">Senha:</label>
    <?=InfraINT::montarInputPassword('pwdSenha', '', 'tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"')?>
  </div>

  <div id="divBotao" class="infraAreaDados" style="height:2.5em;">
    <button type="button" name="btnDisponibilizar" id="btnDisponibilizar" onclick="disponibilizar();" accesskey="D" value="Disponibilizar" class="infraButton" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><span class="infraTeclaAtalho">D</span>isponibilizar</button>
  </div>

  <input type="hidden" id="hdnIdParticipante" name="hdnIdParticipante" class="infraText" value="" />
  <input type="hidden" id="hdnIdUsuarioExterno" name="hdnIdUsuarioExterno" class="infraText" value="" />
  <input type="hidden" id="hdnFlag" name="hdnFlag" value="0" />

  <br />
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
	PaginaSEI::getInstance()->montarAreaDebug();
	//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>