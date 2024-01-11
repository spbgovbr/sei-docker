<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/05/2012 - criado por mga
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->salvarCamposPost(array('rdoStaDestino', 'selInstalacaoFederacaoEnvio', 'txtPalavrasPesquisaFederacaoEnvio', 'selGrupoFederacaoInstitucional', 'selGrupoFederacaoUnidade'));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_procedimento'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  $arrComandos = array();
  $bolEnvioSolicitado = false;
  $numRegistrosEnvios = 0;
  $strResultadoEnvios = '';
  $arrObjAcessoFederacaoDTORet = array();

  switch($_GET['acao']){
  	
  	case 'acesso_federacao_enviar':
  		
  		$strTitulo = 'Envio para o SEI Federação';

  		try{

      	$objEnviarProcessoFederacaoDTO = new EnviarProcessoFederacaoDTO();
        $objEnviarProcessoFederacaoDTO->setDblIdProcedimento($_GET['id_procedimento']);
        $objEnviarProcessoFederacaoDTO->setStrSenha($_POST['pwdSenha']);
        $objEnviarProcessoFederacaoDTO->setStrMotivo($_POST['txaMotivo']);
        $objEnviarProcessoFederacaoDTO->setNumStaTipo(AcessoFederacaoRN::$TAF_PROCESSO_ENVIADO_ORGAO);

        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();

        $numSelecionados = count($arrStrIds);

        if ($numSelecionados) {

          $arrInstalacoes = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnInstalacoesFederacao']);
          $numInstalacoes = count($arrInstalacoes);
          if ($numInstalacoes) {
            if ($_POST['hdnInstalacoesHash'] != PaginaSEI::getInstance()->gerarHashConteudo($_POST['hdnInstalacoesFederacao'])) {
              throw new InfraException('Hash das instalações inválido.');
            }
          }

          $arrOrgaos = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnOrgaosFederacao']);
          $numOrgaos = count($arrOrgaos);
          if ($numOrgaos) {
            if ($_POST['hdnOrgaosHash'] != PaginaSEI::getInstance()->gerarHashConteudo($_POST['hdnOrgaosFederacao'])) {
              throw new InfraException('Hash dos órgãos inválido.');
            }
          }

          $arrUnidades = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnUnidadesFederacao']);
          $numUnidades = count($arrUnidades);
          if ($numUnidades) {
            if ($_POST['hdnUnidadesHash'] != PaginaSEI::getInstance()->gerarHashConteudo($_POST['hdnUnidadesFederacao'])) {
              throw new InfraException('Hash das unidades inválido.');
            }
          }

          $arrObjInstalacaoFederacaoDTO = array();
          $arrObjOrgaoFederacaoDTO = array();
          $arrObjUnidadeFederacaoDTO = array();
          $arrObjAcessoFederacaoDTO = array();
          foreach ($arrStrIds as $strId) {

            //IdInstalacaoFederacao-IdOrgaoFederacao-IdUnidadeFederacao
            $arrId = explode('-', $strId);

            $strIdInstalacaoFederacao = $arrId[0];
            $strIdOrgaoFederacao = $arrId[1];
            $strIdUnidadeFederacao = $arrId[2];

            if (!isset($arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacao])) {

              $numIndiceInstalacao = null;
              for ($i = 0; $i < $numInstalacoes; $i++) {
                if ($arrInstalacoes[$i][0] == $strIdInstalacaoFederacao) {
                  $numIndiceInstalacao = $i;
                  break;
                }
              }

              if ($numIndiceInstalacao === null) {
                throw new InfraException('Instalação '.$strIdInstalacaoFederacao.' não encontrada nos dados para replicação.');
              }

              $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
              $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($arrInstalacoes[$i][0]);
              $objInstalacaoFederacaoDTO->setStrSigla($arrInstalacoes[$i][1]);
              $objInstalacaoFederacaoDTO->setStrDescricao($arrInstalacoes[$i][2]);

              $arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacao] = $objInstalacaoFederacaoDTO;
            }

            if (!isset($arrObjOrgaoFederacaoDTO[$strIdOrgaoFederacao])) {

              $numIndiceOrgao = null;
              for ($i = 0; $i < $numOrgaos; $i++) {
                if ($arrOrgaos[$i][0] == $strIdInstalacaoFederacao && $arrOrgaos[$i][1] == $strIdOrgaoFederacao) {
                  $numIndiceOrgao = $i;
                  break;
                }
              }

              if ($numIndiceOrgao === null) {
                throw new InfraException('Órgão '.$strIdOrgaoFederacao.' não encontrado nos dados para replicação.');
              }

              $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
              $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($arrOrgaos[$i][0]);
              $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($arrOrgaos[$i][1]);
              $objOrgaoFederacaoDTO->setStrSigla($arrOrgaos[$i][2]);
              $objOrgaoFederacaoDTO->setStrDescricao($arrOrgaos[$i][3]);
              $arrObjOrgaoFederacaoDTO[$strIdOrgaoFederacao] = $objOrgaoFederacaoDTO;
            }

            if (!isset($arrObjUnidadeFederacaoDTO[$strIdUnidadeFederacao])) {

              $numIndiceUnidade = null;
              for ($i = 0; $i < $numUnidades; $i++) {
                if ($arrUnidades[$i][0] == $strIdInstalacaoFederacao && $arrUnidades[$i][1] == $strIdUnidadeFederacao) {
                  $numIndiceUnidade = $i;
                  break;
                }
              }

              if ($numIndiceUnidade === null) {
                throw new InfraException('Unidade '.$strIdUnidadeFederacao.' não encontrada nos dados para replicação.');
              }

              $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
              $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($arrUnidades[$i][0]);
              $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($arrUnidades[$i][1]);
              $objUnidadeFederacaoDTO->setStrSigla($arrUnidades[$i][2]);
              $objUnidadeFederacaoDTO->setStrDescricao($arrUnidades[$i][3]);
              $arrObjUnidadeFederacaoDTO[$strIdUnidadeFederacao] = $objUnidadeFederacaoDTO;
            }

            $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
            $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoDest($strIdInstalacaoFederacao);
            $objAcessoFederacaoDTO->setStrIdOrgaoFederacaoDest($strIdOrgaoFederacao);
            $objAcessoFederacaoDTO->setStrIdUnidadeFederacaoDest($strIdUnidadeFederacao);
            $objAcessoFederacaoDTO->setStrIdUsuarioFederacaoDest(null);
            $arrObjAcessoFederacaoDTO[] = $objAcessoFederacaoDTO;
          }

          $objEnviarProcessoFederacaoDTO->setArrObjInstalacaoFederacaoDTO($arrObjInstalacaoFederacaoDTO);
          $objEnviarProcessoFederacaoDTO->setArrObjOrgaoFederacaoDTO($arrObjOrgaoFederacaoDTO);
          $objEnviarProcessoFederacaoDTO->setArrObjUnidadeFederacaoDTO($arrObjUnidadeFederacaoDTO);
          $objEnviarProcessoFederacaoDTO->setArrObjAcessoFederacaoDTO($arrObjAcessoFederacaoDTO);
        }

        if (isset($_POST['sbmEnviar']) && $numSelecionados) {

          $objAcessoFederacaoRN = new AcessoFederacaoRN();
          $objEnviarProcessoFederacaoDTORet = $objAcessoFederacaoRN->concederAcesso($objEnviarProcessoFederacaoDTO);

          $bolEnvioSolicitado = true;

          $arrObjInstalacaoFederacaoDTORet = $objEnviarProcessoFederacaoDTORet->getArrObjInstalacaoFederacaoDTO();
          $arrObjAcessoFederacaoDTORet = $objEnviarProcessoFederacaoDTORet->getArrObjAcessoFederacaoDTO();

          $bolErro = false;

          foreach($arrObjInstalacaoFederacaoDTORet as $objInstalacaoFederacaoDTO){

            foreach ($arrObjAcessoFederacaoDTO as $objAcessoFederacaoDTO) {

              if ($objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoDest() == $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()) {

                $numRegistrosEnvios++;

                $objOrgaoFederacaoDTO = $arrObjOrgaoFederacaoDTO[$objAcessoFederacaoDTO->getStrIdOrgaoFederacaoDest()];
                $objUnidadeFederacaoDTO = $arrObjUnidadeFederacaoDTO[$objAcessoFederacaoDTO->getStrIdUnidadeFederacaoDest()];

                $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
                $strResultadoEnvios .= $strCssTr;

                $strResultadoEnvios .= "\n".'<td align="center"  valign="top">';
                $strResultadoEnvios .= '<a alt="' . PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrDescricao()) . '" title="' . PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrDescricao()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrSigla()).'</a>';
                $strResultadoEnvios .= '</td>'."\n";

                $e = $objInstalacaoFederacaoDTO->getObjInfraException();

                $strResultadoEnvios .= "\n".'<td align="center"  valign="top">';
                if ($e != null) {
                  $bolErro = true;
                  $strResultadoEnvios .= 'Erro';
                } else {
                  $strResultadoEnvios .= 'Enviado';
                }
                $strResultadoEnvios .= '</td>'."\n";

                $strResultadoEnvios .= "\n".'<td align="left"  valign="top">';
                if ($e != null) {
                  if ($e instanceof InfraException && $e->contemValidacoes()) {
                    $strResultadoEnvios .= nl2br(PaginaSEI::tratarHTML(str_replace('\n',"\n",$e->__toString())));
                  } else {
                    $strResultadoEnvios .= nl2br(PaginaSEI::tratarHTML($e->__toString()."\n".InfraString::limparParametrosPhp($e->getTraceAsString())));
                  }
                } else {
                  $strResultadoEnvios .= '&nbsp;';
                }
                $strResultadoEnvios .= '</td>'."\n";

                $strResultadoEnvios .= "\n".'<td align="center"  valign="top">';
                $strResultadoEnvios .= '<a alt="' . PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao()) . '" title="' . PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrSigla()).'</a>';
                $strResultadoEnvios .= '</td>'."\n";

              }
            }
          }

          if (!$bolErro){

            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_federacao_gerenciar&acao_origem='.$_GET['acao'].'&id_procedimento_federacao='.$objEnviarProcessoFederacaoDTORet->getObjProtocoloFederacaoDTO()->getStrIdProtocoloFederacao().'&resultado=1'.PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjAcessoFederacaoDTORet,'IdAcessoFederacao'))));
            die;

          }else{

            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo Envio" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_federacao_enviar&acao_origem='.$_GET['acao'].'&acao_retorno=acesso_federacao_gerenciar').'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo Envio</button>';
            $arrComandos[] = '<button type="button" accesskey="L" id="btnListar" value="Listar Envios" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acesso_federacao_gerenciar&acao_origem='.$_GET['acao'].'&id_procedimento_federacao='.$objEnviarProcessoFederacaoDTORet->getObjProtocoloFederacaoDTO()->getStrIdProtocoloFederacao().PaginaSEI::getInstance()->montarAncora(InfraArray::converterArrInfraDTO($arrObjAcessoFederacaoDTORet,'IdAcessoFederacao'))).'\'" class="infraButton"><span class="infraTeclaAtalho">L</span>istar Envios</button>';

            $strSumarioTabela = 'Tabela de resultado do Envio para o SEI Federação.';
            $strCaptionTabela = 'Resultado do Envio para o SEI Federação';

            $strResultadoEnvios = '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n".
                                  '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros).'</caption>'.
                                  '<tr>'.
                                  '<th class="infraTh" width="15%">Órgão</th>'."\n".
                                  '<th class="infraTh" width="15%">Situação</th>'."\n".
                                  '<th class="infraTh">Detalhes</th>'."\n".
                                  '<th class="infraTh" width="15%">Instalação</th>'."\n".
                                  '</tr>'."\n".
                                   $strResultadoEnvios.
                                  '</table>';
          }
        }

  		}catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e, true);
  		}
  		break;

	    default:
	      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $numRegistros = 0;
  $strResultado = '';
  $strInstalacoesFederacao = '';
  $strInstalacoesHash = '';
  $strOrgaosFederacao = '';
  $strOrgaosHash = '';
  $strUnidadesFederacao = '';
  $strUnidadesHash = '';
  $strMsg = '';

  $strStaDestino = PaginaSEI::getInstance()->recuperarCampo('rdoStaDestino', 'P');
  $numIdInstalacaoFederacaoEnvio = PaginaSEI::getInstance()->recuperarCampo('selInstalacaoFederacaoEnvio');
  $numIdGrupoFederacaoInstitucional = PaginaSEI::getInstance()->recuperarCampo('selGrupoFederacaoInstitucional');
  $numIdGrupoFederacaoUnidade = PaginaSEI::getInstance()->recuperarCampo('selGrupoFederacaoUnidade');
  $strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaFederacaoEnvio');

  if (!$bolEnvioSolicitado) {

    $arrComandos[] = '<button type="submit" name="sbmEnviar" id="sbmEnviar" value="Enviar" class="infraButton">Enviar</button>';

    $numInstalacoes = 0;

    if ($_GET['acao_origem']=='acesso_federacao_enviar') {

      if ($strStaDestino == 'P') {
        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();

        if ($numIdInstalacaoFederacaoEnvio != '') {
          $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoDest($numIdInstalacaoFederacaoEnvio);
        }

        $objAcessoFederacaoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $arrObjInstalacaoFederacaoDTO = $objAcessoFederacaoRN->pesquisarOrgaosUnidadesEnvio($objAcessoFederacaoDTO);
        $numInstalacoes = count($arrObjInstalacaoFederacaoDTO);

      }else if ($strStaDestino == 'I' || $strStaDestino == 'U'){

        $objGrupoFederacaoDTO = new GrupoFederacaoDTO();
        if ($strStaDestino == 'I') {
          $objGrupoFederacaoDTO->setNumIdGrupoFederacao($numIdGrupoFederacaoInstitucional);
        }else{
          $objGrupoFederacaoDTO->setNumIdGrupoFederacao($numIdGrupoFederacaoUnidade);
        }

        $objGrupoFederacaoRN = new GrupoFederacaoRN();
        $arrObjInstalacaoFederacaoDTO = $objGrupoFederacaoRN->pesquisar($objGrupoFederacaoDTO);
        $numInstalacoes = count($arrObjInstalacaoFederacaoDTO);
      }
    }


    if ($numInstalacoes) {

      $arrObjInstalacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($arrObjInstalacaoFederacaoDTO, 'IdInstalacaoFederacao');

      $arrInstalacoesFederacao = array();
      $arrOrgaosFederacao = array();
      $arrObjOrgaoFederacaoDTO = array();
      $arrUnidadesFederacao = array();
      $arrObjUnidadeFederacaoDTO = array();
      $arrObjOrgaoFederacaoDTOUnidade = array();
      $arrObjInstalacaoFederacaoDTOErro = array();
      $arrObjOrgaoFederacaoDTOErro = array();
      foreach ($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO) {
        if ($objInstalacaoFederacaoDTO->getObjInfraException() == null) {
          $arrInstalacoesFederacao[$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()] = array($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao(), $objInstalacaoFederacaoDTO->getStrSigla(), $objInstalacaoFederacaoDTO->getStrDescricao());
          foreach ($objInstalacaoFederacaoDTO->getArrObjOrgaoFederacaoDTO() as $objOrgaoFederacaoDTO) {
            if ($objOrgaoFederacaoDTO->getObjInfraException() == null) {
              if (!isset($arrOrgaosFederacao[$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao()])) {
                $arrOrgaosFederacao[$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao()] = array($objOrgaoFederacaoDTO->getStrIdInstalacaoFederacao(), $objOrgaoFederacaoDTO->getStrIdOrgaoFederacao(), $objOrgaoFederacaoDTO->getStrSigla(), $objOrgaoFederacaoDTO->getStrDescricao());
                $arrObjOrgaoFederacaoDTO[] = $objOrgaoFederacaoDTO;
              }

              foreach ($objOrgaoFederacaoDTO->getArrObjUnidadeFederacaoDTO() as $objUnidadeFederacaoDTO) {
                if (!isset($arrUnidadesFederacao[$objUnidadeFederacaoDTO->getStrIdUnidadeFederacao()])) {
                  $arrUnidadesFederacao[$objUnidadeFederacaoDTO->getStrIdUnidadeFederacao()] = array($objUnidadeFederacaoDTO->getStrIdInstalacaoFederacao(), $objUnidadeFederacaoDTO->getStrIdUnidadeFederacao(), $objUnidadeFederacaoDTO->getStrSigla(), $objUnidadeFederacaoDTO->getStrDescricao());
                  $arrObjOrgaoFederacaoDTOUnidade[$objUnidadeFederacaoDTO->getStrIdUnidadeFederacao()] = $objOrgaoFederacaoDTO;
                  $arrObjUnidadeFederacaoDTO[] = $objUnidadeFederacaoDTO;
                }
              }
            }else{
              $arrObjOrgaoFederacaoDTOErro[] = $objOrgaoFederacaoDTO;
            }
          }
        }else{
          $arrObjInstalacaoFederacaoDTOErro[] = $objInstalacaoFederacaoDTO;
        }
      }
      $strInstalacoesFederacao = PaginaSEI::getInstance()->gerarItensTabelaDinamica(array_values($arrInstalacoesFederacao));
      $strInstalacoesHash = PaginaSEI::getInstance()->gerarHashConteudo($strInstalacoesFederacao);
      $strOrgaosFederacao = PaginaSEI::getInstance()->gerarItensTabelaDinamica(array_values($arrOrgaosFederacao));
      $strOrgaosHash = PaginaSEI::getInstance()->gerarHashConteudo($strOrgaosFederacao);
      $strUnidadesFederacao = PaginaSEI::getInstance()->gerarItensTabelaDinamica(array_values($arrUnidadesFederacao));
      $strUnidadesHash = PaginaSEI::getInstance()->gerarHashConteudo($strUnidadesFederacao);

      $strMsg .= SeiINT::montarMensagemErroFederacao($arrObjInstalacaoFederacaoDTOErro, 'Não foi possível listar os órgãos da instalação', 'Não foi possível listar os órgãos das instalações');
      $strMsg .= SeiINT::montarMensagemErroFederacao($arrObjOrgaoFederacaoDTOErro, 'Não foi possível localizar o órgão', 'Não foi possível localizar os órgãos');

      $numRegistros = count($arrObjOrgaoFederacaoDTO);

      if ($numRegistros > 0) {

        InfraArray::ordenarArrInfraDTO($arrObjOrgaoFederacaoDTO, 'Sigla', InfraArray::$TIPO_ORDENACAO_ASC);

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrIdProtocoloFederacao();
        $objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        $arrIdOrgaoFederacaoAcesso = array();

        if ($objProtocoloDTO->getStrIdProtocoloFederacao()!=null) {
          $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
          $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());

          $objAcessoFederacaoRN = new AcessoFederacaoRN();
          $arrIdOrgaoFederacaoAcesso = InfraArray::converterArrInfraDTO($objAcessoFederacaoRN->obterOrgaosAcessoFederacao($objAcessoFederacaoDTO), 'IdOrgaoFederacao');
        }

        $strResultado = '';

        $strSumarioTabela = 'Tabela de Órgãos do SEI Federação.';
        $strCaptionTabela = 'Órgãos do SEI Federação';

        $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
        $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros).'</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Sigla</th>'."\n";
        $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Unidade Recebimento</th>'."\n";
        $strResultado .= '<th class="infraTh" width="20%">Instalação</th>'."\n";
        $strResultado .= '</tr>'."\n";
        $strCssTr = '';

        $n = 0;

        $arrObjUnidadeFederacaoDTO = InfraArray::indexarArrInfraDTO($arrObjUnidadeFederacaoDTO, 'IdUnidadeFederacao');

        foreach ($arrObjOrgaoFederacaoDTO as $objOrgaoFederacaoDTO) {

          $objInstalacaoFederacaoDTO = $arrObjInstalacaoFederacaoDTO[$objOrgaoFederacaoDTO->getStrIdInstalacaoFederacao()];
          $objUnidadeFederacaoDTO = $arrObjUnidadeFederacaoDTO[$objOrgaoFederacaoDTO->getArrObjUnidadeFederacaoDTO()[0]->getStrIdUnidadeFederacao()];

          $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
          $strResultado .= $strCssTr;

          $strResultado .= "\n".'<td valign="top">';
          if (!in_array($objOrgaoFederacaoDTO->getStrIdOrgaoFederacao(), $arrIdOrgaoFederacaoAcesso)) {
            $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++, $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao().'-'.$objOrgaoFederacaoDTO->getStrIdOrgaoFederacao().'-'.$objUnidadeFederacaoDTO->getStrIdUnidadeFederacao(), $objOrgaoFederacaoDTO->getStrSigla());
          }else{
            $strResultado .= '&nbsp;';
          }
          $strResultado .= '</td>';

          $strResultado .= "\n".'<td align="center"  valign="top">'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrSigla()).'</td>';
          $strResultado .= "\n".'<td align="left"  valign="top">'.PaginaSEI::tratarHTML($objOrgaoFederacaoDTO->getStrDescricao()).'</td>';

          $strResultado .= "\n".'<td align="center"  valign="top">';
          $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objUnidadeFederacaoDTO->getStrDescricao()).'" title="'.PaginaSEI::tratarHTML($objUnidadeFederacaoDTO->getStrDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objUnidadeFederacaoDTO->getStrSigla()).'</a>';
          $strResultado .= '</td>'."\n";

          $strResultado .= "\n".'<td align="center"  valign="top">';
          $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao()).'" title="'.PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrSigla()).'</a>';
          $strResultado .= '</td>'."\n";

          $strResultado .= '</tr>'."\n";
        }
        $strResultado .= '</table>';
      }
    }

    $strItensSelInstalacaoFederacaoEnvio = InstalacaoFederacaoINT::montarSelectSigla('', 'Todas', $numIdInstalacaoFederacaoEnvio);
    $strItensSelGrupoFederacaoInstitucional = GrupoFederacaoINT::montarSelectNomeInstitucional('null', '&nbsp;', $numIdGrupoFederacaoInstitucional);
    $strItensSelGrupoFederacaoUnidade = GrupoFederacaoINT::montarSelectNomeUnidade('null', '&nbsp;', $numIdGrupoFederacaoUnidade);
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

