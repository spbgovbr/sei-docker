<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/03/2022 - criado por mga
*
*
*/

require_once dirname(__FILE__).'/../SEI.php';

abstract class SeiUtilWS extends InfraWS {

  public function __call($func, $params) {
    try{

      SessaoSEI::getInstance(false);

      if (ConfiguracaoSEI::getInstance()->getValor('SEI','WebServices',false,true)!==true){
        throw new InfraException('Serviços não estão disponíveis.');
      }

      ManutencaoSEI::validarWebServices();

      if (!method_exists($this, $func.'Monitorado')) {
        throw new InfraException('Serviço ['.get_class($this).'.'.$func.'] não encontrado.');
      }

      BancoSEI::getInstance()->abrirConexao();

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->setStrSigla($params[0]);
      $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      if ($objUsuarioDTO==null){
        throw new InfraException('Sistema ['.$params[0].'] não encontrado.');
      }

      $objServicoRN = new ServicoRN();

      $objServicoDTO = new ServicoDTO();
      $objServicoDTO->retNumIdServico();
      $objServicoDTO->retStrIdentificacao();
      $objServicoDTO->retStrSiglaUsuario();
      $objServicoDTO->retNumIdUsuario();
      $objServicoDTO->retStrServidor();
      $objServicoDTO->retStrSinLinkExterno();
      $objServicoDTO->retNumIdContatoUsuario();
      $objServicoDTO->retStrChaveAcesso();
      $objServicoDTO->retStrSinServidor();
      $objServicoDTO->retStrSinChaveAcesso();
      $objServicoDTO->setNumIdUsuario($objUsuarioDTO->getNumIdUsuario());

      if(strpos($params[1], ' ') === false && strlen($params[1]) == 72 && preg_match("/[0-9a-z]/", $params[1])){

        $objServicoDTO->setStrCrc(substr($params[1],0,8));
        $objServicoDTO = $objServicoRN->consultar($objServicoDTO);

        if ($objServicoDTO==null){
          throw new InfraException('Serviço do sistema ['.$params[0].'] não encontrado.' );
        }

        if ($objServicoDTO->getStrSinChaveAcesso() == 'N'){
          throw new InfraException('Serviço ['.$objServicoDTO->getStrIdentificacao().'] do sistema ['.$objServicoDTO->getStrSiglaUsuario().'] não possui autenticação por Chave de Acesso.');
        }

        $objInfraBcrypt = new InfraBcrypt();
        if (!$objInfraBcrypt->verificar(md5(substr($params[1],8)), $objServicoDTO->getStrChaveAcesso())) {
          throw new InfraException('Chave de Acesso inválida para o serviço ['.$objServicoDTO->getStrIdentificacao().'] do sistema ['.$objServicoDTO->getStrSiglaUsuario().'].');
        }

      }else{

        $objServicoDTO->setStrIdentificacao($params[1]);

        $objServicoDTO = $objServicoRN->consultar($objServicoDTO);

        if ($objServicoDTO==null){
          throw new InfraException('Serviço ['.$params[1].'] do sistema ['.$params[0].'] não encontrado.');
        }

        if ($objServicoDTO->getStrSinServidor() == 'N'){
          throw new InfraException('Serviço ['.$params[1].'] do sistema ['.$params[0].'] não possui autenticação por Endereço.');
        }

        $this->validarAcessoAutorizado(explode(',',str_replace(' ','',$objServicoDTO->getStrServidor())));

      }

      $numIdUnidade = null;
      if (!in_array($func, array('listarUnidades', 'confirmarDisponibilizacaoPublicacao', 'registrarOuvidoria', 'listarTiposProcedimentoOuvidoria'))){
        $numIdUnidade = $params[2];
      }

      if ($numIdUnidade!=null){

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrSinAtivo();
        $objUnidadeDTO->setNumIdUnidade($numIdUnidade);

        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

        if ($objUnidadeDTO == null){
          throw new InfraException('Unidade ['.$numIdUnidade.'] não encontrada.');
        }

        if ($objUnidadeDTO->getStrSinAtivo()=='N'){
          throw new InfraException('Unidade '.$objUnidadeDTO->getStrSigla().' está desativada.');
        }
      }

      $objServicoDTO->setNumIdUnidade($numIdUnidade);


      if ($numIdUnidade==null){
        SessaoSEI::getInstance()->simularLogin(null, SessaoSEI::$UNIDADE_TESTE, $objServicoDTO->getNumIdUsuario(), null);
      }else{
        SessaoSEI::getInstance()->simularLogin(null, null, $objServicoDTO->getNumIdUsuario(), $numIdUnidade);
      }

      SessaoSEI::getInstance()->setObjServicoDTO($objServicoDTO);

      $numSeg = InfraUtil::verificarTempoProcessamento();

      $debugWebServices = (int)ConfiguracaoSEI::getInstance()->getValor('SEI','DebugWebServices',false,0);

      if ($debugWebServices) {
        InfraDebug::getInstance()->setBolLigado(true);
        InfraDebug::getInstance()->setBolDebugInfra(($debugWebServices==2));
        InfraDebug::getInstance()->limpar();

        InfraDebug::getInstance()->gravar("Serviço: ".$func."\nParâmetros: ".$this->debugParametros($params));

        if ($debugWebServices==1) {
          LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$DEBUG);
        }
      }

      $ret = call_user_func_array(array($this, $func.'Monitorado'), $params);

      if ($debugWebServices==2) {
        LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$DEBUG);
      }

