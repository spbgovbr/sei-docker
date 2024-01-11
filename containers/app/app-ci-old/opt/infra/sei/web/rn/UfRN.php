<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
* 12/06/2018 - cjy - insercao de estado e cidade textualmente, para paises estrangeiros
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UfRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0398Controlado(UfDTO $objUfDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_cadastrar',__METHOD__,$objUfDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarNumIdPais($objUfDTO, $objInfraException);
      $this->validarNumCodigoIbge($objUfDTO, $objInfraException);
      $this->validarStrSiglaRN0405($objUfDTO, $objInfraException);
      $this->validarStrNomeRN0406($objUfDTO, $objInfraException);
      $objInfraException->lancarValidacoes();
			
      $objUfBD = new UfBD($this->getObjInfraIBanco());
      $ret = $objUfBD->cadastrar($objUfDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Estado.',$e);
    }
  }

  protected function alterarRN0399Controlado(UfDTO $objUfDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('uf_alterar',__METHOD__,$objUfDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objUfDTOBanco = new UfDTO();
      $objUfDTOBanco->retNumIdUf();
      $objUfDTOBanco->retNumIdPais();
      $objUfDTOBanco->setNumIdUf($objUfDTO->getNumIdUf());
      $objUfDTOBanco = $this->consultarRN0400($objUfDTOBanco);

      if ($objUfDTOBanco==null){
        throw new InfraException('Estado não encontrado ['.$objUfDTO->getNumIdUf().'].');
      }

      if (!$objUfDTO->isSetNumIdPais()){
        $objUfDTO->setNumIdPais($objUfDTOBanco->getNumIdPais());
      }

      if ($objUfDTO->isSetStrSigla()){
        $this->validarStrSiglaRN0405($objUfDTO, $objInfraException);
      }
      if ($objUfDTO->isSetNumCodigoIbge()){
        $this->validarNumCodigoIbge($objUfDTO, $objInfraException);
      }
      if ($objUfDTO->isSetStrNome()){
        $this->validarStrNomeRN0406($objUfDTO, $objInfraException);
      }
      if ($objUfDTO->isSetNumIdPais()) {  
        $this->validarNumIdPais($objUfDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      $objUfBD->alterar($objUfDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Estado.',$e);
    }
  }

  protected function excluirRN0402Controlado($arrObjUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_excluir',__METHOD__,$arrObjUfDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $contatoRN = new ContatoRN();
      $cidadeRN = new CidadeRN();

      $arrIdUf= InfraArray::converterArrInfraDTO($arrObjUfDTO, 'IdUf');
      if (count($arrIdUf)) {

        $ufDto = new UfDTO();
        $ufDto->setBolExclusaoLogica(false);
        $ufDto->setNumIdUf($arrIdUf, InfraDTO::$OPER_IN);
        $ufDto->retStrNome();
        $ufDto->retNumIdUf();
        $arrObjContatoDTOConsulta = InfraArray::indexarArrInfraDTO($this->listarRN0401Conectado($ufDto), 'IdUf');

        foreach ($arrIdUf as $numIdUf) {
          $strNome = $arrObjContatoDTOConsulta[$numIdUf]->getStrNome();

          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setNumIdUf($numIdUf);

          $numContatos = $contatoRN->contarRN0327($objContatoDTO);
          if ($numContatos) {
            if ($numContatos == 1) {
              $objInfraException->adicionarValidacao('Existe 1 Contato utilizando o Estado ' . $strNome . '.');
            } else {
              $objInfraException->adicionarValidacao('Existem ' . $numContatos . ' Contatos utilizando o Estado ' . $strNome . '.');
            }
          }

          $objCidadeDTO = new CidadeDTO();
          $objCidadeDTO->setNumIdUf($numIdUf);

          $numCidades = $cidadeRN->contarRN0414($objCidadeDTO);
          if ($numCidades) {
            if ($numCidades == 1) {
              $objInfraException->adicionarValidacao('Existe 1 Cidade utilizando o Estado ' . $strNome . '.');
            } else {
              $objInfraException->adicionarValidacao('Existem ' . $numContatos . ' Cidades utilizando o Estado ' . $strNome . '.');
            }
          }
        }
      }


      $objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUfDTO);$i++){
        $objUfBD->excluir($arrObjUfDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Estado.',$e);
    }
  }

  protected function consultarRN0400Conectado(UfDTO $objUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_consultar',__METHOD__,$objUfDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      $ret = $objUfBD->consultar($objUfDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Estado.',$e);
    }
  }

  protected function listarRN0401Conectado(UfDTO $objUfDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_listar',__METHOD__,$objUfDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      $ret = $objUfBD->listar($objUfDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Estados.',$e);
    }
  }

  protected function contarRN0402Conectado(UfDTO $objUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_listar',__METHOD__,$objUfDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      $ret = $objUfBD->contar($objUfDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Estados.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUfDTO);$i++){
        $objUfBD->desativar($arrObjUfDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Estado.',$e);
    }
  }

  protected function reativarControlado($arrObjUfDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('uf_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objUfBD = new UfBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjUfDTO);$i++){
        $objUfBD->reativar($arrObjUfDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Estado.',$e);
    }
  }

 */
  
  private function validarNumCodigoIbge(UfDTO $objUfDTO, InfraException $objInfraException){
    if ($objUfDTO->getNumIdPais()==ID_BRASIL) {
	  	if (InfraString::isBolVazia($objUfDTO->getNumCodigoIbge())){
	      $objInfraException->adicionarValidacao('Código do IBGE não informado.');
	    }else{
	      if (!is_numeric($objUfDTO->getNumCodigoIbge()) || strlen($objUfDTO->getNumCodigoIbge())!=2){
	        $objInfraException->adicionarValidacao('Código do IBGE inválido.');
	      }
	      
	      $dto = new UfDTO();
	      $dto->retNumCodigoIbge();
	      $dto->setNumCodigoIbge($objUfDTO->getNumCodigoIbge());
	      $dto->setNumIdUf($objUfDTO->getNumIdUf(),InfraDTO::$OPER_DIFERENTE);
	      $dto = $this->consultarRN0400($dto);
	      if ($dto!=null){
	      	$objInfraException->adicionarValidacao('Existe outra ocorrência de UF que utiliza o mesmo código do IBGE.');
	      }	      
	    }
    } else {
    	$objUfDTO->setNumCodigoIbge(null);    	
    }
  }

 private function validarNumIdPais(UfDTO $objUfDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objUfDTO->getNumIdPais())){
	      $objInfraException->adicionarValidacao('País não selecionado.');
	  }
  }  
  
  private function validarStrNomeRN0406(UfDTO $objUfDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objUfDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objUfDTO->setStrNome(trim($objUfDTO->getStrNome()));
  
      if (strlen($objUfDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
        return;
      }

      $dto = new UfDTO();
      $dto->retNumIdUf();
      if(!InfraString::isBolVazia($objUfDTO->getNumIdUf())) {
        $dto->setNumIdUf($objUfDTO->getNumIdUf(), InfraDTO::$OPER_DIFERENTE);
      }
      $dto->setStrNome($objUfDTO->getStrNome());
      $dto->setNumIdPais($objUfDTO->getNumIdPais());
      $dto = $this->consultarRN0400($dto);
      if ($dto!=null){
      	$objInfraException->adicionarValidacao('Existe outra ocorrência de UF que utiliza o mesmo Nome.');
      	return;
      }
    }
  }
  
  private function validarStrSiglaRN0405(UfDTO $objUfDTO, InfraException $objInfraException){
    if(!InfraString::isBolVazia($objUfDTO->getNumIdPais())
      && $objUfDTO->getNumIdPais() == ID_BRASIL){
      if (InfraString::isBolVazia($objUfDTO->getStrSigla())){
        $objInfraException->adicionarValidacao('Sigla não informada.');
      }else{
        $objUfDTO->setStrSigla(trim($objUfDTO->getStrSigla()));

        if (strlen($objUfDTO->getStrSigla())>2){
          $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 2 caracteres.');
          return;
        }

        $dto = new UfDTO();
        $dto->retNumIdUf();
        $dto->setNumIdUf($objUfDTO->getNumIdUf(),InfraDTO::$OPER_DIFERENTE);
        $dto->setStrSigla($objUfDTO->getStrSigla());
        $dto->setNumIdPais($objUfDTO->getNumIdPais());
        $dto = $this->consultarRN0400($dto);
        if ($dto!=null){
          $objInfraException->adicionarValidacao('Existe outra ocorrência de UF que utiliza a mesma Sigla.');
          return;
        }
      }
    }
  }
}
?>