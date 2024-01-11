<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadAvaliacaoRN extends InfraRN {

  //'avaliado' é quando a avaliacao cpad foi de acordo com a avaliacao documental
  public static $TA_CPAD_AVALIADO = 'A';
  //'negado' é quando discordou, e deve ser informado o motivo
  public static $TA_CPAD_NEGADO = 'N';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresCpadAvaliacao(){
    try {

      $arrObjCpadAvaliacaoCpadAvaliacaoDTO = array();

      $objCpadAvaliacaoCpadAvaliacaoDTO = new CpadAvaliacaoCpadAvaliacaoDTO();
      $objCpadAvaliacaoCpadAvaliacaoDTO->setStrStaCpadAvaliacao(self::$TA_CPAD_AVALIADO);
      $objCpadAvaliacaoCpadAvaliacaoDTO->setStrDescricao('Concordo');
      $arrObjCpadAvaliacaoCpadAvaliacaoDTO[] = $objCpadAvaliacaoCpadAvaliacaoDTO;

      $objCpadAvaliacaoCpadAvaliacaoDTO = new CpadAvaliacaoCpadAvaliacaoDTO();
      $objCpadAvaliacaoCpadAvaliacaoDTO->setStrStaCpadAvaliacao(self::$TA_CPAD_NEGADO);
      $objCpadAvaliacaoCpadAvaliacaoDTO->setStrDescricao('Negado');
      $arrObjCpadAvaliacaoCpadAvaliacaoDTO[] = $objCpadAvaliacaoCpadAvaliacaoDTO;

      return $arrObjCpadAvaliacaoCpadAvaliacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de CpadAvaliacao.',$e);
    }
  }

  private function validarNumIdAvaliacaoDocumental(CpadAvaliacaoDTO $objCpadAvaliacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadAvaliacaoDTO->getNumIdAvaliacaoDocumental())){
      $objInfraException->adicionarValidacao('Avaliação Documental não informada.');
    }
  }

  private function validarNumIdCpadComposicao(CpadAvaliacaoDTO $objCpadAvaliacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadAvaliacaoDTO->getNumIdCpadComposicao())){
      $objInfraException->adicionarValidacao('Composição não informada.');
    }
  }

  private function validarDthAvaliacao(CpadAvaliacaoDTO $objCpadAvaliacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadAvaliacaoDTO->getDthAvaliacao())){
      $objInfraException->adicionarValidacao('Avaliação não informada.');
    }else{
      if (!InfraData::validarDataHora($objCpadAvaliacaoDTO->getDthAvaliacao())){
        $objInfraException->adicionarValidacao('Avaliação inválida.');
      }
    }
  }

  private function validarStrStaCpadAvaliacao(CpadAvaliacaoDTO $objCpadAvaliacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadAvaliacaoDTO->getStrStaCpadAvaliacao())){
      $objInfraException->adicionarValidacao('Avaliação não informada.');
    }else{
      if (!in_array($objCpadAvaliacaoDTO->getStrStaCpadAvaliacao(),InfraArray::converterArrInfraDTO($this->listarValoresCpadAvaliacao(),'StaCpadAvaliacao'))){
        $objInfraException->adicionarValidacao('Avaliação inválida.');
      }
    }
  }

  private function validarStrMotivo(CpadAvaliacaoDTO $objCpadAvaliacaoDTO, InfraException $objInfraException){
    if ($objCpadAvaliacaoDTO->getStrStaCpadAvaliacao() == self::$TA_CPAD_NEGADO){
      if(InfraString::isBolVazia($objCpadAvaliacaoDTO->getStrMotivo())) {
        $objInfraException->adicionarValidacao('O motivo deve ser informado.');
      }else{
        $objCpadAvaliacaoDTO->setStrMotivo(trim($objCpadAvaliacaoDTO->getStrMotivo()));
      }
    }else{
      $objCpadAvaliacaoDTO->setStrMotivo(null);
    }
  }

  protected function cadastrarControlado(CpadAvaliacaoDTO $objCpadAvaliacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_cadastrar',__METHOD__,$objCpadAvaliacaoDTO);

      $objInfraException = new InfraException();

      //validacoes padroes
      $this->validarNumIdAvaliacaoDocumental($objCpadAvaliacaoDTO, $objInfraException);
      $this->validarDthAvaliacao($objCpadAvaliacaoDTO, $objInfraException);
      $this->validarStrStaCpadAvaliacao($objCpadAvaliacaoDTO, $objInfraException);
      $this->validarStrMotivo($objCpadAvaliacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      //busca a ultima versao (a versao ativa) do cpad
      $objCpadVersaoDTO = new CpadVersaoDTO();
      $objCpadVersaoDTO->retNumIdCpadVersao();
      //filtra pelo orgao (só pode haver uma cpad por orgao)
      $objCpadVersaoDTO->setNumIdOrgaoCpad(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
      $objCpadVersaoRN = new CpadVersaoRN();
      $objCpadVersaoDTO = $objCpadVersaoRN->consultar($objCpadVersaoDTO);
      //se tem versao ativa
      if($objCpadVersaoDTO != null) {
        //testa/busca se o usuario que está realizando a avaliacao cpad está na composicao da versao ativa
        $objCpadComposicaoRN = new CpadComposicaoRN();
        $objCpadComposicaoDTO = new CpadComposicaoDTO();
        $objCpadComposicaoDTO->retNumIdCpadComposicao();
        //filtra pela versao
        $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO->getNumIdCpadVersao());
        //filtra pelo usuario
        $objCpadComposicaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);
        //se o usuario faz parte da versao ativa
        if ($objCpadComposicaoDTO == null) {
          $objInfraException->lancarValidacao("Usuário não pertence a uma comissão permanente de avaliação de documentos ativa nesse órgão");
        }
        //seta o id do componente (usuario) da composicao
        $objCpadAvaliacaoDTO->setNumIdCpadComposicao($objCpadComposicaoDTO->getNumIdCpadComposicao());
        //valida
        $this->validarNumIdCpadComposicao($objCpadAvaliacaoDTO, $objInfraException);

        //cadastra a avaliacao cpad
        $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
        $objCpadAvaliacaoDTO = $objCpadAvaliacaoBD->cadastrar($objCpadAvaliacaoDTO);

        ////////////////////////
        //sempre que é realizada uma avaliacao cpad, a ultima versão da cpad (ativa) deve ser alterada para nao editavel
        $objCpadVersaoDTO_Editavel = new CpadVersaoDTO();
        $objCpadVersaoDTO_Editavel->setNumIdCpadVersao($objCpadVersaoDTO->getNumIdCpadVersao());
        $objCpadVersaoDTO_Editavel->setStrSinEditavel("N");
        //altera direto, para nao precisar fazer uma consulta (select) a mais, mesmo considerando que a versão já pode ser nao editavel (se outro componente fez uma avaliacao cpad antes)
        $objCpadVersaoRN->alterar($objCpadVersaoDTO_Editavel);

        ////////////////////////
        /// sempre que é realizada uma avaliacao cpad, testa se toda a composicao (se todos os componentes/usuarios) já realizou a avaliacao também
        /// se realizou e toda a composicao avaliou de acordo (status 'avaliado' na avaliacao cpad), muda o status da avaliacao documental para 'comissao'

        //busca o numero total de componentes da ultima versao (ativa)
        $objCpadComposicaoDTO_Banco = new CpadComposicaoDTO();
        $objCpadComposicaoDTO_Banco->setNumIdCpadVersao($objCpadVersaoDTO->getNumIdCpadVersao());
        $numIntegrantesComposicao = $objCpadComposicaoRN->contar($objCpadComposicaoDTO_Banco);

        //busca a quantidade de avaliacoes cpad ativas que concordaram (status 'avaliado') dessa avaliacao documental
        $objCpadAvaliacaoDTO_Banco = new CpadAvaliacaoDTO();
        $objCpadAvaliacaoDTO_Banco->setStrStaCpadAvaliacao(self::$TA_CPAD_AVALIADO);
        $objCpadAvaliacaoDTO_Banco->setNumIdAvaliacaoDocumental($objCpadAvaliacaoDTO->getNumIdAvaliacaoDocumental());
        $numAvaliacoesComposicao = $objCpadAvaliacaoBD->contar($objCpadAvaliacaoDTO_Banco);

        //se a quantidade de avaliacoes cpad retornada para os filtros for igual ao numero de componentes da composicao, altera o status
        if($numIntegrantesComposicao == $numAvaliacoesComposicao) {
          $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
          $objAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
          $objAvaliacaoDocumentalDTO->retNumIdAssuntoProxy();
          $objAvaliacaoDocumentalDTO->retNumIdAssuntoOriginal();
          $objAvaliacaoDocumentalDTO->retDblIdProcedimento();
          $objAvaliacaoDocumentalDTO->retNumIdUsuario();
          $objAvaliacaoDocumentalDTO->retNumIdUnidade();
          $objAvaliacaoDocumentalDTO->retStrStaAvaliacao();
          $objAvaliacaoDocumentalDTO->retDtaAvaliacao();
          $objAvaliacaoDocumentalDTO->setNumIdAvaliacaoDocumental($objCpadAvaliacaoDTO->getNumIdAvaliacaoDocumental());
          $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
          $objAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->consultar($objAvaliacaoDocumentalDTO);
          $objAvaliacaoDocumentalDTO->setStrStaAvaliacao(AvaliacaoDocumentalRN::$TA_COMISSAO);
          $objAvaliacaoDocumentalRN->alterar($objAvaliacaoDocumentalDTO);
        }
        ////////////////////////
      }else{
        $objInfraException->lancarValidacao("Não existe uma versão de comissão permanente de avaliação de documentos ativa nesse órgão");
      }

      return $objCpadAvaliacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Avaliação.',$e);
    }
  }

  protected function alterarControlado(CpadAvaliacaoDTO $objCpadAvaliacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_alterar',__METHOD__,$objCpadAvaliacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCpadAvaliacaoDTO->isSetNumIdAvaliacaoDocumental()){
        $this->validarNumIdAvaliacaoDocumental($objCpadAvaliacaoDTO, $objInfraException);
      }
      if ($objCpadAvaliacaoDTO->isSetNumIdCpadComposicao()){
        $this->validarNumIdCpadComposicao($objCpadAvaliacaoDTO, $objInfraException);
      }
      if ($objCpadAvaliacaoDTO->isSetDthAvaliacao()){
        $this->validarDthAvaliacao($objCpadAvaliacaoDTO, $objInfraException);
      }
      if ($objCpadAvaliacaoDTO->isSetStrStaCpadAvaliacao()){
        $this->validarStrStaCpadAvaliacao($objCpadAvaliacaoDTO, $objInfraException);
      }
      if ($objCpadAvaliacaoDTO->isSetStrMotivo()){
        $this->validarStrMotivo($objCpadAvaliacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
      $objCpadAvaliacaoBD->alterar($objCpadAvaliacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Avaliação.',$e);
    }
  }

  protected function excluirControlado($arrObjCpadAvaliacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_excluir',__METHOD__,$arrObjCpadAvaliacaoDTO);


      $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadAvaliacaoDTO);$i++){
        $objCpadAvaliacaoBD->excluir($arrObjCpadAvaliacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Avaliação.',$e);
    }
  }

  protected function consultarConectado(CpadAvaliacaoDTO $objCpadAvaliacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_consultar',__METHOD__,$objCpadAvaliacaoDTO);


      $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
      $ret = $objCpadAvaliacaoBD->consultar($objCpadAvaliacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Avaliação.',$e);
    }
  }

  protected function listarConectado(CpadAvaliacaoDTO $objCpadAvaliacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_listar',__METHOD__,$objCpadAvaliacaoDTO);


      $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
      $ret = $objCpadAvaliacaoBD->listar($objCpadAvaliacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Avaliações.',$e);
    }
  }

  protected function contarConectado(CpadAvaliacaoDTO $objCpadAvaliacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_listar',__METHOD__,$objCpadAvaliacaoDTO);


      $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
      $ret = $objCpadAvaliacaoBD->contar($objCpadAvaliacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Avaliações.',$e);
    }
  }

  protected function desativarControlado($arrObjCpadAvaliacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_avaliacao_desativar',__METHOD__,$arrObjCpadAvaliacaoDTO);


      $objCpadAvaliacaoBD = new CpadAvaliacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadAvaliacaoDTO);$i++){
        $objCpadAvaliacaoBD->desativar($arrObjCpadAvaliacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Avaliação.',$e);
    }
  }

}
