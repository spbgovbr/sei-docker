<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class TipoPermissaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(TipoPermissaoDTO $objTipoPermissaoDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('tipo_permissao_cadastrar',__METHOD__,$objTipoPermissaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrDescricao($objTipoPermissaoDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoPermissaoBD = new TipoPermissaoBD($this->getObjInfraIBanco());
      $ret = $objTipoPermissaoBD->cadastrar($objTipoPermissaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Permissão.',$e);
    }
  }

  protected function alterarControlado(TipoPermissaoDTO $objTipoPermissaoDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('tipo_permissao_alterar',__METHOD__,$objTipoPermissaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrDescricao($objTipoPermissaoDTO,$objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoPermissaoBD = new TipoPermissaoBD($this->getObjInfraIBanco());
      $objTipoPermissaoBD->alterar($objTipoPermissaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Permissão.',$e);
    }
  }

  protected function excluirControlado($arrObjTipoPermissaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('tipo_permissao_excluir',__METHOD__,$arrObjTipoPermissaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

			for($i=0;$i<count($arrObjTipoPermissaoDTO);$i++){
				//Verifica se existem permissoes associadas ao tipo
				$objPermissaoDTO = new PermissaoDTO();
				$objPermissaoDTO->retNumIdTipoPermissao();
				$objPermissaoDTO->setNumIdTipoPermissao($arrObjTipoPermissaoDTO[$i]->getNumIdTipoPermissao());
				$objPermissaoRN = new PermissaoRN();
				if (count($objPermissaoRN->listar($objPermissaoDTO))>0){
					$objInfraException->adicionarValidacao('Existem permissões associadas.');
				}
        $objInfraException->lancarValidacoes();
			}

      $objTipoPermissaoBD = new TipoPermissaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoPermissaoDTO);$i++){
        $objTipoPermissaoBD->excluir($arrObjTipoPermissaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Permissão.',$e);
    }
  }

  protected function consultarConectado(TipoPermissaoDTO $objTipoPermissaoDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('tipo_permissao_consultar',__METHOD__,$objTipoPermissaoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoPermissaoBD = new TipoPermissaoBD($this->getObjInfraIBanco());
      $ret = $objTipoPermissaoBD->consultar($objTipoPermissaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Permissão.',$e);
    }
  }

  protected function listarConectado(TipoPermissaoDTO $objTipoPermissaoDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('tipo_permissao_listar',__METHOD__,$objTipoPermissaoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoPermissaoBD = new TipoPermissaoBD($this->getObjInfraIBanco());
      $ret = $objTipoPermissaoBD->listar($objTipoPermissaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Permissão.',$e);
    }
  }

  private function validarStrDescricao(TipoPermissaoDTO $objTipoPermissaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoPermissaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }
  }

}
?>