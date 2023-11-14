<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array(''));  

  $arrComandos = array();


  //Filtrar parâmetros
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
    $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  } 

  if (isset($_GET['id_documento'])){
    $strParametros .= "&id_documento=".$_GET['id_documento'];
  } 

  if (isset($_GET['id_documento_edoc'])){
    $strParametros .= "&id_documento_edoc=".$_GET['id_documento_edoc'];
  } 
  
  $bolEnvioOK = false;
  $strMsgComplementar = '';
	$strItensSelDe = '';
	$strSinOuvidoriaTipoProcedimento = null;
  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

  switch($_GET['acao']){
    
    case 'email_upload_anexo':
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;

    case 'documento_email_circular':

			$strTitulo = 'Enviar Circular';

			if ($_GET['acao_origem']=='documento_gerar_circular'){
				$strIdDocumentoCircular = $_POST['hdnInfraItensSelecionados'];
			}else{
				$strIdDocumentoCircular = $_POST['hdnIdDocumentoCircular'];
			}

      $objEmailDTO = new EmailDTO();
			$objEmailDTO->setDblIdProtocolo($_GET['id_procedimento']);

      if (isset($_POST['selDe'])) {
        EmailUnidadeINT::validarEmailUnidadeRemetente($_POST['selDe']);
      }

      $objEmailDTO->setStrDe($_POST['selDe']);
      $objEmailDTO->setStrPara(null);
      $objEmailDTO->setStrCCO(null);
		  $objEmailDTO->setStrAssunto($_POST['txtAssunto']);
		  $objEmailDTO->setStrMensagem($_POST['txaMensagem']);
		  $objEmailDTO->setArrIdDocumentosCirculares(explode(',',$strIdDocumentoCircular));
			$objEmailDTO->setArrArquivosUpload(null);
			$objEmailDTO->setArrIdDocumentosProcesso(null);

			if (isset($_POST['hdnFlagEmail'])) {

				try {

					$objEmailRN = new EmailRN();
					$arrObjDocumentoDTO = $objEmailRN->enviarCircular($objEmailDTO);

					foreach($arrObjDocumentoDTO as $objDocumentoDTO){
            if ($objDocumentoDTO->getObjInfraException()!=null){
              $strMsgComplementar .= $objDocumentoDTO->getObjInfraException()->__toString()."\n\n";
            }
          }

					$strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$arrObjDocumentoDTO[0]->getDblIdDocumento().'&montar_visualizacao=1');

					$bolEnvioOK = true;

				} catch (Exception $e) {
					PaginaSEI::getInstance()->processarExcecao($e);
				}
			}
			break;

    case 'procedimento_enviar_email':
    case 'documento_enviar_email':
		case 'responder_formulario':
		case 'email_encaminhar':

    	
    	if ($_GET['acao']=='procedimento_enviar_email'){
    	  $strTitulo = 'Enviar Correspondência Eletrônica';  
    	}else if ($_GET['acao']=='documento_enviar_email'){
    	  $strTitulo = 'Enviar Documento por Correio Eletrônico';
			}else if ($_GET['acao']=='responder_formulario'){
				$strTitulo = 'Responder Formulário';
    	}else if ($_GET['acao']=='email_encaminhar'){
    		$strTitulo = 'Encaminhar / Reenviar Correspondência Eletrônica';
      }

      $strEmailPara = str_replace("\n", '', $_POST['hdnDestinatario']);

      $objEmailDTO = new EmailDTO();
      $strSinCCO = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinCCO']);

      $strEmailDeResponderFormulario = '';

      if ($_GET['acao']!='responder_formulario') {
        if (isset($_POST['selDe'])){
          EmailUnidadeINT::validarEmailUnidadeRemetente($_POST['selDe']);
        }
      }else{
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrSiglaUnidadeGeradora();
        $objProtocoloDTO->retStrSiglaOrgaoUnidadeGeradora();
        $objProtocoloDTO->retStrConteudoDocumento();
        $objProtocoloDTO->retDblIdProcedimentoDocumento();
        $objProtocoloDTO->retNumIdSerieDocumento();
        $objProtocoloDTO->retStrStaDocumentoDocumento();
        $objProtocoloDTO->setDblIdProtocolo($_GET['id_documento']);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        if ($objProtocoloDTO == null) {
          throw new InfraException('Formulário não encontrado.');
        }

        $arrParametros = $objInfraParametro->listarValores(array('SEI_ACESSO_FORMULARIO_OUVIDORIA', 'ID_SERIE_OUVIDORIA'));
        $bolAcessoRestritoOuvidoria = ($arrParametros['SEI_ACESSO_FORMULARIO_OUVIDORIA'] == '1');
        $numIdSerieOuvidoria = $arrParametros['ID_SERIE_OUVIDORIA'];

        if ($objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO &&
          $objProtocoloDTO->getNumIdSerieDocumento() == $numIdSerieOuvidoria) {

          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setStrSigla($objProtocoloDTO->getStrSiglaOrgaoUnidadeGeradora());

          $objOuvidoriaRN = new OuvidoriaRN();
          $strEmailDeResponderFormulario = $objOuvidoriaRN->obterEmailRemetente($objOrgaoDTO);

        } else {
          $strEmailDeResponderFormulario = $objProtocoloDTO->getStrSiglaOrgaoUnidadeGeradora().' <naoresponder@'.InfraString::transformarCaixaBaixa($objProtocoloDTO->getStrSiglaOrgaoUnidadeGeradora()).$objInfraParametro->getValor('SEI_SUFIXO_EMAIL').'>';
        }

        if (isset($_POST['selDe']) && $_POST['selDe']!=$strEmailDeResponderFormulario){
          throw new InfraException('Email do remetente '.InfraString::formatarXML($_POST['selDe']).' não é válido para a unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
        }
      }


      $objEmailDTO->setStrDe($_POST['selDe']);

      if ($strSinCCO=='N') {
        $objEmailDTO->setStrPara($strEmailPara);
        $objEmailDTO->setStrCCO('');
      }else{
        $objEmailDTO->setStrPara('');
        $objEmailDTO->setStrCCO($strEmailPara);
      }

		  $objEmailDTO->setStrAssunto($_POST['txtAssunto']);
      $objEmailDTO->setNumIdTextoPadraoInterno($_POST['selTextoPadrao']);
		  $objEmailDTO->setStrMensagem($_POST['txaMensagem']);
			
		  $objEmailDTO->setDblIdProtocolo($_GET['id_procedimento']);

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retStrSinOuvidoriaTipoProcedimento();
      $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

      if($objProcedimentoDTO==null){
        throw new InfraException('Processo não encontrado.');
      }

			$strSinOuvidoriaTipoProcedimento = $objProcedimentoDTO->getStrSinOuvidoriaTipoProcedimento();

      if (isset($_POST['hdnFlagEmail'])){
      	
     	  try{
					$objEmailDTO->setArrArquivosUpload(PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnAnexos']));
					$objEmailDTO->setArrIdDocumentosProcesso(PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnDocumentos']));

					$objEmailRN = new EmailRN();
					$objDocumentoDTO = $objEmailRN->enviar($objEmailDTO);
					
					$strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$objDocumentoDTO->getDblIdDocumento().'&atualizar_arvore=1');

          if ($objDocumentoDTO->getObjInfraException()!=null){
            $strMsgComplementar = $objDocumentoDTO->getObjInfraException()->__toString();
          }

					$bolEnvioOK = true;
					
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
	    }else{
	    	
				if ($_GET['acao']=='responder_formulario') {

          $strItensSelDe = '<option value="' . PaginaSEI::tratarHTML($strEmailDeResponderFormulario) . '">' . PaginaSEI::tratarHTML($strEmailDeResponderFormulario) . '</option>';

          $strEmailPara = DocumentoINT::obterAtributoConteudo($objProtocoloDTO->getStrConteudoDocumento(), 'NOME') . ' <' . DocumentoINT::obterAtributoConteudo($objProtocoloDTO->getStrConteudoDocumento(), 'EMAIL') . '>';
          $strItensSelPara = '<option value="' . $strEmailPara . '">' . $strEmailPara . '</option>';

          if ($objProcedimentoDTO->getStrSinOuvidoriaTipoProcedimento() == 'S') {
            $objEmailDTO->setStrAssunto('Contato com OUVIDORIA / ' . $objProtocoloDTO->getStrSiglaOrgaoUnidadeGeradora());
          } else {
            $objEmailDTO->setStrAssunto('Contato com ' . $objProtocoloDTO->getStrSiglaOrgaoUnidadeGeradora());
          }

          if ($bolAcessoRestritoOuvidoria &&
              $objProtocoloDTO->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO &&
              $objProtocoloDTO->getNumIdSerieDocumento() == $numIdSerieOuvidoria){

            $objEmailDTO->setStrMensagem('');

          }else {

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->retDthAbertura();
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProcedimentoDocumento());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_GERACAO_PROCEDIMENTO);

            $objAtividadeRN = new AtividadeRN();
            $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);

            $strConteudo = '';
            $strConteudo .= 'Formulário enviado em ' . $objAtividadeDTO->getDthAbertura() . '.' . "\n";
            $strConteudo .= DocumentoINT::formatarExibicaoConteudo(DocumentoINT::$TV_TEXTO, $objProtocoloDTO->getStrConteudoDocumento());

            $arrConteudo = explode("\n", $strConteudo);
            $strConteudo = '';
            foreach ($arrConteudo as $linha) {
              $strConteudo .= '>  ' . $linha . "\n";
            }
            $objEmailDTO->setStrMensagem("\n\n\n" . $strConteudo);
          }
				}else if ($_GET['acao']=='email_encaminhar'){
				  
      	  $objDocumentoDTO = new DocumentoDTO();
      	  $objDocumentoDTO->retStrConteudo();
      	  $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);
      	  
      	  $objDocumentoRN = new DocumentoRN();
      	  $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      	  if ($objDocumentoDTO==null){
      	    throw new InfraException('Documento não encontrado.');
      	  }
      	   
      	  $strConteudo = $objDocumentoDTO->getStrConteudo();
      	  
      		if (!InfraString::isBolVazia($strConteudo) && substr($strConteudo,0,5) == '<?xml'){
      
      			$objXml = new DomDocument('1.0','iso-8859-1');
      
      			$objXml->loadXML($strConteudo);
      
      			$arrAtributos = $objXml->getElementsByTagName('atributo');
      			
      			foreach($arrAtributos as $atributo){
      				if ($atributo->getAttribute('nome') == 'De'){
      					 $_POST['selDe'] = DocumentoINT::formatarTagConteudo(DocumentoINT::$TV_TEXTO,$atributo->nodeValue);
      					 break;
      				}
      			}
      
      			$strEmailPara = '';
      			foreach($arrAtributos as $atributo){
      				if ($atributo->getAttribute('nome') == 'Para' || $atributo->getAttribute('nome') == 'Cco'){
      				   $arrDestinatarios = $atributo->getElementsByTagName('valor');
      				   $numDestinatarios = 0;
      				   foreach($arrDestinatarios as $objDestinatario){
      				     if ($numDestinatarios++){
      				       $strEmailPara .= ';';
      				     }
      				     $strEmailPara .= DocumentoINT::formatarTagConteudo(DocumentoINT::$TV_TEXTO,trim($objDestinatario->nodeValue));
      				   }

								 if ($atributo->getAttribute('nome') == 'Cco'){
                   $strSinCCO = 'S';
								 }
      				   break;
      				}
      			}
      			
      			foreach($arrAtributos as $atributo){
      				if ($atributo->getAttribute('nome') == 'Assunto'){
      					 $objEmailDTO->setStrAssunto(DocumentoINT::formatarTagConteudo(DocumentoINT::$TV_TEXTO,$atributo->nodeValue));
      					 break;
      				}
      			}

      			foreach($arrAtributos as $atributo){
      				if ($atributo->getAttribute('nome') == 'Mensagem'){
      					 $objEmailDTO->setStrMensagem(DocumentoINT::formatarTagConteudo(DocumentoINT::$TV_TEXTO,$atributo->nodeValue));
      					 break;
      				}
      			}

      			$objAnexoRN = new AnexoRN();
      			$arrAnexos = array();
      			foreach($arrAtributos as $atributo){
      				if ($atributo->getAttribute('nome') == 'Anexos'){
      				  $arrAnexosEncaminhar = $atributo->getElementsByTagName('valor');
      				  foreach($arrAnexosEncaminhar as $objAnexoEncaminhar){
      				    foreach($objAnexoEncaminhar->attributes as $attr) {
      				      if ($attr->nodeName == 'id'){

      				        $strNomeArquivo = DocumentoINT::formatarTagConteudo(DocumentoINT::$TV_TEXTO,trim($objAnexoEncaminhar->nodeValue));
											$strNomeUpload = $objAnexoRN->gerarNomeArquivoTemporario();

      				        $objAnexoDTO = new AnexoDTO();
      				        $objAnexoDTO->retNumIdAnexo();      				        
      				        $objAnexoDTO->retDthInclusao();
      				        $objAnexoDTO->setNumIdAnexo($attr->nodeValue);
      				        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);      				        
              				
              				copy($objAnexoRN->obterLocalizacao($objAnexoDTO), DIR_SEI_TEMP.'/'.$strNomeUpload);
              				
              				$numTamanhoAnexo = filesize(DIR_SEI_TEMP.'/'.$strNomeUpload);
              				
              				$arrAnexos[] = array($strNomeUpload, PaginaSEI::tratarHTML($strNomeArquivo), date('d/m/Y H:i:s',time()), $numTamanhoAnexo, InfraUtil::formatarTamanhoBytes($numTamanhoAnexo));
      				      }
      				    }
      				  }
      				  $_POST['hdnAnexos'] = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrAnexos);
      				  break;
      				}
      			}
      		}
				}
	    }
      
      break;
     
    	default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

	//usa função para fazer o submit pois, caso contrário, o navegador não salva os endereços eletrônicos na caixa de digitação (auto-completar do campo deixa de funcionar)
	$arrComandos[] = '<button type="button" onclick="submeterFormulario();" accesskey="E" name="btnEnviar" value="Enviar" class="infraButton"><span class="infraTeclaAtalho">E</span>nviar</button>';
	$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" name="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $bolAcaoUploadEmail = SessaoSEI::getInstance()->verificarPermissao('email_upload_anexo');
  
  $strLinkGrupo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_email_selecionar&tipo_selecao=2&id_object=objLupaGrupo');
  $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_upload_anexo');
  $strLinkAjaxTextoPadrao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=texto_padrao_buscar_conteudo');

  $strLinkEmails = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=email_auto_completar&id_unidade='.SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $strLinkRemoverEmail = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=email_remover&id_unidade='.SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  
  $strItensSelTextoPadrao = TextoPadraoInternoINT::montarSelectSigla('null','&nbsp;',$_POST['selTextoPadrao']);

	if ($_GET['acao'] != 'responder_formulario') {
		$strItensSelDe = EmailUnidadeINT::montarSelectEmail('null', '&nbsp;', $_POST['selDe']);
	}

  $strItensSelDocumentos = '';
  if (isset($_GET['id_documento']) && $_GET['id_documento']!='' && !isset($_POST['hdnFlagEmail'])){

    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retDblIdDocumento();
    $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
    $objDocumentoDTO->retStrNomeSerie();
    $objDocumentoDTO->retStrNumero();
    $objDocumentoDTO->retStrNomeArvore();
    $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);

    $objDocumentoRN = new DocumentoRN();
    $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

    if ($objDocumentoDTO==null){
      throw new InfraException('Documento não encontrado.');
    }

    $strItensSelDocumentos = InfraINT::montarSelectArray(null,null,null,array($objDocumentoDTO->getDblIdDocumento() => DocumentoINT::formatarIdentificacaoComProtocolo($objDocumentoDTO)));
  }

  if ($_GET['acao']=='documento_email_circular'){
    $strDisplayPara = 'display:none;';
    $strDisplayCCO = 'display:none;';
    $strDisplayDocumentosProcesso = 'display:none;';
    $strDisplayAnexos = 'display:none;';
  }


  $jsArrayExtensoesArq = '';
  $bolValidarExtensaoArq = $objInfraParametro->getValor('SEI_HABILITAR_VALIDACAO_EXTENSAO_ARQUIVOS');
  if ( $bolValidarExtensaoArq == "1" ) {

    $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
    $objArquivoExtensaoDTO->retStrExtensao();
    $objArquivoExtensaoDTO->setStrSinInterface('S');
    $objArquivoExtensaoDTO->setOrdStrExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objArquivoExtensaoRN = new ArquivoExtensaoRN();
    $arrObjArquivoExtensaoDTO = $objArquivoExtensaoRN->listar($objArquivoExtensaoDTO);

    $numExt = count($arrObjArquivoExtensaoDTO);
    for ($i = 0; $i < $numExt; $i++) {
      $jsArrayExtensoesArq .= '  arrExt['.$i.'] = {nome : "'.InfraString::transformarCaixaBaixa($arrObjArquivoExtensaoDTO[$i]->getStrExtensao()).'"};'."\n";
    }
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

#lblDe {position:absolute;left:0%;top:0%;}
#selDe {position:absolute;left:0%;top:40%;width:100%;}

#divLabelPara {<?=$strDisplayPara?>}
#lblPara {position:absolute;left:0%;top:0%;}

#divPara {<?=$strDisplayPara?>}
#selPara {position:absolute;left:0%;top:0%;width:20%; visibility:hidden;}

#divCCO {<?=$strDisplayCCO?>}
#divSinCCO {position:absolute;left:0%;top:5%;}

#lblAssunto {position:absolute;left:0%;top:0%;}
#txtAssunto {position:absolute;left:0%;top:6%;width:100%;}

#lblMensagem {position:absolute;left:0%;top:15%;}
#selTextoPadrao {position:absolute;left:0%;top:21%;width:100%;}
#txaMensagem {position:absolute;left:0%;top:31%;width:100%;}

#divDocumentos {<?=$strDisplayDocumentosProcesso?>}
#lblDocumentos {position:absolute;left:0%;top:10%;}
#selDocumentos {position:absolute;left:0%;top:24%;width:95%;}
#divOpcoesDocumentos {position:absolute;left:96%;top:24%;}

#divArquivo {<?=$strDisplayAnexos?>}
#lblArquivo {position:absolute;left:0%;top:0%;width:80%;<?=$strDisplayAnexos?>}
#filArquivo {position:absolute;left:0%;top:40%;width:80%;<?=$strDisplayAnexos?>}

.remover {display:none;color:blue;float:right;font-size:0.8em;}
.select2-highlighted a {display: inline}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->adicionarStyle('js/select2/select2.css');
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->adicionarJavaScript('js/select2/select2.min.js');
PaginaSEI::getInstance()->adicionarJavaScript('js/select2/select2_locale_pt-BR.js');
PaginaSEI::getInstance()->abrirJavaScript();

?>
//<script>

function emailTokenizer(input, selection, selectCallback, opts) {
	var original = input, // store the original so we can compare and know if we need to tell the search to update its text
			dupe = false, // check for whether a token we extracted represents a duplicate selected choice
			token, // token
			index, // position at which the separator was found
			i, l, // looping variables
			separator; // the matched separator
	while (true) {
		index = -1;

		for (i = 0, l = opts.tokenSeparators.length; i < l; i++) {
			separator = opts.tokenSeparators[i];
			index = input.indexOf(separator);
			if (index >= 0) {
				var a=input.indexOf('"');
				if (a==-1 || a>index ) break;
				var b=input.indexOf('"',a+1);
				if (b==-1 || b<index)	break;
				index = input.indexOf(separator,b);
			}
		}

		if (index < 0) break; // did not find any token separator in the input string, bail

		token = input.substring(0, index);
		input = input.substring(index + separator.length);

		if (input.length>0 && input.substr(-1,1)!=separator) input=input+separator;

		if (token.length > 0) {
			token = opts.createSearchChoice.call(this, token, selection);
			if (token !== undefined && token !== null && opts.id(token) !== undefined && opts.id(token) !== null) {
				dupe = false;
				for (i = 0, l = selection.length; i < l; i++) {
					if (opts.id(token) === opts.id(selection[i])) {
						dupe = true; break;
					}
				}

				if (!dupe) selectCallback(token);
			}
		}
	}

	if (original!==input) return input;
}

function removeItem(event,divId){
	event=event||window.event;
  event.stopPropagation();
  event.preventDefault();

  var el=$('#'+divId);
  var html=el.html();
  html=html.substring(0,html.indexOf('<a '));
  html=html.replace(/<span[^>]*>(.*)<\/span>/,'$1');
  $.ajax({
    type:"POST",
    url: "<?=$strLinkRemoverEmail;?>",
    dataType: "xml",
    data: "email="+encodeURIComponent(html)
  });
//  el.parent('li').remove();
  var hdn=$('#hdnDestinatario');
  var term=hdn.select2('container').find('input').val();
  hdn.select2('close');
  hdn.select2('search',term);

}

function format(result, container, query, escapeMarkup) {
  var markup=[];
  Select2.util.markMatch(result.text, query.term, markup, escapeMarkup);
  return markup.join("")+"<a href='#' class='remover' onmousedown='removeItem(event,\""+container.attr('id')+"\");'>Esquecer</a>";;
}

function autocompletarEmails(input) {
  $(input).select2({
    tags: true,
    formatResult: format,

    minimumInputLength: 1,
    formatInputTooShort: "",
    separator:';',
		tokenizer: emailTokenizer,
    tokenSeparators: [";",","],
    createSearchChoice: function (term, data) {
      if (infraValidarEmail(infraTrim(term))) return { id:infraTrim(term),text:infraTrim(term) };
    },
    initSelection: function (element, callback) {
      var data = [];
      var emails = element.val().split(";");
      $(emails).each(function () {
        data.push({
          id: this.toString(),
          text: this.toString()
        });
      });
      $(element).val('');
      callback(data);
    },
    multiple: true,
    ajax: {
      type:"POST",
      url: "<?=$strLinkEmails;?>",
      dataType: "json",
      data: function (term, page) {
        return {
          palavras_pesquisa: infraTrim(term)
        };
      },
      results: function (data, page) {
        return {
          results: data
        };
      }
    }
  });
}

$(document).ready(function () {
  autocompletarEmails("#hdnDestinatario");

  $("#hdnDestinatario").select2("container").find("ul.select2-choices").sortable({
    containment: "parent",
    start: function () {
      $("#hdnDestinatario").select2("onSortStart");
    },
    update: function () {
      $("#hdnDestinatario").select2("onSortEnd");
    }
  });

});
		    
var objLupaGrupo = null;
var objAjaxTextoPadrao = null;
var objLupaDocumentos = null;
var objUpload = null;


function inicializar(){
  
  <?if ($bolEnvioOK){ ?>
    <?if ($_GET['acao']=='documento_email_circular'){?>
      alert('Correspondências eletrônicas enviadas.\n\nVerifique posteriormente a caixa postal da unidade para certificar-se de que não ocorreram problemas na entrega.\n\n<?=PaginaSEI::formatarParametrosJavaScript($strMsgComplementar,false)?>');
    <?}else if ($_GET['acao']!='responder_formulario'){?>
       alert('E-mail enviado.\n\nVerifique posteriormente a caixa postal da unidade para certificar-se de que não ocorreram problemas na entrega.\n\n<?=PaginaSEI::formatarParametrosJavaScript($strMsgComplementar,false)?>');
    <?}?>
    self.setTimeout('window.close()',1000);
  <?}?>
  
  if(document.getElementById('selDe').options.length == '2' && document.getElementById('selDe').value == 'null'){
    document.getElementById('selDe').options[1].selected = true;
	}

  infraEfeitoTabelas();
  
  //Lupa Grupo
  objLupaGrupo = new infraLupaSelect('selPara','hdnPara','<?=$strLinkGrupo?>');
  
  objLupaGrupo.finalizarSelecao = function(){
    var arrEmail=[];
    $('#selPara option').each(function(){
      var email=$(this).val();
      if (email!="") arrEmail.push(email);
    });
    $('#hdnDestinatario').val(arrEmail.join(';'));
    autocompletarEmails("#hdnDestinatario");
  };
  
  objAjaxTextoPadrao = new infraAjaxComplementar('selTextoPadrao','<?=$strLinkAjaxTextoPadrao?>');
  objAjaxTextoPadrao.prepararExecucao = function(){
    return 'id_texto_padrao_interno='+document.getElementById('selTextoPadrao').value;
  };
  objAjaxTextoPadrao.processarResultado = function(arr) {
    if (arr != null) {
      infraInserirCursor(document.getElementById('txaMensagem'), arr['Conteudo']);
    }
  };
  objAjaxTextoPadrao.executar();

  objLupaDocumentos	= new infraLupaSelect('selDocumentos','hdnDocumentos','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_selecionar&tipo_selecao=2&id_object=objLupaDocumentos&id_procedimento='.$_GET['id_procedimento'].'&tipo_selecao_documento='.DocumentoINT::$TSD_EMAIL)?>');

  //Anexos
  objUpload = new infraUpload('frmAnexos','<?=$strLinkAnexos?>');

  <? if ($bolValidarExtensaoArq=='1'){ ?>
  objUpload.validar = function(){
    var i = 0;
    var arrExt = [];
    var oFile = document.getElementById('filArquivo');

    if (oFile.length==0) {
      return false;
    }
    var nomeArquivo;
    if(oFile.files==undefined){
      //ie<10
      nomeArquivo=oFile.value.replace('C:\\\\fakepath\\\\', '');
    } else {
      nomeArquivo = oFile.files[0].name;
    }

    if (nomeArquivo.indexOf('&#')!= -1) {
      alert('Nome do anexo possui caracteres especiais.');
      return false;
    }

    <?=$jsArrayExtensoesArq?>

    if (arrExt.length==0) {
      alert('Nenhuma extensão de arquivo permitida foi cadastrada.');
      return false;
    }

    nomeArquivo = nomeArquivo.replace(/^.*\./, '').toLowerCase();

    for(i=0; i < arrExt.length; i++){
      if (nomeArquivo == arrExt[i].nome) {
        break;
      }
    }

    if (i == arrExt.length){

      var msg = 'O arquivo selecionado não é permitido.\n\nSomente são permitidos arquivos com as extensões: ';
      for(i=0; i < arrExt.length; i++) {
        if (i){
          msg += ', ';
        }
        msg += arrExt[i].nome;
      }
      msg += '.';

      alert(msg);

      return false;
    }
    return true;
  }
  <? } ?>

  objUpload.finalizou = function(arr){
   	objTabelaAnexos.adicionar([arr['nome_upload'],arr['nome'],arr['data_hora'],arr['tamanho'],infraFormatarTamanhoBytes(arr['tamanho'])]);
  }

  var objTabelaAnexos = new infraTabelaDinamica('tblAnexos','hdnAnexos',false,true);
  objTabelaAnexos.gerarEfeitoTabela=true;

	if ('<?=$strSinOuvidoriaTipoProcedimento?>' == 'S') {
		document.getElementById('txaMensagem').focus();
	}else {
		if (document.getElementById('selDe').value=='null') {
			document.getElementById('selDe').focus();
		} else {
      if ('<?=$_GET['acao']?>'=='documento_email_circular'){
        document.getElementById('txtAssunto').focus();
      }else{
        $('.select2-input').focus();
      }
		}
	}
}

function formatarDestinatarios(){

  var arrEmail = $('#hdnDestinatario').val().split(';');
  var strFormatado = '';
  for(var i=0;i < arrEmail.length;i++){
    arrEmail[i] = infraTrim(arrEmail[i]);
    if (arrEmail[i]!=''){
      if (strFormatado == ''){
        strFormatado = arrEmail[i];
      }else{
        strFormatado = strFormatado  + ';' + arrEmail[i];
      }
    }    
  }

  return strFormatado;
}

function validarDestinatariosEmail(){
  
  var strDestinatarios = formatarDestinatarios();
  
  var arrEmail = strDestinatarios.split(';');
  for(var i=0;i < arrEmail.length;i++){
    if (!infraValidarEmail(arrEmail[i])){   
      alert('Endereço eletrônico "'+ arrEmail[i] + '" inválido.');  
      return false;
    }
  }
  
  return true;  
}

function validarEnvio() {
  
  if (document.getElementById('selDe').value == 'null') {
    alert('Remetente do email não informado.');
    document.getElementById('selDe').focus();
    return false;
  }

  if ('<?=$_GET['acao']?>'!='documento_email_circular') {
    if ($("#hdnDestinatario").val()=='') {
      alert('Nenhum destinatário para o email informado.');
      $('.select2-input').focus();
      return false;
    }

    if (!validarDestinatariosEmail()) {
      $('.select2-input').focus();
      return false;
    }
  }

  if (infraTrim(document.getElementById('txtAssunto').value)=='') {
    alert('Informe o Assunto.');
    document.getElementById('txtAssunto').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaMensagem').value)=='') {
    alert('Informe a Mensagem.');
    document.getElementById('txaMensagem').focus();
    return false;
  }
    
  return true;
}

function selecionarGrupo(){
	
  var arrEmail = $('#hdnDestinatario').val().split(';');
  var sel = document.getElementById('selPara');
	sel.length = 0;
  for(var i in arrEmail) {
    for (var j=0; j < sel.length; j++){
      if (arrEmail[i] == sel.options[j].value){
        break;
      }
    }
    if (j == sel.length){
      infraSelectAdicionarOption(sel,arrEmail[i],arrEmail[i]);
    }
  }

	objLupaGrupo.selecionar(700,500);
}

function submeterFormulario(){	
	if (validarEnvio()){
	
	  infraExibirAviso();
	  
    var arrBotoesEnviar = document.getElementsByName('btnEnviar');
    for(var i=0; i < arrBotoesEnviar.length; i++){
       arrBotoesEnviar[i].disabled = true;
    } 
	    
    document.getElementById('frmEmail').submit();
  }
}

function finalizar(){
  <?if ($bolEnvioOK){ ?>
     <? if ($_GET['arvore'] == '1'){ ?>
       if (window.opener!=null){

				 <?if ($_GET['acao']=='documento_email_circular'){?>
				   window.opener.parent.document.getElementById('ifrArvore').src = '<?=$strLinkRetorno?>';
				 <?}else{?>
				   window.opener.location = '<?=$strLinkRetorno?>';
				 <?}?>

       }
     <?}?>
  <?}?>
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();" onunload="finalizar();"');
?>
<form id="frmEmail" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>" style="display:inline;">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divDe" class="infraAreaDados" style="height:5em;">
  <label id="lblDe" for="selDe" accesskey="" class="infraLabelObrigatorio" >De:</label>
  <select id="selDe" name="selDe" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelDe?>
  </select>
  </div>

  <div id="divLabelPara" class="infraAreaDados" style="height:2em;">
    <label id="lblPara" for="txaPara" accesskey="" class="infraLabelObrigatorio">Para:</label>
  </div>

  <div id="divPara" class="infraAreaDadosDinamica">
  <p style="margin-top: 0px;">
    <input type="hidden" name="hdnDestinatario" id="hdnDestinatario" style="width:95%;" value="<?= PaginaSEI::tratarHTML($strEmailPara); ?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    &nbsp;
    <img id="imgSelecionarGrupo" onclick="selecionarGrupo();" src="<?=PaginaSEI::getInstance()->getIconeGrupo()?>" title="Selecionar Grupos de E-mail" alt="Selecionar Grupos de E-mail" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </p>

  <select id="selPara" name="selPara" size="3" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelPara?>
  </select>
  </div>

  <div id="divCCO" class="infraAreaDados" style="height:3em;">
    <div id="divSinCCO" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinCCO" name="chkSinCCO" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinCCO)?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinCCO" for="chkSinCCO" accesskey="" class="infraLabelCheckbox" >Enviar com cópia oculta</label>
    </div>
  </div>

  <div id="divAssuntoMensagem" class="infraAreaDados" style="height:31em;">

	<label id="lblAssunto" for="txtAssunto" accesskey="" class="infraLabelObrigatorio">Assunto:</label>
  <input type="text" id="txtAssunto" name="txtAssunto" class="infraText" value="<?=PaginaSEI::tratarHTML($objEmailDTO->getStrAssunto())?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblMensagem" for="txaMensagem" accesskey="" class="infraLabelObrigatorio">Mensagem:</label>
  <select id="selTextoPadrao" name="selTextoPadrao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
  <?=$strItensSelTextoPadrao?>
  </select>    
  <textarea id="txaMensagem" name="txaMensagem" rows="12" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" onselect="infraPosicionarCursor(this);" onclick="infraPosicionarCursor(this);" onkeyup="infraPosicionarCursor(this);"><?=PaginaSEI::tratarHTML($objEmailDTO->getStrMensagem())?></textarea>

  <input type="hidden" id="hdnPara" name="hdnPara" value="<?=PaginaSEI::tratarHTML($_POST['hdnPara'])?>" />
	<input type="hidden" id="hdnAnexos" name="hdnAnexos" value="<?=$_POST['hdnAnexos']?>"/>
  <input type="hidden" id="hdnIdDocumentoCircular" name="hdnIdDocumentoCircular" value="<?=$strIdDocumentoCircular?>"/>
	<input type="hidden" id="hdnFlagEmail" name="hdnFlagEmail" value="1" />

  </div>

  <div id="divDocumentos" class="infraAreaDados" style="height:12em;">
    <label id="lblDocumentos" for="selDocumentos" class="infraLabelOpcional">Documentos do processo (clique na lupa para selecionar):</label>
    <select id="selDocumentos" name="selDocumentos" multiple="multiple" size="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'4':'5'?>" class="infraSelect"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelDocumentos?>
    </select>
    <div id="divOpcoesDocumentos">
      <img id="imgLupaDocumentos" onclick="objLupaDocumentos.selecionar(700,550);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Documentos" title="Selecionar Documentos" class="infraImg"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <br />
      <img id="imgExcluirDocumentos" onclick="objLupaDocumentos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Documentos Selecionados" title="Remover Documentos Selecionados" class="infraImgNormal"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
    <input type="hidden" id="hdnDocumentos" name="hdnDocumentos" value="<?=$_POST['hdnDocumentos']?>" />
  </div>

</form>

<form id="frmAnexos" style="display:inline;">
  <? if ($bolAcaoUploadEmail){ ?>
  <div id="divArquivo" class="infraAreaDados">
    <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelInputFile">Anexar Arquivo...</label>
    <input type="file" id="filArquivo" name="filArquivo" class="infraInputFile" size="50" onchange="objUpload.executar();" tabindex="1000" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
  </div>
</form>
  <?}?> 
     <div id="divAnexos" >    
     <table id="tblAnexos" name="tblAnexos" class="infraTable" style="width:100%">
        <caption class="infraCaption"><?=PaginaSEI::getInstance()->gerarCaptionTabela("Anexos",0)?></caption>
    		<tr>
    			<th style="display:none;">ID</th>
    			<th width="50%" class="infraTh">Nome</th>
    			<th class="infraTh" align="center">Data</th>
    			<th style="display:none;">Bytes</th>
    			<th width="17%" class="infraTh" align="center">Tamanho</th>
    			<!--
    			<th width="10%" class="infraTh" align="center">Usuário</th>
    			<th width="10%" class="infraTh" align="center">Unidade</th>
    			-->
    			<th width="10%" class="infraTh">Ações</th>
    		</tr>
      </table>
      <!-- campo hidden correspondente (hdnAnexos) deve ficar no outro form -->
    </div>
<? 
PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos,true);
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>