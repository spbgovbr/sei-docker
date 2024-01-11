<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/11/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id: ConjuntoEstilosRN.php 9082 2014-07-28 15:52:49Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ConjuntoEstilosRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrSinUltimo(ConjuntoEstilosDTO $objConjuntoEstilosDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objConjuntoEstilosDTO->getStrSinUltimo())){
      $objInfraException->adicionarValidacao('Sinalizador de Último gerado não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objConjuntoEstilosDTO->getStrSinUltimo())){
        $objInfraException->adicionarValidacao('Sinalizador de Último gerado inválido.');
      }
    }
  }

  protected function sincronizarControlado() {
    try{

      //SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_cadastrar');

      $objEstiloDTO = new EstiloDTO();
      $objEstiloDTO->retNumIdEstilo();
      $objEstiloDTO->retStrNome();
      $objEstiloDTO->retStrFormatacao();

      $objEstiloRN = new EstiloRN();
      $arrObjEstiloDTO = $objEstiloRN->listar($objEstiloDTO);

      $objConjuntoEstilosRN = new ConjuntoEstilosRN();
      $objConjuntoEstilosItemRN = new ConjuntoEstilosItemRN();

      $objConjuntoEstilosDTO = new ConjuntoEstilosDTO();
      $objConjuntoEstilosDTO->retNumIdConjuntoEstilos();
      $objConjuntoEstilosDTO->setStrSinUltimo('S');

      $objConjuntoEstilosDTO = $objConjuntoEstilosRN->consultar($objConjuntoEstilosDTO);

      $bolNovoConjunto = false;

      if ($objConjuntoEstilosDTO == null){
        $bolNovoConjunto = true;
      }else{

        $objConjuntoEstilosItemDTO = new ConjuntoEstilosItemDTO();
        $objConjuntoEstilosItemDTO->retStrFormatacao();
        $objConjuntoEstilosItemDTO->retStrNome();
        $objConjuntoEstilosItemDTO->setNumIdConjuntoEstilos($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());

        $arrObjConjuntoEstilosItemDTO = $objConjuntoEstilosItemRN->listar($objConjuntoEstilosItemDTO);

        if (count($arrObjEstiloDTO) != count($arrObjConjuntoEstilosItemDTO)){
          $bolNovoConjunto = true;
        }else{

          foreach($arrObjEstiloDTO as $objEstiloDTO){
            $bolAchouEstilo = false;
            foreach($arrObjConjuntoEstilosItemDTO as $objConjuntoEstilosItemDTO){
              if ($objEstiloDTO->getStrNome()==$objConjuntoEstilosItemDTO->getStrNome() && $objEstiloDTO->getStrFormatacao()==$objConjuntoEstilosItemDTO->getStrFormatacao()){
                $bolAchouEstilo = true;
                break;
              }
            }

            if (!$bolAchouEstilo){
              $bolNovoConjunto = true;
              break;
            }
          }
        }
      }

      if ($bolNovoConjunto){

        if ($objConjuntoEstilosDTO != null){
          $objConjuntoEstilosDTO = $this->bloquear($objConjuntoEstilosDTO);
          $objConjuntoEstilosDTO->setStrSinUltimo('N');
          $this->alterar($objConjuntoEstilosDTO);
        }

        $objConjuntoEstilosDTO = new ConjuntoEstilosDTO();
        $objConjuntoEstilosDTO->setStrSinUltimo('S');
        $objConjuntoEstilosDTO = $this->cadastrar($objConjuntoEstilosDTO);

        $objRelSecaoModeloEstiloRN=new RelSecaoModeloEstiloRN();
        $objRelSecaoModCjEstilosItemRN=new RelSecaoModCjEstilosItemRN();

        foreach($arrObjEstiloDTO as $objEstiloDTO){

          $objConjuntoEstilosItemDTO = new ConjuntoEstilosItemDTO();
          $objConjuntoEstilosItemDTO->setStrNome($objEstiloDTO->getStrNome());
          $objConjuntoEstilosItemDTO->setStrFormatacao($objEstiloDTO->getStrFormatacao());
          $objConjuntoEstilosItemDTO->setNumIdConjuntoEstilos($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());

          $item=$objConjuntoEstilosItemRN->cadastrar($objConjuntoEstilosItemDTO);
          //cria registros na rel_secao_mod_cj_estilos_item
          $objRelSecaoModeloEstiloDTO=new RelSecaoModeloEstiloDTO();
          $objRelSecaoModeloEstiloDTO->setNumIdEstilo($objEstiloDTO->getNumIdEstilo());
          $objRelSecaoModeloEstiloDTO->retNumIdSecaoModelo();
          $objRelSecaoModeloEstiloDTO->retStrSinPadrao();
          $arrObjRelSecaoModeloEstiloDTO=$objRelSecaoModeloEstiloRN->listar($objRelSecaoModeloEstiloDTO);
          if ($arrObjRelSecaoModeloEstiloDTO){
          	foreach ($arrObjRelSecaoModeloEstiloDTO as $objRelSecaoModeloEstiloDTO) {
          		$objRelSecaoModCjEstilosItemDTO=new RelSecaoModCjEstilosItemDTO();
          		$objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo($objRelSecaoModeloEstiloDTO->getNumIdSecaoModelo());
          		$objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilosItem($item->getNumIdConjuntoEstilosItem());
          		$objRelSecaoModCjEstilosItemDTO->setStrSinPadrao($objRelSecaoModeloEstiloDTO->getStrSinPadrao());
          		$objRelSecaoModCjEstilosItemRN->cadastrar($objRelSecaoModCjEstilosItemDTO);
          	}
          }
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro gerando Conjunto de Estilos.',$e);
    }
  }


  protected function cadastrarControlado(ConjuntoEstilosDTO $objConjuntoEstilosDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_cadastrar',__METHOD__,$objConjuntoEstilosDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrSinUltimo($objConjuntoEstilosDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosBD->cadastrar($objConjuntoEstilosDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Conjunto de Estilos.',$e);
    }
  }

  protected function alterarControlado(ConjuntoEstilosDTO $objConjuntoEstilosDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_alterar',__METHOD__,$objConjuntoEstilosDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objConjuntoEstilosDTO->isSetStrSinUltimo()){
        $this->validarStrSinUltimo($objConjuntoEstilosDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      $objConjuntoEstilosBD->alterar($objConjuntoEstilosDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Conjunto de Estilos.',$e);
    }
  }

  protected function excluirControlado($arrObjConjuntoEstilosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_excluir',__METHOD__,$arrObjConjuntoEstilosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjConjuntoEstilosDTO);$i++){
        $objConjuntoEstilosBD->excluir($arrObjConjuntoEstilosDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Conjunto de Estilos.',$e);
    }
  }

  protected function consultarConectado(ConjuntoEstilosDTO $objConjuntoEstilosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_consultar',__METHOD__,$objConjuntoEstilosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosBD->consultar($objConjuntoEstilosDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Conjunto de Estilos.',$e);
    }
  }

  protected function listarConectado(ConjuntoEstilosDTO $objConjuntoEstilosDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_listar',__METHOD__,$objConjuntoEstilosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosBD->listar($objConjuntoEstilosDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Conjuntos de Estilos.',$e);
    }
  }

  protected function contarConectado(ConjuntoEstilosDTO $objConjuntoEstilosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_listar',__METHOD__,$objConjuntoEstilosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosBD->contar($objConjuntoEstilosDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Conjuntos de Estilos.',$e);
    }
  }
/*
  protected function desativarControlado($arrObjConjuntoEstilosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjConjuntoEstilosDTO);$i++){
        $objConjuntoEstilosBD->desativar($arrObjConjuntoEstilosDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Conjunto de Estilos.',$e);
    }
  }

  protected function reativarControlado($arrObjConjuntoEstilosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjConjuntoEstilosDTO);$i++){
        $objConjuntoEstilosBD->reativar($arrObjConjuntoEstilosDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Conjunto de Estilos.',$e);
    }
  }
*/

  protected function bloquearControlado(ConjuntoEstilosDTO $objConjuntoEstilosDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('conjunto_estilos_consultar',__METHOD__,$objConjuntoEstilosDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objConjuntoEstilosBD = new ConjuntoEstilosBD($this->getObjInfraIBanco());
      $ret = $objConjuntoEstilosBD->bloquear($objConjuntoEstilosDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Conjunto de Estilos.',$e);
    }
  }
}
?>