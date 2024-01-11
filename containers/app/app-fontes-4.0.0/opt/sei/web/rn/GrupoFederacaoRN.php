<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoFederacaoRN extends InfraRN {

  public static $TGF_INSTITUCIONAL = 'I';
  public static $TGF_UNIDADE = 'U';

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
      $objTipoDTO->setStrStaTipo(self::$TGF_INSTITUCIONAL);
      $objTipoDTO->setStrDescricao('Institucional');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGF_UNIDADE);
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

  protected function cadastrarControlado(GrupoFederacaoDTO $objGrupoFederacaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_cadastrar',__METHOD__,$objGrupoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objGrupoFederacaoDTO, $objInfraException);
      $this->validarStrNome($objGrupoFederacaoDTO, $objInfraException);
      $this->validarStrStaTipo($objGrupoFederacaoDTO, $objInfraException);
      $this->validarStrDescricao($objGrupoFederacaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objGrupoFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objGrupoFederacaoBD->cadastrar($objGrupoFederacaoDTO);
      
      if (InfraArray::contar($objGrupoFederacaoDTO->getArrObjRelGrupoFedOrgaoFedDTO())>0){
      	$arrRelGrupoFedOrgaoFed = $objGrupoFederacaoDTO->getArrObjRelGrupoFedOrgaoFedDTO();
      	
	      for ($i=0;$i<InfraArray::contar($arrRelGrupoFedOrgaoFed);$i++){
	      	$objRelGrupoFedOrgaoFedRN = new RelGrupoFedOrgaoFedRN();
	      	$arrRelGrupoFedOrgaoFed[$i]->setNumIdGrupoFederacao($ret->getNumIdGrupoFederacao());
	      	$objRelGrupoFedOrgaoFedRN->cadastrar($arrRelGrupoFedOrgaoFed[$i]);
	      }
      }   

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(GrupoFederacaoDTO $objGrupoFederacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_alterar',__METHOD__,$objGrupoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objGrupoFederacaoDTOBanco = new GrupoFederacaoDTO();
      $objGrupoFederacaoDTOBanco->retNumIdUnidade();
      $objGrupoFederacaoDTOBanco->retStrStaTipo();
      $objGrupoFederacaoDTOBanco->setNumIdGrupoFederacao($objGrupoFederacaoDTO->getNumIdGrupoFederacao());
      $objGrupoFederacaoDTOBanco = $this->consultar($objGrupoFederacaoDTOBanco);

      if ($objGrupoFederacaoDTO->isSetNumIdUnidade() && $objGrupoFederacaoDTO->getNumIdUnidade()!=$objGrupoFederacaoDTOBanco->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Unidade do Grupo do SEI Federação não pode ser alterada.');
      }else{
        $objGrupoFederacaoDTO->setNumIdUnidade($objGrupoFederacaoDTOBanco->getNumIdUnidade());
      }

      if ($objGrupoFederacaoDTO->isSetStrStaTipo() && $objGrupoFederacaoDTO->getStrStaTipo()!=$objGrupoFederacaoDTOBanco->getStrStaTipo()){
        $objInfraException->lancarValidacao('Tipo do Grupo do SEI Federação não pode ser alterado.');
      }else{
        $objGrupoFederacaoDTO->setStrStaTipo($objGrupoFederacaoDTOBanco->getStrStaTipo());
      }

      if ($objGrupoFederacaoDTO->isSetStrNome()){
        $this->validarStrNome($objGrupoFederacaoDTO, $objInfraException);
      }

      if ($objGrupoFederacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objGrupoFederacaoDTO, $objInfraException);
      }

      if ($objGrupoFederacaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objGrupoFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();
      
      
      if ($objGrupoFederacaoDTO->isSetArrObjRelGrupoFedOrgaoFedDTO()) {
      	$dtoRN = new RelGrupoFedOrgaoFedRN();
      	$dto = new RelGrupoFedOrgaoFedDTO();
      	$dto->retTodos();
      	$dto->setNumIdGrupoFederacao($objGrupoFederacaoDTO->getNumIdGrupoFederacao());
      	$dtoRN->excluir($dtoRN->listar($dto));
      	
      	$arrRelGrupoFedOrgaoFed = $objGrupoFederacaoDTO->getArrObjRelGrupoFedOrgaoFedDTO();
      	
	      for ($i=0;$i<InfraArray::contar($arrRelGrupoFedOrgaoFed);$i++){
	      	$arrRelGrupoFedOrgaoFed[$i]->setNumIdGrupoFederacao($objGrupoFederacaoDTO->getNumIdGrupoFederacao());
	      	$dtoRN->cadastrar($arrRelGrupoFedOrgaoFed[$i]);
	      }      	
      	
      }

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      $objGrupoFederacaoBD->alterar($objGrupoFederacaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjGrupoFederacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_excluir',__METHOD__,$arrObjGrupoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $dtoRN = new RelGrupoFedOrgaoFedRN();
      $dto = new RelGrupoFedOrgaoFedDTO();
      for ($i=0;$i<count($arrObjGrupoFederacaoDTO);$i++){
      	$dto->retTodos();
      	$dto->setNumIdGrupoFederacao($arrObjGrupoFederacaoDTO[$i]->getNumIdGrupoFederacao());
      	$dtoRN->excluir($dtoRN->listar($dto));
      }
      
      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoFederacaoDTO);$i++){
        $objGrupoFederacaoBD->excluir($arrObjGrupoFederacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(GrupoFederacaoDTO $objGrupoFederacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_consultar',__METHOD__,$objGrupoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objGrupoFederacaoBD->consultar($objGrupoFederacaoDTO);

      //Auditoria

      return $ret;
      
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo do SEI Federação.',$e);
    }
  }

  protected function listarConectado(GrupoFederacaoDTO $objGrupoFederacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_listar',__METHOD__,$objGrupoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objGrupoFederacaoBD->listar($objGrupoFederacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos Contato.',$e);
    }
  }

  protected function contarConectado(GrupoFederacaoDTO $objGrupoFederacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_listar',__METHOD__,$objGrupoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objGrupoFederacaoBD->contar($objGrupoFederacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos Contato.',$e);
    }
  }

  protected function desativarControlado($arrObjGrupoFederacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_institucional_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoFederacaoDTO);$i++){
        $objGrupoFederacaoBD->desativar($arrObjGrupoFederacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjGrupoFederacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_institucional_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoFederacaoBD = new GrupoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoFederacaoDTO);$i++){
        $objGrupoFederacaoBD->reativar($arrObjGrupoFederacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo do SEI Federação.',$e);
    }
  }

  private function validarNumIdUnidade(GrupoFederacaoDTO $objGrupoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoFederacaoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrNome(GrupoFederacaoDTO $objGrupoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoFederacaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objGrupoFederacaoDTO->setStrNome(trim($objGrupoFederacaoDTO->getStrNome()));
  
      if (strlen($objGrupoFederacaoDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
      }

      $dto = new GrupoFederacaoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();

      $dto->setNumIdGrupoFederacao($objGrupoFederacaoDTO->getNumIdGrupoFederacao(), InfraDTO::$OPER_DIFERENTE);

      if ($objGrupoFederacaoDTO->getStrStaTipo()==self::$TGF_UNIDADE) {
        $dto->setNumIdUnidade($objGrupoFederacaoDTO->getNumIdUnidade());
      }

      $dto->setStrNome($objGrupoFederacaoDTO->getStrNome());
      $dto->setStrStaTipo($objGrupoFederacaoDTO->getStrStaTipo());

      $dto = $this->consultar($dto);

      if ($dto!=null) {
        if ($dto->getStrSinAtivo()=='S') {
          if ($objGrupoFederacaoDTO->getStrStaTipo()==self::$TGF_INSTITUCIONAL) {
            $objInfraException->adicionarValidacao('Existe outro Grupo do SEI Federação Institucional com este Nome.');
          } else {
            $objInfraException->adicionarValidacao('Existe outro Grupo do SEI Federação com este Nome para esta Unidade.');
          }
        } else {
          if ($objGrupoFederacaoDTO->getStrStaTipo()==self::$TGF_INSTITUCIONAL) {
            $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo do SEI Federação Institucional com este Nome.');
          } else {
            $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo do SEI Federação com este Nome para esta Unidade.');
          }
        }
      }
    }
  }

  private function validarStrStaTipo(GrupoFederacaoDTO $objGrupoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoFederacaoDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objGrupoFederacaoDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }
  
  private function validarStrDescricao(GrupoFederacaoDTO $objGrupoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoFederacaoDTO->getStrDescricao())){
      $objGrupoFederacaoDTO->setStrDescricao(null);
    }else{
      $objGrupoFederacaoDTO->setStrDescricao(trim($objGrupoFederacaoDTO->getStrDescricao()));
  
      if (strlen($objGrupoFederacaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(GrupoFederacaoDTO $objGrupoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoFederacaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objGrupoFederacaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function pesquisarConectado(GrupoFederacaoDTO $parObjGrupoFederacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_federacao_listar', __METHOD__, $parObjGrupoFederacaoDTO);

      $arrObjInstalacaoFederacaoDTO = array();

      $objRelGrupoFedOrgaoFedDTO = new RelGrupoFedOrgaoFedDTO();
      $objRelGrupoFedOrgaoFedDTO->retStrIdInstalacaoFederacao();
      $objRelGrupoFedOrgaoFedDTO->retStrIdOrgaoFederacao();
      $objRelGrupoFedOrgaoFedDTO->retStrSiglaOrgaoFederacao();
      $objRelGrupoFedOrgaoFedDTO->retStrDescricaoOrgaoFederacao();
      $objRelGrupoFedOrgaoFedDTO->retStrSiglaInstalacaoFederacao();
      $objRelGrupoFedOrgaoFedDTO->setNumIdGrupoFederacao($parObjGrupoFederacaoDTO->getNumIdGrupoFederacao());

      $objRelGrupoFedOrgaoFedRN = new RelGrupoFedOrgaoFedRN();
      $arrObjRelGrupoFedOrgaoFedDTOPorInstalacao = InfraArray::indexarArrInfraDTO($objRelGrupoFedOrgaoFedRN->listar($objRelGrupoFedOrgaoFedDTO),'IdInstalacaoFederacao',true);

      if (count($arrObjRelGrupoFedOrgaoFedDTOPorInstalacao)) {

        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoDest(array_keys($arrObjRelGrupoFedOrgaoFedDTOPorInstalacao));

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $arrObjInstalacaoFederacaoDTO = $objAcessoFederacaoRN->pesquisarOrgaosUnidadesEnvio($objAcessoFederacaoDTO);

        foreach ($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO) {
          if ($objInstalacaoFederacaoDTO->getObjInfraException() == null) {

            $arrObjOrgaoFederacaoDTORetorno = InfraArray::indexarArrInfraDTO($objInstalacaoFederacaoDTO->getArrObjOrgaoFederacaoDTO(), 'IdOrgaoFederacao');

            $arrObjRelGrupoFedOrgaoFedDTO = $arrObjRelGrupoFedOrgaoFedDTOPorInstalacao[$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()];

            $arrObjOrgaoFederacaoDTOResultado = array();
            foreach($arrObjRelGrupoFedOrgaoFedDTO as $objRelGrupoFedOrgaoFedDTO) {
              if (!isset($arrObjOrgaoFederacaoDTORetorno[$objRelGrupoFedOrgaoFedDTO->getStrIdOrgaoFederacao()])){
                $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
                $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($objRelGrupoFedOrgaoFedDTO->getStrIdOrgaoFederacao());
                $objOrgaoFederacaoDTO->setStrSigla($objRelGrupoFedOrgaoFedDTO->getStrSiglaOrgaoFederacao());
                $objOrgaoFederacaoDTO->setStrDescricao($objRelGrupoFedOrgaoFedDTO->getStrDescricaoOrgaoFederacao());
                $objOrgaoFederacaoDTO->setObjInfraException(new InfraException('Órgão não encontrado na instalação.'));
                $arrObjOrgaoFederacaoDTOResultado[] = $objOrgaoFederacaoDTO;
              }else{
                $arrObjOrgaoFederacaoDTOResultado[] = $arrObjOrgaoFederacaoDTORetorno[$objRelGrupoFedOrgaoFedDTO->getStrIdOrgaoFederacao()];
              }
            }
            $objInstalacaoFederacaoDTO->setArrObjOrgaoFederacaoDTO($arrObjOrgaoFederacaoDTOResultado);
          }
        }
      }

      return $arrObjInstalacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Grupo do SEI Federação.',$e);
    }
  }

}
?>