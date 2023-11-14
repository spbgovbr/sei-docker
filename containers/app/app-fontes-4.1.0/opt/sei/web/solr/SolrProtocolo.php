<?
require_once dirname(__FILE__).'/../SEI.php';

class SolrProtocolo {

  public static function executar(PesquisaProtocoloSolrDTO $objPesquisaProtocoloSolrDTO)
  {

    //die($objPesquisaProtocoloSolrDTO->__toString());

    $objPesquisaProtocoloSolrDTO->setStrResultadoPesquisa(null);
    $objPesquisaProtocoloSolrDTO->setStrLinkPublicacao(null);

    $objPesquisaProtocoloSolrDTO->setStrPalavrasChave(trim($objPesquisaProtocoloSolrDTO->getStrPalavrasChave()));
    $objPesquisaProtocoloSolrDTO->setStrDescricao(trim($objPesquisaProtocoloSolrDTO->getStrDescricao()));
    $objPesquisaProtocoloSolrDTO->setStrObservacao(trim($objPesquisaProtocoloSolrDTO->getStrObservacao()));
    $objPesquisaProtocoloSolrDTO->setStrProtocoloPesquisa(trim($objPesquisaProtocoloSolrDTO->getStrProtocoloPesquisa()));
    $objPesquisaProtocoloSolrDTO->setStrNumero(trim($objPesquisaProtocoloSolrDTO->getStrNumero()));
    $objPesquisaProtocoloSolrDTO->setStrNomeArvore(trim($objPesquisaProtocoloSolrDTO->getStrNomeArvore()));

    $objUnidadeDTO = new UnidadeDTO();
    $objUnidadeDTO->setBolExclusaoLogica(false);
    $objUnidadeDTO->retStrSinProtocolo();
    $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    $objUnidadeRN = new UnidadeRN();
    $objUnidadeDTOAtual = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

    $partialfields = '';

    $bolArvore = $objPesquisaProtocoloSolrDTO->getBolArvore();

    $arrStaProtocolo = array();

    if ($objPesquisaProtocoloSolrDTO->getStrSinProcessos() == 'S'){
      array_push($arrStaProtocolo, "sta_prot:" . ProtocoloRN::$TP_PROCEDIMENTO);
    }

    if (($objPesquisaProtocoloSolrDTO->getStrSinProcessos() == 'N' && $objPesquisaProtocoloSolrDTO->getStrSinDocumentosGerados() == 'S') || ($objPesquisaProtocoloSolrDTO->getStrSinProcessos() == 'S' && $objPesquisaProtocoloSolrDTO->getStrSinConsiderarDocumentos() == 'S')){
      if ($objUnidadeDTOAtual->getStrSinProtocolo()=='N') {
        array_push($arrStaProtocolo, "sta_prot:".ProtocoloRN::$TP_DOCUMENTO_GERADO);
      }else{
        //gerados pelo protocolo ou que já foram assinados
        array_push($arrStaProtocolo, "(sta_prot:".ProtocoloRN::$TP_DOCUMENTO_GERADO.' AND (id_uni_ger:'.SessaoSEI::getInstance()->getNumIdUnidadeAtual().' OR id_assin:*;*))');
      }
    }

    if (($objPesquisaProtocoloSolrDTO->getStrSinProcessos() == 'N' && $objPesquisaProtocoloSolrDTO->getStrSinDocumentosRecebidos() == 'S') || ($objPesquisaProtocoloSolrDTO->getStrSinProcessos() == 'S' && $objPesquisaProtocoloSolrDTO->getStrSinConsiderarDocumentos() == 'S') ) {
      array_push($arrStaProtocolo, "sta_prot:" . ProtocoloRN::$TP_DOCUMENTO_RECEBIDO);
    }

    if (count($arrStaProtocolo) > 0) {
      $partialfields .= '(' . implode(" OR ", $arrStaProtocolo) . ')';
    }

    if ($objPesquisaProtocoloSolrDTO->getStrSinTramitacao() == 'S') {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $partialfields .= '(id_uni_tram:*;' . SessaoSEI::getInstance()->getNumIdUnidadeAtual() . ';*)';
    }

    if (is_array($objPesquisaProtocoloSolrDTO->getArrNumIdOrgao())) {

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->setOrdNumIdOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objOrgaoRN = new OrgaoRN();
      $arrObjOrgaoDTOTodos = InfraArray::indexarArrInfraDTO($objOrgaoRN->listarRN1353($objOrgaoDTO), 'IdOrgao');

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
      $objOrgaoDTO->setOrdNumIdOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $arrIdOrgaoSemRestricao = InfraArray::converterArrInfraDTO($objOrgaoRN->listarPesquisa($objOrgaoDTO), 'IdOrgao');

      $arrIdOrgaoPesquisa = $objPesquisaProtocoloSolrDTO->getArrNumIdOrgao();

      sort($arrIdOrgaoPesquisa);

      if (count($arrIdOrgaoPesquisa) == 0) {

        if (count($arrObjOrgaoDTOTodos) > count($arrIdOrgaoSemRestricao)) {

          if ($partialfields != '') {
            $partialfields .= ' AND ';
          }

          $arrIdOrgaoComRestricao = array_diff(array_keys($arrObjOrgaoDTOTodos), $arrIdOrgaoSemRestricao);

          if (count($arrIdOrgaoComRestricao) < count($arrIdOrgaoSemRestricao)) {
            $partialfields .= 'NOT id_org_ger:('.implode(" OR ", $arrIdOrgaoComRestricao).')';
          } else {
            $partialfields .= 'id_org_ger:('.implode(" OR ", $arrIdOrgaoSemRestricao).')';
          }
        }

      } else {

        foreach ($arrIdOrgaoPesquisa as $numIdOrgao) {
          if (!in_array($numIdOrgao, $arrIdOrgaoSemRestricao)) {
            throw new InfraException('Órgão '.$arrObjOrgaoDTOTodos[$numIdOrgao]->getStrSigla().' possui restrição de pesquisa.');
          }
        }

        if ($partialfields != '') {
          $partialfields .= ' AND ';
        }

        $partialfields .= 'id_org_ger:('.implode(" OR ", $arrIdOrgaoPesquisa).')';
      }
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdUnidadeGeradora() != null) {

      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $arrUnidadesPesquisa = $objPesquisaProtocoloSolrDTO->getNumIdUnidadeGeradora();
      if (!is_array($arrUnidadesPesquisa)){
        $arrUnidadesPesquisa = array($arrUnidadesPesquisa);
      }

      $arrUnidadesFiltro = array();
      foreach($arrUnidadesPesquisa as $numIdUnidade){
        array_push($arrUnidadesFiltro, 'id_uni_ger:' . $numIdUnidade);
      }

      $partialfields .= '(' . implode(" OR ", $arrUnidadesFiltro) . ')';
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdContato() != null) {

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setNumIdContato($objPesquisaProtocoloSolrDTO->getNumIdContato());

      $objUsuarioRN = new UsuarioRN();
      $arrIdContato = array_unique(InfraArray::converterArrInfraDTO($objUsuarioRN->obterUsuariosRelacionados($objUsuarioDTO),'IdContato'));

      if (count($arrIdContato) == 0) {
        $arrIdContato[] = $objPesquisaProtocoloSolrDTO->getNumIdContato();
      }

      $arrContatos = array();

      foreach($arrIdContato as $numIdParticpante) {
        if ($objPesquisaProtocoloSolrDTO->getStrSinInteressado() == 'S') {
          array_push($arrContatos, 'id_int:*;' . $numIdParticpante . ';*');
        }
      }

      foreach($arrIdContato as $numIdParticpante) {
        if ($objPesquisaProtocoloSolrDTO->getStrSinRemetente() == 'S') {
          array_push($arrContatos, 'id_rem:*;' . $numIdParticpante . ';*');
        }
      }

      foreach($arrIdContato as $numIdParticpante){
        if ($objPesquisaProtocoloSolrDTO->getStrSinDestinatario() == 'S') {
          array_push($arrContatos, 'id_dest:*;' . $numIdParticpante . ';*');
        }
      }

      if (count($arrContatos) > 0) {

        if ($partialfields != '') {
          $partialfields .= ' AND ';
        }

        $partialfields .= '(' . implode(" OR ", $arrContatos) . ')';
      }
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdAssinante() != null) {

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setNumIdContato($objPesquisaProtocoloSolrDTO->getNumIdAssinante());

      $objUsuarioRN = new UsuarioRN();
      $arrIdUsuario = array_unique(InfraArray::converterArrInfraDTO($objUsuarioRN->obterUsuariosRelacionados($objUsuarioDTO),'IdUsuario'));

      if (count($arrIdUsuario)) {

        $arrContatos = array();

        foreach ($arrIdUsuario as $numIdAssinante) {
          array_push($arrContatos, 'id_assin:*;' . $numIdAssinante . ';*');
        }

        if (count($arrContatos) > 0) {

          if ($partialfields != '') {
            $partialfields .= ' AND ';
          }

          $partialfields .= '(' . implode(" OR ", $arrContatos) . ')';
        }
      }
    }

    if ($objPesquisaProtocoloSolrDTO->getStrDescricao() != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }
      $partialfields .= '(' . InfraSolrUtil::formatarOperadores($objPesquisaProtocoloSolrDTO->getStrDescricao(), 'desc') . ')';
    }

