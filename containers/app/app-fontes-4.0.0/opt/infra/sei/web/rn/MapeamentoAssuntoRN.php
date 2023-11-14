<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MapeamentoAssuntoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAssuntoOrigem(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMapeamentoAssuntoDTO->getNumIdAssuntoOrigem())){
      $objInfraException->adicionarValidacao('Assunto Origem não informado.');
    }
  }

  private function validarNumIdAssuntoDestino(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMapeamentoAssuntoDTO->getNumIdAssuntoDestino())){
      $objInfraException->adicionarValidacao('Assunto Destino não informado.');
    }
  }

  protected function cadastrarControlado(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_cadastrar',__METHOD__,$objMapeamentoAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAssuntoOrigem($objMapeamentoAssuntoDTO, $objInfraException);
      $this->validarNumIdAssuntoDestino($objMapeamentoAssuntoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objMapeamentoAssuntoBD->cadastrar($objMapeamentoAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Mapeamento de Assunto.',$e);
    }
  }

  protected function alterarControlado(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_alterar',__METHOD__,$objMapeamentoAssuntoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMapeamentoAssuntoDTO->isSetNumIdAssuntoOrigem()){
        $this->validarNumIdAssuntoOrigem($objMapeamentoAssuntoDTO, $objInfraException);
      }
      if ($objMapeamentoAssuntoDTO->isSetNumIdAssuntoDestino()){
        $this->validarNumIdAssuntoDestino($objMapeamentoAssuntoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      $objMapeamentoAssuntoBD->alterar($objMapeamentoAssuntoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Mapeamento de Assunto.',$e);
    }
  }

  protected function excluirControlado($arrObjMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_excluir',__METHOD__,$arrObjMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMapeamentoAssuntoDTO);$i++){
        $objMapeamentoAssuntoBD->excluir($arrObjMapeamentoAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Mapeamento de Assunto.',$e);
    }
  }

  protected function consultarConectado(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_consultar',__METHOD__,$objMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objMapeamentoAssuntoBD->consultar($objMapeamentoAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Mapeamento de Assunto.',$e);
    }
  }

  protected function listarConectado(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_listar',__METHOD__,$objMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objMapeamentoAssuntoBD->listar($objMapeamentoAssuntoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Mapeamentos de Assuntos.',$e);
    }
  }

  protected function contarConectado(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_listar',__METHOD__,$objMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objMapeamentoAssuntoBD->contar($objMapeamentoAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Mapeamentos de Assuntos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_desativar',__METHOD__,$arrObjMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMapeamentoAssuntoDTO);$i++){
        $objMapeamentoAssuntoBD->desativar($arrObjMapeamentoAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Mapeamento de Assunto.',$e);
    }
  }

  protected function reativarControlado($arrObjMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_reativar',__METHOD__,$arrObjMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMapeamentoAssuntoDTO);$i++){
        $objMapeamentoAssuntoBD->reativar($arrObjMapeamentoAssuntoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Mapeamento de Assunto.',$e);
    }
  }

  protected function bloquearControlado(MapeamentoAssuntoDTO $objMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_consultar',__METHOD__,$objMapeamentoAssuntoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMapeamentoAssuntoBD = new MapeamentoAssuntoBD($this->getObjInfraIBanco());
      $ret = $objMapeamentoAssuntoBD->bloquear($objMapeamentoAssuntoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Mapeamento de Assunto.',$e);
    }
  }

 */

  protected function pesquisarConectado(MapeamentoAssuntoDTO $parObjMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_listar',__METHOD__,$parObjMapeamentoAssuntoDTO);

      $ret = array();

      $objAssuntoProxyDTO = new AssuntoProxyDTO();
      $objAssuntoProxyDTO->setDistinct(true);
      $objAssuntoProxyDTO->retNumIdAssunto();
      $objAssuntoProxyDTO->retStrCodigoEstruturadoAssunto();
      $objAssuntoProxyDTO->retStrDescricaoAssunto();
      $objAssuntoProxyDTO->retStrSinAtivoAssunto();
      $objAssuntoProxyDTO->setNumIdTabelaAssuntosAssunto($parObjMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoOrigem());

      $objAssuntoProxyDTO->setStrPalavrasPesquisa($parObjMapeamentoAssuntoDTO->getStrPalavrasPesquisa());
      $objAssuntoProxyDTO = InfraString::prepararPesquisaDTO($objAssuntoProxyDTO,"PalavrasPesquisa", "IdxAssuntoAssunto");

      if ($parObjMapeamentoAssuntoDTO->getStrSinAssuntosNaoMapeados()=='S') {
        $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoOrigem();
        $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoOrigem($parObjMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoOrigem());
        $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoDestino($parObjMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoDestino());
        $arrObjMapeamentoAssuntoDTOMapeados = InfraArray::indexarArrInfraDTO($this->listar($objMapeamentoAssuntoDTO), 'IdAssuntoOrigem');

        if (count($arrObjMapeamentoAssuntoDTOMapeados)) {
          $objAssuntoProxyDTO->setNumIdAssunto(InfraArray::converterArrInfraDTO($arrObjMapeamentoAssuntoDTOMapeados, 'IdAssuntoOrigem'), InfraDTO::$OPER_NOT_IN);
        }
      }

      $objAssuntoProxyDTO->setOrdStrCodigoEstruturadoAssunto($parObjMapeamentoAssuntoDTO->getOrdStrCodigoEstruturadoAssuntoOrigem());

      //paginação
      $objAssuntoProxyDTO->setNumMaxRegistrosRetorno($parObjMapeamentoAssuntoDTO->getNumMaxRegistrosRetorno());
      $objAssuntoProxyDTO->setNumPaginaAtual($parObjMapeamentoAssuntoDTO->getNumPaginaAtual());

      $objAssuntoProxyRN = new AssuntoProxyRN();
      $arrObjAssuntoProxyDTO = $objAssuntoProxyRN->listar($objAssuntoProxyDTO);

      //paginação
      $parObjMapeamentoAssuntoDTO->setNumTotalRegistros($objAssuntoProxyDTO->getNumTotalRegistros());
      $parObjMapeamentoAssuntoDTO->setNumRegistrosPaginaAtual($objAssuntoProxyDTO->getNumRegistrosPaginaAtual());

      if (count($arrObjAssuntoProxyDTO)){

        $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoOrigem();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoDestino();
        $objMapeamentoAssuntoDTO->retStrCodigoEstruturadoAssuntoDestino();
        $objMapeamentoAssuntoDTO->retStrDescricaoAssuntoDestino();
        $objMapeamentoAssuntoDTO->retStrSinAtivoAssuntoDestino();
        $objMapeamentoAssuntoDTO->setNumIdAssuntoOrigem(InfraArray::converterArrInfraDTO($arrObjAssuntoProxyDTO,'IdAssunto'),InfraDTO::$OPER_IN);
        $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoDestino($parObjMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoDestino());

        $arrObjMapeamentoAssuntoDTOMapeados = InfraArray::indexarArrInfraDTO($this->listar($objMapeamentoAssuntoDTO),'IdAssuntoOrigem');

        foreach($arrObjAssuntoProxyDTO as $objAssuntoProxyDTO){

          $dto = new MapeamentoAssuntoDTO();
          $dto->setNumIdAssuntoOrigem($objAssuntoProxyDTO->getNumIdAssunto());
          $dto->setStrCodigoEstruturadoAssuntoOrigem($objAssuntoProxyDTO->getStrCodigoEstruturadoAssunto());
          $dto->setStrDescricaoAssuntoOrigem($objAssuntoProxyDTO->getStrDescricaoAssunto());
          $dto->setStrSinAtivoAssuntoOrigem($objAssuntoProxyDTO->getStrSinAtivoAssunto());

          if (isset($arrObjMapeamentoAssuntoDTOMapeados[$objAssuntoProxyDTO->getNumIdAssunto()])){

            $objMapeamentoAssuntoDTOMapeado = $arrObjMapeamentoAssuntoDTOMapeados[$objAssuntoProxyDTO->getNumIdAssunto()];

            $dto->setNumIdAssuntoDestino($objMapeamentoAssuntoDTOMapeado->getNumIdAssuntoDestino());
            $dto->setStrCodigoEstruturadoAssuntoDestino($objMapeamentoAssuntoDTOMapeado->getStrCodigoEstruturadoAssuntoDestino());
            $dto->setStrDescricaoAssuntoDestino($objMapeamentoAssuntoDTOMapeado->getStrDescricaoAssuntoDestino());
            $dto->setStrSinAtivoAssuntoDestino($objMapeamentoAssuntoDTOMapeado->getStrSinAtivoAssuntoDestino());

          }else{

            $dto->setNumIdAssuntoDestino(null);
            $dto->setStrCodigoEstruturadoAssuntoDestino(null);
            $dto->setStrDescricaoAssuntoDestino(null);
            $dto->setStrSinAtivoAssuntoDestino(null);

          }

          $ret[] = $dto;
        }
      }

      return $ret;

      //Auditoria
    }catch(Exception $e){
      throw new InfraException('Erro pesquisando mapeamentos de assuntos.',$e);
    }
  }

  protected function gerenciarControlado($arrObjMapeamentoAssuntoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('mapeamento_assunto_gerenciar',__METHOD__,$arrObjMapeamentoAssuntoDTO);

      foreach($arrObjMapeamentoAssuntoDTO as $objMapeamentoAssuntoDTO){

        $objMapeamentoAssuntoDTOBanco = new MapeamentoAssuntoDTO();
        $objMapeamentoAssuntoDTOBanco->retNumIdAssuntoOrigem();
        $objMapeamentoAssuntoDTOBanco->retNumIdAssuntoDestino();
        $objMapeamentoAssuntoDTOBanco->setNumIdAssuntoOrigem($objMapeamentoAssuntoDTO->getNumIdAssuntoOrigem());
        $objMapeamentoAssuntoDTOBanco->setNumIdTabelaAssuntosAssuntoDestino($objMapeamentoAssuntoDTO->getNumIdTabelaAssuntosAssuntoDestino());

        $objMapeamentoAssuntoDTOBanco = $this->consultar($objMapeamentoAssuntoDTOBanco);

        if ($objMapeamentoAssuntoDTO->getNumIdAssuntoDestino()==null){

          if ($objMapeamentoAssuntoDTOBanco!=null){
            $this->excluir(array($objMapeamentoAssuntoDTOBanco));
          }

        }else{

          if ($objMapeamentoAssuntoDTOBanco!=null){

            if ($objMapeamentoAssuntoDTOBanco->getNumIdAssuntoDestino()==$objMapeamentoAssuntoDTO->getNumIdAssuntoDestino()) {
              continue;
            }else{
              $this->excluir(array($objMapeamentoAssuntoDTOBanco));
            }

          }

          $this->cadastrar($objMapeamentoAssuntoDTO);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro gerenciando mapeamentos de assuntos.',$e);
    }
  }
}
?>