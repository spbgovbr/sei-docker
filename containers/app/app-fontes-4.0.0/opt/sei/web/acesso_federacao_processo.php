<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/05/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_instalacao_federacao', 'id_orgao_federacao', 'id_procedimento_federacao', 'id_procedimento_federacao_anexado'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrComandos = array();
  $objVisualizarProcessoFederacaoDTORet = null;
  $objProcedimentoDTO = null;
  $strConjuntoProtocolos = '';
  $strResultadoCabecalho = '';
  $strResultado = '';
  $numDocumentosCheck = 0;
  $numMaxProtocolos = 100;
  $arrPdf = array();

  $strConjuntoProtocolos = !isset($_GET['id_procedimento_federacao_anexado']) ? $_GET['id_procedimento_federacao'] : $_GET['id_procedimento_federacao_anexado'];

  switch($_GET['acao']){

    case 'processo_consulta_federacao':

      $strTitulo = 'Consulta de Processo do SEI Federação';

      $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
      $objVisualizarProcessoFederacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);
      $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($_GET['id_procedimento_federacao']);

      if (isset($_GET['id_procedimento_federacao_anexado'])) {
        $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacaoAnexado($_GET['id_procedimento_federacao_anexado']);
      }

      $objVisualizarProcessoFederacaoDTO->setStrSinProtocolos('S');
      $objProtocoloDTOPaginacao = new ProtocoloDTO();
      if (!isset($_POST['hdnMaxProtocolos'])) {
        $objProtocoloDTOPaginacao->setNumPaginaAtual(0);
        $objProtocoloDTOPaginacao->setNumMaxRegistrosRetorno(null);
      }else{
        PaginaSEI::getInstance()->prepararPaginacao($objProtocoloDTOPaginacao, $_POST['hdnMaxProtocolos'], false, null, $strConjuntoProtocolos);
      }
      $objVisualizarProcessoFederacaoDTO->setNumPagProtocolos($objProtocoloDTOPaginacao->getNumPaginaAtual());
      $objVisualizarProcessoFederacaoDTO->setNumMaxProtocolos($objProtocoloDTOPaginacao->getNumMaxRegistrosRetorno());

      $objVisualizarProcessoFederacaoDTO->setStrSinAndamentos('N');

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      $objVisualizarProcessoFederacaoDTORet = $objAcessoFederacaoRN->visualizarProcesso($objVisualizarProcessoFederacaoDTO);

      if (!isset($_POST['hdnMaxProtocolos'])) {
        PaginaSEI::getInstance()->prepararPaginacao($objProtocoloDTOPaginacao, $objVisualizarProcessoFederacaoDTORet->getNumMaxProtocolos(), false, null, $strConjuntoProtocolos);
        $numMaxProtocolos = $objVisualizarProcessoFederacaoDTORet->getNumMaxProtocolos();
      }else{
        $numMaxProtocolos = $_POST['hdnMaxProtocolos'];
      }

      $objProtocoloDTOPaginacao->setNumRegistrosPaginaAtual($objVisualizarProcessoFederacaoDTORet->getNumRegProtocolos());
      $objProtocoloDTOPaginacao->setNumTotalRegistros($objVisualizarProcessoFederacaoDTORet->getNumTotProtocolos());
      PaginaSEI::getInstance()->processarPaginacao($objProtocoloDTOPaginacao, $strConjuntoProtocolos);
      break;

    case 'procedimento_gerar_pdf':

        $strTitulo = 'Geração de PDF de Processo do SEI Federação';

        $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
        $objVisualizarProcessoFederacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);
        $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($_GET['id_procedimento_federacao']);
        $objVisualizarProcessoFederacaoDTO->setStrIdDocumentoFederacao(PaginaSEI::getInstance()->getArrStrItensSelecionados($strConjuntoProtocolos));

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $strLink = $objAcessoFederacaoRN->gerarPdf($objVisualizarProcessoFederacaoDTO);
        header('Location: '.$strLink);
        die;

    case 'procedimento_gerar_zip':

        $strTitulo = 'Geração de ZIP de Processo do SEI Federação';

        $objVisualizarProcessoFederacaoDTO = new VisualizarProcessoFederacaoDTO();
        $objVisualizarProcessoFederacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);
        $objVisualizarProcessoFederacaoDTO->setStrIdProcedimentoFederacao($_GET['id_procedimento_federacao']);
        $objVisualizarProcessoFederacaoDTO->setStrIdDocumentoFederacao(PaginaSEI::getInstance()->getArrStrItensSelecionados($strConjuntoProtocolos));

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $strLink = $objAcessoFederacaoRN->gerarZip($objVisualizarProcessoFederacaoDTO);
        header('Location: '.$strLink);
        die;

	  default:
	    throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objProcedimentoDTO = $objVisualizarProcessoFederacaoDTORet->getObjProcedimentoDTO();

  $strResultadoCabecalho = FederacaoINT::montarTabelaAutuacao($objProcedimentoDTO);

  $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

  $numProtocolos = $objVisualizarProcessoFederacaoDTORet->getNumTotProtocolos();

  if ($numProtocolos) {

    $strResultado .= '<table id="tblProtocolos" width="99.3%" class="infraTable" summary="Lista de Protocolos" >
  					  									<caption class="infraCaption" >'.PaginaSEI::getInstance()->gerarCaptionTabela('Protocolos', $numProtocolos, 'Lista de ', $strConjuntoProtocolos).'</caption>'.
        "\n\n". //auditoria
        '<tr>
                                  <th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck('',$strConjuntoProtocolos).'</th>
                                  <th class="infraTh" width="20%">Processo / Documento</th>
  					  										<th class="infraTh">Tipo</th>
  					  										<th class="infraTh" width="10%">Unidade</th>
  					  										<th class="infraTh" width="10%">Órgão</th>
  					  										<th class="infraTh" width="15%">Data</th>
                                  <th class="infraTh" width="10%">Ações</th>
  					  									</tr>'.
        "\n\n"; //auditoria

    $strCssTr = '';
    foreach ($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO) {

      if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO || $objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {
        $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
        $strResultado .= '<tr '.$strCssTr.'>'."\n";

        $strAcoes = "";

        if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO) {

          $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

          $strIcones = '';
          $strLinkDocumento = '<a href="javascript:void(0);"';
          if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico() == 'S') {
            $strLinkDocumento .= ' class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);window.open(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_consulta_federacao&id_documento_federacao='.$objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo()).'\');"';
          } else if ($objDocumentoDTO->getStrStaEstadoProtocolo() == ProtocoloRN::$TE_DOCUMENTO_CANCELADO) {
            $strLinkDocumento .= ' class="ancoraPadraoPreta" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Documento cancelado.\')" style="text-decoration: line-through"';
          } else {
            $strLinkDocumento .= ' class="ancoraPadraoPreta" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Sem acesso ao documento.\')"';
          }
          $strLinkDocumento .= ' alt="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'" title="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'">'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()).'</a>';

          $bolCheck = false;
          if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico() == 'S') {
            $strAcoes = '<img src="'.Icone::FEDERACAO_LINK.'" id="'.$objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo().'" class="infraImg imgAcoes" tipo="d" instalacaoFederacao="'.$_GET['id_instalacao_federacao'].'" processoFederacao="'.$_GET['id_procedimento_federacao'].'" documentoFederacao="'.$objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo().'" protocoloNumero="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()).'" protocoloDescricao="'.PaginaSEI::tratarHTML(trim($objDocumentoDTO->getStrNomeSerie().' '.$objDocumentoDTO->getStrNumero())).'" orgaoSigla="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrSiglaOrgaoUnidadeGeradoraProtocolo()).'" />';

            if ($objDocumentoDTO->isSetArrObjAssinaturaDTO()) {
              $strTextoAssinatura = DocumentoINT::montarTooltipAssinatura($objDocumentoDTO);
              $strImagemAssinatura = ($objDocumentoDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_DOCUMENTO_GERADO) ? Icone::DOCUMENTO_ASSINAR : Icone::DOCUMENTO_AUTENTICAR;
              $strIcones .= '<a onclick="alert(\''.PaginaSEI::formatarParametrosJavaScript($strTextoAssinatura).'\')" title="'.str_replace("\n", '&#10;', $strTextoAssinatura).'"><img src="'.$strImagemAssinatura.'" class="imagemStatusFederacao" /></a>';
            }else{
              $strIcones .= '<div class="iconeVazio"></div>';
            }

            if ($objDocumentoDTO->isSetObjPublicacaoDTO()) {
              $strTextoPublicacao = PaginaSEI::tratarHTML($objDocumentoDTO->getObjPublicacaoDTO()->getStrTextoInformativo());
              $strIcones .= '<a onclick="alert(\''.PaginaSEI::formatarParametrosJavaScript($strTextoPublicacao).'\')" title="'.str_replace("\n", '&#10;', $strTextoPublicacao).'"><img src="'.Icone::PUBLICACAO.'" class="imagemStatusFederacao" /></a>';
            }else{
              $strIcones .= '<div class="iconeVazio"></div>';
            }


            if ($objDocumentoDTO->getStrSinPdf() == 'S') {
              $bolCheck = true;
            } else {
              $arrPdf[] = $objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo();
            }

            if ($bolCheck || $objDocumentoDTO->getStrSinZip() == 'S') {
              $bolCheck = true;
            }
          }else{
            $strIcones .= '<div class="iconeVazio"></div><div class="iconeVazio"></div>';
          }

          if ($bolCheck) {
            $strResultado .= '<td align="center">'.PaginaSEI::getInstance()->getTrCheck($numDocumentosCheck++, $objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo(), $objDocumentoDTO->getStrNomeSerie(),'N',$strConjuntoProtocolos).'</td>';
          } else {
            $strResultado .= '<td>&nbsp;</td>';
          }

          $strResultado .= '<td align="center">'.$strLinkDocumento.$strIcones.'</td>
                            <td align="left">'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie().' '.$objDocumentoDTO->getStrNumero()).'</td>
                            <td align="center"><a alt="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()).'" title="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo()).'</a></td>
                            <td align="center"><a alt="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()).'" title="'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrSiglaOrgaoUnidadeGeradoraProtocolo()).'</a></td>
                            <td align="center">'.PaginaSEI::tratarHTML($objDocumentoDTO->getDtaGeracaoProtocolo()).'</td>
                            <td align="center">'.$strAcoes.'</td>'."\n";

        } else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao() == RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO) {

          $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

          $strLinkProcessoAnexado = '<a href="javascript:void(0);"';
          if ($objRelProtocoloProtocoloDTO->getStrSinAcessoBasico() == 'S') {
            $strAcoes = '<img src="'.Icone::FEDERACAO_LINK.'" id="'.$objDocumentoDTO->getStrIdProtocoloFederacaoProtocolo().'" class="infraImg imgAcoes" tipo="p" instalacaoFederacao="'.$_GET['id_instalacao_federacao'].'" processoFederacao="'.$_GET['id_procedimento_federacao'].'" processoAnexadoFederacao="'.$objProcedimentoDTOAnexado->getStrIdProtocoloFederacaoProtocolo().'" protocoloNumero="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado()).'" protocoloDescricao="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'" orgaoSigla="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrSiglaOrgaoUnidadeGeradoraProtocolo()).'" />';
            $strLinkProcessoAnexado .= ' class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);window.open(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=processo_consulta_federacao&id_procedimento_federacao_anexado='.$objProcedimentoDTOAnexado->getStrIdProtocoloFederacaoProtocolo()).'\');"';
          } else {
            $strLinkProcessoAnexado .= ' class="ancoraPadraoPreta" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Sem acesso ao processo anexado.\')"';
          }
          $strLinkProcessoAnexado .= ' alt="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'" >'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado()).'</a>';

          $strResultado .= '<td>&nbsp;</td>
                            <td align="center">'.$strLinkProcessoAnexado.'</td>
                            <td align="left">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'</td>
                            <td align="center"><a alt="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()).'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrSiglaUnidadeGeradoraProtocolo()).'</a></td>
                            <td align="center"><a alt="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()).'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrSiglaOrgaoUnidadeGeradoraProtocolo()).'</a></td>
                            <td align="center">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getDtaGeracaoProtocolo()).'</td>
                            <td align="center">'.$strAcoes.'</td>';

        }

        $strResultado .= '</tr>';

        //facilita visualização do texto auditado
        $strResultado .= "\n\n";
      }
    }
    $strResultado .= '</table><br>'."\n";
  }

  if (SessaoSEI::getInstance()->verificarPermissao('andamentos_consulta_federacao')) {
    $arrComandos[] = '<button type="button" accesskey="A" name="btnAndamentos" value="Andamentos" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamentos_consulta_federacao&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">A</span>ndamentos</button>';
  }

  $bolPdf = SessaoSEI::getInstance()->verificarPermissao('procedimento_gerar_pdf');
  $bolZip = SessaoSEI::getInstance()->verificarPermissao('procedimento_gerar_zip');
  if ($numDocumentosCheck > 0){
    if ($bolPdf) {
      $arrComandos[] = '<button type="button" accesskey="P" name="btnGerarPdf" value="Gerar PDF" onclick="gerarPdf();" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>DF</button>';
      $strLinkPdf = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_gerar_pdf&acao_origem='.$_GET['acao']);
    }

    if ($bolZip) {
      $arrComandos[] = '<button type="button" accesskey="Z" name="btnGerarZip" value="Gerar ZIP" onclick="gerarZip();" class="infraButton">Gerar <span class="infraTeclaAtalho">Z</span>IP</button>';
      $strLinkZip = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_gerar_zip&acao_origem='.$_GET['acao']);
    }
  }

  $strLinkMontarArvore = '';
  if (!isset($_GET['id_procedimento_federacao_anexado']) && $objVisualizarProcessoFederacaoDTORet != null && $objVisualizarProcessoFederacaoDTORet->getBolAtualizarArvore()) {
    $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$objProcedimentoDTO->getDblIdProcedimento().'&montar_visualizacao=0');
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

  div.iconeVazio {
    display:inline-block;
    width:24px;
  }

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->adicionarJavaScript('js/popover/popper.min.js');
PaginaSEI::getInstance()->adicionarJavaScript('js/clipboard/clipboard.min.js');
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  //<script>
  function associarNosClipboard(){
    var arrImgAcoes =  $('.imgAcoes');
    for(var i=0;i<arrImgAcoes.length;i++){
      var imgAcoes = $(arrImgAcoes[i]);
      var id = 'popover-content' + imgAcoes.attr("id");
      var divConteudoPopover = null;

      divConteudoPopover = $(
          '<div id="' + id + '" style="display: none;position:relative;">\n' +
          '  <ul class="list-group custom-popover" >\n' +
          '    <li popoverId="' + imgAcoes.attr("id") + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + imgAcoes.attr("protocoloNumero") + "/" + imgAcoes.attr("orgaoSigla") + '" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">' + imgAcoes.attr("protocoloNumero") + "/" + imgAcoes.attr("orgaoSigla") + '</span></li>\n' +
          '    <li popoverId="' + imgAcoes.attr("id") + '" tipo="texto" onclick="copiarParaClipboard(this)" data-clipboard-text="' + imgAcoes.attr("protocoloDescricao") + ' (' + imgAcoes.attr("protocoloNumero") + "/" + imgAcoes.attr("orgaoSigla") + ')" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_texto.svg" />&nbsp;<span class="align-self-center">' + imgAcoes.attr("protocoloDescricao") + ' (' + imgAcoes.attr("protocoloNumero") + "/" + imgAcoes.attr("orgaoSigla") + ')</span></li>\n' +
          '    <li popoverId="' + imgAcoes.attr("id") + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#' + imgAcoes.attr("tipo") + '{' + imgAcoes.attr("instalacaoFederacao") + '|' + imgAcoes.attr("processoFederacao") + '|' + (imgAcoes.attr("tipo") == 'd' ? imgAcoes.attr("documentoFederacao") : imgAcoes.attr("processoAnexadoFederacao")) + '|' + imgAcoes.attr("protocoloNumero") + '/' + imgAcoes.attr("orgaoSigla") + '}#" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_editor.svg"/>&nbsp;<span class="align-self-center">' + imgAcoes.attr("protocoloNumero") + "/" + imgAcoes.attr("orgaoSigla") + '</span></li>\n' +
          '    <li popoverId="' + imgAcoes.attr("id") + '" tipo="link" onclick="copiarParaClipboard(this)" data-clipboard-text="#' + imgAcoes.attr("protocoloDescricao") + ' (' + imgAcoes.attr("tipo") + '{' + imgAcoes.attr("instalacaoFederacao") + '|' + imgAcoes.attr("processoFederacao") + '|' + (imgAcoes.attr("tipo") == 'd' ? imgAcoes.attr("documentoFederacao") : imgAcoes.attr("processoAnexadoFederacao")) + '|' + imgAcoes.attr("protocoloNumero") + '/' + imgAcoes.attr("orgaoSigla") + '})#" class="list-group-item d-flex flex-row clipboard" ><img class="align-self-center" src="imagens/arvore_copiar_editor.svg"/>&nbsp;<span class="align-self-center">' + imgAcoes.attr("protocoloDescricao") + ' (' + imgAcoes.attr("protocoloNumero") + "/" + imgAcoes.attr("orgaoSigla") + ')</span></li>\n' +
          '    <li popoverId="' + imgAcoes.attr("id") + '"  onclick="fecharClipboard(this)" class="list-group-item d-flex flex-row li-fechar" ><span class="align-self-center">Fechar</span></li>\n' +
          '  </ul>\n' +
          '</div>'
      );

      $("body").append(divConteudoPopover);
      imgAcoes.attr("data-toggle","popover");
      imgAcoes.attr("data-placement","left");

      imgAcoes.popover({
        html: true,
        sanitize: false,
        content: function() {
          return $("#"+'popover-content'+ this.id) .html();
        }
      });
      imgAcoes.on('show.bs.popover', function () {
        $("img[data-toggle=popover]").not($(this)).popover("hide");
      })

    }
  }


  function inicializar(){

    infraEfeitoTabelas();

    <? if ($strLinkMontarArvore!=''){ ?>
    parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
    <? } ?>

    associarNosClipboard();

  }

