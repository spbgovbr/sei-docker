<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RetornoProgramadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocolo(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdUnidadeEnvio(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getNumIdUnidadeEnvio())){
      $objInfraException->adicionarValidacao('Unidade de envio não informada.');
    }
  }

  private function validarNumIdUnidadeRetorno(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getNumIdUnidadeRetorno())){
      $objInfraException->adicionarValidacao('Unidade de retorno não informada.');
    }
  }

  private function validarNumIdAtividadeEnvio(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getNumIdAtividadeEnvio())){
      $objInfraException->adicionarValidacao('Atividade de Envio não informada.');
    }
  }
  
  private function validarNumIdAtividadeRetorno(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getNumIdAtividadeRetorno())){
      $objInfraException->adicionarValidacao('Atividade de Retorno não informada.');
    }
  }

  private function validarNumIdUsuario(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarDtaProgramada(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getDtaProgramada())){
      $objInfraException->adicionarValidacao('Data Programada não informada.');
    }else{
      if (!InfraData::validarData($objRetornoProgramadoDTO->getDtaProgramada())){
        $objInfraException->adicionarValidacao('Data Programada inválida.');
      }

      if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objRetornoProgramadoDTO->getDtaProgramada())<0){
        $objInfraException->adicionarValidacao('Data Programada não pode estar no passado.');
      }
      
      if ($objRetornoProgramadoDTO->getNumIdRetornoProgramado()!=null){
      	$objRetornoProgramadoDTOBanco = new RetornoProgramadoDTO();
      	$objRetornoProgramadoDTOBanco->retDtaProgramada();
      	$objRetornoProgramadoDTOBanco->setNumIdRetornoProgramado($objRetornoProgramadoDTO->getNumIdRetornoProgramado());
      	
      	$objRetornoProgramadoDTOBanco = $this->consultar($objRetornoProgramadoDTOBanco);
      	
      	if (InfraData::compararDatas($objRetornoProgramadoDTOBanco->getDtaProgramada(),$objRetornoProgramadoDTO->getDtaProgramada())<0){
      		$objInfraException->adicionarValidacao('Não é possível diminuir o prazo estabelecido anteriormente.');
      	}
      }
      
    }
  }

  private function validarDthAlteracao(RetornoProgramadoDTO $objRetornoProgramadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRetornoProgramadoDTO->getDthAlteracao())){
      $objRetornoProgramadoDTO->setDthAlteracao(null);
    }else{
      if (!InfraData::validarDataHora($objRetornoProgramadoDTO->getDthAlteracao())){
        $objInfraException->adicionarValidacao('Data de Alteração inválida.');
      }
    }
  }

  protected function cadastrarControlado(RetornoProgramadoDTO $objRetornoProgramadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_cadastrar',__METHOD__,$objRetornoProgramadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocolo($objRetornoProgramadoDTO, $objInfraException);
      $this->validarNumIdUnidadeEnvio($objRetornoProgramadoDTO, $objInfraException);
      $this->validarNumIdUnidadeRetorno($objRetornoProgramadoDTO, $objInfraException);
      $this->validarNumIdAtividadeEnvio($objRetornoProgramadoDTO, $objInfraException);
      $this->validarNumIdUsuario($objRetornoProgramadoDTO, $objInfraException);
      $this->validarDtaProgramada($objRetornoProgramadoDTO, $objInfraException);
      $this->validarDthAlteracao($objRetornoProgramadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      $ret = $objRetornoProgramadoBD->cadastrar($objRetornoProgramadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando retorno.',$e);
    }
  }

  protected function alterarControlado(RetornoProgramadoDTO $objRetornoProgramadoDTO){
    try {

      //Valida Permissao
  	  SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_alterar',__METHOD__,$objRetornoProgramadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRetornoProgramadoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objRetornoProgramadoDTO, $objInfraException);
      }
      if ($objRetornoProgramadoDTO->isSetNumIdUnidadeEnvio()){
        $this->validarNumIdUnidadeEnvio($objRetornoProgramadoDTO, $objInfraException);
      }
      if ($objRetornoProgramadoDTO->isSetNumIdUnidadeRetorno()){
        $this->validarNumIdUnidadeRetorno($objRetornoProgramadoDTO, $objInfraException);
      }
      if ($objRetornoProgramadoDTO->isSetNumIdAtividadeEnvio()){
        $this->validarNumIdAtividadeEnvio($objRetornoProgramadoDTO, $objInfraException);
      }
      if ($objRetornoProgramadoDTO->isSetNumIdAtividadeRetorno()){
        $this->validarNumIdAtividadeRetorno($objRetornoProgramadoDTO, $objInfraException);
      }
      if ($objRetornoProgramadoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRetornoProgramadoDTO, $objInfraException);
      }
      if ($objRetornoProgramadoDTO->isSetDtaProgramada()){
        $this->validarDtaProgramada($objRetornoProgramadoDTO, $objInfraException);
      }
      
      $objRetornoProgramadoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objRetornoProgramadoDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());

      $objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      $objRetornoProgramadoBD->alterar($objRetornoProgramadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando retorno.',$e);
    }
  }

  protected function excluirControlado($arrObjRetornoProgramadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_excluir',__METHOD__,$arrObjRetornoProgramadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      for($i=0;$i<count($arrObjRetornoProgramadoDTO);$i++) {
        $dto = new RetornoProgramadoDTO();
        $dto->retStrProtocoloFormatadoProtocolo();
        $dto->retStrSiglaUnidadeRetorno();
        $dto->setNumIdRetornoProgramado($arrObjRetornoProgramadoDTO[$i]->getNumIdRetornoProgramado());
        $dto->setNumIdAtividadeRetorno(null,InfraDTO::$OPER_DIFERENTE);

        $dto = $this->consultar($dto);

        if ($dto != null) {
          $objInfraException->adicionarValidacao('Processo '.$dto->getStrProtocoloFormatadoProtocolo().' já foi devolvido pela unidade '.$dto->getStrSiglaUnidadeRetorno().'.');
        }
      }
      $objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRetornoProgramadoDTO);$i++){
        $objRetornoProgramadoBD->excluir($arrObjRetornoProgramadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo retorno.',$e);
    }
  }

  protected function consultarConectado(RetornoProgramadoDTO $objRetornoProgramadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_consultar',__METHOD__,$objRetornoProgramadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      $ret = $objRetornoProgramadoBD->consultar($objRetornoProgramadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando retorno.',$e);
    }
  }

  protected function listarConectado(RetornoProgramadoDTO $objRetornoProgramadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_listar',__METHOD__,$objRetornoProgramadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      $ret = $objRetornoProgramadoBD->listar($objRetornoProgramadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando retornos.',$e);
    }
  }

  protected function contarConectado(RetornoProgramadoDTO $objRetornoProgramadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_listar',__METHOD__,$objRetornoProgramadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      $ret = $objRetornoProgramadoBD->contar($objRetornoProgramadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando retornos.',$e);
    }
  }

	protected function listarDevolucoesEntregasConectado(RetornoProgramadoDTO $parObjRetornoProgramadoDTO){
		try{
		
			$objRetornoProgramadoDTO = new RetornoProgramadoDTO();
			$objRetornoProgramadoDTO->retNumIdRetornoProgramado();
			$objRetornoProgramadoDTO->retStrSiglaUsuario();
			$objRetornoProgramadoDTO->retDtaProgramada();
			$objRetornoProgramadoDTO->retDblIdProtocolo();
      $objRetornoProgramadoDTO->retNumIdAtividadeRetorno();
			$objRetornoProgramadoDTO->retNumIdUnidadeEnvio();
			$objRetornoProgramadoDTO->retNumIdUnidadeRetorno();
			$objRetornoProgramadoDTO->retDthAberturaAtividadeEnvio();
			$objRetornoProgramadoDTO->retDthAberturaAtividadeRetorno();
			$objRetornoProgramadoDTO->retStrSiglaUnidadeEnvio();
			$objRetornoProgramadoDTO->retStrDescricaoUnidadeEnvio();
			$objRetornoProgramadoDTO->retStrSiglaUnidadeRetorno();
			$objRetornoProgramadoDTO->retStrDescricaoUnidadeRetorno();
			
			$objRetornoProgramadoDTO->adicionarCriterio(array('IdUnidadeEnvio','IdUnidadeRetorno'),
			                                            array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
			                                            array(SessaoSEI::getInstance()->getNumIdUnidadeAtual(),SessaoSEI::getInstance()->getNumIdUnidadeAtual()),
			                                            array(InfraDTO::$OPER_LOGICO_OR));
			
			if ($parObjRetornoProgramadoDTO->isSetDtaInicial() && $parObjRetornoProgramadoDTO->isSetDtaFinal()){
				
        $objRetornoProgramadoDTO->adicionarCriterio(array('Programada','Programada'),
                                          array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
                                          array($parObjRetornoProgramadoDTO->getDtaInicial(),$parObjRetornoProgramadoDTO->getDtaFinal()),
                                          array(InfraDTO::$OPER_LOGICO_AND));				
				 
			}else if ($parObjRetornoProgramadoDTO->isSetDtaProgramada()){
				$objRetornoProgramadoDTO->setDtaProgramada($parObjRetornoProgramadoDTO->getDtaProgramada());
			}

      if ($parObjRetornoProgramadoDTO->isSetNumIdAtividadeRetorno()){
        $objRetornoProgramadoDTO->setNumIdAtividadeRetorno($parObjRetornoProgramadoDTO->getNumIdAtividadeRetorno());
      }

			$objRetornoProgramadoDTO->setOrdDtaProgramada(InfraDTO::$TIPO_ORDENACAO_ASC);

			$arrObjRetornoProgramadoDTO = $this->listar($objRetornoProgramadoDTO);

			//não faz processamento se montando calendario
				
			if (count($arrObjRetornoProgramadoDTO)>0){
				
				$objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
				$objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
				$objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjRetornoProgramadoDTO,'IdProtocolo'));
				
				$objProtocoloRN = new ProtocoloRN();
				$arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');
			}

			$arrRet = array();
			foreach($arrObjRetornoProgramadoDTO as $objRetornoProgramadoDTO){
				
				//se tem acesso
				if (isset($arrObjProtocoloDTO[$objRetornoProgramadoDTO->getDblIdProtocolo()])){
					
				  $objRetornoProgramadoDTO->setObjProtocoloDTO($arrObjProtocoloDTO[$objRetornoProgramadoDTO->getDblIdProtocolo()]);
				  
					if ($objRetornoProgramadoDTO->getDthAberturaAtividadeRetorno()==null){
						$objRetornoProgramadoDTO->setNumDiasPrazo(InfraData::compararDatas(InfraData::getStrDataAtual(),$objRetornoProgramadoDTO->getDtaProgramada()));
					}else{
						$objRetornoProgramadoDTO->setNumDiasPrazo(InfraData::compararDatas($objRetornoProgramadoDTO->retDthAberturaAtividadeRetorno(),$objRetornoProgramadoDTO->getDtaProgramada()));
					}
					$arrRet[] = $objRetornoProgramadoDTO;
				}
			} 
			
		  return $arrRet;
		
		}catch(Exception $e){
		  throw new InfraException('Erro listando devoluções e entregas.',$e);
		}
	}

  protected function validarExistenciaConectado(RetornoProgramadoDTO $parObjRetornoProgramadoDTO, InfraException $objInfraException){
    try{

      $objRetornoProgramadoDTO 	= new RetornoProgramadoDTO();
      $objRetornoProgramadoDTO->setDistinct(true);
      $objRetornoProgramadoDTO->retStrSiglaUnidadeEnvio();
      $objRetornoProgramadoDTO->retStrProtocoloFormatadoProtocolo();

      $objRetornoProgramadoDTO->setNumIdUnidadeRetorno(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objRetornoProgramadoDTO->setDblIdProtocolo($parObjRetornoProgramadoDTO->getDblIdProtocolo());

      if ($parObjRetornoProgramadoDTO->isSetNumIdUnidadeEnvio()) {

        if (is_array($parObjRetornoProgramadoDTO->getNumIdUnidadeEnvio())){
          $objRetornoProgramadoDTO->setNumIdUnidadeEnvio($parObjRetornoProgramadoDTO->getNumIdUnidadeEnvio(),InfraDTO::$OPER_NOT_IN);
        }else{
          $objRetornoProgramadoDTO->setNumIdUnidadeEnvio($parObjRetornoProgramadoDTO->getNumIdUnidadeEnvio(),InfraDTO::$OPER_DIFERENTE);
        }

      }

      $objRetornoProgramadoDTO->setNumIdAtividadeRetorno(null);

      $arrObjRetornoProgramadoDTO = $this->listar($objRetornoProgramadoDTO);

      if (count($arrObjRetornoProgramadoDTO)) {

        $arrObjRetornoProgramadoDTO = InfraArray::indexarArrInfraDTO($arrObjRetornoProgramadoDTO, 'ProtocoloFormatadoProtocolo', true);

        foreach ($arrObjRetornoProgramadoDTO as $strProtocoloFormatadoProtocolo => $arr) {
          $strMsgRetornoProgramado = 'Processo ' . $strProtocoloFormatadoProtocolo . ' possui retorno programado requisitado ';
          if (count($arr) == 1) {
            $strMsgRetornoProgramado .= 'pela unidade ' . $arr[0]->getStrSiglaUnidadeEnvio();
          } else {
            $strMsgRetornoProgramado .= 'pelas unidades: ' . implode(', ', InfraArray::converterArrInfraDTO($arr, 'SiglaUnidadeEnvio'));
          }
          $strMsgRetornoProgramado .= '.';

          $objInfraException->adicionarValidacao($strMsgRetornoProgramado);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro validando existência de Retorno Programado.',$e);
    }
  }
	
  /* 
  protected function desativarControlado($arrObjRetornoProgramadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRetornoProgramadoDTO);$i++){
        $objRetornoProgramadoBD->desativar($arrObjRetornoProgramadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando retorno.',$e);
    }
  }

  protected function reativarControlado($arrObjRetornoProgramadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRetornoProgramadoDTO);$i++){
        $objRetornoProgramadoBD->reativar($arrObjRetornoProgramadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando retorno.',$e);
    }
  }

  protected function bloquearControlado(RetornoProgramadoDTO $objRetornoProgramadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('retorno_programado_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRetornoProgramadoBD = new RetornoProgramadoBD($this->getObjInfraIBanco());
      $ret = $objRetornoProgramadoBD->bloquear($objRetornoProgramadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando retorno.',$e);
    }
  }

 */
}  
?>