#frmAcessoFederacaoEnvio{max-width: 1000px;}

#divMotivo {display:none;}
#lblMotivo {position:absolute;left:0%;top:0%;}
#txaMotivo {position:absolute;left:0%;top:22%;width:99%;}

#divSenha {display:none;}
#lblSenha {position:absolute;left:0%;top:5%;}
#pwdSenha {position:absolute;left:0%;top:43%;width:20%;}

#divDestino {display:none}
#fldStaDestino {position:absolute;left:0%;top:10%;height:70%;width:99%;}
#divOptPesquisa {position:absolute;left:10%;top:45%;}
#divOptGrupoFederacaoInstitucional {position:absolute;left:40%;top:45%;}
#divOptGrupoFederacaoUnidade {position:absolute;left:70%;top:45%;}

#divPesquisa {display:none}
#lblInstalacaoFederacaoEnvio {position:absolute;left:0%;top:5%;display:none;}
#selInstalacaoFederacaoEnvio {position:absolute;left:0%;top:43%;width:50%;display:none;}
#lblPalavrasPesquisaFederacaoEnvio {position:absolute;left:51%;top:5%;}
#txtPalavrasPesquisaFederacaoEnvio {position:absolute;left:51%;top:43%;width:30%;}
#lblGrupoFederacaoInstitucional {position:absolute;left:0%;top:5%;display:none;}
#selGrupoFederacaoInstitucional {position:absolute;left:0%;top:43%;width:82%;display:none;}
#lblGrupoFederacaoUnidade {position:absolute;left:0%;top:5%;display:none;}
#selGrupoFederacaoUnidade {position:absolute;left:0%;top:43%;width:82%;display:none;}
#divBotaoPesquisa {position:absolute;left:83%;top:37%;}

