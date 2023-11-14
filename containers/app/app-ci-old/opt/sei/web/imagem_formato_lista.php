<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/04/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
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

  PaginaSEI::getInstance()->prepararSelecao('imagem_formato_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'imagem_formato_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjImagemFormatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objImagemFormatoDTO = new ImagemFormatoDTO();
          $objImagemFormatoDTO->setNumIdImagemFormato($arrStrIds[$i]);
          $arrObjImagemFormatoDTO[] = $objImagemFormatoDTO;
        }
        $objImagemFormatoRN = new ImagemFormatoRN();
        $objImagemFormatoRN->excluir($arrObjImagemFormatoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'imagem_formato_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjImagemFormatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objImagemFormatoDTO = new ImagemFormatoDTO();
          $objImagemFormatoDTO->setNumIdImagemFormato($arrStrIds[$i]);
          $arrObjImagemFormatoDTO[] = $objImagemFormatoDTO;
        }
        $objImagemFormatoRN = new ImagemFormatoRN();
        $objImagemFormatoRN->desativar($arrObjImagemFormatoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'imagem_formato_reativar':
      $strTitulo = 'Reativar Formatos de Imagem Permitidos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjImagemFormatoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objImagemFormatoDTO = new ImagemFormatoDTO();
            $objImagemFormatoDTO->setNumIdImagemFormato($arrStrIds[$i]);
            $arrObjImagemFormatoDTO[] = $objImagemFormatoDTO;
          }
          $objImagemFormatoRN = new ImagemFormatoRN();
          $objImagemFormatoRN->reativar($arrObjImagemFormatoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'imagem_formato_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Formato de Imagem Permitido','Selecionar Formatos de Imagem Permitidos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='imagem_formato_cadastrar'){
        if (isset($_GET['id_imagem_formato'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_imagem_formato']);
        }
      }
      break;

    case 'imagem_formato_listar':
      $strTitulo = 'Formatos de Imagem Permitidos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'imagem_formato_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'imagem_formato_listar' || $_GET['acao'] == 'imagem_formato_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=imagem_formato_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objImagemFormatoDTO = new ImagemFormatoDTO();
  $objImagemFormatoDTO->retNumIdImagemFormato();
  $objImagemFormatoDTO->retStrFormato();
  $objImagemFormatoDTO->retStrDescricao();

  if ($_GET['acao'] == 'imagem_formato_reativar'){
    //Lista somente inativos
    $objImagemFormatoDTO->setBolExclusaoLogica(false);
    $objImagemFormatoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objImagemFormatoDTO, 'Formato', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objImagemFormatoDTO);

  $objImagemFormatoRN = new ImagemFormatoRN();
  $arrObjImagemFormatoDTO = $objImagemFormatoRN->listar($objImagemFormatoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objImagemFormatoDTO);
  $numRegistros = count($arrObjImagemFormatoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='imagem_formato_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='imagem_formato_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('imagem_formato_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=imagem_formato_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=imagem_formato_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=imagem_formato_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='imagem_formato_reativar'){
      $strSumarioTabela = 'Tabela de Formatos de Imagem Permitidos.';
      $strCaptionTabela = 'Formatos de Imagem Permitidos';
    }else{
      $strSumarioTabela = 'Tabela de Formatos de Imagem Permitidos Inativos.';
      $strCaptionTabela = 'Formatos de Imagem Permitidos Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objImagemFormatoDTO,'Formato','Formato',$arrObjImagemFormatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objImagemFormatoDTO,'Descrição','Descricao',$arrObjImagemFormatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjImagemFormatoDTO[$i]->getNumIdImagemFormato(),$arrObjImagemFormatoDTO[$i]->getStrFormato()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjImagemFormatoDTO[$i]->getStrFormato()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjImagemFormatoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjImagemFormatoDTO[$i]->getNumIdImagemFormato());

      /*
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=imagem_formato_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_imagem_formato='.$arrObjImagemFormatoDTO[$i]->getNumIdImagemFormato())).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Formato de Imagem Permitido" alt="Consultar Formato de Imagem Permitido" class="infraImg" /></a>&nbsp;';
      }
      */

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=imagem_formato_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_imagem_formato='.$arrObjImagemFormatoDTO[$i]->getNumIdImagemFormato()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Formato de Imagem Permitido" alt="Alterar Formato de Imagem Permitido" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjImagemFormatoDTO[$i]->getNumIdImagemFormato();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjImagemFormatoDTO[$i]->getStrFormato());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Formato de Imagem Permitido" alt="Desativar Formato de Imagem Permitido" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Formato de Imagem Permitido" alt="Reativar Formato de Imagem Permitido" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Formato de Imagem Permitido" alt="Excluir Formato de Imagem Permitido" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'imagem_formato_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='imagem_formato_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Formato de Imagem Permitido \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmImagemFormatoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmImagemFormatoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Formato de Imagem Permitido selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Formatos de Imagem Permitidos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmImagemFormatoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmImagemFormatoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Formato de Imagem Permitido \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmImagemFormatoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmImagemFormatoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Formato de Imagem Permitido selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Formatos de Imagem Permitidos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmImagemFormatoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmImagemFormatoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Formato de Imagem Permitido \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmImagemFormatoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmImagemFormatoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Formato de Imagem Permitido selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Formatos de Imagem Permitidos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmImagemFormatoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmImagemFormatoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmImagemFormatoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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