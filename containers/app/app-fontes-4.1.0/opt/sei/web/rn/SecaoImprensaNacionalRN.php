<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2013 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SecaoImprensaNacionalRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdVeiculoImprensaNacional(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional())){
      $objInfraException->adicionarValidacao('Veículo da Imprensa Nacional não informado.');
    }
  }

  private function validarStrNome(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoImprensaNacionalDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objSecaoImprensaNacionalDTO->setStrNome(trim($objSecaoImprensaNacionalDTO->getStrNome()));

      if (strlen($objSecaoImprensaNacionalDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $objSecaoImprensaNacionalDTOBanco = new SecaoImprensaNacionalDTO();
      $objSecaoImprensaNacionalDTOBanco->setStrNome($objSecaoImprensaNacionalDTO->getStrNome());
      $objSecaoImprensaNacionalDTOBanco->setNumIdVeiculoImprensaNacional($objSecaoImprensaNacionalDTO->getNumIdVeiculoImprensaNacional());
      $objSecaoImprensaNacionalDTOBanco->setNumIdSecaoImprensaNacional($objSecaoImprensaNacionalDTO->getNumIdSecaoImprensaNacional(),InfraDTO::$OPER_DIFERENTE);
      
      if ($this->contar($objSecaoImprensaNacionalDTOBanco)){
        $objInfraException->adicionarValidacao('Existe outra Seção cadastrada para este veículo com o mesmo nome.');
      }
    }
  }

  private function validarStrDescricao(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSecaoImprensaNacionalDTO->getStrDescricao())){
      $objSecaoImprensaNacionalDTO->setStrDescricao(null);
    }else{
      $objSecaoImprensaNacionalDTO->setStrDescricao(trim($objSecaoImprensaNacionalDTO->getStrDescricao()));

      if (strlen($objSecaoImprensaNacionalDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_cadastrar',__METHOD__,$objSecaoImprensaNacionalDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdVeiculoImprensaNacional($objSecaoImprensaNacionalDTO, $objInfraException);
      $this->validarStrNome($objSecaoImprensaNacionalDTO, $objInfraException);
      $this->validarStrDescricao($objSecaoImprensaNacionalDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      $ret = $objSecaoImprensaNacionalBD->cadastrar($objSecaoImprensaNacionalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function alterarControlado(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_alterar',__METHOD__,$objSecaoImprensaNacionalDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objSecaoImprensaNacionalDTO->isSetNumIdVeiculoImprensaNacional()){
        $this->validarNumIdVeiculoImprensaNacional($objSecaoImprensaNacionalDTO, $objInfraException);
      }
      if ($objSecaoImprensaNacionalDTO->isSetStrNome()){
        $this->validarStrNome($objSecaoImprensaNacionalDTO, $objInfraException);
      }
      if ($objSecaoImprensaNacionalDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objSecaoImprensaNacionalDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      $objSecaoImprensaNacionalBD->alterar($objSecaoImprensaNacionalDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function excluirControlado($arrObjSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_excluir',__METHOD__,$arrObjSecaoImprensaNacionalDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objPublicacaoRN = new PublicacaoRN();
      $objPublicacaoLegadoRN = new PublicacaoLegadoRN();
      
      for($i=0;$i<count($arrObjSecaoImprensaNacionalDTO);$i++){
        
        $objPublicacaoDTO = new PublicacaoDTO();
        $objPublicacaoDTO->setNumIdSecaoIO($arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional());
        if ($objPublicacaoRN->contarRN1046($objPublicacaoDTO)){
          
          $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
          $objSecaoImprensaNacionalDTO->retStrNome();
          $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional());
          $objSecaoImprensaNacionalDTO = $this->consultar($objSecaoImprensaNacionalDTO);
          
          $objInfraException->adicionarValidacao('Existem publicações associadas com a seção "'.$objSecaoImprensaNacionalDTO->getStrNome().'".');
        }
        
        $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();
        $objPublicacaoLegadoDTO->setNumIdSecaoIO($arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional());
        
        if ($objPublicacaoLegadoRN->contar($objPublicacaoLegadoDTO)){
          $objInfraException->adicionarValidacao('Existem publicações legadas associadas.');
        }
        
      }
      $objInfraException->lancarValidacoes();
      
      
      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSecaoImprensaNacionalDTO);$i++){
        $objSecaoImprensaNacionalBD->excluir($arrObjSecaoImprensaNacionalDTO[$i]);
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function consultarConectado(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_consultar',__METHOD__,$objSecaoImprensaNacionalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      $ret = $objSecaoImprensaNacionalBD->consultar($objSecaoImprensaNacionalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function listarConectado(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_listar',__METHOD__,$objSecaoImprensaNacionalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      $ret = $objSecaoImprensaNacionalBD->listar($objSecaoImprensaNacionalDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Seções do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function contarConectado(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_listar',__METHOD__,$objSecaoImprensaNacionalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      $ret = $objSecaoImprensaNacionalBD->contar($objSecaoImprensaNacionalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Seções do Veículo da Imprensa Nacional.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_desativar',__METHOD__,$arrObjSecaoImprensaNacionalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSecaoImprensaNacionalDTO);$i++){
        $objSecaoImprensaNacionalBD->desativar($arrObjSecaoImprensaNacionalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function reativarControlado($arrObjSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_reativar',__METHOD__,$arrObjSecaoImprensaNacionalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSecaoImprensaNacionalDTO);$i++){
        $objSecaoImprensaNacionalBD->reativar($arrObjSecaoImprensaNacionalDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

  protected function bloquearControlado(SecaoImprensaNacionalDTO $objSecaoImprensaNacionalDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('secao_imprensa_nacional_consultar',__METHOD__,$objSecaoImprensaNacionalDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSecaoImprensaNacionalBD = new SecaoImprensaNacionalBD($this->getObjInfraIBanco());
      $ret = $objSecaoImprensaNacionalBD->bloquear($objSecaoImprensaNacionalDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Seção do Veículo da Imprensa Nacional.',$e);
    }
  }

 */
}
?>