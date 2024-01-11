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
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('servico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array(''));
  
  $strParametros = '&id_usuario='.$_GET['id_usuario'];

  $objUsuarioDTO = new UsuarioDTO();
  $objUsuarioDTO->retStrSigla();
  $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

  $objUsuarioRN = new UsuarioRN();
  $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);
  
  
  switch($_GET['acao']){
    case 'servico_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjServicoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objServicoDTO = new ServicoDTO();
          $objServicoDTO->setNumIdServico($arrStrIds[$i]);
          $arrObjServicoDTO[] = $objServicoDTO;
        }
        $objServicoRN = new ServicoRN();
        $objServicoRN->excluir($arrObjServicoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;


    case 'servico_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjServicoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objServicoDTO = new ServicoDTO();
          $objServicoDTO->setNumIdServico($arrStrIds[$i]);
          $arrObjServicoDTO[] = $objServicoDTO;
        }
        $objServicoRN = new ServicoRN();
        $objServicoRN->desativar($arrObjServicoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'servico_reativar':
      $strTitulo = 'Reativar Serviços';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjServicoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objServicoDTO = new ServicoDTO();
            $objServicoDTO->setNumIdServico($arrStrIds[$i]);
            $arrObjServicoDTO[] = $objServicoDTO;
          }
          $objServicoRN = new ServicoRN();
          $objServicoRN->reativar($arrObjServicoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
        die;
      } 
      break;


    case 'servico_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Serviço '.$objUsuarioDTO->getStrSigla(),'Selecionar Serviços '.$objUsuarioDTO->getStrSigla());

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='servico_cadastrar'){
        if (isset($_GET['id_servico'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_servico']);
        }
      }
      break;

    case 'servico_listar':
      $strTitulo = 'Serviços '.$objUsuarioDTO->getStrSigla();
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'servico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'servico_listar' || $_GET['acao'] == 'servico_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('servico_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objServicoDTO = new ServicoDTO();
  $objServicoDTO->retNumIdServico();
  $objServicoDTO->retStrIdentificacao();
  $objServicoDTO->retStrChaveAcesso();
  $objServicoDTO->retStrSinServidor();
  $objServicoDTO->retStrSinChaveAcesso();
  //$objServicoDTO->retStrDescricao();
  //$objServicoDTO->retStrSenha();
  //$objServicoDTO->retStrServidor();


  $objServicoDTO->setNumIdUsuario($_GET['id_usuario']);
  
  
  if ($_GET['acao'] == 'servico_reativar'){
    //Lista somente inativos
    $objServicoDTO->setBolExclusaoLogica(false);
    $objServicoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objServicoDTO, 'Identificacao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objServicoDTO);

  $objServicoRN = new ServicoRN();
  $arrObjServicoDTO = $objServicoRN->listar($objServicoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objServicoDTO);
  $numRegistros = count($arrObjServicoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='servico_selecionar'){
      //$bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('servico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('servico_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      //$bolAcaoDesativar = false;
      $bolAcaoOperacaoServicoListar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='servico_reativar'){
      //$bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('servico_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('servico_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('servico_excluir');
      //$bolAcaoDesativar = false;
      $bolAcaoOperacaoServicoListar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('servico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('servico_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('servico_excluir');
      $bolAcaoDesativar = false; //SessaoSEI::getInstance()->verificarPermissao('servico_desativar');
      $bolAcaoOperacaoServicoListar = SessaoSEI::getInstance()->verificarPermissao('operacao_servico_listar');
      $bolAcaoGerarChave = SessaoSEI::getInstance()->verificarPermissao('servico_gerar_chave_acesso');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_desativar&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim'.$strParametros);
    }
    */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_excluir&acao_origem='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='servico_reativar'){
      $strSumarioTabela = 'Tabela de Serviços.';
      $strCaptionTabela = 'Serviços';
    }else{
      $strSumarioTabela = 'Tabela de Serviços Inativos.';
      $strCaptionTabela = 'Serviços Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objServicoDTO,'Identificação','Identificacao',$arrObjServicoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objServicoDTO,'Descrição','Descricao',$arrObjServicoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objServicoDTO,'Senha','Senha',$arrObjServicoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objServicoDTO,'Servidor','Servidor',$arrObjServicoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Autenticação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    for($i = 0;$i < $numRegistros; $i++){

      $strId = $arrObjServicoDTO[$i]->getNumIdServico();
      $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjServicoDTO[$i]->getStrIdentificacao());

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjServicoDTO[$i]->getNumIdServico(),$arrObjServicoDTO[$i]->getStrIdentificacao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjServicoDTO[$i]->getStrIdentificacao()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjServicoDTO[$i]->getStrDescricao()).'</td>';

      $strResultado .= '<td align="center">';

      if ($arrObjServicoDTO[$i]->getStrSinServidor()=='S' && $arrObjServicoDTO[$i]->getStrSinChaveAcesso()=='S'){
        $strResultado .= 'Endereço ou Chave de Acesso';
      }else if ($arrObjServicoDTO[$i]->getStrSinServidor()=='S' && $arrObjServicoDTO[$i]->getStrSinChaveAcesso()=='N'){
        $strResultado .= 'Endereço';
      }else if ($arrObjServicoDTO[$i]->getStrSinServidor()=='N' && $arrObjServicoDTO[$i]->getStrSinChaveAcesso()=='S'){
        $strResultado .= 'Chave de Acesso';
      }else{
        $strResultado .= '&nbsp;';
      }

      $strResultado .= '</td>';

      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjServicoDTO[$i]->getNumIdServico());

      if ($bolAcaoOperacaoServicoListar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=operacao_servico_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_servico='.$arrObjServicoDTO[$i]->getNumIdServico().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::VALORES.'" title="Operações" alt="Operações" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoGerarChave){
        $strIconeChave = Icone::SISTEMA_SERVICO_SEM_CHAVE;
        if ($arrObjServicoDTO[$i]->getStrChaveAcesso() != null){
          $strIconeChave = Icone::SISTEMA_SERVICO_COM_CHAVE;
        }
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);gerarChave(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_gerar_chave_acesso&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_servico='.$arrObjServicoDTO[$i]->getNumIdServico().$strParametros).'\')" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img id="imgChaveAcesso'.$arrObjServicoDTO[$i]->getNumIdServico().'" src="'.$strIconeChave.'" title="Gerar Chave de Acesso" alt="Gerar Chave de Acesso" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_servico='.$arrObjServicoDTO[$i]->getNumIdServico().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Serviço" alt="Consultar Serviço" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_servico='.$arrObjServicoDTO[$i]->getNumIdServico().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Serviço" alt="Alterar Serviço" class="infraImg" /></a>&nbsp;';
      }


      /*
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Serviço" alt="Desativar Serviço" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Serviço" alt="Reativar Serviço" class="infraImg" /></a>&nbsp;';
      }
      */

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Serviço" alt="Excluir Serviço" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'servico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_usuario'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
  if ('<?=$_GET['acao']?>'=='servico_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Serviço \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmServicoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmServicoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Serviço selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Serviços selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmServicoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Serviço \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmServicoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmServicoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Serviço selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Serviços selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmServicoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Serviço \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmServicoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmServicoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Serviço selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Serviços selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmServicoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoGerarChave){ ?>
function gerarChave(link){
  if (confirm('Confirma geração de uma nova chave de acesso para o serviço?')){
    infraAbrirJanela(link,'janelaGerarChave',700,200,'location=0,status=1,resizable=1,scrollbars=1');
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmServicoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
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