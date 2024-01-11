<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
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

  PaginaSEI::getInstance()->prepararSelecao('assinante_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('txtCargoFuncao','selOrgao'));

  switch($_GET['acao']){
    case 'assinante_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAssinanteDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAssinanteDTO = new AssinanteDTO();
          $objAssinanteDTO->setNumIdAssinante($arrStrIds[$i]);
          $arrObjAssinanteDTO[] = $objAssinanteDTO;
        }
        $objAssinanteRN = new AssinanteRN();
        $objAssinanteRN->excluirRN1337($arrObjAssinanteDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'assinante_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAssinanteDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAssinanteDTO = new AssinanteDTO();
          $objAssinanteDTO->setNumIdAssinante($arrStrIds[$i]);
          $arrObjAssinanteDTO[] = $objAssinanteDTO;
        }
        $objAssinanteRN = new AssinanteRN();
        $objAssinanteRN->desativarRN1341($arrObjAssinanteDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'assinante_reativar':
      $strTitulo = 'Reativar Assinaturas';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjAssinanteDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objAssinanteDTO = new AssinanteDTO();
            $objAssinanteDTO->setNumIdAssinante($arrStrIds[$i]);
            $arrObjAssinanteDTO[] = $objAssinanteDTO;
          }
          $objAssinanteRN = new AssinanteRN();
          $objAssinanteRN->reativarRN1342($arrObjAssinanteDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'assinante_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Assinatura','Selecionar Assinaturas');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='assinante_cadastrar'){
        if (isset($_GET['id_assinante'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_assinante']);
        }
      }
      break;

    case 'assinante_listar':
      $strTitulo = 'Assinaturas das Unidades';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';
  
  if ($_GET['acao'] == 'assinante_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'assinante_listar' || $_GET['acao'] == 'assinante_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('assinante_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="A" id="btnAdicionar" value="Adicionar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinante_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">A</span>dicionar</button>';
    }
  /* } */

  $objAssinanteDTO = new AssinanteDTO();
  $objAssinanteDTO->retNumIdAssinante();
  $objAssinanteDTO->retStrCargoFuncao();
  $objAssinanteDTO->retStrSiglaOrgao();
  $objAssinanteDTO->retStrDescricaoOrgao();
  
  $strCargoFuncao = PaginaSEI::getInstance()->recuperarCampo('txtCargoFuncao');
  if ($strCargoFuncao!==''){
    $objAssinanteDTO->setStrCargoFuncao($strCargoFuncao);
  }

  $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
  if ($numIdOrgao!=''){
    $objAssinanteDTO->setNumIdOrgao($numIdOrgao);
  }

  $numIdUnidade = $_POST['hdnIdUnidade'];
  if ($numIdUnidade!=''){
    $objAssinanteDTO->setNumIdUnidade($numIdUnidade);
  }
  $strDescricaoUnidade = $_POST['txtUnidade'];

/* 
  if ($_GET['acao'] == 'assinante_reativar'){
    //Lista somente inativos
    $objAssinanteDTO->setBolExclusaoLogica(false);
    $objAssinanteDTO->setStrSinAtivo('N');
  }
 */
  
  PaginaSEI::getInstance()->prepararOrdenacao($objAssinanteDTO, 'CargoFuncao', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objAssinanteDTO);

  $objAssinanteRN = new AssinanteRN();
  $arrObjAssinanteDTO = $objAssinanteRN->pesquisar($objAssinanteDTO);

  PaginaSEI::getInstance()->processarPaginacao($objAssinanteDTO);
  $numRegistros = count($arrObjAssinanteDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='assinante_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('assinante_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('assinante_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='assinante_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('assinante_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('assinante_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('assinante_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('assinante_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('assinante_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('assinante_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('assinante_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinante_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinante_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinante_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='assinante_reativar'){ */
      $strSumarioTabela = 'Tabela de Assinaturas.';
      $strCaptionTabela = 'Assinaturas';
    /* }else{
      $strSumarioTabela = 'Tabela de Assinaturas Inativas.';
      $strCaptionTabela = 'Assinaturas Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //70
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objAssinanteDTO,'Órgão','SiglaOrgao',$arrObjAssinanteDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" >'.PaginaSEI::getInstance()->getThOrdenacao($objAssinanteDTO,'Cargo / Função','CargoFuncao',$arrObjAssinanteDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Unidades</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%" >Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';


    $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
    $objRelAssinanteUnidadeDTO->retNumIdAssinante();
    $objRelAssinanteUnidadeDTO->setNumIdAssinante(InfraArray::converterArrInfraDTO($arrObjAssinanteDTO,'IdAssinante'),InfraDTO::$OPER_IN);

    $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
    $arrObjRelAssinanteUnidadeDTO = InfraArray::indexarArrInfraDTO($objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO),'IdAssinante',true);

    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjAssinanteDTO[$i]->getNumIdAssinante(),$arrObjAssinanteDTO[$i]->getStrCargoFuncao()).'</td>';
      }
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjAssinanteDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjAssinanteDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAssinanteDTO[$i]->getStrSiglaOrgao()).'</a></td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAssinanteDTO[$i]->getStrCargoFuncao()).'</td>';
      $strResultado .= '<td align="center">'.InfraArray::contar($arrObjRelAssinanteUnidadeDTO[$arrObjAssinanteDTO[$i]->getNumIdAssinante()]).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjAssinanteDTO[$i]->getNumIdAssinante());

      /*
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinante_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_assinante='.$arrObjAssinanteDTO[$i]->getNumIdAssinante())).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Assinatura" alt="Consultar Assinatura" class="infraImg" /></a>&nbsp;';
      }
      */
      
      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinante_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_assinante='.$arrObjAssinanteDTO[$i]->getNumIdAssinante()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Assinatura" alt="Alterar Assinatura" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjAssinanteDTO[$i]->getNumIdAssinante();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjAssinanteDTO[$i]->getStrCargoFuncao());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Assinatura" alt="Desativar Assinatura" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Assinatura" alt="Reativar Assinatura" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Assinatura" alt="Excluir Assinatura" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'assinante_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('','Todos',$numIdOrgao);
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');

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
#lblCargoFuncao {position:absolute;left:0%;top:0%;width:70%;}
#txtCargoFuncao {position:absolute;left:0%;top:38%;width:70%;}

#lblOrgao {position:absolute;left:0%;top:0%;width:40%;}
#selOrgao {position:absolute;left:0%;top:38%;width:40%;}

#lblUnidade {position:absolute;left:0%;top:0%;width:70%;}
#txtUnidade {position:absolute;left:0%;top:38%;width:70%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

var objAutoCompletarUnidade = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='assinante_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('txtCargoFuncao').focus();
  }

  //Unidades
  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  //não mostra verificação no resultado
  //objAutoCompletarUnidade.maiusculas = true;
  //objAutoCompletarUnidade.mostrarAviso = true;
  //objAutoCompletarUnidade.tempoAviso = 1000;
  //objAutoCompletarUnidade.tamanhoMinimo = 3;
  objAutoCompletarUnidade.limparCampo = true;
  objAutoCompletarUnidade.prepararExecucao = function(){
  return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao=<?=$numIdOrgao?>';
  };
  objAutoCompletarUnidade.selecionar('<?=$numIdUnidade;?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoUnidade,false)?>');

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação de Assinatura \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAssinanteLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAssinanteLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Assinatura selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Assinaturas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAssinanteLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAssinanteLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Assinatura \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAssinanteLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAssinanteLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Assinatura selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Assinaturas selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAssinanteLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAssinanteLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("ATENÇÃO: Confirma exclusão da Assinatura \""+desc+"\" inclusive em todas as unidades associadas?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAssinanteLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAssinanteLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Assinatura selecionada.');
    return;
  }
  if (confirm("ATENÇÃO: Confirma exclusão das Assinaturas selecionadas inclusive em todas as unidades associadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAssinanteLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAssinanteLista').submit();
  }
}
<? } ?>

function trocarOrgao(){
  document.getElementById('txtUnidade').value='';
  document.getElementById('hdnIdUnidade').value='';
  document.getElementById('frmAssinanteLista').submit();
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAssinanteLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>

  <div id="divCargoFuncao" class="infraAreaDados" style="height:5em;">
    <label id="lblCargoFuncao" for="txtCargoFuncao" accesskey="" class="infraLabelOpcional">Cargo / Função:</label>
    <input type="text" id="txtCargoFuncao" name="txtCargoFuncao" class="infraText" value="<?=PaginaSEI::tratarHTML($strCargoFuncao)?>" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divOrgao" class="infraAreaDados" style="height:5em;">
    <label id="lblOrgao" for="selOrgao" accesskey="r" class="infraLabelOpcional">Ó<span class="infraTeclaAtalho">r</span>gão:</label>
    <select id="selOrgao" name="selOrgao" onchange="trocarOrgao()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>
  </div>

  <div id="divUnidade" class="infraAreaDados" style="height:5em;">
    <label id="lblUnidade" for="txtUnidade" class="infraLabelOpcional">Unidade:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($strDescricaoUnidade)?>" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="<?=$numIdUnidade?>" />
  </div>
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>