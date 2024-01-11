<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

ini_set('memory_limit','1024M');

class SipWS extends SipUtilWS {

  public function getObjInfraLog(){
    return LogSip::getInstance();
  }

  public function validarLogin($strChaveAcesso, $IdLogin,$IdSistema,$IdUsuario,$HashAgente){
    try{

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID LOGIN:'.$IdLogin);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
      InfraDebug::getInstance()->gravar('HASH AGENTE:'.$HashAgente);
      */

      $this->validarAcessoServico($strChaveAcesso, null, $IdSistema);

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setStrIdLogin($IdLogin);
      $objLoginDTO->setNumIdSistema($IdSistema);
      $objLoginDTO->setNumIdUsuario($IdUsuario);
      $objLoginDTO->setStrHashAgente($HashAgente);

      $objLoginRN = new LoginRN();
      $objInfraSessaoDTO = $objLoginRN->logar($objLoginDTO);

      return $objInfraSessaoDTO;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function loginUnificado($strChaveAcesso, $SiglaOrgaoSistema, $SiglaSistema, $Link, $HashAgente){
    try{

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('SIGLA ORGAO SISTEMA:'.$SiglaOrgaoSistema);
      InfraDebug::getInstance()->gravar('SIGLA SISTEMA:'.$SiglaSistema);
      InfraDebug::getInstance()->gravar('LINK:'.$Link);
      InfraDebug::getInstance()->gravar('HASH AGENTE:'.$HashAgente);
      */

      $this->validarAcessoServico($strChaveAcesso);

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setStrSiglaOrgaoSistema($SiglaOrgaoSistema);
      $objLoginDTO->setStrSiglaSistema($SiglaSistema);
      $objLoginDTO->setStrLink($Link);
      $objLoginDTO->setStrHashAgente($HashAgente);

      $objLoginRN = new LoginRN();
      $objInfraSessaoDTO = $objLoginRN->loginUnificado($objLoginDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $objInfraSessaoDTO;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function removerLogin($strChaveAcesso, $SiglaOrgaoSistema, $SiglaSistema, $Link, $IdUsuario){
    try{

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('SIGLA ORGAO SISTEMA:'.$SiglaOrgaoSistema);
      InfraDebug::getInstance()->gravar('SIGLA SISTEMA:'.$SiglaSistema);
      InfraDebug::getInstance()->gravar('LINK:'.$Link);
      InfraDebug::getInstance()->gravar('HASH AGENTE:'.$HashAgente);
      */

      $this->validarAcessoServico($strChaveAcesso);

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setStrSiglaOrgaoSistema($SiglaOrgaoSistema);
      $objLoginDTO->setStrSiglaSistema($SiglaSistema);
      $objLoginDTO->setStrLink($Link);
      $objLoginDTO->setNumIdUsuario($IdUsuario);

      $objLoginRN = new LoginRN();
      $objInfraSessaoDTO = $objLoginRN->removerLogin($objLoginDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $objInfraSessaoDTO;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function carregarOrgaos($strChaveAcesso, $IdSistema){
    try{

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_ORGAOS, $IdSistema);

      //InfraDebug::getInstance()->setBolLigado(false);
      //InfraDebug::getInstance()->setBolDebugInfra(true);
      //InfraDebug::getInstance()->limpar();


      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setNumIdSistema($IdSistema);

      $objSistemaRN = new SistemaRN();
      $ret = $objSistemaRN->listarOrgaos($objSistemaDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function carregarUnidades($strChaveAcesso, $IdSistema, $IdUsuario, $IdUnidade){
    try{

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_UNIDADES, $IdSistema);

      //InfraDebug::getInstance()->setBolLigado(false);
      //InfraDebug::getInstance()->setBolDebugInfra(true);
      //InfraDebug::getInstance()->limpar();

      if (InfraString::isBolVazia($IdUsuario)){

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->setNumIdSistema($IdSistema);
        $objSistemaDTO->setNumIdUnidade($IdUnidade);

        $objSistemaRN = new SistemaRN();
        $ret = $objSistemaRN->listarUnidades($objSistemaDTO);

      }else{

        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->setNumIdSistema($IdSistema);
        $objPermissaoDTO->setNumIdUsuario($IdUsuario);

        $objPermissaoRN = new PermissaoRN();
        $ret = $objPermissaoRN->listarUnidades($objPermissaoDTO);

      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function carregarUsuarios($strChaveAcesso, $IdSistema, $IdUnidade, $Recurso, $Perfil){
    try{

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_USUARIOS, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(true);
      InfraDebug::getInstance()->limpar();
      */

      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdSistema($IdSistema);

      if (!InfraString::isBolVazia($IdUnidade)){
        $objPermissaoDTO->setNumIdUnidade($IdUnidade);
      }

      if (!InfraString::isBolVazia($Recurso)){
        $objPermissaoDTO->setStrNomeRecurso($Recurso);
      }

      if (!InfraString::isBolVazia($Perfil)){
        $objPermissaoDTO->setStrNomePerfil($Perfil);
      }

      $objPermissaoRN = new PermissaoRN();
      $ret = $objPermissaoRN->carregarUsuarios($objPermissaoDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;


    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function carregarUsuario($strChaveAcesso, $IdSistema, $TipoServidorAutenticacao, $IdOrgaoUsuario, $SiglaUsuario){
    try{

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_USUARIOS, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('TIPO SERVIDOR AUTENTICACAO:'.$TipoServidorAutenticacao);
      InfraDebug::getInstance()->gravar('ID ORGAO USUARIO:'.$IdOrgaoUsuario);
      InfraDebug::getInstance()->gravar('SIGLA USUARIO:'.$SiglaUsuario);
      */

      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdSistema($IdSistema);
      $objPermissaoDTO->setNumIdOrgaoUsuario($IdOrgaoUsuario);
      $objPermissaoDTO->setStrSiglaUsuario($SiglaUsuario);
      $objPermissaoDTO->setStrTipoServidorAutenticacao($TipoServidorAutenticacao);

      $objPermissaoRN = new PermissaoRN();
      $ret = $objPermissaoRN->carregarUsuario($objPermissaoDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function replicarUsuario($strChaveAcesso, $Usuarios){
    try {

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_REPLICACAO_USUARIOS);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('USUARIOS:'.count($Usuarios));
      */

      $objUsuarioRN = new UsuarioRN();

      $objInfraException = new InfraException();

      if (!is_array($Usuarios)){
        $Usuarios = (array)$Usuarios;
      }

      foreach($Usuarios as $Usuario) {

        if (!is_array($Usuario)){
          $Usuario = (array)$Usuario;
        }

        $StaOperacao = $Usuario['StaOperacao'];
        $IdOrgao = $Usuario['IdOrgao'];
        $IdOrigem = $Usuario['IdOrigem'];
        $Sigla = $Usuario['Sigla'];
        $Nome = $Usuario['Nome'];

        if (isset($Usuario['NomeSocial'])) {
          $NomeSocial = $Usuario['NomeSocial'];
        }else{
          $NomeSocial = null;
        }

        if (isset($Usuario['Cpf'])) {
          $Cpf = $Usuario['Cpf'];
        }else{
          $Cpf = null;
        }

        if (isset($Usuario['Email'])) {
          $Email = $Usuario['Email'];
        }else{
          $Email = null;
        }

        /*
        InfraDebug::getInstance()->gravar(' ');
        InfraDebug::getInstance()->gravar('OPERACAO:'.$StaOperacao);
        InfraDebug::getInstance()->gravar('ID ORGAO:'.$IdOrgao);
        InfraDebug::getInstance()->gravar('ID ORIGEM:'.$IdOrigem);
        InfraDebug::getInstance()->gravar('SIGLA:'.$Sigla);
        InfraDebug::getInstance()->gravar('NOME:'.$Nome);
        InfraDebug::getInstance()->gravar('NOME SOCIAL: '.(isset($Usuario['NomeSocial'])?$NomeSocial:'NAO INFORMADO'));
        InfraDebug::getInstance()->gravar('CPF: '.(isset($Usuario['Cpf'])?$Cpf:'NAO INFORMADO'));
        InfraDebug::getInstance()->gravar('EMAIL: '.(isset($Usuario['Email'])?$Email:'NAO INFORMADO'));
        */

        try{

          if (InfraString::isBolVazia($IdOrigem)){
            throw new InfraException('Identificador do sistema de origem não informado.');
          }

          $objUsuarioDTOBanco = new UsuarioDTO();
          $objUsuarioDTOBanco->setBolExclusaoLogica(false);
          $objUsuarioDTOBanco->retNumIdUsuario();
          //$objUsuarioDTOBanco->retNumIdOrgao();
          //$objUsuarioDTOBanco->retStrIdOrigem();
          //$objUsuarioDTOBanco->retStrSigla();
          //$objUsuarioDTOBanco->retStrNome();
          //$objUsuarioDTOBanco->retStrNomeSocial();
          $objUsuarioDTOBanco->setStrIdOrigem($IdOrigem);
          $objUsuarioDTOBanco = $objUsuarioRN->consultar($objUsuarioDTOBanco);

          if ($StaOperacao == 'A'){

            $objReplicarUsuarioRhDTO = new ReplicarUsuarioRhDTO();
            $objReplicarUsuarioRhDTO->setStrStaOperacao($StaOperacao);
            $objReplicarUsuarioRhDTO->setNumIdOrgao($IdOrgao);
            $objReplicarUsuarioRhDTO->setStrIdOrigem($IdOrigem);
            $objReplicarUsuarioRhDTO->setStrSigla($Sigla);
            $objReplicarUsuarioRhDTO->setStrNome($Nome);

            if (isset($Usuario['NomeSocial'])) {
              $objReplicarUsuarioRhDTO->setStrNomeSocial($NomeSocial);
            }

            if (isset($Usuario['Cpf'])) {
              $objReplicarUsuarioRhDTO->setDblCpf($Cpf);
            }

            if (isset($Usuario['Email'])) {
              $objReplicarUsuarioRhDTO->setStrEmail($Email);
            }

            $objUsuarioRN->replicar($objReplicarUsuarioRhDTO);

          }else if ($StaOperacao=='E'){
            if ($objUsuarioDTOBanco!=null){
              try{
                $objUsuarioRN->excluir(array($objUsuarioDTOBanco));
              }catch(Exception $e){
                //erro de integridade então desativa
                $objUsuarioRN->desativar(array($objUsuarioDTOBanco));
              }
            }

          }else if ($StaOperacao=='D'){
            if ($objUsuarioDTOBanco!=null){
              $objUsuarioRN->desativar(array($objUsuarioDTOBanco));
            }
          }else if ($StaOperacao=='R'){
            if ($objUsuarioDTOBanco!=null){
              $objUsuarioRN->reativar(array($objUsuarioDTOBanco));
            }
          }else{
            throw new InfraException('Operação '.$StaOperacao.' inválida.');
          }
        }catch(Exception $e){
          $objInfraException->adicionarValidacao("\n * ".$Sigla.' ('.$IdOrigem.'): '.$e->__toString()."\n");

          if (!($e instanceof InfraException && $e->contemValidacoes())){
            try {
              LogSip::getInstance()->gravar(InfraException::inspecionar($e));
            }catch(Exception $e2){}
          }

        }
      }

      if ($objInfraException->contemValidacoes()){
        $objInfraException->lancarValidacoes();
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }

    return false;
  }

  public function replicarPermissao($strChaveAcesso, $Permissoes){
    try {

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_REPLICACAO_PERMISSOES);

      /*
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('PERMISSOES:'.count($Permissoes));
      */

      $objUsuarioRN = new UsuarioRN();
      $objUnidadeRN = new UnidadeRN();
      $objPermissaoRN = new PermissaoRN();

      $objInfraException = new InfraException();

      if (!is_array($Permissoes)){
        $Permissoes = (array)$Permissoes;
      }

      foreach($Permissoes as $Permissao) {

        if (!is_array($Permissao)){
          $Permissao = (array)$Permissao;
        }

        $StaOperacao = $Permissao['StaOperacao'];
        $IdSistema = $Permissao['IdSistema'];
        $IdUsuario = $Permissao['IdUsuario'];
        $IdOrigemUsuario = $Permissao['IdOrigemUsuario'];
        $IdOrgaoUsuario = $Permissao['IdOrgaoUsuario'];
        $IdUnidade = $Permissao['IdUnidade'];
        $IdOrigemUnidade = $Permissao['IdOrigemUnidade'];
        $IdOrgaoUnidade = $Permissao['IdOrgaoUnidade'];
        $IdPerfil = $Permissao['IdPerfil'];
        $DataInicial = $Permissao['DataInicial'];
        $DataFinal = $Permissao['DataFinal'];
        $SinSubunidades = $Permissao['SinSubunidades'];

        /*
        InfraDebug::getInstance()->gravar(' ');
        InfraDebug::getInstance()->gravar('OPERACAO:'.$StaOperacao);
        InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
        InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
        InfraDebug::getInstance()->gravar('ID ORIGEM USUARIO:'.$IdOrigemUsuario);
        InfraDebug::getInstance()->gravar('ID ORGAO USUARIO:'.$IdOrgaoUsuario);
        InfraDebug::getInstance()->gravar('ID UNIDADE:'.$IdUnidade);
        InfraDebug::getInstance()->gravar('ID ORIGEM UNIDADE:'.$IdOrigemUnidade);
        InfraDebug::getInstance()->gravar('ID ORGAO UNIDADE:'.$IdOrgaoUnidade);
        InfraDebug::getInstance()->gravar('ID PERFIL:'.$IdPerfil);
        InfraDebug::getInstance()->gravar('DATA INICIAL:'.$DataInicial);
        InfraDebug::getInstance()->gravar('DATA FINAL:'.$DataFinal);
        InfraDebug::getInstance()->gravar('SIN SUBUNIDADES:'.$SinSubunidades);
        */

        if (InfraString::isBolVazia($IdOrgaoUsuario)) {
          throw new InfraException('Órgão do usuário não informado.');
        }

        if (InfraString::isBolVazia($IdOrgaoUnidade)) {
          throw new InfraException('Órgão da unidade não informado.');
        }

        if (InfraString::isBolVazia($IdUsuario) && InfraString::isBolVazia($IdOrigemUsuario)) {
          throw new InfraException('Nenhum identificador de usuário informado.');
        }

        if (InfraString::isBolVazia($IdUnidade) && InfraString::isBolVazia($IdOrigemUnidade)) {
          throw new InfraException('Nenhum identificador de unidade informado.');
        }

        try {

          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->retNumIdUsuario();
          $objUsuarioDTO->retStrSinAtivo();
          $objUsuarioDTO->setNumIdOrgao($IdOrgaoUsuario);

          if (!InfraString::isBolVazia($IdUsuario)){
            $objUsuarioDTO->setNumIdUsuario($IdUsuario);
          }

          if (!InfraString::isBolVazia($IdOrigemUsuario)){
            $objUsuarioDTO->setStrIdOrigem($IdOrigemUsuario);
          }

          $arrObjUsuarioDTO = $objUsuarioRN->listar($objUsuarioDTO);

          if (count($arrObjUsuarioDTO)==0) {
            throw new InfraException('Nenhum usuário encontrado [IdUsuario='.$IdUsuario.', IdOrigemUsuario=' . $IdOrigemUsuario . ', IdOrgaoUsuario=' . $IdOrgaoUsuario . '].');
          }

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retNumIdUnidade();
          $objUnidadeDTO->retStrSinAtivo();
          $objUnidadeDTO->setNumIdOrgao($IdOrgaoUnidade);

          if (!InfraString::isBolVazia($IdUnidade)){
            $objUnidadeDTO->setNumIdUnidade($IdUnidade);
          }

          if (!InfraString::isBolVazia($IdOrigemUnidade)){
            $objUnidadeDTO->setStrIdOrigem($IdOrigemUnidade);
          }

          $arrObjUnidadeDTO = $objUnidadeRN->listar($objUnidadeDTO);

          if (count($arrObjUnidadeDTO)==0) {
            throw new InfraException('Nenhuma unidade encontrada [IdUnidade='.$IdUnidade.', IdOrigemUnidade=' . $IdOrigemUnidade . ', IdOrgaoUnidade=' . $IdOrgaoUnidade . '].');
          }

          foreach($arrObjUsuarioDTO as $objUsuarioDTO) {

            foreach ($arrObjUnidadeDTO as $objUnidadeDTO) {

              $objPermissaoDTO = new PermissaoDTO();
              $objPermissaoDTO->setNumIdSistema($IdSistema);
              $objPermissaoDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
              $objPermissaoDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
              $objPermissaoDTO->setNumIdPerfil($IdPerfil);
              $bolExiste = $objPermissaoRN->contar($objPermissaoDTO);

              if ($StaOperacao == 'A') {

                $objPermissaoDTO->setDtaDataInicio($DataInicial);
                $objPermissaoDTO->setDtaDataFim($DataFinal);
                $objPermissaoDTO->setStrSinSubunidades($SinSubunidades);
                $objPermissaoDTO->setNumIdTipoPermissao(PermissaoRN::$TIPO_NAO_DELEGAVEL);

                if (!$bolExiste) {
                  $objPermissaoRN->cadastrar($objPermissaoDTO);
                } else {
                  $objPermissaoRN->alterar($objPermissaoDTO);
                }

              } else if ($StaOperacao == 'E') {

                if ($bolExiste) {
                  $objPermissaoRN->excluir(array($objPermissaoDTO));
                }

              } else {
                throw new InfraException('Operação ' . $StaOperacao . ' inválida.');
              }
            }
          }

        }catch(Exception $e){

          $objInfraException->adicionarValidacao("\n * ".$e->__toString());

          if (!($e instanceof InfraException && $e->contemValidacoes())){
            try {
              LogSip::getInstance()->gravar(InfraException::inspecionar($e));
            }catch(Exception $e2){}
          }
        }
      }

      $objInfraException->lancarValidacoes();

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }

    return false;
  }

  public function listarPermissao($strChaveAcesso, $IdSistema, $IdOrgaoUsuario, $IdUsuario, $IdOrigemUsuario , $IdOrgaoUnidade, $IdUnidade, $IdOrigemUnidade, $IdPerfil){
    try {

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_PERMISSOES, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(true);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('ID ORGAO USUARIO:'.$IdOrgaoUsuario);
      InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
      InfraDebug::getInstance()->gravar('ID ORIGEM USUARIO:'.$IdOrigemUsuario);
      InfraDebug::getInstance()->gravar('ID ORGAO UNIDADE:'.$IdOrgaoUnidade);
      InfraDebug::getInstance()->gravar('ID UNIDADE:'.$IdUnidade);
      InfraDebug::getInstance()->gravar('ID ORIGEM UNIDADE:'.$IdOrigemUnidade);
      InfraDebug::getInstance()->gravar('ID PERFIL:'.$IdPerfil);
      */

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setBolExclusaoLogica(false);
      $objSistemaDTO->retStrSigla();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->retStrSinAtivo();
      $objSistemaDTO->setNumIdSistema($IdSistema);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      if ($objSistemaDTO == null){
        throw new InfraException('Sistema ['.$IdSistema.'] não encontrado.');
      }

      if ($objSistemaDTO->getStrSinAtivo() == 'N'){
        throw new InfraException('Sistema '.$objSistemaDTO->getStrSigla().' desativado.');
      }

      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->retNumIdSistema();
      $objPermissaoDTO->retNumIdUsuario();
      $objPermissaoDTO->retStrIdOrigemUsuario();
      $objPermissaoDTO->retNumIdOrgaoUsuario();
      $objPermissaoDTO->retNumIdUnidade();
      $objPermissaoDTO->retStrIdOrigemUnidade();
      $objPermissaoDTO->retNumIdOrgaoUnidade();
      $objPermissaoDTO->retNumIdPerfil();
      $objPermissaoDTO->retDtaDataInicio();
      $objPermissaoDTO->retDtaDataFim();
      $objPermissaoDTO->retStrSinSubunidades();

      $objPermissaoDTO->setNumIdSistema($IdSistema);

      if (!InfraString::isBolVazia($IdUsuario)){
        $objPermissaoDTO->setNumIdUsuario($IdUsuario);
      }

      if (!InfraString::isBolVazia($IdOrigemUsuario)){
        $objPermissaoDTO->setStrIdOrigemUsuario($IdOrigemUsuario);
      }

      if (!InfraString::isBolVazia($IdOrgaoUsuario)){
        $objPermissaoDTO->setNumIdOrgaoUsuario($IdOrgaoUsuario);
      }

      if (!InfraString::isBolVazia($IdUnidade)){
        $objPermissaoDTO->setNumIdUnidade($IdUnidade);
      }

      if (!InfraString::isBolVazia($IdOrigemUnidade)){
        $objPermissaoDTO->setStrIdOrigemUnidade($IdOrigemUnidade);
      }

      if (!InfraString::isBolVazia($IdOrgaoUnidade)){
        $objPermissaoDTO->setNumIdOrgaoUnidade($IdOrgaoUnidade);
      }

      if (!InfraString::isBolVazia($IdPerfil)){
        $objPermissaoDTO->setNumIdPerfil($IdPerfil);
      }

      $objPermissaoDTO->setOrdNumIdOrgaoUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objPermissaoDTO->setOrdNumIdUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objPermissaoDTO->setOrdStrIdOrigemUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objPermissaoRN = new PermissaoRN();
      $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

      $ret = array();
      foreach($arrObjPermissaoDTO as $objPermissaoDTO){
        $ret[] = (object)array(
            'IdSistema' => $objPermissaoDTO->getNumIdSistema(),
            'IdOrgaoUsuario' => $objPermissaoDTO->getNumIdOrgaoUsuario(),
            'IdUsuario' => $objPermissaoDTO->getNumIdUsuario(),
            'IdOrigemUsuario' => $objPermissaoDTO->getStrIdOrigemUsuario(),
            'IdOrgaoUnidade' => $objPermissaoDTO->getNumIdOrgaoUnidade(),
            'IdUnidade' => $objPermissaoDTO->getNumIdUnidade(),
            'IdOrigemUnidade' => $objPermissaoDTO->getStrIdOrigemUnidade(),
            'IdPerfil' => $objPermissaoDTO->getNumIdPerfil(),
            'DataInicial' => $objPermissaoDTO->getDtaDataInicio(),
            'DataFinal' => $objPermissaoDTO->getDtaDataFim(),
            'SinSubunidades' => $objPermissaoDTO->getStrSinSubunidades());
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }

    return null;
  }

  public function carregarPerfis($strChaveAcesso,$IdSistema,$IdUsuario,$IdUnidade){
    try {

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_PERFIS, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
      InfraDebug::getInstance()->gravar('ID UNIDADE:'.$IdUnidade);
      */

      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdSistema($IdSistema);
      $objPermissaoDTO->setNumIdUsuario($IdUsuario);
      $objPermissaoDTO->setNumIdUnidade($IdUnidade);

      $objPerfilRN = new PerfilRN();
      $ret = $objPerfilRN->carregarPerfis($objPermissaoDTO);


      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function carregarRecursos($strChaveAcesso, $IdSistema, $Perfis, $Recursos){
    try {

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_RECURSOS, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('PERFIL:'.print_r($Perfis,true));
      InfraDebug::getInstance()->gravar('RECURSO:'.print_r($Recursos,true));
      */

      $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
      $objRelPerfilRecursoDTO->setDistinct(true);
      $objRelPerfilRecursoDTO->retStrNomeRecurso();
      $objRelPerfilRecursoDTO->setStrSinAtivoPerfil('S');
      $objRelPerfilRecursoDTO->setStrSinAtivoRecurso('S');

      $objRelPerfilRecursoDTO->setNumIdSistema($IdSistema);

      if ($Perfis!=null){
        $objRelPerfilRecursoDTO->setNumIdPerfil($Perfis, InfraDTO::$OPER_IN);
      }

      if ($Recursos!=null){
        $objRelPerfilRecursoDTO->setStrNomeRecurso($Recursos, InfraDTO::$OPER_IN);
      }

      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);

      $ret = InfraArray::converterArrInfraDTO($arrObjRelPerfilRecursoDTO,'NomeRecurso');

      return $ret;

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function autenticar($strChaveAcesso, $IdOrgao,$IdContexto,$Sigla,$Senha){
    try {

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_AUTENTICACAO_USUARIO);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar('ÓRGÃO:'.$IdOrgao);
      InfraDebug::getInstance()->gravar('CONTEXTO:'.$IdContexto);
      InfraDebug::getInstance()->gravar('SIGLA:'.$Sigla);
      InfraDebug::getInstance()->gravar('SENHA:'.$Senha);
      */

      $Senha = base64_decode($Senha);
      for($i = 0; $i < strlen($Senha); $i++){
        $Senha[$i] = ~$Senha[$i];
      }

      $objLoginRN = new LoginRN();

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setNumIdOrgaoUsuario($IdOrgao);
      $objLoginDTO->setStrSiglaUsuario($Sigla);
      $objLoginDTO->setStrSenhaUsuario($Senha);

      $objLoginRN->autenticar($objLoginDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;

    }catch(Exception $e){
      $this->processarExcecao($e,true);
    }
    return false;
  }

  public function listarAcessos($strChaveAcesso, $IdSistema, $IdUsuario){
    try {

      $this->validarAcessoServico($strChaveAcesso);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
      */

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setNumIdSistema($IdSistema);
      $objLoginDTO->setNumIdUsuario($IdUsuario);

      $objLoginRN = new LoginRN();
      $ret = $objLoginRN->listarAcessos($objLoginDTO);


      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }
  }

  public function pesquisarUsuario($strChaveAcesso, $TipoServidorAutenticacao, $IdOrgao, $Sigla){
    try {

      $this->validarAcessoServico($strChaveAcesso,SistemaRN::$TS_PESQUISA_USUARIOS);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('TIPO SERVIDOR AUTENTICACAO:'.$TipoServidorAutenticacao);
      InfraDebug::getInstance()->gravar('ÓRGÃO:'.$IdOrgao);
      InfraDebug::getInstance()->gravar('SIGLA:'.$Sigla);
      */

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setNumIdOrgao($IdOrgao);
      $objUsuarioDTO->setStrSigla($Sigla);
      $objUsuarioDTO->setStrTipoServidorAutenticacao($TipoServidorAutenticacao);

      $objSipRN = new SipRN();
      $ret = $objSipRN->pesquisarUsuario($objUsuarioDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;

    }catch(Exception $e){
      $this->processarExcecao($e);
    }

    return false;
  }

  /**
   * Método de autenticação com retorno completo
   * @param $IdOrgao
   * @param $IdContexto
   * @param $Sigla
   * @param $Senha
   * @param $SiglaSistema
   * @param $SiglaOrgaoSistema
   * @return stdClass
   * @throws InfraException
   * @throws SoapFault
   */
  public function autenticarCompleto($strChaveAcesso,$IdOrgao,$IdContexto,$Sigla,$Senha, $SiglaSistema, $SiglaOrgaoSistema){
    try {

      $this->validarAcessoServico($strChaveAcesso,SistemaRN::$TS_AUTENTICACAO_USUARIO);

      $Senha = base64_decode($Senha);
      for($i = 0; $i < strlen($Senha); $i++){
        $Senha[$i] = ~$Senha[$i];
      }

      $objLoginRN = new LoginRN();
      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setNumIdOrgaoUsuario($IdOrgao);
      $objLoginDTO->setStrSiglaUsuario($Sigla);
      $objLoginDTO->setStrSenhaUsuario($Senha);
      $objLoginDTO->setStrSiglaOrgaoSistema($SiglaOrgaoSistema);
      $objLoginDTO->setStrSiglaSistema($SiglaSistema);
      $objLoginRN->autenticar($objLoginDTO);
      /**
       * Cadastrando Login igual o processo padrão de autenticação do SIP
       */
      $objLoginDTO = $objLoginRN->cadastrar($objLoginDTO);

      /**
       * Retornando mesmos parametros que o SIP passa para autenticar um usuário via POST.
       * Isto é interessante pois são alguns dos dados necessários para usar o metodo do SIP validarLogin,
       * e também é um ganho pois se outro sistema quiser abrir o SEI em uma página especifica com o usuário autenticado
       * basta passar estes parametros pela URL.
       */
      $objResult = new stdClass();
      $objResult->IdSistema = $objLoginDTO->getNumIdSistema();
      $objResult->IdContexto = null;
      $objResult->IdUsuario = $objLoginDTO->getNumIdUsuario();
      $objResult->IdLogin = $objLoginDTO->getStrIdLogin();
      $objResult->HashAgente = $objLoginDTO->getStrHashAgente();

      return $objResult;

    }catch(Exception $e){
      $this->processarExcecao($e,true);
    }
  }

  public function validarReplicacao($strChaveAcesso, $IdReplicacao){
    try {

      $this->validarAcessoServico($strChaveAcesso);

      /*
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID REPLICACAO:'.count($IdReplicacao));
      */

      $ret = CacheSip::getInstance()->getAtributo('R_'.$IdReplicacao);

      if ($ret == true){
        CacheSip::getInstance()->removerAtributo('R_'.$IdReplicacao);
        return true;
      }

      throw new InfraException('Identificador de replicação inválido.');

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

    }catch(Exception $e){
      $this->processarExcecao($e);
    }

    return false;
  }
}

$servidorSoap = new SoapServer("sip.wsdl",array('encoding'=>'ISO-8859-1'));
$servidorSoap->setClass("SipWS");

//Só processa se acessado via POST
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $servidorSoap->handle();
}
