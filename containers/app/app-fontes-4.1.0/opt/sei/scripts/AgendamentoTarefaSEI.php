<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 26/04/2013 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/../web/SEI.php';

  class AgendamentoTarefaSEI extends InfraAgendamentoTarefa {

  private static $instance = null;

    public static function getInstance() {
      if (self::$instance == null) {
        self::$instance = new AgendamentoTarefaSEI(ConfiguracaoSEI::getInstance(), SessaoSEI::getInstance(), BancoSEI::getInstance(), LogSEI::getInstance());
      }
      return self::$instance;
    }
  }

  SessaoSEI::getInstance(false);

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  
  AgendamentoTarefaSEI::getInstance()->executar($objInfraParametro->getValor('SEI_EMAIL_SISTEMA'), $objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR'));
?>