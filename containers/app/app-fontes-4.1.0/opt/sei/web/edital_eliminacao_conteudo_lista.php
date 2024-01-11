<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimentoEdiEliCon','selStaSituacao'));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_edital_eliminacao'));

  $objEditalEliminacaoRN = new EditalEliminacaoRN();

  $strIdNaoEliminar = '';

  switch($_GET['acao']){
    //excluir padrao
    case 'edital_eliminacao_conteudo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjEditalEliminacaoConteudoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
          $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($arrStrIds[$i]);
          $arrObjEditalEliminacaoConteudoDTO[] = $objEditalEliminacaoConteudoDTO;
        }
        $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
        $objEditalEliminacaoConteudoRN->excluir($arrObjEditalEliminacaoConteudoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'edital_eliminacao_conteudo_listar':
      $strTitulo = 'Processos do Edital de Eliminação';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  //id do edital de eliminacao
  $numIdEditalEliminacao = $_GET['id_edital_eliminacao'];
  //dto para consultar o edital
  $objEditalEliminacaoDTO = new EditalEliminacaoDTO();
  $objEditalEliminacaoDTO->retNumIdEditalEliminacao();
  $objEditalEliminacaoDTO->retStrStaEditalEliminacao();
  $objEditalEliminacaoDTO->retDtaPublicacao();
  $objEditalEliminacaoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
  //consulta
  $objEditalEliminacaoDTO = $objEditalEliminacaoRN->consultar($objEditalEliminacaoDTO);
  //situacao do edital
  $strStaEditalChave = $objEditalEliminacaoDTO->getStrStaEditalEliminacao();
  //data da publicacao
  $dtaPublicacao = $objEditalEliminacaoDTO->getDtaPublicacao();
  //parametro de sistema que indica quantos dias um edital pode ser eliminado após sua publicacao
  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $strNumDiasPrazoEliminacao = trim($objInfraParametro->getValor("SEI_NUM_DIAS_PRAZO_ELIMINACAO", false));
  //data que um edital pode ser eliminacao é a data de eliminiacao somada com o parametro de sistema de dias de prazo de eliminacao
  $dtaPrazoEliminacao = InfraData::calcularData($strNumDiasPrazoEliminacao,InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ADIANTE,$dtaPublicacao);

  //recurso de adicionar processos no edital
  $bolAcaoSelecionar = SessaoSEI::getInstance()->verificarPermissao('avaliacao_documental_selecionar');
  //link para abrir o popup de selecao
  $strLinkNovo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=avaliacao_documental_selecionar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&tipo_selecao=2');
  //só pode adicionar se o edital está em situacao Montagem ou Cadastrado
  if ($bolAcaoSelecionar && ($strStaEditalChave == EditalEliminacaoRN::$TE_MONTAGEM || $strStaEditalChave == EditalEliminacaoRN::$TE_CADASTRADO)){
    $arrComandos[] = '<button type="button" accesskey="" id="btnNovo" value="Adicionar" onclick="infraAbrirJanelaModal(\''.$strLinkNovo.'\',800,600);" class="infraButton">Adicionar</button>';
  }
  //dto para listar os conteudos do edital de eliminacao (que na pratica sao os processos)
  $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();
  $objEditalEliminacaoConteudoDTO->retNumIdEditalEliminacaoConteudo();
  $objEditalEliminacaoConteudoDTO->retStrProtocoloProcedimentoFormatado();
  $objEditalEliminacaoConteudoDTO->retDblIdProcedimentoAvaliacaoDocumental();
  $objEditalEliminacaoConteudoDTO->retStrNomeTipoProcedimento();
  $objEditalEliminacaoConteudoDTO->retDthInclusao();
  $objEditalEliminacaoConteudoDTO->retStrSiglaUsuarioInclusao();
  $objEditalEliminacaoConteudoDTO->retStrNomeUsuarioInclusao();
  $objEditalEliminacaoConteudoDTO->retStrStaSituacaoAvaliacaoDocumental();
  //filtra pelo edital de eliminacao
  $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
  //filtros de tela
  $numIdTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimentoEdiEliCon');
  $strStaSituacao = PaginaSEI::getInstance()->recuperarCampo('selStaSituacao');
  if ($numIdTipoProcedimento!==''){
    $objEditalEliminacaoConteudoDTO->setNumIdTipoProcedimentoProcedimento($numIdTipoProcedimento);
  }
  if ($strStaSituacao!==''){
    $objEditalEliminacaoConteudoDTO->setStrStaSituacaoAvaliacaoDocumental($strStaSituacao);
  }

  //paginacao
  PaginaSEI::getInstance()->prepararOrdenacao($objEditalEliminacaoConteudoDTO, 'Inclusao', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objEditalEliminacaoConteudoDTO);
  //listagem
  $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
  //metodo que retorna o atributo QtdArquivamentosRemanescentes dos editais de eliminacao conteudo (que sao as quantidades de documentos arquivados do processo)
  $arrObjEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->listarComQuantidadeDocumentos($objEditalEliminacaoConteudoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objEditalEliminacaoConteudoDTO);
  $numRegistros = count($arrObjEditalEliminacaoConteudoDTO);

  if ($numRegistros > 0){
    //recursos
    $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_conteudo_consultar');
    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_conteudo_alterar');
    $bolAcaoEditalEliminar = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_eliminar');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('edital_eliminacao_conteudo_excluir');

    $bolCheck = false;
    //só pode retirar do edital se o edital está em Montagem ou Cadastrado
    if ($bolAcaoExcluir && ($strStaEditalChave == EditalEliminacaoRN::$TE_MONTAGEM || $strStaEditalChave == EditalEliminacaoRN::$TE_CADASTRADO || $strStaEditalChave == EditalEliminacaoRN::$TE_PUBLICADO)){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="" id="btnExcluir" value="Retirar" onclick="acaoExclusaoMultipla();" class="infraButton">Retirar</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_conteudo_excluir&acao_origem='.$_GET['acao']);
    }
    //só pode eliminar se o edital estiver publicado ou eliminacao parcial, a data de prazo for maior ou igual a hoje
    if ($bolAcaoEditalEliminar && ($strStaEditalChave == EditalEliminacaoRN::$TE_PUBLICADO || $strStaEditalChave == EditalEliminacaoRN::$TE_ELIMINACAO_PARCIAL) && InfraData::compararDatasSimples($dtaPrazoEliminacao,InfraData::getStrDataAtual()) >= 0) {
      $bolEliminar = true;
      $arrComandos[] = '<button type="button" accesskey="" id="btnEliminar" value="Eliminar" onclick="acaoEliminacaoMultipla();" class="infraButton">Eliminar</button>';
      $strLinkEditalEliminar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_eliminar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_edital_eliminacao='.$_GET['id_edital_eliminacao']);
    }

    //imprimir
    //$bolCheck = true;
    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos do Edital de Eliminação.';
    $strCaptionTabela = 'Processos do Edital de Eliminação';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck || $bolEliminar) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoConteudoDTO,'Processo','ProtocoloProcedimentoFormatado',$arrObjEditalEliminacaoConteudoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" >'.PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoConteudoDTO,'Tipo','NomeTipoProcedimento',$arrObjEditalEliminacaoConteudoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoConteudoDTO,'Situação','StaSituacaoAvaliacaoDocumental',$arrObjEditalEliminacaoConteudoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoConteudoDTO,'Inclusão','Inclusao',$arrObjEditalEliminacaoConteudoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objEditalEliminacaoConteudoDTO,'Usuário','SiglaUsuarioInclusao',$arrObjEditalEliminacaoConteudoDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Arquivamentos Remanescentes</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";

    $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();

    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){
      $strLinkArquivados = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_arquivados_listar&acao_origem=edital_eliminacao_conteudo_listar&id_edital_eliminacao_conteudo=' . $arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo());

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strAtributos = "";

      if ($bolCheck || $bolEliminar){
        $strResultado .= '<td align="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo(),$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo()).'</td>';
      }else{
        $strResultado .= '<td align="center" style="display: none;">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo(),$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo()).'</td>';
      }
      $strResultado .= '<td align="center"><a id="ancProc'.$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo().'" target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjEditalEliminacaoConteudoDTO[$i]->getDblIdProcedimentoAvaliacaoDocumental()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</a></td>'."\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getStrNomeTipoProcedimento()) . '</td>' . "\n";

      $strResultado .= '<td align="center"';
      if ($arrObjEditalEliminacaoConteudoDTO[$i]->getStrStaSituacaoAvaliacaoDocumental()==AvaliacaoDocumentalRN::$TA_ELIMINADO){
        $strResultado .= ' class="tdVermelha" ';
      }
      $strResultado .= '>' . PaginaSEI::tratarHTML($objAvaliacaoDocumentalRN->buscarValorAvaliacao($arrObjEditalEliminacaoConteudoDTO[$i]->getStrStaSituacaoAvaliacaoDocumental())) . '</td>' . "\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getDthInclusao()) . '</td>' . "\n";
      $strResultado .= '<td align="center">    <a alt="'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getStrNomeUsuarioInclusao()).'" title="'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getStrNomeUsuarioInclusao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjEditalEliminacaoConteudoDTO[$i]->getStrSiglaUsuarioInclusao()).'</a></td>';
      $strResultado .= '<td align="center"><a href="javascript:void(0);" onclick="infraAbrirJanelaModal(\''.$strLinkArquivados.'\',750,550);" class="ancoraPadraoAzul">'.InfraUtil::formatarMilhares($arrObjEditalEliminacaoConteudoDTO[$i]->getNumQtdArquivamentosRemanescentes()).'</a></td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo());

