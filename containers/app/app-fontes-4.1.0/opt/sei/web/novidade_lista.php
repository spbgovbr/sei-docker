<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->prepararSelecao('novidade_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'novidade_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjNovidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objNovidadeDTO = new NovidadeDTO();
          $objNovidadeDTO->setNumIdNovidade($arrStrIds[$i]);
          $arrObjNovidadeDTO[] = $objNovidadeDTO;
        }
        $objNovidadeRN = new NovidadeRN();
        $objNovidadeRN->excluir($arrObjNovidadeDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'novidade_liberar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjNovidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objNovidadeDTO = new NovidadeDTO();
          $objNovidadeDTO->setNumIdNovidade($arrStrIds[$i]);
          $arrObjNovidadeDTO[] = $objNovidadeDTO;
        }
        $objNovidadeRN = new NovidadeRN();
        $objNovidadeRN->liberar($arrObjNovidadeDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'novidade_cancelar_liberacao':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjNovidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objNovidadeDTO = new NovidadeDTO();
          $objNovidadeDTO->setNumIdNovidade($arrStrIds[$i]);
          $arrObjNovidadeDTO[] = $objNovidadeDTO;
        }
        $objNovidadeRN = new NovidadeRN();
        $objNovidadeRN->cancelarLiberacao($arrObjNovidadeDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'novidade_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Novidade','Selecionar Novidades');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='novidade_cadastrar'){
        if (isset($_GET['id_novidade'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_novidade']);
        }
      }
      break;

    case 'novidade_listar':
      $strTitulo = 'Novidades';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'novidade_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'novidade_listar' || $_GET['acao'] == 'novidade_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('novidade_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objNovidadeDTO = new NovidadeDTO();
  $objNovidadeDTO->retNumIdNovidade();
  $objNovidadeDTO->retStrTitulo();
  $objNovidadeDTO->retStrDescricao();
  $objNovidadeDTO->retDthLiberacao();
  $objNovidadeDTO->retStrSiglaUsuario();
  $objNovidadeDTO->retStrNomeUsuario();

/* 
  if ($_GET['acao'] == 'novidade_reativar'){
    //Lista somente inativos
    $objNovidadeDTO->setBolExclusaoLogica(false);
    $objNovidadeDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objNovidadeDTO, 'Liberacao', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objNovidadeDTO);

  $objNovidadeRN = new NovidadeRN();
  $arrObjNovidadeDTO = $objNovidadeRN->listar($objNovidadeDTO);

  PaginaSEI::getInstance()->processarPaginacao($objNovidadeDTO);
  $numRegistros = count($arrObjNovidadeDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='novidade_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('novidade_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('novidade_alterar');
      $bolAcaoLiberar = false;
      $bolAcaoCancelarLiberacao = false;
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='novidade_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('novidade_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('novidade_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoLiberar = false;
      $bolAcaoCancelarLiberacao = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('novidade_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('novidade_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('novidade_alterar');
      $bolAcaoLiberar = SessaoSEI::getInstance()->verificarPermissao('novidade_liberar');
      $bolAcaoCancelarLiberacao = SessaoSEI::getInstance()->verificarPermissao('novidade_cancelar_liberacao');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('novidade_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('novidade_desativar');
    }

    if ($bolAcaoLiberar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="" id="btnLiberar" value="Liberar" onclick="acaoLiberacaoMultipla();" class="infraButton">Liberar</button>';
      $strLinkLiberar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_liberar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoCancelarLiberacao){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="" id="btnCancelarLiberacao" value="Cancelar Liberação" onclick="acaoCancelarLiberacaoMultipla();" class="infraButton">Cancelar Liberação</button>';
      $strLinkCancelarLiberacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_cancelar_liberacao&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='novidade_reativar'){ */
      $strSumarioTabela = 'Tabela de Novidades.';
      $strCaptionTabela = 'Novidades';
    /* }else{
      $strSumarioTabela = 'Tabela de Novidades Inativas.';
      $strCaptionTabela = 'Novidades Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objNovidadeDTO,'Título','Titulo',$arrObjNovidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objNovidadeDTO,'Descrição','Descricao',$arrObjNovidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objNovidadeDTO,'Usuário','SiglaUsuario',$arrObjNovidadeDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objNovidadeDTO,'Data','Novidade',$arrObjNovidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">'.PaginaSEI::getInstance()->getThOrdenacao($objNovidadeDTO,'Liberação','Liberacao',$arrObjNovidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjNovidadeDTO[$i]->getNumIdNovidade(),$arrObjNovidadeDTO[$i]->getStrTitulo()).'</td>';
      }
      $strResultado .= '<td valign="top">'.PaginaSEI::tratarHTML($arrObjNovidadeDTO[$i]->getStrTitulo()).'</td>';
      $strResultado .= '<td valign="top">'.$arrObjNovidadeDTO[$i]->getStrDescricao().'</td>';
      $strResultado .= '<td align="center" valign="top"><a alt="'.PaginaSEI::tratarHTML($arrObjNovidadeDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjNovidadeDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjNovidadeDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td align="center" valign="top">'.($arrObjNovidadeDTO[$i]->getDthLiberacao()!=NovidadeRN::$DATA_NAO_LIBERADO?$arrObjNovidadeDTO[$i]->getDthLiberacao():'').'</td>';
      $strResultado .= '<td align="center" valign="top">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjNovidadeDTO[$i]->getNumIdNovidade());

      if ($bolAcaoLiberar || $bolAcaoCancelarLiberacao || $bolAcaoExcluir){
        $strId = $arrObjNovidadeDTO[$i]->getNumIdNovidade();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjNovidadeDTO[$i]->getStrTitulo());
      }
      
      if ($bolAcaoLiberar && $arrObjNovidadeDTO[$i]->getDthLiberacao()==NovidadeRN::$DATA_NAO_LIBERADO){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoLiberar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::NOVIDADE_LIBERAR.'" title="Liberar Novidade" alt="Liberar Novidade" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoCancelarLiberacao && $arrObjNovidadeDTO[$i]->getDthLiberacao()!=NovidadeRN::$DATA_NAO_LIBERADO){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoCancelarLiberacao(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeRemover().'" title="Cancelar Liberação da Novidade" alt="Cancelar Liberação da Novidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_novidade='.$arrObjNovidadeDTO[$i]->getNumIdNovidade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Novidade" alt="Consultar Novidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar && $arrObjNovidadeDTO[$i]->getDthLiberacao()==NovidadeRN::$DATA_NAO_LIBERADO){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_novidade='.$arrObjNovidadeDTO[$i]->getNumIdNovidade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Novidade" alt="Alterar Novidade" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir && $arrObjNovidadeDTO[$i]->getDthLiberacao()==NovidadeRN::$DATA_NAO_LIBERADO){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Novidade" alt="Excluir Novidade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'novidade_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
  $objEditorRN=new EditorRN();
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
echo $objEditorRN->montarCssEditor(0);
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='novidade_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoLiberar){ ?>
function acaoLiberar(id,desc){
  if (confirm("Confirma liberação da Novidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmNovidadeLista').action='<?=$strLinkLiberar?>';
    document.getElementById('frmNovidadeLista').submit();
  }
}

function acaoLiberacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Novidade selecionada.');
    return;
  }
  if (confirm("Confirma liberação das Novidades selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmNovidadeLista').action='<?=$strLinkLiberar?>';
    document.getElementById('frmNovidadeLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoCancelarLiberacao){ ?>
function acaoCancelarLiberacao(id,desc){
  if (confirm("Confirma cancelamento da liberação da Novidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmNovidadeLista').action='<?=$strLinkCancelarLiberacao?>';
    document.getElementById('frmNovidadeLista').submit();
  }
}

function acaoCancelarLiberacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Novidade selecionada.');
    return;
  }
  if (confirm("Confirma cancelamento da liberação das Novidades selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmNovidadeLista').action='<?=$strLinkCancelarLiberacao?>';
    document.getElementById('frmNovidadeLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Novidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmNovidadeLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmNovidadeLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Novidade selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Novidades selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmNovidadeLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmNovidadeLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmNovidadeLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>