<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarefaRN extends InfraRN {

  public static $TI_GERACAO_PROCEDIMENTO = 1;
  public static $TI_GERACAO_DOCUMENTO = 2;
  public static $TI_ASSINATURA_DOCUMENTO = 5;
  public static $TI_CANCELAMENTO_ASSINATURA = 6;
  public static $TI_PUBLICACAO = 7;
  public static $TI_ENVIO_EMAIL = 12;
  public static $TI_RECEBIMENTO_DOCUMENTO = 13;
  public static $TI_RELACIONAR_PROCEDIMENTO = 18;
  public static $TI_REMOCAO_RELACIONAMENTO_PROCEDIMENTO = 19;
  public static $TI_SOBRESTAMENTO = 20;
  public static $TI_REMOCAO_SOBRESTAMENTO = 21;
  public static $TI_ARQUIVAMENTO = 24;
  public static $TI_DESARQUIVAMENTO = 26;
  public static $TI_MIGRACAO_LOCALIZADOR = 27;
  public static $TI_CONCLUSAO_PROCESSO_UNIDADE = 28;
  public static $TI_REABERTURA_PROCESSO_UNIDADE = 29;
  public static $TI_ARQUIVO_ANEXADO = 30;
  public static $TI_ARQUIVO_DESANEXADO = 31;
  public static $TI_PROCESSO_REMETIDO_UNIDADE = 32;
  public static $TI_EXCLUSAO_DOCUMENTO = 33;
  public static $TI_PROCESSO_INCLUIDO_EM_BLOCO = 34;
  public static $TI_DOCUMENTO_INCLUIDO_EM_BLOCO = 35;
  public static $TI_PROCESSO_RETIRADO_DO_BLOCO = 36;
  public static $TI_DOCUMENTO_RETIRADO_DO_BLOCO = 37;
  public static $TI_BLOCO_DISPONIBILIZACAO = 38;
  public static $TI_BLOCO_CANCELAMENTO_DISPONIBILIZACAO = 39;
  public static $TI_BLOCO_RETORNO = 40;
  public static $TI_CONCLUSAO_AUTOMATICA_UNIDADE = 41;
  public static $TI_SOBRESTANDO_PROCESSO = 42;
  public static $TI_SOBRESTADO_AO_PROCESSO = 43;
  public static $TI_REMOCAO_SOBRESTANDO_PROCESSO = 44;
  public static $TI_REMOCAO_SOBRESTADO_AO_PROCESSO = 45;
  public static $TI_CANCELAMENTO_AGENDAMENTO = 47;
  public static $TI_PROCESSO_RECEBIDO_UNIDADE = 48;
  public static $TI_LIBERACAO_ACESSO_EXTERNO = 50;
  public static $TI_CANCELAMENTO_DOCUMENTO = 51;
  public static $TI_ACESSO_EXTERNO_SISTEMA = 52;
  public static $TI_RECEBIMENTO_ARQUIVO = 53;
  public static $TI_CANCELADO_RECEBIMENTO_ARQUIVO = 54;
  public static $TI_SOLICITADO_DESARQUIVAMENTO = 55;
  public static $TI_CANCELADA_SOLICITACAO_DESARQUIVAMENTO = 56;
  public static $TI_PROCESSO_ATRIBUIDO = 57;
  public static $TI_ALTERACAO_NIVEL_ACESSO_GLOBAL = 58;
  public static $TI_REMOCAO_ATRIBUICAO = 59;
  public static $TI_PROCESSO_ALTERACAO_ORDEM_ARVORE = 60;
  public static $TI_PROCESSO_CONCESSAO_CREDENCIAL = 61;
  public static $TI_PROCESSO_RECEBIMENTO_CREDENCIAL = 62;
  public static $TI_CONCLUSAO_PROCESSO_USUARIO = 63;
  public static $TI_REABERTURA_PROCESSO_USUARIO = 64;   
  public static $TI_ATUALIZACAO_ANDAMENTO = 65; 
  public static $TI_PROCESSO_TRANSFERENCIA_CREDENCIAL = 66;
  public static $TI_PROCESSO_CONCESSAO_CREDENCIAL_CASSADA = 67;
  public static $TI_PROCESSO_TRANSFERENCIA_CREDENCIAL_CASSADA = 68;
  public static $TI_PROCESSO_CASSACAO_CREDENCIAL = 69;
  public static $TI_CONCLUSAO_AUTOMATICA_USUARIO = 70;
  public static $TI_PROCESSO_CONCESSAO_CREDENCIAL_ANULADA = 71;
  public static $TI_PROCESSO_TRANSFERENCIA_CREDENCIAL_ANULADA = 72;
  public static $TI_CONCESSAO_CREDENCIAL_ASSINATURA = 73;
  public static $TI_CASSACAO_CREDENCIAL_ASSINATURA = 74;
  public static $TI_CONCESSAO_CREDENCIAL_ASSINATURA_CASSADA = 75;
  public static $TI_CONCESSAO_CREDENCIAL_ASSINATURA_ANULADA = 76;
  public static $TI_PROCESSO_RENUNCIA_CREDENCIAL = 77;
  public static $TI_PROCESSO_RENUNCIA_CREDENCIAL_ANULADA = 78;
  public static $TI_PROCESSO_CONCESSAO_CREDENCIAL_RENUNCIADA = 79;
  public static $TI_PROCESSO_TRANSFERENCIA_CREDENCIAL_RENUNCIADA = 80;
  public static $TI_CONCESSAO_CREDENCIAL_ASSINATURA_UTILIZADA = 81;  
  public static $TI_PROCESSO_CIENCIA = 82;
  public static $TI_DOCUMENTO_CIENCIA = 83;
  public static $TI_NOTIFICACAO_ENVIO = 84;
  public static $TI_NOTIFICACAO_REGISTRO = 85;
  public static $TI_LIBERACAO_ASSINATURA_EXTERNA = 86;
  public static $TI_CANCELAMENTO_LIBERACAO_ASSINATURA_EXTERNA = 87;
  public static $TI_LIBERACAO_ASSINATURA_EXTERNA_CANCELADA = 88;
  public static $TI_LIBERACAO_ACESSO_EXTERNO_CANCELADA = 89;
  public static $TI_CANCELAMENTO_LIBERACAO_ACESSO_EXTERNO = 90;
  public static $TI_LIBERACAO_ACESSO_EXTERNO_USUARIO_EXTERNO = 91;
  public static $TI_LIBERACAO_ACESSO_EXTERNO_USUARIO_EXTERNO_CANCELADA = 92;
  public static $TI_CANCELAMENTO_LIBERACAO_ACESSO_EXTERNO_USUARIO_EXTERNO = 93;
  public static $TI_MIGRACAO_UNIDADE = 94;
  public static $TI_BLOCO_CONCLUSAO = 95;
  public static $TI_BLOCO_REABERTURA = 96;
  public static $TI_NOTIFICACAO_TERMINO_PRAZO = 97;
  public static $TI_OUVIDORIA_SOLICITACAO_ATENDIDA = 98;
  public static $TI_OUVIDORIA_SOLICITACAO_NAO_ATENDIDA = 99;
  public static $TI_OUVIDORIA_CANCELADA_SINALIZACAO_ATENDIMENTO = 100;
  public static $TI_ANEXADO_PROCESSO = 101;
  public static $TI_ANEXADO_AO_PROCESSO = 102;
  public static $TI_DESANEXADO_PROCESSO = 103;
  public static $TI_DESANEXADO_DO_PROCESSO = 104;
  public static $TI_ALTERACAO_NIVEL_ACESSO_PROCESSO = 105;
  public static $TI_ALTERACAO_GRAU_SIGILO_PROCESSO = 106;
  public static $TI_ALTERACAO_HIPOTESE_LEGAL_PROCESSO = 107;
  public static $TI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO = 108;
  public static $TI_ALTERACAO_GRAU_SIGILO_DOCUMENTO = 109;
  public static $TI_ALTERACAO_HIPOTESE_LEGAL_DOCUMENTO = 110;
  public static $TI_ALTERACAO_TIPO_CONFERENCIA_DOCUMENTO = 111;
  public static $TI_PROCESSO_ANEXADO_CIENCIA = 112;
  public static $TI_DOCUMENTO_MOVIDO_PARA_PROCESSO = 113;
  public static $TI_DOCUMENTO_MOVIDO_DO_PROCESSO = 114;
  public static $TI_AUTENTICACAO_DOCUMENTO = 115;
  public static $TI_CANCELAMENTO_AUTENTICACAO = 116;
  public static $TI_PROCESSO_CANCELAMENTO_CREDENCIAL = 117;
  public static $TI_PROCESSO_ATIVACAO_CREDENCIAL = 118;
  public static $TI_PROCESSO_ATIVACAO_CREDENCIAL_CASSADA = 119;
  public static $TI_PROCESSO_ATIVACAO_CREDENCIAL_ANULADA = 120;
  public static $TI_PROCESSO_ATIVACAO_CREDENCIAL_RENUNCIADA = 121;
  public static $TI_PROCESSO_BLOQUEADO = 122;
  public static $TI_PROCESSO_DESBLOQUEADO = 123;
  public static $TI_OUVIDORIA_CORRECAO_ENCAMINHAMENTO = 124;
  public static $TI_CANCELAR_ARQUIVAMENTO = 125;
  public static $TI_ALTERACAO_TIPO_PROCESSO = 126;
  public static $TI_PROCESSO_RENOVACAO_CREDENCIAL = 127;
  public static $TI_ALTERACAO_PROTOCOLO_PROCESSO = 128;
  public static $TI_ALTERACAO_DATA_AUTUACAO_PROCESSO = 129;
  public static $TI_ELIMINACAO_ELETRONICO=130;
  public static $TI_DESARQUIVAMENTO_PARA_ELIMINACAO=131;
  public static $TI_PROCESSO_INCLUSAO_EDITAL_ELIMINACAO=132;
  public static $TI_PROCESSO_RETIRADA_EDITAL_ELIMINACAO=133;
  public static $TI_PROCESSO_ENVIADO_FEDERACAO = 134;
  public static $TI_PROCESSO_ENVIADO_FEDERACAO_CANCELADO = 135;
  public static $TI_CANCELAMENTO_ENVIO_PROCESSO_FEDERACAO = 136;
  public static $TI_ALTERACAO_PRIORIDADE_PROCESSO = 137;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }
  
  private function validarStrNome(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTarefaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objTarefaDTO->setStrNome(trim($objTarefaDTO->getStrNome()));
  
      if (strlen($objTarefaDTO->getStrNome())>250){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrIdTarefaModulo(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTarefaDTO->getStrIdTarefaModulo())){
      $objTarefaDTO->setStrIdTarefaModulo(null);
    }else{
      $objTarefaDTO->setStrIdTarefaModulo(trim($objTarefaDTO->getStrIdTarefaModulo()));

      if (strlen($objTarefaDTO->getStrIdTarefaModulo()) > 50){
        $objInfraException->adicionarValidacao('Identificador da tarefa no módulo possui tamanho superior a 50 caracteres.');
      }
    }
  }
  
  private function validarStrSinHistoricoResumido(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTarefaDTO->getStrSinHistoricoResumido())){
      $objInfraException->adicionarValidacao('Sinalizador de Histórico Resumido não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTarefaDTO->getStrSinHistoricoResumido())){
        $objInfraException->adicionarValidacao('Sinalizador de Histórico Resumido inválido.');
      }
    }
  }
  
  private function validarStrSinHistoricoCompleto(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objTarefaDTO->getStrSinHistoricoCompleto())){
      $objInfraException->adicionarValidacao('Sinalizador de Histórico Completo não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objTarefaDTO->getStrSinHistoricoCompleto())){
        $objInfraException->adicionarValidacao('Sinalizador de Histórico Completo inválido.');
      }
    }
  }
  
  private function validarStrSinFecharAndamentosAbertos(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objTarefaDTO->getStrSinFecharAndamentosAbertos())){
  		$objInfraException->adicionarValidacao('Sinalizador de Fechamento de Andamentos Abertos não informado.');
  	}else{
  		if (!InfraUtil::isBolSinalizadorValido($objTarefaDTO->getStrSinFecharAndamentosAbertos())){
  			$objInfraException->adicionarValidacao('Sinalizador de Fechamento de Andamentos Abertos inválido.');
  		}
  	}
  }

  private function validarStrSinLancarAndamentoFechado(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objTarefaDTO->getStrSinLancarAndamentoFechado())){
  		$objInfraException->adicionarValidacao('Sinalizador de Lançamento de Andamento Fechado não informado.');
  	}else{
  		if (!InfraUtil::isBolSinalizadorValido($objTarefaDTO->getStrSinLancarAndamentoFechado())){
  			$objInfraException->adicionarValidacao('Sinalizador de Lançamento de Andamento Fechado inválido.');
  		}
  	}
  }

  private function validarStrSinPermiteProcessoFechado(TarefaDTO $objTarefaDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objTarefaDTO->getStrSinPermiteProcessoFechado())){
  		$objInfraException->adicionarValidacao('Sinalizador de Permissão de Processo Fechado não informado.');
  	}else{
  		if (!InfraUtil::isBolSinalizadorValido($objTarefaDTO->getStrSinPermiteProcessoFechado())){
  			$objInfraException->adicionarValidacao('Sinalizador de Permissão de Processo Fechado inválido.');
  		}
  	}
  }
  
  protected function cadastrarControlado(TarefaDTO $objTarefaDTO) {
    try{
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_cadastrar',__METHOD__,$objTarefaDTO);
  
      //Regras de Negocio
      $objInfraException = new InfraException();

      $numIdTarefa = BancoSEI::getInstance()->getValorSequencia('seq_tarefa');

      if ($numIdTarefa < 1000){
        throw new InfraException('Identificador da tarefa deve ser igual ou superior a 1000.');
      }

      $this->validarStrNome($objTarefaDTO, $objInfraException);
      $this->validarStrIdTarefaModulo($objTarefaDTO, $objInfraException);
      $this->validarStrSinHistoricoResumido($objTarefaDTO, $objInfraException);
      $this->validarStrSinHistoricoCompleto($objTarefaDTO, $objInfraException);
      $this->validarStrSinFecharAndamentosAbertos($objTarefaDTO, $objInfraException);
      $this->validarStrSinLancarAndamentoFechado($objTarefaDTO, $objInfraException);
      $this->validarStrSinPermiteProcessoFechado($objTarefaDTO, $objInfraException);
  
      $objInfraException->lancarValidacoes();
  
      $objTarefaBD = new TarefaBD($this->getObjInfraIBanco());
      $ret = $objTarefaBD->cadastrar($objTarefaDTO);
  
      //Auditoria
  
      return $ret;
  
    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tarefa.',$e);
    }
  }
  
  protected function alterarControlado(TarefaDTO $objTarefaDTO){
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_alterar',__METHOD__,$objTarefaDTO);
  
      //Regras de Negocio
      $objInfraException = new InfraException();
  
      if ($objTarefaDTO->isSetStrNome()){
        $this->validarStrNome($objTarefaDTO, $objInfraException);
      }

      if ($objTarefaDTO->isSetStrIdTarefaModulo()){
        $this->validarStrIdTarefaModulo($objTarefaDTO, $objInfraException);
      }

      if ($objTarefaDTO->isSetStrSinHistoricoResumido()){
        $this->validarStrSinHistoricoResumido($objTarefaDTO, $objInfraException);
      }
      
      if ($objTarefaDTO->isSetStrSinHistoricoCompleto()){
        $this->validarStrSinHistoricoCompleto($objTarefaDTO, $objInfraException);
      }
  
      if ($objTarefaDTO->isSetStrSinFecharAndamentosAbertos()){
        $this->validarStrSinFecharAndamentosAbertos($objTarefaDTO, $objInfraException);
      }
      
      if ($objTarefaDTO->isSetStrSinLancarAndamentoFechado()){
        $this->validarStrSinLancarAndamentoFechado($objTarefaDTO, $objInfraException);
      }
      
      if ($objTarefaDTO->isSetStrSinPermiteProcessoFechado()){
        $this->validarStrSinPermiteProcessoFechado($objTarefaDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();
  
      $objTarefaBD = new TarefaBD($this->getObjInfraIBanco());
      $objTarefaBD->alterar($objTarefaDTO);
  
      //Auditoria
  
    }catch(Exception $e){
      throw new InfraException('Erro alterando Tarefa.',$e);
    }
  }
  
  protected function excluirControlado($arrObjTarefaDTO){
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_excluir',__METHOD__,$arrObjTarefaDTO);
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
  
      $objTarefaBD = new TarefaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjTarefaDTO);$i++){
        $objTarefaBD->excluir($arrObjTarefaDTO[$i]);
      }
  
      //Auditoria
  
    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tarefa.',$e);
    }
  }
  
  protected function consultarConectado(TarefaDTO $objTarefaDTO){
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_consultar',__METHOD__,$objTarefaDTO);
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
  
      $objTarefaBD = new TarefaBD($this->getObjInfraIBanco());
      $ret = $objTarefaBD->consultar($objTarefaDTO);
  
      //Auditoria
  
      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tarefa.',$e);
    }
  }
  
  protected function listarConectado(TarefaDTO $objTarefaDTO) {
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_listar',__METHOD__,$objTarefaDTO);
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
  
      $objTarefaBD = new TarefaBD($this->getObjInfraIBanco());
      $ret = $objTarefaBD->listar($objTarefaDTO);
  
      //Auditoria
  
      return $ret;
  
    }catch(Exception $e){
      throw new InfraException('Erro listando Tarefas.',$e);
    }
  }

  protected function configurarHistoricoControlado($arrObjTarefaDTO) {
    try {
    
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_configurar_historico',__METHOD__,$arrObjTarefaDTO);
    
      //Regras de Negocio
      //$objInfraException = new InfraException();
      
      //$objInfraException->lancarValidacoes();
    
      foreach($arrObjTarefaDTO as $objTarefaDTO){
        $this->alterar($objTarefaDTO);
      }

      //Auditoria
    }catch(Exception $e){
      throw new InfraException('Erro configurando Histórico.',$e);
    }
  }

  protected function contarConectado(TarefaDTO $objTarefaDTO){
    try {
  
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarefa_listar',__METHOD__,$objTarefaDTO);
  
      //Regras de Negocio
      //$objInfraException = new InfraException();
  
      //$objInfraException->lancarValidacoes();
  
      $objTarefaBD = new TarefaBD($this->getObjInfraIBanco());
      $ret = $objTarefaBD->contar($objTarefaDTO);
  
      //Auditoria
  
      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tarefas.',$e);
    }
  }

  public static function getArrTarefasConcessaoCredencial($bolIncluirCredencialAssinatura){

    $ret = array(TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL,
                 TarefaRN::$TI_PROCESSO_TRANSFERENCIA_CREDENCIAL,
                 TarefaRN::$TI_PROCESSO_ATIVACAO_CREDENCIAL);

    if ($bolIncluirCredencialAssinatura){
      $ret[] = TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA;
    }

    return $ret;
  }

  public static function getArrTarefasCassacaoCredencial($bolIncluirCredencialAssinatura){
    $ret = array(TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL_CASSADA,
                 TarefaRN::$TI_PROCESSO_TRANSFERENCIA_CREDENCIAL_CASSADA,
                 TarefaRN::$TI_PROCESSO_ATIVACAO_CREDENCIAL_CASSADA);

    if ($bolIncluirCredencialAssinatura){
      $ret[] = TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA_CASSADA;
    }

    return $ret;
  }

  public static function getArrTarefasAnulacaoCredencial($bolIncluirCredencialAssinatura){
    $ret = array(TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL_ANULADA,
                 TarefaRN::$TI_PROCESSO_TRANSFERENCIA_CREDENCIAL_ANULADA,
                 TarefaRN::$TI_PROCESSO_ATIVACAO_CREDENCIAL_ANULADA);

    if ($bolIncluirCredencialAssinatura){
      $ret[] = TarefaRN::$TI_CONCESSAO_CREDENCIAL_ASSINATURA_ANULADA;
    }

    return $ret;
  }

  public static function getArrTarefasRenunciaCredencial(){
    return array(TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL_RENUNCIADA,
                 TarefaRN::$TI_PROCESSO_TRANSFERENCIA_CREDENCIAL_RENUNCIADA,
                 TarefaRN::$TI_PROCESSO_ATIVACAO_CREDENCIAL_RENUNCIADA);
  }

  public static function getArrTarefasTramitacao(){
    return array(TarefaRN::$TI_GERACAO_PROCEDIMENTO,
								 TarefaRN::$TI_PROCESSO_REMETIDO_UNIDADE,
								 TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL,
								 TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL_ANULADA);
  }
}
?>