//      if ($bolAcaoConsultar){
//        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_conteudo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_edital_eliminacao_conteudo='.$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Processo do Edital de Eliminação" alt="Consultar Processo do Edital de Eliminação" class="infraImg" /></a>&nbsp;';
//      }
//
//      if ($bolAcaoAlterar){
//        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=edital_eliminacao_conteudo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_edital_eliminacao_conteudo='.$arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Processo do Edital de Eliminação" alt="Alterar Processo do Edital de Eliminação" class="infraImg" /></a>&nbsp;';
//      }

      $strId = $arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo();
      $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo());

      //se pode eliminar e se situação for diferente de eliminado
      if ($bolEliminar && $arrObjEditalEliminacaoConteudoDTO[$i]->getStrStaSituacaoAvaliacaoDocumental() != AvaliacaoDocumentalRN::$TA_ELIMINADO /* && $arrObjEditalEliminacaoConteudoDTO[$i]->getNumQtdArquivamentosRemanescentes() == 0 */)  {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoEliminar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::AVALIACAO_ELIMINAR.'" title="Eliminar Processo do Edital de Eliminação" alt="Eliminar Processo do Edital de Eliminação" class="infraImg" /></a>&nbsp;';
      }else{
        if ($strIdNaoEliminar!=''){
          $strIdNaoEliminar .= ',';
        }
        $strIdNaoEliminar .= $arrObjEditalEliminacaoConteudoDTO[$i]->getNumIdEditalEliminacaoConteudo();
      }

      $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink("controlador.php?acao=avaliacao_documental_consultar&acao_origem=".$_GET['acao']."&acao_retorno=".$_GET['acao']."&id_procedimento=".$arrObjEditalEliminacaoConteudoDTO[$i]->getDblIdProcedimentoAvaliacaoDocumental()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" target="_blank"><img src="'.Icone::AVALIACAO_DOCUMENTAL.'" title="Consultar Avaliação Documental" alt="Consultar Avaliação Documental" class="infraImg" /></a>&nbsp;';

      //só pode excluir o processos do edital se ele estiver em montagem ou publicado e a avaliacao documental do processo estiver em comissao (o processo nao pode ter sido eliminado)
      if ($bolAcaoExcluir && ($strStaEditalChave == EditalEliminacaoRN::$TE_MONTAGEM || $strStaEditalChave == EditalEliminacaoRN::$TE_PUBLICADO) && $arrObjEditalEliminacaoConteudoDTO[$i]->getStrStaSituacaoAvaliacaoDocumental() == AvaliacaoDocumentalRN::$TA_COMISSAO) {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Retirar Processo do Edital de Eliminação" alt="Retirar Processo do Edital de Eliminação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="V" id="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_edital_eliminacao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

  //select de tipos de processos
  $strSelTipoProcedimento = EditalEliminacaoINT::montarSelectTipoProcedimento('', 'Todos', $numIdTipoProcedimento, $numIdEditalEliminacao);
  $strSelStaSituacao = AvaliacaoDocumentalINT::montarSelectStaAvaliacao('', 'Todos', $strStaSituacao);
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
<?if(0){?><style><?}?>

#lblTipoProcedimento {position: absolute;left:0%;top:0%; width:25%;}
#selTipoProcedimentoEdiEliCon {position: absolute;left:0%;top:40%;width:50%;}

#lblStaSituacao {position: absolute;left:0%;top:0%; width:25%;}
#selStaSituacao {position: absolute;left:0%;top:40%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar() {
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma a retirada do processo do Edital de Eliminação?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmEditalEliminacaoConteudoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmEditalEliminacaoConteudoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo do Edital de Eliminação selecionado.');
    return;
  }
  if (confirm("Confirma a retirada dos processos selecionados do Edital de Eliminação?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmEditalEliminacaoConteudoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmEditalEliminacaoConteudoLista').submit();
  }
}
 <? } ?>
