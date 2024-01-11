<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array(''));

  $bolHabilitado = ConfiguracaoSEI::getInstance()->getValor('Federacao','Habilitado',false,false);

  switch($_GET['acao']){

    case 'instalacao_federacao_liberar':
      try{
        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($_POST['hdnInfraItemId']);
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoRN->liberarRegistro($objInstalacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'instalacao_federacao_bloquear':
      try{
        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($_POST['hdnInfraItemId']);
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoRN->bloquearRegistro($objInstalacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'instalacao_federacao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjInstalacaoFederacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
          $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($arrStrIds[$i]);
          $arrObjInstalacaoFederacaoDTO[] = $objInstalacaoFederacaoDTO;
        }
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoRN->desativar($arrObjInstalacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'instalacao_federacao_reativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjInstalacaoFederacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
          $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($arrStrIds[$i]);
          $arrObjInstalacaoFederacaoDTO[] = $objInstalacaoFederacaoDTO;
        }
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoRN->reativar($arrObjInstalacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'instalacao_federacao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjInstalacaoFederacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
          $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($arrStrIds[$i]);
          $arrObjInstalacaoFederacaoDTO[] = $objInstalacaoFederacaoDTO;
        }
        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
        $objInstalacaoFederacaoRN->excluir($arrObjInstalacaoFederacaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'instalacao_federacao_listar':
      $strTitulo = 'Instalações do SEI Federação';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
  $strSiglaInstalacaoLocal = $objInstalacaoFederacaoRN->obterSiglaInstalacaoLocal();
  $dblCnpjInstalacaoLocal = $objInstalacaoFederacaoRN->obterCnpjInstalacaoLocal();
  $strDescricaoInstalacaoLocal = $objInstalacaoFederacaoRN->obterDescricaoInstalacaoLocal();

  $arrComandos[]= '<button type="submit" accesskey="" id="sbmAtualizar" name="sbmAtualizar" value="Atualizar" class="infraButton">Atualizar</button>';

  if ($_GET['acao'] == 'instalacao_federacao_listar'){
    $bolAcaoSolicitarRegistro = SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_cadastrar');
    if ($bolHabilitado && $bolAcaoSolicitarRegistro){
      $arrComandos[] = '<button type="button" id="btnSolicitarRegistro" value="Enviar Solicitação de Registro" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton">Enviar Solicitação de Registro</button>';
    }
  }

  $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
  $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
  $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
  $objInstalacaoFederacaoDTO->retStrSigla();
  $objInstalacaoFederacaoDTO->retStrDescricao();
  $objInstalacaoFederacaoDTO->retDblCnpj();
  $objInstalacaoFederacaoDTO->retStrEndereco();
  //$objInstalacaoFederacaoDTO->retStrSenha();
  $objInstalacaoFederacaoDTO->retStrStaTipo();
  $objInstalacaoFederacaoDTO->retStrDescricaoTipo();
  $objInstalacaoFederacaoDTO->retStrStaEstado();
  $objInstalacaoFederacaoDTO->retStrDescricaoEstado();
  $objInstalacaoFederacaoDTO->retStrSinAtivo();
  $objInstalacaoFederacaoDTO->setStrStaTipo(InstalacaoFederacaoRN::$TI_LOCAL, InfraDTO::$OPER_DIFERENTE);

  $objInstalacaoFederacaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

  $arrObjInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->listar($objInstalacaoFederacaoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objInstalacaoFederacaoDTO);
  $numRegistros = count($arrObjInstalacaoFederacaoDTO);

  if ($numRegistros > 0){

    $bolCheck = true;

    $bolAcaoAlterar = $bolHabilitado && SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_alterar');
    $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_desativar');
    $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_reativar');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_excluir');
    $bolAcaoVerificarConexao = SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_verificar_conexao');
    $bolAcaoLiberar = $bolHabilitado && SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_liberar');
    $bolAcaoBloquear = $bolHabilitado && SessaoSEI::getInstance()->verificarPermissao('instalacao_federacao_bloquear');
    $bolAcaoHistorico = SessaoSEI::getInstance()->verificarPermissao('andamento_instalacao_listar');

    if ($bolAcaoLiberar){
      $strLinkLiberar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_liberar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoBloquear){
      $strLinkBloquear = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_bloquear&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Instalações.';
    $strCaptionTabela = 'Instalações';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }

    $strResultado .= '<th class="infraTh">Instalação</th>'."\n";
    $strResultado .= '<th class="infraTh">CNPJ</th>'."\n";
    $strResultado .= '<th class="infraTh">Endereço</th>'."\n";
    $strResultado .= '<th class="infraTh" >Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" >Situação</th>'."\n";
    $strResultado .= '<th class="infraTh" >Conexão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();


    for($i = 0;$i < $numRegistros; $i++){

      $strId = $arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao();
      $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjInstalacaoFederacaoDTO[$i]->getStrSigla());


      if ($arrObjInstalacaoFederacaoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<tr id="tr'.$strId.'" class="trVermelha">';
      }else{
        $strCssTr = ($strCssTr=='infraTrClara')?'infraTrEscura':'infraTrClara';
        $strResultado .= '<tr id="tr'.$strId.'" class="'.$strCssTr.'">';
      }


      if ($bolCheck){
        $strResultado .= '<td valign="middle">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao(),$arrObjInstalacaoFederacaoDTO[$i]->getStrSigla()).'</td>';
      }
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjInstalacaoFederacaoDTO[$i]->getStrDescricao()).'" title="'.PaginaSEI::tratarHTML($arrObjInstalacaoFederacaoDTO[$i]->getStrDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjInstalacaoFederacaoDTO[$i]->getStrSigla()).'</a></td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($arrObjInstalacaoFederacaoDTO[$i]->getDblCnpj())).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjInstalacaoFederacaoDTO[$i]->getStrEndereco()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjInstalacaoFederacaoDTO[$i]->getStrDescricaoTipo()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjInstalacaoFederacaoDTO[$i]->getStrDescricaoEstado()).'</td>';
      $strResultado .= '<td align="center" id="tdConexao'.$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao().'">&nbsp;</td>';

      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());

      //if ($bolAcaoConsultar){
      //  $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_instalacao_federacao='.$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Instalação do SEI Federação" alt="Consultar Instalação do SEI Federação" class="infraImg" /></a>&nbsp;';
      //}

      if ($bolAcaoSolicitarRegistro &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrSinAtivo()=='S' &&
          ($arrObjInstalacaoFederacaoDTO[$i]->getStrStaTipo() == InstalacaoFederacaoRN::$TI_REPLICADA) &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrStaEstado() == InstalacaoFederacaoRN::$EI_ANALISE) {
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&endereco_instalacao='.$arrObjInstalacaoFederacaoDTO[$i]->getStrEndereco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::FEDERACAO_SOLICITAR_REGISTRO.'" title="Solicitar Registro" alt="Solicitar Registro" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoLiberar &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrSinAtivo()=='S' &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrStaTipo() == InstalacaoFederacaoRN::$TI_RECEBIDA &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrStaEstado() != InstalacaoFederacaoRN::$EI_LIBERADA) {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoLiberarRegistro(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::FEDERACAO_LIBERAR.'" title="Liberar Instalação" alt="Liberar Instalação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoBloquear &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrSinAtivo()=='S' &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrStaTipo() == InstalacaoFederacaoRN::$TI_RECEBIDA &&
          $arrObjInstalacaoFederacaoDTO[$i]->getStrStaEstado() != InstalacaoFederacaoRN::$EI_BLOQUEADA) {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoBloquearRegistro(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::FEDERACAO_BLOQUEAR.'" title="Bloquear Instalação" alt="Bloquear Instalação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=instalacao_federacao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_instalacao_federacao='.$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Instalação" alt="Alterar Instalação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoHistorico){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=andamento_instalacao_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_instalacao_federacao='.$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::HISTORICO.'" title="Histórico da Instalação" alt="Histórico da Instalação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar && $arrObjInstalacaoFederacaoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Instalação" alt="Desativar Instalação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjInstalacaoFederacaoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Instalação" alt="Reativar Instalação" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Instalação" alt="Excluir Instalação" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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

#lblCnpj {position:absolute;left:0%;top:0%;width:20%;}
#txtCnpj {position:absolute;left:0%;top:35%;width:20%;}

#lblSigla {position:absolute;left:22%;top:0%;width:20%;}
#txtSigla {position:absolute;left:22%;top:35%;width:20%;}

#lblDescricao {position:absolute;left:44%;top:0%;width:55%;}
#txtDescricao {position:absolute;left:44%;top:35%;width:55%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>


function inicializar(){

  //document.getElementById('btnFechar').focus();

  infraEfeitoTabelas(true);

  <?if ($bolAcaoVerificarConexao){?>
  verificarConexaoInstituicoes();
  <?}?>

}

<? if ($bolAcaoLiberar){ ?>
function acaoLiberarRegistro(id,desc){
  if (confirm("Confirma liberação da Instalação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkLiberar?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoBloquear){ ?>
function acaoBloquearRegistro(id,desc){
  if (confirm("Confirma bloqueio da Instalação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkBloquear?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Instalação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Instalação selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Instalações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Instalação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Instalação selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Instalações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Instalação \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Instalação selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Instalações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInstalacaoFederacaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInstalacaoFederacaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoVerificarConexao){ ?>

function verificarConexaoInstituicoes(){

  var infraItens = document.getElementById('hdnInfraItens').value;

  if (infraTrim(infraItens)!='') {
    infraItens = infraItens.split(",");
    for (i = 0; i<infraItens.length; i++) {
      document.getElementById('tdConexao'+infraItens[i]).innerHTML = '<img src="<?=PaginaSEI::getInstance()->getIconeAguardar()?>" />';
    }
    for (i = 0; i<infraItens.length; i++) {
      setTimeout('verificarConexao(\''+infraItens[i]+'\')',1000);
    }
  }
}

function verificarConexaoInstalacao(id){
  infraLimparFormatarTrAcessada(document.getElementById('tr'+id));
  document.getElementById('tdConexao'+id).innerHTML = '<img src="<?=PaginaSEI::getInstance()->getIconeAguardar()?>" />';
  setTimeout('verificarConexao(\''+id+'\')',1000);
}


function verificarConexao(id){

  var objAjaxConexao = new infraAjaxComplementar(null,'<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=instalacao_federacao_verificar_conexao')?>');
  objAjaxConexao.mostrarAviso = false;

  objAjaxConexao.prepararExecucao = function(){
    return 'IdInstalacaoFederacao='+id;
  };

  objAjaxConexao.processarResultado = function (arr){
    if (arr!=null){
      document.getElementById('tdConexao'+id).innerHTML = arr['Resultado'];
    }
  };

  objAjaxConexao.executar();
}
<? } ?>


  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInstalacaoFederacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

    <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelOpcional">Sigla:</label>
    <input type="text" id="txtSigla" name="txtSigla" value="<?=PaginaSEI::tratarHTML($strSiglaInstalacaoLocal)?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblCnpj" for="txtCnpj" accesskey="" class="infraLabelOpcional">CNPJ:</label>
    <input type="text" id="txtCnpj" name="txtCnpj" value="<?=PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($dblCnpjInstalacaoLocal))?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" value="<?=PaginaSEI::tratarHTML($strDescricaoInstalacaoLocal)?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  if (!$bolHabilitado) {
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblDesabilitado" class="infraLabelObrigatorio">O SEI Federação está desabilitado nesta instalação.</label>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
  }

  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
