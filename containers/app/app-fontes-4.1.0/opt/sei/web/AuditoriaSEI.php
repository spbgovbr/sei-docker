<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 09/04/2013 - criado por MGA
 *
 */
 
 require_once dirname(__FILE__).'/SEI.php';
 
 class AuditoriaSEI extends InfraAuditoria {
	 
 	private static $instance = null;
 	private $strComplemento = null;
 	private static $arrObjProtocoloDTO = array();
 	private static $arrObjAtributoAndamentoDTO = array();
 	
 	public static function getInstance() 
	{ 
	    if (self::$instance == null) { 
        self::$instance = new AuditoriaSEI(BancoSEI::getInstance(),SessaoSEI::getInstance(),CacheSEI::getInstance());
	    } 
	    return self::$instance; 
	} 
	
	//public function getArrExcecoesGet(){
	//  return null;
	//}

	public function getArrExcecoesPost(){
	  return array('pwdSenha','pwdSenhaAtual','pwdSenhaNova','pwdSenhaConfirma');
	}

	public function getTempoCache(){
	  return CacheSEI::getInstance()->getNumTempo();
	}

  public function getObjInfraIBancoAuditoria(){

   if (ConfiguracaoSEI::getInstance()->isSetValor('BancoAuditoriaSEI')){
     return BancoAuditoriaSEI::getInstance();
   }

   return null;
  }

	public function setStrComplemento($strComplemento){
 	  $this->strComplemento = $strComplemento;
  }

	public function prepararParametro($varParametro){

 	  if ($this->strComplemento!=null){
      return $this->strComplemento."\n".$this->formatarDados($varParametro);;
    }

    return $varParametro;
  }

  public function processarComplemento(InfraAuditoriaDTO $objInfraAuditoriaDTO) {
    try{

      $strRequisicao = $objInfraAuditoriaDTO->getStrRequisicao();
      $strOperacao = $objInfraAuditoriaDTO->getStrOperacao();
      $strRecurso = $objInfraAuditoriaDTO->getStrRecurso();

      $strComplemento = '';

      if (($strRecurso=='processo_consulta_externa' || $strRecurso=='documento_consulta_externa') &&
          strpos($strOperacao,'EmailAcessoExterno = ') === false &&
          strpos($strOperacao,'SiglaUsuarioExterno = ') === false){
        $numIdAcessoExterno = InfraString::obterValor($strRequisicao, '[id_acesso_externo] => ', "\n");
        if (is_numeric($numIdAcessoExterno)) {
          $objAcessoExternoDTO = new AcessoExternoDTO();
          $objAcessoExternoDTO->setBolExclusaoLogica(false);
          $objAcessoExternoDTO->retStrEmailDestinatario();
          $objAcessoExternoDTO->retStrSiglaContato();
          $objAcessoExternoDTO->retStrNomeContato();
          $objAcessoExternoDTO->setNumIdAcessoExterno($numIdAcessoExterno);

          $objAcessoExternoRN = new AcessoExternoRN();
          $objAcessoExternoDTO = $objAcessoExternoRN->consultar($objAcessoExternoDTO);

          if ($objAcessoExternoDTO != null) {
            if ($objAcessoExternoDTO->getStrEmailDestinatario()!=null) {
              $strComplemento .= 'EmailAcessoExterno = '.$objAcessoExternoDTO->getStrEmailDestinatario()."\n";
            }
            if ($objAcessoExternoDTO->getStrSiglaContato()!=null) {
              $strComplemento .= 'SiglaAcessoExterno = '.$objAcessoExternoDTO->getStrSiglaContato()."\n";
            }
            if ($objAcessoExternoDTO->getStrNomeContato()!=null) {
              $strComplemento .= 'NomeAcessoExterno = '.$objAcessoExternoDTO->getStrNomeContato()."\n";
            }
          }
        }
      }

      $ini = 0;

      $tag = "\nId";

      $bolProcesso = false;

      while (true) {

        $ini = strpos($strOperacao, $tag, $ini);

        if ($ini !== false) {

          $igual = strpos($strOperacao, " = ", $ini);

          if ($igual !== false) {

            $i = $igual + 3;
            $t = strlen($strOperacao);

            $valor = '';
            while($i < $t){
              if (is_numeric($strOperacao[$i])){
                $valor .= $strOperacao[$i];
              }else{
                break;
              }
              $i++;
            }

            if (is_numeric($valor)){

              $fim = $i;

              $id = substr($strOperacao, $ini + 1, $igual - ($ini + 1));
             //InfraDebug::getInstance()->gravar($id.'='.$valor);

              if ($valor != null && $valor != '[null]') {

                switch ($id){

                  case 'IdProtocolo':
                  case 'IdDocumento':
                  case 'IdProcedimento':
                  case 'IdProtocoloAtividade':


                    if (isset(self::$arrObjProtocoloDTO[$valor])){
                      $objProtocoloDTO = self::$arrObjProtocoloDTO[$valor];
                    }else {
                      $objProtocoloDTO = new ProtocoloDTO();
                      $objProtocoloDTO->retStrProtocoloFormatado();
                      $objProtocoloDTO->retStrStaProtocolo();
                      $objProtocoloDTO->retStrNomeTipoProcedimentoProcedimento();
                      $objProtocoloDTO->retStrNomeSerieDocumento();
                      $objProtocoloDTO->retStrNumeroDocumento();
                      $objProtocoloDTO->setDblIdProtocolo($valor);

                      $objProtocoloRN = new ProtocoloRN();
                      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
                    }

                    if ($objProtocoloDTO != null){

                      if (!isset(self::$arrObjProtocoloDTO[$valor])){
                        self::$arrObjProtocoloDTO[$valor] = $objProtocoloDTO;
                      }

                      if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO){

                        if ($bolProcesso && ($strRecurso == 'procedimento_gerar_pdf' || $strRecurso == 'procedimento_gerar_zip')) {
                          break;
                        }

                        $strComplemento .= 'Processo = '.$objProtocoloDTO->getStrProtocoloFormatado().' - '.$objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()."\n";
                        $bolProcesso = true;
                      }else{

                        $strVersao = '';
                        if ($strRecurso=='documento_visualizar') {
                          $v = InfraString::obterValor($strOperacao, 'Versao = ', "\n");
                          if ($v != null && $v != '[null]') {
                            $strVersao = ' (versão '.$v.')';
                          }
                        }

                        $strComplemento .= 'Documento = '.$objProtocoloDTO->getStrProtocoloFormatado().' - '.$objProtocoloDTO->getStrNomeSerieDocumento().' '.$objProtocoloDTO->getStrNumeroDocumento().$strVersao."\n";


                      }
                    }else{

                      if (isset(self::$arrObjAtributoAndamentoDTO[$valor])){
                        $objAtributoAndamentoDTO = self::$arrObjAtributoAndamentoDTO[$valor];
                      }else {
                        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
                        $objAtributoAndamentoDTO->retStrValor();
                        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
                        $objAtributoAndamentoDTO->setStrIdOrigem($valor);
                        $objAtributoAndamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_EXCLUSAO_DOCUMENTO);

                        $objAtributoAndamentoRN = new AtributoAndamentoRN();
                        $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);
                      }

                      if ($objAtributoAndamentoDTO != null) {

                        if (!isset(self::$arrObjAtributoAndamentoDTO[$valor])){
                          self::$arrObjAtributoAndamentoDTO[$valor] = $objAtributoAndamentoDTO;
                        }

                        $strVersao = '';
                        if ($strRecurso=='documento_visualizar') {
                          $v = InfraString::obterValor($strOperacao, 'Versao = ', "\n");
                          if ($v != null && $v != '[null]') {
                            $strVersao = ' (versão '.$v.')';
                          }
                        }

                        $strComplemento .= 'Documento = '.$objAtributoAndamentoDTO->getStrValor().' '.$strVersao.' (excluído)'."\n";
                      }
                    }
                    break;

                }
              }


              $ini = $fim;

            }else{
              $ini = $igual;
            }
          }else{
            break;
          }
        }else{
          break;
        }
      }

      if ($strComplemento!='') {
        if (substr_count($strComplemento, "\n") > 1) {
          $strComplemento = "\n".$strComplemento;
        } else {
          $strComplemento = str_replace("\n", '', $strComplemento);
        }
      }

      return $strComplemento;

    }catch(Exception $e){
      throw new InfraException('Erro processando Complemento de Auditoria.', $e);
    }
  }
}
?>