<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
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

  PaginaSEI::getInstance()->prepararSelecao('atributo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost());

  $strParametros = '';
  if(isset($_GET['id_tipo_formulario'])){
    $strParametros .= '&id_tipo_formulario='.$_GET['id_tipo_formulario'];
  }

  switch($_GET['acao']){
    case 'atributo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAtributoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAtributoDTO = new AtributoDTO();
          $objAtributoDTO->setNumIdAtributo($arrStrIds[$i]);
          $arrObjAtributoDTO[] = $objAtributoDTO;
        }
        $objAtributoRN = new AtributoRN();
        $objAtributoRN->excluirRN0111($arrObjAtributoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'atributo_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAtributoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAtributoDTO = new AtributoDTO();
          $objAtributoDTO->setNumIdAtributo($arrStrIds[$i]);
          $arrObjAtributoDTO[] = $objAtributoDTO;
        }
        $objAtributoRN = new AtributoRN();
        $objAtributoRN->desativarRN0574($arrObjAtributoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'atributo_reativar':
      $strTitulo = 'Reativar Atributos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjAtributoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objAtributoDTO = new AtributoDTO();
            $objAtributoDTO->setNumIdAtributo($arrStrIds[$i]);
            $arrObjAtributoDTO[] = $objAtributoDTO;
          }
          $objAtributoRN = new AtributoRN();
          $objAtributoRN->reativarRN0575($arrObjAtributoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
        die;
      } 
      break;


    case 'atributo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Campo','Selecionar Campos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='atributo_cadastrar'){
        if (isset($_GET['id_atributo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_atributo']);
        }
      }
      break;

    case 'atributo_listar':
      $strTitulo = 'Campos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'atributo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }



  $objAtributoDTO = new AtributoDTO();
  $objAtributoDTO->setBolExclusaoLogica(false);
  $objAtributoDTO->retNumIdAtributo();
  $objAtributoDTO->retStrNome();
  $objAtributoDTO->retStrRotulo();
  $objAtributoDTO->retNumOrdem();
  $objAtributoDTO->retStrStaTipo();
  $objAtributoDTO->retNumTamanho();
  $objAtributoDTO->retStrSinObrigatorio();
  $objAtributoDTO->retStrSinAtivo();
  $objAtributoDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);

  $objAtributoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objAtributoDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);

  //PaginaSEI::getInstance()->prepararPaginacao($objAtributoDTO);

  $objAtributoINT = new AtributoINT();
  $objAtributoRN = new AtributoRN();
  $arrObjAtributoDTO = $objAtributoRN->listarRN0165($objAtributoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objAtributoDTO);
  $numRegistros = count($arrObjAtributoDTO);

  if ($_GET['acao'] == 'atributo_listar' && $numRegistros){

    $bolAcaoTipoFormularioVisualizar = SessaoSEI::getInstance()->verificarPermissao('tipo_formulario_visualizar');

    if ($bolAcaoTipoFormularioVisualizar){
      $arrComandos[] = '<button type="button" accesskey="V" id="btnVisualizar" value="Visualizar" onclick="infraAbrirJanelaModal(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_formulario_visualizar&id_tipo_formulario='.$_GET['id_tipo_formulario']).'\',700,500)" class="infraButton"><span class="infraTeclaAtalho">V</span>isualizar</button>';
    }
  }

  if ($_GET['acao'] == 'atributo_listar' || $_GET['acao'] == 'atributo_selecionar'){

    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('atributo_cadastrar');

    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atributo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }


  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='atributo_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('atributo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('atributo_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    /*
    }else if ($_GET['acao']=='atributo_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('atributo_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('atributo_excluir');
      $bolAcaoDesativar = false;
    */
    }else{
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('atributo_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('atributo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('atributo_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('atributo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('atributo_desativar');
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atributo_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim'.$strParametros);
    }
    
    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atributo_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }    

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="D" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">D</span>esativar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atributo_desativar&acao_origem='.$_GET['acao'].$strParametros);
    }
    

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='atributo_reativar'){
      $strSumarioTabela = 'Tabela de Campos.';
      $strCaptionTabela = 'Campos';
    }else{
      $strSumarioTabela = 'Tabela de Campos Inativos.';
      $strCaptionTabela = 'Campos Inativos';
    }

    for($i = 0;$i < $numRegistros; $i++){
      $arrObjAtributoDTO[$i]->setStrDescricaoTipo(AtributoINT::obterDescricao($arrObjAtributoDTO[$i]->getStrStaTipo()));
    }
        
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="15%">Nome</th>'."\n";
    $strResultado .= '<th class="infraTh">Rótulo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Ordem</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%" >Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Obrigatório</th>'."\n";
    $strResultado .= '<th class="infraTh" width="8%">Tamanho</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjAtributoDTO[$i]->getStrSinAtivo()=='S'){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;
      }else{
        $strCssTr = '<tr class="trVermelha">';
        $strResultado .= $strCssTr;
      }

      if ($bolCheck){
        $strResultado .= '<td valign="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjAtributoDTO[$i]->getNumIdAtributo(),$arrObjAtributoDTO[$i]->getStrRotulo()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjAtributoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $arrObjAtributoDTO[$i]->getStrRotulo(),false).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAtributoDTO[$i]->getNumOrdem()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAtributoDTO[$i]->getStrDescricaoTipo()).'</td>';
      $strResultado .= '<td align="center">'.$arrObjAtributoDTO[$i]->getStrSinObrigatorio().'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAtributoDTO[$i]->getNumTamanho()).'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjAtributoDTO[$i]->getNumIdAtributo());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atributo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_atributo='.$arrObjAtributoDTO[$i]->getNumIdAtributo().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Campo" alt="Consultar Campo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar && $arrObjAtributoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=atributo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_atributo='.$arrObjAtributoDTO[$i]->getNumIdAtributo().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Campo" alt="Alterar Campo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjAtributoDTO[$i]->getNumIdAtributo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjAtributoDTO[$i]->getStrRotulo());
      }

      if ($bolAcaoDesativar && $arrObjAtributoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Campo" alt="Desativar Campo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjAtributoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Campo" alt="Reativar Campo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Campo" alt="Excluir Campo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'atributo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($_GET['id_tipo_formulario'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $objTipoFormularioDTO = new TipoFormularioDTO();
  $objTipoFormularioDTO->setBolExclusaoLogica(false);
  $objTipoFormularioDTO->retStrNome();
  $objTipoFormularioDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);

  $objTipoFormularioRN = new TipoFormularioRN();
  $objTipoFormularioDTO = $objTipoFormularioRN->consultar($objTipoFormularioDTO);
  $strTipoFormulario = $objTipoFormularioDTO->getStrNome();
  
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
#lblTipoFormulario {position:absolute;left:0%;top:0%;width:50%;}
#txtTipoFormulario {position:absolute;left:0%;top:40%;width:50%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='atributo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Campo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAtributoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAtributoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Campo selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Campos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAtributoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAtributoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Campo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAtributoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAtributoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Campo selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Campos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAtributoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAtributoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Campo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAtributoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAtributoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Campo selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Campos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAtributoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAtributoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAtributoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblTipoFormulario" class="infraLabelObrigatorio">Tipo de Formulário:</label>
  <input type="text" id="txtTipoFormulario" name="txtTipoFormulario" readonly="readonly" class="infraText infraReadOnly" value=" <?=PaginaSEI::tratarHTML($strTipoFormulario)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
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