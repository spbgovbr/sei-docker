<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/11/2015 - criado por bcu
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);


  $strParametros = '';
  $strParametros .= '&id_bloco='.$_GET['id_bloco'];

  switch($_GET['acao']){

    case 'bloco_navegar':

      $objBlocoDTO = new BlocoDTO();
      $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_ASSINATURA);
      $objBlocoDTO->retStrStaEstado();
      $objBlocoDTO->retNumIdUnidade();
      $objBlocoDTO->setNumIdBloco($_GET['id_bloco']);

      $objBlocoRN = new BlocoRN();
      $objBlocoDTO = $objBlocoRN->consultarRN1276($objBlocoDTO);

      $strTitulo = 'Documentos do Bloco de Assinatura '.$_GET['id_bloco'];

      if ($objBlocoDTO==null){
        throw new InfraException('Bloco '.$_GET['id_bloco'].' não encontrado.');
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
  $objRelBlocoProtocoloDTO->retDblIdProtocolo();
  $objRelBlocoProtocoloDTO->retNumIdBloco();
  $objRelBlocoProtocoloDTO->setNumSequencia($_GET['seq']);
  $objRelBlocoProtocoloDTO->retNumIdUnidadeBloco();
  $objRelBlocoProtocoloDTO->retStrProtocoloFormatadoProtocolo();
  $objRelBlocoProtocoloDTO->retStrStaProtocoloProtocolo();
  $objRelBlocoProtocoloDTO->retStrAnotacao();
  $objRelBlocoProtocoloDTO->setNumIdBloco($_GET['id_bloco']);
  $objRelBlocoProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
  $objRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->consultarRN1290($objRelBlocoProtocoloDTO);

  if ($objRelBlocoProtocoloDTO==null){
    throw new InfraException('Documento não encontrado no bloco.',null,null,false);
  }

  $strLinkAjaxAssinaturas=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assinaturas_documento');

  $strIdentificacao = '<label id="lblSeq"></label>';

  $strAcoes = '';

  $strAcoes .= '<a target="_blank" href="#" onclick="abrirArvore(this)"  tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'">
                   <img id="imgArvore" src="'.Icone::ARVORE.'" width="40" height="40" alt="Visualizar Árvore do Processo" title="Visualizar Árvore do Processo" />
                </a>'."\n\n";

  $strAcoes .= '<a href="javascript:void(0)" onclick="assinar()" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'">
                   <img id="imgAssinatura" src="'.Icone::DOCUMENTO_ASSINAR.'" width="40" height="40" alt="Assinar Documento" title="Assinar Documento" />
                </a>'."\n\n";

  $strAcoes .= '<div id="divSelecionar" class="infraDivCheckbox">
                 <input id="chkSelecionado" type="checkbox" onclick="processarClick();" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'" class="infraCheckbox">
                 <label id="lblSelecionado" for="chkSelecionado" class="infraLabelCheckbox">&nbsp;Selecionar para Assinatura</label>
                </div>'."\n\n";

  $strAcoes .= '<a href="javascript:void(0)" onclick="processarDocumento(window.posAnterior);" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'" >
                   <img id="imgEsquerda" src="'.Icone::BLOCO_NAVEGAR_SETA_ESQUERDA.'" width="40" height="40" alt="Documento Anterior" title="Documento Anterior" />
                </a>'."\n\n";

  $strAcoes .= '<a href="javascript:void(0)" onclick="processarDocumento(window.posProximo);" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'">
                  <img id="imgDireita" src="'.Icone::BLOCO_NAVEGAR_SETA_DIREITA.'" width="40" height="40" alt="Próximo Documento" title="Próximo Documento" />
                </a>'."\n\n";

  $strLinkDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo());

  SeiINT::montarCabecalhoConteudo($strIdentificacao, $strAcoes, $strLinkDocumento, $strCss, $strJsInicializar, $strJsCorpo, $strHtml, false);

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
echo '<meta name="viewport" content="width=980" />';
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
echo $strCss;
?>

#divSelecionar {float:left;font-size:1em;text-align:center;padding:10px 50px 0 10px;}
#lblSelecionado {font-size:.875rem;}

#imgEsquerda, #imgDireita {
  float:left;
  visibility:hidden;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="text/javascript">

  var posAtual=<?=$_GET['posicao'];?>;
  var janelaPai=window.parent;
  var trAtual=$(janelaPai.document.getElementById('trPos'+posAtual));
  var checkAtual=trAtual.find('[type=checkbox]');
  var posAnterior,posProximo;

  objAjaxAssinaturas = new infraAjaxComplementar(null,'<?=$strLinkAjaxAssinaturas?>');
  objAjaxAssinaturas.limparCampo = false;
  objAjaxAssinaturas.mostrarAviso = false;
  objAjaxAssinaturas.tempoAviso = 1000;

  objAjaxAssinaturas.prepararExecucao = function(){
    var re = /&id_documento=([^&]*)/;
    var match=re.exec(janelaPai.arrLinkDocumento[posAtual]);
    return '&idDocumento='+match[1];
  };

  objAjaxAssinaturas.processarResultado = function(arr){
    var base64=new infraBase64();
    trAtual.find('td:eq(5)').html(base64.decodificar(arr['assinaturas']));
    if (checkAtual.prop('checked')==true) {
      checkAtual.click();
      exibirCheckbox();
    }
  };

  function inicializar() {
    processarDocumento(posAtual);
    <?=$strJsInicializar?>
  }

  <?=$strJsCorpo?>

  function exibirCheckbox(){
    $('#chkSelecionado').prop('checked',checkAtual.prop('checked'));
  }

  function processarClick(){
    checkAtual.click();
    trAtual.addClass('infraTrAcessada');
    exibirCheckbox();
  }

  function exibirSetas(){
    var idPrev=trAtual.prev().attr('id');
    if (idPrev && idPrev.substr(0,5)=='trPos'){
      posAnterior=idPrev.substr(5);
      $('#imgEsquerda').css('visibility', 'visible');
    } else {
      posAnterior=null;
      $('#imgEsquerda').css('visibility', 'hidden');
    }
    var idNext=trAtual.next().attr('id');
    if (idNext && idNext.substr(0,5)=='trPos'){
      posProximo=idNext.substr(5);
      $('#imgDireita').css('visibility', 'visible');
    } else {
      posProximo=null;
      $('#imgDireita').css('visibility', 'hidden');
    }
  }

  function processarDocumento(posicao){
    if (posicao==null) return;
    posAtual = posicao;
    $('#lblSeq').html('Bloco de Assinatura '+ janelaPai.arrBloco[posicao] + ' - Sequencial ' + janelaPai.arrSequencial[posicao]);
    trAtual.parent().find('.infraTrAcessada').removeClass('infraTrAcessada');
    trAtual=$(janelaPai.document.getElementById('trPos'+posAtual));
    checkAtual=trAtual.addClass('infraTrAcessada').find('[type=checkbox]');
    if (!janelaPai.arrDocumentosVisualizados.hasOwnProperty(posicao)){
      janelaPai.arrDocumentosVisualizados[posicao]=true;
    }
    seiConteudoExibir(janelaPai.arrLinkDocumento[posAtual]);
    exibirCheckbox();
    exibirSetas();
  }

  function abrirArvore(link){
    link.href = janelaPai.arrLinkProcedimento[posAtual];
  }

  function assinar(){
    infraAbrirJanelaModal(janelaPai.arrLinkAssinatura[posAtual],600,450);
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
echo $strHtml;
PaginaSEI::getInstance()->fecharHtml();
?>