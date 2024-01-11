<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/12/2018 - criado por cjy
 *
 * Versão do Gerador de Código: 1.42.0
 */

require_once dirname(__FILE__).'/../SEI.php';

class EditalEliminacaoRN extends InfraRN {

  //quando cadastrado
  public static $TE_CADASTRADO= 'C';
  //quando adicionado algum processo
  public static $TE_MONTAGEM= 'M';
  //quando edital gerado
  public static $TE_GERADO= 'G';
  //quando edital publicado
  public static $TE_PUBLICADO= 'B';
  //quando edital elminado, mas parcialmente, devido a algum processo que nao foi eliminado totalmente
  public static $TE_ELIMINACAO_PARCIAL= 'P';
  //quando editado eliminado totalmente
  public static $TE_ELIMINADO= 'E';

  public static $DTE_CADASTRADO= 'Cadastrado';
  public static $DTE_MONTAGEM= 'Montando Edital';
  public static $DTE_PUBLICADO= 'Publicado';
  public static $DTE_GERADO= 'Gerado';
  public static $DTE_ELIMINACAO_PARCIAL= 'Eliminação Parcial';
  public static $DTE_ELIMINADO= 'Eliminado';

  private static $ID_SERIE_EDITAL_ELIMINACAO_LISTAGEM_ELIMINADOS = "ID_SERIE_EDITAL_ELIMINACAO_LISTAGEM_ELIMINADOS";
  private static $ID_SERIE_EDITAL_ELIMINACAO = "ID_SERIE_EDITAL_ELIMINACAO";

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }


  public function buscarValorEditalEliminacao($strChave) {
    return   $this->listarValoresEditalEliminacao()[$strChave]->getStrDescricao();
  }

  public function listarValoresEditalEliminacao(){
    try {

      $arrObjEditalEliminacaoEditalEliminacaoDTO = array();

      $objEditalEliminacaoEditalEliminacaoDTO = new EditalEliminacaoEditalEliminacaoDTO();
      $objEditalEliminacaoEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_CADASTRADO);
      $objEditalEliminacaoEditalEliminacaoDTO->setStrDescricao(self::$DTE_CADASTRADO);
      $arrObjEditalEliminacaoEditalEliminacaoDTO[self::$TE_CADASTRADO] = $objEditalEliminacaoEditalEliminacaoDTO;

      $objEditalEliminacaoEditalEliminacaoDTO = new EditalEliminacaoEditalEliminacaoDTO();
      $objEditalEliminacaoEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_MONTAGEM);
      $objEditalEliminacaoEditalEliminacaoDTO->setStrDescricao(self::$DTE_MONTAGEM);
      $arrObjEditalEliminacaoEditalEliminacaoDTO[self::$TE_MONTAGEM] = $objEditalEliminacaoEditalEliminacaoDTO;

      $objEditalEliminacaoEditalEliminacaoDTO = new EditalEliminacaoEditalEliminacaoDTO();
      $objEditalEliminacaoEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_PUBLICADO);
      $objEditalEliminacaoEditalEliminacaoDTO->setStrDescricao(self::$DTE_PUBLICADO);
      $arrObjEditalEliminacaoEditalEliminacaoDTO[self::$TE_PUBLICADO] = $objEditalEliminacaoEditalEliminacaoDTO;

      $objEditalEliminacaoEditalEliminacaoDTO = new EditalEliminacaoEditalEliminacaoDTO();
      $objEditalEliminacaoEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_GERADO);
      $objEditalEliminacaoEditalEliminacaoDTO->setStrDescricao(self::$DTE_GERADO);
      $arrObjEditalEliminacaoEditalEliminacaoDTO[self::$TE_GERADO] = $objEditalEliminacaoEditalEliminacaoDTO;

      $objEditalEliminacaoEditalEliminacaoDTO = new EditalEliminacaoEditalEliminacaoDTO();
      $objEditalEliminacaoEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_ELIMINACAO_PARCIAL);
      $objEditalEliminacaoEditalEliminacaoDTO->setStrDescricao(self::$DTE_ELIMINACAO_PARCIAL);
      $arrObjEditalEliminacaoEditalEliminacaoDTO[self::$TE_ELIMINACAO_PARCIAL] = $objEditalEliminacaoEditalEliminacaoDTO;

      $objEditalEliminacaoEditalEliminacaoDTO = new EditalEliminacaoEditalEliminacaoDTO();
      $objEditalEliminacaoEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_ELIMINADO);
      $objEditalEliminacaoEditalEliminacaoDTO->setStrDescricao(self::$DTE_ELIMINADO);
      $arrObjEditalEliminacaoEditalEliminacaoDTO[self::$TE_ELIMINADO] = $objEditalEliminacaoEditalEliminacaoDTO;

      return $arrObjEditalEliminacaoEditalEliminacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de EditalEliminacao.',$e);
    }
  }

  private function validarDblIdProcedimento(EditalEliminacaoDTO $objEditalEliminacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoDTO->getDblIdProcedimento())){
      $objEditalEliminacaoDTO->setDblIdProcedimento(null);
    }
  }

  private function validarDblIdDocumento(EditalEliminacaoDTO $objEditalEliminacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoDTO->getDblIdDocumento())){
      $objEditalEliminacaoDTO->setDblIdDocumento(null);
    }
  }

  private function validarStrEspecificacao(EditalEliminacaoDTO $objEditalEliminacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoDTO->getStrEspecificacao())){
      $objInfraException->adicionarValidacao('Especificação não informada.');
    }else{
      $objEditalEliminacaoDTO->setStrEspecificacao(trim($objEditalEliminacaoDTO->getStrEspecificacao()));

      if (strlen($objEditalEliminacaoDTO->getStrEspecificacao())>100){
        $objInfraException->adicionarValidacao('Especificação possui tamanho superior a 100 caracteres.');
      }else{

        $objEditalEliminacaoDTO_Banco = new EditalEliminacaoDTO();
        $objEditalEliminacaoDTO_Banco->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao(),InfraDTO::$OPER_DIFERENTE);
        $objEditalEliminacaoDTO_Banco->setNumIdOrgaoUnidade(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
        $objEditalEliminacaoDTO_Banco->setStrEspecificacao($objEditalEliminacaoDTO->getStrEspecificacao());

        $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
        if($objEditalEliminacaoBD->contar($objEditalEliminacaoDTO_Banco) > 0){
          $objInfraException->adicionarValidacao('Especificação já existe para esse órgão.');
        }
      }
    }
  }

  private function validarDtaPublicacao(EditalEliminacaoDTO $objEditalEliminacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoDTO->getDtaPublicacao())){
      $objEditalEliminacaoDTO->setDtaPublicacao(null);
    }else{
      if (!InfraData::validarData($objEditalEliminacaoDTO->getDtaPublicacao())){
        $objInfraException->adicionarValidacao('Data de Publicação inválida.');
      }else if(InfraData::compararDatasSimples(InfraData::getStrDataAtual(), $objEditalEliminacaoDTO->getDtaPublicacao()) == 1){
        $objInfraException->adicionarValidacao('Data de Publicação não pode estar no futuro.');
      }
    }
  }

  private function validarStrStaEditalEliminacao(EditalEliminacaoDTO $objEditalEliminacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objEditalEliminacaoDTO->getStrStaEditalEliminacao())){
      $objInfraException->adicionarValidacao('Situação não informada.');
    }else{
      if (!in_array($objEditalEliminacaoDTO->getStrStaEditalEliminacao(),InfraArray::converterArrInfraDTO($this->listarValoresEditalEliminacao(),'StaEditalEliminacao'))){
        $objInfraException->adicionarValidacao('Situação inválida.');
      }
    }
  }

  //cadastro padrão
  protected function cadastrarControlado(EditalEliminacaoDTO $objEditalEliminacaoDTO) {
    try{

      $objEditalEliminacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_cadastrar',__METHOD__,$objEditalEliminacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProcedimento($objEditalEliminacaoDTO, $objInfraException);
      $this->validarDblIdDocumento($objEditalEliminacaoDTO, $objInfraException);
      $this->validarStrEspecificacao($objEditalEliminacaoDTO, $objInfraException);
      //$this->validarDtaPublicacao($objEditalEliminacaoDTO, $objInfraException);
      $this->validarStrStaEditalEliminacao($objEditalEliminacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoBD->cadastrar($objEditalEliminacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Edital de Eliminação.',$e);
    }
  }
  //devem ser feitas validacoes e alteracies se foi alterada a data de publicacao
  protected function alterarControlado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_alterar',__METHOD__,$objEditalEliminacaoDTO);

      $objInfraException = new InfraException();

      //validacoes padroes
      if ($objEditalEliminacaoDTO->isSetDblIdProcedimento()){
        $this->validarDblIdProcedimento($objEditalEliminacaoDTO, $objInfraException);
      }
      if ($objEditalEliminacaoDTO->isSetDblIdDocumento()){
        $this->validarDblIdDocumento($objEditalEliminacaoDTO, $objInfraException);
      }
      if ($objEditalEliminacaoDTO->isSetStrEspecificacao()){
        $this->validarStrEspecificacao($objEditalEliminacaoDTO, $objInfraException);
      }
//      if ($objEditalEliminacaoDTO->isSetDtaPublicacao()){
//        $this->validarDtaPublicacao($objEditalEliminacaoDTO, $objInfraException);
//      }
      if ($objEditalEliminacaoDTO->isSetStrStaEditalEliminacao()){
        $this->validarStrStaEditalEliminacao($objEditalEliminacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      //se setou data de publicacao, busca o edital de elimacao do banco, pois se a data foi alterada, faz alguns testes
     /* if($objEditalEliminacaoDTO->isSetDtaPublicacao()){
        $objEditalEliminacaoDTO_Banco = new EditalEliminacaoDTO();
        $objEditalEliminacaoDTO_Banco->retDtaPublicacao();
        $objEditalEliminacaoDTO_Banco->retDtaPublicacaoPublicacao();
        $objEditalEliminacaoDTO_Banco->retDblIdDocumento();
        $objEditalEliminacaoDTO_Banco->retStrStaEditalEliminacao();
        $objEditalEliminacaoDTO_Banco->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
        //busca no banco
        $objEditalEliminacaoDTO_Banco = $this->consultarConectado($objEditalEliminacaoDTO_Banco);
        //testa se a data foi altarada na tela
        if($objEditalEliminacaoDTO->getDtaPublicacao() != $objEditalEliminacaoDTO_Banco->getDtaPublicacao()) {
          //busca situacao do edital
          $strStaEditalChave = $objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao();
          //se já foi eliminado (mesmo que parcialmente)
          if ($strStaEditalChave == self::$TE_ELIMINADO || $strStaEditalChave == self::$TE_ELIMINACAO_PARCIAL) {
            $objInfraException->lancarValidacao("Não é possível alterar a Data de Publicação porque processos já foram eliminados.");
          }
          //existe registro de publicação para o edital (campo id_documento) e a data informada é diferente de data da publicacao
          if ($objEditalEliminacaoDTO_Banco->getDblIdDocumento() != null && $objEditalEliminacaoDTO_Banco->getDtaPublicacaoPublicacao() != $objEditalEliminacaoDTO->getDtaPublicacao()) {
            $objInfraException->lancarValidacao("Data de Publicação não pode ser alterada.");
          }
          //se a situacao é montagem ou publicado, a situacao pode ser altada conforme os prazos de eliminacao
          if ($strStaEditalChave == self::$TE_MONTAGEM || $strStaEditalChave == self::$TE_PUBLICADO) {
            //busca o parametro de prazo de eliminacao
            $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
            $strNumDiasPrazoEliminacao = trim($objInfraParametro->getValor("SEI_NUM_DIAS_PRAZO_ELIMINACAO", false));
            //busca a data referente a data de publicacao do edital somada aos dias do prazo de eliminacao
            $dtaPrazoEliminacao = InfraData::calcularData($strNumDiasPrazoEliminacao, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $objEditalEliminacaoDTO->getDtaPublicacao());
            //se a data encontrada é posterior a hoje, é publicado
            //senao pode ser montagem ainda
            if (InfraData::compararDatasSimples($dtaPrazoEliminacao, InfraData::getStrDataAtual()) >= 0) {
              $objEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_PUBLICADO);
            } else {
              $objEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_MONTAGEM);
            }
          }
        }
      }*/
      //altera
      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      $objEditalEliminacaoBD->alterar($objEditalEliminacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Edital de Eliminação.',$e);
    }
  }
  //devem ser feitas validacoes antes da exclusao conforme a situacao do edital de eliminacao
  protected function excluirControlado($arrObjEditalEliminacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_excluir',__METHOD__,$arrObjEditalEliminacaoDTO);

      $objInfraException = new InfraException();

      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      //itera pelos editais de eliminacao para exclusao
      for($i=0;$i<count($arrObjEditalEliminacaoDTO);$i++){
        $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
        $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
        $objEditalEliminacaoDTO->retStrStaEditalEliminacao();
        $objEditalEliminacaoDTO->setNumIdEditalEliminacao($arrObjEditalEliminacaoDTO[$i]->getNumIdEditalEliminacao());
        //busca o edital de eliminacao
        $objEditalEliminacaoDTO = $objEditalEliminacaoBD->consultar($objEditalEliminacaoDTO);
        switch ($objEditalEliminacaoDTO->getStrStaEditalEliminacao()){
          //se está cadastradao
          case self::$TE_CADASTRADO:
            //exclui
            $objEditalEliminacaoBD->excluir($arrObjEditalEliminacaoDTO[$i]);
            break;
          //se está eliminado ou parcialmente eliminado
          case self::$TE_ELIMINADO:
          case self::$TE_ELIMINACAO_PARCIAL:
            //nao pode excluir
            $objInfraException->adicionarValidacao("Não é possível excluir um edital que já têm processos eliminados." );
            break;
          //se já foi gerado
          case self::$TE_GERADO:
            //nao pode excluir
          $objInfraException->adicionarValidacao("Não é possível excluir um edital que foi gerado.");
            break;
          //se já foi publicado
          case self::$TE_PUBLICADO:
            //nao pode excluir
          $objInfraException->adicionarValidacao("Não é possível excluir um edital que foi publicado.");
            break;
          //se está em montagem
          case self::$TE_MONTAGEM:
            //nao pode excluir
          $objInfraException->adicionarValidacao("Não é possível excluir um edital que contém processos associados." );
            break;
        }
      }
      $objInfraException->lancarValidacoes();

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Edital de Eliminação.',$e);
    }
  }
  //metodo padrao
  protected function consultarConectado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_consultar',__METHOD__,$objEditalEliminacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoBD->consultar($objEditalEliminacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Edital de Eliminação.',$e);
    }
  }
