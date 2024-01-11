<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/01/2021 - criado por cas84
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvisoRN extends InfraRN {

  public static $AVISO_JANELA = 'J';
  public static $AVISO_BANNER = 'B';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresAviso(){
    try {

      $arrObjAvisoAvisoDTO = array();

      $objAvisoAvisoDTO = new AvisoAvisoDTO();
      $objAvisoAvisoDTO->setStrStaAviso(self::$AVISO_JANELA);
      $objAvisoAvisoDTO->setStrDescricao('Janela');
      $arrObjAvisoAvisoDTO[] = $objAvisoAvisoDTO;

      $objAvisoAvisoDTO = new AvisoAvisoDTO();
      $objAvisoAvisoDTO->setStrStaAviso(self::$AVISO_BANNER);
      $objAvisoAvisoDTO->setStrDescricao('Banner');
      $arrObjAvisoAvisoDTO[] = $objAvisoAvisoDTO;

      return $arrObjAvisoAvisoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Aviso.',$e);
    }
  }

  private function validarStrStaAviso(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAvisoDTO->getStrStaAviso())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objAvisoDTO->getStrStaAviso(),InfraArray::converterArrInfraDTO($this->listarValoresAviso(),'StaAviso'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarDthInicio(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAvisoDTO->getDthInicio())){
      $objInfraException->adicionarValidacao('Data/Hora Início não informada.');
    }else{
      if (!InfraData::validarDataHora($objAvisoDTO->getDthInicio().':00')){
        $objInfraException->adicionarValidacao('Data/Hora Início inválida.');
      }
    }
  }

  private function validarDthFim(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAvisoDTO->getDthFim())){
      $objInfraException->adicionarValidacao('Data/Hora Fim não informada.');
    }else{
      if (!InfraData::validarDataHora($objAvisoDTO->getDthFim())){
        $objInfraException->adicionarValidacao('Data/Hora Fim inválida.');
      }
    }
  }

  private function validarArrObjRelAvisoOrgao(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (InfraArray::contar($objAvisoDTO->getArrObjRelAvisoOrgaoDTO())==0){
      $objInfraException->adicionarValidacao('Nenhum Órgão informado.');
    }
  }

  private function validarStrDescricao(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAvisoDTO->getStrDescricao())){
      $objAvisoDTO->setStrDescricao(null);
    }else{
      $objAvisoDTO->setStrDescricao(trim($objAvisoDTO->getStrDescricao()));

      if (strlen($objAvisoDTO->getStrDescricao())>500){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrLink(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAvisoDTO->getStrLink())){
      $objAvisoDTO->setStrLink(null);
    }else{
      $objAvisoDTO->setStrLink(trim($objAvisoDTO->getStrLink()));

      if (strlen($objAvisoDTO->getStrLink())>250){
        $objInfraException->adicionarValidacao('Link possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrImagem(AvisoDTO $objAvisoDTO, InfraException $objInfraException){
    if (!InfraString::isBolVazia($objAvisoDTO->getStrImagem()) || !InfraString::isBolVazia($objAvisoDTO->getStrNomeArquivo())) {
      if (!InfraString::isBolVazia($objAvisoDTO->getStrNomeArquivo())) {
        $objAvisoDTO->setStrImagem(base64_encode(file_get_contents(DIR_SEI_TEMP . '/' . $objAvisoDTO->getStrNomeArquivo())));
      }
    }else{
      $objInfraException->adicionarValidacao('Imagem não informada.');
    }
  }

  protected function cadastrarControlado(AvisoDTO $objAvisoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_cadastrar', __METHOD__, $objAvisoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrStaAviso($objAvisoDTO, $objInfraException);
      $this->validarDthInicio($objAvisoDTO, $objInfraException);
      $this->validarDthFim($objAvisoDTO, $objInfraException);
      $this->validarArrObjRelAvisoOrgao($objAvisoDTO, $objInfraException);
      $this->validarStrDescricao($objAvisoDTO, $objInfraException);
      $this->validarStrLink($objAvisoDTO, $objInfraException);
      $this->validarStrImagem($objAvisoDTO, $objInfraException);

      $this->validarIntervaloDatas($objAvisoDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      $ret = $objAvisoBD->cadastrar($objAvisoDTO);

      if ($objAvisoDTO->isSetArrObjRelAvisoOrgaoDTO()){
        $objRelAvisoOrgaoRN = new RelAvisoOrgaoRN();
        foreach($objAvisoDTO->getArrObjRelAvisoOrgaoDTO() as $dto){
          $dto->setNumIdAviso($ret->getNumIdAviso());
          $objRelAvisoOrgaoRN->cadastrar($dto);
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Aviso.',$e);
    }
  }

  protected function alterarControlado(AvisoDTO $objAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_alterar', __METHOD__, $objAvisoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAvisoDTO->isSetStrStaAviso()){
        $this->validarStrStaAviso($objAvisoDTO, $objInfraException);
      }
      if ($objAvisoDTO->isSetDthInicio()){
        $this->validarDthInicio($objAvisoDTO, $objInfraException);
      }
      if ($objAvisoDTO->isSetDthFim()){
        $this->validarDthFim($objAvisoDTO, $objInfraException);
      }
      if ($objAvisoDTO->isSetArrObjRelAvisoOrgaoDTO()){
        $this->validarArrObjRelAvisoOrgao($objAvisoDTO, $objInfraException);
      }
      if ($objAvisoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objAvisoDTO, $objInfraException);
      }
      if ($objAvisoDTO->isSetStrLink()){
        $this->validarStrLink($objAvisoDTO, $objInfraException);
      }
      $this->validarStrImagem($objAvisoDTO, $objInfraException);

      $this->validarIntervaloDatas($objAvisoDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      $objAvisoBD->alterar($objAvisoDTO);

      if ($objAvisoDTO->isSetArrObjRelAvisoOrgaoDTO()){

        $objRelAvisoOrgaoDTO = new RelAvisoOrgaoDTO();
        $objRelAvisoOrgaoDTO->retNumIdAviso();
        $objRelAvisoOrgaoDTO->retNumIdOrgao();
        $objRelAvisoOrgaoDTO->setNumIdAviso($objAvisoDTO->getNumIdAviso());

        $objRelAvisoOrgaoRN = new RelAvisoOrgaoRN();
        $objRelAvisoOrgaoRN->excluir($objRelAvisoOrgaoRN->listar($objRelAvisoOrgaoDTO));

        foreach($objAvisoDTO->getArrObjRelAvisoOrgaoDTO() as $dto){
          $dto->setNumIdAviso($objAvisoDTO->getNumIdAviso());
          $objRelAvisoOrgaoRN->cadastrar($dto);
        }
      }


    }catch(Exception $e){
      throw new InfraException('Erro alterando Aviso.',$e);
    }
  }

  private function validarIntervaloDatas($objAvisoDTO,$objInfraException){

    if(InfraData::compararDataHora($objAvisoDTO->getDthInicio(),$objAvisoDTO->getDthFim()) <= 0){
      $objInfraException->adicionarValidacao('Data/Hora Fim deve ser posterior a Data/Hora Início.');
    }

    $objAvisoDTO_Pesquisa = new AvisoDTO();
    $objAvisoDTO_Pesquisa->retNumIdOrgaoRelAvisoOrgao();
    $objAvisoDTO_Pesquisa->retStrSiglaOrgaoRelAvisoOrgao();
    $objAvisoDTO_Pesquisa->setStrStaAviso($objAvisoDTO->getStrStaAviso());
    $objAvisoDTO_Pesquisa->setNumIdAviso($objAvisoDTO->getNumIdAviso(),InfraDTO::$OPER_DIFERENTE);
    $objAvisoDTO_Pesquisa->setBolExclusaoLogica(false);

    $objAvisoDTO_Pesquisa->adicionarCriterio(
        array('Inicio', 'Fim'),
        array(InfraDTO::$OPER_MENOR_IGUAL, InfraDTO::$OPER_MAIOR_IGUAL),
        array($objAvisoDTO->getDthInicio(), $objAvisoDTO->getDthInicio()),
        array(InfraDTO::$OPER_LOGICO_AND),'c1');

    $objAvisoDTO_Pesquisa->adicionarCriterio(
        array('Inicio', 'Fim'),
        array(InfraDTO::$OPER_MENOR_IGUAL, InfraDTO::$OPER_MAIOR_IGUAL),
        array($objAvisoDTO->getDthFim(), $objAvisoDTO->getDthFim()),
        array(InfraDTO::$OPER_LOGICO_AND),"c2");

    $objAvisoDTO_Pesquisa->adicionarCriterio(
      array('Inicio', 'Fim'),
      array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
      array($objAvisoDTO->getDthInicio(), $objAvisoDTO->getDthFim()),
      array(InfraDTO::$OPER_LOGICO_AND),"c3");

    $objAvisoDTO_Pesquisa->agruparCriterios(array('c1','c2','c3'),array(InfraDTO::$OPER_LOGICO_OR,InfraDTO::$OPER_LOGICO_OR));
      if($objAvisoDTO->getStrSinLiberado() == "S"){
        $objAvisoDTO_Pesquisa->setStrSinLiberado("S");
    }

    $arrOrgaos = array();
    $arrObjAvisoDTO = $this->listar($objAvisoDTO_Pesquisa);
    foreach($arrObjAvisoDTO as $dto){
      foreach($objAvisoDTO->getArrObjRelAvisoOrgaoDTO() as $objRelAvisoOrgaoDTO){
        if ($dto->getNumIdOrgaoRelAvisoOrgao() == $objRelAvisoOrgaoDTO->getNumIdOrgao()){
          $arrOrgaos[$dto->getNumIdOrgaoRelAvisoOrgao()] = $dto->getStrSiglaOrgaoRelAvisoOrgao();
        }
      }
    }

    $numOrgaos = count($arrOrgaos);
    if ($numOrgaos){
      if ($numOrgaos == 1) {
        $objInfraException->adicionarValidacao('Já existe aviso desse tipo cadastrado nesse período de datas para o órgão '.array_values($arrOrgaos)[0].'.');
      }else{
        $objInfraException->adicionarValidacao('Já existe aviso desse tipo cadastrado nesse período de datas para os órgãos: '.InfraArray::formatarMsgArray(array_values($arrOrgaos)).'.');
      }
    }
  }

  protected function excluirControlado($arrObjAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_excluir', __METHOD__, $arrObjAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      $objRelAvisoOrgaoRN = new RelAvisoOrgaoRN();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAvisoDTO);$i++){

        $objRelAvisoOrgaoDTO = new RelAvisoOrgaoDTO();
        $objRelAvisoOrgaoDTO->retNumIdOrgao();
        $objRelAvisoOrgaoDTO->retNumIdAviso();
        $objRelAvisoOrgaoDTO->setNumIdAviso($arrObjAvisoDTO[$i]->getNumIdAviso());
        $objRelAvisoOrgaoRN->excluir($objRelAvisoOrgaoRN->listar($objRelAvisoOrgaoDTO));

        $objAvisoBD->excluir($arrObjAvisoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Aviso.',$e);
    }
  }

  protected function consultarConectado(AvisoDTO $objAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_consultar', __METHOD__, $objAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      $ret = $objAvisoBD->consultar($objAvisoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Aviso.',$e);
    }
  }

  protected function listarConectado(AvisoDTO $objAvisoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_listar', __METHOD__, $objAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      $ret = $objAvisoBD->listar($objAvisoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Avisos.',$e);
    }
  }

  protected function contarConectado(AvisoDTO $objAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_listar', __METHOD__, $objAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      $ret = $objAvisoBD->contar($objAvisoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Avisos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_desativar', __METHOD__, $arrObjAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAvisoDTO);$i++){
        $objAvisoBD->desativar($arrObjAvisoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Aviso.',$e);
    }
  }

  protected function reativarControlado($arrObjAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_reativar', __METHOD__, $arrObjAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAvisoDTO);$i++){
        $objAvisoBD->reativar($arrObjAvisoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Aviso.',$e);
    }
  }

  protected function bloquearControlado(AvisoDTO $objAvisoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('aviso_consultar', __METHOD__, $objAvisoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAvisoBD = new AvisoBD($this->getObjInfraIBanco());
      $ret = $objAvisoBD->bloquear($objAvisoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Aviso.',$e);
    }
  }

 */
}
