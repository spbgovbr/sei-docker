<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/10/2011 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

//require_once dirname(__FILE__).'/../Infra.php';

class InfraAuditoriaRN extends InfraRN {

  public static $CR_USUARIO = 'USUARIO';
  public static $CR_UNIDADE = 'UNIDADE';
  public static $CR_DATA_HORA = 'DATA_HORA';
  public static $CR_IP_ACESSO = 'IP_ACESSO';
  public static $CR_NAVEGADOR = 'NAVEGADOR';
  public static $CR_SERVIDOR = 'SERVIDOR';
  public static $CR_RECURSO = 'RECURSO';
  public static $CR_REQUISICAO = 'REQUISICAO';
  public static $CR_OPERACAO = 'OPERACAO';
  public static $CR_COMPLEMENTO = 'COMPLEMENTO';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }

  public static function listarCamposRetorno(){
    try {

      $objArrInfraValorStaDTO = array();

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_USUARIO);
      $objInfraValorStaDTO->setStrDescricao('Usuário');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_UNIDADE);
      $objInfraValorStaDTO->setStrDescricao('Unidade');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_DATA_HORA);
      $objInfraValorStaDTO->setStrDescricao('Data/Hora');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_IP_ACESSO);
      $objInfraValorStaDTO->setStrDescricao('IP de Acesso');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_NAVEGADOR);
      $objInfraValorStaDTO->setStrDescricao('Navegador');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_SERVIDOR);
      $objInfraValorStaDTO->setStrDescricao('Servidor');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_RECURSO);
      $objInfraValorStaDTO->setStrDescricao('Recurso');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_COMPLEMENTO);
      $objInfraValorStaDTO->setStrDescricao('Complemento');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_REQUISICAO);
      $objInfraValorStaDTO->setStrDescricao('Requisição');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;

      $objInfraValorStaDTO = new InfraValorStaDTO();
      $objInfraValorStaDTO->setStrStaValor(self::$CR_OPERACAO);
      $objInfraValorStaDTO->setStrDescricao('Operação');
      $objArrInfraValorStaDTO[] = $objInfraValorStaDTO;


      return $objArrInfraValorStaDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de campos de retorno.',$e);
    }
  }


  private function validarStrRecurso(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrRecurso())){
      $objInfraException->adicionarValidacao('Recurso não informado.');
    }else{
      $objInfraAuditoriaDTO->setStrRecurso(trim($objInfraAuditoriaDTO->getStrRecurso()));

      if (strlen($objInfraAuditoriaDTO->getStrRecurso())>50){
        $objInfraException->adicionarValidacao('Recurso possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarDthAcesso(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getDthAcesso())){
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    }else{
      if (!InfraData::validarDataHora($objInfraAuditoriaDTO->getDthAcesso())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

  private function validarStrIp(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrIp())){
      $objInfraAuditoriaDTO->setStrIp(null);
    }else{
      $objInfraAuditoriaDTO->setStrIp(trim($objInfraAuditoriaDTO->getStrIp()));

      if (strlen($objInfraAuditoriaDTO->getStrIp())>39){
        $objInfraException->adicionarValidacao('IP possui tamanho superior a 39 caracteres.');
      }
    }
  }

  private function validarNumIdUsuario(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getNumIdUsuario())){
      $objInfraAuditoriaDTO->setNumIdUsuario(null);
    }
  }

  private function validarStrSiglaUsuario(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrSiglaUsuario())){
      $objInfraAuditoriaDTO->setStrSiglaUsuario(null);
    }else{
      $objInfraAuditoriaDTO->setStrSiglaUsuario(trim($objInfraAuditoriaDTO->getStrSiglaUsuario()));

      if (strlen($objInfraAuditoriaDTO->getStrSiglaUsuario())>100){
        $objInfraException->adicionarValidacao('Sigla do usuário possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrNomeUsuario(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrNomeUsuario())){
      $objInfraAuditoriaDTO->setStrNomeUsuario(null);
    }else{
      $objInfraAuditoriaDTO->setStrNomeUsuario(trim($objInfraAuditoriaDTO->getStrNomeUsuario()));

      if (strlen($objInfraAuditoriaDTO->getStrNomeUsuario())>100){
        $objInfraException->adicionarValidacao('Nome do usuário possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumIdOrgaoUsuario(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getNumIdOrgaoUsuario())){
      $objInfraAuditoriaDTO->setNumIdOrgaoUsuario(null);
    }
  }

  private function validarStrSiglaOrgaoUsuario(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrSiglaOrgaoUsuario())){
      $objInfraAuditoriaDTO->setStrSiglaOrgaoUsuario(null);
    }else{
      $objInfraAuditoriaDTO->setStrSiglaOrgaoUsuario(trim($objInfraAuditoriaDTO->getStrSiglaOrgaoUsuario()));

      if (strlen($objInfraAuditoriaDTO->getStrSiglaOrgaoUsuario())>30){
        $objInfraException->adicionarValidacao('Sigla do órgão do usuário possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarNumIdUsuarioEmulador(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getNumIdUsuarioEmulador())){
      $objInfraAuditoriaDTO->setNumIdUsuarioEmulador(null);
    }
  }

  private function validarStrSiglaUsuarioEmulador(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrSiglaUsuarioEmulador())){
      $objInfraAuditoriaDTO->setStrSiglaUsuarioEmulador(null);
    }else{
      $objInfraAuditoriaDTO->setStrSiglaUsuarioEmulador(trim($objInfraAuditoriaDTO->getStrSiglaUsuarioEmulador()));

      if (strlen($objInfraAuditoriaDTO->getStrSiglaUsuarioEmulador())>100){
        $objInfraException->adicionarValidacao('Sigla do usuário emulador possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrNomeUsuarioEmulador(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrNomeUsuarioEmulador())){
      $objInfraAuditoriaDTO->setStrNomeUsuarioEmulador(null);
    }else{
      $objInfraAuditoriaDTO->setStrNomeUsuarioEmulador(trim($objInfraAuditoriaDTO->getStrNomeUsuarioEmulador()));

      if (strlen($objInfraAuditoriaDTO->getStrNomeUsuarioEmulador())>100){
        $objInfraException->adicionarValidacao('Nome do usuário emulador possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumIdOrgaoUsuarioEmulador(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getNumIdOrgaoUsuarioEmulador())){
      $objInfraAuditoriaDTO->setNumIdOrgaoUsuarioEmulador(null);
    }
  }

  private function validarStrSiglaOrgaoUsuarioEmulador(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrSiglaOrgaoUsuarioEmulador())){
      $objInfraAuditoriaDTO->setStrSiglaOrgaoUsuarioEmulador(null);
    }else{
      $objInfraAuditoriaDTO->setStrSiglaOrgaoUsuarioEmulador(trim($objInfraAuditoriaDTO->getStrSiglaOrgaoUsuarioEmulador()));

      if (strlen($objInfraAuditoriaDTO->getStrSiglaOrgaoUsuarioEmulador())>30){
        $objInfraException->adicionarValidacao('Sigla do órgão do usuário emulador possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarNumIdUnidade(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getNumIdUnidade())){
      $objInfraAuditoriaDTO->setNumIdUnidade(null);
    }
  }

  private function validarStrSiglaUnidade(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrSiglaUnidade())){
      $objInfraAuditoriaDTO->setStrSiglaUnidade(null);
    }else{
      $objInfraAuditoriaDTO->setStrSiglaUnidade(trim($objInfraAuditoriaDTO->getStrSiglaUnidade()));

      if (strlen($objInfraAuditoriaDTO->getStrSiglaUnidade())>30){
        $objInfraException->adicionarValidacao('Sigla da unidade possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricaoUnidade(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrDescricaoUnidade())){
      $objInfraAuditoriaDTO->setStrDescricaoUnidade(null);
    }else{
      $objInfraAuditoriaDTO->setStrDescricaoUnidade(trim($objInfraAuditoriaDTO->getStrDescricaoUnidade()));

      if (strlen($objInfraAuditoriaDTO->getStrDescricaoUnidade())>250){
        $objInfraException->adicionarValidacao('Descrição da unidade possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarNumIdOrgaoUnidade(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getNumIdOrgaoUnidade())){
      $objInfraAuditoriaDTO->setNumIdOrgaoUnidade(null);
    }
  }

  private function validarStrSiglaOrgaoUnidade(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrSiglaOrgaoUnidade())){
      $objInfraAuditoriaDTO->setStrSiglaOrgaoUnidade(null);
    }else{
      $objInfraAuditoriaDTO->setStrSiglaOrgaoUnidade(trim($objInfraAuditoriaDTO->getStrSiglaOrgaoUnidade()));

      if (strlen($objInfraAuditoriaDTO->getStrSiglaOrgaoUnidade())>30){
        $objInfraException->adicionarValidacao('Sigla do órgão da unidade possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrServidor(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrServidor())){
      $objInfraAuditoriaDTO->setStrServidor(null);
    }else{
      $objInfraAuditoriaDTO->setStrServidor(trim($objInfraAuditoriaDTO->getStrServidor()));

      if (strlen($objInfraAuditoriaDTO->getStrServidor())>250){
        $objInfraException->adicionarValidacao('Servidor de acesso possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrUserAgent(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrUserAgent())){
      $objInfraAuditoriaDTO->setStrUserAgent(null);
    }
  }
  
  private function validarStrRequisicao(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrRequisicao())){
      $objInfraAuditoriaDTO->setStrRequisicao(null);
    }else{
      if (strlen($objInfraAuditoriaDTO->getStrRequisicao()) > 1048576){
        $objInfraAuditoriaDTO->setStrRequisicao(substr($objInfraAuditoriaDTO->getStrRequisicao(), 0, 1048576));
      }
    }
  }

  private function validarStrOperacao(InfraAuditoriaDTO $objInfraAuditoriaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraAuditoriaDTO->getStrOperacao())){
      $objInfraAuditoriaDTO->setStrOperacao(null);
    }else{
      if (strlen($objInfraAuditoriaDTO->getStrOperacao()) > 1048576){
        $objInfraAuditoriaDTO->setStrOperacao(substr($objInfraAuditoriaDTO->getStrOperacao(), 0, 1048576));
      }
    }
  }
  
  protected function pesquisarConectado(InfraAuditoriaDTO $objInfraAuditoriaDTO) {
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_listar');

      $objInfraException = new InfraException();
      
      if ($objInfraAuditoriaDTO->isSetDthInicial() || $objInfraAuditoriaDTO->isSetDthFinal()){
        
        if (!$objInfraAuditoriaDTO->isSetDthInicial()){
          $objInfraException->lancarValidacao('Data/Hora inicial do período de busca não informada.');
        }else{
          if (strlen($objInfraAuditoriaDTO->getDthInicial())=='16'){
          	$objInfraAuditoriaDTO->setDthInicial($objInfraAuditoriaDTO->getDthInicial().':00');
          }
        }
        
        if (!InfraData::validarDataHora($objInfraAuditoriaDTO->getDthInicial())){
          $objInfraException->lancarValidacao('Data/Hora inicial do período de busca inválida.');
        }

        if (!$objInfraAuditoriaDTO->isSetDthFinal()){
          $objInfraAuditoriaDTO->setDthFinal($objInfraAuditoriaDTO->getDthInicial());
        }else{
        	
          if (strlen($objInfraAuditoriaDTO->getDthFinal())=='16'){
          	$objInfraAuditoriaDTO->setDthFinal($objInfraAuditoriaDTO->getDthFinal().':59');
          }
        	
	        if (!InfraData::validarDataHora($objInfraAuditoriaDTO->getDthFinal())){
	          $objInfraException->lancarValidacao('Data/Hora final do período de busca inválida.');
	        }
        }

        if (InfraData::compararDatas($objInfraAuditoriaDTO->getDthInicial(),$objInfraAuditoriaDTO->getDthFinal())<0){
          $objInfraException->lancarValidacao('Período de datas/horas inválido.');
        }
        
        if (strlen($objInfraAuditoriaDTO->getDthInicial())=='10'){
        	$objInfraAuditoriaDTO->setDthInicial($objInfraAuditoriaDTO->getDthInicial().' 00:00:00');
        }

        if (strlen($objInfraAuditoriaDTO->getDthFinal())=='10'){
        	$objInfraAuditoriaDTO->setDthFinal($objInfraAuditoriaDTO->getDthFinal().' 23:59:59');
        }
        
  			$objInfraAuditoriaDTO->adicionarCriterio(array('Acesso','Acesso'),
         			                                   array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
          			                                 array($objInfraAuditoriaDTO->getDthInicial(),$objInfraAuditoriaDTO->getDthFinal()),
          			                                 InfraDTO::$OPER_LOGICO_AND);  			                                  
      }

			if ($objInfraAuditoriaDTO->isSetStrSiglaUsuario()){
			  $objInfraAuditoriaDTO->setStrSiglaUsuario('%'.$objInfraAuditoriaDTO->getStrSiglaUsuario().'%',InfraDTO::$OPER_LIKE);
			}

			if ($objInfraAuditoriaDTO->isSetStrNomeUsuario()){
			  $objInfraAuditoriaDTO->setStrNomeUsuario('%'.$objInfraAuditoriaDTO->getStrNomeUsuario().'%',InfraDTO::$OPER_LIKE);
			}

			if ($objInfraAuditoriaDTO->isSetStrSiglaUnidade()){
			  $objInfraAuditoriaDTO->setStrSiglaUnidade('%'.$objInfraAuditoriaDTO->getStrSiglaUnidade().'%',InfraDTO::$OPER_LIKE);
			}

			if ($objInfraAuditoriaDTO->isSetStrDescricaoUnidade()){
			  $objInfraAuditoriaDTO->setStrDescricaoUnidade('%'.$objInfraAuditoriaDTO->getStrDescricaoUnidade().'%',InfraDTO::$OPER_LIKE);
			}
			
			if ($objInfraAuditoriaDTO->isSetStrIp()){
			  $objInfraAuditoriaDTO->setStrIp('%'.$objInfraAuditoriaDTO->getStrIp().'%',InfraDTO::$OPER_LIKE);
			}

			if ($objInfraAuditoriaDTO->isSetStrServidor()){
			  $objInfraAuditoriaDTO->setStrServidor('%'.$objInfraAuditoriaDTO->getStrServidor().'%',InfraDTO::$OPER_LIKE);
			}
			
			if ($objInfraAuditoriaDTO->isSetStrRecurso()){
			  $objInfraAuditoriaDTO->setStrRecurso('%'.$objInfraAuditoriaDTO->getStrRecurso().'%',InfraDTO::$OPER_LIKE);
			}
			
  		if ($objInfraAuditoriaDTO->isSetStrRequisicao()){
  		  if (trim($objInfraAuditoriaDTO->getStrRequisicao())!=''){
    			$strPalavrasPesquisa = InfraString::transformarCaixaAlta($objInfraAuditoriaDTO->getStrRequisicao());
    			$arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);
    
     			for($i=0;$i<count($arrPalavrasPesquisa);$i++){
     			  $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
     			}
     			
    			if (count($arrPalavrasPesquisa)==1){
    				$objInfraAuditoriaDTO->setStrRequisicao($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
    			}else{
    			  $objInfraAuditoriaDTO->unSetStrRequisicao();
    				$a = array_fill(0,count($arrPalavrasPesquisa),'Requisicao');
    				$b = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
    				$d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
    				$objInfraAuditoriaDTO->adicionarCriterio($a,$b,$arrPalavrasPesquisa,$d);
    			}
  		  }			
  		}

  		if ($objInfraAuditoriaDTO->isSetStrOperacao()){
  		  if (trim($objInfraAuditoriaDTO->getStrOperacao())!=''){
    			$strPalavrasPesquisa = InfraString::transformarCaixaAlta($objInfraAuditoriaDTO->getStrOperacao());
    			$arrPalavrasPesquisa = explode(' ',$strPalavrasPesquisa);
    
     			for($i=0;$i<count($arrPalavrasPesquisa);$i++){
     			  $arrPalavrasPesquisa[$i] = '%'.$arrPalavrasPesquisa[$i].'%';
     			}
     			
    			if (count($arrPalavrasPesquisa)==1){
    				$objInfraAuditoriaDTO->setStrOperacao($arrPalavrasPesquisa[0],InfraDTO::$OPER_LIKE);
    			}else{
    			  $objInfraAuditoriaDTO->unSetStrOperacao();
    				$a = array_fill(0,count($arrPalavrasPesquisa),'Operacao');
    				$b = array_fill(0,count($arrPalavrasPesquisa),InfraDTO::$OPER_LIKE);
    				$d = array_fill(0,count($arrPalavrasPesquisa)-1,InfraDTO::$OPER_LOGICO_AND);
    				$objInfraAuditoriaDTO->adicionarCriterio($a,$b,$arrPalavrasPesquisa,$d);
    			}
  		  }			
  		}
  		
  		$ret = $this->listar($objInfraAuditoriaDTO);
  		
  		//die($objInfraAuditoriaDTO->__toString());
  		
  		return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando auditoria.',$e);
    }
  }
  
  
  protected function cadastrarControlado(InfraAuditoriaDTO $objInfraAuditoriaDTO) {
    try{

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_cadastrar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrRecurso($objInfraAuditoriaDTO, $objInfraException);
      $this->validarDthAcesso($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrIp($objInfraAuditoriaDTO, $objInfraException);
      
      $this->validarNumIdUsuario($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrSiglaUsuario($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrNomeUsuario($objInfraAuditoriaDTO, $objInfraException);
      $this->validarNumIdOrgaoUsuario($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrSiglaOrgaoUsuario($objInfraAuditoriaDTO, $objInfraException);
      
      
      $this->validarNumIdUsuarioEmulador($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrSiglaUsuarioEmulador($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrNomeUsuarioEmulador($objInfraAuditoriaDTO, $objInfraException);
      $this->validarNumIdOrgaoUsuarioEmulador($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrSiglaOrgaoUsuarioEmulador($objInfraAuditoriaDTO, $objInfraException);
      
      
      $this->validarNumIdUnidade($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrSiglaUnidade($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrDescricaoUnidade($objInfraAuditoriaDTO, $objInfraException);
      $this->validarNumIdOrgaoUnidade($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrSiglaOrgaoUnidade($objInfraAuditoriaDTO, $objInfraException);
      
      
      $this->validarStrServidor($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrUserAgent($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrRequisicao($objInfraAuditoriaDTO, $objInfraException);
      $this->validarStrOperacao($objInfraAuditoriaDTO, $objInfraException);
      
      

      $objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraAuditoriaBD->cadastrar($objInfraAuditoriaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Dado de Auditoria.',$e);
    }
  }

  /*
  protected function alterarControlado(InfraAuditoriaDTO $objInfraAuditoriaDTO){
    try {

      //Valida Permissao
  	   SessaoInfra::getInstance()->validarPermissao('infra_auditoria_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objInfraAuditoriaDTO->isSetStrRecurso()){
        $this->validarStrRecurso($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetDthAcesso()){
        $this->validarDthAcesso($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetStrIp()){
        $this->validarStrIp($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetNumIdUsuarioEmulador()){
        $this->validarNumIdUsuarioEmulador($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetStrServidor()){
        $this->validarStrServidor($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetStrUserAgent()){
        $this->validarStrUserAgent($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetStrRequisicao()){
        $this->validarStrRequisicao($objInfraAuditoriaDTO, $objInfraException);
      }
      if ($objInfraAuditoriaDTO->isSetStrOperacao()){
        $this->validarStrOperacao($objInfraAuditoriaDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      $objInfraAuditoriaBD->alterar($objInfraAuditoriaDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Dado de Auditoria.',$e);
    }
  }
  */

  /*
  protected function excluirControlado($arrObjInfraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_excluir');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraAuditoriaDTO);$i++){
        $objInfraAuditoriaBD->excluir($arrObjInfraAuditoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Dado de Auditoria.',$e);
    }
  }
  */
  
  protected function consultarConectado(InfraAuditoriaDTO $objInfraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraAuditoriaBD->consultar($objInfraAuditoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Dado de Auditoria.',$e);
    }
  }

  protected function listarConectado(InfraAuditoriaDTO $objInfraAuditoriaDTO) {
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraAuditoriaBD->listar($objInfraAuditoriaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Dados de Auditoria.',$e);
    }
  }

  protected function contarConectado(InfraAuditoriaDTO $objInfraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_listar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraAuditoriaBD->contar($objInfraAuditoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Dados de Auditoria.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjInfraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraAuditoriaDTO);$i++){
        $objInfraAuditoriaBD->desativar($arrObjInfraAuditoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Dado de Auditoria.',$e);
    }
  }

  protected function reativarControlado($arrObjInfraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInfraAuditoriaDTO);$i++){
        $objInfraAuditoriaBD->reativar($arrObjInfraAuditoriaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Dado de Auditoria.',$e);
    }
  }

  protected function bloquearControlado(InfraAuditoriaDTO $objInfraAuditoriaDTO){
    try {

      //Valida Permissao
      SessaoInfra::getInstance()->validarPermissao('infra_auditoria_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInfraAuditoriaBD = new InfraAuditoriaBD($this->getObjInfraIBanco());
      $ret = $objInfraAuditoriaBD->bloquear($objInfraAuditoriaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Dado de Auditoria.',$e);
    }
  }

 */
}
?>