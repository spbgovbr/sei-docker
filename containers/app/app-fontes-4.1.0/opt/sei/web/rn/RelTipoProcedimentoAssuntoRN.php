<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 03/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.9.2
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelTipoProcedimentoAssuntoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0285Controlado(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_tipo_procedimento_assunto_cadastrar',__METHOD__,$objRelTipoProcedimentoAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdTipoProcedimentoRN0558($objRelTipoProcedimentoAssuntoDTO, $objInfraException);
      $this->validarNumIdAssuntoRN0557($objRelTipoProcedimentoAssuntoDTO, $objInfraException);
      $this->validarNumSequenciaRN1193($objRelTipoProcedimentoAssuntoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAssuntoProxyDTO = new AssuntoProxyDTO();
      $objAssuntoProxyDTO->retNumIdAssuntoProxy();
      $objAssuntoProxyDTO->setNumIdAssunto($objRelTipoProcedimentoAssuntoDTO->getNumIdAssunto());
      $objAssuntoProxyDTO->setNumMaxRegistrosRetorno(1);
      $objAssuntoProxyDTO->setOrdNumIdAssuntoProxy(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objAssuntoProxyRN = new AssuntoProxyRN();
      $objAssuntoProxyDTO = $objAssuntoProxyRN->consultar($objAssuntoProxyDTO);

      if ($objAssuntoProxyDTO == null){
        throw new InfraException('Assunto não consta na tabela de utilização.');
      }

      $objRelTipoProcedimentoAssuntoDTO->setNumIdAssuntoProxy($objAssuntoProxyDTO->getNumIdAssuntoProxy());

      $objRelTipoProcedimentoAssuntoBD = new RelTipoProcedimentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelTipoProcedimentoAssuntoBD->cadastrar($objRelTipoProcedimentoAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Assunto associado ao Tipo de Processo.',$e);
    }
  }

  protected function excluirRN0286Controlado($arrObjRelTipoProcedimentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_tipo_procedimento_assunto_excluir',__METHOD__,$arrObjRelTipoProcedimentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelTipoProcedimentoAssuntoBD = new RelTipoProcedimentoAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelTipoProcedimentoAssuntoDTO);$i++){
        $objRelTipoProcedimentoAssuntoBD->excluir($arrObjRelTipoProcedimentoAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Assunto associado ao Tipo de Processo.',$e);
    }
  }

  protected function listarRN0192Conectado(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_tipo_procedimento_assunto_listar',__METHOD__,$objRelTipoProcedimentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelTipoProcedimentoAssuntoBD = new RelTipoProcedimentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelTipoProcedimentoAssuntoBD->listar($objRelTipoProcedimentoAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Assuntos associados aos Tipos de Processo.',$e);
    }
  }

  protected function consultarConectado(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_tipo_procedimento_assunto_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelTipoProcedimentoAssuntoBD = new RelTipoProcedimentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelTipoProcedimentoAssuntoBD->consultar($objRelTipoProcedimentoAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando associação entre Assunto e Tipo de Processo.',$e);
    }
  }
  
  protected function contarRN0287Conectado(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_tipo_procedimento_assunto_listar',__METHOD__,$objRelTipoProcedimentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelTipoProcedimentoAssuntoBD = new RelTipoProcedimentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objRelTipoProcedimentoAssuntoBD->contar($objRelTipoProcedimentoAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Assuntos associados aos Tipos de Processo.',$e);
    }
  }

  private function validarNumIdTipoProcedimentoRN0558(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelTipoProcedimentoAssuntoDTO->getNumIdTipoProcedimento())){
      $objInfraException->adicionarValidacao('Tipo de Processo não informado na associação com Assunto.');
    }
  }

  private function validarNumIdAssuntoRN0557(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelTipoProcedimentoAssuntoDTO->getNumIdAssunto())){
      $objInfraException->adicionarValidacao('Assunto não informado na associação com Tipo de Processo.');
    }
  }

  private function validarNumSequenciaRN1193(RelTipoProcedimentoAssuntoDTO $objRelTipoProcedimentoAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelTipoProcedimentoAssuntoDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência não informada para o assunto do tipo de processo.');
    }
  }
  
}
?>