<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarefaInstalacaoRN extends InfraRN {

  public static $TI_RECEBIMENTO_SOLICITACAO = 1;
  public static $TI_ENVIO_SOLICITACAO = 2;
  public static $TI_RECEBIMENTO_REPLICACAO = 3;
  public static $TI_ENVIO_REPLICACAO = 4;
  public static $TI_ENVIO_LIBERACAO = 5;
  public static $TI_ENVIO_BLOQUEIO = 6;
  public static $TI_DESATIVACAO = 7;
  public static $TI_REATIVACAO = 8;
  public static $TI_ALTERACAO_ENDERECO = 9;
  public static $TI_RECEBIMENTO_LIBERACAO = 10;
  public static $TI_RECEBIMENTO_BLOQUEIO = 11;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function consultarConectado(TarefaInstalacaoDTO $objTarefaInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_instalacao_consultar',__METHOD__,$objTarefaInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarefaInstalacaoBD = new TarefaInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objTarefaInstalacaoBD->consultar($objTarefaInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tarefa de Instalação.',$e);
    }
  }

  protected function listarConectado(TarefaInstalacaoDTO $objTarefaInstalacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_instalacao_listar',__METHOD__,$objTarefaInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarefaInstalacaoBD = new TarefaInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objTarefaInstalacaoBD->listar($objTarefaInstalacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tarefas de Instalações.',$e);
    }
  }

  protected function contarConectado(TarefaInstalacaoDTO $objTarefaInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_instalacao_listar',__METHOD__,$objTarefaInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarefaInstalacaoBD = new TarefaInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objTarefaInstalacaoBD->contar($objTarefaInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tarefas de Instalações.',$e);
    }
  }
}
