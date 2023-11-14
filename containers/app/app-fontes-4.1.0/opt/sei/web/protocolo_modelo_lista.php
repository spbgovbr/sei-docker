<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/08/2012 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.0
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

  PaginaSEI::getInstance()->prepararSelecao('documento_modelo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples'));

  PaginaSEI::getInstance()->salvarCamposPost(array('selGrupoProtocoloModelo','txtPalavrasPesquisaProtocoloModelo'));

  switch($_GET['acao']){
    case 'protocolo_modelo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjProtocoloModeloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objProtocoloModeloDTO = new ProtocoloModeloDTO();
          $objProtocoloModeloDTO->setDblIdProtocoloModelo($arrStrIds[$i]);
          $arrObjProtocoloModeloDTO[] = $objProtocoloModeloDTO;
        }
        $objProtocoloModeloRN = new ProtocoloModeloRN();
        $objProtocoloModeloRN->excluir($arrObjProtocoloModeloDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_protocolo='.$_GET['id_protocolo']));
      die;

    case 'documento_modelo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Favorito','Selecionar Favoritos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='protocolo_modelo_cadastrar'){
        if (isset($_GET['id_protocolo_modelo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_protocolo_modelo']);
        }
      }
      break;

    case 'protocolo_modelo_listar':
      $strTitulo = 'Favoritos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'documento_modelo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="G" id="btnGrupo" value="Grupos" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">G</span>rupos</button>';
  }

  $objProtocoloModeloDTO = new ProtocoloModeloDTO();

  $strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaProtocoloModelo');
  if ($strPalavrasPesquisa!=''){
    $objProtocoloModeloDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
  }

  $numIdGrupoProtocoloModelo = PaginaSEI::getInstance()->recuperarCampo('selGrupoProtocoloModelo');
  if ($numIdGrupoProtocoloModelo!='null' && $numIdGrupoProtocoloModelo!=''){  
    $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($numIdGrupoProtocoloModelo);
  }

  if ($_GET['acao']=='documento_modelo_selecionar'){
    $objProtocoloModeloDTO->setStrStaProtocoloProtocolo(ProtocoloRN::$TP_DOCUMENTO_GERADO);
    $objProtocoloModeloDTO->setStrStaDocumentoDocumento(DocumentoRN::$TD_EDITOR_INTERNO);
  }


  PaginaSEI::getInstance()->prepararOrdenacao($objProtocoloModeloDTO, 'Alteracao', InfraDTO::$TIPO_ORDENACAO_DESC);

  PaginaSEI::getInstance()->prepararPaginacao($objProtocoloModeloDTO);

  $objProtocoloModeloRN = new ProtocoloModeloRN();  
  $arrObjProtocoloModeloDTO = $objProtocoloModeloRN->listarModelosUnidade($objProtocoloModeloDTO);
    
  PaginaSEI::getInstance()->processarPaginacao($objProtocoloModeloDTO);
  $numRegistros = count($arrObjProtocoloModeloDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='documento_modelo_selecionar'){
      $bolAcaoReativar = false;
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_alterar');
      $bolAcaoImprimir = false;      
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
     }else{
      $bolAcaoReativar = false;
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('protocolo_modelo_desativar');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_excluir&acao_origem='.$_GET['acao']);
    }
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    
    }

    $strResultado = '';

    /* if ($_GET['acao']!='protocolo_modelo_reativar'){ */
      $strSumarioTabela = 'Tabela de Favoritos.';
      $strCaptionTabela = 'Favoritos';
    /* }else{
      $strSumarioTabela = 'Tabela de Favoritos Inativos.';
      $strCaptionTabela = 'Favoritos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }      
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objProtocoloModeloDTO,'Protocolo','IdProtocolo',$arrObjProtocoloModeloDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objProtocoloModeloDTO,'Tipo','NomeSerie',$arrObjProtocoloModeloDTO).'</th>'."\n";    
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objProtocoloModeloDTO,'Usuário','IdUsuario',$arrObjProtocoloModeloDTO).'</th>'."\n";    
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objProtocoloModeloDTO,'Data','Alteracao',$arrObjProtocoloModeloDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objProtocoloModeloDTO,'Descrição','Descricao',$arrObjProtocoloModeloDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objProtocoloModeloDTO,'Grupo','NomeGrupoProtocoloModelo',$arrObjProtocoloModeloDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo(),$arrObjProtocoloModeloDTO[$i]->getStrProtocoloFormatado()).'</td>';
      }

      $strClasseCSS = 'protocoloNormal';

      if ($arrObjProtocoloModeloDTO[$i]->getStrStaNivelAcessoGlobalProtocolo()==ProtocoloRN::$NA_SIGILOSO){
        $strClasseCSS = 'protocoloSigiloso';
      }


      if ($arrObjProtocoloModeloDTO[$i]->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO){
        $strResultado .= '<td align="center" valign="top"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&id_procedimento='.$arrObjProtocoloModeloDTO[$i]->getDblIdProtocolo()).'" target="_blank" class="'.$strClasseCSS.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrProtocoloFormatado()).'</a></td>';
        $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
      }else{
        if ($arrObjProtocoloModeloDTO[$i]->getStrStaNivelAcessoGlobalProtocolo()==ProtocoloRN::$NA_SIGILOSO){
          $strResultado .= '<td align="center" valign="top"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&id_documento='.$arrObjProtocoloModeloDTO[$i]->getDblIdProtocolo()).'" target="_blank" class="'.$strClasseCSS.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeTipoProcedimento()).'">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrProtocoloFormatado()).'</a></td>';
        }else{
          $strResultado .= '<td align="center" valign="top"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$arrObjProtocoloModeloDTO[$i]->getDblIdProtocolo()).'" target="_blank" class="'.$strClasseCSS.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeSerie()).'">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrProtocoloFormatado()).'</a></td>';
        }
        $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeSerie()).'</td>';
      }


      $strResultado .= '<td align="center" valign="top"><a alt="'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getDthAlteracao()).'</td>';
      $strResultado .= '<td valign="top">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($arrObjProtocoloModeloDTO[$i]->getStrNomeGrupoProtocoloModelo()).'</td>';
      $strResultado .= '<td align="center" valign="top">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo(),'Infra','','Selecionar este Favorito');

      //if ($bolAcaoConsultar){
      //  $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_protocolo_modelo='.$arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Favorito" alt="Consultar Favorito" class="infraImg" /></a>&nbsp;';
      //}

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=protocolo_modelo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_protocolo_modelo='.$arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Favorito" alt="Alterar Favorito" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjProtocoloModeloDTO[$i]->getDblIdProtocoloModelo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjProtocoloModeloDTO[$i]->getStrDescricao());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Favorito" alt="Desativar Favorito" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Favorito" alt="Reativar Favorito" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Favorito" alt="Excluir Favorito" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'documento_modelo_selecionar'){
    //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="infraFecharJanelaSelecao();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelGrupoProtocoloModelo = GrupoProtocoloModeloINT::montarSelectNome('null','Todos', $numIdGrupoProtocoloModelo, SessaoSEI::getInstance()->getNumIdUnidadeAtual());


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
#lblGrupoProtocoloModelo {position:absolute;left:0%;top:0%;}
#selGrupoProtocoloModelo {position:absolute;left:0%;top:18%;width:50%;}

#lblPalavrasPesquisaProtocoloModelo {position:absolute;left:0%;top:50%;width:65%;}
#txtPalavrasPesquisaProtocoloModelo {position:absolute;left:0%;top:68%;width:65%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='documento_modelo_selecionar'){
    infraReceberSelecao();
    document.getElementById('sbmPesquisar').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Favorito \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProtocoloModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmProtocoloModeloLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Favorito selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloModeloLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmProtocoloModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Favorito \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProtocoloModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmProtocoloModeloLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Favorito selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloModeloLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmProtocoloModeloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Favorito \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProtocoloModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmProtocoloModeloLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Favorito selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Favoritos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProtocoloModeloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmProtocoloModeloLista').submit();
  }
}
<? } ?>


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProtocoloModeloLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('10em');
  ?>
  <label id="lblGrupoProtocoloModelo" for="selGrupoProtocoloModelo" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
  <select id="selGrupoProtocoloModelo" name="selGrupoProtocoloModelo" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelGrupoProtocoloModelo?>
  </select>
  
  <label id="lblPalavrasPesquisaProtocoloModelo" for="txtPalavrasPesquisaProtocoloModelo" accesskey="" class="infraLabelOpcional">Palavras-chave para pesquisa:</label>
  <input type="text" id="txtPalavrasPesquisaProtocoloModelo" name="txtPalavrasPesquisaProtocoloModelo" class="infraText" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" onkeypress="return tratarDigitacao(event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

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