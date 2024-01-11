<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/03/2010 - criado por mga
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

  switch($_GET['acao']){

    case 'pesquisa_solr_ajuda':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - Novidades');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
?>
<body id="bodyAjuda">
<div>
<blockquote>

  <br />

  <p style='text-align:center;font-weight:bold;font-size:16pt;'>Pesquisa</p>

  <div class="ajudaTexto">A pesquisa busca as informações para apresentar no resultado nos seguintes campos: <br />
    <ol>
      <li>No corpo dos documentos criados no próprio processo (tipo de documento, número, data, texto, sigla, assinatura, tudo que se visualiza no documento pode ser pesquisado);</li>
      <li>Nos documentos externos digitalizados com processamento de OCR - Reconhecimento Ótico de Caracteres (se sua unidade digitaliza documentos, certifique-se que a opção de OCR está ativa no programa de escaneamento);</li>
      <li>Nos documentos externos de texto (planilhas, txt, html, doc, docx, xls, pdf, etc.);</li>
      <li>Nos dados cadastrais de processos e documentos.</li>
    </ol>
    <br>
    A pesquisa pode ser realizada por:
  </div>

  <div class="ajudaTitulo">1. Palavras, Siglas, Expressões ou Números</div>
  <blockquote>
    <div class="ajudaTexto">Busca ocorrências de uma determinada palavra, sigla, expressão (deve ser informada entre aspas duplas) ou número:</div>
    <div class="ajudaExemplo">prescrição</div>
    <br>
    <div class="ajudaExemplo">certidão INSS</div>
    <br>
    <div class="ajudaExemplo">declaração "imposto de renda"</div>
    <br>
    <div class="ajudaExemplo">portaria 744</div>
    <br>
    <br>
  </blockquote>

  <div class="ajudaTitulo">2. Busca por parte de Palavras ou Números (*)</div>
  <blockquote>
    <div class="ajudaTexto">Procura registros que contenham parte da palavra ou número:</div>
    <div class="ajudaExemplo">embarg* (retornará registros com <strong>embarg</strong>o, <strong>embarg</strong>ou,<strong>embarg</strong>ante,...)</div>
    <br>
    <div class="ajudaExemplo">201.7* (retornará registros contendo <strong>201.7</strong>98.988-00, <strong>201.7</strong>19,43, <strong>201.7</strong>1, ...)</div>
    <br>
  </blockquote>

  <div class="ajudaTitulo">3. Conector (E)</div>
  <blockquote>
    <div class="ajudaTexto">Pesquisa por registros que contenham todas as palavras e expressões:</div>
    <div class="ajudaExemplo">móvel e licitação</div>
    <br>
    <div class="ajudaExemplo">nomeação e "cargo efetivo"</div>
    <br>

    Este conector será utilizado automaticamente caso nenhum outro seja informado.
  </blockquote>

  <div class="ajudaTitulo">4. Conector (OU)</div>
  <blockquote>
    <div class="ajudaTexto">Pesquisa por registros que contenham pelo menos uma das palavras ou expressões:</div>
    <div class="ajudaExemplo">funcionário ou servidor</div>
    <br>
  </blockquote>

  <div class="ajudaTitulo">5. Conector (NÃO)</div>
  <blockquote>
    <div class="ajudaTexto">Recupera registros que contenham a primeira, mas não a segunda palavra ou expressão, isto é, exclui os registros que contenham a palavra ou expressão seguinte ao conector (NÃO):</div>
    <div class="ajudaExemplo">certidão não INSS</div>
    <br>
  </blockquote>

</blockquote>
</div>
</body>
<?
PaginaSEI::getInstance()->fecharHtml();
?>