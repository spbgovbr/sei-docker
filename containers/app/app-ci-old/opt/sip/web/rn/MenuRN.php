<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class MenuRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(MenuDTO $objMenuDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('menu_cadastrar',__METHOD__,$objMenuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objMenuDTO, $objInfraException);
      $this->validarStrNome($objMenuDTO, $objInfraException);
      $this->validarStrDescricao($objMenuDTO, $objInfraException);
      $this->validarStrSinAtivo($objMenuDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      $ret = $objMenuBD->cadastrar($objMenuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Menu.',$e);
    }
  }

  protected function alterarControlado(MenuDTO $objMenuDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('menu_alterar',__METHOD__,$objMenuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objMenuDTO, $objInfraException);
      $this->validarStrNome($objMenuDTO, $objInfraException);
      $this->validarStrDescricao($objMenuDTO, $objInfraException);
      $this->validarStrSinAtivo($objMenuDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      $objMenuBD->alterar($objMenuDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Menu.',$e);
    }
  }

  protected function excluirControlado($arrObjMenuDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('menu_excluir',__METHOD__,$arrObjMenuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMenuDTO);$i++){
				
				//Exclui itens de menu associados
				$objItemMenuDTO = new ItemMenuDTO();
				$objItemMenuDTO->retNumIdMenu();
				$objItemMenuDTO->retNumIdItemMenu();
				$objItemMenuDTO->setNumIdMenu($arrObjMenuDTO[$i]->getNumIdMenu());
				$objItemMenuDTO->setBolExclusaoLogica(false);
				$objItemMenuRN = new ItemMenuRN();
				$arrObjItemMenuDTO = $objItemMenuRN->listarHierarquia($objItemMenuDTO);
				
        //Tem que excluir partindo das folhas até a raiz
				//Descobre qual o nível mais baixo
				$numNivel=0;
				foreach($arrObjItemMenuDTO as $dto){
				  if (strlen($dto->getStrRamificacao())>$numNivel){
				    $numNivel = strlen($dto->getStrRamificacao());
				  }
				}
				
				while($numNivel>=0){
  				foreach($arrObjItemMenuDTO as $dto){
  				  if (strlen($dto->getStrRamificacao())==$numNivel){
  				    $objItemMenuRN->excluir(array($dto));
  				  }
  				}
	        $numNivel--; 			  
				}
				

				//$objItemMenuRN->excluir($objItemMenuRN->listar($objItemMenuDTO));
				
				
        $objMenuBD->excluir($arrObjMenuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Menu.',$e);
    }
  }

  protected function desativarControlado($arrObjMenuDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('menu_desativar',__METHOD__,$arrObjMenuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMenuDTO);$i++){
				
				//Desativa itens de menu associados
				$objItemMenuDTO = new ItemMenuDTO();
				$objItemMenuDTO->retNumIdMenu();
				$objItemMenuDTO->retNumIdItemMenu();
				$objItemMenuDTO->setNumIdMenu($arrObjMenuDTO[$i]->getNumIdMenu());
				$objItemMenuRN = new ItemMenuRN();
				$objItemMenuRN->desativar($objItemMenuRN->listar($objItemMenuDTO));
				
        $objMenuBD->desativar($arrObjMenuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Menu.',$e);
    }
  }

  protected function consultarConectado(MenuDTO $objMenuDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('menu_consultar',__METHOD__,$objMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      $ret = $objMenuBD->consultar($objMenuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Menu.',$e);
    }
  }

  protected function listarConectado(MenuDTO $objMenuDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('menu_listar',__METHOD__,$objMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      $ret = $objMenuBD->listar($objMenuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Menus.',$e);
    }
  }

  protected function contarConectado(MenuDTO $objMenuDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('menu_listar',__METHOD__,$objMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMenuBD = new MenuBD($this->getObjInfraIBanco());
      $ret = $objMenuBD->contar($objMenuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando Menus.',$e);
    }
  }
  
  protected function listarAdministradosConectado(MenuDTO $objMenuDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('menu_listar',__METHOD__,$objMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

			//Retorna o ID para fechar com os sistemas administrados
			$objMenuDTO->retNumIdSistema();
			
      $arrObjMenuDTO = $this->listar($objMenuDTO);

			//Obtem sistemas acessados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

			$ret = InfraArray::joinArrInfraDTO($arrObjMenuDTO,'IdSistema',$arrObjAcessoDTO,'IdSistema');
			
      //Auditoria
			
			return $ret;
			

    }catch(Exception $e){
      throw new InfraException('Erro listando Menus administrados.',$e);
    }
  }
	
  private function validarNumIdSistema(MenuDTO $objMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMenuDTO->getNumIdSistema())){
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarStrNome(MenuDTO $objMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMenuDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }
  }

  private function validarStrDescricao(MenuDTO $objMenuDTO, InfraException $objInfraException){
  }

  private function validarStrSinAtivo(MenuDTO $objMenuDTO, InfraException $objInfraException){
    if ($objMenuDTO->getStrSinAtivo()===null || ($objMenuDTO->getStrSinAtivo()!=='S' && $objMenuDTO->getStrSinAtivo()!=='N')){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

}
?>