      try {

        $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

        $objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
        $objMonitoramentoServicoDTO->setNumIdServico($objServicoDTO->getNumIdServico());
        $objMonitoramentoServicoDTO->setStrOperacao($func);
        $objMonitoramentoServicoDTO->setDblTempoExecucao($numSeg*1000);
        $objMonitoramentoServicoDTO->setStrIpAcesso(InfraUtil::getStrIpUsuario());
        $objMonitoramentoServicoDTO->setDthAcesso(InfraData::getStrDataHoraAtual());
        $objMonitoramentoServicoDTO->setStrServidor(substr($_SERVER['SERVER_NAME'].' ('.$_SERVER['SERVER_ADDR'].')',0,250));
        $objMonitoramentoServicoDTO->setStrUserAgent(substr($_SERVER['HTTP_USER_AGENT'], 0, 250));

        $objMonitoramentoServicoRN = new MonitoramentoServicoRN();
        $objMonitoramentoServicoRN->cadastrar($objMonitoramentoServicoDTO);

      }catch(Throwable $e){
        try{
          LogSEI::getInstance()->gravar('Erro monitorando acesso do serviço.'."\n".InfraException::inspecionar($e));
        }catch (Throwable $e){}
      }

      BancoSEI::getInstance()->fecharConexao();

      return $ret;

    }catch(Throwable $e){

      try{
        BancoSEI::getInstance()->fecharConexao();
      }catch(Throwable $e2){}

      $this->processarExcecao($e);
    }
  }

  private function debugParametros($var){
    $ret = '';
    if (is_array($var)) {
      $arr = $var;
      if (isset($arr['Conteudo']) && $arr['Conteudo'] != null) {
        $arr['Conteudo'] = strlen($arr['Conteudo']) . ' bytes';
      }
      if (isset($arr['ConteudoMTOM']) && $arr['ConteudoMTOM'] != null) {
        $arr['ConteudoMTOM'] = strlen($arr['ConteudoMTOM']) . ' bytes';
      }
      $numItens = count($arr);
      for ($i = 0; $i < $numItens; $i++) {
        $arr[$i] = $this->debugParametros($arr[$i]);
      }
      $ret = print_r($arr, true);
    }elseif (is_object($var)) {
      $obj = clone($var);
      if (isset($obj->Conteudo) && $obj->Conteudo != null) {
        $obj->Conteudo = strlen($obj->Conteudo) . ' bytes';
      }
      if (isset($obj->ConteudoMTOM) && $obj->ConteudoMTOM != null) {
        $obj->ConteudoMTOM = strlen($obj->ConteudoMTOM) . ' bytes';
      }
      $ret = print_r($obj, true);
    }else{
      $ret = $var;
    }
    return $ret;
  }

}
