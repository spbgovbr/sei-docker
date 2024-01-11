<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TabelaAssuntosRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(TabelaAssuntosDTO $objTabelaAssuntosDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTabelaAssuntosDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objTabelaAssuntosDTO->setStrNome(trim($objTabelaAssuntosDTO->getStrNome()));

      if (strlen($objTabelaAssuntosDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }

      $dto = new TabelaAssuntosDTO();
      $dto->setNumIdTabelaAssuntos($objTabelaAssuntosDTO->getNumIdTabelaAssuntos(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrNome($objTabelaAssuntosDTO->getStrNome(),InfraDTO::$OPER_IGUAL);

      if ($this->contar($dto)){
        $objInfraException->adicionarValidacao('Existe outra tabela de assuntos que utiliza o mesmo Nome.');
      }
    }
  }

  private function validarStrDescricao(TabelaAssuntosDTO $objTabelaAssuntosDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTabelaAssuntosDTO->getStrDescricao())){
      $objTabelaAssuntosDTO->setStrDescricao(null);
    }else{
      $objTabelaAssuntosDTO->setStrDescricao(trim($objTabelaAssuntosDTO->getStrDescricao()));

      if (strlen($objTabelaAssuntosDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtual(TabelaAssuntosDTO $objTabelaAssuntosDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTabelaAssuntosDTO->getStrSinAtual())){
      $objInfraException->adicionarValidacao('Sinalizador de Sinalizador de Tabela Atual não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTabelaAssuntosDTO->getStrSinAtual())){
        $objInfraException->adicionarValidacao('Sinalizador de Sinalizador de Tabela Atual inválido.');
      }

      if ($objTabelaAssuntosDTO->getStrSinAtual()=='S') {

        $dto = new TabelaAssuntosDTO();
        $dto->setNumIdTabelaAssuntos($objTabelaAssuntosDTO->getNumIdTabelaAssuntos(), InfraDTO::$OPER_DIFERENTE);
        $dto->setStrSinAtual('S');

        if ($this->contar($dto)){
          $objInfraException->adicionarValidacao('Existe outra tabela de assuntos ativa.');
        }
      }
    }
  }

  protected function cadastrarControlado(TabelaAssuntosDTO $objTabelaAssuntosDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_cadastrar',__METHOD__,$objTabelaAssuntosDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objTabelaAssuntosDTO, $objInfraException);
      $this->validarStrDescricao($objTabelaAssuntosDTO, $objInfraException);
      $this->validarStrSinAtual($objTabelaAssuntosDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      $ret = $objTabelaAssuntosBD->cadastrar($objTabelaAssuntosDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tabela de Assuntos.',$e);
    }
  }

  protected function alterarControlado(TabelaAssuntosDTO $objTabelaAssuntosDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_alterar',__METHOD__,$objTabelaAssuntosDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTabelaAssuntosDTO->isSetStrNome()){
        $this->validarStrNome($objTabelaAssuntosDTO, $objInfraException);
      }
      if ($objTabelaAssuntosDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objTabelaAssuntosDTO, $objInfraException);
      }
      if ($objTabelaAssuntosDTO->isSetStrSinAtual()){
        $this->validarStrSinAtual($objTabelaAssuntosDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      $objTabelaAssuntosBD->alterar($objTabelaAssuntosDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tabela de Assuntos.',$e);
    }
  }

  protected function excluirControlado($arrObjTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_excluir',__METHOD__,$arrObjTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssuntoRN = new AssuntoRN();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTabelaAssuntosDTO);$i++){

        $objAssuntoDTO = new AssuntoDTO();
        $objAssuntoDTO->setBolExclusaoLogica(false);
        $objAssuntoDTO->retNumIdAssunto();
        $objAssuntoDTO->setNumIdTabelaAssuntos($arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos());
        $objAssuntoRN->excluirRN0248($objAssuntoRN->listarRN0247($objAssuntoDTO));

        $objTabelaAssuntosBD->excluir($arrObjTabelaAssuntosDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tabela de Assuntos.',$e);
    }
  }

  protected function consultarConectado(TabelaAssuntosDTO $objTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_consultar',__METHOD__,$objTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      $ret = $objTabelaAssuntosBD->consultar($objTabelaAssuntosDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tabela de Assuntos.',$e);
    }
  }

  protected function listarConectado(TabelaAssuntosDTO $objTabelaAssuntosDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_listar',__METHOD__,$objTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      $ret = $objTabelaAssuntosBD->listar($objTabelaAssuntosDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tabelas de Assuntos.',$e);
    }
  }

  protected function contarConectado(TabelaAssuntosDTO $objTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_listar',__METHOD__,$objTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      $ret = $objTabelaAssuntosBD->contar($objTabelaAssuntosDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tabelas de Assuntos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_desativar',__METHOD__,$arrObjTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTabelaAssuntosDTO);$i++){
        $objTabelaAssuntosBD->desativar($arrObjTabelaAssuntosDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tabela de Assuntos.',$e);
    }
  }

  protected function reativarControlado($arrObjTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_reativar',__METHOD__,$arrObjTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTabelaAssuntosDTO);$i++){
        $objTabelaAssuntosBD->reativar($arrObjTabelaAssuntosDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tabela de Assuntos.',$e);
    }
  }

  protected function bloquearControlado(TabelaAssuntosDTO $objTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_consultar',__METHOD__,$objTabelaAssuntosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTabelaAssuntosBD = new TabelaAssuntosBD($this->getObjInfraIBanco());
      $ret = $objTabelaAssuntosBD->bloquear($objTabelaAssuntosDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Tabela de Assuntos.',$e);
    }
  }

 */

  protected function ativarControlado(TabelaAssuntosDTO $parObjTabelaAssuntosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tabela_assuntos_ativar',__METHOD__,$parObjTabelaAssuntosDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
      $objTabelaAssuntosDTO->retNumIdTabelaAssuntos();
      $objTabelaAssuntosDTO->retStrNome();
      $objTabelaAssuntosDTO->setStrSinAtual('S');
      $objTabelaAssuntosDTOAtual = $this->consultar($objTabelaAssuntosDTO);

      $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
      $objTabelaAssuntosDTO->retNumIdTabelaAssuntos();
      $objTabelaAssuntosDTO->retStrNome();
      $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($parObjTabelaAssuntosDTO->getNumIdTabelaAssuntos());
      $objTabelaAssuntosDTONova = $this->consultar($objTabelaAssuntosDTO);

      $objAssuntoProxyRN = new AssuntoProxyRN();

      if ($objTabelaAssuntosDTOAtual!=null) {

        if ($objTabelaAssuntosDTOAtual->getNumIdTabelaAssuntos()==$objTabelaAssuntosDTONova->getNumIdTabelaAssuntos()){
          $objInfraException->lancarValidacao('Tabela de assuntos já está ativa.');
        }

        $objAssuntoProxyDTO = new AssuntoProxyDTO();
        $objAssuntoProxyDTO->retNumIdAssuntoProxy();
        $objAssuntoProxyDTO->retNumIdAssunto();

        $arrObjAssuntoProxyDTO = $objAssuntoProxyRN->listar($objAssuntoProxyDTO);

        $arrIdAssuntoProxy = array_unique(InfraArray::converterArrInfraDTO($arrObjAssuntoProxyDTO, 'IdAssunto'));

        $numAssuntosProxy = count($arrIdAssuntoProxy);

        $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoOrigem();
        $objMapeamentoAssuntoDTO->retNumIdAssuntoDestino();
        $objMapeamentoAssuntoDTO->setNumIdAssuntoOrigem($arrIdAssuntoProxy, InfraDTO::$OPER_IN);
        $objMapeamentoAssuntoDTO->setNumIdTabelaAssuntosAssuntoDestino($objTabelaAssuntosDTONova->getNumIdTabelaAssuntos());

        $objMapeamentoAssuntoRN = new MapeamentoAssuntoRN();
        $arrObjMapeamentoDTO = InfraArray::indexarArrInfraDTO($objMapeamentoAssuntoRN->listar($objMapeamentoAssuntoDTO), 'IdAssuntoOrigem');

        $numMapeamentos = count($arrObjMapeamentoDTO);

        if ($numAssuntosProxy != $numMapeamentos) {

          $arrDiferenca = array_diff($arrIdAssuntoProxy, array_keys($arrObjMapeamentoDTO));

          $objAssuntoDTO = new AssuntoDTO();
          $objAssuntoDTO->setBolExclusaoLogica(false);
          $objAssuntoDTO->retStrCodigoEstruturado();
          $objAssuntoDTO->setNumIdAssunto($arrDiferenca, InfraDTO::$OPER_IN);
          $objAssuntoDTO->setNumMaxRegistrosRetorno(10);

          $objAssuntoRN = new AssuntoRN();
          $arrObjAssuntoDTO = $objAssuntoRN->listarRN0247($objAssuntoDTO);

          $strCodigoAssuntos = implode(",\n",InfraArray::converterArrInfraDTO($arrObjAssuntoDTO, 'CodigoEstruturado'));

          if (count($arrObjAssuntoDTO) == 10){
            $strCodigoAssuntos .= "\n...";
          }

          $objInfraException->lancarValidacao('Os assuntos a seguir não foram mapeados da tabela "'.$objTabelaAssuntosDTOAtual->getStrNome().'" para a tabela "'.$objTabelaAssuntosDTONova->getStrNome().'":'."\n".$strCodigoAssuntos);
        }

        $arrIdAssuntoProxy = array();
        foreach ($arrObjAssuntoProxyDTO as $objAssuntoProxyDTO) {
          $numIdAssuntoMapeado = $arrObjMapeamentoDTO[$objAssuntoProxyDTO->getNumIdAssunto()]->getNumIdAssuntoDestino();

          $objAssuntoProxyDTO->setNumIdAssunto($numIdAssuntoMapeado);
          $objAssuntoProxyRN->alterar($objAssuntoProxyDTO);

          $arrIdAssuntoProxy[$numIdAssuntoMapeado] = 0;
        }
      }

      $objAssuntoDTO = new AssuntoDTO();
      $objAssuntoDTO->setBolExclusaoLogica(false);
      $objAssuntoDTO->retNumIdAssunto();
      $objAssuntoDTO->setStrSinEstrutural('N');

      if ($objTabelaAssuntosDTOAtual!=null) {
        $objAssuntoDTO->setNumIdAssunto(array_keys($arrIdAssuntoProxy), InfraDTO::$OPER_NOT_IN);
      }

      $objAssuntoDTO->setNumIdTabelaAssuntos($objTabelaAssuntosDTONova->getNumIdTabelaAssuntos());

      $objAssuntoRN = new AssuntoRN();
      $arrObjAssuntoDTO = $objAssuntoRN->listarRN0247($objAssuntoDTO);

      foreach($arrObjAssuntoDTO as $objAssuntoDTO){
        $objAssuntoProxyDTO = new AssuntoProxyDTO();
        $objAssuntoProxyDTO->setNumIdAssuntoProxy(null);
        $objAssuntoProxyDTO->setNumIdAssunto($objAssuntoDTO->getNumIdAssunto());
        $objAssuntoProxyRN->cadastrar($objAssuntoProxyDTO);
      }

      if ($objTabelaAssuntosDTOAtual!=null) {
        $objTabelaAssuntosDTOAtual->setStrSinAtual('N');
        $this->alterar($objTabelaAssuntosDTOAtual);
      }

      $objTabelaAssuntosDTONova->setStrSinAtual('S');
      $this->alterar($objTabelaAssuntosDTONova);

    }catch(Exception $e){
      throw new InfraException('Erro ativando Tabela de Assuntos.',$e);
    }
  }
}
?>