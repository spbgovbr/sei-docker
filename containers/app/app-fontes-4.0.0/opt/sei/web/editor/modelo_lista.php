<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: modelo_lista.php 10035 2015-06-09 15:10:40Z mga $
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('modelo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'modelo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjModeloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objModeloDTO = new ModeloDTO();
          $objModeloDTO->setNumIdModelo($arrStrIds[$i]);
          $arrObjModeloDTO[] = $objModeloDTO;
        }
        $objModeloRN = new ModeloRN();
        $objModeloRN->excluir($arrObjModeloDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'modelo_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjModeloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objModeloDTO = new ModeloDTO();
          $objModeloDTO->setNumIdModelo($arrStrIds[$i]);
          $arrObjModeloDTO[] = $objModeloDTO;
        }
        $objModeloRN = new ModeloRN();
        $objModeloRN->desativar($arrObjModeloDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'modelo_reativar':
      $strTitulo = 'Reativar Modelos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjModeloDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objModeloDTO = new ModeloDTO();
            $objModeloDTO->setNumIdModelo($arrStrIds[$i]);
            $arrObjModeloDTO[] = $objModeloDTO;
          }
          $objModeloRN = new ModeloRN();
          $objModeloRN->reativar($arrObjModeloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'modelo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Modelo','Selecionar Modelos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='modelo_cadastrar'){
        if (isset($_GET['id_modelo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_modelo']);
        }
      }
      break;

    case 'modelo_listar':
      $strTitulo = 'Modelos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'modelo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'modelo_listar' || $_GET['acao'] == 'modelo_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('modelo_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objModeloDTO = new ModeloDTO();
  $objModeloDTO->retNumIdModelo();
  $objModeloDTO->retStrNome();

  if ($_GET['acao'] == 'modelo_reativar'){
    //Lista somente inativos
    $objModeloDTO->setBolExclusaoLogica(false);
    $objModeloDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objModeloDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objModeloDTO);

  $objModeloRN = new ModeloRN();
  $arrObjModeloDTO = $objModeloRN->listar($objModeloDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objModeloDTO);
  $numRegistros = count($arrObjModeloDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='modelo_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;// SessaoSEI::getInstance()->verificarPermissao('modelo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('modelo_alterar');
      $bolAcaoEditorSimular = SessaoSEI::getInstance()->verificarPermissao('editor_simular');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoClonar = false;
      $bolAcaoSecaoModeloListar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='modelo_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('modelo_reativar');
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('modelo_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('modelo_excluir');
      $bolAcaoDesativar = false;
      $bolAcaoClonar = false;
      $bolAcaoSecaoModeloListar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('modelo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('modelo_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoEditorSimular = SessaoSEI::getInstance()->verificarPermissao('editor_simular');
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('modelo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('modelo_desativar');
      //-- inclui novo item de ações
      $bolAcaoSecaoModeloListar = SessaoSEI::getInstance()->verificarPermissao('secao_modelo_listar');
      $bolAcaoClonar = SessaoSEI::getInstance()->verificarPermissao('modelo_clonar');
    }
    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    //if ($bolAcaoClonar){
    //  $arrComandos[] = '<button type="button" accesskey="l" id="btnClonar" value="Clonar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_clonar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])).'\'" class="infraButton">C<span class="infraTeclaAtalho">l</span>onar</button>';
    //}

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='modelo_reativar'){
      $strSumarioTabela = 'Tabela de Modelos.';
      $strCaptionTabela = 'Modelos';
    }else{
      $strSumarioTabela = 'Tabela de Modelos Inativos.';
      $strCaptionTabela = 'Modelos Inativos';
    }

    $strResultado .= '<table width="80%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objModeloDTO,'Nome','Nome',$arrObjModeloDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjModeloDTO[$i]->getNumIdModelo(),$arrObjModeloDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjModeloDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjModeloDTO[$i]->getNumIdModelo());

      if($bolAcaoEditorSimular){
        $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);infraAbrirJanela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_simular&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_modelo='.$arrObjModeloDTO[$i]->getNumIdModelo()).'\',\'Teste de modelo\',800,600,null,false);" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PRE_VISUALIZAR.'" title="Visualizar Modelo" alt="Visualizar Modelo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_modelo='.$arrObjModeloDTO[$i]->getNumIdModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Modelo" alt="Consultar Modelo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_modelo='.$arrObjModeloDTO[$i]->getNumIdModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Modelo" alt="Alterar Modelo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoSecaoModeloListar){

        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=secao_modelo_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_modelo='.$arrObjModeloDTO[$i]->getNumIdModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getDiretorioSvgLocal().'/valores.svg" title="Seções do Modelo" alt="Seções do Modelo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoClonar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=modelo_clonar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_modelo_origem='.$arrObjModeloDTO[$i]->getNumIdModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeClonar().'" title="Clonar Modelo" alt="Clonar Modelo" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjModeloDTO[$i]->getNumIdModelo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjModeloDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Modelo" alt="Desativar Modelo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Modelo" alt="Reativar Modelo" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Modelo" alt="Excluir Modelo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'modelo_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='modelo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Modelo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmModeloLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Modelo selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Modelos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Modelo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmModeloLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Modelo selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Modelos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Modelo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmModeloLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Modelo selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Modelos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmModeloLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmModeloLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>