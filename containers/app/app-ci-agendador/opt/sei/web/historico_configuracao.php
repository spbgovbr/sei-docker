<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/07/2013 - criado por bcu
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

  PaginaSEI::getInstance()->prepararSelecao('tarefa_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'tarefa_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTarefaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTarefaDTO = new TarefaDTO();
          $objTarefaDTO->setNumIdTarefa($arrStrIds[$i]);
          $arrObjTarefaDTO[] = $objTarefaDTO;
        }
        $objTarefaRN = new TarefaRN();
        $objTarefaRN->excluir($arrObjTarefaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'tarefa_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTarefaDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTarefaDTO = new TarefaDTO();
          $objTarefaDTO->setNumIdTarefa($arrStrIds[$i]);
          $arrObjTarefaDTO[] = $objTarefaDTO;
        }
        $objTarefaRN = new TarefaRN();
        $objTarefaRN->desativar($arrObjTarefaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'tarefa_reativar':
      $strTitulo = 'Reativar Tarefas';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjTarefaDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objTarefaDTO = new TarefaDTO();
            $objTarefaDTO->setNumIdTarefa($arrStrIds[$i]);
            $arrObjTarefaDTO[] = $objTarefaDTO;
          }
          $objTarefaRN = new TarefaRN();
          $objTarefaRN->reativar($arrObjTarefaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'tarefa_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Andamento','Selecionar Tipo de Andamento');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='tarefa_cadastrar'){
        if (isset($_GET['id_tarefa'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tarefa']);
        }
      }
      break;

    case 'tarefa_configurar_historico':
      $strTitulo = 'Configuração do Histórico';
      
      $arrObjTarefaDTO = array();
      if ($_POST['hdnCompletosItens']!=''){
        $arrItens = explode(',',$_POST['hdnCompletosItens']);
        foreach($arrItens as $item){
          $objTarefaDTO = new TarefaDTO();
          $objTarefaDTO->setNumIdTarefa($item);
          $objTarefaDTO->setStrSinHistoricoCompleto('N');
          $objTarefaDTO->setStrSinHistoricoResumido('N');
          $arrObjTarefaDTO[] = $objTarefaDTO;
        }
    
        $arrCompletos = PaginaSEI::getInstance()->getArrStrItensSelecionados('Completos');         
        foreach($arrCompletos as $completo){
          foreach($arrObjTarefaDTO as $objTarefaDTO){
            if ($objTarefaDTO->getNumIdTarefa()==$completo){
              $objTarefaDTO->setStrSinHistoricoCompleto('S');
            }
          }
        }
    
        $arrResumidos = PaginaSEI::getInstance()->getArrStrItensSelecionados('Resumidos');
        foreach($arrResumidos as $resumido){
          foreach($arrObjTarefaDTO as $objTarefaDTO){
            if ($objTarefaDTO->getNumIdTarefa()==$resumido){
              $objTarefaDTO->setStrSinHistoricoResumido('S');
            }
          }
        }
        try{
          $objTarefaRN = new TarefaRN();
          $objTarefaRN->configurarHistorico($arrObjTarefaDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      
      break;

    case 'tarefa_listar':
      $strTitulo = 'Configuração do Histórico';
      break;
              
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $bolAcaoConfigurarHistorico = SessaoSEI::getInstance()->verificarPermissao('tarefa_configurar_historico');
  
  if ($bolAcaoConfigurarHistorico){
    $arrComandos[] = '<input type="button" onclick="configurarHistorico()" name="btnSalvar" id="btnSalvar" value="Salvar" class="infraButton" />';
    $strLinkConfigurarHistorico = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarefa_configurar_historico&acao_origem='.$_GET['acao']);
  }
  
  if ($_GET['acao'] == 'tarefa_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'tarefa_listar' || $_GET['acao'] == 'tarefa_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('tarefa_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarefa_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  /* } */

  $objTarefaDTO = new TarefaDTO();
  $objTarefaDTO->retNumIdTarefa();
  $objTarefaDTO->retStrNome();
  $objTarefaDTO->retStrSinHistoricoResumido();
  $objTarefaDTO->retStrSinHistoricoCompleto();

  PaginaSEI::getInstance()->prepararOrdenacao($objTarefaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objTarefaDTO);

  $objTarefaRN = new TarefaRN();
  $arrObjTarefaDTO = $objTarefaRN->listar($objTarefaDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objTarefaDTO);
  $numRegistros = count($arrObjTarefaDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='tarefa_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tarefa_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tarefa_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tarefa_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tarefa_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tarefa_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('tarefa_desativar');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarefa_excluir&acao_origem='.$_GET['acao']);
    }

    $strResultado = '';

    
      $strSumarioTabela = 'Tabela de Tipos de Andamentos.';
      $strCaptionTabela = 'Tipos de Andamentos';
    

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="13%">'.PaginaSEI::getInstance()->getThCheck('Completo','Completos').'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="13%">'.PaginaSEI::getInstance()->getThCheck('Resumido','Resumidos').'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTarefaDTO,'Nome','Nome',$arrObjTarefaDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTarefaDTO[$i]->getNumIdTarefa(),$arrObjTarefaDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTarefaDTO[$i]->getNumIdTarefa(),$arrObjTarefaDTO[$i]->getStrNome(),$arrObjTarefaDTO[$i]->getStrSinHistoricoCompleto(),'Completos').'</td>';
      $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTarefaDTO[$i]->getNumIdTarefa(),$arrObjTarefaDTO[$i]->getStrNome(),$arrObjTarefaDTO[$i]->getStrSinHistoricoResumido(),'Resumidos').'</td>';
      $strResultado .= '<td>'.$arrObjTarefaDTO[$i]->getStrNome().'</td>';
      /*$strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTarefaDTO[$i]->getNumIdTarefa());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarefa_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tarefa='.$arrObjTarefaDTO[$i]->getNumIdTarefa())).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Tipo de Andamento" alt="Consultar Tipo de Andamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarefa_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tarefa='.$arrObjTarefaDTO[$i]->getNumIdTarefa())).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Tipo de Andamento" alt="Alterar Tipo de Andamento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjTarefaDTO[$i]->getNumIdTarefa();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjTarefaDTO[$i]->getStrNome());
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Tipo de Andamento" alt="Excluir Tipo de Andamento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n"; */
      $strResultado .= "</tr>\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'tarefa_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='tarefa_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Tipo de Andamento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTarefaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTarefaLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Andamento selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Tipos de Andamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTarefaLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTarefaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Tipo de Andamento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTarefaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTarefaLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Andamento selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Tipos de Andamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTarefaLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTarefaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Tipo de Andamento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTarefaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTarefaLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Andamento selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Tipos de Andamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTarefaLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTarefaLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoConfigurarHistorico){ ?>
function configurarHistorico(){
  document.getElementById('hdnCompletosItemId').value='';
  document.getElementById('hdnResumidosItemId').value='';
  document.getElementById('frmTarefaLista').action='<?=$strLinkConfigurarHistorico?>';
  document.getElementById('frmTarefaLista').submit();
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTarefaLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>