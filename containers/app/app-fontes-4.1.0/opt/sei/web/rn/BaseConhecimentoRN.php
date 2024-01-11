<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class BaseConhecimentoRN extends InfraRN {

  public static $TE_VERSAO_ANTERIOR = 'V';
  public static $TE_LIBERADO 				= 'L';
  public static $TE_RASCUNHO 				= 'R';	
	
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrDescricao(BaseConhecimentoDTO $objBaseConhecimentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objBaseConhecimentoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objBaseConhecimentoDTO->setStrDescricao(trim($objBaseConhecimentoDTO->getStrDescricao()));

      if (strlen($objBaseConhecimentoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function listarAssociadasConectado(TipoProcedimentoDTO $objTipoProcedimentoDTO){
  	try{
  		
  		$objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
  		$objRelBaseConhecTipoProcedDTO->retNumIdBaseConhecimento();
  		$objRelBaseConhecTipoProcedDTO->setNumIdTipoProcedimento($objTipoProcedimentoDTO->getNumIdTipoProcedimento());
  		$objRelBaseConhecTipoProcedDTO->setStrStaEstadoBaseConhecimento(self::$TE_LIBERADO);
  		
  		$objRelBaseConhecTipoProcedRN = new RelBaseConhecTipoProcedRN();
  		$arrObjRelBaseConhecTipoProcedDTO = $objRelBaseConhecTipoProcedRN->listar($objRelBaseConhecTipoProcedDTO);

  		$ret = array();
  		if (count($arrObjRelBaseConhecTipoProcedDTO)){
  			
			  $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
			  $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
			  $objBaseConhecimentoDTO->retNumIdBaseConhecimentoOrigem();
			  $objBaseConhecimentoDTO->retNumIdBaseConhecimentoAgrupador();
			  $objBaseConhecimentoDTO->retNumIdUnidade();
			  $objBaseConhecimentoDTO->retNumIdUsuarioGerador();
			  $objBaseConhecimentoDTO->retNumIdUsuarioLiberacao();
			  $objBaseConhecimentoDTO->retStrNomeUsuarioGerador();
			  $objBaseConhecimentoDTO->retStrNomeUsuarioLiberacao();
			  $objBaseConhecimentoDTO->retStrSiglaUsuarioGerador();
			  $objBaseConhecimentoDTO->retStrSiglaUsuarioLiberacao();
			  $objBaseConhecimentoDTO->retStrSiglaUnidade();
			  $objBaseConhecimentoDTO->retDthGeracao();
			  $objBaseConhecimentoDTO->retDthLiberacao();
			  $objBaseConhecimentoDTO->retStrDescricao();
			  $objBaseConhecimentoDTO->retStrDescricaoUnidade();
			  $objBaseConhecimentoDTO->retDblIdDocumentoEdoc();
			  $objBaseConhecimentoDTO->retStrStaDocumento();
		  
			  $objBaseConhecimentoDTO->setNumIdBaseConhecimento(InfraArray::converterArrInfraDTO($arrObjRelBaseConhecTipoProcedDTO,'IdBaseConhecimento'),InfraDTO::$OPER_IN);

			  $objBaseConhecimentoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
			  $objBaseConhecimentoDTO->setOrdDthLiberacao(InfraDTO::$TIPO_ORDENACAO_DESC);

			  $ret = $this->listar($objBaseConhecimentoDTO);
  		}
  		 
  		
  		return $ret;
  		
   }catch(Exception $e){
      throw new InfraException('Erro listando bases de conhecimento associadas.',$e);
    }
  }

  protected function gerarNovaVersaoControlado($objBaseConhecimentoDTO){
  	try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_nova_versao',__METHOD__,$objBaseConhecimentoDTO);
  		
      $objInfraException = new InfraException();
      
  		$objBaseConhecimentoDTOOrigem = new BaseConhecimentoDTO();
  		$objBaseConhecimentoDTOOrigem->retNumIdBaseConhecimento();
  		$objBaseConhecimentoDTOOrigem->retNumIdBaseConhecimentoAgrupador();
  		$objBaseConhecimentoDTOOrigem->retStrDescricao();
  		$objBaseConhecimentoDTOOrigem->retStrStaEstado();
  		$objBaseConhecimentoDTOOrigem->retStrStaDocumento();
  		$objBaseConhecimentoDTOOrigem->retDblIdDocumentoEdoc();
  		$objBaseConhecimentoDTOOrigem->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimentoOrigem());
  		
  		$objBaseConhecimentoDTOOrigem = $this->consultar($objBaseConhecimentoDTOOrigem);
  		
  		if ($objBaseConhecimentoDTOOrigem->getStrStaEstado()!=self::$TE_LIBERADO){
  	    $objInfraException->adicionarValidacao('Procedimento "'.$objBaseConhecimentoDTOOrigem->getStrDescricao().'" não consta como liberado.');		
  		}
      
      if ($objBaseConhecimentoDTOOrigem->getStrStaDocumento()!=DocumentoRN::$TD_EDITOR_INTERNO) {
        $objInfraException->adicionarValidacao('Só é possível gerar uma nova versão para bases de conhecimento que foram criadas no editor interno.');
      }

      $objInfraException->lancarValidacoes();

      $objBaseConhecimentoDTO->setNumIdBaseConhecimentoBase($objBaseConhecimentoDTOOrigem->getNumIdBaseConhecimento());

			$dto = new BaseConhecimentoDTO();
			$dto->setStrStaEstado(BaseConhecimentoRN::$TE_VERSAO_ANTERIOR);        
      $dto->setNumIdBaseConhecimento($objBaseConhecimentoDTOOrigem->getNumIdBaseConhecimento());
      $this->alterar($dto);

      //complementa dados não informados
      $objBaseConhecimentoDTO->setStrStaDocumento($objBaseConhecimentoDTOOrigem->getStrStaDocumento());
  		$objBaseConhecimentoDTO->setNumIdBaseConhecimentoAgrupador($objBaseConhecimentoDTOOrigem->getNumIdBaseConhecimentoAgrupador());
      $objBaseConhecimentoDTO->setStrStaEstado(BaseConhecimentoRN::$TE_RASCUNHO);
      $objBaseConhecimentoDTO->setNumIdUsuarioGerador(SessaoSEI::getInstance()->getNumIdUsuario());
      $objBaseConhecimentoDTO->setDthGeracao(InfraData::getStrDataHoraAtual());
      $objBaseConhecimentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $ret = $this->cadastrar($objBaseConhecimentoDTO);
            
      return $ret;
  			
    }catch(Exception $e){
      throw new InfraException('Erro gerando nova versão do Procedimento.',$e);
    }
  }
  
  protected function prepararCloneConectado(BaseConhecimentoDTO $parObjBaseConhecimentoDTO){
    try{
    	
    	$objBaseConhecimentoDTO = new BaseConhecimentoDTO();
    	$objBaseConhecimentoDTO->retStrDescricao();
    	$objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjBaseConhecimentoDTO->getNumIdBaseConhecimentoOrigem());
      
      $objBaseConhecimentoDTO = $this->consultar($objBaseConhecimentoDTO);

      $objBaseConhecimentoDTO->setNumIdBaseConhecimento(null);
      $objBaseConhecimentoDTO->setNumIdBaseConhecimentoOrigem($parObjBaseConhecimentoDTO->getNumIdBaseConhecimentoOrigem());
      
      
      $objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
      $objRelBaseConhecTipoProcedDTO->retNumIdTipoProcedimento();
      $objRelBaseConhecTipoProcedDTO->retStrNomeTipoProcedimento(); 
      $objRelBaseConhecTipoProcedDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimentoOrigem());

      $objRelBaseConhecTipoProcedRN = new RelBaseConhecTipoProcedRN();
      $objBaseConhecimentoDTO->setArrObjRelBaseConhecTipoProcedDTO($objRelBaseConhecTipoProcedRN->listar($objRelBaseConhecTipoProcedDTO));
      
    	$objAnexoDTO = new AnexoDTO();
    	$objAnexoDTO->retNumIdAnexo();
    	$objAnexoDTO->retStrNome();
    	$objAnexoDTO->retNumTamanho();
    	$objAnexoDTO->retDthInclusao();
    	$objAnexoDTO->setNumIdBaseConhecimento($parObjBaseConhecimentoDTO->getNumIdBaseConhecimentoOrigem());
    	
    	$objAnexoRN = new AnexoRN();
    	$arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

    	$arrObjAnexoDTONovos = array();
    	
    	foreach($arrObjAnexoDTO as $objAnexoDTO){

				$strNomeUpload = $objAnexoRN->gerarNomeArquivoTemporario();
        
    		$strNomeUploadCompleto = DIR_SEI_TEMP.'/'.$strNomeUpload;
        
        copy($objAnexoRN->obterLocalizacao($objAnexoDTO), $strNomeUploadCompleto);
        
        $objAnexoDTONovo = new AnexoDTO();
        $objAnexoDTONovo->setNumIdAnexo($strNomeUpload);
        $objAnexoDTONovo->setNumIdAnexoOrigem($objAnexoDTO->getNumIdAnexo());
        $objAnexoDTONovo->setStrNome($objAnexoDTO->getStrNome());
        $objAnexoDTONovo->setDthInclusao(InfraData::getStrDataHoraAtual());
        $objAnexoDTONovo->setNumTamanho($objAnexoDTO->getNumTamanho());
        $objAnexoDTONovo->setStrSiglaUsuario(SessaoSEI::getInstance()->getStrSiglaUsuario());
        $objAnexoDTONovo->setStrSiglaUnidade(SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());
        
        $arrObjAnexoDTONovos[] = $objAnexoDTONovo;
    	}    	
    	
    	$objBaseConhecimentoDTO->setArrObjAnexoDTO($arrObjAnexoDTONovos);	
    	
    	return $objBaseConhecimentoDTO;
  	
    }catch(Exception $e){
      throw new InfraException('Erro preparando clone da Base de Conhecimento.',$e);
    }    
  }

  public function liberar($arrObjBaseConhecimentoDTO){

    FeedSEIBasesConhecimento::getInstance()->setBolAcumularFeeds(true);

    $this->liberarInterno($arrObjBaseConhecimentoDTO);

    FeedSEIBasesConhecimento::getInstance()->setBolAcumularFeeds(false);
    FeedSEIBasesConhecimento::getInstance()->indexarFeeds();
  }

  protected function liberarInternoControlado($arrObjBaseConhecimentoDTO){
  	try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_liberar',__METHOD__,$arrObjBaseConhecimentoDTO);
  		
      $objInfraException = new InfraException();
      
  		foreach($arrObjBaseConhecimentoDTO as $objBaseConhecimentoDTO){
  			$dto = new BaseConhecimentoDTO();
  			$dto->retStrDescricao();
  			$dto->retStrStaEstado();
  			$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
  			
  			$dto = $this->consultar($dto);
  			
  			if ($dto->getStrStaEstado()!=self::$TE_RASCUNHO){
  		    $objInfraException->adicionarValidacao('Procedimento "'.$dto->getStrDescricao().'" não consta como rascunho.');		
  			}
  		}
      
  		$objInfraException->lancarValidacoes();
  		
  		$arrObjBaseConhecimentoDTORemocao = array();
  		
  		foreach($arrObjBaseConhecimentoDTO as $objBaseConhecimentoDTO){
  			
  			$dto = new BaseConhecimentoDTO();
  			$dto->retNumIdBaseConhecimentoOrigem();
  			$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
  			
  			$dto = $this->consultar($dto);
  			
  			if ($dto->getNumIdBaseConhecimentoOrigem()!=null){
  				$objBaseConhecimentoDTORemocao = new BaseConhecimentoDTO();
  				$objBaseConhecimentoDTORemocao->setNumIdBaseConhecimento($dto->getNumIdBaseConhecimentoOrigem());
  				$arrObjBaseConhecimentoDTORemocao[] = $objBaseConhecimentoDTORemocao;
  			}

  			$dto = new BaseConhecimentoDTO();
				$dto->setStrStaEstado(BaseConhecimentoRN::$TE_LIBERADO);
				$dto->setNumIdUsuarioLiberacao(SessaoSEI::getInstance()->getNumIdUsuario());		
				$dto->setDthLiberacao(InfraData::getStrDataHoraAtual());
				$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
				
        $this->alterar($dto);
  		}

  		$objIndexacaoRN 	= new IndexacaoRN();

  		if (count($arrObjBaseConhecimentoDTORemocao)){
			  $objIndexacaoDTO 	= new IndexacaoDTO(); 
			  $objIndexacaoDTO->setArrObjBaseConhecimentoDTO($arrObjBaseConhecimentoDTORemocao);
			  $objIndexacaoRN->prepararRemocaoBaseConhecimento($objIndexacaoDTO);
  		}
  		
		  $objIndexacaoDTO 	= new IndexacaoDTO();
		  $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_BASE_CONHECIMENTO_LIBERAR);
		  $objIndexacaoDTO->setArrObjBaseConhecimentoDTO($arrObjBaseConhecimentoDTO);
		  $objIndexacaoRN->indexarBaseConhecimento($objIndexacaoDTO);
		  
    }catch(Exception $e){
      throw new InfraException('Erro liberando Procedimento.',$e);
    }
  }

  public function cancelarLiberacao($arrObjBaseConhecimentoDTO){
    FeedSEIBasesConhecimento::getInstance()->setBolAcumularFeeds(true);

    $this->cancelarLiberacaoInterno($arrObjBaseConhecimentoDTO);

    FeedSEIBasesConhecimento::getInstance()->setBolAcumularFeeds(false);
    FeedSEIBasesConhecimento::getInstance()->indexarFeeds();
  }

  protected function cancelarLiberacaoInternoControlado($arrObjBaseConhecimentoDTO){
  	try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_cancelar_liberacao',__METHOD__,$arrObjBaseConhecimentoDTO);
  		
      $objInfraException = new InfraException();
      
  		foreach($arrObjBaseConhecimentoDTO as $objBaseConhecimentoDTO){
  			$dto = new BaseConhecimentoDTO();
  			$dto->retStrDescricao();
  			$dto->retStrStaEstado();
  			$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
  			
  			$dto = $this->consultar($dto);
  			
  			if ($dto->getStrStaEstado()!=self::$TE_LIBERADO){
  		    $objInfraException->adicionarValidacao('Procedimento "'.$dto->getStrDescricao().'" não consta como liberado.');		
  			}
  		}
      
  		$objInfraException->lancarValidacoes();
  		
  		$arrObjBaseConhecimentoDTOLiberacao = array();
  		
      foreach($arrObjBaseConhecimentoDTO as $objBaseConhecimentoDTO){
  			
  			$dto = new BaseConhecimentoDTO();
  			$dto->retNumIdBaseConhecimentoOrigem();
  			$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
  			
  			$dto = $this->consultar($dto);

  			if ($dto->getNumIdBaseConhecimentoOrigem()!=null){
  	  		$objBaseConhecimentoDTOLiberacao = new BaseConhecimentoDTO();
  	  		$objBaseConhecimentoDTOLiberacao->setNumIdBaseConhecimento($dto->getNumIdBaseConhecimentoOrigem());
  	  		$arrObjBaseConhecimentoDTOLiberacao[] = $objBaseConhecimentoDTOLiberacao;
  			}

  			$dto = new BaseConhecimentoDTO();
				$dto->setStrStaEstado(BaseConhecimentoRN::$TE_RASCUNHO);
				$dto->setNumIdUsuarioLiberacao(null);		
				$dto->setDthLiberacao(null);
				$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
				
        $this->alterar($dto);
      }

      $objIndexacaoRN 	= new IndexacaoRN();
      
      if (count($arrObjBaseConhecimentoDTO)){
			  $objIndexacaoDTO 	= new IndexacaoDTO(); 
			  $objIndexacaoDTO->setArrObjBaseConhecimentoDTO($arrObjBaseConhecimentoDTO);
			  $objIndexacaoRN->prepararRemocaoBaseConhecimento($objIndexacaoDTO);
      }

		  $objIndexacaoDTO 	= new IndexacaoDTO();
		  $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_BASE_CONHECIMENTO_CANCELAR_LIBERACAO);
		  $objIndexacaoDTO->setArrObjBaseConhecimentoDTO($arrObjBaseConhecimentoDTOLiberacao);
		  $objIndexacaoRN->indexarBaseConhecimento($objIndexacaoDTO);
		  
    }catch(Exception $e){
      throw new InfraException('Erro cancelando liberação de Procedimento.',$e);
    }
  }
  
  protected function cadastrarControlado(BaseConhecimentoDTO $objBaseConhecimentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_cadastrar',__METHOD__,$objBaseConhecimentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarStrDescricao($objBaseConhecimentoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objBaseConhecimentoDTO->setStrStaDocumento(DocumentoRN::$TD_EDITOR_INTERNO);

      $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
      $ret = $objBaseConhecimentoBD->cadastrar($objBaseConhecimentoDTO);

      // Cadastra os tipos de processo associados
      if (InfraArray::contar($objBaseConhecimentoDTO->getArrObjRelBaseConhecTipoProcedDTO()) > 0){
	      $objRelBaseConhecTipoProcedRN = new RelBaseConhecTipoProcedRN();
	      foreach($objBaseConhecimentoDTO->getArrObjRelBaseConhecTipoProcedDTO() as $objRelBaseConhecTipoProcedDTO){
	      	$objRelBaseConhecTipoProcedDTO->setNumIdBaseConhecimento($ret->getNumIdBaseConhecimento());
	      	$objRelBaseConhecTipoProcedRN->cadastrar($objRelBaseConhecTipoProcedDTO);
	      }
      }      
      
      // Cadastra os anexos da Base de conhecimento
      if (InfraArray::contar($objBaseConhecimentoDTO->getArrObjAnexoDTO()) > 0){
	      $objAnexoRN = new AnexoRN();
	      $arrAnexos = $objBaseConhecimentoDTO->getArrObjAnexoDTO();
	      for($i=0;$i<InfraArray::contar($arrAnexos);$i++){
	        $arrAnexos[$i]->setDblIdProtocolo(null);
	      	$arrAnexos[$i]->setNumIdBaseConhecimento($ret->getNumIdBaseConhecimento());
	      	$arrAnexos[$i]->setNumIdProjeto(null);
	        $arrAnexos[$i]->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
	        $arrAnexos[$i]->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
	        $arrAnexos[$i]->setStrSinAtivo('S');
	        $objAnexoDTO = $objAnexoRN->cadastrarRN0172($arrAnexos[$i]);
	      }
      }
      
			if ($objBaseConhecimentoDTO->getNumIdBaseConhecimentoAgrupador() == null){
	      // Seta o valor para o Agrupador     
	      $objBaseConhecimentoDTO->setNumIdBaseConhecimentoAgrupador($ret->getNumIdBaseConhecimento());
	      $objBaseConhecimentoBD->alterar($objBaseConhecimentoDTO);
			} 
			     
			$objBaseConhecimentoDTO->setDblIdDocumentoEdocBase(null);

      $objEditorDTO = new EditorDTO();
      $objEditorDTO->setDblIdDocumento(null);
      $objEditorDTO->setNumIdBaseConhecimento($ret->getNumIdBaseConhecimento());

      if ($objBaseConhecimentoDTO->isSetNumIdBaseConhecimentoBase()){
        $objEditorDTO->setNumIdBaseConhecimentoBase($objBaseConhecimentoDTO->getNumIdBaseConhecimentoBase());
      }

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdModelo = $objInfraParametro->getValor('ID_MODELO_INTERNO_BASE_CONHECIMENTO');

      if (InfraString::isBolVazia($numIdModelo)){
        throw new InfraException('Parâmetro ID_MODELO_INTERNO_BASE_CONHECIMENTO não foi configurado.');
      }

      $objModeloDTO = new ModeloDTO();
      $objModeloDTO->setBolExclusaoLogica(false);
      $objModeloDTO->retNumIdModelo();
      $objModeloDTO->setNumIdModelo($numIdModelo);

      $objModeloRN = new ModeloRN();
      if ($objModeloRN->consultar($objModeloDTO)==null){
        throw new InfraException('Parâmetro ID_MODELO_INTERNO_BASE_CONHECIMENTO contém um identificador de modelo inexistente.');
      }


      $objEditorDTO->setNumIdModelo($numIdModelo);

      $arrConteudoInicialSecoes = array();
      $arrConteudoInicialSecoes['Título'] = $objBaseConhecimentoDTO->getStrDescricao();
      $objEditorDTO->setArrConteudoInicialSecoes($arrConteudoInicialSecoes);

      $objEditorRN = new EditorRN();
      $objEditorRN->gerarVersaoInicial($objEditorDTO);

      //Auditoria
      
      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Procedimento.',$e);
    }
  }

  protected function alterarControlado(BaseConhecimentoDTO $objBaseConhecimentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_alterar',__METHOD__,$objBaseConhecimentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

			$objBaseConhecimentoDTOBanco = new BaseConhecimentoDTO();
			$objBaseConhecimentoDTOBanco->retStrDescricao();
			$objBaseConhecimentoDTOBanco->retStrStaDocumento();
			$objBaseConhecimentoDTOBanco->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());

			$objBaseConhecimentoDTOBanco = $this->consultar($objBaseConhecimentoDTOBanco);

      if ($objBaseConhecimentoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objBaseConhecimentoDTO, $objInfraException);
      }else{
				$objBaseConhecimentoDTO->setStrDescricao($objBaseConhecimentoDTOBanco->getStrDescricao());
			}

      $objInfraException->lancarValidacoes();

      $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
      
      $ret = $objBaseConhecimentoBD->alterar($objBaseConhecimentoDTO);

      // Tipos de Processo
      if ($objBaseConhecimentoDTO->isSetArrObjRelBaseConhecTipoProcedDTO()){
	      
	      $objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
	      $objRelBaseConhecTipoProcedDTO->retNumIdBaseConhecimento();
	      $objRelBaseConhecTipoProcedDTO->retNumIdTipoProcedimento(); 
	      $objRelBaseConhecTipoProcedDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());

	      $objRelBaseConhecTipoProcedRN = new RelBaseConhecTipoProcedRN();
	      $objRelBaseConhecTipoProcedRN->excluir($objRelBaseConhecTipoProcedRN->listar($objRelBaseConhecTipoProcedDTO));
	      
	      foreach($objBaseConhecimentoDTO->getArrObjRelBaseConhecTipoProcedDTO() as $objRelBaseConhecTipoProcedDTO){
	      	$objRelBaseConhecTipoProcedDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
	      	$objRelBaseConhecTipoProcedRN->cadastrar($objRelBaseConhecTipoProcedDTO);
	      }
      }      
      
      // Anexos
	    if ($objBaseConhecimentoDTO->isSetArrObjAnexoDTO()){
	        $objAnexoRN = new AnexoRN();
	        $objAnexoDTO = new AnexoDTO();
	        $objAnexoDTO->retNumIdAnexo();
	        $objAnexoDTO->retNumIdUnidade();
	        $objAnexoDTO->retStrNome();
	        $objAnexoDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
	        $arrAnexosAntigos = $objAnexoRN->listarRN0218($objAnexoDTO);
	        
	        $arrAnexosNovos = $objBaseConhecimentoDTO->getArrObjAnexoDTO();
	        
	        $arrRemocao = array();
	        foreach($arrAnexosAntigos as $anexoAntigo){
	          $flagRemover = true;
	          foreach($arrAnexosNovos as $anexoNovo){
	            if ($anexoAntigo->getNumIdAnexo()==$anexoNovo->getNumIdAnexo()){
	              $flagRemover = false;
	              break;
	            }
	          }
	          if ($flagRemover){
	            $arrRemocao[] = $anexoAntigo;
	          }
	        }
	        
	        foreach($arrRemocao as $anexoRemover){
	          if ($anexoRemover->getNumIdUnidade()<>SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
	            $objUnidadeRN = new UnidadeRN();
	            $objUnidadeDTO = new UnidadeDTO();
	            $objUnidadeDTO->retStrSigla();
	            $objUnidadeDTO->setNumIdUnidade($anexoRemover->getNumIdUnidade());
	            $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);
	            $objInfraException->adicionarValidacao('O anexo "'.$anexoRemover->getStrNome().'" não pode ser excluído porque foi adicionado por outra unidade ('.$objUnidadeDTO->getStrSigla().').');
	          }
	        }
	        $objAnexoRN->excluirRN0226($arrRemocao);
	        
	        foreach($arrAnexosNovos as $anexoNovo){
	          if (!is_numeric($anexoNovo->getNumIdAnexo())){
	            $anexoNovo->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
	            $anexoNovo->setDblIdProtocolo(null);
	            $anexoNovo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
	            $anexoNovo->setNumIdProjeto(null);
	            $anexoNovo->setStrSinAtivo('S');
	            $objAnexoRN->cadastrarRN0172($anexoNovo);
	          }
	        }
	    }

			if ($objBaseConhecimentoDTOBanco->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO && $objBaseConhecimentoDTO->getStrDescricao()!=$objBaseConhecimentoDTOBanco->getStrDescricao()) {
				$objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
				$objVersaoSecaoDocumentoDTO->retNumIdSecaoDocumento();
				$objVersaoSecaoDocumentoDTO->retStrConteudo();
				$objVersaoSecaoDocumentoDTO->retNumIdSecaoModeloSecaoDocumento();
				$objVersaoSecaoDocumentoDTO->retStrNomeSecaoModelo();
				$objVersaoSecaoDocumentoDTO->setNumIdBaseConhecimentoSecaoDocumento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
				$objVersaoSecaoDocumentoDTO->setStrSinUltima('S');
				$objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_ASC);

				$objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
				$arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

				$arrObjSecaoDocumentoDTO = array();
				foreach ($arrObjVersaoSecaoDocumentoDTO as $objVersaoSecaoDocumentoDTO) {
					$objSecaoDocumentoDTO = new SecaoDocumentoDTO();
					$objSecaoDocumentoDTO->setNumIdSecaoModelo($objVersaoSecaoDocumentoDTO->getNumIdSecaoModeloSecaoDocumento());

					if ($objVersaoSecaoDocumentoDTO->getStrNomeSecaoModelo() == 'Título') {
						$objSecaoDocumentoDTO->setStrConteudo(str_replace($objBaseConhecimentoDTOBanco->getStrDescricao(), $objBaseConhecimentoDTO->getStrDescricao(), $objVersaoSecaoDocumentoDTO->getStrConteudo()));
					} else {
						$objSecaoDocumentoDTO->setStrConteudo($objVersaoSecaoDocumentoDTO->getStrConteudo());
					}

					$arrObjSecaoDocumentoDTO[] = $objSecaoDocumentoDTO;
				}

				$objEditorDTO = new EditorDTO();
				$objEditorDTO->setDblIdDocumento(null);
				$objEditorDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
				$objEditorDTO->setArrObjSecaoDocumentoDTO($arrObjSecaoDocumentoDTO);

				$objEditorRN = new EditorRN();
				$objEditorRN->adicionarVersao($objEditorDTO);
			}

      //Auditoria
			
    }catch(Exception $e){
      throw new InfraException('Erro alterando Procedimento.',$e);
    }
  }

  protected function configurarEstilosControlado(BaseConhecimentoDTO $parObjBaseConhecimentoDTO){
    try {
  
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_alterar',__METHOD__,$parObjBaseConhecimentoDTO);
  
      $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
      $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjBaseConhecimentoDTO->getNumIdBaseConhecimento());
      $objBaseConhecimentoDTO->setNumIdConjuntoEstilos($parObjBaseConhecimentoDTO->getNumIdConjuntoEstilos());
       
      $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
      $objBaseConhecimentoBD->alterar($objBaseConhecimentoDTO);
  
      //Auditoria
  
    }catch(Exception $e){
      throw new InfraException('Erro configurando estilos do documento.',$e);
    }
  }
  
  protected function excluirControlado($arrObjBaseConhecimentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_excluir',__METHOD__,$arrObjBaseConhecimentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      for($i=0;$i<count($arrObjBaseConhecimentoDTO);$i++){
      	
        $dto = new BaseConhecimentoDTO();
        $dto->retNumIdUnidade();
        $dto->retStrDescricao();
        $dto->retStrStaEstado();
        
        $dto->setNumIdBaseConhecimento($arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento());
        
        $dto = $this->consultar($dto);
        
        if ($dto->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
          $objInfraException->adicionarValidacao('Procedimento "'.$dto->getStrDescricao().'" não pode ser excluído pela unidade atual.');
        }
        
        if ($dto->getStrStaEstado() != self::$TE_RASCUNHO){
        	$objInfraException->adicionarValidacao('Procedimento "'.$dto->getStrDescricao().'" não consta como rascunho.');
        }
      }

      $objInfraException->lancarValidacoes();

      foreach($arrObjBaseConhecimentoDTO as $objBaseConhecimentoDTO){
      	

      	$dto = new BaseConhecimentoDTO();
      	$dto->retNumIdBaseConhecimentoOrigem();
      	$dto->retStrStaDocumento();
      	$dto->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
      	
      	$dto = $this->consultar($dto);
      	
        if ($dto->getNumIdBaseConhecimentoOrigem() != null){
        	$objBaseConhecimentoDTOAnterior = new BaseConhecimentoDTO();
        	$objBaseConhecimentoDTOAnterior->setNumIdBaseConhecimento($dto->getNumIdBaseConhecimentoOrigem());
					$objBaseConhecimentoDTOAnterior->setStrStaEstado(BaseConhecimentoRN::$TE_LIBERADO);
					$this->alterar($objBaseConhecimentoDTOAnterior);        	
        }
        
	      $objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
	      $objRelBaseConhecTipoProcedDTO->retNumIdBaseConhecimento();
	      $objRelBaseConhecTipoProcedDTO->retNumIdTipoProcedimento(); 
	      $objRelBaseConhecTipoProcedDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());

	      $objRelBaseConhecTipoProcedRN = new RelBaseConhecTipoProcedRN();
	      $objRelBaseConhecTipoProcedRN->excluir($objRelBaseConhecTipoProcedRN->listar($objRelBaseConhecTipoProcedDTO));
        
        
        if ($dto->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
          $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
          $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
          $objSecaoDocumentoDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
           
          $objSecaoDocumentoRN = new SecaoDocumentoRN();
          $objSecaoDocumentoRN->excluir($objSecaoDocumentoRN->listar($objSecaoDocumentoDTO));
        }
	      
	      
        // Monta os arrays de anexos da Base de Conhecimento (Se Houver)
	    	$objAnexoDTO =	new AnexoDTO();
	    	$objAnexoRN	= new AnexoRN();
	    	$objAnexoDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
	    	$objAnexoDTO->retNumIdAnexo();
	    	$objAnexoDTO->retStrNome();
	    	$objAnexoDTO->retDthInclusao();
	    	$objAnexoDTO->retNumTamanho();
	    	
	    	$arrAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);
        if (count($arrAnexoDTO) > 0){
        	$objAnexoRN->excluirRN0226($arrAnexoDTO);
        }
				
        $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
        $objBaseConhecimentoBD->excluir($objBaseConhecimentoDTO);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Procedimento.',$e);
    }
  }

  protected function consultarConectado(BaseConhecimentoDTO $objBaseConhecimentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_consultar',__METHOD__,$objBaseConhecimentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
      $ret = $objBaseConhecimentoBD->consultar($objBaseConhecimentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Procedimento.',$e);
    }
  }

  protected function listarConectado(BaseConhecimentoDTO $objBaseConhecimentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_listar',__METHOD__,$objBaseConhecimentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
      $ret = $objBaseConhecimentoBD->listar($objBaseConhecimentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Procedimentos.',$e);
    }
  }

  protected function contarConectado(BaseConhecimentoDTO $objBaseConhecimentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('base_conhecimento_listar',__METHOD__,$objBaseConhecimentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objBaseConhecimentoBD = new BaseConhecimentoBD($this->getObjInfraIBanco());
      $ret = $objBaseConhecimentoBD->contar($objBaseConhecimentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Procedimentos.',$e);
    }
  }
}
?>