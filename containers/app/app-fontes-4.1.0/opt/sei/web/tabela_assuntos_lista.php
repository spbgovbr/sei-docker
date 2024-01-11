<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
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

  PaginaSEI::getInstance()->prepararSelecao('tabela_assuntos_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'tabela_assuntos_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTabelaAssuntosDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
          $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($arrStrIds[$i]);
          $arrObjTabelaAssuntosDTO[] = $objTabelaAssuntosDTO;
        }
        $objTabelaAssuntosRN = new TabelaAssuntosRN();
        $objTabelaAssuntosRN->excluir($arrObjTabelaAssuntosDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'tabela_assuntos_ativar':

      try{
        $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
        $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);

        $objTabelaAssuntosRN = new TabelaAssuntosRN();
        $objTabelaAssuntosRN->ativar($objTabelaAssuntosDTO);

        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }

      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tabela_assuntos'])));
      die;

    case 'tabela_assuntos_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tabela de Assuntos','Selecionar Tabelas de Assuntos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='tabela_assuntos_cadastrar'){
        if (isset($_GET['id_tabela_assuntos'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tabela_assuntos']);
        }
      }
      break;

    case 'tabela_assuntos_listar':
      $strTitulo = 'Tabelas de Assuntos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'tabela_assuntos_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'tabela_assuntos_listar' || $_GET['acao'] == 'tabela_assuntos_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tabela_assuntos_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
  $objTabelaAssuntosDTO->retNumIdTabelaAssuntos();
  $objTabelaAssuntosDTO->retStrNome();
  //$objTabelaAssuntosDTO->retStrDescricao();
  $objTabelaAssuntosDTO->retStrSinAtual();

  PaginaSEI::getInstance()->prepararOrdenacao($objTabelaAssuntosDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objTabelaAssuntosDTO);

  $objTabelaAssuntosRN = new TabelaAssuntosRN();
  $arrObjTabelaAssuntosDTO = $objTabelaAssuntosRN->listar($objTabelaAssuntosDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objTabelaAssuntosDTO);
  $numRegistros = count($arrObjTabelaAssuntosDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='tabela_assuntos_selecionar'){
      $bolAcaoAssuntoListar = false;
      $bolAcaoMapeamentoAssuntoListar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_alterar');
      $bolAcaoExcluir = false;
      $bolCheck = true;
    }else{
      $bolAcaoAtivar = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_ativar');
      $bolAcaoAssuntoListar = SessaoSEI::getInstance()->verificarPermissao('assunto_listar');
      $bolAcaoMapeamentoAssuntoListar = SessaoSEI::getInstance()->verificarPermissao('mapeamento_assunto_listar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_alterar');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tabela_assuntos_excluir');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tabela_assuntos_excluir&acao_origem='.$_GET['acao']);
    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Tabelas de Assuntos.';
    $strCaptionTabela = 'Tabelas de Assuntos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTabelaAssuntosDTO,'Nome','Nome',$arrObjTabelaAssuntosDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTabelaAssuntosDTO,'Descrição','Descricao',$arrObjTabelaAssuntosDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ativa</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos(),$arrObjTabelaAssuntosDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTabelaAssuntosDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTabelaAssuntosDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.($arrObjTabelaAssuntosDTO[$i]->getStrSinAtual()=='S'?'Sim':'&nbsp;').'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos());

      if ($bolAcaoAtivar || $bolAcaoExcluir){
        $strId = $arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjTabelaAssuntosDTO[$i]->getStrNome());
      }

      if ($bolAcaoAtivar && $arrObjTabelaAssuntosDTO[$i]->getStrSinAtual()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoAtivar(\''.$strDescricao.'\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tabela_assuntos_ativar&acao_origem='.$_GET['acao'].'&id_tabela_assuntos='.$strId).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_ATIVAR_TABELA.'" title="Ativar Tabela de Assuntos" alt="Ativar Tabela de Assuntos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoMapeamentoAssuntoListar && $arrObjTabelaAssuntosDTO[$i]->getStrSinAtual()=='S'){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=mapeamento_assunto_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tabela_assuntos_origem='.$arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_MAPEAMENTO_ASSUNTO.'" title="Mapeamentos" alt="Mapeamentos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAssuntoListar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tabela_assuntos='.$arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::VALORES.'" title="Assuntos da Tabela" alt="Assuntos da Tabela" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tabela_assuntos_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tabela_assuntos='.$arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Tabela de Assuntos" alt="Consultar Tabela de Assuntos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tabela_assuntos_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tabela_assuntos='.$arrObjTabelaAssuntosDTO[$i]->getNumIdTabelaAssuntos()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Tabela de Assuntos" alt="Alterar Tabela de Assuntos" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Tabela de Assuntos" alt="Excluir Tabela de Assuntos" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'tabela_assuntos_selecionar'){
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
//<script type="text/javascript">

function inicializar(){
  if ('<?=$_GET['acao']?>'=='tabela_assuntos_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoAtivar){ ?>
function acaoAtivar(desc,url){
  if (confirm("ATENÇÃO: esta operação talvez não possa ser desfeita.\n\nConfirma ativação da Tabela de Assuntos \""+desc+"\"?")){
    document.getElementById('frmTabelaAssuntosLista').action = url;
    document.getElementById('frmTabelaAssuntosLista').submit();
    infraExibirAviso();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Tabela de Assuntos \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTabelaAssuntosLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTabelaAssuntosLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Tabela de Assuntos selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Tabelas de Assuntos selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTabelaAssuntosLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTabelaAssuntosLista').submit();
  }
}
<? } ?>

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTabelaAssuntosLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
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