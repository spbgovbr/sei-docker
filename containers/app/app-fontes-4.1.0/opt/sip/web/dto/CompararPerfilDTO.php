<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/08/2018 - criado por fbv@trf4.jus.br
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class CompararPerfilDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistemaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSistemaDestino');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPerfilDestino');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoSistemaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdSistemaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdPerfilOrigem');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoSistemaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SiglaSistemaOrigem');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'PerfilOrigem');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoServidor');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoPorta');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoNome');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'BancoSenha');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaTipoBanco');

    //estrutura de dados a ser comparada
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaBaseComparacao');//(L)ocal ou (R)emota
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinSomenteDiferencas');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRecursoDestinoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRecursoOrigemDTO');
  }
}

?>