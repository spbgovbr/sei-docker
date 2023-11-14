<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/05/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.26.0
 *
 * Versão no CVS: $Id$
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

  PaginaSip::getInstance()->prepararSelecao('usuario_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array(
      'selOrgaoUsuario', 'txtSiglaUsuario', 'txtNomeRegistroCivilUsuario', 'txtNomeSocialUsuario', 'txtIdOrigemUsuario', 'txtCpfUsuario', 'selSituacaoUsuario'
    ));

  switch ($_GET['acao']) {
    case 'usuario_excluir':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjUsuarioDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setNumIdUsuario($arrStrIds[$i]);
          $arrObjUsuarioDTO[] = $objUsuarioDTO;
        }
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioRN->excluir($arrObjUsuarioDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'usuario_desativar':
      try {
        $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
        $arrObjUsuarioDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setNumIdUsuario($arrStrIds[$i]);
          $arrObjUsuarioDTO[] = $objUsuarioDTO;
        }
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioRN->desativar($arrObjUsuarioDTO);
        PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSip::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
      die;

    case 'usuario_reativar':
      $strTitulo = 'Reativar Usuários';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSip::getInstance()->getArrStrItensSelecionados();
          $arrObjUsuarioDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objUsuarioDTO = new UsuarioDTO();
            $objUsuarioDTO->setNumIdUsuario($arrStrIds[$i]);
            $arrObjUsuarioDTO[] = $objUsuarioDTO;
          }
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioRN->reativar($arrObjUsuarioDTO);
          PaginaSip::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']));
        die;
      }
      break;


    case 'usuario_selecionar':
      $strTitulo = PaginaSip::getInstance()->getTituloSelecao('Selecionar Usuário', 'Selecionar Usuários');

      //Se cadastrou alguem
      if ($_GET['acao_origem'] == 'usuario_cadastrar') {
        if (isset($_GET['id_usuario'])) {
          PaginaSip::getInstance()->adicionarSelecionado($_GET['id_usuario']);
        }
      }
      break;

    case 'usuario_listar':
      $strTitulo = 'Usuários';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';


  if ($_GET['acao'] == 'usuario_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'usuario_listar' || $_GET['acao'] == 'usuario_selecionar') {
    $bolAcaoCadastrar = SessaoSip::getInstance()->verificarPermissao('usuario_cadastrar');
    if ($bolAcaoCadastrar) {
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }

  $objUsuarioDTO = new UsuarioDTO(true);
  $objUsuarioDTO->retNumIdUsuario();
  $objUsuarioDTO->retStrSinBloqueado();
  $objUsuarioDTO->retDthPausa2fa();
  $objUsuarioDTO->retStrIdOrigem();
  $objUsuarioDTO->retStrSigla();
  $objUsuarioDTO->retStrNome();
  $objUsuarioDTO->retStrNomeRegistroCivil();
  $objUsuarioDTO->retStrNomeSocial();
  $objUsuarioDTO->retStrSiglaOrgao();
  $objUsuarioDTO->retStrDescricaoOrgao();

  $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario');
  if ($numIdOrgao !== '') {
    $objUsuarioDTO->setNumIdOrgao($numIdOrgao);
  }

  $strSiglaPesquisa = trim(PaginaSip::getInstance()->recuperarCampo('txtSiglaUsuario'));
  if ($strSiglaPesquisa !== '') {
    $objUsuarioDTO->setStrSigla($strSiglaPesquisa);
  }

  $strNomeRegistroCivilPesquisa = PaginaSip::getInstance()->recuperarCampo('txtNomeRegistroCivilUsuario');
  if ($strNomeRegistroCivilPesquisa !== '') {
    $objUsuarioDTO->setStrNomeRegistroCivil($strNomeRegistroCivilPesquisa);
  }

  $strNomeSocialPesquisa = PaginaSip::getInstance()->recuperarCampo('txtNomeSocialUsuario');
  if ($strNomeSocialPesquisa !== '') {
    $objUsuarioDTO->setStrNomeSocial($strNomeSocialPesquisa);
  }

  $strIdOrigemPesquisa = PaginaSip::getInstance()->recuperarCampo('txtIdOrigemUsuario');
  if ($strIdOrigemPesquisa !== '') {
    $objUsuarioDTO->setStrIdOrigem($strIdOrigemPesquisa);
  }

  $strCpfPesquisa = PaginaSip::getInstance()->recuperarCampo('txtCpfUsuario');
  if ($strCpfPesquisa !== '') {
    $objUsuarioDTO->setDblCpf($strCpfPesquisa);
  }

  $strSituacaoUsuario = PaginaSip::getInstance()->recuperarCampo('selSituacaoUsuario');
  if ($strSituacaoUsuario !== '') {
    switch ($strSituacaoUsuario) {
      case UsuarioRN::$TS_BLOQUEADO:
        $objUsuarioDTO->setStrSinBloqueado('S');
        break;

      case UsuarioRN::$TS_PAUSA_2FA:
        $objUsuarioDTO->adicionarCriterio(array('Pausa2fa', 'Pausa2fa'), array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_MAIOR), array(null, InfraData::getStrDataHoraAtual()), InfraDTO::$OPER_LOGICO_AND);
        break;
    }
  }


  if ($_GET['acao'] == 'usuario_reativar') {
    //Lista somente inativos
    $objUsuarioDTO->setBolExclusaoLogica(false);
    $objUsuarioDTO->setStrSinAtivo('N');
  }

  PaginaSip::getInstance()->prepararOrdenacao($objUsuarioDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSip::getInstance()->prepararPaginacao($objUsuarioDTO);

  $objUsuarioRN = new UsuarioRN();
  $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);

  PaginaSip::getInstance()->processarPaginacao($objUsuarioDTO);
  $numRegistros = count($arrObjUsuarioDTO);

  if ($numRegistros > 0) {
    $bolCheck = false;

    if ($_GET['acao'] == 'usuario_selecionar') {
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('usuario_consultar');
      $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('usuario_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolAcaoBloquear = false;
      $bolAcaoDesbloquear = false;
      $bolAcaoCodigoAcessoListar = false;
      $bolAcaoPausar2fa = false;
      $bolAcaoRemoverPausa2fa = false;
      $bolAcaoLoginListar = false;
      $bolCheck = true;
    } else {
      if ($_GET['acao'] == 'usuario_reativar') {
        $bolAcaoReativar = SessaoSip::getInstance()->verificarPermissao('usuario_reativar');
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('usuario_consultar');
        $bolAcaoAlterar = false;
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('usuario_excluir');
        $bolAcaoDesativar = false;
        $bolAcaoBloquear = false;
        $bolAcaoDesbloquear = false;
        $bolAcaoCodigoAcessoListar = false;
        $bolAcaoPausar2fa = false;
        $bolAcaoRemoverPausa2fa = false;
        $bolAcaoLoginListar = false;
      } else {
        $bolAcaoReativar = false;
        $bolAcaoConsultar = SessaoSip::getInstance()->verificarPermissao('usuario_consultar');
        $bolAcaoAlterar = SessaoSip::getInstance()->verificarPermissao('usuario_alterar');
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSip::getInstance()->verificarPermissao('usuario_excluir');
        $bolAcaoDesativar = SessaoSip::getInstance()->verificarPermissao('usuario_desativar');
        $bolAcaoBloquear = SessaoSip::getInstance()->verificarPermissao('usuario_bloquear');
        $bolAcaoDesbloquear = SessaoSip::getInstance()->verificarPermissao('usuario_desbloquear');
        $bolAcaoCodigoAcessoListar = SessaoSip::getInstance()->verificarPermissao('codigo_acesso_listar');
        $bolAcaoPausar2fa = SessaoSip::getInstance()->verificarPermissao('usuario_pausar_2fa');
        $bolAcaoRemoverPausa2fa = SessaoSip::getInstance()->verificarPermissao('usuario_remover_pausa_2fa');
        $bolAcaoLoginListar = SessaoSip::getInstance()->verificarPermissao('login_listar');
      }
    }


    if ($bolAcaoDesativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_desativar&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoReativar) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir) {
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_excluir&acao_origem=' . $_GET['acao']);
    }

    if ($bolAcaoImprimir) {
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strResultado = '';

    if ($_GET['acao'] != 'usuario_reativar') {
      $strSumarioTabela = 'Tabela de Usuários.';
      $strCaptionTabela = 'Usuários';
    } else {
      $strSumarioTabela = 'Tabela de Usuários Inativos.';
      $strCaptionTabela = 'Usuários Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>' . "\n";
    }
    $strResultado .= '<th class="infraTh" width="8%">' . PaginaSip::getInstance()->getThOrdenacao($objUsuarioDTO, 'ID&nbsp;SIP', 'IdUsuario', $arrObjUsuarioDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="8%">' . PaginaSip::getInstance()->getThOrdenacao($objUsuarioDTO, 'ID&nbsp;Origem', 'IdOrigem', $arrObjUsuarioDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objUsuarioDTO, 'Sigla', 'Sigla', $arrObjUsuarioDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objUsuarioDTO, 'Nome', 'NomeRegistroCivil', $arrObjUsuarioDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objUsuarioDTO, 'Nome Social', 'NomeSocial', $arrObjUsuarioDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">' . PaginaSip::getInstance()->getThOrdenacao($objUsuarioDTO, 'Órgão', 'SiglaOrgao', $arrObjUsuarioDTO) . '</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';
    for ($i = 0; $i < $numRegistros; $i++) {
      if ($arrObjUsuarioDTO[$i]->getStrSinBloqueado() == 'N') {
        if (($i + 2) % 2) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }
      } else {
        $strResultado .= '<tr class="trVermelha">';
      }


      if ($bolCheck) {
        $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjUsuarioDTO[$i]->getNumIdUsuario(), $arrObjUsuarioDTO[$i]->getStrSigla()) . '</td>';
      }
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '</td>';
      $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrIdOrigem()) . '</td>';
      $strResultado .= '<td align="center"><a alt="' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()) . '" title="' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrSigla()) . '</a></td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrNomeRegistroCivil()) . '</td>';
      $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrNomeSocial()) . '</td>';
      $strResultado .= '<td align="center"><a alt="' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()) . '" title="' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjUsuarioDTO[$i]->getStrSiglaOrgao()) . '</a></td>';
      $strResultado .= '<td align="center" valign="center">';

      $strResultado .= PaginaSip::getInstance()->getAcaoTransportarItem($i, $arrObjUsuarioDTO[$i]->getNumIdUsuario());

      if ($bolAcaoBloquear && $arrObjUsuarioDTO[$i]->getStrSinBloqueado() == 'N') {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_bloquear&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/bloquear_usuario.svg" title="Bloquear Usuário" alt="Bloquear Usuário" class="infraImg" /></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoDesbloquear && $arrObjUsuarioDTO[$i]->getStrSinBloqueado() == 'S') {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_desbloquear&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/desbloquear_usuario.svg" title="Desbloquear Usuário" alt="Desbloquear Usuário" class="infraImg" /></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoPausar2fa && ($arrObjUsuarioDTO[$i]->getDthPausa2fa() == null || InfraData::compararDataHorasSimples($arrObjUsuarioDTO[$i]->getDthPausa2fa(), InfraData::getStrDataHoraAtual()) > 0)) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_pausar_2fa&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/2fa_pausar.svg" title="Pausar Autenticação em 2 Fatores" alt="Pausar Autenticação em 2 Fatores" class="infraImg"/></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoRemoverPausa2fa && $arrObjUsuarioDTO[$i]->getDthPausa2fa() != null && InfraData::compararDataHorasSimples(InfraData::getStrDataHoraAtual(), $arrObjUsuarioDTO[$i]->getDthPausa2fa()) > 0) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_remover_pausa_2fa&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/2fa_remover_pausa.svg" title="Remover Pausa da Autenticação em 2 Fatores" alt="Remover Pausa da Autenticação em 2 Fatores" class="infraImg"/></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoConsultar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeConsultar() . '" title="Consultar Usuário" alt="Consultar Usuário" class="infraImg" /></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoAlterar) {
        $strResultado .= '<a href="' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=usuario_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario()) . '" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeAlterar() . '" title="Alterar Usuário" alt="Alterar Usuário" class="infraImg" /></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoLoginListar) {
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);abrirJanelaAcessoUsuario(\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=login_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '&pagina_simples=1') . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/cadeado_aberto.svg" title="Acessos" alt="Acessos" class="infraImg"/></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoCodigoAcessoListar) {
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);abrirJanelaCodigoAcessoUsuario(\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=codigo_acesso_listar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_usuario=' . $arrObjUsuarioDTO[$i]->getNumIdUsuario() . '&pagina_simples=1') . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getDiretorioSvgLocal() . '/2fa.svg" title="Habilitações de Autenticação em 2 Fatores" alt="Habilitações de Autenticação em 2 Fatores" class="infraImg"/></a>&nbsp;&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
        $strId = $arrObjUsuarioDTO[$i]->getNumIdUsuario();
        $strDescricao = PaginaSip::getInstance()->formatarParametrosJavaScript($arrObjUsuarioDTO[$i]->getStrSigla());
      }

      if ($bolAcaoDesativar) {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeDesativar() . '" title="Desativar Usuário" alt="Desativar Usuário" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar) {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeReativar() . '" title="Reativar Usuário" alt="Reativar Usuário" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir) {
        $strResultado .= '<a href="#ID-' . $strId . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSip::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSip::getInstance()->getIconeExcluir() . '" title="Excluir Usuário" alt="Excluir Usuário" class="infraImg" /></a>&nbsp;';
      }


      $strResultado .= '</td></tr>' . "\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'usuario_selecionar') {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  } else {
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('', 'Todos', $numIdOrgao);
  $strItensSelSituacaoUsuario = UsuarioINT::montarSelectSituacao('', 'Todos', $strSituacaoUsuario);
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

  #lblOrgaoUsuario {position:absolute;left:0%;top:0%;width:15%;}
  #selOrgaoUsuario {position:absolute;left:0%;top:20%;width:15%;}

  #lblSiglaUsuario {position:absolute;left:16%;top:0%;width:15%;}
  #txtSiglaUsuario {position:absolute;left:16%;top:20%;width:15%;}

  #lblNomeRegistroCivilUsuario {position:absolute;left:33%;top:0%;width:35%;}
  #txtNomeRegistroCivilUsuario {position:absolute;left:33%;top:20%;width:35%;}

  #lblSituacaoUsuario {position:absolute;left:69%;top:0%;}
  #selSituacaoUsuario {position:absolute;left:69%;top:20%;width:25%;}

  #lblIdOrigemUsuario {position:absolute;left:0%;top:50%;width:14%;}
  #txtIdOrigemUsuario {position:absolute;left:0%;top:70%;width:14%;}

  #lblCpfUsuario {position:absolute;left:16%;top:50%;width:15%;}
  #txtCpfUsuario {position:absolute;left:16%;top:70%;width:15%;}

  #lblNomeSocialUsuario {position:absolute;left:33%;top:50%;width:35%;}
  #txtNomeSocialUsuario {position:absolute;left:33%;top:70%;width:35%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='usuario_selecionar'){
  infraReceberSelecao();
  document.getElementById('btnFecharSelecao').focus();
  }else{
  document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
  }

