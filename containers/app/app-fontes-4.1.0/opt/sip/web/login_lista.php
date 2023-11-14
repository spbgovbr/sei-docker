<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/11/2018 - criado por mga
 *
 * Versão do Gerador de Código: 1.42.0
 */

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->setArrParametrosRepasseLink(array('pagina_simples', 'id_usuario', 'id_codigo_acesso'));

  PaginaSip::getInstance()->prepararSelecao('login_selecionar');

  if (isset($_GET['pagina_simples'])) {
    PaginaSip::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['id_usuario']) || isset($_GET['id_codigo_acesso'])) {
    PaginaSip::getInstance()->salvarCampo('selOrgaoSistemaLogin', null);
    PaginaSip::getInstance()->salvarCampo('selSistemaLogin', null);
    PaginaSip::getInstance()->salvarCampo('selOrgaoUsuarioLogin', null);
    PaginaSip::getInstance()->salvarCampo('hdnIdUsuarioLogin', null);
    PaginaSip::getInstance()->salvarCampo('hdnSiglaUsuarioLogin', null);
    PaginaSip::getInstance()->salvarCampo('rdoTipo', 'T');
    PaginaSip::getInstance()->salvarCampo('txtRemoteAddr', null);
    PaginaSip::getInstance()->salvarCampo('txtHttpClientIp', null);
    PaginaSip::getInstance()->salvarCampo('txtHttpXForwardedFor', null);

    if (isset($_GET['id_usuario'])) {
      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdOrgao();
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario']);

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultar($objUsuarioDTO);

      PaginaSip::getInstance()->salvarCampo('selOrgaoUsuarioLogin', $objUsuarioDTO->getNumIdOrgao());
      PaginaSip::getInstance()->salvarCampo('hdnIdUsuarioLogin', $objUsuarioDTO->getNumIdUsuario());
      PaginaSip::getInstance()->salvarCampo('hdnSiglaUsuarioLogin', $objUsuarioDTO->getStrSigla());
    }
  } else {
    PaginaSip::getInstance()->salvarCamposPost(array(
      'selOrgaoSistemaLogin', 'selSistemaLogin', 'selOrgaoUsuarioLogin', 'hdnIdUsuarioLogin', 'txtUsuarioLogin', 'hdnSiglaUsuarioLogin', 'txtRemoteAddr', 'txtHttpClientIp', 'txtHttpXForwardedFor'
    ));

    if (count($_POST)) {
      PaginaSip::getInstance()->salvarCampo('rdoTipo', $_POST['rdoTipo']);
    }
  }

  switch ($_GET['acao']) {
    case 'login_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjLoginDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $arrStrIdComposto = explode('-', $arrStrIds[$i]);
          $objLoginDTO = new LoginDTO();
          $objLoginDTO->setStrIdLogin($arrStrIdComposto[0]);
          $objLoginDTO->setNumIdSistema($arrStrIdComposto[1]);
          $objLoginDTO->setNumIdUsuario($arrStrIdComposto[2]);
          $arrObjLoginDTO[] = $objLoginDTO;
        }
        $objLoginRN = new LoginRN();
        $objLoginRN->excluir($arrObjLoginDTO);
        PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    /*
        case 'login_desativar':
          try{
            $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
            $arrObjLoginDTO = array();
            for ($i=0;$i<count($arrStrIds);$i++){
              $arrStrIdComposto = explode('-',$arrStrIds[$i]);
              $objLoginDTO = new LoginDTO();
              $objLoginDTO->setStrIdLogin($arrStrIdComposto[0]);
              $objLoginDTO->setNumIdSistema($arrStrIdComposto[1]);
              $objLoginDTO->setNumIdUsuario($arrStrIdComposto[2]);
              $arrObjLoginDTO[] = $objLoginDTO;
            }
            $objLoginRN = new LoginRN();
            $objLoginRN->desativar($arrObjLoginDTO);
            PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
          }catch(Exception $e){
            PaginaSip::getInstance()->processarExcecao($e);
          }
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
          die;

        case 'login_reativar':
          $strTitulo = 'Reativar Acessos';
          if ($_GET['acao_confirmada']=='sim'){
            try{
              $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
              $arrObjLoginDTO = array();
              for ($i=0;$i<count($arrStrIds);$i++){
                $arrStrIdComposto = explode('-',$arrStrIds[$i]);
                $objLoginDTO = new LoginDTO();
                $objLoginDTO->setStrIdLogin($arrStrIdComposto[0]);
                $objLoginDTO->setNumIdSistema($arrStrIdComposto[1]);
                $objLoginDTO->setNumIdUsuario($arrStrIdComposto[2]);
                $arrObjLoginDTO[] = $objLoginDTO;
              }
              $objLoginRN = new LoginRN();
              $objLoginRN->reativar($arrObjLoginDTO);
              PaginaSip::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            }catch(Exception $e){
              PaginaSip::getInstance()->processarExcecao($e);
            }
            header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
            die;
          }
          break;

     */
    case 'login_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Acesso', 'Selecionar Acessos');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'login_cadastrar') {
        if (isset($_GET['id_login']) && isset($_GET['id_sistema']) && isset($_GET['id_usuario'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_login'] . '-' . $_GET['id_sistema'] . '-' . $_GET['id_usuario']);
        }
      }
      break;

    case 'login_listar':
      $strTitulo = 'Acessos';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  if (!isset($_GET['id_codigo_acesso'])) {
    $arrComandos[] = '<button type="submit" id="sbmPesquisar" name="sbmPesquisar" class="infraButton">Pesquisar</button>';
  }

  if ($_GET['acao'] == 'login_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'login_listar' || $_GET['acao'] == 'login_selecionar'){ */
  $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('login_cadastrar');
  if ($bolAcaoCadastrar) {
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }
  /* } */

  $objLoginDTO = new LoginDTO();
  $objLoginDTO->retStrIdLogin();
  $objLoginDTO->retNumIdSistema();
  $objLoginDTO->retNumIdUsuario();
  $objLoginDTO->retDthLogin();
  $objLoginDTO->retStrIdCodigoAcesso();
  //$objLoginDTO->retStrHashInterno();
  //$objLoginDTO->retStrHashUsuario();
  //$objLoginDTO->retStrHashAgente();
  //$objLoginDTO->retStrStaValidado();
  $objLoginDTO->retStrNomeUsuario();
  $objLoginDTO->retStrSiglaUsuario();
  $objLoginDTO->retStrSiglaOrgaoUsuario();
  $objLoginDTO->retStrDescricaoOrgaoUsuario();
  $objLoginDTO->retStrSiglaSistema();
  $objLoginDTO->retStrDescricaoSistema();
  $objLoginDTO->retStrSiglaOrgaoSistema();
  $objLoginDTO->retStrDescricaoOrgaoSistema();
  $objLoginDTO->retStrUserAgent();
  $objLoginDTO->retStrHttpClientIp();
  $objLoginDTO->retStrHttpXForwardedFor();
  $objLoginDTO->retStrRemoteAddr();

  $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistemaLogin');
  if ($numIdOrgaoSistema !== '') {
    $objLoginDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
  }

  $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistemaLogin');
  if ($numIdSistema !== '') {
    $objLoginDTO->setNumIdSistema($numIdSistema);
  }

  $numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuarioLogin');
  $numIdUsuario = PaginaSip::getInstance()->recuperarCampo('hdnIdUsuarioLogin');
  $strSiglaUsuario = PaginaSip::getInstance()->recuperarCampo('hdnSiglaUsuarioLogin');
  if ($numIdUsuario !== '') {
    $objLoginDTO->setNumIdUsuario($numIdUsuario);
  }

  $strRemoteAddr = trim(PaginaSip::getInstance()->recuperarCampo('txtRemoteAddr'));
  if ($strRemoteAddr != '') {
    $objLoginDTO->setStrRemoteAddr('%' . $strRemoteAddr . '%', InfraDTO::$OPER_LIKE);
  }

  $strHttpClientIp = trim(PaginaSip::getInstance()->recuperarCampo('txtHttpClientIp'));
  if ($strHttpClientIp != '') {
    $objLoginDTO->setStrHttpClientIp('%' . $strHttpClientIp . '%', InfraDTO::$OPER_LIKE);
  }

  $strHttpXForwardedFor = trim(PaginaSip::getInstance()->recuperarCampo('txtHttpXForwardedFor'));
  if ($strHttpXForwardedFor != '') {
    $objLoginDTO->setStrHttpXForwardedFor('%' . $strHttpXForwardedFor . '%', InfraDTO::$OPER_LIKE);
  }

  if (isset($_GET['id_codigo_acesso'])) {
    $objLoginDTO->setStrIdCodigoAcesso($_GET['id_codigo_acesso']);
  }

  $strStaTipo = PaginaSip::getInstance()->recuperarCampo('rdoTipo', 'T');
  if ($strStaTipo != 'T') {
    if ($strStaTipo == 'S') {
      $objLoginDTO->setStrIdCodigoAcesso(null);
    } else {
      if ($strStaTipo == 'E') {
        $objLoginDTO->setStrIdCodigoAcesso(null, InfraDTO::$OPER_DIFERENTE);
      }
    }
  }


  /*
    if ($_GET['acao'] == 'login_reativar'){
      //Lista somente inativos
      $objLoginDTO->setBolExclusaoLogica(false);
      $objLoginDTO->setStrSinAtivo('N');
    }
   */

  $numRegistros = 0;

  if (isset($_POST['hdnFlag']) || isset($_GET['id_usuario']) || isset($_GET['id_codigo_acesso'])) {
    PaginaSip::getInstance()->prepararOrdenacao($objLoginDTO, 'Login', InfraDTO::$TIPO_ORDENACAO_DESC);
    PaginaSip::getInstance()->prepararPaginacao($objLoginDTO);

    $objLoginRN = new LoginRN();
    $arrObjLoginDTO = $objLoginRN->listar($objLoginDTO);

    PaginaSip::getInstance()->processarPaginacao($objLoginDTO);
    $numRegistros = count($arrObjLoginDTO);
  }

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'login_selecionar') {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('login_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('login_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoConsultarCodigoAcesso = false;
      $bolCheck = true;
      /*     }else if ($_GET['acao']=='login_reativar'){
            $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('login_reativar');
            $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('login_consultar');
            $bolAcaoAlterar = false;
            $bolAcaoImprimir = true;
            //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
            $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('login_excluir');
            $bolAcaoDesativar = false;
            $bolAcaoConsultarCodigoAcesso = false;

       */
    } else {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('login_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('login_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSip::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('login_excluir');
      $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('login_desativar');
      $bolAcaoConsultarCodigoAcesso = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_consultar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_excluir&acao_origem=' . $_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='login_reativar'){ */
    $strSumarioTabela = 'Tabela de Acessos.';
    $strCaptionTabela = 'Acessos';
    /* }else{
      $strSumarioTabela = 'Tabela de Acessos Inativos.';
      $strCaptionTabela = 'Acessos Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'Sistema', 'SiglaSistema', $arrObjLoginDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'Usuário', 'SiglaUsuario', $arrObjLoginDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'Data/Hora', 'Login', $arrObjLoginDTO) . '</th>' . "\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objLoginDTO,'Hash Interno','HashInterno',$arrObjLoginDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objLoginDTO,'Hash Usuário','HashUsuario',$arrObjLoginDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objLoginDTO,'Hash Agente','HashAgente',$arrObjLoginDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'Remote Addr', 'RemoteAddr', $arrObjLoginDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'Http Client&nbsp;IP', 'HttpClientIp', $arrObjLoginDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'Http&nbsp;X Forwarded&nbsp;for', 'HttpXForwardedFor', $arrObjLoginDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objLoginDTO, 'User Agent', 'UserAgent', $arrObjLoginDTO) . '</th>' . "\n";
    //$strResultado .= '<th class="infraTh">'.PaginaSip::getInstance()->getThOrdenacao($objLoginDTO,'Situação','StaValidado',$arrObjLoginDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="5%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjLoginDTO[$i]->getStrIdLogin() . '-' . $arrObjLoginDTO[$i]->getNumIdSistema() . '-' . $arrObjLoginDTO[$i]->getNumIdUsuario(),
            $arrObjLoginDTO[$i]->getStrIdLogin()) . '</td>';
      }
      $strResultado .= '<td align="center"><a alt="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrDescricaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrDescricaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrSiglaSistema()) . '</a> / <a alt="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrDescricaoOrgaoSistema()) . '" title="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrDescricaoOrgaoSistema()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrSiglaOrgaoSistema()) . '</a></th>' . "\n";
      $strResultado .= '<td align="center"><a alt="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrSiglaUsuario()) . '</a> / <a alt="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrSiglaOrgaoUsuario()) . '</a></th>' . "\n";
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getDthLogin()) . '</td>';

      //$strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrHashInterno()).'</td>';
      //$strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrHashUsuario()).'</td>';
      //$strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrHashAgente()).'</td>';

      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrRemoteAddr()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrHttpClientIp()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrHttpXForwardedFor()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrUserAgent()) . '</td>';
      //$strResultado .= '<td>'.PaginaSip::tratarHTML($arrObjLoginDTO[$i]->getStrStaValidado()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjLoginDTO[$i]->getStrIdLogin() . '-' . $arrObjLoginDTO[$i]->getNumIdSistema() . '-' . $arrObjLoginDTO[$i]->getNumIdUsuario());

      if ($bolAcaoConsultarCodigoAcesso && $arrObjLoginDTO[$i]->getStrIdCodigoAcesso() != null) {
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);abrirJanelaCodigoAcessoLogin(\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_codigo_acesso=' . $arrObjLoginDTO[$i]->getStrIdCodigoAcesso() . '&pagina_simples=1') . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/2fa.svg" title="Consultar Habilitação de Autenticação em 2 Fatores" alt="Consultar Habilitação de Autenticação em 2 Fatores" class="infraImg"/></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_login=' . $arrObjLoginDTO[$i]->getStrIdLogin() . '&id_sistema=' . $arrObjLoginDTO[$i]->getNumIdSistema() . '&id_usuario=' . $arrObjLoginDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Acesso" alt="Alterar Acesso" class="infraImg" /></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjLoginDTO[$i]->getStrIdLogin() . '-' . $arrObjLoginDTO[$i]->getNumIdSistema() . '-' . $arrObjLoginDTO[$i]->getNumIdUsuario();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjLoginDTO[$i]->getStrIdLogin());
      }
      /*
            if ($bolAcaoDesativar){
              $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeDesativar().'" title="Desativar Acesso" alt="Desativar Acesso" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar){
              $strResultado .= '<a href="'.PaginaSip::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSip::getInstance()->getProxTabTabela().'"><img src="'.PaginaSip::getInstance()->getIconeReativar().'" title="Reativar Acesso" alt="Reativar Acesso" class="infraImg" /></a>&nbsp;';
            }
       */

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="' . PaginaSip::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Acesso" alt="Excluir Acesso" class="infraImg" /></a>&nbsp;&nbsp;';
      }

      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'login_selecionar' || isset($_GET['pagina_simples'])) {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('', 'Todos', $numIdOrgaoSistema);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('', 'Todos', $numIdSistema, $numIdOrgaoSistema);
  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('', 'Todos', $numIdOrgaoUsuario);
  $strLinkAjaxSistemas = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=sistema_montar_select_sigla_administrados');
  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');


  $strDisplayCriterios = '';
  if (isset($_GET['id_usuario']) || isset($_GET['id_codigo_acesso'])) {
    $strDisplayCriterios = 'display:none;';
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
<?
if (0){ ?>
  <style><?}?>
    #divInfraAreaDados {
    <?=$strDisplayCriterios?>
    }

    #fldTipo {
      position: absolute;
      left: 0%;
      top: 0%;
      height: 80%;
      width: 19%;
      padding-top: 8px;
    }

    div.infraDivRadio {
      margin: 8px 0 0 15px;
    }

    #lblOrgaoSistemaLogin {
      position: absolute;
      left: 22%;
      top: 0%;
      width: 20%;
    }

    #selOrgaoSistemaLogin {
      position: absolute;
      left: 22%;
      top: 12%;
      width: 20%;
    }

    #lblSistemaLogin {
      position: absolute;
      left: 22%;
      top: 30%;
      width: 20%;
    }

    #selSistemaLogin {
      position: absolute;
      left: 22%;
      top: 42%;
      width: 20%;
    }

    #lblOrgaoUsuarioLogin {
      position: absolute;
      left: 44%;
      top: 0%;
      width: 20%;
    }

    #selOrgaoUsuarioLogin {
      position: absolute;
      left: 44%;
      top: 12%;
      width: 20%;
    }

    #lblUsuarioLogin {
      position: absolute;
      left: 44%;
      top: 30%;
      width: 20%;
    }

    #txtUsuarioLogin {
      position: absolute;
      left: 44%;
      top: 42%;
      width: 20%;
    }

    #lblRemoteAddr {
      position: absolute;
      left: 66%;
      top: 0%;
      width: 20%;
    }

    #txtRemoteAddr {
      position: absolute;
      left: 66%;
      top: 12%;
      width: 20%;
    }

    #lblHttpClientIp {
      position: absolute;
      left: 66%;
      top: 30%;
      width: 20%;
    }

    #txtHttpClientIp {
      position: absolute;
      left: 66%;
      top: 42%;
      width: 20%;
    }

    #lblHttpXForwardedFor {
      position: absolute;
      left: 66%;
      top: 60%;
      width: 20%;
    }

    #txtHttpXForwardedFor {
      position: absolute;
      left: 66%;
      top: 72%;
      width: 20%;
    }

    <?
    if (0){ ?></style><?
} ?>
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
<?
if (0){ ?>
  <script type="text/javascript"><?}?>

    var objAjaxSistemas = null;
    var objAjaxUsuario = null;

    function inicializar() {
      <? if ($_GET['acao'] == 'login_selecionar' || isset($_GET['pagina_simples'])) { ?>
      infraReceberSelecao();
      document.getElementById('btnFecharSelecao').focus();
      <? }else{ ?>
      document.getElementById('btnFechar').focus();
      <? } ?>

      //COMBO DE SISTEMAS
      objAjaxSistemas = new infraAjaxMontarSelectDependente('selOrgaoSistemaLogin', 'selSistemaLogin', '<?= $strLinkAjaxSistemas ?>');
      objAjaxSistemas.prepararExecucao = function () {
        return infraAjaxMontarPostPadraoSelect('null', '', '') + '&idOrgaoSistema=' + document.getElementById('selOrgaoSistemaLogin').value;
      }
      objAjaxSistemas.processarResultado = function () {
        //alert('Carregou sistemas.');
      }

      //AUTO COMPLETAR USUARIO
      objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuarioLogin', 'txtUsuarioLogin', '<?=$strLinkAjaxUsuario?>');
      objAjaxUsuario.carregando = true;
      objAjaxUsuario.prepararExecucao = function () {
        if (!infraSelectSelecionado('selOrgaoUsuarioLogin')) {
          alert('Selecione Órgão do Usuário.');
          document.getElementById('selOrgaoUsuarioLogin').focus();
          return false;
        }
        return 'sigla=' + document.getElementById('txtUsuarioLogin').value + '&idOrgao=' + document.getElementById('selOrgaoUsuarioLogin').value;
      };

      objAjaxUsuario.processarResultado = function (id, descricao, complemento) {

        if (id != '') {
          document.getElementById('hdnSiglaUsuarioLogin').value = descricao;

          if (!this.carregando) {
            //document.getElementById('frmLoginLista').submit();
          }
        } else {
          document.getElementById('hdnSiglaUsuarioLogin').value = '';
        }
      };

      objAjaxUsuario.selecionar('<?=$numIdUsuario;?>', '<?=PaginaSip::getInstance()->formatarParametrosJavascript($strSiglaUsuario, false);?>');
      objAjaxUsuario.carregando = false;

      infraEfeitoTabelas(true);
    }

    <? if ($bolAcaoDesativar){ ?>
    function acaoDesativar(id, desc) {
      if (confirm("Confirma desativação do Acesso \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmLoginLista').action = '<?=$strLinkDesativar?>';
        document.getElementById('frmLoginLista').submit();
      }
    }

    function acaoDesativacaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Acesso selecionado.');
        return;
      }
      if (confirm("Confirma desativação dos Acessos selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmLoginLista').action = '<?=$strLinkDesativar?>';
        document.getElementById('frmLoginLista').submit();
      }
    }
    <? } ?>

    <? if ($bolAcaoReativar){ ?>
    function acaoReativar(id, desc) {
      if (confirm("Confirma reativação do Acesso \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmLoginLista').action = '<?=$strLinkReativar?>';
        document.getElementById('frmLoginLista').submit();
      }
    }

    function acaoReativacaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Acesso selecionado.');
        return;
      }
      if (confirm("Confirma reativação dos Acessos selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmLoginLista').action = '<?=$strLinkReativar?>';
        document.getElementById('frmLoginLista').submit();
      }
    }
    <? } ?>

    <? if ($bolAcaoExcluir){ ?>
    function acaoExcluir(id, desc) {
      if (confirm("Confirma exclusão do Acesso \"" + desc + "\"?")) {
        document.getElementById('hdnInfraItemId').value = id;
        document.getElementById('frmLoginLista').action = '<?=$strLinkExcluir?>';
        document.getElementById('frmLoginLista').submit();
      }
    }

    function acaoExclusaoMultipla() {
      if (document.getElementById('hdnInfraItensSelecionados').value == '') {
        alert('Nenhum Acesso selecionado.');
        return;
      }
      if (confirm("Confirma exclusão dos Acessos selecionados?")) {
        document.getElementById('hdnInfraItemId').value = '';
        document.getElementById('frmLoginLista').action = '<?=$strLinkExcluir?>';
        document.getElementById('frmLoginLista').submit();
      }
    }
    <? } ?>

    function abrirJanelaAcessoLogin(link) {
      infraAbrirJanelaModal(link, 700, 450);
    }

    function abrirJanelaCodigoAcessoLogin(link) {
      infraAbrirJanelaModal(link, 700, 450);
    }

    function OnSubmitForm() {
      if (!document.getElementById('optSinTodos').checked && !document.getElementById('optSinSenha').checked && !document.getElementById('optSin2Fatores').checked) {
        alert('Selecione um Tipo.');
        return false;
      }
      infraExibirAviso();
      return true;
    }

    <?
    if (0){ ?></script><?
} ?>
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmLoginLista" onsubmit="return OnSubmitForm()" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('16em');
    ?>

    <fieldset id="fldTipo" class="infraFieldset">
      <legend class="infraLegend">&nbsp;Tipo&nbsp;</legend>

      <div id="divSinTodos" class="infraDivRadio">
        <input type="radio" name="rdoTipo" id="optSinTodos" value="T" <?=($strStaTipo == 'T' ? 'checked="checked"' : '')?> class="infraRadio"
               tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
        <label id="lblSinTodos" accesskey="" for="optSinTodos" class="infraLabelRadio">Todos</label>
      </div>

      <div id="divSinSenha" class="infraDivRadio">
        <input type="radio" name="rdoTipo" id="optSinSenha" value="S" <?=($strStaTipo == 'S' ? 'checked="checked"' : '')?> class="infraRadio"
               tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
        <label id="lblSinSenha" accesskey="" for="optSinSenha" class="infraLabelRadio">Somente Senha</label>
      </div>

      <div id="divSin2Fatores" class="infraDivRadio">
        <input type="radio" name="rdoTipo" id="optSin2Fatores" value="E" <?=($strStaTipo == 'E' ? 'checked="checked"' : '')?> class="infraRadio"
               tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
        <label id="lblSin2Fatores" accesskey="" for="optSin2Fatores" class="infraLabelRadio">2 Fatores</label>
      </div>

    </fieldset>

    <label id="lblOrgaoSistemaLogin" for="selOrgaoSistemaLogin" accesskey="" class="infraLabelOpcional">Órgão do
      Sistema:</label>
    <select id="selOrgaoSistemaLogin" name="selOrgaoSistemaLogin" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistemaLogin" for="selSistemaLogin" accesskey="S" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistemaLogin" name="selSistemaLogin" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelSistema?>
    </select>

    <label id="lblOrgaoUsuarioLogin" for="selOrgaoUsuarioLogin" accesskey="" class="infraLabelOpcional">Órgão do Usuário:</label>
    <select id="selOrgaoUsuarioLogin" name="selOrgaoUsuarioLogin" onchange="objAjaxUsuario.limpar();" class="infraSelect" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaoUsuario?>
    </select>

    <label id="lblUsuarioLogin" for="txtUsuarioLogin" accesskey="u" class="infraLabelOpcional"><span class="infraTeclaAtalho">U</span>suário:</label>
    <input type="text" id="txtUsuarioLogin" name="txtUsuarioLogin" class="infraText" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <input type="hidden" id="hdnIdUsuarioLogin" name="hdnIdUsuarioLogin" value="<?=$numIdUsuario?>"/>
    <input type="hidden" id="hdnSiglaUsuarioLogin" name="hdnSiglaUsuarioLogin" value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>"/>

    <label id="lblRemoteAddr" for="txtRemoteAddr" accesskey="" class="infraLabelOpcional">Remote Addr:</label>
    <input type="text" id="txtRemoteAddr" name="txtRemoteAddr" class="infraText" value="<?=PaginaSip::tratarHTML($strRemoteAddr)?>" maxlength="39" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblHttpClientIp" for="txtHttpClientIp" accesskey="" class="infraLabelOpcional">Http Client IP:</label>
    <input type="text" id="txtHttpClientIp" name="txtHttpClientIp" class="infraText" value="<?=PaginaSip::tratarHTML($strHttpClientIp)?>" maxlength="39" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblHttpXForwardedFor" for="txtHttpXForwardedFor" accesskey="" class="infraLabelOpcional">Http X Forwarded For:</label>
    <input type="text" id="txtHttpXForwardedFor" name="txtHttpXForwardedFor" class="infraText" value="<?=PaginaSip::tratarHTML($strHttpXForwardedFor)?>" maxlength="39" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <input type="hidden" id="hdnFlag" name="hdnFlag" value="1">

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
