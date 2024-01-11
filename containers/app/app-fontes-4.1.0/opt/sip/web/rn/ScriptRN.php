<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 19/04/2018 - criado por mga
 *
 */

require_once dirname(__FILE__) . '/../Sip.php';

class ScriptRN extends InfraRN {

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }

  protected function atualizarSequenciasConectado() {
    try {
      ini_set('max_execution_time', '0');
      ini_set('mssql.timeout', '0');

      InfraDebug::getInstance()->setBolLigado(true);
      InfraDebug::getInstance()->setBolDebugInfra(false);
      InfraDebug::getInstance()->setBolEcho(true);
      InfraDebug::getInstance()->limpar();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      InfraDebug::getInstance()->gravar('Atualizar Sequencias - Iniciando...');

      $arrSequencias = array(
        'seq_infra_auditoria', 'seq_infra_log'
      );

      foreach ($arrSequencias as $strSequencia) {
        if (BancoSip::getInstance() instanceof InfraSqlServer || BancoSip::getInstance() instanceof InfraMySql) {
          BancoSip::getInstance()->executarSql('drop table ' . $strSequencia);
        } else {
          BancoSip::getInstance()->executarSql('drop sequence ' . $strSequencia);
        }

        $rs = BancoSip::getInstance()->consultarSql('select ' . BancoSip::getInstance()->formatarSelecaoDbl(null, 'max(' . str_replace('seq_', 'id_', $strSequencia) . ')', 'ultimo') . ' from ' . str_replace('seq_', '', $strSequencia));

        if ($rs[0]['ultimo'] == null) {
          $numInicial = 1;
        } else {
          $numInicial = $rs[0]['ultimo'] + 1;
        }

        if (BancoSip::getInstance() instanceof InfraMySql) {
          BancoSip::getInstance()->executarSql('create table ' . $strSequencia . ' (id bigint not null primary key AUTO_INCREMENT, campo char(1) null) AUTO_INCREMENT = ' . $numInicial);
        } else {
          if (BancoSip::getInstance() instanceof InfraSqlServer) {
            BancoSip::getInstance()->executarSql('create table ' . $strSequencia . ' (id bigint identity(' . $numInicial . ',1), campo char(1) null)');
          } else {
            if (BancoSip::getInstance() instanceof InfraOracle) {
              BancoSip::getInstance()->criarSequencialNativa($strSequencia, $numInicial);
            }
          }
        }

        if ($numInicial > 1 && BancoSip::getInstance() instanceof InfraMySql) {
          BancoSip::getInstance()->executarSql('insert into ' . $strSequencia . ' (id,campo) values (' . ($numInicial - 1) . ',\'0\')');
          BancoSip::getInstance()->executarSql('alter table ' . $strSequencia . ' AUTO_INCREMENT = ' . $numInicial);
        }

        InfraDebug::getInstance()->gravar($strSequencia . ': ' . $numInicial);
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

      InfraDebug::getInstance()->gravar('Atualizar Sequencias - Finalizado em ' . InfraData::formatarTimestamp($numSeg));

      InfraDebug::getInstance()->setBolDebugInfra(false);
    } catch (Exception $e) {
      throw new InfraException('Erro atualizando sequencias da base de dados.', $e);
    }
  }
}

?>