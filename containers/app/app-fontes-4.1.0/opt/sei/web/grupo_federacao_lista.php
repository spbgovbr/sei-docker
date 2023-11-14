<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
*/

try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  $bolHabilitado = ConfiguracaoSEI::getInstance()->getValor('Federacao','Habilitado',false,false);

  if (strpos($_GET['acao'], 'grupo_federacao_institucional') === 0) {
    $strInstitucional = ' Institucional';
    $strInstitucionais = ' Institucionais';
    $strRadical = 'grupo_federacao_institucional';
    $strStaTipo = GrupoFederacaoRN::$TGF_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strInstitucionais = '';
    $strRadical = 'grupo_federacao';
    $strStaTipo = GrupoFederacaoRN::$TGF_UNIDADE;
  }

  PaginaSEI::getInstance()->prepararSelecao($strRadical . '_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch ($_GET['acao']) {
    case $strRadical . '_excluir':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoFederacaoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
          $objGrupoFederacaoDTO->setNumIdGrupoFederacao($arrStrIds[$i]);
          $arrObjGrupoFederacaoDTO[] = $objGrupoFederacaoDTO;
        }
        $objGrupoFederacaoRN = new GrupoFederacaoRN();
        $objGrupoFederacaoRN->excluir($arrObjGrupoFederacaoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case $strRadical.'_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoFederacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
          $objGrupoFederacaoDTO->setNumIdGrupoFederacao($arrStrIds[$i]);
          $arrObjGrupoFederacaoDTO[] = $objGrupoFederacaoDTO;
        }
        $objGrupoFederacaoRN = new GrupoFederacaoRN();
        $objGrupoFederacaoRN->desativar($arrObjGrupoFederacaoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case $strRadical.'_reativar':
      $strTitulo = 'Reativar Grupos do SEI Federação'.$strInstitucionais;
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjGrupoFederacaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
            $objGrupoFederacaoDTO->setNumIdGrupoFederacao($arrStrIds[$i]);
            $arrObjGrupoFederacaoDTO[] = $objGrupoFederacaoDTO;
          }
          $objGrupoFederacaoRN = new GrupoFederacaoRN();
          $objGrupoFederacaoRN->reativar($arrObjGrupoFederacaoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

    case $strRadical . '_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo do SEI Federação'.$strInstitucional, 'Selecionar Grupos do SEI Federação'.$strInstitucionais);

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == $strRadical . '_cadastrar') {
        if (isset($_GET['id_grupo_federacao'])) {
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_federacao']);
        }
      }
      break;

    case $strRadical . '_listar':
      $strTitulo = 'Grupos do SEI Federação'.$strInstitucionais;
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  if ($_GET['acao'] == $strRadical . '_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == $strRadical . '_listar' || $_GET['acao'] == $strRadical . '_selecionar') {
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao($strRadical . '_cadastrar');
    if ($bolAcaoCadastrar && $bolHabilitado) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strRadical . '_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
  $objGrupoFederacaoDTO->retNumIdGrupoFederacao();
  //$objGrupoFederacaoDTO->retNumIdUnidade();
  $objGrupoFederacaoDTO->retStrNome();
  //$objGrupoFederacaoDTO->retStrDescricao();

  $objGrupoFederacaoDTO->setStrStaTipo($strStaTipo);

  if ($strStaTipo == GrupoFederacaoRN::$TGF_UNIDADE){
    $objGrupoFederacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  }

  if ($_GET['acao'] == $strRadical.'_reativar'){
    //Lista somente inativos
    $objGrupoFederacaoDTO->setBolExclusaoLogica(false);
    $objGrupoFederacaoDTO->setStrSinAtivo('N');
  }

  $objGrupoFederacaoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoFederacaoDTO);

  $objGrupoFederacaoRN = new GrupoFederacaoRN();
  $arrObjGrupoFederacaoDTO = $objGrupoFederacaoRN->listar($objGrupoFederacaoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoFederacaoDTO);

  $numRegistros = count($arrObjGrupoFederacaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']==$strRadical.'_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']==$strRadical.'_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao($strRadical.'_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<input type="button" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton" />';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }


    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!=$strRadical.'_reativar'){
      $strSumarioTabela = 'Tabela de Grupos do SEI Federação '.$strInstitucionais.'.';
      $strCaptionTabela = 'Grupos do SEI Federação'.$strInstitucionais;
    }else{
      $strSumarioTabela = 'Tabela de Grupos do SEI Federação'.$strInstitucionais.' Inativos.';
      $strCaptionTabela = 'Grupos do SEI Federação'.$strInstitucionais.' Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //75
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoFederacaoDTO,'','IdUnidade',$arrObjGrupoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoFederacaoDTO,'Nome','Nome',$arrObjGrupoFederacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoFederacaoDTO,'Descrição','Descricao',$arrObjGrupoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoFederacaoDTO[$i]->getNumIdGrupoFederacao(),$arrObjGrupoFederacaoDTO[$i]->getStrNome()).'</td>';
      }
      //$strResultado .= '<td>'.$arrObjGrupoFederacaoDTO[$i]->getNumIdUnidade().'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoFederacaoDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.$arrObjGrupoFederacaoDTO[$i]->getStrDescricao().'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoFederacaoDTO[$i]->getNumIdGrupoFederacao());      
      
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
          $strId = $arrObjGrupoFederacaoDTO[$i]->getNumIdGrupoFederacao();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoFederacaoDTO[$i]->getStrNome());
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_federacao='.$arrObjGrupoFederacaoDTO[$i]->getNumIdGrupoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo do SEI Federação" alt="Consultar Grupo do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_federacao='.$arrObjGrupoFederacaoDTO[$i]->getNumIdGrupoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo do SEI Federação" alt="Alterar Grupo do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Grupo do SEI Federação" alt="Desativar Grupo do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'"  onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Grupo do SEI Federação" alt="Reativar Grupo do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo do SEI Federação" alt="Excluir Grupo do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == $strRadical.'_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  <?if ($_GET['acao']==$strRadical.'_selecionar'){?>
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  <?}else{?>
   //document.getElementById('btnFechar').focus(); 
   setTimeout("document.getElementById('btnFechar').focus()", 50);
  <?}?>

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo do SEI Federação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoFederacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoFederacaoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo do SEI Federação selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos do SEI Federação selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoFederacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo do SEI Federação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoFederacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoFederacaoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo do SEI Federação selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos do SEI Federação selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoFederacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo do SEI Federação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoFederacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoFederacaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo do SEI Federação selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos do SEI Federação selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoFederacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoFederacaoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoFederacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  if (!$bolHabilitado) {
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblDesabilitado" class="infraLabelObrigatorio">O SEI Federação está desabilitado nesta instalação.</label>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
  }
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>