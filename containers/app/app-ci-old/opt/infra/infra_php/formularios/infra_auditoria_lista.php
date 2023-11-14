<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/10/2011 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

try {
  //require_once dirname(__FILE__).'/Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  ini_set('max_execution_time','0');
  ini_set('memory_limit','1024M');

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->prepararSelecao('infra_auditoria_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  PaginaInfra::getInstance()->salvarCamposPost(array('txtSiglaUsuario','txtNomeUsuario','txtSiglaUnidade','txtDescricaoUnidade','txtDthInicial','txtDthFinal','txtIp','txtServidor','txtRecurso','txtRequisicao','txtOperacao','selRegistrosPagina'));

  switch($_GET['acao']){
    case 'infra_auditoria_listar':
      $strTitulo = 'Auditoria';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strBase = '1';
  if (isset($_POST['sbmPesquisarBaseLocal'])){
    $strBase = '1';
  }else if (isset($_POST['sbmPesquisarBaseAuditoria'])){
    $strBase = '2';
  }else if (isset($_POST['hdnFlagAuditoria'])){
    $strBase = $_POST['hdnFlagAuditoria'];
  }

  $arrObjArrInfraValorStaDTO = InfraArray::indexarArrInfraDTO(InfraAuditoriaRN::listarCamposRetorno(),'StaValor');

  if(isset($_POST['selCamposExibicao'])){
    $arrCamposExibicao = $_POST['selCamposExibicao'];
    if (!is_array($arrCamposExibicao)){
      $arrCamposExibicao = array($arrCamposExibicao);
    }
  }else{
    $arrCamposExibicao = array_keys($arrObjArrInfraValorStaDTO);
  }

  $arrComandos = array();

  if (BancoAuditoria::getInstance()==null) {
    $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisarBaseLocal" name="sbmPesquisarBaseLocal" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  }else{
    $arrComandos[] = '<button type="submit" accesskey="" id="sbmPesquisarBaseLocal" name="sbmPesquisarBaseLocal" value="Pesquisar na Base do Sistema" class="infraButton" '.($strBase == '1' ? 'style="border:2px solid black"' : '').'>Pesquisar na Base do Sistema</button>';
    $arrComandos[] = '<button type="submit" accesskey="" id="sbmPesquisarBaseAuditoria" name="sbmPesquisarBaseAuditoria" value="Pesquisar na Base de Auditoria" class="infraButton" '.($strBase == '2' ? 'style="border:2px solid black"' : '').'>Pesquisar na Base de Auditoria</button>';
  }

  $arrComandos[] = '<button type="button" accesskey="L" id="btnLimpar" name="btnLimpar" onclick="limpar();" value="Limpar Critérios" class="infraButton"><span class="infraTeclaAtalho">L</span>impar Critérios</button>';
  
  $objInfraAuditoriaDTO = new InfraAuditoriaDTO();
  $objInfraAuditoriaDTO->retDblIdInfraAuditoria();

  if (in_array(InfraAuditoriaRN::$CR_USUARIO, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrSiglaUsuario();
    $objInfraAuditoriaDTO->retStrNomeUsuario();
    $objInfraAuditoriaDTO->retStrSiglaOrgaoUsuario();
    $objInfraAuditoriaDTO->retNumIdUsuarioEmulador();
    $objInfraAuditoriaDTO->retStrSiglaUsuarioEmulador();
    $objInfraAuditoriaDTO->retStrNomeUsuarioEmulador();
    $objInfraAuditoriaDTO->retStrSiglaOrgaoUsuarioEmulador();
  }

  if (in_array(InfraAuditoriaRN::$CR_UNIDADE, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrSiglaUnidade();
    $objInfraAuditoriaDTO->retStrDescricaoUnidade();
    $objInfraAuditoriaDTO->retStrSiglaOrgaoUnidade();
  }

  if (in_array(InfraAuditoriaRN::$CR_DATA_HORA, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retDthAcesso();
  }

  //if (in_array(InfraAuditoriaRN::$CR_RECURSO, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrRecurso();
  //}

  if (in_array(InfraAuditoriaRN::$CR_IP_ACESSO, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrIp();
  }

  if (in_array(InfraAuditoriaRN::$CR_NAVEGADOR, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrUserAgent();
  }

  if (in_array(InfraAuditoriaRN::$CR_SERVIDOR, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrServidor();
  }

  if (in_array(InfraAuditoriaRN::$CR_REQUISICAO, $arrCamposExibicao) || in_array(InfraAuditoriaRN::$CR_COMPLEMENTO, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrRequisicao();
  }

  if (in_array(InfraAuditoriaRN::$CR_OPERACAO, $arrCamposExibicao) || in_array(InfraAuditoriaRN::$CR_COMPLEMENTO, $arrCamposExibicao)) {
    $objInfraAuditoriaDTO->retStrOperacao();
  }

  $objInfraAuditoriaDTO->setStrBase($strBase);
  
  
  $strSiglaUsuario = PaginaInfra::getInstance()->recuperarCampo('txtSiglaUsuario');
  if (!InfraString::isBolVazia($strSiglaUsuario)){
    $objInfraAuditoriaDTO->setStrSiglaUsuario($strSiglaUsuario);
  }

  $strNomeUsuario = PaginaInfra::getInstance()->recuperarCampo('txtNomeUsuario');
  if (!InfraString::isBolVazia($strNomeUsuario)){
    $objInfraAuditoriaDTO->setStrNomeUsuario($strNomeUsuario);
  }
  
  $strSiglaUnidade = PaginaInfra::getInstance()->recuperarCampo('txtSiglaUnidade');
  if (!InfraString::isBolVazia($strSiglaUnidade)){
    $objInfraAuditoriaDTO->setStrSiglaUnidade($strSiglaUnidade);
  }

  $strDescricaoUnidade = PaginaInfra::getInstance()->recuperarCampo('txtDescricaoUnidade');
  if (!InfraString::isBolVazia($strDescricaoUnidade)){
    $objInfraAuditoriaDTO->setStrDescricaoUnidade($strDescricaoUnidade);
  }
  
  $dthInicial = PaginaInfra::getInstance()->recuperarCampo('txtDthInicial');
  if (!InfraString::isBolVazia($dthInicial)){
    $objInfraAuditoriaDTO->setDthInicial($dthInicial);
  }
  
  $dthFinal = PaginaInfra::getInstance()->recuperarCampo('txtDthFinal');
  if (!InfraString::isBolVazia($dthFinal)){
    $objInfraAuditoriaDTO->setDthFinal($dthFinal);	
  }
  
  $strIp = PaginaInfra::getInstance()->recuperarCampo('txtIp');
  if (!InfraString::isBolVazia($strIp)){
    $objInfraAuditoriaDTO->setStrIp($strIp);	
  }

  $strServidor = PaginaInfra::getInstance()->recuperarCampo('txtServidor');
  if (!InfraString::isBolVazia($strServidor)){
    $objInfraAuditoriaDTO->setStrServidor($strServidor);	
  }
  
  $strRecurso = PaginaInfra::getInstance()->recuperarCampo('txtRecurso');
  if (!InfraString::isBolVazia($strRecurso)){
    $objInfraAuditoriaDTO->setStrRecurso($strRecurso);	
  }

  $strRequisicao = PaginaInfra::getInstance()->recuperarCampo('txtRequisicao');
  if (!InfraString::isBolVazia($strRequisicao)){
    $objInfraAuditoriaDTO->setStrRequisicao($strRequisicao);	
  }

  $strOperacao = PaginaInfra::getInstance()->recuperarCampo('txtOperacao');
  if (!InfraString::isBolVazia($strOperacao)){
    $objInfraAuditoriaDTO->setStrOperacao($strOperacao);	
  }
  
  PaginaInfra::getInstance()->prepararOrdenacao($objInfraAuditoriaDTO, 'Acesso', InfraDTO::$TIPO_ORDENACAO_DESC);

  $numRegistrosPagina = PaginaInfra::getInstance()->recuperarCampo('selRegistrosPagina',100);

  PaginaInfra::getInstance()->prepararPaginacao($objInfraAuditoriaDTO,$numRegistrosPagina);

  $arrObjInfraAuditoriaDTO = array();

  if (isset($_POST['sbmPesquisarBaseLocal']) || isset($_POST['sbmPesquisarBaseAuditoria']) || isset($_POST['hdnFlagAuditoria'])){

    $objBancoInfra = BancoInfra::getInstance();

    if ($strBase == '2'){
      BancoInfra::setObjInfraIBanco(BancoAuditoria::getInstance());
    }

    try {
      $objInfraAuditoriaRN = new InfraAuditoriaRN();
      $arrObjInfraAuditoriaDTO = $objInfraAuditoriaRN->pesquisar($objInfraAuditoriaDTO);
    }catch(Exception $e){
      BancoInfra::setObjInfraIBanco($objBancoInfra);
      throw $e;
    }
  }

  PaginaInfra::getInstance()->processarPaginacao($objInfraAuditoriaDTO);
  $numRegistros = count($arrObjInfraAuditoriaDTO);

  $strResultado = '';

  if ($numRegistros > 0){

    $arrComandos[] = '<button type="button" accesskey="G" name="btnGerar" value="Gerar" onclick="gerar();" class="infraButton"><span class="infraTeclaAtalho">G</span>erar Planilha</button>';

    $objInfraAuditoria = AuditoriaInfra::getInstance();


    if ($_POST['hdnFlagGerar']=='1'){
      try{

        $strCsv = '';

        $strSep = '';

        if (in_array(InfraAuditoriaRN::$CR_USUARIO, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_USUARIO]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_UNIDADE, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_UNIDADE]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_DATA_HORA, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_DATA_HORA]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_IP_ACESSO, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_IP_ACESSO]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_NAVEGADOR, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_NAVEGADOR]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_SERVIDOR, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_SERVIDOR]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_RECURSO, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_RECURSO]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_COMPLEMENTO, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_COMPLEMENTO]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_REQUISICAO, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_REQUISICAO]->getStrDescricao();
          $strSep = ';';
        }

        if (in_array(InfraAuditoriaRN::$CR_OPERACAO, $arrCamposExibicao)) {
          $strCsv .= $strSep.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_OPERACAO]->getStrDescricao();
          $strSep = ';';
        }

        $strCsv .= "\n";

        for ($i = 0; $i < $numRegistros; $i++) {

          if (in_array($arrObjInfraAuditoriaDTO[$i]->getDblIdInfraAuditoria(), PaginaInfra::getInstance()->getArrStrItensSelecionados())) {

            $strSep = '';

            if (in_array(InfraAuditoriaRN::$CR_USUARIO, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha(InfraAuditoriaINT::formatarUsuario($arrObjInfraAuditoriaDTO[$i]));
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_UNIDADE, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha(InfraAuditoriaINT::formatarUnidade($arrObjInfraAuditoriaDTO[$i]));
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_DATA_HORA, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getDthAcesso());
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_IP_ACESSO, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getStrIp());
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_NAVEGADOR, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getStrUserAgent());
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_SERVIDOR, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getStrServidor());
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_RECURSO, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getStrRecurso());
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_COMPLEMENTO, $arrCamposExibicao)) {

              $strCsv .= $strSep;

              if ($objInfraAuditoria != null) {
                $strTemp = $objInfraAuditoria->processarComplemento($arrObjInfraAuditoriaDTO[$i]);
                $strCsv .= InfraUtil::formatarCelulaPlanilha($strTemp);
              }

              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_REQUISICAO, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getStrRequisicao());
              $strSep = ';';
            }

            if (in_array(InfraAuditoriaRN::$CR_OPERACAO, $arrCamposExibicao)) {
              $strCsv .= $strSep.InfraUtil::formatarCelulaPlanilha($arrObjInfraAuditoriaDTO[$i]->getStrOperacao());
              $strSep = ';';
            }

            $strCsv .= "\n";

          }
        }

        $strNomeDownload = 'SEI_Auditoria_'.str_replace(array('/',':'),'',InfraData::getStrDataHoraAtual()).'.csv';
        InfraPagina::montarHeaderDownload($strNomeDownload, 'attachment');
        echo $strCsv;
        die;


      }catch(Exception $e){
        PaginaInfra::getInstance()->processarExcecao($e);
      }
    }



    $bolCheck = false;

    $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_auditoria_consultar');
    $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_auditoria_alterar');
    $bolAcaoImprimir = true;
    //$bolAcaoGerarPlanilha = SessaoInfra::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strSumarioTabela = 'Tabela de Dados de Auditoria.';
    $strCaptionTabela = 'Dados de Auditoria';

    $strResultado .= '<table id="tblAuditoria" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="3%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    }
    
    $strResultado .= '<th class="infraTh">Dados de Auditoria</th>'."\n";
    //$strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top" align="center">'.PaginaInfra::getInstance()->getTrCheck($i,$arrObjInfraAuditoriaDTO[$i]->getDblIdInfraAuditoria(),$arrObjInfraAuditoriaDTO[$i]->getDblIdInfraAuditoria()).'</td>';
      }

      $strResultado .= '<td valign="top">';

      $novaLinha = '';
      if (in_array(InfraAuditoriaRN::$CR_USUARIO, $arrCamposExibicao)) {
        $strResultado .= '<b>'.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_USUARIO]->getStrDescricao().': </b> '.PaginaInfra::getInstance()->tratarHTML(InfraAuditoriaINT::formatarUsuario($arrObjInfraAuditoriaDTO[$i]));
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_UNIDADE, $arrCamposExibicao)) {
        $strResultado .= $novaLinha.'<b> '.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_UNIDADE]->getStrDescricao().': </b>'.PaginaInfra::getInstance()->tratarHTML(InfraAuditoriaINT::formatarUnidade($arrObjInfraAuditoriaDTO[$i]));
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_DATA_HORA, $arrCamposExibicao)) {
        $strResultado .= $novaLinha.'<b> '.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_DATA_HORA]->getStrDescricao().': </b>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraAuditoriaDTO[$i]->getDthAcesso());
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_IP_ACESSO, $arrCamposExibicao)) {
        $strResultado .= $novaLinha.'<b>IP de Acesso: </b> '.PaginaInfra::getInstance()->tratarHTML($arrObjInfraAuditoriaDTO[$i]->getStrIp());
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_NAVEGADOR, $arrCamposExibicao)) {
        $strResultado .= $novaLinha.'<b>Navegador: </b> '.PaginaInfra::getInstance()->tratarHTML($arrObjInfraAuditoriaDTO[$i]->getStrUserAgent());
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_SERVIDOR, $arrCamposExibicao)) {
        $strResultado .= $novaLinha.'<b>'.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_SERVIDOR]->getStrDescricao().': </b> '.PaginaInfra::getInstance()->tratarHTML($arrObjInfraAuditoriaDTO[$i]->getStrServidor());
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_RECURSO, $arrCamposExibicao)) {
        $strResultado .= $novaLinha.'<b>'.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_RECURSO]->getStrDescricao().': </b> '.PaginaInfra::getInstance()->tratarHTML($arrObjInfraAuditoriaDTO[$i]->getStrRecurso());
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_COMPLEMENTO, $arrCamposExibicao)) {
        if ($objInfraAuditoria != null) {
          $strTemp = $objInfraAuditoria->processarComplemento($arrObjInfraAuditoriaDTO[$i]);
          if ($strTemp!=null) {
            $strTemp = PaginaInfra::getInstance()->tratarHTML($strTemp);
            $strTemp = str_replace('\n', '', $strTemp);
            $strTemp = str_replace("\n", '<br />', $strTemp);
            $strTemp = str_replace('&lt;br /&gt;', '<br />', $strTemp);
            $strResultado .= $novaLinha.'<b>'.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_COMPLEMENTO]->getStrDescricao().': </b> '.$strTemp;
            $novaLinha = '<br />';
          }
        }
      }

      if (in_array(InfraAuditoriaRN::$CR_REQUISICAO, $arrCamposExibicao)) {
        $strTemp = $arrObjInfraAuditoriaDTO[$i]->getStrRequisicao();
        $strTemp = PaginaInfra::getInstance()->tratarHTML($strTemp);
        $strTemp = str_replace('\n', '', $strTemp);
        $strTemp = str_replace("\n", '<br />', $strTemp);
        $strTemp = str_replace('&lt;br /&gt;', '<br />', $strTemp);
        $strResultado .= $novaLinha.'<b>'.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_REQUISICAO]->getStrDescricao().': </b><br />'.$strTemp;
        $novaLinha = '<br />';
      }

      if (in_array(InfraAuditoriaRN::$CR_OPERACAO, $arrCamposExibicao)) {
        $strTemp = $arrObjInfraAuditoriaDTO[$i]->getStrOperacao();
        $strTemp = PaginaInfra::getInstance()->tratarHTML($strTemp);
        $strTemp = str_replace('\n', '', $strTemp);
        $strTemp = str_replace("\n", '<br />', $strTemp);
        $strTemp = str_replace('&lt;br /&gt;', '<br />', $strTemp);
        $strResultado .= $novaLinha.'<b>'.$arrObjArrInfraValorStaDTO[InfraAuditoriaRN::$CR_OPERACAO]->getStrDescricao().': </b><br />'.$strTemp;
        $novaLinha = '<br />';
      }


      $strResultado .= '</td>';
      
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  if ($_GET['acao'] == 'infra_auditoria_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strLinkRecursosSelecao = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_auditoria_recurso_selecionar&tipo_selecao=1&id_object=objLupaRecursos');
  $strSelCamposExibicao = InfraAuditoriaINT::montarSelectCamposRetorno($arrCamposExibicao);
  $strSelRegistrosPagina = InfraAuditoriaINT::montarSelectRegistrosPagina($numRegistrosPagina);
  
}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
} 

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>

