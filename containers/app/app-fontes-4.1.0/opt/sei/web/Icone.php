<?

require_once dirname(__FILE__) . '/SEI.php';


abstract class Icone {
  public const VERSAO = '18';

  public const ACESSO_EXTERNO = DIR_SEI_SVG . '/acesso_externo.svg?' . self::VERSAO;
  public const ACESSO_EXTERNO_INTEGRAL = DIR_SEI_SVG . '/acesso_externo_integral.svg?' . self::VERSAO;
  public const ACESSO_EXTERNO_PARCIAL = DIR_SEI_SVG . '/acesso_externo_parcial.svg?' . self::VERSAO;
  public const ACESSO_EXTERNO_INCLUSAO = DIR_SEI_SVG . '/acesso_externo_inclusao.svg?' . self::VERSAO;

  public const ACESSO_EXTERNO_VISUALIZACAO = DIR_SEI_SVG . '/acesso_externo_visualizacao.svg?' . self::VERSAO;

  public const ACOMPANHAMENTO_ESPECIAL = DIR_SEI_SVG . '/acompanhamento_especial.svg?' . self::VERSAO;
  public const ACOMPANHAMENTO_ESPECIAL_CADASTRO = DIR_SEI_SVG . '/acompanhamento_especial_cadastro.svg?' . self::VERSAO;
  public const ACOMPANHAMENTO_ESPECIAL_INEXISTENTE = DIR_SEI_SVG . '/acompanhamento_especial_inexistente.svg?' . self::VERSAO;

  public const ANDAMENTO_PLANO_TRABALHO_DESCONSIDERADO = DIR_SEI_SVG . '/andamento_plano_trabalho_desconsiderado.svg?' . self::VERSAO;
  public const ANDAMENTO_PLANO_TRABALHO_EM_ANDAMENTO = DIR_SEI_SVG . '/andamento_plano_trabalho_em_andamento.svg?' . self::VERSAO;
  public const ANDAMENTO_PLANO_TRABALHO_FINALIZADO = DIR_SEI_SVG . '/andamento_plano_trabalho_finalizado.svg?' . self::VERSAO;
  public const ANDAMENTO_PLANO_TRABALHO_NAO_SE_APLICA = DIR_SEI_SVG . '/andamento_plano_trabalho_nao_se_aplica.svg?' . self::VERSAO;
  public const ANDAMENTO_PLANO_TRABALHO_PAUSADO = DIR_SEI_SVG . '/andamento_plano_trabalho_pausado.svg?' . self::VERSAO;
  public const ANDAMENTO_PLANO_TRABALHO_PROBLEMA = DIR_SEI_SVG . '/andamento_plano_trabalho_problema.svg?' . self::VERSAO;

  public const ANOTACAO1 = DIR_SEI_SVG . '/anotacao1.svg?' . self::VERSAO;
  public const ANOTACAO2 = DIR_SEI_SVG . '/anotacao2.svg?' . self::VERSAO;
  public const ANOTACAO_CADASTRO = DIR_SEI_SVG . '/anotacao_cadastro.svg?' . self::VERSAO;

  public const ARQUIVO = DIR_SEI_SVG . '/arquivo.svg?' . self::VERSAO;
  public const ARQUIVO_ATIVAR_TABELA = DIR_SEI_SVG . '/arquivo_ativar_tabela.svg?' . self::VERSAO;
  public const ARQUIVO_CANCELAR_RECEBIMENTO = DIR_SEI_SVG . '/arquivo_cancelar_recebimento.svg?' . self::VERSAO;
  public const ARQUIVO_COMISSAO = DIR_SEI_SVG . '/arquivo_comissao.svg?' . self::VERSAO;
  public const ARQUIVO_DESARQUIVAR = DIR_SEI_SVG . '/arquivo_desarquivar.svg?' . self::VERSAO;
  public const ARQUIVO_EDITAL_ELIMINAR = DIR_SEI_SVG . '/arquivo_edital_eliminar.svg?' . self::VERSAO;
  public const ARQUIVO_ELIMINADO = DIR_SEI_SVG . '/arquivo_eliminado.svg?' . self::VERSAO;
  public const ARQUIVO_GRAFICO = DIR_SEI_SVG . '/arquivo_grafico.svg?' . self::VERSAO;
  public const ARQUIVO_MAPEAMENTO_ASSUNTO = DIR_SEI_SVG . '/arquivo_mapeamento_assunto.svg?' . self::VERSAO;
  public const ARQUIVO_MIGRAR_LOCALIZADOR = DIR_SEI_SVG . '/arquivo_migrar_localizador.svg?' . self::VERSAO;
  public const ARQUIVO_OUTRA_UNIDADE = DIR_SEI_SVG . '/arquivo_outra_unidade.svg?' . self::VERSAO;
  public const ARQUIVO_PESQUISAR = DIR_SEI_SVG . '/arquivo_pesquisar.svg?' . self::VERSAO;
  public const ARQUIVO_PROTOCOLO_ELIMINADO = DIR_SEI_SVG . '/arquivo_protocolo_eliminado.svg?' . self::VERSAO;
  public const ARQUIVO_RECEBER = DIR_SEI_SVG . '/arquivo_receber.svg?' . self::VERSAO;

