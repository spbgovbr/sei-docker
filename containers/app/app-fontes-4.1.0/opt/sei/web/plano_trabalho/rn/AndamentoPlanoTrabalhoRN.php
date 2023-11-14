<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/10/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class AndamentoPlanoTrabalhoRN extends InfraRN {

  public static $SA_EM_ANDAMENTO = '1';
  public static $SA_PAUSADO = '2';
  public static $SA_FINALIZADO = '3';
  public static $SA_PROBLEMA = '4';
  public static $SA_NAO_SE_APLICA = '5';
  public static $SA_DESCONSIDERADO = '6';

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  public function listarValoresSituacao() {
    try {
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO = array();

      $objSituacaoAndamentoPlanoTrabalhoDTO = new SituacaoAndamentoPlanoTrabalhoDTO();
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrStaSituacao(self::$SA_EM_ANDAMENTO);
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrDescricao('Em andamento');
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrIcone(Icone::ANDAMENTO_PLANO_TRABALHO_EM_ANDAMENTO);
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO[] = $objSituacaoAndamentoPlanoTrabalhoDTO;

      $objSituacaoAndamentoPlanoTrabalhoDTO = new SituacaoAndamentoPlanoTrabalhoDTO();
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrStaSituacao(self::$SA_PAUSADO);
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrDescricao('Pausado');
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrIcone(Icone::ANDAMENTO_PLANO_TRABALHO_PAUSADO);
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO[] = $objSituacaoAndamentoPlanoTrabalhoDTO;

      $objSituacaoAndamentoPlanoTrabalhoDTO = new SituacaoAndamentoPlanoTrabalhoDTO();
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrStaSituacao(self::$SA_FINALIZADO);
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrDescricao('Finalizado');
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrIcone(Icone::ANDAMENTO_PLANO_TRABALHO_FINALIZADO);
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO[] = $objSituacaoAndamentoPlanoTrabalhoDTO;

      $objSituacaoAndamentoPlanoTrabalhoDTO = new SituacaoAndamentoPlanoTrabalhoDTO();
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrStaSituacao(self::$SA_PROBLEMA);
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrDescricao('Problema');
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrIcone(Icone::ANDAMENTO_PLANO_TRABALHO_PROBLEMA);
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO[] = $objSituacaoAndamentoPlanoTrabalhoDTO;

      $objSituacaoAndamentoPlanoTrabalhoDTO = new SituacaoAndamentoPlanoTrabalhoDTO();
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrStaSituacao(self::$SA_NAO_SE_APLICA);
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrDescricao('Não se aplica');
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrIcone(Icone::ANDAMENTO_PLANO_TRABALHO_NAO_SE_APLICA);
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO[] = $objSituacaoAndamentoPlanoTrabalhoDTO;

      $objSituacaoAndamentoPlanoTrabalhoDTO = new SituacaoAndamentoPlanoTrabalhoDTO();
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrStaSituacao(self::$SA_DESCONSIDERADO);
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrDescricao('Desconsiderado');
      $objSituacaoAndamentoPlanoTrabalhoDTO->setStrIcone(Icone::ANDAMENTO_PLANO_TRABALHO_DESCONSIDERADO);
      $arrObjSituacaoAndamentoPlanoTrabalhoDTO[] = $objSituacaoAndamentoPlanoTrabalhoDTO;

      return $arrObjSituacaoAndamentoPlanoTrabalhoDTO;
    } catch (Exception $e) {
      throw new InfraException('Erro listando valores de Situacao.', $e);
    }
  }

  private function validarNumIdPlanoTrabalho(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getNumIdPlanoTrabalho())) {
      $objInfraException->adicionarValidacao('Plano de Trabalho não informado.');
    }
  }

  private function validarNumIdEtapaTrabalho(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getNumIdEtapaTrabalho())) {
      $objInfraException->adicionarValidacao('Etapa de Trabalho não informada.');
    }
  }

  private function validarNumIdItemEtapa(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getNumIdItemEtapa())) {
      $objInfraException->adicionarValidacao('Item da Etapa não informado.');
    }
  }

  private function validarDblIdProcedimento(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getDblIdProcedimento())) {
      $objInfraException->adicionarValidacao('Processo não informado.');
    }
  }

  private function validarNumIdTarefaPlanoTrabalho(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getNumIdTarefaPlanoTrabalho())) {
      $objInfraException->adicionarValidacao('Tarefa do Plano de Trabalho não informada.');
    }
  }

  private function validarNumIdUsuarioOrigem(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getNumIdUsuarioOrigem())) {
      $objInfraException->adicionarValidacao('Usuário de Origem não informado.');
    }
  }

  private function validarNumIdUnidadeOrigem(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getNumIdUnidadeOrigem())) {
      $objInfraException->adicionarValidacao('Unidade de Origem não informada.');
    }
  }

  private function validarDthExecucao(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getDthExecucao())) {
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    } else {
      if (!InfraData::validarDataHora($objAndamentoPlanoTrabalhoDTO->getDthExecucao())) {
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

  private function validarStrStaSituacao(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAndamentoPlanoTrabalhoDTO->getStrStaSituacao())) {
      $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao(null);
    } else {
      if (!in_array($objAndamentoPlanoTrabalhoDTO->getStrStaSituacao(), InfraArray::converterArrInfraDTO($this->listarValoresSituacao(), 'StaSituacao'))) {
        $objInfraException->adicionarValidacao('Situação inválida.');
      }
    }
  }

  protected function cadastrarControlado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_cadastrar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPlanoTrabalho($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarDblIdProcedimento($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdTarefaPlanoTrabalho($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdUsuarioOrigem($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdUnidadeOrigem($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarDthExecucao($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarStrStaSituacao($objAndamentoPlanoTrabalhoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoPlanoTrabalhoBD->cadastrar($objAndamentoPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function alterarControlado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_alterar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAndamentoPlanoTrabalhoDTO->isSetNumIdPlanoTrabalho()) {
        $this->validarNumIdPlanoTrabalho($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objAndamentoPlanoTrabalhoDTO->isSetDblIdProcedimento()) {
        $this->validarDblIdProcedimento($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objAndamentoPlanoTrabalhoDTO->isSetNumIdTarefaPlanoTrabalho()) {
        $this->validarNumIdTarefaPlanoTrabalho($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objAndamentoPlanoTrabalhoDTO->isSetNumIdUsuarioOrigem()) {
        $this->validarNumIdUsuarioOrigem($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objAndamentoPlanoTrabalhoDTO->isSetNumIdUnidadeOrigem()) {
        $this->validarNumIdUnidadeOrigem($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objAndamentoPlanoTrabalhoDTO->isSetDthExecucao()) {
        $this->validarDthExecucao($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }
      if ($objAndamentoPlanoTrabalhoDTO->isSetStrSituacao()) {
        $this->validarStrStaSituacao($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      $objAndamentoPlanoTrabalhoBD->alterar($objAndamentoPlanoTrabalhoDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function excluirControlado($arrObjAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_excluir', __METHOD__, $arrObjAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabRN = new AtributoAndamPlanoTrabRN();

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjAndamentoPlanoTrabalhoDTO); $i++) {
        $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
        $objAtributoAndamPlanoTrabDTO->retNumIdAtributoAndamPlanoTrab();
        $objAtributoAndamPlanoTrabDTO->setNumIdAndamentoPlanoTrabalho($arrObjAndamentoPlanoTrabalhoDTO[$i]->getNumIdAndamentoPlanoTrabalho());
        $objAtributoAndamPlanoTrabRN->excluir($objAtributoAndamPlanoTrabRN->listar($objAtributoAndamPlanoTrabDTO));

        $objAndamentoPlanoTrabalhoBD->excluir($arrObjAndamentoPlanoTrabalhoDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function consultarConectado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_consultar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoPlanoTrabalhoBD->consultar($objAndamentoPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function listarConectado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_listar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoPlanoTrabalhoBD->listar($objAndamentoPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Andamentos do Plano de Trabalho.', $e);
    }
  }

  protected function contarConectado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_listar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoPlanoTrabalhoBD->contar($objAndamentoPlanoTrabalhoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Andamentos do Plano de Trabalho.', $e);
    }
  }

  /*
    protected function desativarControlado($arrObjAndamentoPlanoTrabalhoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_desativar', __METHOD__, $arrObjAndamentoPlanoTrabalhoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjAndamentoPlanoTrabalhoDTO);$i++){
          $objAndamentoPlanoTrabalhoBD->desativar($arrObjAndamentoPlanoTrabalhoDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Andamento do Plano de Trabalho.',$e);
      }
    }

    protected function reativarControlado($arrObjAndamentoPlanoTrabalhoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_reativar', __METHOD__, $arrObjAndamentoPlanoTrabalhoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjAndamentoPlanoTrabalhoDTO);$i++){
          $objAndamentoPlanoTrabalhoBD->reativar($arrObjAndamentoPlanoTrabalhoDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Andamento do Plano de Trabalho.',$e);
      }
    }

    protected function bloquearControlado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_consultar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
        $ret = $objAndamentoPlanoTrabalhoBD->bloquear($objAndamentoPlanoTrabalhoDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Andamento do Plano de Trabalho.',$e);
      }
    }

   */

  protected function lancarControlado(AndamentoPlanoTrabalhoDTO $objAndamentoPlanoTrabalhoDTO) {
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_plano_trabalho_lancar', __METHOD__, $objAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPlanoTrabalho($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdTarefaPlanoTrabalho($objAndamentoPlanoTrabalhoDTO, $objInfraException);

      if ($objAndamentoPlanoTrabalhoDTO->isSetStrStaSituacao()) {
        $this->validarStrStaSituacao($objAndamentoPlanoTrabalhoDTO, $objInfraException);
      } else {
        $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao(null);
      }

      $objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoDTO->setDthExecucao(InfraData::getStrDataHoraAtual());
      $objAndamentoPlanoTrabalhoDTO->setNumIdUsuarioOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAndamentoPlanoTrabalhoDTO->setNumIdUnidadeOrigem(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objAndamentoPlanoTrabalhoBD = new AndamentoPlanoTrabalhoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoPlanoTrabalhoBD->cadastrar($objAndamentoPlanoTrabalhoDTO);


      if ($objAndamentoPlanoTrabalhoDTO->isSetArrObjAtributoAndamPlanoTrabDTO()) {
        $objAtributoAndamPlanoTrabRN = new AtributoAndamPlanoTrabRN();

        foreach ($objAndamentoPlanoTrabalhoDTO->getArrObjAtributoAndamPlanoTrabDTO() as $objAtributoAndamPlanoTrabDTO) {
          $objAtributoAndamPlanoTrabDTO->setNumIdAndamentoPlanoTrabalho($ret->getNumIdAndamentoPlanoTrabalho());
          $objAtributoAndamPlanoTrabRN->cadastrar($objAtributoAndamPlanoTrabDTO);
        }
      }

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro lançando Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function atualizarItemControlado(AndamentoPlanoTrabalhoDTO $parObjAndamentoPlanoTrabalhoDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('item_etapa_atualizar_andamento', __METHOD__, $parObjAndamentoPlanoTrabalhoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPlanoTrabalho($parObjAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdEtapaTrabalho($parObjAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarNumIdItemEtapa($parObjAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarDblIdProcedimento($parObjAndamentoPlanoTrabalhoDTO, $objInfraException);
      $this->validarStrStaSituacao($parObjAndamentoPlanoTrabalhoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAndamentoPlanoTrabalhoDTO = new AndamentoPlanoTrabalhoDTO();
      $objAndamentoPlanoTrabalhoDTO->setNumIdPlanoTrabalho($parObjAndamentoPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      $objAndamentoPlanoTrabalhoDTO->setDblIdProcedimento($parObjAndamentoPlanoTrabalhoDTO->getDblIdProcedimento());
      $objAndamentoPlanoTrabalhoDTO->setNumIdTarefaPlanoTrabalho(TarefaPlanoTrabalhoRN::$TPT_ATUALIZACAO_ITEM_ETAPA);
      $objAndamentoPlanoTrabalhoDTO->setStrStaSituacao($parObjAndamentoPlanoTrabalhoDTO->getStrStaSituacao());

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setStrChave('ITEM_ETAPA');
      $objAtributoAndamPlanoTrabDTO->setStrValor(null);
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($parObjAndamentoPlanoTrabalhoDTO->getNumIdItemEtapa());
      $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setStrChave('ETAPA_TRABALHO');
      $objAtributoAndamPlanoTrabDTO->setStrValor(null);
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem($parObjAndamentoPlanoTrabalhoDTO->getNumIdEtapaTrabalho());
      $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

      $objAtributoAndamPlanoTrabDTO = new AtributoAndamPlanoTrabDTO();
      $objAtributoAndamPlanoTrabDTO->setStrChave('DESCRICAO');
      $objAtributoAndamPlanoTrabDTO->setStrValor($parObjAndamentoPlanoTrabalhoDTO->getStrDescricao());
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem(null);
      $arrObjAtributoAndamPlanoTrabDTO[] = $objAtributoAndamPlanoTrabDTO;

      $objAndamentoPlanoTrabalhoDTO->setArrObjAtributoAndamPlanoTrabDTO($arrObjAtributoAndamPlanoTrabDTO);

      $objAndamentoPlanoTrabalhoRN = new AndamentoPlanoTrabalhoRN();
      $objAndamentoPlanoTrabalhoRN->lancar($objAndamentoPlanoTrabalhoDTO);

      if ($parObjAndamentoPlanoTrabalhoDTO->isSetArrObjRelItemEtapaDocumentoDTO()) {
        $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
        $objRelItemEtapaDocumentoDTO->retNumIdItemEtapa();
        $objRelItemEtapaDocumentoDTO->retDblIdDocumento();
        $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($parObjAndamentoPlanoTrabalhoDTO->getNumIdItemEtapa());
        $objRelItemEtapaDocumentoDTO->setNumIdUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objRelItemEtapaDocumentoDTO->setDblIdProcedimentoDocumento($parObjAndamentoPlanoTrabalhoDTO->getDblIdProcedimento());

        $objRelItemEtapaDocumentoRN = new RelItemEtapaDocumentoRN();
        $arrAntigos = InfraArray::converterArrInfraDTO($objRelItemEtapaDocumentoRN->listar($objRelItemEtapaDocumentoDTO), 'IdDocumento');
        $arrNovos = InfraArray::converterArrInfraDTO($parObjAndamentoPlanoTrabalhoDTO->getArrObjRelItemEtapaDocumentoDTO(), 'IdDocumento');

        $arrRemocao = array();
        foreach ($arrAntigos as $dblIdDocumentoAntigo) {
          if (!in_array($dblIdDocumentoAntigo, $arrNovos)) {
            $arrRemocao[] = $dblIdDocumentoAntigo;
          }
        }

        foreach ($arrRemocao as $dblIdDocumentoRemocao) {
          $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
          $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($parObjAndamentoPlanoTrabalhoDTO->getNumIdItemEtapa());
          $objRelItemEtapaDocumentoDTO->setDblIdDocumento($dblIdDocumentoRemocao);
          $objRelItemEtapaDocumentoRN->excluir(array($objRelItemEtapaDocumentoDTO));
        }

        foreach ($arrNovos as $dblIdDocumentoNovo) {
          if (!in_array($dblIdDocumentoNovo, $arrAntigos)) {
            $objRelItemEtapaDocumentoDTO = new RelItemEtapaDocumentoDTO();
            $objRelItemEtapaDocumentoDTO->setNumIdItemEtapa($parObjAndamentoPlanoTrabalhoDTO->getNumIdItemEtapa());
            $objRelItemEtapaDocumentoDTO->setDblIdDocumento($dblIdDocumentoNovo);
            $objRelItemEtapaDocumentoRN->cadastrar($objRelItemEtapaDocumentoDTO);
          }
        }
      }
    } catch (Exception $e) {
      throw new InfraException('Erro atualizando Item da Etapa.', $e);
    }
  }
}
