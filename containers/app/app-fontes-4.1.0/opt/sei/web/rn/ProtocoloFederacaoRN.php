<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/05/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ProtocoloFederacaoRN extends InfraRN
{

  public function __construct()
  {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  private function validarStrIdProtocoloFederacao(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objProtocoloFederacaoDTO->getStrIdProtocoloFederacao())) {
      $objInfraException->adicionarValidacao('Identificador do SEI Federação não informado.');
    } else {

      if (!InfraULID::validar($objProtocoloFederacaoDTO->getStrIdProtocoloFederacao())) {
        $objInfraException->lancarValidacao('Identificador do SEI Federação '.$objProtocoloFederacaoDTO->getStrIdProtocoloFederacao().' inválido.');
      }

      $dto = new ProtocoloFederacaoDTO();
      $dto->retStrIdProtocoloFederacao();
      $dto->setNumMaxRegistrosRetorno(1);
      $dto->setBolExclusaoLogica(false);
      $dto->setStrIdProtocoloFederacao($objProtocoloFederacaoDTO->getStrIdProtocoloFederacao());
      if ($this->consultar($dto) != null) {
        $objInfraException->adicionarValidacao('Já existe um Protocolo cadastrado nesta instalação com o identificador '.$objProtocoloFederacaoDTO->getStrIdProtocoloFederacao().' do SEI Federação.');
      }
    }
  }

  private function validarStrIdInstalacaoFederacao(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objProtocoloFederacaoDTO->getStrIdInstalacaoFederacao())) {
      $objInfraException->adicionarValidacao('Instalação não informada.');
    }
  }

  private function validarStrProtocoloFormatado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objProtocoloFederacaoDTO->getStrProtocoloFormatado())) {
      $objInfraException->adicionarValidacao('Protocolo do SEI Federação não informado.');
    } else {
      $objProtocoloFederacaoDTO->setStrProtocoloFormatado(trim($objProtocoloFederacaoDTO->getStrProtocoloFormatado()));

      if (strlen($objProtocoloFederacaoDTO->getStrProtocoloFormatado()) > 50) {
        $objInfraException->adicionarValidacao('Protocolo do SEI Federação possui tamanho superior a 50 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_cadastrar', __METHOD__, $objProtocoloFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdProtocoloFederacao($objProtocoloFederacaoDTO, $objInfraException);
      $this->validarStrIdInstalacaoFederacao($objProtocoloFederacaoDTO, $objInfraException);
      $this->validarStrProtocoloFormatado($objProtocoloFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objProtocoloFederacaoDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao($objProtocoloFederacaoDTO->getStrProtocoloFormatado(), false));
      $objProtocoloFederacaoDTO->setStrProtocoloFormatadoPesqInv(strrev($objProtocoloFederacaoDTO->getStrProtocoloFormatadoPesquisa()));

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      $ret = $objProtocoloFederacaoBD->cadastrar($objProtocoloFederacaoDTO);

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro cadastrando Protocolo do SEI Federação.', $e);
    }
  }

  protected function alterarControlado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_alterar', __METHOD__, $objProtocoloFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objProtocoloFederacaoDTO->isSetStrIdInstalacaoFederacao()) {
        $this->validarStrIdInstalacaoFederacao($objProtocoloFederacaoDTO, $objInfraException);
      }
      if ($objProtocoloFederacaoDTO->isSetStrProtocoloFormatado()) {
        $this->validarStrProtocoloFormatado($objProtocoloFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      $objProtocoloFederacaoBD->alterar($objProtocoloFederacaoDTO);

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Protocolo do SEI Federação.', $e);
    }
  }

  protected function excluirControlado($arrObjProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_excluir', __METHOD__, $arrObjProtocoloFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjProtocoloFederacaoDTO); $i++) {
        $objProtocoloFederacaoBD->excluir($arrObjProtocoloFederacaoDTO[$i]);
      }

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Protocolo do SEI Federação.', $e);
    }
  }

  protected function consultarConectado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_consultar', __METHOD__, $objProtocoloFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      $ret = $objProtocoloFederacaoBD->consultar($objProtocoloFederacaoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Protocolo do SEI Federação.', $e);
    }
  }

  protected function listarConectado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_listar', __METHOD__, $objProtocoloFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      $ret = $objProtocoloFederacaoBD->listar($objProtocoloFederacaoDTO);

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro listando Protocolos do SEI Federação.', $e);
    }
  }

  protected function contarConectado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_listar', __METHOD__, $objProtocoloFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      $ret = $objProtocoloFederacaoBD->contar($objProtocoloFederacaoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Protocolos do SEI Federação.', $e);
    }
  }

  /*
    protected function desativarControlado($arrObjProtocoloFederacaoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_desativar', __METHOD__, $arrObjProtocoloFederacaoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjProtocoloFederacaoDTO);$i++){
          $objProtocoloFederacaoBD->desativar($arrObjProtocoloFederacaoDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Protocolo do SEI Federação.',$e);
      }
    }

    protected function reativarControlado($arrObjProtocoloFederacaoDTO){
      try {

        SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_reativar', __METHOD__, $arrObjProtocoloFederacaoDTO);

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjProtocoloFederacaoDTO);$i++){
          $objProtocoloFederacaoBD->reativar($arrObjProtocoloFederacaoDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Protocolo do SEI Federação.',$e);
      }
    }
    */

  protected function bloquearControlado(ProtocoloFederacaoDTO $objProtocoloFederacaoDTO)
  {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('protocolo_federacao_consultar', __METHOD__, $objProtocoloFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objProtocoloFederacaoBD = new ProtocoloFederacaoBD($this->getObjInfraIBanco());
      $ret = $objProtocoloFederacaoBD->bloquear($objProtocoloFederacaoDTO);

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Protocolo do SEI Federação.', $e);
    }
  }

}

