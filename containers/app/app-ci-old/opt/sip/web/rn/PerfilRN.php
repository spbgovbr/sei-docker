<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';


class PerfilRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  public function importarPerfilControlado(ImportarRecursosDTO $objImportarRecursosDTO){
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_importar',__METHOD__,$objImportarRecursosDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objImportarRecursosDTO->getNumIdSistema())){
        $objInfraException->adicionarValidacao('Sistema local não informado.');
      }

      if (InfraString::isBolVazia($objImportarRecursosDTO->getNumIdPerfil())){
        $objInfraException->adicionarValidacao('Perfil local não informado.');
      }

      if (InfraString::isBolVazia($objImportarRecursosDTO->getNumIdMenu())){
        $objImportarRecursosDTO->setNumIdMenu(null);
      }

      if (InfraArray::contar($objImportarRecursosDTO->getArrRecursosMenus())==0){
        $objInfraException->adicionarValidacao('Array de recursos e menus do perfil a ser importado não informado.');
      }

      $objInfraException->lancarValidacoes();

      $numIdSistema = $objImportarRecursosDTO->getNumIdSistema();
      $numIdPerfil = $objImportarRecursosDTO->getNumIdPerfil();
      $numIdMenu = $objImportarRecursosDTO->getNumIdMenu();
      $arrRecursosMenus = $objImportarRecursosDTO->getArrRecursosMenus();
