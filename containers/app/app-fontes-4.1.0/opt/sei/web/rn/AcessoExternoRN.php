<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/06/2010 - criado por fazenda_db
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class AcessoExternoRN extends InfraRN
{

  public static $TA_INTERESSADO = 'I';
  public static $TA_USUARIO_EXTERNO = 'E';
  public static $TA_DESTINATARIO_ISOLADO = 'D';
  public static $TA_SISTEMA = 'S';
  public static $TA_ASSINATURA_EXTERNA = 'A';

  public static $TV_INTEGRAL = 'I';
  public static $TV_PARCIAL = 'P';
  public static $TV_NENHUM = 'N';

  public function __construct()
  {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  private function validarNumIdAtividade(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getNumIdAtividade())) {
      $objInfraException->adicionarValidacao('Atividade não informado.');
    }
  }

  private function validarNumIdParticipante(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getNumIdParticipante())) {
      $objInfraException->adicionarValidacao('Interessado não informado.');
    }
  }

  private function validarNumIdUsuarioExterno(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getNumIdUsuarioExterno())) {
      $objInfraException->adicionarValidacao('Usuário Externo não informado.');
    }
  }

  private function validarDblIdProtocoloAtividade(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getDblIdProtocoloAtividade())) {
      $objInfraException->adicionarValidacao('Processo não informado.');
    }
  }

  private function validarNumIdContatoParticipante(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getNumIdContatoParticipante())) {
      $objInfraException->adicionarValidacao('Contato não informado.');
    }
  }

  private function validarDblIdDocumento(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getDblIdDocumento())) {
      $objInfraException->adicionarValidacao('Documento não informado.');
    }
  }

  private function validarNumDias(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getNumDias())) {
      $objAcessoExternoDTO->setNumDias(null);
    } else {
      if ($objAcessoExternoDTO->getNumDias() <= 0) {
        $objInfraException->adicionarValidacao('Validade do acesso deve ser de pelo menos um dia.');
      }
    }
  }

  private function validarArrObjRelAcessoExtProtocolo(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {

    $arrObjRelAcessoExtProtocoloDTO = $objAcessoExternoDTO->getArrObjRelAcessoExtProtocoloDTO();

    if (InfraArray::contar($arrObjRelAcessoExtProtocoloDTO)) {

      $arrIdProtocolos = InfraArray::converterArrInfraDTO($arrObjRelAcessoExtProtocoloDTO, 'IdProtocolo');

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->setDblIdProtocolo($arrIdProtocolos, InfraDTO::$OPER_IN);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO), 'IdProtocolo');

      foreach ($arrObjRelAcessoExtProtocoloDTO as $objRelAcessoExtProtocoloDTO) {
        if (!isset($arrObjProtocoloDTO[$objRelAcessoExtProtocoloDTO->getDblIdProtocolo()])) {
          throw new InfraException('Protocolo ['.$objRelAcessoExtProtocoloDTO->getDblIdProtocolo().'] não encontrado.');
        }
        $objRelAcessoExtProtocoloDTO->setStrProtocoloFormatadoProtocolo($arrObjProtocoloDTO[$objRelAcessoExtProtocoloDTO->getDblIdProtocolo()]->getStrProtocoloFormatado());
      }

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objAcessoExternoDTO->getDblIdProtocoloAtividade());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(array(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO, RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO), InfraDTO::$OPER_IN);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      foreach ($arrObjRelAcessoExtProtocoloDTO as $objRelAcessoExtProtocoloDTO) {
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objRelAcessoExtProtocoloDTO->getDblIdProtocolo());
        if ($objRelProtocoloProtocoloRN->contarRN0843($objRelProtocoloProtocoloDTO) == 0) {
          throw new InfraException('Protocolo '.$objRelAcessoExtProtocoloDTO->getStrProtocoloFormatadoProtocolo().' não pode ser liberado para acesso externo no processo.');
        }
      }
    }
  }

  private function validarDtaValidade(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (!InfraString::isBolVazia($objAcessoExternoDTO->getDtaValidade())) {
      if (!InfraData::validarData($objAcessoExternoDTO->getDtaValidade())) {
        $objInfraException->adicionarValidacao('Data de validade inválida.');
      }
      if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objAcessoExternoDTO->getDtaValidade())<0){
        $objInfraException->adicionarValidacao('Data de validade não pode estar no passado.');
      }
    }
  }

  private function validarStrNome(AnexoDTO $objAnexoDTO, InfraException $objInfraException)
  {
    if(strtolower(substr($objAnexoDTO->getStrNome(), -4)) != '.pdf'){
      $objInfraException->adicionarValidacao('O documento '.$objAnexoDTO->getStrNome().' deve ser no formato PDF.');
    }
  }

  private function validarStrEmailUnidade(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrEmailUnidade())) {
      $objInfraException->adicionarValidacao('E-mail da Unidade não informado.');
    }
  }

  private function validarStrEmailDestinatario(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrEmailDestinatario())) {
      $objInfraException->adicionarValidacao('E-mail do Destinatário não informado.');
    } else {
      $objAcessoExternoDTO->setStrEmailDestinatario(trim($objAcessoExternoDTO->getStrEmailDestinatario()));

      if (strlen($objAcessoExternoDTO->getStrEmailDestinatario()) > 100) {
        $objInfraException->adicionarValidacao('E-mail do Destinatário possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrHashInterno(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrHashInterno())) {
      $objInfraException->adicionarValidacao('HASH Interno não informado.');
    } else {
      $objAcessoExternoDTO->setStrHashInterno(trim($objAcessoExternoDTO->getStrHashInterno()));

      if (strlen($objAcessoExternoDTO->getStrHashInterno()) > 32) {
        $objInfraException->adicionarValidacao('HASH Interno possui tamanho superior a 32 caracteres.');
      }
    }
  }

  private function validarStrStaTipo(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrStaTipo())) {
      $objInfraException->adicionarValidacao('Tipo não informado.');
    } else {
      if (!in_array($objAcessoExternoDTO->getStrStaTipo(), InfraArray::converterArrInfraDTO($this->listarValoresTipoAcessoExterno(), 'StaTipo'))) {
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarStrSenha(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrSenha())) {
      $objInfraException->adicionarValidacao('Senha não informada.');
    }
  }

  private function validarStrMotivo(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrMotivo())) {
      $objInfraException->adicionarValidacao('Motivo não informado.');
    }
  }

  private function validarStrSinProcesso(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {

    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrSinProcesso())) {
      $objInfraException->adicionarValidacao('Sinalizador de acesso ao processo não informado.');
    } else {
      if (!InfraUtil::isBolSinalizadorValido($objAcessoExternoDTO->getStrSinProcesso())) {
        $objInfraException->adicionarValidacao('Sinalizador de acesso ao processo inválido.');
      }
    }
  }

  private function validarStrSinInclusao(AcessoExternoDTO $objAcessoExternoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objAcessoExternoDTO->getStrSinInclusao())) {
      $objInfraException->adicionarValidacao('Sinalizador de inclusão de documentos não informado.');
    } else  if (!InfraUtil::isBolSinalizadorValido($objAcessoExternoDTO->getStrSinInclusao())) {
      $objInfraException->adicionarValidacao('Sinalizador de inclusão de documentos inválido.');
    }else if($objAcessoExternoDTO->getStrSinInclusao() == "S"){
      if(!$objAcessoExternoDTO->isSetArrObjRelAcessoExtSerieDTO() || $objAcessoExternoDTO->getArrObjRelAcessoExtSerieDTO() == null || InfraArray::contar($objAcessoExternoDTO->getArrObjRelAcessoExtSerieDTO()) == 0){
        $objInfraException->adicionarValidacao('Nenhum tipo de documento informado para inclusão.');
      }
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      if ($objInfraParametro->getValor('SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO') != '1'){
        $objInfraException->adicionarValidacao('Não é permitida a liberação de acesso externo com possibilidade de inclusão de documentos.');
      }else if ($objAcessoExternoDTO->getStrStaTipo() != self::$TA_USUARIO_EXTERNO && $objAcessoExternoDTO->getStrStaTipo() != self::$TA_ASSINATURA_EXTERNA) {
        $objInfraException->adicionarValidacao('Apenas Usuários Externos permitem o sinalizador de Inclusão de Documentos.');
      }
    }
  }

  public function cadastrar(AcessoExternoDTO $objAcessoExternoDTO){
    try{

      MailSEI::getInstance()->limpar();

      $ret = $this->cadastrarInterno($objAcessoExternoDTO);

      MailSEI::getInstance()->enviar();

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Acesso Externo.', $e);
    }
  }

  protected function cadastrarInternoControlado(AcessoExternoDTO $objAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      $objAcessoExternoDTOAuditoria = clone($objAcessoExternoDTO);
      $objAcessoExternoDTOAuditoria->unSetStrSenha();
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_cadastrar', __METHOD__, $objAcessoExternoDTOAuditoria);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAcessoExternoDTO->isSetStrSinInclusao()) {
        $this->validarStrSinInclusao($objAcessoExternoDTO, $objInfraException);
      }else{
        $objAcessoExternoDTO->setStrSinInclusao("N");
      }

      $this->validarStrStaTipo($objAcessoExternoDTO, $objInfraException);
      //$this->validarNumIdAtividade($objAcessoExternoDTO, $objInfraException);
      //$this->validarStrHashInterno($objAcessoExternoDTO, $objInfraException);

      if ($objAcessoExternoDTO->isSetArrObjRelAcessoExtProtocoloDTO()) {
        $this->validarArrObjRelAcessoExtProtocolo($objAcessoExternoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_INTERESSADO ||
          $objAcessoExternoDTO->getStrStaTipo() == self::$TA_USUARIO_EXTERNO ||
          $objAcessoExternoDTO->getStrStaTipo() == self::$TA_DESTINATARIO_ISOLADO
      ) {

        $this->validarStrEmailUnidade($objAcessoExternoDTO, $objInfraException);

        if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_INTERESSADO) {
          $this->validarNumIdParticipante($objAcessoExternoDTO, $objInfraException);
        } else if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_USUARIO_EXTERNO) {
          $this->validarNumIdUsuarioExterno($objAcessoExternoDTO, $objInfraException);
          $this->validarDblIdProtocoloAtividade($objAcessoExternoDTO, $objInfraException);
        } else {

          if (InfraString::isBolVazia($objAcessoExternoDTO->getNumIdContatoParticipante())) {
            if (InfraString::isBolVazia($objAcessoExternoDTO->getStrNomeContato())) {
              $objInfraException->adicionarValidacao('Destinatário não informado.');
            } else {
              $objContatoDTO = new ContatoDTO();
              $objContatoDTO->setStrNome($objAcessoExternoDTO->getStrNomeContato());
              $objContatoRN = new ContatoRN();
              $objContatoDTO = $objContatoRN->cadastrarContextoTemporario($objContatoDTO);
              $objAcessoExternoDTO->setNumIdContatoParticipante($objContatoDTO->getNumIdContato());
            }
          }
        }

        $this->validarStrEmailDestinatario($objAcessoExternoDTO, $objInfraException);
        //$this->validarDtaValidade($objAcessoExternoDTO, $objInfraException);
        $this->validarStrSenha($objAcessoExternoDTO, $objInfraException);
        $this->validarStrMotivo($objAcessoExternoDTO, $objInfraException);
        $this->validarNumDias($objAcessoExternoDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objAcessoExternoDTO->setDblIdDocumento(null);
        $objAcessoExternoDTO->setStrSinProcesso('S');

        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $objInfraSip->autenticar(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(),
            SessaoSEI::getInstance()->getNumIdContextoUsuario(),
            SessaoSEI::getInstance()->getStrSiglaUsuario(),
            $objAcessoExternoDTO->getStrSenha());

        if ($objAcessoExternoDTO->getNumDias() != null) {
          $objAcessoExternoDTO->setDtaValidade(InfraData::calcularData($objAcessoExternoDTO->getNumDias(), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE));
        }else{
          $objAcessoExternoDTO->setDtaValidade(null);
        }

        $objParticipanteRN = new ParticipanteRN();

        if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_USUARIO_EXTERNO) {

          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->retNumIdUsuario();
          $objUsuarioDTO->retNumIdContato();
          $objUsuarioDTO->retStrSigla();
          $objUsuarioDTO->retStrNome();
          $objUsuarioDTO->setNumIdUsuario($objAcessoExternoDTO->getNumIdUsuarioExterno());
          $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_EXTERNO);

          $objUsuarioRN = new UsuarioRN();
          $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);


          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->retNumIdParticipante();
          $objParticipanteDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
          $objParticipanteDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);

          $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

          if ($objParticipanteDTO == null) {
            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
            $objParticipanteDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);
            $objParticipanteDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objParticipanteDTO->setNumSequencia(0);
            $objParticipanteDTO = $objParticipanteRN->cadastrarRN0170($objParticipanteDTO);
          }

          $objAcessoExternoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
          $objAcessoExternoDTO->setStrEmailDestinatario($objUsuarioDTO->getStrSigla());

        } else if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_DESTINATARIO_ISOLADO) {

          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->retNumIdContato();
          $objContatoDTO->retStrNome();
          $objContatoDTO->setNumIdContato($objAcessoExternoDTO->getNumIdContatoParticipante());

          $objContatoRN = new ContatoRN();
          $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->retNumIdParticipante();
          $objParticipanteDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
          $objParticipanteDTO->setNumIdContato($objContatoDTO->getNumIdContato());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);

          $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

          if ($objParticipanteDTO == null) {
            $objParticipanteDTO = new ParticipanteDTO();
            $objParticipanteDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
            $objParticipanteDTO->setNumIdContato($objContatoDTO->getNumIdContato());
            $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);
            $objParticipanteDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objParticipanteDTO->setNumSequencia(0);
            $objParticipanteDTO = $objParticipanteRN->cadastrarRN0170($objParticipanteDTO);
          }

          $objAcessoExternoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
        }

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdParticipante();
        $objParticipanteDTO->retDblIdProtocolo();
        $objParticipanteDTO->retStrNomeContato();
        $objParticipanteDTO->setNumIdParticipante($objAcessoExternoDTO->getNumIdParticipante());
        $objParticipanteRN = new ParticipanteRN();
        $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DESTINATARIO_NOME');
        $objAtributoAndamentoDTO->setStrValor($objParticipanteDTO->getStrNomeContato());
        $objAtributoAndamentoDTO->setStrIdOrigem($objParticipanteDTO->getNumIdParticipante());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DESTINATARIO_EMAIL');
        $objAtributoAndamentoDTO->setStrValor($objAcessoExternoDTO->getStrEmailDestinatario());
        $objAtributoAndamentoDTO->setStrIdOrigem($objParticipanteDTO->getNumIdParticipante());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('MOTIVO');
        $objAtributoAndamentoDTO->setStrValor($objAcessoExternoDTO->getStrMotivo());
        $objAtributoAndamentoDTO->setStrIdOrigem($objAcessoExternoDTO->getNumIdParticipante());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        if($objAcessoExternoDTO->getNumDias() != null) {
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('VALIDADE');
          $objAtributoAndamentoDTO->setStrValor(" até ".$objAcessoExternoDTO->getDtaValidade()." (".$objAcessoExternoDTO->getNumDias().' '.($objAcessoExternoDTO->getNumDias() == 1 ? 'dia' : 'dias').")");
          $objAtributoAndamentoDTO->setStrIdOrigem(null);
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
        }

        $strTipoVisualizacao = self::$TV_INTEGRAL;
        if ($objAcessoExternoDTO->isSetArrObjRelAcessoExtProtocoloDTO() && InfraArray::contar($objAcessoExternoDTO->getArrObjRelAcessoExtProtocoloDTO())) {
          $strTipoVisualizacao = self::$TV_PARCIAL;
        }

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('VISUALIZACAO');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($strTipoVisualizacao);
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;


        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objParticipanteDTO->getDblIdProtocolo());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO);

        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $objAcessoExternoDTO->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());

      } else if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_ASSINATURA_EXTERNA) {

        $this->validarStrEmailUnidade($objAcessoExternoDTO, $objInfraException);
        $this->validarDblIdDocumento($objAcessoExternoDTO, $objInfraException);
        //$this->validarStrEmailDestinatario($objAcessoExternoDTO, $objInfraException);
        //$this->validarDtaValidade($objAcessoExternoDTO, $objInfraException);
        $this->validarStrSenha($objAcessoExternoDTO, $objInfraException);
        //$this->validarStrMotivo($objAcessoExternoDTO, $objInfraException);
        $this->validarNumDias($objAcessoExternoDTO, $objInfraException);
        $this->validarStrSinProcesso($objAcessoExternoDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objInfraSip = new InfraSip(SessaoSEI::getInstance());
        $objInfraSip->autenticar(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(),
            SessaoSEI::getInstance()->getNumIdContextoUsuario(),
            SessaoSEI::getInstance()->getStrSiglaUsuario(),
            $objAcessoExternoDTO->getStrSenha());

        if ($objAcessoExternoDTO->getNumDias() != null) {
          $objAcessoExternoDTO->setDtaValidade(InfraData::calcularData($objAcessoExternoDTO->getNumDias(), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE));
        }else{
          $objAcessoExternoDTO->setDtaValidade(null);
        }

        $objAcessoExternoDTO->setStrMotivo(null);

        //busca processo
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->retStrNomeSerie();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->retStrStaProtocoloProtocolo();
        $objDocumentoDTO->setDblIdDocumento($objAcessoExternoDTO->getDblIdDocumento());

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_EDITOR_INTERNO && $objDocumentoDTO->getStrStaDocumento() != DocumentoRN::$TD_FORMULARIO_GERADO) {
          $objInfraException->lancarValidacao('Somente documentos do editor interno ou formulários podem ser liberados para assinatura externa.');
        }

        //busca contato
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->retStrStaTipo();
        $objUsuarioDTO->retNumIdContato();
        $objUsuarioDTO->setNumIdUsuario($objAcessoExternoDTO->getNumIdUsuarioExterno());

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

        if ($objUsuarioDTO->getStrStaTipo() == UsuarioRN::$TU_EXTERNO_PENDENTE) {
          $objInfraException->lancarValidacao('Usuário externo "'.$objUsuarioDTO->getStrSigla().'" ainda não foi liberado.');
        }

        if ($objUsuarioDTO->getStrStaTipo() != UsuarioRN::$TU_EXTERNO) {
          $objInfraException->lancarValidacao('Usuário "'.$objUsuarioDTO->getStrSigla().'" não é um usuário externo.');
        }

        //verifica se o contato já é participante do processo
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdParticipante();
        $objParticipanteDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objParticipanteDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);

        $objParticipanteRN = new ParticipanteRN();
        $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

        if ($objParticipanteDTO == null) {

          $objParticipanteDTO = new ParticipanteDTO();
          $objParticipanteDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
          $objParticipanteDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
          $objParticipanteDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);
          $objParticipanteDTO->setNumSequencia(0);

          $objParticipanteDTO = $objParticipanteRN->cadastrarRN0170($objParticipanteDTO);
        } else {
          $dto = new AcessoExternoDTO();
          $dto->retStrSiglaContato();
          $dto->retDthAberturaAtividade();
          $dto->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
          $dto->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
          $dto->setStrStaTipo(AcessoExternoRN::$TA_ASSINATURA_EXTERNA);
          $dto->setNumMaxRegistrosRetorno(1);

          $dto = $this->consultar($dto);

          if ($dto != null) {
            $objInfraException->lancarValidacao('Usuário externo '.$dto->getStrSiglaContato().' já recebeu liberação para assinatura externa no documento '.$objDocumentoDTO->getStrProtocoloDocumentoFormatado().' em '.substr($dto->getDthAberturaAtividade(), 0, 16).'.');
          }
        }

        $objAcessoExternoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('USUARIO_EXTERNO_SIGLA');
        $objAtributoAndamentoDTO->setStrValor($objUsuarioDTO->getStrSigla());
        $objAtributoAndamentoDTO->setStrIdOrigem($objUsuarioDTO->getNumIdUsuario());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('USUARIO_EXTERNO_NOME');
        $objAtributoAndamentoDTO->setStrValor($objUsuarioDTO->getStrNome());
        $objAtributoAndamentoDTO->setStrIdOrigem($objUsuarioDTO->getNumIdUsuario());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
        $objAtributoAndamentoDTO->setStrValor($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objAtributoAndamentoDTO->setStrIdOrigem($objDocumentoDTO->getDblIdDocumento());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        if ($objAcessoExternoDTO->getStrSinProcesso() == 'S') {
          $strTipoVisualizacao = self::$TV_INTEGRAL;
        } else {
          $strTipoVisualizacao = self::$TV_NENHUM;
        }

        if ($objAcessoExternoDTO->isSetArrObjRelAcessoExtProtocoloDTO() && InfraArray::contar($objAcessoExternoDTO->getArrObjRelAcessoExtProtocoloDTO())) {
          $objAcessoExternoDTO->setStrSinProcesso('S');
          $strTipoVisualizacao = self::$TV_PARCIAL;
        }

        if($objAcessoExternoDTO->getNumDias() != null) {
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('VALIDADE');
          $objAtributoAndamentoDTO->setStrValor(" até ".$objAcessoExternoDTO->getDtaValidade()." (".$objAcessoExternoDTO->getNumDias().' '.($objAcessoExternoDTO->getNumDias() == 1 ? 'dia' : 'dias').")");
          $objAtributoAndamentoDTO->setStrIdOrigem(null);
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;
        }

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('VISUALIZACAO');
        $objAtributoAndamentoDTO->setStrValor(null);
        $objAtributoAndamentoDTO->setStrIdOrigem($strTipoVisualizacao);
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);


        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $objAcessoExternoDTO->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());

      } else if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_SISTEMA) {

        $this->validarNumIdParticipante($objAcessoExternoDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objAcessoExternoDTO->setDblIdDocumento(null);
        $objAcessoExternoDTO->setStrSinProcesso('S');
        $objAcessoExternoDTO->setStrEmailUnidade(null);
        $objAcessoExternoDTO->setStrEmailDestinatario(null);
        $objAcessoExternoDTO->setDtaValidade(null);

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retStrSiglaContato();
        $objParticipanteDTO->retStrNomeContato();
        $objParticipanteDTO->retDblIdProtocolo();
        $objParticipanteDTO->setNumIdParticipante($objAcessoExternoDTO->getNumIdParticipante());

        $objParticipanteRN = new ParticipanteRN();
        $objParticipanteDTO = $objParticipanteRN->consultarRN1008($objParticipanteDTO);

        $arrObjAtributoAndamentoDTO = array();
        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('INTERESSADO');
        $objAtributoAndamentoDTO->setStrValor($objParticipanteDTO->getStrSiglaContato().'¥'.$objParticipanteDTO->getStrNomeContato());
        $objAtributoAndamentoDTO->setStrIdOrigem($objAcessoExternoDTO->getNumIdParticipante());
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objParticipanteDTO->getDblIdProtocolo());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_ACESSO_EXTERNO_SISTEMA);
        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        $objAcessoExternoDTO->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());

      }

      //gera da mesma forma independente do tipo
      $objAcessoExternoDTO->setStrHashInterno(md5(random_bytes(32)));
      $objAcessoExternoDTO->setStrSinAtivo('S');


      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $ret = $objAcessoExternoBD->cadastrar($objAcessoExternoDTO);

      if ($objAcessoExternoDTO->isSetArrObjRelAcessoExtProtocoloDTO()) {

        $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();

        $arrObjRelAcessoExtProtocoloDTO = $objAcessoExternoDTO->getArrObjRelAcessoExtProtocoloDTO();
        foreach ($arrObjRelAcessoExtProtocoloDTO as $objRelAcessoExtProtocoloDTO) {
          $objRelAcessoExtProtocoloDTO->setNumIdAcessoExterno($ret->getNumIdAcessoExterno());
          $objRelAcessoExtProtocoloRN->cadastrar($objRelAcessoExtProtocoloDTO);
        }
      }

      if ($objAcessoExternoDTO->isSetArrObjRelAcessoExtSerieDTO()) {
        $objRelAcessoExtSerieRN = new RelAcessoExtSerieRN();
        $arrObjRelAcessoExtSerieDTO = $objAcessoExternoDTO->getArrObjRelAcessoExtSerieDTO();
        foreach ($arrObjRelAcessoExtSerieDTO as $objRelAcessoExtSerieDTO) {
          $objRelAcessoExtSerieDTO->setNumIdAcessoExterno($ret->getNumIdAcessoExterno());
          $objRelAcessoExtSerieRN->cadastrar($objRelAcessoExtSerieDTO);
        }
      }

      //ENVIAR EMAIL
      if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_INTERESSADO || $objAcessoExternoDTO->getStrStaTipo() == self::$TA_DESTINATARIO_ISOLADO) {

        $objEmailSistemaDTO = new EmailSistemaDTO();
        $objEmailSistemaDTO->retStrDe();
        $objEmailSistemaDTO->retStrPara();
        $objEmailSistemaDTO->retStrAssunto();
        $objEmailSistemaDTO->retStrConteudo();
        $objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_DISPONIBILIZACAO_ACESSO_EXTERNO);

        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);

        if ($objEmailSistemaDTO != null) {

          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->setDblIdProtocolo($objParticipanteDTO->getDblIdProtocolo());

          $objProtocoloRN = new ProtocoloRN();
          $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrDescricao();
          $objUnidadeDTO->retStrSiglaOrgao();
          $objUnidadeDTO->retStrDescricaoOrgao();
          $objUnidadeDTO->retStrSitioInternetOrgaoContato();
          $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

          $strDe = $objEmailSistemaDTO->getStrDe();
          $strDe = str_replace('@email_unidade@', $objAcessoExternoDTO->getStrEmailUnidade(), $strDe);

          $strPara = $objEmailSistemaDTO->getStrPara();
          $strPara = str_replace('@email_destinatario@', $objAcessoExternoDTO->getStrEmailDestinatario(), $strPara);

          $strAssunto = $objEmailSistemaDTO->getStrAssunto();
          $strAssunto = str_replace('@processo@', $objProtocoloDTO->getStrProtocoloFormatado(), $strAssunto);

          $strConteudo = $objEmailSistemaDTO->getStrConteudo();
          $strConteudo = str_replace('@processo@', $objProtocoloDTO->getStrProtocoloFormatado(), $strConteudo);
          $strConteudo = str_replace('@nome_destinatario@', $objParticipanteDTO->getStrNomeContato(), $strConteudo);
          if($objAcessoExternoDTO->getNumDias() != null) {
            $strConteudo = str_replace('@data_validade@', $objAcessoExternoDTO->getDtaValidade(), $strConteudo);
          }else{
            $strConteudo = str_replace('@data_validade@', "data indefinida", $strConteudo);
          }
          $strConteudo = str_replace('@link_acesso_externo@', SessaoSEIExterna::getInstance($ret->getNumIdAcessoExterno())->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL').'/processo_acesso_externo_consulta.php?id_acesso_externo='.$ret->getNumIdAcessoExterno()), $strConteudo);
          $strConteudo = str_replace('@sigla_unidade@', $objUnidadeDTO->getStrSigla(), $strConteudo);
          $strConteudo = str_replace('@descricao_unidade@', $objUnidadeDTO->getStrDescricao(), $strConteudo);
          $strConteudo = str_replace('@sigla_orgao@', $objUnidadeDTO->getStrSiglaOrgao(), $strConteudo);
          $strConteudo = str_replace('@descricao_orgao@', $objUnidadeDTO->getStrDescricaoOrgao(), $strConteudo);
          $strConteudo = str_replace('@sitio_internet_orgao@', $objUnidadeDTO->getStrSitioInternetOrgaoContato(), $strConteudo);

          $objEmailDTO = new EmailDTO();
          $objEmailDTO->setStrDe($strDe);
          $objEmailDTO->setStrPara($strPara);
          $objEmailDTO->setStrAssunto($strAssunto);
          $objEmailDTO->setStrMensagem($strConteudo);

          MailSEI::getInstance()->adicionar($objEmailDTO);
        }
      } else if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_USUARIO_EXTERNO) {

        $objEmailSistemaDTO = new EmailSistemaDTO();
        $objEmailSistemaDTO->retStrDe();
        $objEmailSistemaDTO->retStrPara();
        $objEmailSistemaDTO->retStrAssunto();
        $objEmailSistemaDTO->retStrConteudo();
        $objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_DISPONIBILIZACAO_ACESSO_EXTERNO_USUARIO_EXTERNO);

        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);

        if ($objEmailSistemaDTO != null) {
          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->setDblIdProtocolo($objParticipanteDTO->getDblIdProtocolo());

          $objProtocoloRN = new ProtocoloRN();
          $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retNumIdOrgao();
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrDescricao();
          $objUnidadeDTO->retStrSiglaOrgao();
          $objUnidadeDTO->retStrDescricaoOrgao();
          $objUnidadeDTO->retStrSitioInternetOrgaoContato();
          $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

          $strDe = $objEmailSistemaDTO->getStrDe();
          $strDe = str_replace('@email_unidade@', $objAcessoExternoDTO->getStrEmailUnidade(), $strDe);

          $strPara = $objEmailSistemaDTO->getStrPara();
          $strPara = str_replace('@email_usuario_externo@', $objUsuarioDTO->getStrSigla(), $strPara);

          $strAssunto = $objEmailSistemaDTO->getStrAssunto();
          $strAssunto = str_replace('@processo@', $objProtocoloDTO->getStrProtocoloFormatado(), $strAssunto);

          $strConteudo = $objEmailSistemaDTO->getStrConteudo();
          $strConteudo = str_replace('@processo@', $objProtocoloDTO->getStrProtocoloFormatado(), $strConteudo);
          $strConteudo = str_replace('@nome_usuario_externo@', $objUsuarioDTO->getStrNome(), $strConteudo);
          $strConteudo = str_replace('@email_usuario_externo@', $objUsuarioDTO->getStrSigla(), $strConteudo);
          $strConteudo = str_replace('@link_login_usuario_externo@', ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL').'/controlador_externo.php?acao=usuario_externo_logar&id_orgao_acesso_externo='.$objUnidadeDTO->getNumIdOrgao(), $strConteudo);

          $strConteudo = str_replace('@sigla_unidade@', $objUnidadeDTO->getStrSigla(), $strConteudo);
          $strConteudo = str_replace('@descricao_unidade@', $objUnidadeDTO->getStrDescricao(), $strConteudo);
          $strConteudo = str_replace('@sigla_orgao@', $objUnidadeDTO->getStrSiglaOrgao(), $strConteudo);
          $strConteudo = str_replace('@descricao_orgao@', $objUnidadeDTO->getStrDescricaoOrgao(), $strConteudo);
          $strConteudo = str_replace('@sitio_internet_orgao@', $objUnidadeDTO->getStrSitioInternetOrgaoContato(), $strConteudo);

          $objEmailDTO = new EmailDTO();
          $objEmailDTO->setStrDe($strDe);
          $objEmailDTO->setStrPara($strPara);
          $objEmailDTO->setStrAssunto($strAssunto);
          $objEmailDTO->setStrMensagem($strConteudo);

          MailSEI::getInstance()->adicionar($objEmailDTO);

        }
      } else if ($objAcessoExternoDTO->getStrStaTipo() == self::$TA_ASSINATURA_EXTERNA) {

        $objEmailSistemaDTO = new EmailSistemaDTO();
        $objEmailSistemaDTO->retStrDe();
        $objEmailSistemaDTO->retStrPara();
        $objEmailSistemaDTO->retStrAssunto();
        $objEmailSistemaDTO->retStrConteudo();
        $objEmailSistemaDTO->setNumIdEmailSistema(EmailSistemaRN::$ES_DISPONIBILIZACAO_ASSINATURA_EXTERNA_USUARIO_EXTERNO);

        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);

        if ($objEmailSistemaDTO != null) {

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retNumIdOrgao();
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrDescricao();
          $objUnidadeDTO->retStrSiglaOrgao();
          $objUnidadeDTO->retStrDescricaoOrgao();
          $objUnidadeDTO->retStrSitioInternetOrgaoContato();
          $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

          $strDe = $objEmailSistemaDTO->getStrDe();
          $strDe = str_replace('@email_unidade@', $objAcessoExternoDTO->getStrEmailUnidade(), $strDe);

          $strPara = $objEmailSistemaDTO->getStrPara();
          $strPara = str_replace('@email_usuario_externo@', $objUsuarioDTO->getStrSigla(), $strPara);

          $strAssunto = $objEmailSistemaDTO->getStrAssunto();
          $strAssunto = str_replace('@processo@', $objDocumentoDTO->getStrProtocoloProcedimentoFormatado(), $strAssunto);

          $strConteudo = $objEmailSistemaDTO->getStrConteudo();
          $strConteudo = str_replace('@processo@', $objDocumentoDTO->getStrProtocoloProcedimentoFormatado(), $strConteudo);
          $strConteudo = str_replace('@documento@', $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $strConteudo);
          $strConteudo = str_replace('@tipo_documento@', $objDocumentoDTO->getStrNomeSerie(), $strConteudo);
          $strConteudo = str_replace('@nome_usuario_externo@', $objUsuarioDTO->getStrNome(), $strConteudo);
          $strConteudo = str_replace('@email_usuario_externo@', $objUsuarioDTO->getStrSigla(), $strConteudo);
          $strConteudo = str_replace('@link_login_usuario_externo@', ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL').'/controlador_externo.php?acao=usuario_externo_logar&id_orgao_acesso_externo='.$objUnidadeDTO->getNumIdOrgao(), $strConteudo);
          $strConteudo = str_replace('@sigla_unidade@', $objUnidadeDTO->getStrSigla(), $strConteudo);
          $strConteudo = str_replace('@descricao_unidade@', $objUnidadeDTO->getStrDescricao(), $strConteudo);
          $strConteudo = str_replace('@sigla_orgao@', $objUnidadeDTO->getStrSiglaOrgao(), $strConteudo);
          $strConteudo = str_replace('@descricao_orgao@', $objUnidadeDTO->getStrDescricaoOrgao(), $strConteudo);
          $strConteudo = str_replace('@sitio_internet_orgao@', $objUnidadeDTO->getStrSitioInternetOrgaoContato(), $strConteudo);

          $objEmailDTO = new EmailDTO();
          $objEmailDTO->setStrDe($strDe);
          $objEmailDTO->setStrPara($strPara);
          $objEmailDTO->setStrAssunto($strAssunto);
          $objEmailDTO->setStrMensagem($strConteudo);

          MailSEI::getInstance()->adicionar($objEmailDTO);
        }
      }

      return $ret;

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Acesso Externo.', $e);
    }
  }

  protected function consultarProcessoAcessoExternoConectado(AcessoExternoDTO $parObjAcessoExternoDTO)
  {
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_listar', __METHOD__, $parObjAcessoExternoDTO);

      $bolTodosLiberados = false;
      $bolConsultandoDocumento = false;
      $arrIdLiberados = array();

      if ($parObjAcessoExternoDTO->isSetNumIdAcessoExterno()) {

        $objAcessoExternoDTO = new AcessoExternoDTO();
        $objAcessoExternoDTO->setBolExclusaoLogica(false);
        $objAcessoExternoDTO->retNumIdAcessoExterno();
        $objAcessoExternoDTO->retNumIdUnidade();
        $objAcessoExternoDTO->retStrSinAtivo();
        $objAcessoExternoDTO->retDblIdProtocoloAtividade();
        $objAcessoExternoDTO->retDblIdDocumento();
        $objAcessoExternoDTO->retStrSinProcesso();
        $objAcessoExternoDTO->retStrStaTipo();
        $objAcessoExternoDTO->retDthVisualizacao();
        $objAcessoExternoDTO->setNumIdAcessoExterno($parObjAcessoExternoDTO->getNumIdAcessoExterno());

        $objAcessoExternoDTO = $this->consultar($objAcessoExternoDTO);

        if ($objAcessoExternoDTO == null) {
          throw new InfraException('Disponibilização de Acesso Externo não encontrada.');
        }

        if ($objAcessoExternoDTO->getStrSinAtivo() == 'N') {
          throw new InfraException('Disponibilização de Acesso Externo cancelada.');
        }

        if (InfraString::isBolVazia($objAcessoExternoDTO->getDthVisualizacao())) {
          $objAcessoExternoDTO_Atualizacao = new AcessoExternoDTO();
          $objAcessoExternoDTO_Atualizacao->setNumIdAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());
          $objAcessoExternoDTO_Atualizacao->setDthVisualizacao(InfraData::getStrDataHoraAtual());
          $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
          $objAcessoExternoBD->alterar($objAcessoExternoDTO_Atualizacao);
        }

        $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
        $objRelAcessoExtProtocoloDTO->retDblIdProtocolo();
        $objRelAcessoExtProtocoloDTO->setNumIdAcessoExterno($parObjAcessoExternoDTO->getNumIdAcessoExterno());

        $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();
        $arrIdLiberados = InfraArray::converterArrInfraDTO($objRelAcessoExtProtocoloRN->listar($objRelAcessoExtProtocoloDTO), 'IdProtocolo');

        if (count($arrIdLiberados)) {
          $objAcessoExternoDTO->setStrSinParcial('S');

          $objDocumentoDTO_Pesquisa = new DocumentoDTO();
          $objDocumentoDTO_Pesquisa->retDblIdDocumento();
          $objDocumentoDTO_Pesquisa->setDblIdProcedimento($objAcessoExternoDTO->getDblIdProtocoloAtividade());
          $objDocumentoDTO_Pesquisa->setNumIdUnidadeGeradoraProtocolo($objAcessoExternoDTO->getNumIdUnidade());
          $objDocumentoDTO_Pesquisa->setNumIdUsuarioGeradorProtocolo(SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno());

          $objDocumentoRN = new DocumentoRN();
          $arrObjDocumentoDTO = $objDocumentoRN->listarRN0008($objDocumentoDTO_Pesquisa);

          if (count($arrObjDocumentoDTO) > 0) {
            foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
              $arrIdLiberados[] = $objDocumentoDTO->getDblIdDocumento();
            }
          }
          $bolTodosLiberados = false;
        } else {
          $objAcessoExternoDTO->setStrSinParcial('N');
          $bolTodosLiberados = true;
        }


      }else if ($parObjAcessoExternoDTO->isSetDblIdProcedimento()){

        $objAcessoExternoDTO = new AcessoExternoDTO();
        $objAcessoExternoDTO->setDblIdProtocoloAtividade($parObjAcessoExternoDTO->getDblIdProcedimento());
        $objAcessoExternoDTO->setDblIdDocumento(null);
        $objAcessoExternoDTO->setStrSinParcial('N');

        $bolTodosLiberados = true;

      }else{
        throw new InfraException('Erro processando consulta de Acesso Externo.');
      }


      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retStrNomeTipoProcedimento();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->retDtaGeracaoProtocolo();
      $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();

      $objProcedimentoDTO->setDblIdProcedimento($objAcessoExternoDTO->getDblIdProtocoloAtividade());

      $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objAcessoExternoDTO->getDblIdProtocoloAtividade());
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);

      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      $arrIdProcedimentosAnexados = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO), 'IdProtocolo2');

      if ($parObjAcessoExternoDTO->isSetDblIdProcedimentoAnexadoConsulta()) {

        $objProcedimentoDTO->setArrDblIdProtocoloAssociado(array($parObjAcessoExternoDTO->getDblIdProcedimentoAnexadoConsulta()));

      } else if ($parObjAcessoExternoDTO->isSetDblIdProtocoloConsulta()) {

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->setDblIdProtocolo($parObjAcessoExternoDTO->getDblIdProtocoloConsulta());

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        if ($objProtocoloDTO == null) {
          throw new InfraException('Protocolo não encontrado.', null, null, false);
        }

        $dblIdProcessoAnexado = null;

        if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {

          $dblIdProcessoAnexado = $parObjAcessoExternoDTO->getDblIdProtocoloConsulta();

        } else {

          $bolConsultandoDocumento = true;

          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
          $objDocumentoDTO->retDblIdProcedimento();
          $objDocumentoDTO->setDblIdDocumento($parObjAcessoExternoDTO->getDblIdProtocoloConsulta());

          $objDocumentoRN = new DocumentoRN();
          $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

          if ($objDocumentoDTO->getDblIdProcedimento() != $objAcessoExternoDTO->getDblIdProtocoloAtividade()) {

            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdRelProtocoloProtocolo();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objAcessoExternoDTO->getDblIdProtocoloAtividade());
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($parObjAcessoExternoDTO->getDblIdProtocoloConsulta());
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_MOVIDO);

            if ($objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO)!=null){
              throw new InfraException('Documento movido para o processo '.$objDocumentoDTO->getStrProtocoloProcedimentoFormatado().'.', null, null, false);
            }

            $dblIdProcessoAnexado = $objDocumentoDTO->getDblIdProcedimento();
          }

          $objProcedimentoDTO->setArrDblIdProtocoloAssociado(array($parObjAcessoExternoDTO->getDblIdProtocoloConsulta()));
        }

        if ($dblIdProcessoAnexado != null) {

          $objProcedimentoDTO->setDblIdProcedimento(null);

          if (!in_array($dblIdProcessoAnexado, $arrIdProcedimentosAnexados)) {
            throw new InfraException('Processo solicitado não está anexado ao processo original.');
          }

          $objAcessoExternoDTOAnexado = new AcessoExternoDTO();
          if ($parObjAcessoExternoDTO->isSetNumIdAcessoExterno()) {
            $objAcessoExternoDTOAnexado->setNumIdAcessoExterno($parObjAcessoExternoDTO->getNumIdAcessoExterno());
          }else{
            $objAcessoExternoDTOAnexado->setDblIdProcedimento($parObjAcessoExternoDTO->getDblIdProcedimento());
          }
          $objAcessoExternoDTOAnexado->setDblIdProcedimentoAnexadoConsulta($dblIdProcessoAnexado);
          $objAcessoExternoDTOAnexado = $this->consultarProcessoAcessoExterno($objAcessoExternoDTOAnexado);

          $objProcedimentoDTOPai = $objAcessoExternoDTOAnexado->getObjProcedimentoDTO();

          foreach ($objProcedimentoDTOPai->getArrObjRelProtocoloProtocoloDTO() as $objRelProtocoloProtocoloDTO) {
            if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO &&
                $objRelProtocoloProtocoloDTO->getStrSinAcessoBasico() == 'S' &&
                $objRelProtocoloProtocoloDTO->getDblIdProtocolo2() == $dblIdProcessoAnexado
            ) {

              $objProcedimentoDTO->setDblIdProcedimento($dblIdProcessoAnexado);
              $bolTodosLiberados = true;
              break;
            }
          }
        }
      }

      $objProcedimentoDTO->setStrSinDocTodos('S');
      $objProcedimentoDTO->setStrSinProcAnexados('S');
      $objProcedimentoDTO->setStrSinZip('S');
      $objProcedimentoDTO->setStrSinPdf('S');

      $objProcedimentoRN = new ProcedimentoRN();
      $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

      if (count($arrObjProcedimentoDTO) == 0) {
        throw new InfraException('Processo não encontrado.');
      }

      $objProcedimentoDTO = $arrObjProcedimentoDTO[0];

      $arrRet = array();

      $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

      if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO)) {

        $arrAcessoPermitidoModulos = array();
        $arrAcessoNegadoModulos = array();

        if (count($SEI_MODULOS)) {

          $arrObjProcedimentoAPI = array();
          $arrObjDocumentoAPI = array();

          foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

            if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

              $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

              $objDocumentoAPI = new DocumentoAPI();
              $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
              $objDocumentoAPI->setIdProcedimento($objDocumentoDTO->getDblIdProcedimento());
              $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
              $objDocumentoAPI->setIdUnidadeGeradora($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
              $objDocumentoAPI->setSinAssinado($objDocumentoDTO->getStrSinAssinado());
              $objDocumentoAPI->setSinPublicado($objDocumentoDTO->getStrSinPublicado());
              $objDocumentoAPI->setTipo($objDocumentoDTO->getStrStaProtocoloProtocolo());
              $objDocumentoAPI->setSubTipo($objDocumentoDTO->getStrStaDocumento());
              $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());
              $arrObjDocumentoAPI[] = $objDocumentoAPI;

            } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

              $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

              $objProcedimentoAPI = new ProcedimentoAPI();
              $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTOAnexado->getDblIdProcedimento());
              $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTOAnexado->getNumIdTipoProcedimento());
              $objProcedimentoAPI->setIdUnidadeGeradora($objProcedimentoDTOAnexado->getNumIdUnidadeGeradoraProtocolo());
              $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTOAnexado->getStrStaNivelAcessoGlobalProtocolo());
              $arrObjProcedimentoAPI[] = $objProcedimentoAPI;

            }
          }

          foreach ($SEI_MODULOS as $strModulo => $seiModulo) {
            if (($arr = $seiModulo->executar('verificarAcessoProtocoloExterno', $arrObjProcedimentoAPI, $arrObjDocumentoAPI)) != null) {
              foreach ($arr as $dblIdProtocoloModulo => $numTipoAcessoModulo) {

                if ($numTipoAcessoModulo == SeiIntegracao::$TAM_PERMITIDO) {

                  if (!isset($arrAcessoPermitidoModulos[$dblIdProtocoloModulo])) {
                    $arrAcessoPermitidoModulos[$dblIdProtocoloModulo] = array();
                  }

                  $arrAcessoPermitidoModulos[$dblIdProtocoloModulo][] = $strModulo;

                } else if ($numTipoAcessoModulo == SeiIntegracao::$TAM_NEGADO) {

                  if (!isset($arrAcessoNegadoModulos[$dblIdProtocoloModulo])) {
                    $arrAcessoNegadoModulos[$dblIdProtocoloModulo] = array();
                  }

                  $arrAcessoNegadoModulos[$dblIdProtocoloModulo][] = $strModulo;

                } else {
                  throw new InfraException('Tipo de acesso ['.$numTipoAcessoModulo.'] retornado pelo módulo ['.$strModulo.'] inválido.');
                }
              }
            }
          }
        }

        $objDocumentoRN = new DocumentoRN();

        foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

          $bolMostrarMetadados = true;

          $objRelProtocoloProtocoloDTO->setStrSinAcessoBasico('N');

          $bolAcesso = ($bolTodosLiberados || in_array($objRelProtocoloProtocoloDTO->getDblIdProtocolo2(), $arrIdLiberados));

          if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

            $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

            if (($bolAcesso && $objDocumentoRN->verificarSelecaoAcessoBasico($objDocumentoDTO))
                ||
                ($arrAcessoPermitidoModulos[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()] && $objDocumentoDTO->getStrStaEstadoProtocolo() != ProtocoloRN::$TE_DOCUMENTO_CANCELADO)
                ||
                ($objDocumentoDTO->getDblIdDocumento() == $objAcessoExternoDTO->getDblIdDocumento() && $objDocumentoRN->verificarSelecaoAssinaturaExterna($objDocumentoDTO))
            ) {

              $objRelProtocoloProtocoloDTO->setStrSinAcessoBasico('S');

              //consultando um documento específico se não tiver retorna vazio
            } else if ($bolConsultandoDocumento) {

              if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {
                throw new InfraException('Documento foi cancelado.', null, null, false);
              }

              if ($objDocumentoRN->verificarConteudoGerado($objDocumentoDTO) && $objDocumentoDTO->getStrSinAssinado() == 'N') {
                throw new InfraException('Documento sem assinatura.', null, null, false);
              }

              break;
            }

            //se nao tiver acesso não mostrar metadados de rascunhos
            if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico() == 'N' && $objDocumentoRN->verificarConteudoGerado($objDocumentoDTO) && $objDocumentoDTO->getStrSinAssinado() == 'N' && $objDocumentoDTO->getDblIdDocumento() != $objAcessoExternoDTO->getDblIdDocumento()) {
              $bolMostrarMetadados = false;
            }

          } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

            if ($bolAcesso || $arrAcessoPermitidoModulos[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()]) {
              $objRelProtocoloProtocoloDTO->setStrSinAcessoBasico('S');
            }
          }

          //negacao de modulos tem prioridade
          if ($arrAcessoNegadoModulos[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()]) {
            $objRelProtocoloProtocoloDTO->setStrSinAcessoBasico('N');
          }

          if ($bolMostrarMetadados) {
            $arrRet[] = $objRelProtocoloProtocoloDTO;
          }
        }
      }

      $objProcedimentoDTO->setArrObjRelProtocoloProtocoloDTO($arrRet);

      $objAcessoExternoDTO->setObjProcedimentoDTO($objProcedimentoDTO);

      return $objAcessoExternoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando protocolos de acesso externo.', $e);
    }
  }

  protected function listarDocumentosControleAcessoConectado(AcessoExternoDTO $parObjAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_listar', __METHOD__, $parObjAcessoExternoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retStrStaTipo();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrSinAtivo();
      $objUsuarioDTO->setNumIdUsuario($parObjAcessoExternoDTO->getNumIdUsuarioExterno());

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      if ($objUsuarioDTO == null) {
        throw new InfraException('Usuário externo não encontrado.', null, $parObjAcessoExternoDTO->__toString());
      }

      if ($objUsuarioDTO->getStrSinAtivo()=='N'){
        throw new InfraException('Usuário externo desativado.', null, $parObjAcessoExternoDTO->__toString());
      }

      if ($objUsuarioDTO->getStrStaTipo() == UsuarioRN::$TU_EXTERNO_PENDENTE) {
        $objInfraException->lancarValidacao('Usuário externo "'.$objUsuarioDTO->getStrSigla().'" ainda não foi liberado.');
      }

      if ($objUsuarioDTO->getStrStaTipo() != UsuarioRN::$TU_EXTERNO) {
        $objInfraException->lancarValidacao('Usuário "'.$objUsuarioDTO->getStrSigla().'" não é um usuário externo.');
      }

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retDblIdProtocoloAtividade();
      $objAcessoExternoDTO->retDblIdDocumento();
      $objAcessoExternoDTO->retStrSinProcesso();
      $objAcessoExternoDTO->retDthAberturaAtividade();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->retStrSinInclusao();
      //$objAcessoExternoDTO->retStrSiglaUnidade();
      //$objAcessoExternoDTO->retStrDescricaoUnidade();
      $objAcessoExternoDTO->setStrStaTipo(array(AcessoExternoRN::$TA_ASSINATURA_EXTERNA, AcessoExternoRN::$TA_USUARIO_EXTERNO), InfraDTO::$OPER_IN);
      $objAcessoExternoDTO->setNumIdContatoParticipante($objUsuarioDTO->getNumIdContato());
      if($parObjAcessoExternoDTO->isSetStrSinExpirados() && $parObjAcessoExternoDTO->getStrSinExpirados() == "S"){
        $objAcessoExternoDTO->setDtaValidade(InfraData::getStrDataAtual(), InfraDTO::$OPER_MENOR);
      }else{
        $objAcessoExternoDTO->adicionarCriterio(array('Validade','Validade'),
          array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_IGUAL),
          array(InfraData::getStrDataAtual(),null),
          InfraDTO::$OPER_LOGICO_OR
          );
      }
      $objAcessoExternoDTO->setOrdDthAberturaAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);



      if ($parObjAcessoExternoDTO->isSetDblIdDocumento()) {
        $objAcessoExternoDTO->setDblIdDocumento($parObjAcessoExternoDTO->getDblIdDocumento());
      }

      //paginação
      $objAcessoExternoDTO->setNumMaxRegistrosRetorno($parObjAcessoExternoDTO->getNumMaxRegistrosRetorno());
      $objAcessoExternoDTO->setNumPaginaAtual($parObjAcessoExternoDTO->getNumPaginaAtual());

      $arrObjAcessoExternoDTO = $this->listar($objAcessoExternoDTO);

      //paginação
      $parObjAcessoExternoDTO->setNumTotalRegistros($objAcessoExternoDTO->getNumTotalRegistros());
      $parObjAcessoExternoDTO->setNumRegistrosPaginaAtual($objAcessoExternoDTO->getNumRegistrosPaginaAtual());

      if (count($arrObjAcessoExternoDTO)) {

        //Carregar dados do cabeçalho
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->retStrNomeTipoProcedimento();
        $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();

        $objProcedimentoDTO->setDblIdProcedimento(InfraArray::converterArrInfraDTO($arrObjAcessoExternoDTO, 'IdProtocoloAtividade'), InfraDTO::$OPER_IN);

        $arrIdDocumentos = array_values(array_filter(InfraArray::converterArrInfraDTO($arrObjAcessoExternoDTO, 'IdDocumento')));
        if (count($arrIdDocumentos)) {
          $objProcedimentoDTO->setStrSinDocTodos('S');
          $objProcedimentoDTO->setArrDblIdProtocoloAssociado($arrIdDocumentos);
        }

        $objProcedimentoRN = new ProcedimentoRN();
        $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

        foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {
          foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
            if ($objAcessoExternoDTO->getDblIdProtocoloAtividade() == $objProcedimentoDTO->getDblIdProcedimento()) {

              $objAcessoExternoDTO->setObjProcedimentoDTO($objProcedimentoDTO);

              $arrObjDocumentoDTO = $objProcedimentoDTO->getArrObjDocumentoDTO();
              foreach ($arrObjDocumentoDTO as $objDocumentoDTO) {
                if ($objAcessoExternoDTO->getDblIdDocumento() == $objDocumentoDTO->getDblIdDocumento()) {
                  $objAcessoExternoDTO->setObjDocumentoDTO($objDocumentoDTO);
                }
              }
              break;
            }
          }
        }
      }

      //Auditoria

      return $arrObjAcessoExternoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando documentos para assinatura externa.', $e);
    }
  }

  protected function listarDisponibilizacoesConectado(AcessoExternoDTO $parObjAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_listar', __METHOD__, $parObjAcessoExternoDTO);

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retStrSiglaContato();
      $objAcessoExternoDTO->retStrNomeContato();
      $objAcessoExternoDTO->retStrSiglaUnidade();
      $objAcessoExternoDTO->retStrDescricaoUnidade();
      $objAcessoExternoDTO->retNumIdAtividade();
      $objAcessoExternoDTO->retDthAberturaAtividade();
      $objAcessoExternoDTO->retDthVisualizacao();
      $objAcessoExternoDTO->retNumIdTarefaAtividade();
      $objAcessoExternoDTO->retStrEmailDestinatario();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->retDblIdProtocoloAtividade();
      $objAcessoExternoDTO->retStrSinAtivo();
      $objAcessoExternoDTO->retStrSinInclusao();

      $objAcessoExternoDTO->setStrStaTipo(array(AcessoExternoRN::$TA_INTERESSADO,
          AcessoExternoRN::$TA_DESTINATARIO_ISOLADO,
          AcessoExternoRN::$TA_USUARIO_EXTERNO), InfraDTO::$OPER_IN);

      $objAcessoExternoDTO->setDblIdProtocoloAtividade($parObjAcessoExternoDTO->getDblIdProtocoloAtividade());

      $objAcessoExternoDTO->setOrdDthAberturaAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objAcessoExternoRN = new AcessoExternoRN();
      $arrObjAcessoExternoDTO = $objAcessoExternoRN->listar($objAcessoExternoDTO);

      if (count($arrObjAcessoExternoDTO)) {

        $objAtributoAndamentoRN = new AtributoAndamentoRN();

        foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {

          if ($objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO) {
            $objAcessoExternoDTO->setDthCancelamento(null);
          } else {
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->retStrValor();
            $objAtributoAndamentoDTO->setStrNome('DATA_HORA');
            $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());

            $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);
            $objAcessoExternoDTO->setDthCancelamento($objAtributoAndamentoDTO->getStrValor());
          }
        }

        $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
        $objRelAcessoExtProtocoloDTO->retNumIdAcessoExterno();
        $objRelAcessoExtProtocoloDTO->retStrProtocoloFormatadoProtocolo();
        $objRelAcessoExtProtocoloDTO->setNumIdAcessoExterno(InfraArray::converterArrInfraDTO($arrObjAcessoExternoDTO, 'IdAcessoExterno'), InfraDTO::$OPER_IN);

        $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();
        $arrObjRelAcessoExtProtocoloDTO = InfraArray::indexarArrInfraDTO($objRelAcessoExtProtocoloRN->listar($objRelAcessoExtProtocoloDTO), 'IdAcessoExterno', true);

        foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {
          if (isset($arrObjRelAcessoExtProtocoloDTO[$objAcessoExternoDTO->getNumIdAcessoExterno()])) {
            $objAcessoExternoDTO->setArrObjRelAcessoExtProtocoloDTO($arrObjRelAcessoExtProtocoloDTO[$objAcessoExternoDTO->getNumIdAcessoExterno()]);
          } else {
            $objAcessoExternoDTO->setArrObjRelAcessoExtProtocoloDTO(array());
          }
        }
      }


      return $arrObjAcessoExternoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando disponibilizações de acesso externo.', $e);
    }
  }

  protected function cancelarDisponibilizacaoControlado($parArrObjAcessoExternoDTO)
  {
    try {

      global $SEI_MODULOS;

      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_cancelar', __METHOD__, $parArrObjAcessoExternoDTO);

      $objInfraException = new InfraException();

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retNumIdAtividade();
      $objAcessoExternoDTO->retDblIdProtocoloAtividade();
      $objAcessoExternoDTO->retNumIdTarefaAtividade();
      $objAcessoExternoDTO->retNumIdUnidadeAtividade();
      $objAcessoExternoDTO->retNumIdContatoParticipante();
      $objAcessoExternoDTO->retStrNomeContato();
      $objAcessoExternoDTO->retStrStaTipo();
      $objAcessoExternoDTO->retDblIdDocumento();
      $objAcessoExternoDTO->retStrProtocoloDocumentoFormatado();

      $objAcessoExternoDTO->setNumIdAcessoExterno(InfraArray::converterArrInfraDTO($parArrObjAcessoExternoDTO, 'IdAcessoExterno'), InfraDTO::$OPER_IN);

      $arrObjAcessoExternoDTO = InfraArray::indexarArrInfraDTO($this->listar($objAcessoExternoDTO), 'IdAcessoExterno');


      foreach ($parArrObjAcessoExternoDTO as $parObjAcessoExternoDTO) {

        $objAcessoExternoDTO = $arrObjAcessoExternoDTO[$parObjAcessoExternoDTO->getNumIdAcessoExterno()];

        if ($objAcessoExternoDTO == null) {
          throw new InfraException('Registro de acesso externo ['.$parObjAcessoExternoDTO->getNumIdAcessoExterno().'] não encontrado.');
        }

        $objAcessoExternoDTO->setStrMotivo($parObjAcessoExternoDTO->getStrMotivo());

        if ($objAcessoExternoDTO->getStrStaTipo() != AcessoExternoRN::$TA_INTERESSADO &&
            $objAcessoExternoDTO->getStrStaTipo() != AcessoExternoRN::$TA_DESTINATARIO_ISOLADO &&
            $objAcessoExternoDTO->getStrStaTipo() != AcessoExternoRN::$TA_USUARIO_EXTERNO
        ) {
          $objInfraException->adicionarValidacao('Registro ['.$objAcessoExternoDTO->getNumIdAcessoExterno().'] não é uma Disponibilização de Acesso Externo.');
        }

        if ($objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA) {
          $objInfraException->adicionarValidacao('Disponibilização de acesso externo para "'.$objAcessoExternoDTO->getStrNomeContato().'" já consta como cancelada.');
        } else if ($objAcessoExternoDTO->getNumIdTarefaAtividade() != TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO) {
          $objInfraException->adicionarValidacao('Andamento do processo ['.$objAcessoExternoDTO->getNumIdTarefaAtividade().'] não é uma Disponibilização de Acesso Externo.');
        }

        if ($objAcessoExternoDTO->getNumIdUnidadeAtividade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
          $objInfraException->adicionarValidacao('Disponibilização de acesso externo para o interessado "'.$objAcessoExternoDTO->getStrNomeContato().'" não foi concedida pela unidade atual.');
        }
      }
      $objInfraException->lancarValidacoes();


      $strDataHoraAtual = InfraData::getStrDataHoraAtual();

      $objAtividadeRN = new AtividadeRN();
      $objAtributoAndamentoRN = new AtributoAndamentoRN();
      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $arrObjAcessoExternoAPI = array();
      foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retStrNome();
        $objAtributoAndamentoDTO->retStrValor();
        $objAtributoAndamentoDTO->retStrIdOrigem();
        $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());

        $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

        foreach ($arrObjAtributoAndamentoDTO as $objAtributoAndamentoDTO) {
          if ($objAtributoAndamentoDTO->getStrNome() == 'MOTIVO') {
            $objAtributoAndamentoDTO->setStrValor($objAcessoExternoDTO->getStrMotivo());
            break;
          }
        }

        //lança andamento para o usuário atual registrando o cancelamento da liberação
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdUnidadeOrigem(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdUsuario(null);
        $objAtividadeDTO->setNumIdUsuarioOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAtividadeDTO->setDtaPrazo(null);

        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELAMENTO_LIBERACAO_ACESSO_EXTERNO);

        $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        //altera andamento original de concessão ou transferência
        $objAtividadeDTO = new AtividadeDTO();

        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA);

        $objAtividadeDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
        $objAtividadeRN->mudarTarefa($objAtividadeDTO);

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('USUARIO');
        $objAtributoAndamentoDTO->setStrValor(SessaoSEI::getInstance()->getStrSiglaUsuario().'¥'.SessaoSEI::getInstance()->getStrNomeUsuario());
        $objAtributoAndamentoDTO->setStrIdOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
        $objAtributoAndamentoRN->cadastrarRN1363($objAtributoAndamentoDTO);

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DATA_HORA');
        $objAtributoAndamentoDTO->setStrValor($strDataHoraAtual);
        $objAtributoAndamentoDTO->setStrIdOrigem($ret->getNumIdAtividade()); //relaciona com o andamento de cassação
        $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
        $objAtributoAndamentoRN->cadastrarRN1363($objAtributoAndamentoDTO);

        $objAcessoExternoBD->desativar($objAcessoExternoDTO);

        $objAcessoExternoAPI = new AcessoExternoAPI();
        $objAcessoExternoAPI->setIdAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objAcessoExternoDTO->getDblIdProtocoloAtividade());
        $objAcessoExternoAPI->setProcedimento($objProcedimentoAPI);

        $arrObjAcessoExternoAPI[] = $objAcessoExternoAPI;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('cancelarDisponibilizacaoAcessoExterno', $arrObjAcessoExternoAPI);
      }

    } catch (Exception $e) {
      throw new InfraException('Erro cancelando disponibilização de acesso externo.', $e);
    }
  }

  protected function listarLiberacoesAssinaturaExternaConectado(AcessoExternoDTO $parObjAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_listar', __METHOD__, $parObjAcessoExternoDTO);

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retStrSiglaContato();
      $objAcessoExternoDTO->retStrNomeContato();
      $objAcessoExternoDTO->retStrSiglaUnidade();
      $objAcessoExternoDTO->retStrDescricaoUnidade();
      $objAcessoExternoDTO->retNumIdAtividade();
      $objAcessoExternoDTO->retDthAberturaAtividade();
      $objAcessoExternoDTO->retDthVisualizacao();
      $objAcessoExternoDTO->retNumIdTarefaAtividade();
      $objAcessoExternoDTO->retStrSinProcesso();
      $objAcessoExternoDTO->retNumIdContatoParticipante();
      $objAcessoExternoDTO->retDblIdProtocoloAtividade();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->retStrSinAtivo();
      $objAcessoExternoDTO->retStrSinInclusao();

      $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_ASSINATURA_EXTERNA);
      $objAcessoExternoDTO->setDblIdDocumento($parObjAcessoExternoDTO->getDblIdDocumento());

      $objAcessoExternoRN = new AcessoExternoRN();
      $arrObjAcessoExternoDTO = $objAcessoExternoRN->listar($objAcessoExternoDTO);

      if (count($arrObjAcessoExternoDTO)) {

        $objAssinaturaRN = new AssinaturaRN();
        $objAtributoAndamentoRN = new AtributoAndamentoRN();

        foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {

          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->retStrIdOrigem();
          $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
          $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
          $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);

          $objAssinaturaDTO = new AssinaturaDTO();
          $objAssinaturaDTO->retDthAberturaAtividade();
          $objAssinaturaDTO->setDblIdDocumento($objAtributoAndamentoDTO->getStrIdOrigem());
          $objAssinaturaDTO->setNumIdContatoUsuario($objAcessoExternoDTO->getNumIdContatoParticipante());

          $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

          if ($objAssinaturaDTO != null) {
            $objAcessoExternoDTO->setDthUtilizacao($objAssinaturaDTO->getDthAberturaAtividade());
          } else {
            $objAcessoExternoDTO->setDthUtilizacao(null);
          }

          if ($objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA) {
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setNumMaxRegistrosRetorno(1);
            $objAtributoAndamentoDTO->retStrValor();
            $objAtributoAndamentoDTO->setStrNome('DATA_HORA');
            $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());

            $objAtributoAndamentoDTO = $objAtributoAndamentoRN->consultarRN1366($objAtributoAndamentoDTO);
            $objAcessoExternoDTO->setDthCancelamento($objAtributoAndamentoDTO->getStrValor());
          } else {
            $objAcessoExternoDTO->setDthCancelamento(null);
          }
        }

        $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
        $objRelAcessoExtProtocoloDTO->retNumIdAcessoExterno();
        $objRelAcessoExtProtocoloDTO->retStrProtocoloFormatadoProtocolo();
        $objRelAcessoExtProtocoloDTO->setNumIdAcessoExterno(InfraArray::converterArrInfraDTO($arrObjAcessoExternoDTO, 'IdAcessoExterno'), InfraDTO::$OPER_IN);

        $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();
        $arrObjRelAcessoExtProtocoloDTO = InfraArray::indexarArrInfraDTO($objRelAcessoExtProtocoloRN->listar($objRelAcessoExtProtocoloDTO), 'IdAcessoExterno', true);

        foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {
          if (isset($arrObjRelAcessoExtProtocoloDTO[$objAcessoExternoDTO->getNumIdAcessoExterno()])) {
            $objAcessoExternoDTO->setArrObjRelAcessoExtProtocoloDTO($arrObjRelAcessoExtProtocoloDTO[$objAcessoExternoDTO->getNumIdAcessoExterno()]);
          } else {
            $objAcessoExternoDTO->setArrObjRelAcessoExtProtocoloDTO(array());
          }
        }

      }

      return $arrObjAcessoExternoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando liberações de assinatura externa.', $e);
    }
  }

  protected function cancelarLiberacaoAssinaturaExternaControlado($parArrObjAcessoExternoDTO)
  {
    try {

      global $SEI_MODULOS;

      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_externa_cancelar', __METHOD__, $parArrObjAcessoExternoDTO);

      $objInfraException = new InfraException();

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setBolExclusaoLogica(false);
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retNumIdAtividade();
      $objAcessoExternoDTO->retDblIdProtocoloAtividade();
      $objAcessoExternoDTO->retNumIdTarefaAtividade();
      $objAcessoExternoDTO->retNumIdUnidadeAtividade();
      $objAcessoExternoDTO->retNumIdContatoParticipante();
      $objAcessoExternoDTO->retStrStaTipo();
      $objAcessoExternoDTO->retDblIdDocumento();
      $objAcessoExternoDTO->retStrProtocoloDocumentoFormatado();
      $objAcessoExternoDTO->retStrSinProcesso();

      $objAcessoExternoDTO->setNumIdAcessoExterno(InfraArray::converterArrInfraDTO($parArrObjAcessoExternoDTO, 'IdAcessoExterno'), InfraDTO::$OPER_IN);

      $arrObjAcessoExternoDTO = InfraArray::indexarArrInfraDTO($this->listar($objAcessoExternoDTO), 'IdAcessoExterno');


      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->setNumIdContato(InfraArray::converterArrInfraDTO($arrObjAcessoExternoDTO, 'IdContatoParticipante'), InfraDTO::$OPER_IN);

      $objUsuarioRN = new UsuarioRN();
      $arrObjUsuarioDTO = InfraArray::indexarArrInfraDTO($objUsuarioRN->listarRN0490($objUsuarioDTO), 'IdContato');


      foreach ($parArrObjAcessoExternoDTO as $parObjAcessoExternoDTO) {

        $objAcessoExternoDTO = $arrObjAcessoExternoDTO[$parObjAcessoExternoDTO->getNumIdAcessoExterno()];
        $objUsuarioDTO = $arrObjUsuarioDTO[$objAcessoExternoDTO->getNumIdContatoParticipante()];

        if ($objAcessoExternoDTO == null) {
          throw new InfraException('Registro de acesso externo ['.$parObjAcessoExternoDTO->getNumIdAcessoExterno().'] não encontrado.');
        }

        $objAcessoExternoDTO->setStrMotivo($parObjAcessoExternoDTO->getStrMotivo());

        if ($objAcessoExternoDTO->getStrStaTipo() != AcessoExternoRN::$TA_ASSINATURA_EXTERNA) {
          $objInfraException->adicionarValidacao('Registro ['.$objAcessoExternoDTO->getNumIdAcessoExterno().'] não é uma Liberação de Assinatura Externa.');
        }

        if ($objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA) {
          $objInfraException->adicionarValidacao('Liberação de Assinatura Externa para o usuário "'.$objUsuarioDTO->getStrSigla().'" no documento '.$objAcessoExternoDTO->getStrProtocoloDocumentoFormatado().' já consta como cancelada.');
        } else if ($objAcessoExternoDTO->getNumIdTarefaAtividade() != TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA) {
          $objInfraException->adicionarValidacao('Andamento do processo ['.$objAcessoExternoDTO->getNumIdTarefaAtividade().'] não é uma Liberação de Assinatura Externa.');
        }

        if ($objAcessoExternoDTO->getNumIdUnidadeAtividade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
          $objInfraException->adicionarValidacao('Liberação de Assinatura Externa para o usuário "'.$objUsuarioDTO->getStrSigla().'" no documento '.$objAcessoExternoDTO->getStrProtocoloDocumentoFormatado().' não foi concedida pela unidade atual.');
        }

        if ($objAcessoExternoDTO->getStrSinProcesso() == 'N') {
          $objAssinaturaDTO = new AssinaturaDTO();
          $objAssinaturaDTO->retStrSiglaUsuario();
          $objAssinaturaDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
          $objAssinaturaDTO->setDblIdDocumento($objAcessoExternoDTO->getDblIdDocumento());

          $objAssinaturaRN = new AssinaturaRN();
          $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

          if ($objAssinaturaDTO != null) {
            $objInfraException->adicionarValidacao('Usuário "'.$objAssinaturaDTO->getStrSiglaUsuario().'" já assinou o documento '.$objAcessoExternoDTO->getStrProtocoloDocumentoFormatado().'.');
          }
        }
      }
      $objInfraException->lancarValidacoes();

      $strDataHoraAtual = InfraData::getStrDataHoraAtual();

      $objAtividadeRN = new AtividadeRN();
      $objAtributoAndamentoRN = new AtributoAndamentoRN();
      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $arrObjAcessoExternoAPI = array();

      foreach ($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->retStrNome();
        $objAtributoAndamentoDTO->retStrValor();
        $objAtributoAndamentoDTO->retStrIdOrigem();
        $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());

        $arrObjAtributoAndamentoDTO = $objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('MOTIVO');
        $objAtributoAndamentoDTO->setStrValor($objAcessoExternoDTO->getStrMotivo());
        $objAtributoAndamentoDTO->setStrIdOrigem(null); //relaciona com o andamento de cassação
        $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

        //lança andamento para o usuário atual registrando o cancelamento da liberação
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setDblIdProtocolo($objAcessoExternoDTO->getDblIdProtocoloAtividade());
        $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdUnidadeOrigem(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAtividadeDTO->setNumIdUsuario(null);
        $objAtividadeDTO->setNumIdUsuarioOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAtividadeDTO->setDtaPrazo(null);

        $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_CANCELAMENTO_LIBERACAO_ASSINATURA_EXTERNA);

        $ret = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

        //altera andamento original de concessão ou transferência
        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA);
        $objAtividadeDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
        $objAtividadeRN->mudarTarefa($objAtividadeDTO);

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('USUARIO');
        $objAtributoAndamentoDTO->setStrValor(SessaoSEI::getInstance()->getStrSiglaUsuario().'¥'.SessaoSEI::getInstance()->getStrNomeUsuario());
        $objAtributoAndamentoDTO->setStrIdOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
        $objAtributoAndamentoRN->cadastrarRN1363($objAtributoAndamentoDTO);

        $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
        $objAtributoAndamentoDTO->setStrNome('DATA_HORA');
        $objAtributoAndamentoDTO->setStrValor($strDataHoraAtual);
        $objAtributoAndamentoDTO->setStrIdOrigem($ret->getNumIdAtividade()); //relaciona com o andamento de cassação
        $objAtributoAndamentoDTO->setNumIdAtividade($objAcessoExternoDTO->getNumIdAtividade());
        $objAtributoAndamentoRN->cadastrarRN1363($objAtributoAndamentoDTO);


        $objAcessoExternoBD->desativar($objAcessoExternoDTO);

        $objAcessoExternoAPI = new AcessoExternoAPI();
        $objAcessoExternoAPI->setIdAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());

        $objProcedimentoAPI = new ProcedimentoAPI();
        $objProcedimentoAPI->setIdProcedimento($objAcessoExternoDTO->getDblIdProtocoloAtividade());
        $objAcessoExternoAPI->setProcedimento($objProcedimentoAPI);

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objAcessoExternoDTO->getDblIdDocumento());
        $objAcessoExternoAPI->setDocumento($objDocumentoAPI);

        $arrObjAcessoExternoAPI[] = $objAcessoExternoAPI;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('cancelarLiberacaoAssinaturaExterna', $arrObjAcessoExternoAPI);
      }


    } catch (Exception $e) {
      throw new InfraException('Erro cancelando liberação de assinatura externa.', $e);
    }
  }


  /*
  protected function alterarControlado(AcessoExternoDTO $objAcessoExternoDTO){
    try {

      //Valida Permissao
         SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_alterar',__METHOD__,$objAcessoExternoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAcessoExternoDTO->isSetNumIdAtividade()){
        $this->validarNumIdAtividade($objAcessoExternoDTO, $objInfraException);
      }
      if ($objAcessoExternoDTO->isSetNumIdParticipante()){
        $this->validarNumIdParticipante($objAcessoExternoDTO, $objInfraException);
      }
      if ($objAcessoExternoDTO->isSetDtaValidade()){
        $this->validarDtaValidade($objAcessoExternoDTO, $objInfraException);
      }
      if ($objAcessoExternoDTO->isSetStrEmailUnidade()){
        $this->validarStrEmailUnidade($objAcessoExternoDTO, $objInfraException);
      }
      if ($objAcessoExternoDTO->isSetStrEmailDestinatario()){
        $this->validarStrEmailDestinatario($objAcessoExternoDTO, $objInfraException);
      }
      if ($objAcessoExternoDTO->isSetStrHashInterno()){
        $this->validarStrHashInterno($objAcessoExternoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $objAcessoExternoBD->alterar($objAcessoExternoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Acesso Externo.',$e);
    }
  }

 */

  protected function excluirControlado($arrObjAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_excluir', __METHOD__, $arrObjAcessoExternoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      for ($i = 0; $i < count($arrObjAcessoExternoDTO); $i++) {

        $objAcessoExternoDTO = new AcessoExternoDTO();
        $objAcessoExternoDTO->setBolExclusaoLogica(false);
        $objAcessoExternoDTO->retStrStaTipo();
        $objAcessoExternoDTO->retDtaValidade();
        $objAcessoExternoDTO->retNumIdTarefaAtividade();
        $objAcessoExternoDTO->setNumIdAcessoExterno($arrObjAcessoExternoDTO[$i]->getNumIdAcessoExterno());

        $objAcessoExternoDTO = $this->consultar($objAcessoExternoDTO);

        if ($objAcessoExternoDTO->getStrStaTipo() != AcessoExternoRN::$TA_SISTEMA &&
            !($objAcessoExternoDTO->getStrStaTipo() == AcessoExternoRN::$TA_ASSINATURA_EXTERNA && ($objAcessoExternoDTO->getNumIdTarefaAtividade() == TarefaRN::$TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA || ($objAcessoExternoDTO->getDtaValidade()!=null && InfraData::compararDatas(InfraData::getStrDataAtual(), $objAcessoExternoDTO->getDtaValidade()) < 0)))
        ) {

          if ($objAcessoExternoDTO->getStrStaTipo()==AcessoExternoRN::$TA_ASSINATURA_EXTERNA){
            throw new InfraException('Liberação para Assinatura Externa não pode ser excluída.');
          }else {
            throw new InfraException('Acesso Externo não pode ser excluído.');
          }
        }
      }

      $objInfraException->lancarValidacoes();

      $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();

      for ($i = 0; $i < count($arrObjAcessoExternoDTO); $i++) {
        $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
        $objRelAcessoExtProtocoloDTO->retNumIdAcessoExterno();
        $objRelAcessoExtProtocoloDTO->retDblIdProtocolo();
        $objRelAcessoExtProtocoloDTO->setNumIdAcessoExterno($arrObjAcessoExternoDTO[$i]->getNumIdAcessoExterno());
        $objRelAcessoExtProtocoloRN->excluir($objRelAcessoExtProtocoloRN->listar($objRelAcessoExtProtocoloDTO));
      }

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjAcessoExternoDTO); $i++) {
        $objAcessoExternoBD->excluir($arrObjAcessoExternoDTO[$i]);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Acesso Externo.', $e);
    }
  }

  protected function consultarConectado(AcessoExternoDTO $objAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_consultar', __METHOD__, $objAcessoExternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $ret = $objAcessoExternoBD->consultar($objAcessoExternoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Acesso Externo.', $e);
    }
  }

  protected function listarConectado(AcessoExternoDTO $objAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_listar', __METHOD__, $objAcessoExternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $ret = $objAcessoExternoBD->listar($objAcessoExternoDTO);

      //Auditoria

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro listando Acessos Externos.', $e);
    }
  }

  protected function contarConectado(AcessoExternoDTO $objAcessoExternoDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_listar', __METHOD__, $objAcessoExternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $ret = $objAcessoExternoBD->contar($objAcessoExternoDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Acessos Externos.', $e);
    }
  }

  public function listarValoresTipoAcessoExterno()
  {
    try {

      $arrObjTipoDTO = array();

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_INTERESSADO);
      $objTipoDTO->setStrDescricao('Interessado do Processo');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_USUARIO_EXTERNO);
      $objTipoDTO->setStrDescricao('Usuário Externo');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_DESTINATARIO_ISOLADO);
      $objTipoDTO->setStrDescricao('Destinatário Isolado');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_SISTEMA);
      $objTipoDTO->setStrDescricao('Sistema');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_ASSINATURA_EXTERNA);
      $objTipoDTO->setStrDescricao('Assinatura Externa de Documento');
      $arrObjTipoDTO[] = $objTipoDTO;

      return $arrObjTipoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando valores de Tipo de Acesso Externo.', $e);
    }
  }

  protected function incluirDocumentoControlado(AcessoExternoDTO $objAcessoExternoDTO){
    try {
      $objInfraException = new InfraException();

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      if ($objInfraParametro->getValor('SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO') != '1'){
        $objInfraException->lancarValidacao('Não é permitida a inclusão de documento por Usuário Externo.');
      }

      //busca acesso externo
      $objAcessoExternoDTO_Pesquisa =  new AcessoExternoDTO();
      $objAcessoExternoDTO_Pesquisa->retNumIdUnidade();
      $objAcessoExternoDTO_Pesquisa->retStrSiglaUnidade();
      $objAcessoExternoDTO_Pesquisa->retDblIdProtocoloAtividade();
      $objAcessoExternoDTO_Pesquisa->retNumIdContatoParticipante();
      $objAcessoExternoDTO_Pesquisa->retDtaValidade();
      $objAcessoExternoDTO_Pesquisa->setNumIdAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());
      $objAcessoExternoRN = new AcessoExternoRN();
      $objAcessoExternoDTO_Pesquisa = $objAcessoExternoRN->consultar($objAcessoExternoDTO_Pesquisa);

      SessaoSEI::getInstance()->simularLogin(null, null, SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno(), $objAcessoExternoDTO_Pesquisa->getNumIdUnidade());

      if (!InfraString::isBolVazia($objAcessoExternoDTO_Pesquisa->getDtaValidade()) && InfraData::compararDatas(InfraData::getStrDataAtual(), $objAcessoExternoDTO_Pesquisa->getDtaValidade()) < 0) {
        $objInfraException->lancarValidacao('Não é possível incluir o documento porque esta disponibilização de Acesso Externo expirou em '.$objAcessoExternoDTO_Pesquisa->getDtaValidade().'.');
      }

      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
      $objAtividadeDTO->setDblIdProtocolo($objAcessoExternoDTO_Pesquisa->getDblIdProtocoloAtividade());
      $objAtividadeDTO->setNumIdUnidade($objAcessoExternoDTO_Pesquisa->getNumIdUnidade());
      $objAtividadeDTO->setDthConclusao(null);

      $objAtividadeRN = new AtividadeRN();
      if ($objAtividadeRN->contarRN0035($objAtividadeDTO)==0){
        $objInfraException->lancarValidacao('Não é possível incluir o documento porque o processo já foi concluído na unidade '.$objAcessoExternoDTO_Pesquisa->getStrSiglaUnidade().' que liberou o acesso externo.');
      }

      //abaixo segue a estrutura para cadastro/upload de anexos de um acesso externo
      // -AcessoExternoDTO
      // --ArrObjDocumentoDTO - o AcessoExternoDTO tem um array de documentos... cada documento corresponde a um anexo
      // ---ProtocoloDTO - mas DocumentoDTO não tem um atributo/relacionamento direto com AnexoDTO... são 'ligados' pelo ProtocoloDTO de cada documento
      // ---- ArrObjAnexoDTO - sendo que ProtocoloDTO tem um array de anexos... nesse caso, esse array terá sempre um elemento, que é o anexo referente ao documento

      //os participantes interessado e destinatario sao o proprio usuario externo
      $objParticipanteDTO_Interessado = new ParticipanteDTO();
      $objParticipanteDTO_Interessado->setNumIdContato($objAcessoExternoDTO_Pesquisa->getNumIdContatoParticipante());
      $objParticipanteDTO_Interessado->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
      $objParticipanteDTO_Interessado->setNumSequencia(1);

      $objParticipanteDTO_Destinatario = new ParticipanteDTO();
      $objParticipanteDTO_Destinatario->setNumIdContato($objAcessoExternoDTO_Pesquisa->getNumIdContatoParticipante());
      $objParticipanteDTO_Destinatario->setStrStaParticipacao(ParticipanteRN::$TP_REMETENTE);
      $objParticipanteDTO_Destinatario->setNumSequencia(1);

      //busca o processo ao qual serão anexados documentos
      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO_Consulta = new ProtocoloDTO();
      $objProtocoloDTO_Consulta->retStrStaNivelAcessoLocal();
      $objProtocoloDTO_Consulta->retNumIdHipoteseLegal();
      $objProtocoloDTO_Consulta->retStrStaGrauSigilo();
      $objProtocoloDTO_Consulta->setDblIdProtocolo($objAcessoExternoDTO_Pesquisa->getDblIdProtocoloAtividade());
      $objProtocoloDTO_Consulta = $objProtocoloRN->consultarRN0186($objProtocoloDTO_Consulta);

      $objDocumentoDTO = $objAcessoExternoDTO->getObjDocumentoDTO();

      //atributos para inclusao do documento
      $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EXTERNO);
      $objDocumentoDTO->setStrNumero(null);
      $objDocumentoDTO->setStrNomeArvore(null);
      $objDocumentoDTO->setDblIdProcedimento($objAcessoExternoDTO_Pesquisa->getDblIdProtocoloAtividade());
      //a unudade do documento é a unidade de quem deu o acesso externo
      $objDocumentoDTO->setNumIdUnidadeResponsavel($objAcessoExternoDTO_Pesquisa->getNumIdUnidade());
      $objDocumentoDTO->setNumIdTipoConferencia(null);

      $objProtocoloDTO = $objDocumentoDTO->getObjProtocoloDTO();

      $objProtocoloDTO->setStrStaNivelAcessoLocal($objProtocoloDTO_Consulta->getStrStaNivelAcessoLocal());
      $objProtocoloDTO->setNumIdHipoteseLegal($objProtocoloDTO_Consulta->getNumIdHipoteseLegal());
      $objProtocoloDTO->setStrStaGrauSigilo($objProtocoloDTO_Consulta->getStrStaGrauSigilo());

      //atributos necessarios para validacao da insercao de protocolo
      $objProtocoloDTO->setArrObjObservacaoDTO(array());
      $objProtocoloDTO->setStrDescricao(null);
      $objProtocoloDTO->setNumIdUnidadeGeradora($objAcessoExternoDTO_Pesquisa->getNumIdUnidade());
      //retorna o anexo, que é o documento... apesar do atributo no ProtocoloDTO ser um array, terá sempre apenas um objeto
      $objAnexoDTO = $objProtocoloDTO->getArrObjAnexoDTO()[0];
      //atributos necessarios para o protocolo referente ao documento
      $objProtocoloDTO->setDtaGeracao($objAnexoDTO->getDthInclusao());
      //apesar do protocolo conter um array de anexos, nesse caso, esse array terá sempre um elemento apenas
      $objProtocoloDTO->setArrObjParticipanteDTO(array($objParticipanteDTO_Interessado,$objParticipanteDTO_Destinatario));

      $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

      $objInfraException->lancarValidacoes();

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO =  $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);

      return $objDocumentoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando valores de Tipo de Acesso Externo.', $e);
    }

  }

  /*
  protected function desativarControlado($arrObjAcessoExternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_desativar',__METHOD__,$arrObjAcessoExternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcessoExternoDTO);$i++){
        $objAcessoExternoBD->desativar($arrObjAcessoExternoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Acesso Externo.',$e);
    }
  }

  protected function reativarControlado($arrObjAcessoExternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_reativar',__METHOD__,$arrObjAcessoExternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcessoExternoDTO);$i++){
        $objAcessoExternoBD->reativar($arrObjAcessoExternoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Acesso Externo.',$e);
    }
  }

  protected function bloquearControlado(AcessoExternoDTO $objAcessoExternoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_externo_consultar',__METHOD__,$objAcessoExternoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoExternoBD = new AcessoExternoBD($this->getObjInfraIBanco());
      $ret = $objAcessoExternoBD->bloquear($objAcessoExternoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Acesso Externo.',$e);
    }
  }

 */

  protected function validarExistenciaLiberacaoInclusaoConectado(ProcedimentoDTO $objProcedimentoDTO, InfraException $objInfraException){
    try{

      $strProtocoloFormatado = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->retDtaValidade();
      $objAcessoExternoDTO->retDblIdDocumento();
      $objAcessoExternoDTO->retStrStaTipo();
      $objAcessoExternoDTO->setDblIdProtocoloAtividade($objProcedimentoDTO->getDblIdProcedimento());
      $objAcessoExternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAcessoExternoDTO->setStrStaTipo(array(AcessoExternoRN::$TA_ASSINATURA_EXTERNA, AcessoExternoRN::$TA_USUARIO_EXTERNO), InfraDTO::$OPER_IN);
      $objAcessoExternoDTO->setStrSinInclusao('S');

      $arrObjAcessoExternoDTO = $this->listar($objAcessoExternoDTO);

      $arrIdAcessoExterno = array();
      $arrIdDocumentoAssinatura = array();
      foreach($arrObjAcessoExternoDTO as $objAcessoExternoDTO){
        if (InfraData::compararDatas(InfraData::getStrDataAtual(), $objAcessoExternoDTO->getDtaValidade()) >= 0){
          if ($objAcessoExternoDTO->getStrStaTipo() == AcessoExternoRN::$TA_USUARIO_EXTERNO){
            $arrIdAcessoExterno[] = $objAcessoExternoDTO->getNumIdAcessoExterno();
          }else{
            $arrIdDocumentoAssinatura[] = $objAcessoExternoDTO->getDblIdDocumento();
          }
        }
      }

      if (($numAcessosExternos = count($arrIdAcessoExterno))) {
        $objInfraException->adicionarValidacao('Processo '.$strProtocoloFormatado.' possui '.($numAcessosExternos==1?'liberação':'liberações').' de acesso externo com permissão para inclusão de documentos '.($numAcessosExternos==1?'válida':'válidas').' na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
      }

      if (($numAssinaturasExternas = count($arrIdDocumentoAssinatura))){

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $objProtocoloDTO->setDblIdProtocolo($arrIdDocumentoAssinatura, InfraDTO::$OPER_IN);

        $objProtocoloRN = new ProtocoloRN();
        $strProtocoloDocumentos  = implode(',', InfraArray::converterArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO), 'ProtocoloFormatado'));

        $objInfraException->adicionarValidacao('Processo '.$strProtocoloFormatado.' possui '.($numAssinaturasExternas==1?'liberação':'liberações').' para assinatura externa ('.$strProtocoloDocumentos.') com permissão para inclusão de documentos '.($numAssinaturasExternas==1?'válida':'válidas').' na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'.');
      }


    }catch(Exception $e){
      throw new InfraException('Erro validando existência de Acesso Externo com permissão para inclusão de documentos.',$e);
    }
  }

}
?>