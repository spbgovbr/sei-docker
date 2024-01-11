<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoRN extends InfraRN {

  public static $TA_DATA = 'DATA';
  public static $TA_NUMERO_INTEIRO = 'NUMERO_INTEIRO';
  public static $TA_NUMERO_DECIMAL = 'NUMERO_DECIMAL';
  public static $TA_TEXTO_SIMPLES = 'TEXTO_SIMPLES';
  public static $TA_TEXTO_MASCARA = 'TEXTO_MASCARA';
  public static $TA_TEXTO_GRANDE = 'TEXTO_GRANDE';
  public static $TA_LISTA = 'LISTA';
  public static $TA_DINHEIRO = 'DINHEIRO';
  public static $TA_OPCOES = 'OPCOES';
  public static $TA_SINALIZADOR = 'SINALIZADOR';
  public static $TA_INFORMACAO = 'INFORMACAO';


  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0113Controlado(AtributoDTO $objAtributoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_cadastrar',__METHOD__,$objAtributoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeRN0576($objAtributoDTO, $objInfraException);
      $this->validarStrRotulo($objAtributoDTO, $objInfraException);
      $this->validarStrStaTipoRN0578($objAtributoDTO, $objInfraException);
      $this->validarStrSinObrigatorioRN0579($objAtributoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0580($objAtributoDTO, $objInfraException);
      $this->validarStrValorMinimo($objAtributoDTO, $objInfraException);
      $this->validarStrValorMaximo($objAtributoDTO, $objInfraException);
      $this->validarStrValorPadrao($objAtributoDTO, $objInfraException);
      $this->validarNumTamanho($objAtributoDTO, $objInfraException);
      $this->validarNumDecimais($objAtributoDTO, $objInfraException);
      $this->validarNumLinhas($objAtributoDTO, $objInfraException);
      $this->validarStrMascara($objAtributoDTO, $objInfraException);
      $this->validarNumOrdem($objAtributoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      $ret = $objAtributoBD->cadastrar($objAtributoDTO);

      $objDominioRN = new DominioRN();
      $arrObjDominioDTO = $objAtributoDTO->getArrObjDominioDTO();
      foreach($arrObjDominioDTO as $objDominioDTO){
        $objDominioDTO->setNumIdAtributo($ret->getNumIdAtributo());
        $objDominioRN->cadastrarRN0581($objDominioDTO);
      }      

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Campo.',$e);
    }
  }

  protected function alterarRN0114Controlado(AtributoDTO $objAtributoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('atributo_alterar',__METHOD__,$objAtributoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAtributoDTOBanco = new AtributoDTO();
      $objAtributoDTOBanco->retTodos();
      $objAtributoDTOBanco->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
      $objAtributoDTOBanco = $this->consultarRN0115($objAtributoDTOBanco);

      if ($objAtributoDTO->isSetNumIdTipoFormulario()){
        if ($objAtributoDTO->getNumIdTipoFormulario()!=$objAtributoDTOBanco->getNumIdTipoFormulario()){
          $objInfraException->lancarValidacao('Não é possível alterar o tipo de formulário associado com o campo.');
        }
      }else{
        $objAtributoDTO->setNumIdTipoFormulario($objAtributoDTOBanco->getNumIdTipoFormulario());
      }

      if ($objAtributoDTO->isSetStrNome()){
        $this->validarStrNomeRN0576($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetStrRotulo()){

        $this->validarStrRotulo($objAtributoDTO, $objInfraException);

        if ($objAtributoDTO->getStrRotulo()!=$objAtributoDTOBanco->getStrRotulo()) {
          $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
          $objRelProtocoloAtributoDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
          $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
          if($objRelProtocoloAtributoRN->contar($objRelProtocoloAtributoDTO)>0){
            $objInfraException->adicionarValidacao('Não é possível alterar o rótulo porque existem protocolos utilizando este campo.');
          }
        }
      }

      if ($objAtributoDTO->isSetStrStaTipo()){
        if ($objAtributoDTO->getStrStaTipo()!=$objAtributoDTOBanco->getStrStaTipo()) {

          $this->validarStrStaTipoRN0578($objAtributoDTO, $objInfraException);

          $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
          $objRelProtocoloAtributoDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());

          $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
          if($objRelProtocoloAtributoRN->contar($objRelProtocoloAtributoDTO)>0) {
            $objInfraException->adicionarValidacao('Não é possível alterar o tipo porque existem protocolos utilizando este campo.');
          }

          if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_LISTA &&
              $objAtributoDTO->getStrStaTipo()!=AtributoRN::$TA_OPCOES){

            if ($objAtributoDTO->isSetArrObjDominioDTO() && InfraArray::contar($objAtributoDTO->getArrObjDominioDTO())){

              $objInfraException->adicionarValidacao('Tipo do campo não aceita valores.');

            }else {

              $objDominioDTO = new DominioDTO();
              $objDominioDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());

              $objDominioRN = new DominioRN();
              if ($objDominioRN->contarRN0584($objDominioDTO) && (!$objAtributoDTO->isSetArrObjDominioDTO() || InfraArray::contar($objAtributoDTO->getArrObjDominioDTO()))) {
                $objInfraException->adicionarValidacao('Campo possui valores associados.');
              }
            }
          }
        }
      }

      if ($objAtributoDTO->isSetStrSinObrigatorio()){
        $this->validarStrSinObrigatorioRN0579($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivoRN0580($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetStrValorMinimo()) {
        $this->validarStrValorMinimo($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetStrValorMaximo()) {
        $this->validarStrValorMaximo($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetStrValorPadrao()) {
        $this->validarStrValorPadrao($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetNumTamanho()) {
        $this->validarNumTamanho($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetNumDecimais()) {
        $this->validarNumDecimais($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetNumLinhas()) {
        $this->validarNumLinhas($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetStrMascara()) {
        $this->validarStrMascara($objAtributoDTO, $objInfraException);
      }
      if ($objAtributoDTO->isSetNumOrdem()){
        $this->validarNumOrdem($objAtributoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();
      
      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      $objAtributoBD->alterar($objAtributoDTO);

      //tratar valores posteriormente a alteracao pois o tipo é utilizado nas validacoes
      if ($objAtributoDTO->isSetArrObjDominioDTO()){

        $objDominioDTO = new DominioDTO();
        $objDominioDTO->setBolExclusaoLogica(false);
        $objDominioDTO->retNumIdDominio();
        $objDominioDTO->retStrValor();
        $objDominioDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());

        $objDominioRN = new DominioRN();
        $arrObjDominioDTOAntigos = $objDominioRN->listarRN0199($objDominioDTO);
        $arrObjDominioDTONovos = $objAtributoDTO->getArrObjDominioDTO();

        $arrRemocao = array();
        foreach ($arrObjDominioDTOAntigos as $objDominioDTOAntigo) {
          $flagRemover = true;
          foreach ($arrObjDominioDTONovos as $objDominioDTONovo) {
            if ($objDominioDTOAntigo->getNumIdDominio() == $objDominioDTONovo->getNumIdDominio()) {
              $flagRemover = false;
              break;
            }
          }
          if ($flagRemover) {
            $arrRemocao[] = $objDominioDTOAntigo;
          }
        }

        $objDominioRN->excluirRN0595($arrRemocao);

        foreach ($arrObjDominioDTONovos as $objDominioDTONovo) {
          $flagCadastrar = true;
          $objDominioDTOAntigo = null;
          foreach ($arrObjDominioDTOAntigos as $objDominioDTOAntigo) {
            if ($objDominioDTONovo->getNumIdDominio() == $objDominioDTOAntigo->getNumIdDominio()) {
              $flagCadastrar = false;
              break;
            }
          }

          if ($flagCadastrar) {
            $objDominioDTONovo->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
            $objDominioRN->cadastrarRN0581($objDominioDTONovo);
          } else {
            $objDominioRN->alterarRN0582($objDominioDTONovo);
          }
        }

        $objDominioDTO = new DominioDTO();
        $objDominioDTO->setBolExclusaoLogica(false);
        $objDominioDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
        $objDominioDTO->setStrSinPadrao('S');

        if ($objDominioRN->contarRN0584($objDominioDTO) > 1) {
          $objInfraException->lancarValidacao('Mais de um valor sinalizado como padrão.');
        }
      }


      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Campo.',$e);
    }
  }

  protected function excluirRN0111Controlado($arrObjAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_excluir',__METHOD__,$arrObjAtributoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraArray::contar($arrObjAtributoDTO)) {
        $dto = new AtributoDTO();
        $dto->setBolExclusaoLogica(false);
        $dto->retNumIdAtributo();
        $dto->retStrNome();
        $dto->setNumIdAtributo(InfraArray::converterArrInfraDTO($arrObjAtributoDTO, 'IdAtributo'), InfraDTO::$OPER_IN);
        $arrMap = InfraArray::mapearArrInfraDTO($this->listarRN0165($dto), 'IdAtributo', 'Nome');

        $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();

        foreach ($arrObjAtributoDTO as $objAtributoDTO) {
          $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
          $objRelProtocoloAtributoDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
          if ($objRelProtocoloAtributoRN->contar($objRelProtocoloAtributoDTO) > 0) {
            $objInfraException->adicionarValidacao('Existem protocolos utilizando o campo "' . $arrMap[$objAtributoDTO->getNumIdAtributo()] . '".');
          }
        }

        $objInfraException->lancarValidacoes();

        $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjAtributoDTO); $i++) {

          $objDominioDTO = new DominioDTO();
          $objDominioDTO->setBolExclusaoLogica(false);
          $objDominioDTO->retNumIdDominio();
          $objDominioDTO->setNumIdAtributo($arrObjAtributoDTO[$i]->getNumIdAtributo());

          $objDominioRN = new DominioRN();
          $objDominioRN->excluirRN0595($objDominioRN->listarRN0199($objDominioDTO));

          $objAtributoBD->excluir($arrObjAtributoDTO[$i]);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Campo.',$e);
    }
  }

  protected function consultarRN0115Conectado(AtributoDTO $objAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_consultar',__METHOD__,$objAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      $ret = $objAtributoBD->consultar($objAtributoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Campo.',$e);
    }
  }

  protected function listarRN0165Conectado(AtributoDTO $objAtributoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_listar',__METHOD__,$objAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      $ret = $objAtributoBD->listar($objAtributoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Campos.',$e);
    }
  }

  protected function contarRN0119Conectado(AtributoDTO $objAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_listar',__METHOD__,$objAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      $ret = $objAtributoBD->contar($objAtributoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Campos.',$e);
    }
  }

  protected function desativarRN0574Controlado($arrObjAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_desativar',__METHOD__,$arrObjAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoDTO);$i++){
        $objAtributoBD->desativar($arrObjAtributoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Campo.',$e);
    }
  }

  protected function reativarRN0575Controlado($arrObjAtributoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_reativar',__METHOD__,$arrObjAtributoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoBD = new AtributoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoDTO);$i++){
        $objAtributoBD->reativar($arrObjAtributoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Campo.',$e);
    }
  }

  private function validarStrNomeRN0576(AtributoDTO $objAtributoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{

      $objAtributoDTO->setStrNome(trim($objAtributoDTO->getStrNome()));
  
      if (strlen($objAtributoDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
    	$dto = new AtributoDTO();
      $dto->setBolExclusaoLogica(false);
    	$dto->retStrSinAtivo();
  		$dto->setNumIdAtributo($objAtributoDTO->getNumIdAtributo(),InfraDTO::$OPER_DIFERENTE);
    	$dto->setNumIdTipoFormulario($objAtributoDTO->getNumIdTipoFormulario());
    	$dto->setStrNome($objAtributoDTO->getStrNome());

    	$dto = $this->consultarRN0115($dto);
    	if ($dto!==null){
    		if ($dto->getStrSinAtivo()=='S'){
    			$objInfraException->adicionarValidacao('Existe outro campo que utiliza o mesmo nome.');
    		}else{
    			$objInfraException->adicionarValidacao('Existe campo inativo que utiliza o mesmo nome.');
    		}
    	}
    }
  }

  private function validarStrRotulo(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getStrRotulo())){
      $objInfraException->adicionarValidacao('Rótulo não informado.');
    }else{

      //não retirar espaços
      //$objAtributoDTO->setStrRotulo(trim($objAtributoDTO->getStrRotulo()));

      if (strlen($objAtributoDTO->getStrRotulo())>4000){
        $objInfraException->adicionarValidacao('Rótulo possui tamanho superior a 4000 caracteres.');
      }

      $dto = new AtributoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();
      $dto->setNumIdAtributo($objAtributoDTO->getNumIdAtributo(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdTipoFormulario($objAtributoDTO->getNumIdTipoFormulario());
      $dto->setStrRotulo($objAtributoDTO->getStrRotulo());

      $dto = $this->consultarRN0115($dto);
      if ($dto!==null){
        if ($dto->getStrSinAtivo()=='S'){
          $objInfraException->adicionarValidacao('Existe outro campo que utiliza o mesmo rótulo.');
        }else{
          $objInfraException->adicionarValidacao('Existe campo inativo que utiliza o mesmo rótulo.');
        }
      }
    }
  }

  private function validarStrStaTipoRN0578(AtributoDTO $objAtributoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo do campo não informado.');
    }else{
      $objAtributoDTO->setStrStaTipo(trim($objAtributoDTO->getStrStaTipo()));

      if (strlen($objAtributoDTO->getStrStaTipo())>20){
        $objInfraException->adicionarValidacao('Tipo do campo possui tamanho superior a 20 caracteres.');
      }

      $arr = $this->tiposAtributoRN0591();
			$arr = InfraArray::converterArrInfraDTO($arr,'StaTipo');
			
			if (!in_array($objAtributoDTO->getStrStaTipo(),$arr)){
			    $objInfraException->adicionarValidacao('Tipo do campo inválido.');
			}     	
    }
  }

  private function validarStrSinObrigatorioRN0579(AtributoDTO $objAtributoDTO, InfraException $objInfraException){  	
    
    if (InfraString::isBolVazia($objAtributoDTO->getStrSinObrigatorio())){
    		$objInfraException->adicionarValidacao('Sinalizador de obrigatoriedade não informado.');
  	}else{
      if (!InfraUtil::isBolSinalizadorValido($objAtributoDTO->getStrSinObrigatorio())){
        $objInfraException->adicionarValidacao('Sinalizador de obrigatoriedade inválido.');
      }
  	}    
  }

  private function validarStrSinAtivoRN0580(AtributoDTO $objAtributoDTO, InfraException $objInfraException){
    
    if (InfraString::isBolVazia($objAtributoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAtributoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarStrValorMinimo(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getStrValorMinimo())){
      $objAtributoDTO->setStrValorMinimo(null);
    }else{

      if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_DATA &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_DINHEIRO &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_NUMERO_INTEIRO &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_NUMERO_DECIMAL){

       $objInfraException->adicionarValidacao('Tipo do campo não permite Valor Mínimo.');

      }else {

        $objAtributoDTO->setStrValorMinimo(trim($objAtributoDTO->getStrValorMinimo()));

        if (strlen($objAtributoDTO->getStrValorMinimo()) > 20) {
          $objInfraException->adicionarValidacao('Valor Mínimo possui tamanho superior a 20 caracteres.');
        }
      }
    }
  }

  private function validarStrValorMaximo(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getStrValorMaximo())){
      $objAtributoDTO->setStrValorMaximo(null);
    }else{

      if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_DATA &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_DINHEIRO &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_NUMERO_INTEIRO &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_NUMERO_DECIMAL){

        $objInfraException->adicionarValidacao('Tipo do campo não permite Valor Máximo.');

      }else {

        $objAtributoDTO->setStrValorMaximo(trim($objAtributoDTO->getStrValorMaximo()));

        if (strlen($objAtributoDTO->getStrValorMaximo()) > 20) {
          $objInfraException->adicionarValidacao('Valor Máximo possui tamanho superior a 20 caracteres.');
        }
      }
    }
  }

  private function validarStrValorPadrao(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getStrValorPadrao())){
      $objAtributoDTO->setStrValorPadrao(null);
    }else{

      if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_SINALIZADOR){

        $objInfraException->adicionarValidacao('Tipo do campo não permite Valor Padrão.');

      }else {

        $objAtributoDTO->setStrValorPadrao(trim($objAtributoDTO->getStrValorPadrao()));

        if (strlen($objAtributoDTO->getStrValorPadrao()) > 4000) {
          $objInfraException->adicionarValidacao('Valor Padrão possui tamanho superior a 4000 caracteres.');
        }else {

          if ($objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_SINALIZADOR) {
            if (!InfraUtil::isBolSinalizadorValido($objAtributoDTO->getStrValorPadrao())) {
              $objInfraException->adicionarValidacao('Valor Padrão inválido para o campo.');
            }
          }
        }
      }
    }
  }

  private function validarNumTamanho(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getNumTamanho())){
      if ($objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_NUMERO_INTEIRO ||
          $objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_NUMERO_DECIMAL ||
          $objAtributoDTO->getStrStaTipo()== AtributoRN::$TA_TEXTO_SIMPLES ||
          $objAtributoDTO->getStrStaTipo()== AtributoRN::$TA_TEXTO_GRANDE){

        $objInfraException->adicionarValidacao('Tamanho não informado.');

      }else{
        $objAtributoDTO->setNumTamanho(null);
      }

    }else {

      if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_NUMERO_INTEIRO &&
          $objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_NUMERO_DECIMAL &&
          $objAtributoDTO->getStrStaTipo()!= AtributoRN::$TA_TEXTO_SIMPLES &&
          $objAtributoDTO->getStrStaTipo()!= AtributoRN::$TA_TEXTO_GRANDE) {

        $objInfraException->adicionarValidacao('Tipo do campo não permite Tamanho.');

      }else{

        if ($objAtributoDTO->getNumTamanho() < 1) {
          $objInfraException->adicionarValidacao('Tamanho não pode ser inferior a 1.');
        }

        if (($objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_NUMERO_INTEIRO || $objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_NUMERO_DECIMAL)
            && $objAtributoDTO->getNumTamanho() > 19
        ) {
          //9223372036854775807 limitar ao tamanho do bigint mesmo para decimais
          $objInfraException->adicionarValidacao('Tamanho não pode ser superior a 19.');
        } else if ($objAtributoDTO->getNumTamanho() > 4000) {
          $objInfraException->adicionarValidacao('Tamanho não pode ser superior a 4000.');
        }
      }
    }
  }

  private function validarNumDecimais(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getNumDecimais())){
      if ($objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_NUMERO_DECIMAL) {
        $objInfraException->adicionarValidacao('Decimais não informado.');
      }else{
        $objAtributoDTO->setNumDecimais(null);
      }
    }else{

      if ($objAtributoDTO->getStrStaTipo()!=AtributoRN::$TA_NUMERO_DECIMAL){

        $objInfraException->adicionarValidacao('Tipo do campo não permite Decimais.');

      }else {

        if ($objAtributoDTO->getNumDecimais() < 1) {
          $objInfraException->adicionarValidacao('Decimais não pode ser inferior a 1.');
        }

        if ($objAtributoDTO->getNumDecimais() > 18) {
          $objInfraException->adicionarValidacao('Decimais não pode ser superior a 18.');
        }
      }
    }
  }

  private function validarNumLinhas(AtributoDTO $objAtributoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoDTO->getNumLinhas())){
      if ($objAtributoDTO->getStrStaTipo()== AtributoRN::$TA_TEXTO_GRANDE){
        $objInfraException->adicionarValidacao('Número de Linhas não informado.');
      }else {
        $objAtributoDTO->setNumLinhas(null);
      }
    }else {

      if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_TEXTO_GRANDE) {

        $objInfraException->adicionarValidacao('Tipo campo não permite Número de Linhas.');

      }else{

        if ($objAtributoDTO->getNumLinhas() < 1) {
          $objInfraException->adicionarValidacao('Número de Linhas não pode ser inferior a 1.');
        }

        if ($objAtributoDTO->getNumLinhas() > 100) {
          $objInfraException->adicionarValidacao('Número de Linhas não pode ser superior a 100.');
        }
      }
    }
  }

  private function validarStrMascara(AtributoDTO $objAtributoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objAtributoDTO->getStrMascara())){
      if ($objAtributoDTO->getStrStaTipo() == AtributoRN::$TA_TEXTO_MASCARA){
        $objInfraException->adicionarValidacao('Máscara não informada.');
      }else{
        $objAtributoDTO->setStrMascara(null);
      }
    }else {

      if ($objAtributoDTO->getStrStaTipo() != AtributoRN::$TA_TEXTO_MASCARA) {

        $objInfraException->adicionarValidacao('Tipo do campo não permite Máscara.');

      }else{

        $objAtributoDTO->setStrMascara(trim($objAtributoDTO->getStrMascara()));

        if (strlen($objAtributoDTO->getStrMascara()) > 50) {
          $objInfraException->adicionarValidacao('Máscara possui tamanho superior a 50 caracteres.');
        }
      }
    }
  }

  private function validarNumOrdem(AtributoDTO $objAtributoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoDTO->getNumOrdem())){
      $objInfraException->adicionarValidacao('Ordem não informada.');
    }
  }

  public function tiposAtributoRN0591(){
  	$objArrTipoDTO = array();

  	$objTipoDTO = new TipoDTO();  	
  	$objTipoDTO->setStrStaTipo(AtributoRN::$TA_DATA);
  	$objTipoDTO->setStrDescricao('Data');  	
  	$objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_DINHEIRO);
    $objTipoDTO->setStrDescricao('Dinheiro');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_LISTA);
    $objTipoDTO->setStrDescricao('Lista');
    $objArrTipoDTO[] = $objTipoDTO;

  	$objTipoDTO = new TipoDTO();  	
  	$objTipoDTO->setStrStaTipo(AtributoRN::$TA_NUMERO_INTEIRO);
  	$objTipoDTO->setStrDescricao('Número Inteiro');
  	$objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_NUMERO_DECIMAL);
    $objTipoDTO->setStrDescricao('Número com Decimais');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_TEXTO_GRANDE);
    $objTipoDTO->setStrDescricao('Texto Grande');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_TEXTO_SIMPLES);
    $objTipoDTO->setStrDescricao('Texto Simples');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_TEXTO_MASCARA);
    $objTipoDTO->setStrDescricao('Texto com Máscara');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_OPCOES);
    $objTipoDTO->setStrDescricao('Opções');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_SINALIZADOR);
    $objTipoDTO->setStrDescricao('Sinalizador');
    $objArrTipoDTO[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(AtributoRN::$TA_INFORMACAO);
    $objTipoDTO->setStrDescricao('Informação');
    $objArrTipoDTO[] = $objTipoDTO;

  	return $objArrTipoDTO;
  }
}
?>