  public const ARVORE = DIR_SEI_SVG . '/arvore.svg?' . self::VERSAO;
  public const ARVORE_ABRIR = DIR_SEI_SVG . '/arvore_abrir.svg?' . self::VERSAO;
  public const ARVORE_FECHAR = DIR_SEI_SVG . '/arvore_fechar.svg?' . self::VERSAO;

  public const ASSINATURA1 = DIR_SEI_SVG . '/assinatura1.svg?' . self::VERSAO;
  public const ASSINATURA2 = DIR_SEI_SVG . '/assinatura2.svg?' . self::VERSAO;
  public const AUTENTICACAO1 = DIR_SEI_SVG . '/autenticacao1.svg?' . self::VERSAO;
  public const AUTENTICACAO2 = DIR_SEI_SVG . '/autenticacao2.svg?' . self::VERSAO;

  public const AVALIACAO_DOCUMENTAL = DIR_SEI_SVG . '/avaliacao_documental.svg?' . self::VERSAO;
  public const AVALIACAO_ELIMINADO = DIR_SEI_SVG . '/avaliacao_eliminado.svg?' . self::VERSAO;
  public const AVALIACAO_ELIMINAR = DIR_SEI_SVG . '/avaliacao_eliminar.svg?' . self::VERSAO;
  public const AVALIACAO_GERAR_EDITAL = DIR_SEI_SVG . '/avaliacao_gerar_edital.svg?' . self::VERSAO;
  public const AVALIACAO_GERAR_LISTAGEM = DIR_SEI_SVG . '/avaliacao_gerar_listagem.svg?' . self::VERSAO;
  public const AVALIACAO_PROCESSOS = DIR_SEI_SVG . '/avaliacao_processos.svg?' . self::VERSAO;
  public const AVALIACAO_VERSOES_COMISSOES = DIR_SEI_SVG . '/avaliacao_versoes_comissoes.svg?' . self::VERSAO;

  public const BALAO = DIR_SEI_SVG . '/balao.svg?' . self::VERSAO;

  public const BASE_CONHECIMENTO = DIR_SEI_SVG . '/base_conhecimento.svg?' . self::VERSAO;
  public const BASE_CONHECIMENTO_VERSOES = DIR_SEI_SVG . '/base_conhecimento_versoes.svg?' . self::VERSAO;

  public const BLOCO_AGUARDANDO_DEVOLUCAO = DIR_SEI_SVG . '/bloco_aguardando_devolucao.svg?' . self::VERSAO;
  public const BLOCO_ANOTACAO = DIR_SEI_SVG . '/bloco_anotacao.svg?' . self::VERSAO;
  public const BLOCO_CANCELAR_DISPONIBILIZACAO = DIR_SEI_SVG . '/bloco_cancelar_disponibilizacao.svg?' . self::VERSAO;
  public const BLOCO_COMENTARIO1 = DIR_SEI_SVG . '/bloco_comentario1.svg?' . self::VERSAO;
  public const BLOCO_COMENTARIO2 = DIR_SEI_SVG . '/bloco_comentario2.svg?' . self::VERSAO;
  public const BLOCO_CONCLUIR = DIR_SEI_SVG . '/bloco_concluir.svg?' . self::VERSAO;
  public const BLOCO_CONSULTAR_PROTOCOLOS = DIR_SEI_SVG . '/bloco_consultar_protocolos.svg?' . self::VERSAO;
  public const BLOCO_DEVOLVER = DIR_SEI_SVG . '/bloco_devolver.svg?' . self::VERSAO;
  public const BLOCO_DEVOLVIDO = DIR_SEI_SVG . '/bloco_devolvido.svg?' . self::VERSAO;
  public const BLOCO_DISPONIBILIZAR = DIR_SEI_SVG . '/bloco_disponibilizar.svg?' . self::VERSAO;
  public const BLOCO_INCLUIR_PROTOCOLO = DIR_SEI_SVG . '/bloco_incluir_protocolo.svg?' . self::VERSAO;
  public const BLOCO_NAVEGAR_SETA_DIREITA = DIR_SEI_SVG . '/bloco_navegar_seta_direita.svg?' . self::VERSAO;
  public const BLOCO_NAVEGAR_SETA_ESQUERDA = DIR_SEI_SVG . '/bloco_navegar_seta_esquerda.svg?' . self::VERSAO;
  public const BLOCO_PRIORIDADE1 = DIR_SEI_SVG . '/bloco_prioridade1.svg?' . self::VERSAO;
  public const BLOCO_PRIORIDADE2 = DIR_SEI_SVG . '/bloco_prioridade2.svg?' . self::VERSAO;
  public const BLOCO_REABRIR = DIR_SEI_SVG . '/bloco_reabrir.svg?' . self::VERSAO;
  public const BLOCO_REVISAO1 = DIR_SEI_SVG . '/bloco_revisao1.svg?' . self::VERSAO;
  public const BLOCO_REVISAO2 = DIR_SEI_SVG . '/bloco_revisao2.svg?' . self::VERSAO;
  public const BLOCO_USUARIO = DIR_SEI_SVG . '/bloco_usuario.svg?' . self::VERSAO;