//      echo '<pre>';print_r($arrRecursosMenus);die;

      foreach ($arrRecursosMenus as $strRecurso => $strMenus){
        $objRecursoDTO = ScriptSip::adicionarRecursoPerfil($numIdSistema,$numIdPerfil,$strRecurso);
//        echo '<pre>';print_r($objRecursoDTO);die($strMenus.'#'.$strRecurso);
        foreach ($strMenus as $strMenu){
          //se estiver importando item de menu do sistema remoto e o sistema local nao possuir menu -> cria um menu local
          if(is_null($numIdMenu)){
            $objMenuDTO = new MenuDTO();
            $objMenuDTO->setNumIdSistema($numIdSistema);
            $objMenuDTO->setStrNome('Principal');

            $objMenuRN = new MenuRN();
            $objMenuDTO = $objMenuRN->cadastrar($objMenuDTO);
            $objImportarRecursosDTO->setNumIdMenu($objMenuDTO->getNumIdMenu());
            $numIdMenu = $objImportarRecursosDTO->getNumIdMenu();
          }
          $arrMenus = explode(' / ', $strMenu);
//          echo '<pre>';print_r($arrMenus);die($strMenu.'#'.$strRecurso);

          $objItemMenuDTO = null;
          if(InfraArray::contar($arrMenus)==1){//recurso esta associado a um menu raiz
            $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, null, $objRecursoDTO->getNumIdRecurso(), $arrMenus[0], 1000);
          } else {//recurso esta associado a uma arvore de menus
            for ($i = 0; $i < InfraArray::contar($arrMenus); $i++) {
              if (is_null($objItemMenuDTO)) {//adiciona menu raiz
                $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, null, null, $arrMenus[$i], 1000);
              } else {//adiciona demais itens de menu, ate chegar na folha
                $objItemMenuDTO = ScriptSip::adicionarItemMenu($numIdSistema, $numIdPerfil, $numIdMenu, $objItemMenuDTO->getNumIdItemMenu(), $objRecursoDTO->getNumIdRecurso(), $arrMenus[$i], 1000);
              }
            }
          }
        }
      }

    }catch (Exception $e){
      throw new InfraException('Erro importando Perfil.',$e);
    }
  }

  public function compararPerfil(CompararPerfilDTO $objCompararPerfilDTO) {
    try{

      $objCompararPerfilDTOAuditoria = clone($objCompararPerfilDTO);
      $objCompararPerfilDTOAuditoria->unSetStrBancoSenha();

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_comparar',__METHOD__,$objCompararPerfilDTOAuditoria);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objCompararPerfilDTO->getNumIdOrgaoSistemaDestino())){
        $objInfraException->adicionarValidacao('Órgão do sistema local não informado.');
      }

      if (InfraString::isBolVazia($objCompararPerfilDTO->getNumIdSistemaDestino())){
        $objInfraException->adicionarValidacao('Sistema local não informado.');
      }

      if (InfraString::isBolVazia($objCompararPerfilDTO->getNumIdPerfilDestino())){
        $objInfraException->adicionarValidacao('Perfil local não informado.');
      }

      if (!$objCompararPerfilDTO->isSetStrStaBaseComparacao()) {
        $objInfraException->lancarValidacao('Base de comparação não informada.');
      }
      
      if ($objCompararPerfilDTO->getStrStaBaseComparacao()=='L') {//base (L)ocal
        if (InfraString::isBolVazia($objCompararPerfilDTO->getNumIdOrgaoSistemaOrigem())){
          $objInfraException->adicionarValidacao('Órgão do sistema local não informado.');
        }
  
        if (InfraString::isBolVazia($objCompararPerfilDTO->getNumIdSistemaOrigem())){
          $objInfraException->adicionarValidacao('Sistema local não informado.');
        }
  
        if (InfraString::isBolVazia($objCompararPerfilDTO->getNumIdPerfilOrigem())){
          $objInfraException->adicionarValidacao('Perfil local não informado.');
        }
      } else if ($objCompararPerfilDTO->getStrStaBaseComparacao()=='R') {//base (R)emota
        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrBancoServidor())) {
          $objInfraException->adicionarValidacao('Servidor do banco de dados remoto não informado.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrBancoPorta())) {
          $objInfraException->adicionarValidacao('Porta do banco de dados remoto não informada.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrBancoNome())) {
          $objInfraException->adicionarValidacao('Nome da base de dados remota não informado.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrBancoUsuario())) {
          $objInfraException->adicionarValidacao('Usuário do banco de dados remoto não informado.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrBancoSenha())) {
          $objInfraException->adicionarValidacao('Senha do banco de dados remoto não informada.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrStaTipoBanco())) {
          $objInfraException->adicionarValidacao('Tipo do banco de dados remoto não informado.');
        }

        if (!in_array($objCompararPerfilDTO->getStrStaTipoBanco(), array(SistemaRN::$TBD_MYSQL, SistemaRN::$TBD_SQLSERVER, SistemaRN::$TBD_ORACLE))) {
          $objInfraException->adicionarValidacao('Tipo do banco de dados remoto inválido.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrSiglaOrgaoSistemaOrigem())) {
          $objInfraException->adicionarValidacao('Órgão do sistema remoto não informado.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrSiglaSistemaOrigem())) {
          $objInfraException->adicionarValidacao('Sistema remoto não informado.');
        }

        if (InfraString::isBolVazia($objCompararPerfilDTO->getStrPerfilOrigem())) {
          $objInfraException->adicionarValidacao('Perfil remoto não informado.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objSistemaRN = new SistemaRN();
      
      if ($objCompararPerfilDTO->getStrStaBaseComparacao()=='R') {//base (R)emota
        switch ($objCompararPerfilDTO->getStrStaTipoBanco()) {
          case SistemaRN::$TBD_SQLSERVER:
            BancoSip::setBanco(InfraBancoSqlServer::newInstance($objCompararPerfilDTO->getStrBancoServidor(),
              $objCompararPerfilDTO->getStrBancoPorta(),
              $objCompararPerfilDTO->getStrBancoNome(),
              $objCompararPerfilDTO->getStrBancoUsuario(),
              $objCompararPerfilDTO->getStrBancoSenha()));
            break;

          case SistemaRN::$TBD_MYSQL:
            BancoSip::setBanco(InfraBancoMySqli::newInstance($objCompararPerfilDTO->getStrBancoServidor(),
              $objCompararPerfilDTO->getStrBancoPorta(),
              $objCompararPerfilDTO->getStrBancoNome(),
              $objCompararPerfilDTO->getStrBancoUsuario(),
              $objCompararPerfilDTO->getStrBancoSenha()));
            break;

          case SistemaRN::$TBD_ORACLE:
            BancoSip::setBanco(InfraBancoOracle::newInstance($objCompararPerfilDTO->getStrBancoServidor(),
              $objCompararPerfilDTO->getStrBancoPorta(),
              $objCompararPerfilDTO->getStrBancoNome(),
              $objCompararPerfilDTO->getStrBancoUsuario(),
              $objCompararPerfilDTO->getStrBancoSenha()));
            break;

          case SistemaRN::$TBD_POSTGRESQL:
            BancoSip::setBanco(InfraBancoPostgreSql::newInstance($objCompararPerfilDTO->getStrBancoServidor(),
                $objCompararPerfilDTO->getStrBancoPorta(),
                $objCompararPerfilDTO->getStrBancoNome(),
                $objCompararPerfilDTO->getStrBancoUsuario(),
                $objCompararPerfilDTO->getStrBancoSenha()));
            break;

        }

        //Consulta sistema remoto
        $objSistemaRemotoDTO = new SistemaDTO();
        $objSistemaRemotoDTO->retTodos();
        $objSistemaRemotoDTO->setStrSiglaOrgao($objCompararPerfilDTO->getStrSiglaOrgaoSistemaOrigem());
        $objSistemaRemotoDTO->setStrSigla($objCompararPerfilDTO->getStrSiglaSistemaOrigem());
        $objSistemaRemotoDTO = $objSistemaRN->consultar($objSistemaRemotoDTO);
        if ($objSistemaRemotoDTO == null) {
          BancoSip::setBanco(null);
          $objInfraException->lancarValidacao('Sistema Origem não encontrado.');
        }

        //Consulta perfil remoto
        $objPerfilRemotoDTO = new PerfilDTO();
        $objPerfilRemotoDTO->retTodos();
        $objPerfilRemotoDTO->setStrNome($objCompararPerfilDTO->getStrPerfilOrigem());
        $objPerfilRemotoDTO->setNumIdSistema($objSistemaRemotoDTO->getNumIdSistema());
        $objPerfilRemotoDTO = $this->consultar($objPerfilRemotoDTO);
        if ($objPerfilRemotoDTO == null) {
          BancoSip::setBanco(null);
          $objInfraException->lancarValidacao('Perfil Origem não encontrado.');
        }

        //busca recursos/menus
        $objPerfilRemotoDTO->setStrSinVisualizarProprios('S');
        $arrObjRecursoRemotoDTO = $this->listarMontar($objPerfilRemotoDTO);

        //Finaliza trabalhos com a base de local
        BancoSip::setBanco(null);
      } else if ($objCompararPerfilDTO->getStrStaBaseComparacao()=='L') {//base (L)ocal
        //Consulta sistema local
        $objSistemaRemotoDTO = new SistemaDTO();
        $objSistemaRemotoDTO->retTodos();
        $objSistemaRemotoDTO->setNumIdOrgao($objCompararPerfilDTO->getNumIdOrgaoSistemaOrigem());
        $objSistemaRemotoDTO->setNumIdSistema($objCompararPerfilDTO->getNumIdSistemaOrigem());
        $objSistemaRemotoDTO = $objSistemaRN->consultar($objSistemaRemotoDTO);
        if ($objSistemaRemotoDTO==null){
          $objInfraException->lancarValidacao('Sistema Origem não encontrado.');
        }
  
        //Consulta perfil local
        $objPerfilRemotoDTO = new PerfilDTO();
        $objPerfilRemotoDTO->retTodos();
        $objPerfilRemotoDTO->setNumIdPerfil($objCompararPerfilDTO->getNumIdPerfilOrigem());
        $objPerfilRemotoDTO->setNumIdSistema($objSistemaRemotoDTO->getNumIdSistema());
        $objPerfilRemotoDTO = $this->consultar($objPerfilRemotoDTO);
        if ($objPerfilRemotoDTO==null){
          $objInfraException->lancarValidacao('Perfil Origem não encontrado.');
        }
  
        //busca recursos/menus
        $objPerfilRemotoDTO->setStrSinVisualizarProprios('S');
        $arrObjRecursoRemotoDTO = $this->listarMontar($objPerfilRemotoDTO);
      }

      //Consulta sistema local
			$objSistemaLocalDTO = new SistemaDTO();
			$objSistemaLocalDTO->retTodos();
			$objSistemaLocalDTO->setNumIdOrgao($objCompararPerfilDTO->getNumIdOrgaoSistemaDestino());
			$objSistemaLocalDTO->setNumIdSistema($objCompararPerfilDTO->getNumIdSistemaDestino());
      $objSistemaLocalDTO = $objSistemaRN->consultar($objSistemaLocalDTO);
			if ($objSistemaLocalDTO==null){
			  $objInfraException->lancarValidacao('Sistema Destino não encontrado.');
			}

			//Consulta perfil local
			$objPerfilLocalDTO = new PerfilDTO();
			$objPerfilLocalDTO->retTodos();
			$objPerfilLocalDTO->setNumIdPerfil($objCompararPerfilDTO->getNumIdPerfilDestino());
			$objPerfilLocalDTO->setNumIdSistema($objSistemaLocalDTO->getNumIdSistema());
      $objPerfilLocalDTO = $this->consultar($objPerfilLocalDTO);
			if ($objPerfilLocalDTO==null){
			  $objInfraException->lancarValidacao('Perfil Destino não encontrado.');
			}

			//busca recursos/menus
      $objPerfilLocalDTO->setStrSinVisualizarProprios('S');
			$arrObjRecursoLocalDTO = $this->listarMontar($objPerfilLocalDTO);

      //carrega no DTO o array de objRecursosDTO
      $objCompararPerfilDTO->setArrObjRecursoDestinoDTO($arrObjRecursoLocalDTO);
      $objCompararPerfilDTO->setArrObjRecursoOrigemDTO($arrObjRecursoRemotoDTO);

      //================================================================================================================
      //se usuario marcou checkbox visualizar somente as diferenças
      //entao remove os recursos existentes nos DTO Local e Remoto que possuirem os mesmos menus
			if((is_array($arrObjRecursoLocalDTO))&&(is_array($arrObjRecursoRemotoDTO))&&
        ($objCompararPerfilDTO->getStrSinSomenteDiferencas()=='S')){

			  //indexa DTOs pelo nome do recurso
        $arrObjRecursosPerfilLocal = InfraArray::indexarArrInfraDTO($arrObjRecursoLocalDTO,'Nome');
        $arrObjRecursosPerfilRemoto = InfraArray::indexarArrInfraDTO($arrObjRecursoRemotoDTO,'Nome');

        //a diferença consiste nos recursos apenas em A e em B, ou seja, [(A - interseçao) U (B - interseçao)]
        //somado aos recursos da interseçao onde os menus forem diferentes
        $arrIntersecao = array_intersect(array_keys($arrObjRecursosPerfilLocal),array_keys($arrObjRecursosPerfilRemoto));
        sort($arrIntersecao);
        $arrDiffLocalItersecao = array_diff(array_keys($arrObjRecursosPerfilLocal), $arrIntersecao);
        sort($arrDiffLocalItersecao);
        $arrDiffRemotoItersecao = array_diff(array_keys($arrObjRecursosPerfilRemoto), $arrIntersecao);
        sort($arrDiffRemotoItersecao);
        $arrMergeForaIntersecao = array_merge($arrDiffLocalItersecao,$arrDiffRemotoItersecao);
        sort($arrMergeForaIntersecao);

        $arrObjRecursoLocalDTOtemp = array();
        $arrObjRecursoRemotoDTOtemp = array();

        //insere os recursos Local e Remoto que estao fora da interseçao
        $numRegistros = InfraArray::contar($arrMergeForaIntersecao);
        for($i=0;$i<$numRegistros;$i++){
          $itemRecurso = array_shift($arrMergeForaIntersecao);
          if(in_array($itemRecurso, $arrDiffLocalItersecao)){
            $arrObjRecursoLocalDTOtemp[] = $arrObjRecursosPerfilLocal[$itemRecurso];
          }
          if(in_array($itemRecurso, $arrDiffRemotoItersecao)){
            $arrObjRecursoRemotoDTOtemp[] = $arrObjRecursosPerfilRemoto[$itemRecurso];
          }
        }

        //insere os recursos Local e Remoto que estao na interseçao *E* possuem menus *diferentes*
        $numRegistros = InfraArray::contar($arrIntersecao);
        for($i=0;$i<$numRegistros;$i++){
          $itemRecurso = array_shift($arrIntersecao);
          //separa array de objetos item menu dto
          $arrMenusLocal = $arrObjRecursosPerfilLocal[$itemRecurso]->getArrObjItemMenuDTO();
          $arrMenusRemoto = $arrObjRecursosPerfilRemoto[$itemRecurso]->getArrObjItemMenuDTO();
          //se nao houver menu(s) no sistema remoto entao nao precisa incluir, pois nao eh possivel importar um menu do sistema local para o remoto
          if(InfraArray::contar($arrMenusRemoto)==0){
            continue;
          }
          //se a contagem dos menus nao for igual -> incluir, pois sao diferentes, garantidamente
          if(InfraArray::contar($arrMenusLocal)!=InfraArray::contar($arrMenusRemoto)){
            $arrObjRecursoLocalDTOtemp[] = $arrObjRecursosPerfilLocal[$itemRecurso];
            $arrObjRecursoRemotoDTOtemp[] = $arrObjRecursosPerfilRemoto[$itemRecurso];
            continue;
          }
          //se chegou aqui eh pq a contagem de menus eh igual e positiva -> verifica se algum menu eh diferente -> incluir
          //caso o menu nao esteja associado ao perfil remoto, entao nao exibir, pois nao eh possivel importar um menu do sistema local para o remoto
          $bolMenusIguais = true;
//          if ((InfraArray::contar($arrMenusLocal)>0)&&(InfraArray::contar($arrMenusRemoto)>0)) {
//            if (InfraArray::contar($arrMenusLocal)==InfraArray::contar($arrMenusRemoto)) {
              for ($j = 0; $j < InfraArray::contar($arrMenusLocal); $j++) {
//                if ((!is_null($arrMenusLocal[$j]))&&(!is_null($arrMenusRemoto[$j]))&&
//                  ($arrMenusLocal[$j]->getStrSinPerfil() != $arrMenusRemoto[$j]->getStrSinPerfil())) {
                if(($arrMenusLocal[$j]->getStrSinPerfil()=='N')&&($arrMenusRemoto[$j]->getStrSinPerfil()=='S')){
                  $bolMenusIguais = false;
                  break;
                }
              }
//            }else{
//              $bolMenusIguais = false;
//            }
//          }
          if(!$bolMenusIguais){//se menus forem diferentes, incluir
            $arrObjRecursoLocalDTOtemp[] = $arrObjRecursosPerfilLocal[$itemRecurso];
            $arrObjRecursoRemotoDTOtemp[] = $arrObjRecursosPerfilRemoto[$itemRecurso];
          }
        }
        $objCompararPerfilDTO->setArrObjRecursoDestinoDTO($arrObjRecursoLocalDTOtemp);
        $objCompararPerfilDTO->setArrObjRecursoOrigemDTO($arrObjRecursoRemotoDTOtemp);
      }
      //================================================================================================================

			//Auditoria

      return $objCompararPerfilDTO;

    }catch(Exception $e){

      BancoSip::setBanco(null);

      throw new InfraException('Erro comparando Perfil.',$e);
    }
  }

  protected function clonarControlado(ClonarPerfilDTO $objClonarPerfilDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_clonar',__METHOD__,$objClonarPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objClonarPerfilDTO->getNumIdOrgaoSistema())){
        $objInfraException->adicionarValidacao('Órgão do Sistema não informado.');
      }

      if (InfraString::isBolVazia($objClonarPerfilDTO->getNumIdSistema())){
        $objInfraException->adicionarValidacao('Sistema não informado.');
      }

      if (InfraString::isBolVazia($objClonarPerfilDTO->getNumIdPerfilOrigem())){
        $objInfraException->adicionarValidacao('Perfil Origem não informado.');
      }

      if (InfraString::isBolVazia($objClonarPerfilDTO->getStrPerfilDestino())){
        $objInfraException->adicionarValidacao('Perfil de Destino não informado.');
      }
			

			$dto = new PerfilDTO();
			$dto->setNumIdSistema($objClonarPerfilDTO->getNumIdSistema());
			$dto->setStrNome($objClonarPerfilDTO->getStrPerfilDestino());
			if ($this->contar($dto)>0){
			  $objInfraException->adicionarValidacao('Já existe um perfil neste sistema com este Nome de Origem.');
			}

      $objInfraException->lancarValidacoes();

      //Consulta perfil Destino
      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->retTodos();
			$objPerfilDTO->setNumIdSistema($objClonarPerfilDTO->getNumIdSistema());
			$objPerfilDTO->setNumIdPerfil($objClonarPerfilDTO->getNumIdPerfilOrigem());

      $objClonarPerfilDTO->setObjPerfilDTO($this->consultar($objPerfilDTO));
			
			//Le dados para o Sistema Destino
      //Recursos do perfil
      $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
      $objRelPerfilRecursoDTO->retTodos();
      $objRelPerfilRecursoDTO->setNumIdSistema($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdSistema());
      $objRelPerfilRecursoDTO->setNumIdPerfil($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdPerfil());
      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $objClonarPerfilDTO->setArrObjRelPerfilRecursoDTO($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));
			
      //Itens de menu do perfil
      $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
      $objRelPerfilItemMenuDTO->retTodos();
      $objRelPerfilItemMenuDTO->setNumIdSistema($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdSistema());
      $objRelPerfilItemMenuDTO->setNumIdPerfil($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdPerfil());
      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      $objClonarPerfilDTO->setArrObjRelPerfilItemMenuDTO($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));

      //Coordenadores do perfil
      $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
      $objCoordenadorPerfilDTO->retTodos();
      $objCoordenadorPerfilDTO->setNumIdSistema($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdSistema());
      $objCoordenadorPerfilDTO->setNumIdPerfil($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdPerfil());
      $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
      $objClonarPerfilDTO->setArrObjCoordenadorPerfilDTO($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));
      
			//grava dados para Sistema Origem
      $objPerfilDTO = $objClonarPerfilDTO->getObjPerfilDTO();
			$objPerfilDTO->setNumIdSistema($objClonarPerfilDTO->getNumIdSistema());
      $objPerfilDTO->setStrNome($objClonarPerfilDTO->getStrPerfilDestino());
      $objClonarPerfilDTO->setObjPerfilDTO($this->cadastrar($objPerfilDTO));
			
      
      //Clona recursos dos perfis
      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $arrObjRelPerfilRecursoDTO = $objClonarPerfilDTO->getArrObjRelPerfilRecursoDTO();
      if (is_array($arrObjRelPerfilRecursoDTO)){
        foreach($arrObjRelPerfilRecursoDTO as $dto){
          $dto->setNumIdPerfil($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdPerfil());
          $dto->setNumIdSistema($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdSistema());
          $objRelPerfilRecursoRN->cadastrar($dto);
        }
      }

      //Clona itens de menu dos perfis
      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      $arrObjRelPerfilItemMenuDTO = $objClonarPerfilDTO->getArrObjRelPerfilItemMenuDTO();
      if (is_array($arrObjRelPerfilItemMenuDTO)){
        foreach($arrObjRelPerfilItemMenuDTO as $dto){
          $dto->setNumIdPerfil($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdPerfil());
          $dto->setNumIdSistema($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdSistema());
          $objRelPerfilItemMenuRN->cadastrar($dto);
        }
      }
      
      //Clona coordenadores de perfil
      $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
      $arrObjCoordenadorPerfilDTO = $objClonarPerfilDTO->getArrObjCoordenadorPerfilDTO();
      if (is_array($arrObjCoordenadorPerfilDTO)){
        foreach($arrObjCoordenadorPerfilDTO as $dto){
          $dto->setNumIdPerfil($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdPerfil());
          $dto->setNumIdSistema($objClonarPerfilDTO->getObjPerfilDTO()->getNumIdSistema());
          $objCoordenadorPerfilRN->cadastrar($dto);
        }
      }
      
			//Auditoria

      return $objClonarPerfilDTO->getObjPerfilDTO();

    }catch(Exception $e){
      throw new InfraException('Erro clonando Perfil.',$e);
    }
  }

  protected function listarMontarConectado(PerfilDTO $objPerfilDTO){
    try {
  
      $arrObjRecursoDTO = array();

      $objMontarPerfilDTO = new MontarPerfilDTO();
      $objMontarPerfilDTO->retNumIdRecurso();
      $objMontarPerfilDTO->retStrNome();
      $objMontarPerfilDTO->retStrDescricao();
      $objMontarPerfilDTO->retStrSinAtivo();
      
      $objMontarPerfilDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
      
      if ($objPerfilDTO->getStrSinVisualizarProprios()=='S'){
        $objMontarPerfilDTO->setNumIdPerfilRelPerfilRecurso($objPerfilDTO->getNumIdPerfil());
      }
      
      
      if ($objPerfilDTO->isSetStrNomeRecurso() && !InfraString::isBolVazia($objPerfilDTO->getStrNomeRecurso())){
        $objMontarPerfilDTO->setStrNome('%'.$objPerfilDTO->getStrNomeRecurso().'%',InfraDTO::$OPER_LIKE);
      }
      
   		$objMontarPerfilDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

   		
      //paginação 
   		$objMontarPerfilDTO->setNumMaxRegistrosRetorno($objPerfilDTO->getNumMaxRegistrosRetorno());
  		$objMontarPerfilDTO->setNumPaginaAtual($objPerfilDTO->getNumPaginaAtual());
   		
      $objRecursoRN = new RecursoRN();
      $arrObjMontarPerfilDTO = $objRecursoRN->listarMontar($objMontarPerfilDTO);

			//paginação
			$objPerfilDTO->setNumTotalRegistros($objMontarPerfilDTO->getNumTotalRegistros());
      $objPerfilDTO->setNumRegistrosPaginaAtual($objMontarPerfilDTO->getNumRegistrosPaginaAtual());
      
      if ($objPerfilDTO->getStrSinVisualizarProprios()=='N'){
        $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
        $objRelPerfilRecursoDTO->retNumIdRecurso();
        $objRelPerfilRecursoDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
        $objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
        
        $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
        $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);
      }else{
        $arrObjRelPerfilRecursoDTO = array();
        foreach($arrObjMontarPerfilDTO as $dto){
          $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
          $objRelPerfilRecursoDTO->setNumIdRecurso($dto->getNumIdRecurso());
          $arrObjRelPerfilRecursoDTO[] = $objRelPerfilRecursoDTO;
        }
      }

      $arrTemp = InfraArray::converterArrInfraDTO($arrObjRelPerfilRecursoDTO,'IdRecurso');
      
      foreach($arrObjMontarPerfilDTO as $dto){
          
    		//Lista recursos do sistema
    	  $objRecursoDTO = new RecursoDTO();
    		$objRecursoDTO->setNumIdRecurso($dto->getNumIdRecurso());
    		$objRecursoDTO->setStrNome($dto->getStrNome());
    		$objRecursoDTO->setStrDescricao($dto->getStrDescricao());
    		$objRecursoDTO->setStrSinAtivo($dto->getStrSinAtivo());
    	
    		if ($objPerfilDTO->getStrSinVisualizarProprios()=='S' || in_array($dto->getNumIdRecurso(),$arrTemp)){
    		  $objRecursoDTO->setStrSinPerfil('S');
    		}else{
     		  $objRecursoDTO->setStrSinPerfil('N');
    		}
    		
    		$arrObjRecursoDTO[] = $objRecursoDTO;
      }
      
      
    		
      //Lista itens de menu do perfil
  		$objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO(true);
      $objRelPerfilItemMenuDTO->retNumIdRecurso();
      $objRelPerfilItemMenuDTO->retNumIdMenu();
      $objRelPerfilItemMenuDTO->retNumIdItemMenu();
      $objRelPerfilItemMenuDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
      $objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());				
      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      $arrObjRelPerfilItemMenuDTO = $objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO);

      
  		//Lista hierarquias de menu do sistema, o sistema pode ter mais de um menu
  		$objMenuDTO = new MenuDTO();
  		$objMenuDTO->retNumIdMenu();
  		$objMenuDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
  		$objMenuRN = new MenuRN();
  		$arrObjMenuDTO = $objMenuRN->listar($objMenuDTO);
  		$objItemMenuDTO = new ItemMenuDTO();
  		$objItemMenuRN = new ItemMenuRN();
  		
  		$arrRamificacoesItensMenuDTO = array();
  		foreach($arrObjMenuDTO as $objMenuDTO){
  			$objItemMenuDTO->setNumIdMenu($objMenuDTO->getNumIdMenu());
  		  $arrRamificacoesItensMenuDTO[] = $objItemMenuRN->listarHierarquia($objItemMenuDTO);	
  		}

  		$arrTemp = array();
  		foreach($arrRamificacoesItensMenuDTO as $arrRamificacao){
  		  foreach($arrRamificacao as $ramificacao){
    		  $ramificacao->setStrSinPerfil('N');
    		  foreach($arrObjRelPerfilItemMenuDTO as $objRelPerfilItemMenuDTO){
    		    if ($objRelPerfilItemMenuDTO->getNumIdRecurso()==$ramificacao->getNumIdRecurso() && $objRelPerfilItemMenuDTO->getNumIdMenu()==$ramificacao->getNumIdMenu() && $objRelPerfilItemMenuDTO->getNumIdItemMenu()==$ramificacao->getNumIdItemMenu()){
    		      $ramificacao->setStrSinPerfil('S');
    		      break;
    		      //$objRelPerfilItemMenuDTO->setStrRamificacao($ramificacao->getStrRamificacao());
    		    }
    		  }
    		  
    		  if (!isset($arrTemp[$ramificacao->getNumIdRecurso()])){
    		    $arrTemp[$ramificacao->getNumIdRecurso()] = array();
    		  }
    		  
    		  $arrTemp[$ramificacao->getNumIdRecurso()][] = $ramificacao;
  		  }
  		}
  		
  		//atribui itens de menu do recurso
  		foreach($arrObjRecursoDTO as $objRecursoDTO){
  		  if (isset($arrTemp[$objRecursoDTO->getNumIdRecurso()])){
  		    $objRecursoDTO->setArrObjItemMenuDTO($arrTemp[$objRecursoDTO->getNumIdRecurso()]);
  		  }else{
  		    $objRecursoDTO->setArrObjItemMenuDTO(array());
  		  }
  		}
    	
  		return $arrObjRecursoDTO;
    }catch(Exception $e){
      throw new InfraException('Erro listando recursos para montagem de perfil.',$e);
    }
  }  
    
  protected function montarControlado(PerfilDTO $objPerfilDTO){
    try{

      
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_montar',__METHOD__,$objPerfilDTO);

      //exclui os recursos que foram exibidos
      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

      $arrObjRelPerfilRecursoDTO = $objPerfilDTO->getArrObjRelPerfilRecursoDTO();

      //complementa array com o sistema e o perfil (ja tem o recurso)
      foreach($arrObjRelPerfilRecursoDTO as $objRelPerfilRecursoDTO){
        $objRelPerfilRecursoDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
        $objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
        
        //exclui se existe no banco
        if ($objRelPerfilRecursoRN->contar($objRelPerfilRecursoDTO)==1){
          $objRelPerfilRecursoRN->excluir(array($objRelPerfilRecursoDTO));
        }
      }
      
      
      //adiciona recursos selecionados
      foreach($arrObjRelPerfilRecursoDTO as $objRelPerfilRecursoDTO){
        if ($objRelPerfilRecursoDTO->getStrSinPerfil()=='S'){
          $objRelPerfilRecursoRN->cadastrar($objRelPerfilRecursoDTO);
        }
      }
      

			if ($objPerfilDTO->isSetArrObjRelPerfilItemMenuDTO()){

			  $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
			  
			  $arrObjRelPerfilItemMenuDTO = $objPerfilDTO->getArrObjRelPerfilItemMenuDTO();
			  
			  //complementa array com o sistema e o perfil (ja tem o recurso, menu e item de menu)
        foreach($arrObjRelPerfilItemMenuDTO as $objRelPerfilItemMenuDTO){
					$objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
					$objRelPerfilItemMenuDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
					
					//apaga só se existe no banco
					if ($objRelPerfilItemMenuRN->contar($objRelPerfilItemMenuDTO)==1){
			      $objRelPerfilItemMenuRN->excluir(array($objRelPerfilItemMenuDTO));		  
					}
        }

				//adiciona itens de menu selecionados
        foreach($arrObjRelPerfilItemMenuDTO as $objRelPerfilItemMenuDTO){
          if ($objRelPerfilItemMenuDTO->getStrSinPerfil()=='S'){
						$objRelPerfilItemMenuRN->cadastrar($objRelPerfilItemMenuDTO);
          }
        }
			}      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro montando perfil.',$e);
    }
  }
  
  protected function cadastrarControlado(PerfilDTO $objPerfilDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_cadastrar',__METHOD__,$objPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objPerfilDTO,$objInfraException);
      $this->validarStrNome($objPerfilDTO,$objInfraException);
      $this->validarStrDescricao($objPerfilDTO,$objInfraException);
      $this->validarStrSinAtivo($objPerfilDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      $ret = $objPerfilBD->cadastrar($objPerfilDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Perfil.',$e);
    }
  }

  protected function alterarControlado(PerfilDTO $objPerfilDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('perfil_alterar',__METHOD__,$objPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objPerfilDTO,$objInfraException);
      $this->validarStrNome($objPerfilDTO,$objInfraException);
      $this->validarStrDescricao($objPerfilDTO,$objInfraException);
      $this->validarStrSinAtivo($objPerfilDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      $objPerfilBD->alterar($objPerfilDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Perfil.',$e);
    }
  }

  protected function excluirControlado($arrObjPerfilDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_excluir',__METHOD__,$arrObjPerfilDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $arrReservados = $objInfraParametro->listarValores(array('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA',
                                                               'ID_PERFIL_SIP_ADMINISTRADOR_SIP',
                                                               'ID_PERFIL_SIP_COORDENADOR_PERFIL',
                                                               'ID_PERFIL_SIP_COORDENADOR_UNIDADE'));

      foreach($arrObjPerfilDTO as $objPerfilDTO){
        if (in_array($objPerfilDTO->getNumIdPerfil(),$arrReservados)){
          
          $objPerfilDTOBanco = new PerfilDTO();
          $objPerfilDTOBanco->retStrSiglaSistema();
          $objPerfilDTOBanco->retStrNome();
          $objPerfilDTOBanco->setBolExclusaoLogica(false);
          $objPerfilDTOBanco->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
          $objPerfilDTOBanco = $this->consultar($objPerfilDTOBanco);
          
          $objInfraException->lancarValidacao('Não é possível excluir o perfil reservado "'.$objPerfilDTOBanco->getStrNome().'" do sistema '.$objPerfilDTOBanco->getStrSiglaSistema().'.');
        }
      }
      
      $objInfraException->lancarValidacoes();
      
      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      
      foreach($arrObjPerfilDTO as $objPerfilDTO){
        
				//Exclui coordenadores de perfil associados
				$objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
				$objCoordenadorPerfilDTO->retTodos();
				$objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
				$objCoordenadorPerfilRN = new CoordenadorPerfilRN();
				$objCoordenadorPerfilRN->excluir($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));
				
				//Exclui permissoes associadas
				$objPermissaoDTO = new PermissaoDTO();
				$objPermissaoDTO->retTodos();
				$objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
				$objPermissaoRN = new PermissaoRN();
				$objPermissaoRN->excluir($objPermissaoRN->listar($objPermissaoDTO));
				
				//Exclui ligacao com recursos
				$objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
				$objRelPerfilRecursoDTO->retTodos();
				$objRelPerfilRecursoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
				$objRelPerfilRecursoRN = new RelPerfilRecursoRN();
				$objRelPerfilRecursoRN->excluir($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO));
				
				//Exclui ligacao com itens de menu
				$objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
				$objRelPerfilItemMenuDTO->retTodos();
				$objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
				$objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
				$objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
				
        $objPerfilBD->excluir($objPerfilDTO);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Perfil.',$e);
    }
  }

  protected function desativarControlado($arrObjPerfilDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_desativar',__METHOD__,$arrObjPerfilDTO);

      //Regras de Negocio
      //Regras de Negocio
      $objInfraException = new InfraException();
      
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $arrReservados = $objInfraParametro->listarValores(array('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA',
                                                               'ID_PERFIL_SIP_ADMINISTRADOR_SIP',
                                                               'ID_PERFIL_SIP_COORDENADOR_PERFIL',
                                                               'ID_PERFIL_SIP_COORDENADOR_UNIDADE'));
      
      foreach($arrObjPerfilDTO as $objPerfilDTO){
        if (in_array($objPerfilDTO->getNumIdPerfil(),$arrReservados)){
          
          $objPerfilDTOBanco = new PerfilDTO();
          $objPerfilDTOBanco->retStrSiglaSistema();
          $objPerfilDTOBanco->retStrNome();
          $objPerfilDTOBanco->setBolExclusaoLogica(false);
          $objPerfilDTOBanco->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
          $objPerfilDTOBanco = $this->consultar($objPerfilDTOBanco);
          
          $objInfraException->lancarValidacao('Não é possível desativar o perfil reservado "'.$objPerfilDTOBanco->getStrNome().'" do sistema '.$objPerfilDTOBanco->getStrSiglaSistema().'.');
        }
      }
      
      $objInfraException->lancarValidacoes();
            
      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPerfilDTO);$i++){
        $objPerfilBD->desativar($arrObjPerfilDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Perfil.',$e);
    }
  }

  protected function reativarControlado($arrObjPerfilDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('perfil_reativar',__METHOD__,$arrObjPerfilDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPerfilDTO);$i++){
        $objPerfilBD->reativar($arrObjPerfilDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Perfil.',$e);
    }
  }
  
  protected function consultarConectado(PerfilDTO $objPerfilDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('perfil_consultar',__METHOD__,$objPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      $ret = $objPerfilBD->consultar($objPerfilDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Perfil.',$e);
    }
  }

  protected function listarConectado(PerfilDTO $objPerfilDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('perfil_listar',__METHOD__,$objPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      $ret = $objPerfilBD->listar($objPerfilDTO);
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Perfis.',$e);
    }
  }

  protected function contarConectado(PerfilDTO $objPerfilDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('perfil_contar',__METHOD__,$objPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
		
			
      $objPerfilBD = new PerfilBD($this->getObjInfraIBanco());
      $ret = $objPerfilBD->contar($objPerfilDTO);
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Perfis.',$e);
    }
  }
  	
  protected function listarAdministradosConectado(PerfilDTO $objPerfilDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('perfil_listar',__METHOD__,$objPerfilDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

			//Retorna o ID para fechar com os sistemas Administrados
			$objPerfilDTO->retNumIdSistema();
			
      $arrObjPerfilDTO = $this->listar($objPerfilDTO);

			//Obtem sistemas onde o usuario é administrador
			$objAcessoDTO = new AcessoDTO();
			$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
			$objAcessoRN = new AcessoRN();
			$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
			
      //Filtra perfis onde 
			$ret = InfraArray::joinArrInfraDTO($arrObjPerfilDTO,'IdSistema',$arrObjAcessoDTO,'IdSistema');
			
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Perfis administrados.',$e);
    }
  }

  protected function listarCoordenadosConectado(PerfilDTO $parObjPerfilDTO) {
    try {
  
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('perfil_listar_coordenados',__METHOD__,$objPerfilDTO);
      /////////////////////////////////////////////////////////////////
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
  
      $ret = array();
      
      //Obtem sistemas onde o usuario é coordenador
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      
      $objSistemaRN = new SistemaRN();
      $arrIdSistema = InfraArray::converterArrInfraDTO($objSistemaRN->listarCoordenados($objSistemaDTO),'IdSistema');
      
      if (in_array($parObjPerfilDTO->getNumIdSistema(), $arrIdSistema)){

        $arrObjPerfilDTO = $this->listar($parObjPerfilDTO);
        
        //Obtem perfis onde o usuario é coordenador
        $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
        $objCoordenadorPerfilDTO->retNumIdPerfil();
        $objCoordenadorPerfilDTO->setNumIdUsuario(SessaoSip::getInstance()->getNumIdUsuario());
        $objCoordenadorPerfilDTO->setNumIdSistema($parObjPerfilDTO->getNumIdSistema());
        
        $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
        $arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO);
  
          
        foreach($arrObjPerfilDTO as $objPerfilDTO){
          $objPerfilDTO->setStrSinCoordenadoPeloUsuario('N');
          foreach($arrObjCoordenadorPerfilDTO as $objCoordenadorPerfilDTO){
            if ($objCoordenadorPerfilDTO->getNumIdPerfil()==$objPerfilDTO->getNumIdPerfil()){
              $objPerfilDTO->setStrSinCoordenadoPeloUsuario('S');
              break;
            }
          }
        }
        
        if ($parObjPerfilDTO->getStrSinCoordenadoPeloUsuario()=='S'){
          foreach($arrObjPerfilDTO as $objPerfilDTO){
            if ($objPerfilDTO->getStrSinCoordenadoPeloUsuario()=='S'){
              $ret[] = $objPerfilDTO;
            }
          }
        }else{
          $ret = $arrObjPerfilDTO;
        }

        //Obtem perfis onde o usuario é coordenador
        $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
        $objCoordenadorPerfilDTO->setDistinct(true);
        $objCoordenadorPerfilDTO->retNumIdPerfil();
        $objCoordenadorPerfilDTO->setNumIdSistema($parObjPerfilDTO->getNumIdSistema());
        
        $arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO);
        
        foreach($arrObjPerfilDTO as $objPerfilDTO){
          $objPerfilDTO->setStrSinCoordenadoPorAlgumUsuario('N');
          foreach($arrObjCoordenadorPerfilDTO as $objCoordenadorPerfilDTO){
            if ($objCoordenadorPerfilDTO->getNumIdPerfil()==$objPerfilDTO->getNumIdPerfil()){
              $objPerfilDTO->setStrSinCoordenadoPorAlgumUsuario('S');
              break;
            }
          }
        }
      }
      
      //Auditoria
  
      return $ret;
  
    }catch(Exception $e){
      throw new InfraException('Erro listando Perfis coordenados.',$e);
    }
  }
  
	/**
	Recupera os perfis acessados pelo usuario/sistema informado onde carregará:
	(1) todos os perfis do sistema se usuario administrador do sistema
	(2) todos os perfis coordenados pelo usuario no sistema
	(3) todos os perfis diponiveis as coordenadores de unidade se o usuario for coordenador de pelo menos uma unidade do sistema
	*/
	
  protected function obterAutorizadosConectado(SistemaDTO $objSistemaDTO) {
    try {

      //InfraDebug::getInstance()->setBolLigado(false);
      //InfraDebug::getInstance()->setBolDebugInfra(true);
      //InfraDebug::getInstance()->limpar();
      
      $objInfraException = new InfraException();
      
      $ret = array();
      
			if (!InfraString::isBolVazia($objSistemaDTO->getNumIdSistema())){
			
				//Busca todos os perfis do sistema
				$objPerfilDTO = new PerfilDTO();
				$objPerfilDTO->retNumIdPerfil();
				$objPerfilDTO->retStrNome();
				$objPerfilDTO->retStrSinCoordenado();
				$objPerfilDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());

        $objInfraParametro = new InfraParametro(BancoSip::getInstance());
        if ($objSistemaDTO->getNumIdSistema()==$objInfraParametro->getValor('ID_SISTEMA_SIP')) {
          $arrReservados = $objInfraParametro->listarValores(array('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA',
              'ID_PERFIL_SIP_ADMINISTRADOR_SIP',
              'ID_PERFIL_SIP_COORDENADOR_PERFIL',
              'ID_PERFIL_SIP_COORDENADOR_UNIDADE'));
          $objPerfilDTO->setNumIdPerfil($arrReservados, InfraDTO::$OPER_NOT_IN);
        }
				
				$objPerfilDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
				
				$arrObjPerfilDTO = $this->listar($objPerfilDTO);
				
				//Obtem sistemas autorizados (todos os sistemas exceto os acessados via permissoes pessoais) pelo usuario
				$objAcessoDTO = new AcessoDTO();
				$objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR | AcessoDTO::$COORDENADOR_PERFIL | AcessoDTO::$COORDENADOR_UNIDADE);
				$objAcessoRN = new AcessoRN();
				$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);
	
				/*
				$strSistemas = '';
				foreach($arrObjAcessoDTO as $objAcessoDTO){
					$strSistemas .= $objAcessoDTO->__toString()."\n";
				}
				$objInfraException->lancarValidacao($strSistemas);
				*/
				
				//verifica se o usuario é administrador
				//se afirmativo retorna todos os perfis do sistema
				foreach($arrObjAcessoDTO as $acesso){
					if ($acesso->getNumIdSistema()==$objSistemaDTO->getNumIdSistema() && 
					    $acesso->getNumTipo()==AcessoDTO::$ADMINISTRADOR){
						  return $arrObjPerfilDTO;
					 }
				}

        $numIdUnidade = null;
        $numIdOrgaoUnidade = null;
        if (!InfraString::isBolVazia($objSistemaDTO->getNumIdUnidade())){

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retNumIdUnidade();
          $objUnidadeDTO->retNumIdOrgao();
          $objUnidadeDTO->setNumIdUnidade($objSistemaDTO->getNumIdUnidade());

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

          if ($objUnidadeDTO!=null) {
            $numIdUnidade = $objUnidadeDTO->getNumIdUnidade();
            $numIdOrgaoUnidade = $objUnidadeDTO->getNumIdOrgao();
          }
        }


				foreach($arrObjPerfilDTO as $objPerfilDTO){
  				foreach($arrObjAcessoDTO as $acesso){
  				  if ($acesso->getNumIdSistema()==$objSistemaDTO->getNumIdSistema() && $acesso->getNumIdPerfil()==$objPerfilDTO->getNumIdPerfil()){
  				    if ($acesso->getNumTipo()==AcessoDTO::$COORDENADOR_PERFIL ||
                  ($acesso->getNumTipo()==AcessoDTO::$COORDENADOR_UNIDADE &&
                      (
                          ($acesso->getStrSinGlobalUnidade()=='S' && $acesso->getNumIdOrgaoUnidade()==$numIdOrgaoUnidade) ||
                          ($acesso->getStrSinGlobalUnidade()=='N' && $acesso->getNumIdUnidade()==$numIdUnidade)
                      ))){
   					    $ret[] = $objPerfilDTO;
    					}
    				}
  				}
				} 
			}			
			
      //Auditoria
      return $ret;
		
    }catch(Exception $e){
      throw new InfraException('Erro listando Perfis autorizados.',$e);
    }
  }
	
  private function validarNumIdSistema(PerfilDTO $objPerfilDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPerfilDTO->getNumIdSistema())){
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarStrNome(PerfilDTO $objPerfilDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPerfilDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }

    $objPerfilDTO->setStrNome(trim($objPerfilDTO->getStrNome()));

    if (strlen($objPerfilDTO->getStrNome())>100){
      $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
    }

    $dto = new PerfilDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->setNumIdSistema($objPerfilDTO->getNumIdSistema());
    if ($objPerfilDTO->isSetNumIdPerfil() && $objPerfilDTO->getNumIdPerfil()!=null){
      $dto->setNumIdPerfil($objPerfilDTO->getNumIdPerfil(),InfraDTO::$OPER_DIFERENTE);
    }
    $dto->setStrNome($objPerfilDTO->getStrNome());
    $dto = $this->consultar($dto);

    if ($dto!=null){
      if ($dto->getStrSinAtivo()=='S'){
        $objInfraException->adicionarValidacao('Já existe um perfil com este nome.');
      }else{
        $objInfraException->adicionarValidacao('Existe um perfil inativo com este nome.');
      }
    }
  }
  
  private function validarStrDescricao(PerfilDTO $objPerfilDTO, InfraException $objInfraException){
  }
	
  private function validarStrSinAtivo(PerfilDTO $objPerfilDTO, InfraException $objInfraException){
    if ($objPerfilDTO->getStrSinAtivo()===null || ($objPerfilDTO->getStrSinAtivo()!=='S' && $objPerfilDTO->getStrSinAtivo()!=='N')){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

	protected function carregarPerfisConectado(PermissaoDTO $parObjPermissaoDTO){
    try{    
  		$objInfraException = new InfraException();
  		
  		if (InfraString::isBolVazia($parObjPermissaoDTO->getNumIdSistema())){
  			$objInfraException->adicionarValidacao('Sistema não informado.');
  		}
  		
  		$objInfraException->lancarValidacoes();

  		$objPerfilDTO = new PerfilDTO();
  		$objPerfilDTO->setBolExclusaoLogica(false);
  		$objPerfilDTO->retNumIdPerfil();
  		$objPerfilDTO->retStrNome();
  		$objPerfilDTO->retStrDescricao();
  		$objPerfilDTO->retStrSinAtivo();
  		
  		if (InfraString::isBolVazia($parObjPermissaoDTO->getNumIdUsuario()) && InfraString::isBolVazia($parObjPermissaoDTO->getNumIdUnidade())){
  		  
  		  $objPerfilDTO->setNumIdSistema($parObjPermissaoDTO->getNumIdSistema());
  		  
  		}else{
  		  
    		$objPermissaoDTO = new PermissaoDTO();
    		$objPermissaoDTO->setDistinct(true);
    		$objPermissaoDTO->retNumIdPerfil();
    		$objPermissaoDTO->setNumIdSistema($parObjPermissaoDTO->getNumIdSistema());
    		
    		if (!InfraString::isBolVazia($parObjPermissaoDTO->getNumIdUnidade())){
    		  $objPermissaoDTO->setNumIdUnidade($parObjPermissaoDTO->getNumIdUnidade());
    		}
    		
    		if (!InfraString::isBolVazia($parObjPermissaoDTO->getNumIdUsuario())){
    		  $objPermissaoDTO->setNumIdUsuario($parObjPermissaoDTO->getNumIdUsuario());
    		}
    		
    		$objPermissaoDTO->setDtaDataInicio(InfraData::getStrDataAtual(),InfraDTO::$OPER_MENOR_IGUAL);
    		$objPermissaoDTO->adicionarCriterio(array('DataFim','DataFim'),
    		                                    array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_IGUAL),
    		                                    array(InfraData::getStrDataAtual(),null),
    		                                    InfraDTO::$OPER_LOGICO_OR);
    		
  
        $objPermissaoRN = new PermissaoRN();
        $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

        if (count($arrObjPermissaoDTO)){
          $objPerfilDTO->setNumIdPerfil(InfraArray::converterArrInfraDTO($arrObjPermissaoDTO,'IdPerfil'), InfraDTO::$OPER_IN);
        }else{
          $objPerfilDTO->setNumIdPerfil(null);
        }
  		  
  		}
  		
  		$arrObjPerfilDTO = $this->listar($objPerfilDTO); 
  		

  		$ret = array();
  		foreach($arrObjPerfilDTO as $objPerfilDTO){

  			$ret[] = array(InfraSip::$WS_PERFIL_ID => $objPerfilDTO->getNumIdPerfil(),
										   InfraSip::$WS_PERFIL_NOME => $objPerfilDTO->getStrNome(),
										   InfraSip::$WS_PERFIL_DESCRICAO => $objPerfilDTO->getStrDescricao(),
										   InfraSip::$WS_PERFIL_SIN_ATIVO => $objPerfilDTO->getStrSinAtivo());
       }
  		
       return $ret;
		
    }catch(Exception $e){
      throw new InfraException('Erro carregando perfis.',$e);
    }
	}
}
?>