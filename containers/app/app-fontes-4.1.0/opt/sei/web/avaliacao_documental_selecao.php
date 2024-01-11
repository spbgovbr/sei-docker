<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 **/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('avaliacao_documental_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array("id_edital_eliminacao"));

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcessoAvaDocSel'));

  $bolFechar = false;

  switch($_GET['acao']){

    case 'avaliacao_documental_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Processo para Edital de Eliminação','Selecionar Processos para Edital de Eliminação');

      if (isset($_POST['sbmSalvarSelecao'])) {
        try{

          //array de avaliacao documental, que conterá os processos selecionados
          //obs.: a tela lista PesquisaAvaliacaoDocumentalDTO, que na verdade sao 'protocolo', mas sao o que importa é o IdProcedimento de cada registro.
          //      Quando o usuario clica em salvar, e é criado um array de AvaliacaoDocumentalDTO, pois o relacionado da tabela edital_eliminacao_conteudo é com a tabela avaliacao_documental
          $arrObjAvaliacaoDocumentalDTO = array();
          //retorna ids dos selecionados
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          foreach ($arrStrIds as $strId){
            //cria dto
            $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
            //seta id
            $objAvaliacaoDocumentalDTO->setNumIdAvaliacaoDocumental($strId);
            //adiciona no arrays
            $arrObjAvaliacaoDocumentalDTO[] = $objAvaliacaoDocumentalDTO;
          }
          //cria dto de edital de eliminacao, que contem atributo que é um array de avaliacao documental
          $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
          $objEditalEliminacaoDTO ->setNumIdEditalEliminacao($_GET["id_edital_eliminacao"]);
          $objEditalEliminacaoDTO->setArrObjAvaliacaoDocumentalDTO($arrObjAvaliacaoDocumentalDTO);
          //chama metodo da RN para adicionar os processos no edital de eliminacao
          $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
          $objEditalEliminacaoConteudoRN->adicionar($objEditalEliminacaoDTO);
          $bolFechar = true;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

        break;
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  //dto para buscar os processos
  $objProtocoloRN = new ProtocoloRN();
  $objPesquisaAvaliacaoDocumentalDTO = new PesquisaAvaliacaoDocumentalDTO();
  $objPesquisaAvaliacaoDocumentalDTO->retDblIdProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
  $objPesquisaAvaliacaoDocumentalDTO->retStrNomeTipoProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaConclusaoProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retNumIdAssuntoProxyAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retNumPrazoCorrenteAssuntoAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retNumPrazoIntermediarioAssuntoAvaliacaoDocumental();
  //paginacao
  PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'ConclusaoProcedimento', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objPesquisaAvaliacaoDocumentalDTO,500);
  //filtro por tipo de processo
  $numIdTipoProcesso = PaginaSEI::getInstance()->recuperarCampo('selTipoProcessoAvaDocSel');
  $strSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('', 'Todos', $numIdTipoProcesso);
  if($numIdTipoProcesso !== ''){
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdTipoProcedimento($numIdTipoProcesso);
  }

  //array de retorno
  $arrObjPesquisaAvaliacaoDocumentalDTO = array();
  try {
    //pesquisa
    $arrObjPesquisaAvaliacaoDocumentalDTO = $objProtocoloRN->pesquisarProtocolosEditalEliminacao($objPesquisaAvaliacaoDocumentalDTO);
  }catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
  }
  //paginacao
  PaginaSEI::getInstance()->processarPaginacao($objPesquisaAvaliacaoDocumentalDTO);
  $numRegistros = InfraArray::contar($arrObjPesquisaAvaliacaoDocumentalDTO);

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="T" id="sbmSalvarSelecao" name="sbmSalvarSelecao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';


  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos para Edital de Eliminação.';
    $strCaptionTabela = 'Processos para Edital de Eliminação';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="30%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Processo','ProtocoloFormatado',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" >'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Tipo','NomeTipoProcedimento',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Conclusão','ConclusaoProcedimento',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getNumIdAvaliacaoDocumental(),$arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getNumIdAvaliacaoDocumental()).'</td>';
      $strResultado .= '<td align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDblIdProcedimento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrProtocoloFormatado()).'</a></td>'."\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrNomeTipoProcedimento()) . '</td>' . "\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDtaConclusaoProcedimento()) . '</td>' . "\n";
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
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

  #lblTipoProcesso{position:absolute;left:0%;top:0%;}
  #selTipoProcessoAvaDocSel{position:absolute;left:0%;top:40%;width:50%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

  function inicializar(){
    //se é para fechar a modal, recarrega a tela pai, para atualizar a listagem de processos
    <? if ($bolFechar) { ?>
      parent.location.reload();
      parent.infraFecharJanelaModal();
      return;
    <? }?>

    infraEfeitoTabelas();
  }

  function validarCadastro() {
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum Processo selecionado.');
      return false;
    }
    return true;
  }

  function OnSubmitForm() {
    return validarCadastro();
  }

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

?>

  <form id="frmSelecao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">

    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->abrirAreaDados('5em');
    ?>

    <div>
      <label id="lblTipoProcesso" for="selTipoProcessoAvaDocSel" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">T</span>ipo Processo:</label>
      <select id="selTipoProcessoAvaDocSel" name="selTipoProcessoAvaDocSel" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strSelTipoProcedimento?>
      </select>
    </div>

    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

  </form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>