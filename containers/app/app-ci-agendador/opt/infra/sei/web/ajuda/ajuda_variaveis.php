<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/06/2018 - cjy - adicao de variaveis observacao_documento e observacao_processo
 *
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrVariaveis = array();

  switch($_GET['acao']){

    case 'ajuda_variaveis_secao_modelo':

      $strTitulo = 'Variáveis Disponíveis na Seção';

      $arrVariaveis[] = array('@timbre_orgao@','Timbre do órgão associado com a unidade atual');
      $arrVariaveis[] = array('@sigla_orgao_origem@','Sigla do órgão associado com a unidade atual');
      $arrVariaveis[] = array('@descricao_orgao_origem@','Descrição do órgão associado com a unidade atual');
      $arrVariaveis[] = array('@descricao_orgao_maiusculas@','Descrição em letras maiúsculas do órgão associado com a unidade atual');
      $arrVariaveis[] = array('@hifen_sitio_internet_orgao@','Caractere hífen seguido do sítio da internet cadastrado para o órgão associado com a unidade atual');
      $arrVariaveis[] = array('@hierarquia_unidade@','Hierarquia da unidade atual');
      $arrVariaveis[] = array('@hierarquia_unidade_invertida@','Hierarquia invertida da unidade atual');
      $arrVariaveis[] = array('@hierarquia_unidade_descricao_quebra_linha@','Descrição das unidades da hierarquia separadas por quebra de linha');
      $arrVariaveis[] = array('@hierarquia_unidade_invertida_descricao_quebra_linha@','Descrição das unidades da hierarquia invertida separadas por quebra de linha');
      $arrVariaveis[] = array('@hierarquia_unidade_raiz_sigla@','Sigla da unidade raiz na hierarquia da unidade atual');
      $arrVariaveis[] = array('@hierarquia_unidade_raiz_descricao@','Descrição da unidade raiz na hierarquia da unidade atual');
      $arrVariaveis[] = array('@hierarquia_unidade_superior_sigla@','Sigla da unidade imediatamente superior na hierarquia da unidade atual');
      $arrVariaveis[] = array('@hierarquia_unidade_superior_descricao@','Descrição da unidade imediatamente superior na hierarquia da unidade atual');
      $arrVariaveis[] = array('@sigla_unidade@','Sigla da unidade atual');
      $arrVariaveis[] = array('@descricao_unidade@','Descrição da unidade atual');
      $arrVariaveis[] = array('@descricao_unidade_maiusculas@','Descrição em letras maiúsculas da unidade atual');
      $arrVariaveis[] = array('@endereco_unidade@','Endereço da unidade atual');
      $arrVariaveis[] = array('@complemento_endereco_unidade@','Complemento do endereço da unidade atual');
      $arrVariaveis[] = array('@hifen_bairro_unidade@','Caractere hífen seguido do bairro da unidade atual');
      $arrVariaveis[] = array('@telefone_comercial_unidade@','Telefone comercial da unidade atual');
      $arrVariaveis[] = array('@telefone_residencial_unidade@','Telefone residencial da unidade atual');
      $arrVariaveis[] = array('@telefone_celular_unidade@','Telefone celular da unidade atual');
      $arrVariaveis[] = array('@cidade_unidade@','Cidade da unidade atual');
      $arrVariaveis[] = array('@sigla_uf_unidade@','Sigla da unidade federativa associada com a unidade atual');
      $arrVariaveis[] = array('@cep_unidade@','CEP da unidade atual');
      $arrVariaveis[] = array('@observacao_unidade@','Observação associada com a unidade atual');
      $arrVariaveis[] = array('@dia@','Dia atual (01..31)');
      $arrVariaveis[] = array('@mes@','Mês atual (01..12)');
      $arrVariaveis[] = array('@ano@','Ano atual');
      $arrVariaveis[] = array('@mes_extenso@','Nome do mês atual');
      $arrVariaveis[] = array('@processo@','Número do processo');
      $arrVariaveis[] = array('@tipo_processo@','Tipo do processo');
      $arrVariaveis[] = array('@especificacao_processo@','Especificação do processo');
      $arrVariaveis[] = array('@observacao_processo@','Observação da unidade no processo');
      $arrVariaveis[] = array('@codigo_barras_processo@','Código de barras do número do processo (formato 3 de 9)');
      $arrVariaveis[] = array('@link_acesso_externo_processo@','Número do processo contendo um link para um acesso externo gerado automaticamente');
      $arrVariaveis[] = array('@documento@','Número do documento');
      $arrVariaveis[] = array('@serie@','Tipo do documento');
      $arrVariaveis[] = array('@numeracao_serie@','Numeração associada com o tipo de documento');
      $arrVariaveis[] = array('@descricao_documento@','Descrição do documento');
      $arrVariaveis[] = array('@observacao_documento@','Observação da unidade no documento');
      $arrVariaveis[] = array('@codigo_barras_documento@','Código de barras do número do documento (formato 3 de 9)');
      $arrVariaveis[] = array('@destinatarios_virgula_espaco@','Nomes dos destinatários separados por vírgula e espaço');
      $arrVariaveis[] = array('@destinatarios_virgula_espaco_maiusculas@','Nomes em letras maiúsculas dos destinatários separados por vírgula e espaço');
      $arrVariaveis[] = array('@destinatarios_quebra_linha@','Nomes dos destinatários separados por quebra de linha');
      $arrVariaveis[] = array('@destinatarios_quebra_linha_maiusculas@','Nomes em letras maiúsculas dos destinatários separados por quebra de linha');
      $arrVariaveis[] = array('@nome_destinatario@','Nome do primeiro destinatário');
      $arrVariaveis[] = array('@nome_destinatario_maiusculas@','Nome em letras maiúsculas do primeiro destinatário');
      $arrVariaveis[] = array('@tratamento_destinatario@','Tratamento associado com o primeiro destinatário');
      $arrVariaveis[] = array('@categoria_destinatario@','Categoria associada com o primeiro destinatário');
      $arrVariaveis[] = array('@cargo_destinatario@','Cargo associado com o primeiro destinatário');
      $arrVariaveis[] = array('@titulo_destinatario@','Título associado com o primeiro destinatário');
      $arrVariaveis[] = array('@titulo_abreviatura_destinatario@','Abreviatura do Título associada com o primeiro destinatário');
      $arrVariaveis[] = array('@funcao_destinatario@','Função associada com o primeiro destinatário');
      $arrVariaveis[] = array('@vocativo_destinatario@','Vocativo associado com o primeiro destinatário');
      $arrVariaveis[] = array('@artigo_destinatario_minuscula@','Artigo em letra minúscula associado com o sexo do primeiro destinatário');
      $arrVariaveis[] = array('@artigo_destinatario_maiuscula@','Artigo em letra maiúscula associado com o sexo do primeiro destinatário');
      $arrVariaveis[] = array('@cpf_destinatario@','Número do CPF do primeiro destinatário');
      $arrVariaveis[] = array('@numero_passaporte_destinatario@','Número do passaporte associado com o primeiro destinatário');
      $arrVariaveis[] = array('@pais_passaporte_destinatario@','País de emissão do passaporte do primeiro destinatário');
      $arrVariaveis[] = array('@rg_destinatario@','Número do RG do primeiro destinatário');
      $arrVariaveis[] = array('@orgao_expedidor_rg_destinatario@','Órgão Expedidor associado com o RG do primeiro destinatário');
      $arrVariaveis[] = array('@matricula_destinatario@','Número de matrícula do primeiro destinatário');
      $arrVariaveis[] = array('@matricula_oab_destinatario@','Número de matrícula da OAB do primeiro destinatário');
      $arrVariaveis[] = array('@cnpj_destinatario@','Número do CNPJ do primeiro destinatário');
      $arrVariaveis[] = array('@endereco_destinatario@','Endereço do primeiro destinatário');
      $arrVariaveis[] = array('@complemento_endereco_destinatario@','Complemento do endereço do primeiro destinatário');
      $arrVariaveis[] = array('@bairro_destinatario@','Bairro do primeiro destinatário');
      $arrVariaveis[] = array('@cep_destinatario@','CEP do primeiro destinatário');
      $arrVariaveis[] = array('@cidade_destinatario@','Cidade do primeiro destinatário');
      $arrVariaveis[] = array('@sigla_uf_destinatario@','Sigla da unidade federativa do primeiro destinatário');
      $arrVariaveis[] = array('@hifen_uf_destinatario@','Caractere hífen seguido da sigla da unidade federativa do primeiro destinatário');
      $arrVariaveis[] = array('@pais_destinatario@','País do primeiro destinatário');
      $arrVariaveis[] = array('@email_destinatario@','Endereço eletrônico do primeiro destinatário');
      $arrVariaveis[] = array('@sitio_internet_destinatario@','Sítio na internet do primeiro destinatário');
      $arrVariaveis[] = array('@telefone_comercial_destinatario@','Telefone comercial do primeiro destinatário');
      $arrVariaveis[] = array('@telefone_residencial_destinatario@','Telefone residencial do primeiro destinatário');
      $arrVariaveis[] = array('@telefone_celular_destinatario@','Telefone celular do primeiro destinatário');
      $arrVariaveis[] = array('@nome_pessoa_juridica_associada_destinatario@','Nome da pessoa jurídica associada com o primeiro destinatário');
      $arrVariaveis[] = array('@cnpj_pessoa_juridica_associada_destinatario@','CNPJ da pessoa jurídica associada com o primeiro destinatário');
      $arrVariaveis[] = array('@interessados_virgula_espaco@','Nomes dos interessados separados por vírgula e espaço');
      $arrVariaveis[] = array('@interessados_virgula_espaco_maiusculas@','Nomes em letras maiúsculas dos interessados separados por vírgula e espaço');
      $arrVariaveis[] = array('@interessados_quebra_linha@','Nomes dos interessados separados por quebra de linha');
      $arrVariaveis[] = array('@interessados_quebra_linha_maiusculas@','Nomes em letras maiúsculas dos interessados separados por quebra de linha');
      $arrVariaveis[] = array('@nome_interessado@','Nome do primeiro interessado');
      $arrVariaveis[] = array('@nome_interessado_maiusculas@','Nome em letras maiúsculas do primeiro interessado');
      $arrVariaveis[] = array('@tratamento_interessado@','Tratamento associado com o primeiro interessado');
      $arrVariaveis[] = array('@categoria_interessado@','Categoria associada com o primeiro interessado');
      $arrVariaveis[] = array('@cargo_interessado@','Cargo associado com o primeiro interessado');
      $arrVariaveis[] = array('@titulo_interessado@','Título associado com o primeiro interessado');
      $arrVariaveis[] = array('@titulo_abreviatura_interessado@','Abreviatura do Título associada com o primeiro interessado');
      $arrVariaveis[] = array('@funcao_interessado@','Função associada com o primeiro interessado');
      $arrVariaveis[] = array('@vocativo_interessado@','Vocativo associado com o primeiro interessado');
      $arrVariaveis[] = array('@artigo_interessado_minuscula@','Artigo em letra minúscula associado com o sexo do primeiro interessado');
      $arrVariaveis[] = array('@artigo_interessado_maiuscula@','Artigo em letra maiúscula associado com o sexo do primeiro interessado');
      $arrVariaveis[] = array('@cpf_interessado@','Número do CPF do primeiro interessado');
      $arrVariaveis[] = array('@numero_passaporte_interessado@','Número do passaporte associado com o primeiro interessado');
      $arrVariaveis[] = array('@pais_passaporte_interessado@','País de emissão do passaporte do primeiro interessado');
      $arrVariaveis[] = array('@rg_interessado@','Número do RG do primeiro interessado');
      $arrVariaveis[] = array('@orgao_expedidor_rg_interessado@','Órgão Expedidor associado com o RG do primeiro interessado');
      $arrVariaveis[] = array('@matricula_interessado@','Número de matrícula do primeiro interessado');
      $arrVariaveis[] = array('@matricula_oab_interessado@','Número de matrícula da OAB do primeiro interessado');
      $arrVariaveis[] = array('@cnpj_interessado@','Número do CNPJ do primeiro interessado');
      $arrVariaveis[] = array('@endereco_interessado@','Endereço do primeiro interessado');
      $arrVariaveis[] = array('@complemento_endereco_interessado@','Complemento do endereço do primeiro interessado');
      $arrVariaveis[] = array('@bairro_interessado@','Bairro do primeiro interessado');
      $arrVariaveis[] = array('@cep_interessado@','CEP do primeiro interessado');
      $arrVariaveis[] = array('@cidade_interessado@','Cidade do primeiro interessado');
      $arrVariaveis[] = array('@sigla_uf_interessado@','Sigla da unidade federativa do primeiro interessado');
      $arrVariaveis[] = array('@hifen_uf_interessado@','Caractere hífen seguido da sigla da unidade federativa do primeiro interessado');
      $arrVariaveis[] = array('@pais_interessado@','País do primeiro interessado');
      $arrVariaveis[] = array('@email_interessado@','Endereço eletrônico do primeiro interessado');
      $arrVariaveis[] = array('@sitio_internet_interessado@','Sítio na internet do primeiro interessado');
      $arrVariaveis[] = array('@telefone_comercial_interessado@','Telefone comercial do primeiro interessado');
      $arrVariaveis[] = array('@telefone_residencial_interessado@','Telefone residencial do primeiro interessado');
      $arrVariaveis[] = array('@telefone_celular_interessado@','Telefone celular do primeiro interessado');
      $arrVariaveis[] = array('@nome_pessoa_juridica_associada_interessado@','Nome da pessoa jurídica associada com o primeiro interessado');
      $arrVariaveis[] = array('@cnpj_pessoa_juridica_associada_interessado@','CNPJ da pessoa jurídica associada com o primeiro interessado');
      $arrVariaveis[] = array('@nome_usuario@','Nome do usuário logado');
      $arrVariaveis[] = array('@cargo_usuario@','Cargo do usuário logado');

      foreach($SEI_MODULOS as $seiModulo){
        if (($arr = $seiModulo->executar('obterRelacaoVariaveisEditor'))!=null){
          foreach ($arr as $variavel=>$descricao) {
            if(preg_match(EditorRN::$REGEXP_VARIAVEL_EDITOR,$variavel)!==1){
              throw new InfraException('Variável de editor inválida ['.$variavel.'] criada no módulo '.$seiModulo->getNome());
            }
            $arrVariaveis[]=array('@'.$variavel.'@',$descricao);
          }
        }
      }

      break;

    case 'ajuda_variaveis_tarjas':

      $strTitulo = 'Variáveis Disponíveis na Tarja';

      switch($_GET['tipo']) {
        case 'A':
          $arrVariaveis[] = array('@logo_assinatura@', 'Logotipo associado com a tarja');
          $arrVariaveis[] = array('@nome_assinante@', 'Nome do assinante');
          $arrVariaveis[] = array('@tratamento_assinante@', 'Cargo/Função utilizado pelo assinante');
          $arrVariaveis[] = array('@data_assinatura@', 'Data da assinatura no formato dd/mm/aaaa');
          $arrVariaveis[] = array('@hora_assinatura@', 'Hora da assinatura no formato hh:mm');
          $arrVariaveis[] = array('@codigo_verificador@', 'Código verificador necessário para validação da assinatura');
          $arrVariaveis[] = array('@crc_assinatura@', 'Código CRC necessário para validação da assinatura');
          $arrVariaveis[] = array('@numero_serie_certificado_digital@', 'Número de série do certificado digital utilizado na assinatura');
          $arrVariaveis[] = array('@tipo_conferencia@', 'Tipo de conferência realizada no documento externo');
          break;

        case 'V':
          $arrVariaveis[] = array('@qr_code@','QR Code com um link para a página de validação de assinatura');
          $arrVariaveis[] = array('@codigo_verificador@','Código verificador necessário para validação da assinatura');
          $arrVariaveis[] = array('@crc_assinatura@','Código CRC necessário para validação da assinatura');
          $arrVariaveis[] = array('@link_acesso_externo_processo@','Número do processo contendo um link para um acesso externo gerado automaticamente');
          break;
      }
      break;

    case 'ajuda_variaveis_email_sistema':

      $strTitulo = 'Variáveis Disponíveis';

      switch($_GET['campo']){
        case 'R':
          $strTitulo .= ' para Remetente';
          break;

        case 'D':
          $strTitulo .= ' para Destinatário';
          break;

        case 'A':
          $strTitulo .= ' para Assunto';
          break;

        case 'C':
          $strTitulo .= ' para Conteúdo';
          break;
      }

      switch($_GET['tipo']){

        case EmailSistemaRN::$ES_ENVIO_PROCESSO_PARA_UNIDADE:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@emails_unidade@','Lista de endereços eletrônicos da unidade no formato "Descrição do E-mail <E-mail>"');
              break;

            case 'A':
              $arrVariaveis[] = array('@processo@','Número do processo');
              break;

            case 'C':
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@tipo_processo@','Tipo do processo');
              $arrVariaveis[] = array('@sigla_unidade_remetente@','Sigla da unidade remetente');
              $arrVariaveis[] = array('@descricao_unidade_remetente@','Descrição da unidade remetente');
              $arrVariaveis[] = array('@sigla_orgao_unidade_remetente@','Sigla do órgão da unidade remetente');
              $arrVariaveis[] = array('@descricao_orgao_unidade_remetente@','Descrição do órgão da unidade remetente');
              $arrVariaveis[] = array('@sigla_unidade_destinataria@','Sigla da unidade destinatária');
              $arrVariaveis[] = array('@descricao_unidade_destinataria@','Descrição da unidade destinatária');
              $arrVariaveis[] = array('@sigla_orgao_unidade_destinataria@','Sigla do órgão da unidade destinatária');
              $arrVariaveis[] = array('@descricao_orgao_unidade_destinataria@','Descrição do órgão da unidade destinatária');
              break;
          }
          break;

        case EmailSistemaRN::$ES_CONCESSAO_CREDENCIAL:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@emails_unidade@','Lista de endereços eletrônicos da unidade no formato "Descrição do E-mail <E-mail>"');
              break;

            case 'A':
              $arrVariaveis[] = array('@processo@','Número do processo');
              break;

            case 'C':
              $arrVariaveis[] = array('@sigla_usuario_credencial@','Sigla do usuário que recebeu credencial');
              $arrVariaveis[] = array('@nome_usuario_credencial@','Nome do usuário que recebeu credencial');
              $arrVariaveis[] = array('@sigla_unidade_credencial@','Sigla da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@descricao_unidade_credencial@','Descrição da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@sigla_orgao_unidade_credencial@','Sigla do órgão da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@descricao_orgao_unidade_credencial@','Descrição do órgão da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              break;
          }
          break;

        case EmailSistemaRN::$ES_CONCESSAO_CREDENCIAL_ASSINATURA:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@emails_unidade@','Lista de endereços eletrônicos da unidade no formato "Descrição do E-mail <E-mail>"');
              break;

            case 'A':
              $arrVariaveis[] = array('@processo@','Número do processo');
              break;

            case 'C':
              $arrVariaveis[] = array('@sigla_usuario_credencial@','Sigla do usuário que recebeu credencial');
              $arrVariaveis[] = array('@nome_usuario_credencial@','Nome do usuário que recebeu credencial');
              $arrVariaveis[] = array('@sigla_unidade_credencial@','Sigla da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@descricao_unidade_credencial@','Descrição da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@sigla_orgao_unidade_credencial@','Sigla do órgão da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@descricao_orgao_unidade_credencial@','Descrição do órgão da unidade onde o usuário recebeu credencial');
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@documento@','Número do documento');
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              break;
          }
          break;

        case EmailSistemaRN::$ES_DISPONIBILIZACAO_ACESSO_EXTERNO:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@email_unidade@','Endereço eletrônico da unidade no formato "Descrição do E-mail <E-mail>"');
              break;

            case 'D':
              $arrVariaveis[] = array('@email_destinatario@','Endereço eletrônico do destinatário');
              break;

            case 'A':
              $arrVariaveis[] = array('@processo@','Número do processo');
              break;

            case 'C':
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@nome_destinatario@','Nome do destinatário');
              $arrVariaveis[] = array('@data_validade@','Data de validade do acesso externo');
              $arrVariaveis[] = array('@link_acesso_externo@','Link para acesso externo');
              $arrVariaveis[] = array('@sigla_unidade@','Sigla da unidade');
              $arrVariaveis[] = array('@descricao_unidade@','Descrição da unidade');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@descricao_orgao@','Descrição do órgão');
              $arrVariaveis[] = array('@sitio_internet_orgao@','Sítio do órgão na internet');
              break;
          }
          break;

        case EmailSistemaRN::$ES_DISPONIBILIZACAO_ACESSO_EXTERNO_USUARIO_EXTERNO:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@email_unidade@','Endereço eletrônico da unidade no formato "Descrição do E-mail <E-mail>"');
              break;

            case 'D':
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              break;

            case 'A':
              $arrVariaveis[] = array('@processo@','Número do processo');
              break;

            case 'C':
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@nome_usuario_externo@','Nome do usuário externo');
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              $arrVariaveis[] = array('@link_login_usuario_externo@','Endereço da página de login de usuários externos');
              $arrVariaveis[] = array('@sigla_unidade@','Sigla da unidade');
              $arrVariaveis[] = array('@descricao_unidade@','Descrição da unidade');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@descricao_orgao@','Descrição do órgão');
              $arrVariaveis[] = array('@sitio_internet_orgao@','Sítio do órgão na internet');
              break;
          }
          break;

        case EmailSistemaRN::$ES_DISPONIBILIZACAO_ASSINATURA_EXTERNA_USUARIO_EXTERNO:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@email_unidade@','Endereço eletrônico da unidade no formato "Descrição do E-mail <E-mail>"');
              break;

            case 'D':
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              break;

            case 'A':
              $arrVariaveis[] = array('@processo@','Número do processo');
              break;

            case 'C':
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@documento@','Número do documento');
              $arrVariaveis[] = array('@tipo_documento@','Tipo do documento');
              $arrVariaveis[] = array('@nome_usuario_externo@','Nome do usuário externo');
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              $arrVariaveis[] = array('@link_login_usuario_externo@','Endereço da página de login de usuários externos');
              $arrVariaveis[] = array('@sigla_unidade@','Sigla da unidade');
              $arrVariaveis[] = array('@descricao_unidade@','Descrição da unidade');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@descricao_orgao@','Descrição do órgão');
              $arrVariaveis[] = array('@sitio_internet_orgao@','Sítio do órgão na internet');
              break;
          }
          break;

        case EmailSistemaRN::$ES_CADASTRO_USUARIO_EXTERNO:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              break;

            case 'A':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              break;

            case 'C':
              $arrVariaveis[] = array('@nome_usuario_externo@','Nome do usuário externo');
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              $arrVariaveis[] = array('@link_login_usuario_externo@','Endereço da página de login de usuários externos');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@descricao_orgao@','Descrição do órgão');
              $arrVariaveis[] = array('@sitio_internet_orgao@','Sítio do órgão na internet');
              break;
          }
          break;

        case EmailSistemaRN::$ES_GERACAO_SENHA_USUARIO_EXTERNO:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              break;

            case 'A':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              break;

            case 'C':
              $arrVariaveis[] = array('@nova_senha_usuario_externo@','Nova senha gerada para o usuário externo');
              $arrVariaveis[] = array('@nome_usuario_externo@','Nome do usuário externo');
              $arrVariaveis[] = array('@email_usuario_externo@','Endereço eletrônico do usuário externo');
              $arrVariaveis[] = array('@link_login_usuario_externo@','Endereço da página de login de usuários externos');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@descricao_orgao@','Descrição do órgão');
              $arrVariaveis[] = array('@sitio_internet_orgao@','Sítio do órgão na internet');
              break;
          }
          break;

        case EmailSistemaRN::$ES_CONTATO_OUVIDORIA:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@sigla_orgao_minusculas@','Sigla do órgão em letras minúsculas');
              $arrVariaveis[] = array('@sufixo_email@','Sufixo do endereço eletrônico configurado no parâmetro SEI_SUFIXO_EMAIL da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@nome_contato@','Nome do usuário que realizou contato com a ouvidoria');
              $arrVariaveis[] = array('@email_contato@','Endereço eletrônico do usuário que realizou contato com a ouvidoria');
              break;

            case 'A':
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              break;

            case 'C':
              $arrVariaveis[] = array('@processo@','Número do processo');
              $arrVariaveis[] = array('@tipo_processo@','Tipo do processo');
              $arrVariaveis[] = array('@nome_contato@','Nome do usuário que realizou contato com a ouvidoria');
              $arrVariaveis[] = array('@email_contato@','Endereço eletrônico do usuário que realizou contato com a ouvidoria');
              $arrVariaveis[] = array('@sigla_orgao@','Sigla do órgão');
              $arrVariaveis[] = array('@descricao_orgao@','Descrição do órgão');
              $arrVariaveis[] = array('@sitio_internet_orgao@','Sítio do órgão na internet');
              break;
          }
          break;

        case EmailSistemaRN::$ES_CORRECAO_ENCAMINHAMENTO_OUVIDORIA:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SEI_EMAIL_SISTEMA da tabela de parâmetros');
              $arrVariaveis[] = array('@sigla_orgao_origem@','Sigla do órgão origem');
              $arrVariaveis[] = array('@sigla_orgao_destino@','Sigla do órgão destino');
              $arrVariaveis[] = array('@sigla_orgao_origem_minusculas@','Sigla do órgão origem em letras minúsculas');
              $arrVariaveis[] = array('@sigla_orgao_destino_minusculas@','Sigla do órgão destino em letras minúsculas');
              $arrVariaveis[] = array('@sufixo_email@','Sufixo do endereço eletrônico configurado no parâmetro SEI_SUFIXO_EMAIL da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@nome_contato@','Nome do usuário que realizou contato com a ouvidoria');
              $arrVariaveis[] = array('@email_contato@','Endereço eletrônico do usuário que realizou contato com a ouvidoria');
              break;

            case 'A':
              $arrVariaveis[] = array('@sigla_orgao_origem@','Sigla do órgão origem');
              $arrVariaveis[] = array('@sigla_orgao_destino@','Sigla do órgão destino');
              break;

            case 'C':
              $arrVariaveis[] = array('@processo_origem@','Número do processo origem');
              $arrVariaveis[] = array('@processo_destino@','Número do processo destino');
              $arrVariaveis[] = array('@tipo_processo@','Tipo do processo');
              $arrVariaveis[] = array('@nome_contato@','Nome do usuário que realizou contato com a ouvidoria');
              $arrVariaveis[] = array('@email_contato@','Endereço eletrônico do usuário que realizou contato com a ouvidoria');
              $arrVariaveis[] = array('@sigla_orgao_origem@','Sigla do órgão origem');
              $arrVariaveis[] = array('@sigla_orgao_destino@','Sigla do órgão destino');
              $arrVariaveis[] = array('@descricao_orgao_origem@','Descrição do órgão origem');
              $arrVariaveis[] = array('@descricao_orgao_destino@','Descrição do órgão destino');
              $arrVariaveis[] = array('@sitio_internet_orgao_origem@','Sítio do órgão origem na internet');
              $arrVariaveis[] = array('@sitio_internet_orgao_destino@','Sítio do órgão destino na internet');
              break;
          }
          break;
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $numRegistros = count($arrVariaveis);

  $strResultado = '';
  $strResultado .= '<table width="99%" class="infraTable" summary="Tabela de Variáveis Disponíveis">'."\n"; //80
  $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Variáveis Disponíveis',$numRegistros).'</caption>';
  $strResultado .= '<tr>';
  $strResultado .= '<th class="infraTh" width="30%">Variável</th>'."\n";
  $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
  $strResultado .= '</tr>'."\n";
  $strCssTr='';
  for($i = 0;$i < $numRegistros; $i++){

    $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
    $strResultado .= $strCssTr;

    $strResultado .= '<td><span style="font-family: Courier New">'.PaginaSEI::tratarHTML($arrVariaveis[$i][0]).'</span></td>';
    $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrVariaveis[$i][1]).'</td>';

    $strResultado .= '</tr>'."\n";
  }
  $strResultado .= '</table>';

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo);
PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>