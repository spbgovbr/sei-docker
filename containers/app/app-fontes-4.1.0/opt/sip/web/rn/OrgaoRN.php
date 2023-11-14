<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class OrgaoRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(OrgaoDTO $objOrgaoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('orgao_cadastrar', __METHOD__, $objOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrSigla($objOrgaoDTO, $objInfraException);
      $this->validarStrDescricao($objOrgaoDTO, $objInfraException);
      $this->validarStrSinAutenticar($objOrgaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objOrgaoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->cadastrar($objOrgaoDTO);

      $objRelOrgaoAutenticacaoRN = new RelOrgaoAutenticacaoRN();
      foreach ($objOrgaoDTO->getArrObjRelOrgaoAutenticacaoDTO() as $objRelOrgaoAutenticacaoDTO) {
        $objRelOrgaoAutenticacaoDTO->setNumIdOrgao($ret->getNumIdOrgao());
        $objRelOrgaoAutenticacaoRN->cadastrar($objRelOrgaoAutenticacaoDTO);
      }


      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setNumIdUnidade(null);
      $objUnidadeDTO->setNumIdOrgao($ret->getNumIdOrgao());
      $objUnidadeDTO->setStrSigla('*');
      $objUnidadeDTO->setStrDescricao('Unidade Global');
      $objUnidadeDTO->setStrSinGlobal('S');
      $objUnidadeDTO->setStrSinAtivo('S');
      $objUnidadeDTO->setStrIdOrigem(null);

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeRN->cadastrar($objUnidadeDTO);


      $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
      $objReplicacaoOrgaoDTO->setStrStaOperacao('C');
      $objReplicacaoOrgaoDTO->setNumIdOrgao($ret->getNumIdOrgao());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarOrgao($objReplicacaoOrgaoDTO);


      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Órgão.', $e);
    }
  }

  protected function alterarControlado(OrgaoDTO $objOrgaoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('orgao_alterar', __METHOD__, $objOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objOrgaoDTO->isSetStrSigla()) {
        $this->validarStrSigla($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinAutenticar()) {
        $this->validarStrSinAutenticar($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objOrgaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      if ($objOrgaoDTO->isSetArrObjRelOrgaoAutenticacaoDTO()) {
        $objRelOrgaoAutenticacaoDTO = new RelOrgaoAutenticacaoDTO();
        $objRelOrgaoAutenticacaoDTO->retNumIdOrgao();
        $objRelOrgaoAutenticacaoDTO->retNumIdServidorAutenticacao();
        $objRelOrgaoAutenticacaoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());

        $objRelOrgaoAutenticacaoRN = new RelOrgaoAutenticacaoRN();
        $objRelOrgaoAutenticacaoRN->excluir($objRelOrgaoAutenticacaoRN->listar($objRelOrgaoAutenticacaoDTO));

        foreach ($objOrgaoDTO->getArrObjRelOrgaoAutenticacaoDTO() as $objRelOrgaoAutenticacaoDTO) {
          $objRelOrgaoAutenticacaoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
          $objRelOrgaoAutenticacaoRN->cadastrar($objRelOrgaoAutenticacaoDTO);
        }
      }

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $objOrgaoBD->alterar($objOrgaoDTO);

      $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
      $objReplicacaoOrgaoDTO->setStrStaOperacao('A');
      $objReplicacaoOrgaoDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarOrgao($objReplicacaoOrgaoDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Órgão.', $e);
    }
  }

  protected function excluirControlado($arrObjOrgaoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('orgao_excluir', __METHOD__, $arrObjOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        //Verifica se existem usuarios no orgao
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());
        $objUsuarioRN = new UsuarioRN();
        if (count($objUsuarioRN->listar($objUsuarioDTO)) > 0) {
          $objInfraException->adicionarValidacao('Existem usuários associados.');
        }

        //Verifica se existem unidades no orgao
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());
        $objUnidadeDTO->setStrSinGlobal('N');
        $objUnidadeRN = new UnidadeRN();
        if (count($objUnidadeRN->listar($objUnidadeDTO)) > 0) {
          $objInfraException->adicionarValidacao('Existem unidades associadas.');
        }

        //Verifica se existem Sistemas no orgao
        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->retNumIdSistema();
        $objSistemaDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());
        $objSistemaRN = new SistemaRN();
        if (count($objSistemaRN->listar($objSistemaDTO)) > 0) {
          $objInfraException->adicionarValidacao('Existem sistemas associados.');
        }

        $objInfraException->lancarValidacoes();
      }

      $objRelOrgaoAutenticacaoRN = new RelOrgaoAutenticacaoRN();
      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objRelOrgaoAutenticacaoDTO = new RelOrgaoAutenticacaoDTO();
        $objRelOrgaoAutenticacaoDTO->retNumIdOrgao();
        $objRelOrgaoAutenticacaoDTO->retNumIdServidorAutenticacao();
        $objRelOrgaoAutenticacaoDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());
        $objRelOrgaoAutenticacaoRN->excluir($objRelOrgaoAutenticacaoRN->listar($objRelOrgaoAutenticacaoDTO));
      }

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->setStrSinGlobal('S');
      $objUnidadeDTO->setNumIdOrgao(InfraArray::converterArrInfraDTO($arrObjOrgaoDTO, 'IdOrgao'), InfraDTO::$OPER_IN);

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeRN->excluir($objUnidadeRN->listar($objUnidadeDTO));


      $objSistemaRN = new SistemaRN();

      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
        $objReplicacaoOrgaoDTO->setStrStaOperacao('E');
        $objReplicacaoOrgaoDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());

        $objSistemaRN->replicarOrgao($objReplicacaoOrgaoDTO);
      }

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objOrgaoBD->excluir($arrObjOrgaoDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Órgão.', $e);
    }
  }

  protected function desativarControlado($arrObjOrgaoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('orgao_desativar', __METHOD__, $arrObjOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objOrgaoBD->desativar($arrObjOrgaoDTO[$i]);
      }

      $objSistemaRN = new SistemaRN();

      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
        $objReplicacaoOrgaoDTO->setStrStaOperacao('D');
        $objReplicacaoOrgaoDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());

        $objSistemaRN->replicarOrgao($objReplicacaoOrgaoDTO);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando Órgão.', $e);
    }
  }

  protected function reativarControlado($arrObjOrgaoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('orgao_reativar', __METHOD__, $arrObjOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objOrgaoBD->reativar($arrObjOrgaoDTO[$i]);
      }

      $objSistemaRN = new SistemaRN();

      for ($i = 0; $i < count($arrObjOrgaoDTO); $i++) {
        $objReplicacaoOrgaoDTO = new ReplicacaoOrgaoDTO();
        $objReplicacaoOrgaoDTO->setStrStaOperacao('R');
        $objReplicacaoOrgaoDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());

        $objSistemaRN->replicarOrgao($objReplicacaoOrgaoDTO);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro reativando Órgão.', $e);
    }
  }

  protected function consultarConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_consultar',__METHOD__,$objOrgaoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->consultar($objOrgaoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Órgão.', $e);
    }
  }

  protected function listarConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);
      /////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->listar($objOrgaoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Órgãos.', $e);
    }
  }

  protected function contarConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      //////////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_contar',__METHOD__,$objOrgaoDTO);
      //////////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->contar($objOrgaoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Órgãos.', $e);
    }
  }


  /**
   * Lista somente os orgãos onde o usuario é administrador do SIP
   */
  protected function listarSipConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);
      /////////////////////////////////////////////////////////////////


      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();


      $arrObjOrgaoDTO = $this->listar($objOrgaoDTO);

      //Obtem sistemas administrados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      $ret = InfraArray::joinArrInfraDTO($arrObjOrgaoDTO, 'IdOrgao', $arrObjAcessoDTO, 'IdOrgaoSistema');

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Órgãos SIP.', $e);
    }
  }

  protected function listarAdministradosConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrObjOrgaoDTO = $this->listar($objOrgaoDTO);

      //Obtem sistemas acessados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      $ret = InfraArray::joinArrInfraDTO($arrObjOrgaoDTO, 'IdOrgao', $arrObjAcessoDTO, 'IdOrgaoSistema');

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Órgãos de sistemas administrados.', $e);
    }
  }

  protected function listarCoordenadosConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrObjOrgaoDTO = $this->listar($objOrgaoDTO);

      //Obtem sistemas acessados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$COORDENADOR_PERFIL);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      $ret = InfraArray::joinArrInfraDTO($arrObjOrgaoDTO, 'IdOrgao', $arrObjAcessoDTO, 'IdOrgaoSistema');

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Órgãos de sistemas coordenados.', $e);
    }
  }

  protected function listarAutorizadosConectado() {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $ret = array();

      //Obtem sistemas acessados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR | AcessoDTO::$COORDENADOR_PERFIL | AcessoDTO::$COORDENADOR_UNIDADE);

      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      //Faz distinct no array por orgaos dos sistemas
      $arrIdOrgaoAcesso = array_unique(InfraArray::converterArrInfraDTO($arrObjAcessoDTO, 'IdOrgaoSistema'));

      if (count($arrIdOrgaoAcesso)) {
        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->retNumIdOrgao();
        $objOrgaoDTO->retStrSigla();

        $objOrgaoDTO->setNumIdOrgao($arrIdOrgaoAcesso, InfraDTO::$OPER_IN);

        $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

        $ret = $this->listar($objOrgaoDTO);
      }

      //Auditoria
      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando órgãos de sistemas autorizados.', $e);
    }
  }

  protected function listarPessoaisConectado(OrgaoDTO $objOrgaoDTO) {
    try {
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrObjOrgaoDTO = $this->listar($objOrgaoDTO);

      //Obtem sistemas acessados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$PERMISSAO);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      $ret = InfraArray::joinArrInfraDTO($arrObjOrgaoDTO, 'IdOrgao', $arrObjAcessoDTO, 'IdOrgaoSistema');

      //Auditoria
      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Órgãos de sistemas com permissão.', $e);
    }
  }

  private function validarStrSigla(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSigla())) {
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }

    $objOrgaoDTO->setStrSigla(trim($objOrgaoDTO->getStrSigla()));

    if (strlen($objOrgaoDTO->getStrSigla()) > 30) {
      $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
    }

    $strSigla = $objOrgaoDTO->getStrSigla();

    if (preg_match("/[^0-9a-zA-Z\-_]/", $strSigla)) {
      $objInfraException->adicionarValidacao('Sigla possui caracter inválido.');
    }

    $dto = new OrgaoDTO();
    $dto->setStrSigla($objOrgaoDTO->getStrSigla());
    $dto->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao(), InfraDTO::$OPER_DIFERENTE);

    if ($this->contar($dto)) {
      $objInfraException->adicionarValidacao('Existe outro órgão utilizando a sigla informada.');
    } else {
      $dto->setBolExclusaoLogica(false);

      if ($this->contar($dto)) {
        $objInfraException->adicionarValidacao('Existe outro órgão inativo utilizando a sigla informada.');
      }
    }
  }

  private function validarStrDescricao(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objOrgaoDTO->getStrDescricao())) {
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }
  }

  private function validarStrSinAutenticar(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException) {
    if ($objOrgaoDTO->getStrSinAutenticar() === null || ($objOrgaoDTO->getStrSinAutenticar() !== 'S' && $objOrgaoDTO->getStrSinAutenticar() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Autenticação inválido.');
    }
  }

  private function validarStrSinAtivo(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException) {
    if ($objOrgaoDTO->getStrSinAtivo() === null || ($objOrgaoDTO->getStrSinAtivo() !== 'S' && $objOrgaoDTO->getStrSinAtivo() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }


}

?>