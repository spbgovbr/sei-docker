<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/12/2015 - criado por mga
 *
 * Versão do Gerador de Código: 1.36.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class ArquivamentoDTO extends InfraDTO {

  private $numTipoFkLocalizador = null;
  private $numTipoFkArquivamento = null;
  private $numTipoFkDesarquivamento = null;
  private $numTipoFkRecebimento = null;
  private $numTipoFkSolicitacao = null;
  private $numTipoFkCancelamento = null;

  public function __construct(){
    $this->numTipoFkLocalizador = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkArquivamento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkDesarquivamento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkRecebimento = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkSolicitacao = InfraDTO::$TIPO_FK_OPCIONAL;
    $this->numTipoFkCancelamento = InfraDTO::$TIPO_FK_OPCIONAL;
    parent::__construct();
  }

  public function getStrNomeTabela() {
    return 'arquivamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
        'IdProtocolo',
        'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdLocalizador',
        'id_localizador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdAtividadeArquivamento',
        'id_atividade_arquivamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdAtividadeDesarquivamento',
        'id_atividade_desarquivamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdAtividadeRecebimento',
        'id_atividade_recebimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdAtividadeSolicitacao',
        'id_atividade_solicitacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
        'IdAtividadeCancelamento',
        'id_atividade_cancelamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
        'StaArquivamento',
        'sta_arquivamento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
        'IdProtocoloDocumento',
        'd.id_protocolo',
        'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'ProtocoloFormatadoDocumento',
        'd.protocolo_formatado',
        'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'StaProtocoloProtocolo',
        'd.sta_protocolo',
        'protocolo d');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
        'IdProcedimentoDocumento',
        'id_procedimento',
        'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdSerieDocumento',
        'id_serie',
        'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NumeroDocumento',
        'numero',
        'documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeSerieDocumento',
        'nome',
        'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
        'IdProtocoloProcedimento',
        'p.id_protocolo',
        'protocolo p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'ProtocoloFormatadoProcedimento',
        'p.protocolo_formatado',
        'protocolo p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdTipoProcedimentoProcedimento',
        'id_tipo_procedimento',
        'procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeTipoProcedimento',
        'nome',
        'tipo_procedimento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
        'AberturaArquivamento',
        'a1.dth_abertura',
        'atividade a1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
        'AberturaDesarquivamento',
        'a2.dth_abertura',
        'atividade a2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
        'AberturaRecebimento',
        'a3.dth_abertura',
        'atividade a3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
        'AberturaSolicitacao',
        'a4.dth_abertura',
        'atividade a4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeArquivamento',
        'a1.id_unidade_origem',
        'atividade a1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeDesarquivamento',
        'a2.id_unidade_origem',
        'atividade a2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeRecebimento',
        'a3.id_unidade_origem',
        'atividade a3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeSolicitacao',
        'a4.id_unidade_origem',
        'atividade a4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUsuarioArquivamento',
        'a1.id_usuario_origem',
        'atividade a1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUsuarioDesarquivamento',
        'a2.id_usuario_origem',
        'atividade a2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUsuarioRecebimento',
        'a3.id_usuario_origem',
        'atividade a3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUsuarioSolicitacao',
        'a4.id_usuario_origem',
        'atividade a4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidadeArquivamento',
        'uni1.sigla',
        'unidade uni1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidadeArquivamento',
        'uni1.descricao',
        'unidade uni1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidadeDesarquivamento',
        'uni2.sigla',
        'unidade uni2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidadeDesarquivamento',
        'uni2.descricao',
        'unidade uni2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidadeRecebimento',
        'uni3.sigla',
        'unidade uni3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidadeRecebimento',
        'uni3.descricao',
        'unidade uni3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUnidadeSolicitacao',
        'uni4.sigla',
        'unidade uni4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'DescricaoUnidadeSolicitacao',
        'uni4.descricao',
        'unidade uni4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUsuarioArquivamento',
        'usu1.sigla',
        'usuario usu1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeUsuarioArquivamento',
        'usu1.nome',
        'usuario usu1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUsuarioDesarquivamento',
        'usu2.sigla',
        'usuario usu2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeUsuarioDesarquivamento',
        'usu2.nome',
        'usuario usu2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUsuarioRecebimento',
        'usu3.sigla',
        'usuario usu3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeUsuarioRecebimento',
        'usu3.nome',
        'usuario usu3');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUsuarioSolicitacao',
        'usu4.sigla',
        'usuario usu4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeUsuarioSolicitacao',
        'usu4.nome',
        'usuario usu4');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'SeqLocalizadorLocalizador',
        'seq_localizador',
        'localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidadeLocalizador',
        'id_unidade',
        'localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdTipoLocalizador',
        'id_tipo_localizador',
        'localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'StaEstadoLocalizador',
        'sta_estado',
        'localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaTipoLocalizador',
        'sigla',
        'tipo_localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'NomeTipoLocalizador',
        'nome',
        'tipo_localizador');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'DblIdArquivados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Senha');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Motivo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'CodigoAcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjLocalizadorDTO');

    $this->configurarPK('IdProtocolo',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdProtocolo', 'protocolo d', 'd.id_protocolo');
    $this->configurarFK('IdProtocoloDocumento', 'documento', 'id_documento');
    $this->configurarFK('IdProcedimentoDocumento', 'protocolo p', 'p.id_protocolo');
    $this->configurarFK('IdSerieDocumento', 'serie', 'id_serie');
    $this->configurarFK('IdProtocoloProcedimento', 'procedimento', 'id_procedimento');
    $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');

    $this->configurarFK('IdAtividadeArquivamento', 'atividade a1', 'a1.id_atividade',$this->getNumTipoFkArquivamento());
    $this->configurarFK('IdAtividadeDesarquivamento', 'atividade a2', 'a2.id_atividade',$this->getNumTipoFkDesarquivamento());
    $this->configurarFK('IdAtividadeRecebimento', 'atividade a3', 'a3.id_atividade',$this->getNumTipoFkRecebimento());
    $this->configurarFK('IdAtividadeSolicitacao', 'atividade a4', 'a4.id_atividade',$this->getNumTipoFkSolicitacao());
    $this->configurarFK('IdAtividadeCancelamento', 'atividade a5', 'a5.id_atividade',$this->getNumTipoFkCancelamento());

    $this->configurarFK('IdUnidadeArquivamento', 'unidade uni1', 'uni1.id_unidade');
    $this->configurarFK('IdUnidadeDesarquivamento', 'unidade uni2', 'uni2.id_unidade');
    $this->configurarFK('IdUnidadeRecebimento', 'unidade uni3', 'uni3.id_unidade');
    $this->configurarFK('IdUnidadeSolicitacao', 'unidade uni4', 'uni4.id_unidade');

    $this->configurarFK('IdUsuarioArquivamento', 'usuario usu1', 'usu1.id_usuario');
    $this->configurarFK('IdUsuarioDesarquivamento', 'usuario usu2', 'usu2.id_usuario');
    $this->configurarFK('IdUsuarioRecebimento', 'usuario usu3', 'usu3.id_usuario');
    $this->configurarFK('IdUsuarioSolicitacao', 'usuario usu4', 'usu4.id_usuario');

    $this->configurarFK('IdLocalizador', 'localizador', 'id_localizador',$this->getNumTipoFkLocalizador());
    $this->configurarFK('IdTipoLocalizador', 'tipo_localizador', 'id_tipo_localizador');
  }

  public function getNumTipoFkLocalizador(){
    return $this->numTipoFkLocalizador;
  }

  public function setNumTipoFkLocalizador($numTipoFkLocalizador){
    $this->numTipoFkLocalizador = $numTipoFkLocalizador;
  }

  public function getNumTipoFkArquivamento(){
    return $this->numTipoFkArquivamento;
  }

  public function setNumTipoFkArquivamento($numTipoFkArquivamento){
    $this->numTipoFkArquivamento = $numTipoFkArquivamento;
  }

  public function getNumTipoFkDesarquivamento(){
    return $this->numTipoFkDesarquivamento;
  }

  public function setNumTipoFkDesarquivamento($numTipoFkDesarquivamento){
    $this->numTipoFkDesarquivamento = $numTipoFkDesarquivamento;
  }

  public function getNumTipoFkRecebimento(){
    return $this->numTipoFkRecebimento;
  }

  public function setNumTipoFkRecebimento($numTipoFkRecebimento){
    $this->numTipoFkRecebimento = $numTipoFkRecebimento;
  }

  public function getNumTipoFkSolicitacao(){
    return $this->numTipoFkSolicitacao;
  }

  public function setNumTipoFkSolicitacao($numTipoFkSolicitacao){
    $this->numTipoFkSolicitacao = $numTipoFkSolicitacao;
  }

  public function getNumTipoFkCancelamento()
  {
    return $this->numTipoFkCancelamento;
  }

  public function setNumTipoFkCancelamento($numTipoFkCancelamento)
  {
    $this->numTipoFkCancelamento = $numTipoFkCancelamento;
  }


  
}
?>