<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
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

  global $SEI_MODULOS;

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('alterados'));

  PaginaSEI::getInstance()->prepararSelecao('acompanhamento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples'));

  PaginaSEI::getInstance()->salvarCamposPost(array('selGrupoAcompanhamento','txtPalavrasPesquisaAcompanhamento'));

  switch($_GET['acao']){
    case 'acompanhamento_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjAcompanhamentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objAcompanhamentoDTO = new AcompanhamentoDTO();
          $objAcompanhamentoDTO->setNumIdAcompanhamento($arrStrIds[$i]);
          $arrObjAcompanhamentoDTO[] = $objAcompanhamentoDTO;
        }
        $objAcompanhamentoRN = new AcompanhamentoRN();
        $objAcompanhamentoRN->excluir($arrObjAcompanhamentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&resultado=1'));
      die;


    case 'acompanhamento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Acompanhamento Especial','Selecionar Acompanhamentos Especiais');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='acompanhamento_cadastrar'){
        if (isset($_GET['id_acompanhamento'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_acompanhamento']);
        }
      }
      break;

    case 'acompanhamento_listar':
      $strTitulo = 'Acompanhamento Especial';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'acompanhamento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if (SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_listar')){
    $arrComandos[] = '<button type="button" accesskey="L" id="btnGrupoAcompanhamentoListar" value="Listar Grupos" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">L</span>istar Grupos</button>';
  }

  $objAcompanhamentoDTO = new AcompanhamentoDTO();

  if (isset($_GET['id_grupo_acompanhamento'])){
    $numIdGrupoAcompanhamento = ($_GET['id_grupo_acompanhamento']=='-1'?'null':$_GET['id_grupo_acompanhamento']);
    PaginaSEI::getInstance()->salvarCampo('selGrupoAcompanhamento', $numIdGrupoAcompanhamento);
  }else {
    $numIdGrupoAcompanhamento = PaginaSEI::getInstance()->recuperarCampo('selGrupoAcompanhamento','');
  }

  if ($_GET['acao_origem']=='painel_controle_visualizar'){
    PaginaSEI::getInstance()->salvarCampo('txtPalavrasPesquisaAcompanhamento', '');
  }

  $strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaAcompanhamento');
  if ($strPalavrasPesquisa!=''){
    $objAcompanhamentoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
  }

  if ($numIdGrupoAcompanhamento !== '') {
    $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($numIdGrupoAcompanhamento);
  }

  if (isset($_GET['alterados']) && $_GET['alterados']=='1'){
    $objAcompanhamentoDTO->setStrSinAlterados('S');
  }

  if (isset($_GET['abertos']) && $_GET['abertos']=='1'){
    $objAcompanhamentoDTO->setStrSinAbertos('S');
  }

  if (isset($_GET['fechados']) && $_GET['fechados']=='1'){
    $objAcompanhamentoDTO->setStrSinFechados('S');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objAcompanhamentoDTO, 'IdProtocolo', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objAcompanhamentoDTO);

  $objAcompanhamentoRN = new AcompanhamentoRN();
  $arrObjAcompanhamentoDTO = $objAcompanhamentoRN->listarAcompanhamentosUnidade($objAcompanhamentoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objAcompanhamentoDTO);
  $numRegistros = count($arrObjAcompanhamentoDTO);

  if ($numRegistros > 0){

    $arrRetIconeIntegracao = null;

    if (count($SEI_MODULOS)) {

      $arrObjProcedimentoAPI = array();
      foreach($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO){

        $objProcedimentoDTO = $objAcompanhamentoDTO->getObjProcedimentoDTO();

        $dto = new ProcedimentoAPI();
        $dto->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
        $dto->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
        $dto->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
        $dto->setNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
        $dto->setIdTipoPrioridade($objProcedimentoDTO->getNumIdTipoPrioridade());
        $dto->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());
        $dto->setIdUnidadeGeradora($objProcedimentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $dto->setIdOrgaoUnidadeGeradora($objProcedimentoDTO->getNumIdOrgaoUnidadeGeradoraProtocolo());
        $dto->setIdHipoteseLegal($objProcedimentoDTO->getNumIdHipoteseLegalProtocolo());
        $dto->setGrauSigilo($objProcedimentoDTO->getStrStaGrauSigiloProtocolo());

        $arrObjProcedimentoAPI[] = $dto;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        if (($arrRetIconeIntegracaoModulo = $seiModulo->executar('montarIconeAcompanhamentoEspecial', $arrObjProcedimentoAPI))!=null){
          foreach($arrRetIconeIntegracaoModulo as $dblIdProcedimento => $arrIcone){
            if (!isset($arrRetIconeIntegracao[$dblIdProcedimento])){
              $arrRetIconeIntegracao[$dblIdProcedimento] = $arrIcone;
            }else{
              $arrRetIconeIntegracao[$dblIdProcedimento] = array_merge($arrRetIconeIntegracao[$dblIdProcedimento], $arrIcone);
            }
          }
        }
      }

    }

    if ($_GET['acao']=='acompanhamento_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('acompanhamento_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoRegistrarAnotacao = false;
      $bolAcaoAndamentoSituacaoGerenciar = false;
      $bolAcaoAndamentoMarcadorGerenciar = false;
      $bolAcaoAcompanhamentoAlterarGrupo = false;
      $bolAcaoAlterarControlePrazo = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('acompanhamento_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_desativar');
      $bolAcaoRegistrarAnotacao = SessaoSEI::getInstance()->verificarPermissao('anotacao_registrar');
      $bolAcaoAndamentoSituacaoGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_situacao_gerenciar');
      $bolAcaoAndamentoMarcadorGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_gerenciar');
      $bolAcaoAcompanhamentoAlterarGrupo = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_alterar_grupo');
      $bolAcaoDefinirControlePrazo = SessaoSEI::getInstance()->verificarPermissao('controle_prazo_definir');
    }

    if ($bolAcaoAcompanhamentoAlterarGrupo){
      $arrComandos[] = '<button type="button" accesskey="A" id="btnAcompanhamentoAlterarGrupo" value="Alterar Grupo" onclick="acaoAcompanhamentoAlterarGrupo();" class="infraButton"><span class="infraTeclaAtalho">A</span>lterar Grupo</button>';
      $strLinkAcompanhamentoAlterarGrupo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_alterar_grupo&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }

    if ($bolAcaoExcluir){
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $numTabIndexAcompanhamento = PaginaSEI::getInstance()->getProxTabTabela();
    $strResultado = '';

    /* if ($_GET['acao']!='acompanhamento_reativar'){ */
      $strSumarioTabela = 'Tabela de Acompanhamentos.';
      $strCaptionTabela = 'Acompanhamentos';
    /* }else{
      $strSumarioTabela = 'Tabela de Acompanhamentos Inativos.';
      $strCaptionTabela = 'Acompanhamentos Inativos';
    } */

    $strResultado .= '<table id="tblAcompanhamentos" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck('', 'Infra', '', $numTabIndexAcompanhamento).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="6%">&nbsp;</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoDTO,'Processo','IdProtocolo',$arrObjAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoDTO,'Usuário','IdUsuario',$arrObjAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoDTO,'Data','Alteracao',$arrObjAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoDTO,'Grupo','NomeGrupo',$arrObjAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoDTO,'Observação','Observacao',$arrObjAcompanhamentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $arrProtocolosVisitados = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

    for($i = 0;$i < $numRegistros; $i++){

      $objProcedimentoDTO = $arrObjAcompanhamentoDTO[$i]->getObjProcedimentoDTO();

      $strCssTr = ($strCssTr=='class="infraTrClara"')?'class="infraTrEscura"':'class="infraTrClara"';
      $strResultado .= '<tr '.$strCssTr.'>';

      $strResultado .= '<td valign="top" class="tdAcompanhamento">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento(), ProcedimentoINT::formatarProtocoloTipoRI0200($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado(),$objProcedimentoDTO->getStrNomeTipoProcedimento()),'N','Infra').'</td>';
      $strResultado .= '<td align="center" valign="top" class="tdAcompanhamento">';
      $strResultado .= AnotacaoINT::montarIconeAnotacao($objProcedimentoDTO->getObjAnotacaoDTO(),$bolAcaoRegistrarAnotacao,$arrObjAcompanhamentoDTO[$i]->getDblIdProtocolo(),'&id_acompanhamento='.$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento());
      $strResultado .= ProcedimentoINT::montarIconeVisualizacao($arrObjAcompanhamentoDTO[$i]->getNumTipoVisualizacao(), $objProcedimentoDTO, $arrRetIconeIntegracao,$bolAcaoAndamentoSituacaoGerenciar,$bolAcaoAndamentoMarcadorGerenciar,'&id_acompanhamento='.$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento());
      $strResultado .= ControlePrazoINT::montarIconeControlePrazo($bolAcaoDefinirControlePrazo, $objProcedimentoDTO, true, '&id_acompanhamento=' . $arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento());
      $strResultado .= '</td>';

      $strClasseProcesso = ProtocoloINT::obterCssProtocolo($objProcedimentoDTO, $arrProtocolosVisitados);

      $strResultado .= '<td align="center" valign="top" class=""><a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjAcompanhamentoDTO[$i]->getDblIdProtocolo()).'" target="_blank" class="'.$strClasseProcesso.'" title="'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()).'" tabindex="'.$numTabIndexAcompanhamento.'">'.$objProcedimentoDTO->getStrProtocoloProcedimentoFormatado().'</a></td>';

      $strResultado .= '<td align="center" valign="top" class="tdAcompanhamento"><a alt="'.PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td align="center" valign="top" class="tdAcompanhamento">'.$arrObjAcompanhamentoDTO[$i]->getDthAlteracao().'</td>';
      $strResultado .= '<td align="center" valign="top" class="tdAcompanhamento">'.PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrNomeGrupo()).'</td>';

      $strResultado .= '<td valign="top" class="tdAcompanhamento">';
      $strObservacao = PaginaSEI::tratarHTML($arrObjAcompanhamentoDTO[$i]->getStrObservacao());
      $strObservacao = str_replace('&lt;b&gt;','<b>', $strObservacao);
      $strObservacao = str_replace('&lt;/b&gt;','</b>', $strObservacao);
      $strResultado .= $strObservacao;
      $strResultado .= '</td>';
      
      
      $strResultado .= '<td align="center" valign="top" class="tdAcompanhamento tdAcompanhamentoUltima">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_acompanhamento='.$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento()).'" tabindex="'.$numTabIndexAcompanhamento.'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Acompanhamento" alt="Consultar Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_acompanhamento='.$arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento()).'" tabindex="'.$numTabIndexAcompanhamento.'" ><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Acompanhamento" alt="Alterar Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjAcompanhamentoDTO[$i]->getNumIdAcompanhamento();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" ><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Acompanhamento" alt="Desativar Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" ><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Acompanhamento" alt="Reativar Acompanhamento" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');"  tabindex="'.$numTabIndexAcompanhamento.'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Acompanhamento" alt="Excluir Acompanhamento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'acompanhamento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

	$strItensSelGrupoAcompanhamento = str_replace('&nbsp;','Nenhum', GrupoAcompanhamentoINT::montarSelectIdGrupoAcompanhamentoRI0012('','Todos', $numIdGrupoAcompanhamento, SessaoSEI::getInstance()->getNumIdUnidadeAtual()));
  
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

