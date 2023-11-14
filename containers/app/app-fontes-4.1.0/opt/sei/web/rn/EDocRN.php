<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/07/2008 - criado por fbv
 *
 * Versão do Gerador de Código: 1.19.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class EDocRN extends InfraRN {

	private static $RETURN_VALUE = 0;
	private static $REGISTROS = 1;
  private $objBancoEdoc;
  private $objWs;

	protected function inicializarObjInfraIBanco(){
		return BancoSEI::getInstance();
	}

	private function processarWebService($retWS,$bolDebug=false,$strDetalhes=''){
		//try{

		//sempre contém um atributo "<servico>Result"
		foreach(get_object_vars($retWS) as $key => $val) {

			// Recupera informacoes do XML
			$ret = $val->any;

			if ($bolDebug){
				throw new InfraException($ret);
			}

			// Corta cabecalhos de xs:schema, PHP aparentemente nao consegue interpretar
			$pos = strpos($ret,'<diffgr:diffgram');
			$ret = substr($ret,$pos);

			// Carrega XML

			$objXml = new DomDocument();
			$objXml->loadXML($ret);


			// Verifica se ocorreu erro na criação do texto padrão
			$bolErro = $objXml->getElementsByTagName("MyHasError")->item(0);

			if ($bolErro->nodeValue=='true') {
				$strErro = $objXml->getElementsByTagName("MyMessage")->item(0);
				$strErro = utf8_decode($strErro->nodeValue);

				$strException = $objXml->getElementsByTagName("MyException")->item(0);
				$strException = utf8_decode($strException->nodeValue);
				
				$strStackTrace = $objXml->getElementsByTagName("MyStackTrace")->item(0);
				$strStackTrace = utf8_decode($strStackTrace->nodeValue);
				
				$strDetalhes = $strException."\n\n".$strStackTrace."\n\n".$strDetalhes;
				
				throw new InfraException($strErro,null,$strDetalhes);
			}


			//NOVO - INICIO
			$arrRetorno = array();
			$arrRetorno[self::$RETURN_VALUE] = null;
			$arrRetorno[self::$REGISTROS] = array();

			$returnValue = $objXml->getElementsByTagName("MyReturnValue");

			if ($returnValue->length == 1){
				$arrRetorno[self::$RETURN_VALUE] = utf8_decode($returnValue->item(0)->nodeValue);
			}

			$children = $objXml->childNodes;
			if ($children->length == 1){

				$children = $children->item(0)->childNodes;
				if ($children->length == 1){
					$children = $children->item(0)->childNodes;
					for($i = 0; $i < $children->length; $i++){
						$child = $children->item($i);
						if ($child->nodeName != 'ReturnInfo'){
							$chaves = $child->childNodes;
							$arrCampos = array();
							for($j = 0; $j < $chaves->length; $j++){
								$chave = $chaves->item($j);
								$arrCampos[$chave->nodeName] = utf8_decode($chave->nodeValue);
							}
							$arrRetorno[self::$REGISTROS][$i] = $arrCampos;
						}
					}
				}
			}

			/*
			 foreach($arrRetorno[self::$REGISTROS] as $registro){
			 foreach($registro as $chave => $valor){
			 echo $chave.' = '.$valor.'<br />';
			 }
			 echo '<br /><br />';
			 }
			 die;
			 */

			//NOVO - FIM

			return $arrRetorno;
		}

		//} catch (Exception $e) {
		//  throw new InfraException('Erro processando retorno (e-Doc).', $e);
		//}
	}

	public function consultarHTMLDocumentoRN1204(DocumentoDTO $parObjDocumentoDTO)
  {

    $ret = null;

    try {

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrConteudo();
      $objDocumentoDTO->retStrConteudoAssinatura();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->setDblIdDocumentoEdoc($parObjDocumentoDTO->getDblIdDocumentoEdoc());

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO == null) {
        throw new InfraException('Documento não encontrado.');
      }

      $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

      if (ConfiguracaoSEI::getInstance()->getValor('Edoc', 'Servidor', false) == null){

        if ($objDocumentoDTO->getStrConteudoAssinatura()!=null){
          if ($objDocumentoDTO->getStrConteudo()!=null){
            $ret = $objDocumentoDTO->getStrConteudo();
          }else{
            $ret = $objDocumentoDTO->getStrConteudoAssinatura();
          }
        }

      }else {

        if ($this->objWs == null) {
          $this->objWs = Edoc::getInstance()->getWebService('WsConsultaDocumento');
        }

        $arrParametros = array("idDocumento" => $parObjDocumentoDTO->getDblIdDocumentoEdoc());

        $retWS = $this->objWs->VisualizaDocumento($arrParametros);

        $arrRet = $this->processarWebService($retWS);

        $ret = $arrRet[self::$RETURN_VALUE];
      }


      if ($parObjDocumentoDTO->isSetStrSinValidarXss() && $parObjDocumentoDTO->getStrSinValidarXss()=='S') {
        SeiINT::validarXss($ret, false, false, $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $objDocumentoDTO->getDblIdDocumento());
      }

		} catch (SoapFault $soapFault) {
			throw new InfraException('Erro consultando HTML do documento (e-Doc).', $soapFault, 'VisualizaDocumento: '.print_r($arrParametros,true));
		} catch (Exception $e) {
			throw new InfraException('Erro consultando HTML do documento (e-Doc).', $e, 'VisualizaDocumento: '.print_r($arrParametros,true));
		}

		return $ret;
	}

  /**
   * @param DocumentoDTO $objDocumentoDTO
   * @throws Exception
   * @throws InfraException
   */
  protected function migrarConectado(DocumentoDTO $objDocumentoDTO){
    $objDocumentoRN=new DocumentoRN();
    $objDocumentoDTO->retDtaGeracaoProtocolo();
    $objDocumentoDTO->setDistinct(true);
    $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_EDOC);
    $objDocumentoDTO->setDblIdDocumentoEdoc(null,InfraDTO::$OPER_DIFERENTE);
    $objDocumentoDTO->setStrConteudoAssinatura(null);
    $objDocumentoDTO->setOrdDtaGeracaoProtocolo(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objDocumentoDTO->setNumFiltroFkDocumentoConteudo(InfraDTO::$FILTRO_FK_WHERE);
    $arrObjDocumentoDTODatas=$objDocumentoRN->listarRN0008($objDocumentoDTO);

    echo '['.InfraData::getInstance()->getStrHoraAtual().'] '."Abrindo conexão com e-Doc\n\n";

    $strServidor = ConfiguracaoSEI::getInstance()->getValor('BancoEdoc','Servidor');
    $strPorta=ConfiguracaoSEI::getInstance()->getValor('BancoEdoc','Porta');
    $strBanco=ConfiguracaoSEI::getInstance()->getValor('BancoEdoc','Banco');
    $strUsuario=ConfiguracaoSEI::getInstance()->getValor('BancoEdoc','Usuario');
    $strSenha=ConfiguracaoSEI::getInstance()->getValor('BancoEdoc','Senha');

    $this->objBancoEdoc=InfraBancoSqlServer::newInstance($strServidor,$strPorta,$strBanco,$strUsuario,$strSenha);
    $this->objBancoEdoc->abrirConexao();

    $numMigrOK=0;
    $numMigrErr=0;
    foreach ($arrObjDocumentoDTODatas as $objDocumentoDTO) {
      echo "\n[".InfraData::getInstance()->getStrHoraAtual().'] '.'Processando registros de ['.$objDocumentoDTO->getDtaGeracaoProtocolo().']: ';
      $objDocumentoDTO2=new DocumentoDTO();
      $objDocumentoDTO2->setStrStaDocumento(DocumentoRN::$TD_EDITOR_EDOC);
      $objDocumentoDTO2->setDblIdDocumentoEdoc(null,InfraDTO::$OPER_DIFERENTE);
      $objDocumentoDTO2->setStrConteudoAssinatura(null);
      $objDocumentoDTO2->retDblIdDocumento();
      $objDocumentoDTO2->setDtaGeracaoProtocolo($objDocumentoDTO->getDtaGeracaoProtocolo());
      $objDocumentoDTO2->retDblIdDocumentoEdoc();
      $objDocumentoDTO2->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO2->setNumFiltroFkDocumentoConteudo(InfraDTO::$FILTRO_FK_WHERE);
      $arrObjDocumentoDTO=$objDocumentoRN->listarRN0008($objDocumentoDTO2);

      echo "\n[".InfraData::getInstance()->getStrHoraAtual().'] '.count($arrObjDocumentoDTO)." documentos e-Doc encontrados para processamento.\n";
      foreach ($arrObjDocumentoDTO as $objDocumentoDTOProcessamento) {
        if ($this->migrarDocumento($objDocumentoDTOProcessamento)){
          $numMigrOK++;
        } else {
          $numMigrErr++;
        };
      }
      echo '['.InfraData::getInstance()->getStrHoraAtual().'] '."\n\nTotal de registros processados até o momento:".($numMigrOK+$numMigrErr)."- (migrados ok:".$numMigrOK.", erros:".$numMigrErr.")\n";
//      if($numMigrErr>100) break;
    }
    $this->objBancoEdoc->fecharConexao();
    echo '['.InfraData::getInstance()->getStrHoraAtual().'] '."\n\nTotal de registros processados:".($numMigrOK+$numMigrErr)."\n- migrados ok:".$numMigrOK."\n- erros:".$numMigrErr."\n\n";
  }
	public function migrarDocumentoControlado(DocumentoDTO $objDocumentoDTO)
  {

    $objAssinaturaRN = new AssinaturaRN();
    $objAssinaturaBD = new AssinaturaBD(BancoSEI::getInstance());
    $objDocumentoConteudoBD = new DocumentoConteudoBD(BancoSEI::getInstance());

    $objDocumentoConteudoDTO = new DocumentoConteudoDTO();
    $objDocumentoConteudoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

    $strDocumento= "{".$objDocumentoDTO->getStrProtocoloDocumentoFormatado()." -S ".$objDocumentoDTO->getDblIdDocumento()." -E ".$objDocumentoDTO->getDblIdDocumentoEdoc()."}";
    //consultar xml do edoc (banco)

    $sql = 'select v.conteudo_xml,v.id,v.crc from versao v where v.id_documento=' . $objDocumentoDTO->getDblIdDocumentoEdoc() . ' and v.numero_versao in (select max(v2.numero_versao) from versao v2 where v2.id_documento = ' . $objDocumentoDTO->getDblIdDocumentoEdoc() . ');';
    $rs = $this->objBancoEdoc->consultarSql($sql);

    if (InfraArray::contar($rs) > 1) {
      $sql = 'select v.conteudo_xml,v.id,v.crc from versao v where v.sin_documento_digital=\'S\' and v.id_documento=' . $objDocumentoDTO->getDblIdDocumentoEdoc() . ' and v.numero_versao in (select max(v2.numero_versao) from versao v2 where v2.id_documento = ' . $objDocumentoDTO->getDblIdDocumentoEdoc() . ');';
      $rs = $this->objBancoEdoc->consultarSql($sql);
    }
    if (InfraArray::contar($rs) == 1) {
      $objDocumentoConteudoDTO->setStrConteudoAssinatura($rs[0]['conteudo_xml']);
      $objDocumentoConteudoDTO->setStrCrcAssinatura($rs[0]['crc']);
    } else {
        $strErro = 'Erro consultando conteudo_xml ' . $strDocumento . "\n";
        echo $strErro;
        return false;
    }

    //buscar assinaturas sei
    $objAssinaturaDTO = new AssinaturaDTO();
    $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
    $objAssinaturaDTO->retNumIdAssinatura();
    $objAssinaturaDTO->retDblCpf();
    $objAssinaturaDTO->retStrStaFormaAutenticacao();
    $objAssinaturaDTO->retNumIdTarjaAssinatura();
    $objAssinaturaDTO->setOrdNumIdAssinatura(InfraDTO::$TIPO_ORDENACAO_ASC);
    $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
    $arrObjAssinaturaDTO = InfraArray::indexarArrInfraDTO($arrObjAssinaturaDTO, 'Cpf');
    //buscar assinaturas do edoc
    $rs = $this->objBancoEdoc->consultarSql('select numero_serie_certificado,assinatura,id_tipo_assinatura,cpf,id_versao from rel_versao_assinatura where assinatura is not null and id_versao=' . $rs[0]['id'] . ' order by id;');



    $arrAssinaturasEdoc=array();
    foreach ($rs as $regAssinatura) {
      $cpf=$regAssinatura['cpf'];
      $tipo=$regAssinatura['id_tipo_assinatura'];
      if (!isset($arrAssinaturasEdoc[$cpf])){
        $arrAssinaturasEdoc[$cpf]=array(false,false);
      }
      if (!$arrAssinaturasEdoc[$cpf][$tipo]){
        if ($tipo==2){
          $arrAssinaturasEdoc[$cpf][2]=true;
        } else {
          $arrAssinaturasEdoc[$cpf][1]=array('p7s'=>$regAssinatura['assinatura'],'cert_ns'=>$regAssinatura['numero_serie_certificado']);
        }
      } else {
        if ($tipo==1){
          echo 'Assinatura por Certificado Digital duplicada no e-Doc: '.$strDocumento." ** Ignorado **\n";
//          return false;
        } else {
          //assinatura por sigla/senha duplicada descartada
        }
      }
    }

    foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
      $assinaturaEdoc=$arrAssinaturasEdoc[$objAssinaturaDTO->getDblCpf()];

      if ($objAssinaturaDTO->getStrStaFormaAutenticacao()== AssinaturaRN::$TA_CERTIFICADO_DIGITAL){
        if (!$assinaturaEdoc || !$assinaturaEdoc[1]){
          echo 'Erro: Assinatura por Certificado Digital não localizada no e-Doc: '.$strDocumento."\n";
          return false;
        }
        $objAssinaturaDTO->setStrP7sBase64($assinaturaEdoc[1]['p7s']);
        $objAssinaturaDTO->setStrNumeroSerieCertificado($assinaturaEdoc[1]['cert_ns']);
      } else { //sigla e senha
        if (!$assinaturaEdoc || !$assinaturaEdoc[2]){
          //ignora assinatura sigla e senha não localizada no e-doc
          echo 'Assinatura por sigla/senha não localizada no e-Doc: '.$strDocumento." ** Ignorado **\n";
          continue;
        }

      }
    }

    if (InfraArray::contar($rs) != InfraArray::contar($arrObjAssinaturaDTO)) {
      $strErro = 'Divergência na quantidade de assinaturas  ['.InfraArray::contar($rs).','.InfraArray::contar($arrObjAssinaturaDTO).'] ' . $strDocumento . " ** Ignorado **\n";
      echo $strErro;
    }


    //busca html do edoc (webservice)
    try {
      $strConteudo = $this->consultarHTMLDocumentoRN1204($objDocumentoDTO);
      $objDocumentoConteudoDTO->setStrConteudo($strConteudo);
    } catch (Exception $e) {
      echo 'Erro consultando webservice para o documento ' . $strDocumento . "\n";
//      echo 'W';
      try {
      } catch (Exception $e) {
      }
      return false;
    }
//   ********* gravação dos dados *********
    foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
      $objAssinaturaBD->alterar($objAssinaturaDTO);
    }
    $objDocumentoConteudoBD->alterar($objDocumentoConteudoDTO);

    return true;

  }

}
?>