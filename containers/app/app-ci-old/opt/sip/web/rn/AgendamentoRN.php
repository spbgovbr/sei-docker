<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/04/2013 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../Sip.php';

class AgendamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }
  
  public function testarAgendamento(){
    try{
      LogSip::getInstance()->gravar('Teste Agendamento SIP',InfraLog::$INFORMACAO);
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      InfraMail::enviarConfigurado(ConfiguracaoSip::getInstance(), $objInfraParametro->getValor('SIP_EMAIL_SISTEMA'), $objInfraParametro->getValor('SIP_EMAIL_ADMINISTRADOR'), null, null, 'Teste Agendamento SIP', 'Agendamento SIP executado com sucesso.');
    }catch(Exception $e){
      throw new InfraException('Erro realizando teste de agendamento.',$e);
    }
  }
  
  protected function removerDadosLoginControlado(){
    try{

      ini_set('max_execution_time','0');
      ini_set('memory_limit','-1');
  
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
  
      $numSeg = InfraUtil::verificarTempoProcessamento();
      
      InfraDebug::getInstance()->gravar('REMOVENDO DADOS DE LOGIN');

      $objAgendamentoBD = new AgendamentoBD($this->getObjInfraIBanco());
      $ret = $objAgendamentoBD->removerDadosLogin();

      InfraDebug::getInstance()->gravar($ret.' REGISTROS');
            
      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
      
    }catch(Exception $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      
			throw new InfraException('Erro removendo dados de login.',$e);
    }
  }

  protected function replicarPermissoesAgendadasConectado(){
    try{

      ini_set('max_execution_time','0');
      ini_set('memory_limit','-1');

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      InfraDebug::getInstance()->gravar('REPLICANDO PERMISSOES AGENDADAS');

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->setStrWebService(null, InfraDTO::$OPER_DIFERENTE);

      $objSistemaRN = new SistemaRN();
      $arrIdSistema = InfraArray::converterArrInfraDTO($objSistemaRN->listar($objSistemaDTO),'IdSistema');

      if (count($arrIdSistema)){

        $objPermissaoDTO = new PermissaoDTO();
        $objPermissaoDTO->retNumIdSistema();
        $objPermissaoDTO->retNumIdUsuario();
        $objPermissaoDTO->retNumIdUnidade();
        $objPermissaoDTO->retNumIdPerfil();
        $objPermissaoDTO->setNumIdSistema($arrIdSistema,InfraDTO::$OPER_IN);
        $objPermissaoDTO->setDtaDataInicio(InfraData::getStrDataAtual());

        $objPermissaoRN = new PermissaoRN();
        $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

        foreach($arrObjPermissaoDTO as $dto){
          $objReplicacaoPermissaoDTO = new ReplicacaoPermissaoDTO();
          $objReplicacaoPermissaoDTO->setStrStaOperacao('C');
          $objReplicacaoPermissaoDTO->setNumIdSistema($dto->getNumIdSistema());
          $objReplicacaoPermissaoDTO->setNumIdUsuario($dto->getNumIdUsuario());
          $objReplicacaoPermissaoDTO->setNumIdUnidade($dto->getNumIdUnidade());
          $objReplicacaoPermissaoDTO->setNumIdPerfil($dto->getNumIdPerfil());
          $objSistemaRN->replicarPermissao($objReplicacaoPermissaoDTO);
        }

        $objPermissaoDTO->unSetDtaDataInicio();
        $objPermissaoDTO->setDtaDataFim(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS));

        $arrObjPermissaoDTO = $objPermissaoRN->listar($objPermissaoDTO);

        foreach($arrObjPermissaoDTO as $dto){
          $objReplicacaoPermissaoDTO = new ReplicacaoPermissaoDTO();
          $objReplicacaoPermissaoDTO->setStrStaOperacao('E');
          $objReplicacaoPermissaoDTO->setNumIdSistema($dto->getNumIdSistema());
          $objReplicacaoPermissaoDTO->setNumIdUsuario($dto->getNumIdUsuario());
          $objReplicacaoPermissaoDTO->setNumIdUnidade($dto->getNumIdUnidade());
          $objReplicacaoPermissaoDTO->setNumIdPerfil($dto->getNumIdPerfil());
          $objSistemaRN->replicarPermissao($objReplicacaoPermissaoDTO);
        }
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');

      LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

    }catch(Exception $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);

      throw new InfraException('Erro replicando permissões agendadas.',$e);
    }
  }

  protected function replicarUnidadesHierarquiaSEIConectado(){
    try{
      
      ini_set('max_execution_time','0');
      ini_set('memory_limit','-1');
      
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
      
      $numSeg = InfraUtil::verificarTempoProcessamento();
            
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $numIdSistemaSei = $objInfraParametro->getValor('ID_SISTEMA_SEI');
      
      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->retNumIdHierarquia();
      $objSistemaDTO->retStrWebService();
      $objSistemaDTO->setNumIdSistema($numIdSistemaSei);
      
      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
      
      if ($objSistemaDTO==null){
        throw new InfraException('Sistema SEI não encontrado.');
      }

      if (InfraString::isBolVazia($objSistemaDTO->getStrWebService())){
        throw new InfraException('Sistema SEI não possui Web Service configurado.');
      }
    
      $objRelHierarquiaUnidadeDTO = new RelHierarquiaUnidadeDTO();
      $objRelHierarquiaUnidadeDTO->setBolExclusaoLogica(false);
      $objRelHierarquiaUnidadeDTO->retNumIdUnidade();
      $objRelHierarquiaUnidadeDTO->retStrSiglaUnidade();
      $objRelHierarquiaUnidadeDTO->retStrSiglaOrgaoUnidade();
      $objRelHierarquiaUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());
      $objRelHierarquiaUnidadeDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);
      
      $objRelHierarquiaUnidadeRN = new RelHierarquiaUnidadeRN();
      $arrIdUnidade = InfraArray::converterArrInfraDTO($objRelHierarquiaUnidadeRN->listar($objRelHierarquiaUnidadeDTO),'IdUnidade');
    
      InfraDebug::getInstance()->gravar('REPLICANDO UNIDADES PARA O SEI ('.count($arrIdUnidade).' REGISTROS)...');

      $numRegistros 			=	count($arrIdUnidade);
      $numRegistrosPagina = 100;
      $numPaginas 				= ceil($numRegistros/$numRegistrosPagina);

      $objSistemaRN = new SistemaRN();
      for ($numPaginaAtual = 0; $numPaginaAtual < $numPaginas; $numPaginaAtual++){

        $offset = ($numPaginaAtual*$numRegistrosPagina);

        if (($offset + $numRegistrosPagina) > $numRegistros) {
          $length = $numRegistros - $offset;
        }else{
          $length = $numRegistrosPagina;
        }

        InfraDebug::getInstance()->gravar(($offset+$length).'...');

        $objReplicacaoUnidadeDTO = new ReplicacaoUnidadeDTO();
        $objReplicacaoUnidadeDTO->setStrStaOperacao('A');
        $objReplicacaoUnidadeDTO->setNumIdHierarquia($objSistemaDTO->getNumIdHierarquia());
        $objReplicacaoUnidadeDTO->setNumIdSistema($numIdSistemaSei);
        $objReplicacaoUnidadeDTO->setNumIdUnidade(array_slice($arrIdUnidade, $offset, $length));
      
        try{

          $objSistemaRN->replicarUnidade($objReplicacaoUnidadeDTO);

        }catch(Exception $e) {
          InfraDebug::getInstance()->gravar($this->obterTextoLog($e));
        }
      }
  
      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
      
    }catch(Exception $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
    
      throw new InfraException('Erro replicando unidades para o SEI.',$e);
    }
  }
  
  protected function replicarTodosUsuariosSEIConectado(){
    try{
      
      ini_set('max_execution_time','0');
      ini_set('memory_limit','-1');
      
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
      
      $numSeg = InfraUtil::verificarTempoProcessamento();
      
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $numIdSistemaSei = $objInfraParametro->getValor('ID_SISTEMA_SEI');
      
      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->setStrIdOrigem(null,InfraDTO::$OPER_DIFERENTE);
      $objUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
      
      $objUsuarioRN = new UsuarioRN();
      $arrIdUsuario = InfraArray::converterArrInfraDTO($objUsuarioRN->listar($objUsuarioDTO),'IdUsuario');
      
      InfraDebug::getInstance()->gravar('REPLICANDO TODOS OS USUÁRIOS PARA O SEI ('.count($arrIdUsuario).' REGISTROS)...');

      $numRegistros 			=	count($arrIdUsuario);
      $numRegistrosPagina = 100;
      $numPaginas 				= ceil($numRegistros/$numRegistrosPagina);

      $objSistemaRN = new SistemaRN();
      for ($numPaginaAtual = 0; $numPaginaAtual < $numPaginas; $numPaginaAtual++){

        $offset = ($numPaginaAtual*$numRegistrosPagina);

        if (($offset + $numRegistrosPagina) > $numRegistros) {
          $length = $numRegistros - $offset;
        }else{
          $length = $numRegistrosPagina;
        }

        InfraDebug::getInstance()->gravar(($offset+$length).'...');

        $objReplicacaoUsuarioDTO = new ReplicacaoUsuarioDTO();
        $objReplicacaoUsuarioDTO->setStrStaOperacao('A');
        $objReplicacaoUsuarioDTO->setNumIdSistema($numIdSistemaSei);
        $objReplicacaoUsuarioDTO->setNumIdUsuario(array_slice($arrIdUsuario, $offset, $length));

        try {

          $objSistemaRN->replicarUsuario($objReplicacaoUsuarioDTO);

        }catch(Exception $e) {
          InfraDebug::getInstance()->gravar($this->obterTextoLog($e));
        }
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
      
    }catch(Exception $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
    
      throw new InfraException('Erro replicando todos os usuários para o SEI.',$e);
    }
  }

  protected function replicarRegrasAuditoriaSEIConectado(){
    try{

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      $numIdSistemaSei = $objInfraParametro->getValor('ID_SISTEMA_SEI');

      $objRegraAuditoriaDTO = new RegraAuditoriaDTO();
      $objRegraAuditoriaDTO->retNumIdRegraAuditoria();
      $objRegraAuditoriaDTO->setNumIdSistema($numIdSistemaSei);

      $objRegraAuditoriaRN = new RegraAuditoriaRN();
      $arrObjRegraAuditoriaDTO = $objRegraAuditoriaRN->listar($objRegraAuditoriaDTO);

      InfraDebug::getInstance()->gravar('REPLICANDO REGRAS DE AUDITORIA PARA O SEI ('.count($arrObjRegraAuditoriaDTO).' REGISTROS)...');

      $objSistemaRN = new SistemaRN();
      foreach($arrObjRegraAuditoriaDTO as $objRegraAuditoriaDTO){

        $objReplicacaoRegraAuditoriaDTO = new ReplicacaoRegraAuditoriaDTO();
        $objReplicacaoRegraAuditoriaDTO->setStrStaOperacao('A');
        $objReplicacaoRegraAuditoriaDTO->setNumIdRegraAuditoria($objRegraAuditoriaDTO->getNumIdRegraAuditoria());

        try {

          $objSistemaRN->replicarRegraAuditoria($objReplicacaoRegraAuditoriaDTO);

        }catch(Exception $e) {
          InfraDebug::getInstance()->gravar($this->obterTextoLog($e));
        }
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');

      LogSip::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

    }catch(Exception $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);

      throw new InfraException('Erro replicando regras de auditoria para o SEI.',$e);
    }
  }


  private function obterTextoLog(Exception $e){

    if ($e instanceof InfraException) {

      if ($e->contemValidacoes()) {
        return $e->__toString();
      } else if ($e->getObjException() instanceof SoapFault && InfraException::getTipoInfraException($e->getObjException()) == 'INFRA_VALIDACAO') {
        return $e->getObjException()->faultstring;
      } else if ($e->getObjException() != null) {
        return InfraException::inspecionar($e->getObjException());
      } else {
        return InfraException::inspecionar($e);
      }

    }else{
      return InfraException::inspecionar($e);
    }
  }
}
?>