  public const CIENCIA = DIR_SEI_SVG . '/ciencia.svg?' . self::VERSAO;

  public const COMENTARIO = DIR_SEI_SVG . '/comentario.svg?' . self::VERSAO;

  public const CONTATO_ALTERAR = DIR_SEI_SVG . '/contato_alterar.svg?' . self::VERSAO;

  public const CONTROLE_PRAZO1 = DIR_SEI_SVG . '/controle_prazo1.svg?' . self::VERSAO;
  public const CONTROLE_PRAZO2 = DIR_SEI_SVG . '/controle_prazo2.svg?' . self::VERSAO;
  public const CONTROLE_PRAZO3 = DIR_SEI_SVG . '/controle_prazo3.svg?' . self::VERSAO;
  public const CONTROLE_PRAZO_GERENCIAR = DIR_SEI_SVG . '/controle_prazo_gerenciar.svg?' . self::VERSAO;
  public const CONTROLE_PRAZO_TABELA = DIR_SEI_SVG . '/controle_prazo_tabela.svg?' . self::VERSAO;

  public const CONTROLE_PROCESSOS = DIR_SEI_SVG . '/controle_processos.svg?' . self::VERSAO;

  public const CREDENCIAL_ASSINATURA = DIR_SEI_SVG . '/credencial_assinatura.svg?' . self::VERSAO;
  public const CREDENCIAL_ATIVAR = DIR_SEI_SVG . '/credencial_ativar.svg?' . self::VERSAO;
  public const CREDENCIAL_CANCELAR = DIR_SEI_SVG . '/credencial_cancelar.svg?' . self::VERSAO;
  public const CREDENCIAL_CASSAR = DIR_SEI_SVG . '/credencial_cassar.svg?' . self::VERSAO;
  public const CREDENCIAL_CONCESSAO_ASSINATURA = DIR_SEI_SVG . '/credencial_concessao_assinatura.svg?' . self::VERSAO;
  public const CREDENCIAL_CONSULTAR = DIR_SEI_SVG . '/credencial_consultar.svg?' . self::VERSAO;
  public const CREDENCIAL_GERENCIAR = DIR_SEI_SVG . '/credencial_gerenciar.svg?' . self::VERSAO;
  public const CREDENCIAL_RENOVAR = DIR_SEI_SVG . '/credencial_renovar.svg?' . self::VERSAO;
  public const CREDENCIAL_RENUNCIAR = DIR_SEI_SVG . '/credencial_renunciar.svg?' . self::VERSAO;


