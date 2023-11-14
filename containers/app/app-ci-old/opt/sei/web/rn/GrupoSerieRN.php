<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoSerieRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0775Controlado(GrupoSerieDTO $objGrupoSerieDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_cadastrar',__METHOD__,$objGrupoSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeRN0783($objGrupoSerieDTO, $objInfraException);
      $this->validarStrDescricaoRN0784($objGrupoSerieDTO, $objInfraException);
      $this->validarStrSinAtivoRN0785($objGrupoSerieDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      $ret = $objGrupoSerieBD->cadastrar($objGrupoSerieDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Tipo de Documento.',$e);
    }
  }

  protected function alterarRN0776Controlado(GrupoSerieDTO $objGrupoSerieDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_alterar',__METHOD__,$objGrupoSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objGrupoSerieDTO->isSetStrNome()){
        $this->validarStrNomeRN0783($objGrupoSerieDTO, $objInfraException);
      }
      if ($objGrupoSerieDTO->isSetStrDescricao()){
        $this->validarStrDescricaoRN0784($objGrupoSerieDTO, $objInfraException);
      }
      if ($objGrupoSerieDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0785($objGrupoSerieDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      $objGrupoSerieBD->alterar($objGrupoSerieDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Tipo de Documento.',$e);
    }
  }

  protected function excluirRN0779Controlado($arrObjGrupoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_excluir',__METHOD__,$arrObjGrupoSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objGrupoSerieDTO = new GrupoSerieDTO();
      $objGrupoSerieDTO->setBolExclusaoLogica(false);
      $objGrupoSerieDTO->retNumIdGrupoSerie();
      $objGrupoSerieDTO->retStrNome();
      $objGrupoSerieDTO->setNumIdGrupoSerie(InfraArray::converterArrInfraDTO($arrObjGrupoSerieDTO,'IdGrupoSerie'),InfraDTO::$OPER_IN);
      $arrMap = InfraArray::mapearArrInfraDTO($this->listarRN0778($objGrupoSerieDTO),'IdGrupoSerie','Nome');
      
      $dtoRN = new SerieRN();
      $dto = new SerieDTO();
      $dto->setBolExclusaoLogica(false);
      for ($i=0;$i<count($arrObjGrupoSerieDTO);$i++){
        $dto->setNumIdGrupoSerie($arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie());

        $dto->setStrSinAtivo('S');
        if ($dtoRN->contarRN0647($dto)){
          $objInfraException->adicionarValidacao('Existem tipos utilizando o grupo "' . $arrMap[$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie()] . '".');
        }
 	      
        $dto->setStrSinAtivo('N');
        if ($dtoRN->contarRN0647($dto)){
          $objInfraException->adicionarValidacao('Existem tipos inativos utilizando o grupo "' . $arrMap[$arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie()] . '".');
        }
        
      }
      
      $objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoSerieDTO);$i++){
        $objGrupoSerieBD->excluir($arrObjGrupoSerieDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Tipo de Documento.',$e);
    }
  }

  protected function consultarRN0777Conectado(GrupoSerieDTO $objGrupoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_consultar',__METHOD__,$objGrupoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      $ret = $objGrupoSerieBD->consultar($objGrupoSerieDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Tipo de Documento.',$e);
    }
  }

  protected function listarRN0778Conectado(GrupoSerieDTO $objGrupoSerieDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_listar',__METHOD__,$objGrupoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      $ret = $objGrupoSerieBD->listar($objGrupoSerieDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Tipos de Documento.',$e);
    }
  }

  protected function contarRN0780Conectado(GrupoSerieDTO $objGrupoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_listar',__METHOD__,$objGrupoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      $ret = $objGrupoSerieBD->contar($objGrupoSerieDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos de Tipos de Documento.',$e);
    }
  }

  protected function desativarRN0781Controlado($arrObjGrupoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_desativar',__METHOD__,$arrObjGrupoSerieDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      $dtoRN = new SerieRN();
      $dto = new SerieDTO();
      for ($i=0;$i<count($arrObjGrupoSerieDTO);$i++){
        $dto->setNumIdGrupoSerie($arrObjGrupoSerieDTO[$i]->getNumIdGrupoSerie());
        if ($dtoRN->contarRN0647($dto)>0){
          $objInfraException->adicionarValidacao('Existem tipos ativos utilizando este grupo.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoSerieDTO);$i++){
        $objGrupoSerieBD->desativar($arrObjGrupoSerieDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de Tipo de Documento.',$e);
    }
  }

  protected function reativarRN0782Controlado($arrObjGrupoSerieDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_serie_reativar',__METHOD__,$arrObjGrupoSerieDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoSerieBD = new GrupoSerieBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoSerieDTO);$i++){
        $objGrupoSerieBD->reativar($arrObjGrupoSerieDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de Tipo de Documento.',$e);
    }
  }

  private function validarStrNomeRN0783(GrupoSerieDTO $objGrupoSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoSerieDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objGrupoSerieDTO->setStrNome(trim($objGrupoSerieDTO->getStrNome()));
  
      if (strlen($objGrupoSerieDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new GrupoSerieDTO();
      $dto->retStrSinAtivo();
      $dto->setNumIdGrupoSerie($objGrupoSerieDTO->getNumIdGrupoSerie(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrNome($objGrupoSerieDTO->getStrNome(),InfraDTO::$OPER_IGUAL);
      $dto->setBolExclusaoLogica(false);
          
      $dto = $this->consultarRN0777($dto);
      if ($dto != NULL){
        if ($dto->getStrSinAtivo() == 'S')
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Grupo de Tipo de Documento que utiliza o mesmo Nome.');
        else
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de Tipo de Documento que utiliza o mesmo Nome.');    	
      }
    }
  }

  private function validarStrDescricaoRN0784(GrupoSerieDTO $objGrupoSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoSerieDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objGrupoSerieDTO->setStrDescricao(trim($objGrupoSerieDTO->getStrDescricao()));
  
      if (strlen($objGrupoSerieDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivoRN0785(GrupoSerieDTO $objGrupoSerieDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoSerieDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objGrupoSerieDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
}
?>