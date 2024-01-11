<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/12/2007 - criado por fbv
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
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('cargo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'cargo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCargoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCargoDTO = new CargoDTO();
          $objCargoDTO->setNumIdCargo($arrStrIds[$i]);
          $arrObjCargoDTO[] = $objCargoDTO;
        }
        $objCargoRN = new CargoRN();
        $objCargoRN->excluirRN0303($arrObjCargoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'cargo_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCargoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCargoDTO = new CargoDTO();
          $objCargoDTO->setNumIdCargo($arrStrIds[$i]);
          $arrObjCargoDTO[] = $objCargoDTO;
        }
        $objCargoRN = new CargoRN();
        $objCargoRN->desativarRN0341($arrObjCargoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'cargo_reativar':
      $strTitulo = 'Reativar Cargo';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjCargoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objCargoDTO = new CargoDTO();
            $objCargoDTO->setNumIdCargo($arrStrIds[$i]);
            $arrObjCargoDTO[] = $objCargoDTO;
          }
          $objCargoRN = new CargoRN();
          $objCargoRN->reativarRN0342($arrObjCargoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'cargo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Cargo','Selecionar Cargos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='cargo_cadastrar'){
        if (isset($_GET['id_cargo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_cargo']);
        }
      }
      break;

    case 'cargo_listar':
      $strTitulo = 'Cargos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'cargo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'cargo_listar' || $_GET['acao'] == 'cargo_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('cargo_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cargo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }
  
  $objCargoDTO = new CargoDTO();
  $objCargoDTO->retNumIdCargo();
  $objCargoDTO->retStrExpressao();
  $objCargoDTO->retStrExpressaoTratamento();
  $objCargoDTO->retStrExpressaoVocativo();
  $objCargoDTO->retStrExpressaoTitulo();
  $objCargoDTO->retStrAbreviaturaTitulo();
  $objCargoDTO->retStrStaGenero();

  if ($_GET['acao'] == 'cargo_reativar'){
    //Lista somente inativos
    $objCargoDTO->setBolExclusaoLogica(false);
    $objCargoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objCargoDTO, 'Expressao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objCargoDTO);

  $objCargoRN = new CargoRN();
  $arrObjCargoDTO = $objCargoRN->listarRN0302($objCargoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objCargoDTO);
  $numRegistros = count($arrObjCargoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='cargo_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('cargo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('cargo_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='cargo_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('cargo_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('cargo_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('cargo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('cargo_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('cargo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('cargo_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cargo_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cargo_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cargo_excluir&acao_origem='.$_GET['acao']);
    }
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='cargo_reativar'){
      $strSumarioTabela = 'Tabela de Cargos.';
      $strCaptionTabela = 'Cargos';
    }else{
      $strSumarioTabela = 'Tabela de Cargos Inativos.';
      $strCaptionTabela = 'Cargos Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" >'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objCargoDTO,'Expressão','Expressao',$arrObjCargoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="18%">'.PaginaSEI::getInstance()->getThOrdenacao($objCargoDTO,'Tratamento','ExpressaoTratamento',$arrObjCargoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="18%">'.PaginaSEI::getInstance()->getThOrdenacao($objCargoDTO,'Vocativo','ExpressaoVocativo',$arrObjCargoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="18%">'.PaginaSEI::getInstance()->getThOrdenacao($objCargoDTO,'Título','ExpressaoTitulo',$arrObjCargoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">'.PaginaSEI::getInstance()->getThOrdenacao($objCargoDTO,'Gênero','StaGenero',$arrObjCargoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjCargoDTO[$i]->getNumIdCargo(),$arrObjCargoDTO[$i]->getStrExpressao()).'</td>';
      }
      
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCargoDTO[$i]->getStrExpressao()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCargoDTO[$i]->getStrExpressaoTratamento()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjCargoDTO[$i]->getStrExpressaoVocativo()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML(TituloINT::formatarExpressaoAbreviatura($arrObjCargoDTO[$i]->getStrExpressaoTitulo(),$arrObjCargoDTO[$i]->getStrAbreviaturaTitulo())).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjCargoDTO[$i]->getStrStaGenero()).'</td>';

      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjCargoDTO[$i]->getNumIdCargo());
      
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cargo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cargo='.$arrObjCargoDTO[$i]->getNumIdCargo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Cargo" alt="Consultar Cargo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cargo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cargo='.$arrObjCargoDTO[$i]->getNumIdCargo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Cargo" alt="Alterar Cargo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjCargoDTO[$i]->getNumIdCargo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjCargoDTO[$i]->getStrExpressao());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjCargoDTO[$i]->getNumIdCargo().'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Cargo" alt="Desativar Cargo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$arrObjCargoDTO[$i]->getNumIdCargo().'"  onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Cargo" alt="Reativar Cargo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjCargoDTO[$i]->getNumIdCargo().'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Cargo" alt="Excluir Cargo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'cargo_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='cargo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Cargo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCargoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCargoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Cargo selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Cargos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCargoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCargoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Cargo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCargoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCargoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Cargo selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Cargos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCargoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCargoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Cargo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCargoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCargoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Cargo selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Cargos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCargoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCargoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCargoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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