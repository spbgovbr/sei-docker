<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class CoordenadorUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_cadastrar',__METHOD__,$objCoordenadorUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objCoordenadorUnidadeDTO,$objInfraException);
			$this->validarNumIdUsuario($objCoordenadorUnidadeDTO,$objInfraException);
			$this->validarNumIdUnidade($objCoordenadorUnidadeDTO,$objInfraException);
			
			if ($this->contar($objCoordenadorUnidadeDTO)){
			  $objInfraException->adicionarValidacao('Este usuário já é coordenador desta unidade.');
			}

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->setNumIdSistema($objCoordenadorUnidadeDTO->getNumIdSistema());

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      //não está na hierarquia
      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
      $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());
      $objRelHierarquiaUnidadeDTO->setNumIdUnidade($objCoordenadorUnidadeDTO->getNumIdUnidade());

      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      if ($objRelHierarquiaUnidadeRN->contar($objRelHierarquiaUnidadeDTO)==0){

        //não é global
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setStrSinGlobal('S');
        $objUnidadeDTO->setNumIdUnidade($objCoordenadorUnidadeDTO->getNumIdUnidade());

        $objUnidadeRN = new UnidadeRN();
        if ($objUnidadeRN->contar($objUnidadeDTO)==0) {
          $objInfraException->adicionarValidacao('Unidade não consta na hierarquia do sistema.');
        }
      }

      $objInfraException->lancarValidacoes();

      
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      
      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdUsuario($objCoordenadorUnidadeDTO->getNumIdUsuario());
      $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_UNIDADE'));
      
      $objPermissaoRN = new PermissaoRN();
      $objPermissaoRN->adicionarPerfilReservado($objPermissaoDTO);
      
      $objCoordenadorUnidadeBD = new CoordenadorUnidadeBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorUnidadeBD->cadastrar($objCoordenadorUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Coordenador de Unidade.',$e);
    }
  }

  protected function alterarControlado(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_alterar',__METHOD__,$objCoordenadorUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objCoordenadorUnidadeDTO,$objInfraException);
			$this->validarNumIdUsuario($objCoordenadorUnidadeDTO,$objInfraException);
			$this->validarNumIdUnidade($objCoordenadorUnidadeDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objCoordenadorUnidadeBD = new CoordenadorUnidadeBD($this->getObjInfraIBanco());
      $objCoordenadorUnidadeBD->alterar($objCoordenadorUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Coordenador de Unidade.',$e);
    }
  }

  protected function excluirControlado($arrObjCoordenadorUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_excluir',__METHOD__,$arrObjCoordenadorUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $objPermissaoRN = new PermissaoRN();
      
      $objCoordenadorUnidadeBD = new CoordenadorUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCoordenadorUnidadeDTO);$i++){
        $objCoordenadorUnidadeBD->excluir($arrObjCoordenadorUnidadeDTO[$i]);
        
        $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO();
        $objCoordenadorUnidadeDTO->setNumIdUsuario($arrObjCoordenadorUnidadeDTO[$i]->getNumIdUsuario());
        if ($this->contar($objCoordenadorUnidadeDTO)==0){
          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumIdUsuario($arrObjCoordenadorUnidadeDTO[$i]->getNumIdUsuario());
          $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_UNIDADE'));
          $objPermissaoRN->removerPerfilReservado($objPermissaoDTO);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Coordenador de Unidade.',$e);
    }
  }

  protected function consultarConectado(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_consultar',__METHOD__,$objCoordenadorUnidadeDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCoordenadorUnidadeBD = new CoordenadorUnidadeBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorUnidadeBD->consultar($objCoordenadorUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Coordenador de Unidade.',$e);
    }
  }

  protected function contarConectado(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO) {
    try {
      ////////////////////////////////////////////////////////////////////// 
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_contar',__METHOD__,$objCoordenadorUnidadeDTO);
			//////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCoordenadorUnidadeBD = new CoordenadorUnidadeBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorUnidadeBD->contar($objCoordenadorUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando Coordenadores de Unidade.',$e);
    }
  }
  
  
  protected function listarConectado(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_listar',__METHOD__,$objCoordenadorUnidadeDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCoordenadorUnidadeBD = new CoordenadorUnidadeBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorUnidadeBD->listar($objCoordenadorUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Coordenadores de Unidade.',$e);
    }
  }
  
	protected function listarAdministradosConectado($objCoordenadorUnidadeDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_unidade_listar',__METHOD__,$objCoordenadorUnidadeDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

			//Retorna o ID para fechar com os sistemas Administrados
			$objCoordenadorUnidadeDTO->retNumIdSistema();
			
      $arrObjCoordenadorUnidadeDTO = $this->listar($objCoordenadorUnidadeDTO);
			
		  //Obtem sistemas administrados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

			$ret = InfraArray::joinArrInfraDTO($arrObjCoordenadorUnidadeDTO,'IdSistema',$arrObjAcessoDTO,'IdSistema');
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Coordenadores de Unidade por sistemas administrados.',$e);
    }
  }
	
  private function validarNumIdSistema(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCoordenadorUnidadeDTO->getNumIdSistema())){
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
	}

  private function validarNumIdUsuario(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCoordenadorUnidadeDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
	}

  private function validarNumIdUnidade(CoordenadorUnidadeDTO $objCoordenadorUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCoordenadorUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
	}

}
?>