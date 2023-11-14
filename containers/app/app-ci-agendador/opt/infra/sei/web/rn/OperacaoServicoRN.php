<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OperacaoServicoRN extends InfraRN {

  public static $TS_GERAR_PROCEDIMENTO = 0;
  public static $TS_INCLUIR_DOCUMENTO = 1;
  public static $TS_CONSULTAR_PROCEDIMENTO = 2;
  public static $TS_CONSULTAR_DOCUMENTO = 3;
  public static $TS_GERAR_BLOCO = 4;
  public static $TS_EXCLUIR_BLOCO = 5;
  public static $TS_DISPONIBILIZAR_BLOCO = 6;
  public static $TS_CANCELAR_DISPONIBILIZACAO_BLOCO = 7;
  public static $TS_INCLUIR_DOCUMENTO_BLOCO = 8;
  public static $TS_RETIRAR_DOCUMENTO_BLOCO = 9;
  public static $TS_INCLUIR_PROCEDIMENTO_BLOCO = 10;
  public static $TS_RETIRAR_PROCEDIMENTO_BLOCO = 11;
  public static $TS_REABRIR_PROCEDIMENTO = 12;
  public static $TS_CONCLUIR_PROCEDIMENTO = 13;
  public static $TS_LISTAR_EXTENSOES_PERMITIDAS = 14;
  public static $TS_ENVIAR_PROCEDIMENTO = 15;
  public static $TS_LISTAR_USUARIOS = 16;
  public static $TS_ATRIBUIR_PROCEDIMENTO = 17;
  public static $TS_CONSULTAR_BLOCO = 18;
  public static $TS_LISTAR_HIPOTESES_LEGAIS = 19;
  public static $TS_CANCELAR_DOCUMENTO = 20;
  public static $TS_LISTAR_TIPOS_CONFERENCIA = 21;
  public static $TS_ADICIONAR_ARQUIVO = 22;
  public static $TS_ADICIONAR_CONTEUDO_ARQUIVO = 23;
  public static $TS_LISTAR_CONTATOS = 24;
  public static $TS_ATUALIZAR_CONTATOS = 25;
  public static $TS_LISTAR_PAISES = 26;
  public static $TS_LISTAR_ESTADOS = 27;
  public static $TS_LISTAR_CIDADES = 28;
  public static $TS_LANCAR_ANDAMENTO = 29;
  public static $TS_LISTAR_ANDAMENTOS = 30;
  public static $TS_BLOQUEAR_PROCEDIMENTO = 31;
  public static $TS_DESBLOQUEAR_PROCEDIMENTO = 32;
  public static $TS_RELACIONAR_PROCEDIMENTO = 33;
  public static $TS_REMOVER_RELACIONAMENTO_PROCEDIMENTO = 34;
  public static $TS_LISTAR_MARCADORES_UNIDADE = 35;
  public static $TS_DEFINIR_MARCADOR = 36;
  public static $TS_LISTAR_ANDAMENTOS_MARCADORES = 37;
  public static $TS_SOBRESTAR_PROCEDIMENTO = 38;
  public static $TS_REMOVER_SOBRESTAMENTO_PROCEDIMENTO = 39;
  public static $TS_ANEXAR_PROCEDIMENTO = 40;
  public static $TS_DESANEXAR_PROCEDIMENTO = 41;
  public static $TS_LISTAR_CARGOS = 42;
  public static $TS_CONSULTAR_PROCEDIMENTO_INDIVIDUAL = 43;
  public static $TS_EXCLUIR_PROCEDIMENTO = 44;
  public static $TS_EXCLUIR_DOCUMENTO = 45;
  public static $TS_BLOQUEAR_DOCUMENTO = 46;
  public static $TS_CONSULTAR_PUBLICACAO = 47;
  public static $TS_AGENDAR_PUBLICACAO = 48;
  public static $TS_ALTERAR_PUBLICACAO = 49;
  public static $TS_CANCELAR_PUBLICACAO = 50;
  public static $TS_LISTAR_FERIADOS = 51;
  public static $TS_CONFIRMAR_DISPONIBILIZACAO_PUBLICACAO = 52;
  public static $TS_DEFINIR_CONTROLE_PRAZO = 53;
  public static $TS_REMOVER_CONTROLE_PRAZO = 54;
  public static $TS_CONCLUIR_CONTROLE_PRAZO = 55;
  public static $TS_ENVIAR_EMAIL = 56;
  public static $TS_REGISTRAR_OUVIDORIA = 57;
  public static $TS_LISTAR_TIPOS_PROCEDIMENTO_OUVIDORIA = 58;

  public static $TS_LISTAR_UNIDADES = 1000;
  public static $TS_LISTAR_TIPOS_PROCEDIMENTO = 1001;
  public static $TS_LISTAR_SERIES = 1002;


  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresOperacaoServico(){
    try {

      $arrObjTipoOperacaoServicoDTO = array();

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_GERAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Gerar Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('gerarProcedimento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_INCLUIR_DOCUMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Incluir Documento');
      $objTipoOperacaoServicoDTO->setStrOperacao('incluirDocumento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONSULTAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Consultar Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('consultarProcedimento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONSULTAR_PROCEDIMENTO_INDIVIDUAL);
      $objTipoOperacaoServicoDTO->setStrDescricao('Consultar Processo Individual');
      $objTipoOperacaoServicoDTO->setStrOperacao('consultarProcedimentoIndividual');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_EXCLUIR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Excluir Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('excluirProcedimento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONSULTAR_DOCUMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Consultar Documento');
      $objTipoOperacaoServicoDTO->setStrOperacao('consultarDocumento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_EXCLUIR_DOCUMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Excluir Documento');
      $objTipoOperacaoServicoDTO->setStrOperacao('excluirDocumento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_GERAR_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Gerar Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('gerarBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_EXCLUIR_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Excluir Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('excluirBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;
      
      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_DISPONIBILIZAR_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Disponibilizar Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('disponibilizarBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;
      
      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CANCELAR_DISPONIBILIZACAO_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Cancelar Disponibilização de Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('cancelarDisponibilizacaoBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;
      
      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_INCLUIR_DOCUMENTO_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Incluir Documento em Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('incluirDocumentoBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_RETIRAR_DOCUMENTO_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Retirar Documento de Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('retirarDocumentoBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_INCLUIR_PROCEDIMENTO_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Incluir Processo em Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('incluirProcessoBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;
      
      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_RETIRAR_PROCEDIMENTO_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Retirar Processo de Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('retirarProcessoBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_REABRIR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Reabrir Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('reabrirProcesso');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONCLUIR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Concluir Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('concluirProcesso');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_EXTENSOES_PERMITIDAS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Extensões de Arquivos Permitidas');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarExtensoesPermitidas');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ENVIAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Enviar Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('enviarProcesso');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LANCAR_ANDAMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Lançar Andamento');
      $objTipoOperacaoServicoDTO->setStrOperacao('lancarAndamento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_ANDAMENTOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Andamentos');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarAndamentos');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_USUARIOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Usuários com Permissão na Unidade');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarUsuarios');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_PAISES);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Países');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarPaises');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_ESTADOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Estados');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarEstados');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_CIDADES);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Cidades');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarCidades');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_CARGOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Cargos');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarCargos');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_CONTATOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Contatos');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarContatos');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ATUALIZAR_CONTATOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Atualizar Contatos');
      $objTipoOperacaoServicoDTO->setStrOperacao('atualizarContatos');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ATRIBUIR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Atribuir Processo na Unidade');
      $objTipoOperacaoServicoDTO->setStrOperacao('atribuirProcesso');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_BLOQUEAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Bloquear Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('bloquearProcesso');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_DESBLOQUEAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Desbloquear Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('desbloquearProcesso');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_RELACIONAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Relacionar Processo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('relacionarProcesso');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_REMOVER_RELACIONAMENTO_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Remover Relacionamento Processo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('removerRelacionamentoProcesso');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_SOBRESTAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Sobrestar Processo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('sobrestarProcesso');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_REMOVER_SOBRESTAMENTO_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Remover Sobrestamento Processo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('removerSobrestamentoProcesso');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ANEXAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Anexar Processo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('anexarProcesso');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_DESANEXAR_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Desanexar Processo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('desanexarProcesso');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONSULTAR_BLOCO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Consultar Bloco');
      $objTipoOperacaoServicoDTO->setStrOperacao('consultarBloco');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CANCELAR_DOCUMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Cancelar Documento');
      $objTipoOperacaoServicoDTO->setStrOperacao('cancelarDocumento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_BLOQUEAR_DOCUMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Bloquear Documento');
      $objTipoOperacaoServicoDTO->setStrOperacao('bloquearDocumento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_HIPOTESES_LEGAIS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Hipóteses Legais');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarHipotesesLegais');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_TIPOS_CONFERENCIA);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Tipos de Conferência');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarTiposConferencia');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ADICIONAR_ARQUIVO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Adicionar Arquivo');
      $objTipoOperacaoServicoDTO->setStrOperacao('adicionarArquivo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ADICIONAR_CONTEUDO_ARQUIVO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Adicionar Conteúdo Arquivo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $objTipoOperacaoServicoDTO->setStrOperacao('adicionarConteudoArquivo');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_MARCADORES_UNIDADE);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Marcadores da Unidade');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarMarcadoresUnidade');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_FERIADOS);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Feriados');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarFeriados');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONFIRMAR_DISPONIBILIZACAO_PUBLICACAO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Confirmar Disponibilização de Publicação');
      $objTipoOperacaoServicoDTO->setStrOperacao('confirmarDisponibilizacaoPublicacao');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;


      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_DEFINIR_MARCADOR);
      $objTipoOperacaoServicoDTO->setStrDescricao('Definir Marcador');
      $objTipoOperacaoServicoDTO->setStrOperacao('definirMarcador');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_ANDAMENTOS_MARCADORES);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Andamentos de Marcadores');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarAndamentosMarcadores');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_UNIDADES);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Unidades');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarUnidades');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('N');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_TIPOS_PROCEDIMENTO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Tipos de Processo');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarTiposProcedimento');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('N');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_SERIES);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Tipos de Documento');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarSeries');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('N');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONSULTAR_PUBLICACAO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Consultar Publicação');
      $objTipoOperacaoServicoDTO->setStrOperacao('consultarPublicacao');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_AGENDAR_PUBLICACAO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Agendar Publicação');
      $objTipoOperacaoServicoDTO->setStrOperacao('agendarPublicacao');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ALTERAR_PUBLICACAO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Alterar Publicação');
      $objTipoOperacaoServicoDTO->setStrOperacao('alterarPublicacao');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CANCELAR_PUBLICACAO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Cancelar Agendamento de Publicação');
      $objTipoOperacaoServicoDTO->setStrOperacao('cancelarAgendamentoPublicacao');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_DEFINIR_CONTROLE_PRAZO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Definir Controle de Prazos');
      $objTipoOperacaoServicoDTO->setStrOperacao('definirControlePrazo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_REMOVER_CONTROLE_PRAZO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Remover Controle de Prazos');
      $objTipoOperacaoServicoDTO->setStrOperacao('definirControlePrazo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_CONCLUIR_CONTROLE_PRAZO);
      $objTipoOperacaoServicoDTO->setStrDescricao('Concluir Controle de Prazos');
      $objTipoOperacaoServicoDTO->setStrOperacao('concluirControlePrazo');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_ENVIAR_EMAIL);
      $objTipoOperacaoServicoDTO->setStrDescricao('Enviar E-mail');
      $objTipoOperacaoServicoDTO->setStrOperacao('enviarEmail');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_REGISTRAR_OUVIDORIA);
      $objTipoOperacaoServicoDTO->setStrDescricao('Registrar Ouvidoria');
      $objTipoOperacaoServicoDTO->setStrOperacao('registrarOuvidoria');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      $objTipoOperacaoServicoDTO = new TipoOperacaoServicoDTO();
      $objTipoOperacaoServicoDTO->setNumStaOperacaoServico(self::$TS_LISTAR_TIPOS_PROCEDIMENTO_OUVIDORIA);
      $objTipoOperacaoServicoDTO->setStrDescricao('Listar Tipos de Processo da Ouvidoria');
      $objTipoOperacaoServicoDTO->setStrOperacao('listarTipoProcedimentoOuvidoria');
      $objTipoOperacaoServicoDTO->setStrSinConfiguravel('S');
      $arrObjTipoOperacaoServicoDTO[] = $objTipoOperacaoServicoDTO;

      InfraArray::ordenarArrInfraDTO($arrObjTipoOperacaoServicoDTO,'Descricao',InfraArray::$TIPO_ORDENACAO_ASC);

      return $arrObjTipoOperacaoServicoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Operações.',$e);
    }
  }

  public function listarValoresOperacaoServicoConfiguraveis(){
    try {
      $arrObjTipoOperacaoServicoDTO = $this->listarValoresOperacaoServico();
      $arr = array();
      foreach($arrObjTipoOperacaoServicoDTO as $objTipoOperacaoServicoDTO){
        if ($objTipoOperacaoServicoDTO->getStrSinConfiguravel()=='S'){
          $arr[] = $objTipoOperacaoServicoDTO;
        }
      }
      return $arr;
    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Operações configuráveis.',$e);
    }
  }

  private function validarNumIdServico(OperacaoServicoDTO $objOperacaoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOperacaoServicoDTO->getNumIdServico())){
      $objInfraException->adicionarValidacao('Serviço não informado.');
    }
  }

  private function validarNumStaOperacaoServico(OperacaoServicoDTO $objOperacaoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOperacaoServicoDTO->getNumStaOperacaoServico())){
      $objInfraException->adicionarValidacao('Tipo da Operação do Serviço não informado.');
    }else{
      if (!in_array($objOperacaoServicoDTO->getNumStaOperacaoServico(),InfraArray::converterArrInfraDTO($this->listarValoresOperacaoServico(),'StaOperacaoServico'))){
        $objInfraException->adicionarValidacao('Tipo da Operação do Serviço inválido.');
      }
    }
  }

  private function validarNumIdTipoProcedimento(OperacaoServicoDTO $objOperacaoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOperacaoServicoDTO->getNumIdTipoProcedimento())){
      $objOperacaoServicoDTO->setNumIdTipoProcedimento(null);
    }
  }

  private function validarNumIdSerie(OperacaoServicoDTO $objOperacaoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOperacaoServicoDTO->getNumIdSerie())){
      $objOperacaoServicoDTO->setNumIdSerie(null);
    }
  }

  private function validarNumIdUnidade(OperacaoServicoDTO $objOperacaoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOperacaoServicoDTO->getNumIdUnidade())){
      $objOperacaoServicoDTO->setNumIdUnidade(null);
    }
  }
  
  protected function cadastrarControlado(OperacaoServicoDTO $objOperacaoServicoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_cadastrar',__METHOD__,$objOperacaoServicoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdServico($objOperacaoServicoDTO, $objInfraException);
      $this->validarNumStaOperacaoServico($objOperacaoServicoDTO, $objInfraException);
      $this->validarNumIdTipoProcedimento($objOperacaoServicoDTO, $objInfraException);
      $this->validarNumIdSerie($objOperacaoServicoDTO, $objInfraException);
      $this->validarNumIdUnidade($objOperacaoServicoDTO, $objInfraException);
      
      $dto = new OperacaoServicoDTO();
      $dto->setNumStaOperacaoServico($objOperacaoServicoDTO->getNumStaOperacaoServico());
      $dto->setNumIdTipoProcedimento($objOperacaoServicoDTO->getNumIdTipoProcedimento());
      $dto->setNumIdSerie($objOperacaoServicoDTO->getNumIdSerie());
      $dto->setNumIdUnidade($objOperacaoServicoDTO->getNumIdUnidade());
      $dto->setNumIdServico($objOperacaoServicoDTO->getNumIdServico());
      
      if ($this->contar($dto)){
      	$objInfraException->adicionarValidacao('Já existe uma operação cadastrada com estas características.');
      }
      
      $objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      $ret = $objOperacaoServicoBD->cadastrar($objOperacaoServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Operação do Serviço.',$e);
    }
  }

  protected function alterarControlado(OperacaoServicoDTO $objOperacaoServicoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_alterar',__METHOD__,$objOperacaoServicoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $dto = new OperacaoServicoDTO();
      $dto->retNumStaOperacaoServico();
      $dto->retNumIdTipoProcedimento();
      $dto->retNumIdSerie();
      $dto->retNumIdUnidade();
      $dto->setNumIdOperacaoServico($objOperacaoServicoDTO->getNumIdOperacaoServico());
      $dto = $this->consultar($dto);
      
      if ($dto==null){
      	throw new InfraException('Operação do Serviço não encontrada para alteração.');
      }

      if ($objOperacaoServicoDTO->isSetNumIdServico()){
        $this->validarNumIdServico($objOperacaoServicoDTO, $objInfraException);
      }
      
      if ($objOperacaoServicoDTO->isSetNumStaOperacaoServico()){
        $this->validarNumStaOperacaoServico($objOperacaoServicoDTO, $objInfraException);
        $numStaOperacaoServico = $objOperacaoServicoDTO->getNumStaOperacaoServico();
      }else{
      	$numStaOperacaoServico = $dto->getNumStaOperacaoServico();
      }
      
      if ($objOperacaoServicoDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objOperacaoServicoDTO, $objInfraException);
        $numIdTipoProcedimento = $objOperacaoServicoDTO->getNumIdTipoProcedimento();
      }else{
      	$numIdTipoProcedimento = $dto->getNumIdTipoProcedimento();
      }
      
      if ($objOperacaoServicoDTO->isSetNumIdSerie()){
        $this->validarNumIdSerie($objOperacaoServicoDTO, $objInfraException);
        $numIdSerie = $objOperacaoServicoDTO->getNumIdSerie();
      }else{
      	$numIdSerie = $dto->getNumIdSerie();
      }
      
      if ($objOperacaoServicoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objOperacaoServicoDTO, $objInfraException);
        $numIdUnidade = $objOperacaoServicoDTO->getNumIdUnidade();
      }else{
      	$numIdUnidade = $dto->getNumIdUnidade();
      }
      
      $dto = new OperacaoServicoDTO();
      $dto->setNumStaOperacaoServico($numStaOperacaoServico);
      $dto->setNumIdTipoProcedimento($numIdTipoProcedimento);
      $dto->setNumIdSerie($numIdSerie);
      $dto->setNumIdUnidade($numIdUnidade);
      $dto->setNumIdServico($objOperacaoServicoDTO->getNumIdServico());
      $dto->setNumIdOperacaoServico($objOperacaoServicoDTO->getNumIdOperacaoServico(),InfraDTO::$OPER_DIFERENTE);
      
      //campos iguais e nao alterou o sinalizador de link externo
      if ($this->contar($dto)){
      	$objInfraException->adicionarValidacao('Já existe uma operação do serviço cadastrada com estas características.');
      }
      
      $objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      $objOperacaoServicoBD->alterar($objOperacaoServicoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Operação do Serviço.',$e);
    }
  }

  protected function excluirControlado($arrObjOperacaoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_excluir',__METHOD__,$arrObjOperacaoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOperacaoServicoDTO);$i++){
        $objOperacaoServicoBD->excluir($arrObjOperacaoServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Operação do Serviço.',$e);
    }
  }

  protected function consultarConectado(OperacaoServicoDTO $objOperacaoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_consultar',__METHOD__,$objOperacaoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      $ret = $objOperacaoServicoBD->consultar($objOperacaoServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Operação do Serviço.',$e);
    }
  }

  protected function listarConectado(OperacaoServicoDTO $objOperacaoServicoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_listar',__METHOD__,$objOperacaoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      $ret = $objOperacaoServicoBD->listar($objOperacaoServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Operações.',$e);
    }
  }

  protected function contarConectado(OperacaoServicoDTO $objOperacaoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_listar',__METHOD__,$objOperacaoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      $ret = $objOperacaoServicoBD->contar($objOperacaoServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Operações.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjOperacaoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOperacaoServicoDTO);$i++){
        $objOperacaoServicoBD->desativar($arrObjOperacaoServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Operação do Serviço.',$e);
    }
  }

  protected function reativarControlado($arrObjOperacaoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOperacaoServicoDTO);$i++){
        $objOperacaoServicoBD->reativar($arrObjOperacaoServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Operação do Serviço.',$e);
    }
  }

  protected function bloquearControlado(OperacaoServicoDTO $objOperacaoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('operacao_servico_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOperacaoServicoBD = new OperacaoServicoBD($this->getObjInfraIBanco());
      $ret = $objOperacaoServicoBD->bloquear($objOperacaoServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Operação do Serviço.',$e);
    }
  }

 */
}
?>