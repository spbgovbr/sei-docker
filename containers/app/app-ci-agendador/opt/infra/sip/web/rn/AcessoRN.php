<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class AcessoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

	/**
			Lista os sistemas onde, dependendo do parâmetro recebido, o usuário é:
			1 - administrador
			2 - é coordenador de algum perfil (retorna um registro por perfil)
			3 - é coordenador em alguma unidade (retorna um registro por unidade)
			4 - possui alguma permissao (retorna um registro por perfil/unidade)
	*/
  protected function obterAcessosConectado(AcessoDTO $objAcessoDTO) {
    try {

			//Obtem ID do usuário
			$numIdUsuario = SessaoSip::getInstance()->getNumIdUsuario();
			
			$objPermissaoRN = new PermissaoRN();
			
			//Inicializa array de retorno
			$ret = array();
			
			$objPermissaoDTOCriterios = new PermissaoDTO();
			$objPermissaoDTOCriterios->setNumIdUsuario($numIdUsuario);
			$objPermissaoDTOCriterios->setDtaDataInicio(InfraData::getStrDataAtual(),InfraDTO::$OPER_MENOR_IGUAL);
			$objPermissaoDTOCriterios->setStrSinAtivoPerfil('S');
			$objPermissaoDTOCriterios->setStrSinAtivoSistema('S');
			$objPermissaoDTOCriterios->setStrSinAtivoUsuario('S');
			$objPermissaoDTOCriterios->setStrSinAtivoUnidade('S');
			$objPermissaoDTOCriterios->adicionarCriterio(array('DataFim','DataFim'),
                                        			     array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_IGUAL),
                                        			     array(InfraData::getStrDataAtual(),null),
                                        			     InfraDTO::$OPER_LOGICO_OR);			
			
			if ( ($objAcessoDTO->getNumTipo() & AcessoDTO::$ADMINISTRADOR) or
				   ($objAcessoDTO->getNumTipo() & AcessoDTO::$TODOS)){
				   	
				//Busca sistemas onde o usuário é administrador
				$objAdministradorSistemaDTO = new AdministradorSistemaDTO();
		    $objAdministradorSistemaDTO->retNumIdSistema();
		    $objAdministradorSistemaDTO->retStrSiglaSistema();
		    $objAdministradorSistemaDTO->retNumIdOrgaoSistema();
			  $objAdministradorSistemaDTO->setNumIdUsuario($numIdUsuario);
				
				if ($objAcessoDTO->isSetNumIdSistema()){
					$objAdministradorSistemaDTO->setNumIdSistema($objAcessoDTO->getNumIdSistema());
				}
			  
				$objAdministradorSistemaRN = new AdministradorSistemaRN();
				$arrObjAdministradorSistemaDTO = $objAdministradorSistemaRN->listar($objAdministradorSistemaDTO);
				
				foreach($arrObjAdministradorSistemaDTO as $objAdministradorSistemaDTO){
				  $dto = new AcessoDTO();
					$dto->setNumTipo(AcessoDTO::$ADMINISTRADOR);
					$dto->setNumIdSistema($objAdministradorSistemaDTO->getNumIdSistema());
					$dto->setStrSiglaSistema($objAdministradorSistemaDTO->getStrSiglaSistema());
					$dto->setNumIdOrgaoSistema($objAdministradorSistemaDTO->getNumIdOrgaoSistema());
					$ret[]=$dto;
				}
			}

			if ( ($objAcessoDTO->getNumTipo() & AcessoDTO::$COORDENADOR_PERFIL) or
				   ($objAcessoDTO->getNumTipo() & AcessoDTO::$TODOS)){
				//Busca os sistemas onde o usuario é coordenador de algum perfil
				$objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
			  $objCoordenadorPerfilDTO->setDistinct(true);
			  $objCoordenadorPerfilDTO->retNumIdSistema();
				$objCoordenadorPerfilDTO->retNumIdPerfil();
				$objCoordenadorPerfilDTO->retStrSiglaSistema();
				$objCoordenadorPerfilDTO->retNumIdOrgaoSistema();
			  $objCoordenadorPerfilDTO->setNumIdUsuario($numIdUsuario);
			
				if ($objAcessoDTO->isSetNumIdSistema()){
					$objCoordenadorPerfilDTO->setNumIdSistema($objAcessoDTO->getNumIdSistema());
				}
			  
				$objCoordenadorPerfilRN = new CoordenadorPerfilRN();
				$arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO);
				
				foreach($arrObjCoordenadorPerfilDTO as $objCoordenadorPerfilDTO){
					$dto = new AcessoDTO();
					$dto->setNumTipo(AcessoDTO::$COORDENADOR_PERFIL);
					$dto->setNumIdSistema($objCoordenadorPerfilDTO->getNumIdSistema());
					$dto->setStrSiglaSistema($objCoordenadorPerfilDTO->getStrSiglaSistema());
					$dto->setNumIdOrgaoSistema($objCoordenadorPerfilDTO->getNumIdOrgaoSistema());
					$dto->setNumIdPerfil($objCoordenadorPerfilDTO->getNumIdPerfil());
					$ret[]=$dto;
				}
			}
			
			if ( ($objAcessoDTO->getNumTipo() & AcessoDTO::$COORDENADOR_UNIDADE) or
				   ($objAcessoDTO->getNumTipo() & AcessoDTO::$TODOS)){
				
			  //Busca os sistemas onde o usuario é coordenador de alguma unidade
				$objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO();
				$objCoordenadorUnidadeDTO->setDistinct(true);
				$objCoordenadorUnidadeDTO->retNumIdSistema();
				$objCoordenadorUnidadeDTO->retNumIdUnidade();
        $objCoordenadorUnidadeDTO->retNumIdOrgaoUnidade();
        $objCoordenadorUnidadeDTO->retStrSinGlobalUnidade();
				$objCoordenadorUnidadeDTO->retStrSiglaSistema();
				$objCoordenadorUnidadeDTO->retNumIdOrgaoSistema();
				$objCoordenadorUnidadeDTO->setNumIdUsuario($numIdUsuario);
				
				if ($objAcessoDTO->isSetNumIdSistema()){
					$objCoordenadorUnidadeDTO->setNumIdSistema($objAcessoDTO->getNumIdSistema());
				}
				
				$objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
				$arrObjCoordenadorUnidadeDTO = $objCoordenadorUnidadeRN->listar($objCoordenadorUnidadeDTO);
				
				if (count($arrObjCoordenadorUnidadeDTO)>0){

					$objPerfilRN = new PerfilRN();
	
					$objPerfilDTO = new PerfilDTO();
					$objPerfilDTO->retNumIdSistema();
					$objPerfilDTO->retNumIdPerfil();
					$objPerfilDTO->setNumIdSistema(array_unique(InfraArray::converterArrInfraDTO($arrObjCoordenadorUnidadeDTO,'IdSistema')),InfraDTO::$OPER_IN);
					$objPerfilDTO->setStrSinCoordenado('S');
					$arrObjPerfisCoordenados = InfraArray::indexarArrInfraDTO($objPerfilRN->listar($objPerfilDTO),'IdSistema',true);

					foreach($arrObjCoordenadorUnidadeDTO as $objCoordenadorUnidadeDTO){
						if (isset($arrObjPerfisCoordenados[$objCoordenadorUnidadeDTO->getNumIdSistema()])){
							foreach($arrObjPerfisCoordenados[$objCoordenadorUnidadeDTO->getNumIdSistema()] as $objPerfilDTO){

							  $objPermissaoDTO = clone($objPermissaoDTOCriterios);
							  $objPermissaoDTO->setNumIdSistema($objCoordenadorUnidadeDTO->getNumIdSistema());
							  $objPermissaoDTO->setNumIdUnidade($objCoordenadorUnidadeDTO->getNumIdUnidade());
							  $objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
							  	
							  
							  if ($objPermissaoRN->contar($objPermissaoDTO)>0){
  								$dto = new AcessoDTO();
  								$dto->setNumTipo(AcessoDTO::$COORDENADOR_UNIDADE);
  								$dto->setNumIdSistema($objCoordenadorUnidadeDTO->getNumIdSistema());
  								$dto->setStrSiglaSistema($objCoordenadorUnidadeDTO->getStrSiglaSistema());
  								$dto->setNumIdOrgaoSistema($objCoordenadorUnidadeDTO->getNumIdOrgaoSistema());
                  $dto->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
  								$dto->setNumIdUnidade($objCoordenadorUnidadeDTO->getNumIdUnidade());
                  $dto->setNumIdOrgaoUnidade($objCoordenadorUnidadeDTO->getNumIdOrgaoUnidade());
                  $dto->setStrSinGlobalUnidade($objCoordenadorUnidadeDTO->getStrSinGlobalUnidade());
  								$ret[]=$dto;
							  }
							}
						}
					}
				}
			}

			if ( ($objAcessoDTO->getNumTipo() & AcessoDTO::$PERMISSAO) or
				   ($objAcessoDTO->getNumTipo() & AcessoDTO::$TODOS)){

					//Busca os sistemas onde o usuario possui permissao
				  $objPermissaoDTO = clone($objPermissaoDTOCriterios);
					$objPermissaoDTO->retNumIdSistema();
					$objPermissaoDTO->retNumIdUnidade();
					$objPermissaoDTO->retNumIdPerfil();
					$objPermissaoDTO->retStrSiglaSistema();
					$objPermissaoDTO->retNumIdOrgaoSistema();
					
					if ($objAcessoDTO->isSetNumIdSistema()){
						$objPermissaoDTO->setNumIdSistema($objAcessoDTO->getNumIdSistema());
					}
					
					$arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);
					
					foreach($arrObjPermissaoDTO as $objPermissaoDTO){
						$dto = new AcessoDTO();
						$dto->setNumTipo(AcessoDTO::$PERMISSAO);
						$dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
						$dto->setStrSiglaSistema($objPermissaoDTO->getStrSiglaSistema());
						$dto->setNumIdOrgaoSistema($objPermissaoDTO->getNumIdOrgaoSistema());
						$dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
						$dto->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
						$ret[]=$dto;
				 }
			}
			
      //Auditoria
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo Sistemas acessados.',$e);
    }
  }
	
}
?>