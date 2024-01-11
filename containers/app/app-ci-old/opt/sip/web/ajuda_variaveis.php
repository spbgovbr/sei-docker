<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/07/2018 - criado por mga
 *
 */

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  $arrVariaveis = array();

  switch($_GET['acao']){

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

        case EmailSistemaRN::$ES_ATIVACAO_2_FATORES:
        case EmailSistemaRN::$ES_DESATIVACAO_2_FATORES:
        case EmailSistemaRN::$ES_ALERTA_SEGURANCA:
        case EmailSistemaRN::$ES_AVISO_BLOQUEIO:

          switch($_GET['campo']) {
            case 'R':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@sigla_orgao_sistema@','Sigla do órgão do sistema');
              $arrVariaveis[] = array('@email_sistema@','Endereço eletrônico do sistema configurado no parâmetro SIP_EMAIL_SISTEMA da tabela de parâmetros');
              break;

            case 'D':
              $arrVariaveis[] = array('@email_usuario@','Endereço eletrônico do usuário');
              if ($_GET['tipo'] == EmailSistemaRN::$ES_ATIVACAO_2_FATORES || $_GET['tipo'] == EmailSistemaRN::$ES_DESATIVACAO_2_FATORES) {
                $arrVariaveis[] = array('@nome_usuario@', 'Nome do usuário');
              }
              break;

            case 'A':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@sigla_orgao_sistema@','Sigla do órgão do sistema');
              $arrVariaveis[] = array('@sigla_usuario@','Sigla do usuário');
              $arrVariaveis[] = array('@nome_usuario@','Nome do usuário');
              break;

            case 'C':
              $arrVariaveis[] = array('@sigla_sistema@','Sigla do sistema');
              $arrVariaveis[] = array('@sigla_orgao_sistema@','Sigla do órgão do sistema');
              $arrVariaveis[] = array('@sigla_usuario@','Sigla do usuário');
              $arrVariaveis[] = array('@nome_usuario@','Nome do usuário');
              $arrVariaveis[] = array('@data@','Data do acesso no formato dd/mm/aaaa');
              $arrVariaveis[] = array('@hora@','Hora do acesso no formato hh:mm');

              if ($_GET['tipo'] == EmailSistemaRN::$ES_ATIVACAO_2_FATORES) {
                $arrVariaveis[] = array('@endereco_ativacao@', 'Endereço para ativação do mecanismo');
              }else if ($_GET['tipo'] == EmailSistemaRN::$ES_DESATIVACAO_2_FATORES) {
                $arrVariaveis[] = array('@endereco_desativacao@', 'Endereço para desativação do mecanismo');
              }else if ($_GET['tipo'] == EmailSistemaRN::$ES_ALERTA_SEGURANCA) {
                $arrVariaveis[] = array('@endereco_bloqueio@', 'Endereço para bloqueio do usuário');
              }
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
  $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela('Variáveis Disponíveis',$numRegistros).'</caption>';
  $strResultado .= '<tr>';
  $strResultado .= '<th class="infraTh" width="30%">Variável</th>'."\n";
  $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
  $strResultado .= '</tr>'."\n";
  $strCssTr='';
  for($i = 0;$i < $numRegistros; $i++){

    $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
    $strResultado .= $strCssTr;

    $strResultado .= '<td><span style="font-family: Courier New">'.PaginaSip::tratarHTML($arrVariaveis[$i][0]).'</span></td>';
    $strResultado .= '<td>'.PaginaSip::tratarHTML($arrVariaveis[$i][1]).'</td>';

    $strResultado .= '</tr>'."\n";
  }
  $strResultado .= '</table>';

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo);
PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>