<?
PaginaSEI::getInstance()->fecharStyle();

if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
  PaginaSEI::getInstance()->abrirStyle();
  ?>
  #divOptPesquisa {top:20%;}
  #divOptGrupoFederacaoInstitucional {top:20%;}
  #divOptGrupoFederacaoUnidade {top:20%;}
  <?
  PaginaSEI::getInstance()->fecharStyle();
}

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});

function inicializar(){

  parent.parent.parent.infraOcultarAviso(false);

  <? if (!$bolEnvioSolicitado) { ?>
  document.getElementById('divMotivo').style.display = 'block';
  document.getElementById('divSenha').style.display = 'block';
  document.getElementById('divDestino').style.display = 'block';
  document.getElementById('divPesquisa').style.display = 'block';
  configurarDestino(false);
  <?}?>

  infraEfeitoTabelas();
}

function configurarDestino(bolLimparResultado){

  document.getElementById('lblInstalacaoFederacaoEnvio').style.display = 'none';
  document.getElementById('selInstalacaoFederacaoEnvio').style.display = 'none';
  document.getElementById('lblPalavrasPesquisaFederacaoEnvio').style.display = 'none';
  document.getElementById('txtPalavrasPesquisaFederacaoEnvio').style.display = 'none';
  document.getElementById('lblGrupoFederacaoInstitucional').style.display = 'none';
  document.getElementById('selGrupoFederacaoInstitucional').style.display = 'none';
  document.getElementById('lblGrupoFederacaoUnidade').style.display = 'none';
  document.getElementById('selGrupoFederacaoUnidade').style.display = 'none';

  if (bolLimparResultado) {
    limparResultado();
  }

  if (document.getElementById('optPesquisa').checked) {
    document.getElementById('lblInstalacaoFederacaoEnvio').style.display = 'block';
    document.getElementById('selInstalacaoFederacaoEnvio').style.display = 'block';
    document.getElementById('lblPalavrasPesquisaFederacaoEnvio').style.display = 'block';
    document.getElementById('txtPalavrasPesquisaFederacaoEnvio').style.display = 'block';
  }else if (document.getElementById('optGrupoFederacaoInstitucional').checked){
    document.getElementById('lblGrupoFederacaoInstitucional').style.display = 'block';
    document.getElementById('selGrupoFederacaoInstitucional').style.display = 'block';
  }else if (document.getElementById('optGrupoFederacaoUnidade').checked){
    document.getElementById('lblGrupoFederacaoUnidade').style.display = 'block';
    document.getElementById('selGrupoFederacaoUnidade').style.display = 'block';
  }
}

