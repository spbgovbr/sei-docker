<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/11/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class AcessoDTO extends InfraDTO {

  public static $ADMINISTRADOR = 1;
  public static $COORDENADOR_PERFIL = 2;
  public static $COORDENADOR_UNIDADE = 4;
  public static $PERMISSAO = 8;
  public static $TODOS = 16;

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Tipo');

    //sistema acessado
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSistema');

    //sigla do sistema acessado
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaSistema');

    //órgão do sistema acessado
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistema');

    //perfil coordenado, se tipo COORDENADOR_PERFIL
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPerfil');

    //unidade coordenada, se tipo COORDENADOR_UNIDADE
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUnidade');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinGlobalUnidade');
  }

}

?>