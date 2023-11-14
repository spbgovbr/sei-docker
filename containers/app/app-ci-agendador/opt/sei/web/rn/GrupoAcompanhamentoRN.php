<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoAcompanhamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoAcompanhamentoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{

      $objGrupoAcompanhamentoDTO->setStrNome(trim($objGrupoAcompanhamentoDTO->getStrNome()));

      $objGrupoAcompanhamentoDTO->setStrNome(InfraUtil::filtrarISO88591($objGrupoAcompanhamentoDTO->getStrNome()));

      if (strlen($objGrupoAcompanhamentoDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
      }

      $dto = new GrupoAcompanhamentoDTO();
      $dto->retNumIdGrupoAcompanhamento();
      $dto->setNumIdGrupoAcompanhamento($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento(), InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUnidade($objGrupoAcompanhamentoDTO->getNumIdUnidade());
      $dto->setStrNome($objGrupoAcompanhamentoDTO->getStrNome());
      $dto = $this->consultar($dto);
      if ($dto != null) {
        $objInfraException->adicionarValidacao('Existe outro Grupo de Acompanhamento com mesmo nome nesta unidade.');
      }
    }
  }

  public function getNumMaxTamanhoNome(){
    return 100;
  }

  private function validarNumIdUnidade(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoAcompanhamentoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  protected function cadastrarControlado(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_acompanhamento_cadastrar',__METHOD__,$objGrupoAcompanhamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objGrupoAcompanhamentoDTO, $objInfraException);
      $this->validarNumIdUnidade($objGrupoAcompanhamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objGrupoAcompanhamentoBD->cadastrar($objGrupoAcompanhamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Acompanhamento.',$e);
    }
  }

  protected function alterarControlado(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('grupo_acompanhamento_alterar',__METHOD__,$objGrupoAcompanhamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objGrupoAcompanhamentoDTO->isSetStrNome()){
        $this->validarStrNome($objGrupoAcompanhamentoDTO, $objInfraException);
      }
      if ($objGrupoAcompanhamentoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objGrupoAcompanhamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD($this->getObjInfraIBanco());
      $objGrupoAcompanhamentoBD->alterar($objGrupoAcompanhamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Acompanhamento.',$e);
    }
  }

  protected function excluirControlado($arrObjGrupoAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_acompanhamento_excluir',__METHOD__,$arrObjGrupoAcompanhamentoDTO);

      $objInfraException = new InfraException();

      if (count($arrObjGrupoAcompanhamentoDTO)){

        $arrIdGrupoAcompanhamento = InfraArray::converterArrInfraDTO($arrObjGrupoAcompanhamentoDTO,'IdGrupoAcompanhamento');

        $objProtocoloRN = new ProtocoloRN();
        $objAcompanhamentoRN 	= new AcompanhamentoRN();
        
        //Exclui processos que estão no Grupo de Acompanhamento mas que não estão acessíveis
        $objAcompanhamentoDTO = new AcompanhamentoDTO();
        $objAcompanhamentoDTO->retNumIdAcompanhamento();
        $objAcompanhamentoDTO->retDblIdProtocolo();
        $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($arrIdGrupoAcompanhamento,InfraDTO::$OPER_IN);
        
        $arrObjAcompanhamentoDTO = $objAcompanhamentoRN->listar($objAcompanhamentoDTO);
          
        if (count($arrObjAcompanhamentoDTO)){
          
          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
          $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjAcompanhamentoDTO,'IdProtocolo'));
          
          $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');
          
          $arrExclusao = array();
          foreach($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO){
            if (!isset($arrObjProtocoloDTO[$objAcompanhamentoDTO->getDblIdProtocolo()])){
              $arrExclusao[] = $objAcompanhamentoDTO;
            }
          }
          
          if (count($arrExclusao)){
            $objAcompanhamentoRN->excluir($arrExclusao);
          }
        }
             
      
        foreach($arrObjGrupoAcompanhamentoDTO as $objGrupoAcompanhamentoDTO){
  
          //Verifica se há relacionamentos
  	      $objAcompanhamentoDTO = new AcompanhamentoDTO(); 
  	      $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento());
  				$numAcompanhamentos = $objAcompanhamentoRN->contar($objAcompanhamentoDTO);
  				
  	      if ($numAcompanhamentos){
  		      //Regras de Negocio
  		      $dto = new GrupoAcompanhamentoDTO();
  		      $dto->retStrNome();
  		      $dto->setNumIdGrupoAcompanhamento($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento());
  		      
  		      $dto = $this->consultar($dto);
  		      
  		      if ($numAcompanhamentos==1){
  	      	  $objInfraException->adicionarValidacao('Existe um Acompanhamento Especial associado ao grupo "'.$dto->getStrNome().'".');
  		      }else{
  		        $objInfraException->adicionarValidacao('Existem Acompanhamentos Especiais associados ao grupo "'.$dto->getStrNome().'".');
  		      }
  		      
  	      }
        }
        $objInfraException->lancarValidacoes();

        $objRelUsuarioGrupoAcompDTO = new RelUsuarioGrupoAcompDTO();
        $objRelUsuarioGrupoAcompDTO->retNumIdGrupoAcompanhamento();
        $objRelUsuarioGrupoAcompDTO->retNumIdUsuario();
        $objRelUsuarioGrupoAcompDTO->setNumIdGrupoAcompanhamento($arrIdGrupoAcompanhamento, InfraDTO::$OPER_IN);

        $objRelUsuarioGrupoAcompRN = new RelUsuarioGrupoAcompRN();
        $objRelUsuarioGrupoAcompRN->excluir($objRelUsuarioGrupoAcompRN->listar($objRelUsuarioGrupoAcompDTO));
  
        $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjGrupoAcompanhamentoDTO);$i++){
          $objGrupoAcompanhamentoBD->excluir($arrObjGrupoAcompanhamentoDTO[$i]);
        }
        
      }
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Acompanhamento.',$e);
    }
  }

  protected function consultarConectado(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_acompanhamento_consultar',__METHOD__,$objGrupoAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objGrupoAcompanhamentoBD->consultar($objGrupoAcompanhamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Acompanhamento.',$e);
    }
  }

  protected function listarConectado(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_acompanhamento_listar',__METHOD__,$objGrupoAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objGrupoAcompanhamentoBD->listar($objGrupoAcompanhamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Acompanhamentos.',$e);
    }
  }

  protected function contarConectado(GrupoAcompanhamentoDTO $objGrupoAcompanhamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_acompanhamento_listar',__METHOD__,$objGrupoAcompanhamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD($this->getObjInfraIBanco());
      $ret = $objGrupoAcompanhamentoBD->contar($objGrupoAcompanhamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos de Acompanhamentos.',$e);
    }
  }

}
?>