#lblAviso {position:absolute;top:0%;left:20%;font-size:1.4em;color:red}

#lblSiglaUsuario {position:absolute;left:0%;top:0%;}
#txtSiglaUsuario {position:absolute;left:20%;top:0%;width:40%;}

#lblNomeUsuario {position:absolute;left:0%;top:0%;}
#txtNomeUsuario {position:absolute;left:20%;top:0%;width:60%;}

#lblSiglaUnidade {position:absolute;left:0%;top:0%;}
#txtSiglaUnidade {position:absolute;left:20%;top:0%;width:40%;}

#lblDescricaoUnidade {position:absolute;left:0%;top:0%;}
#txtDescricaoUnidade {position:absolute;left:20%;top:0%;width:60%;}

#lblRecurso {position:absolute;left:0%;top:0%;}
#txtRecurso {position:absolute;left:20%;top:0%;width:40%;}
#imgPesquisarRecursos {position:absolute;left:61%;top:0%}

#lblDthInicial {position:absolute;left:0%;top:0%;}
#txtDthInicial {position:absolute;left:20%;top:0%;width:15%;}
#imgCalDthInicial {position:absolute;left:36%;top:0%;}

#lblDthFinal {position:absolute;left:39.5%;top:0%;}
#txtDthFinal {position:absolute;left:42%;top:0%;width:15%;}
#imgCalDthFinal {position:absolute;left:58%;top:0%;}

