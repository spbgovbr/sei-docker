<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

ini_set('memory_limit', '1024M');

class SipWS extends SipUtilWS {

  public function getObjInfraLog() {
    return LogSip::getInstance();
  }

  public function validarLogin($strChaveAcesso, $IdLogin, $IdSistema, $IdUsuario, $HashAgente) {
    try {
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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function loginUnificado($strChaveAcesso, $SiglaOrgaoSistema, $SiglaSistema, $Link, $HashAgente) {
    try {
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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function removerLogin($strChaveAcesso, $SiglaOrgaoSistema, $SiglaSistema, $Link, $IdUsuario) {
    try {
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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function carregarOrgaos($strChaveAcesso, $IdSistema, $SinTodos) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_ORGAOS, $IdSistema);

      //InfraDebug::getInstance()->setBolLigado(false);
      //InfraDebug::getInstance()->setBolDebugInfra(false);
      //InfraDebug::getInstance()->limpar();

      $ret = array();

      //Busca hierarquia do sistema
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->setNumIdSistema($IdSistema);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      if ($objSistemaDTO == null) {
        throw new InfraException('Sistema não encontrado.');
      }

      $arrObjRelHierarquiaUnidadeDTO = null;

      if ($SinTodos == 'N') {
        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->setDistinct(true);
        $objRelHierarquiaUnidadeDTO->retNumIdOrgaoUnidade();
        $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());

        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
        $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

        if (count($arrObjRelHierarquiaUnidadeDTO) == 0) {
          return $ret;
        }
      }

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->retStrSinAtivo();

      if ($SinTodos == 'N') {
        $objOrgaoDTO->setNumIdOrgao(InfraArray::converterArrInfraDTO($arrObjRelHierarquiaUnidadeDTO, 'IdOrgaoUnidade'), InfraDTO::$OPER_IN);
      }

      $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

      foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {
        //ATENÇÃO: os elementos devem ser adicionados no array seguindo a ordem dos índices (posição 0, 1, 2, ...)
        //Ao enviar via web-services o PHP ignora o valor do índice passado na constante e assume a ordem em que foram adicionados.

        $numIdOrgao = $objOrgaoDTO->getNumIdOrgao();

        $ret[$numIdOrgao] = array();
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_ID] = $numIdOrgao;
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_SIGLA] = $objOrgaoDTO->getStrSigla();
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_DESCRICAO] = $objOrgaoDTO->getStrDescricao();
        $ret[$numIdOrgao][InfraSip::$WS_ORGAO_SIN_ATIVO] = $objOrgaoDTO->getStrSinAtivo();
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function carregarUnidades($strChaveAcesso, $IdSistema, $IdUsuario, $IdUnidade) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_UNIDADES, $IdSistema);

      //InfraDebug::getInstance()->setBolLigado(false);
      //InfraDebug::getInstance()->setBolDebugInfra(true);
      //InfraDebug::getInstance()->limpar();

      if (InfraString::isBolVazia($IdUsuario)) {
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->setNumIdSistema($IdSistema);
        $objSistemaDTO->setNumIdUnidade($IdUnidade);

        $objSistemaRN = new SistemaRN();
        $ret = $objSistemaRN->listarUnidades($objSistemaDTO);
      } else {
        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->setNumIdSistema($IdSistema);
        $objPermissaoDTO->setNumIdUsuario($IdUsuario);

        $objPermissaoRN = new PermissaoRN();
        $ret = $objPermissaoRN->listarUnidades($objPermissaoDTO);
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function carregarUsuarios($strChaveAcesso, $IdSistema, $IdUnidade, $Recurso, $Perfil) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_USUARIOS, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(true);
      InfraDebug::getInstance()->limpar();
      */

      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setNumIdSistema($IdSistema);

      if (!InfraString::isBolVazia($IdUnidade)) {
        $objPermissaoDTO->setNumIdUnidade($IdUnidade);
      }

      if (!InfraString::isBolVazia($Recurso)) {
        $objPermissaoDTO->setStrNomeRecurso($Recurso);
      }

      if (!InfraString::isBolVazia($Perfil)) {
        $objPermissaoDTO->setStrNomePerfil($Perfil);
      }

      $objPermissaoRN = new PermissaoRN();
      $ret = $objPermissaoRN->carregarUsuarios($objPermissaoDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function carregarUsuario($strChaveAcesso, $IdSistema, $TipoServidorAutenticacao, $IdOrgaoUsuario, $SiglaUsuario) {
    try {
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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function replicarUsuario($strChaveAcesso, $Usuarios, $SinConsiderarOrgao) {
    try {
      ini_set('max_execution_time', '0');
      ini_set('memory_limit', '2048M');

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

      if (!is_array($Usuarios)) {
        $Usuarios = (array)$Usuarios;
      }

      foreach ($Usuarios as $Usuario) {
        if (!is_array($Usuario)) {
          $Usuario = (array)$Usuario;
        }

        $StaOperacao = $Usuario['StaOperacao'];
        $IdOrgao = $Usuario['IdOrgao'];
        $IdOrigem = $Usuario['IdOrigem'];
        $Sigla = $Usuario['Sigla'];
        $Nome = $Usuario['Nome'];

        if (isset($Usuario['NomeSocial'])) {
          $NomeSocial = $Usuario['NomeSocial'];
        } else {
          $NomeSocial = null;
        }

        if (isset($Usuario['Cpf'])) {
          $Cpf = $Usuario['Cpf'];
        } else {
          $Cpf = null;
        }

        if (isset($Usuario['Email'])) {
          $Email = $Usuario['Email'];
        } else {
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

        try {
          if (InfraString::isBolVazia($IdOrigem)) {
            throw new InfraException('Identificador de origem do usuário não informado.');
          }

          if ($SinConsiderarOrgao == 'S' && InfraString::isBolVazia($IdOrgao)) {
            throw new InfraException('Identificador do órgão do usuário não informado.');
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

          if ($SinConsiderarOrgao == 'S') {
            $objUsuarioDTOBanco->setNumIdOrgao($IdOrgao);
          }

          $objUsuarioDTOBanco = $objUsuarioRN->consultar($objUsuarioDTOBanco);

          if ($StaOperacao == 'C' || $StaOperacao == 'A') {
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
          } else {
            if ($StaOperacao == 'E') {
              if ($objUsuarioDTOBanco != null) {
                try {
                  $objUsuarioRN->excluir(array($objUsuarioDTOBanco));
                } catch (Exception $e) {
                  //erro de integridade então desativa
                  $objUsuarioRN->desativar(array($objUsuarioDTOBanco));
                }
              }
            } else {
              if ($StaOperacao == 'D') {
                if ($objUsuarioDTOBanco != null) {
                  $objUsuarioRN->desativar(array($objUsuarioDTOBanco));
                }
              } else {
                if ($StaOperacao == 'R') {
                  if ($objUsuarioDTOBanco != null) {
                    $objUsuarioRN->reativar(array($objUsuarioDTOBanco));
                  }
                } else {
                  throw new InfraException('Operação ' . $StaOperacao . ' inválida.');
                }
              }
            }
          }
        } catch (Exception $e) {
          $objInfraException->adicionarValidacao("\n * " . $Sigla . ' (' . $IdOrigem . '): ' . $e->__toString() . "\n");

          if (!($e instanceof InfraException && $e->contemValidacoes())) {
            try {
              LogSip::getInstance()->gravar(InfraException::inspecionar($e));
            } catch (Exception $e2) {
            }
          }
        }
      }

      if ($objInfraException->contemValidacoes()) {
        $objInfraException->lancarValidacoes();
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }

    return false;
  }

  public function replicarPermissao($strChaveAcesso, $Permissoes) {
    try {
      ini_set('max_execution_time', '0');
      ini_set('memory_limit', '2048M');

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_REPLICACAO_PERMISSOES);


      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('PERMISSOES:'.count($Permissoes));
      */


      $objUsuarioRN = new UsuarioRN();
      $objUnidadeRN = new UnidadeRN();
      $objPerfilRN = new PerfilRN();
      $objPermissaoRN = new PermissaoRN();

      $objInfraException = new InfraException();

      if (!is_array($Permissoes)) {
        $Permissoes = (array)$Permissoes;
      }

      foreach ($Permissoes as $Permissao) {
        if (!is_array($Permissao)) {
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

        if (InfraString::isBolVazia($IdSistema)) {
          throw new InfraException('Sistema não informado.');
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

          if (!InfraString::isBolVazia($IdOrgaoUsuario)) {
            $objUsuarioDTO->setNumIdOrgao($IdOrgaoUsuario);
          }

          if (!InfraString::isBolVazia($IdUsuario)) {
            $objUsuarioDTO->setNumIdUsuario($IdUsuario);
          }

          if (!InfraString::isBolVazia($IdOrigemUsuario)) {
            $objUsuarioDTO->setStrIdOrigem($IdOrigemUsuario);
          }

          $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

          if ($objUsuarioDTO == null) {
            throw new InfraException('Nenhum usuário encontrado [IdUsuario=' . $IdUsuario . ', IdOrigemUsuario=' . $IdOrigemUsuario . ', IdOrgaoUsuario=' . $IdOrgaoUsuario . '].');
          }

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retNumIdUnidade();
          $objUnidadeDTO->retStrSinAtivo();

          if (!InfraString::isBolVazia($IdOrgaoUnidade)) {
            $objUnidadeDTO->setNumIdOrgao($IdOrgaoUnidade);
          }

          if (!InfraString::isBolVazia($IdUnidade)) {
            $objUnidadeDTO->setNumIdUnidade($IdUnidade);
          }

          if (!InfraString::isBolVazia($IdOrigemUnidade)) {
            $objUnidadeDTO->setStrIdOrigem($IdOrigemUnidade);
          }

          $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

          if ($objUnidadeDTO == null) {
            throw new InfraException('Nenhuma unidade encontrada [IdUnidade=' . $IdUnidade . ', IdOrigemUnidade=' . $IdOrigemUnidade . ', IdOrgaoUnidade=' . $IdOrgaoUnidade . '].');
          }

          $objPerfilDTO = new PerfilDTO();
          $objPerfilDTO->retNumIdPerfil();
          $objPerfilDTO->retStrSinAtivo();
          $objPerfilDTO->setNumIdSistema($IdSistema);

          if (!InfraString::isBolVazia($IdPerfil)) {
            $objPerfilDTO->setNumIdPerfil($IdPerfil);
          }

          $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

          if ($objPerfilDTO == null) {
            throw new InfraException('Nenhum perfil encontrado [IdSistema=' . $IdSistema . ', IdPerfil=' . $IdPerfil . '].');
          }

          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumMaxRegistrosRetorno(1);
          $objPermissaoDTO->retNumIdSistema();
          $objPermissaoDTO->setNumIdSistema($IdSistema);
          $objPermissaoDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
          $objPermissaoDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
          $objPermissaoDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

          $bolExiste = ($objPermissaoRN->consultar($objPermissaoDTO) != null);

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
          } else {
            if ($StaOperacao == 'E') {
              if ($bolExiste) {
                $objPermissaoRN->excluir(array($objPermissaoDTO));
              }
            } else {
              throw new InfraException('Operação ' . $StaOperacao . ' inválida.');
            }
          }
        } catch (Exception $e) {
          $objInfraException->adicionarValidacao("\n * " . $e->__toString());

          if (!($e instanceof InfraException && $e->contemValidacoes())) {
            try {
              LogSip::getInstance()->gravar(InfraException::inspecionar($e));
            } catch (Exception $e2) {
            }
          }
        }
      }

      $objInfraException->lancarValidacoes();

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }

    return false;
  }

  public function listarPermissao($strChaveAcesso, $IdSistema, $IdOrgaoUsuario, $IdUsuario, $IdOrigemUsuario, $IdOrgaoUnidade, $IdUnidade, $IdOrigemUnidade, $IdPerfil, $IdGruposPerfil, $NomeGruposPerfil) {
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
      InfraDebug::getInstance()->gravar('ID GRUPOS PERFIL:'.print_r($IdGruposPerfil,true));
      InfraDebug::getInstance()->gravar('NOME GRUPOS PERFIL:'.print_r($NomeGruposPerfil,true));
      */

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setBolExclusaoLogica(false);
      $objSistemaDTO->retStrSigla();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->retStrSinAtivo();
      $objSistemaDTO->setNumIdSistema($IdSistema);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      if ($objSistemaDTO == null) {
        throw new InfraException('Sistema [' . $IdSistema . '] não encontrado.');
      }

      if ($objSistemaDTO->getStrSinAtivo() == 'N') {
        throw new InfraException('Sistema ' . $objSistemaDTO->getStrSigla() . ' desativado.');
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

      if (!InfraString::isBolVazia($IdUsuario)) {
        $objPermissaoDTO->setNumIdUsuario($IdUsuario);
      }

      if (!InfraString::isBolVazia($IdOrigemUsuario)) {
        $objPermissaoDTO->setStrIdOrigemUsuario($IdOrigemUsuario);
      }

      if (!InfraString::isBolVazia($IdOrgaoUsuario)) {
        $objPermissaoDTO->setNumIdOrgaoUsuario($IdOrgaoUsuario);
      }

      if (!InfraString::isBolVazia($IdUnidade)) {
        $objPermissaoDTO->setNumIdUnidade($IdUnidade);
      }

      if (!InfraString::isBolVazia($IdOrigemUnidade)) {
        $objPermissaoDTO->setStrIdOrigemUnidade($IdOrigemUnidade);
      }

      if (!InfraString::isBolVazia($IdOrgaoUnidade)) {
        $objPermissaoDTO->setNumIdOrgaoUnidade($IdOrgaoUnidade);
      }

      $this->filtrarPerfilGrupo($objPermissaoDTO, $IdSistema, $IdPerfil, $IdGruposPerfil, $NomeGruposPerfil);

      $objPermissaoDTO->setOrdNumIdOrgaoUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objPermissaoDTO->setOrdNumIdUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objPermissaoDTO->setOrdStrIdOrigemUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objPermissaoRN = new PermissaoRN();
      $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

      $ret = array();
      foreach ($arrObjPermissaoDTO as $objPermissaoDTO) {
        $ret[] = (object)array(
          'IdSistema' => $objPermissaoDTO->getNumIdSistema(), 'IdOrgaoUsuario' => $objPermissaoDTO->getNumIdOrgaoUsuario(), 'IdUsuario' => $objPermissaoDTO->getNumIdUsuario(), 'IdOrigemUsuario' => $objPermissaoDTO->getStrIdOrigemUsuario(), 'IdOrgaoUnidade' => $objPermissaoDTO->getNumIdOrgaoUnidade(), 'IdUnidade' => $objPermissaoDTO->getNumIdUnidade(), 'IdOrigemUnidade' => $objPermissaoDTO->getStrIdOrigemUnidade(), 'IdPerfil' => $objPermissaoDTO->getNumIdPerfil(), 'DataInicial' => $objPermissaoDTO->getDtaDataInicio(), 'DataFinal' => $objPermissaoDTO->getDtaDataFim(), 'SinSubunidades' => $objPermissaoDTO->getStrSinSubunidades()
        );
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }

    return null;
  }

  public function replicarCoordenacaoPerfil($strChaveAcesso, $CoordenacoesPerfil) {
    try {
      ini_set('max_execution_time', '0');
      ini_set('memory_limit', '2048M');

      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_REPLICACAO_COORDENACOES_PERFIL);

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('COORDENACOES DE PERFIL:'.count($CoordenacoesPerfil));
      */


      $objUsuarioRN = new UsuarioRN();
      $objPerfilRN = new PerfilRN();
      $objCoordenadorPerfilRN = new CoordenadorPerfilRN();

      $objInfraException = new InfraException();

      if (!is_array($CoordenacoesPerfil)) {
        $CoordenacoesPerfil = (array)$CoordenacoesPerfil;
      }

      foreach ($CoordenacoesPerfil as $CoordenadorPerfil) {
        if (!is_array($CoordenadorPerfil)) {
          $CoordenadorPerfil = (array)$CoordenadorPerfil;
        }

        $StaOperacao = $CoordenadorPerfil['StaOperacao'];
        $IdSistema = $CoordenadorPerfil['IdSistema'];
        $IdOrgaoUsuario = $CoordenadorPerfil['IdOrgaoUsuario'];
        $IdUsuario = $CoordenadorPerfil['IdUsuario'];
        $IdOrigemUsuario = $CoordenadorPerfil['IdOrigemUsuario'];
        $IdPerfil = $CoordenadorPerfil['IdPerfil'];

        InfraDebug::getInstance()->gravar(' ');
        InfraDebug::getInstance()->gravar('OPERACAO:' . $StaOperacao);
        InfraDebug::getInstance()->gravar('ID SISTEMA:' . $IdSistema);
        InfraDebug::getInstance()->gravar('ID ORGAO USUARIO:' . $IdOrgaoUsuario);
        InfraDebug::getInstance()->gravar('ID USUARIO:' . $IdUsuario);
        InfraDebug::getInstance()->gravar('ID ORIGEM USUARIO:' . $IdOrigemUsuario);
        InfraDebug::getInstance()->gravar('ID PERFIL:' . $IdPerfil);

        if (InfraString::isBolVazia($IdSistema)) {
          throw new InfraException('Sistema não informado.');
        }

        if (InfraString::isBolVazia($IdUsuario) && InfraString::isBolVazia($IdOrigemUsuario)) {
          throw new InfraException('Nenhum identificador de usuário informado.');
        }

        try {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->retNumIdUsuario();
          $objUsuarioDTO->retStrSinAtivo();

          if (!InfraString::isBolVazia($IdOrgaoUsuario)) {
            $objUsuarioDTO->setNumIdOrgao($IdOrgaoUsuario);
          }

          if (!InfraString::isBolVazia($IdUsuario)) {
            $objUsuarioDTO->setNumIdUsuario($IdUsuario);
          }

          if (!InfraString::isBolVazia($IdOrigemUsuario)) {
            $objUsuarioDTO->setStrIdOrigem($IdOrigemUsuario);
          }

          $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

          if ($objUsuarioDTO == null) {
            throw new InfraException('Nenhum usuário encontrado [IdUsuario=' . $IdUsuario . ', IdOrigemUsuario=' . $IdOrigemUsuario . ', IdOrgaoUsuario=' . $IdOrgaoUsuario . '].');
          }

          $objPerfilDTO = new PerfilDTO();
          $objPerfilDTO->retNumIdPerfil();
          $objPerfilDTO->retStrSinAtivo();
          $objPerfilDTO->setNumIdSistema($IdSistema);

          if (!InfraString::isBolVazia($IdPerfil)) {
            $objPerfilDTO->setNumIdPerfil($IdPerfil);
          }

          $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

          if ($objPerfilDTO == null) {
            throw new InfraException('Nenhum perfil encontrado [IdSistema=' . $IdSistema . ', IdPerfil=' . $IdPerfil . '].');
          }

          $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
          $objCoordenadorPerfilDTO->retTodos();
          $objCoordenadorPerfilDTO->setNumIdSistema($IdSistema);
          $objCoordenadorPerfilDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
          $objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

          $bolExiste = ($objCoordenadorPerfilRN->consultar($objCoordenadorPerfilDTO) != null);

          if ($StaOperacao == 'A') {
            if (!$bolExiste) {
              $objCoordenadorPerfilRN->cadastrar($objCoordenadorPerfilDTO);
            }
          } else {
            if ($StaOperacao == 'E') {
              if ($bolExiste) {
                $objCoordenadorPerfilRN->excluir(array($objCoordenadorPerfilDTO));
              }
            } else {
              throw new InfraException('Operação ' . $StaOperacao . ' inválida.');
            }
          }
        } catch (Exception $e) {
          $objInfraException->adicionarValidacao("\n * " . $e->__toString());

          if (!($e instanceof InfraException && $e->contemValidacoes())) {
            try {
              LogSip::getInstance()->gravar(InfraException::inspecionar($e));
            } catch (Exception $e2) {
            }
          }
        }
      }

      $objInfraException->lancarValidacoes();

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }

    return false;
  }

  public function listarCoordenacaoPerfil($strChaveAcesso, $IdSistema, $IdOrgaoUsuario, $IdUsuario, $IdOrigemUsuario, $IdPerfil, $IdGruposPerfil, $NomeGruposPerfil) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_COORDENADOR_PERFIL, $IdSistema);

      /*
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(true);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('ID ORGAO USUARIO:'.$IdOrgaoUsuario);
      InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
      InfraDebug::getInstance()->gravar('ID ORIGEM USUARIO:'.$IdOrigemUsuario);
      InfraDebug::getInstance()->gravar('ID PERFIL:'.$IdPerfil);
      InfraDebug::getInstance()->gravar('ID GRUPOS PERFIL:'.print_r($IdGruposPerfil,true));
      InfraDebug::getInstance()->gravar('NOME GRUPOS PERFIL:'.print_r($NomeGruposPerfil,true));
      */

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setBolExclusaoLogica(false);
      $objSistemaDTO->retStrSigla();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->retStrSinAtivo();
      $objSistemaDTO->setNumIdSistema($IdSistema);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      if ($objSistemaDTO == null) {
        throw new InfraException('Sistema [' . $IdSistema . '] não encontrado.');
      }

      if ($objSistemaDTO->getStrSinAtivo() == 'N') {
        throw new InfraException('Sistema ' . $objSistemaDTO->getStrSigla() . ' desativado.');
      }

      $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
      $objCoordenadorPerfilDTO->retNumIdSistema();
      $objCoordenadorPerfilDTO->retNumIdOrgaoUsuario();
      $objCoordenadorPerfilDTO->retNumIdUsuario();
      $objCoordenadorPerfilDTO->retStrIdOrigemUsuario();
      $objCoordenadorPerfilDTO->retNumIdPerfil();

      $objCoordenadorPerfilDTO->setNumIdSistema($IdSistema);

      if (!InfraString::isBolVazia($IdUsuario)) {
        $objCoordenadorPerfilDTO->setNumIdUsuario($IdUsuario);
      }

      if (!InfraString::isBolVazia($IdOrigemUsuario)) {
        $objCoordenadorPerfilDTO->setStrIdOrigemUsuario($IdOrigemUsuario);
      }

      if (!InfraString::isBolVazia($IdOrgaoUsuario)) {
        $objCoordenadorPerfilDTO->setNumIdOrgaoUsuario($IdOrgaoUsuario);
      }

      $this->filtrarPerfilGrupo($objCoordenadorPerfilDTO, $IdSistema, $IdPerfil, $IdGruposPerfil, $NomeGruposPerfil);

      $objCoordenadorPerfilDTO->setOrdNumIdOrgaoUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objCoordenadorPerfilDTO->setOrdNumIdUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objCoordenadorPerfilDTO->setOrdStrIdOrigemUsuario(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
      $arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO);

      $ret = array();
      foreach ($arrObjCoordenadorPerfilDTO as $objCoordenadorPerfilDTO) {
        $ret[] = (object)array(
          'IdSistema' => $objCoordenadorPerfilDTO->getNumIdSistema(), 'IdOrgaoUsuario' => $objCoordenadorPerfilDTO->getNumIdOrgaoUsuario(), 'IdUsuario' => $objCoordenadorPerfilDTO->getNumIdUsuario(), 'IdOrigemUsuario' => $objCoordenadorPerfilDTO->getStrIdOrigemUsuario(), 'IdPerfil' => $objCoordenadorPerfilDTO->getNumIdPerfil()
        );
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }

    return null;
  }

  public function carregarPerfis($strChaveAcesso, $IdSistema, $IdUsuario, $IdUnidade, $Perfis, $IdGruposPerfil, $NomeGruposPerfil, $StaFiltroRecursosMenus) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_PERFIS, $IdSistema);

      $ret = array();

      /*
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID SISTEMA:'.$IdSistema);
      InfraDebug::getInstance()->gravar('ID USUARIO:'.$IdUsuario);
      InfraDebug::getInstance()->gravar('ID UNIDADE:'.$IdUnidade);
      InfraDebug::getInstance()->gravar('PERFIS:'.print_r($Perfis,true));
      InfraDebug::getInstance()->gravar('ID GRUPOS PERFIL:'.print_r($IdGruposPerfil,true));
      InfraDebug::getInstance()->gravar('NOME GRUPOS PERFIL:'.print_r($NomeGruposPerfil,true));
      InfraDebug::getInstance()->gravar('STA FILTRO RECURSOS MENUS:'.$StaFiltroRecursosMenus);
      */


      if (InfraString::isBolVazia($IdSistema)) {
        throw new InfraException('Sistema não informado.');
      }

      if (InfraString::isBolVazia($StaFiltroRecursosMenus)) {
        $StaFiltroRecursosMenus = 'N';
      }

      if (!in_array($StaFiltroRecursosMenus, array('N', 'R', 'M', 'T'))) {
        throw new InfraException('Filtro de recursos/menus inválido.');
      }

      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->setBolExclusaoLogica(false);
      $objPerfilDTO->retNumIdPerfil();
      $objPerfilDTO->retStrNome();
      $objPerfilDTO->retStrDescricao();
      $objPerfilDTO->retStrSinAtivo();

      if (InfraString::isBolVazia($IdUsuario) && InfraString::isBolVazia($IdUnidade)) {
        $objPerfilDTO->setNumIdSistema($IdSistema);

        $this->filtrarPerfilGrupo($objPerfilDTO, $IdSistema, $Perfis, $IdGruposPerfil, $NomeGruposPerfil);
      } else {
        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->setDistinct(true);
        $objPermissaoDTO->retNumIdPerfil();
        $objPermissaoDTO->setNumIdSistema($IdSistema);

        if (!InfraString::isBolVazia($IdUnidade)) {
          $objPermissaoDTO->setNumIdUnidade($IdUnidade);
        }

        if (!InfraString::isBolVazia($IdUsuario)) {
          $objPermissaoDTO->setNumIdUsuario($IdUsuario);
        }

        $this->filtrarPerfilGrupo($objPermissaoDTO, $IdSistema, $Perfis, $IdGruposPerfil, $NomeGruposPerfil);

        $objPermissaoDTO->setDtaDataInicio(InfraData::getStrDataAtual(), InfraDTO::$OPER_MENOR_IGUAL);
        $objPermissaoDTO->adicionarCriterio(array('DataFim', 'DataFim'), array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_IGUAL), array(InfraData::getStrDataAtual(), null), InfraDTO::$OPER_LOGICO_OR);


        $objPermissaoRN = new PermissaoRN();
        $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

        if (count($arrObjPermissaoDTO)) {
          $objPerfilDTO->setNumIdPerfil(InfraArray::converterArrInfraDTO($arrObjPermissaoDTO, 'IdPerfil'), InfraDTO::$OPER_IN);
        } else {
          $objPerfilDTO->setNumIdPerfil(null);
        }
      }

      $objPerfilDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objPerfilRN = new PerfilRN();
      $arrObjPerfilDTO = $objPerfilRN->listar($objPerfilDTO);


      if (count($arrObjPerfilDTO)) {
        $arrIdPerfil = InfraArray::converterArrInfraDTO($arrObjPerfilDTO, 'IdPerfil');

        $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
        $objRelGrupoPerfilPerfilDTO->retNumIdPerfil();
        $objRelGrupoPerfilPerfilDTO->retNumIdGrupoPerfil();
        $objRelGrupoPerfilPerfilDTO->retStrNomeGrupoPerfil();
        $objRelGrupoPerfilPerfilDTO->retStrSinAtivoGrupoPerfil();
        $objRelGrupoPerfilPerfilDTO->setNumIdPerfil($arrIdPerfil, InfraDTO::$OPER_IN);
        $objRelGrupoPerfilPerfilDTO->setOrdStrNomeGrupoPerfil(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objRelGrupoPerfilPerfilRN = new RelGrupoPerfilPerfilRN();
        $arrObjRelGrupoPerfilPerfilDTO = InfraArray::indexarArrInfraDTO($objRelGrupoPerfilPerfilRN->listar($objRelGrupoPerfilPerfilDTO), 'IdPerfil', true);

        $arrObjRelPerfilRecursoDTO = array();
        $arrObjRelPerfilItemMenuDTO = array();

        if ($StaFiltroRecursosMenus != 'N') {
          $arrRecursosItensMenu = array();

          if ($StaFiltroRecursosMenus == 'T' || $StaFiltroRecursosMenus == 'M') {
            $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
            $objRelPerfilItemMenuDTO->retNumIdPerfil();
            $objRelPerfilItemMenuDTO->retNumIdRecurso();
            $objRelPerfilItemMenuDTO->retNumIdMenu();
            $objRelPerfilItemMenuDTO->retNumIdItemMenu();
            $objRelPerfilItemMenuDTO->setNumIdSistema($IdSistema);
            $objRelPerfilItemMenuDTO->setNumIdPerfil($arrIdPerfil, InfraDTO::$OPER_IN);

            $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
            $arrObjRelPerfilItemMenuDTO = $objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO);

            if ($StaFiltroRecursosMenus == 'M') {
              $arrRecursosItensMenu = array_keys(InfraArray::indexarArrInfraDTO($arrObjRelPerfilItemMenuDTO, 'IdRecurso'));
            }

            $arrObjRelPerfilItemMenuDTO = InfraArray::indexarArrInfraDTO($arrObjRelPerfilItemMenuDTO, 'IdPerfil', true);

            foreach (array_keys($arrObjRelPerfilItemMenuDTO) as $numIdPerfil) {
              $arrObjRelPerfilItemMenuDTO[$numIdPerfil] = InfraArray::indexarArrInfraDTO($arrObjRelPerfilItemMenuDTO[$numIdPerfil], 'IdRecurso', true);
            }

            $objMenuDTO = new MenuDTO();
            $objMenuDTO->setBolExclusaoLogica(false);
            $objMenuDTO->retNumIdMenu();
            $objMenuDTO->retStrNome();
            $objMenuDTO->retStrSinAtivo();
            $objMenuDTO->setNumIdSistema($IdSistema);
            $objMenuDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objMenuRN = new MenuRN();
            $arrObjMenuDTO = InfraArray::indexarArrInfraDTO($objMenuRN->listar($objMenuDTO), 'IdMenu');

            $objItemMenuDTO = new ItemMenuDTO();
            $objItemMenuRN = new ItemMenuRN();

            $arrObjItemMenuDTO = array();
            foreach ($arrObjMenuDTO as $objMenuDTO) {
              $objItemMenuDTO->setNumIdMenu($objMenuDTO->getNumIdMenu());
              $arrObjItemMenuDTO = array_merge($arrObjItemMenuDTO, $objItemMenuRN->listarHierarquia($objItemMenuDTO));
            }
            $arrObjItemMenuDTO = InfraArray::indexarArrInfraDTO($arrObjItemMenuDTO, 'IdItemMenu');
          }

          if (!($StaFiltroRecursosMenus == 'M' && count($arrRecursosItensMenu) == 0)) {
            $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO();
            $objRelPerfilRecursoDTO->retNumIdPerfil();
            $objRelPerfilRecursoDTO->retNumIdRecurso();
            $objRelPerfilRecursoDTO->retStrNomeRecurso();
            $objRelPerfilRecursoDTO->retStrDescricaoRecurso();
            $objRelPerfilRecursoDTO->retStrSinAtivoRecurso();
            $objRelPerfilRecursoDTO->setNumIdSistema($IdSistema);
            $objRelPerfilRecursoDTO->setNumIdPerfil($arrIdPerfil, InfraDTO::$OPER_IN);

            if ($StaFiltroRecursosMenus == 'M') {
              $objRelPerfilRecursoDTO->setNumIdRecurso($arrRecursosItensMenu, InfraDTO::$OPER_IN);
            }

            $objRelPerfilRecursoDTO->setOrdStrNomeRecurso(InfraDTO::$TIPO_ORDENACAO_ASC);

            $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
            $arrObjRelPerfilRecursoDTO = InfraArray::indexarArrInfraDTO($objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO), 'IdPerfil', true);
          }
        }

        foreach ($arrObjPerfilDTO as $objPerfilDTO) {
          $numIdPerfil = $objPerfilDTO->getNumIdPerfil();

          $arrRecursos = array();
          $arrMenus = array();
          $arrItensMenu = array();

          if (isset($arrObjRelPerfilRecursoDTO[$numIdPerfil])) {
            foreach ($arrObjRelPerfilRecursoDTO[$numIdPerfil] as $objRelPerfilRecursoDTO) {
              $numIdRecurso = $objRelPerfilRecursoDTO->getNumIdRecurso();

              $arrRecursos[] = array(
                InfraSip::$WS_RECURSO_ID => $numIdRecurso, InfraSip::$WS_RECURSO_NOME => $objRelPerfilRecursoDTO->getStrNomeRecurso(), InfraSip::$WS_RECURSO_DESCRICAO => $objRelPerfilRecursoDTO->getStrDescricaoRecurso(), InfraSip::$WS_RECURSO_SIN_ATIVO => $objRelPerfilRecursoDTO->getStrSinAtivoRecurso()
              );

              if (isset($arrObjRelPerfilItemMenuDTO[$numIdPerfil][$numIdRecurso])) {
                foreach ($arrObjRelPerfilItemMenuDTO[$numIdPerfil][$numIdRecurso] as $objRelPerfilItemMenuDTO) {
                  $numIdMenu = $objRelPerfilItemMenuDTO->getNumIdMenu();

                  if (!isset($arrItensMenu[$numIdMenu])) {
                    $arrItensMenu[$numIdMenu] = array();
                  }

                  if (!isset($arrObjItemMenuDTO[$objRelPerfilItemMenuDTO->getNumIdItemMenu()])) {
                    throw new InfraException('Item de menu [' . $objRelPerfilItemMenuDTO->getNumIdItemMenu() . '] não encontrado.');
                  }

                  $objItemMenuDTO = $arrObjItemMenuDTO[$objRelPerfilItemMenuDTO->getNumIdItemMenu()];

                  $arrItensMenu[$numIdMenu][] = array(
                    InfraSip::$WS_ITEM_MENU_ID => $objItemMenuDTO->getNumIdItemMenu(), InfraSip::$WS_ITEM_MENU_RECURSO_ID => $objItemMenuDTO->getNumIdRecurso(), InfraSip::$WS_ITEM_MENU_ROTULO => $objItemMenuDTO->getStrRotulo(), InfraSip::$WS_ITEM_MENU_RAMIFICACAO => $objItemMenuDTO->getStrRamificacao(), InfraSip::$WS_ITEM_MENU_SIN_ATIVO => $objItemMenuDTO->getStrSinAtivo()
                  );
                }
              }
            }

            foreach ($arrItensMenu as $numIdMenu => $arr) {
              $arrMenus[$numIdMenu] = array(
                InfraSip::$WS_MENU_ID => $numIdMenu, InfraSip::$WS_MENU_NOME => $arrObjMenuDTO[$numIdMenu]->getStrNome(), InfraSip::$WS_MENU_SIN_ATIVO => $arrObjMenuDTO[$numIdMenu]->getStrSinAtivo(), InfraSip::$WS_MENU_ITENS_MENU => $arr
              );
            }
            $arrMenus = array_values($arrMenus);
            InfraArray::ordenarArray($arrMenus, InfraSip::$WS_MENU_NOME, InfraArray::$TIPO_ORDENACAO_ASC);
          }

          $arrGrupos = array();
          if (isset($arrObjRelGrupoPerfilPerfilDTO[$objPerfilDTO->getNumIdPerfil()])) {
            foreach (
              $arrObjRelGrupoPerfilPerfilDTO[$objPerfilDTO->getNumIdPerfil()] as $objRelGrupoPerfilPerfilDTO
            ) {
              $arrGrupos[] = array(
                InfraSip::$WS_GRUPO_PERFIL_ID => $objRelGrupoPerfilPerfilDTO->getNumIdGrupoPerfil(), InfraSip::$WS_GRUPO_PERFIL_NOME => $objRelGrupoPerfilPerfilDTO->getStrNomeGrupoPerfil(), InfraSip::$WS_GRUPO_PERFIL_SIN_ATIVO => $objRelGrupoPerfilPerfilDTO->getStrSinAtivoGrupoPerfil()
              );
            }
          }

          $ret[] = array(
            InfraSip::$WS_PERFIL_ID => $objPerfilDTO->getNumIdPerfil(), InfraSip::$WS_PERFIL_NOME => $objPerfilDTO->getStrNome(), InfraSip::$WS_PERFIL_DESCRICAO => $objPerfilDTO->getStrDescricao(), InfraSip::$WS_PERFIL_SIN_ATIVO => $objPerfilDTO->getStrSinAtivo(), InfraSip::$WS_PERFIL_GRUPOS => (count($arrGrupos) ? $arrGrupos : null), InfraSip::$WS_PERFIL_RECURSOS => (count($arrRecursos) ? $arrRecursos : null), InfraSip::$WS_PERFIL_MENUS => (count($arrMenus) ? $arrMenus : null)
          );
        }
      }

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function carregarRecursos($strChaveAcesso, $IdSistema, $Perfis, $Recursos) {
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

      if ($Perfis != null) {
        $objRelPerfilRecursoDTO->setNumIdPerfil($Perfis, InfraDTO::$OPER_IN);
      }

      if ($Recursos != null) {
        $objRelPerfilRecursoDTO->setStrNomeRecurso($Recursos, InfraDTO::$OPER_IN);
      }

      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();
      $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);

      $ret = InfraArray::converterArrInfraDTO($arrObjRelPerfilRecursoDTO, 'NomeRecurso');

      return $ret;

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return $ret;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function autenticar($strChaveAcesso, $IdOrgao, $IdContexto, $Sigla, $Senha) {
    try {
      $objSistemaDTO = $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_AUTENTICACAO_USUARIO);

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
      for ($i = 0; $i < strlen($Senha); $i++) {
        $Senha[$i] = ~$Senha[$i];
      }

      $objLoginRN = new LoginRN();

      $objLoginDTO = new LoginDTO();
      $objLoginDTO->setStrSiglaSistema($objSistemaDTO->getStrSigla());
      $objLoginDTO->setStrSiglaOrgaoSistema($objSistemaDTO->getStrSiglaOrgao());
      $objLoginDTO->setNumIdOrgaoUsuario($IdOrgao);
      $objLoginDTO->setStrSiglaUsuario($Sigla);
      $objLoginDTO->setStrSenhaUsuario($Senha);

      $objLoginRN->autenticar($objLoginDTO);

      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

      return true;
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
    return false;
  }

  public function listarAcessos($strChaveAcesso, $IdSistema, $IdUsuario) {
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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function pesquisarUsuario($strChaveAcesso, $TipoServidorAutenticacao, $IdOrgao, $Sigla) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_PESQUISA_USUARIOS);

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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
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
  public function autenticarCompleto($strChaveAcesso, $IdOrgao, $IdContexto, $Sigla, $Senha, $SiglaSistema, $SiglaOrgaoSistema) {
    try {
      $this->validarAcessoServico($strChaveAcesso, SistemaRN::$TS_AUTENTICACAO_USUARIO);

      $Senha = base64_decode($Senha);
      for ($i = 0; $i < strlen($Senha); $i++) {
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
    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }
  }

  public function validarReplicacao($strChaveAcesso, $IdReplicacao) {
    try {
      SessaoSip::getInstance(false);

      /*
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->limpar();

      InfraDebug::getInstance()->gravar(__METHOD__);
      InfraDebug::getInstance()->gravar('ID REPLICACAO:'.count($IdReplicacao));
      */

      $ret = CacheSip::getInstance()->getAtributo('R_' . $IdReplicacao);

      if ($ret == true) {
        CacheSip::getInstance()->removerAtributo('R_' . $IdReplicacao);
        return true;
      }

      throw new InfraException('Identificador de replicação inválido.');
      //LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

    } catch (Throwable $e) {
      $this->processarExcecao($e, true);
    }

    return false;
  }

  private function filtrarPerfilGrupo($dto, $IdSistema, $IdPerfis, $IdGruposPerfil, $NomeGruposPerfil) {
    if ($IdGruposPerfil != null && !is_array($IdGruposPerfil)) {
      $IdGruposPerfil = array($IdGruposPerfil);
    }

    if ($NomeGruposPerfil != null && !is_array($NomeGruposPerfil)) {
      $NomeGruposPerfil = array($NomeGruposPerfil);
    }

    if ($IdPerfis != null && !is_array($IdPerfis)) {
      $IdPerfis = array($IdPerfis);
    }

    if (InfraArray::contar($IdGruposPerfil) || InfraArray::contar($NomeGruposPerfil)) {
      $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
      $objRelGrupoPerfilPerfilDTO->setDistinct(true);
      $objRelGrupoPerfilPerfilDTO->retNumIdPerfil();
      $objRelGrupoPerfilPerfilDTO->setNumIdSistema($IdSistema);
      $objRelGrupoPerfilPerfilDTO->setStrSinAtivoGrupoPerfil('S');

      if (InfraArray::contar($IdGruposPerfil)) {
        $objRelGrupoPerfilPerfilDTO->setNumIdGrupoPerfil($IdGruposPerfil, InfraDTO::$OPER_IN);
      }

      if (InfraArray::contar($NomeGruposPerfil)) {
        $objRelGrupoPerfilPerfilDTO->setStrNomeGrupoPerfil($NomeGruposPerfil, InfraDTO::$OPER_IN);
      }

      if (InfraArray::contar($IdPerfis)) {
        $objRelGrupoPerfilPerfilDTO->setNumIdPerfil($IdPerfis, InfraDTO::$OPER_IN);
      }

      $objRelGrupoPerfilPerfilRN = new RelGrupoPerfilPerfilRN();
      $arrIdPerfisGrupos = InfraArray::converterArrInfraDTO($objRelGrupoPerfilPerfilRN->listar($objRelGrupoPerfilPerfilDTO), 'IdPerfil');

      if (count($arrIdPerfisGrupos) == 0) {
        $dto->setNumIdPerfil(null);
      } else {
        $dto->setNumIdPerfil($arrIdPerfisGrupos, InfraDTO::$OPER_IN);
      }
    } else {
      if (InfraArray::contar($IdPerfis)) {
        $dto->setNumIdPerfil($IdPerfis, InfraDTO::$OPER_IN);
      }
    }
  }
}

$servidorSoap = new SoapServer("sip.wsdl", array('encoding' => 'ISO-8859-1'));
$servidorSoap->setClass("SipWS");

//Só processa se acessado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $servidorSoap->handle();
}
