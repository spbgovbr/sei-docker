<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4� REGI�O
 *
 * 11/07/2018 - criado por mga
 *
 * Vers�o do Gerador de C�digo: 1.41.0
 */

require_once dirname(__FILE__) . '/../Sip.php';

class UsuarioHistoricoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco) {
    parent::__construct($objInfraIBanco);
  }

}