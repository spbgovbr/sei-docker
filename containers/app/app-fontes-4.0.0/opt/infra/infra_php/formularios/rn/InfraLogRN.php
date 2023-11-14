<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

//require_once 'Infra.php';

class InfraLogRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }

  protected function pesquisarConectado(InfraLogDTO $objInfraLogDTO) {
    try {

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_listar');

      $objInfraException = new InfraException();

      if ($objInfraLogDTO->isSetStrStaTipo()){
        $arrStaTipo = $objInfraLogDTO->getStrStaTipo();

        if (!is_array($arrStaTipo)){
          $arrStaTipo = array($arrStaTipo);
        }

        $objInfraLogDTO->unSetStrStaTipo();

        if (count($arrStaTipo) && count($arrStaTipo) != count(InfraLog::getArrTipos())){
          $objInfraLogDTO->setStrStaTipo($arrStaTipo, InfraDTO::$OPER_IN);
        }
      }

      if ($objInfraLogDTO->isSetDthInicial() || $objInfraLogDTO->isSetDthFinal()){
        
        if (!$objInfraLogDTO->isSetDthInicial()){
          $objInfraException->lancarValidacao('Data/Hora inicial do período de busca não informada.');
        }else{
          if (strlen($objInfraLogDTO->getDthInicial())=='16'){
          	$objInfraLogDTO->setDthInicial($objInfraLogDTO->getDthInicial().':00');
          }
        }
        
        if (!InfraData::validarDataHora($objInfraLogDTO->getDthInicial())){
          $objInfraException->lancarValidacao('Data/Hora inicial do período de busca inválida.');
        }

        if (!$objInfraLogDTO->isSetDthFinal()){
          $objInfraLogDTO->setDthFinal($objInfraLogDTO->getDthInicial());
        }else{
        	
          if (strlen($objInfraLogDTO->getDthFinal())=='16'){
          	$objInfraLogDTO->setDthFinal($objInfraLogDTO->getDthFinal().':59');
          }
        	
	        if (!InfraData::validarDataHora($objInfraLogDTO->getDthFinal())){
	          $objInfraException->lancarValidacao('Data/Hora final do período de busca inválida.');
	        }
        }

        if (InfraData::compararDatas($objInfraLogDTO->getDthInicial(),$objInfraLogDTO->getDthFinal())<0){
          $objInfraException->lancarValidacao('Período de datas/horas inválido.');
        }
        
        if (strlen($objInfraLogDTO->getDthInicial())=='10'){
        	$objInfraLogDTO->setDthInicial($objInfraLogDTO->getDthInicial().' 00:00:00');
        }

        if (strlen($objInfraLogDTO->getDthFinal())=='10'){
        	$objInfraLogDTO->setDthFinal($objInfraLogDTO->getDthFinal().' 23:59:59');
        }
        
  			$objInfraLogDTO->adicionarCriterio(array('Log','Log'),
  			                                  array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
  			                                  array($objInfraLogDTO->getDthInicial(),$objInfraLogDTO->getDthFinal()),
  			                                  InfraDTO::$OPER_LOGICO_AND);  			                                  
      }
      
			
  		if ($objInfraLogDTO->isSetStrTextoLog()){
  		  if (trim($objInfraLogDTO->getStrTextoLog())!=''){
    			$strPalavrasPesquisa = InfraString::transformarCaixaAlta($objInfraLogDTO->getStrTextoLog());
    			$arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);
    
     			for($i=0;$i<count($arrPalavrasPesquisa);$i++){
     			  $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
     			}
     			
    			if (count($arrPalavrasPesquisa)==1){
    				$objInfraLogDTO->setStrTextoLog($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
    			}else{
    			  $objInfraLogDTO->unSetStrTextoLog();
    				$a = array_fill(0,count($arrPalavrasPesquisa),'TextoLog');
    				$b = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
    				$d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
    				$objInfraLogDTO->adicionarCriterio($a,$b,$arrPalavrasPesquisa,$d);
    			}
  		  }			
  		}
  		
			if ($objInfraLogDTO->isSetStrIp()){
			  $objInfraLogDTO->setStrIp('%'.$objInfraLogDTO->getStrIp().'%',InfraDTO::$OPER_LIKE);
			}

      //die($objInfraLogDTO->__toString());

  		return $this->listar($objInfraLogDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando log.',$e);
    }
  }
  
  protected function cadastrarControlado(InfraLogDTO $objInfraLogDTO) {
    try{

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDthLog($objInfraLogDTO, $objInfraException);
      $this->validarStrTextoLog($objInfraLogDTO, $objInfraException);
      $this->validarStrIp($objInfraLogDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      $ret = $objInfraLogBD->cadastrar($objInfraLogDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Log.',$e);
    }
  }

  protected function alterarControlado(InfraLogDTO $objInfraLogDTO){
    try {

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objInfraLogDTO->isSetDthLog()){
        $this->validarDthLog($objInfraLogDTO, $objInfraException);
      }
      if ($objInfraLogDTO->isSetStrTextoLog()){
        $this->validarStrTextoLog($objInfraLogDTO, $objInfraException);
      }
      if ($objInfraLogDTO->isSetStrIp()){
        $this->validarStrIp($objInfraLogDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      $objInfraLogBD->alterar($objInfraLogDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Log.',$e);
    }
  }

  protected function excluirControlado($arrObjInfraLogDTO){
    try {

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraLogDTO);$i++){
        $objInfraLogBD->excluir($arrObjInfraLogDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Log.',$e);
    }
  }

  protected function consultarConectado(InfraLogDTO $objInfraLogDTO){
    try {

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      $ret = $objInfraLogBD->consultar($objInfraLogDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Log.',$e);
    }
  }

  protected function listarConectado(InfraLogDTO $objInfraLogDTO) {
    try {

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      $ret = $objInfraLogBD->listar($objInfraLogDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Logs.',$e);
    }
  }

  protected function contarConectado(InfraLogDTO $objInfraLogDTO){
    try {

      //Valida Permissao
      //SessaoInfra::getInstance()->validarPermissao('infra_log_contar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      $ret = $objInfraLogBD->contar($objInfraLogDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Logs.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjInfraLogDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_log_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraLogDTO);$i++){
        $objInfraLogBD->desativar($arrObjInfraLogDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Log.',$e);
    }
  }

  protected function reativarControlado($arrObjInfraLogDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_log_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraLogBD = new InfraLogBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraLogDTO);$i++){
        $objInfraLogBD->reativar($arrObjInfraLogDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Log.',$e);
    }
  }

 */
  private function validarDthLog(InfraLogDTO $objInfraLogDTO, InfraException $objInfraException){
    if (!InfraData::validarDataHora($objInfraLogDTO->getDthLog())){
      $objInfraException->adicionarValidacao('Data inválida.');
    }
  }

  private function validarStrTextoLog(InfraLogDTO $objInfraLogDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraLogDTO->getStrTextoLog())){
      $objInfraException->adicionarValidacao('Texto não informado.');
    }

    $objInfraLogDTO->setStrTextoLog(trim($objInfraLogDTO->getStrTextoLog()));
  }

  private function validarStrIp(InfraLogDTO $objInfraLogDTO, InfraException $objInfraException){
    if ($objInfraLogDTO->getStrIp()!==null){

      $objInfraLogDTO->setStrIp(trim($objInfraLogDTO->getStrIp()));

      if (strlen($objInfraLogDTO->getStrIp())>39){
        $objInfraException->adicionarValidacao('IP possui tamanho superior a 39 caracteres.');
      }
    }
  }

}
?>