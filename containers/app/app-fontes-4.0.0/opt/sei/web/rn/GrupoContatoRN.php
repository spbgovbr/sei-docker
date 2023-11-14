<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoContatoRN extends InfraRN {

  public static $TGC_INSTITUCIONAL = 'I';
  public static $TGC_UNIDADE = 'U';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresTipo(){
    try {

      $arrObjTipoDTO = array();

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGC_INSTITUCIONAL);
      $objTipoDTO->setStrDescricao('Institucional');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGC_UNIDADE);
      $objTipoDTO->setStrDescricao('Unidade');
      $arrObjTipoDTO[] = $objTipoDTO;

      return $arrObjTipoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }

  public function getNumMaxTamanhoNome(){
    return 50;
  }

  protected function cadastrarRN0472Controlado(GrupoContatoDTO $objGrupoContatoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_cadastrar',__METHOD__,$objGrupoContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidadeRN0478($objGrupoContatoDTO, $objInfraException);
      $this->validarStrNomeRN0479($objGrupoContatoDTO, $objInfraException);
      $this->validarStrStaTipo($objGrupoContatoDTO, $objInfraException);
      $this->validarStrDescricaoRN0480($objGrupoContatoDTO, $objInfraException);
      $this->validarStrSinAtivo($objGrupoContatoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objGrupoContatoBD->cadastrar($objGrupoContatoDTO);
      
      if (InfraArray::contar($objGrupoContatoDTO->getArrObjRelGrupoContatoDTO())>0){
      	$arrRelGrupoContato = $objGrupoContatoDTO->getArrObjRelGrupoContatoDTO();
      	
	      for ($i=0;$i<InfraArray::contar($arrRelGrupoContato);$i++){
	      	$objRelGrupoContatoRN = new RelGrupoContatoRN();
	      	$arrRelGrupoContato[$i]->setNumIdGrupoContato($ret->getNumIdGrupoContato());
	      	$objRelGrupoContatoRN->cadastrarRN0462($arrRelGrupoContato[$i]);
	      }
      }   

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo Contato.',$e);
    }
  }

  protected function alterarRN0473Controlado(GrupoContatoDTO $objGrupoContatoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_alterar',__METHOD__,$objGrupoContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objGrupoContatoDTOBanco = new GrupoContatoDTO();
      $objGrupoContatoDTOBanco->retNumIdUnidade();
      $objGrupoContatoDTOBanco->retStrStaTipo();
      $objGrupoContatoDTOBanco->setNumIdGrupoContato($objGrupoContatoDTO->getNumIdGrupoContato());
      $objGrupoContatoDTOBanco = $this->consultarRN0474($objGrupoContatoDTOBanco);

      if ($objGrupoContatoDTO->isSetNumIdUnidade() && $objGrupoContatoDTO->getNumIdUnidade()!=$objGrupoContatoDTOBanco->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Unidade do Grupo Contato não pode ser alterada.');
      }else{
        $objGrupoContatoDTO->setNumIdUnidade($objGrupoContatoDTOBanco->getNumIdUnidade());
      }

      if ($objGrupoContatoDTO->isSetStrStaTipo() && $objGrupoContatoDTO->getStrStaTipo()!=$objGrupoContatoDTOBanco->getStrStaTipo()){
        $objInfraException->lancarValidacao('Tipo do Grupo Contato não pode ser alterado.');
      }else{
        $objGrupoContatoDTO->setStrStaTipo($objGrupoContatoDTOBanco->getStrStaTipo());
      }

      if ($objGrupoContatoDTO->isSetStrNome()){
        $this->validarStrNomeRN0479($objGrupoContatoDTO, $objInfraException);
      }

      if ($objGrupoContatoDTO->isSetStrDescricao()){
        $this->validarStrDescricaoRN0480($objGrupoContatoDTO, $objInfraException);
      }

      if ($objGrupoContatoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objGrupoContatoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();
      
      
      if ($objGrupoContatoDTO->isSetArrObjRelGrupoContatoDTO()) {
      	$dtoRN = new RelGrupoContatoRN();
      	$dto = new RelGrupoContatoDTO();
      	$dto->retTodos();
      	$dto->setNumIdGrupoContato($objGrupoContatoDTO->getNumIdGrupoContato());
      	$dtoRN->excluirRN0464($dtoRN->listarRN0463($dto));
      	
      	$arrRelGrupoContato = $objGrupoContatoDTO->getArrObjRelGrupoContatoDTO();
      	
	      for ($i=0;$i<InfraArray::contar($arrRelGrupoContato);$i++){
	      	$arrRelGrupoContato[$i]->setNumIdGrupoContato($objGrupoContatoDTO->getNumIdGrupoContato());
	      	$dtoRN->cadastrarRN0462($arrRelGrupoContato[$i]);
	      }      	
      	
      }

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      $objGrupoContatoBD->alterar($objGrupoContatoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo Contato.',$e);
    }
  }

  protected function excluirRN0475Controlado($arrObjGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_excluir',__METHOD__,$arrObjGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $dtoRN = new RelGrupoContatoRN();
      $dto = new RelGrupoContatoDTO();
      for ($i=0;$i<count($arrObjGrupoContatoDTO);$i++){
      	$dto->retTodos();
      	$dto->setNumIdGrupoContato($arrObjGrupoContatoDTO[$i]->getNumIdGrupoContato());
      	$dtoRN->excluirRN0464($dtoRN->listarRN0463($dto));
      }
      
      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoContatoDTO);$i++){
        $objGrupoContatoBD->excluir($arrObjGrupoContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo Contato.',$e);
    }
  }

  protected function consultarRN0474Conectado(GrupoContatoDTO $objGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_consultar',__METHOD__,$objGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objGrupoContatoBD->consultar($objGrupoContatoDTO);

      //Auditoria

      return $ret;
      
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo Contato.',$e);
    }
  }

  protected function listarRN0477Conectado(GrupoContatoDTO $objGrupoContatoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_listar',__METHOD__,$objGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objGrupoContatoBD->listar($objGrupoContatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos Contato.',$e);
    }
  }

  protected function contarRN0145Conectado(GrupoContatoDTO $objGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_listar',__METHOD__,$objGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objGrupoContatoBD->contar($objGrupoContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos Contato.',$e);
    }
  }

  protected function desativarControlado($arrObjGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_institucional_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoContatoDTO);$i++){
        $objGrupoContatoBD->desativar($arrObjGrupoContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo Contato.',$e);
    }
  }

  protected function reativarControlado($arrObjGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_contato_institucional_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoContatoBD = new GrupoContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoContatoDTO);$i++){
        $objGrupoContatoBD->reativar($arrObjGrupoContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo Contato.',$e);
    }
  }

  private function validarNumIdUnidadeRN0478(GrupoContatoDTO $objGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoContatoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrNomeRN0479(GrupoContatoDTO $objGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoContatoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objGrupoContatoDTO->setStrNome(trim($objGrupoContatoDTO->getStrNome()));
  
      if (strlen($objGrupoContatoDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
      }

      $dto = new GrupoContatoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();

      $dto->setNumIdGrupoContato($objGrupoContatoDTO->getNumIdGrupoContato(), InfraDTO::$OPER_DIFERENTE);

      if ($objGrupoContatoDTO->getStrStaTipo()==self::$TGC_UNIDADE) {
        $dto->setNumIdUnidade($objGrupoContatoDTO->getNumIdUnidade());
      }

      $dto->setStrNome($objGrupoContatoDTO->getStrNome());
      $dto->setStrStaTipo($objGrupoContatoDTO->getStrStaTipo());

      $dto = $this->consultarRN0474($dto);

      if ($dto!=null) {
        if ($dto->getStrSinAtivo()=='S') {
          if ($objGrupoContatoDTO->getStrStaTipo()==self::$TGC_INSTITUCIONAL) {
            $objInfraException->adicionarValidacao('Existe outro Grupo de Contatos Institucional com este Nome.');
          } else {
            $objInfraException->adicionarValidacao('Existe outro Grupo de Contatos com este Nome para esta Unidade.');
          }
        } else {
          if ($objGrupoContatoDTO->getStrStaTipo()==self::$TGC_INSTITUCIONAL) {
            $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de Contatos Institucional com este Nome.');
          } else {
            $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de Contatos com este Nome para esta Unidade.');
          }
        }
      }
    }
  }

  private function validarStrStaTipo(GrupoContatoDTO $objGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoContatoDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objGrupoContatoDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }
  
  private function validarStrDescricaoRN0480(GrupoContatoDTO $objGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoContatoDTO->getStrDescricao())){
      $objGrupoContatoDTO->setStrDescricao(null);
    }else{
      $objGrupoContatoDTO->setStrDescricao(trim($objGrupoContatoDTO->getStrDescricao()));
  
      if (strlen($objGrupoContatoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(GrupoContatoDTO $objGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoContatoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objGrupoContatoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }  
}
?>