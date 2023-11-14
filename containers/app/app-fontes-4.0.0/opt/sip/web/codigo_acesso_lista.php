<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/06/2018 - criado por mga
*
* Versão do Gerador de Código: 1.41.0
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->setArrParametrosRepasseLink(array('pagina_simples','id_usuario'));

  PaginaSip::getInstance()->prepararSelecao('codigo_acesso_selecionar');

  if (isset($_GET['pagina_simples'])){
    PaginaSip::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistemaCodigoAcesso', 'selSistemaCodigoAcesso', 'selOrgaoUsuarioCodigoAcesso', 'hdnIdUsuarioCodigoAcesso', 'txtUsuarioCodigoAcesso','hdnSiglaUsuarioCodigoAcesso', 'hdnNomeUsuarioCodigoAcesso', 'selEstadoCodigoAcesso'));

  switch($_GET['acao']){
    case 'codigo_acesso_excluir':
      try{
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjCodigoAcessoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCodigoAcessoDTO = new CodigoAcessoDTO();
          $objCodigoAcessoDTO->setStrIdCodigoAcesso($arrStrIds[$i]);
          $arrObjCodigoAcessoDTO[] = $objCodigoAcessoDTO;
        }
        $objCodigoAcessoRN = new CodigoAcessoRN();
        $objCodigoAcessoRN->excluir($arrObjCodigoAcessoDTO);
        PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSip::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'codigo_acesso_desativar':
      try{
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjCodigoAcessoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objCodigoAcessoDTO = new CodigoAcessoDTO();
          $objCodigoAcessoDTO->setStrIdCodigoAcesso($arrStrIds[$i]);
          $objCodigoAcessoDTO->setNumIdUsuarioDesativacao(SessaoSip::getInstance()->getNumIdUsuario());
          $arrObjCodigoAcessoDTO[] = $objCodigoAcessoDTO;
        }
        $objCodigoAcessoRN = new CodigoAcessoRN();
        $objCodigoAcessoRN->desativar($arrObjCodigoAcessoDTO);
        PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSip::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'codigo_acesso_reativar':
      $strTitulo = 'Reativar Autenticação em 2 Fatores';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjCodigoAcessoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objCodigoAcessoDTO = new CodigoAcessoDTO();
            $objCodigoAcessoDTO->setStrIdCodigoAcesso($arrStrIds[$i]);
            $arrObjCodigoAcessoDTO[] = $objCodigoAcessoDTO;
          }
          $objCodigoAcessoRN = new CodigoAcessoRN();
          $objCodigoAcessoRN->reativar($arrObjCodigoAcessoDTO);
          PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'codigo_acesso_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Autenticação em 2 Fatores','Selecionar Autenticação em 2 Fatores');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='codigo_acesso_cadastrar'){
        if (isset($_GET['id_codigo_acesso'])){
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_codigo_acesso']);
        }
      }
      break;

    case 'codigo_acesso_listar':
      $strTitulo = '' .
          'Habilitações de Autenticação em 2 Fatores';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';

  if ($_GET['acao'] == 'codigo_acesso_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'codigo_acesso_listar' || $_GET['acao'] == 'codigo_acesso_selecionar'){
    $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
  }

  $objCodigoAcessoDTO = new CodigoAcessoDTO();
  $objCodigoAcessoDTO->retStrIdCodigoAcesso();
  $objCodigoAcessoDTO->retStrSiglaUsuario();
  $objCodigoAcessoDTO->retStrNomeUsuario();
  $objCodigoAcessoDTO->retStrSiglaOrgaoUsuario();
  $objCodigoAcessoDTO->retStrDescricaoOrgaoUsuario();
  $objCodigoAcessoDTO->retStrSiglaSistema();
  $objCodigoAcessoDTO->retStrDescricaoSistema();
  $objCodigoAcessoDTO->retStrSiglaOrgaoSistema();
  $objCodigoAcessoDTO->retStrDescricaoOrgaoSistema();
  //$objCodigoAcessoDTO->retStrCodigo();
  //$objCodigoAcessoDTO->retStrChave();
  //$objCodigoAcessoDTO->retStrUserAgent();
  //$objCodigoAcessoDTO->retStrHttpClientIp();
  //$objCodigoAcessoDTO->retStrHttpXForwardedFor();
  //$objCodigoAcessoDTO->retStrRemoteAddr();
  $objCodigoAcessoDTO->retDthGeracao();
  $objCodigoAcessoDTO->retDthAtivacao();
  $objCodigoAcessoDTO->retDthAcesso();
  $objCodigoAcessoDTO->retStrEmail();
  $objCodigoAcessoDTO->retStrSinAtivo();

  $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistemaCodigoAcesso');
  if ($numIdOrgaoSistema!==''){
    $objCodigoAcessoDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
  }

  $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistemaCodigoAcesso');
  if ($numIdSistema!==''){
    $objCodigoAcessoDTO->setNumIdSistema($numIdSistema);
  }

  $numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuarioCodigoAcesso');
  if ($numIdOrgaoUsuario!==''){
    $objCodigoAcessoDTO->setNumIdOrgaoUsuario($numIdOrgaoUsuario);
  }

  $strEstado = PaginaSip::getInstance()->recuperarCampo('selEstadoCodigoAcesso', 'A');
  if ($strEstado=='null'){
    $objCodigoAcessoDTO->setStrIdCodigoAcesso(null);
  }else if ($strEstado == 'A'){
    $objCodigoAcessoDTO->setDthAtivacao(null, InfraDTO::$OPER_DIFERENTE);
  }else if ($strEstado == 'D'){
    $objCodigoAcessoDTO->setBolExclusaoLogica(false);
    $objCodigoAcessoDTO->setDthAtivacao(null, InfraDTO::$OPER_DIFERENTE);
    $objCodigoAcessoDTO->setStrSinAtivo('N');
  }else if ($strEstado == 'I'){
    $objCodigoAcessoDTO->setBolExclusaoLogica(false);
    $objCodigoAcessoDTO->setDthAtivacao(null);
    $objCodigoAcessoDTO->setStrSinAtivo('N');
  }else{
    $objCodigoAcessoDTO->setBolExclusaoLogica(false);
  }

  $strDesabilitarFiltroUsuario = '';

  if (isset($_GET['id_usuario'])){

    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->setBolExclusaoLogica(false);
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->retStrSigla();
    $objUsuarioDTO->retStrNome();
    $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

    $objUsuarioRN = new UsuarioRN();
    $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

    $numIdUsuario = $objUsuarioDTO->getNumIdUsuario();
    $strSiglaUsuario = $objUsuarioDTO->getStrSigla();
    $strNomeUsuario = $objUsuarioDTO->getStrNome();

    $strDesabilitarFiltroUsuario = 'disabled="disabled"';

  }else{

    $numIdUsuario = PaginaSip::getInstance()->recuperarCampo('hdnIdUsuarioCodigoAcesso');
    $strSiglaUsuario = PaginaSip::getInstance()->recuperarCampo('hdnSiglaUsuarioCodigoAcesso');
    $strNomeUsuario = PaginaSip::getInstance()->recuperarCampo('hdnNomeUsuarioCodigoAcesso');

  }

  if ($numIdUsuario!==''){
    $objCodigoAcessoDTO->setNumIdUsuario($numIdUsuario);
  }

  if ($_GET['acao'] == 'codigo_acesso_reativar'){
    //Lista somente inativos
    $objCodigoAcessoDTO->setBolExclusaoLogica(false);
    $objCodigoAcessoDTO->setStrSinAtivo('N');
  }


  PaginaSip::getInstance()->prepararOrdenacao($objCodigoAcessoDTO, 'Geracao', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSip::getInstance()->prepararPaginacao($objCodigoAcessoDTO);

  $objCodigoAcessoRN = new CodigoAcessoRN();
  $arrObjCodigoAcessoDTO = $objCodigoAcessoRN->listar($objCodigoAcessoDTO);

  PaginaSip::getInstance()->processarPaginacao($objCodigoAcessoDTO);
  $numRegistros = count($arrObjCodigoAcessoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='codigo_acesso_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoLoginListar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='codigo_acesso_reativar'){
      $bolAcaoReativar = false; //SessaoSip::getInstance()->verificarPermissao('codigo_acesso_reativar');
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_excluir');
      $bolAcaoDesativar = false;
      $bolAcaoLoginListar = false;
    }else{
      $bolAcaoReativar = false; //SessaoSip::getInstance()->verificarPermissao('codigo_acesso_reativar');
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_desativar');
      $bolAcaoLoginListar = SessaoSip::getInstance()->verificarPermissao('login_listar');
    }


    if ($bolAcaoDesativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    /*
    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_excluir&acao_origem='.$_GET['acao']);
    }
    */

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    if ($_GET['acao']!='codigo_acesso_reativar'){
      $strSumarioTabela = 'Tabela de Habilitações de Autenticação em 2 Fatores.';
      $strCaptionTabela = 'Habilitações';
    }else{
      $strSumarioTabela = 'Tabela de Habilitações de Autenticação em 2 Fatores.';
      $strCaptionTabela = 'Habilitações Inativas';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSip::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Sistema','SiglaSistema',$arrObjCodigoAcessoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Usuário','SiglaUsuario',$arrObjCodigoAcessoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'E-mail','Email',$arrObjCodigoAcessoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Código','Codigo',$arrObjCodigoAcessoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Chave','Chave',$arrObjCodigoAcessoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'User Agent','UserAgent',$arrObjCodigoAcessoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Http Client IP','HttpClientIp',$arrObjCodigoAcessoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Http X Forward For','HttpXForwardedFor',$arrObjCodigoAcessoDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Remote Addr','RemoteAddr',$arrObjCodigoAcessoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Geração','Geracao',$arrObjCodigoAcessoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Ativação','Ativacao',$arrObjCodigoAcessoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objCodigoAcessoDTO,'Último Acesso','Acesso',$arrObjCodigoAcessoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      //$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';

      if ($arrObjCodigoAcessoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<tr class="trVermelha">';
      }else{
        if ( ($i+2) % 2 ) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }
      }

      //$strResultado .= $strCssTr;

      //if ($bolCheck){
        $strResultado .= '<td valign="center" style="display:none">'.PaginaSip::getInstance()->getTrCheck($i,$arrObjCodigoAcessoDTO[$i]->getStrIdCodigoAcesso(),$arrObjCodigoAcessoDTO[$i]->getStrSiglaUsuario()).'</td>';
      //}

      $strResultado .= '<td align="center"><a alt="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrDescricaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrDescricaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrSiglaSistema()).'</a> / <a alt="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrDescricaoOrgaoSistema()).'" title="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrDescricaoOrgaoSistema()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrSiglaOrgaoSistema()).'</a></th>'."\n";
      $strResultado .= '<td align="center"><a alt="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrSiglaUsuario()).'</a> / <a alt="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrDescricaoOrgaoUsuario()).'" title="'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrDescricaoOrgaoUsuario()).'" class="ancoraSigla">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrSiglaOrgaoUsuario()).'</a></th>'."\n";
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getStrEmail()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getDthGeracao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getDthAtivacao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSip::tratarHTML($arrObjCodigoAcessoDTO[$i]->getDthAcesso()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i,$arrObjCodigoAcessoDTO[$i]->getStrIdCodigoAcesso());


      if ($bolAcaoConsultar){
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);abrirJanelaCodigoAcesso(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_codigo_acesso='.$arrObjCodigoAcessoDTO[$i]->getStrIdCodigoAcesso().'&pagina_simples=1').'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getDiretorioSvgLocal().'/2fa.svg" title="Consultar Habilitação de Autenticação em 2 Fatores" alt="Consultar Habilitação de Autenticação em 2 Fatores" class="infraImg"/></a>&nbsp;';
      }

      if ($bolAcaoLoginListar){
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);abrirJanelaAcessoCodigoAcesso(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_codigo_acesso='.$arrObjCodigoAcessoDTO[$i]->getStrIdCodigoAcesso().'&pagina_simples=1').'\');" tabindex="'.PaginaSip::getInstance()->getProxTabDados().'"><img src="'.PaginaSip::getInstance()->getDiretorioSvgLocal().'/cadeado_aberto.svg" title="Acessos" alt="Acessos" class="infraImg"/></a>&nbsp;';
      }

      //if ($bolAcaoAlterar){
      //  $strResultado .= '<a href="'.SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_codigo_acesso='.$arrObjCodigoAcessoDTO[$i]->getStrIdCodigoAcesso()).'" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeAlterar().'" title="Alterar Habilitação de Autenticação em 2 Fatores" alt="Alterar Habilitação de Autenticação em 2 Fatores" class="infraImg" /></a>&nbsp;';
      //}

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjCodigoAcessoDTO[$i]->getStrIdCodigoAcesso();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjCodigoAcessoDTO[$i]->getDthGeracao());
      }

      if ($bolAcaoDesativar && $arrObjCodigoAcessoDTO[$i]->getStrSinAtivo()=='S'){
        $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeDesativar().'" title="Desativar Habilitação de Autenticação em 2 Fatores" alt="Desativar Habilitação de Autenticação em 2 Fatores" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar && $arrObjCodigoAcessoDTO[$i]->getStrSinAtivo()=='N'){
        $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeReativar().'" title="Reativar Habilitação de Autenticação em 2 Fatores" alt="Reativar Habilitação de Autenticação em 2 Fatores" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeExcluir().'" title="Excluir Habilitação de Autenticação em 2 Fatores" alt="Excluir Habilitação de Autenticação em 2 Fatores" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'codigo_acesso_selecionar' || isset($_GET['pagina_simples'])){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('','Todos',$numIdOrgaoUsuario);
  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaTodos('','Todos',$numIdOrgaoSistema);
  $strItensSelSistema = SistemaINT::montarSelectSigla('','Todos', $numIdSistema, $numIdOrgaoSistema);

  $strItensSelEstadoCodigoAcesso = InfraINT::montarSelectArray('','Todas', $strEstado, array('A' => 'Ativadas', 'D' => 'Desativadas', 'I' => 'Incompletas'));

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
} 

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
<?if(0){?><style><?}?>