function limparResultado(){
  if (document.getElementById('divErroFederacao')!=null) {
    document.getElementById('divErroFederacao').style.display = 'none';
  }
  if (document.getElementById('divInfraAreaTabela')!=null) {
    document.getElementById('divInfraAreaTabela').style.display = 'none';
  }
}

function OnSubmitForm(){

  if (document.getElementById('hdnInfraItensSelecionados') == null || infraTrim(document.getElementById('hdnInfraItensSelecionados').value) == '') {
    alert('Nenhum Órgão selecionado.');
    return false;
  }

  if (infraTrim(document.getElementById('pwdSenha').value) == '') {
    alert('Senha não informada.');
    document.getElementById('pwdSenha').focus();
    return false;
  }

  parent.parent.parent.infraExibirAviso(false);

  return true;
}


function pesquisar(){

  if (document.getElementById('selInstalacaoFederacaoEnvio').options.length==0){
    alert('Nenhuma instalação encontrada.');
    return false;
  }

  if (document.getElementById('optPesquisa').checked && !infraSelectSelecionado(document.getElementById('selInstalacaoFederacaoEnvio'))){
    alert('Nenhuma opção de Instalação selecionada.');
    document.getElementById('selInstalacaoFederacaoEnvio').focus();
    return false;
  }

  if (document.getElementById('optGrupoFederacaoInstitucional').checked && !infraSelectSelecionado(document.getElementById('selGrupoFederacaoInstitucional'))){
    alert('Nenhum Grupo Institucional selecionado.');
    document.getElementById('selGrupoFederacaoInstitucional').focus();
    return false;
  }

  if (document.getElementById('optGrupoFederacaoUnidade').checked && !infraSelectSelecionado(document.getElementById('selGrupoFederacaoUnidade'))){
    alert('Nenhum Grupo da Unidade selecionado.');
    document.getElementById('selGrupoFederacaoUnidade').focus();
    return false;
  }

  parent.parent.parent.infraExibirAviso(false);

  if (document.getElementById('hdnInfraItensSelecionados')!=null) {
    document.getElementById('hdnInfraItensSelecionados').value = '';
  }

  document.getElementById('frmAcessoFederacaoEnvio').submit();
}


