<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class LocalizadorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'localizador';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdLocalizador',
                                   'id_localizador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoLocalizador',
                                   'id_tipo_localizador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoSuporte',
                                   'id_tipo_suporte');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdLugarLocalizador',
                                   'id_lugar_localizador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Complemento',
                                   'complemento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaEstado',
                                   'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'SeqLocalizador',
                                   'seq_localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoLocalizador',
                                              'nome',
                                              'tipo_localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaTipoLocalizador',
                                              'sigla',
                                              'tipo_localizador');
                                              
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeTipoSuporte',
                                              'nome',
                                              'tipo_suporte');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeLugarLocalizador',
                                              'nome',
                                              'lugar_localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidadeLocalizador',
                                              'sigla',
                                              'unidade');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'DescricaoEstado');                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'Identificacao');                                              
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'QtdProtocolos');                                              
    
    $this->configurarPK('IdLocalizador',InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdTipoLocalizador', 'tipo_localizador', 'id_tipo_localizador');
    $this->configurarFK('IdTipoSuporte', 'tipo_suporte', 'id_tipo_suporte');
    $this->configurarFK('IdLugarLocalizador', 'lugar_localizador', 'id_lugar_localizador');
  }
}
?>