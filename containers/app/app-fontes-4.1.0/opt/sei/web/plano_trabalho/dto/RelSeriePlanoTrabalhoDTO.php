<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 02/01/2023 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.44
 **/

require_once dirname(__FILE__) . '/../../SEI.php';

class RelSeriePlanoTrabalhoDTO extends InfraDTO {
  public function getStrNomeTabela() {
    return 'rel_serie_plano_trabalho';
  }

  public function montar() {
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdSerie', 'id_serie');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdPlanoTrabalho', 'id_plano_trabalho');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeSerie', 'nome', 'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomePlanoTrabalho', 'nome', 'plano_trabalho');

    $this->configurarPK('IdSerie', InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdPlanoTrabalho', InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdPlanoTrabalho', 'plano_trabalho', 'id_plano_trabalho');

  }
}
