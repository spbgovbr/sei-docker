<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2010 - criado por alexandre_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoEmailRN extends InfraRN {

  public static $TGE_INSTITUCIONAL = 'I';
  public static $TGE_UNIDADE = 'U';
  
	public function __construct(){
		parent::__construct();
	}

	protected function inicializarObjInfraIBanco(){
		return BancoSEI::getInstance();
	}

	private function validarNumIdUnidade(GrupoEmailDTO $objGrupoEmailDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objGrupoEmailDTO->getNumIdUnidade())){
			$objInfraException->adicionarValidacao('Unidade não informada.');
		}
	}

	private function validarStrNome(GrupoEmailDTO $objGrupoEmailDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objGrupoEmailDTO->getStrNome())){
			$objInfraException->adicionarValidacao('Nome não informado.');
		}else{
			$objGrupoEmailDTO->setStrNome(trim($objGrupoEmailDTO->getStrNome()));

			if (strlen($objGrupoEmailDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
				$objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
			}

			$dto = new GrupoEmailDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();

			$dto->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail(),InfraDTO::$OPER_DIFERENTE);

      if ($objGrupoEmailDTO->getStrStaTipo()==self::$TGE_UNIDADE) {
        $dto->setNumIdUnidade($objGrupoEmailDTO->getNumIdUnidade());
      }

			$dto->setStrNome($objGrupoEmailDTO->getStrNome());
			$dto->setStrStaTipo($objGrupoEmailDTO->getStrStaTipo());

			$dto = $this->consultar($dto);

			if ($dto!=null) {
				if ($dto->getStrSinAtivo()=='S') {
					if ($objGrupoEmailDTO->getStrStaTipo()==self::$TGE_INSTITUCIONAL) {
						$objInfraException->adicionarValidacao('Existe outro Grupo de E-mail Institucional com este Nome.');
					} else {
						$objInfraException->adicionarValidacao('Existe outro Grupo de E-mail com este Nome para esta Unidade.');
					}

				} else {
					if ($objGrupoEmailDTO->getStrStaTipo()==self::$TGE_INSTITUCIONAL) {
						$objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de E-mail Institucional com este Nome.');
					} else {
						$objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de E-mail com este Nome para esta Unidade.');
					}

				}
			}
		}
	}

  public function getNumMaxTamanhoNome(){
    return 50;
  }

	private function validarStrDescricao(GrupoEmailDTO $objGrupoEmailDTO, InfraException $objInfraException){
		if (InfraString::isBolVazia($objGrupoEmailDTO->getStrDescricao())){
			$objGrupoEmailDTO->setStrDescricao(null);
		}else{
			$objGrupoEmailDTO->setStrDescricao(trim($objGrupoEmailDTO->getStrDescricao()));

			if (strlen($objGrupoEmailDTO->getStrDescricao())>250){
				$objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
			}
		}
	}

  private function validarStrStaTipo(GrupoEmailDTO $objGrupoEmailDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoEmailDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objGrupoEmailDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }
	
  private function validarArrObjEmailGrupoEmail(GrupoEmailDTO $objGrupoEmailDTO, InfraException $objInfraException){
  	/*    
    if (count($objGrupoEmailDTO->getArrObjEmailGrupoEmailDTO())>1){
      $objInfraException->adicionarValidacao('Já existe este e-mail cadastrado.');
    }
    */
  }	
  
  private function validarStrSinAtivo(GrupoEmailDTO $objGrupoEmailDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoEmailDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objGrupoEmailDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
  
  public function listarValoresTipo(){
    try {

      $arrObjTipoDTO = array();

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGE_INSTITUCIONAL);
      $objTipoDTO->setStrDescricao('Institucional');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGE_UNIDADE);
      $objTipoDTO->setStrDescricao('Unidade');
      $arrObjTipoDTO[] = $objTipoDTO;
      
      return $arrObjTipoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }  

	protected function cadastrarControlado(GrupoEmailDTO $objGrupoEmailDTO) {
		try{

			//Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_cadastrar',__METHOD__,$objGrupoEmailDTO);

			//Regras de Negocio
			$objInfraException = new InfraException();

			$this->validarNumIdUnidade($objGrupoEmailDTO, $objInfraException);
			$this->validarStrNome($objGrupoEmailDTO, $objInfraException);
			$this->validarStrDescricao($objGrupoEmailDTO, $objInfraException);
			$this->validarStrStaTipo($objGrupoEmailDTO, $objInfraException);
			$this->validarStrSinAtivo($objGrupoEmailDTO, $objInfraException);

			$objInfraException->lancarValidacoes();
			
			$objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
			$ret = $objGrupoEmailBD->cadastrar($objGrupoEmailDTO);
			
			$arrObjEmailGrupoEmailDTO =  $objGrupoEmailDTO->getArrObjEmailGrupoEmailDTO();
			
			$objEmailGrupoEmailRN = new EmailGrupoEmailRN();
			foreach($arrObjEmailGrupoEmailDTO as $objEmailGrupoEmailDTO){
				$objEmailGrupoEmailDTO->setNumIdGrupoEmail($ret->getNumIdGrupoEmail());
			  $objEmailGrupoEmailRN->cadastrar($objEmailGrupoEmailDTO);      	
			}

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro cadastrando Grupo de E-mail.',$e);
		}
	}

	protected function alterarControlado(GrupoEmailDTO $objGrupoEmailDTO){
		try {

			//Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_alterar',__METHOD__,$objGrupoEmailDTO);

			//Regras de Negocio
			$objInfraException = new InfraException();

			$objGrupoEmailDTOBanco = new GrupoEmailDTO();
			$objGrupoEmailDTOBanco->retNumIdUnidade();
			$objGrupoEmailDTOBanco->retStrStaTipo();
			$objGrupoEmailDTOBanco->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail());
			$objGrupoEmailDTOBanco = $this->consultar($objGrupoEmailDTOBanco);

			if ($objGrupoEmailDTO->isSetNumIdUnidade() && $objGrupoEmailDTO->getNumIdUnidade()!=$objGrupoEmailDTOBanco->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Unidade do Grupo de E-mail não pode ser alterada.');
			}else{
				$objGrupoEmailDTO->setNumIdUnidade($objGrupoEmailDTOBanco->getNumIdUnidade());
			}

			if ($objGrupoEmailDTO->isSetStrStaTipo() && $objGrupoEmailDTO->getStrStaTipo()!=$objGrupoEmailDTOBanco->getStrStaTipo()){
        $objInfraException->lancarValidacao('Tipo do Grupo de E-mail não pode ser alterado.');
			}else{
				$objGrupoEmailDTO->setStrStaTipo($objGrupoEmailDTOBanco->getStrStaTipo());
			}

			if ($objGrupoEmailDTO->isSetStrNome()){
				$this->validarStrNome($objGrupoEmailDTO, $objInfraException);
			}
			if ($objGrupoEmailDTO->isSetStrDescricao()){
				$this->validarStrDescricao($objGrupoEmailDTO, $objInfraException);
			}
			if ($objGrupoEmailDTO->isSetStrSinAtivo()){
				$this->validarStrSinAtivo($objGrupoEmailDTO, $objInfraException);
			}
			
			$objInfraException->lancarValidacoes();
			
      if ($objGrupoEmailDTO->isSetArrObjEmailGrupoEmailDTO()){
        
        $objEmailGrupoEmailDTO = new EmailGrupoEmailDTO();
        $objEmailGrupoEmailDTO->retNumIdEmailGrupoEmail();
        $objEmailGrupoEmailDTO->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail());
        
        $objEmailGrupoEmailRN = new EmailGrupoEmailRN();
        $objEmailGrupoEmailRN->excluir($objEmailGrupoEmailRN->listar($objEmailGrupoEmailDTO));
        
        $arrObjEmailGrupoEmailDTO = $objGrupoEmailDTO->getArrObjEmailGrupoEmailDTO();
				foreach($arrObjEmailGrupoEmailDTO as $objEmailGrupoEmailDTO){
					$objEmailGrupoEmailDTO->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail());
				  $objEmailGrupoEmailRN->cadastrar($objEmailGrupoEmailDTO);      	
				}
      }			

			$objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
			$objGrupoEmailBD->alterar($objGrupoEmailDTO);

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro alterando Grupo de E-mail.',$e);
		}
	}

	protected function excluirControlado($arrObjGrupoEmailDTO){
		try {

			//Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_excluir',__METHOD__,$arrObjGrupoEmailDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();
      $objEmailGrupoEmailRN = new EmailGrupoEmailRN();
      $objEmailGrupoEmailDTO = new EmailGrupoEmailDTO();      
      $objEmailGrupoEmailDTO->retNumIdEmailGrupoEmail();

      $objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoEmailDTO);$i++){
        $objEmailGrupoEmailDTO->setNumIdGrupoEmail($arrObjGrupoEmailDTO[$i]->getNumIdGrupoEmail());
        $objEmailGrupoEmailRN->excluir($objEmailGrupoEmailRN->listar($objEmailGrupoEmailDTO));
        $objGrupoEmailBD->excluir($arrObjGrupoEmailDTO[$i]);
      }

			//Auditoria

		}catch(Exception $e){
			throw new InfraException('Erro excluindo Grupo de E-mail.',$e);
		}
	}

	protected function consultarConectado(GrupoEmailDTO $objGrupoEmailDTO){
		try {

			//Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_consultar',__METHOD__,$objGrupoEmailDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
		
			$ret = $objGrupoEmailBD->consultar($objGrupoEmailDTO);
    
			//Auditoria

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro consultando Grupo de E-mail.',$e);
		}
	}

	protected function listarConectado(GrupoEmailDTO $objGrupoEmailDTO) {
		try {

			//Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_listar',__METHOD__,$objGrupoEmailDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
			$ret = $objGrupoEmailBD->listar($objGrupoEmailDTO);

			//Auditoria

			return $ret;

		}catch(Exception $e){
			throw new InfraException('Erro listando Grupos de E-mail.',$e);
		}
	}

	protected function contarConectado(GrupoEmailDTO $objGrupoEmailDTO){
		try {

			//Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_listar',__METHOD__,$objGrupoEmailDTO);

			//Regras de Negocio
			//$objInfraException = new InfraException();

			//$objInfraException->lancarValidacoes();

			$objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
			$ret = $objGrupoEmailBD->contar($objGrupoEmailDTO);

			//Auditoria

			return $ret;
		}catch(Exception $e){
			throw new InfraException('Erro contando Grupos de E-mail.',$e);
		}
	}
	
  protected function desativarControlado($arrObjGrupoEmailDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_institucional_desativar',__METHOD__,$arrObjGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoEmailDTO);$i++){
        $objGrupoEmailBD->desativar($arrObjGrupoEmailDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de E-mail.',$e);
    }
  }

  protected function reativarControlado($arrObjGrupoEmailDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_email_institucional_reativar',__METHOD__,$arrObjGrupoEmailDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoEmailBD = new GrupoEmailBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoEmailDTO);$i++){
        $objGrupoEmailBD->reativar($arrObjGrupoEmailDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de E-mail.',$e);
    }
  }
	
}
?>