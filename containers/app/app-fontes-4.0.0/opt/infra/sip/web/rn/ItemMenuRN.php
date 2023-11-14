<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2007 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class ItemMenuRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function cadastrarControlado(ItemMenuDTO $objItemMenuDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('item_menu_cadastrar',__METHOD__,$objItemMenuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdMenu($objItemMenuDTO, $objInfraException);
      $this->validarNumIdSistema($objItemMenuDTO, $objInfraException);
      $this->validarNumIdMenuPai($objItemMenuDTO, $objInfraException);
      $this->validarNumIdItemMenuPai($objItemMenuDTO, $objInfraException);
      $this->validarNumIdRecurso($objItemMenuDTO, $objInfraException);
      $this->validarStrRotulo($objItemMenuDTO, $objInfraException);
			$this->validarStrDescricao($objItemMenuDTO, $objInfraException);
      $this->validarStrIcone($objItemMenuDTO, $objInfraException);
      $this->validarNumSequencia($objItemMenuDTO, $objInfraException);
      $this->validarStrSinNovaJanela($objItemMenuDTO, $objInfraException);
      $this->validarStrSinAtivo($objItemMenuDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      $ret = $objItemMenuBD->cadastrar($objItemMenuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Item de Menu.',$e);
    }
  }

  protected function alterarControlado(ItemMenuDTO $objItemMenuDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('item_menu_alterar',__METHOD__,$objItemMenuDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objItemMenuDTO->isSetNumIdSistema()){
        $this->validarNumIdSistema($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetNumIdMenuPai()){
        $this->validarNumIdMenuPai($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetNumIdItemMenuPai()){
        $this->validarNumIdItemMenuPai($objItemMenuDTO, $objInfraException);
        $this->validarItemPai($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetNumIdRecurso()){
        $this->validarNumIdRecurso($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetStrRotulo()){
        $this->validarStrRotulo($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetStrDescricao()){
			  $this->validarStrDescricao($objItemMenuDTO, $objInfraException);
      }

      if ($objItemMenuDTO->isSetStrIcone()) {
        $this->validarStrIcone($objItemMenuDTO, $objInfraException);
      }

      if ($objItemMenuDTO->isSetNumSequencia()){
        $this->validarNumSequencia($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetStrSinNovaJanela()){
        $this->validarStrSinNovaJanela($objItemMenuDTO, $objInfraException);
      }
      
      if ($objItemMenuDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objItemMenuDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();
      
      $dto = new ItemMenuDTO();
      $dto->retNumIdSistema();
      $dto->retNumIdRecurso();
      $dto->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
      $dto->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());
      
      $dto = $this->consultar($dto);
      
      if (($objItemMenuDTO->isSetNumIdSistema() && $objItemMenuDTO->getNumIdSistema() != $dto->getNumIdSistema()) || 
          ($objItemMenuDTO->isSetNumIdRecurso() && $objItemMenuDTO->getNumIdRecurso() != $dto->getNumIdRecurso())){
        
        $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
        $objRelPerfilItemMenuDTO->retTodos();
        $objRelPerfilItemMenuDTO->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
        $objRelPerfilItemMenuDTO->setNumIdItemMenu($objItemMenuDTO->getNumIdItemMenu());
        $objRelPerfilItemMenuDTO->setNumIdSistema($dto->getNumIdSistema());
        $objRelPerfilItemMenuDTO->setNumIdRecurso($dto->getNumIdRecurso());
        
        $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
        $objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
      }

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      $objItemMenuBD->alterar($objItemMenuDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Item de Menu.',$e);
    }
  }

  protected function excluirControlado($arrObjItemMenuDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('item_menu_excluir',__METHOD__,$arrObjItemMenuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjItemMenuDTO);$i++){
				
				//Exclui o item de todos os perfis
				$objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
				$objRelPerfilItemMenuDTO->retTodos();
				$objRelPerfilItemMenuDTO->setNumIdMenu($arrObjItemMenuDTO[$i]->getNumIdMenu());
				$objRelPerfilItemMenuDTO->setNumIdItemMenu($arrObjItemMenuDTO[$i]->getNumIdItemMenu());
				$objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
				$objRelPerfilItemMenuRN->excluir($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO));
				
				//Exclui itens de menu inferiores
				$objItemMenuDTO = new ItemMenuDTO();
				$objItemMenuDTO->retNumIdMenu();
				$objItemMenuDTO->retNumIdItemMenu();
				$objItemMenuDTO->setNumIdMenuPai($arrObjItemMenuDTO[$i]->getNumIdMenu());
				$objItemMenuDTO->setNumIdItemMenuPai($arrObjItemMenuDTO[$i]->getNumIdItemMenu());
				$objItemMenuDTO->setBolExclusaoLogica(false);
				$this->excluir($this->listar($objItemMenuDTO));
				
				//Exclui o item de menu
        $objItemMenuBD->excluir($arrObjItemMenuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Item de Menu.',$e);
    }
  }

  protected function desativarControlado($arrObjItemMenuDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('item_menu_desativar',__METHOD__,$arrObjItemMenuDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjItemMenuDTO);$i++){
        $objItemMenuBD->desativar($arrObjItemMenuDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Item de Menu.',$e);
    }
  }

  protected function consultarConectado(ItemMenuDTO $objItemMenuDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('item_menu_consultar',__METHOD__,$objItemMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      $ret = $objItemMenuBD->consultar($objItemMenuDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Item de Menu.',$e);
    }
  }

  protected function listarConectado(ItemMenuDTO $objItemMenuDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('item_menu_listar',__METHOD__,$objItemMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      $ret = $objItemMenuBD->listar($objItemMenuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Itens de Menu.',$e);
    }
  }

  protected function contarConectado(ItemMenuDTO $objItemMenuDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('item_menu_listar',__METHOD__,$objItemMenuDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
      $ret = $objItemMenuBD->contar($objItemMenuDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando Itens de Menu.',$e);
    }
  }

  private function validarItemPai($parObjItemMenuDTO, $objInfraException){
    
    if ($parObjItemMenuDTO->getNumIdItemMenuPai()!=null){
      
      $objItemMenuDTO = new ItemMenuDTO();
      $objItemMenuDTO->setNumIdMenu($parObjItemMenuDTO->getNumIdMenu());
      $objItemMenuDTO->setBolExclusaoLogica(false);
      $arrHierarquia = $this->listarHierarquia($objItemMenuDTO);
      
      foreach($arrHierarquia as $objItemMenuDTONovoPai){
        if ($objItemMenuDTONovoPai->getNumIdItemMenu()==$parObjItemMenuDTO->getNumIdItemMenuPai()){
          $arrPais = $objItemMenuDTONovoPai->getArrPais();
          
          foreach($arrPais as $objItemMenuDTOPaiNovoPai){
            if ($objItemMenuDTOPaiNovoPai->getNumIdItemMenu()==$parObjItemMenuDTO->getNumIdItemMenu()){
              
              $dto = new ItemMenuDTO();
              $dto->retNumIdMenuPai();
              $dto->retNumIdItemMenuPai();
              $dto->setNumIdMenu($parObjItemMenuDTO->getNumIdMenu());
              $dto->setNumIdItemMenu($parObjItemMenuDTO->getNumIdItemMenu());
              
              $dto1 = $this->consultar($dto);
              
              $dto = new ItemMenuDTO();
              $dto->setNumIdMenuPai($dto1->getNumIdMenuPai());
              $dto->setNumIdItemMenuPai($dto1->getNumIdItemMenuPai());
              $dto->setNumIdMenu($objItemMenuDTONovoPai->getNumIdMenu());
              $dto->setNumIdItemMenu($objItemMenuDTONovoPai->getNumIdItemMenu());
              
              $objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
              $objItemMenuBD->alterar($dto);
              
              break;
            }
          }
          break;
        }
      }
    }    
  }
  
  protected function listarPerfilConectado(PerfilDTO $objPerfilDTO){
    try {
    
      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('item_menu_listar_perfil',__METHOD__,$objItemMenuDTO);
      /////////////////////////////////////////////////////////////////
    
      $ret = array();
      
      $objMenuDTO = new MenuDTO();
      $objMenuDTO->retNumIdMenu();
      $objMenuDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
      
      $objMenuRN = new MenuRN();
      $arrObjMenuDTO = $objMenuRN->listar($objMenuDTO);
     
      $objRelPerfilItemMenuDTO = new RelPerfilItemMenuDTO();
      $objRelPerfilItemMenuDTO->retNumIdItemMenu();
      $objRelPerfilItemMenuDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());
      
      $objRelPerfilItemMenuRN = new RelPerfilItemMenuRN();
      $arrObjRelPerfilItemMenuDTO = InfraArray::indexarArrInfraDTO($objRelPerfilItemMenuRN->listar($objRelPerfilItemMenuDTO),'IdItemMenu');

      if (count($arrObjRelPerfilItemMenuDTO)){
        $objItemMenuRN = new ItemMenuRN();
        foreach($arrObjMenuDTO as $objMenuDTO){
          $objItemMenuDTO = new ItemMenuDTO();
          $objItemMenuDTO->setNumIdSistema($objPerfilDTO->getNumIdSistema());
          $objItemMenuDTO->setNumIdMenu($objMenuDTO->getNumIdMenu());
          $arrObjItemMenuDTO = $objItemMenuRN->listarHierarquia($objItemMenuDTO);
          foreach($arrObjItemMenuDTO as $objItemMenuDTO){
            if (isset($arrObjRelPerfilItemMenuDTO[$objItemMenuDTO->getNumIdItemMenu()])){
              $ret[] = $objItemMenuDTO;
            }
          }
        }
      }
            
      return $ret;
      
    }catch(Exception $e){
      throw new InfraException('Erro listando itens de menu do perfil.',$e);
    }
  } 
   
  protected function listarHierarquiaConectado(ItemMenuDTO $objItemMenuDTO){
    try {
      
      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('rel_hierarquia_unidade_listar',__METHOD__,$objItemMenuDTO);
			/////////////////////////////////////////////////////////////////

			$objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
			
			$dto = new ItemMenuDTO();
			
			$dto->retTodos();
			$dto->retStrNomeMenu();
			$dto->retStrNomeRecurso();
			$dto->retStrCaminhoRecurso();
			$dto->retStrSinAtivoRecurso();
			$dto->setNumIdMenu($objItemMenuDTO->getNumIdMenu());
			$dto->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
			$dto->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);
      $dto->setBolExclusaoLogica($objItemMenuDTO->isBolExclusaoLogica());
      
			$objItemMenuBD = new ItemMenuBD($this->getObjInfraIBanco());
			$arr = $objItemMenuBD->listar($dto);
		  
		  $arrRet = array();
		  $arrPais = array();
		  $arrTodas = array();
		  $arrTodas2 = array();
		  foreach($arr as $dto){
		    $numIdItemMenu = $dto->getNumIdItemMenu();
		    $arrRet[$numIdItemMenu] = $dto;
		    $arrTodas[$numIdItemMenu] = $dto;
		    $arrTodas2[$numIdItemMenu] = $dto;
	      $arrPais[$numIdItemMenu] = $dto->getNumIdItemMenuPai();
		  }

		  foreach($arrTodas as $numIdItemMenu => $dto){
    		
		    
		    $arrItensMenuSuperiores = array();
    		
   		  $numIdItemMenuPai = $arrPais[$numIdItemMenu];
    		
   		  $arrItensProcessados = array($numIdItemMenuPai => 0);
   		   
    		//Enquanto tiver pai armazena unidades superiores
    		while ($numIdItemMenuPai != null){
    		  
    		  foreach($arrTodas2 as $numIdItemMenu2 => $dto2){
    		  
    		    if ($numIdItemMenu2 == $numIdItemMenuPai){
    		      
   		        //referencia circular
        		  if (isset($arrItensProcessados[$arrPais[$numIdItemMenu2]])){ 
        		    throw new InfraException('Referência circular no menu envolvendo o item '.$arrPais[$numIdItemMenu2].'.');
        		  }else{
        		    $arrItensProcessados[$arrPais[$numIdItemMenu2]] = 0;
        		  }
    		      
   		        $arrItensMenuSuperiores[] = $dto2;
   		        
    		      //Se a unidade tem pai
         		  $numIdItemMenuPai = $arrPais[$numIdItemMenu2];
    		      break;
    		    }
    		  }
    		}
    		
 				$arrRet[$numIdItemMenu]->setArrPais(array_reverse($arrItensMenuSuperiores));
			}
      

		  foreach($arrRet as $numIdItemMenu => $dto){
		    $strRamificacao = '';
		    $arrItensMenuSuperiores = $dto->getArrPais();
		    foreach ($arrItensMenuSuperiores as $itemmenu) {
		    	if ($strRamificacao!=''){
		    	  $strRamificacao .= ' / ';
		    	}
		    	$strRamificacao .= $itemmenu->getStrRotulo();
		    }
		    
	    	if ($strRamificacao!=''){
	    	  $strRamificacao .= ' / ';
	    	}
		    $strRamificacao .=  $dto->getStrRotulo();
		    
		    $arrRet[$numIdItemMenu]->setStrRamificacao($strRamificacao);
		    $arrRet[$numIdItemMenu]->setStrNivel(str_repeat('-',count($arrItensMenuSuperiores)+1));
		  }

		  
		  
		  $arrTemp = array();
		  foreach($arrRet as $objItemMenuDTO){
		    if ($objItemMenuDTO->getNumIdItemMenuPai()==null){
		      $objItemMenuDTO->setNumIdItemMenuPai(0);
		    }
	      $arrTemp[$objItemMenuDTO->getNumIdItemMenuPai()][] = $objItemMenuDTO;
		  }
		  $arrRet = $arrTemp;
		  
		  unset($arrTemp);
		  
		  $arrTemp = array();
      
		  $this->ordenarHierarquia($arrRet,$arrTemp,0);
      
      foreach($arrTemp as $objItemMenuDTO){
		    if ($objItemMenuDTO->getNumIdItemMenuPai()==0){
		      $objItemMenuDTO->setNumIdItemMenuPai(null);
		    }
      }
      
		  /*
		  foreach($arrTemp as $item){
		    InfraDebug::getInstance()->gravar($item->getStrNivel().'@'.$item->getStrRotulo(). '@'.$item->getStrRamificacao());
		  }
		  */
			return $arrTemp;
			

    }catch(Exception $e){
      throw new InfraException('Erro listando hierarquia.',$e);
    }
	}

	private function ordenarHierarquia($arr,&$ret,$pai){
	  if (isset($arr[$pai]) && is_array($arr[$pai])){
  	  foreach($arr[$pai] as $item){
  	      $ret[] = $item;
  	      $this->ordenarHierarquia($arr,$ret,$item->getNumIdItemMenu());
  	  }
	  }
	}
	
	/*
	private function ordenarHierarquia($arr,&$ret,$pai){
	  foreach($arr as $item){
	    if ($item->getNumIdItemMenuPai()==$pai){
	      $ret[] = $item;
	      $this->ordenarHierarquia($arr,$ret,$item->getNumIdItemMenu());
	    }
	  }
	}
	*/
	
	protected function listarItensMenuInferioresConectado(ItemMenuDTO $objItemMenuDTO){
		
		$ret = array();
		
		$dto = new ItemMenuDTO();					
		$dto->retTodos();
		$dto->retStrNomeRecurso();
		$dto->retStrCaminhoRecurso();
		$dto->retStrSinAtivoRecurso();
		$dto->setNumIdMenuPai($objItemMenuDTO->getNumIdMenu());
		$dto->setNumIdItemMenuPai($objItemMenuDTO->getNumIdItemMenu());
		$dto->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
		$dto->setBolExclusaoLogica($objItemMenuDTO->isBolExclusaoLogica());
		$arrFilhos = $this->listar($dto);
		
		foreach($arrFilhos as $filho){
			$filho->setStrRamificacao($objItemMenuDTO->getStrRamificacao().' / '.$filho->getStrRotulo());
			$filho->setArrPais(array_merge($objItemMenuDTO->getArrPais(),array($filho)));
			$filho->setStrNivel($objItemMenuDTO->getStrNivel().'-');
			$filho->setBolExclusaoLogica($objItemMenuDTO->isBolExclusaoLogica());
			$ret[] = $filho;
			$tmp = $this->listarItensMenuInferiores($filho);
			$ret = array_merge($ret,$tmp);
		}
			
		return $ret;
	}


  private function validarNumIdMenu(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objItemMenuDTO->getNumIdMenu())){
      $objInfraException->adicionarValidacao('Menu não informado.');
    }
  }

  private function validarNumIdSistema(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objItemMenuDTO->getNumIdSistema())){
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
  }

  private function validarNumIdMenuPai(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
  }

  private function validarNumIdItemMenuPai(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
  }

  private function validarNumIdRecurso(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
  }

  private function validarStrRotulo(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objItemMenuDTO->getStrRotulo())){
      $objInfraException->adicionarValidacao('Rótulo não informado.');
    }
  }
	
  private function validarStrDescricao(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objItemMenuDTO->getStrDescricao())){
      $objItemMenuDTO->setStrDescricao(null);
    }else{
      $objItemMenuDTO->setStrDescricao(trim($objItemMenuDTO->getStrDescricao()));

      if (strlen($objItemMenuDTO->getStrDescricao())>200){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 200 caracteres.');
      }
    }
  }

  private function validarStrIcone(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objItemMenuDTO->getStrIcone())){
      $objItemMenuDTO->setStrIcone(null);
    }else{
      $objItemMenuDTO->setStrIcone(trim($objItemMenuDTO->getStrIcone()));

      if (strlen($objItemMenuDTO->getStrIcone())>250){
        $objInfraException->adicionarValidacao('Ícone possui tamanho superior a 250 caracteres.');
      }

      if (preg_match("/[^0-9a-zA-Z\-_.]/", $objItemMenuDTO->getStrIcone())){
        $objInfraException->adicionarValidacao('Ícone possui caracter inválido.');
      }
    }
  }

  private function validarNumSequencia(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objItemMenuDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência não informada.');
    }
  }

  private function validarStrSinAtivo(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if ($objItemMenuDTO->getStrSinAtivo()===null || ($objItemMenuDTO->getStrSinAtivo()!=='S' && $objItemMenuDTO->getStrSinAtivo()!=='N')){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
    }
  }

  private function validarStrSinNovaJanela(ItemMenuDTO $objItemMenuDTO, InfraException $objInfraException){
    if ($objItemMenuDTO->getStrSinNovaJanela()===null || ($objItemMenuDTO->getStrSinNovaJanela()!=='S' && $objItemMenuDTO->getStrSinNovaJanela()!=='N')){
      $objInfraException->adicionarValidacao('Sinalizador de Abertura em Nova Janela inválido.');
    }
  }
  
}
?>