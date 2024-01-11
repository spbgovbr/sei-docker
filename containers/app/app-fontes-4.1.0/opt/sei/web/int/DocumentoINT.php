<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/07/2008 - criado por mga
 *
 * Versão do Gerador de Código: 1.21.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoINT extends InfraINT {

	public static $TV_HTML = 'H';
	public static $TV_TEXTO = 'T';
	public static $LINK_VISUALIZACAO_CSS='<style type="text/css" >.lnkseisel{background-color: yellow;}</style>';
  public static $LINK_VISUALIZACAO_JS='<script type="text/javascript">document.addEventListener(\'click\',function(ev){if(ev.target.className.indexOf(\'ancora_sei\')!==-1){var b=document.getElementsByClassName(\'lnkseisel\');if(b.length>0){for(var a=b.length;a;)b[--a].className=\'ancora_sei\';}ev.target.className=\'ancora_sei lnkseisel\';}});</script>';

  //Tipo Seleção Documento
  public static $TSD_EMAIL = 'EMAIL';
  public static $TSD_PDF = 'PDF';
  public static $TSD_ZIP = 'ZIP';

  public static function formatarIdentificacao($objDocumentoDTO){
    $strIdentificacao = $objDocumentoDTO->getStrNomeSerie().' '.$objDocumentoDTO->getStrNumero();

    if ($objDocumentoDTO->isSetStrNomeArvore() && $objDocumentoDTO->getStrNomeArvore()!=null){
      $strIdentificacao .=  ' '.$objDocumentoDTO->getStrNomeArvore();
    }
    return PaginaSEI::tratarHTML($strIdentificacao);
  }

  public static function formatarIdentificacaoComProtocolo($objDocumentoDTO){
    return PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()).' - '.self::formatarIdentificacao($objDocumentoDTO);
  }

  public static function verificarDocumentoRecebidoDuplicado($dtaElaboracao,$numIdSerie,$numNumero){
		$objDocumentoDTO = new DocumentoDTO();
		$objDocumentoDTO->retDblIdDocumento();
		$objDocumentoDTO->retStrProtocoloDocumentoFormatado();
		$objDocumentoDTO->setDtaGeracaoProtocolo(trim($dtaElaboracao));
		$objDocumentoDTO->setNumIdSerie(trim($numIdSerie));
		$objDocumentoDTO->setStrNumero(trim($numNumero));
		$objDocumentoDTO->setStrStaProtocoloProtocolo(ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);

		$objDocumentoRN = new DocumentoRN();
		$arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO);

		if (count($arrObjDocumentoDTO)){
			return $arrObjDocumentoDTO[0];
		}
		return null;
	}

	public static function selecionarIconeAnexo($strNomeAnexo){

		$ext = explode('.',$strNomeAnexo);

		if (count($ext)>1){

			$ext = strtolower($ext[count($ext)-1]);

			switch($ext){
				case 'doc':
        case 'docx':
					return Icone::DOCUMENTO_WORD;

				case 'jpeg':	
				case 'jpg':
				case 'gif':
				case 'bmp':
				case 'png':  
					return Icone::DOCUMENTO_IMAGEM;

				case 'ppt':
        case 'pps':
					return Icone::DOCUMENTO_POWERPOINT;

				case 'xls':
				case 'xlsx':
					return Icone::DOCUMENTO_EXCEL;

				case 'txt':
					return Icone::DOCUMENTO_TXT;

				case 'pdf':
					return Icone::DOCUMENTO_PDF;

				case 'exe':
				case 'com':
					return Icone::DOCUMENTO_APLICATIVO;

				case 'zip':
          return Icone::DOCUMENTO_ZIP;

        case 'rar':
					return Icone::DOCUMENTO_RAR;

				case 'ods':
				  return Icone::DOCUMENTO_ODS;
				  
				case 'odt':
				  return Icone::DOCUMENTO_ODT;

				case 'odp':
				  return Icone::DOCUMENTO_ODP;
				  
				case 'odg':
				  return Icone::DOCUMENTO_ODG;
				  
				case 'html':
				case 'htm':
					return Icone::DOCUMENTO_HTML;

				case 'avi':
				case 'swf':
				case 'wmv':
				case 'mp4':  
					return Icone::DOCUMENTO_VIDEO;

				case 'mp3':
				case 'wma':
					return Icone::DOCUMENTO_AUDIO;
			}
		}
		return null;
	}

	public static function montarIdentificacaoArvore($objDocumentoDTO){
    $strIdentificacaoDocumento = $objDocumentoDTO->getStrNomeSerie();

    if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

      $bolDetalhes = false;
      if ($objDocumentoDTO->getStrNumero() != null) {
        $strIdentificacaoDocumento .= ' '.$objDocumentoDTO->getStrNumero();
        $bolDetalhes = true;
      }

      if ($objDocumentoDTO->isSetStrNomeArvore() && $objDocumentoDTO->getStrNomeArvore() != null) {
        $strIdentificacaoDocumento .= ' '.$objDocumentoDTO->getStrNomeArvore();
        $bolDetalhes = true;
      }

      if ($bolDetalhes){
        $strIdentificacaoDocumento .= ' ('.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().')';
      }else {
        $strIdentificacaoDocumento .= ' '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado();
      }

    }else {

      if ($objDocumentoDTO->getStrNumero() != null) {
        $strIdentificacaoDocumento .= ' '.$objDocumentoDTO->getStrNumero();
      }

      if ($objDocumentoDTO->isSetStrNomeArvore() && $objDocumentoDTO->getStrNomeArvore() != null) {
        $strIdentificacaoDocumento .= ' '.$objDocumentoDTO->getStrNomeArvore();
      }

      $strIdentificacaoDocumento .= ' ('.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().')';

    }

		return $strIdentificacaoDocumento;
	}

	public static function montarTooltipEmail($parObjDocumentoDTO, &$bolFlagCCO){

		$strRet = '';
		$bolFlagCCO = false;

    $strConteudo = $parObjDocumentoDTO->getStrConteudo();

		if (!InfraString::isBolVazia($strConteudo) && substr($strConteudo,0,5) == '<?xml'){

			$objXml = new DomDocument('1.0','iso-8859-1');

			$objXml->loadXML($strConteudo);

			$arrAtributos = $objXml->getElementsByTagName('atributo');
			
			foreach($arrAtributos as $atributo){
				if ($atributo->getAttribute('nome') == 'Data'){
					 $strRet .= utf8_decode($atributo->getAttribute('titulo')).': ';
					 $strRet .= self::formatarTagConteudo(self::$TV_TEXTO,$atributo->nodeValue).'\n';
					 break;
				}
			}
			
			foreach($arrAtributos as $atributo){
				if ($atributo->getAttribute('nome') == 'De'){
					 $strRet .= utf8_decode($atributo->getAttribute('titulo')).': ';
					 $strRet .= self::formatarTagConteudo(self::$TV_TEXTO,$atributo->nodeValue).'\n';
					 break;
				}
			}

			foreach($arrAtributos as $atributo){
				if ($atributo->getAttribute('nome') == 'Para'){
					 $strRet .= utf8_decode($atributo->getAttribute('titulo')).': ';
				   $arrDestinatarios = $atributo->getElementsByTagName('valor');
				   $numDestinatarios = 0;
				   foreach($arrDestinatarios as $objDestinatario){
				     if ($numDestinatarios++){
				       $strRet .= '          ';
				     }
				     $strRet .= self::formatarTagConteudo(self::$TV_TEXTO,trim($objDestinatario->nodeValue)).'\n';
				   }
				   break;
				}
			}

      foreach($arrAtributos as $atributo){
        if ($atributo->getAttribute('nome') == 'Cco'){
          $strRet .= utf8_decode($atributo->getAttribute('titulo')).': ';
          $arrDestinatarios = $atributo->getElementsByTagName('valor');
          $numDestinatarios = 0;
          foreach($arrDestinatarios as $objDestinatario){
            if ($numDestinatarios++){
              $strRet .= '          ';
            }
            $strRet .= self::formatarTagConteudo(self::$TV_TEXTO,trim($objDestinatario->nodeValue)).'\n';
          }
          $bolFlagCCO = true;
          break;
        }
      }

			
			foreach($arrAtributos as $atributo){
				if ($atributo->getAttribute('nome') == 'Assunto'){
					 $strRet .= utf8_decode($atributo->getAttribute('titulo')).': ';
					 $strRet .= self::formatarTagConteudo(self::$TV_TEXTO,$atributo->nodeValue).'\n';
					 break;
				}
				
			}
		}
		return $strRet;
	}
	
	public static function montarTooltipAssinatura($parObjDocumentoDTO){
	  $strRet = ($parObjDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO) ? 'Assinado por:'."\n" : 'Autenticado por:'."\n";
	  $arrObjAssinaturaDTO = $parObjDocumentoDTO->getArrObjAssinaturaDTO();
	  $numAssinaturas = count($arrObjAssinaturaDTO);
	  for($i=0;$i<$numAssinaturas;$i++){
	    $objAssinaturaDTO = $arrObjAssinaturaDTO[$i];
	    if ($i){
	      $strRet .= "\n\n";
      }
	    $strRet .= $objAssinaturaDTO->getStrNome()."\n".$objAssinaturaDTO->getStrTratamento();
	    if ($objAssinaturaDTO->isSetStrSiglaUnidade()){
        $strRet .= "\n".$objAssinaturaDTO->getStrSiglaUnidade();
      }
	  }
	  return PaginaSEI::tratarHTML($strRet);
	}

	public static function montarTooltipAndamento($strTexto){
		return str_replace("\r\n", "\\n", str_replace("'", '\'', str_replace('"', '\"', str_replace('\\','\\\\',$strTexto))));
	}
	
	public static function formatarTagConteudo($strTipoVisualizacao, $tag){
    $ret = $tag;
    if ($ret != '') {
      $ret = utf8_decode($tag);
      if ($strTipoVisualizacao == self::$TV_HTML) {
        //$ret = nl2br(str_replace(' ','&nbsp;',InfraPagina::tratarHTML($ret)));
        $ret = nl2br(InfraPagina::tratarHTML($ret));
      }
    }
		return $ret;
	}

	public static function formatarExibicaoConteudo($strTipoVisualizacao, $strConteudo, $objInfraSessao=null, $strLinkDownload=null){

		$strResultado = '';

		if (!InfraString::isBolVazia($strConteudo)){

			if (substr($strConteudo,0,5) != '<?xml'){
				$strResultado = $strConteudo;
			}else{

				//die($strConteudo);

				/*
				 $strConteudo = '<?xml version="1.0"?>
				 <documento>
				 <atributo id="" tipo="" nome="" titulo="Atributo A">nomeA</atributo>
				 <atributo id="" tipo="" nome="" titulo="Atributo B">nomeB</atributo>
				 <atributo id="" tipo="" nome="" titulo="Atributo C">
				 <valores>
				 <valor id="" tipo="" nome="" titulo="Valor C1">nomeC1</valor>
				 <valor id="" tipo="" nome="" titulo="Valor C2">nomeC2</valor>
				 </valores>
				 </atributo>
				 <atributo id="" tipo="" nome="" titulo="Atributo D">
				 <valores id="" tipo="" nome="" titulo="Valores D1">
				 <valor id="" tipo="" nome="" titulo="Valor D1V1">D1V1</valor>
				 <valor id="" tipo="" nome="" titulo="Valor D1V2">D1V2</valor>
				 <valor id="" tipo="" nome="" titulo="Valor D1V3">D1V3</valor>
				 </valores>
				 <valores id="" tipo="" nome="" titulo="Valores D2">
				 <valor id="" tipo="" nome="" titulo="Valor D2V1">D2V1</valor>
				 <valor id="" tipo="" nome="" titulo="Valor D2V2">D2V2</valor>
				 <valor id="" tipo="" nome="" titulo="Valor D2V3">D2V3</valor>
				 </valores>
				 <valores id="" tipo="" nome="" titulo="Valores D3">
				 <valor id="" tipo="" nome="" nome="d3v1" titulo="Valor D3V1">D3V1</valor>
				 <valor id="" tipo="" nome="" titulo="Valor D3V2" ocultar="S">D3V2</valor>
				 <valor id="" tipo="" nome="" titulo="Valor D3V3">D3V3</valor>
				 </valores>
				 </atributo>
				 </documento>';

				$strConteudo = '<?xml version="1.0" encoding="iso-8859-1"?>
        <formulario>
        <atributo id="" nome="" tipo="OPCOES">
        <rotulo>Atributo A</rotulo>
        <dominio id="" valor="">Opção X</dominio>
        </atributo>
        <atributo id="" nome="" tipo="TEXTO_MASCARA">
        <rotulo>Atributo B</rotulo>
				<valor>Valor B</valor>
        </atributo>
        <atributo id="" nome="" tipo="SINALIZADOR">
        <rotulo>Atributo C</rotulo>
        <valor>S</valor>
        </atributo>
        <atributo id="" nome="" tipo="LISTA">
        <rotulo>Atributo D</rotulo>
        <dominio id="" valor="">Item X</dominio>
        </atributo>
        </formulario>';
				*/


        //internamente o DOM utiliza UTF-8 mesmo passando iso-8859-1
        //por isso e necessario usar utf8_decode
        $objXml = new DomDocument('1.0','iso-8859-1');

				$objXml->loadXML($strConteudo);

        if ($strTipoVisualizacao == self::$TV_HTML) {

          $strNovaLinha = '<br />' . "\n";
          $strItemInicio = '<b>';
          $strItemFim = '</b>';
          $strSubitemInicio = '<i>';
          $strSubitemFim = '</i>';
          $strEspaco = '&nbsp;';

        } else {

          $strNovaLinha = "\n";
          $strItemInicio = '';
          $strItemFim = '';
          $strSubitemInicio = '';
          $strSubitemFim = '';
          $strEspaco = ' ';
        }

        if ($objXml->documentElement->nodeName == 'documento') {

          $arrAtributos = $objXml->getElementsByTagName('atributo');

          $strResultado = '';

          if ($objInfraSessao != null) {
            $bolAcaoDownload = $objInfraSessao->verificarPermissao('documento_download_anexo');
          }

          foreach($arrAtributos as $atributo){

            $arrValores = $atributo->getElementsByTagName('valores');

            if ($arrValores->length==0){
              //não mostra item que não possua valor
              if (!InfraString::isBolVazia($atributo->nodeValue) && $atributo->getAttribute('ocultar')!='S'){
                $strResultado .= $strNovaLinha.$strItemInicio.self::formatarTagConteudo($strTipoVisualizacao,$atributo->getAttribute('titulo')).$strItemFim.': '.$strNovaLinha.$strEspaco.$strEspaco.self::formatarTagConteudo($strTipoVisualizacao,$atributo->nodeValue);
                $strResultado .= $strNovaLinha;
              }
            }else{

              if ($atributo->getAttribute('titulo')!=''){
                $strResultado .= $strNovaLinha.$strItemInicio.self::formatarTagConteudo($strTipoVisualizacao,$atributo->getAttribute('titulo')).$strItemFim.':';
              }

              foreach($arrValores as $valores){

                if ($valores->getAttribute('titulo')!=''){
                  $strResultado .= $strNovaLinha.$strEspaco.$strEspaco.$strSubitemInicio.self::formatarTagConteudo($strTipoVisualizacao,$valores->getAttribute('titulo')).':'.$strSubitemFim;
                }

                $arrValor = $valores->getElementsByTagName('valor');

                foreach($arrValor as $valor){

                  if ($valor->getAttribute('ocultar')!='S') {

                    $strResultado .= $strNovaLinha . $strEspaco . $strEspaco . $strEspaco . $strEspaco;

                    if ($valor->getAttribute('titulo') != '') {
                      $strResultado .= self::formatarTagConteudo($strTipoVisualizacao, $valor->getAttribute('titulo')) . ': ';
                    }

                    if ($valor->getAttribute('tipo') == 'ANEXO') {
                      if ($objInfraSessao == null || $strLinkDownload == null) {
                        $strResultado .= self::formatarTagConteudo($strTipoVisualizacao, $valor->nodeValue);
                      } else {
                        if ($bolAcaoDownload) {
                          $objAnexoDTO = new AnexoDTO();
                          $objAnexoDTO->setNumIdAnexo($valor->getAttribute('id'));
                          $objAnexoRN = new AnexoRN();
                          if ($objAnexoRN->contarRN0734($objAnexoDTO) > 0) {
                            $strResultado .= '<a href="' . $objInfraSessao->assinarLink($strLinkDownload . '&id_anexo=' . $valor->getAttribute('id')) . '" target="_blank" class="ancoraVisualizacaoDocumento">' . self::formatarTagConteudo($strTipoVisualizacao, $valor->nodeValue) . '</a>';
                          } else {
                            $strResultado .= '<a href="javascript:void(0);" onclick="alert(\'Este anexo foi excluído.\');"  class="ancoraVisualizacaoDocumento">' . self::formatarTagConteudo($strTipoVisualizacao, $valor->nodeValue) . '</a>';
                          }
                        } else {
                          $strResultado .= self::formatarTagConteudo($strTipoVisualizacao, $valor->nodeValue);
                        }
                      }
                    } else {
                      $strResultado .= self::formatarTagConteudo($strTipoVisualizacao, $valor->nodeValue);
                    }
                  }
                }

                if ($arrValor->length>1){
                  $strResultado .= $strNovaLinha;
                }
              }
              $strResultado .= $strNovaLinha;
            }
          }

        }else if ($objXml->documentElement->nodeName == 'formulario') {

          $arrAtributos = $objXml->getElementsByTagName('atributo');

          $strResultado = '';

          foreach($arrAtributos as $atributo){

            $strStaTipo = $atributo->getAttribute('tipo');

            $strRotulo = utf8_decode($atributo->getElementsByTagName('rotulo')->item(0)->nodeValue);

            if ($strStaTipo==AtributoRN::$TA_INFORMACAO){

              $strResultado .= $strNovaLinha.self::formatarRotulo($strTipoVisualizacao, $strRotulo, false);

            }else {

              $strResultado .= $strNovaLinha . $strItemInicio . self::formatarRotulo($strTipoVisualizacao, $strRotulo) . $strItemFim;

              $strResultado .= $strNovaLinha.$strEspaco.$strEspaco;

              if ($strStaTipo == AtributoRN::$TA_LISTA || $strStaTipo == AtributoRN::$TA_OPCOES) {

                $valor = $atributo->getElementsByTagName('dominio');
                if ($valor->length == 1) {
                  $strResultado .= self::formatarTagConteudo($strTipoVisualizacao, $valor->item(0)->nodeValue);
                } else {
                  $strResultado .= '-';
                }

              } else if ($strStaTipo == AtributoRN::$TA_SINALIZADOR) {

                $valor = $atributo->getElementsByTagName('valor');
                if ($valor->length == 1) {
                  if ($valor->item(0)->nodeValue == 'S') {
                    $strResultado .= 'Sim';
                  } else if ($valor->item(0)->nodeValue == 'N') {
                    $strResultado .= 'Não';
                  } else {
                    $strResultado .= '-';
                  }
                }

              } else {

                $valor = $atributo->getElementsByTagName('valor');
                if ($valor->length == 1) {
                  $strResultado .= self::formatarTagConteudo($strTipoVisualizacao, $valor->item(0)->nodeValue);
                } else {
                  $strResultado .= '-';
                }

              }
            }

            $strResultado .= $strNovaLinha;
          }

        }
			}
		}
		return $strResultado;
	}

	public static function obterAtributoConteudo($strConteudo, $strNomeAtributo){

		if (!InfraString::isBolVazia($strConteudo) && substr($strConteudo,0,5) == '<?xml'){

			$objXml = new DomDocument('1.0','iso-8859-1');

			$objXml->loadXML($strConteudo);

			$arrAtributos = $objXml->getElementsByTagName('atributo');
			foreach($arrAtributos as $atributo){
				if ($atributo->getAttribute('nome') == $strNomeAtributo){
					return self::formatarTagConteudo(self::$TV_TEXTO,$atributo->nodeValue);
				}
			}
		}

		return null;
	}

	public static function montarTitulo($objDocumentoDTO){
	  return SessaoSEI::getInstance()->getStrSiglaSistema().'/'.SessaoSEI::getInstance()->getStrSiglaOrgaoSistema().' - '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' - '.$objDocumentoDTO->getStrNomeSerie();
	}
	
	public static function limparHtml($strHtml){


    $substituicoes = array (
        '@<head[^>]*?>.*?</head>@si'                                            => '',       // Strip out javascript
        '@<div class="Micron"[^>]*?>.*?</div>@si'                               => '',       // espaçamento de seção
        '@<div id="divVersao"[^>]*?>.*?</div>@si'                               => '',       // rodape de versão
        EditorRN::$REGEX_SPAN_SCAYT_SELECTION                                   => '',       // sujeira do scayt
        EditorRN::$REGEX_SPAN_SCAYT                                             => '$4',     // sujeira do scayt
        '@<[\/\!]*?[^<>]*?>@si'                                                 => '',       // Strip out HTML tags
      //'@([\r\n])[\s]+@'                                                     => '',       // Strip out white space
        '@&(quot|#34);@i'                                                       => '"',      // Replace HTML entities
        '@&(amp|#38);@i'                                                        => '&',      // Ampersand &
        '@&(lt|#60);@i'                                                         => '<',      // Less Than <
        '@&(gt|#62);@i'                                                         => '>',      // Greater Than >
      //'@&(ordf|#170);@i'                                                    => 'ª',
      //'@&(ordm|#186);@i'                                                    => 'º',
      //'@&(sect|#167);@i'                                                    => '§',
        '@&(nbsp|#160);@i'                                                      => ' ',      // Non Breaking Space
        '@&(iexcl|#161);@i'                                                     => chr(161), // Inverted Exclamation point
        '@&(cent|#162);@i'                                                      => chr(162), // Cent
        '@&(pound|#163);@i'                                                     => chr(163), // Pound
        '@&(copy|#169);@i'                                                      => chr(169), // Copyright
        '@&(reg|#174);@i'                                                       => chr(174), // Registered
        //'@&#(d+);@e'                                                            => 'chr()',  // Evaluate as php
        '@<b[^>]*?>.*?</b\s*>@si'                                               => '',       // negrito
        '@<i[^>]*?>.*?</i\s*>@si'                                               => '',       // italico
        '@<br[^>]*?>@si'                                                        => ' '       // espaço
    );
//    $strHtml=preg_replace_callback('@&#(d+);@','self::limparCaracteresHtml',$strHtml);
    return InfraString::removerAcentosHTML(preg_replace(array_keys($substituicoes), array_values($substituicoes), $strHtml));
  }

  private static function limparCaracteresHtml($matches)
  {
    return chr(intval($matches[1]));
  }

  public static function formatarRotulo($strTipoVisualizacao, $strRotulo, $bolFinalizar = true ){
    if ($strRotulo!='') {

      if ($bolFinalizar && !in_array(substr(trim($strRotulo), -1),array('.',':','?','!'))){
				$strRotulo .= ':';
			}

      if ($strTipoVisualizacao == self::$TV_HTML) {

        $strRotulo = PaginaSEI::tratarHTML($strRotulo);

        $tamRotulo = strlen($strRotulo);
        $numEspacos = 0;
        for ($i = 0; $i < $tamRotulo; $i++) {
          if ($strRotulo[$i]== ' ') {
            $numEspacos++;
          } else {
            break;
          }
        }

        $strRotulo = str_repeat('&nbsp;', $numEspacos).trim(nl2br($strRotulo));
      }
    }
    return $strRotulo;
  }

  public static function montarUpload($frmAnexos,$strLinkAnexos,$filArquivo,$objUpload,$funcaoConclusao,$objTabelaAnexos=null,$tblAnexos=null,$hdnAnexos=null){

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

    $numTamMbDocExterno = $objInfraParametro->getValor('SEI_TAM_MB_DOC_EXTERNO');
    if (InfraString::isBolVazia($numTamMbDocExterno) || !is_numeric($numTamMbDocExterno)){
      throw new InfraException('Valor do parâmetro SEI_TAM_MB_DOC_EXTERNO inválido.');
    }

    $jsArrayExtensoesArq = '';
    $bolValidarExtensaoArq = $objInfraParametro->getValor('SEI_HABILITAR_VALIDACAO_EXTENSAO_ARQUIVOS'); //string "1" ou "0" (default se não hover param no bd)
    // Se adicionado o parâmetro SEI_HABILITAR_LISTAGEM_EXTENSAO_ARQUIVOS a apresentação fica configurável ao desejo do gestor: se 1 exibe as extensões permitidas na tela. Se 0 não exibe na tela. Contudo, se isso for usado mais vezes, penso que deveria ser elaborado como componente/objeto para aumentar o reuso de código e evitar repetições.
    if ( $bolValidarExtensaoArq == "1" ) {
      $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
      $objArquivoExtensaoDTO->retNumTamanhoMaximo();
      $objArquivoExtensaoDTO->retStrExtensao();
      $objArquivoExtensaoDTO->retStrDescricao();
      $objArquivoExtensaoDTO->setStrSinInterface('S');
      $objArquivoExtensaoDTO->setOrdStrExtensao(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objArquivoExtensaoRN = new ArquivoExtensaoRN();
      $arrObjArquivoExtensaoDTO = $objArquivoExtensaoRN->listar($objArquivoExtensaoDTO);

      $numExt = count($arrObjArquivoExtensaoDTO);
      for($i = 0; $i < $numExt; $i++){
        $jsArrayExtensoesArq .= '  arrExt['.$i.'] = {nome : "'.InfraString::transformarCaixaBaixa($arrObjArquivoExtensaoDTO[$i]->getStrExtensao()).'", tamanho : '.(($arrObjArquivoExtensaoDTO[$i]->getNumTamanhoMaximo()!=null)?$arrObjArquivoExtensaoDTO[$i]->getNumTamanhoMaximo():$numTamMbDocExterno).'};'."\n";
      }
    }

    $strConteudo = "
    
    $objUpload = new infraUpload('$frmAnexos','$strLinkAnexos');
    $objUpload.validar = function(){
      var i = 0;
      var arrExt = [];
      var oFile = document.getElementById('$filArquivo');

      if (oFile.length==0) {
        return false;
      }
      var nomeArquivo,bolFileApi=false;
      if(oFile.files==undefined){
        //ie<10
        nomeArquivo=oFile.value.replace('C:\\\\fakepath\\\\', '');
      } else {
        bolFileApi=true;
        nomeArquivo = oFile.files[0].name;
      }

      if (nomeArquivo.indexOf('&#')!= -1) {
        alert('Nome do anexo possui caracteres especiais.');
        return false;
      }

      if (bolFileApi && oFile.files[0].size > ($numTamMbDocExterno * 1024 * 1024)) {
        alert('Arquivo excede o tamanho máximo geral permitido para documentos externos de ' + '$numTamMbDocExterno' + 'Mb.');
        return false;
      }

      if ('$bolValidarExtensaoArq'=='1'){

        $jsArrayExtensoesArq

        if (arrExt.length==0) {
          alert('Nenhuma extensão de arquivo permitida foi cadastrada.');
          return false;
        }

        nomeArquivo = nomeArquivo.replace(/^.*\./, '').toLowerCase();

        for(i=0; i < arrExt.length; i++){
          if (nomeArquivo == arrExt[i].nome) {
            if (bolFileApi && oFile.files[0].size > (arrExt[i].tamanho * 1024 * 1024)) {
              alert('O tamanho máximo permitido para arquivos com extensão ' + arrExt[i].nome.toUpperCase() + ' é ' + arrExt[i].tamanho + 'Mb.');
              return false;
            }
            break;
          }
        }

        if (i == arrExt.length){

          var msg = 'O arquivo selecionado não é permitido.\\n\\nSomente são permitidos arquivos com as extensões: ';
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
      }

      return true;
    };
    
    $objUpload.finalizou = $funcaoConclusao;
    
    ";
    if($objTabelaAnexos != null && $tblAnexos != null && $hdnAnexos != null) {
      $strConteudo .= "
        //Monta tabela de anexos
        $objTabelaAnexos = new infraTabelaDinamica('$tblAnexos','$hdnAnexos',false,false);
        $objTabelaAnexos.gerarEfeitoTabela=true;
      ";
    }

    return $strConteudo;
  }

  public static function download($objDocumentoDTO, $objInfraSessao, $strLinkDownload){

    $objDocumentoRN = new DocumentoRN();

    if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){

      if ($objDocumentoDTO->getDblIdDocumentoEdoc()==null) {
        die('Documento sem conteúdo.');
      }

      $objEDocRN = new EDocRN();
      $strConteudo = $objEDocRN->consultarHTMLDocumentoRN1204($objDocumentoDTO);

      SeiINT::download(null, $strConteudo, null, $objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.html', 'inline', $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $objDocumentoDTO->getDblIdDocumento());

    }else if ($objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){

      $objEditorDTO = new EditorDTO();
      $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objEditorDTO->setNumIdBaseConhecimento(null);
      $objEditorDTO->setStrSinCabecalho('S');
      $objEditorDTO->setStrSinRodape('S');
      $objEditorDTO->setStrSinCarimboPublicacao('S');
      $objEditorDTO->setStrSinIdentificacaoVersao('N');

      $objEditorRN = new EditorRN();
      $strConteudo = $objEditorRN->consultarHtmlVersao($objEditorDTO);

      SeiINT::download(null, $strConteudo, null, $objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.html', 'inline',  $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $objDocumentoDTO->getDblIdDocumento());

      //links para anexos de documentos de email
    }else if (isset($_GET['id_anexo'])){

      $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retStrNome();
      $objAnexoDTO->retStrHash();
      $objAnexoDTO->retDblIdProtocolo();
      $objAnexoDTO->setNumIdAnexo($_GET['id_anexo']);
      $objAnexoDTO->retDthInclusao();

      $objAnexoRN = new AnexoRN();
      $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

      SeiINT::download($objAnexoDTO, null, null, null, SeiINT::getContentDisposition($objAnexoDTO->getStrNome()), $objAnexoDTO->getStrNome(), $objAnexoDTO->getDblIdProtocolo());

    }else if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retStrNome();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retStrHash();
      $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());
      $objAnexoDTO->retDblIdProtocolo();
      $objAnexoDTO->retDthInclusao();
      $objAnexoDTO->retStrProtocoloFormatadoProtocolo();

      $objAnexoRN = new AnexoRN();
      $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

      if (count($arrObjAnexoDTO)==1){

        $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

        SeiINT::download($arrObjAnexoDTO[0], null, null, null, SeiINT::getContentDisposition($arrObjAnexoDTO[0]->getStrNome()), $arrObjAnexoDTO[0]->getStrProtocoloFormatadoProtocolo(), $arrObjAnexoDTO[0]->getDblIdProtocolo());
      }else{
        die('Documento não contém anexo.');
      }

    }else{

      $dto = new DocumentoDTO();
      $dto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $dto->setObjInfraSessao($objInfraSessao);
      $dto->setStrLinkDownload($strLinkDownload);

      $strConteudo = $objDocumentoRN->consultarHtmlFormulario($dto);

      SeiINT::download(null, $strConteudo, null, $objDocumentoDTO->getStrProtocoloDocumentoFormatado().'.html', 'inline',  $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $objDocumentoDTO->getDblIdDocumento());
    }
  }

  public static function validarEscolhaTipoDocumento(DocumentoDTO $parObjDocumentoDTO){

    if (!isset($_GET['id_item_etapa']) && $parObjDocumentoDTO->getNumIdSerie() != null && in_array($_GET['acao'], array('documento_escolher_tipo', 'documento_receber'))) {

      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retNumIdPlanoTrabalho();
      $objProcedimentoDTO->setDblIdProcedimento($parObjDocumentoDTO->getDblIdProcedimento());

      $objProcedimentoRN = new ProcedimentoRN();
      $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);

      if ($objProcedimentoDTO->getNumIdPlanoTrabalho() != null) {

        $objInfraException = new InfraException();

        $objSerieDTO = new SerieDTO();
        $objSerieDTO->retStrNome();
        $objSerieDTO->setNumIdSerie($parObjDocumentoDTO->getNumIdSerie());

        $objSerieRN = new SerieRN();
        $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);

        $objRelSeriePlanoTrabalhoDTO = new RelSeriePlanoTrabalhoDTO();
        $objRelSeriePlanoTrabalhoDTO->retNumIdSerie();
        $objRelSeriePlanoTrabalhoDTO->retStrNomePlanoTrabalho();
        $objRelSeriePlanoTrabalhoDTO->setNumIdSerie($parObjDocumentoDTO->getNumIdSerie());
        $objRelSeriePlanoTrabalhoDTO->setNumIdPlanoTrabalho($objProcedimentoDTO->getNumIdPlanoTrabalho());

        $objRelSeriePlanoTrabalhoRN = new RelSeriePlanoTrabalhoRN();
        if (($objRelSeriePlanoTrabalhoDTO = $objRelSeriePlanoTrabalhoRN->consultar($objRelSeriePlanoTrabalhoDTO)) != null) {
          $objInfraException->lancarValidacao('O tipo de documento "' . $objSerieDTO->getStrNome() . '" não é permitido no processo por restrições do Plano de Trabalho "'.$objRelSeriePlanoTrabalhoDTO->getStrNomePlanoTrabalho().'".');
        }

        $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
        if (($arrObjRelItemEtapaSerieDTO = $objPlanoTrabalhoRN->obterEtapasDocumento($parObjDocumentoDTO)) != null) {

          if (!SessaoSEI::getInstance()->verificarPermissao('plano_trabalho_detalhar')) {
            $objInfraException->lancarValidacao('O tipo de documento "' . $objSerieDTO->getStrNome() . '" somente pode ser incluído no processo por meio do Plano de Trabalho.');
          }

          $arrAncora = array();
          foreach ($arrObjRelItemEtapaSerieDTO as $objRelItemEtapaSerieDTO) {
            $arrAncora[] = $objRelItemEtapaSerieDTO->getNumIdEtapaTrabalhoItemEtapa() . '-' . $objRelItemEtapaSerieDTO->getNumIdItemEtapa();
          }
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=plano_trabalho_detalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $parObjDocumentoDTO->getDblIdProcedimento() . '&id_serie='.$parObjDocumentoDTO->getNumIdSerie(). '&arvore=1') . PaginaSEI::montarAncora($arrAncora));
          die;
        }
      }
    }
  }
}
?>