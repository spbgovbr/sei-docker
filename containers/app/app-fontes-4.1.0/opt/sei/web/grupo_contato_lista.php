<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
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

  if (strpos($_GET['acao'], 'grupo_contato_institucional') === 0) {
    $strInstitucional = ' Institucional';
    $strInstitucionais = ' Institucionais';
    $strRadical = 'grupo_contato_institucional';
    $strStaTipo = GrupoContatoRN::$TGC_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strInstitucionais = '';
    $strRadical = 'grupo_contato';
    $strStaTipo = GrupoContatoRN::$TGC_UNIDADE;
  }

  PaginaSEI::getInstance()->prepararSelecao($strRadical . '_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch ($_GET['acao']) {
    case $strRadical . '_excluir':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoContatoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objGrupoContatoDTO = new GrupoContatoDTO();
          $objGrupoContatoDTO->setNumIdGrupoContato($arrStrIds[$i]);
          $arrObjGrupoContatoDTO[] = $objGrupoContatoDTO;
        }
        $objGrupoContatoRN = new GrupoContatoRN();
        $objGrupoContatoRN->excluirRN0475($arrObjGrupoContatoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case $strRadical.'_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoContatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoContatoDTO = new GrupoContatoDTO();
          $objGrupoContatoDTO->setNumIdGrupoContato($arrStrIds[$i]);
          $arrObjGrupoContatoDTO[] = $objGrupoContatoDTO;
        }
        $objGrupoContatoRN = new GrupoContatoRN();
        $objGrupoContatoRN->desativar($arrObjGrupoContatoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case $strRadical.'_reativar':
      $strTitulo = 'Reativar Grupos de Contatos'.$strInstitucionais;
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjGrupoContatoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objGrupoContatoDTO = new GrupoContatoDTO();
            $objGrupoContatoDTO->setNumIdGrupoContato($arrStrIds[$i]);
            $arrObjGrupoContatoDTO[] = $objGrupoContatoDTO;
          }
          $objGrupoContatoRN = new GrupoContatoRN();
          $objGrupoContatoRN->reativar($arrObjGrupoContatoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

    case $strRadical . '_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Contatos'.$strInstitucional, 'Selecionar Grupos de Contatos'.$strInstitucionais);

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == $strRadical . '_cadastrar') {
        if (isset($_GET['id_grupo_contato'])) {
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_contato']);
        }
      }
      break;

    case $strRadical . '_listar':
      $strTitulo = 'Grupos de Contatos'.$strInstitucionais;
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
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strRadical . '_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objGrupoContatoDTO = new GrupoContatoDTO();
  $objGrupoContatoDTO->retNumIdGrupoContato();
  //$objGrupoContatoDTO->retNumIdUnidade();
  $objGrupoContatoDTO->retStrNome();
  //$objGrupoContatoDTO->retStrDescricao();

  $objGrupoContatoDTO->setStrStaTipo($strStaTipo);

  if ($strStaTipo == GrupoContatoRN::$TGC_UNIDADE){
    $objGrupoContatoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  }

  if ($_GET['acao'] == $strRadical.'_reativar'){
    //Lista somente inativos
    $objGrupoContatoDTO->setBolExclusaoLogica(false);
    $objGrupoContatoDTO->setStrSinAtivo('N');
  }

  $objGrupoContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoContatoDTO);

  $objGrupoContatoRN = new GrupoContatoRN();
  $arrObjGrupoContatoDTO = $objGrupoContatoRN->listarRN0477($objGrupoContatoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoContatoDTO);

  $numRegistros = count($arrObjGrupoContatoDTO);

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
      $strSumarioTabela = 'Tabela de Grupos de Contatos'.$strInstitucionais.'.';
      $strCaptionTabela = 'Grupos de Contatos'.$strInstitucionais;
    }else{
      $strSumarioTabela = 'Tabela de Grupos de Contatos'.$strInstitucionais.' Inativos.';
      $strCaptionTabela = 'Grupos de Contatos'.$strInstitucionais.' Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //75
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoContatoDTO,'','IdUnidade',$arrObjGrupoContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoContatoDTO,'Nome','Nome',$arrObjGrupoContatoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoContatoDTO,'Descrição','Descricao',$arrObjGrupoContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoContatoDTO[$i]->getNumIdGrupoContato(),$arrObjGrupoContatoDTO[$i]->getStrNome()).'</td>';
      }
      //$strResultado .= '<td>'.$arrObjGrupoContatoDTO[$i]->getNumIdUnidade().'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoContatoDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.$arrObjGrupoContatoDTO[$i]->getStrDescricao().'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoContatoDTO[$i]->getNumIdGrupoContato());      
      
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
          $strId = $arrObjGrupoContatoDTO[$i]->getNumIdGrupoContato();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoContatoDTO[$i]->getStrNome());
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_contato='.$arrObjGrupoContatoDTO[$i]->getNumIdGrupoContato()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de Contatos" alt="Consultar Grupo de Contatos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_contato='.$arrObjGrupoContatoDTO[$i]->getNumIdGrupoContato()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de Contatos" alt="Alterar Grupo de Contatos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Grupo de Contatos" alt="Desativar Grupo de Contatos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'"  onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Grupo de Contatos" alt="Reativar Grupo de Contatos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de Contatos" alt="Excluir Grupo de Contatos" class="infraImg" /></a>&nbsp;';
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
  if (confirm("Confirma desativação do Grupo de Contatos \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoContatoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoContatoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Contatos selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos Contato selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoContatoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoContatoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de Contatos \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoContatoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoContatoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Contatos selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos Contato selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoContatoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoContatoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de Contatos \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoContatoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoContatoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Contatos selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos Contato selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoContatoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoContatoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoContatoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>