<?
if ($bolAcaoDesativar) { ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Usuário \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUsuarioLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmUsuarioLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Usuário selecionado.');
  return;
  }
  if (confirm("Confirma desativação dos Usuários selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUsuarioLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmUsuarioLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoReativar) { ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Usuário \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUsuarioLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmUsuarioLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Usuário selecionado.');
  return;
  }
  if (confirm("Confirma reativação dos Usuários selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUsuarioLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmUsuarioLista').submit();
  }
  }
  <?
} ?>

<?
if ($bolAcaoExcluir) { ?>
  function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Usuário \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmUsuarioLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmUsuarioLista').submit();
  }
  }

  function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Usuário selecionado.');
  return;
  }
  if (confirm("Confirma exclusão dos Usuários selecionados?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmUsuarioLista').action='<?=$strLinkExcluir?>';
  document.getElementById('frmUsuarioLista').submit();
  }
  }
  <?
} ?>

  function abrirJanelaAcessoUsuario(link){
  infraAbrirJanelaModal(link,900,600);
  }

  function abrirJanelaCodigoAcessoUsuario(link){
  infraAbrirJanelaModal(link,900,600);
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmUsuarioLista" method="post" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>
    <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelOpcional">Órgã<span
        class="infraTeclaAtalho">o</span>:</label>
    <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>

    <label id="lblSiglaUsuario" for="txtSiglaUsuario" accesskey="S" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">S</span>igla:</label>
    <input type="text" id="txtSiglaUsuario" name="txtSiglaUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($strSiglaPesquisa)?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblNomeRegistroCivilUsuario" for="txtNomeRegistroCivilUsuario" accesskey="N"
           class="infraLabelOpcional"><span class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNomeRegistroCivilUsuario" name="txtNomeRegistroCivilUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($strNomeRegistroCivilPesquisa)?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblSituacaoUsuario" for="selSituacaoUsuario" accesskey="" class="infraLabelOpcional">Situação:</label>
    <select id="selSituacaoUsuario" name="selSituacaoUsuario" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelSituacaoUsuario;?>
    </select>

    <label id="lblIdOrigemUsuario" for="txtIdOrigemUsuario" accesskey="" class="infraLabelOpcional">ID Origem:</label>
    <input type="text" id="txtIdOrigemUsuario" name="txtIdOrigemUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($strIdOrigemPesquisa);?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblCpfUsuario" for="txtCpfUsuario" class="infraLabelOpcional">CPF:</label>
    <input type="text" id="txtCpfUsuario" name="txtCpfUsuario" onkeypress="return infraMascaraCpf(this, event)"
           class="infraText" value="<?=PaginaSip::tratarHTML(InfraUtil::formatarCpf($strCpfPesquisa));?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblNomeSocialUsuario" for="txtNomeSocialUsuario" accesskey="N" class="infraLabelOpcional">Nome
      Social:</label>
    <input type="text" id="txtNomeSocialUsuario" name="txtNomeSocialUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($strNomeSocialPesquisa)?>" maxlength="50"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>


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
?>