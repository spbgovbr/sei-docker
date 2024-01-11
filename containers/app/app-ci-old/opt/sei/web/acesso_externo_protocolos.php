<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 03/08/2016 - criado por mga
 *
 */

try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('acesso_externo_protocolo_selecionar');

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch ($_GET['acao']) {

    case 'acesso_externo_protocolo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Protocolo para Acesso Externo', 'Selecionar Protocolos para Acesso Externo');
      break;

    case 'acesso_externo_protocolo_detalhe':
      $strTitulo = 'Protocolos Disponibilizados para Acesso Externo';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $arrIdProtocolo = null;
  if ($_GET['acao'] == 'acesso_externo_protocolo_detalhe') {
    $objRelAcessoExtProtocoloDTO = new RelAcessoExtProtocoloDTO();
    $objRelAcessoExtProtocoloDTO->retDblIdProtocolo();
    $objRelAcessoExtProtocoloDTO->setNumIdAcessoExterno($_GET['id_acesso_externo']);

    $objRelAcessoExtProtocoloRN = new RelAcessoExtProtocoloRN();
    $arrIdProtocolo = InfraArray::converterArrInfraDTO($objRelAcessoExtProtocoloRN->listar($objRelAcessoExtProtocoloDTO),'IdProtocolo');

    if (count($arrIdProtocolo)==0){
      throw new InfraException('Nenhum protocolo liberado no Acesso Externo.');
    }
  }


  //Carregar dados do cabeçalho
  $objProcedimentoDTO = new ProcedimentoDTO();
  $objProcedimentoDTO->retStrNomeTipoProcedimento();
  $objProcedimentoDTO->retStrProtocoloProcedimentoFormatado();
  $objProcedimentoDTO->retDtaGeracaoProtocolo();
  $objProcedimentoDTO->retStrStaNivelAcessoGlobalProtocolo();

  $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objProcedimentoDTO->setStrSinDocTodos('S');
  $objProcedimentoDTO->setStrSinProcAnexados('S');

  if ($arrIdProtocolo!=null){
    $objProcedimentoDTO->setArrDblIdProtocoloAssociado($arrIdProtocolo);
  }

  $objProcedimentoRN = new ProcedimentoRN();
  $arr = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

  if (count($arr)==0){
    throw new InfraException('Processo não encontrado.');
  }

  $objProcedimentoDTO = $arr[0];


  $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();


  $numRegistros = 0;

  $objDocumentoRN = new DocumentoRN();

  $strResultado = '';

  $strThCheck = PaginaSEI::getInstance()->getThCheck();

  foreach($arrObjRelProtocoloProtocoloDTO as $objRelProtocoloProtocoloDTO){

    if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao()==RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO){

      $objDocumentoDTO = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

      if ($objDocumentoDTO->getDblIdDocumento() != $_GET['id_documento']) {

        if ($objDocumentoRN->verificarSelecaoAcessoBasico($objDocumentoDTO)) {

          $strResultado .= '<tr class="infraTrClara">';

          if (PaginaSEI::getInstance()->isBolPaginaSelecao()) {
            $strResultado .= '<td align="center">' . PaginaSEI::getInstance()->getTrCheck($numRegistros, $objDocumentoDTO->getDblIdDocumento(), $objDocumentoDTO->getStrProtocoloDocumentoFormatado() . ' (' . trim($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()) . ')') . '</td>';
          }

          $strResultado .= '<td align="center"><a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento=' . $objDocumentoDTO->getDblIdDocumento()) . '" target="_blank" alt="' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()) . '" title="' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()) . '" class="ancoraPadraoPreta" style="font-size:1em">' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()) . '</a></td>
                          <td align="center">' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie() . ' ' . $objDocumentoDTO->getStrNumero()) . '</td>
                          <td align="center"><a alt="' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" title="' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrDescricaoUnidadeGeradoraProtocolo()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objDocumentoDTO->getStrSiglaUnidadeGeradoraProtocolo()) . '</a></td>';

          if (PaginaSEI::getInstance()->isBolPaginaSelecao()) {
            $strResultado .= '<td align="center">' . PaginaSEI::getInstance()->getAcaoTransportarItem($numRegistros, $objDocumentoDTO->getDblIdDocumento()) . '</td>';
          }

          $strResultado .= '</tr>';

          $numRegistros++;
        }
      }

    }else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao()==RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO){

      $objProcedimentoDTOAnexado = $objRelProtocoloProtocoloDTO->getObjProtocoloDTO2();

      $strResultado .= '<tr class="infraTrClara">';

      if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
        $strResultado .= '<td align="center">' . PaginaSEI::getInstance()->getTrCheck($numRegistros, $objProcedimentoDTOAnexado->getDblIdProcedimento(), $objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado() . ' (' . $objProcedimentoDTOAnexado->getStrNomeTipoProcedimento() . ')') . '</td>';
      }

      $strResultado .= '<td align="center"><a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProcedimentoDTOAnexado->getDblIdProcedimento()).'" target="_blank" alt="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'" class="ancoraPadraoPreta" style="font-size:1em">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrProtocoloProcedimentoFormatado()).'</a></td>
                        <td align="center">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrNomeTipoProcedimento()).'</td>
                        <td align="center"><a alt="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()).'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrDescricaoUnidadeGeradoraProtocolo()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objProcedimentoDTOAnexado->getStrSiglaUnidadeGeradoraProtocolo()).'</a></td>';

      if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
        $strResultado .= '<td align="center">'.PaginaSEI::getInstance()->getAcaoTransportarItem($numRegistros,$objProcedimentoDTOAnexado->getDblIdProcedimento()).'</td>';
      }

      $strResultado .= '</tr>';

      $numRegistros++;
    }
  }

  if ($numRegistros) {

    $strSumarioTabela = 'Tabela de Protocolos.';
    $strCaptionTabela = 'Protocolos';

    $strResultado = '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n".
                    '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>'.
                    '<tr>'.
                    (PaginaSEI::getInstance()->isBolPaginaSelecao()?'<th class="infraTh" width="1%">' . $strThCheck . '</th>':'') . "\n".
                    '<th class="infraTh" width="20%">Protocolo</th>' . "\n".
                    '<th class="infraTh">Tipo</th>' . "\n".
                    '<th class="infraTh" width="20%">Unidade</th>' . "\n".
                    (PaginaSEI::getInstance()->isBolPaginaSelecao()?'<th class="infraTh" width="10%">Ações</th>':''). "\n".
                    '</tr>' .$strResultado .'</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  infraReceberSelecao();
  infraEfeitoTabelas();
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmProtocoloAcessoExternoSelecao" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>