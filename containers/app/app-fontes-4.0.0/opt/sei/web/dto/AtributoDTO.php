<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'atributo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtributo',
                                   'id_atributo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoFormulario',
                                   'id_tipo_formulario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Rotulo',
                                   'rotulo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaTipo',
                                   'sta_tipo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinObrigatorio',
                                   'sin_obrigatorio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'ValorMinimo',
                                    'valor_minimo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'ValorMaximo',
                                    'valor_maximo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'ValorPadrao',
                                    'valor_padrao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'Tamanho',
                                    'tamanho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'Linhas',
                                    'linhas');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'Decimais',
                                    'decimais');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'Mascara',
                                    'mascara');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                    'Ordem',
                                    'ordem');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'DescricaoTipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjDominioDTO');

    $this->configurarPK('IdAtributo', InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>