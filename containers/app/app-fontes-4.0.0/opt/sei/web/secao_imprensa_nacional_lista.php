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

  PaginaSEI::getInstance()->prepararSelecao('secao_imprensa_nacional_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  
  if (isset($_GET['id_veiculo_imprensa_nacional'])){
    $strParametros .= '&id_veiculo_imprensa_nacional='.$_GET['id_veiculo_imprensa_nacional'];
  }  
  
  switch($_GET['acao']){
    case 'secao_imprensa_nacional_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjSecaoImprensaNacionalDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
          $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($arrStrIds[$i]);
          $arrObjSecaoImprensaNacionalDTO[] = $objSecaoImprensaNacionalDTO;
        }
        $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
        $objSecaoImprensaNacionalRN->excluir($arrObjSecaoImprensaNacionalDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

/* 
    case 'secao_imprensa_nacional_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjSecaoImprensaNacionalDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
          $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($arrStrIds[$i]);
          $arrObjSecaoImprensaNacionalDTO[] = $objSecaoImprensaNacionalDTO;
        }
        $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
        $objSecaoImprensaNacionalRN->desativar($arrObjSecaoImprensaNacionalDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'secao_imprensa_nacional_reativar':
      $strTitulo = 'Reativar Seções do Veículo da Imprensa Nacional';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjSecaoImprensaNacionalDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
            $objSecaoImprensaNacionalDTO->setNumIdSecaoImprensaNacional($arrStrIds[$i]);
            $arrObjSecaoImprensaNacionalDTO[] = $objSecaoImprensaNacionalDTO;
          }
          $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
          $objSecaoImprensaNacionalRN->reativar($arrObjSecaoImprensaNacionalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
        die;
      } 
      break;

 */
    case 'secao_imprensa_nacional_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Seção do Veículo da Imprensa Nacional','Selecionar Seções do Veículo da Imprensa Nacional');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='secao_imprensa_nacional_cadastrar'){
        if (isset($_GET['id_secao_imprensa_nacional'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_secao_imprensa_nacional']);
        }
      }
      break;

    case 'secao_imprensa_nacional_listar':
      $strTitulo = 'Seções do Veículo da Imprensa Nacional';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'secao_imprensa_nacional_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'secao_imprensa_nacional_listar' || $_GET['acao'] == 'secao_imprensa_nacional_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objSecaoImprensaNacionalDTO = new SecaoImprensaNacionalDTO();
  $objSecaoImprensaNacionalDTO->retNumIdSecaoImprensaNacional();
  $objSecaoImprensaNacionalDTO->retStrNome();
  $objSecaoImprensaNacionalDTO->retStrDescricao();
  //$objSecaoImprensaNacionalDTO->retStrSiglaVeiculoImprensaNacional();
  
  $objSecaoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);

/* 
  if ($_GET['acao'] == 'secao_imprensa_nacional_reativar'){
    //Lista somente inativos
    $objSecaoImprensaNacionalDTO->setBolExclusaoLogica(false);
    $objSecaoImprensaNacionalDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objSecaoImprensaNacionalDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objSecaoImprensaNacionalDTO);

  $objSecaoImprensaNacionalRN = new SecaoImprensaNacionalRN();
  $arrObjSecaoImprensaNacionalDTO = $objSecaoImprensaNacionalRN->listar($objSecaoImprensaNacionalDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objSecaoImprensaNacionalDTO);
  $numRegistros = count($arrObjSecaoImprensaNacionalDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='secao_imprensa_nacional_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='secao_imprensa_nacional_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('secao_imprensa_nacional_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_desativar&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim'.$strParametros);
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='secao_imprensa_nacional_reativar'){ */
      $strSumarioTabela = 'Tabela de Seções do Veículo da Imprensa Nacional.';
      $strCaptionTabela = 'Seções do Veículo da Imprensa Nacional';
    /* }else{
      $strSumarioTabela = 'Tabela de Seções do Veículo da Imprensa Nacional Inativas.';
      $strCaptionTabela = 'Seções do Veículo da Imprensa Nacional Inativas';
    } */

    $strResultado .= '<table width="90%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objSecaoImprensaNacionalDTO,'Seção','Nome',$arrObjSecaoImprensaNacionalDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSecaoImprensaNacionalDTO,'Descrição','Descricao',$arrObjSecaoImprensaNacionalDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSecaoImprensaNacionalDTO,'Veículo da Imprensa Nacional','SiglaVeiculoImprensaNacional',$arrObjSecaoImprensaNacionalDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional(),$arrObjSecaoImprensaNacionalDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjSecaoImprensaNacionalDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSecaoImprensaNacionalDTO[$i]->getStrDescricao()).'</td>';
      //$strResultado .= '<td align="center">'.$arrObjSecaoImprensaNacionalDTO[$i]->getStrSiglaVeiculoImprensaNacional().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_secao_imprensa_nacional='.$arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Seção do Veículo da Imprensa Nacional" alt="Consultar Seção do Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_imprensa_nacional_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_secao_imprensa_nacional='.$arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Seção do Veículo da Imprensa Nacional" alt="Alterar Seção do Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjSecaoImprensaNacionalDTO[$i]->getNumIdSecaoImprensaNacional();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjSecaoImprensaNacionalDTO[$i]->getStrNome());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Seção do Veículo da Imprensa Nacional" alt="Desativar Seção do Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Seção do Veículo da Imprensa Nacional" alt="Reativar Seção do Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Seção do Veículo da Imprensa Nacional" alt="Excluir Seção do Veículo da Imprensa Nacional" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'secao_imprensa_nacional_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_veiculo_imprensa_nacional'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
  
  $objVeiculoImprensaNacionalDTO = new VeiculoImprensaNacionalDTO();
  $objVeiculoImprensaNacionalDTO->retStrSigla();
  $objVeiculoImprensaNacionalDTO->setNumIdVeiculoImprensaNacional($_GET['id_veiculo_imprensa_nacional']);
  
  $objVeiculoImprensaNacionalRN = new VeiculoImprensaNacionalRN();
  $objVeiculoImprensaNacionalDTO = $objVeiculoImprensaNacionalRN->consultar($objVeiculoImprensaNacionalDTO);
  
  if ($objVeiculoImprensaNacionalDTO==null){
    throw new InfraException('Veículo não encontrado.');
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
#lblVeiculoImprensaNacional {position:absolute;left:0%;top:0%;width:25%;}
#txtVeiculoImprensaNacional {position:absolute;left:0%;top:40%;width:25%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='secao_imprensa_nacional_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Seção do Veículo da Imprensa Nacional \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSecaoImprensaNacionalLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmSecaoImprensaNacionalLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Seção do Veículo da Imprensa Nacional selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Seções do Veículo da Imprensa Nacional selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSecaoImprensaNacionalLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmSecaoImprensaNacionalLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Seção do Veículo da Imprensa Nacional \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSecaoImprensaNacionalLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmSecaoImprensaNacionalLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Seção do Veículo da Imprensa Nacional selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Seções do Veículo da Imprensa Nacional selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSecaoImprensaNacionalLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmSecaoImprensaNacionalLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Seção do Veículo da Imprensa Nacional \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmSecaoImprensaNacionalLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmSecaoImprensaNacionalLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Seção do Veículo da Imprensa Nacional selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Seções do Veículo da Imprensa Nacional selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmSecaoImprensaNacionalLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmSecaoImprensaNacionalLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSecaoImprensaNacionalLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblVeiculoImprensaNacional" for="txtVeiculoImprensaNacional" accesskey="" class="infraLabelOpcional">Veículo da Imprensa Nacional:</label>
  <input type="text" id="txtVeiculoImprensaNacional" name="txtVeiculoImprensaNacional" value="<?=$objVeiculoImprensaNacionalDTO->getStrSigla()?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
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