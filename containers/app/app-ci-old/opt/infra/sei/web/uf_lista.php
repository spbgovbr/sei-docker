<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2007 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
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

  PaginaSEI::getInstance()->prepararSelecao('uf_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selPais'));

  switch($_GET['acao']){
    case 'uf_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjUfDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objUfDTO = new UfDTO();
          $objUfDTO->setNumIdUf($arrStrIds[$i]);
          $arrObjUfDTO[] = $objUfDTO;
        }
        $objUfRN = new UfRN();
        $objUfRN->excluirRN0402($arrObjUfDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'uf_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Estado','Selecionar Estados');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='uf_cadastrar'){
        if (isset($_GET['id_uf'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_uf']);
        }
      }
      break;

    case 'uf_listar':
      $strTitulo = 'Estados';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'uf_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] != 'uf_reativar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('uf_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objUfDTO = new UfDTO();
  $objUfDTO->retNumIdUf();
  $objUfDTO->retStrSigla();
  $objUfDTO->retStrNome();
  $objUfDTO->retStrPais();
  $objUfDTO->retNumCodigoIbge();
/* 
  if ($_GET['acao'] == 'uf_reativar'){
    //Lista somente inativos
    $objUfDTO->setBolExclusaoLogica(false);
    $objUfDTO->setStrSinAtivo('N');
  }
 */


  $objUfDTO->setNumIdPais(PaginaSEI::getInstance()->recuperarCampo('selPais'));

  PaginaSEI::getInstance()->prepararOrdenacao($objUfDTO, 'IdUf', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objUfDTO);

  $objUfRN = new UfRN();
  $arrObjUfDTO = $objUfRN->listarRN0401($objUfDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objUfDTO);
  $numRegistros = count($arrObjUfDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='uf_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('uf_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('uf_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='uf_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('uf_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('uf_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('uf_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('uf_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('uf_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('uf_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<input type="button" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton" />';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='uf_reativar'){ */
      $strSumarioTabela = 'Tabela de Estados.';
      $strCaptionTabela = 'Estados';
    /* }else{
      $strSumarioTabela = 'Tabela de Estados Inativos.';
      $strCaptionTabela = 'Estados Inativos';
    } */

    $strResultado .= '<table width="90%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="8%">'.PaginaSEI::getInstance()->getThOrdenacao($objUfDTO,'IBGE','CodigoIbge',$arrObjUfDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">'.PaginaSEI::getInstance()->getThOrdenacao($objUfDTO,'Sigla','Sigla',$arrObjUfDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUfDTO,'Nome','Nome',$arrObjUfDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objUfDTO,'Pais','Pais',$arrObjUfDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjUfDTO[$i]->getNumIdUf(),$arrObjUfDTO[$i]->getStrSigla()).'</td>';
      }
      $strResultado .= '<td width="12%" align="center">'.PaginaSEI::tratarHTML($arrObjUfDTO[$i]->getNumCodigoIbge()).'</td>';
      $strResultado .= '<td width="10%" align="center">'.PaginaSEI::tratarHTML($arrObjUfDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td width="38%">'.PaginaSEI::tratarHTML($arrObjUfDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td width="20%">'.PaginaSEI::tratarHTML($arrObjUfDTO[$i]->getStrPais()).'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjUfDTO[$i]->getNumIdUf());
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_uf='.$arrObjUfDTO[$i]->getNumIdUf()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Estado" alt="Consultar Estado" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=uf_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_uf='.$arrObjUfDTO[$i]->getNumIdUf()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Estado" alt="Alterar Estado" class="infraImg" /></a>&nbsp;';
      }

/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjUfDTO[$i]->getNumIdUf().'"  onclick="acaoDesativar(\''.$arrObjUfDTO[$i]->getNumIdUf().'\',\''.$arrObjUfDTO[$i]->getStrSigla().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Estado" alt="Desativar Estado" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$arrObjUfDTO[$i]->getNumIdUf().'"  onclick="acaoReativar(\''.$arrObjUfDTO[$i]->getNumIdUf().'\',\''.$arrObjUfDTO[$i]->getStrSigla().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Estado" alt="Reativar Estado" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjUfDTO[$i]->getNumIdUf().'"  onclick="acaoExcluir(\''.$arrObjUfDTO[$i]->getNumIdUf().'\',\''.$arrObjUfDTO[$i]->getStrSigla().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Estado" alt="Excluir Estado" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'uf_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelPais = PaisINT::montarSelectNome('null','&nbsp;',$objUfDTO->getNumIdPais());

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
#lblPais {position:absolute;left:0%;top:0%;width:40%;}
#selPais {position:absolute;left:0%;top:40%;width:40%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='uf_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
   //document.getElementById('btnFechar').focus(); 
   setTimeout("document.getElementById('btnFechar').focus()", 50);
 }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Estado \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUfLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUfLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Estado selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Estados selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUfLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUfLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Estado \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUfLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUfLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Estado selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Estados selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUfLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUfLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Estado \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUfLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUfLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Estado selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Estados selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUfLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUfLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUfLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblPais" for="selPais" accesskey="P" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">P</span>aís:</label>
  <select id="selPais" name="selPais" class="infraSelect" onchange="this.form.submit();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelPais?>
  </select>
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