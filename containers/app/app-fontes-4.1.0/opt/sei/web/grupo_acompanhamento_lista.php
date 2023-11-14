<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
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

  PaginaSEI::getInstance()->prepararSelecao('grupo_acompanhamento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  switch($_GET['acao']){
    case 'grupo_acompanhamento_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoAcompanhamentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();
          $objGrupoAcompanhamentoDTO->setNumIdGrupoAcompanhamento($arrStrIds[$i]);
          $arrObjGrupoAcompanhamentoDTO[] = $objGrupoAcompanhamentoDTO;
        }
        $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
        $objGrupoAcompanhamentoRN->excluir($arrObjGrupoAcompanhamentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'grupo_acompanhamento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Acompanhamento','Selecionar Grupos de Acompanhamento');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='grupo_acompanhamento_cadastrar'){
        if (isset($_GET['id_grupo_acompanhamento'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_acompanhamento']);
        }
      }
      break;

    case 'grupo_acompanhamento_listar':
      $strTitulo = 'Grupos de Acompanhamento';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'grupo_acompanhamento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'grupo_acompanhamento_listar' || $_GET['acao'] == 'grupo_acompanhamento_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();
  $objGrupoAcompanhamentoDTO->retNumIdGrupoAcompanhamento();
  $objGrupoAcompanhamentoDTO->retStrNome();
  $objGrupoAcompanhamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('selUnidade');
  if ($numIdUnidade!==''){
    $objGrupoAcompanhamentoDTO->setNumIdUnidade($numIdUnidade);
  }


  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoAcompanhamentoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoAcompanhamentoDTO);

  $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
  $arrObjGrupoAcompanhamentoDTO = $objGrupoAcompanhamentoRN->listar($objGrupoAcompanhamentoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoAcompanhamentoDTO);
  $numRegistros = count($arrObjGrupoAcompanhamentoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='grupo_acompanhamento_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_desativar');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='grupo_acompanhamento_reativar'){ */
      $strSumarioTabela = 'Tabela de Grupos de Acompanhamento.';
      $strCaptionTabela = 'Grupos de Acompanhamento';
    /* }else{
      $strSumarioTabela = 'Tabela de Grupos de Acompanhamento Inativos.';
      $strCaptionTabela = 'Grupos de Acompanhamento Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO,'Nome','Nome',$arrObjGrupoAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%" >Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(),$arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_acompanhamento='.$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de Acompanhamento" alt="Consultar Grupo de Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_acompanhamento='.$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de Acompanhamento" alt="Alterar Grupo de Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoAcompanhamentoDTO[$i]->getStrNome());
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de Acompanhamento" alt="Excluir Grupo de Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'grupo_acompanhamento_selecionar'){
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
#lblUnidade {position:absolute;left:0%;top:0%;width:25%;}
#selUnidade {position:absolute;left:0%;top:40%;width:25%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='grupo_acompanhamento_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo de Acompanhamento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoAcompanhamentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoAcompanhamentoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Acompanhamento selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos de Acompanhamento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoAcompanhamentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoAcompanhamentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de Acompanhamento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoAcompanhamentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoAcompanhamentoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Acompanhamento selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos de Acompanhamento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoAcompanhamentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoAcompanhamentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de Acompanhamento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoAcompanhamentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoAcompanhamentoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Acompanhamento selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos de Acompanhamento selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoAcompanhamentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoAcompanhamentoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoAcompanhamentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>