    if ($objPesquisaProtocoloSolrDTO->getStrObservacao() != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }
      $partialfields .= '(' . InfraSolrUtil::formatarOperadores($objPesquisaProtocoloSolrDTO->getStrObservacao(), 'obs_' . SessaoSEI::getInstance()->getNumIdUnidadeAtual()) . ')';
    }

    if ($objPesquisaProtocoloSolrDTO->getDblIdProcedimento() != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $objRelProtocoloProtocoloDTO 	= new RelProtocoloProtocoloDTO();
      $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
      $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO);
      $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($objPesquisaProtocoloSolrDTO->getDblIdProcedimento());

      $objRelProtocoloProtocoloRN 	= new RelProtocoloProtocoloRN();
      $arrIdProcessosAnexados = InfraArray::converterArrInfraDTO($objRelProtocoloProtocoloRN->listarRN0187($objRelProtocoloProtocoloDTO),'IdProtocolo2');

      if (count($arrIdProcessosAnexados)==0) {
        $partialfields .= '(id_proc:' . $objPesquisaProtocoloSolrDTO->getDblIdProcedimento() . ')';
      }else{

        $strProcessos = 'id_proc:' . $objPesquisaProtocoloSolrDTO->getDblIdProcedimento();
        foreach($arrIdProcessosAnexados as $dblIdProcessoAnexado){
          $strProcessos .= ' OR id_proc:'.$dblIdProcessoAnexado;
        }

        $partialfields .= '('.$strProcessos.')';
      }
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdAssunto() != null) {

      $objAssuntoProxyDTO = new AssuntoProxyDTO();
      $objAssuntoProxyDTO->retNumIdAssuntoProxy();
      $objAssuntoProxyDTO->setNumIdAssunto($objPesquisaProtocoloSolrDTO->getNumIdAssunto());

      $objAssuntoProxyRN = new AssuntoProxyRN();
      $arrObjAssuntoProxyDTO = $objAssuntoProxyRN->listar($objAssuntoProxyDTO);

      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $arrAssuntos = array();
      foreach($arrObjAssuntoProxyDTO as $objAssuntoProxyDTO){
        array_push($arrAssuntos, 'id_assun:*;' . $objAssuntoProxyDTO->getNumIdAssuntoProxy() . ';*');
      }

      $partialfields .= '(' . implode(" OR ", $arrAssuntos) . ')';
    }

    if ($objPesquisaProtocoloSolrDTO->getStrProtocoloPesquisa() != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $partialfields .= '(prot_pesq:*' . InfraSolrUtil::formatarCaracteresEspeciais(InfraUtil::retirarFormatacao($objPesquisaProtocoloSolrDTO->getStrProtocoloPesquisa(),false)) . '*';

      if ($objPesquisaProtocoloSolrDTO->getStrSinDocumentosGerados() == 'S' || $objPesquisaProtocoloSolrDTO->getStrSinDocumentosRecebidos()=='S'){
        $partialfields .= ' OR prot_proc:*' . InfraSolrUtil::formatarCaracteresEspeciais($objPesquisaProtocoloSolrDTO->getStrProtocoloPesquisa()) . '*';
      }

      $partialfields .= ')';

    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdTipoProcedimento() != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }
      $partialfields .= '(id_tipo_proc:' . $objPesquisaProtocoloSolrDTO->getNumIdTipoProcedimento() . ')';
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdSerie() != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $arrSeriesPesquisa = $objPesquisaProtocoloSolrDTO->getNumIdSerie();
      if (!is_array($arrSeriesPesquisa)){
        $arrSeriesPesquisa = array($arrSeriesPesquisa);
      }

      $arrSeriesFiltro = array();
      foreach($arrSeriesPesquisa as $numIdSerie){
        array_push($arrSeriesFiltro, '(id_serie:' . $numIdSerie . ')');
      }

      $partialfields .= '(' . implode(" OR ", $arrSeriesFiltro) . ')';
    }

    $strNumero = InfraUtil::retirarFormatacao($objPesquisaProtocoloSolrDTO->getStrNumero(), false);
    if ($strNumero != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }
      $partialfields .= '(numero:*' . $strNumero . '*)';
    }

    $strNomeArvore = InfraUtil::retirarFormatacao($objPesquisaProtocoloSolrDTO->getStrNomeArvore(), false);
    if ($strNomeArvore != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }
      $partialfields .= '(nome_arvore:*' . $strNomeArvore . '*)';
    }

    $strDinValorInicio = SeiINT::formatarDinIndexacao($objPesquisaProtocoloSolrDTO->getDinValorInicio());
    $strDinValorFim = SeiINT::formatarDinIndexacao($objPesquisaProtocoloSolrDTO->getDinValorFim());
    if ($strDinValorInicio != null) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }
      if ($strDinValorFim==null){
        $partialfields .= '(aux1: ['. $strDinValorInicio . ' TO *])';
      }else{
        $partialfields .= '(aux1: ['.$strDinValorInicio.' TO ' . $strDinValorFim . '])';
      }
    }

    $dtaInicio = $objPesquisaProtocoloSolrDTO->getDtaInicio();
    $dtaFim = $objPesquisaProtocoloSolrDTO->getDtaFim();
    $strStaTipoData = $objPesquisaProtocoloSolrDTO->getStrStaTipoData();

    if (!InfraString::isBolVazia($dtaInicio)) {
      $dia1 = substr($dtaInicio, 0, 2);
      $mes1 = substr($dtaInicio, 3, 2);
      $ano1 = substr($dtaInicio, 6, 4);

      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $partialfields .= " ( ";
      if ($strStaTipoData == "G") {
        $partialfields .= 'dta_ger';
      }else{
        $partialfields .= 'dta_inc';
      }
      $partialfields .= ':[' . $ano1 . '-' . $mes1 . '-' . $dia1 . 'T00:00:00Z';
      if (!InfraString::isBolVazia($dtaFim)) {
        $dia2 = substr($dtaFim, 0, 2);
        $mes2 = substr($dtaFim, 3, 2);
        $ano2 = substr($dtaFim, 6, 4);
        $partialfields .= ' TO ' . $ano2 . '-' . $mes2 . '-' . $dia2 . 'T00:00:00Z]';
      }else{
        $partialfields .= ' TO *]';
      }
      $partialfields .= " ) ";
    }


    $arrUsuarioGerador = array();

    if ($objPesquisaProtocoloSolrDTO->getNumIdUsuarioGerador1() != null) {
      array_push($arrUsuarioGerador, "id_usu_ger:" . $objPesquisaProtocoloSolrDTO->getNumIdUsuarioGerador1());
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdUsuarioGerador2() != null) {
      array_push($arrUsuarioGerador, "id_usu_ger:" . $objPesquisaProtocoloSolrDTO->getNumIdUsuarioGerador2());
    }

    if ($objPesquisaProtocoloSolrDTO->getNumIdUsuarioGerador3() != null) {
      array_push($arrUsuarioGerador, "id_usu_ger:" . $objPesquisaProtocoloSolrDTO->getNumIdUsuarioGerador3());
    }

    if (count($arrUsuarioGerador) > 0) {
      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $partialfields .= '(' . implode(" OR ", $arrUsuarioGerador) . ')';
    }

    if ($objUnidadeDTOAtual->getStrSinProtocolo() == 'N') {

      if ($partialfields != '') {
        $partialfields .= ' AND ';
      }

      $partialfields .= '(tipo_aces_g:P OR id_uni_aces:*;' . SessaoSEI::getInstance()->getNumIdUnidadeAtual() . ';*)';
    }

    $strJoin = "";
    if($objPesquisaProtocoloSolrDTO->getStrSinProcessos() == "S" && $objPesquisaProtocoloSolrDTO->getStrSinConsiderarDocumentos() == "S"){
      $strJoin = '{!join from=id_proc to=id_prot} ';
    }

    $parametros = new stdClass();
    $parametros->q = InfraSolrUtil::formatarOperadores($objPesquisaProtocoloSolrDTO->getStrPalavrasChave());

    if (is_numeric($objPesquisaProtocoloSolrDTO->getStrPalavrasChave()) && $objPesquisaProtocoloSolrDTO->getStrProtocoloPesquisa()==null){
      $parametros->q = '('.$parametros->q.' OR prot_pesq:*'.$objPesquisaProtocoloSolrDTO->getStrPalavrasChave().'*)';
    }



    if ($parametros->q != '' && $partialfields != '') {
      $parametros->q = $strJoin.'(' . $parametros->q . ')'.' AND ' . $partialfields;
    } else if ($partialfields != '') {
      $parametros->q = $strJoin.$partialfields;
    }

    $parametros->q = utf8_encode($parametros->q);
    $parametros->start = $objPesquisaProtocoloSolrDTO->getNumInicioPaginacao();
    $parametros->rows = 10;
    $strDtaOrd = ($strStaTipoData == "I" ? "dta_inc" : "dta_ger");
    $parametros->sort = $strDtaOrd.' desc, id_prot desc';


    $urlBusca = ConfiguracaoSEI::getInstance()->getValor('Solr', 'Servidor') . '/' . ConfiguracaoSEI::getInstance()->getValor('Solr', 'CoreProtocolos') . '/select?' . http_build_query($parametros) . '&hl=true&hl.snippets=2&hl.fl=content&hl.fragsize=100&hl.maxAnalyzedChars=1048576&hl.alternateField=content&hl.maxAlternateFieldLength=100&fl=id,id_proc,id_doc,id_tipo_proc,id_serie,id_anexo,id_uni_ger,prot_doc,prot_proc,numero,nome_arvore,id_usu_ger,dta_ger,dta_inc';

    //InfraDebug::getInstance()->setBolLigado(true);
    //InfraDebug::getInstance()->gravar('URL:'.$urlBusca);
    //InfraDebug::getInstance()->gravar("PARÂMETROS: " . print_r($parametros, true));

    //die(print_r($parametros,true));

    try {
      $resultados = file_get_contents($urlBusca, false);
    }catch(Exception $e){
      throw new InfraException('Erro realizando pesquisa.',$e, urldecode($urlBusca)."\n\n".print_r($_POST, true),false);
    }

    if ($resultados == '') {
      throw new InfraException('Nenhum retorno encontrado no resultado da pesquisa.');
    }

    $xml = simplexml_load_string($resultados);

    $html = '';

    $arrRet = $xml->xpath('/response/result/@numFound');

    $itens = array_shift($arrRet);

    if ($itens == 0) {

      $html .= "<div class=\"pesquisaSemResultado\">";
      $html .= "Nenhum resultado encontrado.";
      $html .= "<br/>";
      $html .= "<br/>";
      $html .= "Sugestões:";
      $html .= "<ul>";
      $html .= "<li>Certifique-se de que todas as palavras estejam escritas corretamente.</li>";
      $html .= "<li>Tente palavras-chave ou critérios diferentes.</li>";
      $html .= "<li>Tente palavras-chave ou critérios mais genéricos.</li>";
      $html .= "</ul>";
      $html .= "</div>";

    } else if ($itens == 1) {

      $dblIdProcedimento = $xml->xpath("//long[@name='id_proc']");
      if (is_array($dblIdProcedimento)) {
        $dblIdProcedimento = $dblIdProcedimento[0];

        $strLinkArvore = 'controlador.php?acao=procedimento_trabalhar&acao_origem=protocolo_pesquisar&id_procedimento=' . $dblIdProcedimento;

        $dblIdDocumento = $xml->xpath("//long[@name='id_doc']");
        if (is_array($dblIdDocumento)) {
          $dblIdDocumento = $dblIdDocumento[0];
          $strLinkArvore .= '&id_documento=' . $dblIdDocumento;
        }

        if (!$bolArvore) {

          if (!InfraString::isBolVazia($dblIdDocumento)){
            $objPublicacaoDTO = new PublicacaoDTO();
            $objPublicacaoDTO->retNumIdPublicacao();
            $objPublicacaoDTO->setDblIdDocumento($dblIdDocumento);
            $objPublicacaoDTO->setNumMaxRegistrosRetorno(1);

            $objPublicacaoRN = new PublicacaoRN();
            if ($objPublicacaoRN->consultarRN1044($objPublicacaoDTO)!=null){
              $objPesquisaProtocoloSolrDTO->setStrLinkPublicacao(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$dblIdDocumento));
              return;
            }
          }

          header("Location: " . SessaoSEI::getInstance()->assinarLink($strLinkArvore));
          die;

        } else {
          $strParametros = '&id_procedimento=' . $dblIdProcedimento . '&id_documento=' . $dblIdDocumento;
          $strRetorno = '<script type="text/javascript" charset="iso-8859-1">';
          $strRetorno .= 'parent.parent.document.getElementById("ifrArvore").src = "' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=' . $_GET['acao'] . $strParametros . '&montar_visualizacao=1') . '";';
          $strRetorno .= '</script>';
          $objPesquisaProtocoloSolrDTO->setStrResultadoPesquisa($strRetorno);
          return;
        }

      }

    } else {

      $registros = $xml->xpath('/response/result/doc');

      $numRegistros = sizeof($registros);

      if ($numRegistros) {

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $numTipoPesquisaRestrito = $objInfraParametro->getValor('SEI_EXIBIR_ARVORE_RESTRITO_SEM_ACESSO', false);

        $arrProtocolosVisitados = SessaoSEI::getInstance()->getAtributo('PROTOCOLOS_VISITADOS_' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

        $html = SeiSolrUtil::criarBarraEstatisticas($itens, $parametros->start, ($parametros->start + $parametros->rows));

        $arrIdProtocolos = array();
        $arrRegistros = array();
        $arrIdTipoProcedimento = array();
        $arrIdUnidadeGeradora = array();
        $arrIdUsuarioGerador = array();
        $arrIdSerie = array();

        for ($i = 0; $i < $numRegistros; $i++) {

          $regResultado = $registros[$i];

          $dtaGeracao = InfraSolrUtil::obterTag($regResultado, 'dta_ger', 'date');
          $dtaGeracao = preg_replace("/(\\d{4})-(\\d{2})-(\\d{2})(.*)/", "$3/$2/$1", $dtaGeracao);
          $dtaInclusao = InfraSolrUtil::obterTag($regResultado, 'dta_inc', 'date');
          $dtaInclusao = preg_replace("/(\\d{4})-(\\d{2})-(\\d{2})(.*)/", "$3/$2/$1", $dtaInclusao);

          $arrRegistros[$i] = array(
            'id' => InfraSolrUtil::obterTag($regResultado, 'id', 'str'),
            'id_proc' => InfraSolrUtil::obterTag($regResultado, 'id_proc', 'long'),
            'id_doc' => InfraSolrUtil::obterTag($regResultado, 'id_doc', 'long'),
            'id_anexo' => InfraSolrUtil::obterTag($regResultado, 'id_anexo', 'int'),
            'id_uni_ger' => InfraSolrUtil::obterTag($regResultado, 'id_uni_ger', 'int'),
            'id_usu_ger' => InfraSolrUtil::obterTag($regResultado, 'id_usu_ger', 'int'),
            'id_tipo_proc' => InfraSolrUtil::obterTag($regResultado, 'id_tipo_proc', 'int'),
            'id_serie' => InfraSolrUtil::obterTag($regResultado, 'id_serie', 'int'),
            'numero' => InfraSolrUtil::obterTag($regResultado, 'numero', 'str'),
            'nome_arvore' => InfraSolrUtil::obterTag($regResultado, 'nome_arvore', 'str'),
            'prot_doc' => InfraSolrUtil::obterTag($regResultado, 'prot_doc', 'str'),
            'prot_proc' => InfraSolrUtil::obterTag($regResultado, 'prot_proc', 'str'),
            'dta_ger' => $dtaGeracao,
            'dta_inc' => $dtaInclusao
          );

          $arrIdProtocolos[] = $arrRegistros[$i]['id_proc'];

          if ($arrRegistros[$i]['id_doc'] != null) {
            $arrIdProtocolos[] = $arrRegistros[$i]['id_doc'];
          }

          $arrIdTipoProcedimento[$arrRegistros[$i]["id_tipo_proc"]] = 0;

          if ($arrRegistros[$i]["id_serie"] != null) {
            $arrIdSerie[$arrRegistros[$i]["id_serie"]] = 0;
          }

          $arrIdUnidadeGeradora[$arrRegistros[$i]["id_uni_ger"]] = 0;
          $arrIdUsuarioGerador[$arrRegistros[$i]["id_usu_ger"]] = 0;
        }


        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(array_unique($arrIdProtocolos));

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');

        $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
        $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
        $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
        $objTipoProcedimentoDTO->retStrNome();
        $objTipoProcedimentoDTO->setNumIdTipoProcedimento(array_keys($arrIdTipoProcedimento), InfraDTO::$OPER_IN);

        $objTipoProcedimentoRN = new TipoProcedimentoRN();
        $arrObjTipoProcedimentoDTO = InfraArray::indexarArrInfraDTO($objTipoProcedimentoRN->listarRN0244($objTipoProcedimentoDTO), 'IdTipoProcedimento');

        $arrObjSerieDTO = array();
        if (count($arrIdSerie)) {
          $objSerieDTO = new SerieDTO();
          $objSerieDTO->setBolExclusaoLogica(false);
          $objSerieDTO->retNumIdSerie();
          $objSerieDTO->retStrNome();
          $objSerieDTO->setNumIdSerie(array_keys($arrIdSerie), InfraDTO::$OPER_IN);

          $objSerieRN = new SerieRN();
          $arrObjSerieDTO = InfraArray::indexarArrInfraDTO($objSerieRN->listarRN0646($objSerieDTO), 'IdSerie');
        }

        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setBolExclusaoLogica(false);
        $objUsuarioDTO->retNumIdUsuario();
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->setNumIdUsuario(array_keys($arrIdUsuarioGerador), InfraDTO::$OPER_IN);

        $objUsuarioRN = new UsuarioRN();
        $arrObjUsuarioDTOGerador = InfraArray::indexarArrInfraDTO($objUsuarioRN->listarRN0490($objUsuarioDTO), 'IdUsuario');

        $arrObjUnidadeDTO = array();
        for ($i = 0; $i < $numRegistros; $i++) {

          $strDataRegistro = $arrRegistros[$i]['dta_inc'];
          $strChaveHistorico = $arrRegistros[$i]['id_uni_ger'].'_'.$strDataRegistro;
          if (!isset($arrObjUnidadeDTO[$strChaveHistorico])) {
            $objUnidadeDTO = new UnidadeDTO();
            $objUnidadeDTO->setNumIdUnidade($arrRegistros[$i]['id_uni_ger']);
            $objUnidadeDTO->setDtaHistorico($strDataRegistro);
            $arrObjUnidadeDTO[$strChaveHistorico] = $objUnidadeDTO;
          }
        }

        $objHistoricoRN = new HistoricoRN();
        $objHistoricoRN->aplicar('Unidade', $arrObjUnidadeDTO, 'Historico', 'IdUnidade', 'Sigla', 'Descricao','SiglaOrgao','DescricaoOrgao');

        $html .= "<table border=\"0\" class=\"pesquisaResultado\">\n";

        for ($i = 0; $i < $numRegistros; $i++) {

          $dados = $arrRegistros[$i];

          $objObjProtocoloDTOProcesso = null;
          $objProtocoloDTODocumento = null;

          if (!isset($arrObjProtocoloDTO[$dados['id_proc']])){
            continue;
          }

          $objObjProtocoloDTOProcesso = $arrObjProtocoloDTO[$dados['id_proc']];

          $bolPublicacao = false;
          if ($dados['id_doc']!=null){
            if (!isset($arrObjProtocoloDTO[$dados['id_doc']])) {
              continue;
            }else{

              $objProtocoloDTODocumento = $arrObjProtocoloDTO[$dados['id_doc']];

              if ($objProtocoloDTODocumento->getNumCodigoAcesso() < 0 && $objUnidadeDTOAtual->getStrSinProtocolo()=='N'){
                continue;
              }

              if ($objProtocoloDTODocumento->getNumCodigoAcesso() == ProtocoloRN::$CA_DOCUMENTO_PUBLICADO){
                $bolPublicacao = true;
              }
            }
          }

          if ($objObjProtocoloDTOProcesso->getNumCodigoAcesso() < 0 && !$bolPublicacao && $objUnidadeDTOAtual->getStrSinProtocolo()=='N'){
            continue;
          }


          $strNomeTipoProcedimento = '';
          if (isset($arrObjTipoProcedimentoDTO[$dados['id_tipo_proc']])) {
            $strNomeTipoProcedimento = $arrObjTipoProcedimentoDTO[$dados['id_tipo_proc']]->getStrNome();
          } else {
            $strNomeTipoProcedimento = '[tipo de processo não encontrado]';
          }

          $strNomeSerie = '';
          if (isset($arrObjSerieDTO[$dados['id_serie']])) {
            $strNomeSerie = $arrObjSerieDTO[$dados['id_serie']]->getStrNome();
          } else {
            $strNomeSerie = '[tipo de documento não encontrado]';
          }

          if ($strStaTipoData == 'I' ) {
            $strMetaTagData = 'Inclusão';
          }else{
            $strMetaTagData = 'Data';
          }

          $strDataRegistro = $arrRegistros[$i]['dta_inc'];
          if (isset($arrObjUnidadeDTO[$dados['id_uni_ger'].'_'.$strDataRegistro])) {
            $objUnidadeDTO = $arrObjUnidadeDTO[$dados['id_uni_ger'].'_'.$strDataRegistro];
            if (!$objUnidadeDTO->isSetStrSigla()){
              $strSiglaUnidadeGeradora = '[histórico não encontrado]';
              $strDescricaoUnidadeGeradora = '[histórico não encontrado]';
            }else {
              $strSiglaUnidadeGeradora = $objUnidadeDTO->getStrSigla();
              $strDescricaoUnidadeGeradora = $objUnidadeDTO->getStrDescricao();
            }
          } else {
            $strSiglaUnidadeGeradora = '[unidade não encontrada]';
            $strDescricaoUnidadeGeradora = '[unidade não encontrada]';
          }

          if (isset($arrObjUsuarioDTOGerador[$dados['id_usu_ger']])) {
            $strSiglaUsuarioGerador = $arrObjUsuarioDTOGerador[$dados['id_usu_ger']]->getStrSigla();
            $strNomeUsuarioGerador = $arrObjUsuarioDTOGerador[$dados['id_usu_ger']]->getStrNome();
          } else {
            $strSiglaUsuarioGerador = '[usuário não encontrado]';
            $strNomeUsuarioGerador = '[usuário não encontrado]';
          }

          $strIdentificacaoLinkArvore = PaginaSEI::tratarHTML($strNomeTipoProcedimento.' Nº '.$dados["prot_proc"]);
          $strIdentificacaoDocumento = null;
          if ($dados['id_doc'] != null) {
            $strIdentificacaoDocumento = $strNomeSerie.($dados['numero'] != null ? ' '.$dados['numero'] : '');
            $strIdentificacaoLinkArvore .= ' - '.$strIdentificacaoDocumento. ' ('.$dados["prot_doc"].')';
          }

          $strTitulo = '';
          $strLinkDocumento = '';
          $strProtocoloDocumento = '';

          if ($objObjProtocoloDTOProcesso->getNumCodigoAcesso() > 0 || $objUnidadeDTOAtual->getStrSinProtocolo()=='S' ||
            ($bolPublicacao && $objObjProtocoloDTOProcesso->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_RESTRITO && $numTipoPesquisaRestrito=='1')) {

            $strLinkArvore = 'controlador.php?acao=procedimento_trabalhar&acao_origem=protocolo_pesquisar&id_procedimento='.$dados['id_proc'];
            if ($dados['id_doc'] != null) {
              $strLinkArvore .= '&id_documento='.$dados['id_doc'];
            }
            $strLinkArvore = SessaoSEI::getInstance()->assinarLink($strLinkArvore);

            if (!($bolArvore && $dados['id_proc'] == $objPesquisaProtocoloSolrDTO->getDblIdProcedimento())) {
              $strTitulo .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.$strLinkArvore.'" target="_blank" class="arvore">';
              $strTitulo .= '<img border="0" src="'.Icone::ARVORE.'" alt="'.$strIdentificacaoLinkArvore.'" title="'.$strIdentificacaoLinkArvore.'" class="arvore"  tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" />';
              $strTitulo .= '</a>';
            }

            $strCssProcesso = 'protocoloNormal';
            if ($arrProtocolosVisitados != null && isset($arrProtocolosVisitados[$dados['id_proc']])){
              $strCssProcesso .= ' processoVisitado';
            }

            $strTitulo .= '<span>'.PaginaSEI::tratarHTML($strNomeTipoProcedimento.' Nº ').'</span><a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" target="_blank" class="'.$strCssProcesso.'" href="'.$strLinkArvore.'" title="'.PaginaSEI::tratarHTML($strNomeTipoProcedimento).'" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.PaginaSEI::tratarHTML($dados["prot_proc"]).'</a>';

          }else{
            $strTitulo .= '<div style="display:inline-block;width:28px"></div>';
            $strTitulo .= '<span>'.PaginaSEI::tratarHTML(($objObjProtocoloDTOProcesso->getStrStaNivelAcessoGlobal()!=ProtocoloRN::$NA_SIGILOSO ? $strNomeTipoProcedimento.' ' : '').'Nº '.$dados['prot_proc']).'</span>';
          }

          if ($strIdentificacaoDocumento != null) {

            $strCssDocumento = 'protocoloNormal';
            if ($arrProtocolosVisitados != null && isset($arrProtocolosVisitados[$dados['id_doc']])){
              $strCssDocumento .= ' processoVisitado';
            }

            if ($dados['id_anexo'] == null) {
              $strLinkDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem=protocolo_pesquisar&id_documento='.$dados['id_doc']);
            } else {
              $strLinkDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_download_anexo&acao_origem=protocolo_pesquisar&id_anexo='.$dados['id_anexo']);
            }

            $strTitulo .= ' (<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" target="_blank" class="protocoloNormal" href="'.$strLinkDocumento.'" title="'.PaginaSEI::tratarHTML($strIdentificacaoDocumento).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.PaginaSEI::tratarHTML($strIdentificacaoDocumento).'</a>)';

            $strProtocoloDocumento = '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" target="_blank" class="'.$strCssDocumento.'" href="'.$strLinkDocumento.'" title="'.PaginaSEI::tratarHTML($strIdentificacaoDocumento).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.PaginaSEI::tratarHTML($dados["prot_doc"]).'</a>';
          }


          $arrMetatags = array();
          $arrMetatags['Unidade'] = '<a alt="'.PaginaSEI::tratarHTML($strDescricaoUnidadeGeradora).'" title="'.PaginaSEI::tratarHTML($strDescricaoUnidadeGeradora).'" class="ancoraSigla" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.PaginaSEI::tratarHTML($strSiglaUnidadeGeradora).'</a>';
          $arrMetatags['Usuário'] = '<a alt="'.PaginaSEI::tratarHTML($strNomeUsuarioGerador).'" title="'.PaginaSEI::tratarHTML($strNomeUsuarioGerador).'" class="ancoraSigla" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.PaginaSEI::tratarHTML($strSiglaUsuarioGerador).'</a>';
          if ($strStaTipoData == 'I' ) {
            $arrMetatags[$strMetaTagData] = $dados['dta_inc'];
          }else{
            $arrMetatags[$strMetaTagData] = $dados['dta_ger'];
          }

          if ($objProtocoloDTODocumento==null || $objProtocoloDTODocumento->getNumCodigoAcesso() < 0){
            $snippet = '...';
          }else {
            $temp = $xml->xpath("/response/lst[@name='highlighting']/lst[@name='".$dados['id']."']/arr[@name='content']/str");
            $snippet = '';
            for ($j = 0; $j < count($temp); $j++) {
              $snippetTemp = utf8_decode($temp[$j]);
              $snippetTemp = strtoupper(trim(strip_tags($snippetTemp))) == "NULL" ? null : $snippetTemp;
              $snippetTemp = preg_replace("/<br>/i", "<br />", $snippetTemp);
              $snippetTemp = preg_replace("/&lt;.*?&gt;/", "", $snippetTemp);
              $snippet .= trim($snippetTemp).'<b>&nbsp;&nbsp;...&nbsp;&nbsp;</b>';
            }
          }



          // REMOVE TAGS DO TÍTULO
          $strTitulo = preg_replace("/&lt;.*?&gt;/", "", $strTitulo);

          $html .= "<tr class=\"pesquisaTituloRegistro\">\n";
          $html .= "<td colspan=\"2\" class=\"pesquisaTituloEsquerda\">";
          $html .= $strTitulo;
          $html .= "</td>";
          $html .= "<td class=\"pesquisaTituloDireita\">";
          $html .= $strProtocoloDocumento;
          $html .= "</td>";
          $html .= "</tr>";

          if ($snippet != null) {
            $html .= "<tr><td colspan=\"3\" class=\"pesquisaSnippet\">".$snippet."</td></tr>\n";
          }

          if (count($arrMetatags)) {
            $html .= "<tr>";
            foreach ($arrMetatags as $nomeMetaTag => $valorMetaTag) {
              $html .= "<td width=\"33%\" class=\"pesquisaMetatag\"><b>".$nomeMetaTag.":</b> ".$valorMetaTag."</td>\n";
            }
            $html .= "</tr>\n";
          }
        }
        $html .= "</table>\n";

        $html .= SeiSolrUtil::criarBarraNavegacao($itens, $parametros->start, $parametros->rows);
      }
    }

    $objPesquisaProtocoloSolrDTO->setStrResultadoPesquisa($html);
  }
}
?>