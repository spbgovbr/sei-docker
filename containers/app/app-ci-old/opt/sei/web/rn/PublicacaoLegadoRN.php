<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoLegadoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdPublicacaoLegadoAgrupador(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getNumIdPublicacaoLegadoAgrupador())){
      $objInfraException->adicionarValidacao('Id Publicação Legado Agrupador não informado.');
    }
  }

  private function validarNumIdSerie(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getNumIdSerie())){
      $objInfraException->adicionarValidacao('Id Série não informado.');
    }
  }

  private function validarNumIdUnidade(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Id Unidade não informado.');
    }
  }

  private function validarNumIdVeiculoIO(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getNumIdVeiculoIO())){
      $objPublicacaoLegadoDTO->setNumIdVeiculoIO(null);
    }
  }

  private function validarNumIdSecaoIO(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getNumIdSecaoIO())){
      $objPublicacaoLegadoDTO->setNumIdSecaoIO(null);
    }
  }

  private function validarNumIdVeiculoPublicacao(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getNumIdVeiculoPublicacao())){
      $objInfraException->adicionarValidacao('Id Veículo de Publicação não informado.');
    }
  }

  private function validarStrIdDocumento(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getStrIdDocumento())){
      $objInfraException->adicionarValidacao('Id Documento não informado.');
    }else{
      $objPublicacaoLegadoDTO->setStrIdDocumento(trim($objPublicacaoLegadoDTO->getStrIdDocumento()));

      if (strlen($objPublicacaoLegadoDTO->getStrIdDocumento())>20){
        $objInfraException->adicionarValidacao('Id Documento possui tamanho superior a 20 caracteres.');
      }
    }
  }

  private function validarDtaPublicacao(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getDtaPublicacao())){
      $objInfraException->adicionarValidacao('Data de Publicação não informada.');
    }else{
      if (!InfraData::validarData($objPublicacaoLegadoDTO->getDtaPublicacao())){
        $objInfraException->adicionarValidacao('Data de Publicação inválida.');
      }
    }
  }

  private function validarStrNumero(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getStrNumero())){
      $objPublicacaoLegadoDTO->setStrNumero(null);
    }else{
      $objPublicacaoLegadoDTO->setStrNumero(trim($objPublicacaoLegadoDTO->getStrNumero()));

      if (strlen($objPublicacaoLegadoDTO->getStrNumero())>30){
        $objInfraException->adicionarValidacao('Número possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrResumo(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getStrResumo())){
      $objPublicacaoLegadoDTO->setStrResumo(null);
    }else{
      $objPublicacaoLegadoDTO->setStrResumo(trim($objPublicacaoLegadoDTO->getStrResumo()));
    }
  }

  private function validarStrConteudoDocumento(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getStrConteudoDocumento())){
      $objInfraException->adicionarValidacao('Conteúdo do Documento não informado.');
    }else{
      $objPublicacaoLegadoDTO->setStrConteudoDocumento(trim($objPublicacaoLegadoDTO->getStrConteudoDocumento()));
    }
  }

  private function validarStrPaginaIO(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getStrPaginaIO())){
      $objPublicacaoLegadoDTO->setStrPaginaIO(null);
    }else{
      $objPublicacaoLegadoDTO->setStrPaginaIO(trim($objPublicacaoLegadoDTO->getStrPaginaIO()));

      if (strlen($objPublicacaoLegadoDTO->getStrPaginaIO())>20){
        $objInfraException->adicionarValidacao('Página da Imprensa Oficial possui tamanho superior a 20 caracteres.');
      }
    }
  }

  private function validarDtaPublicacaoIO(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getDtaPublicacaoIO())){
      $objPublicacaoLegadoDTO->setDtaPublicacaoIO(null);
    }else{
      if (!InfraData::validarData($objPublicacaoLegadoDTO->getDtaPublicacaoIO())){
        $objInfraException->adicionarValidacao('Data de Publicação da Imprensa Oficial inválida.');
      }
    }
  }

  private function validarDtaGeracao(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getDtaGeracao())){
      $objInfraException->adicionarValidacao('Data de Geração não informada.');
    }else{
      if (!InfraData::validarData($objPublicacaoLegadoDTO->getDtaGeracao())){
        $objInfraException->adicionarValidacao('Data de Geração inválida.');
      }
    }
  }

  private function validarStrProtocoloFormatado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoLegadoDTO->getStrProtocoloFormatado())){
      $objInfraException->adicionarValidacao('Protocolo Formatado não informado.');
    }else{
      $objPublicacaoLegadoDTO->setStrProtocoloFormatado(trim($objPublicacaoLegadoDTO->getStrProtocoloFormatado()));

      if (strlen($objPublicacaoLegadoDTO->getStrProtocoloFormatado())>50){
        $objInfraException->adicionarValidacao('Protocolo Formatado possui tamanho superior a 50 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_cadastrar',__METHOD__,$objPublicacaoLegadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPublicacaoLegadoAgrupador($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarNumIdSerie($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarNumIdUnidade($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarNumIdVeiculoIO($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarNumIdSecaoIO($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarNumIdVeiculoPublicacao($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarStrIdDocumento($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarDtaPublicacao($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarStrNumero($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarStrResumo($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarStrConteudoDocumento($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarStrPaginaIO($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarDtaPublicacaoIO($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarDtaGeracao($objPublicacaoLegadoDTO, $objInfraException);
      $this->validarStrProtocoloFormatado($objPublicacaoLegadoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoLegadoBD->cadastrar($objPublicacaoLegadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Publicação Legado.',$e);
    }
  }

  protected function alterarControlado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_alterar',__METHOD__,$objPublicacaoLegadoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objPublicacaoLegadoDTO->isSetNumIdPublicacaoLegadoAgrupador()){
        $this->validarNumIdPublicacaoLegadoAgrupador($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetNumIdVeiculoIO()){
        $this->validarNumIdVeiculoIO($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetNumIdSecaoIO()){
        $this->validarNumIdSecaoIO($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetNumIdVeiculoPublicacao()){
        $this->validarNumIdVeiculoPublicacao($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetStrIdDocumento()){
        $this->validarStrIdDocumento($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetDtaPublicacao()){
        $this->validarDtaPublicacao($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetStrNumero()){
        $this->validarStrNumero($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetStrResumo()){
        $this->validarStrResumo($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetStrConteudoDocumento()){
        $this->validarStrConteudoDocumento($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetStrPaginaIO()){
        $this->validarStrPaginaIO($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetDtaPublicacaoIO()){
        $this->validarDtaPublicacaoIO($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetDtaGeracao()){
        $this->validarDtaGeracao($objPublicacaoLegadoDTO, $objInfraException);
      }
      if ($objPublicacaoLegadoDTO->isSetStrProtocoloFormatado()){
        $this->validarStrProtocoloFormatado($objPublicacaoLegadoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      $objPublicacaoLegadoBD->alterar($objPublicacaoLegadoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Publicação Legado.',$e);
    }
  }

  protected function excluirControlado($arrObjPublicacaoLegadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_excluir',__METHOD__,$arrObjPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPublicacaoLegadoDTO);$i++){
        $objPublicacaoLegadoBD->excluir($arrObjPublicacaoLegadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Publicação Legado.',$e);
    }
  }

  protected function consultarConectado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_consultar',__METHOD__,$objPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoLegadoBD->consultar($objPublicacaoLegadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Publicação Legado.',$e);
    }
  }
  
  protected function retornarPublicacoesRelacionadasLegadoConectado($parArrObjPublicacaoLegadoDTO) {
    try{
  
      $ret = array();
  
      if (InfraArray::contar($parArrObjPublicacaoLegadoDTO)){
  
        $arrIdPublicacaoLegado = InfraArray::converterArrInfraDTO($parArrObjPublicacaoLegadoDTO,'IdPublicacaoLegado');
         
        $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();
        $objPublicacaoLegadoDTO->retNumIdPublicacaoLegadoAgrupador();
        $objPublicacaoLegadoDTO->setNumIdPublicacaoLegado($arrIdPublicacaoLegado, InfraDTO::$OPER_IN);
        $arrObjPublicacaoLegadoDTO = $this->listar($objPublicacaoLegadoDTO);
         
        $arrIdPublicacaoLegadoAgrupador = array();
        foreach($arrObjPublicacaoLegadoDTO as $objPublicacaoLegadoDTO){
          $arrIdPublicacaoLegadoAgrupador[] =  $objPublicacaoLegadoDTO->getNumIdPublicacaoLegadoAgrupador();
        }
         
        $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();
        $objPublicacaoLegadoDTO->retNumIdPublicacaoLegado();
        $objPublicacaoLegadoDTO->retNumIdPublicacaoLegadoAgrupador();
        $objPublicacaoLegadoDTO->setNumIdPublicacaoLegadoAgrupador($arrIdPublicacaoLegadoAgrupador, InfraDTO::$OPER_IN);
        $arrObjPublicacaoLegadoDTO = $this->listar($objPublicacaoLegadoDTO);
  
        $arrObjPublicacaoLegadoDTO_Agrupador = array();
        foreach($arrObjPublicacaoLegadoDTO as $objPublicacaoLegadoDTO){
          $arrObjPublicacaoLegadoDTO_Agrupador[$objPublicacaoLegadoDTO->getNumIdPublicacaoLegadoAgrupador()][] = $objPublicacaoLegadoDTO;
        }
  
        foreach($arrObjPublicacaoLegadoDTO_Agrupador as $numIdPublicacaoLegadoAgrupador => $arrObjPublicacaoLegadoDTO){
          if (InfraArray::contar($arrObjPublicacaoLegadoDTO)>1){
            foreach($arrObjPublicacaoLegadoDTO as $objPublicacaoLegadoDTO){
              if (!in_array($objPublicacaoLegadoDTO->getNumIdPublicacaoLegado(),$ret)){
                $ret[] = $objPublicacaoLegadoDTO->getNumIdPublicacaoLegado();
              }
            }
          }
        }
      }
       
      return InfraArray::gerarArrInfraDTO('PublicacaoLegadoDTO','IdPublicacaoLegado',$ret);
       
    }catch(Exception $e){
      throw new InfraException('Erro retornando publicações legadas relacionadas.',$e);
    }
  }
  
  protected function listarPublicacaoLegadoConectado($parObjPublicacaoLegadoDTO){
    try{
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_listar',__METHOD__,$parObjPublicacaoLegadoDTO);
      
      //Regras de Negocio
      //$objInfraException = new InfraException();
      
      //$objInfraException->lancarValidacoes();
      
  
      $objPublicacaoLegadoRN = new PublicacaoLegadoRN();
      
      $objPublicacaoLegadoDTO = new PublicacaoLegadoDTO();
      $objPublicacaoLegadoDTO->setStrIdDocumento($parObjPublicacaoLegadoDTO->getStrIdDocumento());
      
      $objPublicacaoLegadoDTO->retNumIdPublicacaoLegado();
      $objPublicacaoLegadoDTO->retStrIdDocumento();
      $objPublicacaoLegadoDTO->retDtaGeracao();
      $objPublicacaoLegadoDTO->retStrNomeSerie();
      $objPublicacaoLegadoDTO->retStrNumero();      
      $objPublicacaoLegadoDTO->retNumIdVeiculoIO();
      $objPublicacaoLegadoDTO->retStrNomeVeiculoPublicacao();
      $objPublicacaoLegadoDTO->retStrDescricaoVeiculoPublicacao();
      $objPublicacaoLegadoDTO->retStrSiglaVeiculoImprensaNacional();
      $objPublicacaoLegadoDTO->retStrDescricaoVeiculoImprensaNacional();
      $objPublicacaoLegadoDTO->retDtaPublicacaoIO();
      $objPublicacaoLegadoDTO->retNumIdSecaoIO();
      $objPublicacaoLegadoDTO->retStrNomeSecaoImprensaNacional();
      $objPublicacaoLegadoDTO->retStrPaginaIO();
      $objPublicacaoLegadoDTO->retDtaPublicacao();
      $objPublicacaoLegadoDTO->retStrProtocoloFormatado();      
      $objPublicacaoLegadoDTO->retStrSiglaUnidade();
      $objPublicacaoLegadoDTO->retStrDescricaoUnidade();      
      $objPublicacaoLegadoDTO->retStrSiglaOrgaoUnidade();
      $objPublicacaoLegadoDTO->retStrDescricaoOrgaoUnidade();
      $objPublicacaoLegadoDTO->retStrResumo();                        
      
      $objPublicacaoLegadoDTO->setOrdDtaPublicacao(InfraDTO::$TIPO_ORDENACAO_DESC);
  
      
      $arrObjPublicacaoLegadoDTO = $objPublicacaoLegadoRN->listar($objPublicacaoLegadoDTO);
      
  
      return $arrObjPublicacaoLegadoDTO;
  
    }catch(Exception $e){
      throw new InfraException('Erro listando publicação legado.',$e);
    }
  }
   
  protected function listarConectado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_listar',__METHOD__,$objPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoLegadoBD->listar($objPublicacaoLegadoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Publicações Legado.',$e);
    }
  }

  protected function contarConectado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_listar',__METHOD__,$objPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoLegadoBD->contar($objPublicacaoLegadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Publicações Legado.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjPublicacaoLegadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_desativar',__METHOD__,$arrObjPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPublicacaoLegadoDTO);$i++){
        $objPublicacaoLegadoBD->desativar($arrObjPublicacaoLegadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Publicação Legado.',$e);
    }
  }

  protected function reativarControlado($arrObjPublicacaoLegadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_reativar',__METHOD__,$arrObjPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPublicacaoLegadoDTO);$i++){
        $objPublicacaoLegadoBD->reativar($arrObjPublicacaoLegadoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Publicação Legado.',$e);
    }
  }

  protected function bloquearControlado(PublicacaoLegadoDTO $objPublicacaoLegadoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_legado_consultar',__METHOD__,$objPublicacaoLegadoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoLegadoBD = new PublicacaoLegadoBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoLegadoBD->bloquear($objPublicacaoLegadoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Publicação Legado.',$e);
    }
  }

 */
}
?>