  public const DOCUMENTO_ALTERAR = DIR_SEI_SVG . '/documento_alterar.svg?' . self::VERSAO;
  public const DOCUMENTO_APLICATIVO = DIR_SEI_SVG . '/documento_aplicativo.svg?' . self::VERSAO;
  public const DOCUMENTO_ASSINAR = DIR_SEI_SVG . '/documento_assinar.svg?' . self::VERSAO;
  public const DOCUMENTO_ASSINATURA_EXTERNA = DIR_SEI_SVG . '/documento_assinatura_externa.svg?' . self::VERSAO;
  public const DOCUMENTO_ASSINATURAS_CONSULTAR = DIR_SEI_SVG . '/documento_assinaturas_consultar.svg?' . self::VERSAO;
  public const DOCUMENTO_AUDIO = DIR_SEI_SVG . '/documento_audio.svg?' . self::VERSAO;
  public const DOCUMENTO_AUTENTICAR = DIR_SEI_SVG . '/documento_autenticar.svg?' . self::VERSAO;
  public const DOCUMENTO_BASE_CONHECIMENTO = DIR_SEI_SVG . '/documento_base_conhecimento.svg?' . self::VERSAO;
  public const DOCUMENTO_CANCELADO = DIR_SEI_SVG . '/documento_cancelado.svg?' . self::VERSAO;
  public const DOCUMENTO_CANCELAR = DIR_SEI_SVG . '/documento_cancelar.svg?' . self::VERSAO;
  public const DOCUMENTO_CIRCULAR = DIR_SEI_SVG . '/documento_circular.svg?' . self::VERSAO;
  public const DOCUMENTO_EDITAR_CONTEUDO = DIR_SEI_SVG . '/documento_editar_conteudo.svg?' . self::VERSAO;
  public const DOCUMENTO_EMAIL = DIR_SEI_SVG . '/documento_email.svg?' . self::VERSAO;
  public const DOCUMENTO_EMAIL_CCO = DIR_SEI_SVG . '/documento_email_cco.svg?' . self::VERSAO;
  public const DOCUMENTO_EXCEL = DIR_SEI_SVG . '/documento_excel.svg?' . self::VERSAO;
  public const DOCUMENTO_FORMULARIO1 = DIR_SEI_SVG . '/documento_formulario1.svg?' . self::VERSAO;
  public const DOCUMENTO_FORMULARIO2 = DIR_SEI_SVG . '/documento_formulario2.svg?' . self::VERSAO;
  public const DOCUMENTO_GERAR_PDF = DIR_SEI_SVG . '/documento_gerar_pdf.svg?' . self::VERSAO;
  public const DOCUMENTO_HTML = DIR_SEI_SVG . '/documento_html.svg?' . self::VERSAO;
  public const DOCUMENTO_IMAGEM = DIR_SEI_SVG . '/documento_imagem.svg?' . self::VERSAO;
  public const DOCUMENTO_IMPRIMIR = DIR_SEI_SVG . '/documento_imprimir.svg?' . self::VERSAO;
  public const DOCUMENTO_INCLUIR = DIR_SEI_SVG . '/documento_incluir.svg?' . self::VERSAO;
  public const DOCUMENTO_INTERNO = DIR_SEI_SVG . '/documento_interno.svg?' . self::VERSAO;
  public const DOCUMENTO_MODELO = DIR_SEI_SVG . '/documento_modelo.svg?' . self::VERSAO;
  public const DOCUMENTO_MOVER = DIR_SEI_SVG . '/documento_mover.svg?' . self::VERSAO;
  public const DOCUMENTO_MOVIDO = DIR_SEI_SVG . '/documento_movido.svg?' . self::VERSAO;
  public const DOCUMENTO_NAO_IDENTIFICADO = DIR_SEI_SVG . '/documento_nao_identificado.svg?' . self::VERSAO;
  public const DOCUMENTO_ODG = DIR_SEI_SVG . '/documento_odg.svg?' . self::VERSAO;
  public const DOCUMENTO_ODP = DIR_SEI_SVG . '/documento_odp.svg?' . self::VERSAO;
  public const DOCUMENTO_ODS = DIR_SEI_SVG . '/documento_ods.svg?' . self::VERSAO;
  public const DOCUMENTO_ODT = DIR_SEI_SVG . '/documento_odt.svg?' . self::VERSAO;
  public const DOCUMENTO_PDF = DIR_SEI_SVG . '/documento_pdf.svg?' . self::VERSAO;
  public const DOCUMENTO_POWERPOINT = DIR_SEI_SVG . '/documento_powerpoint.svg?' . self::VERSAO;
  public const DOCUMENTO_RAR = DIR_SEI_SVG . '/documento_rar.svg?' . self::VERSAO;
  public const DOCUMENTO_SEM_CONTEUDO = DIR_SEI_SVG . '/documento_sem_conteudo.svg?' . self::VERSAO;
  public const DOCUMENTO_RECUPERAR_VERSAO = DIR_SEI_SVG . '/documento_recuperar_versao.svg?' . self::VERSAO;
  public const DOCUMENTO_TXT = DIR_SEI_SVG . '/documento_txt.svg?' . self::VERSAO;
  public const DOCUMENTO_VERSOES = DIR_SEI_SVG . '/documento_versoes.svg?' . self::VERSAO;
  public const DOCUMENTO_VIDEO = DIR_SEI_SVG . '/documento_video.svg?' . self::VERSAO;
  public const DOCUMENTO_WORD = DIR_SEI_SVG . '/documento_word.svg?' . self::VERSAO;
  public const DOCUMENTO_ZIP = DIR_SEI_SVG . '/documento_zip.svg?' . self::VERSAO;

