<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class CoordenadorPerfilRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }
  
  protected function cadastrarMultiploControlado(CoordenadorPerfilDTO $parObjCoordenadorPerfilDTO) {
    try{
      
      SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_cadastrar', __METHOD__, $parObjCoordenadorPerfilDTO);
      
      //$objInfraException = new InfraException();
      //$objInfraException->lancarValidacoes();
      
			$objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
			$objCoordenadorPerfilDTO->retNumIdPerfil();
			$objCoordenadorPerfilDTO->setNumIdSistema($parObjCoordenadorPerfilDTO->getNumIdSistema());
			$objCoordenadorPerfilDTO->setNumIdUsuario($parObjCoordenadorPerfilDTO->getNumIdUsuario());
			
			$arrPerfisCoordenadosBanco = InfraArray::converterArrInfraDTO($this->listar($objCoordenadorPerfilDTO),'IdPerfil');
			$arrPerfisCoordenadosNovos = InfraArray::converterArrInfraDTO($parObjCoordenadorPerfilDTO->getArrObjPerfilDTO(),'IdPerfil');

			foreach($arrPerfisCoordenadosNovos as $numIdPerfilNovo){
			  if (!in_array($numIdPerfilNovo,$arrPerfisCoordenadosBanco)){
			    $dto = new CoordenadorPerfilDTO();
			    $dto->setNumIdUsuario($parObjCoordenadorPerfilDTO->getNumIdUsuario());
			    $dto->setNumIdSistema($parObjCoordenadorPerfilDTO->getNumIdSistema());
			    $dto->setNumIdPerfil($numIdPerfilNovo);
			    $this->cadastrar($dto);
			  }
			}
			
			foreach($arrPerfisCoordenadosBanco as $numIdPerfilBanco){
			  if (!in_array($numIdPerfilBanco,$arrPerfisCoordenadosNovos)){
			    $dto = new CoordenadorPerfilDTO();
			    $dto->setNumIdUsuario($parObjCoordenadorPerfilDTO->getNumIdUsuario());
			    $dto->setNumIdSistema($parObjCoordenadorPerfilDTO->getNumIdSistema());
			    $dto->setNumIdPerfil($numIdPerfilBanco);
			    $this->excluir(array($dto));
			  }
			}
      
    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Coordenador de Perfil.',$e);
    }
      
  }

  protected function cadastrarControlado(CoordenadorPerfilDTO $objCoordenadorPerfilDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_cadastrar', __METHOD__, $objCoordenadorPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPerfil($objCoordenadorPerfilDTO,$objInfraException);
      $this->validarNumIdUsuario($objCoordenadorPerfilDTO,$objInfraException);
      $this->validarNumIdSistema($objCoordenadorPerfilDTO,$objInfraException);
      
      if ($this->contar($objCoordenadorPerfilDTO)){
        $objInfraException->adicionarValidacao('Este usuário já é coordenador deste perfil.');
      }

      $objInfraException->lancarValidacoes();
      
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      
      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdUsuario($objCoordenadorPerfilDTO->getNumIdUsuario());
      $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_PERFIL'));
      
      $objPermissaoRN = new PermissaoRN();
      $objPermissaoRN->adicionarPerfilReservado($objPermissaoDTO);
      
      $objCoordenadorPerfilBD = new CoordenadorPerfilBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorPerfilBD->cadastrar($objCoordenadorPerfilDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Coordenador de Perfil.',$e);
    }
  }

  protected function alterarControlado(CoordenadorPerfilDTO $objCoordenadorPerfilDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_alterar', __METHOD__,$objCoordenadorPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPerfil($objCoordenadorPerfilDTO,$objInfraException);
      $this->validarNumIdUsuario($objCoordenadorPerfilDTO,$objInfraException);
      $this->validarNumIdSistema($objCoordenadorPerfilDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objCoordenadorPerfilBD = new CoordenadorPerfilBD($this->getObjInfraIBanco());
      $objCoordenadorPerfilBD->alterar($objCoordenadorPerfilDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Coordenador de Perfil.',$e);
    }
  }

  protected function excluirControlado($arrObjCoordenadorPerfilDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_excluir', __METHOD__, $arrObjCoordenadorPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $objPermissaoRN = new PermissaoRN();
      
      $objCoordenadorPerfilBD = new CoordenadorPerfilBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCoordenadorPerfilDTO);$i++){
        $objCoordenadorPerfilBD->excluir($arrObjCoordenadorPerfilDTO[$i]);
        
        //Se o usuário não é mais administrador de nenhum sistema remove o perfil "Administrador de Sistema" do SIP das suas permissões
        $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
        $objCoordenadorPerfilDTO->setNumIdUsuario($arrObjCoordenadorPerfilDTO[$i]->getNumIdUsuario());
        if ($this->contar($objCoordenadorPerfilDTO)==0){
          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumIdUsuario($arrObjCoordenadorPerfilDTO[$i]->getNumIdUsuario());
          $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_PERFIL'));
          $objPermissaoRN->removerPerfilReservado($objPermissaoDTO);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Coordenador de Perfil.',$e);
    }
  }

  protected function consultarConectado(CoordenadorPerfilDTO $objCoordenadorPerfilDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_consultar', __METHOD__,$objCoordenadorPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCoordenadorPerfilBD = new CoordenadorPerfilBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorPerfilBD->consultar($objCoordenadorPerfilDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Coordenador de Perfil.',$e);
    }
  }

  protected function contarConectado(CoordenadorPerfilDTO $objCoordenadorPerfilDTO) {
    try {
      ////////////////////////////////////////////////////////////////////// 
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_contar', __METHOD__,$objCoordenadorPerfilDTO);
			//////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCoordenadorPerfilBD = new CoordenadorPerfilBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorPerfilBD->contar($objCoordenadorPerfilDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando Coordenadores de Perfis.',$e);
    }
  }
  
  protected function listarConectado(CoordenadorPerfilDTO $objCoordenadorPerfilDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_listar', __METHOD__,$objCoordenadorPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCoordenadorPerfilBD = new CoordenadorPerfilBD($this->getObjInfraIBanco());
      $ret = $objCoordenadorPerfilBD->listar($objCoordenadorPerfilDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Coordenadores de Perfis.',$e);
    }
  }

	protected function listarAdministradosConectado($objCoordenadorPerfilDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('coordenador_perfil_listar', __METHOD__,$objCoordenadorPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

			//Retorna o ID para fechar com os sistemas Administrados
			$objCoordenadorPerfilDTO->retNumIdSistema();
			
      $arrObjCoordenadorPerfilDTO = $this->listar($objCoordenadorPerfilDTO);
			
		  //Obtem sistemas administrados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

			$ret = InfraArray::joinArrInfraDTO($arrObjCoordenadorPerfilDTO,'IdSistema',$arrObjAcessoDTO,'IdSistema');
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Coordenadores de Perfil por sistemas administrados.',$e);
    }
  }
	
	
  private function validarNumIdPerfil(CoordenadorPerfilDTO $objCoordenadorPerfilDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCoordenadorPerfilDTO->getNumIdPerfil())){
      $objInfraException->adicionarValidacao('Perfil não informado.');
    }
  }
	
  private function validarNumIdUsuario(CoordenadorPerfilDTO $objCoordenadorPerfilDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCoordenadorPerfilDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }
	
  private function validarNumIdSistema(CoordenadorPerfilDTO $objCoordenadorPerfilDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCoordenadorPerfilDTO->getNumIdSistema())){
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }

  }

}
?>