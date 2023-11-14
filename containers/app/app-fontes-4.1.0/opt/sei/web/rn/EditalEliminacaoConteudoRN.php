<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoConteudoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAvaliacaoDocumental(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoConteudoDTO->getNumIdAvaliacaoDocumental())){
      $objInfraException->adicionarValidacao('Avaliação Documental não informada.');
    }
  }

  private function validarNumIdEditalEliminacao(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacao())){
      $objInfraException->adicionarValidacao('Edital de Eliminação não informado.');
    }
  }

  private function validarNumIdUsuarioInclusao(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoConteudoDTO->getNumIdUsuarioInclusao())){
      $objInfraException->adicionarValidacao('Usuário de Inclusão não informado.');
    }
  }

  private function validarDthInclusao(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoConteudoDTO->getDthInclusao())){
      $objInfraException->adicionarValidacao('Data de Inclusão não informada.');
    }else{
      if (!InfraData::validarDataHora($objEditalEliminacaoConteudoDTO->getDthInclusao())){
        $objInfraException->adicionarValidacao('Data de Inclusão inválida.');
      }
    }
  }

  protected function cadastrarControlado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_cadastrar',__METHOD__,$objEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAvaliacaoDocumental($objEditalEliminacaoConteudoDTO, $objInfraException);
      $this->validarNumIdEditalEliminacao($objEditalEliminacaoConteudoDTO, $objInfraException);
      $this->validarNumIdUsuarioInclusao($objEditalEliminacaoConteudoDTO, $objInfraException);
      $this->validarDthInclusao($objEditalEliminacaoConteudoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoConteudoBD->cadastrar($objEditalEliminacaoConteudoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Conteúdo do Edital de Eliminação.',$e);
    }
  }

  protected function alterarControlado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_alterar',__METHOD__,$objEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objEditalEliminacaoConteudoDTO->isSetNumIdAvaliacaoDocumental()){
        $this->validarNumIdAvaliacaoDocumental($objEditalEliminacaoConteudoDTO, $objInfraException);
      }
      if ($objEditalEliminacaoConteudoDTO->isSetNumIdEditalEliminacao()){
        $this->validarNumIdEditalEliminacao($objEditalEliminacaoConteudoDTO, $objInfraException);
      }
      if ($objEditalEliminacaoConteudoDTO->isSetNumIdUsuarioInclusao()){
        $this->validarNumIdUsuarioInclusao($objEditalEliminacaoConteudoDTO, $objInfraException);
      }
      if ($objEditalEliminacaoConteudoDTO->isSetDthInclusao()){
        $this->validarDthInclusao($objEditalEliminacaoConteudoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $objEditalEliminacaoConteudoBD->alterar($objEditalEliminacaoConteudoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Conteúdo do Edital de Eliminação.',$e);
    }
  }

  protected function excluirControlado($arrObjEditalEliminacaoConteudoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_excluir',__METHOD__,$arrObjEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      //variavel que contem o id do edital de eliminacao
      $numIdEditalEliminacao = null;
      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      //itera pelos conteudos (processos)
      for($i=0;$i<count($arrObjEditalEliminacaoConteudoDTO);$i++){
        //dto para consultar o processo
        $objEditalEliminacaoConteudoDTO_Banco = new EditalEliminacaoConteudoDTO();
        $objEditalEliminacaoConteudoDTO_Banco->retNumIdEditalEliminacao();
        $objEditalEliminacaoConteudoDTO_Banco->retStrStaSituacaoAvaliacaoDocumental();
        $objEditalEliminacaoConteudoDTO_Banco->retDblIdProcedimentoAvaliacaoDocumental();
        $objEditalEliminacaoConteudoDTO_Banco->retStrStaSituacaoEditalEliminacao();
        $objEditalEliminacaoConteudoDTO_Banco->retStrProtocoloProcedimentoFormatado();
        //seta o id
        $objEditalEliminacaoConteudoDTO_Banco->setNumIdEditalEliminacaoConteudo($arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo());
        //consulta
        $objEditalEliminacaoConteudoDTO_Banco = $objEditalEliminacaoConteudoBD->consultar($objEditalEliminacaoConteudoDTO_Banco);
        //se for null, seta (o teste é para setar apenas na primeira vez)
        if($numIdEditalEliminacao == null) {
          $numIdEditalEliminacao = $objEditalEliminacaoConteudoDTO_Banco->getNumIdEditalEliminacao();
        }
        //edital de eliminacao em situacao de montagem ou publicado, nao pode ter processos excluidos
        if($objEditalEliminacaoConteudoDTO_Banco->getStrStaSituacaoEditalEliminacao() != EditalEliminacaoRN::$TE_MONTAGEM && $objEditalEliminacaoConteudoDTO_Banco->getStrStaSituacaoEditalEliminacao() != EditalEliminacaoRN::$TE_PUBLICADO){
          $objInfraException->adicionarValidacao("Situação do Edital de Eliminação não permite exclusão de processos.");
        }
        //avaliacao documental em situacao de comissao, nao pode ter processos excluidos
        if($objEditalEliminacaoConteudoDTO_Banco->getStrStaSituacaoAvaliacaoDocumental() != AvaliacaoDocumentalRN::$TA_COMISSAO){
          $objInfraException->adicionarValidacao("Situação da Avaliação Documental do processo ".$objEditalEliminacaoConteudoDTO_Banco->getStrProtocoloProcedimentoFormatado()." não permite exclusão do processo.");
        }
        $objEditalEliminacaoConteudoBD->excluir($arrObjEditalEliminacaoConteudoDTO[$i]);
      }
      //lanca possiveis excecoes
      $objInfraException->lancarValidacoes();

      //busca o edital de eliminacao, para ver sua situacao
      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      //retorna a situacao
      $objEditalEliminacaoDTO->retStrStaEditalEliminacao();
      $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
      $objEditalEliminacaoDTO->retDblIdDocumento();
      $objEditalEliminacaoDTO->retStrDocumentoFormatado();
      //filtra pelo id
      $objEditalEliminacaoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
      //consulta
      $objEditalEliminacaoRN = new EditalEliminacaoRN();
      $objEditalEliminacaoDTO = $objEditalEliminacaoRN->consultar($objEditalEliminacaoDTO);
      //se a situacao do edital de eliminacao for publicado, lanca andamento
      if($objEditalEliminacaoDTO->getStrStaEditalEliminacao() == EditalEliminacaoRN::$TE_PUBLICADO){
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($objEditalEliminacaoConteudoDTO_Banco->getDblIdProcedimentoAvaliacaoDocumental());
        //array com processos para lancar andamentos
        $arrObjProcessos = array($objProcedimentoDTO);
        //busca os processos anexados (se existirem), para lancar os andamentos neles tambem
        $objProcedimentoRN = new ProcedimentoRN();
        $arrObjProcedimentosDTO_Anexados = $objProcedimentoRN->listarProcessosAnexados($objProcedimentoDTO);
        if(InfraArray::contar($arrObjProcedimentosDTO_Anexados) > 0){
          $arrObjProcessos = array_merge($arrObjProcessos, $arrObjProcedimentosDTO_Anexados);
        }
        //itera pelos processos (o processo excluido e seus anexados)
        foreach ($arrObjProcessos as  $objProcedimentoDTO) {
          //lanca os andamentos
          $arrObjAtributoAndamentoDTO = array();
          $objAtributoAndamentoDTO = new AtributoAndamentoDTO();
          $objAtributoAndamentoDTO->setStrNome('DOCUMENTO');
          $objAtributoAndamentoDTO->setStrValor($objEditalEliminacaoDTO->getStrDocumentoFormatado());
          $objAtributoAndamentoDTO->setStrIdOrigem($objEditalEliminacaoDTO->getDblIdDocumento());
          $arrObjAtributoAndamentoDTO[] = $objAtributoAndamentoDTO;

          $objAtividadeDTO = new AtividadeDTO();
          $objAtividadeDTO->setDblIdProtocolo($objProcedimentoDTO->getDblIdProcedimento());
          $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAtividadeDTO->setNumIdTarefa(TarefaRN::$TI_PROCESSO_RETIRADA_EDITAL_ELIMINACAO);
          $objAtividadeDTO->setArrObjAtributoAndamentoDTO($arrObjAtributoAndamentoDTO);
          $objAtividadeRN = new AtividadeRN();
          $objAtividadeRN->gerarInternaRN0727($objAtividadeDTO);
        }
      }

      //busca os processos restantes do edital de eliminacao, pois se nao restar nenhum, altera a situacao do edital de eliminacao para Cadastrados
      $objEditalEliminacaoConteudoDTO_Banco = new EditalEliminacaoConteudoDTO();
      //seta id do edital
      $objEditalEliminacaoConteudoDTO_Banco->setNumIdEditalEliminacao($numIdEditalEliminacao);
      //testa se nao tem registros e a situacao do edital é diferente de publicado
      if($objEditalEliminacaoConteudoBD->contar($objEditalEliminacaoConteudoDTO_Banco) == 0 && $objEditalEliminacaoDTO->getStrStaEditalEliminacao() != EditalEliminacaoRN::$TE_PUBLICADO){
        //nova situacao
        $objEditalEliminacaoDTO->setStrStaEditalEliminacao(EditalEliminacaoRN::$TE_CADASTRADO);
        //altera edital
        $objEditalEliminacaoRN->alterar($objEditalEliminacaoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Conteúdo do Edital de Eliminação.',$e);
    }
  }

  protected function consultarConectado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_consultar',__METHOD__,$objEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoConteudoBD->consultar($objEditalEliminacaoConteudoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Conteúdo do Edital de Eliminação.',$e);
    }
  }

  protected function listarConectado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_listar',__METHOD__,$objEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoConteudoBD->listar($objEditalEliminacaoConteudoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Conteúdos do Edital de Eliminação.',$e);
    }
  }

  //metodo que retorna o atributo QtdArquivamentosRemanescentes dos editais de eliminacao conteudo (que sao as quantidades de documentos arquivados do processo)
  protected function listarComQuantidadeDocumentosConectado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_listar',__METHOD__,$objEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      //lista
      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoBD->listar($objEditalEliminacaoConteudoDTO);
      if(InfraArray::contar($arrObjEditalEliminacaoConteudoDTO)) {
        //ids dos processos do edital de eliminacao
        $arrIdProcedimento = InfraArray::converterArrInfraDTO($arrObjEditalEliminacaoConteudoDTO, "IdProcedimentoAvaliacaoDocumental");

        $objProcedimentoRN = new ProcedimentoRN();
        //lista os processos anexados dos processos retornados na lista
        $arrObjRelProtocoloProtocoloDTO = $objProcedimentoRN->listarRelProtocoloAnexados(InfraArray::gerarArrInfraDTO('ProcedimentoDTO', 'IdProcedimento', $arrIdProcedimento));
        $arrObjRelProtocoloProtocoloDTO_AnexadosIndexado = array();
        if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO)) {
          $arrObjRelProtocoloProtocoloDTO_AnexadosIndexado = InfraArray::indexarArrInfraDTO($arrObjRelProtocoloProtocoloDTO, "IdProtocolo1", true);
        }

        if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO) > 0) {
          $arrIdProcedimento = array_merge($arrIdProcedimento, InfraArray::converterArrInfraDTO($arrObjRelProtocoloProtocoloDTO, "IdProtocolo2"));
        }

        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->retDblIdProcedimentoDocumento();
        $objArquivamentoDTO->setDblIdProcedimentoDocumento($arrIdProcedimento, InfraDTO::$OPER_IN);
        $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_RECEBIDO, ArquivamentoRN::$TA_ARQUIVADO, ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO), InfraDTO::$OPER_IN);
        $objArquivamentoRN = new ArquivamentoRN();
        $arrObjArquivamentoDTO = $objArquivamentoRN->listar($objArquivamentoDTO);
        $arrObjArquivamentoDTO_Indexado = InfraArray::indexarArrInfraDTO($arrObjArquivamentoDTO, "IdProcedimentoDocumento", true);
        foreach ($arrObjEditalEliminacaoConteudoDTO as $objEditalEliminacaoConteudoDTO) {
          if (array_key_exists($objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental(), $arrObjArquivamentoDTO_Indexado)) {
            $numQtdDocumentosArquivados = count($arrObjArquivamentoDTO_Indexado[$objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental()]);
          } else {
            $numQtdDocumentosArquivados = 0;
          }
          if (array_key_exists($objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental(), $arrObjRelProtocoloProtocoloDTO_AnexadosIndexado)) {
            foreach ($arrObjRelProtocoloProtocoloDTO_AnexadosIndexado[$objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental()] as $objRelProtocoloProtocoloDTO) {
              if (array_key_exists($objRelProtocoloProtocoloDTO->getDblIdProtocolo2(), $arrObjArquivamentoDTO_Indexado)) {
                $numQtdDocumentosArquivados += count($arrObjArquivamentoDTO_Indexado[$objRelProtocoloProtocoloDTO->getDblIdProtocolo2()]);
              }
            }
          }
          $objEditalEliminacaoConteudoDTO->setNumQtdArquivamentosRemanescentes($numQtdDocumentosArquivados);

        }
      }

      return $arrObjEditalEliminacaoConteudoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando Conteúdos do Edital de Eliminação.',$e);
    }
  }


  protected function contarConectado(EditalEliminacaoConteudoDTO $objEditalEliminacaoConteudoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_conteudo_listar',__METHOD__,$objEditalEliminacaoConteudoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoConteudoBD->contar($objEditalEliminacaoConteudoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Conteúdos do Edital de Eliminação.',$e);
    }
  }

  protected  function adicionarControlado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {
      $objInfraException = new InfraException();
      //busca o edital de eliminacao no banco
      $objEditalEliminacaoRN = new EditalEliminacaoRN();
      $objEditalEliminacaoDTO_Banco = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO_Banco->retStrStaEditalEliminacao();
      $objEditalEliminacaoDTO_Banco->retDblIdProcedimento();
      $objEditalEliminacaoDTO_Banco->retDblIdDocumento();
      $objEditalEliminacaoDTO_Banco->retDtaPublicacao();
      $objEditalEliminacaoDTO_Banco->retNumIdEditalEliminacao();
      //seta o id do edital de eliminacao
      $objEditalEliminacaoDTO_Banco->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
      //consulta
      $objEditalEliminacaoDTO_Banco = $objEditalEliminacaoRN->consultar($objEditalEliminacaoDTO_Banco);
      //se a situacao do edital de eliminacao for diferente de Cadastro e Eliminacao, nao permite adicionar
      if($objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() != EditalEliminacaoRN::$TE_CADASTRADO && $objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() != EditalEliminacaoRN::$TE_MONTAGEM){
        $objInfraException->lancarValidacao("Situação do Edital de Eliminação não permite inclusão de processos");
      }
      $objEditalEliminacaoConteudoBD = new EditalEliminacaoConteudoBD($this->getObjInfraIBanco());
      $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
      //itera as avaliacoes documentais (processos) escolhidos na tela
      foreach ($objEditalEliminacaoDTO->getArrObjAvaliacaoDocumentalDTO() as $objAvaliacaoDocumentalDTO){
        //busca a avaliacao documental
        $objAvaliacaoDocumentalDTO_Banco = new AvaliacaoDocumentalDTO();
        $objAvaliacaoDocumentalDTO_Banco->retStrStaAvaliacao();
        $objAvaliacaoDocumentalDTO_Banco->retStrProtocoloFormatado();
        $objAvaliacaoDocumentalDTO_Banco->retNumIdAvaliacaoDocumental();
        //seta o id da avaliacao documental
        $objAvaliacaoDocumentalDTO_Banco->setNumIdAvaliacaoDocumental($objAvaliacaoDocumentalDTO->getNumIdAvaliacaoDocumental());
        //consulta
        $objAvaliacaoDocumentalDTO_Banco = $objAvaliacaoDocumentalRN->consultar($objAvaliacaoDocumentalDTO_Banco);
        //testa se a situacao da avaliacao documental é diferente de Comissao
        if($objAvaliacaoDocumentalDTO_Banco->getStrStaAvaliacao() != AvaliacaoDocumentalRN::$TA_COMISSAO){
          //nao permite adicionar se for diferente
          $objInfraException->adicionarValidacao("Situação da avaliação documental do processo ".$objAvaliacaoDocumentalDTO_Banco->getStrProtocoloFormatado()." não permite inclusão em Edital de Eliminação.");
        }else{
          //cria dto EditalEliminacaoConteudoDTO para cadastro do processo
          $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
          $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
          $objEditalEliminacaoConteudoDTO->setNumIdAvaliacaoDocumental($objAvaliacaoDocumentalDTO_Banco->getNumIdAvaliacaoDocumental());
          $objEditalEliminacaoConteudoDTO->setNumIdUsuarioInclusao(SessaoSEI::getInstance()->getNumIdUsuario());
          $objEditalEliminacaoConteudoDTO->setDthInclusao(InfraData::getStrDataHoraAtual());
          //cadastra
          $objEditalEliminacaoConteudoBD->cadastrar($objEditalEliminacaoConteudoDTO);
        }
      }
      $objInfraException->lancarValidacoes();
      //se a situacao do edital de eliminacao for Cadastrado, altera para Montagem (esse caso ocorre quando o edital nao tem nenhum processo)
      if($objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() == EditalEliminacaoRN::$TE_CADASTRADO){
        $objEditalEliminacaoDTO_Banco->setStrStaEditalEliminacao(EditalEliminacaoRN::$TE_MONTAGEM);
        $objEditalEliminacaoRN->alterar($objEditalEliminacaoDTO_Banco);
      }
    }catch(Exception $e){
      throw new InfraException('Erro adicionando processos ao Edital de Eliminação.',$e);
    }
  }
}