<? if ($bolPdf){ ?>
  function gerarPdf() {

    if (document.getElementById('hdn<?=$strConjuntoProtocolos?>ItensSelecionados').value==''){
      alert('Nenhum documento selecionado.');
      return;
    }

    var pdf = document.getElementById('hdnPdf').value;

    var erro = 0;

    if (pdf!='') {

      selecionados = document.getElementById('hdn<?=$strConjuntoProtocolos?>ItensSelecionados').value;

      if (selecionados!='') {

        pdf = pdf.split(',');
        selecionados = selecionados.split(',');

        for (var j = 0; j<<?=$numDocumentosCheck?>; j++) {

          box = document.getElementById('chk<?=$strConjuntoProtocolos?>Item'+j);

          if (!box.checked){

            infraFormatarTrDesmarcada(box.parentNode.parentNode);

          }else {

            for (var i = 0; i<pdf.length; i++) {
              if (pdf[i]==box.value) {
                box.checked = false;
                infraFormatarTrAcessada(box.parentNode.parentNode);
                erro += 1;
              }
            }
          }
        }
      }
    }

    if (erro) {

      var msg = '';
      if (erro==1){
        msg = 'Não é possível gerar o PDF para o documento destacado.';
      }else{
        msg = 'Não é possível gerar o PDF para os documentos destacados.';
      }

      msg += '\n\nDeseja continuar?';

      if (!confirm(msg)){
        return;
      }
    }

    infraSelecionarItens(null,'<?=$strConjuntoProtocolos?>');

    if (document.getElementById('hdn<?=$strConjuntoProtocolos?>ItensSelecionados').value==''){
      alert('Nenhum documento selecionado.');
      return;
    }

    document.getElementById('frmAcessoFederacaoProcesso').action = '<?=$strLinkPdf?>';
    document.getElementById('frmAcessoFederacaoProcesso').target = '_blank';
    document.getElementById('frmAcessoFederacaoProcesso').submit();
  }
<? } ?>

<? if ($bolZip){ ?>

  function gerarZip() {

    if (document.getElementById('hdn<?=$strConjuntoProtocolos?>ItensSelecionados').value==''){
      alert('Nenhum documento selecionado.');
      return;
    }

    document.getElementById('frmAcessoFederacaoProcesso').action = '<?=$strLinkZip?>';
    document.getElementById('frmAcessoFederacaoProcesso').target = '_blank';
    document.getElementById('frmAcessoFederacaoProcesso').submit();
  }

  function OnSubmitForm(){
    return true;
  }
<? } ?>

  //</script>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcessoFederacaoProcesso" method="post" onsubmit="return OnSubmitForm();">
<?
  if ($strResultadoCabecalho!='') {
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    echo $strResultadoCabecalho.'<br>';
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numProtocolos, false, '', null, $strConjuntoProtocolos);
  }
?>
  <input type="hidden" id="hdnPdf" name="hdnPdf" value="<?=implode(',',$arrPdf)?>" />
  <input type="hidden" id="hdnMaxProtocolos" name="hdnMaxProtocolos" value="<?=$numMaxProtocolos?>" />

</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>