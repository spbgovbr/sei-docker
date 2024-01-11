<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class PermissaoRN extends InfraRN {

	public static $TIPO_NAO_DELEGAVEL = 1;
	public static $TIPO_DELEGAVEL = 2;
	public static $TIPO_DELEGAVEL_UMA_VEZ = 3;
	
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  protected function carregarUsuarioConectado(PermissaoDTO $objPermissaoDTO){
    try{
       
        $ret = null;
        
    		$objInfraException = new InfraException();
  
    		$objPermissaoDTO->setStrTipoServidorAutenticacao(strtoupper(trim($objPermissaoDTO->getStrTipoServidorAutenticacao())));
    		
    		if ($objPermissaoDTO->getStrTipoServidorAutenticacao()!='AD' && $objPermissaoDTO->getStrTipoServidorAutenticacao()!='LDAP'){
    		  throw new InfraException('Tipo do servidor de autenticação ['.$objPermissaoDTO->getStrTipoServidorAutenticacao().'] inválido.');
    		}
    		    		
    		if (InfraString::isBolVazia($objPermissaoDTO->getNumIdSistema())){
    		  $objInfraException->adicionarValidacao('Sistema não informado.');
    		}
  
    		if (InfraString::isBolVazia($objPermissaoDTO->getNumIdOrgaoUsuario())){
    		  $objInfraException->adicionarValidacao('Órgão do usuário não informado.');
    		}

    		if (InfraString::isBolVazia($objPermissaoDTO->getStrSiglaUsuario())){
    		  $objInfraException->adicionarValidacao('Sigla do usuário não informada.');
    		}
    		
    		$objInfraException->lancarValidacoes();
  
    		$objUsuarioDTO = new UsuarioDTO();
    		$objUsuarioDTO->retNumIdUsuario();
    		$objUsuarioDTO->retStrSigla();
    		$objUsuarioDTO->retStrNome();
    		$objUsuarioDTO->retNumIdOrgao();
    		$objUsuarioDTO->retStrSiglaOrgao();
    		$objUsuarioDTO->retStrDescricaoOrgao();
    		$objUsuarioDTO->setNumIdOrgao($objPermissaoDTO->getNumIdOrgaoUsuario());
    		$objUsuarioDTO->setStrSigla($objPermissaoDTO->getStrSiglaUsuario());
    		
    		$objUsuarioRN = new UsuarioRN();
    		$objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

    		if ($objUsuarioDTO!=null){
    		  
    		  $ret = array('IdUsuario' => $objUsuarioDTO->getNumIdUsuario(),
                       'SiglaUsuario' => $objUsuarioDTO->getStrSigla(),
    		               'NomeUsuario' => $objUsuarioDTO->getStrNome(),
    		               'IdOrgaoUsuario' => $objUsuarioDTO->getNumIdOrgao(),
    		               'SiglaOrgaoUsuario' => $objUsuarioDTO->getStrSiglaOrgao(),
    		               'DescricaoOrgaoUsuario' => $objUsuarioDTO->getStrDescricaoOrgao());

    		  $objUsuarioDTO->setStrTipoServidorAutenticacao($objPermissaoDTO->getStrTipoServidorAutenticacao());
    		  
    		  $objSipRN = new SipRN();
    		  $retPesquisa = $objSipRN->pesquisarUsuario($objUsuarioDTO);
    		  
    		  $ret['ContextoUnidade'] = $retPesquisa['ContextoUnidade'];
    		  //$ret['ContextoUsuario'] = $retPesquisa['ContextoUsuario'];

   		    $ret['IdUnidadePadrao'] = '';

    		  $objPermissaoDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
    		  
      		$objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
      		$ret['Unidades'] = $objPermissaoBD->carregarUsuario($objPermissaoDTO);
    		}
    		
    		return $ret;
    		 
    }catch(Exception $e){
      throw new InfraException('Erro carregando usuário.',$e);
    }
  }
    
  protected function carregarConectado(LoginDTO $objLoginDTO){
    try{   
       
  		$objInfraException = new InfraException();
  		
  		if (InfraString::isBolVazia($objLoginDTO->getNumIdSistema())){
  			$objInfraException->adicionarValidacao('Sistema não informado.');
  		}
  		
      if (InfraString::isBolVazia($objLoginDTO->getNumIdUsuario())){
        $objInfraException->adicionarValidacao('Usuário não informado.');
      }
  		
  		$objInfraException->lancarValidacoes();

  		$objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
  		$objPermissaoBD->carregar($objLoginDTO);

  		//retorna itens de menu e recursos por unidade
  		$arrPermissoes = $objLoginDTO->getObjInfraSessaoDTO()->getArrPermissoes();
  		
      //Lista menus do sistema
  		$objMenuDTO = new MenuDTO();
  		$objMenuDTO->retNumIdMenu();
  		$objMenuDTO->retStrNome();
  		$objMenuDTO->setNumIdSistema($objLoginDTO->getNumIdSistema());
  		$objMenuRN = new MenuRN();
  		$arrObjMenuDTO = $objMenuRN->listar($objMenuDTO);

  		
  		
  		//busca menus para complementar com os itens que o usuário tem acesso
  		//o usuario pode ter acesso a um item interno do menu mas os itens "pais" devem ser retornados também
  		$arrMenuCompleto = array();
  		foreach($arrObjMenuDTO as $objMenuDTO){
  			$objItemMenuDTO = new ItemMenuDTO();
  			$objItemMenuDTO->setNumIdMenu($objMenuDTO->getNumIdMenu());
  			$objItemMenuRN = new ItemMenuRN();
  			$arrMenuCompleto[$objMenuDTO->getNumIdMenu()] = $objItemMenuRN->listarHierarquia($objItemMenuDTO);
  		}
  		
  		$arrUnidadesPermissao = array_keys($arrPermissoes);
  		
  		foreach($arrUnidadesPermissao as $numIdUnidadePermissao){
  		  
  		  $arrItensUnidade = $arrPermissoes[$numIdUnidadePermissao][InfraSip::$WS_LOGIN_PERMISSAO_MENU];
    		
    		$ret = array();
    		
    		if (count($arrItensUnidade)>0){
      		//carrega cada menu individualmente
      		foreach($arrObjMenuDTO as $menu){
      		  
      			//Conterá os itens que o usuário tem acesso
      			$arrItensAdicionados = array();
      			
      			$arrMenu = array();
      			
      			$numIdMenu = $menu->getNumIdMenu();
      			
      			//Inicia varredura nos itens do menu do sistema
      			$numItensMenu = count($arrMenuCompleto[$numIdMenu]);
      			for($i=0;$i<$numItensMenu;$i++){
      			  
      			  $objItemMenuDTO = $arrMenuCompleto[$numIdMenu][$i];
      			  
      				if (in_array($objItemMenuDTO->getNumIdItemMenu(),$arrItensUnidade)){  
      					
      					//Se o item tem recurso associado verifica se esta ativo
      					if ($objItemMenuDTO->getNumIdRecurso()!=null && $objItemMenuDTO->getStrSinAtivoRecurso()=='N'){
      					  continue;
      					}
      					
      					//Verifica todos os itens pais do item atual
      					$arrPais = $objItemMenuDTO->getArrPais();
      					$numPais = count($arrPais);
      					for($k=0;$k<$numPais;$k++){
      					  
      						//Se já adicionou o pai ignora
      						if (!isset($arrItensAdicionados[$arrPais[$k]->getNumIdItemMenu()])){
      
      							//Verifica somente os itens pais que tem recurso associado
      							//Itens que nao possuem recursos associados são utilizados somente para organizar o menu
      							//Se usuario não tem acesso ao item pai então não monta esta ramificação
      						  if ($arrPais[$k]->getNumIdRecurso()!=null){
      							  
      						    if (!in_array($arrPais[$k]->getNumIdItemMenu(),$arrItensUnidade)){   
      						      break;  
      						    }
      						    
      		            //Se o recurso nao esta ativo sai fora
      		            if ($arrPais[$k]->getStrSinAtivoRecurso()=='N'){
      		              break;
      		            }						
      						  }
      						}
      					}
      					
      					//Se o usuario tem acesso a todos os pais e eles estão ativos
      					if ($k == $numPais){
      					 //adiciona os pais 
      					 
      						for($k=0;$k<$numPais;$k++){
      							if (!isset($arrItensAdicionados[$arrPais[$k]->getNumIdItemMenu()])){ 
      							  
      							  
                  		$str = '';
                  		$str .= $arrPais[$k]->getStrNivel().'^';
                  		if ($arrPais[$k]->getNumIdRecurso()!=null){
                  			$str .= $arrPais[$k]->getStrCaminhoRecurso().'^';
                  		}else{
                  			$str .= '#^';
                  		}
                  		
                  		$str .= $arrPais[$k]->getStrDescricao().'^';
                  		$str .= $arrPais[$k]->getStrRotulo().'^';
                 		  $str .= ($arrPais[$k]->getStrSinNovaJanela()=='S') ? '_blank^' : '^';
                      $str .= $arrPais[$k]->getStrIcone();

                 		  $arrMenu[] = $str;
                 		  

    							    $arrItensAdicionados[$arrPais[$k]->getNumIdItemMenu()] = 0;
      							}
      						}
      						//adiciona o filho
      						
      						
              		$str = '';
              		$str .= $objItemMenuDTO->getStrNivel().'^';
              		if ($objItemMenuDTO->getNumIdRecurso()!=null){
              			$str .= $objItemMenuDTO->getStrCaminhoRecurso().'^';
              		}else{
              			$str .= '#^';
              		}
              		$str .= $objItemMenuDTO->getStrDescricao().'^';
              		$str .= $objItemMenuDTO->getStrRotulo().'^';
             		  $str .= ($objItemMenuDTO->getStrSinNovaJanela()=='S') ? '_blank^': '^';
                  $str .= $objItemMenuDTO->getStrIcone();
      						$arrMenu[] = $str;
      						
      						$arrItensAdicionados[$objItemMenuDTO->getNumIdItemMenu()] = 0;
      					}
      				}
      			}
      			
      			$ret[$menu->getStrNome()] = $arrMenu;	
      		}
    		}
    		//substitui os ids pelos itens montados
    		$arrPermissoes[$numIdUnidadePermissao][InfraSip::$WS_LOGIN_PERMISSAO_MENU] = $ret;
  		}
  		
	    $objLoginDTO->getObjInfraSessaoDTO()->setArrPermissoes($arrPermissoes);
	    
    }catch(Exception $e){
      throw new InfraException('Erro carregando permissões.',$e);
    }
		
	}
 	
	protected function carregarUsuariosConectado(PermissaoDTO $objPermissaoDTO){
    try{    
  		$objInfraException = new InfraException();
  		
  		if (InfraString::isBolVazia($objPermissaoDTO->getNumIdSistema())){
  			$objInfraException->adicionarValidacao('Sistema não informado.');
  		}
  		
  		$objInfraException->lancarValidacoes();

  		$objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
  		$ret = $objPermissaoBD->carregarUsuarios($objPermissaoDTO);
  		
  		return $ret;
		
    }catch(Exception $e){
      throw new InfraException('Erro carregando usuários.',$e);
    }
		
	}
	
  protected function copiarControlado(PermissaoCopiarDTO $objPermissaoCopiarDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_copiar',__METHOD__,$objPermissaoCopiarDTO);

			$objUnidadeDTOCopia = null;

			//Antes das regras complementa o array recebido com as informações das permissoes
			$arrNovo = array();
			foreach($objPermissaoCopiarDTO->getArrObjPermissaoDTO() as $dto){
				 $objPermissaoDTO = new PermissaoDTO(true);
				 $objPermissaoDTO->retTodos();
				 $objPermissaoDTO->setNumIdUnidade($dto->getNumIdUnidade());
				 $objPermissaoDTO->setNumIdSistema($dto->getNumIdSistema());
				 $objPermissaoDTO->setNumIdUsuario($dto->getNumIdUsuario());
				 $objPermissaoDTO->setNumIdPerfil($dto->getNumIdPerfil());
				 $objPermissaoDTO = $this->consultar($objPermissaoDTO);
				 $arrNovo[] = $objPermissaoDTO;
			}
			//Substitui o array recebido pelo novo completo
			$objPermissaoCopiarDTO->setArrObjPermissaoDTO($arrNovo);
			
      //Regras de Negocio
      $objInfraException = new InfraException();

			//Verifica se o usuario origem/destino das copias
			foreach($objPermissaoCopiarDTO->getArrObjPermissaoDTO() as $objPermissaoDTO){
				if ( $objPermissaoDTO->getNumIdUsuario()==$objPermissaoCopiarDTO->getNumIdUsuario() &&
						($objPermissaoCopiarDTO->getNumIdUnidade()==null || $objPermissaoDTO->getNumIdUnidade()==$objPermissaoCopiarDTO->getNumIdUnidade())){
					 $objInfraException->lancarValidacao('Não é possível copiar a permissão do sistema '.$objPermissaoDTO->getStrSiglaOrgaoSistema().'/'.$objPermissaoDTO->getStrSiglaSistema().' e usuário '.$objPermissaoDTO->getStrSiglaOrgaoUsuario().'/'.$objPermissaoDTO->getStrSiglaUsuario().' para o mesmo usuário e unidade.');
					}
			 }

      foreach($objPermissaoCopiarDTO->getArrObjPermissaoDTO() as $objPermissaoDTO) {

        $objSistemaDTO = new SistemaDTO();
        $objSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());

				if ($objPermissaoCopiarDTO->getNumIdUnidade()==null){
					$objSistemaDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
				}else{
					$objSistemaDTO->setNumIdUnidade($objPermissaoCopiarDTO->getNumIdUnidade());
				}

        $objPerfilRN = new PerfilRN();
        $arrObjPerfilDTO = InfraArray::indexarArrInfraDTO($objPerfilRN->obterAutorizados($objSistemaDTO),'IdPerfil');

        if (!isset($arrObjPerfilDTO[$objPermissaoDTO->getNumIdPerfil()])) {

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrSiglaOrgao();

					if ($objPermissaoCopiarDTO->getNumIdUnidade()==null){
						$objUnidadeDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
					}else{
						$objUnidadeDTO->setNumIdUnidade($objPermissaoCopiarDTO->getNumIdUnidade());
					}

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

          if ($objUnidadeDTO==null){
            throw new InfraException('Unidade não encontrada.');
          }

          $objInfraException->adicionarValidacao('Usuário atual não têm acesso para copiar permissão do sistema "'.$objPermissaoDTO->getStrSiglaSistema().'/'.$objPermissaoDTO->getStrSiglaOrgaoSistema().'" na unidade "' . $objUnidadeDTO->getStrSigla() . '/'.$objUnidadeDTO->getStrSiglaOrgao().'" no perfil "' . $objPermissaoDTO->getStrNomePerfil() . '".');
        }
      }

		 $objInfraException->lancarValidacoes();


      //Armazena permissões que o usuario já tem para evitar registro duplicado
      $arrStrPermissoesExistentes = array();
      foreach($objPermissaoCopiarDTO->getArrObjPermissaoDTO() as $objPermissaoDTO){

        $dto = new PermissaoDTO();
        $dto->setNumMaxRegistrosRetorno(1);
        $dto->retNumIdUsuario();
        $dto->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
        $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
        $dto->setNumIdUsuario($objPermissaoCopiarDTO->getNumIdUsuario());

        if ($objPermissaoCopiarDTO->getNumIdUnidade()==null){

          $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

        }else {

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrSiglaOrgao();
          $objUnidadeDTO->setNumIdUnidade($objPermissaoCopiarDTO->getNumIdUnidade());

          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

          if ($objUnidadeDTO==null){
            throw new InfraException('Unidade não encontrada.');
          }

          $dto->setNumIdUnidade($objPermissaoCopiarDTO->getNumIdUnidade());
        }

        $strChave = $objPermissaoDTO->getNumIdSistema().'#'.$objPermissaoCopiarDTO->getNumIdUsuario().'#'.$objPermissaoDTO->getNumIdPerfil().'#'.$dto->getNumIdUnidade();

        if (!in_array($strChave,$arrStrPermissoesExistentes) && $this->consultar($dto) != null) {
          $arrStrPermissoesExistentes[] = $strChave;
        }
      }

		 //Armazena as chaves das permissoes copiadas para evitar
		 //tentar copiar duas vezes para mesma unidade (este problema acontece
		 //quando nas permissoes selecionadas para cópia o usuario tinha acesso 
		 //ao mesmo sistema em mais de uma unidade)

		 $arrStrPermissoesCopiadas = array();
		 $ret = array();
     foreach($objPermissaoCopiarDTO->getArrObjPermissaoDTO() as $objPermissaoDTO){
				
				//Muda o usuario e unidade
				$objPermissaoDTO->setNumIdUsuario($objPermissaoCopiarDTO->getNumIdUsuario());

				//Muda data de início da permissão
				$objPermissaoDTO->setDtaDataInicio(InfraData::getStrDataHoraAtual());

			  if ($objPermissaoCopiarDTO->getNumIdUnidade()==null){
					$objPermissaoDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
				}else{
					$objPermissaoDTO->setNumIdUnidade($objPermissaoCopiarDTO->getNumIdUnidade());
				}

				
				$strChave = $objPermissaoDTO->getNumIdSistema().'#'.$objPermissaoDTO->getNumIdUsuario().'#'.$objPermissaoDTO->getNumIdPerfil().'#'.$objPermissaoDTO->getNumIdUnidade();

				if (!in_array($strChave,$arrStrPermissoesCopiadas) && !in_array($strChave, $arrStrPermissoesExistentes)){
					//Cadastra copia
					$ret[] = $this->cadastrar($objPermissaoDTO);
					$arrStrPermissoesCopiadas[] = $strChave;
				}
			}

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro copiando permissões.',$e);
    }
  }

  protected function delegarControlado(PermissaoDelegarDTO $objPermissaoDelegarDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_delegar',__METHOD__,$objPermissaoDelegarDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
			
			$arrObjPermissaoDTO = $objPermissaoDelegarDTO->getArrObjPermissaoDTO();
			if (count($arrObjPermissaoDTO)==0){
				$objInfraException->lancarValidacao('Nenhuma permissão foi informada para delegação.');
			}

			//Completa dados para todas as permissoes
			$arr = array();
			foreach($arrObjPermissaoDTO as $objPermissaoDTO){
				$objPermissaoDTO->retTodos();
				$objPermissaoDTO = $this->consultar($objPermissaoDTO);
				$arr[]=$objPermissaoDTO;
			}
			$objPermissaoDelegarDTO->setArrObjPermissaoDTO($arr);
			$arrObjPermissaoDTO = $arr;
			
			foreach($arrObjPermissaoDTO as $permissao){
				if ($permissao->getNumIdUsuario()==$objPermissaoDelegarDTO->getNumIdUsuario()){
					$objInfraException->lancarValidacao('Não é possível delegar permissões para si mesmo.');
				}
				
				if ($permissao->getNumIdTipoPermissao()==PermissaoRN::$TIPO_NAO_DELEGAVEL){
					$objInfraException->lancarValidacao('Uma ou mais permissões não podem ser delegadas.');
				}
			}
			
      $objInfraException->lancarValidacoes();

      foreach($arrObjPermissaoDTO as $objPermissaoDTO){
				
				//Muda o usuario
				$objPermissaoDTO->setNumIdUsuario($objPermissaoDelegarDTO->getNumIdUsuario());
				
				//Se for delegavel uma vez, entao nao pode mais delegar
				if ($objPermissaoDTO->getNumIdTipoPermissao()==PermissaoRN::$TIPO_DELEGAVEL_UMA_VEZ){
					$objPermissaoDTO->setNumIdTipoPermissao(PermissaoRN::$TIPO_NAO_DELEGAVEL);
				}
				
				//Delega cadastrando copia
				$this->cadastrar($objPermissaoDTO);
			}

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro delegando permissões.',$e);
    }
  }

  protected function cadastrarControlado(PermissaoDTO $objPermissaoDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_cadastrar',__METHOD__,$objPermissaoDTO);

      $objInfraException = new InfraException();
			
      $this->validarNumIdPerfil($objPermissaoDTO,$objInfraException);
			$this->validarNumIdSistema($objPermissaoDTO,$objInfraException);
			$this->validarNumIdUsuario($objPermissaoDTO,$objInfraException);
			$this->validarNumIdUnidade($objPermissaoDTO,$objInfraException);
			$this->validarNumIdTipoPermissao($objPermissaoDTO,$objInfraException);
			$this->validarDtaDataInicio($objPermissaoDTO,$objInfraException);
			$this->validarDtaDataFim($objPermissaoDTO,$objInfraException);
			$this->validarPeriodoDatas($objPermissaoDTO,$objInfraException);
			$this->validarSinSubunidades($objPermissaoDTO,$objInfraException);

			$objInfraParametro = new InfraParametro(BancoSip::getInstance());

			if ($objPermissaoDTO->getNumIdSistema()==$objInfraParametro->getValor('ID_SISTEMA_SIP')){

				if  ($objPermissaoDTO->getNumIdPerfil()==$objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA')){
					$objInfraException->adicionarValidacao('Não é possível cadastrar permissão no perfil reservado para Administrador de Sistema no SIP.');
				}

				if  ($objPermissaoDTO->getNumIdPerfil()==$objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SIP')){
					$objInfraException->adicionarValidacao('Não é possível cadastrar permissão no perfil reservado para Administrador do SIP.');
				}

				if  ($objPermissaoDTO->getNumIdPerfil()==$objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_PERFIL')){
					$objInfraException->adicionarValidacao('Não é possível cadastrar permissão no perfil reservado para Coordenador de Perfil no SIP.');
				}

				if  ($objPermissaoDTO->getNumIdPerfil()==$objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_UNIDADE')){
					$objInfraException->adicionarValidacao('Não é possível cadastrar permissão no perfil reservado para Coordenador de Unidade no SIP.');
				}

			}

			$dto = new PermissaoDTO();
			$dto->retStrSiglaUsuario();
			$dto->retStrSiglaSistema();
			$dto->retStrNomePerfil();
			$dto->retStrSiglaUnidade();
			$dto->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
			$dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
			$dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
			$dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
			$dto = $this->consultar($dto);
			if ($dto!=null){
				$objInfraException->adicionarValidacao('Usuário \''.$dto->getStrSiglaUsuario().'\' já possui permissão no sistema \''.$dto->getStrSiglaSistema().'\', perfil \''.$dto->getStrNomePerfil().'\' e unidade \''.$dto->getStrSiglaUnidade().'\'.');
			}

			$objInfraException->lancarValidacoes();

			$objSistemaDTO = new SistemaDTO();
			$objSistemaDTO->setBolExclusaoLogica(false);
			$objSistemaDTO->retStrSigla();
			$objSistemaDTO->retNumIdHierarquia();
			$objSistemaDTO->retStrSinAtivo();
			$objSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());

			$objSistemaRN = new SistemaRN();
			$objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

			if ($objSistemaDTO == null){
				throw new InfraException('Sistema ['.$objPermissaoDTO->getNumIdSistema().'] não encontrado.');
			}

			if ($objSistemaDTO->getStrSinAtivo() == 'N'){
				throw new InfraException('Sistema '.$objSistemaDTO->getStrSigla().' desativado.');
			}

			$objUnidadeDTO = new UnidadeDTO();
			$objUnidadeDTO->setBolExclusaoLogica(false);
			$objUnidadeDTO->retStrSigla();
			$objUnidadeDTO->retStrSinAtivo();
			$objUnidadeDTO->retStrSinGlobal();
			$objUnidadeDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

			$objUnidadeRN = new UnidadeRN();
			$objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

			if ($objUnidadeDTO == null){
				throw new InfraException('Unidade ['.$objPermissaoDTO->getNumIdUnidade().'] não encontrada.');
			}

			if ($objUnidadeDTO->getStrSinAtivo() == 'N'){
				throw new InfraException('Unidade '.$objUnidadeDTO->getStrSigla().' desativada.');
			}

			if ($objUnidadeDTO->getStrSinGlobal()=='N'){

				$objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
				$objRelHierarquiaUnidadeDTO->retNumIdUnidade();
				$objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());
				$objRelHierarquiaUnidadeDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

				$objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
				if ($objRelHierarquiaUnidadeRN->consultar($objRelHierarquiaUnidadeDTO)==null){
					$objInfraException->lancarValidacao('Unidade '.$objUnidadeDTO->getStrSigla().' não consta na hierarquia do sistema '.$objSistemaDTO->getStrSigla().'.');
				}
			}

			$objUsuarioDTO = new UsuarioDTO();
			$objUsuarioDTO->setBolExclusaoLogica(false);
			$objUsuarioDTO->retStrSigla();
			$objUsuarioDTO->retStrSinAtivo();
			$objUsuarioDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());

			$objUsuarioRN = new UsuarioRN();
			$objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

			if ($objUsuarioDTO == null){
				throw new InfraException('Usuário ['.$objPermissaoDTO->getNumIdUsuario().'] não encontrado.');
			}

			if ($objUsuarioDTO->getStrSinAtivo() == 'N'){
				throw new InfraException('Usuário '.$objUsuarioDTO->getStrSigla().' desativado.');
			}

			$objPerfilDTO = new PerfilDTO();
			$objPerfilDTO->setBolExclusaoLogica(false);
			$objPerfilDTO->retStrNome();
      $objPerfilDTO->retStrSiglaSistema();
			$objPerfilDTO->retNumIdSistema();
			$objPerfilDTO->retStrSinAtivo();
			$objPerfilDTO->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());

			$objPerfilRN = new PerfilRN();
			$objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

			if ($objPerfilDTO == null){
				throw new InfraException('Perfil ['.$objPermissaoDTO->getNumIdPerfil().'] não encontrado.');
			}

			if ($objPerfilDTO->getNumIdSistema() != $objPermissaoDTO->getNumIdSistema()){
				throw new InfraException('Perfil '.$objPerfilDTO->getStrNome().' não pertence ao sistema '.$objSistemaDTO->getStrSigla().'.');
			}

			if ($objPerfilDTO->getStrSinAtivo() == 'N'){
				throw new InfraException('Perfil '.$objPerfilDTO->getStrNome().' desativado.');
			}

			if (SessaoSip::getInstance()->isBolHabilitada()) {

				$dto = new SistemaDTO();
				$dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
				$dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

		    $objPerfilRN = new PerfilRN();
		    $arrObjPerfilDTO = InfraArray::indexarArrInfraDTO($objPerfilRN->obterAutorizados($dto),'IdPerfil');

				if (!isset($arrObjPerfilDTO[$objPermissaoDTO->getNumIdPerfil()])) {
					$objInfraException->lancarValidacao('Usuário atual não têm acesso para cadastrar a permissão na unidade "' . $objUnidadeDTO->getStrSigla() . '" no perfil "' . $objPerfilDTO->getStrNome() . '" do sistema "'.$objPerfilDTO->getStrSiglaSistema().'".');
				}
			}

      //verificar se o usuário já tem permissão neste sistema
      $dto = new PermissaoDTO();
      $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
      $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      
      if ($this->contar($dto)==0){
        $objReplicacaoUsuarioDTO = new ReplicacaoUsuarioDTO();
        $objReplicacaoUsuarioDTO->setStrStaOperacao('C');
        $objReplicacaoUsuarioDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
        $objReplicacaoUsuarioDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
        $objSistemaRN->replicarUsuario($objReplicacaoUsuarioDTO);
      }

      //verificar se o usuário já tem permissão nesta unidade do sistema
      $dto = new PermissaoDTO();
      $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
      $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
      
      if ($this->contar($dto)==0){

        if ($objUnidadeDTO->getStrSinGlobal()=='N'){
          $objReplicacaoAssociacaoUsuarioUnidadeDTO = new ReplicacaoAssociacaoUsuarioUnidadeDTO();
          $objReplicacaoAssociacaoUsuarioUnidadeDTO->setStrStaOperacao('C');
          $objReplicacaoAssociacaoUsuarioUnidadeDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $objReplicacaoAssociacaoUsuarioUnidadeDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
          $objReplicacaoAssociacaoUsuarioUnidadeDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
          
          $objSistemaRN = new SistemaRN();
          $objSistemaRN->replicarAssociacaoUsuarioUnidade($objReplicacaoAssociacaoUsuarioUnidadeDTO);
        }
      }

      $objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());

      if ($objPermissaoDTO->getNumIdSistema()==$objInfraParametro->getValor('ID_SISTEMA_SIP') && $objPermissaoDTO->getNumIdPerfil()==$objInfraParametro->getValor('ID_PERFIL_SIP_BASICO')){
        
        $objAdministradorSistemaDTO = new AdministradorSistemaDTO();
        $objAdministradorSistemaDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      
        $objAdministradorSistemaRN = new AdministradorSistemaRN();
        if ($objAdministradorSistemaRN->contar($objAdministradorSistemaDTO)){
          $dto = new PermissaoDTO();
          $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
          $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
          $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA'));
  
          if ($this->contar($dto)==0){
            //Assume a unidade do usuário atual
            $dto->setNumIdTipoPermissao(1); //Nao delegavel
            $dto->setDtaDataInicio(InfraData::getStrDataAtual());
            $dto->setDtaDataFim(null);
						$dto->setStrSinSubunidades('N');
            $objPermissaoBD->cadastrar($dto);
          }
        }

        $objAdministradorSistemaDTO = new AdministradorSistemaDTO();
        $objAdministradorSistemaDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
        $objAdministradorSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
        if ($objAdministradorSistemaRN->contar($objAdministradorSistemaDTO)){
          $dto = new PermissaoDTO();
          $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
          $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
          $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SIP'));
  
          if ($this->contar($dto)==0){
            //Assume a unidade do usuário atual
            $dto->setNumIdTipoPermissao(1); //Nao delegavel
            $dto->setDtaDataInicio(InfraData::getStrDataAtual());
            $dto->setDtaDataFim(null);
						$dto->setStrSinSubunidades('N');
            $objPermissaoBD->cadastrar($dto);
          }
        }
        
        $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
        $objCoordenadorPerfilDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      
        $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
        if ($objCoordenadorPerfilRN->contar($objCoordenadorPerfilDTO)){
          $dto = new PermissaoDTO();
          $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
          $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
          $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_PERFIL'));
  
          if ($this->contar($dto)==0){
            //Assume a unidade do usuário atual
            $dto->setNumIdTipoPermissao(1); //Nao delegavel
            $dto->setDtaDataInicio(InfraData::getStrDataAtual());
            $dto->setDtaDataFim(null);
						$dto->setStrSinSubunidades('N');
            $objPermissaoBD->cadastrar($dto);
          }
        }

        $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO();
        $objCoordenadorUnidadeDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      
        $objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
        if ($objCoordenadorUnidadeRN->contar($objCoordenadorUnidadeDTO)){
          $dto = new PermissaoDTO();
          $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
          $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
          $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
          $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_UNIDADE'));
  
          if ($this->contar($dto)==0){
            //Assume a unidade do usuário atual
            $dto->setNumIdTipoPermissao(1); //Nao delegavel
            $dto->setDtaDataInicio(InfraData::getStrDataAtual());
            $dto->setDtaDataFim(null);
						$dto->setStrSinSubunidades('N');
            $objPermissaoBD->cadastrar($dto);
          }
        }
      }
      
      $ret = $objPermissaoBD->cadastrar($objPermissaoDTO);

      $objReplicacaoPermissaoDTO = new ReplicacaoPermissaoDTO();
      $objReplicacaoPermissaoDTO->setStrStaOperacao('C');
      $objReplicacaoPermissaoDTO->setNumIdSistema($ret->getNumIdSistema());
      $objReplicacaoPermissaoDTO->setNumIdUsuario($ret->getNumIdUsuario());
      $objReplicacaoPermissaoDTO->setNumIdUnidade($ret->getNumIdUnidade());
      $objReplicacaoPermissaoDTO->setNumIdPerfil($ret->getNumIdPerfil());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarPermissao($objReplicacaoPermissaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Permissão.',$e);
    }
  }

  protected function alterarControlado(PermissaoDTO $objPermissaoDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('permissao_alterar',__METHOD__,$objPermissaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdPerfil($objPermissaoDTO,$objInfraException);
      $this->validarNumIdSistema($objPermissaoDTO,$objInfraException);
      $this->validarNumIdUsuario($objPermissaoDTO,$objInfraException);
      $this->validarNumIdUnidade($objPermissaoDTO,$objInfraException);

			if (SessaoSip::getInstance()->isBolHabilitada()) {
				$dto = new PermissaoDTO();
				$dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
				$dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
				$dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
				$dto->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
				if (count($this->listarAdministradas($dto))==0) {

					$objUnidadeDTO = new UnidadeDTO();
					$objUnidadeDTO->setBolExclusaoLogica(false);
					$objUnidadeDTO->retStrSigla();
					$objUnidadeDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

					$objUnidadeRN = new UnidadeRN();
					$objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

					if ($objUnidadeDTO==null){
						throw new InfraException('Unidade não encontrada.');
					}

					$objUsuarioDTO = new UsuarioDTO();
					$objUsuarioDTO->setBolExclusaoLogica(false);
					$objUsuarioDTO->retStrSigla();
					$objUsuarioDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());

					$objUsuarioRN = new UsuarioRN();
					$objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

					if ($objUsuarioDTO==null){
						throw new InfraException('Usuário não encontrado.');
					}

					$objPerfilDTO = new PerfilDTO();
					$objPerfilDTO->setBolExclusaoLogica(false);
					$objPerfilDTO->retStrNome();
          $objPerfilDTO->retStrSiglaSistema();
					$objPerfilDTO->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());

					$objPerfilRN = new PerfilRN();
					$objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);

					if ($objPerfilDTO==null){
						throw new InfraException('Perfil não encontrado.');
					}

					$objInfraException->adicionarValidacao('Usuário atual não têm acesso para alterar a permissão de "' . $objUsuarioDTO->getStrSigla() . '" na unidade "' . $objUnidadeDTO->getStrSigla() . '" no perfil "' . $objPerfilDTO->getStrNome() . '" do sistema "'.$objPerfilDTO->getStrSiglaSistema().'".');
				}
			}


			$objInfraException->lancarValidacoes();


			$objPermissaoDTOBanco = new PermissaoDTO();
      $objPermissaoDTOBanco->retDtaDataInicio();
      $objPermissaoDTOBanco->retDtaDataFim();
      $objPermissaoDTOBanco->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
      $objPermissaoDTOBanco->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
      $objPermissaoDTOBanco->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
      $objPermissaoDTOBanco->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      $objPermissaoDTOBanco = $this->consultar($objPermissaoDTOBanco);

      if ($objPermissaoDTO->isSetNumIdTipoPermissao()) {
        $this->validarNumIdTipoPermissao($objPermissaoDTO, $objInfraException);
      }

      if ($objPermissaoDTO->isSetDtaDataInicio()) {
        $this->validarDtaDataInicio($objPermissaoDTO, $objInfraException);
      }else{
        $objPermissaoDTO->setDtaDataInicio($objPermissaoDTOBanco->getDtaDataInicio());
      }

      if ($objPermissaoDTO->isSetDtaDataFim()) {
        $this->validarDtaDataFim($objPermissaoDTO, $objInfraException);
      }else{
        $objPermissaoDTO->setDtaDataFim($objPermissaoDTOBanco->getDtaDataFim());
      }

      $this->validarPeriodoDatas($objPermissaoDTO,$objInfraException);

			if ($objPermissaoDTO->isSetStrSinSubunidades()) {
				$this->validarSinSubunidades($objPermissaoDTO, $objInfraException);
			}
			
      $objInfraException->lancarValidacoes();

      $objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
      $objPermissaoBD->alterar($objPermissaoDTO);

      $objReplicacaoPermissaoDTO = new ReplicacaoPermissaoDTO();
      $objReplicacaoPermissaoDTO->setStrStaOperacao('A');
      $objReplicacaoPermissaoDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
      $objReplicacaoPermissaoDTO->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
      $objReplicacaoPermissaoDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
      $objReplicacaoPermissaoDTO->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());

      $objSistemaRN = new SistemaRN();
      $objSistemaRN->replicarPermissao($objReplicacaoPermissaoDTO);
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Permissão.',$e);
    }
  }

  protected function excluirControlado($arrObjPermissaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_excluir',__METHOD__,$arrObjPermissaoDTO);

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      
      //Regras de Negocio
      $objInfraException = new InfraException();

			if (SessaoSip::getInstance()->isBolHabilitada()) {
				foreach ($arrObjPermissaoDTO as $objPermissaoDTO) {
					if (count($this->listarAdministradas(clone($objPermissaoDTO)))==0) {
						$dto = new PermissaoDTO();
						$dto->retStrSiglaUsuario();
						$dto->retStrSiglaUnidade();
						$dto->retStrNomePerfil();
            $dto->retStrSiglaSistema();
						$dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
						$dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
						$dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
						$dto->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
						$dto = $this->consultar($dto);
						if ($dto != null) {
							$objInfraException->adicionarValidacao('Usuário atual não têm acesso para excluir a permissão de "' . $dto->getStrSiglaUsuario() . '" na unidade "' . $dto->getStrSiglaUnidade() . '" no perfil "' . $dto->getStrNomePerfil() . '" do sistema "'.$dto->getStrSiglaSistema().'".');
						}
					}
				}
			}


      $objInfraException->lancarValidacoes();

			$objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
			
			$objAdministradorSistemaRN = new AdministradorSistemaRN();
			$objCoordenadorPerfilRN = new CoordenadorPerfilRN();
			$objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
			$objPerfilRN = new PerfilRN(); 
			$objRelPerfilRecursoRN = new RelPerfilRecursoRN();
			$objSistemaRN = new SistemaRN();
			
			for($i=0;$i<count($arrObjPermissaoDTO);$i++){
  			$objReplicacaoPermissaoDTO = new ReplicacaoPermissaoDTO();
  			$objReplicacaoPermissaoDTO->setStrStaOperacao('E');
  			$objReplicacaoPermissaoDTO->setNumIdSistema($arrObjPermissaoDTO[$i]->getNumIdSistema());
  			$objReplicacaoPermissaoDTO->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
  			$objReplicacaoPermissaoDTO->setNumIdUnidade($arrObjPermissaoDTO[$i]->getNumIdUnidade());
  			$objReplicacaoPermissaoDTO->setNumIdPerfil($arrObjPermissaoDTO[$i]->getNumIdPerfil());
  			$objSistemaRN->replicarPermissao($objReplicacaoPermissaoDTO);
			}

      for($i=0;$i<count($arrObjPermissaoDTO);$i++){

				//Excluir a permissao
        $objPermissaoBD->excluir($arrObjPermissaoDTO[$i]);
				
        if ($arrObjPermissaoDTO[$i]->getNumIdSistema()==$objInfraParametro->getValor('ID_SISTEMA_SIP')){

          //excluindo básico
          if ($arrObjPermissaoDTO[$i]->getNumIdPerfil()==$objInfraParametro->getValor('ID_PERFIL_SIP_BASICO')){
            
            //se nao tem mais nenhuma permissao basica no SIP
            $dto = new PermissaoDTO();
            $dto->setNumIdSistema($arrObjPermissaoDTO[$i]->getNumIdSistema());
            $dto->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
            $dto->setNumIdPerfil($arrObjPermissaoDTO[$i]->getNumIdPerfil());
            
            if ($this->contar($dto)==0){

              $objAdministradorSistemaDTO = new AdministradorSistemaDTO();
              $objAdministradorSistemaDTO->retNumIdSistema();
              $objAdministradorSistemaDTO->retNumIdUsuario();
              $objAdministradorSistemaDTO->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
              $objAdministradorSistemaRN->excluir($objAdministradorSistemaRN->listar($objAdministradorSistemaDTO));

              $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
              $objCoordenadorPerfilDTO->retNumIdUsuario();
              $objCoordenadorPerfilDTO->retNumIdPerfil();
              $objCoordenadorPerfilDTO->retNumIdSistema();
              $objCoordenadorPerfilDTO->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
              $objCoordenadorPerfilRN->excluir($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO));

              $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO();
              $objCoordenadorUnidadeDTO->retNumIdUsuario();
              $objCoordenadorUnidadeDTO->retNumIdUnidade();
              $objCoordenadorUnidadeDTO->retNumIdSistema();
              $objCoordenadorUnidadeDTO->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
              $objCoordenadorUnidadeRN->excluir($objCoordenadorUnidadeRN->listar($objCoordenadorUnidadeDTO));
            }
          }
          
          //se nao tem mais permissao no básico do SIP na unidade
          $dto = new PermissaoDTO();
          $dto->setNumIdSistema($arrObjPermissaoDTO[$i]->getNumIdSistema());
          $dto->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
          $dto->setNumIdUnidade($arrObjPermissaoDTO[$i]->getNumIdUnidade());
          $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_BASICO'));
          
          if ($this->contar($dto)==0){
                    
            $dto = new PermissaoDTO();
            $dto->setNumIdSistema($arrObjPermissaoDTO[$i]->getNumIdSistema());
            $dto->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
            $dto->setNumIdUnidade($arrObjPermissaoDTO[$i]->getNumIdUnidade());
            
            $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA'));
            if ($this->contar($dto)){
              $objPermissaoBD->excluir($dto);
            }

            $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_ADMINISTRADOR_SIP'));
            if ($this->contar($dto)){
              $objPermissaoBD->excluir($dto);            
            }
            
            $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_PERFIL'));
            if ($this->contar($dto)){
              $objPermissaoBD->excluir($dto);            
            }
            
            $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_COORDENADOR_UNIDADE'));
            if ($this->contar($dto)){
              $objPermissaoBD->excluir($dto);            
            }
            
          }
        }
      }

      //obter conjunto distinto de sistema/usuario
      $arrUsuarioSistema = array();
      $arrUsuarioSistemaUnidade = array();
      for($i=0;$i<count($arrObjPermissaoDTO);$i++){
        
        for($j=0;$j<count($arrUsuarioSistema);$j++){
          if ($arrUsuarioSistema[$j]->getNumIdSistema()==$arrObjPermissaoDTO[$i]->getNumIdSistema() && 
              $arrUsuarioSistema[$j]->getNumIdUsuario()==$arrObjPermissaoDTO[$i]->getNumIdUsuario()){
            break;
          }
        }
        
        if ($j==count($arrUsuarioSistema)){
          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumIdSistema($arrObjPermissaoDTO[$i]->getNumIdSistema());
          $objPermissaoDTO->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
          $arrUsuarioSistema[] = $objPermissaoDTO;
        }
        
        ////
        for($j=0;$j<count($arrUsuarioSistemaUnidade);$j++){
          if ($arrUsuarioSistemaUnidade[$j]->getNumIdSistema()==$arrObjPermissaoDTO[$i]->getNumIdSistema() && 
              $arrUsuarioSistemaUnidade[$j]->getNumIdUsuario()==$arrObjPermissaoDTO[$i]->getNumIdUsuario() && 
              $arrUsuarioSistemaUnidade[$j]->getNumIdUnidade()==$arrObjPermissaoDTO[$i]->getNumIdUnidade()){
            break;
          }
        }
        
        if ($j==count($arrUsuarioSistemaUnidade)){
          $objPermissaoDTO = new PermissaoDTO();
          $objPermissaoDTO->setNumIdSistema($arrObjPermissaoDTO[$i]->getNumIdSistema());
          $objPermissaoDTO->setNumIdUsuario($arrObjPermissaoDTO[$i]->getNumIdUsuario());
          $objPermissaoDTO->setNumIdUnidade($arrObjPermissaoDTO[$i]->getNumIdUnidade());
          $arrUsuarioSistemaUnidade[] = $objPermissaoDTO;
        }
        
        
      }
      
      $objSistemaRN = new SistemaRN();

      foreach($arrUsuarioSistemaUnidade as $dto){
        //se o usuario não tem mais nenhuma permissao na unidade do sistema
        if ($this->contar($dto)==0){
          
          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->setBolExclusaoLogica(false);
          $objUnidadeDTO->retStrSinGlobal();
          $objUnidadeDTO->setNumIdUnidade($dto->getNumIdUnidade());
          
          $objUnidadeRN = new UnidadeRN();
          $objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);

          if ($objUnidadeDTO == null){
            throw new InfraException('Unidade ['.$dto->getNumIdUnidade().'] não encontrada.');
          }

          if ($objUnidadeDTO->getStrSinGlobal()=='N'){
            $objReplicacaoAssociacaoUsuarioUnidadeDTO = new ReplicacaoAssociacaoUsuarioUnidadeDTO();
            $objReplicacaoAssociacaoUsuarioUnidadeDTO->setStrStaOperacao('E');
            $objReplicacaoAssociacaoUsuarioUnidadeDTO->setNumIdSistema($dto->getNumIdSistema());
            $objReplicacaoAssociacaoUsuarioUnidadeDTO->setNumIdUsuario($dto->getNumIdUsuario());
            $objReplicacaoAssociacaoUsuarioUnidadeDTO->setNumIdUnidade($dto->getNumIdUnidade());
            $objSistemaRN->replicarAssociacaoUsuarioUnidade($objReplicacaoAssociacaoUsuarioUnidadeDTO);
          }
        }
      }

      foreach($arrUsuarioSistema as $dto){
        //se o usuario não tem mais nenhuma permissao no sistema
        if ($this->contar($dto)==0){
          $objReplicacaoUsuarioDTO = new ReplicacaoUsuarioDTO();
          $objReplicacaoUsuarioDTO->setStrStaOperacao('E');
          $objReplicacaoUsuarioDTO->setNumIdSistema($dto->getNumIdSistema());
          $objReplicacaoUsuarioDTO->setNumIdUsuario($dto->getNumIdUsuario());
          
          $objSistemaRN->replicarUsuario($objReplicacaoUsuarioDTO);
        }
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Permissão.',$e);
    }
  }

  protected function consultarConectado(PermissaoDTO $objPermissaoDTO){
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('permissao_consultar',__METHOD__,$objPermissaoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
      $ret = $objPermissaoBD->consultar($objPermissaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Permissão.',$e);
    }
  }

  protected function listarConectado(PermissaoDTO $objPermissaoDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('permissao_listar',__METHOD__,$objPermissaoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
      $ret = $objPermissaoBD->listar($objPermissaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Permissões.',$e);
    }
  }
  
  protected function listarUnidadesConectado(PermissaoDTO $objPermissaoDTO) {
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_listar',__METHOD__,$objPermissaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
      $ret = $objPermissaoBD->listarUnidades($objPermissaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando unidades das permissões.',$e);
    }
  }
  

  protected function contarConectado(PermissaoDTO $objPermissaoDTO) {
    try {

      //Valida Permissao
			/////////////////////////////////////////////////////////////////
      //SessaoSip::getInstance()->validarAuditarPermissao('permissao_contar',__METHOD__,$objPermissaoDTO);
			/////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
      $ret = $objPermissaoBD->contar($objPermissaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro contando Permissões.',$e);
    }
  }
  
	/**
	  Todas as permissoes do usuario.
	*/
  protected function listarPessoaisConectado(PermissaoDTO $objPermissaoDTO) {
    try {

      //Valida Permissao
			
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_listar_pessoais',__METHOD__,$objPermissaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

			$objPermissaoDTO->setNumIdUsuario(SessaoSip::getInstance()->getNumIdUsuario());

			$objInfraParametro = new InfraParametro(BancoSip::getInstance());
			$objPermissaoDTO->setNumIdPerfil($objInfraParametro->listarValores(array('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA',
              'ID_PERFIL_SIP_ADMINISTRADOR_SIP',
							'ID_PERFIL_SIP_COORDENADOR_PERFIL',
							'ID_PERFIL_SIP_COORDENADOR_UNIDADE')),InfraDTO::$OPER_NOT_IN);

      $ret = $this->listar($objPermissaoDTO);

			return $ret;
			

    }catch(Exception $e){
      throw new InfraException('Erro listando Permissões pessoais.',$e);
    }
  }
	
	/**
	  Todas as permissoes que o usuario possui acesso para administração:
		1) permissões de sistemas onde ele é administrador
		2) permissões de perfis que ele coordena
		3) permissões de unidades onde ele é coordenador (em perfis disponíveis aos coordenadores)
		
	*/
  protected function listarAdministradasConectado(PermissaoDTO $objPermissaoDTO) {
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('permissao_listar_administradas',__METHOD__,$objPermissaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      
      $ret = array();
      
      if (!InfraString::isBolVazia($objPermissaoDTO->getNumIdSistema())){

				$objUnidadeDTO = null;
				if ($objPermissaoDTO->isSetNumIdUnidade()) {
					$objUnidadeDTO = new UnidadeDTO();
					$objUnidadeDTO->setBolExclusaoLogica(false);
					$objUnidadeDTO->retNumIdUnidade();
					$objUnidadeDTO->retNumIdOrgao();
					$objUnidadeDTO->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

					$objUnidadeRN = new UnidadeRN();
					$objUnidadeDTO = $objUnidadeRN->consultar($objUnidadeDTO);
				}

			  $objAcessoDTO = new AcessoDTO();
			  $objAcessoDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
			  $objAcessoDTO->setNumTipo(AcessoDTO::$ADMINISTRADOR | AcessoDTO::$COORDENADOR_PERFIL | AcessoDTO::$COORDENADOR_UNIDADE);
			
				$objAcessoRN = new AcessoRN();
				$arrObjAcessoDTO = $objAcessoRN->obterAcessos($objAcessoDTO);

				if (count($arrObjAcessoDTO)) {

					$bolAdministrador = false;
					foreach ($arrObjAcessoDTO as $objAcessoDTO) {
						if ($objAcessoDTO->getNumTipo() == AcessoDTO::$ADMINISTRADOR) {
							$bolAdministrador = true;
							break;
						}
					}

					if (!$bolAdministrador) {

						$arrCriterios = array();

						$bolFlagPerfil = false;


						foreach ($arrObjAcessoDTO as $objAcessoDTO) {

							if ($objAcessoDTO->getNumTipo() == AcessoDTO::$COORDENADOR_PERFIL) {

								if ($objPermissaoDTO->isSetNumIdPerfil()) {
									if ($objPermissaoDTO->getNumIdPerfil() != $objAcessoDTO->getNumIdPerfil()) {
										continue;
									} else {
										$bolFlagPerfil = true;
									}
								}

								$strCriterio = 'Criterio' . count($arrCriterios);

								$objPermissaoDTO->adicionarCriterio(array('IdUnidade', 'IdPerfil'),
										array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IGUAL),
										array(null, $objAcessoDTO->getNumIdPerfil()),
										InfraDTO::$OPER_LOGICO_AND,
										$strCriterio);

								$arrCriterios[] = $strCriterio;

							} else if ($objAcessoDTO->getNumTipo() == AcessoDTO::$COORDENADOR_UNIDADE) {

								if ($objPermissaoDTO->isSetNumIdUnidade() &&
									   !(($objAcessoDTO->getStrSinGlobalUnidade()=='S' && $objAcessoDTO->getNumIdOrgaoUnidade()==$objUnidadeDTO->getNumIdOrgao()) ||
									     ($objAcessoDTO->getStrSinGlobalUnidade()=='N' && $objAcessoDTO->getNumIdUnidade()==$objUnidadeDTO->getNumIdUnidade()))){
									continue;
								}

								if ($objPermissaoDTO->isSetNumIdPerfil()) {
									if ($objAcessoDTO->getNumIdPerfil() != $objPermissaoDTO->getNumIdPerfil()) {
										continue;
									} else {
										$bolFlagPerfil = true;
									}
								}

								$strCriterio = 'Criterio' . count($arrCriterios);

								if ($objAcessoDTO->getStrSinGlobalUnidade()=='S'){
									$objPermissaoDTO->adicionarCriterio(array('IdOrgaoUnidade', 'IdPerfil'),
											array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
											array($objAcessoDTO->getNumIdOrgaoUnidade(), $objAcessoDTO->getNumIdPerfil()),
											InfraDTO::$OPER_LOGICO_AND,
											$strCriterio);

								}else{
									$objPermissaoDTO->adicionarCriterio(array('IdUnidade', 'IdPerfil'),
											array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
											array($objAcessoDTO->getNumIdUnidade(), $objAcessoDTO->getNumIdPerfil()),
											InfraDTO::$OPER_LOGICO_AND,
											$strCriterio);

								}

								$arrCriterios[] = $strCriterio;
							}
						}

						if (count($arrCriterios) > 1) {
							$objPermissaoDTO->agruparCriterios($arrCriterios, array_fill(0, count($arrCriterios) - 1, InfraDTO::$OPER_LOGICO_OR));
						}

						//se buscou por um perfil que não tem acesso
						if ($objPermissaoDTO->isSetNumIdPerfil() && !$bolFlagPerfil) {
							$objPermissaoDTO->setNumIdPerfil(null);
						}

					}

					$objPermissaoDTO->setDistinct(true);
					$objPermissaoDTO->retNumIdPerfil();
					$objPermissaoDTO->retNumIdSistema();
					$objPermissaoDTO->retNumIdUsuario();
					$objPermissaoDTO->retNumIdUnidade();


					$objInfraParametro = new InfraParametro(BancoSip::getInstance());
					$objPermissaoDTO->adicionarCriterio(array('IdPerfil'),
							                                array(InfraDTO::$OPER_NOT_IN),
							                                array($objInfraParametro->listarValores(array('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA',
																																														'ID_PERFIL_SIP_ADMINISTRADOR_SIP',
																																														'ID_PERFIL_SIP_COORDENADOR_PERFIL',
																																														'ID_PERFIL_SIP_COORDENADOR_UNIDADE'))));


					$ret = $this->listar($objPermissaoDTO);
				}
      }      
      
			return $ret;
			
    }catch(Exception $e){
      throw new InfraException('Erro listando Permissões administradas.',$e);
    }
  }
	
  private function validarNumIdPerfil(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
			if (InfraString::isBolVazia($objPermissaoDTO->getNumIdPerfil())){
				$objInfraException->adicionarValidacao('Perfil não informado.');
			}
	}
	
  private function validarNumIdSistema(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPermissaoDTO->getNumIdSistema())){
      $objInfraException->adicionarValidacao('Sistema não informado.');
    }
	}

  private function validarNumIdUsuario(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPermissaoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
	}

  private function validarNumIdUnidade(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPermissaoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
	}

  private function validarNumIdTipoPermissao(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPermissaoDTO->getNumIdTipoPermissao())){
      $objInfraException->adicionarValidacao('Tipo de Permissão não informado.');
    }
	}

  private function validarDtaDataInicio(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPermissaoDTO->getDtaDataInicio())){
      $objInfraException->adicionarValidacao('Data Inicial não informada.');
    }
		
    if (!InfraData::validarData($objPermissaoDTO->getDtaDataInicio())){
      $objInfraException->adicionarValidacao('Data Inicial inválida.');
    }
		
  }

	private function validarDtaDataFim(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (!InfraData::validarData($objPermissaoDTO->getDtaDataFim())){
      $objInfraException->adicionarValidacao('Data Final inválida.');
    }
		
		// if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objPermissaoDTO->getDtaDataFim())<0){
		//	$objInfraException->adicionarValidacao('Data Final não pode estar no passado.');
		//}
	}

	private function validarPeriodoDatas(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
    if (!InfraString::isBolVazia($objPermissaoDTO->getDtaDataFim())) {
      if (InfraData::compararDatas($objPermissaoDTO->getDtaDataInicio(), $objPermissaoDTO->getDtaDataFim()) < 0) {
        $objInfraException->adicionarValidacao('Data Final deve ser igual ou superior a Data Inicial.');
      }
    }
	}

	private function validarSinSubunidades(PermissaoDTO $objPermissaoDTO, InfraException $objInfraException){
		if ($objPermissaoDTO->getStrSinSubunidades()!=='S' && $objPermissaoDTO->getStrSinSubunidades()!=='N'){
			$objInfraException->adicionarValidacao('Sinalizador de estensão para subunidades inválido.');
		}
	}

	protected function adicionarPerfilReservadoControlado(PermissaoDTO $parObjPermissaoDTO){
	  try{
	    
	    $objInfraException = new InfraException();
	    
	    $objInfraParametro = new InfraParametro(BancoSip::getInstance());

			$objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());

      //verifica unidades onde o usuário tem permissão básica no SIP
      $objPermissaoDTO = new PermissaoDTO();
      $objPermissaoDTO->setDistinct(true);
      $objPermissaoDTO->retNumIdSistema();
      $objPermissaoDTO->retNumIdUnidade();
      $objPermissaoDTO->retNumIdUsuario();
      $objPermissaoDTO->retNumIdPerfil();
      $objPermissaoDTO->retDtaDataInicio();
      $objPermissaoDTO->retDtaDataFim();
      $objPermissaoDTO->setNumIdSistema($objInfraParametro->getValor('ID_SISTEMA_SIP'));
      $objPermissaoDTO->setNumIdUsuario($parObjPermissaoDTO->getNumIdUsuario());
      $objPermissaoDTO->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_BASICO'));

      $arrObjPermissaoDTO = $this->listar($objPermissaoDTO);
      
      if (count($arrObjPermissaoDTO)==0){
        
        $dto = new PermissaoDTO();
        $dto->setNumIdSistema($objInfraParametro->getValor('ID_SISTEMA_SIP'));
        $dto->setNumIdUsuario($parObjPermissaoDTO->getNumIdUsuario());
        $dto->setNumIdPerfil($objInfraParametro->getValor('ID_PERFIL_SIP_BASICO'));
        $dto->setNumIdUnidade(SessaoSip::getInstance()->getNumIdUnidadeAtual());
			  $dto->setNumIdTipoPermissao(1);
			  $dto->setStrSinSubunidades('N');
			  $dto->setDtaDataInicio(InfraData::getStrDataAtual());
			  $dto->setDtaDataFim(null);
				$objPermissaoBD->cadastrar($dto);
			  $arrObjPermissaoDTO = array($dto);
      }else{

        foreach($arrObjPermissaoDTO as $objPermissaoDTO){
          if (InfraData::compararDatas($objPermissaoDTO->getDtaDataInicio(),InfraData::getStrDataAtual())<0){
            $objPermissaoDTO->setDtaDataInicio(InfraData::getStrDataAtual());
          }
          $objPermissaoDTO->setDtaDataFim(null);
          $objPermissaoBD->alterar($objPermissaoDTO);
        }
      }

      $objInfraException->lancarValidacoes();

      
      foreach($arrObjPermissaoDTO as $objPermissaoDTO){

        $dto = new PermissaoDTO();
        $dto->retNumIdSistema();
        $dto->retNumIdUnidade();
        $dto->retNumIdUsuario();
        $dto->retNumIdPerfil();
        $dto->retDtaDataInicio();
        $dto->retDtaDataFim();
        $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
        $dto->setNumIdUsuario($objPermissaoDTO->getNumIdUsuario());
        $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());

        $dto->setNumIdPerfil($parObjPermissaoDTO->getNumIdPerfil());

        $arrObjPermissaoDTOReservado = $this->listar($dto);

        //Se o usuário não tem permissão neste perfil (independente da unidade)
        if (count($arrObjPermissaoDTOReservado)==0){
          //Assume a unidade do usuário atual
          $dto->setNumIdTipoPermissao(1); //Nao delegavel
          $dto->setStrSinSubunidades('N');
          $dto->setDtaDataInicio(InfraData::getStrDataAtual());
          $dto->setDtaDataFim(null);
          $objPermissaoBD->cadastrar($dto);
        }else{

          foreach($arrObjPermissaoDTOReservado as $objPermissaoDTOReservado){
            if (InfraData::compararDatas($objPermissaoDTOReservado->getDtaDataInicio(),InfraData::getStrDataAtual())<0){
              $objPermissaoDTOReservado->setDtaDataInicio(InfraData::getStrDataAtual());
            }
            $objPermissaoDTOReservado->setDtaDataFim(null);
            $objPermissaoBD->alterar($objPermissaoDTOReservado);
          }
        }
      }
	  }catch(Exception $e){
			throw new InfraException('Erro adicionando permissões em perfil reservado.',$e);
		}
	}

	protected function removerPerfilReservadoControlado(PermissaoDTO $parObjPermissaoDTO){
		try {

			//Valida Permissao
			SessaoSip::getInstance()->validarAuditarPermissao('permissao_excluir',__METHOD__,$parObjPermissaoDTO);

			$objInfraParametro = new InfraParametro(BancoSip::getInstance());

			$objInfraException = new InfraException();

			if (!in_array($parObjPermissaoDTO->getNumIdPerfil(),$objInfraParametro->listarValores(array('ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA',
					'ID_PERFIL_SIP_ADMINISTRADOR_SIP',
					'ID_PERFIL_SIP_COORDENADOR_PERFIL',
					'ID_PERFIL_SIP_COORDENADOR_UNIDADE')))){
				$objInfraException->lancarValidacao('Perfil solicitado para exclusão não é reservado.');
			}

			$objInfraException->lancarValidacoes();

			$objPermissaoDTO = new PermissaoDTO();
			$objPermissaoDTO->retNumIdSistema();
			$objPermissaoDTO->retNumIdUsuario();
			$objPermissaoDTO->retNumIdPerfil();
			$objPermissaoDTO->retNumIdUnidade();
			$objPermissaoDTO->setNumIdSistema($objInfraParametro->getValor('ID_SISTEMA_SIP'));
			$objPermissaoDTO->setNumIdUsuario($parObjPermissaoDTO->getNumIdUsuario());
			$objPermissaoDTO->setNumIdPerfil($parObjPermissaoDTO->getNumIdPerfil());

			$arrObjPermissaoDTO = $this->listar($objPermissaoDTO);

			$objPermissaoBD = new PermissaoBD($this->getObjInfraIBanco());
			foreach($arrObjPermissaoDTO as $objPermissaoDTO){
				$objPermissaoBD->excluir($objPermissaoDTO);
			}

		}catch(Exception $e){
			throw new InfraException('Erro removendo permissão em perfil reservado.',$e);
		}
	}
	
	protected function atribuirPermissoesBlocoControlado(PermissaoDTO $objPermissaoDTO){
	
	  try{
	
	    $ret = array();
	    
	    $objInfraException = new InfraException();
	
	    $objSistemaRN = new SistemaRN();
	    $objSistemaDTO = new SistemaDTO();
	    $objSistemaDTO->retNumIdSistema();
	    $objSistemaDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
	    $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
	
	    if ($objSistemaDTO==null){
	      $objInfraException->lancarValidacao('Sistema não encontrado.');
	    }
	
	    $objPerfilDTO = new PerfilDTO();
	    $objPerfilDTO->retNumIdPerfil();
	    $objPerfilDTO->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
	    $objPerfilDTO->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
	
	    $objPerfilRN = new PerfilRN();
	    $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);
	
	    if ($objPerfilDTO==null){
	      $objInfraException->lancarValidacao('Perfil não encontrado.');
	    }
	
	    $objOrgaoDTO = new OrgaoDTO();
	    $objOrgaoDTO->retNumIdOrgao();
	    $objOrgaoDTO->retStrSigla();
	
	    $objOrgaoRN = new OrgaoRN();
	    $arrObjOrgaoDTO = $objOrgaoRN->listar($objOrgaoDTO);

			foreach($arrObjOrgaoDTO as $objOrgaoDTO){
				$objOrgaoDTO->setStrSigla(InfraString::transformarCaixaAlta($objOrgaoDTO->getStrSigla()));
			}

	    $arrOrgao = InfraArray::mapearArrInfraDTO($arrObjOrgaoDTO,'Sigla','IdOrgao');

	
	    $objUsuarioRN = new UsuarioRN();
	    $arrObjUsuarioDTO = $objPermissaoDTO->getArrObjUsuarioDTO();
	    foreach($arrObjUsuarioDTO as $objUsuarioDTO){
	
	      $objUsuarioDTO->setStrSiglaOrgao(InfraString::transformarCaixaAlta(trim($objUsuarioDTO->getStrSiglaOrgao())));
	      $objUsuarioDTO->setStrSigla(InfraString::transformarCaixaBaixa(trim($objUsuarioDTO->getStrSigla())));
	
	
	      if (!isset($arrOrgao[$objUsuarioDTO->getStrSiglaOrgao()])){
	        $objInfraException->adicionarValidacao('Órgão '.$objUsuarioDTO->getStrSiglaOrgao().' do usuário '.$objUsuarioDTO->getStrSigla().' inválido.');
	      }
	
	      $dto = new UsuarioDTO();
	      $dto->retNumIdUsuario();
	      $dto->setNumIdOrgao($arrOrgao[$objUsuarioDTO->getStrSiglaOrgao()]);
	      $dto->setStrSigla($objUsuarioDTO->getStrSigla());
	
	      $dto = $objUsuarioRN->consultar($dto);
	      if ($dto==null){
          $objInfraException->adicionarValidacao('Usuário '.$objUsuarioDTO->getStrSigla().'/'.$objUsuarioDTO->getStrSiglaOrgao().' não cadastrado.');
	      }else{
	        $objUsuarioDTO->setNumIdUsuario($dto->getNumIdUsuario());
	      }
	    }
	
	    $objInfraException->lancarValidacoes();
	
	    $objPermissaoRN = new PermissaoRN();
	
	    foreach($arrObjUsuarioDTO as $objUsuarioDTO){
	
	      $strUsuario = $objUsuarioDTO->getStrSigla().'/'.$objUsuarioDTO->getStrSiglaOrgao();
	
	      $dto = new PermissaoDTO();
	      $dto->setNumIdSistema($objPermissaoDTO->getNumIdSistema());
	      $dto->setNumIdUnidade($objPermissaoDTO->getNumIdUnidade());
	      $dto->setNumIdPerfil($objPermissaoDTO->getNumIdPerfil());
	      $dto->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());
	
	      if ($objPermissaoRN->contar($dto)==0){
	        $dto->setNumIdTipoPermissao(1);
	        $dto->setDtaDataInicio(InfraData::getStrDataAtual());
	        $dto->setDtaDataFim(null);
	        $dto->setStrSinSubunidades('N');
	        $ret[] = $objPermissaoRN->cadastrar($dto);
	      }
	    }
	
	    return $ret;
	    
	  }catch(Exception $e){
	    throw new InfraException('Erro atribuindo permissões em bloco.',$e);
	  }
	}
		
}
?>