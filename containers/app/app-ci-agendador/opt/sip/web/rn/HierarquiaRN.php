<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class HierarquiaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function clonarControlado(ClonarHierarquiaDTO $objClonarHierarquiaDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_clonar',__METHOD__,$objClonarHierarquiaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objClonarHierarquiaDTO->getNumIdHierarquiaOrigem())){
        $objInfraException->adicionarValidacao('Hierarquia de Origem não informada.');
      }

      if (InfraString::isBolVazia($objClonarHierarquiaDTO->getStrNomeDestino())){
        $objInfraException->adicionarValidacao('Nome de Destino não informado.');
      }
      
			$dto = new HierarquiaDTO();
			$dto->retNumIdHierarquia();
			$dto->setStrNome($objClonarHierarquiaDTO->getStrNomeDestino());
			if ($this->contar($dto)){
			  $objInfraException->adicionarValidacao('Já existe uma hierarquia com este nome.');
			}

      $objInfraException->lancarValidacoes();
      
      
      $objHierarquiaDTO = new HierarquiaDTO();
      $objHierarquiaDTO->retTodos();
      $objHierarquiaDTO->setNumIdHierarquia($objClonarHierarquiaDTO->getNumIdHierarquiaOrigem());
      $objHierarquiaDTO = $this->consultar($objHierarquiaDTO);
      
      $objHierarquiaDTO->setNumIdHierarquia(null);
      $objHierarquiaDTO->setStrNome($objClonarHierarquiaDTO->getStrNomeDestino());
      
      $objHierarquiaDTO = $this->cadastrar($objHierarquiaDTO);

      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
      $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
      $objRelHierarquiaUnidadeDTO->retDtaDataInicio();
      $objRelHierarquiaUnidadeDTO->retDtaDataFim();
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objClonarHierarquiaDTO->getNumIdHierarquiaOrigem());

      
      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listarHierarquia($objRelHierarquiaUnidadeDTO);
      $arrObjRelHierarquiaUnidadeDTO = InfraArray::indexarArrInfraDTO($arrObjRelHierarquiaUnidadeDTO,'IdUnidade');

      $arrCadastradas = array();
      
      foreach($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO){
        $this->cadastrarUnidadeHierarquia($objHierarquiaDTO->getNumIdHierarquia(),$arrObjRelHierarquiaUnidadeDTO,$arrCadastradas,$objRelHierarquiaUnidadeDTO->getNumIdUnidade());
      }

      //Auditoria

      return $objHierarquiaDTO;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Hierarquia.',$e);
    }
  }
  
  private function cadastrarUnidadeHierarquia($numIdHierarquia, $arrObjRelHierarquiaUnidadeDTO, &$arrCadastradas, $unidade){
    if (!in_array($unidade,$arrCadastradas)){
      
      $dto = clone($arrObjRelHierarquiaUnidadeDTO[$unidade]);
      
      if ($arrObjRelHierarquiaUnidadeDTO[$unidade]->getNumIdUnidadePai()!=null){
        $this->cadastrarUnidadeHierarquia($numIdHierarquia, $arrObjRelHierarquiaUnidadeDTO, $arrCadastradas, $arrObjRelHierarquiaUnidadeDTO[$unidade]->getNumIdUnidadePai());
      }else{
        $dto->setNumIdHierarquiaPai($numIdHierarquia);
      }
      
      $dto->setNumIdHierarquia($numIdHierarquia);
      
      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      $objRelHierarquiaUnidadeRN->cadastrar($dto);
      
      $arrCadastradas[] = $dto->getNumIdUnidade();
      
    }
  }

  protected function cadastrarControlado(HierarquiaDTO $objHierarquiaDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_cadastrar',__METHOD__,$objHierarquiaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objHierarquiaDTO,$objInfraException);
      $this->validarStrDescricao($objHierarquiaDTO,$objInfraException);
			$this->validarDtaDataInicio($objHierarquiaDTO,$objInfraException);
			$this->validarDtaDataFim($objHierarquiaDTO,$objInfraException);
			$this->validarPeriodoDatas($objHierarquiaDTO,$objInfraException);
			$this->validarStrSinAtivo($objHierarquiaDTO,$objInfraException);
			
      $objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      $ret = $objHierarquiaBD->cadastrar($objHierarquiaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Hierarquia.',$e);
    }
  }

  protected function alterarControlado(HierarquiaDTO $objHierarquiaDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_alterar',__METHOD__,$objHierarquiaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objHierarquiaDTO,$objInfraException);
      $this->validarStrDescricao($objHierarquiaDTO,$objInfraException);
			$this->validarDtaDataInicio($objHierarquiaDTO,$objInfraException);
			$this->validarDtaDataFim($objHierarquiaDTO,$objInfraException);
			$this->validarPeriodoDatas($objHierarquiaDTO,$objInfraException);
			$this->validarStrSinAtivo($objHierarquiaDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      $objHierarquiaBD->alterar($objHierarquiaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Hierarquia.',$e);
    }
  }

  protected function excluirControlado($arrObjHierarquiaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_excluir',__METHOD__,$arrObjHierarquiaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

			
			for($i=0;$i<count($arrObjHierarquiaDTO);$i++){
				//Verifica se existem sistemas associados
				$objSistemaDTO = new SistemaDTO();
				$objSistemaDTO->retNumIdSistema();
				$objSistemaDTO->setNumIdHierarquia($arrObjHierarquiaDTO[$i]->getNumIdHierarquia());
				$objSistemaRN = new SistemaRN();
				if (count($objSistemaRN->listar($objSistemaDTO))>0){
					$objInfraException->adicionarValidacao('Existem sistemas associados.');
				}
				
        $objInfraException->lancarValidacoes();
			}

			$objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
			
      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjHierarquiaDTO);$i++){
				

      	do{
					//recupera unidades pais da hierarquia
					$objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
					$objRelHierarquiaUnidadeDTO->retNumIdUnidadePai();
					$objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrObjHierarquiaDTO[$i]->getNumIdHierarquia());
					$objRelHierarquiaUnidadeDTO->setNumIdUnidadePai(null,InfraDTO::$OPER_DIFERENTE);
					$objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
					$objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
					$arrUnidadesPai = array_unique(InfraArray::converterArrInfraDTO($objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO),'IdUnidadePai'));
	      	
	      	
	      	//Recupera unidades que nao sao pais
					$objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
					$objRelHierarquiaUnidadeDTO->retNumIdUnidade();
					$objRelHierarquiaUnidadeDTO->retNumIdHierarquia();
					$objRelHierarquiaUnidadeDTO->setNumIdHierarquia($arrObjHierarquiaDTO[$i]->getNumIdHierarquia());
					$objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
					
					if (count($arrUnidadesPai)){
					  $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrUnidadesPai,InfraDTO::$OPER_NOT_IN);
					}
					
					$objRelHierarquiaUnidadeRN->excluir($objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO));
					
					//enquanto tiver alguma unidade pai
      	}while(count($arrUnidadesPai));
				
        $objHierarquiaBD->excluir($arrObjHierarquiaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Hierarquia.',$e);
    }
  }

  protected function desativarControlado($arrObjHierarquiaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_desativar',__METHOD__,$arrObjHierarquiaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjHierarquiaDTO);$i++){
        $objHierarquiaBD->desativar($arrObjHierarquiaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Hierarquia.',$e);
    }
  }

  protected function reativarControlado($arrObjHierarquiaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_reativar',__METHOD__,$arrObjHierarquiaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjHierarquiaDTO);$i++){
        $objHierarquiaBD->reativar($arrObjHierarquiaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Hierarquia.',$e);
    }
  }

  protected function consultarConectado(HierarquiaDTO $objHierarquiaDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_consultar',__METHOD__,$objHierarquiaDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      $ret = $objHierarquiaBD->consultar($objHierarquiaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Hierarquia.',$e);
    }
  }

  protected function listarConectado(HierarquiaDTO $objHierarquiaDTO) {
    try {

      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_listar',__METHOD__,$objHierarquiaDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      $ret = $objHierarquiaBD->listar($objHierarquiaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Hierarquias.',$e);
    }
  }

  protected function contarConectado(HierarquiaDTO $objHierarquiaDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('hierarquia_listar',__METHOD__,$objHierarquiaDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHierarquiaBD = new HierarquiaBD($this->getObjInfraIBanco());
      $ret = $objHierarquiaBD->contar($objHierarquiaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando unidades da hierarquia.',$e);
    }
  }

  private function validarStrNome(HierarquiaDTO $objHierarquiaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHierarquiaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }

    $objHierarquiaDTO->setStrNome(trim($objHierarquiaDTO->getStrNome()));

    if (strlen($objHierarquiaDTO->getStrNome())>50){
      $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
    }

    $dto = new HierarquiaDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->setNumIdHierarquia($objHierarquiaDTO->getNumIdHierarquia(),InfraDTO::$OPER_DIFERENTE);
    $dto->setStrNome($objHierarquiaDTO->getStrNome());
    $dto = $this->consultar($dto);
    if ($dto!=null){
      if ($dto->getStrSinAtivo()=='N'){
        $objInfraException->adicionarValidacao('Existe outra hierarquia inativa com o mesmo nome.');
      }else{
        $objInfraException->adicionarValidacao('Existe outra hierarquia com o mesmo nome.');
      }
    }
	}

  private function validarStrDescricao(HierarquiaDTO $objHierarquiaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHierarquiaDTO->getStrDescricao())){
      $objHierarquiaDTO->setStrDescricao(null);
    }

    $objHierarquiaDTO->setStrDescricao(trim($objHierarquiaDTO->getStrDescricao()));

    if (strlen($objHierarquiaDTO->getStrDescricao())>200){
      $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 200 caracteres.');
    }
  }
	
  private function validarDtaDataInicio(HierarquiaDTO $objHierarquiaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHierarquiaDTO->getDtaDataInicio())){
      $objInfraException->adicionarValidacao('Data Inicial não informada.');
    }

    if (!InfraData::validarData($objHierarquiaDTO->getDtaDataInicio())){
      $objInfraException->adicionarValidacao('Data Inicial inválida.');
    }
		
		//if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objHierarquiaDTO->getDtaDataInicio())<0){
		//	$objInfraException->adicionarValidacao('Data Inicial não pode estar no passado.');
		//}
	}

  private function validarDtaDataFim(HierarquiaDTO $objHierarquiaDTO, InfraException $objInfraException){
    if (!InfraData::validarData($objHierarquiaDTO->getDtaDataFim())){
      $objInfraException->adicionarValidacao('Data Final inválida.');
    }

		if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objHierarquiaDTO->getDtaDataFim())<0){
			$objInfraException->adicionarValidacao('Data Final não pode estar no passado.');
		}
	}
	
  private function validarPeriodoDatas(HierarquiaDTO $objHierarquiaDTO, InfraException $objInfraException){
		if(InfraData::compararDatas($objHierarquiaDTO->getDtaDataInicio(),$objHierarquiaDTO->getDtaDataFim())<0){
      $objInfraException->adicionarValidacao('Data Final deve ser igual ou superior a Data Inicial.');
		}
  }

  private function validarStrSinAtivo(HierarquiaDTO $objHierarquiaDTO, InfraException $objInfraException){
    if ($objHierarquiaDTO->getStrSinAtivo()===null || ($objHierarquiaDTO->getStrSinAtivo()!=='S' && $objHierarquiaDTO->getStrSinAtivo()!=='N')){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }
}

?>