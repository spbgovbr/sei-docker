<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/01/2008 - criado por marcio_db//
*
* Versão do Gerador de Código: 1.13.1
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

  PaginaSEI::getInstance()->prepararSelecao('assunto_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTabelaAssuntosDTO = new TabelaAssuntosDTO();
  $objTabelaAssuntosDTO->retNumIdTabelaAssuntos();
  $objTabelaAssuntosDTO->retStrNome();
  $objTabelaAssuntosDTO->retStrSinAtual();

  if (!PaginaSEI::getInstance()->isBolPaginaSelecao()) {
    $objTabelaAssuntosDTO->setNumIdTabelaAssuntos($_GET['id_tabela_assuntos']);
  }else{
    $objTabelaAssuntosDTO->setStrSinAtual('S');
  }

  $objTabelaAssuntosRN = new TabelaAssuntosRN();
  $objTabelaAssuntosDTO = $objTabelaAssuntosRN->consultar($objTabelaAssuntosDTO);

  $strParametros = '&id_tabela_assuntos='.$objTabelaAssuntosDTO->getNumIdTabelaAssuntos();

  PaginaSEI::getInstance()->salvarCamposPost(array('txtPalavrasPesquisaAssuntos'));

  if (isset($_POST['hdnFlag'])) {
    PaginaSEI::getInstance()->salvarCampo('chkSinAssuntosDesativados', $_POST['chkSinAssuntosDesativados']);
  }

  switch($_GET['acao']){
    case 'assunto_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAssuntoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAssuntoDTO = new AssuntoDTO();
          $objAssuntoDTO->setNumIdAssunto($arrStrIds[$i]);
          $arrObjAssuntoDTO[] = $objAssuntoDTO;
        }
        $objAssuntoRN = new AssuntoRN();
        $objAssuntoRN->excluirRN0248($arrObjAssuntoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'assunto_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAssuntoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAssuntoDTO = new AssuntoDTO();
          $objAssuntoDTO->setNumIdAssunto($arrStrIds[$i]);
          $arrObjAssuntoDTO[] = $objAssuntoDTO;
        }
        $objAssuntoRN = new AssuntoRN();
        $objAssuntoRN->desativarRN0258($arrObjAssuntoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'assunto_reativar':
      $strTitulo = 'Reativar Assunto';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjAssuntoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objAssuntoDTO = new AssuntoDTO();
            $objAssuntoDTO->setNumIdAssunto($arrStrIds[$i]);
            $arrObjAssuntoDTO[] = $objAssuntoDTO;
          }
          $objAssuntoRN = new AssuntoRN();
          $objAssuntoRN->reativarRN0522($arrObjAssuntoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
        die;
      } 
      break;
      
    case 'assunto_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Assunto','Selecionar Assuntos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='assunto_cadastrar'){
        if (isset($_GET['id_assunto'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_assunto']);
        }
      }
      break;

    case 'assunto_listar':
      $strTitulo = 'Assuntos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<button type="submit" accesskey="P" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  
  if ($_GET['acao'] == 'assunto_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'assunto_listar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('assunto_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objAssuntoDTO = new AssuntoDTO();
  $objAssuntoDTO->retNumIdAssunto();
  $objAssuntoDTO->retStrCodigoEstruturado();
  $objAssuntoDTO->retStrSinAtivo();
  $objAssuntoDTO->retStrDescricao();
  $objAssuntoDTO->retStrSinEstrutural();
  $objAssuntoDTO->retStrSinAtivo();

  $strSinAssuntosDesativados = PaginaSEI::getInstance()->getCheckbox(PaginaSEI::getInstance()->recuperarCampo('chkSinAssuntosDesativados','S'));

  if ($strSinAssuntosDesativados=='S'){
    $objAssuntoDTO->setBolExclusaoLogica(false);
  }

  $objAssuntoDTO->setNumIdTabelaAssuntos($objTabelaAssuntosDTO->getNumIdTabelaAssuntos());

  $objAssuntoDTO->setStrPalavrasPesquisa(PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaAssuntos'));
  
  PaginaSEI::getInstance()->prepararOrdenacao($objAssuntoDTO, 'CodigoEstruturado', InfraDTO::$TIPO_ORDENACAO_ASC);
  
  PaginaSEI::getInstance()->prepararPaginacao($objAssuntoDTO);
  
  $objAssuntoRN = new AssuntoRN();
  $arrObjAssuntoDTO = $objAssuntoRN->pesquisarRN0246($objAssuntoDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objAssuntoDTO);
  
  $numRegistros = count($arrObjAssuntoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='assunto_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('assunto_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('assunto_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='assunto_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('assunto_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('assunto_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('assunto_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('assunto_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('assunto_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('assunto_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('assunto_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('assunto_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_desativar&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim'.$strParametros);
    }    
    
    if ($bolAcaoExcluir){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }
    
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }
    
    $strResultado = '';

    $strSumarioTabela = 'Tabela de Assuntos.';
    $strCaptionTabela = 'Assuntos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th width="23%" class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAssuntoDTO,'Código','CodigoEstruturado',$arrObjAssuntoDTO).'</th>'."\n";
    $strResultado .= '<th width="53%" class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAssuntoDTO,'Descrição','Descricao',$arrObjAssuntoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    $n = 0;
    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjAssuntoDTO[$i]->getStrSinAtivo()=='S'){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;

      $bolCheckItem = $bolCheck;
      
      if ($_GET['acao']=='assunto_selecionar' && $arrObjAssuntoDTO[$i]->getStrSinEstrutural()=='S'){
        $bolCheckItem = false;
      }
       
      if ($bolCheckItem){
        $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($n,$arrObjAssuntoDTO[$i]->getNumIdAssunto(),AssuntoINT::formatarCodigoDescricaoRI0568($arrObjAssuntoDTO[$i]->getStrCodigoEstruturado(),$arrObjAssuntoDTO[$i]->getStrDescricao())).'</td>';
      }else{
        $strResultado .= '<td>&nbsp;</td>';          
      }
      
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAssuntoDTO[$i]->getStrCodigoEstruturado()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAssuntoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">';

      if ($bolCheckItem){
        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($n,$arrObjAssuntoDTO[$i]->getNumIdAssunto());
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_assunto='.$arrObjAssuntoDTO[$i]->getNumIdAssunto().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Assunto" alt="Consultar Assunto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_assunto='.$arrObjAssuntoDTO[$i]->getNumIdAssunto().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Assunto" alt="Alterar Assunto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjAssuntoDTO[$i]->getNumIdAssunto();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjAssuntoDTO[$i]->getStrCodigoEstruturado());
      }

      if ($bolAcaoDesativar && $arrObjAssuntoDTO[$i]->getStrSinAtivo()=='S'){
      	$strResultado .= '<a href="#ID-'.$arrObjAssuntoDTO[$i]->getNumIdAssunto().'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Assunto" alt="Desativar Assunto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjAssuntoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="#ID-'.$arrObjAssuntoDTO[$i]->getNumIdAssunto().'"  onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Assunto" alt="Reativar Assunto" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjAssuntoDTO[$i]->getNumIdAssunto().'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Assunto" alt="Excluir Assunto" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
      
      if ($bolCheckItem){
        $n++;
      }
    }
    $strResultado .= '</table>';
  }
  
  if ($_GET['acao'] == 'assunto_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
    $arrComandos[] = '<button type="button" accesskey="V" id="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_tabela_assuntos'])).'\'" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
  }


  if (PaginaSEI::getInstance()->isBolPaginaSelecao()) {
    $strDisplayOpcaoDesativados = 'display:none;';
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

#lblTabelaAssuntos {position:absolute;left:0%;top:0%;width:40%;}
#txtTabelaAssuntos {position:absolute;left:0%;top:20%;width:40%;}

#lblPalavrasPesquisaAssuntos {position:absolute;left:0%;top:50%;width:70%;}
#txtPalavrasPesquisaAssuntos {position:absolute;left:0%;top:70%;width:70%;}

#divSinAssuntosDesativados {position:absolute;left:72%;top:70%;<?=$strDisplayOpcaoDesativados?>}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){	
  if ('<?=$_GET['acao']?>'=='assunto_selecionar'){
    infraReceberSelecao();
  }
  
  if (infraGetAnchor()==null){
    document.getElementById('txtPalavrasPesquisaAssuntos').focus();
  }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Assunto \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAssuntoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAssuntoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Assunto selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Assuntos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAssuntoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAssuntoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Assunto \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAssuntoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAssuntoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Assunto selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Assuntos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAssuntoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAssuntoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Assunto \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAssuntoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAssuntoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Assunto selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Assuntos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAssuntoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAssuntoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAssuntoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('10em');
  ?>

    <label id="lblTabelaAssuntos" class="infraLabelObrigatorio">Tabela:</label>
    <input type="text" id="txtTabelaAssuntos" name="txtTabelaAssuntos" readonly="readonly" class="infraText infraReadOnly" value=" <?=PaginaSEI::tratarHTML($objTabelaAssuntosDTO->getStrNome())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

	  <label id="lblPalavrasPesquisaAssuntos" for="txtPalavrasPesquisaAssuntos" class="infraLabelOpcional">Palavras para Pesquisa:</label>
	  <input type="text" id="txtPalavrasPesquisaAssuntos" name="txtPalavrasPesquisaAssuntos" value="<?=PaginaSEI::tratarHTML($objAssuntoDTO->getStrPalavrasPesquisa())?>" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <div id="divSinAssuntosDesativados" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinAssuntosDesativados" name="chkSinAssuntosDesativados" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinAssuntosDesativados)?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinAssuntosDesativados" for="chkSinAssuntosDesativados" accesskey="" class="infraLabelCheckbox" >Incluir desativados</label>
    </div>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  
  <input type="hidden" name="hdnFlag" value="1" />  
  
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>