<? if ($bolAcaoEditalEliminar){ ?>
  function acaoEliminar(id,desc){
    document.getElementById('hdnInfraItemId').value = id;
    document.getElementById('frmEditalEliminacaoConteudoLista').action = '<?=$strLinkEditalEliminar?>';
    document.getElementById('frmEditalEliminacaoConteudoLista').submit();
  }

function acaoEliminacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo do Edital de Eliminação selecionado.');
    return;
  }

  if (!bloquearNaoEliminacaoSelecionado()) {
    document.getElementById('hdnInfraItemId').value = '';
    document.getElementById('frmEditalEliminacaoConteudoLista').action = '<?=$strLinkEditalEliminar?>';
    document.getElementById('frmEditalEliminacaoConteudoLista').submit();
  }
}
<? } ?>

function bloquearNaoEliminacaoSelecionado(){

  var naoEliminar = document.getElementById('hdnIdNaoEliminar').value;

  if (naoEliminar!='') {

    selecionados = document.getElementById('hdnInfraItensSelecionados').value;

    if (selecionados!='') {
      naoEliminar = naoEliminar.split(',');
      selecionados = selecionados.split(',');

      for (var i = 0; i<naoEliminar.length; i++) {
        for (var j = 0; j<selecionados.length; j++) {
          if (naoEliminar[i]==selecionados[j]) {
            var a = document.getElementById('ancProc' + naoEliminar[i]);
            alert('Operação não aplicável no processo ' + a.innerHTML + '.');
            return true;
          }
        }
      }
    }
  }
  return false;
}


<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEditalEliminacaoConteudoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblTipoProcedimento" for="selTipoProcedimentoEdiEliCon" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label><br/>
  <select id="selTipoProcedimentoEdiEliCon" name="selTipoProcedimentoEdiEliCon" onchange="this.form.submit();" class="infraSelect combo" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strSelTipoProcedimento?>
  </select>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblStaSituacao" for="selStaSituacao" accesskey="" class="infraLabelOpcional">Situação do Processo:</label>
  <select id="selStaSituacao" name="selStaSituacao" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strSelStaSituacao?>
  </select>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

  <input type="hidden" id="hdnIdNaoEliminar" name="hdnIdNaoEliminar" value="<?=$strIdNaoEliminar?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
