<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AnotacaoRN extends InfraRN {

  public static $TA_UNIDADE = 'U';
  public static $TA_INDIVIDUAL = 'I';
	
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarDblIdProtocolo(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdUsuario(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarStrDescricao(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getStrDescricao())){
      $objAnotacaoDTO->setStrDescricao(null);
    }else{
      $objAnotacaoDTO->setStrDescricao(trim($objAnotacaoDTO->getStrDescricao()));
      $objAnotacaoDTO->setStrDescricao(InfraUtil::filtrarISO88591($objAnotacaoDTO->getStrDescricao()));
      $objAnotacaoDTO->setStrDescricao(str_replace(array('<b>','</b>','<i>','</i>'),'',$objAnotacaoDTO->getStrDescricao()));
  
      if (strlen($objAnotacaoDTO->getStrDescricao())>500){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarDthAnotacao(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getDthAnotacao())){
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    }else{
      if (!InfraData::validarDataHora($objAnotacaoDTO->getDthAnotacao())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }
  
  private function validarStrSinPrioridade(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getStrSinPrioridade())){
    		$objInfraException->adicionarValidacao('Sinalizador de prioridade não informado.');
  	}else{
      if (!InfraUtil::isBolSinalizadorValido($objAnotacaoDTO->getStrSinPrioridade())){
        $objInfraException->adicionarValidacao('Sinalizador de prioridade inválido.');
      }
  	}
  }
  
  private function validarStrStaAnotacao(AnotacaoDTO $objAnotacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnotacaoDTO->getStrStaAnotacao())){
      $objInfraException->adicionarValidacao('Tipo da anotação não informado.');
    }else{
      if ($objAnotacaoDTO->getStrStaAnotacao()!=self::$TA_INDIVIDUAL && $objAnotacaoDTO->getStrStaAnotacao()!=self::$TA_UNIDADE) {
        $objInfraException->adicionarValidacao('Tipo da anotação inválido.');
      }
    }
  }

  protected function registrarControlado(AnotacaoDTO $parObjAnotacaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_registrar',__METHOD__,$parObjAnotacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      if (InfraString::isBolVazia($parObjAnotacaoDTO->getStrDescricao()) && $parObjAnotacaoDTO->getStrSinPrioridade()=='S'){
      	$objInfraException->lancarValidacao('Descrição não informada.');
      }
      
      $objInfraException->lancarValidacoes();
      
      $arrIdProtocolo = $parObjAnotacaoDTO->getDblIdProtocolo();
      
      $objProtocoloRN = new ProtocoloRN();
      
      foreach($arrIdProtocolo as $dblIdProtocolo){
      
      	$objProtocoloDTO = new ProtocoloDTO();
      	$objProtocoloDTO->retStrStaNivelAcessoGlobal();
      	$objProtocoloDTO->setDblIdProtocolo($dblIdProtocolo);
      	
      	$objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
      	
      	if ($objProtocoloDTO==null){
      	  throw new InfraException('Processo não encontrado.');
      	}
      	
      	
        $objAnotacaoDTOBanco = new AnotacaoDTO();
        $objAnotacaoDTOBanco->retNumIdAnotacao();
        $objAnotacaoDTOBanco->retDthAnotacao();
        $objAnotacaoDTOBanco->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        
        if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
        	$objAnotacaoDTOBanco->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        	$objAnotacaoDTOBanco->setStrStaAnotacao(AnotacaoRN::$TA_INDIVIDUAL);
        }else{
        	$objAnotacaoDTOBanco->setStrStaAnotacao(AnotacaoRN::$TA_UNIDADE);
        }
        
        $objAnotacaoDTOBanco->setDblIdProtocolo($dblIdProtocolo);
        $objAnotacaoDTOBanco->setOrdDthAnotacao(InfraDTO::$TIPO_ORDENACAO_DESC);

        $arrObjAnotacaoDTOBanco = $this->listar($objAnotacaoDTOBanco);

        if (InfraString::isBolVazia($parObjAnotacaoDTO->getStrDescricao())){
            $this->excluir($arrObjAnotacaoDTOBanco);
        }else{
          $objAnotacaoDTO = new AnotacaoDTO();
          $objAnotacaoDTO->setDblIdProtocolo($dblIdProtocolo);
          $objAnotacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objAnotacaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          $objAnotacaoDTO->setDthAnotacao(InfraData::getStrDataHoraAtual());
          $objAnotacaoDTO->setStrDescricao($parObjAnotacaoDTO->getStrDescricao());
          $objAnotacaoDTO->setStrSinPrioridade($parObjAnotacaoDTO->getStrSinPrioridade());

	        if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
	        	$objAnotacaoDTO->setStrStaAnotacao(AnotacaoRN::$TA_INDIVIDUAL);
	        }else{
	        	$objAnotacaoDTO->setStrStaAnotacao(AnotacaoRN::$TA_UNIDADE);
	        }
          
          if (count($arrObjAnotacaoDTOBanco) == 0){
            $this->cadastrar($objAnotacaoDTO);
          }else{

            $objAnotacaoDTO->setNumIdAnotacao($arrObjAnotacaoDTOBanco[0]->getNumIdAnotacao());
            $this->alterar($objAnotacaoDTO);

            if (count($arrObjAnotacaoDTOBanco) > 1){
              $this->excluir(array_slice($arrObjAnotacaoDTOBanco,1));
            }
          }
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro registrando Anotação.',$e);
    }
  }
  
  protected function cadastrarControlado(AnotacaoDTO $objAnotacaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_cadastrar',__METHOD__,$objAnotacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objAnotacaoDTO, $objInfraException);
      $this->validarDblIdProtocolo($objAnotacaoDTO, $objInfraException);
      $this->validarNumIdUsuario($objAnotacaoDTO, $objInfraException);
      $this->validarStrDescricao($objAnotacaoDTO, $objInfraException);
      $this->validarDthAnotacao($objAnotacaoDTO, $objInfraException);
      $this->validarStrSinPrioridade($objAnotacaoDTO, $objInfraException);
      $this->validarStrStaAnotacao($objAnotacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      $ret = $objAnotacaoBD->cadastrar($objAnotacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Anotação.',$e);
    }
  }

  protected function alterarControlado(AnotacaoDTO $objAnotacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_alterar',__METHOD__,$objAnotacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAnotacaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objAnotacaoDTO, $objInfraException);
      }
      if ($objAnotacaoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objAnotacaoDTO, $objInfraException);
      }
      if ($objAnotacaoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objAnotacaoDTO, $objInfraException);
      }
      if ($objAnotacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objAnotacaoDTO, $objInfraException);
      }
      if ($objAnotacaoDTO->isSetDthAnotacao()){
        $this->validarDthAnotacao($objAnotacaoDTO, $objInfraException);
      }
      if ($objAnotacaoDTO->isSetStrSinPrioridade()){
        $this->validarStrSinPrioridade($objAnotacaoDTO, $objInfraException);
      }
      if ($objAnotacaoDTO->isSetStrStaAnotacao()){
        $this->validarStrStaAnotacao($objAnotacaoDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      $objAnotacaoBD->alterar($objAnotacaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Anotação.',$e);
    }
  }

  protected function excluirControlado($arrObjAnotacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_excluir',__METHOD__,$arrObjAnotacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAnotacaoDTO);$i++){
        $objAnotacaoBD->excluir($arrObjAnotacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Anotação.',$e);
    }
  }

  protected function consultarConectado(AnotacaoDTO $objAnotacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_consultar',__METHOD__,$objAnotacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      $ret = $objAnotacaoBD->consultar($objAnotacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Anotação.',$e);
    }
  }

  protected function listarConectado(AnotacaoDTO $objAnotacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_listar',__METHOD__,$objAnotacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      $ret = $objAnotacaoBD->listar($objAnotacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Anotações.',$e);
    }
  }

  protected function contarConectado(AnotacaoDTO $objAnotacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_listar',__METHOD__,$objAnotacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      $ret = $objAnotacaoBD->contar($objAnotacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Anotações.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAnotacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAnotacaoDTO);$i++){
        $objAnotacaoBD->desativar($arrObjAnotacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Anotação.',$e);
    }
  }

  protected function reativarControlado($arrObjAnotacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAnotacaoDTO);$i++){
        $objAnotacaoBD->reativar($arrObjAnotacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Anotação.',$e);
    }
  }

  protected function bloquearControlado(AnotacaoDTO $objAnotacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anotacao_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnotacaoBD = new AnotacaoBD($this->getObjInfraIBanco());
      $ret = $objAnotacaoBD->bloquear($objAnotacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Anotação.',$e);
    }
  }

 */

  protected function complementarConectado($varArr){
    try {

      if (InfraArray::contar($varArr)) {

        if ($varArr[0] instanceof ProtocoloDTO ){
          $strAtributoId = 'IdProtocolo';
          $strAtributoNivelAcesso = 'StaNivelAcessoGlobal';
        }else if ($varArr[0] instanceof ProcedimentoDTO){
          $strAtributoId = 'IdProcedimento';
          $strAtributoNivelAcesso = 'StaNivelAcessoGlobalProtocolo';
        }else{
          throw new InfraException('Tipo do parâmetro inválido ('.get_class($varArr[0]).').');
        }

        $objAnotacaoDTO = new AnotacaoDTO();
        $objAnotacaoDTO->retDthAnotacao();
        $objAnotacaoDTO->retDblIdProtocolo();
        $objAnotacaoDTO->retStrDescricao();
        $objAnotacaoDTO->retStrSiglaUsuario();
        $objAnotacaoDTO->retStrNomeUsuario();
        $objAnotacaoDTO->retStrSinPrioridade();
        $objAnotacaoDTO->retNumIdUsuario();
        $objAnotacaoDTO->retStrStaAnotacao();
        $objAnotacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAnotacaoDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($varArr, $strAtributoId), InfraDTO::$OPER_IN);
        $objAnotacaoDTO->setOrdDthAnotacao(InfraDTO::$TIPO_ORDENACAO_DESC);

        $arrObjAnotacaoDTO = InfraArray::indexarArrInfraDTO($this->listar($objAnotacaoDTO), 'IdProtocolo', true);

        foreach ($varArr as $dto) {

          $dto->setObjAnotacaoDTO(null);

          $dblIdProtocolo = $dto->get($strAtributoId);
          $strNivelAcessoGlobal = $dto->get($strAtributoNivelAcesso);

          if (isset($arrObjAnotacaoDTO[$dblIdProtocolo])) {
            foreach ($arrObjAnotacaoDTO[$dblIdProtocolo] as $objAnotacaoDTO) {
              if ($strNivelAcessoGlobal == ProtocoloRN::$NA_SIGILOSO) {
                if ($objAnotacaoDTO->getStrStaAnotacao() == AnotacaoRN::$TA_INDIVIDUAL && $objAnotacaoDTO->getNumIdUsuario() == SessaoSEI::getInstance()->getNumIdUsuario()) {
                  $dto->setObjAnotacaoDTO($objAnotacaoDTO);
                  break;
                }
              } else {
                if ($objAnotacaoDTO->getStrStaAnotacao() == AnotacaoRN::$TA_UNIDADE) {
                  $dto->setObjAnotacaoDTO($objAnotacaoDTO);
                  break;
                }
              }
            }
          }
        }
      }

    } catch (Exception $e) {
      throw new InfraException('Erro complementando Anotações.', $e);
    }
  }
}
?>