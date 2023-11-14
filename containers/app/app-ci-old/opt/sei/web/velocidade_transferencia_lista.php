<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/05/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
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

  PaginaSEI::getInstance()->prepararSelecao('velocidade_transferencia_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnIdUnidade','txtUnidade','hdnIdUsuario','txtUsuario'));

  switch($_GET['acao']){
    case 'velocidade_transferencia_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVelocidadeTransferenciaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVelocidadeTransferenciaDTO = new VelocidadeTransferenciaDTO();
          $objVelocidadeTransferenciaDTO->setNumIdUsuario($arrStrIds[$i]);
          $arrObjVelocidadeTransferenciaDTO[] = $objVelocidadeTransferenciaDTO;
        }
        $objVelocidadeTransferenciaRN = new VelocidadeTransferenciaRN();
        $objVelocidadeTransferenciaRN->excluir($arrObjVelocidadeTransferenciaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'velocidade_transferencia_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVelocidadeTransferenciaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVelocidadeTransferenciaDTO = new VelocidadeTransferenciaDTO();
          $objVelocidadeTransferenciaDTO->setNumIdUsuario($arrStrIds[$i]);
          $arrObjVelocidadeTransferenciaDTO[] = $objVelocidadeTransferenciaDTO;
        }
        $objVelocidadeTransferenciaRN = new VelocidadeTransferenciaRN();
        $objVelocidadeTransferenciaRN->desativar($arrObjVelocidadeTransferenciaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'velocidade_transferencia_reativar':
      $strTitulo = 'Reativar Velocidades de Transferência de Dados';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjVelocidadeTransferenciaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objVelocidadeTransferenciaDTO = new VelocidadeTransferenciaDTO();
            $objVelocidadeTransferenciaDTO->setNumIdUsuario($arrStrIds[$i]);
            $arrObjVelocidadeTransferenciaDTO[] = $objVelocidadeTransferenciaDTO;
          }
          $objVelocidadeTransferenciaRN = new VelocidadeTransferenciaRN();
          $objVelocidadeTransferenciaRN->reativar($arrObjVelocidadeTransferenciaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'velocidade_transferencia_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Velocidade de Transferência de Dados','Selecionar Velocidades de Transferência de Dados');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='velocidade_transferencia_cadastrar'){
        if (isset($_GET['id_usuario'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_usuario']);
        }
      }
      break;

    case 'velocidade_transferencia_listar':
      $strTitulo = 'Velocidades de Transferência de Dados';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'velocidade_transferencia_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'velocidade_transferencia_listar' || $_GET['acao'] == 'velocidade_transferencia_selecionar'){ 
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=velocidade_transferencia_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  } */

  $objVelocidadeTransferenciaDTO = new VelocidadeTransferenciaDTO();
  $objVelocidadeTransferenciaDTO->retNumIdUsuario();
  $objVelocidadeTransferenciaDTO->retDblVelocidade();
  $objVelocidadeTransferenciaDTO->retStrSiglaUsuario();
  $objVelocidadeTransferenciaDTO->retStrNomeUsuario();
  $objVelocidadeTransferenciaDTO->retStrSiglaOrgaoUsuario();
  $objVelocidadeTransferenciaDTO->retStrDescricaoOrgaoUsuario();
  $objVelocidadeTransferenciaDTO->retStrSiglaUnidade();
  $objVelocidadeTransferenciaDTO->retStrDescricaoUnidade();
  $objVelocidadeTransferenciaDTO->retStrSiglaOrgaoUnidade();
  $objVelocidadeTransferenciaDTO->retStrDescricaoOrgaoUnidade();
  $objVelocidadeTransferenciaDTO->retStrNomeCidadeContato();
  $objVelocidadeTransferenciaDTO->retStrSiglaUfCidadeContato();
  $objVelocidadeTransferenciaDTO->retStrNomeUfCidadeContato();

  $numIdUsuario = PaginaSEI::getInstance()->recuperarCampo('hdnIdUsuario');
  $strNomeUsuario = PaginaSEI::getInstance()->recuperarCampo('txtUsuario');
  if ($numIdUsuario!==''){
    $objVelocidadeTransferenciaDTO->setNumIdUsuario($numIdUsuario);
  }

  $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('hdnIdUnidade');
  $strDescricaoUnidade = PaginaSEI::getInstance()->recuperarCampo('txtUnidade');
  if ($numIdUnidade!==''){
    $objVelocidadeTransferenciaDTO->setNumIdUnidade($numIdUnidade);
  }

/* 
  if ($_GET['acao'] == 'velocidade_transferencia_reativar'){
    //Lista somente inativos
    $objVelocidadeTransferenciaDTO->setBolExclusaoLogica(false);
    $objVelocidadeTransferenciaDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objVelocidadeTransferenciaDTO, 'Velocidade', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objVelocidadeTransferenciaDTO,100);

  $objVelocidadeTransferenciaRN = new VelocidadeTransferenciaRN();
  $arrObjVelocidadeTransferenciaDTO = $objVelocidadeTransferenciaRN->listar($objVelocidadeTransferenciaDTO);

  PaginaSEI::getInstance()->processarPaginacao($objVelocidadeTransferenciaDTO);
  $numRegistros = count($arrObjVelocidadeTransferenciaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='velocidade_transferencia_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_consultar');
      $bolAcaoAlterar = false;//SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='velocidade_transferencia_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;//SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_consultar');
      $bolAcaoAlterar = false;//SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('velocidade_transferencia_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=velocidade_transferencia_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=velocidade_transferencia_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=velocidade_transferencia_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='velocidade_transferencia_reativar'){ */
      $strSumarioTabela = 'Tabela de Velocidades de Transferência de Dados.';
      $strCaptionTabela = 'Velocidades de Transferência de Dados';
    /* }else{
      $strSumarioTabela = 'Tabela de Velocidades de Transferência de Dados Inativas.';
      $strCaptionTabela = 'Velocidades de Transferência de Dados Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objVelocidadeTransferenciaDTO,'Velocidade (Kbs)','Velocidade',$arrObjVelocidadeTransferenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objVelocidadeTransferenciaDTO,'Usuário','SiglaUsuario',$arrObjVelocidadeTransferenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objVelocidadeTransferenciaDTO,'Última Unidade','SiglaUnidade',$arrObjVelocidadeTransferenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objVelocidadeTransferenciaDTO,'Cidade','NomeCidadeContato',$arrObjVelocidadeTransferenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objVelocidadeTransferenciaDTO,'Estado','SiglaUfCidadeContato',$arrObjVelocidadeTransferenciaDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjVelocidadeTransferenciaDTO[$i]->getNumIdUsuario(),$arrObjVelocidadeTransferenciaDTO[$i]->getDblVelocidade()).'</td>';
      }
      $strResultado .= '<td align="center">'.InfraUtil::formatarMilhares($arrObjVelocidadeTransferenciaDTO[$i]->getDblVelocidade()).'</td>';
      //$strResultado .= '<td align="center">'.$arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaUsuario().'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaUsuario()).'</a> / <a alt="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrDescricaoOrgaoUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrDescricaoOrgaoUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaOrgaoUsuario()).'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaUnidade()).'</a> / <a alt="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrDescricaoOrgaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrDescricaoOrgaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaOrgaoUnidade()).'</a></td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrNomeCidadeContato()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrNomeUfCidadeContato()).'" title="'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrNomeUfCidadeContato()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaUfCidadeContato()).'</a></td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjVelocidadeTransferenciaDTO[$i]->getNumIdUsuario());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=velocidade_transferencia_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_usuario='.$arrObjVelocidadeTransferenciaDTO[$i]->getNumIdUsuario()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Velocidade de Transferência de Dados" alt="Consultar Velocidade de Transferência de Dados" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=velocidade_transferencia_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_usuario='.$arrObjVelocidadeTransferenciaDTO[$i]->getNumIdUsuario()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Velocidade de Transferência de Dados" alt="Alterar Velocidade de Transferência de Dados" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjVelocidadeTransferenciaDTO[$i]->getNumIdUsuario();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjVelocidadeTransferenciaDTO[$i]->getStrSiglaUsuario());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Velocidade de Transferência de Dados" alt="Desativar Velocidade de Transferência de Dados" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Velocidade de Transferência de Dados" alt="Reativar Velocidade de Transferência de Dados" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Velocidade de Transferência de Dados" alt="Excluir Velocidade de Transferência de Dados" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'velocidade_transferencia_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');
  $strLinkAjaxUsuarios = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');

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

#lblUnidade {position:absolute;left:0%;top:0%;}
#txtUnidade {position:absolute;left:0%;top:20%;width:50%;}

#lblUsuario {position:absolute;left:0%;top:45%;}
#txtUsuario {position:absolute;left:0%;top:65%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarUnidade = null;
var objAutoCompletarUsuario = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='velocidade_transferencia_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  objAutoCompletarUnidade.limparCampo = true;
  objAutoCompletarUnidade.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value;
  };
  objAutoCompletarUnidade.selecionar('<?=$numIdUnidade;?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoUnidade,false)?>');

  objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuarios?>');
  objAutoCompletarUsuario.limparCampo = true;
  objAutoCompletarUsuario.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
  };
  objAutoCompletarUsuario.selecionar('<?=$numIdUsuario?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUsuario,false)?>');

  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Velocidade de Transferência de Dados do usuário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVelocidadeTransferenciaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVelocidadeTransferenciaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Velocidade de Transferência de Dados selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Velocidades de Transferência de Dados selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVelocidadeTransferenciaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVelocidadeTransferenciaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Velocidade de Transferência de Dados do usuário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVelocidadeTransferenciaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVelocidadeTransferenciaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Velocidade de Transferência de Dados selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Velocidades de Transferência de Dados selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVelocidadeTransferenciaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVelocidadeTransferenciaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Velocidade de Transferência de Dados do usuário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVelocidadeTransferenciaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVelocidadeTransferenciaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Velocidade de Transferência de Dados selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Velocidades de Transferência de Dados selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVelocidadeTransferenciaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVelocidadeTransferenciaLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVelocidadeTransferenciaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('10em');
  ?>

  <label id="lblUnidade" for="txtUnidade" class="infraLabelOpcional">Unidade:</label>
  <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($strDescricaoUnidade)?>" />
  <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="<?=$numIdUnidade?>" />

  <label id="lblUsuario" for="txtUsuario" class="infraLabelOpcional">Usuário:</label>
  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=PaginaSEI::tratarHTML($strNomeUsuario)?>" />
  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" class="infraText" value="<?=$numIdUsuario?>" />
  
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