<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class LocalizadorRN extends InfraRN {
  
  public static $EA_ABERTO = 'A';
  public static $EA_FECHADO = 'F';
  
  public static $POS_LOCALIZADOR_ID_UNIDADE = 0;
  public static $POS_LOCALIZADOR_IDENTIFICACAO = 1;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0617Controlado(LocalizadorDTO $objLocalizadorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_cadastrar',__METHOD__,$objLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidadeRN0623($objLocalizadorDTO, $objInfraException);
      $this->validarNumIdTipoLocalizadorRN0624($objLocalizadorDTO, $objInfraException);
      $this->validarNumIdTipoSuporteRN0625($objLocalizadorDTO, $objInfraException);
      $this->validarNumIdLugarLocalizadorRN0626($objLocalizadorDTO, $objInfraException);
      $this->validarStrComplementoRN0628($objLocalizadorDTO, $objInfraException);
      $this->validarStrStaEstadoRN0630($objLocalizadorDTO, $objInfraException);
      $this->validarNumSeqLocalizadorRN0629($objLocalizadorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLocalizadorBD->cadastrar($objLocalizadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Localizador.',$e);
    }
  }

  protected function alterarRN0618Controlado(LocalizadorDTO $objLocalizadorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('localizador_alterar',__METHOD__,$objLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objLocalizadorDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0623($objLocalizadorDTO, $objInfraException);
      }
      if ($objLocalizadorDTO->isSetNumIdTipoLocalizador()){
        $this->validarNumIdTipoLocalizadorRN0624($objLocalizadorDTO, $objInfraException);
      }
      if ($objLocalizadorDTO->isSetNumIdTipoSuporte()){
        $this->validarNumIdTipoSuporteRN0625($objLocalizadorDTO, $objInfraException);
      }
      if ($objLocalizadorDTO->isSetNumIdLugarLocalizador()){
        $this->validarNumIdLugarLocalizadorRN0626($objLocalizadorDTO, $objInfraException);
      }
      if ($objLocalizadorDTO->isSetStrComplemento()){
        $this->validarStrComplementoRN0628($objLocalizadorDTO, $objInfraException);
      }
      if ($objLocalizadorDTO->isSetStrStaEstado()){
        $this->validarStrStaEstadoRN0630($objLocalizadorDTO, $objInfraException);
      }
      if ($objLocalizadorDTO->isSetNumSeqLocalizador()){
        $this->validarNumSeqLocalizadorRN0629($objLocalizadorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      $objLocalizadorBD->alterar($objLocalizadorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Localizador.',$e);
    }
  }

  protected function excluirRN0620Controlado($arrObjLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_excluir',__METHOD__,$arrObjLocalizadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      
      $objArquivamentoRN = new ArquivamentoRN();

      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrSiglaTipoLocalizador();
      $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
      $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_ARQUIVADO,ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO),InfraDTO::$OPER_IN);
      $objArquivamentoDTO->setNumMaxRegistrosRetorno(1);
      
      for ($i=0;$i<count($arrObjLocalizadorDTO);$i++){
        $objArquivamentoDTO->setNumIdLocalizador($arrObjLocalizadorDTO[$i]->getNumIdLocalizador());
        $objArquivamentoDTO_Banco = $objArquivamentoRN->consultar($objArquivamentoDTO);
      	if ($objArquivamentoDTO_Banco != null){
      		$objInfraException->adicionarValidacao('Existem protocolos no localizador '.$objArquivamentoDTO_Banco->getStrSiglaTipoLocalizador().'-'.$objArquivamentoDTO_Banco->getNumSeqLocalizadorLocalizador());
      	}      	
      }

      $objInfraException->lancarValidacoes();

      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLocalizadorDTO);$i++){

        $objArquivamentoDTO = new ArquivamentoDTO();
        $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
        $objArquivamentoDTO->retDblIdProtocolo();
        $objArquivamentoDTO->setNumIdLocalizador($arrObjLocalizadorDTO[$i]->getNumIdLocalizador());

        $objArquivamentoRN->excluir($objArquivamentoRN->listar($objArquivamentoDTO));

        $objLocalizadorBD->excluir($arrObjLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Localizador.',$e);
    }
  }

  protected function consultarRN0619Conectado(LocalizadorDTO $objLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_consultar',__METHOD__,$objLocalizadorDTO);
      
      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objLocalizadorDTO->isRetStrIdentificacao()){
        $objLocalizadorDTO->retStrSiglaTipoLocalizador();  
        $objLocalizadorDTO->retNumSeqLocalizador();  
      }
      
      if ($objLocalizadorDTO->isRetStrDescricaoEstado()){
        $objLocalizadorDTO->retStrStaEstado();  
      }
      
      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLocalizadorBD->consultar($objLocalizadorDTO);

      if ($ret !== null){
        
        if ($objLocalizadorDTO->isRetStrIdentificacao()){
          $this->preencherIdentificacaoRN1114($ret);
        }
        
        if ($objLocalizadorDTO->isRetStrDescricaoEstado()){
          $arrObjEstadoLocalizadorDTO = $this->listarEstadosLocalizadorRN0680();
          foreach($arrObjEstadoLocalizadorDTO as $objEstadoLocalizadorDTO){
            if ($ret->getStrStaEstado()==$objEstadoLocalizadorDTO->getStrStaEstado()){
              $ret->setStrDescricaoEstado($objEstadoLocalizadorDTO->getStrDescricao());
              break;
            }
          }
        }
      }
      
      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Localizador.',$e);
    }
  }

  protected function listarRN0622Conectado(LocalizadorDTO $objLocalizadorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_listar',__METHOD__,$objLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objLocalizadorDTO->isRetStrIdentificacao()){
        $objLocalizadorDTO->retStrSiglaTipoLocalizador();  
        $objLocalizadorDTO->retNumSeqLocalizador();  
      }
      
      if ($objLocalizadorDTO->isRetStrDescricaoEstado()){
        $objLocalizadorDTO->retStrStaEstado();  
      }
      
      if ($objLocalizadorDTO->isRetNumQtdProtocolos()){
        $objLocalizadorDTO->retNumIdLocalizador();
      }
      
      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLocalizadorBD->listar($objLocalizadorDTO);

      if (count($ret)>0){
        
        if ($objLocalizadorDTO->isRetStrIdentificacao()){
          foreach($ret as $dto){
            $this->preencherIdentificacaoRN1114($dto);
          }
        }
        
        
        if ($objLocalizadorDTO->isRetStrDescricaoEstado()){
          $arrObjEstadoLocalizadorDTO = $this->listarEstadosLocalizadorRN0680();
          foreach($ret as $dto){
            foreach($arrObjEstadoLocalizadorDTO as $objEstadoLocalizadorDTO){
              if ($dto->getStrStaEstado()==$objEstadoLocalizadorDTO->getStrStaEstado()){
                $dto->setStrDescricaoEstado($objEstadoLocalizadorDTO->getStrDescricao());
                break;
              }
            }
          }
        }
        
        
        if ($objLocalizadorDTO->isRetNumQtdProtocolos()){
          $objArquivamentoRN = new ArquivamentoRN();
          $objArquivamentoDTO = new ArquivamentoDTO();
          $objArquivamentoDTO->setNumTipoFkLocalizador(InfraDTO::$TIPO_FK_OBRIGATORIA);
          $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_ARQUIVADO,ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO),InfraDTO::$OPER_IN);
          $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_NAO_ELIMINADO);

          foreach($ret as $dto){
            $objArquivamentoDTO->setNumIdLocalizador($dto->getNumIdLocalizador());
            $dto->setNumQtdProtocolos($objArquivamentoRN->contar($objArquivamentoDTO));
          }
        }
      }
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Localizadors.',$e);
    }
  }

  protected function contarRN0621Conectado(LocalizadorDTO $objLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_listar',__METHOD__,$objLocalizadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      $ret = $objLocalizadorBD->contar($objLocalizadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Localizadors.',$e);
    }
  }


  protected function desativarControlado($arrObjLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLocalizadorDTO);$i++){
        $objLocalizadorBD->desativar($arrObjLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Localizador.',$e);
    }
  }

  protected function reativarControlado($arrObjLocalizadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('localizador_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objLocalizadorBD = new LocalizadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjLocalizadorDTO);$i++){
        $objLocalizadorBD->reativar($arrObjLocalizadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Localizador.',$e);
    }
  }


  private function validarNumIdUnidadeRN0623(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLocalizadorDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdTipoLocalizadorRN0624(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLocalizadorDTO->getNumIdTipoLocalizador())){
      $objInfraException->adicionarValidacao('Tipo de Localizador não informado.');
    }
  }

  private function validarNumIdTipoSuporteRN0625(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLocalizadorDTO->getNumIdTipoSuporte())){
      $objInfraException->adicionarValidacao('Suporte não informado.');
    }
  }

  private function validarNumIdLugarLocalizadorRN0626(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLocalizadorDTO->getNumIdLugarLocalizador())){
      $objInfraException->adicionarValidacao('Lugar do Localizador não informado.');
    }
  }

  private function validarStrComplementoRN0628(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objLocalizadorDTO->getStrComplemento())){
      $objLocalizadorDTO->setStrComplemento(null);
    }else{

      $objLocalizadorDTO->setStrComplemento(trim($objLocalizadorDTO->getStrComplemento()));

      if (strlen($objLocalizadorDTO->getStrComplemento())>50){
        $objInfraException->adicionarValidacao('Complemento possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrStaEstadoRN0630(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objLocalizadorDTO->getStrStaEstado())){
      $objInfraException->adicionarValidacao('Estado não informado.');
    }else{
      $arr = $this->listarEstadosLocalizadorRN0680();
      foreach($arr as $dto) {
        if ($dto->getStrStaEstado() == $objLocalizadorDTO->getStrStaEstado())
          return;
      }
      
      $objInfraException->adicionarValidacao('Estado do Localizador inválido.');
    }
  }

  private function validarNumSeqLocalizadorRN0629(LocalizadorDTO $objLocalizadorDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objLocalizadorDTO->getNumSeqLocalizador())){
      $objInfraException->adicionarValidacao('Sequência do Localizador não informada.');
    }else{
      if (InfraString::isBolVazia($objLocalizadorDTO->getNumIdTipoLocalizador())){
        $objInfraException->adicionarValidacao('Tipo de Localizador não informado.');
      }else{
        $dtoRN = new LocalizadorRN();
        $dto = new LocalizadorDTO();
        $dto->setNumIdLocalizador($objLocalizadorDTO->getNumIdLocalizador(), InfraDTO::$OPER_DIFERENTE);
        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $dto->setNumIdTipoLocalizador($objLocalizadorDTO->getNumIdTipoLocalizador());
        $dto->setNumSeqLocalizador($objLocalizadorDTO->getNumSeqLocalizador());
        $dto->retNumIdLocalizador();
        $dto = $dtoRN->consultarRN0619($dto);
        
       	if ($dto != null){
     		  $objInfraException->adicionarValidacao('Sequência do Localizador já está sendo utilizada na unidade.');
        }
      }
    }    
  }
  
  public function listarEstadosLocalizadorRN0680(){
  	$arr = array();

  	$objEstadoLocalizadorDTO = new EstadoLocalizadorDTO();
  	$objEstadoLocalizadorDTO->setStrStaEstado(LocalizadorRN::$EA_ABERTO);
  	$objEstadoLocalizadorDTO->setStrDescricao('Aberto');
  	$arr[] = $objEstadoLocalizadorDTO;
  	
  	$objEstadoLocalizadorDTO = new EstadoLocalizadorDTO();
  	$objEstadoLocalizadorDTO->setStrStaEstado(LocalizadorRN::$EA_FECHADO);
  	$objEstadoLocalizadorDTO->setStrDescricao('Fechado');
  	$arr[] = $objEstadoLocalizadorDTO;
  	 	
  	return $arr;  	
  }

  private function preencherIdentificacaoRN1114(LocalizadorDTO $objLocalizadorDTO){
    $objLocalizadorDTO->setStrIdentificacao($objLocalizadorDTO->getStrSiglaTipoLocalizador().'-'.$objLocalizadorDTO->getNumSeqLocalizador());
  }
  
  
}
?>