//metodo padrao
  protected function listarConectado(EditalEliminacaoDTO $objEditalEliminacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_listar',__METHOD__,$objEditalEliminacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoBD->listar($objEditalEliminacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Editais de Eliminação.',$e);
    }
  }
//metodo padrao
  protected function contarConectado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('edital_eliminacao_listar',__METHOD__,$objEditalEliminacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      $ret = $objEditalEliminacaoBD->contar($objEditalEliminacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Editais de Eliminação.',$e);
    }
  }

  protected function removerProcedimentoControlado(ProcedimentoDTO $objProcedimentoDTO){
    try {

      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
      $objEditalEliminacaoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());

      $objEditalEliminacaoDTO = $this->consultar($objEditalEliminacaoDTO);

      if($objEditalEliminacaoDTO != null) {
        $objEditalEliminacaoDTO->setDblIdProcedimento(null);

        $objEditalEliminacaoBD = new EditalEliminacaoBD(BancoSEI::getInstance());
        $objEditalEliminacaoBD->alterar($objEditalEliminacaoDTO);
      }
    }catch(Exception $e){
      throw new InfraException('Erro removendo processo associado com edital de eliminação.', $e);
    }
  }

  //remove o documento (o edital em si) do edital de eliminacao
  //na pratica, seta para null o id_documento no edital_eliminacao
  protected function removerDocumentoControlado(DocumentoDTO  $objDocumentoDTO){
    try {

      $objInfraException = new InfraException();

      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
      $objEditalEliminacaoDTO->retDblIdDocumento();
      $objEditalEliminacaoDTO->retStrStaEditalEliminacao();
      $objEditalEliminacaoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

      $objEditalEliminacaoDTO = $this->consultar($objEditalEliminacaoDTO);

      if($objEditalEliminacaoDTO != null) {
        //se for publicado, eliminado ou eliminado parcialmente, nao pode excluir
        if ($objEditalEliminacaoDTO->getStrStaEditalEliminacao() == self::$TE_PUBLICADO) {
          $objInfraException->lancarValidacao("Documento associado com registro de Edital de Eliminação publicado.");
        } else if ($objEditalEliminacaoDTO->getStrStaEditalEliminacao() == self::$TE_ELIMINACAO_PARCIAL || $objEditalEliminacaoDTO->getStrStaEditalEliminacao() == self::$TE_ELIMINADO) {
          $objInfraException->lancarValidacao("Documento associado com registro de Edital de Eliminação que possui protocolos eliminados.");
          //senao seta o id_documento para nulo e altera o edital de eliminacao
        } else {
          $objEditalEliminacaoDTO->setDblIdDocumento(null);
          $objEditalEliminacaoDTO->setStrStaEditalEliminacao(self::$TE_MONTAGEM);

          $objEditalEliminacaoBD = new EditalEliminacaoBD(BancoSEI::getInstance());
          $objEditalEliminacaoBD->alterar($objEditalEliminacaoDTO);
        }
      }
    }catch(Exception $e){
      throw new InfraException('Erro removendo documento associado com edital de eliminação.', $e);
    }
  }
  //geracao do edital de eliminacao (do edital em si)
  //na pratica, sao gerados o processo (que gera o id_processo no edital_eliminacao) e o documento (que gera o id_documento no edital_eliminacao)
  protected function gerarControlado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {
      $objInfraException = new InfraException();
      //numero do edital de eliminacao
      $numIdEditalEliminacao = $objEditalEliminacaoDTO->getNumIdEditalEliminacao();
      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      //dto com edital de eliminacao do BD
      $objEditalEliminacaoDTO_Banco = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO_Banco->retDblIdProcedimento();
      $objEditalEliminacaoDTO_Banco->retDblIdDocumento();
      $objEditalEliminacaoDTO_Banco->retNumIdEditalEliminacao();
      $objEditalEliminacaoDTO_Banco->retStrStaEditalEliminacao();
      $objEditalEliminacaoDTO_Banco->setNumIdEditalEliminacao($numIdEditalEliminacao);
      //consulta no BD
      $objEditalEliminacaoDTO_Banco = $objEditalEliminacaoBD->consultar($objEditalEliminacaoDTO_Banco);
      //se a situacao é diferente de montagem, nao pode gerar
      if($objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() != self::$TE_MONTAGEM) {
        $objInfraException->lancarValidacao("Situação do edital não permite geração.");
      }else{
        //dto para buscar os conteudos (processos) que o edital de eliminacao contem
        $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
        $objEditalEliminacaoConteudoDTO->retNumIdAvaliacaoDocumental();
        $objEditalEliminacaoConteudoDTO->retNumIdUnidadeGeradoraProtocolo();
        $objEditalEliminacaoConteudoDTO->retDblIdProcedimentoAvaliacaoDocumental();
        $objEditalEliminacaoConteudoDTO->retStrProtocoloProcedimentoFormatado();
        $objEditalEliminacaoConteudoDTO->retStrNomeTipoProcedimento();
        $objEditalEliminacaoConteudoDTO->retStrSiglaOrgaoUnidadeGeradoraProtocolo();
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
        //lista os conteudos (processos) do edital
        $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
        $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);
        //tem que ter ao menos 1 processo para gerar
        if(InfraArray::contar($arrObjEditalEliminacaoConteudoDTO) == 0){
          $objInfraException->lancarValidacao("Não é possível gerar um edital para publicação sem processos associados.");
        }else{
          //itera pelos processos do edital, para validar a elimanacao
          foreach ($arrObjEditalEliminacaoConteudoDTO as $objEditalEliminacaoConteudoDTO){
            //busca o procedimento
            $objProcedimentoDTO = new ProcedimentoDTO();
            $objProcedimentoDTO->retDblIdProcedimento();
            //filtra pelo id da avaliacao documental do edital de eliminacao conteudo
            $objProcedimentoDTO->setNumIdAvaliacaoDocumentalEditalEliminacaoConteudo($objEditalEliminacaoConteudoDTO->getNumIdAvaliacaoDocumental());
            //consulta o procedimento
            $objProcedimentoRN = new ProcedimentoRN();
            $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
            //busca os processos anexados
            $objProcedimentoDTO->setArrObjProcedimentoAnexadoDTO($objProcedimentoRN->listarProcessosAnexados($objProcedimentoDTO));
            //valida o processo e seus anexados para eliminacao
            $objProcedimentoRN->validarEliminacao($objProcedimentoDTO);
          }
          //procedimento do edital de eliminacao
          $objProcedimentoDTO_Edital = new ProcedimentoDTO();
          //se nao existe um procedimento de edital de eliminicao
          if(InfraString::isBolVazia($objEditalEliminacaoDTO_Banco->getDblIdProcedimento())){
            //gera o processo
            $objProcedimentoDTO_Edital = $this->gerarProcessoEdital($objProcedimentoDTO_Edital);
            //seta o id_procedumento gerado no edital de eliminacao
            $objEditalEliminacaoDTO_Banco->setDblIdProcedimento($objProcedimentoDTO_Edital->getDblIdProcedimento());
          }else{
            //seta o id_procedumento existente no edital de eliminacao
            $objProcedimentoDTO_Edital->setDblIdProcedimento($objEditalEliminacaoDTO_Banco->getDblIdProcedimento());
          }
          //se nao existe um documento (um edital em si) de edital de eliminacao
          if(!InfraString::isBolVazia($objEditalEliminacaoDTO_Banco->getDblIdDocumento())){
            $objInfraException->lancarValidacao("Já existe um edital de eliminação no processo.");
          }else{
            //gera o documento (edital em si)
            $objDocumentoDTO_Edital = $this->gerarDocumentoEdital($objProcedimentoDTO_Edital, $arrObjEditalEliminacaoConteudoDTO, self::$ID_SERIE_EDITAL_ELIMINACAO );
            //seta o id_documento gerado no edital de elimanacao
            $objEditalEliminacaoDTO_Banco->setDblIdDocumento($objDocumentoDTO_Edital->getDblIdDocumento());
            //seta a data de eliminacao com a data atual
            //$objEditalEliminacaoDTO_Banco->setDtaPublicacao(InfraData::getStrDataAtual());
          }
        }
      }
      //altera a situacao para gerado
      $objEditalEliminacaoDTO_Banco->setStrStaEditalEliminacao(EditalEliminacaoRN::$TE_GERADO);
      //altera
      $objEditalEliminacaoBD->alterar($objEditalEliminacaoDTO_Banco);
      //lanca validacoes
      $objInfraException->lancarValidacoes();
    }catch(Exception $e){
      throw new InfraException('Erro gerando Edital de Eliminação.',$e);
    }

    return $objEditalEliminacaoDTO_Banco;
  }
  //geracao do documento contendo os processos eliminados do edital de eliminacao
  protected function gerarEliminadosControlado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {
      $objInfraException = new InfraException();
      //busca o edital de eliminacao
      $objEditalEliminacaoDTO_Banco = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO_Banco->retNumIdEditalEliminacao();
      $objEditalEliminacaoDTO_Banco->retDblIdProcedimento();
      $objEditalEliminacaoDTO_Banco->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
      $objEditalEliminacaoDTO = $this->consultarConectado($objEditalEliminacaoDTO_Banco);
      //dto para buscar os conteudos (processos) eliminados que o edital de eliminacao contem
      $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
      $objEditalEliminacaoConteudoDTO->retNumIdAvaliacaoDocumental();
      $objEditalEliminacaoConteudoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objEditalEliminacaoConteudoDTO->retDblIdProcedimentoAvaliacaoDocumental();
      $objEditalEliminacaoConteudoDTO->retStrProtocoloProcedimentoFormatado();
      $objEditalEliminacaoConteudoDTO->retStrNomeTipoProcedimento();
      $objEditalEliminacaoConteudoDTO->retStrSiglaOrgaoUnidadeGeradoraProtocolo();
      $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
      $objEditalEliminacaoConteudoDTO->setStrStaSituacaoAvaliacaoDocumental(AvaliacaoDocumentalRN::$TA_ELIMINADO);
      //lista os conteudos (processos) do edital
      $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
      $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);
      //itera pelos processos do edital, para validar a elimanacao
      foreach ($arrObjEditalEliminacaoConteudoDTO as $objEditalEliminacaoConteudoDTO){
        //busca o procedimento
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->retDblIdProcedimento();
        //filtra pelo id da avaliacao documental do edital de eliminacao conteudo
        $objProcedimentoDTO->setNumIdAvaliacaoDocumentalEditalEliminacaoConteudo($objEditalEliminacaoConteudoDTO->getNumIdAvaliacaoDocumental());
        //consulta o procedimento
        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoDTO = $objProcedimentoRN->consultarRN0201($objProcedimentoDTO);
        //busca os procedimentos anexados, pois esses também deverão ser validados
        $arrIdProcedimentosAnexados = InfraArray::converterArrInfraDTO($objProcedimentoRN->listarProcessosAnexados($objProcedimentoDTO),"IdProcedimento");
        //seta os processos anexados
        $objProcedimentoDTO->setArrObjProcedimentoAnexadoDTO(InfraArray::gerarArrInfraDTO('ProcedimentoDTO','IdProcedimento',$arrIdProcedimentosAnexados));
      }
      //procedimento do edital de eliminacao
      $objProcedimentoDTO_Edital = new ProcedimentoDTO();
      $objProcedimentoDTO_Edital->setDblIdProcedimento($objEditalEliminacaoDTO->getDblIdProcedimento());
      //gera o documento (edital em si)
      $objDocumentoDTO = $this->gerarDocumentoEdital($objProcedimentoDTO_Edital, $arrObjEditalEliminacaoConteudoDTO,  self::$ID_SERIE_EDITAL_ELIMINACAO_LISTAGEM_ELIMINADOS);
      $objInfraException->lancarValidacoes();

      $objDocumentoDTO_Gerado = new DocumentoDTO();
      $objDocumentoDTO_Gerado->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO_Gerado->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO_Gerado = $objDocumentoRN->consultarRN0005($objDocumentoDTO_Gerado);

    }catch(Exception $e){
      throw new InfraException('Erro gerando Edital de Eliminação.',$e);
    }

    return $objDocumentoDTO_Gerado;
  }

  //geracao do documento (que gera o id_documento no edital_eliminacao)
  private function gerarDocumentoEdital(ProcedimentoDTO $objProcedimentoDTO, array $arrObjEditalEliminacaoConteudoDTO, $strParametro)  {
    //dto do documento
    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->setDblIdDocumento(null);
    //procedimento que contem o documento (já foi criado antes)
    $objDocumentoDTO->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
    $objDocumentoDTO->setDblIdDocumentoEdoc(null);
    $objDocumentoDTO->setDblIdDocumentoEdocBase(null);
    //parametro que contem a serie/tipo do documento
    $objInfraParametro = new InfraParametro($this->getObjInfraIBanco());
    $numIdSerie = $objInfraParametro->getValor($strParametro);

    if ($numIdSerie==''){
      throw new InfraException('Parâmetro '.$strParametro.' não foi configurado.');
    }

    $objDocumentoDTO->setNumIdSerie($numIdSerie);
    $objDocumentoDTO->setNumIdUnidadeResponsavel(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objDocumentoDTO->setStrNumero(null);
    //será gerado um html contendo os processos
    $objDocumentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
    //testa se contem conteudo/processos
    if(InfraArray::contar($arrObjEditalEliminacaoConteudoDTO) > 0){
      //lista interessados
      $objParticipanteDTO = new ParticipanteDTO();
      $objParticipanteDTO->retDblIdProtocolo();
      $objParticipanteDTO->retNumIdContato();
      $objParticipanteDTO->retStrNomeContato();
      //filtra apenas pelos participantes Interessado
      $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_INTERESSADO);
      //interessantes de todos os processos do edital
      $objParticipanteDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjEditalEliminacaoConteudoDTO,"IdProcedimentoAvaliacaoDocumental"),InfraDTO::$OPER_IN);
      //lista os interessados
      $objParticipanteRN = new ParticipanteRN();
      $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);
      //indexa pela processo (pode ter mais de um interessado, por isso o parametro true), para buscar na listagem do processo
      $arrObjParticipanteDTO_IndexadoProcesso = InfraArray::indexarArrInfraDTO($arrObjParticipanteDTO,"IdProtocolo",true);
      //string da tabela do edital

      $strTabela = '<table width="100%" cellpadding="3" style="border:1px solid black; border-collapse:collapse;">'."\n";
      if($strParametro == self::$ID_SERIE_EDITAL_ELIMINACAO) {
        $strTabela .= '<tr>'."\n".
                      '<th style="border:1px solid black;">Processo</th>'."\n".
                      '<th style="border:1px solid black;">Tipo</th>'."\n".
                      '<th style="border:1px solid black;">Interessados</th>'."\n".
                      '<th style="border:1px solid black;">Arquivamentos</th>'."\n".
                      '</tr>'."\n";
      }else{
        $strTabela .= '<tr>'."\n".
                      '<th style="border:1px solid black;">Processo</th>'."\n".
                      '<th style="border:1px solid black;">Arquivamentos</th>'."\n".
                      '</tr>'."\n";
      }
      //rn para consultar os arquivamentos
      $objArquivamentoRN = new ArquivamentoRN();
      //itera pelos processos
      foreach ($arrObjEditalEliminacaoConteudoDTO as $objEditalEliminacaoConteudoDTO){
        //string que contem os interessdos do processo
        $strInteressados = "";
        //testa se o processo tem interessados
        if(array_key_exists ($objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental(), $arrObjParticipanteDTO_IndexadoProcesso)){
          //retorna os interessdos
          $arrObjParticipanteDTO = $arrObjParticipanteDTO_IndexadoProcesso[$objEditalEliminacaoConteudoDTO->getDblIdProcedimentoAvaliacaoDocumental()];
          //itera pelos interessados
          foreach ($arrObjParticipanteDTO as $i => $objParticipanteDTO){
            //retorna o nome do interessado
            $strNomeContato = $objParticipanteDTO->getStrNomeContato();
            //se nao for o primeiro interessado, adiciona quebra de linha
            if($i > 0){
              $strNomeContato = "<br/>".$strNomeContato;
            }
            //concatena o nome do interessado
            $strInteressados .= $strNomeContato;
          }
          //dados da coluna Arquivamentos
          //lista os documentos desse processo que estao fisico-arquivados
        };
        if($strParametro == self::$ID_SERIE_EDITAL_ELIMINACAO) {
          $arrObjArquivamentoDTO = $objArquivamentoRN->listarParaEditalEliminacao($objEditalEliminacaoConteudoDTO);
        }else{
          $arrObjArquivamentoDTO = $objArquivamentoRN->listarProcessosEliminados($objEditalEliminacaoConteudoDTO);
        }
        //string que conterá a lista de documentos do processo
        $strDocumentos = "";
        //testa se encontrou documentos assim
        if(InfraArray::contar($arrObjArquivamentoDTO) > 0){
          //itera pelos documentos
          foreach ($arrObjArquivamentoDTO as $i => $objArquivamentoDTO){

            if ($objArquivamentoDTO->getNumIdLocalizador()!=null) {
              //retorna os dados do documento
              $strDocumento = $objArquivamentoDTO->getStrProtocoloFormatadoDocumento()." ".LocalizadorINT::montarIdentificacaoRI1132($objArquivamentoDTO->getStrSiglaTipoLocalizador(), $objArquivamentoDTO->getNumSeqLocalizadorLocalizador())."/".$objArquivamentoDTO->getStrSiglaOrgaoUnidadeLocalizador();
            } else{
              $strDocumento = $objArquivamentoDTO->getStrProtocoloFormatadoDocumento()." RECEBIDO/".$objArquivamentoDTO->getStrSiglaOrgaoUnidadeRecebimento();
            }

            //se nao for o primeiro documento, adiciona quebra de linha
            if($i > 0){
              $strDocumento = "<br/>".$strDocumento;
            }
            $strDocumentos .= $strDocumento;
          }

        }
        //exibe os dados do processo
        if($strParametro == self::$ID_SERIE_EDITAL_ELIMINACAO) {
          $strTabela .= '<tr>'."\n".
                        '<td align="center" style="border:1px solid black;">'.$objEditalEliminacaoConteudoDTO->getStrProtocoloProcedimentoFormatado().'</td>'."\n".
                        '<td align="left" style="border:1px solid black;">'.$objEditalEliminacaoConteudoDTO->getStrNomeTipoProcedimento().'</td>'."\n".
                        '<td align="left" style="border:1px solid black;">'.$strInteressados.'</td>'."\n".
                        '<td align="left" style="border:1px solid black;">'.$strDocumentos.'</td>'."\n".
                        '</tr>'."\n";
        }else{
          $strTabela .= '<tr>'."\n".
                        '<td align="center" style="border:1px solid black;">'.$objEditalEliminacaoConteudoDTO->getStrProtocoloProcedimentoFormatado().'</td>'."\n".
                        '<td align="left" style="border:1px solid black;">'.$strDocumentos.'</td>'."\n".
                        '</tr>'."\n";
        }


      }
      //fecha a tabela
      $strTabela .= "</table>\n<br>";
      //seta o conteudo do documento no atributo do dto
      $objDocumentoDTO->setStrConteudo($strTabela);
    }
    //cria dto e seta atributos necessarios para criacao do documento
    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->setDblIdProtocolo(null);
    $objProtocoloDTO->setStrStaNivelAcessoLocal(ProtocoloRN::$NA_PUBLICO);
    $objProtocoloDTO->setNumIdHipoteseLegal(null);
    $objProtocoloDTO->setStrDescricao(null);
    $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
    $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO(array());
    $objProtocoloDTO->setArrObjParticipanteDTO(array());
    $objProtocoloDTO->setArrObjObservacaoDTO(array());
    //seta o dto de protocolo no documento
    $objDocumentoDTO->setObjProtocoloDTO($objProtocoloDTO);

    $objDocumentoRN = new DocumentoRN();
    //cadastra o documento
    return $objDocumentoRN->cadastrarRN0003($objDocumentoDTO);
  }
  //geracao do processo (que gera o id_processo no edital_eliminacao)
  private function gerarProcessoEdital(ProcedimentoDTO $objProcedimentoDTO){
    //existe um tipo de processo especifico para edital de eliminacao
    //esse tipo é referenciado por um id cadastrado na infra parametro

    //busca o id tipo de processo para edital de eliminacao na infra parametro
    $objInfraParametro = new InfraParametro($this->getObjInfraIBanco());
    $numIdTipoProcedimento = $objInfraParametro->getValor('ID_TIPO_PROCEDIMENTO_ELIMINACAO');

    if ($numIdTipoProcedimento==''){
      throw new InfraException('Parâmetro ID_TIPO_PROCEDIMENTO_ELIMINACAO não foi configurado.');
    }

    //cria dto para o tipo de processo para edital de eliminacao, que será buscado do banco
    $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
    $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
    $objTipoProcedimentoDTO->retStrStaNivelAcessoSugestao();
    $objTipoProcedimentoDTO->retStrStaGrauSigiloSugestao();
    $objTipoProcedimentoDTO->retNumIdHipoteseLegalSugestao();
    //filtra pelo parametro da infra parametro
    $objTipoProcedimentoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento); //usar tipo buscado na tabela de parâmetros
    //busca o tipo de processo
    $objTipoProcedimentoRN = new TipoProcedimentoRN();
    $objTipoProcedimentoDTO = $objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO);

    //seta atributos do processo que conterá o edital de eliminacao
    $objProcedimentoDTO->setDblIdProcedimento(null);
    $objProcedimentoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
    $objProcedimentoDTO->setStrSinGerarPendencia('S');

    //seta atributos do protocolo que conterá o edital de eliminacao
    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->setStrDescricao(null);
    $objProtocoloDTO->setNumIdUnidadeGeradora(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objProtocoloDTO->setNumIdUsuarioGerador(SessaoSEI::getInstance()->getNumIdUsuario());
    $objProtocoloDTO->setDtaGeracao(InfraData::getStrDataAtual());
    $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
    $objProtocoloDTO->setStrStaNivelAcessoLocal($objTipoProcedimentoDTO->getStrStaNivelAcessoSugestao());
    $objProtocoloDTO->setStrStaGrauSigilo($objTipoProcedimentoDTO->getStrStaGrauSigiloSugestao());
    $objProtocoloDTO->setNumIdHipoteseLegal($objTipoProcedimentoDTO->getNumIdHipoteseLegalSugestao());

    //busca e adiciona os assuntos
    $objRelTipoProcedimentoAssuntoDTO = new RelTipoProcedimentoAssuntoDTO();
    $objRelTipoProcedimentoAssuntoDTO->retNumIdAssunto();
    $objRelTipoProcedimentoAssuntoDTO->retNumSequencia();
    $objRelTipoProcedimentoAssuntoDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
    $objRelTipoProcedimentoAssuntoRN = new RelTipoProcedimentoAssuntoRN();
    $arrObjRelTipoProcedimentoAssuntoDTO = $objRelTipoProcedimentoAssuntoRN->listarRN0192($objRelTipoProcedimentoAssuntoDTO);
    $arrObjAssuntoDTO = array();
    foreach($arrObjRelTipoProcedimentoAssuntoDTO as $objRelTipoProcedimentoAssuntoDTO){
      $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
      $objRelProtocoloAssuntoDTO->setNumIdAssunto($objRelTipoProcedimentoAssuntoDTO->getNumIdAssunto());
      $objRelProtocoloAssuntoDTO->setNumSequencia($objRelTipoProcedimentoAssuntoDTO->getNumSequencia());
      $arrObjAssuntoDTO[] = $objRelProtocoloAssuntoDTO;
    }
    //seta assuntos
    $objProtocoloDTO->setArrObjRelProtocoloAssuntoDTO($arrObjAssuntoDTO);
    //seta outros arrays
    $objProtocoloDTO->setArrObjParticipanteDTO(array());
    $objProtocoloDTO->setArrObjObservacaoDTO(array());
    $objProcedimentoDTO->setObjProtocoloDTO($objProtocoloDTO);
    //gera o procedimento em si
    $objProcedimentoRN = new ProcedimentoRN();
    //retorna o dto atualizado
    $objProcedimentoDTO = $objProcedimentoRN->gerarRN0156($objProcedimentoDTO);

    //consulta procedimentos de editais de eliminacao da unidade
    $objEditalEliminacaoDTO_ExitentesUnidade = new EditalEliminacaoDTO();
    $objEditalEliminacaoDTO_ExitentesUnidade->retDblIdProcedimento();
    $objEditalEliminacaoDTO_ExitentesUnidade->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objEditalEliminacaoDTO_ExitentesUnidade->setDblIdProcedimento($objProcedimentoDTO->getDblIdProcedimento(), InfraDTO::$OPER_DIFERENTE);
    $arrObjEditalEliminacaoDTO = $this->listarConectado($objEditalEliminacaoDTO_ExitentesUnidade);

    $qtdEditaisUnidade = InfraArray::contar($arrObjEditalEliminacaoDTO);
    if($qtdEditaisUnidade){
      $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
      foreach ($arrObjEditalEliminacaoDTO as $objEditalEliminacaoDTO){
        if ($objEditalEliminacaoDTO->getDblIdProcedimento()!=null) {
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo(null);
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($objProcedimentoDTO->getDblIdProcedimento());
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objEditalEliminacaoDTO->getDblIdProcedimento());
          $objRelProtocoloProtocoloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          $objRelProtocoloProtocoloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_RELACIONADO);
          $objRelProtocoloProtocoloDTO->setNumSequencia(0);
          $objRelProtocoloProtocoloDTO->setDthAssociacao(InfraData::getStrDataHoraAtual());
          $objRelProtocoloProtocoloRN->cadastrarRN0839($objRelProtocoloProtocoloDTO);
        }
      }
    }



    return $objProcedimentoDTO;
  }
  //após executar a eliminacao de um edital de eliminacao ou de um processo, deve ser alterada a situacao do edital
  //devem ser buscados todos os conteudos/processos:
  //  se todos os processos estão eliminados, o edital está eliminado;
  //  se houver pelo menos um processo eliminado parcialmente, o edital está eliminado parcialmente;
  //  se houver pelo menos um processo em outra situacao diferente de eliminacao e de eliminacao parcial e os outros eliminados, o edital está eliminado parcialmente;
  //  se nenhum processo está eliminado ou eliminado parcialmente, o edital mantem a situacao atual (gerado)
  //obs.: um processo em "outra" situacao normalmente quer dizer um processo na situacao de "Comissão", o que ocorre quando um processo tentou ser eliminado, mas ocorreu um erro, então ocorreu um rollback e a avaliacao documental permanece como "Comissão"
  private function alterarSituacaoEditalEliminacao($numIdEditalEliminacao){
    //dto para buscar os conteudos/processos do edital
    $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
    $objEditalEliminacaoConteudoDTO->retNumIdEditalEliminacaoConteudo();
    $objEditalEliminacaoConteudoDTO->retStrStaSituacaoAvaliacaoDocumental();
    $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
    //busca os processos
    $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
    $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listar($objEditalEliminacaoConteudoDTO);
    //converte a lista de processos considerando suas situacoes
    $strStaSituacaoAvaliacaoDocumental = InfraArray::converterArrInfraDTO($arrObjEditalEliminacaoConteudoDTO,"StaSituacaoAvaliacaoDocumental");
    //booleanos que indicam quais situacoes de processos foram encontradas nos processos
    $bolEliminado = false;
    $bolParcialmenteElimnado = false;
    $bolOutro = false;
    //itera pelas situacoes dos processos
    foreach ($strStaSituacaoAvaliacaoDocumental as $staSituacaoAvaliacaoDocumental){
      //se tem o processo está eliminado, seta o bool referente
      if($staSituacaoAvaliacaoDocumental == AvaliacaoDocumentalRN::$TA_ELIMINADO){
        $bolEliminado = true;
        continue;
      }
      if($staSituacaoAvaliacaoDocumental == AvaliacaoDocumentalRN::$TA_ELIMINACAO_PARCIAL){
        //se tem o processo está eliminado parcialmente, seta o bool referente
        $bolParcialmenteElimnado = true;
        continue;
      }
      if($staSituacaoAvaliacaoDocumental != AvaliacaoDocumentalRN::$TA_ELIMINADO && $staSituacaoAvaliacaoDocumental != AvaliacaoDocumentalRN::$TA_ELIMINACAO_PARCIAL){
        //se tem o processo está em outra situacao, seta o bool referente
        $bolOutro = true;
        continue;
      }
    }
    //se tem pelo menos um processo eliminado parcialmente, ou tem processos eliminados e pelo menos um em outra situacao, quer dizer que o edital está eliminado parcialmente
    if($bolParcialmenteElimnado || ($bolEliminado && $bolOutro)){
      $strStaEditalEliminacao = self::$TE_ELIMINACAO_PARCIAL;
    //se tem processo eliminado e nao tem processo em outra situacao, o edital está eliminado
    }else if($bolEliminado && !$bolOutro){
      $strStaEditalEliminacao = self::$TE_ELIMINADO;
    }else{
    //senao o edital segue gerado, pois tem apenas processos em outra situacao
      $strStaEditalEliminacao = self::$TE_GERADO;
    }
    //se a nova situacao é diferente de gerado, deve atualizar o edital de eliminacao
    if($strStaEditalEliminacao !=  self::$TE_GERADO){
      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
      $objEditalEliminacaoDTO->setStrStaEditalEliminacao($strStaEditalEliminacao);
      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      $objEditalEliminacaoBD->alterar($objEditalEliminacaoDTO);
    }
    return $strStaEditalEliminacao;
  }

  //eliminacao de um edital inteiro
  protected function eliminarEditalConectado(EditalEliminacaoDTO $objEditalEliminacaoDTO){
    try {

      LimiteSEI::getInstance()->configurarNivel3();

      $objInfraException = new InfraException();

      //cor
      $arrCorBarraProgresso = array('cor_fundo' => '#5c9ccc', 'cor_borda' => '#4297d7');
      //identificacao
      $prb = InfraBarraProgresso2::newInstance('Eliminacao', $arrCorBarraProgresso);
      //inicio
      $prb->setStrRotulo('');

      $objEditalEliminacaoBD = new EditalEliminacaoBD($this->getObjInfraIBanco());
      //dto para buscar o edital de eliminacao do banco
      $objEditalEliminacaoDTO_Banco = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO_Banco->retNumIdEditalEliminacao();
      $objEditalEliminacaoDTO_Banco->retDblIdProcedimento();
      $objEditalEliminacaoDTO_Banco->retDblIdDocumento();
      $objEditalEliminacaoDTO_Banco->retStrEspecificacao();
      $objEditalEliminacaoDTO_Banco->retDtaPublicacao();
      $objEditalEliminacaoDTO_Banco->retStrStaEditalEliminacao();
      //seta o id do edital de eliminacao
      $objEditalEliminacaoDTO_Banco->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
      //consulta o edital de eliminacao
      $objEditalEliminacaoDTO_Banco = $objEditalEliminacaoBD->consultar($objEditalEliminacaoDTO_Banco);
      //testa se já foi eliminado ou eliminado parcialmente
      if ($objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() == self::$TE_ELIMINADO) {
        $objInfraException->lancarValidacao("Todos os processos do edital " . $objEditalEliminacaoDTO_Banco->getStrEspecificacao() . " já foram eliminados.");
      } else if ($objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() != self::$TE_ELIMINACAO_PARCIAL && $objEditalEliminacaoDTO_Banco->getStrStaEditalEliminacao() != self::$TE_PUBLICADO) {
        $objInfraException->lancarValidacao("Situação do edital " . $objEditalEliminacaoDTO_Banco->getStrEspecificacao() . " não permite eliminação.");
      }
      //busca o parametro de quantos dias é o prazo para poder eliminar um edital a partir de sua publicação
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $strNumDiasPrazoEliminacao = trim($objInfraParametro->getValor("SEI_NUM_DIAS_PRAZO_ELIMINACAO", false));
      //busca a data que o edital pode ser eliminado, somando os dias de prazo (o parametro) com a data de publicacao do edital de eliminacao
      $dtaPrazoEliminacao = InfraData::calcularData($strNumDiasPrazoEliminacao, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $objEditalEliminacaoDTO_Banco->getDtaPublicacao());
      //numero de dias que faltam para o edital poder ser eliminado
      $numDias = InfraData::compararDatas(InfraData::getStrDataAtual(), $dtaPrazoEliminacao);
      //se faltam dias, nao pode ser
      if ($numDias > 0) {
        $objInfraException->lancarValidacao("Prazo para eliminação do edital " . $objEditalEliminacaoDTO_Banco->getStrEspecificacao() . " de " . $strNumDiasPrazoEliminacao . " dias após publicação ainda não foi concluído (decorridos " . $numDias . " dias)");
      }

      //dto para buscar os processos que serao eliminados
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retDblIdProcedimento();
      $objProcedimentoDTO->retNumIdEditalEliminacaoConteudo();
      $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
      $objProcedimentoDTO->setNumIdEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());
      //apenas os que nao foram eliminados
      $objProcedimentoDTO->setStrStaAvaliacaoDocumental(AvaliacaoDocumentalRN::$TA_ELIMINADO, InfraDTO::$OPER_DIFERENTE);
      if($objEditalEliminacaoDTO->isSetArrObjEditalEliminacaoConteudoDTO() && InfraArray::contar($objEditalEliminacaoDTO->getArrObjEditalEliminacaoConteudoDTO())){
        $arrIdEditalEliminacaoConteudo = InfraArray::converterArrInfraDTO($objEditalEliminacaoDTO->getArrObjEditalEliminacaoConteudoDTO(),"IdEditalEliminacaoConteudo");
        $objProcedimentoDTO->setNumIdEditalEliminacaoConteudo($arrIdEditalEliminacaoConteudo,InfraDTO::$OPER_IN);
      }
      $objProcedimentoRN = new ProcedimentoRN();
      //lista os processos
      $arrObjProcedimentoDTO = $objProcedimentoRN->listarRN0278($objProcedimentoDTO);
      $qtdProcessos = count($arrObjProcedimentoDTO);
      //configuracao da barra de progresso

      //inicio
      $prb->setNumMin(0);
      //numero de registros
      $prb->setNumMax($qtdProcessos);
      //primeira posicao
      $prb->setNumPosicao(0);
      //rotulo inicial
      $prb->setStrRotulo('Iniciando eliminação de processos ...');
      //sleep inicial para usuario ver a quantidade de processos
      sleep(1);

      $numRegistros = 0;
      //itera pelos processos

      $objInfraException = new InfraException();

      foreach ($arrObjProcedimentoDTO as $key => $objProcedimentoDTO) {

        //mostra o processo que está sendo eliminado
        $prb->setStrRotulo('Eliminando processo ' . $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado() .' [' . ++$numRegistros . ' de ' . $qtdProcessos . ']...');

        //elimina o processo
        $objProcedimentoRN->eliminar($objProcedimentoDTO, $objInfraException);

        //move para o processo item na barra de progresso
        $prb->moverProximo();
        usleep(250000);

      }

      $numErros = 0;
      if ($objInfraException->contemValidacoes()){
        $numErros = InfraArray::contar($objInfraException->getArrObjInfraValidacao());
      }

      //atualiza a situacao do edital de eliminacao
      $strStaEditalEliminacao = $this->alterarSituacaoEditalEliminacao($objEditalEliminacaoDTO->getNumIdEditalEliminacao());

      //mostra a mensagem e outras validacoes
      $strMensagem = '';
      if ($strStaEditalEliminacao == self::$TE_ELIMINACAO_PARCIAL) {
        $strMensagem = 'Processos do edital eliminados parcialmente:';
      } else if ($strStaEditalEliminacao == self::$TE_ELIMINADO) {
        $strMensagem = 'Eliminação finalizada:';
      }

      $prb->setStrRotulo($strMensagem.' '.$numRegistros.' processo(s), '.$numErros.' erro(s).');
      sleep(1);

    }catch(Exception $e){
      throw new InfraException('Erro eliminando Edital.',$e);
    }
  }
}
