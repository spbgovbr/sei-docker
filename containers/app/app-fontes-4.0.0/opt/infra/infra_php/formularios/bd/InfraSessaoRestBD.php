<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 03/07/2019 - criado por cle@trf4.jus.br
  * Versão do Gerador de Código: 1.42.0
  */

  require_once dirname(__FILE__).'/../../Infra.php';

  class InfraSessaoRestBD extends InfraBD {

    public function __construct(InfraIBanco $objInfraIBanco){
       parent::__construct($objInfraIBanco);
    }

  }
