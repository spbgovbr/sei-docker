<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/07/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelProtocoloAtributoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocolo(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloAtributoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Id do Protocolo não informado.');
    }
  }

  private function validarNumIdAtributo(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelProtocoloAtributoDTO->getNumIdAtributo())){
      $objInfraException->adicionarValidacao('Id do Atributo não informado.');
    }
  }

  private function validarStrValor(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO, InfraException $objInfraException){

    $objAtributoDTO = new AtributoDTO();
    $objAtributoDTO->setBolExclusaoLogica(false);
    $objAtributoDTO->retStrNome();
    $objAtributoDTO->retStrRotulo();
    $objAtributoDTO->retStrSinObrigatorio();
    $objAtributoDTO->retStrStaTipo();
    $objAtributoDTO->retStrValorMinimo();
    $objAtributoDTO->retStrValorMaximo();
    $objAtributoDTO->retNumTamanho();
    $objAtributoDTO->retNumDecimais();
    $objAtributoDTO->retStrMascara();
    $objAtributoDTO->setNumIdAtributo($objRelProtocoloAtributoDTO->getNumIdAtributo());

    $objAtributoRN = new AtributoRN();
    $objAtributoDTO = $objAtributoRN->consultarRN0115($objAtributoDTO);

    if (InfraString::isBolVazia($objRelProtocoloAtributoDTO->getStrValor())){
      if ($objAtributoDTO->getStrSinObrigatorio()=='N') {
        $objRelProtocoloAtributoDTO->setStrValor(null);
      }else{
        $objInfraException->adicionarValidacao('Campo "'.$objAtributoDTO->getStrRotulo().'" não informado.');
      }
    }else{
      $objRelProtocoloAtributoDTO->setStrValor(trim($objRelProtocoloAtributoDTO->getStrValor()));

      $bolValidouTamanho = false;

      switch($objAtributoDTO->getStrStaTipo()){
        case AtributoRN::$TA_DATA:
          if(!InfraData::validarData($objRelProtocoloAtributoDTO->getStrValor())) {
            $objInfraException->adicionarValidacao('Valor de data inválido para o campo "' . $objAtributoDTO->getStrRotulo() . '".');
          }else {
            if ($objAtributoDTO->getStrValorMinimo() != null && $objAtributoDTO->getStrValorMaximo() != null) {

              if ($objAtributoDTO->getStrValorMinimo() == '@HOJE@' && $objAtributoDTO->getStrValorMaximo() == '@FUTURO@') {

                if (InfraData::compararDatas($objRelProtocoloAtributoDTO->getStrValor(), InfraData::getStrDataAtual()) > 0) {
                  $objInfraException->adicionarValidacao('O valor para o campo "' . $objAtributoDTO->getStrRotulo() . '" deve ser igual ou superior a ' . InfraData::getStrDataAtual() . '.');
                }

              } else if ($objAtributoDTO->getStrValorMinimo() == '@PASSADO@' && $objAtributoDTO->getStrValorMaximo() == '@HOJE@') {

                if (InfraData::compararDatas($objRelProtocoloAtributoDTO->getStrValor(), InfraData::getStrDataAtual()) < 0) {
                  $objInfraException->adicionarValidacao('O valor para o campo "' . $objAtributoDTO->getStrRotulo() . '" deve ser igual ou inferior a ' . InfraData::getStrDataAtual() . '.');
                }

              } else if ($objAtributoDTO->getStrValorMinimo() == '@AMANHA@' && $objAtributoDTO->getStrValorMaximo() == '@FUTURO@') {

                if (InfraData::compararDatas($objRelProtocoloAtributoDTO->getStrValor(), InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE)) > 0) {
                  $objInfraException->adicionarValidacao('O valor para o campo "' . $objAtributoDTO->getStrRotulo() . '" deve ser superior a ' . InfraData::getStrDataAtual() . '.');
                }

              } else if ($objAtributoDTO->getStrValorMinimo() == '@PASSADO@' && $objAtributoDTO->getStrValorMaximo() == '@ONTEM@') {

                if (InfraData::compararDatas($objRelProtocoloAtributoDTO->getStrValor(), InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS)) < 0) {
                  $objInfraException->adicionarValidacao('O valor para o campo "' . $objAtributoDTO->getStrRotulo() . '" deve ser inferior a ' . InfraData::getStrDataAtual() . '.');
                }

              } else {

                if (InfraData::compararDatas($objRelProtocoloAtributoDTO->getStrValor(), $objAtributoDTO->getStrValorMinimo()) > 0 ||
                    InfraData::compararDatas($objRelProtocoloAtributoDTO->getStrValor(), $objAtributoDTO->getStrValorMaximo()) < 0
                ) {
                  $objInfraException->adicionarValidacao('O valor para o campo "' . $objAtributoDTO->getStrRotulo() . '" deve estar entre ' . $objAtributoDTO->getStrValorMinimo() . ' e ' . $objAtributoDTO->getStrValorMaximo() . '.');
                }
              }
            }
          }

          break;

        case AtributoRN::$TA_DINHEIRO:

          if (!InfraUtil::validarDin($objRelProtocoloAtributoDTO->getStrValor())){
            $objInfraException->adicionarValidacao('Valor monetário inválido para o campo "' . $objAtributoDTO->getStrRotulo() . '".');
          }else {
            if ($objAtributoDTO->getStrValorMinimo() != null && InfraUtil::prepararDin($objRelProtocoloAtributoDTO->getStrValor()) < InfraUtil::prepararDin($objAtributoDTO->getStrValorMinimo())) {
              $objInfraException->adicionarValidacao('O valor mínimo para o campo "' . $objAtributoDTO->getStrRotulo() . '" é ' . $objAtributoDTO->getStrValorMinimo() . '.');
            }

            if ($objAtributoDTO->getStrValorMaximo() != null && InfraUtil::prepararDin($objRelProtocoloAtributoDTO->getStrValor()) > InfraUtil::prepararDin($objAtributoDTO->getStrValorMaximo())) {
              $objInfraException->adicionarValidacao('O valor máximo para o campo "' . $objAtributoDTO->getStrRotulo() . '" é ' . $objAtributoDTO->getStrValorMaximo() . '.');
            }
          }

          break;

        case AtributoRN::$TA_LISTA:
        case AtributoRN::$TA_OPCOES:

          //considerar apenas valores ativos
          $objDominioDTO = new DominioDTO();
          $objDominioDTO->setNumIdAtributo($objRelProtocoloAtributoDTO->getNumIdAtributo());
          $objDominioDTO->setStrValor($objRelProtocoloAtributoDTO->getStrValor());

          $objDominioRN = new DominioRN();
          if ($objDominioRN->contarRN0584($objDominioDTO)==0){
            $objInfraException->adicionarValidacao('Valor "'.$objRelProtocoloAtributoDTO->getStrValor().'" não está disponível para o campo "' . $objAtributoDTO->getStrRotulo() . '".');
          }

          break;

        case AtributoRN::$TA_NUMERO_INTEIRO:
          if(!is_numeric($objRelProtocoloAtributoDTO->getStrValor())) {
            $objInfraException->adicionarValidacao('Valor numérico inválido para o campo "' . $objAtributoDTO->getStrRotulo() . '".');
          }else {
            if ($objAtributoDTO->getNumTamanho() != null && strlen($objRelProtocoloAtributoDTO->getStrValor()) > $objAtributoDTO->getNumTamanho()) {
              $objInfraException->adicionarValidacao('Comprimento do campo "' . $objAtributoDTO->getStrRotulo() . '" excede o tamanho permitido.');
              $bolValidouTamanho = true;
            }

            if ($objAtributoDTO->getStrValorMinimo() != null && $objRelProtocoloAtributoDTO->getStrValor() < $objAtributoDTO->getStrValorMinimo()) {
              $objInfraException->adicionarValidacao('O valor mínimo para o campo "' . $objAtributoDTO->getStrRotulo() . '" é ' . $objAtributoDTO->getStrValorMinimo() . '.');
            }

            if ($objAtributoDTO->getStrValorMaximo() != null && $objRelProtocoloAtributoDTO->getStrValor() > $objAtributoDTO->getStrValorMaximo()) {
              $objInfraException->adicionarValidacao('O valor máximo para o campo "' . $objAtributoDTO->getStrRotulo() . '" é ' . $objAtributoDTO->getStrValorMaximo() . '.');
            }
          }

          break;

        case AtributoRN::$TA_NUMERO_DECIMAL:

          if(!is_numeric(InfraUtil::prepararDbl($objRelProtocoloAtributoDTO->getStrValor()))) {
            $objInfraException->adicionarValidacao('Valor numérico inválido para o campo "' . $objAtributoDTO->getStrRotulo() . '".');
          }else {
            if ($objAtributoDTO->getNumTamanho() != null && strlen(str_replace(',', '', $objRelProtocoloAtributoDTO->getStrValor())) > $objAtributoDTO->getNumTamanho()) {
              $objInfraException->adicionarValidacao('Comprimento do campo "' . $objAtributoDTO->getStrRotulo() . '" excede o tamanho permitido.');
              $bolValidouTamanho = true;
            }

            if ($objAtributoDTO->getStrValorMinimo() != null && bccomp(InfraUtil::prepararDbl($objRelProtocoloAtributoDTO->getStrValor()), InfraUtil::prepararDbl($objAtributoDTO->getStrValorMinimo()), $objAtributoDTO->getNumDecimais()) == -1) {
              $objInfraException->adicionarValidacao('O valor mínimo para o campo "' . $objAtributoDTO->getStrRotulo() . '" é ' . $objAtributoDTO->getStrValorMinimo() . '.');
            }

            if ($objAtributoDTO->getStrValorMaximo() != null && bccomp(InfraUtil::prepararDbl($objRelProtocoloAtributoDTO->getStrValor()), InfraUtil::prepararDbl($objAtributoDTO->getStrValorMaximo()), $objAtributoDTO->getNumDecimais()) == 1) {
              $objInfraException->adicionarValidacao('O valor máximo para o campo "' . $objAtributoDTO->getStrRotulo() . '" é ' . $objAtributoDTO->getStrValorMaximo() . '.');
            }
          }

          break;

        case AtributoRN::$TA_TEXTO_GRANDE:
        case AtributoRN::$TA_TEXTO_SIMPLES:

          if ($objAtributoDTO->getNumTamanho() != null && strlen($objRelProtocoloAtributoDTO->getStrValor()) > $objAtributoDTO->getNumTamanho()) {
            $objInfraException->adicionarValidacao('Campo "' . $objAtributoDTO->getStrRotulo() . '" possui tamanho superior a ' . $objAtributoDTO->getNumTamanho() . ' caracteres.');
            $bolValidouTamanho = true;
          }

          break;

        case AtributoRN::$TA_TEXTO_MASCARA:

          if (!InfraUtil::validarMascara($objRelProtocoloAtributoDTO->getStrValor(),$objAtributoDTO->getStrMascara())) {
            $objInfraException->adicionarValidacao('Campo "' . $objAtributoDTO->getStrRotulo() . '" inválido.');
          }

          break;

        case AtributoRN::$TA_SINALIZADOR:

          if (!InfraUtil::isBolSinalizadorValido($objRelProtocoloAtributoDTO->getStrValor())) {
            $objInfraException->adicionarValidacao('Campo "' . $objAtributoDTO->getStrRotulo() . '" inválido.');
          }

          break;

        case AtributoRN::$TA_INFORMACAO:

          if ($objRelProtocoloAtributoDTO->getStrValor()!='') {
            $objInfraException->adicionarValidacao('Campo "' . $objAtributoDTO->getStrNome() . '" não pode receber valor.');
          }

          break;


        default:
          throw new InfraException('Tipo do campo "'.$objAtributoDTO->getStrRotulo().'" não mapeado para validação.');
      }

      if (!$bolValidouTamanho && strlen($objRelProtocoloAtributoDTO->getStrValor())>4000){
        $objInfraException->adicionarValidacao('Campo "'.$objAtributoDTO->getStrRotulo().'" excede o tamanho máximo permitido pelo sistema de 4000 caracteres.');
      }

    }
  }

  protected function cadastrarControlado(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_cadastrar',__METHOD__,$objRelProtocoloAtributoDTO);


      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocolo($objRelProtocoloAtributoDTO, $objInfraException);
      $this->validarNumIdAtributo($objRelProtocoloAtributoDTO, $objInfraException);
      $this->validarStrValor($objRelProtocoloAtributoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAtributoBD->cadastrar($objRelProtocoloAtributoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Atributo de Protocolo.',$e);
    }
  }

  protected function alterarControlado(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_alterar',__METHOD__,$objRelProtocoloAtributoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelProtocoloAtributoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objRelProtocoloAtributoDTO, $objInfraException);
      }

      if ($objRelProtocoloAtributoDTO->isSetNumIdAtributo()){
        $this->validarNumIdAtributo($objRelProtocoloAtributoDTO, $objInfraException);
      }

      if ($objRelProtocoloAtributoDTO->isSetStrValor()) {
        $this->validarStrValor($objRelProtocoloAtributoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      $objRelProtocoloAtributoBD->alterar($objRelProtocoloAtributoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Atributo de Protocolo.',$e);
    }
  }

  protected function excluirControlado($arrObjRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_excluir',__METHOD__,$arrObjRelProtocoloAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloAtributoDTO);$i++){
        $objRelProtocoloAtributoBD->excluir($arrObjRelProtocoloAtributoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Atributo de Protocolo.',$e);
    }
  }

  protected function consultarConectado(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_consultar',__METHOD__,$objRelProtocoloAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAtributoBD->consultar($objRelProtocoloAtributoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Atributo de Protocolo.',$e);
    }
  }

  protected function listarConectado(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_listar',__METHOD__,$objRelProtocoloAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAtributoBD->listar($objRelProtocoloAtributoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Atributos de Protocolo.',$e);
    }
  }

  protected function contarConectado(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_listar',__METHOD__,$objRelProtocoloAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAtributoBD->contar($objRelProtocoloAtributoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Atributos de Protocolo.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloAtributoDTO);$i++){
        $objRelProtocoloAtributoBD->desativar($arrObjRelProtocoloAtributoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Atributo de Protocolo.',$e);
    }
  }

  protected function reativarControlado($arrObjRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelProtocoloAtributoDTO);$i++){
        $objRelProtocoloAtributoBD->reativar($arrObjRelProtocoloAtributoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Atributo de Protocolo.',$e);
    }
  }

  protected function bloquearControlado(RelProtocoloAtributoDTO $objRelProtocoloAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_protocolo_atributo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelProtocoloAtributoBD = new RelProtocoloAtributoBD($this->getObjInfraIBanco());
      $ret = $objRelProtocoloAtributoBD->bloquear($objRelProtocoloAtributoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Atributo de Protocolo.',$e);
    }
  }

 */

  protected function validarValoresConectado($arrObjRelProtocoloAtributoDTO) {
    try{

      //Regras de Negocio
      $objInfraException = new InfraException();

      foreach($arrObjRelProtocoloAtributoDTO as $objRelProtocoloAtributoDTO) {
        $this->validarStrValor($objRelProtocoloAtributoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

    }catch(Exception $e){
      throw new InfraException('Erro validando atributos.',$e);
    }
  }
}
?>