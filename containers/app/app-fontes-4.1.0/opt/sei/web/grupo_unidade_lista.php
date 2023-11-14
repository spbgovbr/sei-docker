<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/09/2014 - criado por bcu
 *
 * Versão do Gerador de Código: 1.30.0
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();
  if(strpos($_GET['acao'],'grupo_unidade_institucional')===0){
    $strInstitucional = ' Institucional';
    $strInstitucionais= ' Institucionais';
    $strRadical= 'grupo_unidade_institucional';
    $strStaTipo=GrupoUnidadeRN::$TGU_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strInstitucionais= '';
    $strRadical= 'grupo_unidade';
    $strStaTipo=GrupoUnidadeRN::$TGU_UNIDADE;
  }

  PaginaSEI::getInstance()->prepararSelecao($strRadical.'_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case $strRadical.'_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoUnidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
          $objGrupoUnidadeDTO->setNumIdGrupoUnidade($arrStrIds[$i]);
          $arrObjGrupoUnidadeDTO[] = $objGrupoUnidadeDTO;
        }
        $objGrupoUnidadeRN = new GrupoUnidadeRN();
        $objGrupoUnidadeRN->excluir($arrObjGrupoUnidadeDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case $strRadical.'_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoUnidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
          $objGrupoUnidadeDTO->setNumIdGrupoUnidade($arrStrIds[$i]);
          $arrObjGrupoUnidadeDTO[] = $objGrupoUnidadeDTO;
        }
        $objGrupoUnidadeRN = new GrupoUnidadeRN();
        $objGrupoUnidadeRN->desativar($arrObjGrupoUnidadeDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case $strRadical.'_reativar':
      $strTitulo = 'Reativar Grupos de Envio'.$strInstitucionais;
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjGrupoUnidadeDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
            $objGrupoUnidadeDTO->setNumIdGrupoUnidade($arrStrIds[$i]);
            $arrObjGrupoUnidadeDTO[] = $objGrupoUnidadeDTO;
          }
          $objGrupoUnidadeRN = new GrupoUnidadeRN();
          $objGrupoUnidadeRN->reativar($arrObjGrupoUnidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

    case $strRadical.'_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Envio'.$strInstitucional,'Selecionar Grupos de Envio'.$strInstitucionais);

      //Se cadastrou alguem
      if ($_GET['acao_origem']==$strRadical.'_cadastrar'){
        if (isset($_GET['id_grupo_unidade'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_unidade']);
        }
      }
      break;

    case $strRadical.'_listar':
      $strTitulo = 'Grupos de Envio'.$strInstitucionais;
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  if ($_GET['acao'] == $strRadical.'_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == $strRadical.'_listar' || $_GET['acao'] == $strRadical.'_selecionar' ) {
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao($strRadical . '_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $strRadical . '_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
  $objGrupoUnidadeDTO->retNumIdGrupoUnidade();
  $objGrupoUnidadeDTO->retStrNome();
  $objGrupoUnidadeDTO->retStrDescricao();

  $objGrupoUnidadeDTO->setStrStaTipo($strStaTipo);

  if ($strStaTipo == GrupoUnidadeRN::$TGU_UNIDADE){
    $objGrupoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  }

  if ($_GET['acao'] == $strRadical.'_reativar'){
    //Lista somente inativos
    $objGrupoUnidadeDTO->setBolExclusaoLogica(false);
    $objGrupoUnidadeDTO->setStrSinAtivo('N');
  }

  $objGrupoUnidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoUnidadeDTO);

  $objGrupoUnidadeRN = new GrupoUnidadeRN();

  $arrObjGrupoUnidadeDTO = $objGrupoUnidadeRN->listar($objGrupoUnidadeDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoUnidadeDTO);

  $numRegistros = count($arrObjGrupoUnidadeDTO);

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
    } else if ($_GET['acao']== $strRadical.'_reativar'){
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

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_excluir&acao_origem='.$_GET['acao']);
    }
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!=$strRadical.'_reativar'){
      $strSumarioTabela = 'Tabela de Grupos de Envio'.$strInstitucionais.'.';
      $strCaptionTabela = 'Grupos de Envio'.$strInstitucionais.'.';
    }else{
      $strSumarioTabela = 'Tabela de Grupos de Envio'.$strInstitucionais.' Inativos.';
      $strCaptionTabela = 'Grupos de Envio'.$strInstitucionais.' Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="30%">Nome</th>'."\n";
    $strResultado .= '<th class="infraTh" width="50%">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade(),$arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoUnidadeDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoUnidadeDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_unidade='.$arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de Envio" alt="Consultar Grupo de Envio" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strRadical.'_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_unidade='.$arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de Envio" alt="Alterar Grupo de Envio" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoUnidadeDTO[$i]->getStrNome());
      }
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Grupo de Envio" alt="Desativar Grupo de Envio" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Grupo de Envio" alt="Reativar Grupo de Envio" class="infraImg" /></a>&nbsp;';
      }
      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de Envio" alt="Excluir Grupo de Envio" class="infraImg" /></a>&nbsp;';
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
  <? if ($_GET['acao']==$strRadical.'_selecionar'){ ?>
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  <?}else{?>
  document.getElementById('btnFechar').focus();
  <?}?>

  infraEfeitoTabelas();
  }
<? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo de Envio \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmGrupoUnidadeLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmGrupoUnidadeLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Grupo de Envio selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Grupos de Envio selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmGrupoUnidadeLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmGrupoUnidadeLista').submit();
  }
  }
<? } ?>

<? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de Envio \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmGrupoUnidadeLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmGrupoUnidadeLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Grupo de Envio selecionado.');
  return;
  }
  if (confirm("Confirma reativação dos Grupos de Envio selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmGrupoUnidadeLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmGrupoUnidadeLista').submit();
  }
  }
<? } ?>
<? if ($bolAcaoExcluir){ ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de Envio \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmGrupoUnidadeLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmGrupoUnidadeLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Grupo de Envio selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Grupos de Envio selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmGrupoUnidadeLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmGrupoUnidadeLista').submit();
  }
  }
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmGrupoUnidadeLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros, true);
    //PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
    <br />
    <br />
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>