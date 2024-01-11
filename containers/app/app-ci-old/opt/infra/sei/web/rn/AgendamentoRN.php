<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/04/2013 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class AgendamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function testarAgendamento(){
    try{
      LogSEI::getInstance()->gravar('Teste Agendamento SEI',InfraLog::$INFORMACAO);
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());

      $objEmailDTO = new EmailDTO();
      $objEmailDTO->setStrDe($objInfraParametro->getValor('SEI_EMAIL_SISTEMA'));
      $objEmailDTO->setStrPara($objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));
      $objEmailDTO->setStrAssunto('Teste Agendamento SEI');
      $objEmailDTO->setStrMensagem('Agendamento SEI executado com sucesso.');

      EmailRN::processar(array($objEmailDTO));

    }catch(Throwable $e){
      throw new InfraException('Erro realizando teste de agendamento.',$e);
    }
  }
  
  public function otimizarIndicesSolr(){
    try{

      ini_set('max_execution_time','0');
  
      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
      
      $numSeg = InfraUtil::verificarTempoProcessamento();
      
      InfraDebug::getInstance()->gravar('OTIMIZANDO INDICES DO SOLR');

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_FAILONERROR, true);
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_URL, ConfiguracaoSEI::getInstance()->getValor('Solr','Servidor').'/'.ConfiguracaoSEI::getInstance()->getValor('Solr','CoreProtocolos').'/update?optimize=true');
      if (curl_exec($ch)===false){
        throw new InfraException('Erro executando URL de otimização do índice de protocolos no Solr: '.curl_error($ch));
      }

      curl_setopt($ch, CURLOPT_URL, ConfiguracaoSEI::getInstance()->getValor('Solr','Servidor').'/'.ConfiguracaoSEI::getInstance()->getValor('Solr','CoreBasesConhecimento').'/update?optimize=true');
      if (curl_exec($ch)===false){
        throw new InfraException('Erro executando URL de otimização do índice de bases de conhecimento no Solr: '.curl_error($ch));
      }

      curl_setopt($ch, CURLOPT_URL, ConfiguracaoSEI::getInstance()->getValor('Solr','Servidor').'/'.ConfiguracaoSEI::getInstance()->getValor('Solr','CorePublicacoes').'/update?optimize=true');
      if (curl_exec($ch)===false){
        throw new InfraException('Erro executando URL de otimização do índice de publicações no Solr: '.curl_error($ch));
      }

      curl_close($ch);

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
      
    }catch(Throwable $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      
			throw new InfraException('Erro otimizando índices do Solr.',$e);
    }
  }
  
  protected function removerDadosTemporariosEstatisticasControlado(){
    try{

      LimiteSEI::getInstance()->configurarNivel3();

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();

      $objAgendamentoBD = new AgendamentoBD($this->getObjInfraIBanco());

      $numSeg = InfraUtil::verificarTempoProcessamento();
      
      InfraDebug::getInstance()->gravar('REMOVENDO DADOS DE ESTATISTICAS');
      InfraDebug::getInstance()->gravar($objAgendamentoBD->removerDadosEstatisticas().' REGISTROS');

      InfraDebug::getInstance()->gravar('REMOVENDO DADOS DE CONTROLE DE UNIDADE');
      InfraDebug::getInstance()->gravar($objAgendamentoBD->removerDadosControleUnidade().' REGISTROS');

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
      
    }catch(Throwable $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      
			throw new InfraException('Erro removendo dados de estatísticas.',$e);
    }
  }

  protected function removerDadosTemporariosAuditoriaControlado(){
    try{

      LimiteSEI::getInstance()->configurarNivel3();

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
  
      $numSeg = InfraUtil::verificarTempoProcessamento();
      
      InfraDebug::getInstance()->gravar('REMOVENDO DADOS TEMPORARIOS DE AUDITORIA');

      $objAgendamentoBD = new AgendamentoBD($this->getObjInfraIBanco());
      $ret = $objAgendamentoBD->removerDadosTemporariosAuditoria();
      
      InfraDebug::getInstance()->gravar($ret.' REGISTROS');
      
      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
      
    }catch(Throwable $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      
			throw new InfraException('Erro removendo dados temporários de auditoria.',$e);
    }
  }

  public function removerAquivosExternosExcluidos(){
    try{

      LimiteSEI::getInstance()->configurarNivel3();

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      InfraDebug::getInstance()->gravar('REMOVENDO ARQUIVOS EXTERNOS EXCLUIDOS');

      $objAnexoRN = new AnexoRN();

      $diretorioAno = dir(ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/');
      $arrAno = array();
      while($ano = $diretorioAno -> read()){
        if (is_numeric($ano)){
          $arrAno[] = $ano;
        }
      }
      $diretorioAno->close();

      $numArquivosRemovidos = 0;
      $numBytesTotal = 0;
      if (count($arrAno)){

        sort($arrAno);

        foreach($arrAno as $ano){

          $arrMes = array();
          $diretorioMes = dir(ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/'.$ano);
          while($mes = $diretorioMes -> read()){
            if (is_numeric($mes)){
              $arrMes[] = $mes;
            }
          }
          $diretorioMes->close();

          if (count($arrMes)){
            sort($arrMes);

            foreach($arrMes as $mes){
              $arrDia = array();
              $diretorioDia = dir(ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/'.$ano.'/'.$mes);
              while($dia = $diretorioDia -> read()){
                if (is_numeric($dia)){
                  $arrDia[] = $dia;
                }
              }
              $diretorioDia->close();

              if (count($arrDia)){

                sort($arrDia);

                $objAnexoDTO = new AnexoDTO();
                $objAnexoDTO->retNumIdAnexo();
                $objAnexoDTO->adicionarCriterio(array('Inclusao','Inclusao'),
                    array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
                    array($arrDia[0].'/'.$mes.'/'.$ano.' 00:00:00',$arrDia[count($arrDia)-1].'/'.$mes.'/'.$ano.' 23:59:59'),
                    InfraDTO::$OPER_LOGICO_AND);
                $objAnexoDTO->setOrdNumIdAnexo(InfraDTO::$TIPO_ORDENACAO_ASC);

                $arrIdAnexosMes = InfraArray::indexarArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO),'IdAnexo');
                //InfraDebug::getInstance()->gravar($arrDia[0].'/'.$mes.'/'.$ano.' até '.$arrDia[count($arrDia)-1].'/'.$mes.'/'.$ano.': '.count($arrIdAnexosMes).' anexos');

                foreach($arrDia as $dia){

                  $diretorioArquivos = opendir(ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/'.$ano.'/'.$mes.'/'.$dia);

                  if ($diretorioArquivos){

                    while (($arquivo = readdir($diretorioArquivos)) !== false) {
                      if (is_numeric($arquivo) && !isset($arrIdAnexosMes[$arquivo])){
                        $strCaminhoArquivo = ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/'.$ano.'/'.$mes.'/'.$dia.'/'.$arquivo;
                        $numBytesArquivo = filesize($strCaminhoArquivo);
                        unlink($strCaminhoArquivo);
                        InfraDebug::getInstance()->gravar($strCaminhoArquivo.' ('.InfraUtil::formatarTamanhoBytes($numBytesArquivo).')');
                        $numBytesTotal += $numBytesArquivo;
                        $numArquivosRemovidos++;
                      }
                    }
                    closedir($diretorioArquivos);
                  }
                }

                unset($arrIdAnexosMes);
              }
            }
          }
        }
      }

      InfraDebug::getInstance()->gravar($numArquivosRemovidos.' ARQUIVOS REMOVIDOS ('.InfraUtil::formatarTamanhoBytes($numBytesTotal).')');

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');

      LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);


    }catch(Throwable $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);

      throw new InfraException('Erro removendo arquivos externos excluídos.',$e);
    }
  }

	public function removerAquivosNaoUtilizados(){
    try{

      LimiteSEI::getInstance()->configurarNivel3();


      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
  
      $numSeg = InfraUtil::verificarTempoProcessamento();

      InfraDebug::getInstance()->gravar('REMOVENDO ARQUIVOS NAO UTILIZADOS');

      $objAnexoRN = new AnexoRN();

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setBolExclusaoLogica(false);
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retDthInclusao();

      $objAnexoDTO->adicionarCriterio(array('IdProtocolo','IdBaseConhecimento','IdProjeto'),
                                     array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                     array(null,null,null),
                                     array(InfraDTO::$OPER_LOGICO_AND,InfraDTO::$OPER_LOGICO_AND),
                                     'cIsolado');

      $objAnexoDTO->adicionarCriterio(array('SinAtivo'),
                                       array(InfraDTO::$OPER_IGUAL),
                                       array('N'),
                                       null,
                                       'cDesativado');

      $objAnexoDTO->agruparCriterios(array('cIsolado','cDesativado'),InfraDTO::$OPER_LOGICO_OR);

      $objAnexoDTO->setDthInclusao(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, InfraData::getStrDataHoraAtual()),InfraDTO::$OPER_MENOR_IGUAL);

      $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

      $numArquivosRemovidos = 0;
      $numBytesTotal = 0;
      foreach($arrObjAnexoDTO as $objAnexoDTO){

        $strCaminhoArquivo = $objAnexoRN->obterLocalizacao($objAnexoDTO);

        $bolExclusaoBaseOk = true;
        try {
          $objAnexoRN->excluirRN0226(array($objAnexoDTO));
        }catch(Exception $e){
          $bolExclusaoBaseOk = false;
          InfraDebug::getInstance()->gravar('ERRO EXCLUINDO ANEXO '.$objAnexoDTO->getNumIdAnexo().': '.$e->__toString());
        }

        if ($bolExclusaoBaseOk && file_exists($strCaminhoArquivo)) {
          $numBytesArquivo = filesize($strCaminhoArquivo);
          unlink($strCaminhoArquivo);
          InfraDebug::getInstance()->gravar($strCaminhoArquivo . ' (' . InfraUtil::formatarTamanhoBytes($numBytesArquivo) . ')');
          $numBytesTotal += $numBytesArquivo;
          $numArquivosRemovidos++;
        }
      }

      InfraDebug::getInstance()->gravar($numArquivosRemovidos.' ARQUIVOS REMOVIDOS ('.InfraUtil::formatarTamanhoBytes($numBytesTotal).')');
      
      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
      
      LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);

    }catch(Throwable $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      
			throw new InfraException('Erro removendo arquivos não utilizados.',$e);
    }
  }

  public function confirmarPublicacaoInterna(){
    try{

      LimiteSEI::getInstance()->configurarNivel3();

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
      InfraDebug::getInstance()->limpar();
  
      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_SEI, SessaoSEI::$UNIDADE_TESTE);
      
      $numSeg = InfraUtil::verificarTempoProcessamento();
  
      InfraDebug::getInstance()->gravar('CONFIRMANDO PUBLICACOES INTERNAS');
  
      $objPublicacaoDTO = new PublicacaoDTO();
      $objPublicacaoDTO->setDtaPublicacao(InfraData::getStrDataAtual());
      //$objPublicacaoDTO->setDtaPublicacao('17/09/2013');
      
      $objPublicacaoRN = new PublicacaoRN();
      $objPublicacaoRN->confirmarPublicacaoInterna($objPublicacaoDTO);
      
      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
      InfraDebug::getInstance()->gravar('TEMPO TOTAL DE EXECUCAO: '.$numSeg.' s');
      InfraDebug::getInstance()->gravar('FIM');
  
      LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug(),InfraLog::$INFORMACAO);
  
    }catch(Throwable $e){
      InfraDebug::getInstance()->setBolLigado(false);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(false);
  
      throw new InfraException('Erro removendo dados temporários de auditoria.',$e);
    }
  }

  public function processarFederacao(){
    try{

      LimiteSEI::getInstance()->configurarNivel3();

      ini_set('max_execution_time', '600');

      //InfraDebug::getInstance()->setBolLigado(true);
      //InfraDebug::getInstance()->setBolDebugInfra(true);
      //InfraDebug::getInstance()->limpar();

      SessaoSEI::getInstance(false)->simularLogin(SessaoSEI::$USUARIO_SEI, SessaoSEI::$UNIDADE_TESTE);

      if (ConfiguracaoSEI::getInstance()->getValor('Federacao', 'Habilitado',false, false) == true) {
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoRN->processarAgendamento();
      }

      //LogSEI::getInstance()->gravar('Agendamento Federacao: '.InfraDebug::getInstance()->getStrDebug());

    }catch(Throwable $e){
      throw new InfraException('Erro processando agendamento do SEI Federação.',$e);
    }
  }
}
?>