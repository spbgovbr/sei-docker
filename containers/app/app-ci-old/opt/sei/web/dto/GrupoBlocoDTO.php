<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoBlocoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'grupo_bloco';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdGrupoBloco', 'id_grupo_bloco');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Blocos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Documentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'SemAssinatura');

    $this->configurarPK('IdGrupoBloco',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
