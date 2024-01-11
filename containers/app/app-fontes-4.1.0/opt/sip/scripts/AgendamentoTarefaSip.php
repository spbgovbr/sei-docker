<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 29/04/2013 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/../web/Sip.php';

class AgendamentoTarefaSip extends InfraAgendamentoTarefa
{

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new AgendamentoTarefaSip(
                ConfiguracaoSip::getInstance(),
                SessaoSip::getInstance(),
                BancoSip::getInstance(),
                LogSip::getInstance()
            );
        }
        return self::$instance;
    }
}

SessaoSip::getInstance(false);

$objInfraParametro = new InfraParametro(BancoSip::getInstance());

AgendamentoTarefaSip::getInstance()->executar(
    $objInfraParametro->getValor('SIP_EMAIL_SISTEMA'),
    $objInfraParametro->getValor('SIP_EMAIL_ADMINISTRADOR')
);
?>