#lblSelGrupoAcompanhamento {position:absolute;left:0%;top:0%;}
#selGrupoAcompanhamento {position:absolute;left:0%;top:18%;width:50%;}

#lblPalavrasPesquisaAcompanhamento {position:absolute;left:0%;top:50%;width:65%;}
#txtPalavrasPesquisaAcompanhamento {position:absolute;left:0%;top:68%;width:65%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

  seiConfigurarTabIndexSinalizacoes('tblAcompanhamentos','<?=$numTabIndexAcompanhamento?>');

  //infraOcultarMenuSistemaEsquema();

  if ('<?=$_GET['acao']?>'=='acompanhamento_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Acompanhamento Especial no processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAcompanhamentoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Acompanhamento selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Acompanhamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmAcompanhamentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Acompanhamento Especial no processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAcompanhamentoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Acompanhamento selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Acompanhamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmAcompanhamentoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Acompanhamento Especial no processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAcompanhamentoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Acompanhamento selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Acompanhamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmAcompanhamentoLista').submit();
  }
}
<? } ?>

/////

<? if ($bolAcaoAcompanhamentoAlterarGrupo){ ?>
function acaoAcompanhamentoAlterarGrupo(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Acompanhamento selecionado.');
    return;
  }
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmAcompanhamentoLista').action='<?=$strLinkAcompanhamentoAlterarGrupo?>';
  document.getElementById('frmAcompanhamentoLista').submit();
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcompanhamentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?php   
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('10em');
  ?>
  <label id="lblSelGrupoAcompanhamento" for="selGrupoAcompanhamento" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
  <select id="selGrupoAcompanhamento" name="selGrupoAcompanhamento" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelGrupoAcompanhamento?>
  </select>

  <label id="lblPalavrasPesquisaAcompanhamento" for="txtPalavrasPesquisaAcompanhamento" accesskey="" class="infraLabelOpcional">Palavras-chave para pesquisa:</label>
  <input type="text" id="txtPalavrasPesquisaAcompanhamento" name="txtPalavrasPesquisaAcompanhamento" class="infraText" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" onkeypress="return tratarDigitacao(event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>