#lblIp {position:absolute;left:0%;top:0%;}
#txtIp {position:absolute;left:20%;top:0%;width:40%;}

#lblServidor {position:absolute;left:0%;top:0%;}
#txtServidor {position:absolute;left:20%;top:0%;width:40%;}

#lblRequisicao {position:absolute;left:0%;top:0%;}
#txtRequisicao {position:absolute;left:20%;top:0%;width:60%;}

#lblOperacao {position:absolute;left:0%;top:0%;}
#txtOperacao {position:absolute;left:20%;top:0%;width:60%;}

#lblCamposExibicao {position:absolute;left:0%;top:0%;}
#selCamposExibicao, .multipleSelect {position:absolute;left:20%;top:0%;width:30%;}

#lblRegistrosPagina {position:absolute;left:0%;top:0%;}
#selRegistrosPagina {position:absolute;left:20%;top:0%;}

#tblAuditoria {
  table-layout: fixed;
  width: 100%;
}

#tblAuditoria  td {
  word-wrap: break-word;         /* All browsers since IE 5.5+ */
  overflow-wrap: break-word;     /* Renamed property in CSS3 draft spec */
}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

var objLupaRecursos = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_auditoria_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  objLupaRecursos = new infraLupaText('txtRecurso','hdnRecurso','<?=$strLinkRecursosSelecao?>');

  //infraEfeitoTabelas();
}