#lblOrgaoSistemaCodigoAcesso {position:absolute;left:0%;top:0%;width:25%;}
#selOrgaoSistemaCodigoAcesso {position:absolute;left:0%;top:20%;width:25%;}

#lblSistemaCodigoAcesso {position:absolute;left:0%;top:50%;width:25%;}
#selSistemaCodigoAcesso {position:absolute;left:0%;top:70%;width:25%;}

#lblOrgaoUsuarioCodigoAcesso {position:absolute;left:30%;top:0%;width:25%;}
#selOrgaoUsuarioCodigoAcesso {position:absolute;left:30%;top:20%;width:25%;}

#lblUsuarioCodigoAcesso {position:absolute;left:30%;top:50%;width:25%;}
#txtUsuarioCodigoAcesso {position:absolute;left:30%;top:70%;width:25%;}
#lblNomeUsuarioCodigoAcesso {position:absolute;left:60%;top:70%;}

#lblEstadoCodigoAcesso {position:absolute;left:60%;top:0%;width:25%;}
#selEstadoCodigoAcesso {position:absolute;left:60%;top:20%;width:25%;}

<?if(0){?></style><?}?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

var objAjaxUsuario = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='codigo_acesso_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuarioCodigoAcesso','txtUsuarioCodigoAcesso','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.carregando = true;
  objAjaxUsuario.prepararExecucao = function(){
    if (!infraSelectSelecionado('selOrgaoUsuarioCodigoAcesso')){
      alert('Selecione Órgão do Usuário.');
      document.getElementById('selOrgaoUsuarioCodigoAcesso').focus();
      return false;
    }
    return 'sigla='+document.getElementById('txtUsuarioCodigoAcesso').value + '&idOrgao='+document.getElementById('selOrgaoUsuarioCodigoAcesso').value;
  };

  objAjaxUsuario.processarResultado = function(id,descricao,complemento){

    if (id != ''){
      document.getElementById('hdnSiglaUsuarioCodigoAcesso').value = descricao;
      document.getElementById('hdnNomeUsuarioCodigoAcesso').value = complemento;
      document.getElementById('lblNomeUsuarioCodigoAcesso').innerHTML = complemento;

      if (!this.carregando){
        document.getElementById('frmCodigoAcessoLista').submit();
      }
    }else{
      document.getElementById('hdnSiglaUsuarioCodigoAcesso').value = '';
      document.getElementById('hdnNomeUsuarioCodigoAcesso').value = '';
      document.getElementById('lblNomeUsuarioCodigoAcesso').innerHTML = '';
    }
  };

  objAjaxUsuario.selecionar('<?=$numIdUsuario;?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strSiglaUsuario,false);?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strNomeUsuario,false)?>');
  objAjaxUsuario.carregando = false;

  infraEfeitoTabelas(true);
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da habilitação gerada em \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCodigoAcessoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCodigoAcessoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma habilitação selecionada.');
    return;
  }
  if (confirm("Confirma desativação das habilitações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCodigoAcessoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmCodigoAcessoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da habilitação gerada em \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCodigoAcessoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCodigoAcessoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma habilitação selecionada.');
    return;
  }
  if (confirm("Confirma reativação das habilitações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCodigoAcessoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmCodigoAcessoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da habilitação gerada em \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmCodigoAcessoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCodigoAcessoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma habilitação selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das habilitações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmCodigoAcessoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmCodigoAcessoLista').submit();
  }
}
<? } ?>

