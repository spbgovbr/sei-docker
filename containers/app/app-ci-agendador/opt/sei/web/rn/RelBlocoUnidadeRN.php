<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/10/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelBlocoUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidadeRN1298(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdBlocoRN1299(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdBloco())){
      $objInfraException->adicionarValidacao('Bloco não informado.');
    }
  }

  private function validarNumIdGrupoBloco(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdGrupoBloco())){
      $objRelBlocoUnidadeDTO->setNumIdGrupoBloco(null);
    }
  }

  private function validarNumIdUsuarioAtribuicao(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdUsuarioAtribuicao())){
      $objRelBlocoUnidadeDTO->setNumIdUsuarioAtribuicao(null);
    }
  }

  private function validarNumIdUsuarioRevisao(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdUsuarioRevisao())){
      $objRelBlocoUnidadeDTO->setNumIdUsuarioRevisao(null);
    }
  }

  private function validarNumIdUsuarioPrioridade(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdUsuarioPrioridade())){
      $objRelBlocoUnidadeDTO->setNumIdUsuarioPrioridade(null);
    }
  }

  private function validarNumIdUsuarioComentario(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getNumIdUsuarioComentario())){
      $objRelBlocoUnidadeDTO->setNumIdUsuarioComentario(null);
    }
  }
  
  private function validarStrSinRetornado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
   if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getStrSinRetornado())){
      $objInfraException->adicionarValidacao('Sinalizador de bloco retornado não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objRelBlocoUnidadeDTO->getStrSinRetornado())){
        $objInfraException->adicionarValidacao('Sinalizador de bloco retornado inválido.');
      }
    }
  }

  private function validarStrSinPrioridade(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getStrSinPrioridade())){
      $objInfraException->adicionarValidacao('Sinalizador de bloco prioritário não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objRelBlocoUnidadeDTO->getStrSinPrioridade())){
        $objInfraException->adicionarValidacao('Sinalizador de bloco prioritário inválido.');
      }
    }
  }

  private function validarStrSinRevisao(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getStrSinRevisao())){
      $objInfraException->adicionarValidacao('Sinalizador de bloco revisado não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objRelBlocoUnidadeDTO->getStrSinRevisao())){
        $objInfraException->adicionarValidacao('Sinalizador de bloco revisado inválido.');
      }
    }
  }

  private function validarStrTextoComentario(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getStrTextoComentario())){
      $objRelBlocoUnidadeDTO->setStrTextoComentario(null);
      $objRelBlocoUnidadeDTO->setStrSinComentario('N');
    }else{
      $objRelBlocoUnidadeDTO->setStrTextoComentario(trim($objRelBlocoUnidadeDTO->getStrTextoComentario()));
      $objRelBlocoUnidadeDTO->setStrTextoComentario(InfraUtil::filtrarISO88591($objRelBlocoUnidadeDTO->getStrTextoComentario()));
      $objRelBlocoUnidadeDTO->setStrTextoComentario(str_replace(array('<b>','</b>','<i>','</i>'),'',$objRelBlocoUnidadeDTO->getStrTextoComentario()));

      if (strlen($objRelBlocoUnidadeDTO->getStrTextoComentario())>2000){
        $objInfraException->adicionarValidacao('Comentário possui tamanho superior a 2000 caracteres.');
      }
      $objRelBlocoUnidadeDTO->setStrSinComentario('S');
    }
  }

  private function validarDthRevisao(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getDthRevisao())){
      $objRelBlocoUnidadeDTO->setDthRevisao(null);
    }else{
      if (!InfraData::validarDataHora($objRelBlocoUnidadeDTO->getDthRevisao())){
        $objInfraException->adicionarValidacao('Data/hora da Revisão inválida.');
      }
    }
  }

  private function validarDthPrioridade(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getDthPrioridade())){
      $objRelBlocoUnidadeDTO->setDthPrioridade(null);
    }else{
      if (!InfraData::validarDataHora($objRelBlocoUnidadeDTO->getDthPrioridade())){
        $objInfraException->adicionarValidacao('Data/hora da Priorização inválida.');
      }
    }
  }

  private function validarDthComentario(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelBlocoUnidadeDTO->getDthComentario())){
      $objRelBlocoUnidadeDTO->setDthComentario(null);
    }else{
      if (!InfraData::validarDataHora($objRelBlocoUnidadeDTO->getDthComentario())){
        $objInfraException->adicionarValidacao('Data/hora do Comentário inválida.');
      }
    }
  }

  protected function cadastrarRN1300Controlado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_cadastrar',__METHOD__,$objRelBlocoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidadeRN1298($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarNumIdBlocoRN1299($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarNumIdGrupoBloco($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarNumIdUsuarioAtribuicao($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarNumIdUsuarioRevisao($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarNumIdUsuarioPrioridade($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarNumIdUsuarioComentario($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarStrSinRetornado($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarStrSinPrioridade($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarStrSinRevisao($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarStrTextoComentario($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarDthPrioridade($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarDthRevisao($objRelBlocoUnidadeDTO, $objInfraException);
      $this->validarDthComentario($objRelBlocoUnidadeDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $dto = new RelBlocoUnidadeDTO();
      $dto->retStrSiglaUnidade();
      $dto->setNumIdUnidade($objRelBlocoUnidadeDTO->getNumIdUnidade());
      $dto->setNumIdBloco($objRelBlocoUnidadeDTO->getNumIdBloco());
      $dtoRN = new RelBlocoUnidadeRN();
      $dto = $dtoRN->consultarRN1303($dto);
      if ($dto != null) {
        $objInfraException->lancarValidacao('Bloco já consta na unidade "'.$dto->getStrSiglaUnidade().'".');
      }

      //if($objRelBlocoUnidadeDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
      //	$objInfraException->lancarValidacao('Bloco não pode ser disponibilizado para a unidade geradora "'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().'".');
      //}
      
      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoUnidadeBD->cadastrar($objRelBlocoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Bloco Unidade.',$e);
    }
  }
  
  protected function alterarRN1301Controlado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_alterar',__METHOD__,$objRelBlocoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelBlocoUnidadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN1298($objRelBlocoUnidadeDTO, $objInfraException);
      }
      
      if ($objRelBlocoUnidadeDTO->isSetNumIdBloco()){
        $this->validarNumIdBlocoRN1299($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetNumIdGrupoBloco()){
        $this->validarNumIdGrupoBloco($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetNumIdUsuarioAtribuicao()){
        $this->validarNumIdUsuarioAtribuicao($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetNumIdUsuarioRevisao()){
        $this->validarNumIdUsuarioRevisao($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetNumIdUsuarioPrioridade()){
        $this->validarNumIdUsuarioPrioridade($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetNumIdUsuarioComentario()){
        $this->validarNumIdUsuarioComentario($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetStrSinRetornado()){
        $this->validarStrSinRetornado($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetStrSinRevisao()){
        $this->validarStrSinRevisao($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetStrSinPrioridade()){
        $this->validarStrSinPrioridade($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetStrTextoComentario()){
        $this->validarStrTextoComentario($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetDthRevisao()){
        $this->validarDthRevisao($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetDthPrioridade()){
        $this->validarDthPrioridade($objRelBlocoUnidadeDTO, $objInfraException);
      }

      if ($objRelBlocoUnidadeDTO->isSetDthComentario()){
        $this->validarDthComentario($objRelBlocoUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      $objRelBlocoUnidadeBD->alterar($objRelBlocoUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Bloco Unidade.',$e);
    }
  }

  protected function excluirRN1302Controlado($arrObjRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_excluir',__METHOD__,$arrObjRelBlocoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      foreach($arrObjRelBlocoUnidadeDTO as $objRelBlocoUnidadeDTO){
        $objRelBlocoUnidadeBD->excluir($objRelBlocoUnidadeDTO);
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Bloco Unidade.',$e);
    }
  }

  protected function consultarRN1303Conectado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_consultar',__METHOD__,$objRelBlocoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoUnidadeBD->consultar($objRelBlocoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Bloco Unidade.',$e);
    }
  }

  protected function listarRN1304Conectado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_listar',__METHOD__,$objRelBlocoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoUnidadeBD->listar($objRelBlocoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Blocos Unidade.',$e);
    }
  }

  protected function contarRN1305Conectado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_listar',__METHOD__,$objRelBlocoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoUnidadeBD->contar($objRelBlocoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Blocos Unidade.',$e);
    }
  }
/* 
  protected function desativarRN1306Controlado($arrObjRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBlocoUnidadeDTO);$i++){
        $objRelBlocoUnidadeBD->desativar($arrObjRelBlocoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Bloco Unidade.',$e);
    }
  }

  protected function reativarRN1307Controlado($arrObjRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelBlocoUnidadeDTO);$i++){
        $objRelBlocoUnidadeBD->reativar($arrObjRelBlocoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Bloco Unidade.',$e);
    }
  }

  protected function bloquearRN1308Controlado(RelBlocoUnidadeDTO $objRelBlocoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_bloco_unidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objRelBlocoUnidadeBD->bloquear($objRelBlocoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Bloco Unidade.',$e);
    }
  }

 */
}
?>