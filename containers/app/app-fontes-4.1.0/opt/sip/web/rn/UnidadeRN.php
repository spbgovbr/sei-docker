<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';


class UnidadeRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(UnidadeDTO $objUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('unidade_cadastrar', __METHOD__, $objUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objUnidadeDTO, $objInfraException);
      $this->validarStrIdOrigem($objUnidadeDTO, $objInfraException);
      $this->validarStrSigla($objUnidadeDTO, $objInfraException);
      $this->validarStrDescricao($objUnidadeDTO, $objInfraException);
      $this->validarStrSinGlobal($objUnidadeDTO, $objInfraException);
      $this->validarStrSinAtivo($objUnidadeDTO, $objInfraException);


      $dto = new UnidadeDTO();
      $dto->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
      $dto->setStrSigla($objUnidadeDTO->getStrSigla());
      if ($this->contar($dto) > 0) {
        $objInfraException->adicionarValidacao('Já existe uma unidade neste órgão com esta sigla.');
      }

      if ($objUnidadeDTO->getStrSinGlobal() == 'S') {
        $dto = new UnidadeDTO();
        $dto->setBolExclusaoLogica(false);
        $dto->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
        $dto->setStrSinGlobal('S');
        if ($this->contar($dto) > 0) {
          $objInfraException->adicionarValidacao('Já existe uma unidade global neste órgão.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->cadastrar($objUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Unidade.', $e);
    }
  }

  protected function alterarControlado(UnidadeDTO $objUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('unidade_alterar', __METHOD__, $objUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objUnidadeDTOBanco = new UnidadeDTO();
      $objUnidadeDTOBanco->setBolExclusaoLogica(false);
      $objUnidadeDTOBanco->retStrSinGlobal();
      $objUnidadeDTOBanco->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
      $objUnidadeDTOBanco = $this->consultar($objUnidadeDTOBanco);

      if ($objUnidadeDTOBanco == null) {
        throw new InfraException('Unidade [' . $objUnidadeDTO->getNumIdUnidade() . '] não encontrada.');
      }

      if ($objUnidadeDTOBanco->getStrSinGlobal() == 'S') {
        $objInfraException->lancarValidacao('Não é permitida alteração da unidade global.');
      }

      if ($objUnidadeDTO->isSetNumIdOrgao()) {
        $this->validarNumIdOrgao($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrIdOrigem()) {
        $this->validarStrIdOrigem($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrSigla()) {
        $this->validarStrSigla($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objUnidadeDTO, $objInfraException);
      }

      if ($objUnidadeDTO->isSetStrSinGlobal() && $objUnidadeDTO->getStrSinGlobal() != 'N') {
        $objInfraException->adicionarValidacao('Não é possível sinalizar uma unidade como global.');
      }

      if ($objUnidadeDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objUnidadeDTO, $objInfraException);
      }

      $dto = new UnidadeDTO();
      $dto->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade(), InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
      $dto->setStrSigla($objUnidadeDTO->getStrSigla());
      if ($this->contar($dto) > 0) {
        $objInfraException->adicionarValidacao('Existe outra unidade neste órgão com esta sigla.');
      }

      $objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $objUnidadeBD->alterar($objUnidadeDTO);

      //Atualiza unidade nos sistemas
      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
      $objRelHierarquiaUnidadeDTO->setDistinct(true);
      $objRelHierarquiaUnidadeDTO->retNumIdHierarquia();
      $objRelHierarquiaUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());

      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

      $objSistemaRN = new SistemaRN();

      foreach ($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO) {
        $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
        $objReplicacaoUnidadeDTO->setStrStaOperacao('A');
        $objReplicacaoUnidadeDTO->setNumIdHierarquia($objRelHierarquiaUnidadeDTO->getNumIdHierarquia());
        $objReplicacaoUnidadeDTO->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());

        $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Unidade.', $e);
    }
  }

  protected function excluirControlado($arrObjUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('unidade_excluir', __METHOD__, $arrObjUnidadeDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();
      for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
        $dto = new UnidadeDTO();
        $dto->retStrSigla();
        $dto->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());

        $dto = $this->consultar($dto);

        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->setDistinct(true);
        $objPermissaoDTO->retStrSiglaSistema();
        $objPermissaoDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $objPermissaoRN = new PermissaoRN();
        $arr = $objPermissaoRN->listar($objPermissaoDTO);
        if (count($arr) > 0) {
          $objInfraException->adicionarValidacao('Existem permissões associadas com a unidade ' . $dto->getStrSigla() . ' no(s) sistema(s): ' . implode(',', InfraArray::converterArrInfraDTO($arr, 'SiglaSistema')));
        }

        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->retStrNomeHierarquia();
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
        $arr = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);
        if (count($arr) > 0) {
          $objInfraException->adicionarValidacao('A unidade ' . $dto->getStrSigla() . ' pertence à(s) hierarquia(s): ' . implode(',', InfraArray::converterArrInfraDTO($arr, 'NomeHierarquia')));
        }
      }
      $objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
        //Exclui coordenadores de unidade associados
        $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO();
        $objCoordenadorUnidadeDTO->retTodos();
        $objCoordenadorUnidadeDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
        $objCoordenadorUnidadeRN->excluir($objCoordenadorUnidadeRN->listar($objCoordenadorUnidadeDTO));

        /*
        //Exclui permisoes da unidade associados
        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->retNumIdPerfil();
        $objPermissaoDTO->retNumIdSistema();
        $objPermissaoDTO->retNumIdUsuario();
        $objPermissaoDTO->retNumIdUnidade();
        $objPermissaoDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $objPermissaoRN = new PermissaoRN();
        $objPermissaoRN->excluir($objPermissaoRN->listar($objPermissaoDTO));
        */

        //Exclui das hierarquias associadas
        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
        $objRelHierarquiaUnidadeDTO->retNumIdHierarquia();
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
        $objRelHierarquiaUnidadeRN->excluir($objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO));


        $objUnidadeBD->excluir($arrObjUnidadeDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Unidade.', $e);
    }
  }

  protected function desativarControlado($arrObjUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('unidade_desativar', __METHOD__, $arrObjUnidadeDTO);

      $objInfraException = new InfraException();
      for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
        $dto = new UnidadeDTO();
        $dto->setBolExclusaoLogica(false);
        $dto->retStrSinGlobal();
        $dto->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());
        $dto = $this->consultar($dto);

        if ($dto == null) {
          throw new InfraException('Unidade [' . $arrObjUnidadeDTO[$i]->getNumIdUnidade() . '] não encontrada.');
        }

        if ($dto->getStrSinGlobal() == 'S') {
          $objInfraException->lancarValidacao('Não é permitida desativação da unidade global.');
        }

        $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
        $objRelHierarquiaUnidadeDTO->retStrNomeHierarquia();
        $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
        $objRelHierarquiaUnidadeDTO->setNumIdUnidade($arrObjUnidadeDTO[$i]->getNumIdUnidade());

        $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
        $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);

        foreach ($arrObjRelHierarquiaUnidadeDTO as $objRelHierarquiaUnidadeDTO) {
          $objInfraException->adicionarValidacao('Unidade "' . $objRelHierarquiaUnidadeDTO->getStrSiglaUnidade() . '" está ativa na hierarquia "' . $objRelHierarquiaUnidadeDTO->getStrNomeHierarquia() . '".');
        }
      }
      $objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
        $objUnidadeBD->desativar($arrObjUnidadeDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando Unidade.', $e);
    }
  }

  protected function reativarControlado($arrObjUnidadeDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('unidade_reativar', __METHOD__, $arrObjUnidadeDTO);

      //$objInfraException = new InfraException();
      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjUnidadeDTO); $i++) {
        $objUnidadeBD->reativar($arrObjUnidadeDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro reativando Unidade.', $e);
    }
  }

  protected function consultarConectado(UnidadeDTO $objUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('unidade_consultar',__METHOD__,$objUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->consultar($objUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Unidade.', $e);
    }
  }

  protected function listarConectado(UnidadeDTO $objUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('unidade_listar',__METHOD__,$objUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->listar($objUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Unidades.', $e);
    }
  }

  protected function contarConectado(UnidadeDTO $objUnidadeDTO) {
    try {
      //////////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('unidade_contar',__METHOD__,$objUnidadeDTO);
      //////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $ret = $objUnidadeBD->contar($objUnidadeDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Unidades.', $e);
    }
  }

  protected function pesquisarConectado(UnidadeDTO $objUnidadeDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      SessaoSip::getInstance()->validarAuditarPermissao('unidade_listar', __METHOD__, $objUnidadeDTO);
      /////////////////////////////////////////////////////////////////

      if ($objUnidadeDTO->isSetStrSigla()) {
        if (trim($objUnidadeDTO->getStrSigla()) != '') {
          $objUnidadeDTO->setStrSigla('%' . trim($objUnidadeDTO->getStrSigla()) . '%', InfraDTO::$OPER_LIKE);
        }
      }

      if ($objUnidadeDTO->isSetStrDescricao()) {
        if (trim($objUnidadeDTO->getStrDescricao()) != '') {
          InfraString::tratarPalavrasPesquisaDTO($objUnidadeDTO, 'Descricao');
        }
      }

      if ($objUnidadeDTO->isSetStrIdOrigem()) {
        if (trim($objUnidadeDTO->getStrIdOrigem()) != '') {
          $objUnidadeDTO->setStrIdOrigem('%' . trim($objUnidadeDTO->getStrIdOrigem()) . '%', InfraDTO::$OPER_LIKE);
        }
      }

      return $this->listar($objUnidadeDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro pesquisando Unidades.', $e);
    }
  }

  /**
   * Recupera as unidades autorizadas pelo usuario/sistema informado onde carregará:
   * (1) todas as unidades da hierarquia do sistema se usuario administrador do sistema
   * (2) todas as unidades da hierarquia do sistema se usuario coordena algum perfil do sistema
   * (3) unidades da hierarquia do sistema coordenadas pelo usuario no sistema
   */

  protected function obterAutorizadasConectado(UnidadesAutorizadasDTO $objUnidadesAutorizadasDTO) {
    try {
      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objUnidadesAutorizadasDTO->getNumIdSistema() == null) {
        $objInfraException->adicionarValidacao('Sistema não informado.');
      }

      if ($objUnidadesAutorizadasDTO->getNumIdOrgaoUnidade() === null) {
        $objInfraException->adicionarValidacao('Órgão da Unidade não informado.');
      }

      $objInfraException->lancarValidacoes();

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->setNumIdOrgao($objUnidadesAutorizadasDTO->getNumIdOrgaoUnidade());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO);

      if ($objOrgaoDTO == null) {
        throw new InfraException('Órgão inválido.');
      }

      //Busca ID da hierarquia associada com o sistema
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->setNumIdSistema($objUnidadesAutorizadasDTO->getNumIdSistema());
      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
      if ($objSistemaDTO == null) {
        throw new InfraException('Sistema inválido.');
      }


      //Busca todas as unidades da hieraquia do sistema e do orgão escolhido
      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
      $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
      $objRelHierarquiaUnidadeDTO->retNumIdOrgaoUnidade();
      $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());
      $objRelHierarquiaUnidadeDTO->setNumIdOrgaoUnidade($objUnidadesAutorizadasDTO->getNumIdOrgaoUnidade());
      $objRelHierarquiaUnidadeDTO->setStrSinAtivoUnidade('S');
      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      $objRelHierarquiaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjRelHierarquiaUnidadeDTO = $objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO);


      //Obtem sistemas autorizados (todos os sistemas exceto os acessados via permissoes pessoais) pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumIdSistema($objUnidadesAutorizadasDTO->getNumIdSistema());
      $objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR | AcessoDTO::$COORDENADOR_PERFIL | AcessoDTO::$COORDENADOR_UNIDADE);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      //verifica se o usuario é administrador
      foreach ($arrObjAcessoDTO as $acesso) {
        if ($acesso->getNumIdSistema() == $objSistemaDTO->getNumIdSistema()) {
          if ($acesso->getNumTipo() == AcessoDTO::$ADMINISTRADOR) {
            $objUnidadeDTOGlobal = null;

            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->setBolExclusaoLogica(false);
            $objUnidadeDTO->retNumIdUnidade();
            $objUnidadeDTO->retStrSigla();
            $objUnidadeDTO->setStrSinGlobal('S');
            $objUnidadeDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());

            $objUnidadeDTO = $this->consultar($objUnidadeDTO);

            if ($objUnidadeDTO == null) {
              throw new InfraException('Unidade global não encontrada no órgão ' . $objOrgaoDTO->getStrSigla() . '.');
            }

            $objRelHierarquiaUnidadeDTOGlobal = new RelHierarquiaUnidadeDTO();
            $objRelHierarquiaUnidadeDTOGlobal->setNumIdUnidade($objUnidadeDTO->getNumIdUnidade());
            $objRelHierarquiaUnidadeDTOGlobal->setStrSiglaUnidade($objUnidadeDTO->getStrSigla());

            return array_merge(array($objRelHierarquiaUnidadeDTOGlobal), $arrObjRelHierarquiaUnidadeDTO);
          }
        }
      }

      //verifica se o usuario coordena algum perfil do sistema
      foreach ($arrObjAcessoDTO as $acesso) {
        if ($acesso->getNumIdSistema() == $objSistemaDTO->getNumIdSistema()) {
          if ($acesso->getNumTipo() == AcessoDTO::$COORDENADOR_PERFIL) {
            return $arrObjRelHierarquiaUnidadeDTO;
          }
        }
      }

      $ret = array();

      //trata unidade global para coordenadores de unidade
      foreach ($arrObjAcessoDTO as $acesso) {
        if ($acesso->getNumTipo() == AcessoDTO::$COORDENADOR_UNIDADE && $acesso->getNumIdSistema() == $objSistemaDTO->getNumIdSistema() && $acesso->getStrSinGlobalUnidade() == 'S') {
          foreach ($arrObjRelHierarquiaUnidadeDTO as $unidade) {
            if ($acesso->getNumIdOrgaoUnidade() == $unidade->getNumIdOrgaoUnidade() && $acesso->getNumIdUnidade() != $unidade->getNumIdUnidade()) {
              $ret[$unidade->getNumIdUnidade()] = $unidade;
            }
          }
        }
      }

      //Filtra unidades coordenadas (se existirem)
      foreach ($arrObjRelHierarquiaUnidadeDTO as $unidade) {
        foreach ($arrObjAcessoDTO as $acesso) {
          if ($acesso->getNumTipo() == AcessoDTO::$COORDENADOR_UNIDADE && $acesso->getNumIdSistema() == $objSistemaDTO->getNumIdSistema() && ($acesso->getStrSinGlobalUnidade() == 'N' && $acesso->getNumIdUnidade() == $unidade->getNumIdUnidade())) {
            $ret[$unidade->getNumIdUnidade()] = $unidade;
            break;
          }
        }
      }

      //Auditoria
      return array_values($ret);
    } catch (Exception $e) {
      throw new InfraException('Erro listando Unidades autorizadas.', $e);
    }
  }

  private function validarNumIdOrgao(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objUnidadeDTO->getNumIdOrgao())) {
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }
  }

  private function validarStrIdOrigem(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objUnidadeDTO->getStrIdOrigem())) {
      $objUnidadeDTO->setStrIdOrigem(null);
    }
    $objUnidadeDTO->setStrIdOrigem(trim($objUnidadeDTO->getStrIdOrigem()));

    if (strlen($objUnidadeDTO->getStrIdOrigem()) > 50) {
      $objInfraException->adicionarValidacao('Identificador de Origem possui superior a 50 caracteres.');
    }
  }

  private function validarStrSigla(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objUnidadeDTO->getStrSigla())) {
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }
    $objUnidadeDTO->setStrSigla(trim($objUnidadeDTO->getStrSigla()));

    if (strlen($objUnidadeDTO->getStrSigla()) > 30) {
      $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
    }
  }

  private function validarStrDescricao(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objUnidadeDTO->getStrDescricao())) {
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }

    $objUnidadeDTO->setStrDescricao(trim($objUnidadeDTO->getStrDescricao()));

    if (strlen($objUnidadeDTO->getStrDescricao()) > 250) {
      $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
    }
  }

  private function validarStrSinAtivo(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException) {
    if ($objUnidadeDTO->getStrSinAtivo() === null || ($objUnidadeDTO->getStrSinAtivo() !== 'S' && $objUnidadeDTO->getStrSinAtivo() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

  private function validarStrSinGlobal(UnidadeDTO $objUnidadeDTO, InfraException $objInfraException) {
    if ($objUnidadeDTO->getStrSinGlobal() === null || ($objUnidadeDTO->getStrSinGlobal() !== 'S' && $objUnidadeDTO->getStrSinGlobal() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Unidade Global inválido.');
    }
  }

}

?>