<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.10.1
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

  PaginaSEI::getInstance()->prepararSelecao('tipo_procedimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtNomeTipoProcessoPesquisa', 'txtAssuntoTipoProcesso', 'hdnIdAssuntoTipoProcesso', 'selSinalizacaoTipoProcedimento', 'selNivelAcessoTipoProcedimento'));


  switch($_GET['acao']){
    case 'tipo_procedimento_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTipoProcedimentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
          $objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrStrIds[$i]);
          $arrObjTipoProcedimentoDTO[] = $objTipoProcedimentoDTO;
        }
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $objTipoProcedimentoRN->excluirRN0268($arrObjTipoProcedimentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'tipo_procedimento_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTipoProcedimentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
          $objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrStrIds[$i]);
          $arrObjTipoProcedimentoDTO[] = $objTipoProcedimentoDTO;
        }
        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $objTipoProcedimentoRN->desativarRN0269($arrObjTipoProcedimentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'tipo_procedimento_reativar':
      $strTitulo = 'Reativar Tipo de Processo';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjTipoProcedimentoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
            $objTipoProcedimentoDTO->setNumIdTipoProcedimento($arrStrIds[$i]);
            $arrObjTipoProcedimentoDTO[] = $objTipoProcedimentoDTO;
          }

          $objTipoProcedimentoRN = new TipoProcedimentoRN();
          $objTipoProcedimentoRN->reativarRN0352($arrObjTipoProcedimentoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

    case 'tipo_procedimento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Processo','Selecionar Tipos de Processo');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='tipo_procedimento_cadastrar'){
        if (isset($_GET['id_tipo_procedimento'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tipo_procedimento']);
        }
      }
      break;

    case 'tipo_procedimento_listar':
      $strTitulo = 'Tipos de Processo';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'tipo_procedimento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'tipo_procedimento_listar' || $_GET['acao'] == 'tipo_procedimento_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
  $objTipoProcedimentoDTO->retStrNome();
  //$objTipoProcedimentoDTO->retStrDescricao();

  if ($_GET['acao'] == 'tipo_procedimento_reativar'){
    //Lista somente inativos
    $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
    $objTipoProcedimentoDTO->setStrSinAtivo('N');
  }

  $strNomeTipoProcessoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtNomeTipoProcessoPesquisa');
  if (trim($strNomeTipoProcessoPesquisa) != ''){
    $objTipoProcedimentoDTO->setStrNome(trim($strNomeTipoProcessoPesquisa));
  }

  $strIdAssunto = PaginaSEI::getInstance()->recuperarCampo('hdnIdAssuntoTipoProcesso');
  $strDescricaoAssunto = PaginaSEI::getInstance()->recuperarCampo('txtAssuntoTipoProcesso');
  if(!InfraString::isBolVazia($strIdAssunto)){
    $objTipoProcedimentoDTO->setNumIdAssunto($strIdAssunto);
  }

  $strSinalizacaoTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selSinalizacaoTipoProcedimento');
  if ($strSinalizacaoTipoProcedimento!=='' && $strSinalizacaoTipoProcedimento != 'null'){

    switch($strSinalizacaoTipoProcedimento){
      case TipoProcedimentoRN::$TS_EXCLUSIVO_OUVIDORIA:
        $objTipoProcedimentoDTO->setStrSinOuvidoria('S');
        break;

      case TipoProcedimentoRN::$TS_PROCESSO_UNICO:
        $objTipoProcedimentoDTO->setStrSinIndividual('S');
        break;

      case TipoProcedimentoRN::$TS_INTERNO_SISTEMA:
        $objTipoProcedimentoDTO->setStrSinInterno('S');
        break;
    }
  }

  $strStaNivelAcessoTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selNivelAcessoTipoProcedimento');
  if ($strStaNivelAcessoTipoProcedimento!=='' && $strStaNivelAcessoTipoProcedimento != 'null'){
    $objNivelAcessoPermitidoDTO = new NivelAcessoPermitidoDTO();
    $objNivelAcessoPermitidoDTO->setStrStaNivelAcesso($strStaNivelAcessoTipoProcedimento);
    $objTipoProcedimentoDTO->setArrObjNivelAcessoPermitidoDTO(array($objNivelAcessoPermitidoDTO));
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objTipoProcedimentoDTO,'Nome',InfraDTO::$TIPO_ORDENACAO_ASC);

  PaginaSEI::getInstance()->prepararPaginacao($objTipoProcedimentoDTO);

  $objTipoProcedimentoRN = new TipoProcedimentoRN();
  $arrObjTipoProcedimentoDTO = $objTipoProcedimentoRN->pesquisar($objTipoProcedimentoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objTipoProcedimentoDTO);
  $numRegistros = count($arrObjTipoProcedimentoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='tipo_procedimento_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_alterar');
      $bolAcaoAdicionar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_adicionar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='tipo_procedimento_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoAdicionar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_adicionar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('tipo_procedimento_desativar');
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" acesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="Imprimir" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strResultado = '';

    if ($_GET['acao']!='tipo_procedimento_reativar'){
      $strSumarioTabela = 'Tabela de Tipos de Processo.';
      $strCaptionTabela = 'Tipos de Processo';
    }else{
      $strSumarioTabela = 'Tabela de Tipos de Processo Inativos.';
      $strCaptionTabela = 'Tipos de Processo Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //70
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO,'ID','IdTipoProcedimento',$arrObjTipoProcedimentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO,'Nome','Nome',$arrObjTipoProcedimentoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO,'Descrição','Descricao',$arrObjTipoProcedimentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';

      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(),$arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>';
      }

      $strResultado .= '<td align="center">'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_procedimento='.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Tipo de Processo" alt="Consultar Tipo de Processo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_procedimento='.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Tipo de Processo" alt="Alterar Tipo de Processo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'"  onclick="acaoDesativar(\''.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'\',\''.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Tipo de Processo" alt="Desativar Tipo de Processo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'"  onclick="acaoReativar(\''.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'\',\''.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Tipo de Processo" alt="Reativar Tipo de Processo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'"  onclick="acaoExcluir(\''.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento().'\',\''.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Tipo de Processo" alt="Excluir Tipo de Processo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'tipo_procedimento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="Fechar" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="Fechar" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');

  $strItensSelSinalizacaoTipoProcedimento = TipoProcedimentoINT::montarSelectSinalizacao('null','&nbsp;',$strSinalizacaoTipoProcedimento);
  $strItensSelNivelAcessoTipoProcedimento = ProtocoloINT::montarSelectStaNivelAcesso('null','&nbsp;', $strStaNivelAcessoTipoProcedimento);

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

#lblNomeTipoProcessoPesquisa {position:absolute;left:0%;top:0%;}
#txtNomeTipoProcessoPesquisa {position:absolute;left:0%;top:40%;width:25%;}

#lblAssuntoTipoProcesso {position:absolute;left:26%;top:0%;}
#txtAssuntoTipoProcesso {position:absolute;left:26%;top:40%;width:25%;}

#lblSinalizacaoTipoProcedimento {position:absolute;left:52%;top:0%;}
#selSinalizacaoTipoProcedimento {position:absolute;left:52%;top:40%;width:25%;}

#lblNivelAcessoTipoProcedimento {position:absolute;left:78%;top:0%;}
#selNivelAcessoTipoProcedimento {position:absolute;left:78%;top:40%;width:20%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarAssuntoRI1223 = null;


function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_procedimento_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
   //document.getElementById('btnFechar').focus();
   setTimeout("document.getElementById('btnFechar').focus()", 50);
 }

  objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssuntoTipoProcesso','txtAssuntoTipoProcesso','<?=$strLinkAjaxAssuntoRI1223?>');
  objAutoCompletarAssuntoRI1223.limparCampo = true;
  objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
  return 'palavras_pesquisa='+document.getElementById('txtAssuntoTipoProcesso').value;
  };
  objAutoCompletarAssuntoRI1223.selecionar('<?=$strIdAssunto;?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoAssunto,false)?>');

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Tipo de Processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoProcedimentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTipoProcedimentoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Processo selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Tipos de Processo selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoProcedimentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTipoProcedimentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Tipo de Processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoProcedimentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTipoProcedimentoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Processo selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Tipos de Processo selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoProcedimentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTipoProcedimentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Tipo de Processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoProcedimentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTipoProcedimentoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Processo selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Tipos de Processo selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoProcedimentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTipoProcedimentoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoProcedimentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblNomeTipoProcessoPesquisa" accesskey="o" for="txtNomeTipoProcessoPesquisa" class="infraLabelOpcional">N<span class="infraTeclaAtalho">o</span>me:</label>
  <input type="text" id="txtNomeTipoProcessoPesquisa" name="txtNomeTipoProcessoPesquisa" value="<?=PaginaSEI::tratarHTML($strNomeTipoProcessoPesquisa)?>" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblAssuntoTipoProcesso" for="txtAssuntoTipoProcesso" class="infraLabelOpcional">Assunto:</label>
  <input type="text" id="txtAssuntoTipoProcesso" name="txtAssuntoTipoProcesso" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($strDescricaoAssunto)?>" />

  <label id="lblSinalizacaoTipoProcedimento" for="selSinalizacaoTipoProcedimento" accesskey="" class="infraLabelOpcional">Sinalização:</label>
  <select id="selSinalizacaoTipoProcedimento" name="selSinalizacaoTipoProcedimento" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelSinalizacaoTipoProcedimento;?>
  </select>

  <label id="lblNivelAcessoTipoProcedimento" for="selNivelAcessoTipoProcedimento" accesskey="" class="infraLabelOpcional">Nível de Acesso:</label>
  <select id="selNivelAcessoTipoProcedimento" name="selNivelAcessoTipoProcedimento" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelNivelAcessoTipoProcedimento;?>
  </select>

  <input type="hidden" id="hdnIdAssuntoTipoProcesso" name="hdnIdAssuntoTipoProcesso" class="infraText" value="<?=$strIdAssunto?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>