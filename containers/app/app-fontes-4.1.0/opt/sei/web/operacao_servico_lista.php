<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
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

  PaginaSEI::getInstance()->prepararSelecao('operacao_servico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selStaOperacaoServicoPesqOperacaoServico','selTipoProcedimentoPesqOperacaoServico','selSeriePesqOperacaoServico','selUnidadePesqOperacaoServico'));

  $strParametros = '&id_usuario='.$_GET['id_usuario'].'&id_servico='.$_GET['id_servico'];
  
  $objUsuarioDTO = new UsuarioDTO();
  $objUsuarioDTO->retStrSigla();
  $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

  $objUsuarioRN = new UsuarioRN();
  $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
  
  $objServicoDTO = new ServicoDTO();
  $objServicoDTO->retStrIdentificacao();
  $objServicoDTO->setNumIdServico($_GET['id_servico']);

  $objServicoRN = new ServicoRN();
  $objServicoDTO = $objServicoRN->consultar($objServicoDTO);
  
  switch($_GET['acao']){
    case 'operacao_servico_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjOperacaoServicoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objOperacaoServicoDTO = new OperacaoServicoDTO();
          $objOperacaoServicoDTO->setNumIdOperacaoServico($arrStrIds[$i]);
          $arrObjOperacaoServicoDTO[] = $objOperacaoServicoDTO;
        }
        $objOperacaoServicoRN = new OperacaoServicoRN();
        $objOperacaoServicoRN->excluir($arrObjOperacaoServicoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

/* 
    case 'operacao_servico_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjOperacaoServicoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objOperacaoServicoDTO = new OperacaoServicoDTO();
          $objOperacaoServicoDTO->setNumIdOperacaoServico($arrStrIds[$i]);
          $arrObjOperacaoServicoDTO[] = $objOperacaoServicoDTO;
        }
        $objOperacaoServicoRN = new OperacaoServicoRN();
        $objOperacaoServicoRN->desativar($arrObjOperacaoServicoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'operacao_servico_reativar':
      $strTitulo = 'Reativar Operações';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjOperacaoServicoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objOperacaoServicoDTO = new OperacaoServicoDTO();
            $objOperacaoServicoDTO->setNumIdOperacaoServico($arrStrIds[$i]);
            $arrObjOperacaoServicoDTO[] = $objOperacaoServicoDTO;
          }
          $objOperacaoServicoRN = new OperacaoServicoRN();
          $objOperacaoServicoRN->reativar($arrObjOperacaoServicoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
        die;
      } 
      break;

 */
    case 'operacao_servico_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Operação '.$objUsuarioDTO->getStrSigla().' - '.$objServicoDTO->getStrIdentificacao(),'Selecionar Operações '.$objUsuarioDTO->getStrSigla().' - '.$objServicoDTO->getStrIdentificacao());

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='operacao_servico_cadastrar'){
        if (isset($_GET['id_operacao_servico'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_operacao_servico']);
        }
      }
      break;

    case 'operacao_servico_listar':
      $strTitulo = 'Operações '.$objUsuarioDTO->getStrSigla().' - '.$objServicoDTO->getStrIdentificacao();
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'operacao_servico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'operacao_servico_listar' || $_GET['acao'] == 'operacao_servico_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objOperacaoServicoDTO = new OperacaoServicoDTO();
  $objOperacaoServicoDTO->retNumIdOperacaoServico();
  $objOperacaoServicoDTO->retNumStaOperacaoServico();
  $objOperacaoServicoDTO->retStrSiglaUnidade();
  $objOperacaoServicoDTO->retStrDescricaoUnidade();
  $objOperacaoServicoDTO->retStrNomeTipoProcedimento();
  $objOperacaoServicoDTO->retStrNomeSerie();
  
  $numStaOperacaoServico = PaginaSEI::getInstance()->recuperarCampo('selStaOperacaoServicoPesqOperacaoServico');
  if ($numStaOperacaoServico!==''){
    $objOperacaoServicoDTO->setNumStaOperacaoServico($numStaOperacaoServico);
  }

  $numIdTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimentoPesqOperacaoServico');
  if ($numIdTipoProcedimento!==''){
    $objOperacaoServicoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
  }

  $numIdSerie = PaginaSEI::getInstance()->recuperarCampo('selSeriePesqOperacaoServico');
  if ($numIdSerie!==''){
    $objOperacaoServicoDTO->setNumIdSerie($numIdSerie);
  }

  $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('selUnidadePesqOperacaoServico');
  if ($numIdUnidade!==''){
    $objOperacaoServicoDTO->setNumIdUnidade($numIdUnidade);
  }

  $objOperacaoServicoDTO->setNumIdServico($_GET['id_servico']);
  
/* 
  if ($_GET['acao'] == 'operacao_servico_reativar'){
    //Lista somente inativos
    $objOperacaoServicoDTO->setBolExclusaoLogica(false);
    $objOperacaoServicoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objOperacaoServicoDTO, 'DescricaoOperacaoServico', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objOperacaoServicoDTO,100);

  $objOperacaoServicoDTO->setOrdNumIdOperacaoServico(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objOperacaoServicoRN = new OperacaoServicoRN();
  $arrObjOperacaoServicoDTO = $objOperacaoServicoRN->listar($objOperacaoServicoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objOperacaoServicoDTO);
  $numRegistros = count($arrObjOperacaoServicoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='operacao_servico_selecionar'){
      $bolAcaoReativar = false;
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_consultar');
      //$bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='operacao_servico_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_desativar&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim'.$strParametros);
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $arrTiposOperacaoServicos =  InfraArray::indexarArrInfraDTO($objOperacaoServicoRN->listarValoresOperacaoServico(),'StaOperacaoServico');

    foreach($arrObjOperacaoServicoDTO as $dto){
      $dto->setStrDescricaoOperacaoServico($arrTiposOperacaoServicos[$dto->getNumStaOperacaoServico()]->getStrDescricao());
    }
    
    $strResultado = '';

    /* if ($_GET['acao']!='operacao_servico_reativar'){ */
      $strSumarioTabela = 'Tabela de Operações.';
      $strCaptionTabela = 'Operações';
    /* }else{
      $strSumarioTabela = 'Tabela de Operações Inativas.';
      $strCaptionTabela = 'Operações Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objOperacaoServicoDTO,'Tipo da Operação','DescricaoOperacaoServico',$arrObjOperacaoServicoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objOperacaoServicoDTO,'Unidade','SiglaUnidade',$arrObjOperacaoServicoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objOperacaoServicoDTO,'Tipo do Processo','NomeTipoProcedimento',$arrObjOperacaoServicoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objOperacaoServicoDTO,'Tipo do Documento','NomeSerie',$arrObjOperacaoServicoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjOperacaoServicoDTO[$i]->getNumIdOperacaoServico(),$arrObjOperacaoServicoDTO[$i]->getNumIdOperacaoServico()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjOperacaoServicoDTO[$i]->getStrDescricaoOperacaoServico()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjOperacaoServicoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjOperacaoServicoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjOperacaoServicoDTO[$i]->getStrSiglaUnidade()).'</a></td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjOperacaoServicoDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjOperacaoServicoDTO[$i]->getStrNomeSerie()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjOperacaoServicoDTO[$i]->getNumIdOperacaoServico());

      /*
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_operacao_servico='.$arrObjOperacaoServicoDTO[$i]->getNumIdOperacaoServico().$strParametros)).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Operação" alt="Consultar Operação" class="infraImg" /></a>&nbsp;';
      }
      */

      
      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_operacao_servico='.$arrObjOperacaoServicoDTO[$i]->getNumIdOperacaoServico().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Operação" alt="Alterar Operação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjOperacaoServicoDTO[$i]->getNumIdOperacaoServico();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrTiposOperacaoServicos[$arrObjOperacaoServicoDTO[$i]->getNumStaOperacaoServico()]->getStrDescricao());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Operação" alt="Desativar Operação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Operação" alt="Reativar Operação" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Operação" alt="Excluir Operação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'operacao_servico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_servico'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensselStaOperacaoServicoPesqOperacaoServico = OperacaoServicoINT::montarSelectStaOperacaoServico('','Todos',$numStaOperacaoServico);
  $strItensselTipoProcedimentoPesqOperacaoServico = TipoProcedimentoINT::montarSelectNome('','Todos',$numIdTipoProcedimento);
  $strItensselSeriePesqOperacaoServico = SerieINT::montarSelectNomeRI0802('','Todos',$numIdSerie);
  $strItensselUnidadePesqOperacaoServico = UnidadeINT::montarSelectSigla('','Todas',$numIdUnidade);
  
  
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

#lblStaOperacaoServico {position:absolute;left:0%;top:0%;width:24%;}
#selStaOperacaoServicoPesqOperacaoServico {position:absolute;left:0%;top:40%;width:24%;}

#lblUnidade {position:absolute;left:25%;top:0%;width:24%;}
#selUnidadePesqOperacaoServico {position:absolute;left:25%;top:40%;width:24%;}

#lblTipoProcedimento {position:absolute;left:50%;top:0%;width:24%;}
#selTipoProcedimentoPesqOperacaoServico {position:absolute;left:50%;top:40%;width:24%;}

#lblSerie {position:absolute;left:75%;top:0%;width:24%;}
#selSeriePesqOperacaoServico {position:absolute;left:75%;top:40%;width:24%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='operacao_servico_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Operação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOperacaoServicoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmOperacaoServicoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Operação selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Operações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOperacaoServicoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmOperacaoServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Operação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOperacaoServicoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmOperacaoServicoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Operação selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Operações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOperacaoServicoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmOperacaoServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Operação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmOperacaoServicoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmOperacaoServicoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Operação selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Operações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmOperacaoServicoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmOperacaoServicoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOperacaoServicoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblStaOperacaoServico" for="selStaOperacaoServicoPesqOperacaoServico" accesskey="" class="infraLabelOpcional">Tipo da Operação:</label>
  <select id="selStaOperacaoServicoPesqOperacaoServico" name="selStaOperacaoServicoPesqOperacaoServico" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensselStaOperacaoServicoPesqOperacaoServico?>
  </select>

  <label id="lblUnidade" for="selUnidadePesqOperacaoServico" accesskey="" class="infraLabelOpcional">Unidade:</label>
  <select id="selUnidadePesqOperacaoServico" name="selUnidadePesqOperacaoServico" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensselUnidadePesqOperacaoServico?>
  </select>

  <label id="lblTipoProcedimento" for="selTipoProcedimentoPesqOperacaoServico" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>
  <select id="selTipoProcedimentoPesqOperacaoServico" name="selTipoProcedimentoPesqOperacaoServico" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensselTipoProcedimentoPesqOperacaoServico?>
  </select>

  <label id="lblSerie" for="selSeriePesqOperacaoServico" accesskey="" class="infraLabelOpcional">Tipo do Documento:</label>
  <select id="selSeriePesqOperacaoServico" name="selSeriePesqOperacaoServico" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensselSeriePesqOperacaoServico?>
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