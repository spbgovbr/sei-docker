<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: RelSecaoModeloEstiloDTO.php 7875 2013-08-20 14:59:02Z bcu $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class RelSecaoModeloEstiloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_secao_modelo_estilo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSecaoModelo',
                                   'id_secao_modelo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdEstilo',
                                   'id_estilo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinPadrao',
                                   'sin_padrao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'OrdemSecaoModelo',
                                              'ordem',
                                              'secao_modelo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdModelo',
                                              'id_modelo',
                                              'secao_modelo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeModelo',
                                              'nome',
                                              'modelo');
        
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeEstilo',
                                              'nome',
                                              'estilo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'Formatacao',
                                              'formatacao',
                                              'estilo');

    $this->configurarPK('IdSecaoModelo',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdEstilo',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSecaoModelo', 'secao_modelo', 'id_secao_modelo');
    $this->configurarFK('IdEstilo', 'estilo', 'id_estilo');
  }
}
?>