function validarForm(){
  
  if (infraTrim(document.getElementById('txtDthInicial').value)!=''){
    if (!infraValidarDataHora(document.getElementById('txtDthInicial'))){
      document.getElementById('txtDthInicial').focus();
      return false;
    }
  }

  if (infraTrim(document.getElementById('txtDthFinal').value)!=''){
    if (!infraValidarDataHora(document.getElementById('txtDthFinal'))){
      document.getElementById('txtDthFinal').focus();
      return false;
    }
  }

  if ($("#selCamposExibicao").multipleSelect("getSelects").length==0) {
    alert('Nenhum campo para exibição selecionado.');
    return false;
  }

  infraExibirAviso();
  
  return true;
}

function limpar(){
  document.getElementById('txtSiglaUsuario').value = '';
  document.getElementById('txtNomeUsuario').value = '';
  document.getElementById('txtSiglaUnidade').value = '';
  document.getElementById('txtDescricaoUnidade').value = '';
  document.getElementById('txtDthInicial').value = '';
  document.getElementById('txtDthFinal').value = '';
  document.getElementById('txtIp').value = '';
  document.getElementById('txtServidor').value = '';
  document.getElementById('txtRecurso').value = '';
  document.getElementById('txtRequisicao').value = '';
  document.getElementById('txtOperacao').value = '';
}