function abrirJanelaCodigoAcesso(link){
  infraAbrirJanela(link,'janelaCodigoAcesso',700,550,'location=0,status=1,resizable=1,scrollbars=1',true);
}

function abrirJanelaAcessoCodigoAcesso(link){
  infraAbrirJanela(link,'janelaAcessoCodigoAcesso',700,550,'location=0,status=1,resizable=1,scrollbars=1',true);
}

<?if(0){?></script><?}?>
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCodigoAcessoLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSip::getInstance()->abrirAreaDados('10em');
  ?>

  <label id="lblOrgaoSistemaCodigoAcesso" for="selOrgaoSistemaCodigoAcesso" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Sistema:</label>
  <select id="selOrgaoSistemaCodigoAcesso" name="selOrgaoSistemaCodigoAcesso" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelOrgaoSistema?>
  </select>

  <label id="lblSistemaCodigoAcesso" for="selSistemaCodigoAcesso" accesskey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>istema:</label>
  <select id="selSistemaCodigoAcesso" name="selSistemaCodigoAcesso" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelSistema?>
  </select>

  <label id="lblOrgaoUsuarioCodigoAcesso" for="selOrgaoUsuarioCodigoAcesso" accesskey="o" class="infraLabelObrigatorio">Órgã<span class="infraTeclaAtalho">o</span> do Usuário:</label>
  <select id="selOrgaoUsuarioCodigoAcesso" name="selOrgaoUsuarioCodigoAcesso" <?=$strDesabilitarFiltroUsuario?> onchange="objAjaxUsuario.limpar();this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelOrgaoUsuario?>
  </select>

  <label id="lblUsuarioCodigoAcesso" for="txtUsuarioCodigoAcesso" accesskey="u" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">U</span>suário:</label>
  <input type="text" id="txtUsuarioCodigoAcesso" name="txtUsuarioCodigoAcesso" <?=$strDesabilitarFiltroUsuario?> class="infraText" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" maxlength="100" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <label id="lblNomeUsuarioCodigoAcesso" class="infraLabelOpcional"></label>

  <label id="lblEstadoCodigoAcesso" for="selEstadoCodigoAcesso" accesskey="" class="infraLabelObrigatorio">Situação:</label>
  <select id="selEstadoCodigoAcesso" name="selEstadoCodigoAcesso" <?=$strDesabilitarFiltroUsuario?> onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelEstadoCodigoAcesso?>
  </select>

  <input type="hidden" id="hdnIdUsuarioCodigoAcesso" name="hdnIdUsuarioCodigoAcesso" value="<?=$numIdUsuario?>" />
  <input type="hidden" id="hdnSiglaUsuarioCodigoAcesso" name="hdnSiglaUsuarioCodigoAcesso" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" />
  <input type="hidden" id="hdnNomeUsuarioCodigoAcesso" name="hdnNomeUsuarioCodigoAcesso" value="<?=PaginaSip::tratarHTML($strNomeUsuario)?>" />

  <?
  PaginaSip::getInstance()->fecharAreaDados();
  PaginaSip::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSip::getInstance()->montarAreaDebug();
  PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
