<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/07/2013 - criado por mkr@trf4.jus.br
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

  PaginaSEI::getInstance()->prepararSelecao('veiculo_publicacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  
  if (isset($_GET['id_veiculo_publicacao_atual'])){
    $strParametros .= '&id_veiculo_publicacao_atual='.$_GET['id_veiculo_publicacao_atual'];
  }

  switch($_GET['acao']){
    case 'veiculo_publicacao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVeiculoPublicacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
          $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($arrStrIds[$i]);
          $arrObjVeiculoPublicacaoDTO[] = $objVeiculoPublicacaoDTO;
        }
        $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
        $objVeiculoPublicacaoRN->excluir($arrObjVeiculoPublicacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'veiculo_publicacao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVeiculoPublicacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
          $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($arrStrIds[$i]);
          $arrObjVeiculoPublicacaoDTO[] = $objVeiculoPublicacaoDTO;
        }
        $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
        $objVeiculoPublicacaoRN->desativar($arrObjVeiculoPublicacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'veiculo_publicacao_reativar':
      $strTitulo = 'Reativar Veículos de Publicação';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjVeiculoPublicacaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
            $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($arrStrIds[$i]);
            $arrObjVeiculoPublicacaoDTO[] = $objVeiculoPublicacaoDTO;
          }
          $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
          $objVeiculoPublicacaoRN->reativar($arrObjVeiculoPublicacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;


    case 'veiculo_publicacao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Veículo de Publicação','Selecionar Veículos de Publicação');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='veiculo_publicacao_cadastrar'){
        if (isset($_GET['id_veiculo_publicacao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_veiculo_publicacao']);
        }
      }
      break;

    case 'veiculo_publicacao_listar':
      $strTitulo = 'Veículos de Publicação';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'veiculo_publicacao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'veiculo_publicacao_listar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();
  $objVeiculoPublicacaoDTO->retNumIdVeiculoPublicacao();
  $objVeiculoPublicacaoDTO->retStrNome();
  //$objVeiculoPublicacaoDTO->retStrDescricao();
  $objVeiculoPublicacaoDTO->retStrStaTipo();  
  $objVeiculoPublicacaoDTO->retStrSinFonteFeriados();
  //$objVeiculoPublicacaoDTO->retStrSinPermiteExtraordinaria();
  //$objVeiculoPublicacaoDTO->retStrWebService();


  if ($_GET['acao'] == 'veiculo_publicacao_reativar'){
    //Lista somente inativos
    $objVeiculoPublicacaoDTO->setBolExclusaoLogica(false);
    $objVeiculoPublicacaoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objVeiculoPublicacaoDTO, 'IdVeiculoPublicacao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objVeiculoPublicacaoDTO);

  $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
  $arrObjVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->listar($objVeiculoPublicacaoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objVeiculoPublicacaoDTO);
  $numRegistros = count($arrObjVeiculoPublicacaoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='veiculo_publicacao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='veiculo_publicacao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('veiculo_publicacao_desativar');
    }

    $arrObjVeiculoPublicacaoAPI = array();
    foreach($arrObjVeiculoPublicacaoDTO as $objVeiculoPublicacaoDTO){
      $objVeiculoPublicacaoAPI = new VeiculoPublicacaoAPI();
      $objVeiculoPublicacaoAPI->setIdVeiculoPublicacao($objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao());
      $objVeiculoPublicacaoAPI->setNome($objVeiculoPublicacaoDTO->getStrNome());
      $arrObjVeiculoPublicacaoAPI[] = $objVeiculoPublicacaoAPI;
    }

    $arrIntegracaoAcoesVeiculo = array();
    foreach ($SEI_MODULOS as $seiModulo) {
      if (($arr = $seiModulo->executar('montarAcaoVeiculoPublicacao', $arrObjVeiculoPublicacaoAPI)) != null){
        foreach($arr as $key => $arrAcoes) {
          $arrIntegracaoAcoesVeiculo[$key] = array_merge($arrIntegracaoAcoesVeiculo[$key] ?: array(), $arrAcoes);
        }
      }
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='veiculo_publicacao_reativar'){
      $strSumarioTabela = 'Tabela de Veículos de Publicação.';
      $strCaptionTabela = 'Veículos de Publicação';
    }else{
      $strSumarioTabela = 'Tabela de Veículos de Publicação Inativos.';
      $strCaptionTabela = 'Veículos de Publicação Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    
    if (!PaginaSEI::getInstance()->isBolPaginaSelecao()){
      $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'ID','IdVeiculoPublicacao',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    }
    
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'Nome','Nome',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'Descrição','Descricao',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'Tipo','StaTipo',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'Fonte&nbsp;de&nbsp;Feriados','SinFonteFeriados',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'Permite Edição Extraordinária','SinPermiteExtraordinaria',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objVeiculoPublicacaoDTO,'Web Service','WebService',$arrObjVeiculoPublicacaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
    $arrObjTipoDTO = InfraArray::indexarArrInfraDTO($objVeiculoPublicacaoRN->listarValoresTipo(),'StaTipo');
    
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao(),$arrObjVeiculoPublicacaoDTO[$i]->getStrNome()).'</td>';
      }
      
      if (!PaginaSEI::getInstance()->isBolPaginaSelecao()){
        $strResultado .= '<td align="center">'.$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao().'</td>';
      }
      
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjVeiculoPublicacaoDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.$arrObjVeiculoPublicacaoDTO[$i]->getStrDescricao().'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjTipoDTO[$arrObjVeiculoPublicacaoDTO[$i]->getStrStaTipo()]->getStrDescricao()).'</td>';
      $strResultado .= '<td align="center">'.($arrObjVeiculoPublicacaoDTO[$i]->getStrSinFonteFeriados()=='S'?'X':'&nbsp;').'</td>';
      //$strResultado .= '<td>'.$arrObjVeiculoPublicacaoDTO[$i]->getStrSinPermiteExtraordinaria().'</td>';
      //$strResultado .= '<td>'.$arrObjVeiculoPublicacaoDTO[$i]->getStrWebService().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao());

      if (is_array($arrIntegracaoAcoesVeiculo) && isset($arrIntegracaoAcoesVeiculo[$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao()])) {
        foreach ($arrIntegracaoAcoesVeiculo[$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao()] as $strIconeIntegracao) {
          $strResultado .= '&nbsp;' . $strIconeIntegracao;
        }
      }

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_veiculo_publicacao='.$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Veículo de Publicação" alt="Consultar Veículo de Publicação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_veiculo_publicacao='.$arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Veículo de Publicação" alt="Alterar Veículo de Publicação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjVeiculoPublicacaoDTO[$i]->getNumIdVeiculoPublicacao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjVeiculoPublicacaoDTO[$i]->getStrNome());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Veículo de Publicação" alt="Desativar Veículo de Publicação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Veículo de Publicação" alt="Reativar Veículo de Publicação" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Veículo de Publicação" alt="Excluir Veículo de Publicação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'veiculo_publicacao_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='veiculo_publicacao_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Veículo de Publicação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVeiculoPublicacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVeiculoPublicacaoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Veículo de Publicação selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Veículos de Publicação selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVeiculoPublicacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVeiculoPublicacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Veículo de Publicação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVeiculoPublicacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVeiculoPublicacaoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Veículo de Publicação selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Veículos de Publicação selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVeiculoPublicacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVeiculoPublicacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Veículo de Publicação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVeiculoPublicacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVeiculoPublicacaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Veículo de Publicação selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Veículos de Publicação selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVeiculoPublicacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVeiculoPublicacaoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVeiculoPublicacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>