<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcessoFederacaoEnvio" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>

  <div id="divDestino" class="infraAreaDados" style="height:8em;">
    <fieldset id="fldStaDestino" class="infraFieldset">
      <legend class="infraLegend">Destino</legend>

      <div id="divOptPesquisa" class="infraDivRadio">
        <input type="radio" name="rdoStaDestino" id="optPesquisa" onchange="configurarDestino(true)" value="P" <?=($strStaDestino=='P'?'checked="checked"':'')?> class="infraRadio"/>
        <label for="optPesquisa" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Pesquisar Instalações</label>
      </div>

      <div id="divOptGrupoFederacaoInstitucional" class="infraDivRadio">
        <input type="radio" name="rdoStaDestino" id="optGrupoFederacaoInstitucional" onchange="configurarDestino(true)" value="I" <?=($strStaDestino=='I'?'checked="checked"':'')?> class="infraRadio"/>
        <label for="optGrupoFederacaoInstitucional" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Grupo Institucional</label>
      </div>

      <div id="divOptGrupoFederacaoUnidade" class="infraDivRadio">
        <input type="radio" name="rdoStaDestino" id="optGrupoFederacaoUnidade" onchange="configurarDestino(true)" value="U" <?=($strStaDestino=='U'?'checked="checked"':'')?> class="infraRadio"/>
        <label for="optGrupoFederacaoUnidade" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Grupo da Unidade</label>
      </div>
    </fieldset>
  </div>

  <div id="divPesquisa" class="infraAreaDados" style="height:5em">

    <label id="lblInstalacaoFederacaoEnvio" for="selInstalacaoFederacaoEnvio" accesskey="" class="infraLabelOpcional">Instalação:</label>
    <select id="selInstalacaoFederacaoEnvio" name="selInstalacaoFederacaoEnvio" onchange="limparResultado()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelInstalacaoFederacaoEnvio?>
    </select>

    <label id="lblGrupoFederacaoInstitucional" for="selGrupoFederacaoInstitucional" accesskey="" class="infraLabelOpcional">Grupo:</label>
    <select id="selGrupoFederacaoInstitucional" name="selGrupoFederacaoInstitucional" onchange="limparResultado()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelGrupoFederacaoInstitucional?>
    </select>

    <label id="lblGrupoFederacaoUnidade" for="selGrupoFederacaoUnidade" accesskey="" class="infraLabelOpcional">Grupo:</label>
    <select id="selGrupoFederacaoUnidade" name="selGrupoFederacaoUnidade" onchange="limparResultado()" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelGrupoFederacaoUnidade?>
    </select>

    <label id="lblPalavrasPesquisaFederacaoEnvio" for="txtPalavrasPesquisaFederacaoEnvio" accesskey="" class="infraLabelOpcional">Texto para pesquisa:</label>
    <input type="text" id="txtPalavrasPesquisaFederacaoEnvio" name="txtPalavrasPesquisaFederacaoEnvio" class="infraText" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    
    <div id="divBotaoPesquisa">
      <button type="button" name="btnPesquisarOrgaos" id="btnPesquisarOrgaos" onclick="pesquisar()" value="Pesquisar Órgãos" class="infraButton" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Pesquisar Órgãos</button>
    </div>
  </div>

  <?=$strMsg?>

  <br>

  <input type="hidden" id="hdnInstalacoesFederacao" name="hdnInstalacoesFederacao" value="<?=$strInstalacoesFederacao?>" />
  <input type="hidden" id="hdnInstalacoesHash" name="hdnInstalacoesHash" value="<?=$strInstalacoesHash?>" />
  <input type="hidden" id="hdnOrgaosFederacao" name="hdnOrgaosFederacao" value="<?=$strOrgaosFederacao?>" />
  <input type="hidden" id="hdnOrgaosHash" name="hdnOrgaosHash" value="<?=$strOrgaosHash?>" />
  <input type="hidden" id="hdnUnidadesFederacao" name="hdnUnidadesFederacao" value="<?=$strUnidadesFederacao?>" />
  <input type="hidden" id="hdnUnidadesHash" name="hdnUnidadesHash" value="<?=$strUnidadesHash?>" />
  <?

  if ($numRegistros) {
    PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
  }

  if ($numRegistrosEnvios){
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoEnvios, $numRegistrosEnvios);
  }
  ?>

  <br>

  <div id="divMotivo" class="infraAreaDados" style="height:8em;">
    <label id="lblMotivo" for="txaMotivo" class="infraLabelOpcional">Motivo:</label>
    <textarea id="txaMotivo" name="txaMotivo" rows="3" class="infraTextarea" maxlength="4000" tabindex="<?=PaginaSEI::getInstance()->getProxTabTabela()?>"><?=PaginaSEI::tratarHTML($_POST['txaMotivo'])?></textarea>
  </div>

  <div id="divSenha" class="infraAreaDados" style="height:5em;">
    <label id="lblSenha" for="pwdSenha" accesskey="" class="infraLabelObrigatorio">Senha:</label>
    <?=InfraINT::montarInputPassword('pwdSenha', '', 'tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"')?>
  </div>

  <?
	PaginaSEI::getInstance()->montarAreaDebug();

	if ($numRegistros) {
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  }

?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>