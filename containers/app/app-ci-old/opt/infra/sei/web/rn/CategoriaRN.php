<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CategoriaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(CategoriaDTO $objCategoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCategoriaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Categoria não informada.');
    }else{
      $objCategoriaDTO->setStrNome(trim($objCategoriaDTO->getStrNome()));

      if (strlen($objCategoriaDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Categoria possui tamanho superior a 100 caracteres.');
      }else{
        $objCategoriaDTO_Banco = new CategoriaDTO();
        $objCategoriaDTO_Banco->setStrNome($objCategoriaDTO->getStrNome());
        $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
        if($objCategoriaBD->contar($objCategoriaDTO_Banco) > 0){
          $objInfraException->adicionarValidacao('Já existe Categoria com este nome.');
        }
      }
    }
  }

  private function validarStrSinAtivo(CategoriaDTO $objCategoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCategoriaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objCategoriaDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(CategoriaDTO $objCategoriaDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_cadastrar',__METHOD__,$objCategoriaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objCategoriaDTO, $objInfraException);
      $this->validarStrSinAtivo($objCategoriaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      $ret = $objCategoriaBD->cadastrar($objCategoriaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Categoria.',$e);
    }
  }

  protected function alterarControlado(CategoriaDTO $objCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_alterar',__METHOD__,$objCategoriaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCategoriaDTO->isSetStrNome()){
        $this->validarStrNome($objCategoriaDTO, $objInfraException);
      }
      if ($objCategoriaDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objCategoriaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      $objCategoriaBD->alterar($objCategoriaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Categoria.',$e);
    }
  }

  protected function excluirControlado($arrObjCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_excluir',__METHOD__,$arrObjCategoriaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objContatoRN = new ContatoRN();
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setNumMaxRegistrosRetorno(1);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->setBolExclusaoLogica(false);
      for ($i=0;$i<count($arrObjCategoriaDTO);$i++){
        $objContatoDTO->setNumIdCategoria($arrObjCategoriaDTO[$i]->getNumIdCategoria());

        $objContatoDTO->setStrSinAtivo('S');
        if ($objContatoRN->consultarRN0324($objContatoDTO)!=null){
          $objInfraException->lancarValidacao('Existem contatos utilizando esta categoria.');
        }

        $objContatoDTO->setStrSinAtivo('N');
        if ($objContatoRN->consultarRN0324($objContatoDTO)!=null){
          $objInfraException->lancarValidacao('Existem contatos inativos utilizando esta categoria.');
        }
      }
      
      $objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCategoriaDTO);$i++){
        $objCategoriaBD->excluir($arrObjCategoriaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Categoria.',$e);
    }
  }

  protected function consultarConectado(CategoriaDTO $objCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_consultar',__METHOD__,$objCategoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      $ret = $objCategoriaBD->consultar($objCategoriaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Categoria.',$e);
    }
  }

  protected function listarConectado(CategoriaDTO $objCategoriaDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_listar',__METHOD__,$objCategoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      $ret = $objCategoriaBD->listar($objCategoriaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Categorias.',$e);
    }
  }

  protected function contarConectado(CategoriaDTO $objCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_listar',__METHOD__,$objCategoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      $ret = $objCategoriaBD->contar($objCategoriaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Categorias.',$e);
    }
  }

  protected function desativarControlado($arrObjCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_desativar',__METHOD__,$arrObjCategoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCategoriaDTO);$i++){
        $objCategoriaBD->desativar($arrObjCategoriaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Categoria.',$e);
    }
  }

  protected function reativarControlado($arrObjCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_reativar',__METHOD__,$arrObjCategoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCategoriaDTO);$i++){
        $objCategoriaBD->reativar($arrObjCategoriaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Categoria.',$e);
    }
  }

  protected function bloquearControlado(CategoriaDTO $objCategoriaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('categoria_consultar',__METHOD__,$objCategoriaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCategoriaBD = new CategoriaBD($this->getObjInfraIBanco());
      $ret = $objCategoriaBD->bloquear($objCategoriaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Categoria.',$e);
    }
  }


}
