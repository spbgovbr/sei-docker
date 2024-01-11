<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelBlocoProtocoloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocoloRN1285(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoProtocoloDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdBlocoRN1286(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoProtocoloDTO->getNumIdBloco())){
      $objInfraException->adicionarValidacao('Bloco não informado.');
    }else{
    	$objBlocoDTO = new BlocoDTO();
      $objBlocoDTO->retNumIdBloco();
    	$objBlocoDTO->setNumIdBloco($objRelBlocoProtocoloDTO->getNumIdBloco());
    	
    	$objBlocoRN = new BlocoRN();
    	if ($objBlocoRN->consultarRN1276($objBlocoDTO)==null){
    		$objInfraException->adicionarValidacao('Bloco '.$objRelBlocoProtocoloDTO->getNumIdBloco().' não encontrado.');
    	}
    }
  }
  
  private function validarStrAnotacao(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoProtocoloDTO->getStrAnotacao())){
      $objRelBlocoProtocoloDTO->setStrAnotacao(null);
    }else{
      $objRelBlocoProtocoloDTO->setStrAnotacao(trim($objRelBlocoProtocoloDTO->getStrAnotacao()));
      $objRelBlocoProtocoloDTO->setStrAnotacao(InfraUtil::filtrarISO88591($objRelBlocoProtocoloDTO->getStrAnotacao()));

      if (strlen($objRelBlocoProtocoloDTO->getStrAnotacao())>2000){
        $objInfraException->adicionarValidacao('Anotação possui tamanho superior a 2000 caracteres.');
      }
    }
  }

  private function validarNumSequencia(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objRelBlocoProtocoloDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência não informada.');
    }
    
    $dto = new RelBlocoProtocoloDTO();
    $dto->setNumMaxRegistrosRetorno(1);
    $dto->retDblIdProtocolo();
    $dto->setDblIdProtocolo($objRelBlocoProtocoloDTO->getDblIdProtocolo(),InfraDTO::$OPER_DIFERENTE);
    $dto->setNumIdBloco($objRelBlocoProtocoloDTO->getNumIdBloco());
    $dto->setNumSequencia($objRelBlocoProtocoloDTO->getNumSequencia());

    if ($this->consultarRN1290($dto)!=null){
      $objInfraException->adicionarValidacao('Já existe outro documento usando o mesmo número de sequência '.$objRelBlocoProtocoloDTO->getNumSequencia().' no bloco '.$objRelBlocoProtocoloDTO->getNumIdBloco().'.');
    }
    
  }
  
  private function cadastrarRN1287(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_cadastrar',__METHOD__,$objRelBlocoProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocoloRN1285($objRelBlocoProtocoloDTO, $objInfraException);
      $this->validarNumIdBlocoRN1286($objRelBlocoProtocoloDTO, $objInfraException);
      $this->validarStrAnotacao($objRelBlocoProtocoloDTO, $objInfraException);
      $this->validarNumSequencia($objRelBlocoProtocoloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();
      
      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoProtocoloBD->cadastrar($objRelBlocoProtocoloDTO);

      $this->montarIndexacao($ret);
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando protocolo no bloco.',$e);
    }
  }

  protected function cadastrarDisponibilizarMultiploControlado($arrObjRelBlocoProtocoloDTO) {
    try{

      if (count($arrObjRelBlocoProtocoloDTO)) {
        $this->cadastrarMultiplo($arrObjRelBlocoProtocoloDTO);

        $objBlocoRN = new BlocoRN();
        $objBlocoRN->disponibilizar(InfraArray::gerarArrInfraDTO('BlocoDTO','IdBloco',InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO, 'IdBloco')));
      }

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando protocolos no bloco e disponibilizando.',$e);
    }
  }

  protected function cadastrarMultiploControlado($arrObjRelBlocoProtocoloDTO) {
    try {

      if (InfraArray::contar($arrObjRelBlocoProtocoloDTO)) {

        //Regras de Negocio
        $objInfraException = new InfraException();

        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $objBlocoRN = new BlocoRN();
        $objDocumentoRN = new DocumentoRN();
        $objProtocoloRN = new ProtocoloRN();

        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO, 'IdProtocolo'));

        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

        $objBlocoDTO = new BlocoDTO();
        $objBlocoDTO->setDistinct(true);
        $objBlocoDTO->retNumIdBloco();
        $objBlocoDTO->retNumIdUnidade();
        $objBlocoDTO->retStrStaTipo();
        $objBlocoDTO->retStrStaEstado();
        $objBlocoDTO->setNumIdBloco(InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO, 'IdBloco'), InfraDTO::$OPER_IN);

        $arrObjBlocoDTO = InfraArray::indexarArrInfraDTO($objBlocoRN->listarRN1277($objBlocoDTO), 'IdBloco');


        foreach ($arrObjBlocoDTO as $objBlocoDTO) {
          if ($objBlocoDTO->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $objInfraException->adicionarValidacao('Bloco ' . $objBlocoDTO->getNumIdBloco() . ' não pertence à unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '.');
          } else if ($objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_DISPONIBILIZADO) {
            $objInfraException->adicionarValidacao('Bloco ' . $objBlocoDTO->getNumIdBloco() . ' não pode estar disponibilizado.');
          } else if ($objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_CONCLUIDO) {
            $objInfraException->adicionarValidacao('Bloco ' . $objBlocoDTO->getNumIdBloco() . ' não pode estar concluído.');
          }
        }
        $objInfraException->lancarValidacoes();

        foreach ($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO) {

          if (!isset($arrObjBlocoDTO[$objRelBlocoProtocoloDTO->getNumIdBloco()])) {
            $objInfraException->lancarValidacao('Bloco ' . $objRelBlocoProtocoloDTO->getNumIdBloco() . ' não encontrado.');
          }

          $dto = new RelBlocoProtocoloDTO();
          $dto->retNumIdBloco();
          $dto->retStrProtocoloFormatadoProtocolo();
          $dto->setDblIdProtocolo($objRelBlocoProtocoloDTO->getDblIdProtocolo());
          $dto->setNumIdBloco($objRelBlocoProtocoloDTO->getNumIdBloco());

          $dto = $objRelBlocoProtocoloRN->consultarRN1290($dto);

          //if (count($dto) > 0) {
          if ($dto != null) {
            $objInfraException->adicionarValidacao('Protocolo ' . $dto->getStrProtocoloFormatadoProtocolo() . ' já consta no bloco ' . $dto->getNumIdBloco() . '.');
          }

          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retStrStaNivelAcessoGlobal();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->retStrStaProtocolo();
          $objProtocoloDTO->retStrStaDocumentoDocumento();
          $objProtocoloDTO->setDblIdProtocolo($objRelBlocoProtocoloDTO->getDblIdProtocolo());

          $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

          if ($objProtocoloDTO == null) {
            $objInfraException->lancarValidacao('Processo ou Documento não encontrado.');
          }

          if (!isset($arrObjProtocoloDTO[$objRelBlocoProtocoloDTO->getDblIdProtocolo()])) {
            $objInfraException->adicionarValidacao('Unidade não têm acesso ao protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . '.');
          }

          //if ($arrObjProtocoloDTO[$objRelBlocoProtocoloDTO->getDblIdProtocolo()]->getStrSinAberto() == 'N') {
          //  $objInfraException->adicionarValidacao('Protocolo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não está aberto na unidade.');
          //}

          if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
            $objInfraException->adicionarValidacao(($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO?'Processo':'Documento').' sigiloso ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' não pode ser incluído em bloco.');
          }

          if ($arrObjBlocoDTO[$objRelBlocoProtocoloDTO->getNumIdBloco()]->getStrStaTipo() != BlocoRN::$TB_ASSINATURA && $objProtocoloDTO->getStrStaProtocolo() != ProtocoloRN::$TP_PROCEDIMENTO) {

            if ($arrObjBlocoDTO[$objRelBlocoProtocoloDTO->getNumIdBloco()]->getStrStaTipo() == BlocoRN::$TB_REUNIAO) {
              $objInfraException->adicionarValidacao('Não é possível adicionar o documento ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' em um bloco de reunião.');
            } else if ($arrObjBlocoDTO[$objRelBlocoProtocoloDTO->getNumIdBloco()]->getStrStaTipo() == BlocoRN::$TB_INTERNO) {
              $objInfraException->adicionarValidacao('Não é possível adicionar o documento ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' em um bloco interno.');
            }

          }

          if ($arrObjBlocoDTO[$objRelBlocoProtocoloDTO->getNumIdBloco()]->getStrStaTipo() == BlocoRN::$TB_ASSINATURA) {
            if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_DOCUMENTO_RECEBIDO) {
              $objInfraException->adicionarValidacao('Não é possível adicionar o documento externo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' em um bloco de assinatura.');
            } else if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
              $objInfraException->adicionarValidacao('Não é possível adicionar o processo ' . $objProtocoloDTO->getStrProtocoloFormatado() . ' em um bloco de assinatura.');
            } else if ($objProtocoloDTO->getStrStaDocumentoDocumento() != DocumentoRN::$TD_EDITOR_INTERNO && $objProtocoloDTO->getStrStaDocumentoDocumento() != DocumentoRN::$TD_FORMULARIO_GERADO){
              $objInfraException->adicionarValidacao('Somente documentos gerados no editor ou formulários podem ser adicionados em bloco de assinatura.');
            }
          }

        }
        $objInfraException->lancarValidacoes();

        foreach ($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO) {

          $dto = new RelBlocoProtocoloDTO();
          $dto->retNumSequencia();
          $dto->setNumIdBloco($objRelBlocoProtocoloDTO->getNumIdBloco());
          $dto->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_DESC);
          $dto->setNumMaxRegistrosRetorno(1);

          $dto = $this->consultarRN1290($dto);

          if ($dto == null) {
            $objRelBlocoProtocoloDTO->setNumSequencia(1);
          } else {
            $objRelBlocoProtocoloDTO->setNumSequencia($dto->getNumSequencia() + 1);
          }

          $this->cadastrarRN1287($objRelBlocoProtocoloDTO);

          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retDblIdProtocolo();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->retStrStaProtocolo();
          $objProtocoloDTO->setDblIdProtocolo($objRelBlocoProtocoloDTO->getDblIdProtocolo());

          $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {

            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('BLOCO');
            $objAtributoAndamentoDTO->setStrValor($objRelBlocoProtocoloDTO->getNumIdBloco());
            $objAtributoAndamentoDTO->setStrIdOrigem($objRelBlocoProtocoloDTO->getNumIdBloco());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_INCLUIDO_EM_BLOCO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            $objAtividadeRN = new AtividadeRN();
            $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

          } else {

            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTO->getDblIdProtocolo());
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('BLOCO');
            $objAtributoAndamentoDTO->setStrValor($objRelBlocoProtocoloDTO->getNumIdBloco());
            $objAtributoAndamentoDTO->setStrIdOrigem($objRelBlocoProtocoloDTO->getNumIdBloco());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
            $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
            $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DOCUMENTO_INCLUIDO_EM_BLOCO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            $objAtividadeRN = new AtividadeRN();
            $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);

          }
        }

        $objBlocoRN->removerRevisao($arrObjBlocoDTO);

      }

      //Auditoria

      return true;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando protocolos no bloco.',$e);
    }
  }
  
  protected function alterarRN1288Controlado(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_alterar',__METHOD__,$objRelBlocoProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelBlocoProtocoloDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocoloRN1285($objRelBlocoProtocoloDTO, $objInfraException);
      }
      
      if ($objRelBlocoProtocoloDTO->isSetNumIdBloco()){
        $this->validarNumIdBlocoRN1286($objRelBlocoProtocoloDTO, $objInfraException);
      }
      
      if ($objRelBlocoProtocoloDTO->isSetStrAnotacao()){
        $this->validarStrAnotacao($objRelBlocoProtocoloDTO, $objInfraException);
      }

      $dto = new RelBlocoProtocoloDTO();
      $dto->retDblIdProtocolo();
      $dto->setNumIdBloco($objRelBlocoProtocoloDTO->getNumIdBloco());
      $dto->setDblIdProtocolo($objRelBlocoProtocoloDTO->getDblIdProtocolo());
      
      if ($this->consultarRN1290($dto)==null){
        $objInfraException->adicionarValidacao('Documento não encontrado no bloco.');
      }
      
      $objInfraException->lancarValidacoes();

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      $objRelBlocoProtocoloBD->alterar($objRelBlocoProtocoloDTO);

      $this->montarIndexacao($objRelBlocoProtocoloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando protocolo no bloco.',$e);
    }
  }

  protected function excluirRN1289Controlado($arrObjRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_excluir',__METHOD__, $arrObjRelBlocoProtocoloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

     	$objBlocoRN = new BlocoRN();
     	$objProtocoloRN = new ProtocoloRN();

     	if (count($arrObjRelBlocoProtocoloDTO)) {

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO, 'IdProtocolo'), InfraDTO::$OPER_IN);

        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO), 'IdProtocolo');

        $objBlocoDTO = new BlocoDTO();
        $objBlocoDTO->setDistinct(true);
        $objBlocoDTO->retNumIdUnidade();
        $objBlocoDTO->retNumIdBloco();
        $objBlocoDTO->retStrStaTipo();
        $objBlocoDTO->retStrStaEstado();
        $objBlocoDTO->setNumIdBloco(InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO, 'IdBloco'), InfraDTO::$OPER_IN);

        $arrObjBlocoDTO = InfraArray::indexarArrInfraDTO($objBlocoRN->listarRN1277($objBlocoDTO), 'IdBloco');

        foreach ($arrObjBlocoDTO as $objBlocoDTO) {
          if ($objBlocoDTO->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $objInfraException->adicionarValidacao('Bloco ' . $objBlocoDTO->getNumIdBloco() . ' não pertence à unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '.');
          } else if ($objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_DISPONIBILIZADO) {
            $objInfraException->adicionarValidacao('Bloco ' . $objBlocoDTO->getNumIdBloco() . ' não pode estar disponibilizado.');
          }
        }

        for ($i = 0; $i < count($arrObjRelBlocoProtocoloDTO); $i++) {

          if (!isset($arrObjBlocoDTO[$arrObjRelBlocoProtocoloDTO[$i]->getNumIdBloco()])) {
            $objInfraException->lancarValidacao('Bloco ' . $arrObjRelBlocoProtocoloDTO[$i]->getNumIdBloco() . ' não encontrado.');
          }

          if (!isset($arrObjProtocoloDTO[$arrObjRelBlocoProtocoloDTO[$i]->getDblIdProtocolo()])) {
            $objInfraException->lancarValidacao('Protocolo não encontrado.');
          }

          if ($arrObjBlocoDTO[$arrObjRelBlocoProtocoloDTO[$i]->getNumIdBloco()]->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $objInfraException->lancarValidacao('Bloco ' . $arrObjRelBlocoProtocoloDTO[$i]->getNumIdBloco() . ' não pertence à unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . '.');
          }

          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->retDblIdProtocolo();
          $objRelBlocoProtocoloDTO->setNumIdBloco($arrObjRelBlocoProtocoloDTO[$i]->getNumIdBloco());
          $objRelBlocoProtocoloDTO->setDblIdProtocolo($arrObjRelBlocoProtocoloDTO[$i]->getDblIdProtocolo());

          if ($this->consultarRN1290($objRelBlocoProtocoloDTO) == null) {
            $objInfraException->adicionarValidacao('Protocolo ' . $arrObjProtocoloDTO[$arrObjRelBlocoProtocoloDTO[$i]->getDblIdProtocolo()]->getStrProtocoloFormatado() . ' não consta no bloco.');
          }
        }

        $objInfraException->lancarValidacoes();


        $objProtocoloRN = new ProtocoloRN();

        $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());

        foreach ($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO) {

          $objRelBlocoProtocoloBD->excluir($objRelBlocoProtocoloDTO);

          $objProtocoloDTO = $arrObjProtocoloDTO[$objRelBlocoProtocoloDTO->getDblIdProtocolo()];

          if ($objProtocoloDTO->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {

            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('BLOCO');
            $objAtributoAndamentoDTO->setStrValor($objRelBlocoProtocoloDTO->getNumIdBloco());
            $objAtributoAndamentoDTO->setStrIdOrigem($objRelBlocoProtocoloDTO->getNumIdBloco());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($objProtocoloDTO->getDblIdProtocolo());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_RETIRADO_DO_BLOCO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            $objAtividadeRN = new AtividadeRN();

            $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
          } else {

            $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
            $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
            $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProtocoloDTO->getDblIdProtocolo());
            $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);

            $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
            $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

            $arrObjAtributoAndamentoDTO = array();
            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('BLOCO');
            $objAtributoAndamentoDTO->setStrValor($objRelBlocoProtocoloDTO->getNumIdBloco());
            $objAtributoAndamentoDTO->setStrIdOrigem($objRelBlocoProtocoloDTO->getNumIdBloco());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
            $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
            $objAtributoAndamentoDTO->setStrValor($objProtocoloDTO->getStrProtocoloFormatado());
            $objAtributoAndamentoDTO->setStrIdOrigem($objProtocoloDTO->getDblIdProtocolo());
            $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo1());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_DOCUMENTO_RETIRADO_DO_BLOCO);
            $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);

            $objAtividadeRN = new AtividadeRN();

            $objAtividadeDTO = $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
          }
        }

        $objBlocoRN->removerRevisao($arrObjBlocoDTO);

      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo protocolos do bloco.',$e);
    }
  }

  protected function listarProtocolosBlocoConectado(RelBlocoProtocoloDTO $parObjRelBlocoProtocoloDTO){
    try {

      $ret = array();

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_listar',__METHOD__,$parObjRelBlocoProtocoloDTO);

      $parObjRelBlocoProtocoloDTO->retDblIdProtocolo();

      $parObjRelBlocoProtocoloDTO = InfraString::prepararPesquisaDTO($parObjRelBlocoProtocoloDTO,"PalavrasPesquisa","IdxRelBlocoProtocolo");

      $arrObjRelProtocoloBlocoDTO = $this->listarRN1291($parObjRelBlocoProtocoloDTO);

      if (count($arrObjRelProtocoloBlocoDTO)) {

        $arrIdProtocoloPartes = array_chunk(array_unique(InfraArray::converterArrInfraDTO($arrObjRelProtocoloBlocoDTO,'IdProtocolo')), 500);

        $objProtocoloRN = new ProtocoloRN();
        $objAssinaturaRN = new AssinaturaRN();

        foreach($arrIdProtocoloPartes as $arrIdProtocolo) {

          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS_EXCETO_SIGILOSOS_SEM_ACESSO);
          $objPesquisaProtocoloDTO->setDblIdProtocolo($arrIdProtocolo);
          $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

          if (count($arrObjProtocoloDTO)) {

            if ($parObjRelBlocoProtocoloDTO->isRetArrObjAssinaturaDTO()) {
              $objAssinaturaDTO = new AssinaturaDTO();
              $objAssinaturaDTO->retDblIdDocumento();
              $objAssinaturaDTO->retStrNome();
              $objAssinaturaDTO->retStrTratamento();
              $objAssinaturaDTO->retDthAberturaAtividade();
              $objAssinaturaDTO->retNumIdUsuario();
              $objAssinaturaDTO->retStrIdOrigemUsuario();
              $objAssinaturaDTO->retNumIdOrgaoUsuario();
              $objAssinaturaDTO->retStrSiglaUsuario();
              $objAssinaturaDTO->setDblIdDocumento(array_keys($arrObjProtocoloDTO), InfraDTO::$OPER_IN);
              $arrObjAssinaturaDTO = InfraArray::indexarArrInfraDTO($objAssinaturaRN->listarRN1323($objAssinaturaDTO), 'IdDocumento', true);
            }

            $numItens = count($arrObjRelProtocoloBlocoDTO);

            for($i = 0; $i < $numItens; $i++) {

              $objRelBlocoProtocoloDTO = $arrObjRelProtocoloBlocoDTO[$i];

              $dblIdProtocolo = $objRelBlocoProtocoloDTO->getDblIdProtocolo();

              if (isset($arrObjProtocoloDTO[$dblIdProtocolo])) {

                $objRelBlocoProtocoloDTO->setObjProtocoloDTO($arrObjProtocoloDTO[$dblIdProtocolo]);

                if ($parObjRelBlocoProtocoloDTO->isRetArrObjAssinaturaDTO()) {
                  if (isset($arrObjAssinaturaDTO[$dblIdProtocolo])) {
                    $objRelBlocoProtocoloDTO->setArrObjAssinaturaDTO($arrObjAssinaturaDTO[$dblIdProtocolo]);
                  } else {
                    $objRelBlocoProtocoloDTO->setArrObjAssinaturaDTO(array());
                  }
                }

                $ret[] = $objRelBlocoProtocoloDTO;

                unset($arrObjRelProtocoloBlocoDTO[$i]);
              }
            }

            //refaz numeracao de indices sem duplicar array
            array_splice($arrObjRelProtocoloBlocoDTO, 0, 0);
          }
        }
      }

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando protocolos do bloco.',$e);
    }
  }

  protected function consultarRN1290Conectado(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_consultar',__METHOD__,$objRelBlocoProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoProtocoloBD->consultar($objRelBlocoProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando protocolo do bloco.',$e);
    }
  }

  protected function listarRN1291Conectado(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_listar',__METHOD__,$objRelBlocoProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      
      /*
      if ($objRelBlocoProtocoloDTO->isRetStrSinAberto()){
        $objRelBlocoProtocoloDTO->retDblIdProtocolo();
      }
      */

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoProtocoloBD->listar($objRelBlocoProtocoloDTO);

      /*
      if (count($ret)>0){
        if ($objRelBlocoProtocoloDTO->isRetStrSinAberto()){
          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
          $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($ret,'IdProtocolo'));
          
          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);
          
          foreach($ret as $objRelBlocoProtocoloDTO){
            $objRelBlocoProtocoloDTO->setStrSinAberto('N');
            foreach($arrObjProtocoloDTO as $objProtocoloDTO){
              if ($objRelBlocoProtocoloDTO->getDblIdProtocolo()==$objProtocoloDTO->getDblIdProtocolo()){
                $objRelBlocoProtocoloDTO->setStrSinAberto('S');
                break;
              }
            }
          }
        }
      }
      */
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando protocolos do bloco.',$e);
    }
  }

  protected function contarRN1292Conectado(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_listar',__METHOD__,$objRelBlocoProtocoloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoProtocoloBD->contar($objRelBlocoProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando protocolos do bloco.',$e);
    }
  }

  public function montarIdxRelBlocoProtocolo(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO){
    $strIndexacao = '';
    $strIndexacao .= ' '.$objRelBlocoProtocoloDTO->getStrProtocoloProcedimentoFormatado();
    $strIndexacao .= ' '.$objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo();
    $strIndexacao .= ' '.$objRelBlocoProtocoloDTO->getStrAnotacao();
    $strIndexacao = InfraString::prepararIndexacao($strIndexacao);
    $strIndexacao = substr($strIndexacao,0,4000);
    $objRelBlocoProtocoloDTO->setStrIdxRelBlocoProtocolo($strIndexacao);
  }

  protected function montarIndexacaoControlado(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO){

    $dto = new RelBlocoProtocoloDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retDblIdProtocolo();
    $dto->retNumIdBloco();
    $dto->retStrAnotacao();
    $dto->retStrProtocoloFormatadoProtocolo();
    $dto->retStrProtocoloProcedimentoFormatado();
    $dto->setNumIdBloco($objRelBlocoProtocoloDTO->getNumIdBloco());
    $dto->setDblIdProtocolo($objRelBlocoProtocoloDTO->getDblIdProtocolo());

    $dto = $this->consultarRN1290Conectado($dto);

    $this->montarIdxRelBlocoProtocolo($dto);

    $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
    $objRelBlocoProtocoloBD->alterar($dto);
  }

/* 
  protected function desativarRN1293Controlado($arrObjRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBlocoProtocoloDTO);$i++){
        $objRelBlocoProtocoloBD->desativar($arrObjRelBlocoProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Rel_Bloco_Protocolo.',$e);
    }
  }

  protected function reativarRN1294Controlado($arrObjRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBlocoProtocoloDTO);$i++){
        $objRelBlocoProtocoloBD->reativar($arrObjRelBlocoProtocoloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Rel_Bloco_Protocolo.',$e);
    }
  }

  protected function bloquearRN1295Controlado(RelBlocoProtocoloDTO $objRelBlocoProtocoloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_protocolo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoProtocoloBD = new RelBlocoProtocoloBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoProtocoloBD->bloquear($objRelBlocoProtocoloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Rel_Bloco_Protocolo.',$e);
    }
  }

 */


}
?>