  public const EMAIL_ENCAMINHAR = DIR_SEI_SVG . '/email_encaminhar.svg?' . self::VERSAO;
  public const EMAIL_ENVIAR = DIR_SEI_SVG . '/email_enviar.svg?' . self::VERSAO;
  public const EMAIL_RESPONDER = DIR_SEI_SVG . '/email_responder.svg?' . self::VERSAO;

  public const EXCLAMACAO = DIR_SEI_SVG . '/exclamacao.svg?' . self::VERSAO;

  public const FEDERACAO = DIR_SEI_SVG . '/federacao.svg?' . self::VERSAO;
  public const FEDERACAO_ACESSO_CANCELAMENTO = DIR_SEI_SVG . '/federacao_acesso_cancelamento.svg?' . self::VERSAO;
  public const FEDERACAO_ACESSO_LIBERACAO = DIR_SEI_SVG . '/federacao_acesso_liberacao.svg?' . self::VERSAO;
  public const FEDERACAO_BLOQUEAR = DIR_SEI_SVG . '/federacao_bloquear.svg?' . self::VERSAO;
  public const FEDERACAO_GERENCIAR = DIR_SEI_SVG . '/federacao_gerenciar.svg?' . self::VERSAO;
  public const FEDERACAO_INSTALACAO = DIR_SEI_SVG . '/federacao_instalacao.svg?' . self::VERSAO;
  public const FEDERACAO_LIBERAR = DIR_SEI_SVG . '/federacao_liberar.svg?' . self::VERSAO;
  public const FEDERACAO_LINK = DIR_SEI_SVG . '/federacao_link.svg?' . self::VERSAO;
  public const FEDERACAO_MOTIVO_CANCELAMENTO = DIR_SEI_SVG . '/federacao_motivo_cancelamento.svg?' . self::VERSAO;
  public const FEDERACAO_MOTIVO_LIBERACAO = DIR_SEI_SVG . '/federacao_motivo_liberacao.svg?' . self::VERSAO;
  public const FEDERACAO_ORIGEM = DIR_SEI_SVG . '/federacao_origem.svg?' . self::VERSAO;
  public const FEDERACAO_SOLICITAR_REGISTRO = DIR_SEI_SVG . '/federacao_solicitar_registro.svg?' . self::VERSAO;

  public const HISTORICO = DIR_SEI_SVG . '/historico.svg?' . self::VERSAO;

  public const ITEM_ETAPA_INCLUIR_DOCUMENTO = DIR_SEI_SVG . '/item_etapa_incluir_documento.svg?' . self::VERSAO;

  public const LINHA_DIRETA1 = DIR_SEI_SVG . '/linha_direta1.svg?' . self::VERSAO;
  public const LINHA_DIRETA2 = DIR_SEI_SVG . '/linha_direta2.svg?' . self::VERSAO;

  public const MARCADOR_ADICIONAR = DIR_SEI_SVG . '/marcador_adicionar.svg?' . self::VERSAO;
  public const MARCADOR_GERENCIAR = DIR_SEI_SVG . '/marcador_gerenciar.svg?' . self::VERSAO;
  public const MARCADOR_REMOVER = DIR_SEI_SVG . '/marcador_remover.svg?' . self::VERSAO;


  public const MARCADOR_ANOTACAO = DIR_SEI_SVG . '/marcador_anotacao.svg?' . self::VERSAO;

