<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TipoProcedimentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'tipo_procedimento';
  }

  public function montar() {

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdTipoProcedimento',
                                   'id_tipo_procedimento');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                            	     'IdHipoteseLegalSugestao',
                            	     'id_hipotese_legal_sugestao');
  	 
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaNivelAcessoSugestao',
                                   'sta_nivel_acesso_sugestao');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                            	     'StaGrauSigiloSugestao',
                            	     'sta_grau_sigilo_sugestao');
  	 
  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinInterno',
                                   'sin_interno');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinOuvidoria',
                                   'sin_ouvidoria');

  	 $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinIndividual',
                                   'sin_individual');

  	 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjNivelAcessoPermitidoDTO');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelTipoProcedimentoAssuntoDTO');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjTipoProcedRestricaoDTO');
		 $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'IdAssunto');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinSomenteUtilizados');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Processos');
     $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Alterados');
                                        
     $this->configurarPK('IdTipoProcedimento', InfraDTO::$TIPO_PK_NATIVA );

     $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>