<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->prepararSelecao('replicacao_federacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $bolHabilitado = ConfiguracaoSEI::getInstance()->getValor('Federacao','Habilitado',false,false);

  switch($_GET['acao']){

    case 'replicacao_federacao_replicar':

      try{
        $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
        $objReplicacaoFederacaoRN->replicar();
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

      break;

    case 'replicacao_federacao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjReplicacaoFederacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
          $objReplicacaoFederacaoDTO->setStrIdReplicacaoFederacao($arrStrIds[$i]);
          $arrObjReplicacaoFederacaoDTO[] = $objReplicacaoFederacaoDTO;
        }
        $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
        $objReplicacaoFederacaoRN->excluir($arrObjReplicacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'replicacao_federacao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjReplicacaoFederacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
          $objReplicacaoFederacaoDTO->setStrIdReplicacaoFederacao($arrStrIds[$i]);
          $arrObjReplicacaoFederacaoDTO[] = $objReplicacaoFederacaoDTO;
        }
        $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
        $objReplicacaoFederacaoRN->desativar($arrObjReplicacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'replicacao_federacao_reativar':
      $strTitulo = 'Reativar Replicações do SEI Federação';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjReplicacaoFederacaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
            $objReplicacaoFederacaoDTO->setStrIdReplicacaoFederacao($arrStrIds[$i]);
            $arrObjReplicacaoFederacaoDTO[] = $objReplicacaoFederacaoDTO;
          }
          $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
          $objReplicacaoFederacaoRN->reativar($arrObjReplicacaoFederacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'replicacao_federacao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Replicação do SEI Federação','Selecionar Replicações do SEI Federação');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='replicacao_federacao_cadastrar'){
        if (isset($_GET['id_replicacao_federacao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_replicacao_federacao']);
        }
      }
      break;

    case 'replicacao_federacao_listar':
      $strTitulo = 'Replicações para o SEI Federação';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'replicacao_federacao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /*
  if ($_GET['acao'] == 'replicacao_federacao_listar' || $_GET['acao'] == 'replicacao_federacao_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('replicacao_federacao_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  }
  */

  $bolAcaoReplicar = SessaoSEI::getInstance()->verificarPermissao('replicacao_federacao_replicar');
  if ($bolHabilitado && $bolAcaoReplicar){
    $arrComandos[] = '<button type="button" accesskey="" id="btnReplicar" value="Replicar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_replicar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton">Replicar</button>';
  }


  $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
  $objReplicacaoFederacaoDTO->setBolExclusaoLogica(false);
  $objReplicacaoFederacaoDTO->retStrIdReplicacaoFederacao();
  $objReplicacaoFederacaoDTO->retStrIdInstalacaoFederacao();
  $objReplicacaoFederacaoDTO->retStrSiglaInstalacaoFederacao();
  $objReplicacaoFederacaoDTO->retStrDescricaoInstalacaoFederacao();
  $objReplicacaoFederacaoDTO->retStrIdProtocoloFederacao();
  $objReplicacaoFederacaoDTO->retStrProtocoloFormatadoFederacao();
  $objReplicacaoFederacaoDTO->retNumStaTipo();
  $objReplicacaoFederacaoDTO->retDthCadastro();
  $objReplicacaoFederacaoDTO->retDthReplicacao();
  $objReplicacaoFederacaoDTO->retNumTentativa();
  $objReplicacaoFederacaoDTO->retStrErro();

/*
  if ($_GET['acao'] == 'replicacao_federacao_reativar'){
    //Lista somente inativos
    $objReplicacaoFederacaoDTO->setBolExclusaoLogica(false);
    $objReplicacaoFederacaoDTO->setStrSinAtivo('N');
  }
 */
  PaginaSEI::getInstance()->prepararOrdenacao($objReplicacaoFederacaoDTO, 'Cadastro', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objReplicacaoFederacaoDTO, 100);

  $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
  $arrObjReplicacaoFederacaoDTO = $objReplicacaoFederacaoRN->listar($objReplicacaoFederacaoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objReplicacaoFederacaoDTO);
  $numRegistros = count($arrObjReplicacaoFederacaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='replicacao_federacao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='replicacao_federacao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('replicacao_federacao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('replicacao_federacao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('replicacao_federacao_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='replicacao_federacao_reativar'){ */
      $strSumarioTabela = 'Tabela de Replicações Pendentes.';
      $strCaptionTabela = 'Replicações Pendentes';
    /* }else{
      $strSumarioTabela = 'Tabela de Replicações do SEI Federação Inativas.';
      $strCaptionTabela = 'Replicações do SEI Federação Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReplicacaoFederacaoDTO,'Instalação','SiglaInstalacaoFederacao',$arrObjReplicacaoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReplicacaoFederacaoDTO,'Protocolo','ProtocoloFormatadoFederacao',$arrObjReplicacaoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReplicacaoFederacaoDTO,'Tipo','StaTipo',$arrObjReplicacaoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReplicacaoFederacaoDTO,'Cadastramento','Cadastro',$arrObjReplicacaoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReplicacaoFederacaoDTO,'Replicação','Replicacao',$arrObjReplicacaoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objReplicacaoFederacaoDTO,'Tentativas','Tentativa',$arrObjReplicacaoFederacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Erro</th>'."\n";
    //$strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $arrObjTipoReplicacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($objReplicacaoFederacaoRN->listarValoresTipo(),'StaTipo');

    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjReplicacaoFederacaoDTO[$i]->getStrIdReplicacaoFederacao(),$arrObjReplicacaoFederacaoDTO[$i]->getDthCadastro()).'</td>';
      }
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getStrDescricaoInstalacaoFederacao()).'" title="'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getStrDescricaoInstalacaoFederacao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getStrSiglaInstalacaoFederacao()).'</a></td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getStrProtocoloFormatadoFederacao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjTipoReplicacaoFederacaoDTO[$arrObjReplicacaoFederacaoDTO[$i]->getNumStaTipo()]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getDthCadastro()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getDthReplicacao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getNumTentativa()).'</td>';
      $strResultado .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjReplicacaoFederacaoDTO[$i]->getStrErro()).'</td>';

      /*
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjReplicacaoFederacaoDTO[$i]->getStrIdReplicacaoFederacao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_replicacao_federacao='.$arrObjReplicacaoFederacaoDTO[$i]->getStrIdReplicacaoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Replicação do SEI Federação" alt="Consultar Replicação do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=replicacao_federacao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_replicacao_federacao='.$arrObjReplicacaoFederacaoDTO[$i]->getStrIdReplicacaoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Replicação do SEI Federação" alt="Alterar Replicação do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjReplicacaoFederacaoDTO[$i]->getStrIdReplicacaoFederacao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjReplicacaoFederacaoDTO[$i]->getDthCadastro());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Replicação do SEI Federação" alt="Desativar Replicação do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Replicação do SEI Federação" alt="Reativar Replicação do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Replicação do SEI Federação" alt="Excluir Replicação do SEI Federação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td>';
      */

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'replicacao_federacao_selecionar'){
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
<?if(0){?><style><?}?>

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='replicacao_federacao_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}


<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmReplicacaoFederacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  if (!$bolHabilitado) {
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblDesabilitado" class="infraLabelObrigatorio">O SEI Federação está desabilitado nesta instalação.</label>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
  }
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
