<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/12/2018 - criado por cjy
 *
 * Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->prepararSelecao('edital_eliminacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selStaEditalEliminacao'));

  $objEditalEliminacaoRN = new EditalEliminacaoRN();

  switch ($_GET['acao']) {
    //excluir padrao
    case 'edital_eliminacao_excluir':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjEditalEliminacaoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
          $objEditalEliminacaoDTO->setNumIdEditalEliminacao($arrStrIds[$i]);
          $arrObjEditalEliminacaoDTO[] = $objEditalEliminacaoDTO;
        }
        $objEditalEliminacaoRN->excluir($arrObjEditalEliminacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'edital_eliminacao_gerar':
      $strTitulo = 'Editais de Eliminação';
      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO->setNumIdEditalEliminacao($_GET['id_edital_eliminacao']);
      try {
        $objEditalEliminacaoRN->gerar($objEditalEliminacaoDTO);
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_listar&acao_origem=edital_eliminacao_listar' . PaginaSEI::getInstance()->montarAncora($_GET['id_edital_eliminacao'])));
      die;
      break;

    case 'edital_eliminacao_eliminados_gerar':
      $strTitulo = 'Editais de Eliminação';
      $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
      $objEditalEliminacaoDTO->setNumIdEditalEliminacao($_GET['id_edital_eliminacao']);
      try {
        $objDocumento_EliminadosGerado =$objEditalEliminacaoRN->gerarEliminados($objEditalEliminacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem("Documento ".$objDocumento_EliminadosGerado->getStrProtocoloDocumentoFormatado()." gerado no Processo do Edital de Eliminação.", InfraPagina::$TIPO_MSG_AVISO);
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_listar&acao_origem=edital_eliminacao_listar' . PaginaSEI::getInstance()->montarAncora($_GET['id_edital_eliminacao'])));
      die;
      break;

    //listar padrao
    case 'edital_eliminacao_listar':
      $strTitulo = 'Editais de Eliminação';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }
  $arrComandos = array();
  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_cadastrar');
  if ($bolAcaoCadastrar) {
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }
  //dto para filtro/listagem de editais de eliminacao
  $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
  $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
  $objEditalEliminacaoDTO->retStrEspecificacao();
  $objEditalEliminacaoDTO->retDtaPublicacao();
  $objEditalEliminacaoDTO->retStrStaEditalEliminacao();
  $objEditalEliminacaoDTO->retStrProcedimentoFormatado();
  $objEditalEliminacaoDTO->retStrDocumentoFormatado();
  $objEditalEliminacaoDTO->retDblIdProcedimento();
  $objEditalEliminacaoDTO->retDblIdDocumento();
  //filtros e ordenacao
  $objEditalEliminacaoDTO->setNumIdOrgaoUnidade(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
  $strStaEditalEliminacao = PaginaSEI::getInstance()->recuperarCampo('selStaEditalEliminacao');
  if ($strStaEditalEliminacao !== '') {
    $objEditalEliminacaoDTO->setStrStaEditalEliminacao($strStaEditalEliminacao);
  }
  PaginaSEI::getInstance()->prepararOrdenacao($objEditalEliminacaoDTO, 'IdEditalEliminacao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objEditalEliminacaoDTO);

  $objEditalEliminacaoRN = new EditalEliminacaoRN();
  //listagem
  $arrObjEditalEliminacaoDTO = $objEditalEliminacaoRN->listar($objEditalEliminacaoDTO);

  $numRegistros = count($arrObjEditalEliminacaoDTO);

  if ($numRegistros > 0) {
    //recursos
    $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_reativar');;
    $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_consultar');
    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_alterar');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_excluir');
    $bolAcaoEliminadosGerar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_eliminados_gerar');
    $bolAcaoGerar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_gerar');
    $bolAcaoEditalEliminar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_eliminar');
    $bolAcaoEditalEliminacaoConteudoListar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_conteudo_listar');

    $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_excluir&acao_origem=' . $_GET['acao']);

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Editais de Eliminação.';
    $strCaptionTabela = 'Editais de Eliminação';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display: none;">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoDTO, 'Especificação', 'Especificacao', $arrObjEditalEliminacaoDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoDTO, 'Situação', 'StaEditalEliminacao', $arrObjEditalEliminacaoDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoDTO, 'Processo', 'StrProcedimentoFormatado', $arrObjEditalEliminacaoDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">' . PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoDTO, 'Edital', 'StrDocumentoFormatado', $arrObjEditalEliminacaoDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoDTO, 'Publicação', 'Publicacao', $arrObjEditalEliminacaoDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    //parametro de sistema que indica quantos dias um edital pode ser eliminado após sua publicacao
    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $strNumDiasPrazoEliminacao = trim($objInfraParametro->getValor('SEI_NUM_DIAS_PRAZO_ELIMINACAO'));

    if ($strNumDiasPrazoEliminacao==''){
      throw new InfraException('Parâmetro SEI_NUM_DIAS_PRAZO_ELIMINACAO não foi configurado.');
    }

    for ($i = 0; $i < $numRegistros; $i++) {
      //situacao do edital (chave e descricao)
      $strStaEditalChave = $arrObjEditalEliminacaoDTO[$i]->getStrStaEditalEliminacao();
      $strStaEditalDescricao = $objEditalEliminacaoRN->buscarValorEditalEliminacao($strStaEditalChave);
      $numIdEditalEliminacao = $arrObjEditalEliminacaoDTO[$i]->getNumIdEditalEliminacao();
      //data de publicacao
      $dtaPublicacao = $arrObjEditalEliminacaoDTO[$i]->getDtaPublicacao();

      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top" style="display: none;">' . PaginaSEI::getInstance()->getTrCheck($i, $numIdEditalEliminacao, $arrObjEditalEliminacaoDTO[$i]->getNumIdEditalEliminacao()) . '</td>';
      $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjEditalEliminacaoDTO[$i]->getStrEspecificacao()) . '</td>';

      $strResultado .= '<td align="center"';
      if ($strStaEditalChave == EditalEliminacaoRN::$TE_ELIMINADO){
        $strResultado .= ' class="tdVermelha" ';
      }
      $strResultado .= '>' . PaginaSEI::tratarHTML($strStaEditalDescricao) . '</td>';

      $strResultado .= '<td align="center">';
      if ($arrObjEditalEliminacaoDTO[$i]->getDblIdProcedimento()!=null) {
        $strResultado .= '<a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjEditalEliminacaoDTO[$i]->getDblIdProcedimento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoDTO[$i]->getStrProcedimentoFormatado()).'</a>';
      }else{
        $strResultado .= '&nbsp;' . "\n";
      }
      $strResultado .= '</td>' . "\n";

      $strResultado .= '<td align="center">';
      if ($arrObjEditalEliminacaoDTO[$i]->getDblIdDocumento()!=null) {
        $strResultado .= '<a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_documento='.$arrObjEditalEliminacaoDTO[$i]->getDblIdDocumento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoDTO[$i]->getStrDocumentoFormatado()).'</a>';
      }else{
        $strResultado .= '&nbsp;' . "\n";
      }
      $strResultado .= '</td>' . "\n";

      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($dtaPublicacao) . '</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $numIdEditalEliminacao);

      //só pode ser gerado se está em montagem
      //obs.: só está em montagem se tem 1 ou mais processos
      if ($bolAcaoGerar && $strStaEditalChave == EditalEliminacaoRN::$TE_MONTAGEM) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_gerar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_edital_eliminacao=' . $numIdEditalEliminacao) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_GERAR_EDITAL.'" title="Gerar Edital para Publicação" alt="Gerar Edital para Publicação" class="infraImg" /></a>&nbsp;';
      }
      //data que um edital pode ser eliminacao é a data de eliminiacao somada com o parametro de sistema de dias de prazo de eliminacao
      $dtaPrazoEliminacao = InfraData::calcularData($strNumDiasPrazoEliminacao, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtaPublicacao);
      //se essa data de eliminacao do edital calculada acima for maior ou igual a hoje e a situação do edital for publicado ou eliminaco parcialmente (ou seja, tentou eliminar antes, mas teve algum documento arquivado ou solicitado para arquivamento e que agora pode ter sido eliminado na tela de Documentos para Eliminacao), o botão Eliminar fica habilitado
      if ($bolAcaoEditalEliminar && ($strStaEditalChave == EditalEliminacaoRN::$TE_PUBLICADO || $strStaEditalChave == EditalEliminacaoRN::$TE_ELIMINACAO_PARCIAL) && InfraData::compararDatasSimples($dtaPrazoEliminacao, InfraData::getStrDataAtual()) >= 0) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_eliminar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_edital_eliminacao=' . $numIdEditalEliminacao) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_ELIMINAR.'" title="Eliminar Processos do Edital" alt="Eliminar Processos do Edital" class="infraImg" /></a>&nbsp;';
      }

      if($bolAcaoEliminadosGerar && ($strStaEditalChave == EditalEliminacaoRN::$TE_ELIMINADO || $strStaEditalChave == EditalEliminacaoRN::$TE_ELIMINACAO_PARCIAL)){
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_eliminados_gerar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_edital_eliminacao=' . $numIdEditalEliminacao) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_GERAR_LISTAGEM.'" title="Gerar Listagem de Eliminação" alt="Gerar Listagem de Eliminação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoEditalEliminacaoConteudoListar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_conteudo_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_edital_eliminacao=' . $numIdEditalEliminacao) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_PROCESSOS.'" title="Processos do Edital de Eliminação " alt="Processos do Edital de Eliminação " class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_edital_eliminacao=' . $numIdEditalEliminacao) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Edital de Eliminação" alt="Consultar Edital de Eliminação" class="infraImg" /></a>&nbsp;';
      }
      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_edital_eliminacao=' . $numIdEditalEliminacao) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Edital de Eliminação" alt="Alterar Edital de Eliminação" class="infraImg" /></a>&nbsp;';
      }
      //excluir é só para situacao Cadastrado
      //obs.: quando um edital está na situacao Montagem, mas todos os processos são removidos, volta para situacao Cadastrado
      if ($bolAcaoExcluir && $strStaEditalChave == EditalEliminacaoRN::$TE_CADASTRADO) {
        $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($numIdEditalEliminacao) . '" onclick="acaoExcluir(\'' . $numIdEditalEliminacao . '\',\'' . $arrObjEditalEliminacaoDTO[$i]->getStrEspecificacao() . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Edital de Eliminação" alt="Excluir Edital de Eliminação" class="infraImg" /></a>&nbsp;';
      }


      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'edital_eliminacao_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelStaEditalEliminacao = EditalEliminacaoINT::montarSelectStaEditalEliminacao('', 'Todos', $strStaEditalEliminacao);

}catch (Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}
PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<?if(0){?>
  <style><?}?>

    #lblStaEditalEliminacao {position: absolute;left:0%;top:0%; width:25%;}
    #selStaEditalEliminacao {position: absolute;left:0%;top:40%;width:25%;}

    <?
    if (0){ ?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

  function inicializar() {
    infraEfeitoTabelas(true);
  }

  //exclusao
  <? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id, desc) {
    if (confirm("Confirma exclusão do Edital de Eliminação \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmEditalEliminacaoLista').action = '<?=$strLinkExcluir?>';
      document.getElementById('frmEditalEliminacaoLista').submit();
    }
  }

  <? } ?>

<?if (0){ ?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmEditalEliminacaoLista" method="post" action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblStaEditalEliminacao" for="selStaEditalEliminacao" accesskey="" class="infraLabelOpcional">Situação:</label>
    <select id="selStaEditalEliminacao" name="selStaEditalEliminacao" onchange="this.form.submit();" class="infraSelect" tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">
      <?= $strItensSelStaEditalEliminacao ?>
    </select>

    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
