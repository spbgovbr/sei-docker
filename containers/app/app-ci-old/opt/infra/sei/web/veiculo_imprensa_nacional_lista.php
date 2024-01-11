<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2013 - criado por mga
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

  PaginaSEI::getInstance()->prepararSelecao('veiculo_imprensa_nacional_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'veiculo_imprensa_nacional_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVeiculoImprensaNacionalDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
          $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($arrStrIds[$i]);
          $arrObjVeiculoImprensaNacionalDTO[] = $objVeiculoImprensaNacionalDTO;
        }
        $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
        $objVeiculoImprensaNacionalRN->excluir($arrObjVeiculoImprensaNacionalDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'veiculo_imprensa_nacional_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVeiculoImprensaNacionalDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
          $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($arrStrIds[$i]);
          $arrObjVeiculoImprensaNacionalDTO[] = $objVeiculoImprensaNacionalDTO;
        }
        $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
        $objVeiculoImprensaNacionalRN->desativar($arrObjVeiculoImprensaNacionalDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'veiculo_imprensa_nacional_reativar':
      $strTitulo = 'Reativar Veículos da Imprensa Nacional';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjVeiculoImprensaNacionalDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
            $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($arrStrIds[$i]);
            $arrObjVeiculoImprensaNacionalDTO[] = $objVeiculoImprensaNacionalDTO;
          }
          $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
          $objVeiculoImprensaNacionalRN->reativar($arrObjVeiculoImprensaNacionalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'veiculo_imprensa_nacional_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Veículo da Imprensa Nacional','Selecionar Veículos da Imprensa Nacional');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='veiculo_imprensa_nacional_cadastrar'){
        if (isset($_GET['id_veiculo_imprensa_nacional'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_veiculo_imprensa_nacional']);
        }
      }
      break;

    case 'veiculo_imprensa_nacional_listar':
      $strTitulo = 'Veículos da Imprensa Nacional';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'veiculo_imprensa_nacional_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'veiculo_imprensa_nacional_listar' || $_GET['acao'] == 'veiculo_imprensa_nacional_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_imprensa_nacional_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
  $objVeiculoImprensaNacionalDTO->retNumIdVeiculoImprensaNacional();
  $objVeiculoImprensaNacionalDTO->retStrSigla();
  $objVeiculoImprensaNacionalDTO->retStrDescricao();
/* 
  if ($_GET['acao'] == 'veiculo_imprensa_nacional_reativar'){
    //Lista somente inativos
    $objVeiculoImprensaNacionalDTO->setBolExclusaoLogica(false);
    $objVeiculoImprensaNacionalDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objVeiculoImprensaNacionalDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objVeiculoImprensaNacionalDTO);

  $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
  $arrObjVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->listar($objVeiculoImprensaNacionalDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objVeiculoImprensaNacionalDTO);
  $numRegistros = count($arrObjVeiculoImprensaNacionalDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='veiculo_imprensa_nacional_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoSecaoListar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_listar');
      $bolCheck = true;
/*     }else if ($_GET['acao']=='veiculo_imprensa_nacional_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_excluir');
      $bolAcaoSecaoListar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_listar');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('veiculo_imprensa_nacional_desativar');
      $bolAcaoSecaoListar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_listar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_imprensa_nacional_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_imprensa_nacional_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_imprensa_nacional_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='veiculo_imprensa_nacional_reativar'){ */
      $strSumarioTabela = 'Tabela de Veículos da Imprensa Nacional.';
      $strCaptionTabela = 'Veículos da Imprensa Nacional';
    /* }else{
      $strSumarioTabela = 'Tabela de Veículos da Imprensa Nacional Inativos.';
      $strCaptionTabela = 'Veículos da Imprensa Nacional Inativos';
    } */

    $strResultado .= '<table width="80%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoImprensaNacionalDTO,'Sigla','Sigla',$arrObjVeiculoImprensaNacionalDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoImprensaNacionalDTO,'Descrição','Descricao',$arrObjVeiculoImprensaNacionalDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjVeiculoImprensaNacionalDTO[$i]->getNumIdVeiculoImprensaNacional(),$arrObjVeiculoImprensaNacionalDTO[$i]->getStrSigla()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjVeiculoImprensaNacionalDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjVeiculoImprensaNacionalDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjVeiculoImprensaNacionalDTO[$i]->getNumIdVeiculoImprensaNacional());

      if ($bolAcaoSecaoListar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_veiculo_imprensa_nacional='.$arrObjVeiculoImprensaNacionalDTO[$i]->getNumIdVeiculoImprensaNacional()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::VALORES.'" title="Seções do Veículo" alt="Seções do Veículo" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_imprensa_nacional_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_veiculo_imprensa_nacional='.$arrObjVeiculoImprensaNacionalDTO[$i]->getNumIdVeiculoImprensaNacional()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Veículo da Imprensa Nacional" alt="Consultar Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_imprensa_nacional_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_veiculo_imprensa_nacional='.$arrObjVeiculoImprensaNacionalDTO[$i]->getNumIdVeiculoImprensaNacional()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Veículo da Imprensa Nacional" alt="Alterar Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjVeiculoImprensaNacionalDTO[$i]->getNumIdVeiculoImprensaNacional();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjVeiculoImprensaNacionalDTO[$i]->getStrSigla());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Veículo da Imprensa Nacional" alt="Desativar Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Veículo da Imprensa Nacional" alt="Reativar Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Veículo da Imprensa Nacional" alt="Excluir Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'veiculo_imprensa_nacional_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='veiculo_imprensa_nacional_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Veículo da Imprensa Nacional \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVeiculoImprensaNacionalLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVeiculoImprensaNacionalLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Veículo da Imprensa Nacional selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Veículos da Imprensa Nacional selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVeiculoImprensaNacionalLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVeiculoImprensaNacionalLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Veículo da Imprensa Nacional \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVeiculoImprensaNacionalLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVeiculoImprensaNacionalLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Veículo da Imprensa Nacional selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Veículos da Imprensa Nacional selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVeiculoImprensaNacionalLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVeiculoImprensaNacionalLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Veículo da Imprensa Nacional \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVeiculoImprensaNacionalLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVeiculoImprensaNacionalLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Veículo da Imprensa Nacional selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Veículos da Imprensa Nacional selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVeiculoImprensaNacionalLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVeiculoImprensaNacionalLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVeiculoImprensaNacionalLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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