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
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('cidade_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUf','selPais'));
  
  switch($_GET['acao']){
    case 'cidade_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjCidadeDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCidadeDTO = new CidadeDTO();
          $objCidadeDTO->setNumIdCidade($arrStrIds[$i]);
          $arrObjCidadeDTO[] = $objCidadeDTO;
        }
        $objCidadeRN = new CidadeRN();
        $objCidadeRN->excluir($arrObjCidadeDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'cidade_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Cidade','Selecionar Cidades');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='cidade_cadastrar'){
        if (isset($_GET['id_cidade'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_cidade']);
        }
      }
      break;

    case 'cidade_listar':
      $strTitulo = 'Cidades';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'cidade_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] != 'cidade_reativar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('cidade_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objCidadeDTO = new CidadeDTO(true);
  $objCidadeDTO->retNumIdCidade();
  $objCidadeDTO->retStrNome();
  $objCidadeDTO->retStrSiglaUf();
  $objCidadeDTO->retStrNomeUf();
  $objCidadeDTO->retStrPais();
  $objCidadeDTO->retNumCodigoIbge();
  $objCidadeDTO->retStrSinCapital();
  $numIdPais = PaginaSEI::getInstance()->recuperarCampo('selPais');
  if ($numIdPais!==''){
    $objCidadeDTO->setNumIdPais($numIdPais);
  }
  $numIdUf = PaginaSEI::getInstance()->recuperarCampo('selUf');
  if ($numIdUf!==''){
    $objCidadeDTO->setNumIdUf($numIdUf);
  }

/* 
  if ($_GET['acao'] == 'cidade_reativar'){
    //Lista somente inativos
    $objCidadeDTO->setBolExclusaoLogica(false);
    $objCidadeDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objCidadeDTO, 'IdCidade', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objCidadeDTO);

  $objCidadeRN = new CidadeRN();
  $arrObjCidadeDTO = $objCidadeRN->listarRN0410($objCidadeDTO);

  PaginaSEI::getInstance()->processarPaginacao($objCidadeDTO);
  $numRegistros = count($arrObjCidadeDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='cidade_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('cidade_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('cidade_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='cidade_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('cidade_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('cidade_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('cidade_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('cidade_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('cidade_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('cidade_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="D" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">D</span>esativar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='cidade_reativar'){ */
      $strSumarioTabela = 'Tabela de Cidades.';
      $strCaptionTabela = 'Cidades';
    /* }else{
      $strSumarioTabela = 'Tabela de Cidades Inativas.';
      $strCaptionTabela = 'Cidades Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //80
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="5%">'.PaginaSEI::getInstance()->getThOrdenacao($objCidadeDTO,'IBGE','CodigoIbge',$arrObjCidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCidadeDTO,'Nome','Nome',$arrObjCidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">'.PaginaSEI::getInstance()->getThOrdenacao($objCidadeDTO,'Capital','SinCapital',$arrObjCidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">'.PaginaSEI::getInstance()->getThOrdenacao($objCidadeDTO,'Estado','SiglaUf',$arrObjCidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objCidadeDTO,'Pais','Pais',$arrObjCidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjCidadeDTO[$i]->getNumIdCidade(),$arrObjCidadeDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td width="12%">'.PaginaSEI::tratarHTML($arrObjCidadeDTO[$i]->getNumCodigoIbge()).'</td>';
      $strResultado .= '<td width="40%">'.PaginaSEI::tratarHTML($arrObjCidadeDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td width="4%" align="center">'.$arrObjCidadeDTO[$i]->getStrSinCapital().'</td>';
      $strResultado .= '<td width="12%" align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjCidadeDTO[$i]->getStrNomeUf()).'" title="'.PaginaSEI::tratarHTML($arrObjCidadeDTO[$i]->getStrNomeUf()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjCidadeDTO[$i]->getStrSiglaUf()).'</a></td>';
      $strResultado .= '<td width="20%">'.PaginaSEI::tratarHTML($arrObjCidadeDTO[$i]->getStrPais()).'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjCidadeDTO[$i]->getNumIdCidade());
      
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjCidadeDTO[$i]->getNumIdCidade();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjCidadeDTO[$i]->getStrNome());
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cidade='.$arrObjCidadeDTO[$i]->getNumIdCidade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Cidade" alt="Consultar Cidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cidade_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_cidade='.$arrObjCidadeDTO[$i]->getNumIdCidade()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Cidade" alt="Alterar Cidade" class="infraImg" /></a>&nbsp;';
      }

/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjCidadeDTO[$i]->getNumIdCidade().'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Cidade" alt="Desativar Cidade" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a  href="#ID-'.$arrObjCidadeDTO[$i]->getNumIdCidade().'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Cidade" alt="Reativar Cidade" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjCidadeDTO[$i]->getNumIdCidade().'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Cidade" alt="Excluir Cidade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'cidade_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelPais = PaisINT::montarSelectNome('','Todos',$numIdPais);
  $strItensSelUf = UfINT::montarSelectSiglaNome('','Todos',$numIdUf, $numIdPais);

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

#lblUf {position:absolute;left:45%;top:0%;width:40%;}
#selUf {position:absolute;left:45%;top:40%;width:40%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='cidade_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Cidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCidadeLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCidadeLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Cidade selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Cidades selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCidadeLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCidadeLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Cidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCidadeLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCidadeLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Cidade selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Cidades selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCidadeLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCidadeLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Cidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCidadeLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCidadeLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Cidade selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Cidades selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCidadeLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCidadeLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCidadeLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblPais" for="selPais" accesskey="p" class="infraLabelOpcional"><span class="infraTeclaAtalho">P</span>aís:</label>
  <select id="selPais" name="selPais" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelPais?>
  </select>

  <label id="lblUf" for="selUf" class="infraLabelOpcional">Estado:</label>
  <select id="selUf" name="selUf" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelUf?>
  </select>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>