<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/11/2015 - criado por mga
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_procedimento', 'id_acompanhamento', 'id_usuario_atribuicao'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $bolMultiplo = false;

  $arrComandos = array();

  $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

  switch ($_GET['acao']) {

    case 'andamento_marcador_remover':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAndamentoMarcadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
          $objAndamentoMarcadorDTO->setNumIdMarcador($arrStrIds[$i]);
          $objAndamentoMarcadorDTO->setDblIdProcedimento($_GET['id_procedimento']);
          $arrObjAndamentoMarcadorDTO[] = $objAndamentoMarcadorDTO;
        }
        $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
        $objAndamentoMarcadorRN->remover($arrObjAndamentoMarcadorDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'));
      die;

    case 'andamento_marcador_gerenciar':
      $strTitulo = 'Marcadores do Processo';

      if ($_GET['acao_origem']=='procedimento_visualizar'){

        $dto = new AndamentoMarcadorDTO();
        $dto->setNumMaxRegistrosRetorno(1);
        $dto->retNumIdMarcador();
        $dto->setDblIdProcedimento($_GET['id_procedimento']);
        $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $dto->setStrSinUltimo('S');

        $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
        if ($objAndamentoMarcadorRN->consultar($dto)==null){
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']));
          die;
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $bolAcaoListar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_listar');
  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_cadastrar');
  if ($bolAcaoCadastrar){
    $arrComandos[] = '<button type="button" id="btnAdicionar" value="Adicionar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton">Adicionar</button>';
  }

  $objProtocoloDTO = new ProtocoloDTO();
  $objProtocoloDTO->retStrProtocoloFormatado();
  $objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);

  $objProtocoloRN = new ProtocoloRN();
  $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

  if ($objProtocoloDTO == null) {
    throw new InfraException("Processo não encontrado.");
  }

  $strTitulo .= ' '.$objProtocoloDTO->getStrProtocoloFormatado();


  $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
  $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
  $objAndamentoMarcadorDTO->retNumIdMarcador();
  $objAndamentoMarcadorDTO->retDblIdProcedimento();
  $objAndamentoMarcadorDTO->retStrNomeMarcador();
  $objAndamentoMarcadorDTO->retStrStaIconeMarcador();
  $objAndamentoMarcadorDTO->retStrSinAtivoMarcador();
  $objAndamentoMarcadorDTO->retStrTexto();
  $objAndamentoMarcadorDTO->retDthExecucao();
  $objAndamentoMarcadorDTO->retNumIdUsuario();
  $objAndamentoMarcadorDTO->retStrSiglaUsuario();
  $objAndamentoMarcadorDTO->retStrNomeUsuario();
  $objAndamentoMarcadorDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objAndamentoMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objAndamentoMarcadorDTO->setStrSinUltimo('S');

  PaginaSEI::getInstance()->prepararOrdenacao($objAndamentoMarcadorDTO, 'Execucao', InfraDTO::$TIPO_ORDENACAO_DESC);

  PaginaSEI::getInstance()->prepararPaginacao($objAndamentoMarcadorDTO);

  $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
  $arrObjAndamentoMarcadorDTO = $objAndamentoMarcadorRN->listar($objAndamentoMarcadorDTO);

  PaginaSEI::getInstance()->processarPaginacao($objAndamentoMarcadorDTO);

  $numRegistrosAndamento = count($arrObjAndamentoMarcadorDTO);

  if ($numRegistrosAndamento > 0) {

    $objMarcadorRN = new MarcadorRN();
    $arrObjIconeMarcadorDTO = InfraArray::indexarArrInfraDTO($objMarcadorRN->listarValoresIcone(),'StaIcone');

    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_alterar');
    $bolAcaoRemover = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_remover');

    if ($bolAcaoRemover){
      $arrComandos[] = '<button type="button" id="btnRemover" value="Remover" onclick="acaoRemocaoMultipla();" class="infraButton">Remover</button>';
      $strLinkRemover = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_remover&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento']);
    }

    $bolCheck = false;

    $strResultado = '';

    $strResultado .= '<table id="tblMarcadores" width="99%" class="infraTable" summary="Marcadores">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela('Marcadores', $numRegistrosAndamento, '') . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoMarcadorDTO,'Marcador','NomeMarcador',$arrObjAndamentoMarcadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Texto</th>';
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoMarcadorDTO,'Usuário','SiglaUsuario',$arrObjAndamentoMarcadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objAndamentoMarcadorDTO,'Data/Hora','Execucao',$arrObjAndamentoMarcadorDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>';
    $strResultado .= '</tr>' . "\n";

    $strQuebraLinha = '<span style="line-height:.5em"><br /></span>';

    $i = 0;
    foreach ($arrObjAndamentoMarcadorDTO as $objAndamentoMarcadorDTO) {

      $strResultado .= '<tr class="infraTrClara">';

      $strResultado .= '<td valign="center">'.PaginaSEI::getInstance()->getTrCheck($i++, $objAndamentoMarcadorDTO->getNumIdMarcador(),$objAndamentoMarcadorDTO->getStrNomeMarcador()).'</td>';
      $strResultado .= '<td align="left" valign="center">';
      $strResultado .= '<a href="#" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$arrObjIconeMarcadorDTO[$objAndamentoMarcadorDTO->getStrStaIconeMarcador()]->getStrArquivo().'" title="'.PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeMarcador()).'" alt="'.PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeMarcador()).'" class="infraImg" /></a>&nbsp;';
      $strResultado .= PaginaSEI::tratarHTML(MarcadorINT::formatarMarcadorDesativado($objAndamentoMarcadorDTO->getStrNomeMarcador(),$objAndamentoMarcadorDTO->getStrSinAtivoMarcador()));
      $strResultado .= '</td>'."\n";

      $strResultado .= '<td valign="center">'.PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrTexto()).'</td>'."\n";

      $strResultado .= '<td align="center"  valign="center">';
      $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrSiglaUsuario()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center" valign="center">'.substr($objAndamentoMarcadorDTO->getDthExecucao(), 0, 16).'</td>'."\n";

      $strResultado .= '<td align="center" valign="center">';

      if ($bolAcaoAlterar){
        //$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_andamento_marcador='.$objAndamentoMarcadorDTO->getNumIdAndamentoMarcador()).'" ><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Texto do Marcador no Processo" alt="Alterar Texto do Marcador no Processo" class="infraImg" /></a>&nbsp;';
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoAlterar(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_marcador='.$objAndamentoMarcadorDTO->getNumIdMarcador().'&id_procedimento='.$objAndamentoMarcadorDTO->getDblIdProcedimento()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::MARCADOR_ANOTACAO.'" title="Alterar Texto do Marcador" alt="Alterar Texto do Marcador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoRemover){
        $strId = $objAndamentoMarcadorDTO->getNumIdMarcador();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objAndamentoMarcadorDTO->getStrNomeMarcador());
      }

      if ($bolAcaoRemover){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoRemover(\''.$strId.'\',\''.$strDescricao.'\');" ><img src="'.PaginaSEI::getInstance()->getIconeRemover().'" title="Remover Marcador do Processo" alt="Remover Marcador do Processo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  if ($bolAcaoListar){
    $arrComandos[] = '<button type="button" id="btnListar" value="Histórico" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_marcador_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento']).'\'" class="infraButton">Histórico</button>';
  }

  if (!PaginaSEI::getInstance()->isBolArvore()) {

    if (PaginaSEI::getInstance()->getAcaoRetorno()=='acompanhamento_listar'){
      $strAncora = $_GET['id_acompanhamento'];
    }else{
      $strAncora = $_GET['id_procedimento'];
    }

    $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
  }


  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&montar_visualizacao=0');

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
//<script type="javascript">

var objAjaxMarcadores = null;

function inicializar(){

  //atualiza árvore para mostrar o relacionamento
  <?if (($_GET['acao_origem']=='andamento_marcador_cadastrar' || $_GET['acao_origem']=='andamento_marcador_remover') && $_GET['resultado']=='1') { ?>
  parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>

  infraEfeitoTabelas();

}

function OnSubmitForm() {
  return true;
}

<? if ($bolAcaoRemover){ ?>
function acaoRemover(id,desc){
  if (confirm("Confirma remoção do Marcador \""+desc+"\" do processo?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGerenciarMarcador').action='<?=$strLinkRemover?>';
    document.getElementById('frmGerenciarMarcador').submit();
  }
}

function acaoRemocaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Marcador selecionado.');
    return;
  }
  if (confirm("Confirma remoção dos Marcadores selecionados do processo?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGerenciarMarcador').action='<?=$strLinkRemover?>';
    document.getElementById('frmGerenciarMarcador').submit();
  }
}
<? } ?>

<? if ($bolAcaoAlterar){ ?>
function acaoAlterar(link){
  infraAbrirJanela(link,'janelaAlterarTextoMarcador',500,250,'location=0,status=1,resizable=1,scrollbars=1');
}
<? } ?>

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmGerenciarMarcador" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistrosAndamento);
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>