<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
* 12/06/2018 - cjy - insercao de estado e cidade textualmente, para paises estrangeiros *
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class CidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0407Controlado(CidadeDTO $objCidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_cadastrar',__METHOD__,$objCidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPais($objCidadeDTO, $objInfraException);
      $this->validarNumCodigoIbge($objCidadeDTO, $objInfraException);
      $this->validarNumIdUfRN0412($objCidadeDTO, $objInfraException);
      $this->validarStrNomeRN0413($objCidadeDTO, $objInfraException);
      $this->validarStrSinCapital($objCidadeDTO, $objInfraException);
      $this->validarDblLatitude($objCidadeDTO, $objInfraException);
      $this->validarDblLongitude($objCidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      $ret = $objCidadeBD->cadastrar($objCidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Cidade.',$e);
    }
  }

  protected function alterarRN0408Controlado(CidadeDTO $objCidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('cidade_alterar',__METHOD__,$objCidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCidadeDTO->isSetNumCodigoIbge()){
        $this->validarNumCodigoIbge($objCidadeDTO, $objInfraException);
      }
      if ($objCidadeDTO->isSetNumIdUf()){
        $this->validarNumIdUfRN0412($objCidadeDTO, $objInfraException);
      }
      if ($objCidadeDTO->isSetNumIdPais()){
        $this->validarNumIdPais($objCidadeDTO, $objInfraException);
      }
      if ($objCidadeDTO->isSetStrNome()){
        $this->validarStrNomeRN0413($objCidadeDTO, $objInfraException);
      }
      if ($objCidadeDTO->isSetStrSinCapital()){
        $this->validarStrSinCapital($objCidadeDTO, $objInfraException);
      }
      if ($objCidadeDTO->isSetDblLatitude()){
        $this->validarDblLatitude($objCidadeDTO, $objInfraException);
      }
      if ($objCidadeDTO->isSetDblLongitude()){
        $this->validarDblLongitude($objCidadeDTO, $objInfraException);
      }
      $objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      $objCidadeBD->alterar($objCidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Cidade.',$e);
    }
  }

  protected function excluirControlado($arrObjCidadeDTO){
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_excluir',__METHOD__,$arrObjCidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $contatoRN = new ContatoRN();

      $arrIdCidade = InfraArray::converterArrInfraDTO($arrObjCidadeDTO, 'IdCidade');
      if (count($arrIdCidade)) {

        $cidadeDto = new CidadeDTO();
        $cidadeDto->setBolExclusaoLogica(false);
        $cidadeDto->setNumIdCidade($arrIdCidade, InfraDTO::$OPER_IN);
        $cidadeDto->retStrNome();
        $cidadeDto->retNumIdCidade();
        $arrObjContatoDTOConsulta = InfraArray::indexarArrInfraDTO($this->listarRN0410Conectado($cidadeDto), 'IdCidade');

        foreach ($arrIdCidade as $numIdCidade) {
          $strNome = $arrObjContatoDTOConsulta[$numIdCidade]->getStrNome();

          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setNumIdCidade($numIdCidade);

          $numContatos = $contatoRN->contarRN0327($objContatoDTO);
          if ($numContatos) {
            if ($numContatos == 1) {
              $objInfraException->adicionarValidacao('Existe 1 Contato utilizando a Cidade ' . $strNome . '.');
            } else {
              $objInfraException->adicionarValidacao('Existem ' . $numContatos . ' Contatos utilizando a Cidade ' . $strNome . '.');
            }
          }
        }
      }


      $objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjCidadeDTO); $i++) {
        $objCidadeBD->excluir($arrObjCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Cidade.',$e);
    }
  }

  protected function consultarRN0409Conectado(CidadeDTO $objCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_consultar',__METHOD__,$objCidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      $ret = $objCidadeBD->consultar($objCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Cidade.',$e);
    }
  }

  protected function listarRN0410Conectado(CidadeDTO $objCidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_listar',__METHOD__,$objCidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      $ret = $objCidadeBD->listar($objCidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Cidades.',$e);
    }
  }

  protected function contarRN0414Conectado(CidadeDTO $objCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_listar',__METHOD__,$objCidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      $ret = $objCidadeBD->contar($objCidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Cidades.',$e);
    }
  }

