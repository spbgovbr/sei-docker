<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RecursoRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function gerarControlado(RecursoPadraoDTO $objRecursoPadraoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('recurso_gerar', __METHOD__, $objRecursoPadraoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objRecursoPadraoDTO->getNumIdSistema())) {
        $objInfraException->adicionarValidacao('Sistema não informado.');
      }

      if (InfraString::isBolVazia($objRecursoPadraoDTO->getStrEntidade())) {
        $objInfraException->adicionarValidacao('Entidade não informada.');
      }

      if (InfraString::isBolVazia($objRecursoPadraoDTO->getStrCaminhoBase())) {
        $objInfraException->adicionarValidacao('Caminho Base não informado.');
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoCadastrar() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoAlterar() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoConsultar() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoListar() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoSelecionar() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoExcluir() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoDesativar() != 'S' && $objRecursoPadraoDTO->getStrSinAcaoReativar() != 'S') {
        $objInfraException->adicionarValidacao('Nenhuma ação solicitada para geração.');
      }

      $objInfraException->lancarValidacoes();

      //tratamento para verificar se foi informado mais de uma entidade (separador é vírgula ',')
      $arrStrEntidade = explode(',', str_replace(' ', '', $objRecursoPadraoDTO->getStrEntidade()));

      $arrObjRecursoDTO = array();
      if ($objRecursoPadraoDTO->getStrSinAcaoCadastrar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'cadastrar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoAlterar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'alterar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoConsultar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'consultar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoListar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'listar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoSelecionar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'selecionar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoExcluir() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'excluir')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoDesativar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'desativar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      if ($objRecursoPadraoDTO->getStrSinAcaoReativar() == 'S') {
        foreach ($arrStrEntidade as $strEntidade) {
          if (InfraString::isBolVazia($strEntidade)) {
            continue;
          }
          $objRecursoPadraoDTO->setStrEntidade($strEntidade);
          if (($objRecursoDTO = $this->gerarRecursoPadrao($objRecursoPadraoDTO, 'reativar')) != null) {
            $arrObjRecursoDTO[] = $objRecursoDTO;
          }
        }
      }

      $objInfraException->lancarValidacoes();

      return $arrObjRecursoDTO;
      //Auditoria
    } catch (Exception $e) {
      throw new InfraException('Erro gerando Recursos Padrão.', $e);
    }
  }

  private function gerarRecursoPadrao($objRecursoPadraoDTO, $strAcaoPadrao) {
    $objRecursoDTO = new RecursoDTO();
    $objRecursoDTO->setNumIdSistema($objRecursoPadraoDTO->getNumIdSistema());
    $objRecursoDTO->setStrNome($objRecursoPadraoDTO->getStrEntidade() . '_' . $strAcaoPadrao);
    if ($this->contar($objRecursoDTO) == 0) {
      $objRecursoDTO->setStrDescricao(null);
      $objRecursoDTO->setStrCaminho($objRecursoPadraoDTO->getStrCaminhoBase() . $objRecursoDTO->getStrNome());
      $objRecursoDTO->setStrSinAtivo('S');
      return $this->cadastrar($objRecursoDTO);
    }
    return null;
  }

  protected function cadastrarControlado(RecursoDTO $objRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('recurso_cadastrar', __METHOD__, $objRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdSistema($objRecursoDTO, $objInfraException);
      $this->validarStrNome($objRecursoDTO, $objInfraException);
      $this->validarStrDescricao($objRecursoDTO, $objInfraException);
      $this->validarStrCaminho($objRecursoDTO, $objInfraException);
      $this->validarStrSinAtivo($objRecursoDTO, $objInfraException);


      $objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      $ret = $objRecursoBD->cadastrar($objRecursoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Recurso.', $e);
    }
  }

  protected function alterarControlado(RecursoDTO $objRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('recurso_alterar', __METHOD__, $objRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objRecursoDTOBanco = new RecursoDTO();
      $objRecursoDTOBanco->setBolExclusaoLogica(false);
      $objRecursoDTOBanco->retNumIdSistema();
      $objRecursoDTOBanco->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
      $objRecursoDTOBanco = $this->consultar($objRecursoDTOBanco);

      if ($objRecursoDTOBanco == null) {
        throw new InfraException('Recurso não encontrado [' . $objRecursoDTO->getNumIdRecurso() . '].');
      }

      if ($objRecursoDTO->isSetNumIdSistema()) {
        $this->validarNumIdSistema($objRecursoDTO, $objInfraException);
      } else {
        $objRecursoDTO->setNumIdSistema($objRecursoDTOBanco->getNumIdSistema());
      }

      if ($objRecursoDTO->isSetStrNome()) {
        $this->validarStrNome($objRecursoDTO, $objInfraException);
      }

      if ($objRecursoDTO->isSetStrDescricao()) {
        $this->validarStrDescricao($objRecursoDTO, $objInfraException);
      }

      if ($objRecursoDTO->isSetStrCaminho()) {
        $this->validarStrCaminho($objRecursoDTO, $objInfraException);
      }

      if ($objRecursoDTO->isSetStrSinAtivo()) {
        $this->validarStrSinAtivo($objRecursoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      $objRecursoBD->alterar($objRecursoDTO);
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Recurso.', $e);
    }
  }

  protected function excluirControlado($arrObjRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('recurso_excluir', __METHOD__, $arrObjRecursoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();


      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());

      $objRelPerfilRecursoDTO = new RelPerfilRecursoDTO(true);
      $objRelPerfilRecursoRN = new RelPerfilRecursoRN();

      $objItemMenuDTO = new ItemMenuDTO();
      $objItemMenuRN = new ItemMenuRN();
      $objRelRegraAuditoriaRecursoRN = new RelRegraAuditoriaRecursoRN();

      for ($i = 0; $i < count($arrObjRecursoDTO); $i++) {
        $objRecursoDTO = new RecursoDTO();
        $objRecursoDTO->retNumIdRecurso();
        $objRecursoDTO->retStrNome();
        $objRecursoDTO->setNumIdRecurso($arrObjRecursoDTO[$i]->getNumIdRecurso());
        $objRecursoDTO->setBolExclusaoLogica(false);
        $objRecursoDTO = $this->consultar($objRecursoDTO);


        //Verifica se o recurso esta em algum perfil
        $objRelPerfilRecursoDTO->retTodos();
        $objRelPerfilRecursoDTO->setNumIdRecurso($objRecursoDTO->getNumIdRecurso());
        $arrObjRelPerfilRecursoDTO = $objRelPerfilRecursoRN->listar($objRelPerfilRecursoDTO);
        foreach ($arrObjRelPerfilRecursoDTO as $dto) {
          $objInfraException->adicionarValidacao('O recurso \'' . $objRecursoDTO->getStrNome() . '\' esta associado ao perfil \'' . $dto->getStrNomePerfil() . '\'.');
        }

        //Exclui os itens de menu que apontam para o recurso
        $objItemMenuDTO->retTodos();
        $objItemMenuDTO->setNumIdRecurso($arrObjRecursoDTO[$i]->getNumIdRecurso());
        $objItemMenuDTO->setBolExclusaoLogica(false);
        $arrObjItemMenuDTO = $objItemMenuRN->listar($objItemMenuDTO);
        //$objItemMenuRN->excluir($arrObjItemMenuDTO);
        foreach ($arrObjItemMenuDTO as $dto) {
          $objInfraException->adicionarValidacao('O recurso \'' . $objRecursoDTO->getStrNome() . '\' esta associado ao item de menu \'' . $dto->getStrRotulo() . '\'.');
        }


        $objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
        $objRelRegraAuditoriaRecursoDTO->retStrDescricaoRegraAuditoria();
        $objRelRegraAuditoriaRecursoDTO->setNumIdRecurso($arrObjRecursoDTO[$i]->getNumIdRecurso());

        $arrObjRelRegraAuditoriaRecursoDTO = $objRelRegraAuditoriaRecursoRN->listar($objRelRegraAuditoriaRecursoDTO);
        foreach ($arrObjRelRegraAuditoriaRecursoDTO as $dto) {
          $objInfraException->adicionarValidacao('O recurso \'' . $objRecursoDTO->getStrNome() . '\' esta associado a regra de auditoria \'' . $dto->getStrDescricaoRegraAuditoria() . '\'.');
        }

        $objInfraException->lancarValidacoes();

        //Exclui o recurso
        $objRecursoBD->excluir($arrObjRecursoDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Recurso.', $e);
    }
  }

  protected function desativarControlado($arrObjRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('recurso_desativar', __METHOD__, $arrObjRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRecursoDTO); $i++) {
        $objRecursoBD->desativar($arrObjRecursoDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro desativando Recurso.', $e);
    }
  }

  protected function reativarControlado($arrObjRecursoDTO) {
    try {
      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('recurso_reativar', __METHOD__, $arrObjRecursoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjRecursoDTO); $i++) {
        $objRecursoBD->reativar($arrObjRecursoDTO[$i]);
      }
      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro reativando Recurso.', $e);
    }
  }

  protected function consultarConectado(RecursoDTO $objRecursoDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('recurso_consultar',__METHOD__,$objRecursoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      $ret = $objRecursoBD->consultar($objRecursoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Recurso.', $e);
    }
  }

  protected function listarConectado(RecursoDTO $objRecursoDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('recurso_listar',__METHOD__,$objRecursoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      $ret = $objRecursoBD->listar($objRecursoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Recursos.', $e);
    }
  }

  protected function listarMontarConectado(MontarPerfilDTO $objMontarPerfilDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('recurso_listar',__METHOD__,$objMontarPerfilDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      $ret = $objRecursoBD->listar($objMontarPerfilDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando recursos para montagem de perfil.', $e);
    }
  }

  protected function contarConectado(RecursoDTO $objRecursoDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('recurso_contar',__METHOD__,$objRecursoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRecursoBD = new RecursoBD($this->getObjInfraIBanco());
      $ret = $objRecursoBD->contar($objRecursoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Recursos.', $e);
    }
  }

  protected function listarAdministradosConectado(RecursoDTO $objRecursoDTO) {
    try {
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('recurso_listar',__METHOD__,$objRecursoDTO);
      /////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();


      //Retorna o ID para fechar com os sistemas administrados
      $objRecursoDTO->retNumIdSistema();

      $arrObjRecursoDTO = $this->listar($objRecursoDTO);

      //Obtem sistemas acessados pelo usuario
      $objAcessoDTO = new AcessoDTO();
      $objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR);
      $objAcessoRN = new AcessoRN();
      $arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

      $ret = InfraArray::joinArrInfraDTO($arrObjRecursoDTO, 'IdSistema', $arrObjAcessoDTO, 'IdSistema');

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Recursos administrados.', $e);
    }
  }

  private function validarNumIdSistema(RecursoDTO $objRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRecursoDTO->getNumIdSistema())) {
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarStrNome(RecursoDTO $objRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRecursoDTO->getStrNome())) {
      $objInfraException->adicionarValidacao('Nome não informado.');
    }
    $objRecursoDTO->setStrNome(trim($objRecursoDTO->getStrNome()));

    if (strlen($objRecursoDTO->getStrNome()) > 100) {
      $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
    }

    $dto = new RecursoDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->setNumIdSistema($objRecursoDTO->getNumIdSistema());
    if ($objRecursoDTO->isSetNumIdRecurso() && $objRecursoDTO->getNumIdRecurso() != null) {
      $dto->setNumIdRecurso($objRecursoDTO->getNumIdRecurso(), InfraDTO::$OPER_DIFERENTE);
    }
    $dto->setStrNome($objRecursoDTO->getStrNome());
    $dto = $this->consultar($dto);

    if ($dto != null) {
      if ($dto->getStrSinAtivo() == 'S') {
        $objInfraException->adicionarValidacao('Já existe um recurso com este nome.');
      } else {
        $objInfraException->adicionarValidacao('Existe um recurso inativo com este nome.');
      }
    }
  }

  private function validarStrDescricao(RecursoDTO $objRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRecursoDTO->getStrDescricao())) {
      $objRecursoDTO->setStrDescricao(null);
    }
    $objRecursoDTO->setStrDescricao(trim($objRecursoDTO->getStrDescricao()));

    if (strlen($objRecursoDTO->getStrDescricao()) > 200) {
      $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 200 caracteres.');
    }
  }

  private function validarStrCaminho(RecursoDTO $objRecursoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objRecursoDTO->getStrCaminho())) {
      $objInfraException->adicionarValidacao('Caminho não informado.');
    }
    $objRecursoDTO->setStrCaminho(trim($objRecursoDTO->getStrCaminho()));

    if (strlen($objRecursoDTO->getStrCaminho()) > 255) {
      $objInfraException->adicionarValidacao('Caminho possui tamanho superior a 255 caracteres.');
    }
  }

  private function validarStrSinAtivo(RecursoDTO $objRecursoDTO, InfraException $objInfraException) {
    if ($objRecursoDTO->getStrSinAtivo() === null || ($objRecursoDTO->getStrSinAtivo() !== 'S' && $objRecursoDTO->getStrSinAtivo() !== 'N')) {
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

}

?>