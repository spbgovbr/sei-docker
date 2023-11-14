<?
	try {

		require_once dirname(__FILE__) . '/../web/SEI.php';

		session_start();

		SessaoSEI::getInstance(false);

		InfraDebug::getInstance()->setBolLigado(false);
		InfraDebug::getInstance()->setBolDebugInfra(false);
		InfraDebug::getInstance()->setBolEcho(true);
		InfraDebug::getInstance()->limpar();

		$data = array();
		$data[] = array('00', 'S', 'ORGANIZAÇÃO E FUNCIONAMENTO', null, null, null, 'Caso gere ato (art. 12, § 2º, "a", "b" e "c", Res. 318/2014, CJF), este será de guarda permanente.');
		$data[] = array('00.01', 'S', 'ADMINISTRAÇÃO JUDICIÁRIA', null, null, null, null);
		$data[] = array('00.01.01', 'S', 'ORGANIZAÇÃO ADMINISTRATIVA', null, null, null, null);
		$data[] = array('00.01.01.01', 'N', 'Modernização Administrativa', '3', '0', 'G', 'Projetos, estudos, qualidade, reengenharia e outros modelos gerenciais. Pode ser classificado pelo assunto pertinente.');
		$data[] = array('00.01.01.03', 'N', 'Estatuto. Regulamentos. Padronização de procedimentos', '3', '0', 'G', null);
		$data[] = array('00.01.01.05', 'N', 'Estrutura organizacional', '3', '0', 'G', 'Inclusive organograma.');
		$data[] = array('00.01.01.07', 'N', 'Ampliação da Justiça Federal', '3', '0', 'G', 'Criação, implantação, especialização de varas, turmas, JEF, TRF.');
		$data[] = array('00.01.01.09', 'N', 'Jurisdição/delimitação territorial', '3', '0', 'G', null);
		$data[] = array('00.01.01.11', 'N', 'Delegações de competência. Procuração', '100', '0', 'E', null);
		$data[] = array('00.01.01.12', 'N', 'Indicação de magistrado à Diretoria do Foro, Presidência dos TRF?s e outros cargos de direção', '10', '5', 'G', null);
		$data[] = array('00.01.01.13', 'N', 'Escolha de magistrado para a composição dos TRF\'s.', '10', '5', 'G', null);
		$data[] = array('00.01.01.14', 'N', 'Horário de expediente', '2', '0', 'E', null);
		$data[] = array('00.01.01.15', 'N', 'Mapeamento e Modelagem de Processos de Trabalho', '3', '0', 'G', null);
		$data[] = array('00.01.01.16', 'N', 'Atribuições e competências das unidades', '3', '0', 'G', null);
		$data[] = array('00.01.01.17', 'N', 'Formalização de acordos (acordos, ajuste, contrato, convênio, termo de cooperação, tratado)', '100', '3', 'G', 'O ato essencial gerado deverá ser encaminhado diretamente ao arquivo, conforme Resolução n. 318/2014, artigo 12.');
		$data[] = array('00.02', 'S', 'DESENVOLVIMENTO DE PESQUISA CIENTÍFICA', null, null, null, null);
		$data[] = array('00.02.00.01', 'N', 'Pesquisa científica', '100', '0', 'G', null);
		$data[] = array('00.03', 'S', 'GESTÃO SÓCIO-AMBIENTAL E RESPONSABILIDADE SOCIAL', null, null, null, null);
		$data[] = array('00.03.00.01', 'N', 'Gestão Ambiental', '3', '0', 'G', null);
		$data[] = array('00.03.00.03', 'N', 'Responsabilidade Social / Voluntariado', '3', '0', 'G', null);
		$data[] = array('00.03.00.05', 'N', 'Programas sócio-educativos para menores', '3', '0', 'G', null);
		$data[] = array('00.04', 'S', 'PLANEJAMENTO ESTRATÉGICO', null, null, null, null);
		$data[] = array('00.04.00.01', 'N', 'Planejamento estratégico', '3', '0', 'G', null);
		$data[] = array('00.05', 'S', 'RELATO DE ATIVIDADES', null, null, null, null);
		$data[] = array('00.05.00.01', 'N', 'Estatística para subsidiar a elaboração de relatórios de atividades', '2', '0', 'E', 'Dados transferidos para o relatório. Pode ser classificado pelo assunto pertinente.');
		$data[] = array('00.05.00.02', 'N', 'Relato de Atividades', '3', '0', 'G', 'Todo e qualquer tipo de Relatório (devendo o assunto ficar especificado na descrição do documento), ');
		$data[] = array('00.06', 'S', 'FISCALIZAÇÃO CONTÁBIL, FINANCEIRA, ORÇAMENTÁRIA, OPERACIONAL E PATRIMONIAL', null, null, null, 'Prazo mínimo de guarda 10 anos, conforme Art. nº 19, da IN 49/2005-TCU.');
		$data[] = array('00.06.01', 'S', 'AUDITORIA', null, null, null, null);
		$data[] = array('00.06.01.01', 'N', 'Auditoria externa', '3', '0', 'G', null);
		$data[] = array('00.06.01.02', 'N', 'Auditoria  interna', '100', '0', 'G', null);
		$data[] = array('00.06.02', 'S', 'PRESTAÇÃO  DE CONTAS', null, null, null, null);
		$data[] = array('00.06.02.01', 'N', 'Tomada de contas especial', '0', '0', 'E', null);
		$data[] = array('00.06.02.03', 'N', 'Decisão do TCU sobre as contas', '100', '0', 'G', null);
		$data[] = array('00.06.02.05', 'N', 'Prestação de Contas Anual', '0', '0', 'E', null);
		$data[] = array('00.07', 'S', 'INFORMAÇÃO PARA SUBSIDIAR AÇÕES JUDICIAIS', null, null, null, null);
		$data[] = array('00.07.00.01', 'N', 'Informação para subsidiar ações judiciais', '2', '0', 'E', 'Informações em ações contra o órgão, documentos para subsidiar a defesa. ');
		$data[] = array('00.07.00.02', 'N', 'Acompanhamento de decisões judiciais', '3', '0', 'E', null);
		$data[] = array('00.08', 'S', 'REGULAMENTAÇÃO', null, null, null, null);
		$data[] = array('00.08.00.01', 'N', 'Estudos e proposições para normas, regulamentações, diretrizes ', '3', '0', 'G', 'Caso gere ato normativo,  deverá ser classificado pelo assunto pertinente.');
		$data[] = array('00.09', 'S', 'REGISTRO NOS ÓRGÃOS COMPETENTES', null, null, null, null);
		$data[] = array('00.09.00.01', 'N', 'Registro junto à Receita Federal / Ministério da Fazenda', '100', '0', 'E', 'Cadastro Geral de Contribuinte (CGC), Cadastro Nacional da Pessoa Jurídica (CNPJ). Sujeito à análise histórica.');
		$data[] = array('00.10', 'S', 'ÓRGÃOS COLEGIADOS DE COMPETÊNCIA ADMINISTRATIVA, COMITÊS, COMISSÕES E GRUPOS DE TRABALHO', null, null, null, null);
		$data[] = array('00.10.00.01', 'N', 'Funcionamento de colegiados', '3', '0', 'G', null);
		$data[] = array('00.10.00.02', 'N', 'Criação de comitês, comissões e grupos de trabalho', '2', '0', 'G', null);
		$data[] = array('00.10.00.03', 'N', 'Indicação de membros para composição', '2', '0', 'G', null);
		$data[] = array('00.10.00.04', 'N', 'Convocação para reunião', '2', '0', 'E', null);
		$data[] = array('00.10.00.05', 'N', 'Registro de reunião', '3', '0', 'G', 'Ata, Memória da Reunião.');
		$data[] = array('00.10.02', 'S', 'DISTRIBUIÇÃO DE PROCESSO ADMINISTRATIVO DO COLEGIADO', null, null, null, null);
		$data[] = array('00.10.02.01', 'N', 'Distribuição de processo administrativo do colegiado', '2', '0', 'E', null);
		$data[] = array('00.10.03', 'S', 'TRAMITAÇÃO, PROCESSAMENTO, BAIXA E ARQUIVAMENTO DE PROCESSO ADMINISTRATIVO DO COLEGIADO', null, null, null, null);
		$data[] = array('00.10.03.01', 'N', 'Providências/informações sobre o andamento de processo do colegiado', '2', '0', 'E', 'Diligências, antecedentes, inclusão em pauta.');
		$data[] = array('00.10.03.02', 'N', 'Comunicação de decisões, despachos, julgamentos de processo do colegiado', '3', '0', 'E', 'Tanto expedida, quanto recebida.');
		$data[] = array('00.10.03.03', 'N', 'Certidão de processo do colegiado', '100', '0', 'E', null);
		$data[] = array('00.10.04', 'S', 'JULGAMENTO DE PROCESSO DO COLEGIADO', null, null, null, null);
		$data[] = array('00.10.04.01', 'N', 'Registro de audiência de julgamento de processo do colegiado', '3', '0', 'G', 'Inclusive ata, livro de transcrição de depoimentos,notas taquigráficas, registros em audio, vídeo e meios digitais.');
		$data[] = array('00.11', 'S', 'COMUNICAÇÃO E REPRESENTAÇÃO SOCIAL', null, null, null, null);
		$data[] = array('00.11.01', 'S', 'RELACÕES COM A IMPRENSA. ENTREVISTAS. NOTICIÁRIOS. REPORTAGENS. EDITORIAIS', null, null, null, null);
		$data[] = array('00.11.01.01', 'N', 'Relações com a imprensa', '2', '0', 'E', 'Inclusive credenciamento.');
		$data[] = array('00.11.01.02', 'N', 'coletânea de reportagens sobre o Poder Judiciário', '3', '0', 'G', 'Clipping.');
		$data[] = array('00.11.01.03', 'N', 'matérias sobre a instituição a serem divulgadas pela imprensa', '2', '0', 'G', 'Release.');
		$data[] = array('00.11.01.04', 'N', 'Produção Jornalística', '2', '0', 'G', 'Independente de mídia (Programas veiculados na TV e nas Rádios).');
		$data[] = array('00.11.01.05', 'N', 'Credenciamento de imprensa', '3', '0', 'E', null);
		$data[] = array('00.11.02', 'S', 'RELAÇÕES PÚBLICAS : SOLENIDADES, COMEMORAÇÕES, HOMENAGENS. ', null, null, null, null);
		$data[] = array('00.11.02.01', 'N', 'Memória da solenidade', '3', '0', 'G', 'Planejamento, programação, discursos, palestras e trabalhos apresentados por técnicos do órgão.');
		$data[] = array('00.11.02.02', 'N', 'Visitas e visitantes oficiais', '2', '0', 'G', null);
		$data[] = array('00.11.02.03', 'N', 'Agradecimentos, congratulações, felicitações etc. ', '2', '0', 'E', null);
		$data[] = array('00.11.02.04', 'N', 'Eventos culturais', '2', '5', 'E', 'Pode ser juntado ao dossiê.');
		$data[] = array('00.11.02.05', 'N', 'Campanhas institucionais', '2', '5', 'E', 'Pode ser juntado ao dossiê.');
		$data[] = array('00.11.04', 'S', 'OUVIDORIA', null, null, null, null);
		$data[] = array('00.11.04.01', 'N', 'Ouvidoria externa', '5', '0', 'E', 'Caso gere processo, este será classificado pelo assunto.');
		$data[] = array('00.11.04.02', 'N', 'Ouvidoria interna ', '5', '0', 'E', 'Atendimento ao servidor / magistrado. Caso gere processo, este será classificado pelo assunto.');
		$data[] = array('00.12', 'S', 'HIGIENE E SEGURANÇA DO TRABALHO', null, null, null, null);
		$data[] = array('00.12.00.01', 'N', 'Higiene e Segurança do trabalho', '3', '0', 'G', null);
		$data[] = array('00.12.00.03', 'N', 'Prevenção de acidentes de trabalho - CIPA', '3', '0', 'G', null);
		$data[] = array('00.12.00.05', 'N', 'Ergonomia', '3', '0', 'G', null);
		$data[] = array('00.12.00.07', 'N', 'Combate a incêndio', '3', '0', 'G', null);
		$data[] = array('00.12.00.09', 'N', 'Vigilância sanitária', '3', '0', 'G', null);
		$data[] = array('00.12.00.11', 'N', 'Inspeções periódicas de saúde ', '3', '0', 'G', null);
		$data[] = array('00.13', 'S', 'GESTÃO DE PROJETOS ', null, null, null, null);
		$data[] = array('00.13.00.01', 'N', 'Portfólio de Projetos', '3', '0', 'G', null);
		$data[] = array('10', 'S', 'ORÇAMENTO E FINANÇAS', null, null, null, 'Caso gere ato (art. 12, § 2º, "a", "b" e "c", Res. 318/2014, CJF), este será de guarda permanente.');
		$data[] = array('10.01', 'S', 'SIAFI', null, null, null, null);
		$data[] = array('10.01.00.01', 'N', 'SIAFI', '2', '0', 'E', 'Controle  de acesso.');
		$data[] = array('10.02', 'S', 'PROGRAMAÇÃO ORÇAMENTÁRIA E FINANCEIRA ', null, null, null, null);
		$data[] = array('10.02.00.01', 'N', 'Plano plurianual - PPA', '100', '0', 'G', 'Documentação referente à lei, inclusive propostas parciais e definição de metas.');
		$data[] = array('10.02.00.02', 'N', 'Lei de diretrizes orçamentárias - LDO', '5', '0', 'E', 'Documentação referente à lei.');
		$data[] = array('10.03', 'S', 'LEI ORÇAMENTÁRIA ANUAL  - LOA', null, null, null, null);
		$data[] = array('10.03.00.01', 'N', 'Lei Orçamentária Anual - LOA', '100', '10', 'G', 'Documentação referente à lei, inclusive contigenciamento e descontigenciamento. ');
		$data[] = array('10.03.00.02', 'N', 'Reprogramação orçamentária', '6', '0', 'E', null);
		$data[] = array('10.03.00.03', 'N', 'Alterações no QDD - Notas de Dotação', '1', '0', 'E', null);
		$data[] = array('10.03.00.04', 'N', 'Proposta orçamentária', '6', '0', 'E', null);
		$data[] = array('10.04', 'S', 'SOLICITAÇÃO DE DOTAÇÃO ORÇAMENTÁRIA', null, null, null, null);
		$data[] = array('10.04.00.01', 'N', 'Programação financeira de custeio e capital', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.02', 'N', 'Programação financeira de pessoal', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.03', 'N', 'Crédito suplementar, especial ou extraordinário.', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.04', 'N', 'Enquadramento da despesa', '100', '10', 'E', null);
		$data[] = array('10.04.00.05', 'N', 'Programação financeira de precatórios', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.06', 'N', 'Programação financeira de RPVs', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.07', 'N', 'Programação Financeira para Contribuição Patronal', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.08', 'N', 'Restituição de Receitas (arrecadadas por GRU)', '100', '10', 'E', null);
		$data[] = array('10.04.00.09', 'N', 'Programação financeira de pessoal (exercícios anteriores - DEA)', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.04.00.10', 'N', 'Programação Financeira de Sentenças Judiciais', '100', '10', 'E', 'Inclui solicitação de dotação orçamentária.');
		$data[] = array('10.05', 'S', 'EXECUÇÃO ORÇAMENTÁRIA E FINANCEIRA', null, null, null, null);
		$data[] = array('10.05.00.01', 'N', 'Transferência orçamentária', '1', '0', 'E', null);
		$data[] = array('10.05.00.02', 'N', 'Transferência financeira ', '2', '0', 'E', 'Processo de pagamento relativos aos serviços prestados por autônomos ou empresas mediante cessão de mão-de-obra ou empreitada, com retenção de INSS ou declaração de autônomo, deverão ser preservados por 10 anos.');
		$data[] = array('10.05.00.03', 'N', 'Cronograma de desembolso', '1', '0', 'E', null);
		$data[] = array('10.05.00.04', 'N', 'Relação de Ordem Bancária', '100', '10', 'E', 'Inclusive Relação de Ordem Bancária Intra-SIAFI.');
		$data[] = array('10.05.00.05', 'N', 'Declaração de Imposto de Renda na Fonte - DIRF', '0', '0', 'E', null);
		$data[] = array('10.05.00.06', 'N', 'Guia de Recolhimento do FGTS e Informações à Previdência Social - GFIP', '5', '51', 'E', null);
		$data[] = array('10.05.00.07', 'N', 'Suprimento de fundos', '100', '10', 'E', 'Pode ser gerado um processo.');
		$data[] = array('10.05.00.08', 'N', 'Pagamento de tributos/impostos', '100', '10', 'E', null);
		$data[] = array('10.05.00.09', 'N', 'Empenho', '100', '0', 'E', null);
		$data[] = array('10.05.00.10', 'N', 'Conformidade de Gestão', '100', '10', 'E', null);
		$data[] = array('10.05.00.11', 'N', 'Crédito suplementar, especial ou extraordinário.', '100', '10', 'E', null);
		$data[] = array('10.05.00.12', 'N', 'Encerramento do Exercício', '100', '10', 'E', null);
		$data[] = array('10.05.00.13', 'N', 'Descentralização Orçamentária', '100', '10', 'E', null);
		$data[] = array('10.05.00.14', 'N', 'Contingenciamento', '100', '10', 'E', null);
		$data[] = array('10.05.01', 'S', 'ARRECADAÇÃO', null, null, null, null);
		$data[] = array('10.05.01.01', 'N', 'Valores restituídos a Justiça Federal  ou ao Erário     ', '2', '0', 'E', null);
		$data[] = array('10.05.01.02', 'N', 'Guias de Recolhimento', '2', '0', 'E', 'Documento de Arrecadação de Receitas Federais - DARF e avisos de depósito.');
		$data[] = array('10.05.01.03', 'N', 'Dados estatísticos da arrecadação', '2', '0', 'E', 'Pode ser eliminado após transferido ao relatório anual. ');
		$data[] = array('10.06', 'S', 'CONTROLE ORÇAMENTÁRIO E FINANCEIRO', null, null, null, null);
		$data[] = array('10.06.00.01', 'N', 'Custos', '2', '0', 'E', null);
		$data[] = array('10.06.00.02', 'N', 'Dados Estatísticos', '2', '0', 'E', 'Pode ser eliminado após transferido ao relatório anual. ');
		$data[] = array('10.06.00.03', 'N', 'Rol de Responsáveis', '100', '10', 'E', null);
		$data[] = array('10.06.01', 'S', 'DEMONSTRATIVO FINANCEIRO', null, null, null, null);
		$data[] = array('10.06.01.01', 'N', 'Balancete Mensal', '100', '10', 'E', 'Orçamentário, físico-financeiro, patrimonial, compensado.');
		$data[] = array('10.06.01.02', 'N', 'Demonstrativo - Balanço', '100', '10', 'E', 'Orçamentário, físico-financeiro, patrimonial, compensado.');
		$data[] = array('20', 'S', 'GESTÃO DE PESSOAS', null, null, null, 'Caso gere ato (art. 12, § 2º, "a", "b" e "c", Res. 318/2014, CJF), este será de guarda permanente.');
		$data[] = array('20.01', 'S', 'QUADROS, TABELAS E POLÍTICA DE PESSOAL', null, null, null, null);
		$data[] = array('20.01.01', 'S', 'CARGOS E FUNÇÕES', null, null, null, null);
		$data[] = array('20.01.01.01', 'N', 'Estudos e previsão', '2', '0', 'G', 'O projeto será classificado na classe 00.');
		$data[] = array('20.01.01.02', 'N', 'Remuneração', '2', '0', 'E', 'Enquadramento e tabelas.');
		$data[] = array('20.01.01.03', 'N', 'Classificação de cargos e funções', '100', '0', 'G', 'Uso pelo CJF.');
		$data[] = array('20.01.01.04', 'N', 'Atribuições de cargos e funções', '100', '0', 'G', 'Uso pelo CJF.');
		$data[] = array('20.01.01.05', 'N', 'Criação de cargos e funções', '5', '0', 'G', null);
		$data[] = array('20.01.01.06', 'N', 'Controle e distribuição de cargos providos e vagos', '100', '0', 'E', null);
		$data[] = array('20.01.01.07', 'N', 'Controle e distribuição de funções comissionadas providas e vagas', '100', '0', 'E', null);
		$data[] = array('20.01.01.08', 'N', 'Acumulação de cargos / proventos', '5', '51', 'E', null);
		$data[] = array('20.01.01.09', 'N', 'Transformação de cargo', '2', '0', 'G', null);
		$data[] = array('20.01.01.10', 'N', 'Carga horária', '5', '51', 'E', null);
		$data[] = array('20.01.01.11', 'N', 'Transformação de função comissionada', '2', '0', 'G', null);
		$data[] = array('20.02', 'S', 'INGRESSO E DESLIGAMENTO', null, null, null, null);
		$data[] = array('20.02.01', 'S', 'CONCURSO PÚBLICO PARA A MAGISTRATURA FEDERAL', null, null, null, null);
		$data[] = array('20.02.01.01', 'N', 'Concurso público para a magistratura', '100', '0', 'G', 'Dossiê do concurso.');
		$data[] = array('20.02.01.02', 'N', 'Inscrição', '100', '0', 'E', 'Dossiês dos candidatos aprovados serão incluídos nos assentamentos funcionais.');
		$data[] = array('20.02.01.03', 'N', 'Avaliação escrita e oral', '100', '0', 'E', 'Prova de Juízes aprovados - Guarda Permanente.');
		$data[] = array('20.02.02', 'S', 'INGRESSO NA MAGISTRATURA PELO QUINTO CONSTITUCIONAL', null, null, null, null);
		$data[] = array('20.02.02.01', 'N', 'Lísta tríplice para o quinto constitucional', '100', '0', 'G', null);
		$data[] = array('20.02.03', 'S', 'CONCURSO PÚBLICO PARA O SERVIÇO FEDERAL', null, null, null, null);
		$data[] = array('20.02.03.01', 'N', 'Concurso público para servidor', '0', '0', 'G', 'Dossiê do concurso.');
		$data[] = array('20.02.03.02', 'N', 'Questionamentos e solicitações', '100', '5', 'E', null);
		$data[] = array('20.02.03.03', 'N', 'Convocação de candidato aprovado em concurso público', '0', '0', 'E', null);
		$data[] = array('20.02.03.04', 'N', 'Convocação de candidatos aprovados em outros concursos públicos', '100', '0', 'E', null);
		$data[] = array('20.02.03.05', 'N', 'Candidatos aprovados em concurso público da Justiça Federal solicitados por outro órgão', '100', '2', 'E', null);
		$data[] = array('20.02.03.06', 'N', 'Recursos de candidatos', '100', '5', 'E', null);
		$data[] = array('20.02.04', 'S', 'PROVIMENTO POR NOMEAÇÃO, POSSE E EXERCÍCIO', null, null, null, null);
		$data[] = array('20.02.04.01', 'N', 'Nomeação de magistrados', '5', '51', 'E', null);
		$data[] = array('20.02.04.02', 'N', 'Nomeação de servidor para cargo em comissão', '5', '51', 'E', null);
		$data[] = array('20.02.04.03', 'N', 'Nomeação de servidor para cargo efetivo', '5', '51', 'E', null);
		$data[] = array('20.02.04.04', 'N', 'Compromisso com as atribuições do cargo', '0', '0', 'G', 'Termo de posse para atestar compromisso.');
		$data[] = array('20.02.04.05', 'N', 'Efetivo exercício', '2', '0', 'E', null);
		$data[] = array('20.02.04.06', 'N', 'Prazo para posse', '2', '0', 'E', null);
		$data[] = array('20.02.04.07', 'N', 'Exame pré-admissional', '0', '0', 'G', null);
		$data[] = array('20.02.04.08', 'N', 'Incapacidade para ingressar no serviço público', '5', '0', 'G', null);
		$data[] = array('20.02.04.09', 'N', 'Candidatos portadores de deficiência', '100', '2', 'E', null);
		$data[] = array('20.02.05', 'S', 'OUTRAS FORMAS DE PROVIMENTO', null, null, null, null);
		$data[] = array('20.02.05.01', 'N', 'Promoção de magistrados por antiguidade', '5', '0', 'G', null);
		$data[] = array('20.02.05.02', 'N', 'Promoção de magistrados por merecimento', '5', '0', 'G', null);
		$data[] = array('20.02.05.03', 'N', 'Lista tríplice para promoção por merecimento', '100', '0', 'G', null);
		$data[] = array('20.02.05.04', 'N', 'Transferência de magistrados entre órgãos', '5', '51', 'E', null);
		$data[] = array('20.02.05.05', 'N', 'Permuta de magistrados entre órgãos', '5', '51', 'E', null);
		$data[] = array('20.02.05.06', 'N', 'Readaptação de servidor no cargo efetivo', '5', '51', 'E', null);
		$data[] = array('20.02.05.07', 'N', 'Recondução de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.05.08', 'N', 'Reintegração de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.05.09', 'N', 'Reversão de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.05.10', 'N', 'Admissão de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.05.11', 'N', 'Aproveitamento de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.05.12', 'N', 'Contratação de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.05.13', 'N', 'Transferência de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.06', 'S', 'DADOS E IDENTIFICAÇÃO FUNCIONAIS', null, null, null, null);
		$data[] = array('20.02.06.01', 'N', 'Assentamento Funcional ', '100', '0', 'G', 'De uso restrito da Secretaria de Recursos Humanos.');
		$data[] = array('20.02.06.02', 'N', 'Dependentes', '100', '95', 'E', null);
		$data[] = array('20.02.06.03', 'N', 'Beneficiário de pensão', '100', '95', 'E', 'Inclusão / exclusão.');
		$data[] = array('20.02.06.04', 'N', 'Recadastramento de inativos e pensionistas', '2', '0', 'E', null);
		$data[] = array('20.02.06.05', 'N', 'Declaração sobre servidor', '0', '0', 'E', 'Inclusive "nada consta", "histórico funcional".');
		$data[] = array('20.02.06.06', 'N', ' Documentos admissionais', '0', '0', 'G', 'Documentos obrigatórios entregues no momento da posse em cargo ou função. A declaração de IR e bens será juntada no processo de nomeação e exoneração/vacância.');
		$data[] = array('20.02.06.07', 'N', 'Comprovante de votação', '1', '0', 'E', 'Eleição');
		$data[] = array('20.02.06.08', 'N', 'Tempo de serviço', '100', '95', 'E', null);
		$data[] = array('20.02.06.09', 'N', 'Identificação funcional ', '5', '10', 'E', 'Carteira, crachá, identificação digital.');
		$data[] = array('20.02.06.10', 'N', 'Carteiras e crachás recolhidos', '100', '0', 'E', 'A carteira ou crachá deverá ser destruído');
		$data[] = array('20.02.06.11', 'N', 'Tempo de contribuição', '100', '95', 'E', null);
		$data[] = array('20.02.07', 'S', 'VITALICIAMENTO E PROMOÇÃO ', null, null, null, null);
		$data[] = array('20.02.07.01', 'N', 'Sentença de juízes em período de vitaliciamento', '100', '0', 'E', null);
		$data[] = array('20.02.07.02', 'N', 'Vitaliciamento de juiz federal substituto', '0', '0', 'G', null);
		$data[] = array('20.02.07.03', 'N', 'Desempenho dos servidores', '0', '0', 'G', 'Inclusive avaliação e gestão.');
		$data[] = array('20.02.07.04', 'N', 'Estágio probatório', '5', '51', 'E', null);
		$data[] = array('20.02.07.05', 'N', 'Promoção / Progressão funcional de servidores', '0', '0', 'G', null);
		$data[] = array('20.02.08', 'S', 'INCENTIVOS FUNCIONAIS', null, null, null, null);
		$data[] = array('20.02.08.01', 'N', 'Premiações e medalhas', '0', '0', 'G', null);
		$data[] = array('20.02.08.02', 'N', 'Honra ao mérito, elogios, voto de louvor', '0', '0', 'G', null);
		$data[] = array('20.02.09', 'S', 'DESLIGAMENTO DE MAGISTRADO', null, null, null, null);
		$data[] = array('20.02.09.01', 'N', 'Demissão de magistrado', '10', '0', 'G', null);
		$data[] = array('20.02.09.02', 'N', 'Promoção de magistrado', '10', '0', 'G', null);
		$data[] = array('20.02.09.03', 'N', 'Desligamento por aposentadoria', '10', '0', 'G', null);
		$data[] = array('20.02.09.04', 'N', 'Posse  de magistrado em outro cargo inacumulável', '10', '0', 'G', null);
		$data[] = array('20.02.09.05', 'N', 'Falecimento de magistrado', '0', '0', 'G', null);
		$data[] = array('20.02.10', 'S', 'VACÂNCIA DE CARGO PÚBLICO', null, null, null, null);
		$data[] = array('20.02.10.01', 'N', 'Exoneração de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.10.02', 'N', 'Demissão de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.10.03', 'N', 'Vacância por promoção de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.10.04', 'N', 'Readaptação de servidor', '5', '51', 'E', null);
		$data[] = array('20.02.10.05', 'N', 'Vacância por aposentadoria', '5', '51', 'E', null);
		$data[] = array('20.02.10.06', 'N', 'Posse de servidor em outro cargo inacumulável', '5', '51', 'E', null);
		$data[] = array('20.02.10.07', 'N', 'Falecimento de servidor', '5', '51', 'E', null);
		$data[] = array('20.03', 'S', 'MOVIMENTAÇÃO', null, null, null, null);
		$data[] = array('20.03.01', 'S', 'LOTAÇÃO DE SERVIDOR', null, null, null, null);
		$data[] = array('20.03.01.01', 'N', 'Controle de lotação de servidores', '100', '0', 'E', 'Pode ser transferido para relatórios estatísticos.');
		$data[] = array('20.03.01.02', 'N', 'Lotação de servidor', '5', '0', 'E', null);
		$data[] = array('20.03.01.03', 'N', 'Exercício provisório', '100', '0', 'E', 'Licença para acompanhar cônjuge.');
		$data[] = array('20.03.02', 'S', 'MOVIMENTAÇÃO E REMOÇÃO', null, null, null, null);
		$data[] = array('20.03.02.01', 'N', 'Mudança de magistrado de turma ou vara', '5', '95', 'E', 'Inclusive Afastamento de magistrado em trânsito');
		$data[] = array('20.03.02.02', 'N', 'Remoção', '5', '51', 'E', 'Inclusive remoção compulsória. O dossiê do concurso de remoção deverá ter guarda permanente.');
		$data[] = array('20.03.02.03', 'N', 'Redistribuição de servidor', '5', '51', 'E', null);
		$data[] = array('20.03.02.04', 'N', 'Disponibilidade', '5', '51', 'E', null);
		$data[] = array('20.03.03', 'S', 'REQUISIÇÃO DE PESSOAL. CESSÃO', null, null, null, null);
		$data[] = array('20.03.03.01', 'N', 'Requisição de servidor', '5', '51', 'E', 'Inclusive solicitação, prorrogação.');
		$data[] = array('20.03.03.02', 'N', 'Cessão de servidor', '5', '51', 'E', 'Inclusive solicitação, prorrogação');
		$data[] = array('20.03.04', 'S', 'DESIGNAÇÃO, CONVOCAÇÃO, SUBSTITUIÇÃO E DISPENSA', null, null, null, null);
		$data[] = array('20.03.04.01', 'N', 'Designação de magistrados ', '5', '0', 'E', null);
		$data[] = array('20.03.04.02', 'N', 'Substituição de magistrados', '5', '0', 'E', null);
		$data[] = array('20.03.04.03', 'N', 'Convocação para outros órgãos', '5', '0', 'E', null);
		$data[] = array('20.03.04.04', 'N', 'Designação de servidor para função comissionada', '5', '0', 'E', null);
		$data[] = array('20.03.04.05', 'N', 'Substituição de servidor em cargo ou função comissionada', '5', '0', 'E', null);
		$data[] = array('20.03.04.06', 'N', 'Dispensa de servidor da função comissionada', '5', '0', 'E', null);
		$data[] = array('20.04', 'S', 'CAPACITAÇÃO E APERFEIÇOAMENTO ', null, null, null, null);
		$data[] = array('20.04.00.01', 'N', 'Comunicação de participação de magistrado em curso / evento', '2', '0', 'E', null);
		$data[] = array('20.04.00.02', 'N', 'Programas de aperfeiçoamento ', '2', '0', 'G', 'EMAGIS, LNC, PNA E PNC');
		$data[] = array('20.04.00.03', 'N', 'Trabalho de conclusão / monografia', '0', '0', 'E', 'Quando solicitado pelo órgão.');
		$data[] = array('20.04.00.04', 'N', 'Eventos de capacitação promovidos pela instituição', '2', '0', 'G', 'Programa, relatório, relação de participantes.');
		$data[] = array('20.04.00.05', 'N', 'Participação de servidor / magistrado em cursos e eventos de treinamento, aperfeiçoamento, etc.', '0', '0', 'E', 'Comprovante de participação.');
		$data[] = array('20.04.00.06', 'N', 'Eventos promovidos por outras instituições', '5', '0', 'E', null);
		$data[] = array('20.05', 'S', 'VENCIMENTOS E REMUNERAÇÃO', null, null, null, null);
		$data[] = array('20.05.00.01', 'N', 'Débitos pendentes de magistrados e servidores com a União', '5', '51', 'E', null);
		$data[] = array('20.05.00.02', 'N', 'Débitos da União com magistrados e servidores', '5', '51', 'E', null);
		$data[] = array('20.05.00.03', 'N', 'Decisão judicial sobre salários, vencimentos, proventos e remunerações', '5', '51', 'E', null);
		$data[] = array('20.05.00.04', 'N', 'Reestruturação e alterações salariais', '5', '0', 'G', null);
		$data[] = array('20.05.00.05', 'N', 'Diferenças e reposições salariais', '5', '95', 'E', 'URV');
		$data[] = array('20.05.00.06', 'N', 'Salário-família', '5', '51', 'E', 'Para os casos especiais previstos no Regime Jurídico Único, o prazo total de guarda para os documentos referentes à concessão de salário-família será de 100 anos.');
		$data[] = array('20.05.00.07', 'N', 'Teto Remuneratório Constitucional', '5', '51', 'E', 'Conf. Art. nº 37, XI, CF/88.');
		$data[] = array('20.05.00.08', 'N', 'Salário de menor aprendiz', '100', '10', 'E', null);
		$data[] = array('20.05.01', 'S', 'DESCONTOS. CONSIGNAÇÕES', null, null, null, null);
		$data[] = array('20.05.01.01', 'N', 'Consignação em folha', '100', '2', 'E', 'Autorização, alteração, desistência, quitação, etc.');
		$data[] = array('20.05.01.02', 'N', 'Devolução de parcela remuneratória', '5', '51', 'E', 'Autorização, alteração, desistência, quitação, desconto em folha.');
		$data[] = array('20.05.01.03', 'N', 'Desconto em folha para falta não justificada', '5', '95', 'E', null);
		$data[] = array('20.05.02', 'S', 'CONTRIBUIÇÃO SINDICAL DO SERVIDOR', null, null, null, null);
		$data[] = array('20.05.02.01', 'N', 'Contribuição sindical do servidor', '5', '51', 'E', null);
		$data[] = array('20.05.03', 'S', 'CONTRIBUIÇÃO À ENTIDADE DE CLASSE ', null, null, null, null);
		$data[] = array('20.05.03.01', 'N', 'Contribuição à Entidade de Classe - magistrado', '5', '51', 'E', null);
		$data[] = array('20.05.04', 'S', 'CONTRIBUIÇÃO PARA A SEGURIDADE SOCIAL', null, null, null, null);
		$data[] = array('20.05.04.01', 'N', 'Contribuição para seguridade social', '5', '51', 'E', null);
		$data[] = array('20.05.05', 'S', 'IMPOSTO DE RENDA DE PESSOA FÍSICA (IRPF)', null, null, null, null);
		$data[] = array('20.05.05.01', 'N', 'Comprovante Anual de Rendimentos para IRPF', '0', '0', 'E', null);
		$data[] = array('20.05.05.02', 'N', 'Declaração de Ajuste Anual do Imposto de Renda de Pessoa Física', '7', '0', 'E', 'Inclusive Declaração de Bens e Valores.');
		$data[] = array('20.05.06', 'S', 'PENSÃO ALIMENTÍCIA', null, null, null, null);
		$data[] = array('20.05.06.01', 'N', 'Pensão alimentícia', '5', '95', 'E', 'Inclusive decisão judicial.');
		$data[] = array('20.05.07', 'S', 'ENCARGOS PATRONAIS. RECOLHIMENTOS', null, null, null, null);
		$data[] = array('20.05.07.01', 'N', 'PIS-Programa de Integração Social; PASEP-Programa de Formação do Patrimônio do Servidor Público ', '5', '51', 'E', null);
		$data[] = array('20.05.07.02', 'N', 'Recolhimento da contribuição sindical do empregado', '5', '51', 'E', null);
		$data[] = array('20.05.07.03', 'N', 'Contribuição do empregador para o plano de seguridade social ', '5', '51', 'E', null);
		$data[] = array('20.05.07.04', 'N', 'Recolhimento do Imposto de renda', '5', '51', 'E', null);
		$data[] = array('20.05.07.05', 'N', 'Isenção do Imposto de renda', '5', '51', 'E', null);
		$data[] = array('20.05.08', 'S', 'RESSARCIMENTOS E REEMBOLSOS', null, null, null, null);
		$data[] = array('20.05.08.01', 'N', 'Ressarcimento a magistrado / servidor', '100', '10', 'E', null);
		$data[] = array('20.05.08.02', 'N', 'Reembolso de despesas', '100', '10', 'E', null);
		$data[] = array('20.05.09', 'S', 'FOLHAS DE PAGAMENTO DE PESSOAL. FICHAS FINANCEIRAS', null, null, null, null);
		$data[] = array('20.05.09.01', 'N', 'Ficha financeira ', '2', '0', 'G', null);
		$data[] = array('20.05.09.02', 'N', 'Folha de pagamento', '5', '95', 'E', null);
		$data[] = array('20.05.09.03', 'N', 'Rubrica', '5', '0', 'G', 'Inclusive criação, alteração, exclusão. Para uso exclusivo pelo CJF.');
		$data[] = array('20.05.09.04', 'N', 'Relação Anual de Informações Sociais (RAIS)', '5', '10', 'E', null);
		$data[] = array('20.05.10', 'S', 'VANTAGENS  E INDENIZAÇÕES', null, null, null, null);
		$data[] = array('20.05.10.01', 'N', 'Indenização por exoneração de função', '5', '95', 'E', null);
		$data[] = array('20.05.10.02', 'N', 'Indenização de transporte', '5', '95', 'E', 'Uso de carro próprio por oficial de justiça.');
		$data[] = array('20.05.10.03', 'N', 'Ajuda de custo para mudança de domicílio', '100', '10', 'E', null);
		$data[] = array('20.05.10.04', 'N', 'Auxílio-moradia', '100', '10', 'E', null);
		$data[] = array('20.05.10.05', 'N', 'Diárias', '100', '10', 'E', null);
		$data[] = array('20.05.10.06', 'N', 'Viagem a serviço com ônus', '100', '10', 'E', null);
		$data[] = array('20.05.10.07', 'N', 'Viagem a serviço sem ônus', '5', '0', 'E', null);
		$data[] = array('20.05.10.08', 'N', 'Indenização de férias', '5', '95', 'E', null);
		$data[] = array('20.05.11', 'S', 'GRATIFICAÇÓES E ADICIONAIS', null, null, null, null);
		$data[] = array('20.05.11.01', 'N', 'Gratificação por encargo de curso ou concurso', '5', '51', 'E', 'Instrutoria prestada por servidor / magistrado. Pode ser gerado um processo.');
		$data[] = array('20.05.11.02', 'N', 'Vantagens pessoais', '5', '51', 'E', 'VPNI, manutenção, extinção.');
		$data[] = array('20.05.11.03', 'N', 'Quintos e décimos', '5', '51', 'E', 'Cargos em comissão e de função.');
		$data[] = array('20.05.11.04', 'N', 'Gratificação natalina (13º salário)', '5', '51', 'E', null);
		$data[] = array('20.05.11.05', 'N', 'Gratificações relativas a Planos de Cargos', '5', '51', 'E', 'GEL, GRM, GAE, GAJ. Pode ser gerado um processo.  ');
		$data[] = array('20.05.11.06', 'N', 'Adicional por tempo de serviço', '5', '51', 'E', 'Anuênio - quinquênio');
		$data[] = array('20.05.11.07', 'N', 'Adicional noturno', '5', '51', 'E', null);
		$data[] = array('20.05.11.08', 'N', 'Adicional de periculosidade', '5', '51', 'E', null);
		$data[] = array('20.05.11.09', 'N', 'Adicional de insalubridade', '5', '51', 'E', null);
		$data[] = array('20.05.11.10', 'N', 'Adicional de atividades penosas', '5', '51', 'E', null);
		$data[] = array('20.05.11.11', 'N', 'Serviço extraordinário', '5', '51', 'E', 'Horas extras de servidor');
		$data[] = array('20.05.11.12', 'N', 'Adicional de férias, abono pecuniário', '5', '51', 'E', null);
		$data[] = array('20.05.11.13', 'N', 'Adicional de qualificação - AQ', '5', '51', 'E', null);
		$data[] = array('20.06', 'S', 'AFASTAMENTOS', null, null, null, null);
		$data[] = array('20.06.00.01', 'N', 'Afastamento para estudo ou missão no exterior', '5', '51', 'E', null);
		$data[] = array('20.06.00.02', 'N', 'Afastamento de magistrado para frequência a cursos ou seminários de aperfeiçoamento e estudos', '100', '95', 'E', null);
		$data[] = array('20.06.00.03', 'N', 'Afastamento de magistrado para prestação de serviços exclusivamente à Justiça Eleitoral', '100', '95', 'E', null);
		$data[] = array('20.06.00.04', 'N', 'Afastamento de magistrado para presidir associação de classe', '100', '95', 'E', null);
		$data[] = array('20.06.00.05', 'N', 'Afastamento de dias trabalhados por magistrado a título de plantão', '100', '95', 'E', null);
		$data[] = array('20.06.00.06', 'N', 'Afastamento por motivo de exercício de mandato eletivo', '5', '51', 'E', null);
		$data[] = array('20.06.00.07', 'N', 'Afastamento para prestação de depoimentos', '5', '51', 'E', null);
		$data[] = array('20.06.00.08', 'N', 'Afastamento para prestação de assistência como jurado', '5', '51', 'E', null);
		$data[] = array('20.06.00.09', 'N', 'Afastamento para participação em programa de Pós-Graduação Stricto Sensu no país.', '5', '51', 'E', null);
		$data[] = array('20.06.00.10', 'N', 'Afastamento para servir a outro órgão ou entidade', '5', '51', 'E', null);
		$data[] = array('20.06.01', 'S', 'LICENÇAS ESPECIAIS', null, null, null, null);
		$data[] = array('20.06.01.01', 'N', 'Licença para trato de interesse particular', '5', '95', 'E', null);
		$data[] = array('20.06.01.02', 'N', 'Licença por doença em pessoa da família', '5', '95', 'E', null);
		$data[] = array('20.06.01.03', 'N', 'Licença para acompanhar cônjuge', '5', '95', 'E', null);
		$data[] = array('20.06.01.04', 'N', 'Licença para curso de formação', '5', '95', 'E', null);
		$data[] = array('20.06.01.05', 'N', 'Licença para capacitação', '5', '95', 'E', null);
		$data[] = array('20.06.01.06', 'N', 'Licença para atividade política', '5', '95', 'E', null);
		$data[] = array('20.06.01.07', 'N', 'Licença para desempenho de mandato classista', '5', '95', 'E', null);
		$data[] = array('20.06.01.08', 'N', 'Licença-prêmio por assiduidade', '5', '95', 'E', null);
		$data[] = array('20.06.01.09', 'N', 'Licença para serviço militar', '5', '95', 'E', null);
		$data[] = array('20.06.02', 'S', 'CONCESSÃO PARA AUSENTAR-SE DO SERVIÇO ', null, null, null, null);
		$data[] = array('20.06.02.01', 'N', 'Ausência ao serviço por motivo de casamento', '5', '51', 'E', null);
		$data[] = array('20.06.02.02', 'N', 'Ausência ao serviço para doação de sangue', '5', '51', 'E', null);
		$data[] = array('20.06.02.03', 'N', 'Ausência ao serviço por motivo de falecimento de familiares ', '5', '51', 'E', null);
		$data[] = array('20.06.02.04', 'N', 'Horário especial', '5', '51', 'E', null);
		$data[] = array('20.06.02.05', 'N', 'Ausência ao serviço para alistamento eleitoral', '5', '51', 'E', null);
		$data[] = array('20.07', 'S', 'REGIME DISCIPLINAR', null, null, null, null);
		$data[] = array('20.07.00.01', 'N', 'Investigação preliminar', '3', '0', 'G', null);
		$data[] = array('20.07.00.02', 'N', 'Apuração de responsabilidades', '5', '0', 'G', null);
		$data[] = array('20.07.00.03', 'N', 'Sindicância', '5', '0', 'G', null);
		$data[] = array('20.07.00.04', 'N', 'Justificação de conduta', '3', '0', 'G', null);
		$data[] = array('20.07.00.05', 'N', 'Ação disciplinar (PAD)', '5', '0', 'G', null);
		$data[] = array('20.07.00.06', 'N', 'Penas disciplinares', '5', '0', 'G', null);
		$data[] = array('20.07.00.07', 'N', 'Representação', '3', '0', 'G', null);
		$data[] = array('20.07.00.08', 'N', 'Denúncia', '3', '0', 'G', null);
		$data[] = array('20.08', 'S', 'SEGURIDADE SOCIAL', null, null, null, null);
		$data[] = array('20.08.01', 'S', 'AUXÍLIOS', null, null, null, null);
		$data[] = array('20.08.01.01', 'N', 'Auxílio-natalidade', '100', '10', 'E', null);
		$data[] = array('20.08.01.02', 'N', 'Auxílio-funeral', '100', '10', 'E', null);
		$data[] = array('20.08.01.03', 'N', 'Auxílio-doença', '100', '10', 'E', null);
		$data[] = array('20.08.01.04', 'N', 'Auxílio-reclusão', '100', '10', 'E', null);
		$data[] = array('20.08.01.05', 'N', 'Auxílio-acidente ', '100', '10', 'E', null);
		$data[] = array('20.08.01.06', 'N', 'Auxílio-saúde', '100', '10', 'E', null);
		$data[] = array('20.08.01.07', 'N', 'Assistência pré-escolar', '100', '10', 'E', null);
		$data[] = array('20.08.01.08', 'N', 'Auxílio transporte', '100', '10', 'E', 'Vale-transporte');
		$data[] = array('20.08.01.09', 'N', 'Auxílio alimentação', '100', '10', 'E', null);
		$data[] = array('20.08.02', 'S', 'LICENÇAS ', null, null, null, null);
		$data[] = array('20.08.02.01', 'N', 'Atestado médico ', '0', '0', 'G', null);
		$data[] = array('20.08.02.02', 'N', 'Licença por acidente em serviço', '5', '95', 'E', null);
		$data[] = array('20.08.02.03', 'N', 'Licença à adotante', '5', '95', 'E', null);
		$data[] = array('20.08.02.04', 'N', 'Licença à gestante', '5', '95', 'E', null);
		$data[] = array('20.08.02.05', 'N', 'Licença-paternidade', '5', '95', 'E', null);
		$data[] = array('20.08.02.06', 'N', 'Licença para tratamento de saúde', '5', '95', 'E', 'LTS');
		$data[] = array('20.08.03', 'S', 'APOSENTADORIA', null, null, null, null);
		$data[] = array('20.08.03.01', 'N', 'Aposentadoria por invalidez', '100', '95', 'E', null);
		$data[] = array('20.08.03.02', 'N', 'Aposentadoria compulsória', '100', '95', 'E', null);
		$data[] = array('20.08.03.03', 'N', 'Aposentadoria voluntária', '100', '95', 'E', null);
		$data[] = array('20.08.03.04', 'N', 'Abono de permanência', '100', '95', 'E', null);
		$data[] = array('20.08.03.06', 'N', 'Reversão de aposentadoria', '100', '95', 'E', null);
		$data[] = array('20.08.04', 'S', 'PENSÃO', null, null, null, null);
		$data[] = array('20.08.04.01', 'N', 'Pensão estatutária (concessão, revisão e alteração)', '5', '95', 'E', 'Concessão, revisão, suspensão.');
		$data[] = array('20.08.05', 'S', 'ASSISTÊNCIA À SAÚDE', null, null, null, null);
		$data[] = array('20.08.05.01', 'N', 'Assistência à saúde', '100', '10', 'E', 'Médicos, dentistas, psicólogos, fonoaudiólogos, fisioterapeutas.');
		$data[] = array('20.08.05.02', 'N', 'Plano de saúde', '100', '0', 'E', 'Inclusão / exclusão');
		$data[] = array('20.08.05.03', 'N', 'Tratamento de saúde fora do domicílio', '5', '51', 'E', null);
		$data[] = array('20.08.05.04', 'N', 'Credenciamento de profissionais e de estabelecimentos hospitalares', '100', '0', 'E', 'Médicos, dentistas, psicólogos, fonoaudiólogos, fisioterapeutas.');
		$data[] = array('20.08.05.05', 'N', 'Prontuário médico ', '100', '0', 'G', null);
		$data[] = array('20.08.06', 'S', 'SERVIÇO SOCIAL', null, null, null, 'Podem ser juntados aos prontuários médicos.');
		$data[] = array('20.08.06.01', 'N', 'Acompanhamento psicossocial', '5', '0', 'G', null);
		$data[] = array('20.08.06.02', 'N', 'Acompanhamento social', '5', '0', 'G', null);
		$data[] = array('20.08.06.03', 'N', 'Assistência social', '5', '0', 'G', null);
		$data[] = array('20.09', 'S', 'SINDICATOS. ACORDOS. DISSÍDIOS. ASSOCIAÇÕES', null, null, null, null);
		$data[] = array('20.09.00.01', 'N', 'Sindicatos. Acordos. Dissídios', '5', '0', 'G', null);
		$data[] = array('20.09.00.02', 'N', 'Associações', '5', '0', 'G', null);
		$data[] = array('20.09.00.03', 'N', 'Movimentos reivindicatórios', '5', '0', 'G', 'Greves, paralisações.');
		$data[] = array('20.10', 'S', 'FREQUÊNCIA E FÉRIAS', null, null, null, null);
		$data[] = array('20.10.00.01', 'N', 'Afastamento por motivo de suspensão de contrato de trabalho (CLT)', '5', '51', 'E', null);
		$data[] = array('20.10.00.02', 'N', 'Convocação para o TRE', '5', '51', 'E', 'Prestação de serviço eleitoral');
		$data[] = array('20.10.00.03', 'N', 'Compensação de dias trabalhados para a justiça eleitoral', '5', '51', 'E', null);
		$data[] = array('20.10.00.04', 'N', 'Frequência', '5', '51', 'E', 'Livro de ponto, folha de ponto e boletim de frequência. Todos os documentos citados devem ser preservados pelo prazo previsto na tabela de 56 anos.');
		$data[] = array('20.10.00.05', 'N', 'Plantão', '2', '0', 'E', 'Convocação, compensação, controle.');
		$data[] = array('20.10.00.06', 'N', 'Recesso', '2', '0', 'E', 'Convocação, compensação, controle.');
		$data[] = array('20.10.00.07', 'N', 'Convocação para atuar em Turma Especial', '2', '0', 'E', null);
		$data[] = array('20.10.00.08', 'N', 'Convocação para atuar em Regime de Exceção - Mutirão', '2', '0', 'E', null);
		$data[] = array('20.10.00.09', 'N', 'Férias', '7', '0', 'E', 'Escala, marcação, adiamento, cancelamento.');
		$data[] = array('20.11', 'S', 'ESTÁGIOS', null, null, null, null);
		$data[] = array('20.11.00.01', 'N', 'Termo de compromisso de estágio', '100', '3', 'E', null);
		$data[] = array('20.11.00.02', 'N', 'Frequência de estagiários', '5', '0', 'E', null);
		$data[] = array('20.11.00.03', 'N', 'Pagamento da bolsa-estágio', '100', '10', 'E', null);
		$data[] = array('20.11.00.04', 'N', 'Declaração de estágio', '0', '0', 'E', null);
		$data[] = array('20.11.00.05', 'N', 'Seleção de estagiário', '3', '0', 'E', null);
		$data[] = array('20.11.00.06', 'N', 'Contratação e acompanhamento de estágio', '3', '0', 'E', null);
		$data[] = array('30', 'S', 'ADMINISTRAÇÃO DE BENS, MATERIAIS E SERVIÇOS    ', null, null, null, 'Caso gere ato (art. 12, § 2º, "a", "b" e "c", Res. 318/2014, CJF), este será de guarda permanente.');
		$data[] = array('30.01', 'S', 'ACOMPANHAMENTO DE LICITAÇÕES E CONTRATAÇÕES', null, null, null, null);
		$data[] = array('30.01.01', 'S', 'ACOMPANHAMENTO DE LICITAÇÕES', null, null, null, 'O processo deve ser classificado pelo código do assunto de que trata a licitação / contratação. ');
		$data[] = array('30.01.01.01', 'N', 'Coleta de dados e acompanhamento das licitações', '100', '0', 'E', 'Inclusive análise da conformidade jurídica de atos administrativos.');
		$data[] = array('30.01.01.02', 'N', 'Coleta de preços de serviços / materiais', '100', '0', 'E', null);
		$data[] = array('30.01.01.03', 'N', 'Licitação', '100', '0', 'E', 'Processo de contratação. ');
		$data[] = array('30.01.01.04', 'N', 'Julgamento de proposta ', '100', '0', 'E', null);
		$data[] = array('30.01.01.05', 'N', 'Aviso de julgamento de licitação, adjudicação e de homologação', '100', '0', 'E', null);
		$data[] = array('30.01.01.06', 'N', 'Recursos da decisão', '100', '0', 'E', 'Inclusive de licitação/pregão.');
		$data[] = array('30.01.01.07', 'N', 'Julgamento dos recursos', '100', '0', 'E', 'Inclusive de licitação/pregão.');
		$data[] = array('30.01.01.08', 'N', 'Entrega de edital às empresas interessadas', '100', '0', 'E', null);
		$data[] = array('30.01.01.09', 'N', 'Preços de itens ofertados', '100', '0', 'E', null);
		$data[] = array('30.01.01.10', 'N', 'Esclarecimentos sobre edital', '100', '0', 'E', null);
		$data[] = array('30.01.01.11', 'N', 'Cadastramento de fornecedores', '3', '0', 'E', null);
		$data[] = array('30.01.01.12', 'N', 'Capacidade técnica ', '0', '0', 'E', null);
		$data[] = array('30.01.01.13', 'N', 'Informação sobre produtos e serviços', '0', '0', 'E', null);
		$data[] = array('30.01.02', 'S', 'ACOMPANHAMENTO DE CONTRATAÇÕES', null, null, null, 'O processo deve ser classificado pelo código do assunto de que trata a licitação / contratação.');
		$data[] = array('30.01.02.01', 'N', 'Alteração / renegociação de cláusulas contratuais', '100', '0', 'E', 'Aditamento / prorrogação / repactuação contratual / reajuste de preços.');
		$data[] = array('30.01.02.02', 'N', 'Acompanhamento contratual ', '100', '0', 'E', 'Procedimentos diversos pertinentes à execução do contrato - assinatura, informação, solicitação, inclusive análise de conformidade jurídica em todas as fases contratuais até o arquivamento do processo.');
		$data[] = array('30.01.02.03', 'N', 'Análise e conferência de documento de cobrança.', '100', '0', 'E', null);
		$data[] = array('30.01.02.04', 'N', 'Planilha de reajuste de preço', '100', '0', 'E', null);
		$data[] = array('30.01.02.05', 'N', 'Documentos gerais relativos à cobrança / planilhas de custo, à regularidade fiscal / previdenciária', '100', '0', 'E', 'Folha de pagamento, FGTS, INSS.');
		$data[] = array('30.01.02.06', 'N', 'Avaliação de serviços prestados', '5', '0', 'E', null);
		$data[] = array('30.01.02.07', 'N', 'Nota fiscal ', '100', '0', 'E', null);
		$data[] = array('30.01.03', 'S', 'PENALIDADES CONTRATUAIS', null, null, null, null);
		$data[] = array('30.01.03.01', 'N', 'Análise, comunicação, solicitação de aplicação de sanções', '100', '0', 'E', null);
		$data[] = array('30.01.03.02', 'N', 'Rescisão Contratual', '100', '0', 'E', null);
		$data[] = array('30.02', 'S', 'OBRAS E SERVIÇOS', null, null, null, null);
		$data[] = array('30.02.01', 'S', 'OBRAS', null, null, null, 'Verificar projeto básico e/ou executivo. Se não enquadrado no inciso I, art. 6º da Lei nº 8.666/93 (obra), classificar no 30.02.02.00 ou 30.02.05.00.');
		$data[] = array('30.02.01.01', 'N', 'Projeto arquitetônico', '0', '0', 'G', 'O projeto original, o executivo e os complementares devem ser de guarda permanente.');
		$data[] = array('30.02.01.02', 'N', 'Projeto "as built" (conforme construído)', '100', '2', 'E', 'O documento original (aprovado) deverá ser de guarda permanente.');
		$data[] = array('30.02.01.08', 'N', 'Execução de obras', '100', '10', 'G', null);
		$data[] = array('30.02.01.09', 'N', 'Plano de obras', '100', '10', 'G', null);
		$data[] = array('30.02.01.10', 'N', 'Modernização de instalações', '100', '10', 'G', null);
		$data[] = array('30.02.02', 'S', 'CONTRATAÇÃO DE SERVIÇOS', null, null, null, 'Inclusive serviços de manutenção e conservação contratados.');
		$data[] = array('30.02.02.01', 'N', 'Contratação / pagamento de  serviços (exceto magistrado e servidor) ', '100', '10', 'E', null);
		$data[] = array('30.02.05', 'S', 'SERVIÇOS DE MANUTENÇÃO E CONSERVAÇÃO EXECUTADOS NO ÓRGÃO', null, null, null, 'Elevador, ar condicionado, subestações e gerador, limpeza, vistoria.');
		$data[] = array('30.02.05.06', 'N', 'Manutenção', '100', '10', 'E', null);
		$data[] = array('30.02.05.07', 'N', 'Conservação ', '100', '10', 'E', null);
		$data[] = array('30.02.07', 'S', 'MUDANÇAS', null, null, null, null);
		$data[] = array('30.02.07.01', 'N', 'Para outros imóveis', '100', '10', 'E', null);
		$data[] = array('30.02.07.02', 'N', 'Dentro do mesmo imóvel', '100', '10', 'E', null);
		$data[] = array('30.03', 'S', 'SEGURANÇA', null, null, null, 'Os documentos que não envolvem pagamentos serão eliminados após 2 anos.');
		$data[] = array('30.03.00.01', 'N', 'Transporte para Magistrados/servidores', '2', '0', 'E', null);
		$data[] = array('30.03.00.02', 'N', 'Contratação de serviços de vigilância', '100', '10', 'E', null);
		$data[] = array('30.03.00.03', 'N', 'Registro de ocorrências  / ronda', '2', '0', 'E', 'Caso ocorra "sinistro", abrir processo de sindicância.');
		$data[] = array('30.03.00.04', 'N', 'Controle de chaves em geral ', '2', '0', 'E', null);
		$data[] = array('30.03.00.05', 'N', 'Porte de arma de fogo', '100', '10', 'E', null);
		$data[] = array('30.03.00.06', 'N', 'Controle de entrada/saída de veículos de garagem', '2', '0', 'E', null);
		$data[] = array('30.03.00.07', 'N', 'Utilização de vaga na garagem', '2', '0', 'E', null);
		$data[] = array('30.03.00.08', 'N', 'Sinistro', '100', '0', 'G', null);
		$data[] = array('30.03.00.09', 'N', 'Inspeções periódicas de prevenção de incêndio', '4', '0', 'E', null);
		$data[] = array('30.03.00.10', 'N', 'Contratação de seguros', '100', '10', 'E', null);
		$data[] = array('30.03.01', 'S', 'USO DE DEPENDÊNCIAS ', null, null, null, null);
		$data[] = array('30.03.01.01', 'N', 'Entrada/saída de pessoas - controle de portaria', '4', '0', 'E', null);
		$data[] = array('30.03.01.02', 'N', 'Entrada fora do horário de expediente', '4', '0', 'E', null);
		$data[] = array('30.03.01.03', 'N', 'Uso das dependências (Controle)', '2', '0', 'E', null);
		$data[] = array('30.03.01.04', 'N', 'Utilização das dependências para outros fins', '4', '0', 'E', null);
		$data[] = array('30.03.01.05', 'N', 'Uso extraordinário de dependências (acionamento de sistemas, ar condicionado e outros) ', '2', '0', 'E', null);
		$data[] = array('30.04', 'S', 'ADMINISTRAÇÃO DE BENS MÓVEIS ', null, null, null, null);
		$data[] = array('30.04.01', 'S', 'EXTRAVIO. ROUBO. DESAPARECIMENTO DE MATERIAL', null, null, null, null);
		$data[] = array('30.04.01.01', 'N', 'Comunicação de ocorrência ', '2', '0', 'E', null);
		$data[] = array('30.04.02', 'S', 'TRANSPORTE  E MOVIMENTAÇÃO DE MATERIAL', null, null, null, null);
		$data[] = array('30.04.02.01', 'N', 'Controle de movimentação de material', '2', '0', 'E', null);
		$data[] = array('30.04.02.02', 'N', 'Recolhimento de material ao depósito', '2', '0', 'E', null);
		$data[] = array('30.04.02.03', 'N', 'Relatório de movimentação de bens móveis (RMBM)  ', '100', '10', 'E', null);
		$data[] = array('30.04.03', 'S', 'ADMINISTRAÇÃO E USO DE VEÍCULOS', null, null, null, null);
		$data[] = array('30.04.03.01', 'N', 'Controle de combustível', '1', '0', 'E', 'Requisição, fornecimento.');
		$data[] = array('30.04.03.02', 'N', 'Manutenção e conservação de veículos', '100', '10', 'E', null);
		$data[] = array('30.04.03.03', 'N', 'Licenciamentos, acidentes, infrações, multas e pagamentos', '100', '10', 'E', 'Pode ser gerado um processo. Acidentes envolvendo servidor, classificar como processo de apuração de responsabilidade, sob o código 20.07.00.02.');
		$data[] = array('30.04.03.04', 'N', 'Controle de uso do veículo', '2', '0', 'E', null);
		$data[] = array('30.04.03.05', 'N', 'Licenciamento de Veículos', '2', '0', 'E', 'documento IPVA, DPVAT');
		$data[] = array('30.04.03.06', 'N', 'Aquisição de veículos', '100', '10', 'E', null);
		$data[] = array('30.04.03.07', 'N', 'Plano anual de aquisição de veículos', '100', '10', 'G', null);
		$data[] = array('30.04.04', 'S', ' MATERIAL PERMANENTE', null, null, null, null);
		$data[] = array('30.04.04.01', 'N', 'Empréstimo de material permanente  ', '100', '0', 'E', 'Não se aplica a empréstimo de acervo bibliográfico, que deve ser classificado na 40.01.01.03.');
		$data[] = array('30.04.04.02', 'N', 'Tombamento ', '100', '0', 'E', null);
		$data[] = array('30.04.04.03', 'N', 'Responsabilidade sobre guarda de material permanente', '3', '0', 'E', null);
		$data[] = array('30.04.04.04', 'N', 'Solicitação de bem por transferência  ', '3', '0', 'E', null);
		$data[] = array('30.04.04.05', 'N', 'Enquadramento contábil', '100', '0', 'E', null);
		$data[] = array('30.04.04.06', 'N', 'Reavaliação, redução a valor recuperável, depreciação, amortização e exaustão', '100', '0', 'E', null);
		$data[] = array('30.04.05', 'S', ' AQUISIÇÃO DE MATERIAL PERMANENTE', null, null, null, 'Os documentos referentes à material não adquirido deverão ser eliminados após dois anos no arquivo corrente.');
		$data[] = array('30.04.05.01', 'N', 'Aquisição de material permanente por compra / pagamento', '100', '100', 'E', 'Se o bem se deteriorar antes do julgamento das contas, utilizar o prazo Julgamento TCU mais 10 anos.  ');
		$data[] = array('30.04.05.02', 'N', 'Aquisição de material permanente  por cessão', '5', '100', 'E', null);
		$data[] = array('30.04.05.03', 'N', 'Aquisição de material permanente  por doação ', '5', '100', 'E', null);
		$data[] = array('30.04.05.04', 'N', 'Aquisição de material permanente  por permuta ', '5', '100', 'E', null);
		$data[] = array('30.04.05.05', 'N', 'Aquisição de material permanente por dação  ', '5', '100', 'E', null);
		$data[] = array('30.04.06', 'S', 'ALUGUEL. COMODATO. LEASING', null, null, null, null);
		$data[] = array('30.04.06.01', 'N', 'Contratação / pagamento de aluguel, comodato, leasing de material permanente', '100', '10', 'E', null);
		$data[] = array('30.04.07', 'S', 'INVENTÁRIO DE MATERIAL PERMANENTE', null, null, null, null);
		$data[] = array('30.04.07.01', 'N', 'Inventário anual de material permanente', '100', '0', 'G', null);
		$data[] = array('30.04.08', 'S', 'DESFAZIMENTO DE MATERIAL PERMANENTE', null, null, null, null);
		$data[] = array('30.04.08.01', 'N', 'Cessão de material permanente  ', '5', '0', 'G', null);
		$data[] = array('30.04.08.02', 'N', 'Alienação por doação de material permanente  ', '5', '0', 'G', null);
		$data[] = array('30.04.08.03', 'N', 'Alienação por permuta de material permanente  ', '5', '0', 'G', null);
		$data[] = array('30.04.08.04', 'N', 'Alienação de material permanente por dação em pagamento ', '5', '0', 'G', null);
		$data[] = array('30.04.08.05', 'N', 'Alienação por venda de material permanente', '5', '0', 'G', null);
		$data[] = array('30.04.08.06', 'N', 'Inutilização de material permanente', '5', '0', 'G', null);
		$data[] = array('30.04.09', 'S', 'MATERIAL DE CONSUMO ', null, null, null, 'Os documentos referentes à material não adquirido deverão ser eliminados após dois anos no arquivo corrente.');
		$data[] = array('30.04.09.01', 'N', 'Aquisição de material de consumo por compra / pagamento', '100', '10', 'E', null);
		$data[] = array('30.04.09.02', 'N', 'Aquisição de material de consumo por cessão  ', '100', '10', 'E', null);
		$data[] = array('30.04.09.03', 'N', 'Aquisição de material de consumo por doação ', '100', '10', 'E', null);
		$data[] = array('30.04.09.04', 'N', 'Aquisição de material de consumo por permuta', '100', '10', 'E', null);
		$data[] = array('30.04.09.05', 'N', 'Aquisição de material de consumo por dação em pagamento ', '100', '10', 'E', null);
		$data[] = array('30.04.09.06', 'N', 'Produção interna de material de consumo', '100', '10', 'E', null);
		$data[] = array('30.04.09.07', 'N', 'Requisição / entrega de material ', '1', '0', 'E', 'Dados transferidos para a estatística.');
		$data[] = array('30.04.09.08', 'N', 'Transferência de material de consumo  ', '100', '10', 'E', null);
		$data[] = array('30.04.10', 'S', 'INVENTÁRIO DE MATERIAL DE CONSUMO', null, null, null, null);
		$data[] = array('30.04.10.01', 'N', 'Controle de estoque e almoxarifado ', '2', '0', 'E', null);
		$data[] = array('30.04.10.02', 'N', 'Inventário anual de material de consumo', '100', '10', 'E', null);
		$data[] = array('30.04.11', 'S', 'DESFAZIMENTO DE MATERIAL DE CONSUMO', null, null, null, null);
		$data[] = array('30.04.11.01', 'N', 'Cessão de material de consumo', '5', '0', 'G', null);
		$data[] = array('30.04.11.02', 'N', 'Alienação de material consumo por doação', '5', '0', 'G', null);
		$data[] = array('30.04.11.03', 'N', 'Alienação de material consumo por permuta', '5', '0', 'G', null);
		$data[] = array('30.04.11.04', 'N', 'Alienação de material consumo  por dação em pagamento ', '5', '0', 'G', null);
		$data[] = array('30.04.11.05', 'N', 'Alienação de material consumo por venda', '5', '0', 'G', null);
		$data[] = array('30.04.11.06', 'N', 'Inutilização de material consumo', '5', '0', 'G', null);
		$data[] = array('30.05', 'S', 'BENS IMÓVEIS', null, null, null, null);
		$data[] = array('30.05.01', 'S', 'AQUISIÇÃO DE IMÓVEIS', null, null, null, 'O processo de aquisição de imóveis é feito pela Secretaria de Patrimônio da União.');
		$data[] = array('30.05.01.01', 'N', 'Aquisição de imóveis por compra', '100', '0', 'G', null);
		$data[] = array('30.05.01.02', 'N', 'Aquisição de imóveis por cessão', '100', '0', 'G', null);
		$data[] = array('30.05.01.03', 'N', 'Aquisição de imóveis por doação', '100', '0', 'G', null);
		$data[] = array('30.05.01.04', 'N', 'Aquisição de imóveis por permuta', '100', '0', 'G', null);
		$data[] = array('30.05.02', 'S', 'ADMINISTRAÇÃO DE IMÓVEIS   ', null, null, null, null);
		$data[] = array('30.05.02.01', 'N', 'Aluguel de imóveis', '100', '10', 'E', 'Caso a vigência do contrato de locação seja menor que o julgamento do TCU, utilizar o prazo Julgamento TCU mais 10 anos.');
		$data[] = array('30.05.02.02', 'N', 'Aquisição, controle e administração da ocupação de imóveis funcionais', '100', '0', 'G', null);
		$data[] = array('30.05.02.03', 'N', 'Ocupação de imóveis funcionais próprios da União, estados e municípios ', '100', '0', 'G', null);
		$data[] = array('30.05.03', 'S', 'DESPESAS CONDOMINIAIS', null, null, null, null);
		$data[] = array('30.05.03.01', 'N', 'Contas de água e esgoto', '100', '10', 'E', null);
		$data[] = array('30.05.03.02', 'N', 'Contas de gás', '100', '10', 'E', null);
		$data[] = array('30.05.03.03', 'N', 'Contas de energia elétrica', '100', '10', 'E', null);
		$data[] = array('30.05.03.04', 'N', 'Conta de condomínio', '100', '10', 'E', null);
		$data[] = array('30.05.04', 'S', 'ALIENAÇÃO DE IMÓVEIS', null, null, null, 'Para transações que envolvam pagamento de despesas pendentes utilizar prazos dos documentos financeiros (Julgamento TCU mais 10 anos e Guarda Permanente).');
		$data[] = array('30.05.04.01', 'N', 'Alienação de imóveis por venda', '100', '0', 'G', null);
		$data[] = array('30.05.04.02', 'N', 'Alienação de imóveis por cessão', '100', '0', 'G', null);
		$data[] = array('30.05.04.03', 'N', 'Alienação de imóveis por doação', '100', '0', 'G', null);
		$data[] = array('30.05.04.04', 'N', 'Alienação de imóveis por permuta', '100', '0', 'G', null);
		$data[] = array('30.05.05', 'S', 'DESAPROPRIAÇÃO. REINTEGRAÇÃO DE POSSE. REIVINDICAÇÃO DE DOMÍNIO. TOMBAMENTO ', null, null, null, null);
		$data[] = array('30.05.05.01', 'N', 'Desapropriação, reintegração de posse, reivindicação de domínio e de tombamento de imóveis.', '100', '10', 'G', null);
		$data[] = array('30.05.06', 'S', 'INVENTÁRIO DE BENS IMÓVEIS', null, null, null, null);
		$data[] = array('30.05.06.01', 'N', 'Inventário de bens imóveis', '100', '0', 'G', 'Inclusive imóveis próprios');
		$data[] = array('40', 'S', 'GESTÃO DA DOCUMENTAÇÃO E INFORMAÇÃO', null, null, null, 'Caso gere ato (art. 12, § 2º, "a", "b" e "c", Res. 318/2014, CJF), este será de guarda permanente.');
		$data[] = array('40.01', 'S', 'POLÍTICAS DE ACERVO', null, null, null, null);
		$data[] = array('40.01.00.01', 'N', 'Política de Segurança da informação', '2', '0', 'G', null);
		$data[] = array('40.01.00.02', 'N', 'Controle terminológico', '100', '0', 'G', 'Uso pelo CJF');
		$data[] = array('40.01.00.03', 'N', 'Classificação da informação ', '5', '0', 'G', null);
		$data[] = array('40.01.01', 'S', 'POLÍTICA DE ACESSO À INFORMAÇÃO ', null, null, null, 'Dados transferidos para estatística e para relatórios.');
		$data[] = array('40.01.01.01', 'N', 'Política de acesso aos documentos e informações', '5', '0', 'G', null);
		$data[] = array('40.01.01.02', 'N', 'Solicitação de pesquisas e informações', '1', '0', 'E', null);
		$data[] = array('40.01.01.03', 'N', 'Empréstimo de acervo ', '0', '0', 'E', null);
		$data[] = array('40.01.01.04', 'N', 'Desarquivamento de documentos/processos administrativos', '2', '3', 'E', null);
		$data[] = array('40.01.01.05', 'N', 'Serviço de informação ao cidadão', '2', '0', 'E', null);
		$data[] = array('40.01.02', 'S', 'CONSERVAÇÃO E RESTAURAÇÃO DO ACERVO', null, null, null, null);
		$data[] = array('40.01.02.01', 'N', 'Conservação e restauração do acervo', '3', '0', 'G', 'Inclusive relatórios de condições ambientais.');
		$data[] = array('40.02', 'S', 'DOCUMENTAÇÃO BIBLIOGRÁFICA', null, null, null, null);
		$data[] = array('40.02.00.01', 'N', 'Seleção de material bibliográfico', '2', '0', 'E', 'Os documentos da aquisição serão classificados na classe 30.');
		$data[] = array('40.02.01', 'S', 'PROCESSAMENTO TÉCNICO', null, null, null, 'Registro, catalogação, classificação, indexação.');
		$data[] = array('40.02.01.01', 'N', 'Ficha de descrição', '100', '0', 'E', null);
		$data[] = array('40.02.02', 'S', 'BIBLIOTECA VIRTUAL', null, null, null, null);
		$data[] = array('40.02.02.01', 'N', 'Biblioteca virtual', '2', '0', 'G', 'Planejamento, desenvolvimento, gerenciamento, convênio.');
		$data[] = array('40.03', 'S', 'SISTEMA DE ARQUIVOS E CONTROLE DE DOCUMENTOS', null, null, null, null);
		$data[] = array('40.03.01', 'S', 'PRODUÇÃO DE DOCUMENTOS : LEVANTAMENTO, DIAGNÓSTICO E CONTROLE DE FLUXO', null, null, null, null);
		$data[] = array('40.03.01.01', 'N', 'Estudo sobre produção de documentos', '5', '5', 'G', null);
		$data[] = array('40.03.02', 'S', 'PROTOCOLO E ARQUIVAMENTO: RECEPÇÃO,  AUTUAÇÃO, TRAMITAÇÃO E EXPEDIÇÃO DE DOCUMENTOS e PROCESSOS ADMINISTRATIVOS', null, null, null, null);
		$data[] = array('40.03.02.01', 'N', 'Tramitação de documentos e de processos administrativos', '2', '0', 'E', 'A tramitação pode se dar por meio de ofícios, guias,  livros, etc. O documento encaminhado deve ser classificado pelo assunto específico.');
		$data[] = array('40.03.02.02', 'N', 'Autuação de  processo administrativo', '3', '0', 'E', 'Livros ou fichas usadas anteriormente a existência de sistemas informatizados são passíveis de avaliação histórica. ');
		$data[] = array('40.03.03', 'S', 'CLASSIFICAÇÃO E DESTINAÇÃO', null, null, null, null);
		$data[] = array('40.03.03.01', 'N', 'Instrumentos do programa de gestão documental ', '100', '0', 'G', 'Uso exclusivo do CJF (gestão das tabelas TUA, TUC, TUMP, PCTT, SIGLAS JUDICIÁRIAS).');
		$data[] = array('40.03.04', 'S', 'ANÁLISE. AVALIAÇÃO. SELEÇÃO DOCUMENTAL', null, null, null, null);
		$data[] = array('40.03.04.01', 'N', 'Descarte de documentos / processos ', '5', '0', 'G', null);
		$data[] = array('40.03.04.02', 'N', 'Microfilmagem', '5', '0', 'G', 'Projetos e estudos para substituição de suporte e preservação.');
		$data[] = array('40.03.04.03', 'N', 'Digitalização', '5', '0', 'G', 'Projetos e estudos para substituição de suporte e preservação.');
		$data[] = array('40.04', 'S', 'MEMÓRIA INSTITUCIONAL', null, null, null, null);
		$data[] = array('40.04.00.01', 'N', 'Peças museológicas', '0', '0', 'E', null);
		$data[] = array('40.04.00.02', 'N', 'Registro audiovisual etc.', '2', '0', 'G', null);
		$data[] = array('40.04.00.03', 'N', 'História oral', '100', '0', 'G', null);
		$data[] = array('40.04.00.04', 'N', 'Registros de memória institucional  ', '2', '0', 'G', null);
		$data[] = array('40.05', 'S', 'JURISPRUDÊNCIA', null, null, null, null);
		$data[] = array('40.05.01', 'S', 'ACÓRDÃOS. ANÁLISE. DESCRIÇÃO. INDEXAÇÃO. PESQUISA', null, null, null, 'A seleção de acórdãos para publicação será classificada no código 90.07.00.01.');
		$data[] = array('40.05.01.01', 'N', 'Análise e Indexação de jurisprudência ', '100', '0', 'E', null);
		$data[] = array('40.05.01.04', 'N', 'Pesquisa de jurisprudência', '1', '0', 'E', 'Dados transferidos para a estatística.');
		$data[] = array('40.05.02', 'S', 'SÚMULA. ENUNCIADO ', null, null, null, null);
		$data[] = array('40.05.02.01', 'N', 'Súmula. Enunciado ', '2', '0', 'G', null);
		$data[] = array('40.05.03', 'S', 'REPOSITÓRIO OFICIAL', null, null, null, null);
		$data[] = array('40.05.03.01', 'N', 'Remessa de publicações aos repositórios oficiais', '1', '0', 'E', null);
		$data[] = array('40.05.03.02', 'N', 'Repositório oficial', '2', '0', 'E', null);
		$data[] = array('40.06', 'S', 'EDITORAÇÃO E PUBLICAÇÃO', null, null, null, null);
		$data[] = array('40.06.01', 'S', 'PUBLICAÇÕES OFICIAIS', null, null, null, null);
		$data[] = array('40.06.01.01', 'N', 'Publicações em veículos externos ', '1', '0', 'E', 'As publicações externas ao órgão poderão integrar o acervo bibliográfico.');
		$data[] = array('40.06.01.02', 'N', 'Material para publicação', '100', '0', 'E', null);
		$data[] = array('40.06.01.03', 'N', 'Publicações do órgão', '0', '0', 'G', 'As publicações do órgão, revistas, boletins, informativos, relatórios, discursos, etc., poderão integrar o acervo bibliográfico. ');
		$data[] = array('40.06.02', 'S', 'PROJETO   EDITORIAL', null, null, null, null);
		$data[] = array('40.06.02.01', 'N', 'Projeto Editorial ', '2', '0', 'G', null);
		$data[] = array('40.06.02.03', 'N', 'Pauta', '2', '0', 'E', null);
		$data[] = array('40.06.02.04', 'N', 'Autorização do autor', '0', '0', 'G', null);
		$data[] = array('40.06.02.05', 'N', 'Artigo original do autor', '2', '50', 'E', 'Lei nº 9.610, de 19/02/98.');
		$data[] = array('40.06.02.06', 'N', 'ISBN / ISSN', '5', '0', 'G', null);
		$data[] = array('40.06.03', 'S', 'SERVIÇOS GRÁFICOS E REPROGRÁFICOS', null, null, null, null);
		$data[] = array('40.06.03.01', 'N', 'Serviços gráficos, diagramação, impressão, encadernação', '2', '2', 'E', null);
		$data[] = array('40.06.03.02', 'N', 'Controle de serviços reprográficos', '2', '0', 'E', 'Pode gerar processo.');
		$data[] = array('40.06.03.03', 'N', 'Requisição de cópia reprográfica', '2', '0', 'E', null);
		$data[] = array('40.06.03.04', 'N', 'Projetos de programação de identidade visual - logotipos, símbolos, ícones, personagens, etc. ', '2', '0', 'G', 'Criação, aprovação, revisão. Inclusive em meio eletrônico ');
		$data[] = array('40.06.04', 'S', 'DISTRIBUIÇÃO. PROMOÇÃO. DIVULGAÇÃO', null, null, null, null);
		$data[] = array('40.06.04.01', 'N', 'Material de distribuição, promoção e divulgação - folhetos, cartazes, folders, etc. ', '0', '0', 'G', 'Divulgar eventos, cursos, etc. Inclusive em meio eletrônico. Um exemplar deve ser juntado ao dossiê do evento/curso.');
		$data[] = array('40.06.05', 'S', 'ADMINISTRAÇÃO DE PORTAIS', null, null, null, null);
		$data[] = array('40.06.05.01', 'N', 'Desenvolvimento de página eletrônica', '5', '5', 'G', null);
		$data[] = array('40.06.05.02', 'N', 'Manutenção evolutiva, corretiva, adaptativa', '5', '5', 'G', null);
		$data[] = array('40.06.05.03', 'N', 'Memória dos layouts e funcionalidades', '5', '5', 'G', null);
		$data[] = array('40.07', 'S', 'TECNOLOGIA DA INFORMAÇÃO', null, null, null, null);
		$data[] = array('40.07.01', 'S', 'DESENVOLVIMENTO DE SISTEMA', null, null, null, null);
		$data[] = array('40.07.01.01', 'N', 'Implantação de sistemas', '5', '0', 'G', null);
		$data[] = array('40.07.01.02', 'N', 'Desenvolvimento de sistemas', '5', '0', 'G', null);
		$data[] = array('40.07.01.03', 'N', 'Manutenção evolutiva/corretiva/adaptativa ', '5', '0', 'G', 'Inclusive para customização de sistemas.');
		$data[] = array('40.07.01.04', 'N', 'Memória dos leiautes e funcionalidades dos diversos sistemas informatizados', '2', '0', 'G', 'Inclusive páginas de internet e intranet.');
		$data[] = array('40.07.01.05', 'N', 'Metodologia de desenvolvimento de sistemas', '5', '0', 'G', null);
		$data[] = array('40.07.01.06', 'N', 'Documentos do sistema', '5', '0', 'G', 'Diagrama de fluxo de dados/ Modelo de entidade / relacionamento/ Dicionário de dados.');
		$data[] = array('40.07.01.07', 'N', 'Manuais de uso', '2', '0', 'G', 'Os manuais dos sistemas criados pela instituição. ');
		$data[] = array('40.07.01.08', 'N', 'Análises para utilização de softwares', '5', '0', 'E', 'Documentos relativos à aquisição de software deverão ser classificados no código 30.04.09.01.');
		$data[] = array('40.07.02', 'S', 'ADMINISTRAÇÃO DE REDE', null, null, null, null);
		$data[] = array('40.07.02.01', 'N', 'Administração de rede', '2', '0', 'E', null);
		$data[] = array('40.07.02.02', 'N', 'Cópia de segurança diária', '100', '0', 'E', null);
		$data[] = array('40.07.02.03', 'N', 'Cópia de segurança semanal', '100', '0', 'E', null);
		$data[] = array('40.07.02.04', 'N', 'Cópia de segurança mensal', '1', '0', 'E', null);
		$data[] = array('40.07.03', 'S', 'SUPORTE TÉCNICO ', null, null, null, null);
		$data[] = array('40.07.03.01', 'N', 'Atendimento e suporte ao usuário', '2', '0', 'E', 'Dados transferidos para a estatística.');
		$data[] = array('40.07.03.03', 'N', 'Equipamentos de informática', '2', '0', 'E', 'Inclusive instalação, manutenção, conservação.');
		$data[] = array('40.07.04', 'S', 'TECNOLOGIA', null, null, null, null);
		$data[] = array('40.07.04.01', 'N', 'Infraestrutura de Informática', '2', '0', 'E', null);
		$data[] = array('40.07.04.02', 'N', 'Itens de Configuração', '5', '0', 'E', null);
		$data[] = array('40.07.05', 'S', 'CERTIFICAÇÃO DIGITAL', null, null, null, null);
		$data[] = array('40.07.05.01', 'N', 'Credenciamento de Autoridade Certificadora (AC)', '2', '0', 'G', null);
		$data[] = array('40.07.05.02', 'N', 'Credenciamento de Autoridade de Registro (AR)', '2', '0', 'G', null);
		$data[] = array('40.07.05.03', 'N', 'Credenciamento de Posto Provisório', '2', '0', 'G', null);
		$data[] = array('40.07.05.04', 'N', 'Credenciamento de Instalação Técnica', '2', '0', 'G', null);
		$data[] = array('40.07.05.05', 'N', 'Cadastramento de Certificado Digital', '3', '0', 'E', 'Procedimentos para cadastramento de usuários junto à AC-JUS.');
		$data[] = array('40.07.06', 'S', 'SEGURANÇA DA INFORMAÇÃO', null, null, null, null);
		$data[] = array('40.07.06.01', 'N', 'Resposta a incidentes', '2', '0', 'G', null);
		$data[] = array('40.07.06.02', 'N', 'Auditoria de TI', '2', '0', 'G', null);
		$data[] = array('40.07.06.03', 'N', 'Acesso aos sistemas e uso de recursos de TI', '5', '0', 'E', 'Solicitação.');
		$data[] = array('40.07.06.04', 'N', 'Análise de risco', '2', '0', 'G', null);
		$data[] = array('40.08', 'S', 'SERVIÇOS DE TRANSMISSÃO DE DADOS, VOZ E IMAGEM', null, null, null, null);
		$data[] = array('40.08.00.01', 'N', 'Serviço de transmissão de dados, voz e imagem', '100', '10', 'E', null);
		$data[] = array('40.08.00.04', 'N', 'Serviço de radiofrequência', '5', '0', 'G', null);
		$data[] = array('50', 'S', 'VAGO', null, null, null, null);
		$data[] = array('60', 'S', 'VAGO', null, null, null, null);
		$data[] = array('70', 'S', 'VAGO', null, null, null, null);
		$data[] = array('80', 'S', 'VAGO', null, null, null, null);
		$data[] = array('90', 'S', 'ATIVIDADES FORENSES', null, null, null, 'Caso gere ato (art. 12, § 2º, "a", "b" e "c", Res. 318/2014, CJF),  este será de guarda permanente.');
		$data[] = array('90.00.00.01', 'N', 'Advogados', '100', '0', 'E', 'Ações envolvendo cadastramento, alteração, suspensão, impedimento');
		$data[] = array('90.00.00.02', 'N', 'Adesão a serviços processuais', '3', '0', 'E', 'Inclusive advogados.');
		$data[] = array('90.00.00.03', 'N', 'Procuradores da União e Autárquico', '2', '0', 'E', 'Credenciamento');
		$data[] = array('90.00.00.04', 'N', 'Cadastramento de jurado, perito, tradutor, intérprete, advogado voluntário e defensor dativo', '2', '0', 'E', null);
		$data[] = array('90.00.00.05', 'N', 'Honorários de perito, tradutor, intérprete, advogado voluntário e defensor dativo ', '100', '10', 'E', null);
		$data[] = array('90.01', 'S', 'PROTOCOLO JUDICIÁRIO', null, null, null, null);
		$data[] = array('90.01.00.01', 'N', 'Petições protocoladas e outros documentos judiciais', '3', '0', 'E', 'Registro, pré-cadastramento, cadastramento');
		$data[] = array('90.01.01', 'S', 'REGISTRO E AUTUAÇÃO DE PROCESSOS', null, null, null, null);
		$data[] = array('90.01.01.01', 'N', 'Registro de processos judiciais - tombo', '5', '0', 'G', null);
		$data[] = array('90.01.02', 'S', 'DISTRIBUIÇÃO PROCESSUAL', null, null, null, null);
		$data[] = array('90.01.02.01', 'N', 'Distribuição de processos', '2', '0', 'E', 'Inclusive prevenção, suspeição, impedimento');
		$data[] = array('90.01.02.02', 'N', 'Escala de distribuição', '2', '0', 'E', null);
		$data[] = array('90.01.02.03', 'N', 'Análise de pedidos de certidão (relação de prováveis)', '5', '0', 'E', null);
		$data[] = array('90.02', 'S', 'TRAMITAÇÃO, PROCESSAMENTO, BAIXA E ARQUIVAMENTO', null, null, null, null);
		$data[] = array('90.02.00.01', 'N', 'Providências / informações sobre o andamento processual', '2', '0', 'E', 'Diligências, antecedentes, devolução de cartas e processos, inclusão em pauta, perito, cálculo judicial. ');
		$data[] = array('90.02.00.02', 'N', 'Cargas de processos judiciais', '100', '0', 'E', 'Livro ou relação "on-line" de carga para advogado, perito, MPU, etc.');
		$data[] = array('90.02.00.03', 'N', 'Entrega definitiva de autos', '3', '0', 'G', 'Este código refere-se à entrega para as partes. Classificar sob o código 40.03.04.01 a entrega de autos findos.');
		$data[] = array('90.02.00.04', 'N', 'Remessa Externa (entre os distintos órgãos)', '2', '0', 'E', 'Inclusive baixa por declinação de competência.');
		$data[] = array('90.02.00.05', 'N', 'Remessa Interna (entre setores do mesmo órgão)', '2', '0', 'E', 'Inclusive guia de remessa ao arquivo. É desnecessária a impressão, basta o registro eletrônico.');
		$data[] = array('90.02.00.06', 'N', 'Comunicação de decisões, despachos, julgamentos, etc.', '3', '0', 'E', 'Tanto expedida, quanto recebida.');
		$data[] = array('90.02.00.07', 'N', 'Registro de Guia de Recolhimento (Criminal)', '5', '10', 'E', null);
		$data[] = array('90.02.00.08', 'N', 'Registro de Livramento Condicional', '5', '10', 'E', null);
		$data[] = array('90.02.00.09', 'N', 'Rol dos Culpados', '100', '0', 'G', 'Registro no CJF, no Rol Nacional de Culpados. Livro ou relação "on-line".');
		$data[] = array('90.02.00.10', 'N', 'Fiança', '5', '10', 'E', 'Inclusive termo, ofício.');
		$data[] = array('90.02.00.11', 'N', 'Termo de suspensão de processo ', '100', '0', 'E', null);
		$data[] = array('90.02.00.12', 'N', 'Bens Apreendidos', '5', '0', 'G', 'Termo de apreensão, termo de doação ');
		$data[] = array('90.02.00.13', 'N', 'Cumprimento de Diligências', '100', '0', 'E', 'Inclusive controle de entrega de mandados aos Oficiais de Justiça.');
		$data[] = array('90.02.00.14', 'N', 'Editais', '2', '0', 'E', null);
		$data[] = array('90.02.00.15', 'N', 'Cartas - De Ordem, Precatória, Rogatória', '2', '0', 'E', null);
		$data[] = array('90.02.00.16', 'N', 'Mandados', '2', '0', 'E', null);
		$data[] = array('90.02.00.17', 'N', 'Certidões', '0', '0', 'E', 'Certidão narratória ou objeto e pé, negativa, CDA etc.');
		$data[] = array('90.02.00.18', 'N', 'Petições', '100', '0', 'E', 'Petição de cunho eminentemente processual.');
		$data[] = array('90.02.00.19', 'N', 'Petições não passíveis de juntada aos autos', '3', '0', 'E', null);
		$data[] = array('90.02.00.20', 'N', 'Alvarás', '3', '5', 'E', null);
    $data[] = array('90.02.00.21', 'N', 'Compromisso de Liberdade Provisória sem fiança', '3', '5', 'E', 'Registro, termo.');
		$data[] = array('90.02.01', 'S', 'JULGAMENTO', null, null, null, null);
		$data[] = array('90.02.01.01', 'N', 'Carta de Sentença', '2', '0', 'E', null);
		$data[] = array('90.02.01.02', 'N', 'Livro de Sentença - termo', '100', '0', 'G', null);
		$data[] = array('90.02.01.03', 'N', 'Ata de julgamento', '1', '0', 'G', 'Utilizadas para restauração de autos.');
		$data[] = array('90.02.01.04', 'N', 'Pauta de julgamento', '3', '0', 'E', null);
		$data[] = array('90.02.01.05', 'N', 'Memorial', '100', '0', 'E', null);
		$data[] = array('90.02.01.06', 'N', 'Livro de audiência', '100', '0', 'G', null);
		$data[] = array('90.02.01.07', 'N', 'Registro de audiência / sessão de julgamento', '3', '0', 'G', 'Inclusive ata livro de transcrição de depoimentos, notas taquigráficas, registros em audio, vídeo e meios digitais.');
    $data[] = array('90.02.01.08', 'N', 'Suspensão Condicional do Processo', '100', '10', 'E', 'Registro, termo.');
		$data[] = array('90.03', 'S', 'EXECUÇÃO', null, null, null, null);
		$data[] = array('90.03.00.01', 'N', 'Penhora', '100', '0', 'E', null);
		$data[] = array('90.03.00.02', 'N', 'Arrematação e Adjudicação', '100', '0', 'E', 'Autos, carta.');
		$data[] = array('90.03.00.03', 'N', 'Registro de Suspensão Condicional de Execução da pena', '5', '10', 'E', null);
		$data[] = array('90.03.00.04', 'N', 'Comparecimento dos condenados com benefício de sursis e declaração de prestação laborativa', '2', '5', 'E', null);
		$data[] = array('90.03.00.05', 'N', 'Controle de pena alternativa', '100', '0', 'E', null);
		$data[] = array('90.03.00.06', 'N', 'Controle de réu preso ', '100', '0', 'E', null);
		$data[] = array('90.03.00.07', 'N', 'Laudo de Avaliação', '3', '0', 'E', null);
		$data[] = array('90.03.01', 'S', 'PRECATÓRIO', null, null, null, null);
		$data[] = array('90.03.01.01', 'N', 'Precatório ou Requisição de Pequeno Valor (RPV)', '100', '10', 'E', null);
		$data[] = array('90.03.01.02', 'N', 'Controle de precatório', '3', '0', 'E', 'Inclusive erros, duplicidade, cancelamento.');
		$data[] = array('90.03.02', 'S', 'CÁLCULOS JUDICIAIS', null, null, null, null);
		$data[] = array('90.03.02.01', 'N', 'Cálculo judicial', '100', '0', 'E', null);
		$data[] = array('90.03.02.02', 'N', 'Laudo judicial', '100', '0', 'E', null);
		$data[] = array('90.03.02.03', 'N', 'Manual', '3', '0', 'G', 'Uso pelo CJF.');
		$data[] = array('90.04', 'S', 'DEPÓSITO JUDICIAL', null, null, null, null);
		$data[] = array('90.04.00.01', 'N', 'Depósito Judicial', '5', '0', 'G', 'Termo de recebimento, tombamento e remessa de bens apreendidos, bens acautelados.');
		$data[] = array('90.04.00.02', 'N', 'Fiel depositário', '100', '5', 'E', 'Termo de compromisso.');
		$data[] = array('90.05', 'S', 'CORREGEDORIA', null, null, null, null);
		$data[] = array('90.05.00.02', 'N', 'Consultas, orientações, providências e registro de reclamações', '5', '0', 'E', 'Pode ser gerado processo.');
		$data[] = array('90.05.00.03', 'N', 'Representação por excesso de prazo', '3', '0', 'G', null);
		$data[] = array('90.05.00.04', 'N', 'Avocação', '3', '0', 'G', null);
		$data[] = array('90.05.00.06', 'N', 'Intimação pela Corregedoria', '3', '0', 'E', null);
		$data[] = array('90.05.00.07', 'N', 'Procedimento de Controle Administrativo', '3', '0', 'G', null);
		$data[] = array('90.05.00.08', 'N', 'Pedido de Providência', '5', '0', 'G', null);
		$data[] = array('90.05.00.09', 'N', 'Reclamação Disciplinar', '5', '0', 'G', null);
		$data[] = array('90.05.00.10', 'N', 'Recurso de Decisão do Corregedor', '5', '0', 'G', null);
		$data[] = array('90.05.00.11', 'N', 'Recurso Disciplinar de Magistrado', '5', '0', 'G', null);
		$data[] = array('90.05.00.12', 'N', 'Revisão Disciplinar', '3', '0', 'G', null);
		$data[] = array('90.05.01', 'S', 'INSPEÇÃO', null, null, null, null);
		$data[] = array('90.05.01.01', 'N', 'Inspeção geral ordinária', '3', '0', 'G', null);
		$data[] = array('90.05.01.02', 'N', 'Inspeção geral extraordinária', '3', '0', 'G', null);
		$data[] = array('90.05.01.03', 'N', 'Inspeção de avaliação', '3', '0', 'G', null);
		$data[] = array('90.05.02', 'S', 'CORREIÇÃO', null, null, null, null);
		$data[] = array('90.05.02.01', 'N', 'Correição geral ordinária', '3', '0', 'G', null);
		$data[] = array('90.05.02.02', 'N', 'Correição geral extraordinária', '3', '0', 'G', null);
		$data[] = array('90.05.02.03', 'N', 'Correição de avaliação', '3', '0', 'G', null);
		$data[] = array('90.05.02.04', 'N', 'Correição parcial', '3', '0', 'G', null);
		$data[] = array('90.05.03', 'S', 'IMPEDIMENTO E SUSPEIÇÃO', null, null, null, 'Art. 134 a 138, CPC e Resolução nº 82, CNJ, 09 junho de 2009.');
		$data[] = array('90.05.03.01', 'N', 'Impedimento / suspeição', '3', '0', 'G', 'Art. 134 a 138, CPC.');
		$data[] = array('90.06', 'S', 'ESTATÍSTICA JUDICIÁRIA', null, null, null, null);
		$data[] = array('90.06.00.01', 'N', 'Estatística da produção judiciária', '3', '0', 'E', 'Inclusive a produtividade de magistrados (Podem ser transferidos para relatórios anuais). ');
		$data[] = array('90.07', 'S', 'ADMINISTRAÇÃO DE GABINETES', null, null, null, null);
		$data[] = array('90.07.00.01', 'N', 'Seleção de acórdãos para publicação na Revista', '1', '0', 'E', null);
		$data[] = array('90.07.00.02', 'N', 'Sentença', '100', '0', 'G', 'Em suporte papel ou digital cfe. Art. 12, Res. 318/2014, CJF.');
		$data[] = array('90.07.00.03', 'N', 'Inteiro teor do acórdão', '100', '0', 'G', 'Relatório, voto, ementa e acórdão. Em suporte papel ou digital cfe. Art. 12, Res. 318/2014, CJF.');
		$data[] = array('90.07.00.04', 'N', 'Decisões interlocutórias proferidas', '100', '0', 'E', null);
		$data[] = array('90.07.00.05', 'N', 'Decisões terminativas proferidas', '100', '0', 'G', 'Em suporte papel ou digital cfe. Art. 12, Res. 318/2014, CJF.');
		$data[] = array('90.07.00.06', 'N', 'Decisões recursais monocráticas', '100', '0', 'G', 'Em suporte papel ou digital cfe. Art. 12, Res. 318/2014, CJF.');
		$data[] = array('90.08', 'S', 'PRAZOS PROCESSUAIS', null, null, null, null);
		$data[] = array('90.08.00.01', 'N', 'Prazos forenses', '3', '0', 'E', null);
		$data[] = array('90.08.01', 'S', 'ANO JUDICIÁRIO', null, null, null, null);
		$data[] = array('90.08.01.01', 'N', 'Recesso forense', '3', '0', 'E', null);
		$data[] = array('90.08.01.02', 'N', 'Férias forenses', '3', '0', 'E', null);
		$data[] = array('90.08.01.03', 'N', 'Plantão ', '3', '0', 'E', null);


    BancoSEI::getInstance()->abrirConexao();
    BancoSEI::getInstance()->abrirTransacao();

    $objTabelaAssuntoRN = new TabelaAssuntosRN();

		$objTabelaAssuntoDTO = new TabelaAssuntosDTO();
		$objTabelaAssuntoDTO->setStrNome('PCTT Abril/2017');
		$objTabelaAssuntoDTO->setNumIdTabelaAssuntos(null);
		$objTabelaAssuntoDTO->setStrDescricao('Plano de Classificação e Tabela de Temporalidade dos Documentos administrativos da Justiça Federal (versão abril/2017)');
		$objTabelaAssuntoDTO->setStrSinAtual('N');
		$objTabelaAssuntoDTO = $objTabelaAssuntoRN->cadastrar($objTabelaAssuntoDTO);
//
    $objAssuntoRN = new AssuntoRN();

    $objAssuntoDTO = new AssuntoDTO();
    $objAssuntoDTO->setNumIdTabelaAssuntos($objTabelaAssuntoDTO->getNumIdTabelaAssuntos());
//		$objAssuntoDTO->setNumIdTabelaAssuntos(5);
		$objAssuntoDTO->setNumIdAssunto(null);
		$objAssuntoDTO->setStrSinAtivo('S');

    InfraDebug::getInstance()->gravar('ASSUNTOS:');

		foreach ($data as $arr) {

			InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($arr[0].' - '.$arr[2]));

			$objAssuntoDTO->setStrCodigoEstruturado($arr[0]);
			$objAssuntoDTO->setStrSinEstrutural($arr[1]);
			$objAssuntoDTO->setStrDescricao($arr[2]);
			$objAssuntoDTO->setNumPrazoCorrente($arr[3]);
			$objAssuntoDTO->setNumPrazoIntermediario($arr[4]);
			$objAssuntoDTO->setStrStaDestinacao($arr[5]);
			$objAssuntoDTO->setStrObservacao($arr[6]);
			$objAssuntoRN->cadastrarRN0259($objAssuntoDTO);
		}

		$arrMapeamentos = explode("\n",'00.01.01.01;00.01.01.01
00.01.01.02;90.05.00.02
00.01.02;00.01.01.03
00.01.03;00.01.01.03
00.01.04.01;00.01.01.07
00.01.04.02;00.01.01.07
00.01.04.03;90.05.00.06
00.01.04.04;20.03.01.01
00.01.04.05;00.01.01.07
00.01.04.06;00.01.01.07
00.01.04.07;00.01.01.07
00.01.04.08;00.01.01.09
00.01.04.09;00.01.01.05
00.01.04.10;20.03.01.01
00.01.04.11;00.01.01.03
00.01.05;00.01.01.01
00.01.06;00.01.01.03
00.01.07;00.01.01.16
00.01.08.01;00.03.00.01
00.01.08.02;00.03.00.01
00.01.09.01;00.01.01.01
00.01.10.01;20.08.06.03
00.01.10.02;20.08.06.03
00.01.10.03;20.08.06.03
00.01.11.01;30.01.02.06
00.01.11.02;30.03.00.10
00.01.11.03;00.03.00.03
00.01.11.04;00.03.00.03
00.01.11.05;00.03.00.03
00.01.12.01;00.04.00.01
00.02.01;00.04.00.01
00.02.02;00.04.00.01
00.02.03;00.01.01.03
00.03.01;00.05.00.01
00.03.02;00.05.00.02
00.03.03;00.05.00.02
00.03.04;00.01.01.03
00.04.01.01;30.03.00.10
00.04.01.02;00.01.01.17
00.04.01.03;30.01.02.02
00.04.01.04;00.01.01.17
00.04.01.05;00.01.01.17
00.04.01.06;00.01.01.17
00.04.01.07;00.01.01.17
00.04.01.08;00.01.01.17
00.04.01.09;00.01.01.17
00.04.01.10;00.01.01.17
00.04.01.11;00.06.02.05
00.04.01.12;00.01.01.17
00.04.01.13;00.01.01.17
00.04.02.01;00.01.01.17
00.04.02.02;30.01.02.03
00.04.02.03;30.01.02.04
00.04.02.04;30.01.02.05
00.04.02.05;30.01.02.06
00.04.02.06;20.05.01.01
00.04.03.01;30.01.03.01
00.04.03.02;30.01.03.02
00.04.04;00.01.01.03
00.04.05;30.01.02.01
00.04.06;30.01.02.01
00.05.01.01;00.06.01.02
00.05.01.02;00.06.01.02
00.05.02.01;00.06.02.01
00.05.02.02;00.06.01.02
00.05.02.03;00.06.01.02
00.05.02.04;00.06.01.02
00.05.02.05;00.06.02.03
00.05.02.06;00.01.01.03
00.05.03.01;00.06.01.02
00.05.04.01;10.06.01.01
00.05.04.02;10.06.01.02
00.05.05.01;00.01.01.03
00.05.05.02;00.06.01.02
00.05.05.03;00.06.01.02
00.05.06;00.01.01.03
00.06.01;00.01.01.03
00.06.02;00.07.00.01
00.99;00.01.01.05
00.99.04;00.01.01.05
00.99.20;00.01.01.05
00.99.32;00.01.01.05
00.99.40;00.01.01.05
00.99.80;00.01.01.05
01.01.01;00.08.00.01
01.01.01.01;00.01.01.03
01.01.02;00.08.00.01
01.01.03.01;00.09.00.01
01.01.04.01;00.01.01.03
01.01.04.02;00.01.01.03
01.01.04.03;00.01.01.05
01.01.04.04;00.01.01.03
01.01.04.05;00.01.01.03
01.01.04.06;00.01.01.03
01.01.04.07;00.01.01.05
01.02.01;00.10.00.05
01.02.02;00.10.00.05
01.02.03.01;40.03.02.01
01.02.03.02;40.03.02.01
01.02.03.03;00.10.04.01
01.02.03.04;00.10.00.05
01.02.03.05;00.10.00.05
01.02.03.06;00.01.01.03
01.02.04;00.10.00.02
01.02.04.01;00.01.01.03
01.02.05;00.10.00.02
01.03.01.01;20.02.06.05
01.03.02.01;00.11.01.01
01.03.03;00.11.01.05
01.03.04.01;00.11.01.02
01.03.04.02;00.11.01.03
01.03.05;00.01.01.03
01.04.01.01;00.11.02.01
01.04.01.02;00.11.02.01
01.04.01.03;00.11.02.01
01.04.01.04;00.11.02.01
01.04.01.05;00.11.02.01
01.04.01.06;00.11.02.03
01.04.01.08;00.11.02.01
01.04.01.09;00.11.02.01
01.04.01.10;00.11.02.01
01.04.02.01;00.11.02.01
01.04.02.02;00.11.02.01
01.04.03.01;00.11.02.03
01.04.04.01;00.11.04.01
01.04.04.02;00.11.04.02
01.04.05;00.01.01.03
01.05.01.01;00.11.02.04
01.05.01.02;00.11.02.05
01.05.01.03;00.11.02.04
01.05.02.01;40.03.02.01
01.05.03;00.01.01.03
02.01.01.04;20.07.00.02
02.01.02.01;20.02.06.09
02.01.02.02;20.02.06.09
02.01.02.03;20.02.06.09
02.01.02.04;20.02.06.09
02.01.02.05;20.02.06.09
02.01.03.01;20.05.09.04
02.01.04.01;00.11.04.01
02.01.04.02;20.09.00.01
02.01.04.03;20.09.00.01
02.01.05.01;20.09.00.01
02.01.05.02;20.09.00.02
02.01.06.01;20.09.00.03
02.01.07;90.05.00.02
02.01.08;00.01.01.03
02.02.01.01;20.02.08.02
02.02.01.02;20.02.08.02
02.02.02.01;20.02.08.01
02.02.02.02;20.04.00.04
02.02.02.03;00.11.02.01
02.02.02.04;00.11.02.01
02.02.02.05;00.11.02.01
02.02.02.06;00.11.02.01
02.02.02.07;00.11.02.01
02.02.02.08;00.11.02.01
02.02.02.09;20.02.08.01
02.03.01;00.07.00.01
02.04.01.01;20.04.00.04
02.04.02;20.02.06.01
02.04.03;20.05.09.02
02.04.04;20.02.06.01
02.04.05;20.02.06.02
02.04.06;20.02.06.02
02.04.07;20.02.06.05
02.04.08;20.02.06.07
02.05.01.01;20.02.06.01
02.05.01.02;20.02.06.01
02.05.02.01;20.02.03.01
02.05.02.02;20.02.03.01
02.05.02.03;20.02.03.01
02.05.02.04;20.02.01.01
02.05.02.05;20.02.04.07
02.05.02.06;20.02.03.03
02.05.02.07;20.02.03.01
02.05.02.08;20.02.01.02
02.05.02.09;20.02.03.05
02.05.02.10;20.02.03.05
02.05.02.11;20.02.03.05
02.05.02.12;20.02.03.01
02.05.02.13;20.02.03.01
02.05.02.14;20.02.03.01
02.05.02.15;20.02.03.01
02.05.02.16;20.02.03.01
02.05.02.17;20.02.03.01
02.05.02.18;20.02.01.03
02.05.02.19;20.02.03.06
02.05.02.20;20.02.04.06
02.05.02.21;20.02.03.01
02.05.02.22;20.02.04.09
02.05.02.23;20.02.03.01
02.05.02.24;20.02.03.01
02.05.02.25;20.02.04.05
02.05.03.01;20.02.07.03
02.05.03.02;20.02.07.03
02.05.03.03;20.02.07.04
02.05.04.01;20.11.00.06
02.05.04.02;00.08.00.01
02.05.04.03;20.11.00.06
02.05.04.04;20.11.00.06
02.05.04.05;20.11.00.06
02.05.04.06;20.11.00.03
02.05.04.07;20.11.00.04
02.05.04.08;20.11.00.06
02.05.04.09;00.01.01.17
02.05.04.10;20.11.00.06
02.05.04.11;30.03.00.10
02.05.04.12;20.11.00.06
02.05.04.13;20.11.00.06
02.05.04.14;20.11.00.06
02.05.04.15;20.11.00.06
02.05.05.01;20.02.03.04
02.05.05.02;20.02.03.05
02.05.05.03;20.02.03.05
02.05.05.04;20.02.03.01
02.05.05.05;20.02.04.08
02.05.05.06;20.02.03.01
02.05.05.07;20.11.00.06
02.05.05.08;20.02.04.03
02.05.05.09;20.02.04.06
02.05.05.10;20.02.04.06
02.05.05.11;20.01.01.07
02.05.06.01;20.08.06.01
02.05.06.02;20.08.06.02
02.05.06.03;20.08.06.02
02.06.01.01;20.04.00.04
02.06.01.02;20.04.00.02
02.06.01.03;20.04.00.05
02.06.01.04;20.04.00.04
02.06.01.05;20.04.00.04
02.06.01.06;20.04.00.05
02.06.01.07;20.04.00.04
02.06.01.08;20.04.00.04
02.06.02.01;20.04.00.04
02.06.02.02;30.02.02.01
02.06.02.03;20.04.00.05
02.06.02.04;20.04.00.05
02.06.02.05;20.04.00.04
02.06.02.06;30.02.02.01
02.06.02.07;20.04.00.05
02.06.02.08;20.04.00.03
02.06.03.01;20.04.00.04
02.06.03.02;20.04.00.04
02.06.04.01;20.04.00.06
02.06.05.03;20.08.06.03
02.07.01.01;20.01.01.03
02.07.02.01;20.01.01.03
02.07.02.02;20.01.01.06
02.07.02.03;20.01.01.05
02.07.02.04;20.01.01.07
02.07.02.05;20.01.01.04
02.07.02.06;20.01.01.08
02.07.02.07;20.01.01.09
02.07.02.08;20.01.01.10
02.07.02.09;20.01.01.11
02.07.03.01;00.08.00.01
02.07.03.02;20.05.00.04
02.07.04.01;20.01.01.01
02.07.04.02;20.02.05.10
02.07.04.03;20.03.02.02
02.07.05.01;20.02.04.02
02.07.05.02;20.02.04.03
02.07.05.03;20.02.04.03
02.07.05.04;20.02.04.03
02.07.05.05;20.02.05.06
02.07.05.06;20.02.04.04
02.07.05.07;20.02.04.05
02.07.05.08;20.01.01.06
02.07.05.09;20.02.05.11
02.07.05.10;20.02.05.12
02.07.05.11;20.02.05.07
02.07.05.12;20.02.05.08
02.07.05.13;20.08.03.06
02.07.05.14;20.02.04.03
02.07.05.15;20.02.05.10
02.07.06.01;20.02.10.01
02.07.06.02;20.03.04.06
02.07.06.03;20.03.04.06
02.07.06.04;20.02.10.05
02.07.06.05;20.02.10.02
02.07.06.06;20.02.10.01
02.07.06.07;20.02.10.01
02.07.07.01;20.03.01.01
02.07.07.02;00.10.00.03
02.07.07.03;00.10.00.03
02.07.07.04;20.03.01.02
02.07.07.05;20.03.01.01
02.07.07.06;20.03.02.02
02.07.07.07;20.02.05.13
02.07.07.08;20.03.02.02
02.07.08.01;20.03.04.04
02.07.08.02;20.03.04.05
02.07.08.03;20.03.04.04
02.07.08.04;20.03.02.04
02.07.08.05;20.03.02.04
02.07.08.06;20.03.02.03
02.07.08.07;20.03.04.05
02.07.09.01;20.03.03.01
02.07.09.02;20.03.03.01
02.07.09.03;20.03.03.02
02.07.09.04;20.03.03.01
02.07.09.05;20.03.03.02
02.07.10.01;20.02.07.05
02.08.01.01;20.05.09.01
02.08.01.04;20.05.10.02
02.08.01.05;20.05.11.05
02.08.01.06;20.05.10.01
02.08.01.07;20.05.00.05
02.08.01.08;20.05.11.05
02.08.01.09;20.05.11.05
02.08.01.10;20.05.09.03
02.08.02;20.05.09.02
02.08.03;20.05.09.02
02.08.04;20.05.09.02
02.08.05;20.05.09.02
02.08.06.01;20.05.00.01
02.08.06.02;20.05.00.01
02.08.06.03;20.05.00.02
02.08.07.01;20.01.01.02
02.08.07.02;20.05.00.03
02.08.07.03;20.05.00.07
02.08.07.04;20.05.00.08
02.08.07.05;20.05.00.08
02.08.07.06;00.06.02.03
02.08.07.07;20.05.11.02
02.08.08.01;20.05.00.05
02.08.09.01;20.05.00.06
02.08.09.02;20.05.00.06
02.08.10.01;20.05.11.03
02.08.10.02;20.05.11.03
02.08.10.03;20.05.11.02
02.08.11.01;20.05.11.04
02.08.12;00.01.01.03
02.09.01.01;20.05.11.06
02.09.02.01;20.05.11.07
02.09.02.02;20.05.11.07
02.09.03.01;20.05.11.08
02.09.03.02;20.05.11.08
02.09.04.01;20.05.11.09
02.09.04.02;20.05.11.09
02.09.05;20.05.11.10
02.09.06.01;20.05.11.11
02.09.06.02;20.05.11.11
02.09.07.01;20.05.11.12
02.09.08;00.01.01.03
02.09.09.01;20.05.11.13
02.10.01.01;20.05.01.01
02.10.01.02;20.05.01.01
02.10.01.03;20.05.09.02
02.10.01.04;20.05.09.02
02.10.02.01;20.05.02.01
02.10.03.01;20.05.04.01
02.10.04.01;20.05.05.02
02.10.04.02;20.05.05.02
02.10.04.03;20.05.07.05
02.10.05.01;20.05.06.01
02.10.05.02;20.05.06.01
02.10.06;00.01.01.03
02.11.01.01;20.05.07.03
02.11.02.01;20.05.07.01
02.11.03.01;10.05.00.06
02.11.04.01;20.05.02.01
02.11.05.01;20.05.04.01
02.11.06.01;20.08.01.01
02.11.07.01;20.05.07.04
02.11.07.02;20.05.07.05
02.11.08;00.01.01.03
02.12.01;20.10.00.09
02.12.02;20.10.00.09
02.12.03;20.10.00.09
02.12.04;00.01.01.03
02.13.01.01;20.06.01.01
02.13.01.02;20.06.01.08
02.13.01.03;20.06.01.01
02.13.01.04;20.06.01.06
02.13.01.05;20.06.01.07
02.13.01.06;20.06.01.08
02.13.01.07;20.06.01.09
02.13.01.08;20.06.01.01
02.13.01.09;20.06.01.03
02.13.01.10;20.06.01.04
02.13.01.11;20.06.01.05
02.13.01.12;20.03.01.03
02.13.02.01;20.08.02.01
02.13.02.02;20.08.02.01
02.13.02.03;20.10.00.04
02.13.02.04;00.12.00.03
02.13.02.05;20.08.02.06
02.13.02.06;20.08.02.06
02.13.02.07;20.08.02.01
02.13.02.08;20.08.02.06
02.13.02.09;20.08.02.02
02.13.02.10;20.08.02.03
02.13.02.11;20.06.01.02
02.13.02.12;20.08.02.04
02.13.02.13;20.08.02.05
02.13.03.01;20.06.00.06
02.13.03.02;20.06.00.01
02.13.03.03;20.06.00.08
02.13.03.04;20.06.00.06
02.13.03.05;20.06.00.07
02.13.03.06;20.10.00.01
02.13.04;00.01.01.03
02.14.01.01;20.05.08.01
02.14.01.02;20.05.08.02
02.14.01.03;20.05.08.02
02.14.01.04;20.05.08.01
02.14.02.01;20.05.10.03
02.14.02.02;20.05.10.03
02.14.03;20.05.08.02
02.14.04;20.05.08.02
02.14.05;00.01.01.03
02.15.01;20.10.00.02
02.15.02;20.06.01.01
02.15.03;20.06.02.01
02.15.04;20.06.02.04
02.15.05;20.06.02.04
02.15.06;00.01.01.03
02.15.07;20.06.02.04
02.15.08;20.06.02.01
02.15.09;20.06.02.05
02.15.10;20.06.02.02
02.15.11;20.06.02.03
02.15.12;20.10.00.03
02.15.13;20.06.00.08
02.16.01.01;20.08.01.07
02.16.01.02;20.08.01.07
02.16.01.03;20.08.01.08
02.16.01.04;20.08.01.08
02.16.01.05;20.08.01.08
02.16.01.06;20.08.01.07
02.16.01.07;20.08.01.09
02.16.01.08;20.08.01.08
02.16.01.09;20.08.01.08
02.16.01.10;20.08.01.08
02.16.01.11;20.05.10.04
02.16.02.01;20.08.01.02
02.16.02.02;20.08.01.01
02.16.02.03;20.08.01.02
02.16.02.04;20.08.01.05
02.16.02.05;20.08.01.03
02.16.02.06;20.08.01.04
02.16.02.07;20.08.01.06
02.16.03;00.01.01.03
02.17.01.01;20.07.00.02
02.17.01.02;20.07.00.05
02.17.01.03;20.07.00.03
02.17.01.04;20.07.00.02
02.17.02.01;20.07.00.02
02.17.02.02;20.07.00.03
02.17.03.01;20.07.00.06
02.17.04;00.01.01.03
02.18;30.03.00.10
02.18.01;00.01.01.03
02.19.01;20.02.04.08
02.19.01.01;20.08.02.01
02.19.02;20.02.09.03
02.19.03;20.02.09.03
02.19.04;20.02.09.03
02.19.05.02;20.02.06.08
02.19.05.03;20.02.06.08
02.19.06.01;20.08.04.01
02.19.06.02;20.08.04.01
02.19.06.03;20.08.04.01
02.19.06.04;20.08.04.01
02.19.07;00.01.01.03
02.19.08;20.02.06.04
02.20;20.05.00.01
02.20.01;00.01.01.03
02.21;20.08.05.01
02.21.01;20.08.05.01
02.21.02.01;20.05.08.02
02.21.02.02;20.05.08.02
02.21.03;20.02.06.09
02.21.04;20.08.05.04
02.21.05;30.01.02.07
02.21.06;20.08.05.05
02.21.07;20.08.05.01
02.21.08;00.12.00.11
02.21.09;20.08.05.01
02.21.10;20.08.05.04
02.21.11;20.08.05.04
02.21.12;20.08.05.01
02.21.13;20.08.05.01
02.21.14;20.08.05.05
02.21.15;20.08.05.03
02.21.16;20.08.05.04
02.21.17;20.08.05.05
02.21.18;20.02.06.09
02.21.19;00.01.01.03
02.21.20;20.08.05.01
02.22.01;30.05.02.02
02.22.02;30.05.02.03
02.22.03;00.01.01.03
02.23;30.03.00.01
02.23.01;30.03.00.01
02.24.01;00.12.00.03
02.24.02;30.05.02.02
02.24.03.01;00.12.00.11
02.24.04;20.02.06.01
02.24.05;00.01.01.03
02.24.06;20.08.05.01
02.24.07;00.12.00.05
02.25.01.01;20.10.00.04
02.25.01.02;20.10.00.05
02.25.01.03;20.10.00.04
02.25.02;00.01.01.03
02.26.01.01;20.05.10.06
02.26.01.02;20.10.00.04
02.26.01.03;20.05.10.05
02.26.01.04;20.05.10.06
02.26.01.05;20.05.10.06
02.26.02.01;20.05.10.06
02.26.02.02;20.05.10.06
02.26.02.03;20.05.10.06
02.26.02.04;20.05.10.03
02.26.02.05;20.05.10.03
02.26.03;20.05.10.07
02.27;20.05.11.02
03.01.01;30.01.01.01
03.01.02;30.02.02.01
03.01.03.01;30.01.01.11
03.01.03.02;30.01.01.03
03.01.03.03;30.01.01.12
03.01.03.04;30.01.01.11
03.02.01;30.01.01.13
03.02.02;30.01.01.03
03.03.01;40.06.03.02
03.03.02;40.06.03.02
03.03.03;40.06.03.03
03.04.01.01;30.02.02.01
03.04.01.02;30.01.01.02
03.04.01.03;30.01.01.03
03.04.01.04;30.01.01.04
03.04.01.05;30.01.01.04
03.04.01.06;30.01.01.05
03.04.01.07;30.01.01.06
03.04.01.08;30.01.01.07
03.04.01.09;30.01.01.13
03.04.01.10;30.01.01.08
03.04.01.11;30.01.01.10
03.04.01.12;30.01.01.03
03.04.01.13;30.01.01.09
03.04.01.14;30.01.01.10
03.04.01.15;30.01.03.01
03.04.01.16;30.01.02.07
03.04.01.17;30.01.01.03
03.04.01.18;30.01.01.03
03.04.01.19;30.01.01.03
03.04.01.20;30.01.01.03
03.04.01.21;30.01.01.03
03.04.01.22;30.01.01.03
03.04.01.23;30.01.01.03
03.04.01.24;30.01.01.03
03.05.01.01;30.04.05.01
03.05.02.01;30.04.05.03
03.05.02.02;30.04.05.02
03.05.02.03;30.04.05.04
03.05.03.01;30.04.06.01
03.05.04.01;30.04.04.03
03.06.01.01;30.04.09.01
03.06.02.01;30.04.09.02
03.06.03.01;30.02.02.01
03.06.03.02;30.02.02.01
03.06.03.03;30.01.02.02
03.07.01.01;30.04.09.07
03.07.01.02;30.04.09.07
03.07.01.03;30.04.04.03
03.07.01.04;30.01.01.03
03.07.01.05;30.01.01.03
03.07.01.06;30.04.10.01
03.07.01.07;30.02.02.01
03.07.01.08;30.02.02.01
03.07.02.01;30.04.10.01
03.07.02.02;30.04.10.01
03.07.02.04;30.04.10.01
03.07.03.01;30.04.01.01
03.07.03.02;20.07.00.02
03.07.04.01;30.02.02.01
03.07.04.02;30.02.02.01
03.07.04.03;30.03.00.01
03.07.04.04;30.02.02.01
03.07.05.01;30.04.02.01
03.07.06.01;30.04.02.02
03.08.01;30.04.02.02
03.08.02.01;30.04.08.02
03.08.02.02;30.04.08.05
03.08.02.03;30.04.08.02
03.08.02.04;30.04.02.02
03.09.01.01;30.02.02.01
03.09.01.02;30.02.05.07
03.09.02;30.02.05.07
03.10.01.01;30.04.07.01
03.10.01.02;30.04.07.01
03.10.01.03;30.04.07.01
03.10.01.04;30.04.07.01
03.10.01.05;30.04.07.01
03.10.02.01;30.04.07.01
03.10.02.02;30.04.02.03
03.10.03.01;30.04.10.01
03.10.04.01;30.05.06.01
04.01.01.01;30.05.03.01
04.01.01.02;30.05.03.02
04.01.02.01;30.05.03.02
04.01.03.01;30.05.03.03
04.01.04.01;30.05.03.04
04.02.01;30.05.01.01
04.02.01.01;30.05.01.01
04.02.02;30.05.01.02
04.02.02.01;30.05.01.02
04.02.03;30.05.01.03
04.02.03.01;30.02.07.01
04.02.04;30.05.01.04
04.02.04.01;30.05.01.04
04.02.05;30.05.02.01
04.02.05.01;30.05.02.01
04.03.01.01;30.05.04.01
04.03.02;30.05.04.02
04.03.02.01;30.05.04.02
04.03.03.01;30.05.04.03
04.03.04.01;30.05.04.04
04.04.01;30.05.05.01
04.05.01;30.02.01.01
04.05.01.01;30.02.01.01
04.05.01.02;30.02.01.01
04.05.01.03;30.02.01.08
04.05.01.04;30.02.01.08
04.05.01.05;30.02.01.08
04.05.02;30.02.01.09
04.05.02.01;30.02.01.08
04.05.03;30.02.01.08
04.05.03.01;30.02.01.08
04.05.04;30.02.01.08
04.05.05;30.02.01.08
04.05.06;30.02.01.08
04.05.07;30.02.01.08
04.05.08;30.02.01.08
04.06.01;30.02.05.06
04.06.01.01;30.02.05.06
04.06.02;30.02.05.06
04.06.02.01;30.02.05.06
04.06.03;30.02.05.06
04.06.03.01;30.02.05.06
04.06.04;30.02.05.06
04.06.04.01;30.02.05.06
04.06.05;30.02.05.06
04.06.05.01;30.02.05.06
04.07.01.01;30.04.03.05
04.07.02.01;30.04.03.01
04.07.02.02;30.04.03.02
04.07.02.03;30.04.03.02
04.07.03.01;30.04.03.02
04.07.03.02;30.04.03.04
04.07.04.01;30.04.03.04
04.07.04.02;30.04.03.04
04.07.04.03;30.03.00.06
04.07.05.01;30.03.00.06
04.07.05.02;30.03.00.07
04.08.01.01;30.03.00.03
04.08.01.02;30.03.00.04
04.08.01.03;30.03.00.05
04.08.01.04;30.03.00.05
04.08.02.01;30.03.00.02
04.08.03.01;30.03.00.10
04.08.03.02;30.03.00.10
04.08.04.01;20.04.00.05
04.08.05.01;30.03.00.08
04.08.06.01;30.03.01.01
04.08.06.02;30.03.01.02
04.09.01;30.02.02.01
04.09.02;30.02.05.06
04.09.03.01;30.03.01.04
04.10.01.01;30.03.01.04
04.10.01.02;30.03.01.05
04.10.01.03;30.03.01.04
05.01.01;10.01.00.01
05.01.02;10.01.00.01
05.01.03;10.01.00.01
05.02.01.01;10.02.00.01
05.02.01.02;10.02.00.01
05.02.01.03;10.02.00.01
05.02.01.04;10.02.00.01
05.02.02.01;10.05.00.14
05.02.02.02;10.03.00.01
05.02.03.01;10.03.00.04
05.02.03.02;10.03.00.03
05.02.03.03;10.03.00.04
05.02.03.04;10.03.00.04
05.02.03.05;10.03.00.04
05.02.03.06;10.03.00.03
05.02.03.07;10.04.00.03
05.02.04.01;10.05.00.09
05.02.04.02;10.05.00.09
05.02.04.03;10.05.00.09
05.02.05.01;10.05.00.01
05.02.05.02;10.05.00.13
05.02.06.01;10.03.00.04
05.02.06.02;10.05.00.09
05.02.06.03;10.05.00.03
05.02.06.04;10.05.00.03
05.02.06.05;10.05.00.01
05.02.06.06;10.04.00.02
05.02.06.07;10.04.00.02
05.02.06.08;10.06.01.01
05.02.06.09;10.04.00.02
05.02.06.10;10.04.00.02
05.02.06.11;10.05.00.03
05.02.07.01;10.02.00.02
05.03.01.01;10.04.00.04
05.03.01.02;30.01.02.05
05.03.01.03;10.06.01.02
05.03.01.04.01;10.06.01.02
05.03.01.05.01;10.05.00.03
05.03.01.06;10.04.00.01
05.03.01.07;10.04.00.02
05.03.01.08;10.05.00.03
05.03.02.01;10.05.00.03
05.03.02.02;10.05.00.13
05.03.03.01;10.05.00.03
05.03.03.02;10.05.00.03
05.03.03.03;10.05.00.04
05.03.03.04;10.05.00.04
05.03.03.05;10.05.00.10
05.03.04;10.05.01.02
05.03.04.01;10.05.01.02
05.03.04.02;10.05.01.02
05.03.04.03;10.05.01.02
05.03.04.04;10.05.00.05
05.03.04.05;10.05.01.02
05.03.04.06;10.05.00.08
05.03.05.01;10.05.01.01
05.03.05.02;10.05.01.03
05.03.06.01;10.05.00.07
05.03.06.02;10.05.00.07
05.03.06.03;10.05.00.07
05.03.06.04;10.05.00.07
05.03.06.05;10.05.00.07
05.04.01.01;10.05.00.02
05.04.02.01;10.05.00.04
05.04.02.02;10.05.00.04
05.04.03;10.05.00.04
05.05.01.01;10.06.00.01
05.05.02.01;10.06.00.01
05.05.02.02;10.06.00.02
05.06;10.06.00.02
05.06.01;10.06.00.02
05.07;10.06.00.03
05.07.01;10.06.00.03
06.01.01;40.01.01.01
06.01.02;40.07.01.07
06.01.03;40.01.00.02
06.01.04.01;40.07.05.04
06.01.04.02;40.07.05.04
06.01.05.01;40.01.01.03
06.01.05.02;40.01.01.03
06.01.05.03;40.01.01.03
06.01.05.04;40.01.01.04
06.01.05.05;40.06.03.03
06.01.05.06;40.01.01.02
06.01.05.07;40.01.02.01
06.01.05.08;40.01.01.03
06.01.05.09;40.07.03.01
06.01.05.10;40.01.01.01
06.01.05.11;40.07.03.01
06.01.06.01;40.01.02.01
06.01.06.02;40.01.02.01
06.01.06.03;40.06.03.01
06.02.01;40.05.01.04
06.02.02.01;40.02.00.01
06.02.02.03;40.02.00.01
06.02.03.01;40.02.00.01
06.02.04.01;30.04.04.03
06.02.05.01;40.01.02.01
06.02.05.02;40.01.02.01
06.02.06.01;40.01.01.02
06.02.06.02;40.01.02.01
06.02.06.03;40.01.02.01
06.02.07;40.01.02.01
06.02.08.01;00.01.01.17
06.03.01.01;00.01.01.17
06.03.02.01;40.03.02.02
06.03.02.02;40.03.02.01
06.03.02.03;40.03.02.01
06.03.02.05;40.01.01.01
06.03.02.06;40.01.01.01
06.03.02.07;40.01.01.01
06.03.02.08;40.01.01.01
06.04.01.01;40.01.00.03
06.04.01.02;40.01.01.01
06.04.02.01;40.03.03.01
06.04.03.01;40.03.04.01
06.04.03.02;40.03.04.01
06.04.05;40.01.01.01
06.04.06.01;40.03.04.02
06.04.06.02;40.03.04.02
06.04.06.03;40.03.04.03
06.04.07.01;40.03.04.03
06.04.07.02;40.03.04.03
06.07.01;40.04.00.01
06.07.02;40.01.01.02
06.07.03;40.04.00.04
06.07.04;40.04.00.04
06.07.05;40.04.00.04
06.07.06;40.04.00.04
06.07.07;40.04.00.04
06.07.08;40.04.00.04
06.07.09;40.04.00.04
06.07.10;40.04.00.04
06.07.11;40.04.00.04
06.07.12;40.04.00.04
06.07.13;40.04.00.04
06.07.14;40.04.00.04
06.07.15;40.04.00.04
06.07.16;40.04.00.04
06.07.17;40.04.00.04
06.07.18;40.04.00.04
06.07.19;40.04.00.04
06.07.20;40.04.00.04
06.07.21;40.07.01.08
06.07.22;40.04.00.04
06.07.23;40.04.00.04
06.07.24;40.04.00.04
06.07.25;40.04.00.04
06.07.26;40.04.00.04
06.07.27;40.04.00.04
06.07.28;40.04.00.03
06.07.29;40.04.00.04
06.07.30;40.04.00.04
06.08.01.01;40.05.01.01
06.08.01.02;40.05.01.01
06.08.01.03;40.05.01.01
06.08.02.01;40.05.01.01
06.08.02.02;40.05.01.01
06.08.02.03;40.05.01.01
06.08.03.01;40.05.01.04
06.08.04.01;40.05.02.01
06.08.04.02;40.05.02.01
06.08.04.03;40.05.02.01
06.08.04.04;40.05.01.01
06.08.04.05;40.05.02.01
06.08.04.06;40.05.02.01
06.08.04.07;40.05.01.01
06.08.05.01;40.05.03.01
06.08.05.02;40.05.03.02
06.08.05.03;40.05.03.02
06.09.01.01;90.02.00.14
06.09.01.02;40.06.01.01
06.09.01.03;40.06.01.02
06.09.01.04;40.06.01.01
06.09.02.01;40.06.01.03
06.09.02.02;40.06.01.03
06.09.03.01;40.06.02.01
06.09.03.02;40.06.02.03
06.09.03.03;40.06.02.04
06.09.03.04;40.06.02.04
06.09.03.05;40.06.02.05
06.09.03.06;40.06.02.06
06.09.04.01;40.06.02.01
06.09.04.02;40.06.02.01
06.09.05.01;40.06.04.01
06.09.05.02;40.06.04.01
06.10.00;40.07.04.01
06.10.01.01;40.07.01.02
06.10.01.02;40.07.04.01
06.10.01.03;40.07.01.01
06.10.01.04;40.07.01.02
06.10.01.05;40.06.05.02
06.10.01.06;40.07.01.03
06.10.01.07;40.07.06.03
06.10.01.08;40.07.01.03
06.10.01.09;40.07.01.03
06.10.02.01;40.07.04.01
06.10.02.02;40.07.04.01
06.10.02.03;40.07.04.01
06.10.02.04;40.07.04.01
06.10.02.05;40.07.06.03
06.10.02.06;40.07.01.07
06.10.02.07;40.07.01.07
06.10.02.08;40.07.01.07
06.10.02.09;40.07.01.08
06.10.03.01;40.07.04.01
06.10.03.02;40.07.04.01
06.10.03.03;40.07.04.01
06.10.03.04;40.07.04.01
06.10.04.01;40.07.03.01
06.10.04.02;40.07.03.03
06.10.04.03;40.07.04.01
06.10.04.04;40.07.03.01
06.10.04.05;40.07.03.01
06.10.04.06;40.07.03.01
06.11.01;40.01.01.02
06.11.02;40.07.04.01
06.11.03;40.07.04.01
06.11.04;40.07.04.01
07.01.01.01;90.02.00.04
07.01.01.02;90.02.00.04
07.01.01.03;90.02.00.04
07.01.01.04;90.02.00.04
07.01.02.01;90.02.00.04
07.01.03.01;90.02.00.04
07.01.03.02;90.02.00.04
07.01.04.01;90.02.00.04
07.02.01;40.08.00.04
07.03.01;40.08.00.01
07.04.01.01;40.08.00.01
07.04.01.02;40.08.00.01
07.04.02.01;40.08.00.01
07.04.02.02;40.08.00.01
07.04.02.03;40.08.00.01
07.04.03.01;40.08.00.01
07.04.03.02;40.08.00.01
07.04.03.03;40.08.00.01
07.05.01;40.08.00.01
08.01.01.01;20.01.01.06
08.01.02.01;00.01.01.03
08.01.03.01;20.02.06.01
08.01.03.02;20.02.06.05
08.01.03.03;20.04.00.01
08.01.03.04;20.02.06.02
08.01.03.05;20.04.00.05
08.01.04.01;20.02.06.09
08.01.04.02;20.02.06.09
08.02.01.01;20.02.01.01
08.02.01.02;20.02.03.05
08.02.01.03;20.02.01.02
08.02.01.04;20.02.01.02
08.02.01.05;20.02.01.03
08.02.01.06;20.02.01.03
08.02.01.07;20.02.01.01
08.02.01.08;20.02.01.01
08.02.01.09;20.02.01.01
08.03.01.01;20.04.00.05
08.03.01.02;20.04.00.01
08.03.01.03;20.04.00.05
08.03.01.04;20.04.00.04
08.03.01.05;20.04.00.02
08.03.01.06;20.04.00.03
08.03.02.01;00.01.01.12
08.04.01.01;20.02.04.01
08.04.01.02;20.03.02.01
08.04.02.01;20.10.00.08
08.04.02.02;20.10.00.08
08.04.02.03;20.02.04.01
08.04.02.04;20.03.04.01
08.04.03.01;20.03.04.01
08.04.03.02;20.03.04.02
08.04.03.03;20.03.02.02
08.04.03.04;00.01.01.12
08.04.04.01;00.01.01.12
08.04.04.02;00.01.01.13
08.04.04.03;00.01.01.13
08.04.06.01;20.02.09.03
08.04.07.01;20.03.02.02
08.04.07.02;20.02.05.04
08.04.07.03;20.03.02.02
08.04.07.04;20.03.02.02
08.04.07.05;20.02.05.05
08.04.07.06;20.02.05.05
08.04.08.01;90.06.00.01
08.04.08.02;20.02.07.01
08.04.08.03;20.02.07.02
08.04.08.04;20.02.05.02
08.04.08.05;20.02.05.01
08.05.01.01;20.01.01.02
08.05.01.02;20.05.00.06
08.05.01.03;20.05.00.06
08.05.01.04;20.05.11.04
08.05.01.05;20.01.01.02
08.05.02.01;20.05.11.06
08.05.02.02;20.05.11.12
08.05.03.01;20.05.11.12
08.05.04.01;20.05.04.01
08.05.04.02;10.05.00.05
08.05.04.03;20.05.05.02
08.05.04.04;20.05.00.03
08.05.04.05;20.05.06.01
08.05.04.06;20.05.03.01
08.05.04.07;10.05.00.05
08.05.05.01;10.04.00.07
08.05.05.02;20.05.07.01
08.05.05.03;30.01.02.05
08.05.05.04;20.08.01.01
08.05.05.05;10.05.00.05
08.05.06.01;20.10.00.09
08.05.06.02;20.10.00.09
08.05.06.03;20.10.00.09
08.05.06.04;20.05.11.12
08.05.06.04.01;20.10.00.09
08.05.07.01;20.06.01.08
08.05.07.02;20.08.02.06
08.05.07.03;20.06.01.08
08.05.07.04;20.08.02.06
08.05.07.05;20.08.02.06
08.05.07.06;20.06.01.02
08.05.07.07;20.08.02.04
08.05.07.08;20.08.02.05
08.05.07.09;20.08.02.03
08.05.07.10;20.08.02.02
08.05.07.11;20.06.01.01
08.05.08.01;30.03.00.01
08.05.08.02;20.05.10.06
08.05.08.03;20.05.10.05
08.05.08.04;20.05.10.06
08.05.08.05;20.05.10.06
08.05.08.06;20.06.02.01
08.05.08.07;20.06.02.03
08.05.08.08;20.06.02.01
08.05.08.09;20.06.02.03
08.05.08.10;20.06.00.02
08.05.08.11;20.06.00.03
08.05.08.12;20.06.00.04
08.05.08.13;20.06.00.05
08.05.08.14;20.06.02.02
08.05.09.01;20.05.08.01
08.05.09.02;20.05.08.02
08.05.09.03;20.05.08.02
08.05.09.04;20.05.08.01
08.05.09.05;20.05.08.02
08.05.10.01;20.05.10.03
08.05.10.02;20.05.10.03
08.05.10.03;20.05.10.03
08.05.11;20.05.00.03
08.06.01.01;20.05.08.02
08.06.01.02;20.08.01.02
08.06.01.03;20.08.01.05
08.06.01.04;20.08.01.03
08.06.01.05;20.08.01.01
08.06.01.06;20.08.01.04
08.06.01.07;20.08.01.07
08.06.02.01;20.02.09.03
08.06.02.02;20.02.09.03
08.06.02.03;20.02.06.08
08.06.02.04;20.02.06.08
08.06.02.05;20.08.03.04
08.06.03.01;20.08.04.01
08.06.03.02;20.08.04.01
08.06.03.03;20.08.04.01
08.06.03.04;20.08.04.01
08.07.01;20.08.05.01
08.07.02;20.08.05.01
08.07.03;20.08.05.01
08.07.04;20.08.05.05
08.07.05;20.08.05.05
08.07.06;00.12.00.11
08.07.07;20.08.05.01
08.07.08;20.08.05.01
08.07.09;20.08.05.05
08.07.10;20.08.05.01
09.00.00.00;90.00.00.02
09.00.00.04;90.00.00.04
09.00.00.05;90.00.00.04
09.00.00.06;90.00.00.04
09.00.00.07;90.00.00.01
09.00.00.08;90.00.00.04
09.00.00.09;90.00.00.04
09.00.00.10;90.00.00.04
09.00.00.11;90.00.00.04
09.00.00.12;90.00.00.04
09.00.00.13;90.02.01.07
09.00.00.14;90.00.00.04
09.00.02;90.00.00.02
09.01.02;90.00.00.03
09.01.03;90.00.00.01
09.01.04;20.06.00.06
09.01.05;90.02.00.01
09.02.01;90.01.00.01
09.02.02.01;90.02.00.18
09.02.02.03;90.01.00.01
09.02.03.01;90.01.00.01
09.02.04.01;90.01.00.01
09.02.04.02;90.01.01.01
09.02.04.03;90.01.01.01
09.02.04.04;90.01.01.01
09.02.04.05;90.01.01.01
09.02.04.08;90.01.00.01
09.02.04.09;90.02.00.18
09.02.04.10;90.02.00.18
09.02.04.11;90.02.00.18
09.02.05.01;90.01.02.01
09.02.05.02;90.01.02.01
09.02.05.03;90.01.02.01
09.02.05.04;90.00.00.01
09.02.05.05;90.01.02.01
09.02.05.06;90.02.01.07
09.03.01.01;90.02.00.06
09.03.01.02;00.10.03.02
09.03.01.03;00.10.03.02
09.03.01.04;90.02.00.01
09.03.01.05;90.02.00.02
09.03.01.06;90.01.00.01
09.03.01.07;90.02.00.04
09.03.01.08;90.02.00.04
09.03.01.09;90.02.00.05
09.03.01.10;90.02.00.04
09.03.02.01;90.02.00.14
09.03.02.02;90.02.00.15
09.03.02.03;90.02.00.15
09.03.02.04;90.02.00.15
09.03.02.05;90.02.00.16
09.03.02.06;90.02.00.17
09.03.02.07;90.02.00.18
09.03.02.08;90.02.00.19
09.03.02.09;90.02.00.20
09.03.02.10;90.02.00.16
09.03.02.11;90.00.00.05
09.03.02.12;90.04.00.01
09.03.02.13;90.02.00.12
09.03.03.01;90.02.01.01
09.03.03.02;90.02.01.02
09.03.03.03;90.02.01.03
09.03.03.04;90.02.01.04
09.03.03.05;00.01.01.05
09.03.03.06;90.02.01.07
09.03.03.07;90.02.01.05
09.03.04.01;90.02.01.06
09.03.05.01;90.02.01.07
09.03.05.02;00.01.01.05
09.03.05.03;90.02.01.07
09.03.05.04;00.10.04.01
09.03.05.05;90.02.01.07
09.03.06.01;00.10.04.01
09.03.06.02;90.07.00.01
09.03.07.01;90.03.00.01
09.03.08.01;90.03.01.02
09.03.08.02;90.03.01.02
09.03.08.03;10.04.00.05
09.03.08.04;00.01.01.05
09.03.08.05;00.01.01.05
09.03.08.06;90.03.01.01
09.03.08.07;10.04.00.05
09.03.08.08;10.04.00.05
09.03.09.01;00.01.01.05
09.03.09.02;90.03.02.01
09.03.09.03;90.03.02.02
09.03.10.01;90.02.00.06
09.03.11;40.01.02.01
09.04.01;40.01.01.04
09.04.02;40.01.01.04
09.05.01.01;00.01.01.05
09.05.01.02;90.05.01.01
09.05.01.03;00.01.01.05
09.05.01.04;90.05.01.01
09.05.01.05;90.05.01.02
09.05.01.06;90.05.01.03
09.05.02.01;90.05.02.01
09.05.02.02;90.05.02.02
09.05.02.03;90.05.02.04
09.05.02.04;90.05.02.03
09.05.03.01;20.07.00.04
09.05.03.02;20.07.00.05
09.05.03.03;90.05.00.03
09.05.03.04;20.07.00.03
09.05.03.05;20.07.00.01
09.05.03.06;00.01.01.05
09.05.04;90.02.00.01
09.05.04.01;00.01.01.05
09.05.04.02;00.01.01.05
09.05.04.03;00.01.01.05
09.05.04.04;90.02.00.01
09.06.01;00.01.01.05
09.06.02;00.01.01.05
09.06.03;00.01.01.05
09.06.04;90.06.00.01
09.06.05;90.06.00.01
09.06.06;90.06.00.01
09.07.01;90.07.00.01
09.07.02;90.07.00.02
09.07.03;90.07.00.03
09.07.04;90.07.00.04
09.07.05;90.07.00.05
09.07.06;90.07.00.05
09.07.07;90.07.00.05
09.07.08;00.01.01.05
09.08.01;90.08.00.01
09.08.02.01;90.08.01.01
09.08.02.02;90.08.01.02
09.08.02.03;90.08.01.03
09.08.02.04;90.08.01.01
09.08.02.05;20.10.00.06
09.08.02.06;90.08.01.01
09.09;20.10.00.08
11;00.10.00.02
90.95;00.01.01.05
90.96;00.01.01.05
90.97;00.01.01.05
90.98;00.01.01.05
90.99;00.01.01.05
90.99.01;00.01.01.05
');

   $objMapeamentoAssuntoRN = new MapeamentoAssuntoRN();

   InfraDebug::getInstance()->setBolDebugInfra(true);

   InfraDebug::getInstance()->gravar('MAPEAMENTOS:');

   foreach($arrMapeamentos as $strMapeamento){
     $arr = explode(';',$strMapeamento);

     $objAssuntoDTO = new AssuntoDTO();
     $objAssuntoDTO->setBolExclusaoLogica(false);
     $objAssuntoDTO->retNumIdAssunto();
     $objAssuntoDTO->setStrCodigoEstruturado($arr[0]);
     $objAssuntoDTO->setStrSinAtualTabelaAssuntos('S');
     $objAssuntoDTO->setNumIdTabelaAssuntos($objTabelaAssuntoDTO->getNumIdTabelaAssuntos(), InfraDTO::$OPER_DIFERENTE);

     $objAssuntoDTOOrigem = $objAssuntoRN->consultarRN0256($objAssuntoDTO);

     if ($objAssuntoDTOOrigem==null) {
       InfraDebug::getInstance()->gravar($arr[0].': NAO ENCONTRADO');
     }

     if ($objAssuntoDTOOrigem!=null) {

       $objAssuntoDTO = new AssuntoDTO();
       $objAssuntoDTO->setBolExclusaoLogica(false);
       $objAssuntoDTO->retNumIdAssunto();
       $objAssuntoDTO->setStrCodigoEstruturado($arr[1]);
       $objAssuntoDTO->setNumIdTabelaAssuntos($objTabelaAssuntoDTO->getNumIdTabelaAssuntos(), InfraDTO::$OPER_IGUAL);

       $objAssuntoDTODestino = $objAssuntoRN->consultarRN0256($objAssuntoDTO);

       if ($objAssuntoDTODestino==null) {
         InfraDebug::getInstance()->gravar($arr[1].': NAO ENCONTRADO');
       }

       if ($objAssuntoDTODestino!=null) {

         $objMapeamentoAssuntoDTO = new MapeamentoAssuntoDTO();
         $objMapeamentoAssuntoDTO->setNumIdAssuntoOrigem($objAssuntoDTOOrigem->getNumIdAssunto());
         $objMapeamentoAssuntoDTO->setNumIdAssuntoDestino($objAssuntoDTODestino->getNumIdAssunto());

         InfraDebug::getInstance()->gravar($arr[0] . ' --> ' . $arr[1]);


         $objMapeamentoAssuntoRN->cadastrar($objMapeamentoAssuntoDTO);
       }
     }
   }

   $objAssuntoDTO = new AssuntoDTO();
   $objAssuntoDTO->setStrSinEstrutural('N');
   $objAssuntoDTO->setStrCodigoEstruturado('00.07');
   $objAssuntoDTO->setNumIdAssunto(1595);
   $objAssuntoDTO->setStrSinAtualTabelaAssuntos('S');
   $objAssuntoDTO->setNumIdTabelaAssuntos($objTabelaAssuntoDTO->getNumIdTabelaAssuntos(), InfraDTO::$OPER_DIFERENTE);

   if ($objAssuntoRN->contarRN0249($objAssuntoDTO)==1){
     $objAssuntoDTO->setStrSinEstrutural('S');
     $objAssuntoBD = new AssuntoBD(BancoSEI::getInstance());
     $objAssuntoBD->alterar($objAssuntoDTO);
   }

    BancoSEI::getInstance()->confirmarTransacao();
    BancoSEI::getInstance()->fecharConexao();

		InfraDebug::getInstance()->gravar('FIM');

	}catch(Exception $e){

	  try {
      BancoSEI::getInstance()->cancelarTransacao();
    }catch(Exception $e){}

    try {
      BancoSEI::getInstance()->fecharConexao();
    }catch(Exception $e){}

    echo(InfraException::inspecionar($e));
		try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
	}
?>