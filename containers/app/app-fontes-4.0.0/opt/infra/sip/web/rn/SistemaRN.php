<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class SistemaRN extends InfraRN {

  public static $TBD_MYSQL = 'M';
  public static $TBD_ORACLE = 'O';
  public static $TBD_POSTGRESQL = 'P';
  public static $TBD_SQLSERVER = 'S';

  public static $T2E_INDISPONIVEL = 'I';
  public static $T2E_OPCIONAL = 'P';
  public static $T2E_OBRIGATORIA = 'O';

  public static $TS_AUTENTICACAO_USUARIO = '1';
  public static $TS_PESQUISA_USUARIOS = '2';
  public static $TS_PESQUISA_UNIDADES = '3';
  public static $TS_PESQUISA_PERMISSOES = '4';
  public static $TS_PESQUISA_PERFIS = '5';
  public static $TS_PESQUISA_RECURSOS = '6';
  public static $TS_REPLICACAO_USUARIOS = '7';
  public static $TS_REPLICACAO_PERMISSOES = '8';
  public static $TS_PESQUISA_ORGAOS = '9';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  public function listarValores2Fatores(){
    try {

      $arrObjInfraValorStaDTO = array();

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$T2E_INDISPONIVEL);
      $objInfraValorStaDTO->setStrDescricao('Indisponível');
      $arrObjInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$T2E_OPCIONAL);
      $objInfraValorStaDTO->setStrDescricao('Opcional');
      $arrObjInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$T2E_OBRIGATORIA);
      $objInfraValorStaDTO->setStrDescricao('Obrigatória');
      $arrObjInfraValorStaDTO[] = $objInfraValorStaDTO;

      return $arrObjInfraValorStaDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Autenticação em 2 Fatores.',$e);
    }
  }

  public function listarValoresServico(){
    try {

      $arrObjTipoServicoDTO = array();

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_AUTENTICACAO_USUARIO);
      $objTipoServicoDTO->setStrDescricao('Autenticação de Usuário');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_PESQUISA_USUARIOS);
      $objTipoServicoDTO->setStrDescricao('Pesquisa de Usuários');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_PESQUISA_UNIDADES);
      $objTipoServicoDTO->setStrDescricao('Pesquisa de Unidades');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_PESQUISA_PERFIS);
      $objTipoServicoDTO->setStrDescricao('Pesquisa de Perfis');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_PESQUISA_RECURSOS);
      $objTipoServicoDTO->setStrDescricao('Pesquisa de Recursos');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_PESQUISA_PERMISSOES);
      $objTipoServicoDTO->setStrDescricao('Pesquisa de Permissões');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_REPLICACAO_USUARIOS);
      $objTipoServicoDTO->setStrDescricao('Replicação de Usuários');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_REPLICACAO_PERMISSOES);
      $objTipoServicoDTO->setStrDescricao('Replicação de Permissões');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      $objTipoServicoDTO = new TipoServicoDTO();
      $objTipoServicoDTO->setStrStaServico(self::$TS_PESQUISA_ORGAOS);
      $objTipoServicoDTO->setStrDescricao('Pesquisa de Órgãos');
      $arrObjTipoServicoDTO[] = $objTipoServicoDTO;

      InfraArray::ordenarArrInfraDTO($arrObjTipoServicoDTO, 'Descricao', InfraArray::$TIPO_ORDENACAO_ASC);

      return $arrObjTipoServicoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Serviços.',$e);
    }
  }

  public function importar(ImportarSistemaDTO $objImportarSistemaDTO) {
    try{

      $objImportarSistemaDTOAuditoria = clone($objImportarSistemaDTO);
      $objImportarSistemaDTOAuditoria->unSetStrBancoSenha();

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('sistema_importar', __METHOD__, $objImportarSistemaDTOAuditoria);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrSiglaOrgaoSistemaOrigem())){
        $objInfraException->adicionarValidacao('Órgão do Sistema Origem não informado.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrSiglaOrigem())){
        $objInfraException->adicionarValidacao('Sigla do Sistema Origem não informada.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getNumIdOrgaoSistemaDestino())){
        $objInfraException->adicionarValidacao('Órgão do Sistema Destino não informado.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getNumIdHierarquiaDestino())){
        $objInfraException->adicionarValidacao('Hierarquia de Destino não informada.');
      }
			
      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrSiglaDestino())){
        $objInfraException->adicionarValidacao('Sigla do Sistema Destino não informada.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrBancoServidor())){
        $objInfraException->adicionarValidacao('Servidor do Banco de Dados de origem não informado.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrBancoPorta())){
        $objInfraException->adicionarValidacao('Porta do Banco de Dados de origem não informada.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrBancoNome())){
        $objInfraException->adicionarValidacao('Nome da Base de Dados de origem não informado.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrBancoUsuario())){
        $objInfraException->adicionarValidacao('Usuário do Banco de Dados de origem não informado.');
      }
      
      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrBancoSenha())){
        $objInfraException->adicionarValidacao('Senha do Banco de Dados de origem não informada.');
      }

      if (InfraString::isBolVazia($objImportarSistemaDTO->getStrStaTipoBanco())){
        $objInfraException->adicionarValidacao('Tipo do Banco de Dados de origem não informado.');
      }

      if (!in_array($objImportarSistemaDTO->getStrStaTipoBanco(),array(self::$TBD_MYSQL,self::$TBD_SQLSERVER,self::$TBD_ORACLE))){
        $objInfraException->adicionarValidacao('Tipo do Banco de Dados de origem inválido.');
      }


      $dto = new SistemaDTO();
      $dto->setNumIdOrgao($objImportarSistemaDTO->getNumIdOrgaoSistemaDestino());
      $dto->setStrSigla($objImportarSistemaDTO->getStrSiglaDestino());
      if ($this->contar($dto)>0){
        $objInfraException->adicionarValidacao('Já existe um sistema com este órgão e sigla de destino.');        
      }
      
      
      $objInfraException->lancarValidacoes();

      switch($objImportarSistemaDTO->getStrStaTipoBanco()){
        case self::$TBD_SQLSERVER:
          BancoSip::setBanco(InfraBancoSqlServer::newInstance($objImportarSistemaDTO->getStrBancoServidor(),
              $objImportarSistemaDTO->getStrBancoPorta(),
              $objImportarSistemaDTO->getStrBancoNome(),
              $objImportarSistemaDTO->getStrBancoUsuario(),
              $objImportarSistemaDTO->getStrBancoSenha()));
          break;

        case self::$TBD_MYSQL:
          BancoSip::setBanco(InfraBancoMySqli::newInstance($objImportarSistemaDTO->getStrBancoServidor(),
              $objImportarSistemaDTO->getStrBancoPorta(),
              $objImportarSistemaDTO->getStrBancoNome(),
              $objImportarSistemaDTO->getStrBancoUsuario(),
              $objImportarSistemaDTO->getStrBancoSenha()));
          break;

        case self::$TBD_ORACLE:
          BancoSip::setBanco(InfraBancoOracle::newInstance($objImportarSistemaDTO->getStrBancoServidor(),
              $objImportarSistemaDTO->getStrBancoPorta(),
              $objImportarSistemaDTO->getStrBancoNome(),
              $objImportarSistemaDTO->getStrBancoUsuario(),
              $objImportarSistemaDTO->getStrBancoSenha()));
          break;

        case self::$TBD_POSTGRESQL:
          BancoSip::setBanco(InfraBancoPostgreSql::newInstance($objImportarSistemaDTO->getStrBancoServidor(),
              $objImportarSistemaDTO->getStrBancoPorta(),
              $objImportarSistemaDTO->getStrBancoNome(),
              $objImportarSistemaDTO->getStrBancoUsuario(),
              $objImportarSistemaDTO->getStrBancoSenha()));
          break;

      }

			$dto = new SistemaDTO();
			$dto->retTodos();
			//$dto->setNumIdOrgao($objImportarSistemaDTO->getNumIdOrgaoSistemaOrigem());
      $dto->setStrSiglaOrgao($objImportarSistemaDTO->getStrSiglaOrgaoSistemaOrigem());
			$dto->setStrSigla($objImportarSistemaDTO->getStrSiglaOrigem());
      $objSistemaDTO = $this->consultar($dto);
			if ($objSistemaDTO==null){
			  $objInfraException->lancarValidacao('Sistema de Origem não encontrado.');
			}
			
      //Consulta sistema origem
      $objDadosSistemaDTO = new DadosSistemaDTO();
      $objDadosSistemaDTO->setObjSistemaDTO($objSistemaDTO);
			$this->obterDadosCopiaSistema($objDadosSistemaDTO);
			
			//Finaliza trabalhos com a base de origem
      BancoSip::setBanco(null);
			
			
			//grava dados para Sistema Destino
      $objSistemaDTO = $objDadosSistemaDTO->getObjSistemaDTO();
			$objSistemaDTO->setNumIdOrgao($objImportarSistemaDTO->getNumIdOrgaoSistemaDestino());
			$objSistemaDTO->setNumIdHierarquia($objImportarSistemaDTO->getNumIdHierarquiaDestino());
      $objSistemaDTO->setStrSigla($objImportarSistemaDTO->getStrSiglaDestino());
      $objSistemaDTO->setStrWebService(null);
      //$objDadosSistemaDTO->setObjSistemaDTO($this->cadastrar($objSistemaDTO));
			
      $this->gravarDadosCopiaSistema($objDadosSistemaDTO);
      
			//Auditoria

      return $objDadosSistemaDTO->getObjSistemaDTO();

    }catch(Exception $e){
      
      BancoSip::setBanco(null);
      
      throw new InfraException('Erro clonando Sistema.',$e);
    }
  }

  public function clonar(ClonarSistemaDTO $objClonarSistemaDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('sistema_clonar',__METHOD__,$objClonarSistemaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objClonarSistemaDTO->getNumIdOrgaoSistemaOrigem())){
        $objInfraException->adicionarValidacao('Órgão do Sistema Origem não informado.');
      }

      if (InfraString::isBolVazia($objClonarSistemaDTO->getNumIdSistemaOrigem())){
        $objInfraException->adicionarValidacao('Sistema Origem não informado.');
      }

      if (InfraString::isBolVazia($objClonarSistemaDTO->getNumIdOrgaoSistemaDestino())){
        $objInfraException->adicionarValidacao('Órgão do Sistema Destino não informado.');
      }
			
      if (InfraString::isBolVazia($objClonarSistemaDTO->getStrSiglaDestino())){
        $objInfraException->adicionarValidacao('Sigla do Sistema Destino não informada.');
      }
      
			$dto = new SistemaDTO();
			$dto->retNumIdSistema();
			$dto->setNumIdOrgao($objClonarSistemaDTO->getNumIdOrgaoSistemaDestino());
			$dto->setStrSigla($objClonarSistemaDTO->getStrSiglaDestino());
			if (count($this->listar($dto))>0){
			  $objInfraException->adicionarValidacao('Já existe um sistema no órgão destino com esta sigla.');
			}

      $objInfraException->lancarValidacoes();

			
      //Consulta sistema origem
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retTodos();
			$objSistemaDTO->setNumIdOrgao($objClonarSistemaDTO->getNumIdOrgaoSistemaOrigem());
			$objSistemaDTO->setNumIdSistema($objClonarSistemaDTO->getNumIdSistemaOrigem());
      $objSistemaDTO = $this->consultar($objSistemaDTO);

      $objSistemaDTO->setStrCrc(null);
      $objSistemaDTO->setStrChaveAcesso(null);

      $objDadosSistemaDTO = new DadosSistemaDTO();
      $objDadosSistemaDTO->setObjSistemaDTO($objSistemaDTO);
			
			//Le dados para o Sistema Origem
			$this->obterDadosCopiaSistema($objDadosSistemaDTO);
			
			//grava dados para Sistema Destino
      $objSistemaDTO = $objDadosSistemaDTO->getObjSistemaDTO();
			$objSistemaDTO->setNumIdOrgao($objClonarSistemaDTO->getNumIdOrgaoSistemaDestino());
      $objSistemaDTO->setStrSigla($objClonarSistemaDTO->getStrSiglaDestino());
      $objSistemaDTO->setStrWebService(null);
      //$objDadosSistemaDTO->setObjSistemaDTO($this->cadastrar($objSistemaDTO));
			
      $this->gravarDadosCopiaSistema($objDadosSistemaDTO);
      
			//Auditoria

      return $objDadosSistemaDTO->getObjSistemaDTO();

    }catch(Exception $e){
      throw new InfraException('Erro clonando Sistema.',$e);
    }
  }

	protected function obterDadosCopiaSistemaControlado(DadosSistemaDTO $objDadosSistemaDTO){

      //Recursos
      $objRecursoDTO = new RecursoDTO();
      $objRecursoDTO->setBolExclusaoLogica(false);
      $objRecursoDTO->retTodos();
      $objRecursoDTO->setNumIdSistema($objDadosSistemaDTO->getObjSistemaDTO()->getNumIdSistema());
      $objRecursoRN = new RecursoRN();
      $objDadosSistemaDTO->setArrObjRecursoDTO($objRecursoRN->listar($objRecursoDTO));

      //Perfis
      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->setBolExclusaoLogica(false);
      $objPerfilDTO->retTodos();
      $objPerfilDTO->setNumIdSistema($objDadosSistemaDTO->getObjSistemaDTO()->getNumIdSistema());
      $objPerfilRN = new PerfilRN();
      $objDadosSistemaDTO->setArrObjPerfilDTO($objPerfilRN->listar($objPerfilDTO));
      
      //Recursos dos perfis
      $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
      $objRelPerfilRecursoDTO->retTodos();
      $objRelPerfilRecursoDTO->setNumIdSistema($objDadosSistemaDTO->getObjSistemaDTO()->getNumIdSistema());
      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $objDadosSistemaDTO->setArrObjRelPerfilRecursoDTO($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));
      
      //Menus
      $objMenuDTO = new MenuDTO();
      $objMenuDTO->setBolExclusaoLogica(false);
      $objMenuDTO->retTodos();
      $objMenuDTO->setNumIdSistema($objDadosSistemaDTO->getObjSistemaDTO()->getNumIdSistema());
      $objMenuRN = new MenuRN();
			$arrObjMenuDTO = $objMenuRN->listar($objMenuDTO);
			for($i=0;$i<count($arrObjMenuDTO);$i++){
        //Clona itens de menu associados
  			$objItemMenuDTO = new ItemMenuDTO();
  			$objItemMenuDTO->setBolExclusaoLogica(false);
  			$objItemMenuDTO->retTodos();
  			$objItemMenuDTO->setNumIdMenu($arrObjMenuDTO[$i]->getNumIdMenu());
  			$objItemMenuDTO->setNumIdSistema($objDadosSistemaDTO->getObjSistemaDTO()->getNumIdSistema());
  			$objItemMenuRN = new ItemMenuRN();
  			$arrObjMenuDTO[$i]->setArrObjItemMenuDTO($objItemMenuRN->listarHierarquia($objItemMenuDTO));
			}			
			$objDadosSistemaDTO->setArrObjMenuDTO($arrObjMenuDTO);
  
      //Itens de menu dos perfis
      $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
      $objRelPerfilItemMenuDTO->retTodos();
      $objRelPerfilItemMenuDTO->setNumIdSistema($objDadosSistemaDTO->getObjSistemaDTO()->getNumIdSistema());
      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      $objDadosSistemaDTO->setArrObjRelPerfilItemMenuDTO($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
		
	}
	
	protected function gravarDadosCopiaSistemaControlado(DadosSistemaDTO $objDadosSistemaDTO){
      
	    $objSistemaDTO = $this->cadastrar($objDadosSistemaDTO->getObjSistemaDTO());
	    
      //cadastra usuário atual como administrador
      $objAdministradorSistemaDTO = new AdministradorSistemaDTO();
      $objAdministradorSistemaDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
      $objAdministradorSistemaDTO->setNumIdUsuario(SessaoSip::getInstance()->getNumIdUsuario());
      
      $objAdministradorSistemaRN = new AdministradorSistemaRN();
      $objAdministradorSistemaRN->cadastrar($objAdministradorSistemaDTO);
      
      //Clona recursos
      $objRecursoRN = new RecursoRN();
      $arrObjRecursoDTO =$objDadosSistemaDTO->getArrObjRecursoDTO();
      //Prepara array com mapeamento dos Ids antigos para os novos
      $arrRecursos = array();      
      foreach($arrObjRecursoDTO as $dto){
        $numIdOriginal = $dto->getNumIdRecurso();
        $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $ret = $objRecursoRN->cadastrar($dto);
        $arrRecursos[$numIdOriginal] = $ret->getNumIdRecurso();
      }

      //Clona Perfis
      $objPerfilRN = new PerfilRN();
      $arrObjPerfilDTO = $objDadosSistemaDTO->getArrObjPerfilDTO();
      //Prepara array com mapeamento dos Ids antigos para os novos
      $arrPerfis = array();      
      foreach($arrObjPerfilDTO as $dto){
        $numIdOriginal = $dto->getNumIdPerfil();
        $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $ret = $objPerfilRN->cadastrar($dto);
        $arrPerfis[$numIdOriginal] = $ret->getNumIdPerfil();
      }
      
      //Clona recursos dos perfis
      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $arrObjRelPerfilRecursoDTO = $objDadosSistemaDTO->getArrObjRelPerfilRecursoDTO();
      foreach($arrObjRelPerfilRecursoDTO as $dto){
        $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $dto->setNumIdRecurso($arrRecursos[$dto->getNumIdRecurso()]);
        $dto->setNumIdPerfil($arrPerfis[$dto->getNumIdPerfil()]);
        $objRelPerfilRecursoRN->cadastrar($dto);
      }
      
      
      //Clona Menus
      $objMenuRN = new MenuRN();
      $arrObjMenuDTO = $objDadosSistemaDTO->getArrObjMenuDTO();
      $arrObjItemMenuDTO = array();
      //Prepara array com mapeamento dos Ids antigos para os novos
      $arrMenus = array();      
      foreach($arrObjMenuDTO as $dto){
        $numIdMenuOriginal = $dto->getNumIdMenu();
        $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $ret = $objMenuRN->cadastrar($dto);
        $arrMenus[$numIdMenuOriginal] = $ret->getNumIdMenu();
        
        $arrObjItemMenuDTO = array_merge($arrObjItemMenuDTO, $dto->getArrObjItemMenuDTO());
      }
      
      //Clona itens de menu associados
			$objItemMenuRN = new ItemMenuRN();

			//$arrObjItemMenuDTO = $dto->getArrObjItemMenuDTO();
			
      //Prepara array com mapeamento dos Ids antigos para os novos
      $arrItensMenus = array();      

      //Tem que adicionar partindo da raiz até as folhas
			//Descobre qual o nível mais baixo
			$numNivel=0;
			foreach($arrObjItemMenuDTO as $dto){
			  if (strlen($dto->getStrRamificacao())>$numNivel){
			    $numNivel = strlen($dto->getStrRamificacao());
			  }
			}
			
			for($i=0;$i<=$numNivel;$i++){
				foreach($arrObjItemMenuDTO as $dto){
				  if (strlen($dto->getStrRamificacao())==$i){
				    //Adiciona Item
            $numIdItemMenuOriginal = $dto->getNumIdItemMenu();
            $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
            $dto->setNumIdMenu($arrMenus[$dto->getNumIdMenu()]);
            if ($dto->getNumIdMenuPai()!=null && $dto->getNumIdItemMenuPai()!=null){
              $dto->setNumIdMenuPai($arrMenus[$dto->getNumIdMenuPai()]);
              $dto->setNumIdItemMenuPai($arrItensMenus[$dto->getNumIdItemMenuPai()]);
            }else{
              $dto->setNumIdMenuPai(null);
              $dto->setNumIdItemMenuPai(null);
            }
            $dto->setNumIdRecurso($arrRecursos[$dto->getNumIdRecurso()]);
            $ret = $objItemMenuRN->cadastrar($dto);
            $arrItensMenus[$numIdItemMenuOriginal] = $ret->getNumIdItemMenu();
				  }
				}
			}
			      
      //Clona itens de menu dos perfis
      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      $arrObjRelPerfilItemMenuDTO = $objDadosSistemaDTO->getArrObjRelPerfilItemMenuDTO();
      foreach($arrObjRelPerfilItemMenuDTO as $dto){
        $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $dto->setNumIdPerfil($arrPerfis[$dto->getNumIdPerfil()]);
        $dto->setNumIdMenu($arrMenus[$dto->getNumIdMenu()]);
        $dto->setNumIdItemMenu($arrItensMenus[$dto->getNumIdItemMenu()]);
        $dto->setNumIdRecurso($arrRecursos[$dto->getNumIdRecurso()]);
        $objRelPerfilItemMenuRN->cadastrar($dto);
      }
      
      //Auditoria
	}
  
  protected function cadastrarControlado(SistemaDTO $objSistemaDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('sistema_cadastrar',__METHOD__,$objSistemaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objSistemaDTO,$objInfraException);
      $this->validarNumIdHierarquia($objSistemaDTO,$objInfraException);
			$this->validarStrSigla($objSistemaDTO,$objInfraException);
			$this->validarStrDescricao($objSistemaDTO,$objInfraException);
			$this->validarStrPaginaInicial($objSistemaDTO,$objInfraException);
			$this->validarStrWebService($objSistemaDTO,$objInfraException);
			$this->validarStrSta2Fatores($objSistemaDTO,$objInfraException);
			$this->validarStrServicosLiberados($objSistemaDTO,$objInfraException);
      $this->validarStrEsquemaLogin($objSistemaDTO,$objInfraException);
			$this->validarStrSinAtivo($objSistemaDTO,$objInfraException);
			
			if ($objSistemaDTO->isSetStrNomeArquivo()){
			  $this->validarStrNomeArquivo($objSistemaDTO, $objInfraException);
			}
			
      $objInfraException->lancarValidacoes();
      
      if ($objSistemaDTO->isSetStrNomeArquivo() && !InfraString::isBolVazia($objSistemaDTO->getStrNomeArquivo())) {
        $objSistemaDTO->setStrLogo(base64_encode(file_get_contents(DIR_SIP_TEMP.'/'.$objSistemaDTO->getStrNomeArquivo())));
      }

      $objSistemaDTO->setStrChaveAcesso(null);
      $objSistemaDTO->setStrCrc(null);
      
      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      $ret = $objSistemaBD->cadastrar($objSistemaDTO);

      //replica todos orgaos do sistema
      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      
      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);
      
      
      foreach($arrObjOrgaoDTO as $objOrgaoDTO){
        $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
        $objReplicacaoOrgaoDTO->setStrStaOperacao('C');
        $objReplicacaoOrgaoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
        $objReplicacaoOrgaoDTO->setNumIdSistema($ret->getNumIdSistema());
        $this->replicarOrgao($objReplicacaoOrgaoDTO);
      }

      if ($objSistemaDTO->isSetStrNomeArquivo() && !InfraString::isBolVazia($objSistemaDTO->getStrNomeArquivo())){
        unlink(DIR_SIP_TEMP . '/' . $objSistemaDTO->getStrNomeArquivo());
      }

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Sistema.',$e);
    }
  }

  protected function alterarControlado(SistemaDTO $objSistemaDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('sistema_alterar',__METHOD__,$objSistemaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      if ($objSistemaDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objSistemaDTO,$objInfraException);
      }
      
      if ($objSistemaDTO->isSetNumIdHierarquia()){
        $this->validarNumIdHierarquia($objSistemaDTO,$objInfraException);
      }
      
      if ($objSistemaDTO->isSetStrSigla()){
			  $this->validarStrSigla($objSistemaDTO,$objInfraException);
      }
      
      if ($objSistemaDTO->isSetStrDescricao()){
			  $this->validarStrDescricao($objSistemaDTO,$objInfraException);
      }
      
      if ($objSistemaDTO->isSetStrPaginaInicial()){
			  $this->validarStrPaginaInicial($objSistemaDTO,$objInfraException);
      }
      
      if ($objSistemaDTO->isSetStrWebService()){
			  $this->validarStrWebService($objSistemaDTO,$objInfraException);
      }

      if ($objSistemaDTO->isSetStrSta2Fatores()){
        $this->validarStrSta2Fatores($objSistemaDTO,$objInfraException);
      }

      if ($objSistemaDTO->isSetStrServicosLiberados()){
        $this->validarStrServicosLiberados($objSistemaDTO,$objInfraException);
      }

      if ($objSistemaDTO->isSetStrChaveAcesso()){
        $this->validarStrChaveAcesso($objSistemaDTO,$objInfraException);
      }

      if ($objSistemaDTO->isSetStrCrc()){
        $this->validarStrCrc($objSistemaDTO,$objInfraException);
      }

      if ($objSistemaDTO->isSetStrSinAtivo()){
			  $this->validarStrSinAtivo($objSistemaDTO,$objInfraException);
      }

      if ($objSistemaDTO->isSetStrEsquemaLogin()) {
        $this->validarStrEsquemaLogin($objSistemaDTO, $objInfraException);
      }

      if ($objSistemaDTO->isSetStrNomeArquivo()) {
        $this->validarStrNomeArquivo($objSistemaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      
      if ($objSistemaDTO->isSetStrNomeArquivo() && !InfraString::isBolVazia($objSistemaDTO->getStrNomeArquivo())) {
        if ($objSistemaDTO->getStrNomeArquivo()=="*REMOVER*") {
          $objSistemaDTO->setStrLogo(null);
        } else {
          $objSistemaDTO->setStrLogo(base64_encode(file_get_contents(DIR_SIP_TEMP.'/'.$objSistemaDTO->getStrNomeArquivo())));
        }
      }
      
      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      $objSistemaBD->alterar($objSistemaDTO);

      
      //replica todos orgaos do sistema
      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      
      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);
      
      
      foreach($arrObjOrgaoDTO as $objOrgaoDTO){
        $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
        $objReplicacaoOrgaoDTO->setStrStaOperacao('C');
        $objReplicacaoOrgaoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
        $objReplicacaoOrgaoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $this->replicarOrgao($objReplicacaoOrgaoDTO);
      }

      if ($objSistemaDTO->isSetStrNomeArquivo() && !InfraString::isBolVazia($objSistemaDTO->getStrNomeArquivo()) && $objSistemaDTO->getStrNomeArquivo()!="*REMOVER*") {
        unlink(DIR_SIP_TEMP.'/'.$objSistemaDTO->getStrNomeArquivo());
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Sistema.',$e);
    }
  }

  protected function excluirControlado($arrObjSistemaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('sistema_excluir',__METHOD__,$arrObjSistemaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

			
			for($i=0;$i<count($arrObjSistemaDTO);$i++){
				//Verifica se existem permissoes no sistema
				$objPermissaoDTO = new PermissaoDTO();
				$objPermissaoDTO->retNumIdSistema();
				$objPermissaoDTO->setNumIdSistema($arrObjSistemaDTO[$i]->getNumIdSistema());
				$objPermissaoRN = new PermissaoRN();
				if (count($objPermissaoRN->listar($objPermissaoDTO))>0){
					$objInfraException->adicionarValidacao('Existem permissões associadas.');
				}
				
        $objInfraException->lancarValidacoes();
			}


      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSistemaDTO);$i++){

				//Exclui perfis associados
				$objPerfilDTO = new PerfilDTO();
				$objPerfilDTO->retNumIdPerfil();
				$objPerfilDTO->retNumIdSistema();
				$objPerfilDTO->setNumIdSistema($arrObjSistemaDTO[$i]->getNumIdSistema());
				$objPerfilDTO->setBolExclusaoLogica(false);
				$objPerfilRN = new PerfilRN();
				$objPerfilRN->excluir($objPerfilRN->listar($objPerfilDTO));
        
				
				//Exclui administradores de sistemas
				$objAdministradorSistemaDTO = new AdministradorSistemaDTO();
				$objAdministradorSistemaDTO->retNumIdUsuario();
				$objAdministradorSistemaDTO->retNumIdSistema();
				$objAdministradorSistemaDTO->setNumIdSistema($arrObjSistemaDTO[$i]->getNumIdSistema());
				$objAdministradorSistemaRN = new AdministradorSistemaRN();
				$objAdministradorSistemaRN->excluir($objAdministradorSistemaRN->listar($objAdministradorSistemaDTO));
				
        
				//Exclui menus asssociados sistemas
				$objMenuDTO = new MenuDTO();
				$objMenuDTO->retNumIdMenu();
				$objMenuDTO->setNumIdSistema($arrObjSistemaDTO[$i]->getNumIdSistema());
				$objMenuDTO->setBolExclusaoLogica(false);
				$objMenuRN = new MenuRN();
				$objMenuRN->excluir($objMenuRN->listar($objMenuDTO));
				
				
				//Exclui recursos associados
				$objRecursoDTO = new RecursoDTO();
				$objRecursoDTO->retNumIdRecurso();
				$objRecursoDTO->retNumIdSistema();
				$objRecursoDTO->setNumIdSistema($arrObjSistemaDTO[$i]->getNumIdSistema());
				$objRecursoDTO->setBolExclusaoLogica(false);
				$objRecursoRN = new RecursoRN();
				$objRecursoRN->excluir($objRecursoRN->listar($objRecursoDTO));

        //Exclui logins associados
				$objLoginDTO = new LoginDTO();
				$objLoginDTO->retStrIdLogin();
				$objLoginDTO->retNumIdSistema();
				$objLoginDTO->retNumIdUsuario();
				
				$objLoginDTO->setNumIdSistema($arrObjSistemaDTO[$i]->getNumIdSistema());
				$objLoginRN = new LoginRN();
				$objLoginRN->excluir($objLoginRN->listar($objLoginDTO));
				
        $objSistemaBD->excluir($arrObjSistemaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Sistema.',$e);
    }
  }

  protected function desativarControlado($arrObjSistemaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('sistema_desativar',__METHOD__,$arrObjSistemaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSistemaDTO);$i++){
        $objSistemaBD->desativar($arrObjSistemaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Sistema.',$e);
    }
  }

  protected function reativarControlado($arrObjSistemaDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('sistema_reativar',__METHOD__,$arrObjSistemaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSistemaDTO);$i++){
        $objSistemaBD->reativar($arrObjSistemaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Sistema.',$e);
    }
  }

  protected function consultarConectado(SistemaDTO $objSistemaDTO){
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_consultar',__METHOD__,$objSistemaDTO);
      /////////////////////////////////////////////////////////////////
			
      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      $ret = $objSistemaBD->consultar($objSistemaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Sistema.',$e);
    }
  }

  protected function listarConectado(SistemaDTO $objSistemaDTO) {
    try {
      ////////////////////////////////////////////////////////////////////// 
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_listar',__METHOD__,$objSistemaDTO);
			//////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      $ret = $objSistemaBD->listar($objSistemaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Sistemas.',$e);
    }
  }

  protected function contarConectado(SistemaDTO $objSistemaDTO) {
    try {
      ////////////////////////////////////////////////////////////////////// 
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_contar',__METHOD__,$objSistemaDTO);
			//////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSistemaBD = new SistemaBD($this->getObjInfraIBanco());
      $ret = $objSistemaBD->contar($objSistemaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando Sistemas.',$e);
    }
  }
  
	/**
	Lista todos os sistemas onde o usuario é administrador (se o usuario 
  administra o SIP entao lista todos os sistemas do órgão do SIP administrado)
	*/
  protected function listarSipConectado(SistemaDTO $objSistemaDTO) {
    try {

			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_listar',__METHOD__,$objSistemaDTO);
			/////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

			//Solicita retorno do ID do órgão
			$objSistemaDTO->retNumIdOrgao();
      $arrObjSistemaDTO = $this->listar($objSistemaDTO);

			//Obtem sistemas administrados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
			
			$arrSistemasAdicionados = array();
			
			$ret = array();
			
			//Se tem permissão no SIP então carrega todos os sistemas independente do órgão
			foreach($arrObjAcessoDTO as $acesso){
			  if (strtoupper($acesso->getStrSiglaSistema())==SessaoSip::getInstance()->getStrSiglaSistema()){
					foreach($arrObjSistemaDTO as $sistema){
  						//if ($sistema->getNumIdOrgao()==$acesso->getNumIdOrgaoSistema()){
  							if(!in_array($sistema->getNumIdSistema(),$arrSistemasAdicionados)){
  								$arrSistemasAdicionados[] = $sistema->getNumIdSistema();
  								$ret[] = $sistema;
  							}
  						//}
					 }
			   }
			 }
			 
			//Adiciona sistemas administrados restantes
		  foreach($arrObjAcessoDTO as $acesso){
				foreach($arrObjSistemaDTO as $sistema){
					if ($acesso->getNumIdSistema()==$sistema->getNumIdSistema()){
						if(!in_array($sistema->getNumIdSistema(),$arrSistemasAdicionados)){
							$arrSistemasAdicionados[] = $sistema->getNumIdSistema();
							$ret[] = $sistema;
						}
					}
				}
			}
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Sistemas SIP.',$e);
    }
  }

  protected function listarAdministradosConectado(SistemaDTO $objSistemaDTO) {
    try {

			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_listar',__METHOD__,$objSistemaDTO);
			/////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrObjSistemaDTO = $this->listar($objSistemaDTO);

			//Obtem sistemas acessados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
			
			$ret = InfraArray::joinArrInfraDTO($arrObjSistemaDTO, 'IdSistema', $arrObjAcessoDTO, 'IdSistema');
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Sistemas administrados.',$e);
    }
  }

  protected function listarCoordenadosConectado(SistemaDTO $objSistemaDTO) {
    try {
  
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_listar',__METHOD__,$objSistemaDTO);
      /////////////////////////////////////////////////////////////////
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
      
      $ret = array();

      //Obtem sistemas acessados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$COORDENADOR_PERFIL);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
      
      $arrObjAcessoDTO = InfraArray::distinctArrInfraDTO($arrObjAcessoDTO,'IdSistema');
      
      if (count($arrObjAcessoDTO)){
        
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setNumIdSistema(InfraArray::converterArrInfraDTO($arrObjAcessoDTO,'IdSistema'),InfraDTO::$OPER_IN);
        
        $ret = $this->listar($objSistemaDTO);
      }
      	
      //Auditoria
  
      return $ret;
  
    }catch(Exception $e){
      throw new InfraException('Erro listando Sistemas coordenados.',$e);
    }
  }
  
  protected function listarAutorizadosConectado(SistemaDTO $objSistemaDTO) {
    try {

			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_listar',__METHOD__,$objSistemaDTO);
			/////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrObjSistemaDTO = $this->listar($objSistemaDTO);

			//Obtem sistemas acessados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR | AcessoDTO::$COORDENADOR_PERFIL | AcessoDTO::$COORDENADOR_UNIDADE);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
			
			$arrObjAcessoDTO = InfraArray::distinctArrInfraDTO($arrObjAcessoDTO,'IdSistema');
			
			$ret = InfraArray::joinArrInfraDTO($arrObjSistemaDTO, 'IdSistema', $arrObjAcessoDTO, 'IdSistema');
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Sistemas autorizados.',$e);
    }
  }

  protected function listarPessoaisConectado(SistemaDTO $objSistemaDTO) {
    try {

			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('sistema_listar',__METHOD__,$objSistemaDTO);
			/////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrObjSistemaDTO = $this->listar($objSistemaDTO);

			//Obtem sistemas acessados pelo usuario
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$PERMISSAO);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
			
			$arrObjAcessoDTO = InfraArray::distinctArrInfraDTO($arrObjAcessoDTO,'IdSistema');
			
			$ret = InfraArray::joinArrInfraDTO($arrObjSistemaDTO, 'IdSistema', $arrObjAcessoDTO, 'IdSistema');
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Sistemas com permissão.',$e);
    }
  }

  protected function listarOrgaosConectado(SistemaDTO $parObjSistemaDTO){
    try{

      //Busca hierarquia do sistema
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->setNumIdSistema($parObjSistemaDTO->getNumIdSistema());

      $objSistemaDTO = $this->consultar($objSistemaDTO);

      if ($objSistemaDTO==null){
        throw new InfraException('Sistema não encontrado.');
      }

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->retStrSinAtivo();
      $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

      $ret = array();

      foreach($arrObjOrgaoDTO as $objOrgaoDTO){

        //ATENÇÃO: os elementos devem ser adicionados no array seguindo a ordem dos índices (posição 0, 1, 2, ...)
        //Ao enviar via web-services o PHP ignora o valor do índice passado na constante e assume a ordem em que foram adicionados.

        $numIdOrgao = $objOrgaoDTO->getNumIdOrgao();

        $ret[$numIdOrgao] = array();
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_ID] = $numIdOrgao;
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_SIGLA] = $objOrgaoDTO->getStrSigla();
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_DESCRICAO] = $objOrgaoDTO->getStrDescricao();
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_SIN_ATIVO] = $objOrgaoDTO->getStrSinAtivo();
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro carregando órgãos.',$e);
    }

  }

  protected function listarUnidadesConectado(SistemaDTO $parObjSistemaDTO){
    try{
          
      //Busca hierarquia do sistema
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->setNumIdSistema($parObjSistemaDTO->getNumIdSistema());
      
      $objSistemaDTO = $this->consultar($objSistemaDTO);
      
      if ($objSistemaDTO==null){
        throw new InfraException('Sistema não encontrado.');
      }
    	  
      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
    	$objRelHierarquiaUnidadeDTO->retArrUnidadesInferiores();
    	$objRelHierarquiaUnidadeDTO->retArrUnidadesSuperiores();
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());

      if ($parObjSistemaDTO->isSetNumIdUnidade()){
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($parObjSistemaDTO->getNumIdUnidade());
      }

      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      $arrHierarquia = $objRelHierarquiaUnidadeRN->listarHierarquia($objRelHierarquiaUnidadeDTO);
      
      $ret = array();
      
      foreach($arrHierarquia as $objRelHierarquiaUnidadeDTO){
        
        //ATENÇÃO: os elementos devem ser adicionados no array seguindo a ordem dos índices (posição 0, 1, 2, ...)
        //Ao enviar via web-services o PHP ignora o valor do índice passado na constante e assume a ordem em que foram adicionados.
        
      	$numIdUnidade = $objRelHierarquiaUnidadeDTO->getNumIdUnidade();
      	
      	$ret[$numIdUnidade] = array();
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_ID] = $numIdUnidade;
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_ORGAO_ID] = $objRelHierarquiaUnidadeDTO->getNumIdOrgaoUnidade();
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_SIGLA] = $objRelHierarquiaUnidadeDTO->getStrSiglaUnidade();
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_DESCRICAO] = $objRelHierarquiaUnidadeDTO->getStrDescricaoUnidade();
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_SIN_ATIVO] = $objRelHierarquiaUnidadeDTO->getStrSinAtivo();
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_SUBUNIDADES] = InfraArray::converterArrInfraDTO($objRelHierarquiaUnidadeDTO->getArrUnidadesInferiores(),'IdUnidade');
      	$ret[$numIdUnidade][InfraSip::$WS_UNIDADE_UNIDADES_SUPERIORES] = InfraArray::converterArrInfraDTO($objRelHierarquiaUnidadeDTO->getArrUnidadesSuperiores(),'IdUnidade');
        $ret[$numIdUnidade][InfraSip::$WS_UNIDADE_ID_ORIGEM] = $objRelHierarquiaUnidadeDTO->getStrIdOrigemUnidade();
      }
    	  
      return $ret;
      
    }catch(Exception $e){
      throw new InfraException('Erro carregando unidades do sistema.',$e);
    }
    
  }

  private function validarStrNomeArquivo(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (!InfraString::isBolVazia($objSistemaDTO->getStrNomeArquivo()) && $objSistemaDTO->getStrNomeArquivo()!="*REMOVER*"){
      if (!file_exists(DIR_SIP_TEMP.'/'.$objSistemaDTO->getStrNomeArquivo())) {
        $objInfraException->adicionarValidacao('Não foi possível abrir arquivo da imagem.');
      }
    }
  }
  
  private function validarNumIdOrgao(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getNumIdOrgao())){
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }
  }
  
  private function validarNumIdHierarquia(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getNumIdHierarquia())){
      $objInfraException->adicionarValidacao('Hierarquia não informada.');
    }
  }
	
  private function validarStrSigla(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }

    $objSistemaDTO->setStrSigla(trim($objSistemaDTO->getStrSigla()));

    if (strlen($objSistemaDTO->getStrSigla())>15){
      $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 15 caracteres.');
    }

    $strSigla = $objSistemaDTO->getStrSigla();

    if (preg_match("/[^0-9a-zA-Z\-_]/", $strSigla)){
      $objInfraException->adicionarValidacao('Sigla possui caracter inválido.');
    }

    $dto = new SistemaDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema(),InfraDTO::$OPER_DIFERENTE);
    $dto->setNumIdOrgao($objSistemaDTO->getNumIdOrgao());
    $dto->setStrSigla($objSistemaDTO->getStrSigla());
    $dto = $this->consultar($dto);
    if ($dto!=null){
      if ($dto->getStrSinAtivo()=='N'){
        $objInfraException->adicionarValidacao('Existe outro sistema inativo com a mesma sigla neste órgão.');
      }else{
        $objInfraException->adicionarValidacao('Existe outro sistema com a mesma sigla neste órgão.');
      }
    }
  }
	
  private function validarStrDescricao(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
  	$objSistemaDTO->setStrDescricao(trim($objSistemaDTO->getStrDescricao()));
  	
    
    if (strlen($objSistemaDTO->getStrDescricao())>200){
      $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 200 caracteres.');
    }
    
  }

  private function validarStrPaginaInicial(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
  	$objSistemaDTO->setStrPaginaInicial(trim($objSistemaDTO->getStrPaginaInicial()));
  	
    
    if (strlen($objSistemaDTO->getStrPaginaInicial())>255){
      $objInfraException->adicionarValidacao('Localização da Página Inicial possui tamanho superior a 255 caracteres.');
    }
    
  }

  private function validarStrWebService(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
  	$objSistemaDTO->setStrWebService(trim($objSistemaDTO->getStrWebService()));

    if (strlen($objSistemaDTO->getStrWebService())>255){
      $objInfraException->adicionarValidacao('Localização do Web Service possui tamanho superior a 255 caracteres.');
    }
    
  }

  private function validarStrSta2Fatores(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getStrSta2Fatores())){
      $objInfraException->adicionarValidacao('Tipo da Autenticação em 2 Fatores não informado.');
    }else{
      if (!in_array($objSistemaDTO->getStrSta2Fatores(), InfraArray::converterArrInfraDTO($this->listarValores2Fatores(),'StaValor'))){
        $objInfraException->adicionarValidacao('Tipo da Autenticação em 2 Fatores inválido.');
      }
    }
  }

  private function validarStrServicosLiberados(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getStrServicosLiberados())){
      $objSistemaDTO->setStrServicosLiberados(null);
    }else{

      $objSistemaDTO->setStrServicosLiberados(trim(str_replace(' ', '', $objSistemaDTO->getStrServicosLiberados())));

      if (strlen($objSistemaDTO->getStrServicosLiberados())>200){
        $objInfraException->adicionarValidacao('Conjunto de serviços liberados possui tamanho superior a 200 caracteres.');
      }

      $arrServico = explode(',',trim($objSistemaDTO->getStrServicosLiberados()));

      foreach($arrServico as $strServico) {
        if (!in_array($strServico, InfraArray::converterArrInfraDTO($this->listarValoresServico(), 'StaServico'))) {
          $objInfraException->adicionarValidacao('Serviço '.$strServico.' inválido.');
        }
      }
    }
  }

  private function validarStrChaveAcesso(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getStrChaveAcesso())){
      $objSistemaDTO->setStrChaveAcesso(null);
    }else{
      $objSistemaDTO->setStrChaveAcesso(trim($objSistemaDTO->getStrChaveAcesso()));

      if (strlen($objSistemaDTO->getStrChaveAcesso())>60){
        $objInfraException->adicionarValidacao('Chave de Acesso possui tamanho superior a 60 caracteres.');
      }
    }
  }

  private function validarStrCrc(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getStrCrc())){
      $objSistemaDTO->setStrCrc(null);
    }else{
      $objSistemaDTO->setStrCrc(trim($objSistemaDTO->getStrCrc()));

      if (strlen($objSistemaDTO->getStrCrc())>8){
        $objInfraException->adicionarValidacao('CRC possui tamanho superior a 8 caracteres.');
      }
    }
  }

  private function validarStrEsquemaLogin(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSistemaDTO->getStrEsquemaLogin())){
      $objSistemaDTO->setStrEsquemaLogin(null);
    }else{
      if (!in_array($objSistemaDTO->getStrEsquemaLogin(), array_keys(PaginaSip::getInstance()->listarEsquemas()))){
        $objInfraException->adicionarValidacao('Esquema de login inválido.');
      }
    }
  }

  private function validarStrSinAtivo(SistemaDTO $objSistemaDTO, InfraException $objInfraException){
    if (!InfraUtil::isBolSinalizadorValido($objSistemaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }
	
  protected function listarHierarquiaConectado(SistemaDTO $objSistemaDTO){
		//Busca hierarquia do sistema
    $dto = new SistemaDTO();
    $dto->retNumIdHierarquia();
    $dto->setNumIdSistema($objSistemaDTO->getNumIdSistema());
    $dto = $this->consultar($dto);
    if ($objSistemaDTO==null){
      throw new InfraException('Sistema não encontrado.');
    }
		
    $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
  	$objRelHierarquiaUnidadeDTO->retArrUnidadesInferiores();
    $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($dto->getNumIdHierarquia());

    if ($objSistemaDTO->isSetNumIdUnidade()){
      $objRelHierarquiaUnidadeDTO->setNumIdUnidade($objSistemaDTO->getNumIdUnidade());
    }

    $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
    $arrHierarquia = $objRelHierarquiaUnidadeRN->listarHierarquia($objRelHierarquiaUnidadeDTO);
    
    return $arrHierarquia;
  }  
  
  public function replicarRegraAuditoria(ReplicacaoRegraAuditoriaDTO $objReplicacaoRegraAuditoriaDTO) {
  
    try{
  
      $objInfraException = new InfraException();
       
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
       
      $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
      $objRegraAuditoriaDTO->setBolExclusaoLogica(false);
      $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
      $objRegraAuditoriaDTO->retStrDescricao();
      $objRegraAuditoriaDTO->retNumIdSistema();
      $objRegraAuditoriaDTO->retStrSinAtivo();
      $objRegraAuditoriaDTO->setNumIdRegraAuditoria($objReplicacaoRegraAuditoriaDTO->getNumIdRegraAuditoria());
       
      $objRegraAuditoriaRN = new RegraAuditoriaRN();
      $objRegraAuditoriaDTO = $objRegraAuditoriaRN->consultar($objRegraAuditoriaDTO);
  
      $objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
      $objRelRegraAuditoriaRecursoDTO->retStrNomeRecurso();
      $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());
  
      $objRelRegraAuditoriaRecursoRN = new RelRegraAuditoriaRecursoRN();
      $arrObjRelRegraAuditoriaRecursoDTO = $objRelRegraAuditoriaRecursoRN->listar($objRelRegraAuditoriaRecursoDTO);
  
      if ($objRegraAuditoriaDTO->getNumIdSistema() == $objInfraParametro->getValor('ID_SISTEMA_SIP')){
  
        AuditoriaSip::getInstance()->replicarRegra($objReplicacaoRegraAuditoriaDTO->getStrStaOperacao(),
        $objRegraAuditoriaDTO->getNumIdRegraAuditoria(),
        $objRegraAuditoriaDTO->getStrDescricao(),
        $objRegraAuditoriaDTO->getStrSinAtivo(),
        InfraArray::converterArrInfraDTO($arrObjRelRegraAuditoriaRecursoDTO,'NomeRecurso'));
  
      }else{

        $objReplicacaoServicoDTO = new ReplicacaoServicoDTO();
        $objReplicacaoServicoDTO->setNumIdSistema($objRegraAuditoriaDTO->getNumIdSistema());
        $objReplicacaoServicoDTO->setStrNomeOperacao('replicarRegraAuditoria');
        $objReplicacaoServicoDTO = Replicacao::getInstance()->obterServico($objReplicacaoServicoDTO);

        if ($objReplicacaoServicoDTO != null){
          Replicacao::getInstance()->executar($objReplicacaoServicoDTO, $objReplicacaoRegraAuditoriaDTO->getStrStaOperacao(),
                                                                        $objRegraAuditoriaDTO->getNumIdRegraAuditoria(),
                                                                        $objRegraAuditoriaDTO->getStrDescricao(),
                                                                        $objRegraAuditoriaDTO->getStrSinAtivo(),
                                                                        InfraArray::converterArrInfraDTO($arrObjRelRegraAuditoriaRecursoDTO,'NomeRecurso'));
        }
      }
  
    }catch(Exception $e){
      throw new InfraException('Erro replicando regra de auditoria.',$e);
    }
  }
  
  public function replicarUsuario(ReplicacaoUsuarioDTO $objReplicacaoUsuarioDTO) {
    
    try{

      $objReplicacaoServicoDTO = new ReplicacaoServicoDTO();
      $objReplicacaoServicoDTO->setNumIdSistema($objReplicacaoUsuarioDTO->getNumIdSistema());
      $objReplicacaoServicoDTO->setStrNomeOperacao('replicarUsuario');
      $objReplicacaoServicoDTO = Replicacao::getInstance()->obterServico($objReplicacaoServicoDTO);
      
      if ($objReplicacaoServicoDTO != null){
        
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setBolExclusaoLogica(false);
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retNumIdOrgao();
        $objUsuarioDTO->retStrIdOrigem();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNomeRegistroCivil();
        $objUsuarioDTO->retStrNomeSocial();
        $objUsuarioDTO->retDblCpf();
        $objUsuarioDTO->retStrEmail();
        $objUsuarioDTO->retStrSinAtivo();

        if (is_array($objReplicacaoUsuarioDTO->getNumIdUsuario())){
          $arrIdUsuario = $objReplicacaoUsuarioDTO->getNumIdUsuario();
        }else{
          $arrIdUsuario = array($objReplicacaoUsuarioDTO->getNumIdUsuario());
        }

        $objUsuarioDTO->setNumIdUsuario($arrIdUsuario, InfraDTO::$OPER_IN);
        
        $objUsuarioRN = new UsuarioRN();
        $arrObjUsuarioDTO = $objUsuarioRN->listar($objUsuarioDTO);

        if (count($arrObjUsuarioDTO)) {
          $arr = array();
          foreach ($arrObjUsuarioDTO as $objUsuarioDTO) {
            $arr[] = array(
                'StaOperacao' => $objReplicacaoUsuarioDTO->getStrStaOperacao(),
                'IdUsuario' => $objUsuarioDTO->getNumIdUsuario(),
                'IdOrgao' => $objUsuarioDTO->getNumIdOrgao(),
                'IdOrigem' => $objUsuarioDTO->getStrIdOrigem(),
                'Sigla' => $objUsuarioDTO->getStrSigla(),
                'Nome' => $objUsuarioDTO->getStrNomeRegistroCivil(),
                'NomeSocial' => $objUsuarioDTO->getStrNomeSocial(),
                'Cpf' => $objUsuarioDTO->getDblCpf(),
                'Email' => $objUsuarioDTO->getStrEmail(),
                'SinAtivo' => $objUsuarioDTO->getStrSinAtivo());
          }
          Replicacao::getInstance()->executar($objReplicacaoServicoDTO, $arr);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro replicando usuário.',$e);
    }
  }

  public function replicarUnidade(ReplicacaoUnidadeDTO $objReplicacaoUnidadeDTO) {
    
    $strMsg = '';
    
    try{

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->setNumIdHierarquia($objReplicacaoUnidadeDTO->getNumIdHierarquia());
      
      if ($objReplicacaoUnidadeDTO->isSetNumIdSistema()){
        $objSistemaDTO->setNumIdSistema($objReplicacaoUnidadeDTO->getNumIdSistema());
      }
      
      $arrObjSistemaDTO = $this->listar($objSistemaDTO);
      
      foreach($arrObjSistemaDTO as $objSistemaDTO){

        $objReplicacaoServicoDTO = new ReplicacaoServicoDTO();
        $objReplicacaoServicoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $objReplicacaoServicoDTO->setStrNomeOperacao('replicarUnidade');
        $objReplicacaoServicoDTO = Replicacao::getInstance()->obterServico($objReplicacaoServicoDTO);

        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();

        if ($objReplicacaoServicoDTO != null){

          $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
          $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
          $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
          $objRelHierarquiaUnidadeDTO->retStrIdOrigemUnidade();
          $objRelHierarquiaUnidadeDTO->retNumIdOrgaoUnidade();
          $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
          $objRelHierarquiaUnidadeDTO->retStrDescricaoUnidade();
          $objRelHierarquiaUnidadeDTO->retStrSinAtivo();

          $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objReplicacaoUnidadeDTO->getNumIdHierarquia());

          if (is_array($objReplicacaoUnidadeDTO->getNumIdUnidade())){
            $arrIdUnidade = $objReplicacaoUnidadeDTO->getNumIdUnidade();
          }else{
            $arrIdUnidade = array($objReplicacaoUnidadeDTO->getNumIdUnidade());
          }

          $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrIdUnidade, InfraDTO::$OPER_IN);

          $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

          if (count($arrObjRelHierarquiaUnidadeDTO)) {

            $arr = array();
            foreach ($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO) {
              $arr[] = array(
                  'StaOperacao' => $objReplicacaoUnidadeDTO->getStrStaOperacao(),
                  'IdUnidade' => $objRelHierarquiaUnidadeDTO->getNumIdUnidade(),
                  'IdOrigem' => $objRelHierarquiaUnidadeDTO->getStrIdOrigemUnidade(),
                  'IdOrgao' => $objRelHierarquiaUnidadeDTO->getNumIdOrgaoUnidade(),
                  'Sigla' => $objRelHierarquiaUnidadeDTO->getStrSiglaUnidade(),
                  'Descricao' => $objRelHierarquiaUnidadeDTO->getStrDescricaoUnidade(),
                  'SinAtivo' => $objRelHierarquiaUnidadeDTO->getStrSinAtivo());
            }

            Replicacao::getInstance()->executar($objReplicacaoServicoDTO, $arr);
          }
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro replicando unidade.',$e);
    }
  }
  
  public function replicarOrgao(ReplicacaoOrgaoDTO $objReplicacaoOrgaoDTO) {
    
    $strMsg = '';
    
    try{

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      
      if ($objReplicacaoOrgaoDTO->isSetNumIdSistema()){
        $objSistemaDTO->setNumIdSistema($objReplicacaoOrgaoDTO->getNumIdSistema());
      }
      
      $arrObjSistemaDTO = $this->listar($objSistemaDTO);
      
      foreach($arrObjSistemaDTO as $objSistemaDTO){

        $objReplicacaoServicoDTO = new ReplicacaoServicoDTO();
        $objReplicacaoServicoDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
        $objReplicacaoServicoDTO->setStrNomeOperacao('replicarOrgao');
        $objReplicacaoServicoDTO = Replicacao::getInstance()->obterServico($objReplicacaoServicoDTO);
        
        if ($objReplicacaoServicoDTO != null){
      
          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setBolExclusaoLogica(false);
          $objOrgaoDTO->retNumIdOrgao();
          $objOrgaoDTO->retStrSigla();
          $objOrgaoDTO->retStrDescricao();
          $objOrgaoDTO->retStrSinAtivo();


          if (is_array($objReplicacaoOrgaoDTO->getNumIdOrgao())){
            $arrIdOrgao = $objReplicacaoOrgaoDTO->getNumIdOrgao();
          }else{
            $arrIdOrgao = array($objReplicacaoOrgaoDTO->getNumIdOrgao());
          }

          $objOrgaoDTO->setNumIdOrgao($arrIdOrgao, InfraDTO::$OPER_IN);

          $objOrgaoRN = new OrgaoRN();
          $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

          if (count($arrObjOrgaoDTO)) {

            $arr = array();
            foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {
              $arr[] = array(
                  'StaOperacao' => $objReplicacaoOrgaoDTO->getStrStaOperacao(),
                  'IdOrgao' => $objOrgaoDTO->getNumIdOrgao(),
                  'Sigla' => $objOrgaoDTO->getStrSigla(),
                  'Descricao' => $objOrgaoDTO->getStrDescricao(),
                  'SinAtivo' => $objOrgaoDTO->getStrSinAtivo());
            }

            Replicacao::getInstance()->executar($objReplicacaoServicoDTO, $arr);
          }
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro replicando órgão.',$e);
    }
  }

  public function replicarAssociacaoUsuarioUnidade(ReplicacaoAssociacaoUsuarioUnidadeDTO $objReplicacaoAssociacaoUsuarioUnidadeDTO) {
    
    try{

      $objReplicacaoServicoDTO = new ReplicacaoServicoDTO();
      $objReplicacaoServicoDTO->setNumIdSistema($objReplicacaoAssociacaoUsuarioUnidadeDTO->getNumIdSistema());
      $objReplicacaoServicoDTO->setStrNomeOperacao('replicarAssociacaoUsuarioUnidade');
      $objReplicacaoServicoDTO = Replicacao::getInstance()->obterServico($objReplicacaoServicoDTO);
      
      if ($objReplicacaoServicoDTO != null){
          Replicacao::getInstance()->executar($objReplicacaoServicoDTO, $objReplicacaoAssociacaoUsuarioUnidadeDTO->getStrStaOperacao(),
                                                                        $objReplicacaoAssociacaoUsuarioUnidadeDTO->getNumIdUsuario(),
                                                                        $objReplicacaoAssociacaoUsuarioUnidadeDTO->getNumIdUnidade());
      }

    }catch(Exception $e){
      throw new InfraException('Erro replicando associação entre usuário e unidade.',$e);
    }
  }

  public function replicarPermissao(ReplicacaoPermissaoDTO $objReplicacaoPermissaoDTO) {

    try{

      $objReplicacaoServicoDTO = new ReplicacaoServicoDTO();
      $objReplicacaoServicoDTO->setNumIdSistema($objReplicacaoPermissaoDTO->getNumIdSistema());
      $objReplicacaoServicoDTO->setStrNomeOperacao('replicarPermissao');
      $objReplicacaoServicoDTO = Replicacao::getInstance()->obterServico($objReplicacaoServicoDTO);

      if ($objReplicacaoServicoDTO != null){

        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->retNumIdSistema();
        $objPermissaoDTO->retNumIdUsuario();
        $objPermissaoDTO->retNumIdUnidade();
        $objPermissaoDTO->retNumIdPerfil();
        $objPermissaoDTO->retDtaDataInicio();
        $objPermissaoDTO->retDtaDataFim();
        $objPermissaoDTO->retStrSinSubunidades();

        $objPermissaoDTO->setNumIdSistema($objReplicacaoPermissaoDTO->getNumIdSistema());

        if ($objReplicacaoPermissaoDTO->isSetNumIdUsuario()) {
          $objPermissaoDTO->setNumIdUsuario($objReplicacaoPermissaoDTO->getNumIdUsuario());
        }

        if ($objReplicacaoPermissaoDTO->isSetNumIdUnidade()) {
          $objPermissaoDTO->setNumIdUnidade($objReplicacaoPermissaoDTO->getNumIdUnidade());
        }

        if ($objReplicacaoPermissaoDTO->isSetNumIdPerfil()) {
          $objPermissaoDTO->setNumIdPerfil($objReplicacaoPermissaoDTO->getNumIdPerfil());
        }

        $objPermissaoRN = new PermissaoRN();
        $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

        foreach($arrObjPermissaoDTO as $objPermissaoDTO){

          $arrUnidadesReplicacao = array($objPermissaoDTO->getNumIdUnidade());

          if ($objPermissaoDTO->getStrSinSubunidades()=='S'){
            $objSistemaDTO = new SistemaDTO();
            $objSistemaDTO->setNumIdSistema($objReplicacaoPermissaoDTO->getNumIdSistema());
            $objSistemaDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
            $arrHierarquia = Replicacao::getInstance()->obterHierarquia($objSistemaDTO);
            $arrUnidadesReplicacao = array_merge($arrUnidadesReplicacao, InfraArray::converterArrInfraDTO($arrHierarquia[$objPermissaoDTO->getNumIdUnidade()]->getArrUnidadesInferiores(),'IdUnidade'));
          }

          $arr = array();
          foreach($arrUnidadesReplicacao as $numIdUnidadeReplicacao) {
            $arr[] = array(
                'StaOperacao' => $objReplicacaoPermissaoDTO->getStrStaOperacao(),
                'IdSistema' => $objPermissaoDTO->getNumIdSistema(),
                'IdUsuario' => $objPermissaoDTO->getNumIdUsuario(),
                'IdUnidade' => $numIdUnidadeReplicacao,
                'IdPerfil' => $objPermissaoDTO->getNumIdPerfil(),
                'DtaInicio' => $objPermissaoDTO->getDtaDataInicio(),
                'DtaFim' =>  $objPermissaoDTO->getDtaDataFim());
          }

          Replicacao::getInstance()->executar($objReplicacaoServicoDTO, $arr);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro replicando permissão.',$e);
    }
  }

  public static function gerarChaveAcessoControlado( SistemaDTO $objSistemaDTO){
    try {

      SessaoSip::getInstance()->validarAuditarPermissao('sistema_gerar_chave_acesso', __METHOD__, $objSistemaDTO);

      $strRandom = random_bytes(32);
      $strSha256 = hash('sha256', $strRandom);

      $objInfraBcrypt = new InfraBcrypt();
      $strChave = $objInfraBcrypt->hash(md5($strSha256));

      $strCrc = strtolower(hash('crc32b', $objSistemaDTO->getNumIdSistema()));

      $objSistemaDTO_Chave = new SistemaDTO();
      $objSistemaDTO_Chave->setStrCrc($strCrc);
      $objSistemaDTO_Chave->setStrChaveAcesso($strChave);
      $objSistemaDTO_Chave->setNumIdSistema($objSistemaDTO->getNumIdSistema());

      $objSistemaBD = new SistemaBD(BancoSip::getInstance());
      $objSistemaBD->alterar($objSistemaDTO_Chave);

      $objSistemaDTORet = new SistemaDTO();
      $objSistemaDTORet->setStrChaveCompleta($strCrc.$strSha256);

      return $objSistemaDTORet;

    }catch(Exception $e){
      throw new InfraException('Erro gerando chave de acesso para o Sistema.', $e);
    }
  }}
?>