$( document ).ready(function() {
  $("#selCamposExibicao").multipleSelect({
  filter: false,
  minimumCountSelected: 1,
  allSelected: 'Todos',
  selectAll: true
  });
});

function gerar() {

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum registro selecionado.');
    return;
  }

  //infraExibirAviso(false);

  document.getElementById('hdnFlagGerar').value = '1';
  document.getElementById('frmInfraAuditoriaLista').target = '_blank';
  document.getElementById('frmInfraAuditoriaLista').submit();
  document.getElementById('frmInfraAuditoriaLista').target = '_self';
  document.getElementById('hdnFlagGerar').value = '0';
}

function selecionarRecurso(){
  document.getElementById('hdnRecurso').value = document.getElementById('txtRecurso').value;
  objLupaRecursos.selecionar(700,500);
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraAuditoriaLista" method="post" onsubmit="return validarForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaInfra::getInstance()->abrirAreaDados('3em');
  ?>
  <label id="lblAviso" name="lblAviso">ATENÇÃO: Informar o maior número possível de critérios antes de realizar a pesquisa!</label>
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>

  <label id="lblSiglaUsuario" for="txtSiglaUsuario" accesskey="" class="infraLabelOpcional">Sigla do Usuário:</label>
  <input type="text" id="txtSiglaUsuario" name="txtSiglaUsuario" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strSiglaUsuario)?>"  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblNomeUsuario" for="txtNomeUsuario" accesskey="" class="infraLabelOpcional">Nome do Usuário:</label>
  <input type="text" id="txtNomeUsuario" name="txtNomeUsuario" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strNomeUsuario)?>"  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblSiglaUnidade" for="txtSiglaUnidade" accesskey="" class="infraLabelOpcional">Sigla da Unidade:</label>
  <input type="text" id="txtSiglaUnidade" name="txtSiglaUnidade" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strSiglaUnidade)?>"  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblDescricaoUnidade" for="txtDescricaoUnidade" accesskey="" class="infraLabelOpcional">Descrição da Unidade:</label>
  <input type="text" id="txtDescricaoUnidade" name="txtDescricaoUnidade" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strDescricaoUnidade)?>"  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblRecurso" for="txtRecurso" accesskey="" class="infraLabelOpcional">Recurso:</label>
  <input type="text" id="txtRecurso" name="txtRecurso" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strRecurso)?>"  tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img id="imgPesquisarRecursos" onclick="selecionarRecurso()" src="<?=PaginaInfra::getInstance()->getIconePesquisar()?>" alt="Pesquisar Recursos Auditados" title="Pesquisar Recursos Auditados" class="infraImg" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <input type="hidden" id="hdnRecurso" name="hdnRecurso" value="<?=PaginaInfra::getInstance()->tratarHTML($strRecurso)?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblDthInicial" for="txtDthInicial" accesskey="" class="infraLabelOpcional" >Período:</label>
  <input type="text" id="txtDthInicial" name="txtDthInicial" onkeypress="return infraMascara(this, event,'##/##/#### ##:##')" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($dthInicial)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" id="imgCalDthInicial" title="Selecionar Data/Hora Inicial" alt="Selecionar Data/Hora Inicial" class="infraImg" onclick="infraCalendario('txtDthInicial',this,true,'<?=InfraData::getStrDataAtual().' 00:00'?>');" />
  
  <label id="lblDthFinal" for="txtDthFinal" accesskey="" class="infraLabelOpcional" >a</label>
  <input type="text" id="txtDthFinal" name="txtDthFinal" onkeypress="return infraMascara(this, event,'##/##/#### ##:##')" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($dthFinal)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" id="imgCalDthFinal" title="Selecionar Data/Hora Final" alt="Selecionar Data/Hora Final" class="infraImg" onclick="infraCalendario('txtDthFinal',this,true,'<?=InfraData::getStrDataAtual().' 23:59'?>');" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblIp" for="txtIp" accesskey="" class="infraLabelOpcional">IP:</label>
  <input type="text" id="txtIp" name="txtIp" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strIp)?>" onkeypress="return infraMascaraNumero(this,event,16,'.');" maxlength="16" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblServidor" for="txtServidor" accesskey="" class="infraLabelOpcional">Servidor:</label>
  <input type="text" id="txtServidor" name="txtServidor" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strServidor)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblRequisicao" for="txtRequisicao" accesskey="" class="infraLabelOpcional">Requisição:</label>
  <input type="text" id="txtRequisicao" name="txtRequisicao" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strRequisicao)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblOperacao" for="txtOperacao" accesskey="" class="infraLabelOpcional">Operação:</label>
  <input type="text" id="txtOperacao" name="txtOperacao" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strOperacao)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('3em','style="overflow:visible;"');
  ?>
  <label id="lblCamposExibicao" accesskey="" class="infraLabelOpcional">Exibir:</label>
  <select style="display: none" multiple id="selCamposExibicao" name="selCamposExibicao[]" class="infraSelect multipleSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
    <?=$strSelCamposExibicao;?>
  </select>
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('2.8em');
  ?>
  <label id="lblRegistrosPagina" accesskey="" class="infraLabelOpcional">Registros por Página:</label>
  <select id="selRegistrosPagina" name="selRegistrosPagina" class="infraSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
    <?=$strSelRegistrosPagina;?>
  </select>
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  ?>
  <input type="hidden" id="hdnFlagAuditoria" name="hdnFlagAuditoria" value="<?=PaginaInfra::getInstance()->tratarHTML($strBase)?>" />
  <input type="hidden" id="hdnFlagGerar" name="hdnFlagGerar" value="0" />
  <?
  PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaInfra::getInstance()->montarAreaDebug();
  PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>