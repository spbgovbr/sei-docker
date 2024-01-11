<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/07/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoFormularioRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(TipoFormularioDTO $objTipoFormularioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoFormularioDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objTipoFormularioDTO->setStrNome(trim($objTipoFormularioDTO->getStrNome()));

      if (strlen($objTipoFormularioDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
    }
    
    $dto = new TipoFormularioDTO();
    $dto->retStrSinAtivo();
    $dto->setNumIdTipoFormulario($objTipoFormularioDTO->getNumIdTipoFormulario(),InfraDTO::$OPER_DIFERENTE);
    $dto->setStrNome($objTipoFormularioDTO->getStrNome(),InfraDTO::$OPER_IGUAL);
    $dto->setBolExclusaoLogica(false);

    $dto = $this->consultar($dto);
    if ($dto != NULL){
      if ($dto->getStrSinAtivo() == 'S') {
        $objInfraException->adicionarValidacao('Existe outro Tipo de Formulário que utiliza o mesmo Nome.');
      }else {
        $objInfraException->adicionarValidacao('Existe um Tipo de Formulário inativo que utiliza o mesmo Nome.');
      }
   }
}

  private function validarStrDescricao(TipoFormularioDTO $objTipoFormularioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoFormularioDTO->getStrDescricao())){
      $objTipoFormularioDTO->setStrDescricao(null);
    }else{
      $objTipoFormularioDTO->setStrDescricao(trim($objTipoFormularioDTO->getStrDescricao()));

      if (strlen($objTipoFormularioDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(TipoFormularioDTO $objTipoFormularioDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTipoFormularioDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTipoFormularioDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(TipoFormularioDTO $objTipoFormularioDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_cadastrar',__METHOD__,$objTipoFormularioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objTipoFormularioDTO, $objInfraException);
      $this->validarStrDescricao($objTipoFormularioDTO, $objInfraException);
      $this->validarStrSinAtivo($objTipoFormularioDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      $ret = $objTipoFormularioBD->cadastrar($objTipoFormularioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Formulário.',$e);
    }
  }

  protected function alterarControlado(TipoFormularioDTO $objTipoFormularioDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_alterar',__METHOD__,$objTipoFormularioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTipoFormularioDTO->isSetStrNome()){
        $this->validarStrNome($objTipoFormularioDTO, $objInfraException);
      }
      if ($objTipoFormularioDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objTipoFormularioDTO, $objInfraException);
      }
      if ($objTipoFormularioDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objTipoFormularioDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      $objTipoFormularioBD->alterar($objTipoFormularioDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Formulário.',$e);
    }
  }

  protected function excluirControlado($arrObjTipoFormularioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_excluir',__METHOD__,$arrObjTipoFormularioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (count($arrObjTipoFormularioDTO)) {

        $objTipoFormularioDTO = new TipoFormularioDTO();
        $objTipoFormularioDTO->setBolExclusaoLogica(false);
        $objTipoFormularioDTO->retNumIdTipoFormulario();
        $objTipoFormularioDTO->retStrNome();
        $objTipoFormularioDTO->setNumIdTipoFormulario(InfraArray::converterArrInfraDTO($arrObjTipoFormularioDTO,'IdTipoFormulario'),InfraDTO::$OPER_IN);

        $arrObjTipoFormularioDTOBanco = InfraArray::indexarArrInfraDTO($this->listar($objTipoFormularioDTO),'IdTipoFormulario');

        $objSerieRN = new SerieRN();
        $objDocumentoRN = new DocumentoRN();

        foreach ($arrObjTipoFormularioDTO as $objTipoFormularioDTO) {

          if (!isset($arrObjTipoFormularioDTOBanco[$objTipoFormularioDTO->getNumIdTipoFormulario()])){
            throw new InfraException('Tipo de formulário não encontrado para exclusão.');
          }

          $objTipoFormularioDTOBanco = $arrObjTipoFormularioDTOBanco[$objTipoFormularioDTO->getNumIdTipoFormulario()];


          $objSerieDTO = new SerieDTO();
          $objSerieDTO->setBolExclusaoLogica(false);
          $objSerieDTO->retStrNome();
          $objSerieDTO->setNumIdTipoFormulario($objTipoFormularioDTO->getNumIdTipoFormulario());

          if ($objSerieRN->contarRN0647($objSerieDTO)) {
            $objInfraException->adicionarValidacao('Existem tipos de documento utilizando o tipo de formulário "' . $objTipoFormularioDTOBanco->getStrNome() . '".');
          }

          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->setNumIdTipoFormulario($objTipoFormularioDTO->getNumIdTipoFormulario());

          if ($objDocumentoRN->contarRN0007($objDocumentoDTO)){
            $objInfraException->adicionarValidacao('Existem documentos associados com o tipo de formulário "' . $objTipoFormularioDTOBanco->getStrNome() . '".');
          }
        }

        $objInfraException->lancarValidacoes();

        $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjTipoFormularioDTO); $i++) {

          $objAtributoDTO = new AtributoDTO();
          $objAtributoDTO->setBolExclusaoLogica(false);
          $objAtributoDTO->retNumIdAtributo();
          $objAtributoDTO->setNumIdTipoFormulario($arrObjTipoFormularioDTO[$i]->getNumIdTipoFormulario());

          $objAtributoRN = new AtributoRN();
          $objAtributoRN->excluirRN0111($objAtributoRN->listarRN0165($objAtributoDTO));

          $objTipoFormularioBD->excluir($arrObjTipoFormularioDTO[$i]);
        }
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Formulário.',$e);
    }
  }

  protected function consultarConectado(TipoFormularioDTO $objTipoFormularioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_consultar',__METHOD__,$objTipoFormularioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      $ret = $objTipoFormularioBD->consultar($objTipoFormularioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Formulário.',$e);
    }
  }

  protected function listarConectado(TipoFormularioDTO $objTipoFormularioDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_listar',__METHOD__,$objTipoFormularioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      $ret = $objTipoFormularioBD->listar($objTipoFormularioDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Formulários.',$e);
    }
  }

  protected function contarConectado(TipoFormularioDTO $objTipoFormularioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_listar',__METHOD__,$objTipoFormularioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      $ret = $objTipoFormularioBD->contar($objTipoFormularioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Formulários.',$e);
    }
  }

  protected function desativarControlado($arrObjTipoFormularioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_desativar',__METHOD__,$arrObjTipoFormularioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoFormularioDTO);$i++){
        $objTipoFormularioBD->desativar($arrObjTipoFormularioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Formulário.',$e);
    }
  }

  protected function reativarControlado($arrObjTipoFormularioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_reativar',__METHOD__,$arrObjTipoFormularioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTipoFormularioDTO);$i++){
        $objTipoFormularioBD->reativar($arrObjTipoFormularioDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Formulário.',$e);
    }
  }

  protected function bloquearControlado(TipoFormularioDTO $objTipoFormularioDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_consultar',__METHOD__,$objTipoFormularioDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTipoFormularioBD = new TipoFormularioBD($this->getObjInfraIBanco());
      $ret = $objTipoFormularioBD->bloquear($objTipoFormularioDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Tipo de Formulário.',$e);
    }
  }

  protected function clonarControlado(ClonarTipoFormularioDTO $objClonarTipoFormularioDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tipo_formulario_clonar',__METHOD__, $objClonarTipoFormularioDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objClonarTipoFormularioDTO->getNumIdTipoFormularioOrigem())){
        $objInfraException->adicionarValidacao('Tipo de Formulário Origem não informado.');
      }

      if (InfraString::isBolVazia($objClonarTipoFormularioDTO->getStrNomeDestino())){
        $objInfraException->adicionarValidacao('Nome de Destino não informado.');
      }

      $objInfraException->lancarValidacoes();

      $objTipoFormularioDTO = new TipoFormularioDTO();
      $objTipoFormularioDTO->retTodos();
      $objTipoFormularioDTO->setNumIdTipoFormulario($objClonarTipoFormularioDTO->getNumIdTipoFormularioOrigem());
      $objTipoFormularioDTO = $this->consultar($objTipoFormularioDTO);

      $objTipoFormularioDTO->setNumIdTipoFormulario(null);
      $objTipoFormularioDTO->setStrNome($objClonarTipoFormularioDTO->getStrNomeDestino());

      $objTipoFormularioDTO = $this->cadastrar($objTipoFormularioDTO);
      $numIdTipoFormularioNovo = $objTipoFormularioDTO->getNumIdTipoFormulario();

      //modelo clonado, clonar seções com seus estilos
      $objAtributoDTO = new AtributoDTO();
      $objAtributoDTO->retTodos();
      $objAtributoDTO->setNumIdTipoFormulario($objClonarTipoFormularioDTO->getNumIdTipoFormularioOrigem());
      $objAtributoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objAtributoRN = new AtributoRN();
      $arrObjAtributoDTO = $objAtributoRN->listarRN0165($objAtributoDTO);

      $objDominioRN = new DominioRN();
      foreach($arrObjAtributoDTO as $objAtributoDTO){

        $objAtributoDTO->setNumIdTipoFormulario($numIdTipoFormularioNovo);

        $objDominioDTO = new DominioDTO();
        $objDominioDTO->retTodos();
        $objDominioDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
        $objAtributoDTO->setArrObjDominioDTO($objDominioRN->listarRN0199($objDominioDTO));

        $objAtributoRN->cadastrarRN0113($objAtributoDTO);
      }

      //Auditoria

      return $objTipoFormularioDTO;

    }catch(Exception $e){
      throw new InfraException('Erro clonando Tipo de Formulário.',$e);
    }
  }
}
?>