  public const MARCADOR_AMARELO = DIR_SEI_SVG . '/marcador_amarelo.svg?' . self::VERSAO;
  public const MARCADOR_AMARELO_CLARO = DIR_SEI_SVG . '/marcador_amarelo_claro.svg?' . self::VERSAO;
  public const MARCADOR_AMARELO_OURO = DIR_SEI_SVG . '/marcador_amarelo_ouro.svg?' . self::VERSAO;
  public const MARCADOR_AZUL = DIR_SEI_SVG . '/marcador_azul.svg?' . self::VERSAO;
  public const MARCADOR_AZUL_CEU = DIR_SEI_SVG . '/marcador_azul_ceu.svg?' . self::VERSAO;
  public const MARCADOR_AZUL_MARINHO = DIR_SEI_SVG . '/marcador_azul_marinho.svg?' . self::VERSAO;
  public const MARCADOR_AZUL_RIVIERA = DIR_SEI_SVG . '/marcador_azul_riviera.svg?' . self::VERSAO;
  public const MARCADOR_BEGE = DIR_SEI_SVG . '/marcador_bege.svg?' . self::VERSAO;
  public const MARCADOR_BRANCO = DIR_SEI_SVG . '/marcador_branco.svg?' . self::VERSAO;
  public const MARCADOR_BRONZE = DIR_SEI_SVG . '/marcador_bronze.svg?' . self::VERSAO;
  public const MARCADOR_CHAMPAGNE = DIR_SEI_SVG . '/marcador_champagne.svg?' . self::VERSAO;
  public const MARCADOR_CIANO = DIR_SEI_SVG . '/marcador_ciano.svg?' . self::VERSAO;
  public const MARCADOR_CINZA = DIR_SEI_SVG . '/marcador_cinza.svg?' . self::VERSAO;
  public const MARCADOR_CINZA_ESCURO = DIR_SEI_SVG . '/marcador_cinza_escuro.svg?' . self::VERSAO;
  public const MARCADOR_LARANJA = DIR_SEI_SVG . '/marcador_laranja.svg?' . self::VERSAO;
  public const MARCADOR_LILAS = DIR_SEI_SVG . '/marcador_lilas.svg?' . self::VERSAO;
  public const MARCADOR_MARROM = DIR_SEI_SVG . '/marcador_marrom.svg?' . self::VERSAO;
  public const MARCADOR_OURO = DIR_SEI_SVG . '/marcador_ouro.svg?' . self::VERSAO;
  public const MARCADOR_PRATA = DIR_SEI_SVG . '/marcador_prata.svg?' . self::VERSAO;
  public const MARCADOR_PRETO = DIR_SEI_SVG . '/marcador_preto.svg?' . self::VERSAO;
  public const MARCADOR_ROSA = DIR_SEI_SVG . '/marcador_rosa.svg?' . self::VERSAO;
  public const MARCADOR_ROSA_CLARO = DIR_SEI_SVG . '/marcador_rosa_claro.svg?' . self::VERSAO;
  public const MARCADOR_ROXO = DIR_SEI_SVG . '/marcador_roxo.svg?' . self::VERSAO;
  public const MARCADOR_TIJOLO = DIR_SEI_SVG . '/marcador_tijolo.svg?' . self::VERSAO;
  public const MARCADOR_VERDE = DIR_SEI_SVG . '/marcador_verde.svg?' . self::VERSAO;
  public const MARCADOR_VERDE_ABACATE = DIR_SEI_SVG . '/marcador_verde_abacate.svg?' . self::VERSAO;
  public const MARCADOR_VERDE_AGUA = DIR_SEI_SVG . '/marcador_verde_agua.svg?' . self::VERSAO;
  public const MARCADOR_VERDE_AMAZONAS = DIR_SEI_SVG . '/marcador_verde_amazonas.svg?' . self::VERSAO;
  public const MARCADOR_VERDE_ESCURO = DIR_SEI_SVG . '/marcador_verde_escuro.svg?' . self::VERSAO;
  public const MARCADOR_VERDE_TURQUESA = DIR_SEI_SVG . '/marcador_verde_turquesa.svg?' . self::VERSAO;
  public const MARCADOR_VERMELHO = DIR_SEI_SVG . '/marcador_vermelho.svg?' . self::VERSAO;
  public const MARCADOR_VINHO = DIR_SEI_SVG . '/marcador_vinho.svg?' . self::VERSAO;

  public const MODULO_ACESSO_CONCEDIDO = DIR_SEI_SVG . '/modulo_acesso_concedido.svg?' . self::VERSAO;
  public const MODULO_ACESSO_NEGADO = DIR_SEI_SVG . '/modulo_acesso_negado.svg?' . self::VERSAO;


  public const NOVIDADE_LIBERAR = DIR_SEI_SVG . '/novidade_liberar.svg?' . self::VERSAO;

  public const ORGANOGRAMA = DIR_SEI_SVG . '/organograma.svg?' . self::VERSAO;

