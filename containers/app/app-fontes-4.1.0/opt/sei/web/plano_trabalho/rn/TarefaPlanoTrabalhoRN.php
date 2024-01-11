<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/10/2022 - criado por mgb29
 *
 */

require_once dirname(__FILE__) . '/../../SEI.php';

class TarefaPlanoTrabalhoRN extends InfraRN {

  public static $TPT_ASSOCIACAO_PLANO_TRABALHO = 1;
  public static $TPT_ATUALIZACAO_ITEM_ETAPA = 2;
  public static $TPT_ASSOCIACAO_DOCUMENTO_ITEM_ETAPA = 3;
  public static $TPT_REMOCAO_ASSOCIACAO_DOCUMENTO_ITEM_ETAPA = 4;
  public static $TPT_REMOCAO_ASSOCIACAO_PLANO_TRABALHO = 5;

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }

}

?>