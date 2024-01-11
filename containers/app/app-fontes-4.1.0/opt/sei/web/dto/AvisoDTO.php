<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/01/2021 - criado por cas84
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvisoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'aviso';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAviso', 'id_aviso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaAviso', 'sta_aviso');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinLiberado', 'sin_liberado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Inicio', 'dth_inicio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Fim', 'dth_fim');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Link', 'link');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Imagem', 'imagem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdOrgaoRelAvisoOrgao', 'id_orgao', 'rel_aviso_orgao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaOrgaoRelAvisoOrgao', 'sigla', 'orgao');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'NomeArquivo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelAvisoOrgaoDTO');

    $this->configurarPK('IdAviso',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdAviso','rel_aviso_orgao','id_aviso');
    $this->configurarFK('IdOrgaoRelAvisoOrgao','orgao','id_orgao');

  }
}