/*
  protected function desativarControlado($arrObjCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCidadeDTO);$i++){
        $objCidadeBD->desativar($arrObjCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Cidade.',$e);
    }
  }

  protected function reativarControlado($arrObjCidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('cidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCidadeBD = new CidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCidadeDTO);$i++){
        $objCidadeBD->reativar($arrObjCidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Cidade.',$e);
    }
  }

 */

  private function validarNumCodigoIbge(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
  	if ($objCidadeDTO->getNumIdPais()==PaisINT::buscarIdPaisBrasil()){
	    if (InfraString::isBolVazia($objCidadeDTO->getNumCodigoIbge())){
	      $objInfraException->adicionarValidacao('Código do IBGE não informado.');
	    }else{
	      if (!is_numeric($objCidadeDTO->getNumCodigoIbge()) || strlen($objCidadeDTO->getNumCodigoIbge())!=7){
	        $objInfraException->adicionarValidacao('Código do IBGE inválido.');
	      }

	      $dto = new CidadeDTO();
	      $dto->retNumCodigoIbge();
	      if ($objCidadeDTO->isSetNumIdCidade()) {
	        $dto->setNumIdCidade($objCidadeDTO->getNumIdCidade(),InfraDTO::$OPER_DIFERENTE);
	      }
	      $dto->setNumCodigoIbge($objCidadeDTO->getNumCodigoIbge());
	      $dto = $this->consultarRN0409($dto);
	      if ($dto!=null){
	      	$objInfraException->adicionarValidacao('Existe outra ocorrência de Cidade que utiliza o mesmo código do IBGE.');
	      }
	    }
  	}
  }

  private function validarNumIdUfRN0412(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
    if (!$objCidadeDTO->isSetNumIdPais()) {
  	  $cidade=new CidadeDTO();
  		$cidade->setNumIdCidade($objCidadeDTO->getNumIdCidade());
  		$cidade->retNumIdPais();
  		$cidade=$this->consultarRN0409Conectado($cidade);
  		$numIdPais=$cidade->getNumIdPais();
  	} else {
  		$numIdPais=$objCidadeDTO->getNumIdPais();
  	}
  	if (!InfraString::isBolVazia($objCidadeDTO->getNumIdUf())){
  		$objUfDTO = new UfDTO();
  		$objUfDTO->setNumIdUf($objCidadeDTO->getNumIdUf());
  		$objUfRN = new UfRN();
  		$objUfDTO->retNumIdPais();
  		$objUfDTO=$objUfRN->consultarRN0400($objUfDTO);

  		if ($objUfDTO->getNumIdPais()!=$numIdPais) {
  			$objInfraException->adicionarValidacao('Unidade Federativa não corresponde ao país informado.');;
  		}
  	}
  	if ($numIdPais==PaisINT::buscarIdPaisBrasil()){
      if (InfraString::isBolVazia($objCidadeDTO->getNumIdUf())){
        $objInfraException->adicionarValidacao('Unidade Federativa não informada.');
      }
  	}
  }

  private function validarNumIdPais(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objCidadeDTO->getNumIdPais())){
	      $objInfraException->adicionarValidacao('País não selecionado.');
	  }
  }
  private function validarStrSinCapital(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
  	if ($objCidadeDTO->getStrSinCapital()=='S'){
	      $cidade=new CidadeDTO();
	      $cidade->setNumIdCidade($objCidadeDTO->getNumIdCidade());
	      $cidade->retNumIdPais();
	      $cidade->retNumIdUf();
	      $cidade=$this->consultarRN0409Conectado($cidade);
	      if (!is_null($cidade) && $cidade->getNumIdPais()==PaisINT::buscarIdPaisBrasil()) {
	      	$cidade->setNumIdCidade($objCidadeDTO->getNumIdCidade(),InfraDTO::$OPER_DIFERENTE);
	      	$cidade->setStrSinCapital('S');
	      	if ($this->contarRN0414Conectado($cidade)) {
	      		$objInfraException->adicionarValidacao('Já existe capital cadastrada nesta UF.');
	      	}
	      }
	  }
  }
  private function validarStrNomeRN0413(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCidadeDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{

      $objCidadeDTO->setStrNome(trim($objCidadeDTO->getStrNome()));

      if (strlen($objCidadeDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }

      $dto = new CidadeDTO();
      $dto->retNumIdCidade();
      if($objCidadeDTO->getNumIdCidade() != null) {
        $dto->setNumIdCidade($objCidadeDTO->getNumIdCidade(), InfraDTO::$OPER_DIFERENTE);
      }
      $dto->setNumIdPais($objCidadeDTO->getNumIdPais());
      $dto->setNumIdUf($objCidadeDTO->getNumIdUf());
      $dto->setStrNome($objCidadeDTO->getStrNome());
      $dto = $this->consultarRN0409($dto);
      if ($dto != null) {
        $objInfraException->adicionarValidacao('Existe outra ocorrência de Cidade, nesta UF, que utiliza o mesmo Nome.');
      }
    }
  }
  private function validarDblLatitude(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCidadeDTO->getDblLatitude())){
      $objCidadeDTO->setDblLatitude(null);
    }
  }

  private function validarDblLongitude(CidadeDTO $objCidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCidadeDTO->getDblLongitude())){
      $objCidadeDTO->setDblLongitude(null);
    }
  }
}
?>