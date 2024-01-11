<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RetornoProgramadoDTO extends InfraDTO {

  private $numFiltroFkAtividadeRetorno = null;

  public function __construct(){
    $this->numFiltroFkAtividadeRetorno = InfraDTO::$FILTRO_FK_ON;
    parent::__construct();
  }

  public function getStrNomeTabela() {
  	 return 'retorno_programado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdRetornoProgramado',
                                   'id_retorno_programado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                    'IdProtocolo',
                                    'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeEnvio',
                                   'id_unidade_envio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtividadeEnvio',
                                   'id_atividade_envio');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidadeRetorno',
                                   'id_unidade_retorno');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAtividadeRetorno',
                                   'id_atividade_retorno');

		$this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Programada',
                                   'dta_programada');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Inicial',
                                   'dta_programada');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Final',
                                   'dta_programada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Alteracao',
                                   'dth_alteracao');
                                   
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'SiglaUsuario',
                                   'sigla',
                                   'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
                                   'AberturaAtividadeEnvio',
                                   'ae.dth_abertura',
                                   'atividade ae');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
                                   'AberturaAtividadeRetorno',
                                   'ar.dth_abertura',
                                   'atividade ar');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'SiglaUnidadeEnvio',
                                   'ue.sigla',
                                   'unidade ue');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'DescricaoUnidadeEnvio',
                                   'ue.descricao',
                                   'unidade ue');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                   'SiglaUnidadeRetorno',
                                   'ur.sigla',
                                   'unidade ur');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'DescricaoUnidadeRetorno',
                                    'ur.descricao',
                                    'unidade ur');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoProtocolo',
                                              'protocolo_formatado',
                                              'protocolo');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'DiasPrazo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'DataInicial');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'DataFinal');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ,'ProtocoloDTO');
                                   
    $this->configurarPK('IdRetornoProgramado',InfraDTO::$TIPO_PK_NATIVA );
    
		$this->configurarFK('IdUnidadeEnvio','unidade ue','ue.id_unidade');
    $this->configurarFK('IdUnidadeRetorno','unidade ur','ur.id_unidade');
		$this->configurarFK('IdUsuario','usuario','id_usuario');    
		$this->configurarFK('IdAtividadeEnvio','atividade ae','ae.id_atividade');
		$this->configurarFK('IdAtividadeRetorno','atividade ar','ar.id_atividade', InfraDTO::$TIPO_FK_OPCIONAL, $this->getNumFiltroFkAtividadeRetorno());
    $this->configurarFK('IdProtocolo','protocolo','id_protocolo');
  }

  public function setNumFiltroFkAtividadeRetorno($numFiltroFkAtividadeRetorno){
    $this->numFiltroFkAtividadeRetorno = $numFiltroFkAtividadeRetorno;
  }

  public function getNumFiltroFkAtividadeRetorno(){
    return $this->numFiltroFkAtividadeRetorno;
  }
}
?>