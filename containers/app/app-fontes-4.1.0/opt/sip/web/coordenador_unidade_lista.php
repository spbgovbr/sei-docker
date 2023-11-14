<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  /*
  if (isset($_GET['id_orgao_unidade']) && isset($_GET['id_unidade'])){
    PaginaSip::getInstance()->salvarCampo('selOrgaoUnidade',$_GET['id_orgao_unidade']);
    PaginaSip::getInstance()->salvarCampo('selUnidade',$_GET['id_unidade']);
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoUnidade','selUnidade'));
  }
    */

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'selOrgaoUnidade', 'selUnidade'));

  switch ($_GET['acao']) {
    case 'coordenador_unidade_excluir':
      try {
        $arrObjCoordenadorUnidadeDTO = array();
        $arrStrId = PaginaSip::getInstance()->getArrStrItensSelecionados();
        for ($i = 0; $i < count($arrStrId); $i++) {
          $arrStrIdComposto = explode('#', $arrStrId[$i]);
          $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO();
          $objCoordenadorUnidadeDTO->setNumIdSistema($arrStrIdComposto[0]);
          $objCoordenadorUnidadeDTO->setNumIdUsuario($arrStrIdComposto[1]);
          $objCoordenadorUnidadeDTO->setNumIdUnidade($arrStrIdComposto[2]);
          $arrObjCoordenadorUnidadeDTO[] = $objCoordenadorUnidadeDTO;
        }
        $objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
        $objCoordenadorUnidadeRN->excluir($arrObjCoordenadorUnidadeDTO);
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      break;

    case 'coordenador_unidade_listar':
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();
  if (SessaoSip::getInstance()->verificarPermissao('coordenador_unidade_cadastrar')) {
    $arrComandos[] = '<input type="button" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_unidade_cadastrar') . '\';" class="infraButton" />';
  }
  $objCoordenadorUnidadeDTO = new CoordenadorUnidadeDTO(true);
  $objCoordenadorUnidadeDTO->retTodos();


  //ORGAO SISTEMA
  $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema');
  if ($numIdOrgaoSistema !== '') {
    $objCoordenadorUnidadeDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
  } else {
    $objCoordenadorUnidadeDTO->setNumIdOrgaoSistema(null);
  }

  //SISTEMA
  $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
  if ($numIdSistema !== '') {
    $objCoordenadorUnidadeDTO->setNumIdSistema($numIdSistema);
  } else {
    $objCoordenadorUnidadeDTO->setNumIdSistema(null);
  }

  //ORGAO UNIDADE
  $numIdOrgaoUnidade = PaginaSip::getInstance()->recuperarCampo('selOrgaoUnidade');
  if ($numIdOrgaoUnidade !== '') {
    $objCoordenadorUnidadeDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);
    $strDisplayUnidade = 'visibility:visible;';
  } else {
    $strDisplayUnidade = 'visibility:hidden;';
  }

  //ORGAO UNIDADE
  $numIdUnidade = PaginaSip::getInstance()->recuperarCampo('selUnidade');
  if ($numIdUnidade !== '') {
    $objCoordenadorUnidadeDTO->setNumIdUnidade($numIdUnidade);
  }

  PaginaSip::getInstance()->prepararOrdenacao($objCoordenadorUnidadeDTO, 'SiglaUsuario', InfraDTO::$TIPO_ORDENACAO_ASC);

  $objCoordenadorUnidadeRN = new CoordenadorUnidadeRN();
  $arrObjCoordenadorUnidadeDTO = $objCoordenadorUnidadeRN->listarAdministrados($objCoordenadorUnidadeDTO);

  $numRegistros = count($arrObjCoordenadorUnidadeDTO);

  if ($numRegistros > 0) {
    $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('coordenador_unidade_consultar');
    $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('coordenador_unidade_alterar');
    $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('coordenador_unidade_excluir');
    //Montar ações múltiplas
    $bolCheck = false;
    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('coordenador_unidade_lista.php?acao=coordenador_unidade_excluir');
    }

    $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

    $strResultado = '';
    $strResultado .= '<table width="70%" class="infraTable" summary="Tabela de Coordenadores de Unidade cadastrados">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Coordenadores de Unidade', $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
    }

    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objCoordenadorUnidadeDTO, 'Unidade', 'SiglaUnidade', $arrObjCoordenadorUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objCoordenadorUnidadeDTO, 'Usuário', 'SiglaUsuario', $arrObjCoordenadorUnidadeDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objCoordenadorUnidadeDTO, 'Sistema', 'SiglaSistema', $arrObjCoordenadorUnidadeDTO) . '</th>' . "\n";

    $strResultado .= '<th class="infraTh">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    for ($i = 0; $i < $numRegistros; $i++) {
      if (($i + 2) % 2) {
        $strResultado .= '<tr class="infraTrEscura">';
      } else {
        $strResultado .= '<tr class="infraTrClara">';
      }
      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i,
            $arrObjCoordenadorUnidadeDTO[$i]->getNumIdSistema() . '#' . $arrObjCoordenadorUnidadeDTO[$i]->getNumIdUsuario() . '#' . $arrObjCoordenadorUnidadeDTO[$i]->getNumIdUnidade(), '') . '</td>';
      }

      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoUnidade()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoUnidade()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaUnidade()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoOrgaoUnidade()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoOrgaoUnidade()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaOrgaoUnidade()) . '</a>';
      $strResultado .= '</td>';


      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaUsuario()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaOrgaoUsuario()) . '</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaSistema()) . '</a>';
      $strResultado .= '&nbsp;/&nbsp;';
      $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoOrgaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrDescricaoOrgaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaOrgaoSistema()) . '</a>';
      $strResultado .= '</td>';

      //$strResultado .= '<td align="center">'.$arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaUnidade().' / '.$arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaOrgaoUnidade().'</td>';
      //$strResultado .= '<td align="center">'.$arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaUsuario().' / '.$arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaOrgaoUsuario().'</td>';
      //$strResultado .= '<td align="center">'.$arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaSistema().' / '.$arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaOrgaoSistema().'</td>';


      $strResultado .= '<td align="center">';
      /*
            if ($bolAcaoConsultar){
                $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_unidade_consultar&id_sistema='.$arrObjCoordenadorUnidadeDTO[$i]->getNumIdSistema().'&id_usuario='.$arrObjCoordenadorUnidadeDTO[$i]->getNumIdUsuario().'&id_unidade='.$arrObjCoordenadorUnidadeDTO[$i]->getNumIdUnidade())).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeConsultar().'" title="Consultar Usuário com Permissão" alt="Consultar Usuário com Permissão" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar){
                $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_unidade_alterar&id_sistema='.$arrObjCoordenadorUnidadeDTO[$i]->getNumIdSistema().'&id_usuario='.$arrObjCoordenadorUnidadeDTO[$i]->getNumIdUsuario().'&id_unidade='.$arrObjCoordenadorUnidadeDTO[$i]->getNumIdUnidade())).'" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getIconeAlterar().'" title="Alterar Usuário com Permissão" alt="Alterar Usuário com Permissão" class="infraImg" /></a>&nbsp;';
            }
      */

      if ($bolAcaoExcluir) {
        $strResultado .= '<a onclick="acaoExcluir(\'' . $arrObjCoordenadorUnidadeDTO[$i]->getNumIdSistema() . '#' . $arrObjCoordenadorUnidadeDTO[$i]->getNumIdUsuario() . '#' . $arrObjCoordenadorUnidadeDTO[$i]->getNumIdUnidade() . '\',\'' . PaginaSip::formatarParametrosJavaScript($arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaUnidade() . '/' . $arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaUsuario() . '/' . $arrObjCoordenadorUnidadeDTO[$i]->getStrSiglaSistema()) . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Coordenador de Unidade" alt="Excluir Coordenador de Unidade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<input type="button" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton" />';

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdOrgaoSistema);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdSistema, $numIdOrgaoSistema);

  $strItensSelOrgaoUnidade = OrgaoINT::montarSelectSiglaTodos('', 'Todos', $numIdOrgaoUnidade);
  $strItensSelUnidade = UnidadeINT::montarSelectSiglaAutorizadas('', 'Todas', $numIdUnidade, $numIdOrgaoUnidade, $numIdSistema);
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Usuários com Permissão');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
  #selOrgaoSistema {position:absolute;left:0%;top:18%;width:25%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:25%;}
  #selSistema {position:absolute;left:0%;top:68%;width:25%;}


  #lblOrgaoUnidade {position:absolute;left:33%;top:0%;width:25%;}
  #selOrgaoUnidade {position:absolute;left:33%;top:18%;width:25%;}

  #lblUnidade {position:absolute;left:33%;top:50%;width:25%;<?=$strDisplayUnidade?>}
  #selUnidade {position:absolute;left:33%;top:68%;width:25%;<?=$strDisplayUnidade?>}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='coordenador_unidade_selecionar'){
  infraReceberSelecao();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Coordenador de Unidade \""+desc+"\"?")){
  document.getElementById('hdnInfraItensSelecionados').value=id;
  document.getElementById('frmCoordenadorUnidadeLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmCoordenadorUnidadeLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Usuário com Permissão selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Coordenadores de Unidade selecionados?")){
  document.getElementById('frmCoordenadorUnidadeLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmCoordenadorUnidadeLista').submit();
  }
  }
  <?
} ?>

  function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  obj.form.submit();
  }

  function trocarOrgaoUnidade(obj){
  document.getElementById('selUnidade').value='';
  obj.form.submit();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody('Coordenadores de Unidade', 'onload="inicializar();"');
?>
  <form id="frmCoordenadorUnidadeLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('coordenador_unidade_lista.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao('Coordenadores de Unidade');
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelObrigatorio">Ó<span
        class="infraTeclaAtalho">r</span>gão do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>

    <label id="lblOrgaoUnidade" for="selOrgaoUnidade" accesskey="o" class="infraLabelObrigatorio">Ór<span
        class="infraTeclaAtalho">g</span>ão da Unidade:</label>
    <select id="selOrgaoUnidade" name="selOrgaoUnidade" onchange="trocarOrgaoUnidade(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaoUnidade?>
    </select>

    <label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">U</span>nidade:</label>
    <select id="selUnidade" name="selUnidade" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitarUnidade?> >
      <?=$strItensSelUnidade?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros, true);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>