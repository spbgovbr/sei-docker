<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/09/2022 - criado por mgb29
 *
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_plano_trabalho'));

  switch ($_GET['acao']) {
    case 'etapa_trabalho_excluir':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjEtapaTrabalhoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
          $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho($arrStrIds[$i]);
          $arrObjEtapaTrabalhoDTO[] = $objEtapaTrabalhoDTO;
        }
        $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
        $objEtapaTrabalhoRN->excluir($arrObjEtapaTrabalhoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $_GET['id_plano_trabalho']));
      die;


    case 'etapa_trabalho_desativar':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjEtapaTrabalhoDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
          $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho($arrStrIds[$i]);
          $arrObjEtapaTrabalhoDTO[] = $objEtapaTrabalhoDTO;
        }
        $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
        $objEtapaTrabalhoRN->desativar($arrObjEtapaTrabalhoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $_GET['id_plano_trabalho']));
      die;

    case 'etapa_trabalho_reativar':
      $strTitulo = 'Reativar Etapas de Trabalho';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjEtapaTrabalhoDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
            $objEtapaTrabalhoDTO->setNumIdEtapaTrabalho($arrStrIds[$i]);
            $arrObjEtapaTrabalhoDTO[] = $objEtapaTrabalhoDTO;
          }
          $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
          $objEtapaTrabalhoRN->reativar($arrObjEtapaTrabalhoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $_GET['id_plano_trabalho']));
        die;
      }
      break;

    case 'item_etapa_excluir':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjItemEtapaDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objItemEtapaDTO = new ItemEtapaDTO();
          $objItemEtapaDTO->setNumIdItemEtapa(explode('-', $arrStrIds[$i])[1]);
          $arrObjItemEtapaDTO[] = $objItemEtapaDTO;
        }
        $objItemEtapaRN = new ItemEtapaRN();
        $objItemEtapaRN->excluir($arrObjItemEtapaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $_GET['id_plano_trabalho']));
      die;


    case 'item_etapa_desativar':
      try {
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjItemEtapaDTO = array();
        for ($i = 0; $i < count($arrStrIds); $i++) {
          $objItemEtapaDTO = new ItemEtapaDTO();
          $objItemEtapaDTO->setNumIdItemEtapa(explode('-', $arrStrIds[$i])[1]);
          $arrObjItemEtapaDTO[] = $objItemEtapaDTO;
        }
        $objItemEtapaRN = new ItemEtapaRN();
        $objItemEtapaRN->desativar($arrObjItemEtapaDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      } catch (Exception $e) {
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $_GET['id_plano_trabalho']));
      die;

    case 'item_etapa_reativar':
      $strTitulo = 'Reativar Itens da Etapa';
      if ($_GET['acao_confirmada'] == 'sim') {
        try {
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjItemEtapaDTO = array();
          for ($i = 0; $i < count($arrStrIds); $i++) {
            $objItemEtapaDTO = new ItemEtapaDTO();
            $objItemEtapaDTO->setNumIdItemEtapa(explode('-', $arrStrIds[$i])[1]);
            $arrObjItemEtapaDTO[] = $objItemEtapaDTO;
          }
          $objItemEtapaRN = new ItemEtapaRN();
          $objItemEtapaRN->reativar($arrObjItemEtapaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        } catch (Exception $e) {
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $_GET['id_plano_trabalho']));
        die;
      }
      break;

    case 'plano_trabalho_configurar':
      $strTitulo = 'Configurar Plano de Trabalho';
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $arrComandos = array();

  $objPlanoTrabalhoDTO = new PlanoTrabalhoDTO();
  $objPlanoTrabalhoDTO->setBolExclusaoLogica(false);
  $objPlanoTrabalhoDTO->retNumIdPlanoTrabalho();
  $objPlanoTrabalhoDTO->retStrNome();

  if (isset($_GET['id_plano_trabalho'])) {
    $numIdPlanoTrabalho = $_GET['id_plano_trabalho'];
  } else {
    $numIdPlanoTrabalho = $_POST['selPlanoTrabalho'];
  }

  $objPlanoTrabalhoDTO->setNumIdPlanoTrabalho($numIdPlanoTrabalho);

  $objPlanoTrabalhoRN = new PlanoTrabalhoRN();
  $objPlanoTrabalhoDTO = $objPlanoTrabalhoRN->consultar($objPlanoTrabalhoDTO);

  if ($objPlanoTrabalhoDTO != null) {
    $bolAcaoCadastrarEtapaTrabalho = SessaoSEI::getInstance()->verificarPermissao('etapa_trabalho_cadastrar');
    if ($bolAcaoCadastrarEtapaTrabalho) {
      $arrComandos[] = '<button type="button" id="btnNova" value="Nova Etapa" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()) . '\'" class="infraButton">Nova Etapa</button>';
    }

    $objEtapaTrabalhoDTO = new EtapaTrabalhoDTO();
    $objEtapaTrabalhoDTO->setBolExclusaoLogica(false);
    $objEtapaTrabalhoDTO->retNumIdEtapaTrabalho();
    $objEtapaTrabalhoDTO->retStrNome();
    $objEtapaTrabalhoDTO->retNumOrdem();
    $objEtapaTrabalhoDTO->retStrSinAtivo();
    $objEtapaTrabalhoDTO->setNumIdPlanoTrabalho($numIdPlanoTrabalho);
    $objEtapaTrabalhoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objEtapaTrabalhoRN = new EtapaTrabalhoRN();
    $arrObjEtapaTrabalhoDTO = $objEtapaTrabalhoRN->listar($objEtapaTrabalhoDTO);

    $numRegistrosEtapas = count($arrObjEtapaTrabalhoDTO);
    $numRegistrosItens = 0;

    if ($numRegistrosEtapas) {
      $objItemEtapaDTO = new ItemEtapaDTO();
      $objItemEtapaDTO->setBolExclusaoLogica(false);
      $objItemEtapaDTO->retNumIdEtapaTrabalho();
      $objItemEtapaDTO->retNumIdItemEtapa();
      $objItemEtapaDTO->retStrNome();
      $objItemEtapaDTO->retNumOrdem();
      $objItemEtapaDTO->retStrSinAtivo();
      $objItemEtapaDTO->setNumIdEtapaTrabalho(InfraArray::converterArrInfraDTO($arrObjEtapaTrabalhoDTO, 'IdEtapaTrabalho'), InfraDTO::$OPER_IN);
      $objItemEtapaDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objItemEtapaRN = new ItemEtapaRN();
      $arrObjItemEtapaDTOTodas = $objItemEtapaRN->listar($objItemEtapaDTO);

      $numRegistrosItens = count($arrObjItemEtapaDTOTodas);

      $arrObjItemEtapaDTOTodas = InfraArray::indexarArrInfraDTO($arrObjItemEtapaDTOTodas, 'IdEtapaTrabalho', true);

      $bolAcaoReativarEtapaTrabalho = SessaoSEI::getInstance()->verificarPermissao('etapa_trabalho_reativar');
      $bolAcaoConsultarEtapaTrabalho = SessaoSEI::getInstance()->verificarPermissao('etapa_trabalho_consultar');
      $bolAcaoAlterarEtapaTrabalho = SessaoSEI::getInstance()->verificarPermissao('etapa_trabalho_alterar');
      $bolAcaoExcluirEtapaTrabalho = SessaoSEI::getInstance()->verificarPermissao('etapa_trabalho_excluir');
      $bolAcaoDesativarEtapaTrabalho = SessaoSEI::getInstance()->verificarPermissao('etapa_trabalho_desativar');

      $bolAcaoCadastrarItemEtapa = SessaoSEI::getInstance()->verificarPermissao('item_etapa_cadastrar');
      $bolAcaoReativarItemEtapa = SessaoSEI::getInstance()->verificarPermissao('item_etapa_reativar');
      $bolAcaoConsultarItemEtapa = SessaoSEI::getInstance()->verificarPermissao('item_etapa_consultar');
      $bolAcaoAlterarItemEtapa = SessaoSEI::getInstance()->verificarPermissao('item_etapa_alterar');
      $bolAcaoExcluirItemEtapa = SessaoSEI::getInstance()->verificarPermissao('item_etapa_excluir');
      $bolAcaoDesativarItemEtapa = SessaoSEI::getInstance()->verificarPermissao('item_etapa_desativar');


      if ($bolAcaoDesativarEtapaTrabalho) {
        $strLinkDesativarEtapaTrabalho = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_desativar&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      }

      if ($bolAcaoReativarEtapaTrabalho) {
        $strLinkReativarEtapaTrabalho = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      }

      if ($bolAcaoExcluirEtapaTrabalho) {
        $strLinkExcluirEtapaTrabalho = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_excluir&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      }

      if ($bolAcaoDesativarItemEtapa) {
        $strLinkDesativarItemEtapa = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_desativar&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      }

      if ($bolAcaoReativarItemEtapa) {
        $strLinkReativarItemEtapa = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      }

      if ($bolAcaoExcluirItemEtapa) {
        $strLinkExcluirItemEtapa = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_excluir&acao_origem=' . $_GET['acao'] . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho());
      }


      $strResultado = '';

      $strSumarioTabela = 'Tabela de Itens.';
      $strCaptionTabela = 'Itens';

      $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '" style="background-color:white;">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistrosItens) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%" style="display:none">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th colspan="2" class="infraTh">Nome</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Ordem</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">ID</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="20%">Ações</th>' . "\n";
      $strResultado .= '</tr>' . "\n";

      $n = 0;

      foreach ($arrObjEtapaTrabalhoDTO as $objEtapaTrabalhoDTO) {
        if (!isset($arrObjItemEtapaDTOTodas[$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho()])) {
          $arrObjItemEtapaDTO = array();
        } else {
          $arrObjItemEtapaDTO = $arrObjItemEtapaDTOTodas[$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho()];
        }

        if ($objEtapaTrabalhoDTO->getStrSinAtivo() == 'N') {
          $strResultado .= '<tr class="trVermelha">';
        } else {
          $strResultado .= '<tr class="infraTrEscura">';
        }

        $strResultado .= '<td style="display:none">' . PaginaSEI::getInstance()->getTrCheck($n++, $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho(), $objEtapaTrabalhoDTO->getStrNome()) . '</td>' . "\n";
        $strResultado .= '<td colspan="2"><span class="textoEtapa">' . PaginaSEI::tratarHTML($objEtapaTrabalhoDTO->getStrNome()) . '</span></td>' . "\n";
        $strResultado .= '<td align="center"><span class="textoEtapa">' . $objEtapaTrabalhoDTO->getNumOrdem() . '</span></td>' . "\n";
        $strResultado .= '<td align="center"><span class="textoEtapa">'.$objEtapaTrabalhoDTO->getNumIdEtapaTrabalho().'</span></td>' . "\n";
        $strResultado .= '<td align="center">' . "\n";

        if ($bolAcaoCadastrarItemEtapa) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeMais() . '" title="Adicionar Item" alt="Adicionar Item" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoConsultarEtapaTrabalho) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Etapa de Trabalho" alt="Consultar Etapa de Trabalho" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoAlterarEtapaTrabalho) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=etapa_trabalho_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Etapa de Trabalho" alt="Alterar Etapa de Trabalho" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoDesativarEtapaTrabalho || $bolAcaoReativarEtapaTrabalho || $bolAcaoExcluirEtapaTrabalho) {
          $strId = $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objEtapaTrabalhoDTO->getStrNome());
        }

        if ($bolAcaoDesativarEtapaTrabalho && $objEtapaTrabalhoDTO->getStrSinAtivo() == 'S') {
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativarEtapaTrabalho(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeDesativar() . '" title="Desativar Etapa de Trabalho" alt="Desativar Etapa de Trabalho" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoReativarEtapaTrabalho && $objEtapaTrabalhoDTO->getStrSinAtivo() == 'N') {
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativarEtapaTrabalho(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeReativar() . '" title="Reativar Etapa de Trabalho" alt="Reativar Etapa de Trabalho" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoExcluirEtapaTrabalho && count($arrObjItemEtapaDTO) == 0) {
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluirEtapaTrabalho(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Etapa de Trabalho" alt="Excluir Etapa de Trabalho" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '&nbsp;</td>' . "\n";
        $strResultado .= '</tr>' . "\n";

        /** @var ItemEtapaDTO $objItemEtapaDTO */
        foreach ($arrObjItemEtapaDTO as $objItemEtapaDTO) {
          if ($objItemEtapaDTO->getStrSinAtivo() == 'N') {
            $strResultado .= '<tr class="trVermelha">';
          } else {
            $strResultado .= '<tr class="infraTrClara">';
          }

          $strResultado .= '<td style="display:none">' . PaginaSEI::getInstance()->getTrCheck($n++, $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '-' . $objItemEtapaDTO->getNumIdItemEtapa(),
              $objItemEtapaDTO->getStrNome()) . '</td>' . "\n";
          $strResultado .= '<td width="1%">&nbsp;</td>' . "\n";
          $strResultado .= '<td>' . PaginaSEI::tratarHTML($objItemEtapaDTO->getStrNome()) . '</td>' . "\n";
          $strResultado .= '<td align="center">' . $objItemEtapaDTO->getNumOrdem() . '</td>' . "\n";
          $strResultado .= '<td align="center">' . $objItemEtapaDTO->getNumIdItemEtapa() . '</td>' . "\n";
          $strResultado .= '<td align="center">' . "\n";

          if ($bolAcaoConsultarItemEtapa) {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa() . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Item da Etapa" alt="Consultar Item da Etapa" class="infraImg" /></a>&nbsp;';
          }

          if ($bolAcaoAlterarItemEtapa) {
            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=item_etapa_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_item_etapa=' . $objItemEtapaDTO->getNumIdItemEtapa() . '&id_etapa_trabalho=' . $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '&id_plano_trabalho=' . $objPlanoTrabalhoDTO->getNumIdPlanoTrabalho()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Item da Etapa" alt="Alterar Item da Etapa" class="infraImg" /></a>&nbsp;';
          }

          if ($bolAcaoDesativarItemEtapa || $bolAcaoReativarItemEtapa || $bolAcaoExcluirItemEtapa) {
            $strId = $objEtapaTrabalhoDTO->getNumIdEtapaTrabalho() . '-' . $objItemEtapaDTO->getNumIdItemEtapa();
            $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objItemEtapaDTO->getStrNome());
          }

          if ($bolAcaoDesativarItemEtapa && $objItemEtapaDTO->getStrSinAtivo() == 'S') {
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoDesativarItemEtapa(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeDesativar() . '" title="Desativar Item da Etapa" alt="Desativar Item da Etapa" class="infraImg" /></a>&nbsp;';
          }

          if ($bolAcaoReativarItemEtapa && $objItemEtapaDTO->getStrSinAtivo() == 'N') {
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoReativarItemEtapa(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeReativar() . '" title="Reativar Item da Etapa" alt="Reativar Item da Etapa" class="infraImg" /></a>&nbsp;';
          }

          if ($bolAcaoExcluirItemEtapa) {
            $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluirItemEtapa(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Item da Etapa" alt="Excluir Item da Etapa" class="infraImg" /></a>&nbsp;';
          }

          $strResultado .= '&nbsp;</td>';
          $strResultado .= '</tr>' . "\n";
        }
      }
      $strResultado .= '</table>';
    }
  }

  $arrComandos[] = '<button type="button" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSEI::montarAncora($numIdPlanoTrabalho)) . '\'" class="infraButton">Voltar</button>';

  $strItensSelPlanoTrabalho = PlanoTrabalhoINT::montarSelectNome('null', '&nbsp;', $numIdPlanoTrabalho);
} catch (Exception $e) {
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

#lblPlanoTrabalho {position:absolute;left:0%;top:0%;width:60%;}
#selPlanoTrabalho {position:absolute;left:0%;top:40%;width:60%;}

span.textoEtapa{
font-size:.9em;
font-weight: bold;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

  function inicializar() {
    infraEfeitoTabelas();
  }

  <? if ($bolAcaoDesativarEtapaTrabalho){ ?>
  function acaoDesativarEtapaTrabalho(id, desc) {
    if (confirm("Confirma desativação da Etapa \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmPlanoTrabalhoConfigurar').action = '<?=$strLinkDesativarEtapaTrabalho?>';
      document.getElementById('frmPlanoTrabalhoConfigurar').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoReativarEtapaTrabalho){ ?>
  function acaoReativarEtapaTrabalho(id, desc) {
    if (confirm("Confirma reativação da Etapa \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmPlanoTrabalhoConfigurar').action = '<?=$strLinkReativarEtapaTrabalho?>';
      document.getElementById('frmPlanoTrabalhoConfigurar').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoExcluirEtapaTrabalho){ ?>
  function acaoExcluirEtapaTrabalho(id, desc) {
    if (confirm("Confirma exclusão da Etapa \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmPlanoTrabalhoConfigurar').action = '<?=$strLinkExcluirEtapaTrabalho?>';
      document.getElementById('frmPlanoTrabalhoConfigurar').submit();
    }
  }
  <? } ?>


  <? if ($bolAcaoDesativarItemEtapa){ ?>
  function acaoDesativarItemEtapa(id, desc) {
    if (confirm("Confirma desativação do Item \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmPlanoTrabalhoConfigurar').action = '<?=$strLinkDesativarItemEtapa?>';
      document.getElementById('frmPlanoTrabalhoConfigurar').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoReativarItemEtapa){ ?>
  function acaoReativarItemEtapa(id, desc) {
    if (confirm("Confirma reativação do Item \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmPlanoTrabalhoConfigurar').action = '<?=$strLinkReativarItemEtapa?>';
      document.getElementById('frmPlanoTrabalhoConfigurar').submit();
    }
  }
  <? } ?>

  <? if ($bolAcaoExcluirItemEtapa){ ?>
  function acaoExcluirItemEtapa(id, desc) {
    if (confirm("Confirma exclusão do Item \"" + desc + "\"?")) {
      document.getElementById('hdnInfraItemId').value = id;
      document.getElementById('frmPlanoTrabalhoConfigurar').action = '<?=$strLinkExcluirItemEtapa?>';
      document.getElementById('frmPlanoTrabalhoConfigurar').submit();
    }
  }
  <?}?>

  //</script>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
<form id="frmPlanoTrabalhoConfigurar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblPlanoTrabalho" for="selPlanoTrabalho" accesskey="" class="infraLabelOpcional">Plano de Trabalho:</label>
  <select id="selPlanoTrabalho" name="selPlanoTrabalho" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelPlanoTrabalho?>
  </select>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistrosEtapas + $numRegistrosItens);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
