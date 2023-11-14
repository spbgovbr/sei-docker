<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 12/06/2014 - criado por mga
 *
 * Versão do Gerador de Código: 1.33.1
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->prepararSelecao('servidor_autenticacao_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  switch ($_GET['acao']) {
    case 'servidor_autenticacao_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjServidorAutenticacaoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
          $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($arrStrIds[$i]);
          $arrObjServidorAutenticacaoDTO[] = $objServidorAutenticacaoDTO;
        }
        $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
        $objServidorAutenticacaoRN->excluir($arrObjServidorAutenticacaoDTO);
        PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    /*
        case 'servidor_autenticacao_desativar':
          try{
            $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
            $arrObjServidorAutenticacaoDTO = array();
            for ($i=0;$i<count($arrStrIds);$i++){
              $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
              $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($arrStrIds[$i]);
              $arrObjServidorAutenticacaoDTO[] = $objServidorAutenticacaoDTO;
            }
            $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
            $objServidorAutenticacaoRN->desativar($arrObjServidorAutenticacaoDTO);
            PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
          }catch(Exception $e){
            PaginaSip::getInstance()->processarExcecao($e);
          }
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
          die;

        case 'servidor_autenticacao_reativar':
          $strTitulo = 'Reativar Servidores de Autenticação';
          if ($_GET['acao_confirmada']=='sim'){
            try{
              $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
              $arrObjServidorAutenticacaoDTO = array();
              for ($i=0;$i<count($arrStrIds);$i++){
                $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
                $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao($arrStrIds[$i]);
                $arrObjServidorAutenticacaoDTO[] = $objServidorAutenticacaoDTO;
              }
              $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
              $objServidorAutenticacaoRN->reativar($arrObjServidorAutenticacaoDTO);
              PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            }catch(Exception $e){
              PaginaSip::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
            die;
          }
          break;

     */
    case 'servidor_autenticacao_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Servidor de Autenticação', 'Selecionar Servidores de Autenticação');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'servidor_autenticacao_cadastrar') {
        if (isset($_GET['id_servidor_autenticacao'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_servidor_autenticacao']);
        }
      }
      break;

    case 'servidor_autenticacao_listar':
      $strTitulo = 'Servidores de Autenticação';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'servidor_autenticacao_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'servidor_autenticacao_listar' || $_GET['acao'] == 'servidor_autenticacao_selecionar'){ */
  $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_cadastrar');
  if ($bolAcaoCadastrar) {
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }
  /* } */

  $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
  $objServidorAutenticacaoDTO->retNumIdServidorAutenticacao();
  $objServidorAutenticacaoDTO->retStrNome();
  $objServidorAutenticacaoDTO->retStrStaTipo();
  $objServidorAutenticacaoDTO->retStrEndereco();
  $objServidorAutenticacaoDTO->retNumPorta();
  //$objServidorAutenticacaoDTO->retStrSufixo();
  //$objServidorAutenticacaoDTO->retStrUsuarioPesquisa();
  //$objServidorAutenticacaoDTO->retStrSenhaPesquisa();
  //$objServidorAutenticacaoDTO->retStrContextoPesquisa();
  //$objServidorAutenticacaoDTO->retStrAtributoFiltroPesquisa();
  //$objServidorAutenticacaoDTO->retStrAtributoRetornoPesquisa();
  $objServidorAutenticacaoDTO->retNumVersao();

  /*
    if ($_GET['acao'] == 'servidor_autenticacao_reativar'){
      //Lista somente inativos
      $objServidorAutenticacaoDTO->setBolExclusaoLogica(false);
      $objServidorAutenticacaoDTO->setStrSinAtivo('N');
    }
   */
  $objServidorAutenticacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSip::getInstance()->prepararPaginacao($objServidorAutenticacaoDTO);

  $objServidorAutenticacaoRN = new ServidorAutenticacaoRN();
  $arrObjServidorAutenticacaoDTO = $objServidorAutenticacaoRN->listar($objServidorAutenticacaoDTO);

  //PaginaSip::getInstance()->processarPaginacao($objServidorAutenticacaoDTO);
  $numRegistros = count($arrObjServidorAutenticacaoDTO);

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'servidor_autenticacao_selecionar') {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
      /*     }else if ($_GET['acao']=='servidor_autenticacao_reativar'){
            $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_reativar');
            $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_consultar');
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
            $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_excluir');
            $bolAcaoDesativar = false;
       */
    } else {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_excluir');
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('servidor_autenticacao_desativar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_excluir&acao_origem=' . $_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='servidor_autenticacao_reativar'){ */
    $strSumarioTabela = 'Tabela de Servidores de Autenticação.';
    $strCaptionTabela = 'Servidores de Autenticação';
    /* }else{
      $strSumarioTabela = 'Tabela de Servidores de Autenticação Inativos.';
      $strCaptionTabela = 'Servidores de Autenticação Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh">Nome</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Endereço</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Porta</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="15%">Tipo</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">Versão</th>' . "\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Sufixo','Sufixo',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Usuário de Pesquisa','UsuarioPesquisa',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Senha de Pesquisa','SenhaPesquisa',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Contexto de Pesquisa','ContextoPesquisa',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Atributo Filtro','AtributoFiltroPesquisa',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Atributo Retorno','AtributoRetornoPesquisa',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objServidorAutenticacaoDTO,'Versão','Versao',$arrObjServidorAutenticacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';

    $arrObjTipoServidorAutenticacaoDTO = InfraArray::indexarArrInfraDTO($objServidorAutenticacaoRN->listarValoresTipo(), 'StaTipo');

    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjServidorAutenticacaoDTO[$i]->getNumIdServidorAutenticacao(),
            ServidorAutenticacaoINT::formatarIdentificacao($arrObjServidorAutenticacaoDTO[$i]->getStrNome(), $arrObjServidorAutenticacaoDTO[$i]->getStrEndereco())) . '</td>';
      }
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjServidorAutenticacaoDTO[$i]->getStrNome()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjServidorAutenticacaoDTO[$i]->getStrEndereco()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjServidorAutenticacaoDTO[$i]->getNumPorta()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjTipoServidorAutenticacaoDTO[$arrObjServidorAutenticacaoDTO[$i]->getStrStaTipo()]->getStrDescricao()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjServidorAutenticacaoDTO[$i]->getNumVersao()) . '</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjServidorAutenticacaoDTO[$i]->getNumIdServidorAutenticacao());

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_servidor_autenticacao=' . $arrObjServidorAutenticacaoDTO[$i]->getNumIdServidorAutenticacao()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Servidor de Autenticação" alt="Consultar Servidor de Autenticação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_servidor_autenticacao=' . $arrObjServidorAutenticacaoDTO[$i]->getNumIdServidorAutenticacao()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Servidor de Autenticação" alt="Alterar Servidor de Autenticação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjServidorAutenticacaoDTO[$i]->getNumIdServidorAutenticacao();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjServidorAutenticacaoDTO[$i]->getStrNome());
      }
      /*
            if ($bolAcaoDesativar){
              $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeDesativar().'" title="Desativar Servidor de Autenticação" alt="Desativar Servidor de Autenticação" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar){
              $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeReativar().'" title="Reativar Servidor de Autenticação" alt="Reativar Servidor de Autenticação" class="infraImg" /></a>&nbsp;';
            }
       */

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="' . PaginaSip::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Servidor de Autenticação" alt="Excluir Servidor de Autenticação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'servidor_autenticacao_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='servidor_autenticacao_selecionar'){
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  }else{
  document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Servidor de Autenticação \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmServidorAutenticacaoLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmServidorAutenticacaoLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Servidor de Autenticação selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Servidores de Autenticação selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmServidorAutenticacaoLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmServidorAutenticacaoLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Servidor de Autenticação \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmServidorAutenticacaoLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmServidorAutenticacaoLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Servidor de Autenticação selecionado.');
  return;
  }
  if (confirm("Confirma reativação dos Servidores de Autenticação selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmServidorAutenticacaoLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmServidorAutenticacaoLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Servidor de Autenticação \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmServidorAutenticacaoLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmServidorAutenticacaoLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Servidor de Autenticação selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Servidores de Autenticação selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmServidorAutenticacaoLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmServidorAutenticacaoLista').submit();
  }
  }
  <?
} ?>

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmServidorAutenticacaoLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>