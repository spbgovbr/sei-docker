<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/10/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class HipoteseLegalRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(HipoteseLegalDTO $objHipoteseLegalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHipoteseLegalDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objHipoteseLegalDTO->setStrNome(trim($objHipoteseLegalDTO->getStrNome()));

      if (strlen($objHipoteseLegalDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new HipoteseLegalDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();
      $dto->setStrNome($objHipoteseLegalDTO->getStrNome());
      $dto->setNumIdHipoteseLegal($objHipoteseLegalDTO->getNumIdHipoteseLegal(),InfraDTO::$OPER_DIFERENTE);
      $dto = $this->consultar($dto);
      if ($dto!=null){
        if ($dto->getStrSinAtivo()=='S'){
          $objInfraException->adicionarValidacao('Existe outra Hipótese Legal cadastrada com o mesmo nome.');
        }else{
          $objInfraException->adicionarValidacao('Existe outra Hipótese Legal inativa cadastrada com o mesmo nome.');
        }
      }
    }
  }

  private function validarStrBaseLegal(HipoteseLegalDTO $objHipoteseLegalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHipoteseLegalDTO->getStrBaseLegal())){
      $objInfraException->adicionarValidacao('Base Legal não informada.');
    }else{
      $objHipoteseLegalDTO->setStrBaseLegal(trim($objHipoteseLegalDTO->getStrBaseLegal()));

      if (strlen($objHipoteseLegalDTO->getStrBaseLegal())>50){
        $objInfraException->adicionarValidacao('Base Legal possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrDescricao(HipoteseLegalDTO $objHipoteseLegalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHipoteseLegalDTO->getStrDescricao())){
      $objHipoteseLegalDTO->setStrDescricao(null);
    }else{
      $objHipoteseLegalDTO->setStrDescricao(trim($objHipoteseLegalDTO->getStrDescricao()));

      if (strlen($objHipoteseLegalDTO->getStrDescricao())>500){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 500 caracteres.');
      }
    }
  }
  
  public function validarStrStaNivelAcesso(HipoteseLegalDTO $objHipoteseLegalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHipoteseLegalDTO->getStrStaNivelAcesso())){
      $objInfraException->adicionarValidacao('Nível de acesso não informado.');
    }else{
      if ($objHipoteseLegalDTO->getStrStaNivelAcesso()!=ProtocoloRN::$NA_RESTRITO && $objHipoteseLegalDTO->getStrStaNivelAcesso()!=ProtocoloRN::$NA_SIGILOSO){
        $objInfraException->adicionarValidacao('Nível de acesso inválido.');
      }
    }
  }

  private function validarStrSinAtivo(HipoteseLegalDTO $objHipoteseLegalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objHipoteseLegalDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objHipoteseLegalDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(HipoteseLegalDTO $objHipoteseLegalDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_cadastrar',__METHOD__,$objHipoteseLegalDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objHipoteseLegalDTO, $objInfraException);
      $this->validarStrBaseLegal($objHipoteseLegalDTO, $objInfraException);
      $this->validarStrDescricao($objHipoteseLegalDTO, $objInfraException);
      $this->validarStrStaNivelAcesso($objHipoteseLegalDTO, $objInfraException);
      $this->validarStrSinAtivo($objHipoteseLegalDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      $ret = $objHipoteseLegalBD->cadastrar($objHipoteseLegalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Hipótese Legal.',$e);
    }
  }

  protected function alterarControlado(HipoteseLegalDTO $objHipoteseLegalDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_alterar',__METHOD__,$objHipoteseLegalDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objHipoteseLegalDTO->isSetStrNome()){
        $this->validarStrNome($objHipoteseLegalDTO, $objInfraException);
      }
      if ($objHipoteseLegalDTO->isSetStrBaseLegal()){
        $this->validarStrBaseLegal($objHipoteseLegalDTO, $objInfraException);
      }
      if ($objHipoteseLegalDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objHipoteseLegalDTO, $objInfraException);
      }
      if ($objHipoteseLegalDTO->isSetStrStaNivelAcesso()){
        $this->validarStrSinAtivo($objHipoteseLegalDTO, $objInfraException);
      }
      if ($objHipoteseLegalDTO->isSetStrStaNivelAcesso()){
        $this->validarStrSinAtivo($objHipoteseLegalDTO, $objInfraException);
      }
      
      if ($objHipoteseLegalDTO->isSetStrStaNivelAcesso() || $objHipoteseLegalDTO->isSetStrNome() || $objHipoteseLegalDTO->isSetStrBaseLegal()){
        
        $objHipoteseLegalDTOBanco = new HipoteseLegalDTO();
        $objHipoteseLegalDTOBanco->retStrNome();
        $objHipoteseLegalDTOBanco->retStrBaseLegal();
        $objHipoteseLegalDTOBanco->retStrStaNivelAcesso();
        $objHipoteseLegalDTOBanco->setNumIdHipoteseLegal($objHipoteseLegalDTO->getNumIdHipoteseLegal());        
        $objHipoteseLegalDTOBanco = $this->consultar($objHipoteseLegalDTOBanco);
        
        if ($objHipoteseLegalDTOBanco->getStrStaNivelAcesso()!=$objHipoteseLegalDTO->getStrStaNivelAcesso() ||
            $objHipoteseLegalDTOBanco->getStrNome()!=$objHipoteseLegalDTO->getStrNome() || 
            $objHipoteseLegalDTOBanco->getStrBaseLegal()!=$objHipoteseLegalDTO->getStrBaseLegal()){
          
          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->setNumIdHipoteseLegal($objHipoteseLegalDTO->getNumIdHipoteseLegal());
          
          $objProtocoloRN = new ProtocoloRN();
          $numProtocolos = $objProtocoloRN->contarRN0667($objProtocoloDTO);
          if ($numProtocolos==1){
            $objInfraException->adicionarValidacao('Apenas a descrição pode ser alterada porque existe um protocolo utilizando esta Hipótese Legal.');
          }else if ($numProtocolos > 0){
            $objInfraException->adicionarValidacao('Apenas a descrição pode ser alterada porque existem '.$numProtocolos.' protocolos utilizando esta Hipótese Legal.');
          }
        }
      }

      $objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      $objHipoteseLegalBD->alterar($objHipoteseLegalDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Hipótese Legal.',$e);
    }
  }

  protected function excluirControlado($arrObjHipoteseLegalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_excluir',__METHOD__,$arrObjHipoteseLegalDTO);

      if (count($arrObjHipoteseLegalDTO)){

        //Regras de Negocio
        $objInfraException = new InfraException();
        
        $objHipoteseLegalDTO = new HipoteseLegalDTO();
        $objHipoteseLegalDTO->setBolExclusaoLogica(false);
        $objHipoteseLegalDTO->retNumIdHipoteseLegal();
        $objHipoteseLegalDTO->retStrNome();
        $objHipoteseLegalDTO->setNumIdHipoteseLegal(InfraArray::converterArrInfraDTO($arrObjHipoteseLegalDTO,'IdHipoteseLegal'),InfraDTO::$OPER_IN);
        $arrObjHipoteseLegalDTOBanco = InfraArray::indexarArrInfraDTO($this->listar($objHipoteseLegalDTO),'IdHipoteseLegal');
        
        $objProtocoloRN = new ProtocoloRN();
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        
        for($i=0;$i<count($arrObjHipoteseLegalDTO);$i++){
          $objProtocoloDTO = new ProtocoloDTO();
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO->setNumIdHipoteseLegal($arrObjHipoteseLegalDTO[$i]->getNumIdHipoteseLegal());
          $numProtocolos = $objProtocoloRN->contarRN0667($objProtocoloDTO);
  
          if ($numProtocolos){
            if ($numProtocolos==1){
              $objInfraException->adicionarValidacao('Existe um protocolo associado com a hipótese legal "'.$arrObjHipoteseLegalDTOBanco[$arrObjHipoteseLegalDTO[$i]->getNumIdHipoteseLegal()]->getStrNome().'".');
            }else{
              $objInfraException->adicionarValidacao('Existem '.$numProtocolos.' protocolos associados com a hipótese legal "'.$arrObjHipoteseLegalDTOBanco[$arrObjHipoteseLegalDTO[$i]->getNumIdHipoteseLegal()]->getStrNome().'".');
            }
          }
          
          $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
          $objTipoProcedimentoDTO->retStrNome();
          $objTipoProcedimentoDTO->setNumIdHipoteseLegalSugestao($arrObjHipoteseLegalDTO[$i]->getNumIdHipoteseLegal());
          $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO);
          
          $numTipoProcedimento = count($arrObjTipoProcedimentoDTO);
          if ($numTipoProcedimento){
            if ($numTipoProcedimento==1){
              $objInfraException->adicionarValidacao('O tipo de processo "'.$arrObjTipoProcedimentoDTO[0]->getStrNome().'" está sugerindo a hipótese legal "'.$arrObjHipoteseLegalDTOBanco[$arrObjHipoteseLegalDTO[$i]->getNumIdHipoteseLegal()]->getStrNome().'".');
            }else{
              $objInfraException->adicionarValidacao('Os '.$numTipoProcedimento.' tipos de processo abaixo estão sugerindo a hipótese legal "'.$arrObjHipoteseLegalDTOBanco[$arrObjHipoteseLegalDTO[$i]->getNumIdHipoteseLegal()]->getStrNome().'":\n'.implode('\n',InfraArray::converterArrInfraDTO($arrObjTipoProcedimentoDTO,'Nome')));
            }
          }
          
        }
        
        $objInfraException->lancarValidacoes();
  
        $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjHipoteseLegalDTO);$i++){
          $objHipoteseLegalBD->excluir($arrObjHipoteseLegalDTO[$i]);
        }
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Hipótese Legal.',$e);
    }
  }

  protected function consultarConectado(HipoteseLegalDTO $objHipoteseLegalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_consultar',__METHOD__,$objHipoteseLegalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      $ret = $objHipoteseLegalBD->consultar($objHipoteseLegalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Hipótese Legal.',$e);
    }
  }

  protected function listarConectado(HipoteseLegalDTO $objHipoteseLegalDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_listar',__METHOD__,$objHipoteseLegalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      $ret = $objHipoteseLegalBD->listar($objHipoteseLegalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Hipóteses Legais.',$e);
    }
  }

  protected function contarConectado(HipoteseLegalDTO $objHipoteseLegalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_listar',__METHOD__,$objHipoteseLegalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      $ret = $objHipoteseLegalBD->contar($objHipoteseLegalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Hipóteses Legais.',$e);
    }
  }

  protected function desativarControlado($arrObjHipoteseLegalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_desativar',__METHOD__,$arrObjHipoteseLegalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjHipoteseLegalDTO);$i++){
        $objHipoteseLegalBD->desativar($arrObjHipoteseLegalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Hipótese Legal.',$e);
    }
  }

  protected function reativarControlado($arrObjHipoteseLegalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_reativar',__METHOD__,$arrObjHipoteseLegalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjHipoteseLegalDTO);$i++){
        $objHipoteseLegalBD->reativar($arrObjHipoteseLegalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Hipótese Legal.',$e);
    }
  }

  protected function bloquearControlado(HipoteseLegalDTO $objHipoteseLegalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('hipotese_legal_consultar',__METHOD__,$objHipoteseLegalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objHipoteseLegalBD = new HipoteseLegalBD($this->getObjInfraIBanco());
      $ret = $objHipoteseLegalBD->bloquear($objHipoteseLegalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Hipótese Legal.',$e);
    }
  }


}
?>