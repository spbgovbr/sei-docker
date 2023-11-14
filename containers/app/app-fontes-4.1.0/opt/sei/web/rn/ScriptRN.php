<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/04/2018 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class ScriptRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function atualizarSequenciasConectado(){

    try{

      ini_set('max_execution_time','0');
      ini_set('mssql.timeout','0');

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(true);
      InfraDebug::getInstance()->limpar();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      InfraDebug::getInstance()->gravar('Atualizar Sequencias - Iniciando...');

      $arrSequencias = array(
          'seq_acesso',
          'seq_acesso_externo',
          'seq_acompanhamento',
          'seq_anexo',
          'seq_anotacao',
          'seq_arquivo_extensao',
          'seq_assinante',
          'seq_assinatura',
          'seq_assunto',
          'seq_atividade',
          'seq_atributo',
          'seq_atributo_andamento',
          'seq_base_conhecimento',
          'seq_bloco',
          'seq_cargo',
          'seq_cidade',
          'seq_conjunto_estilos',
          'seq_conjunto_estilos_item',
          'seq_contato',
          'seq_controle_interno',
          'seq_dominio',
          'seq_email_grupo_email',
          'seq_email_unidade',
          'seq_estilo',
          'seq_feed',
          'seq_feriado',
          'seq_grupo_acompanhamento',
          'seq_grupo_contato',
          'seq_grupo_email',
          'seq_grupo_protocolo_modelo',
          'seq_grupo_serie',
          'seq_hipotese_legal',
          'seq_imagem_formato',
          'seq_localizador',
          'seq_lugar_localizador',
          'seq_modelo',
          'seq_nivel_acesso_permitido',
          'seq_novidade',
          'seq_numeracao',
          'seq_observacao',
          'seq_operacao_servico',
          'seq_ordenador_despesa',
          'seq_pais',
          'seq_participante',
          'seq_protocolo_modelo',
          'seq_publicacao',
          'seq_rel_protocolo_protocolo',
          'seq_retorno_programado',
          'seq_secao_documento',
          'seq_secao_imprensa_nacional',
          'seq_secao_modelo',
          'seq_serie',
          'seq_serie_publicacao',
          'seq_servico',
          'seq_texto_padrao_interno',
          'seq_tipo_conferencia',
          'seq_tipo_localizador',
          'seq_tipo_procedimento',
          'seq_tipo_suporte',
          'seq_tratamento',
          'seq_uf',
          'seq_unidade_publicacao',
          'seq_veiculo_imprensa_nacional',
          'seq_veiculo_publicacao',
          'seq_vocativo',
          'seq_grupo_unidade',
          'seq_email_utilizado',
          'seq_andamento_situacao',
          'seq_situacao',
          'seq_tarefa',
          'seq_email_sistema',
          'seq_tipo_formulario',
          'seq_tarja_assinatura',
          'seq_monitoramento_servico',
          'seq_tipo_contato',
          'seq_rel_unidade_tipo_contato',
          'seq_marcador',
          'seq_andamento_marcador',
          'seq_assunto_proxy',
          'seq_tabela_assuntos',
          'seq_serie_restricao',
          'seq_tipo_proced_restricao');

      foreach($arrSequencias as $strSequencia){

        if (BancoSEI::getInstance() instanceof InfraSqlServer || BancoSEI::getInstance() instanceof InfraMySql){
          BancoSEI::getInstance()->executarSql('drop table '.$strSequencia);
        }else{
          BancoSEI::getInstance()->executarSql('drop sequence '.$strSequencia);
        }

        $strIdOrigem = str_replace('seq_','id_',$strSequencia);
        $strTabelaOrigem = str_replace('seq_','',$strSequencia);

        $rs = BancoSEI::getInstance()->consultarSql('select max('.$strIdOrigem.') as ultimo from '.$strTabelaOrigem);

        if ($rs[0]['ultimo'] == null){

          $numInicial = 1;

        }else{

          $numInicial = $rs[0]['ultimo'];

          if (($strSequencia == 'seq_tarefa' || $strSequencia=='seq_email_sistema') && $numInicial < 1000){
            $numInicial = 1000;
          }

          $numInicial++;
        }

        BancoSEI::getInstance()->criarSequencialNativa($strSequencia, $numInicial);

        if ($numInicial > 1 && BancoSEI::getInstance() instanceof InfraMySql){
          BancoSEI::getInstance()->executarSql('insert into '.$strSequencia.' (id,campo) values ('.($numInicial-1).',\'0\')');
          BancoSEI::getInstance()->executarSql('alter table '.$strSequencia.' AUTO_INCREMENT = '.$numInicial);
        }

        InfraDebug::getInstance()->gravar($strSequencia.': '.$numInicial);

      }

      $arrSequencias = array(
          'seq_auditoria_protocolo',
          'seq_estatisticas',
          'seq_infra_auditoria',
          'seq_infra_log',
          'seq_infra_navegador',
          'seq_protocolo',
          'seq_versao_secao_documento',
          'seq_controle_unidade',
          'seq_monitoramento_servico');

      foreach($arrSequencias as $strSequencia){

        if (BancoSEI::getInstance() instanceof InfraSqlServer || BancoSEI::getInstance() instanceof InfraMySql){
          BancoSEI::getInstance()->executarSql('drop table '.$strSequencia);
        }else{
          BancoSEI::getInstance()->executarSql('drop sequence '.$strSequencia);
        }

        $rs = BancoSEI::getInstance()->consultarSql('select '.BancoSEI::getInstance()->formatarSelecaoDbl(null,'max('.str_replace('seq_','id_',$strSequencia).')','ultimo').' from '.str_replace('seq_','',$strSequencia));

        if ($rs[0]['ultimo'] == null){
          $numInicial = 1;
        }else{
          $numInicial = $rs[0]['ultimo'] + 1;
        }

        if (BancoSEI::getInstance() instanceof InfraMySql){
          BancoSEI::getInstance()->executarSql('create table '.$strSequencia.' (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = '.$numInicial);
        }else if (BancoSEI::getInstance() instanceof InfraSqlServer){
          BancoSEI::getInstance()->executarSql('create table '.$strSequencia.' (id bigint identity('.$numInicial.',1), campo char(1) null)');
        }else{
          BancoSEI::getInstance()->criarSequencialNativa($strSequencia, $numInicial);
        }

        if ($numInicial > 1 && BancoSEI::getInstance() instanceof InfraMySql){
          BancoSEI::getInstance()->executarSql('insert into '.$strSequencia.' (id,campo) values ('.($numInicial-1).',\'0\')');
          BancoSEI::getInstance()->executarSql('alter table '.$strSequencia.' AUTO_INCREMENT = '.$numInicial);
        }

        InfraDebug::getInstance()->gravar($strSequencia.': '.$numInicial);
      }

      if (BancoSEI::getInstance() instanceof InfraSqlServer || BancoSEI::getInstance() instanceof InfraMySql){
        BancoSEI::getInstance()->executarSql('drop table seq_upload');
      }else{
        BancoSEI::getInstance()->executarSql('drop sequence seq_upload');
      }

      if (BancoSEI::getInstance() instanceof InfraMySql){
        BancoSEI::getInstance()->executarSql('create table seq_upload (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = 1');
      }else if (BancoSEI::getInstance() instanceof InfraSqlServer){
        BancoSEI::getInstance()->executarSql('create table seq_upload (id bigint identity(1,1), campo char(1) null)');
      }else{
        BancoSEI::getInstance()->criarSequencialNativa('seq_upload', 1);
      }

      InfraDebug::getInstance()->gravar('seq_upload: 1');


      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

      InfraDebug::getInstance()->gravar('Atualizar Sequencias - Finalizado em '.InfraData::formatarTimestamp($numSeg));

      InfraDebug::getInstance()->setBolDebugInfra(true);

    }catch(Exception $e){
      throw new InfraException('Erro atualizando sequencias da base de dados.',$e);
    }
  }
}
?>