  public const OUVIDORIA_ACESSO_RESTRITO = DIR_SEI_SVG . '/ouvidoria_acesso_restrito.svg?' . self::VERSAO;
  public const OUVIDORIA_FINALIZAR = DIR_SEI_SVG . '/ouvidoria_finalizar.svg?' . self::VERSAO;
  public const OUVIDORIA_REENCAMINHAR = DIR_SEI_SVG . '/ouvidoria_reencaminhar.svg?' . self::VERSAO;
  public const OUVIDORIA_SOLICITACAO_ATENDIDA = DIR_SEI_SVG . '/ouvidoria_solicitacao_atendida.svg?' . self::VERSAO;
  public const OUVIDORIA_SOLICITACAO_NAO_ATENDIDA = DIR_SEI_SVG . '/ouvidoria_solicitacao_nao_atendida.svg?' . self::VERSAO;

  public const PLANO_TRABALHO = DIR_SEI_SVG . '/plano_trabalho.svg?' . self::VERSAO;
  public const PLANO_TRABALHO_CONFIGURAR = DIR_SEI_SVG . '/plano_trabalho_configurar.svg?' . self::VERSAO;

  public const PRE_VISUALIZAR = DIR_SEI_SVG . '/pre_visualizar.svg?' . self::VERSAO;

  public const PROCESSO = DIR_SEI_SVG . '/processo.svg?' . self::VERSAO;
  public const PROCESSO_ABERTO = DIR_SEI_SVG . '/processo_aberto.svg?' . self::VERSAO;
  public const PROCESSO_ALTERAR = DIR_SEI_SVG . '/processo_alterar.svg?' . self::VERSAO;
  public const PROCESSO_ANDAMENTOS = DIR_SEI_SVG . '/processo_andamentos.svg?' . self::VERSAO;
  public const PROCESSO_ANEXADO = DIR_SEI_SVG . '/processo_anexado.svg?' . self::VERSAO;
  public const PROCESSO_ANEXAR = DIR_SEI_SVG . '/processo_anexar.svg?' . self::VERSAO;
  public const PROCESSO_ATRIBUIR = DIR_SEI_SVG . '/processo_atribuir.svg?' . self::VERSAO;
  public const PROCESSO_ATUALIZAR_ANDAMENTO = DIR_SEI_SVG . '/processo_atualizar_andamento.svg?' . self::VERSAO;
  public const PROCESSO_BLOQUEADO = DIR_SEI_SVG . '/processo_bloqueado.svg?' . self::VERSAO;
  public const PROCESSO_CONCLUIR = DIR_SEI_SVG . '/processo_concluir.svg?' . self::VERSAO;
  public const PROCESSO_DESANEXADO = DIR_SEI_SVG . '/processo_desanexado.svg?' . self::VERSAO;
  public const PROCESSO_DESANEXAR = DIR_SEI_SVG . '/processo_desanexar.svg?' . self::VERSAO;
  public const PROCESSO_DUPLICAR = DIR_SEI_SVG . '/processo_duplicar.svg?' . self::VERSAO;
  public const PROCESSO_ENVIAR = DIR_SEI_SVG . '/processo_enviar.svg?' . self::VERSAO;
  public const PROCESSO_FECHADO = DIR_SEI_SVG . '/processo_fechado.svg?' . self::VERSAO;
  public const PROCESSO_FEDERACAO = DIR_SEI_SVG . '/processo_federacao.svg?' . self::VERSAO;
  public const PROCESSO_FEDERACAO_SEM_ACESSO = DIR_SEI_SVG . '/processo_federacao_sem_acesso.svg?' . self::VERSAO;
  public const PROCESSO_GERAR_PDF = DIR_SEI_SVG . '/processo_gerar_pdf.svg?' . self::VERSAO;
  public const PROCESSO_GERAR_RELACIONADO = DIR_SEI_SVG . '/processo_gerar_relacionado.svg?' . self::VERSAO;
  public const PROCESSO_GERAR_ZIP = DIR_SEI_SVG . '/processo_gerar_zip.svg?' . self::VERSAO;
  public const PROCESSO_MODELO = DIR_SEI_SVG . '/processo_modelo.svg?' . self::VERSAO;
  public const PROCESSO_ORDENAR_ARVORE = DIR_SEI_SVG . '/processo_ordenar_arvore.svg?' . self::VERSAO;
  public const PROCESSO_PESQUISAR = DIR_SEI_SVG . '/processo_pesquisar.svg?' . self::VERSAO;
  public const PROCESSO_PRIORITARIO = DIR_SEI_SVG . '/processo_prioritario.svg?' . self::VERSAO;
  public const PROCESSO_PRIORITARIO_TABELA = DIR_SEI_SVG . '/processo_prioritario_tabela.svg?' . self::VERSAO;
  public const PROCESSO_REABERTURA_PROGRAMADA = DIR_SEI_SVG . '/processo_reabertura_programada.svg?' . self::VERSAO;
  public const PROCESSO_REABRIR = DIR_SEI_SVG . '/processo_reabrir.svg?' . self::VERSAO;
  public const PROCESSO_RELACIONADOS = DIR_SEI_SVG . '/processo_relacionados.svg?' . self::VERSAO;
  public const PROCESSO_REMOVER_RELACIONAMENTO = DIR_SEI_SVG . '/processo_remover_relacionamento.svg?' . self::VERSAO;
  public const PROCESSO_REMOVER_SOBRESTAMENTO = DIR_SEI_SVG . '/processo_remover_sobrestamento.svg?' . self::VERSAO;
  public const PROCESSO_RESTRITO = DIR_SEI_SVG . '/processo_restrito.svg?' . self::VERSAO;
  public const PROCESSO_SIGILOSO = DIR_SEI_SVG . '/processo_sigiloso.svg?' . self::VERSAO;
  public const PROCESSO_SOBRESTAR = DIR_SEI_SVG . '/processo_sobrestar.svg?' . self::VERSAO;

