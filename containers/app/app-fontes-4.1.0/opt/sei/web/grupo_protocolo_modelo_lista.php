<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/08/2012 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.0
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

  PaginaSEI::getInstance()->prepararSelecao('grupo_protocolo_modelo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  switch($_GET['acao']){
    case 'grupo_protocolo_modelo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjGrupoProtocoloModeloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objGrupoProtocoloModeloDTO = new GrupoProtocoloModeloDTO();
          $objGrupoProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($arrStrIds[$i]);
          $arrObjGrupoProtocoloModeloDTO[] = $objGrupoProtocoloModeloDTO;
        }
        $objGrupoProtocoloModeloRN = new GrupoProtocoloModeloRN();
        $objGrupoProtocoloModeloRN->excluir($arrObjGrupoProtocoloModeloDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'grupo_protocolo_modelo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Grupo de Favorito','Selecionar Grupo de Favorito');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='grupo_protocolo_modelo_cadastrar'){
        if (isset($_GET['id_grupo_protocolo_modelo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_grupo_protocolo_modelo']);
        }
      }
      break;

    case 'grupo_protocolo_modelo_listar':
      $strTitulo = 'Grupos de Favoritos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'grupo_protocolo_modelo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'grupo_protocolo_modelo_listar' || $_GET['acao'] == 'grupo_protocolo_modelo_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('grupo_protocolo_modelo_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objGrupoProtocoloModeloDTO = new GrupoProtocoloModeloDTO();
  $objGrupoProtocoloModeloDTO->retNumIdGrupoProtocoloModelo();
  $objGrupoProtocoloModeloDTO->retStrNome();
  $objGrupoProtocoloModeloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('selUnidade');
  if ($numIdUnidade!==''){
    $objGrupoProtocoloModeloDTO->setNumIdUnidade($numIdUnidade);
  }


  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoProtocoloModeloDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objGrupoProtocoloModeloDTO);

  $objGrupoProtocoloModeloRN = new GrupoProtocoloModeloRN();
  $arrObjGrupoProtocoloModeloDTO = $objGrupoProtocoloModeloRN->listar($objGrupoProtocoloModeloDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objGrupoProtocoloModeloDTO);
  $numRegistros = count($arrObjGrupoProtocoloModeloDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='grupo_protocolo_modelo_selecionar'){
      $bolAcaoReativar = false;  
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_protocolo_modelo_alterar');
      $bolAcaoImprimir = false;      
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
      }else{
      $bolAcaoReativar = false; 
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('grupo_protocolo_modelo_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('grupo_protocolo_modelo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('grupo_protocolo_modelo_desativar');
    }
 
    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='grupo_protocolo_modelo_reativar'){ */
      $strSumarioTabela = 'Tabela de Grupos de Favoritos.';
      $strCaptionTabela = 'Grupos de Favoritos';
    /* }else{
      $strSumarioTabela = 'Tabela de Grupos de Favoritos Inativos.';
      $strCaptionTabela = 'Grupos de Favoritos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoProtocoloModeloDTO,'Nome','Nome',$arrObjGrupoProtocoloModeloDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjGrupoProtocoloModeloDTO[$i]->getNumIdGrupoProtocoloModelo(),$arrObjGrupoProtocoloModeloDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjGrupoProtocoloModeloDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjGrupoProtocoloModeloDTO[$i]->getNumIdGrupoProtocoloModelo());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_protocolo_modelo='.$arrObjGrupoProtocoloModeloDTO[$i]->getNumIdGrupoProtocoloModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Grupo de Favorito" alt="Consultar Grupo de Favorito" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_grupo_protocolo_modelo='.$arrObjGrupoProtocoloModeloDTO[$i]->getNumIdGrupoProtocoloModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Grupo de Favorito" alt="Alterar Grupo de Favorito" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjGrupoProtocoloModeloDTO[$i]->getNumIdGrupoProtocoloModelo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjGrupoProtocoloModeloDTO[$i]->getStrNome());
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Grupo de Favorito" alt="Excluir Grupo de Favorito" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'grupo_protocolo_modelo_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='grupo_protocolo_modelo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Grupo de Favorito \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoProtocoloModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoProtocoloModeloLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Favorito selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Grupos de Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoProtocoloModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmGrupoProtocoloModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Grupo de Favorito \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoProtocoloModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoProtocoloModeloLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Favorito selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Grupos de Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoProtocoloModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmGrupoProtocoloModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Grupo de Favorito \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGrupoProtocoloModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoProtocoloModeloLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Grupo de Favorito selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Grupos de Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmGrupoProtocoloModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmGrupoProtocoloModeloLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoProtocoloModeloLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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