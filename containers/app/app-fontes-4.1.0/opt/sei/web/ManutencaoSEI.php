<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
 * 
 * 25/05/2022 - criado por mgb29
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class ManutencaoSEI {

  private static $MENSAGEM_PADRAO = 'Sistema em Manutenчуo';

  public static function validarInterface(){

    if (ConfiguracaoSEI::getInstance()->getValor('Manutencao','Ativada',false,false) == true){

      $arrUsuarios = ConfiguracaoSEI::getInstance()->getValor('Manutencao','Usuarios',false,null);

      if (is_array($arrUsuarios) && count($arrUsuarios)){
        $strUsuarioAtual = strtolower(SessaoSEI::getInstance()->getStrSiglaUsuario().'/'.SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario());
        $bolEncontrou = false;
        foreach($arrUsuarios as $strUsuario){
          if ($strUsuarioAtual == strtolower($strUsuario)){
            $bolEncontrou = true;
            break;
          }
        }
        if (!$bolEncontrou){
          $strMensagem = ConfiguracaoSEI::getInstance()->getValor('Manutencao','Mensagem',false,self::$MENSAGEM_PADRAO);
          $strDetalhes = ConfiguracaoSEI::getInstance()->getValor('Manutencao','Detalhes',false,'');
          PaginaSEIManutencao::getInstance()->montarPaginaManutencao($strMensagem, $strDetalhes);
        }
      }
    }
  }

  public static function validarWebServices(){

    if (ConfiguracaoSEI::getInstance()->getValor('Manutencao','Ativada',false,false) == true){
      $objInfraException = new InfraException();
      $objInfraException->lancarValidacao(ConfiguracaoSEI::getInstance()->getValor('Manutencao','Mensagem',false,self::$MENSAGEM_PADRAO));
    }
  }
}
?>