  public const PROTOCOLO_EXCLUIR = DIR_SEI_SVG . '/protocolo_excluir.svg?' . self::VERSAO;

  public const PUBLICACAO = DIR_SEI_SVG . '/publicacao.svg?' . self::VERSAO;
  public const PUBLICACAO_AGENDAR = DIR_SEI_SVG . '/publicacao_agendar.svg?' . self::VERSAO;
  public const PUBLICACAO_ALTERAR = DIR_SEI_SVG . '/publicacao_alterar.svg?' . self::VERSAO;
  public const PUBLICACAO_CANCELAR = DIR_SEI_SVG . '/publicacao_cancelar.svg?' . self::VERSAO;
  public const PUBLICACAO_CONSULTAR = DIR_SEI_SVG . '/publicacao_consultar.svg?' . self::VERSAO;
  public const PUBLICACAO_GERAR_RELACIONADA = DIR_SEI_SVG . '/publicacao_gerar_relacionada.svg?' . self::VERSAO;
  public const PUBLICACAO_RELACIONADAS = DIR_SEI_SVG . '/publicacao_relacionadas.svg?' . self::VERSAO;

  public const RETORNO_AGUARDANDO1 = DIR_SEI_SVG . '/retorno_aguardando1.svg?' . self::VERSAO;
  public const RETORNO_AGUARDANDO2 = DIR_SEI_SVG . '/retorno_aguardando2.svg?' . self::VERSAO;
  public const RETORNO_AGUARDANDO3 = DIR_SEI_SVG . '/retorno_aguardando3.svg?' . self::VERSAO;
  public const RETORNO_AGUARDANDO_TABELA = DIR_SEI_SVG . '/retorno_aguardando_tabela.svg?' . self::VERSAO;

  public const RETORNO_PROGRAMADO1 = DIR_SEI_SVG . '/retorno_programado1.svg?' . self::VERSAO;
  public const RETORNO_PROGRAMADO2 = DIR_SEI_SVG . '/retorno_programado2.svg?' . self::VERSAO;
  public const RETORNO_PROGRAMADO3 = DIR_SEI_SVG . '/retorno_programado3.svg?' . self::VERSAO;
  public const RETORNO_PROGRAMADO_TABELA = DIR_SEI_SVG . '/retorno_programado_tabela.svg?' . self::VERSAO;

  public const SISTEMA_COM_SERVICO = DIR_SEI_SVG . '/sistema_com_servico.svg?' . self::VERSAO;
  public const SISTEMA_SEM_SERVICO = DIR_SEI_SVG . '/sistema_sem_servico.svg?' . self::VERSAO;
  public const SISTEMA_SERVICO_SEM_CHAVE = DIR_SEI_SVG . '/sistema_servico_sem_chave.svg?' . self::VERSAO;
  public const SISTEMA_SERVICO_COM_CHAVE = DIR_SEI_SVG . '/sistema_servico_com_chave.svg?' . self::VERSAO;

  public const SITUACAO = DIR_SEI_SVG . '/situacao.svg?' . self::VERSAO;
  public const SITUACAO_GERENCIAR = DIR_SEI_SVG . '/situacao_gerenciar.svg?' . self::VERSAO;

  public const TABELA_ITEM_CELULA = DIR_SEI_SVG . '/tabela_item_celula.svg?' . self::VERSAO;

  public const VALORES = DIR_SEI_SVG . '/valores.svg?' . self::VERSAO;
}
?>