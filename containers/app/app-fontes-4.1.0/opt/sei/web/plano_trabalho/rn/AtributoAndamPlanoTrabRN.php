<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/10/2022 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.1
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class AtributoAndamPlanoTrabRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

  private function validarNumIdAndamentoPlanoTrabalho(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAtributoAndamPlanoTrabDTO->getNumIdAndamentoPlanoTrabalho())) {
      $objInfraException->adicionarValidacao('Andamento do Plano de Trabalho não informado.');
    }
  }

  private function validarStrChave(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAtributoAndamPlanoTrabDTO->getStrChave())) {
      $objInfraException->adicionarValidacao('Chave não informada.');
    } else {
      $objAtributoAndamPlanoTrabDTO->setStrChave(trim($objAtributoAndamPlanoTrabDTO->getStrChave()));

      if (strlen($objAtributoAndamPlanoTrabDTO->getStrChave()) > 50) {
        $objInfraException->adicionarValidacao('Chave possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrValor(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAtributoAndamPlanoTrabDTO->getStrValor())) {
      $objAtributoAndamPlanoTrabDTO->setStrValor(null);
    } else {
      $objAtributoAndamPlanoTrabDTO->setStrValor(trim($objAtributoAndamPlanoTrabDTO->getStrValor()));

      if (strlen($objAtributoAndamPlanoTrabDTO->getStrValor()) > 250) {
        $objInfraException->adicionarValidacao('Valor possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrIdOrigem(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objAtributoAndamPlanoTrabDTO->getStrIdOrigem())) {
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem(null);
    } else {
      $objAtributoAndamPlanoTrabDTO->setStrIdOrigem(trim($objAtributoAndamPlanoTrabDTO->getStrIdOrigem()));

      if (strlen($objAtributoAndamPlanoTrabDTO->getStrIdOrigem()) > 50) {
        $objInfraException->adicionarValidacao('ID de Origem possui tamanho superior a 50 caracteres.');
      }
    }
  }


  protected function cadastrarControlado(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_cadastrar', __METHOD__, $objAtributoAndamPlanoTrabDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAndamentoPlanoTrabalho($objAtributoAndamPlanoTrabDTO, $objInfraException);
      $this->validarStrChave($objAtributoAndamPlanoTrabDTO, $objInfraException);
      $this->validarStrValor($objAtributoAndamPlanoTrabDTO, $objInfraException);
      $this->validarStrIdOrigem($objAtributoAndamPlanoTrabDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamPlanoTrabBD->cadastrar($objAtributoAndamPlanoTrabDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Atributo de Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function alterarControlado(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_alterar', __METHOD__, $objAtributoAndamPlanoTrabDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAtributoAndamPlanoTrabDTO->isSetNumIdAndamentoPlanoTrabalho()) {
        $this->validarNumIdAndamentoPlanoTrabalho($objAtributoAndamPlanoTrabDTO, $objInfraException);
      }
      if ($objAtributoAndamPlanoTrabDTO->isSetStrChave()) {
        $this->validarStrChave($objAtributoAndamPlanoTrabDTO, $objInfraException);
      }
      if ($objAtributoAndamPlanoTrabDTO->isSetStrValor()) {
        $this->validarStrValor($objAtributoAndamPlanoTrabDTO, $objInfraException);
      }
      if ($objAtributoAndamPlanoTrabDTO->isSetStrIdOrigem()) {
        $this->validarStrIdOrigem($objAtributoAndamPlanoTrabDTO, $objInfraException);
      }


      $objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
      $objAtributoAndamPlanoTrabBD->alterar($objAtributoAndamPlanoTrabDTO);
    } catch (Exception $e) {
      throw new InfraException('Erro alterando Atributo de Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function excluirControlado($arrObjAtributoAndamPlanoTrabDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_excluir', __METHOD__, $arrObjAtributoAndamPlanoTrabDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjAtributoAndamPlanoTrabDTO); $i++) {
        $objAtributoAndamPlanoTrabBD->excluir($arrObjAtributoAndamPlanoTrabDTO[$i]);
      }
    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Atributo de Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function consultarConectado(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_consultar', __METHOD__, $objAtributoAndamPlanoTrabDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamPlanoTrabBD->consultar($objAtributoAndamPlanoTrabDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Atributo de Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function listarConectado(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_listar', __METHOD__, $objAtributoAndamPlanoTrabDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamPlanoTrabBD->listar($objAtributoAndamPlanoTrabDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando Atributos de Andamento do Plano de Trabalho.', $e);
    }
  }

  protected function contarConectado(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO) {
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_listar', __METHOD__, $objAtributoAndamPlanoTrabDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamPlanoTrabBD->contar($objAtributoAndamPlanoTrabDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Atributos de Andamento do Plano de Trabalho.', $e);
    }
  }
  /*
    protected function desativarControlado($arrObjAtributoAndamPlanoTrabDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_desativar', __METHOD__, $arrObjAtributoAndamPlanoTrabDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjAtributoAndamPlanoTrabDTO);$i++){
          $objAtributoAndamPlanoTrabBD->desativar($arrObjAtributoAndamPlanoTrabDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Atributo de Andamento do Plano de Trabalho.',$e);
      }
    }

    protected function reativarControlado($arrObjAtributoAndamPlanoTrabDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_reativar', __METHOD__, $arrObjAtributoAndamPlanoTrabDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjAtributoAndamPlanoTrabDTO);$i++){
          $objAtributoAndamPlanoTrabBD->reativar($arrObjAtributoAndamPlanoTrabDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Atributo de Andamento do Plano de Trabalho.',$e);
      }
    }

    protected function bloquearControlado(AtributoAndamPlanoTrabDTO $objAtributoAndamPlanoTrabDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andam_plano_trab_consultar', __METHOD__, $objAtributoAndamPlanoTrabDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objAtributoAndamPlanoTrabBD = new AtributoAndamPlanoTrabBD($this->getObjInfraIBanco());
        $ret = $objAtributoAndamPlanoTrabBD->bloquear($objAtributoAndamPlanoTrabDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Atributo de Andamento do Plano de Trabalho.',$e);
      }
    }

   */
}
