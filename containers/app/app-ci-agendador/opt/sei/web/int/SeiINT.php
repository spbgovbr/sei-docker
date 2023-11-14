<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/05/2014 - criado por mga
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SeiINT extends InfraINT {

  public static $MSG_ERRO_XSS = 'Documento possui conteúdo não permitido';
  private static $NIVEL_VERIFICACAO_ROTINA = null;


  public static function montarHeaderFavicon($strDir){
    echo '<link rel="apple-touch-icon" sizes="180x180" href="'.$strDir.'/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="'.$strDir.'/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="'.$strDir.'/favicon-16x16.png">
<link rel="manifest" href="'.$strDir.'/sei.webmanifest">
<link rel="mask-icon" href="'.$strDir.'/safari-pinned-tab.svg" color="#5bbad5">
<link rel="shortcut icon" href="'.$strDir.'/favicon.ico">
<meta name="msapplication-TileColor" content="#0994dc">
<meta name="msapplication-config" content="'.$strDir.'/browserconfig.xml">
<meta name="theme-color" content="#0994dc">
';
  }

  public static function validarHttps(){
    
    $bolHttps = ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','https');
    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on');
    
    if (($bolHttps && !$isHttps) || (!$bolHttps && $isHttps)){
      
      $strServer = ConfiguracaoSEI::getInstance()->getValor('SEI','URL');
    
      $posIni = strpos($strServer, '//');
      if ($posIni!==false){
        $strServer = substr($strServer, $posIni+2);
      }
    
      $posFim = strpos($strServer, '/');
      if ($posFim!==false){
        $strServer = substr($strServer, 0, $posFim);
      }
      
      header('Location: '.($bolHttps?'https':'http').'://'.$strServer.$_SERVER['REQUEST_URI']);
      die;
    }
  }
  
  public static function obterURL(){
    
    $strURL = ConfiguracaoSEI::getInstance()->getValor('SEI','URL');
    
    if (ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','https')){
      $strURL = str_replace('http://','https://',$strURL);
    }else{
      $strURL = str_replace('https://','http://',$strURL);
    }
    return $strURL.'/';
  }

  public static function download($objAnexoDTO = null, $varConteudo = null, $strNomeArquivoTemporario = null, $strNomeArquivoDownload = null, $strContentDisposition = 'inline', $strIdentificacao = null, $dblIdDocumento = null, $bolOriginal = false, $bolMarcarLinksSei = false){

    try {

      LimiteSEI::getInstance()->configurarNivel2();

      $strCaminhoNomeArquivo = null;

      if ($objAnexoDTO != null) {

        $objAnexoRN = new AnexoRN();
        $strCaminhoNomeArquivo = $objAnexoRN->obterLocalizacao($objAnexoDTO);

        if ($strNomeArquivoDownload == null) {
          $strNomeArquivoDownload = $objAnexoDTO->getStrNome();
        }

        try {
          $numTamanho = filesize($strCaminhoNomeArquivo);
        }catch(Throwable $e){
          if (!file_exists($strCaminhoNomeArquivo)) {
            throw new InfraException('Arquivo não encontrado no repositório.', null, $strCaminhoNomeArquivo);
          }
          throw $e;
        }

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $strVerificacaoHash = $objInfraParametro->getValor('SEI_HABILITAR_VERIFICACAO_REPOSITORIO', false);

        if ($strVerificacaoHash == '1') {
          if ($numTamanho > TAM_BLOCO_LEITURA_ARQUIVO) {

            if (md5_file($strCaminhoNomeArquivo) != $objAnexoDTO->getStrHash()) {
              throw new InfraException('Conteúdo do arquivo corrompido.', null, $strCaminhoNomeArquivo);
            }

          } else {

            $fp = fopen($strCaminhoNomeArquivo, "rb");
            $varConteudo = fread($fp, TAM_BLOCO_LEITURA_ARQUIVO);
            fclose($fp);

            if (md5($varConteudo) != $objAnexoDTO->getStrHash()) {
              throw new InfraException('Conteúdo do arquivo corrompido.', null, $strCaminhoNomeArquivo);
            }
          }
        }

      } else if ($strNomeArquivoTemporario != null) {
        $strCaminhoNomeArquivo = DIR_SEI_TEMP.'/'.$strNomeArquivoTemporario;

        if ($strNomeArquivoDownload == null) {
          $strNomeArquivoDownload = $strNomeArquivoTemporario;
        }

        if (dirname(realpath($strCaminhoNomeArquivo)) != realpath(DIR_SEI_TEMP)){
          throw new InfraException('Caminho para o arquivo temporário inválido.', null, dirname(realpath($strCaminhoNomeArquivo)));
        }
      }

      $strMimeType = InfraUtil::getStrMimeType($strNomeArquivoDownload);

      $strContentType = 'Content-Type: ' . $strMimeType . ';';

      if ($strCaminhoNomeArquivo != null && ($strMimeType == 'text/html' || $strMimeType == 'text/plain')) {

        $strCharset = strtolower(InfraUtil::obterCharsetArquivo($strCaminhoNomeArquivo));

        if ($strCharset == 'utf-8' || $strCharset == 'iso-8859-1') {
          $strContentType .= ' charset='.$strCharset;
        }
      }

      $bolCabecalhoEvitarXSS = false;

      if ($strMimeType == 'text/html'){

        $bolCabecalhoEvitarXSS = true;

        if (!$bolOriginal) {

          if ($varConteudo == null) {
            $varConteudo = file_get_contents($strCaminhoNomeArquivo);
          }

          if (self::validarXss($varConteudo, true, false, $strIdentificacao, $dblIdDocumento, $strCaminhoNomeArquivo, $strCharset)){
            self::converterHtmlPdf($varConteudo, $strIdentificacao);
            die;
          }
        }
      }

      InfraPagina::montarHeaderDownload($strNomeArquivoDownload, $strContentDisposition, $strContentType, $bolCabecalhoEvitarXSS);

      ob_start();

      if ($varConteudo != null){

        if($bolMarcarLinksSei){
          $varConteudo = str_replace('</head>', "\n".DocumentoINT::$LINK_VISUALIZACAO_CSS."\n".DocumentoINT::$LINK_VISUALIZACAO_JS."\n</head>", $varConteudo);
        }

        echo $varConteudo;

      }else {

        $fp = fopen($strCaminhoNomeArquivo, "rb");

        while (!feof($fp)) {

          echo fread($fp, TAM_BLOCO_LEITURA_ARQUIVO);

          if (ob_get_length()) {
            ob_flush();
            flush();
            ob_end_flush();
          }
        }

        fclose($fp);
      }

      if (ob_get_length()) {
        @ob_flush();
        @flush();
        @ob_end_flush();
      }

      //@ob_end_clean();

      if ($strNomeArquivoTemporario != null){
        unlink(DIR_SEI_TEMP.'/'.$strNomeArquivoTemporario);
      }

    }catch(Throwable $e){

      if (strpos(strtoupper($e->__toString()),'NO SUCH FILE OR DIRECTORY')!==false || strpos(strtoupper($e->__toString()),'STAT FAILED FOR')!==false){
        throw new InfraException('Erro acessando o sistema de arquivos.', $e);
      }

      throw $e;
    }
  }

  public static function getContentDisposition($strNomeArquivo){

    $ret = 'inline';

    $strMimeType = InfraUtil::getStrMimeType($strNomeArquivo);

    $strTipo = substr($strMimeType, 0, 6);

    if ($strTipo == 'video/' || $strTipo == 'audio/' || $strMimeType == 'application/zip' || $strMimeType == 'application/rar') {
      $ret = 'attachment';
    }

    return $ret;
  }

  public static function validarXss(&$strConteudo, $bolDownload = false, $bolFiltrar = false, $strIdentificacao = null, $dblIdDocumento = null, $strNomeArquivo = null, $strCharset = null){
    try {

      $arrXssExcecoes = ConfiguracaoSEI::getInstance()->getValor('XSS', 'ProtocolosExcecoes', false, array());

      if ($strIdentificacao != null) {

        if (in_array($strIdentificacao, $arrXssExcecoes)) {
          return false;
        }

        $strIdentificacao = ' ('.$strIdentificacao.')';
      }

      if ($strNomeArquivo != null){
        $strNomeArquivo = ', arquivo '.$strNomeArquivo;
      }

      if (self::$NIVEL_VERIFICACAO_ROTINA == null){
        $strXssNivelValidacao = ConfiguracaoSEI::getInstance()->getValor('XSS', 'NivelVerificacao', false, 'A');
      }else{
        $strXssNivelValidacao = self::$NIVEL_VERIFICACAO_ROTINA;
      }

      if (!in_array($strXssNivelValidacao,array('N','B','A'))){
        throw new InfraException('Nível de verificação de XSS inválido ['.$strXssNivelValidacao.'].');
      }

      if (trim($strConteudo)!='') {

        if ($strXssNivelValidacao == 'B') {

          $arrXssBasico = ConfiguracaoSEI::getInstance()->getValor('XSS', 'NivelBasico', false, null);

          $arrXssNaoPermitidosBasico = null;
          if ($arrXssBasico !== null) {
            if (isset($arrXssBasico['ValoresNaoPermitidos']) && $arrXssBasico['ValoresNaoPermitidos'] !== null) {
              $arrXssNaoPermitidosBasico = $arrXssBasico['ValoresNaoPermitidos'];
            }
          }

          $objInfraXSS = new InfraXSS();
          $arrRetBasico = $objInfraXSS->verificacaoBasica($strConteudo, $arrXssNaoPermitidosBasico);

          if ($arrRetBasico != null) {

            if (InfraArray::contar($arrRetBasico) == 1) {
              $strEncontrados = ', encontrado '.$arrRetBasico[0];
            } else {
              $strEncontrados = ', encontrados '.implode(' | ', $arrRetBasico);
            }

            $objInfraExceptionXss = new InfraException(self::$MSG_ERRO_XSS.$strIdentificacao.'.', null, 'Nível '.$strXssNivelValidacao.$strEncontrados.$strNomeArquivo.'.');

            if ($bolDownload) {
              LogSEI::getInstance()->gravar('Descrição:'."\n".$objInfraExceptionXss->getStrDescricao()."\n\nDetalhes:\n".$objInfraExceptionXss->getStrDetalhes());
              return true;
            } else {
              throw $objInfraExceptionXss;
            }
          }

        } else if ($strXssNivelValidacao == 'A') {

          $arrXssAvancadoTagsPermitidas = null;
          $arrXssAvancadoTagsAtributosPermitidos = null;

          $arrXssAvancado = ConfiguracaoSEI::getInstance()->getValor('XSS', 'NivelAvancado', false, null);

          if ($arrXssAvancado !== null) {

            if (isset($arrXssAvancado['TagsPermitidas']) && $arrXssAvancado['TagsPermitidas'] !== null) {
              $arrXssAvancadoTagsPermitidas = $arrXssAvancado['TagsPermitidas'];
            }

            if (isset($arrXssAvancado['TagsAtributosPermitidos']) && $arrXssAvancado['TagsAtributosPermitidos'] !== null) {
              $arrXssAvancadoTagsAtributosPermitidos = $arrXssAvancado['TagsAtributosPermitidos'];
            }
          }

          $bolUtf8 = ($bolDownload && $strCharset == 'utf-8');

          $strConteudoXss = $strConteudo;

          $strConteudoXss = preg_replace('/(Criado por\s*<a )onclick="alert\(\'(?:[0-9\.\,\pL \-_]|\\\\&#039;)*\'\)" alt/i', '$1alt', $strConteudoXss);
          $strConteudoXss = preg_replace('/(<\/a>, versão \d* por\s+<a )onclick="alert\(\'(?:[0-9\.\,\pL \-_]|\\\\&#039;)*\'\)" alt/i', '$1alt', $strConteudoXss);

          if (!$bolUtf8) {
            $strConteudoXss = utf8_encode($strConteudoXss);
          }

          $objInfraXSS = new InfraXSS();

          $bolXss = $objInfraXSS->verificacaoAvancada($strConteudoXss, $arrXssAvancadoTagsPermitidas, $arrXssAvancadoTagsAtributosPermitidos, !$bolDownload);

          if ($bolXss) {

            if ($bolDownload) {
              return true;
            }

            if ($strConteudoXss != '') {

              $strDiferencas = $objInfraXSS->getStrDiferenca();

              if (!$bolUtf8) {
                $strConteudoXss = utf8_decode($strConteudoXss);
                $strDiferencas = utf8_decode($strDiferencas);
              }

            } else {
              $strDiferencas = "Não foi possível processar o conteúdo.";
            }

            $strDiferencas = "\n\nDiferenças:\n".$strDiferencas;

            $strUsuario = '';
            if (SessaoSEI::getInstance()->getStrSiglaUsuario() !== null) {
              $strUsuario .= ", usuário ".SessaoSEI::getInstance()->getStrSiglaUsuario();

              if (SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario() !== null) {
                $strUsuario .= '/'.SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario();
              }
            }

            if ($dblIdDocumento != null) {
              $strIdConteudo = ', id_documento '.$dblIdDocumento;

              $objProtocoloDTO = new ProtocoloDTO();
              $objProtocoloDTO->retStrStaNivelAcessoGlobal();
              $objProtocoloDTO->setDblIdProtocolo($dblIdDocumento);

              $objProtocoloRN = new ProtocoloRN();
              $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

              if ($objProtocoloDTO != null && $objProtocoloDTO->getStrStaNivelAcessoGlobal() != ProtocoloRN::$NA_PUBLICO) {
                $strDiferencas = '';
              }
            }

            if ($bolFiltrar) {
              $strConteudo = $strConteudoXss;
            }

            throw new InfraException(self::$MSG_ERRO_XSS.$strIdentificacao.'.', null, 'Nível '.$strXssNivelValidacao.$strUsuario.$strIdConteudo.$strNomeArquivo.'.'.$strDiferencas);
          }
        }
      }

      return false;

    }catch(Throwable $e){
      throw new InfraException('Erro validando XSS.', $e);
    }
  }
  
  public static function rotinaVerificaoXss($strNivelVerificacao, $dtaInicio, $dtaFim){
    try{

      BancoSEI::getInstance()->abrirConexao();

      $objInfraException = new InfraException();

      LimiteSEI::getInstance()->configurarNivel3();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      self::logar('Verificação XSS - Iniciando análise de documentos...');

      if (InfraString::isBolVazia($strNivelVerificacao)){
        $objInfraException->lancarValidacao('Nível de verificação não informado.');
      }

      if (!in_array($strNivelVerificacao,array('B','A'))){
        throw new InfraException('Nível de verificação de XSS "'.$strNivelVerificacao.'" inválido valores possíveis "A" (Avançado) e "B" (Básico).');
      }

      self::$NIVEL_VERIFICACAO_ROTINA = $strNivelVerificacao;

      $dtaInicio = trim($dtaInicio);
      $dtaFim = trim($dtaFim);

      if ($dtaInicio!='' || $dtaFim!='') {

        if (InfraString::isBolVazia($dtaInicio)){
          $objInfraException->lancarValidacao('Data inicial não informada.');
        }

        if (InfraString::isBolVazia($dtaFim)){
          $objInfraException->lancarValidacao('Data final não informada.');
        }

        if (!InfraData::validarData($dtaInicio)) {
          $objInfraException->lancarValidacao("Data inicial [" . $dtaInicio . "] inválida.\n");
        }

        if (!InfraData::validarData($dtaFim)) {
          $objInfraException->lancarValidacao("Data final [" . $dtaFim . "] inválida.\n");
        }

        if (InfraData::compararDatas($dtaInicio, $dtaFim)<0){
          $objInfraException->lancarValidacao("Período inválido.");
        }
      }

      if ($dtaInicio!=null && $dtaFim!=null) {
        self::logar('Verificação XSS - '.$dtaInicio.' ate '.$dtaFim.'...');
      }

      $arrXssExcecoes = ConfiguracaoSEI::getInstance()->getValor('XSS', 'ProtocolosExcecoes', false, array());

      $numIgnorar = InfraArray::contar($arrXssExcecoes);
      if ($numIgnorar==0){
        self::logar('Verificação XSS - Nenhuma exceção configurada...');
      }else if ($numIgnorar==1){
        self::logar('Verificação XSS - 1 exceção configurada...');
      }else{
        self::logar('Verificação XSS - '.$numIgnorar.' exceções configuradas...');
      }

      $objProtocoloRN 	= new ProtocoloRN();

      $objProtocoloDTO 	= new ProtocoloDTO();
      $objProtocoloDTO->setDistinct(true);
      $objProtocoloDTO->retDtaInclusao();
      $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO, InfraDTO::$OPER_DIFERENTE);

      if ($dtaInicio!=null && $dtaFim!=null) {
        $objProtocoloDTO->adicionarCriterio(array('Inclusao', 'Inclusao'),
            array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
            array($dtaInicio, $dtaFim),
            InfraDTO::$OPER_LOGICO_AND);
      }

      $objProtocoloDTO->setOrdDtaInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjProtocoloDTOData = $objProtocoloRN->listarRN0668($objProtocoloDTO);

      $objEditorRN = new EditorRN();
      $objAnexoRN = new AnexoRN();
      $objDocumentoRN = new DocumentoRN();

      $numRegistrosProcessados = 0;
      $numErros = 0;

      foreach($arrObjProtocoloDTOData as $objProtocoloDTOData){

        $dtaInclusao = $objProtocoloDTOData->getDtaInclusao();

        self::logar('Verificação XSS - Data '.$dtaInclusao.'...');

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->retStrStaDocumentoDocumento();
        $objProtocoloDTO->setDtaInclusao($dtaInclusao);
        $objProtocoloDTO->retStrSiglaUnidadeGeradora();
        $objProtocoloDTO->retStrNomeSerieDocumento();
        $objProtocoloDTO->retStrStaNivelAcessoGlobal();
        $objProtocoloDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_DESC);
        $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

        $numRegistros 			=	count($arrObjProtocoloDTO);
        $numRegistrosPagina = 50;
        $numPaginas 				= ceil($numRegistros/$numRegistrosPagina);

        $arrObjNivelAcessoDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarNiveisAcessoRN0878(),'StaNivel');

        for ($numPaginaAtual = 0; $numPaginaAtual < $numPaginas; $numPaginaAtual++) {

          $arrObjProtocoloDTOPagina = array_slice($arrObjProtocoloDTO, ($numPaginaAtual * $numRegistrosPagina), $numRegistrosPagina);

          foreach($arrObjProtocoloDTOPagina as $objProtocoloDTOPagina){

            if (in_array($objProtocoloDTOPagina->getStrProtocoloFormatado(),$arrXssExcecoes)) {
              self::logar('Verificação XSS - Documento '.$objProtocoloDTOPagina->getStrProtocoloFormatado().' ignorado');
            }else{

              $strComplemento = '[ID='.$objProtocoloDTOPagina->getDblIdProtocolo().', Protocolo='.$objProtocoloDTOPagina->getStrProtocoloFormatado().', Tipo='.$objProtocoloDTOPagina->getStrNomeSerieDocumento().', Unidade='.$objProtocoloDTOPagina->getStrSiglaUnidadeGeradora().', Acesso='.$arrObjNivelAcessoDTO[$objProtocoloDTOPagina->getStrStaNivelAcessoGlobal()]->getStrDescricao().']';

              if ($objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_EDITOR_INTERNO) {

                $numRegistrosProcessados++;

                try {

                  $objEditorDTO = new EditorDTO();
                  $objEditorDTO->setDblIdDocumento($objProtocoloDTOPagina->getDblIdProtocolo());
                  $objEditorDTO->setNumIdBaseConhecimento(null);
                  $objEditorDTO->setStrSinCabecalho('S');
                  $objEditorDTO->setStrSinRodape('S');
                  $objEditorDTO->setStrSinCarimboPublicacao('N');
                  $objEditorDTO->setStrSinIdentificacaoVersao('N');
                  $objEditorDTO->setStrSinProcessarLinks('N');
                  $objEditorDTO->setStrSinValidarXss('S');

                  $objEditorRN->consultarHtmlVersao($objEditorDTO);

                } catch (Throwable $excXss) {
                  $numErros++;

                  if (strpos($excXss->__toString(), self::$MSG_ERRO_XSS) !== false) {
                    self::logar('Verificação XSS - '.$excXss->getStrDescricao().' '.$excXss->getStrDetalhes()."\n\n".$strComplemento);
                  }else{
                    self::logar(InfraException::inspecionar($excXss));
                  }
                }


              } else if ($objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_EXTERNO ||
                         $objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO ||
                         $objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_GERADO) {


                if ($objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_AUTOMATICO || $objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_FORMULARIO_GERADO){

                  try{
                    $objDocumentoDTO = new DocumentoDTO();
                    $objDocumentoDTO->setDblIdDocumento($objProtocoloDTOPagina->getDblIdProtocolo());
                    $objDocumentoDTO->setObjInfraSessao(SessaoSEI::getInstance());
                    $objDocumentoDTO->setStrLinkDownload(null);
                    $objDocumentoDTO->setStrSinValidarXss('S');

                    $objDocumentoRN->consultarHtmlFormulario($objDocumentoDTO);
                  } catch (Throwable $excXss) {
                    $numErros++;
                    if (strpos($excXss->__toString(), self::$MSG_ERRO_XSS) !== false) {
                      self::logar('Verificação XSS - '.$excXss->getStrDescricao().' '.$excXss->getStrDetalhes()."\n\n".$strComplemento);
                    }else{
                      self::logar(InfraException::inspecionar($excXss));
                    }
                  }
                }

                $objAnexoDTO = new AnexoDTO();
                $objAnexoDTO->retNumIdAnexo();
                $objAnexoDTO->retDthInclusao();
                $objAnexoDTO->retStrNome();
                $objAnexoDTO->retDthInclusao();
                $objAnexoDTO->retNumTamanho();
                $objAnexoDTO->retStrHash();
                $objAnexoDTO->setDblIdProtocolo($objProtocoloDTOPagina->getDblIdProtocolo());

                $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

                foreach ($arrObjAnexoDTO as $objAnexoDTO) {

                  if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/html') {

                    $numRegistrosProcessados++;

                    $strCaminhoArquivo = $objAnexoRN->obterLocalizacao($objAnexoDTO);

                    $strMsg = '';
                    if (!file_exists($strCaminhoArquivo)) {
                      $strMsg = $strCaminhoArquivo.' não encontrado ';
                    } else if (filesize($strCaminhoArquivo) != $objAnexoDTO->getNumTamanho()) {
                      $strMsg = $strCaminhoArquivo.' tamanho diferente ';
                    } else if (md5_file($strCaminhoArquivo) != $objAnexoDTO->getStrHash()) {
                      $strMsg = $strCaminhoArquivo.' conteúdo corrompido ';
                    }

                    if ($strMsg != '') {

                      $numErros++;
                      self::logar($strMsg.' (documento associado '.$objProtocoloDTOPagina->getStrProtocoloFormatado().')');

                    } else {

                      try {
                        $strConteudo = file_get_contents($objAnexoRN->obterLocalizacao($objAnexoDTO));
                        if ($objProtocoloDTOPagina->getStrStaDocumentoDocumento() == DocumentoRN::$TD_EXTERNO){
                          self::validarXss($strConteudo, false, false, $objProtocoloDTOPagina->getStrProtocoloFormatado(), $objProtocoloDTOPagina->getDblIdProtocolo(), $strCaminhoArquivo);
                        }else{
                          self::validarXss($strConteudo, false, false, $objProtocoloDTOPagina->getStrProtocoloFormatado().', anexo '.$objAnexoDTO->getStrNome(), $objProtocoloDTOPagina->getDblIdProtocolo(), $strCaminhoArquivo);
                        }

                      } catch (Throwable $excXss) {
                        $numErros++;
                        if (strpos($excXss->__toString(), self::$MSG_ERRO_XSS) !== false) {
                          self::logar('Verificação XSS - '.$excXss->getStrDescricao().' '.$excXss->getStrDetalhes()."\n\n".$strComplemento);
                        }else{
                          self::logar(InfraException::inspecionar($excXss));
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

      self::logar('Verificação XSS - '.InfraUtil::formatarMilhares($numRegistrosProcessados).' documentos verificados em '.InfraData::formatarTimestamp($numSeg). ' ('.InfraUtil::formatarMilhares($numErros).' erros)');

      $numSeg = InfraUtil::verificarTempoProcessamento();

      self::logar('Verificação XSS - Iniciando análise de bases de conhecimento...');

      $objBaseConhecimentoRN 	= new BaseConhecimentoRN();
      $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
      $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
      $objBaseConhecimentoDTO->retStrDescricao();
      $objBaseConhecimentoDTO->retStrSiglaUnidade();
      $objBaseConhecimentoDTO->retStrStaDocumento();
      $objBaseConhecimentoDTO->retDblIdDocumentoEdoc();
      $objBaseConhecimentoDTO->setStrStaEstado(array(BaseConhecimentoRN::$TE_LIBERADO, BaseConhecimentoRN::$TE_RASCUNHO), InfraDTO::$OPER_IN);

      if ($dtaInicio!=null && $dtaFim!=null) {
        $objBaseConhecimentoDTO->adicionarCriterio(array('Geracao', 'Geracao'),
            array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
            array($dtaInicio.' 00:00:00', $dtaFim.' 23:59:59'),
            InfraDTO::$OPER_LOGICO_AND);
      }

      $objBaseConhecimentoDTO->setOrdNumIdBaseConhecimento(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjBaseConhecimentoDTO =	$objBaseConhecimentoRN->listar($objBaseConhecimentoDTO);

      $numRegistros 			=	count($arrObjBaseConhecimentoDTO);
      $numRegistrosPagina = 10;
      $numPaginas 				= ceil($numRegistros/$numRegistrosPagina);

      $numRegistrosProcessados = 0;
      $numErros = 0;

      $objEditorRN = new EditorRN();
      $objEdocRN = new EDocRN();

      for ($numPaginaAtual = 0; $numPaginaAtual < $numPaginas; $numPaginaAtual++){

        if ($numPaginaAtual ==  ($numPaginas-1)){
          $numRegistrosAtual = $numRegistros;
        }else{
          $numRegistrosAtual = ($numPaginaAtual+1)*$numRegistrosPagina;
        }

        self::logar('Verificação XSS - Bases de Conhecimento - ['.$numRegistrosAtual.' de '.$numRegistros.']...');

        $offset = ($numPaginaAtual*$numRegistrosPagina);

        if (($offset + $numRegistrosPagina) > $numRegistros) {
          $length = $numRegistros - $offset;
        }else{
          $length = $numRegistrosPagina;
        }

        $arrBasesConhecimentoDTOPagina = array_slice($arrObjBaseConhecimentoDTO, $offset, $length);

        foreach($arrBasesConhecimentoDTOPagina as $objBaseConhecimentoDTOPagina) {

          $numRegistrosProcessados++;

          try {

            if ($objBaseConhecimentoDTOPagina->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC){

              $objDocumentoDTO = new DocumentoDTO();
              $objDocumentoDTO->setDblIdDocumentoEdoc($objBaseConhecimentoDTOPagina->getDblIdDocumentoEdoc());
              $objEdocRN->consultarHTMLDocumentoRN1204($objDocumentoDTO);

            }else {

              $objEditorDTO = new EditorDTO();
              $objEditorDTO->setDblIdDocumento(null);
              $objEditorDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTOPagina->getNumIdBaseConhecimento());
              $objEditorDTO->setStrSinCabecalho('S');
              $objEditorDTO->setStrSinRodape('S');
              $objEditorDTO->setStrSinCarimboPublicacao('N');
              $objEditorDTO->setStrSinIdentificacaoVersao('N');
              $objEditorDTO->setStrSinProcessarLinks('N');
              $objEditorDTO->setStrSinValidarXss('S');

              $objEditorRN->consultarHtmlVersao($objEditorDTO);
            }

          } catch (Throwable $excXss) {
            $numErros++;
            if (strpos($excXss->__toString(), self::$MSG_ERRO_XSS) !== false) {
              self::logar('Verificação XSS - '.$excXss->getStrDescricao().' '.$excXss->getStrDetalhes());
            }else{
              self::logar(InfraException::inspecionar($excXss));
            }
          }

          $objAnexoDTO = new AnexoDTO();
          $objAnexoDTO->retNumIdAnexo();
          $objAnexoDTO->retDthInclusao();
          $objAnexoDTO->retStrNome();
          $objAnexoDTO->retDthInclusao();
          $objAnexoDTO->retNumTamanho();
          $objAnexoDTO->retStrHash();
          $objAnexoDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTOPagina->getNumIdBaseConhecimento());

          $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

          foreach ($arrObjAnexoDTO as $objAnexoDTO) {

            if (InfraUtil::getStrMimeType($objAnexoDTO->getStrNome()) == 'text/html') {

              $numRegistrosProcessados++;

              $strCaminhoArquivo = $objAnexoRN->obterLocalizacao($objAnexoDTO);

              $strMsg = '';
              if (!file_exists($strCaminhoArquivo)) {
                $strMsg = $strCaminhoArquivo.' não encontrado ';
              } else if (filesize($strCaminhoArquivo) != $objAnexoDTO->getNumTamanho()) {
                $strMsg = $strCaminhoArquivo.' tamanho diferente ';
              } else if (md5_file($strCaminhoArquivo) != $objAnexoDTO->getStrHash()) {
                $strMsg = $strCaminhoArquivo.' conteúdo corrompido ';
              }

              if ($strMsg != '') {

                $numErros++;
                self::logar($strMsg.' (base de conhecimento associada '.$objBaseConhecimentoDTOPagina->getStrDescricao().'/'.$objBaseConhecimentoDTOPagina->getStrSiglaUnidade().')');

              } else {

                try {
                  $strConteudo = file_get_contents($objAnexoRN->obterLocalizacao($objAnexoDTO));
                  self::validarXss($strConteudo, false, false, 'base de conhecimento '.$objBaseConhecimentoDTOPagina->getStrDescricao().'/'.$objBaseConhecimentoDTOPagina->getStrSiglaUnidade().', anexo '.$objAnexoDTO->getStrNome(), null, $strCaminhoArquivo);
                } catch (Throwable $excXss) {
                  $numErros++;
                  if (strpos($excXss->__toString(), self::$MSG_ERRO_XSS) !== false) {
                    self::logar('Verificação XSS - '.$excXss->getStrDescricao().' '.$excXss->getStrDetalhes());
                  }else{
                    self::logar(InfraException::inspecionar($excXss));
                  }
                }
              }
            }
          }
        }
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

      self::logar('Verificação XSS - '.InfraUtil::formatarMilhares($numRegistrosProcessados).' bases de conhecimento verificadas em '.InfraData::formatarTimestamp($numSeg). ' ('.InfraUtil::formatarMilhares($numErros).' erros)');

      BancoSEI::getInstance()->fecharConexao();
      
    }catch(Throwable $e){
      throw new InfraException('Erro na rotina de verificação de XSS.', $e);
    }
  }

  private static function logar($strTexto, $strTipoLog='I'){
    InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($strTexto));
    LogSEI::getInstance()->gravar($strTexto,$strTipoLog);
  }

  private static function converterHtmlPdf($strConteudo, $strIdentificacao){

    try{

      $objAnexoRN = new AnexoRN();
      $strArquivoTemp = $objAnexoRN->gerarNomeArquivoTemporario();
      $strArquivoTempHtml = $strArquivoTemp.'.html';
      $strArquivoTempPdf = $strArquivoTemp.'.pdf';

      if (file_put_contents(DIR_SEI_TEMP.'/'.$strArquivoTempHtml, $strConteudo) === false) {
        throw new InfraException('Erro criando arquivo html temporário para criação de pdf para visualização.');
      }

      $strComandoExecucao = DocumentoRN::montarComandoGeracaoPdf('documento-'.InfraUtil::retirarFormatacao($strIdentificacao,false), DIR_SEI_TEMP.'/'.$strArquivoTempHtml, DIR_SEI_TEMP.'/'.$strArquivoTempPdf);

      $ret = shell_exec($strComandoExecucao);
      if ($ret != '') {

        //LogSEI::getInstance()->gravar("Erro gerando PDF para visualização ".$strIdentificacao.".\n\nComando - ".$strComandoExecucao."\n\nRetorno - ".$ret);

        SeiINT::download(null, null, $strArquivoTempHtml, InfraUtil::formatarNomeArquivo($strIdentificacao.'.html'), 'attachment', null, null, true);

      }else{

        unlink(DIR_SEI_TEMP.'/'.$strArquivoTempHtml);

        self::download(null, null, $strArquivoTempPdf, InfraUtil::formatarNomeArquivo($strIdentificacao.'.pdf'), 'inline');
      }

    }catch(Throwable $e){
      throw new InfraException('Erro convertendo conteúdo para visualização.', $e);
    }
  }

  public static function compararXss($strHtml, EditorDTO $parObjEditorDTO){

    try {

      $strHtmlXss = $strHtml = utf8_encode($strHtml);

      $excXss = null;

      try{

        SeiINT::validarXss($strHtmlXss, false, true, null, $parObjEditorDTO->getDblIdDocumento());

      }catch(Throwable $e){
        if (strpos($e->__toString(), self::$MSG_ERRO_XSS) !== false) {
          $excXss = $e;
        }else {
          throw $e;
        }
      }

      if ($excXss!=null) {
        $pregHref='/href=\"[^\"]*\"/';


        $arrMatchesOriginal=array();
        $qtd=preg_match_all($pregHref,$strHtml,$arrMatchesOriginal,PREG_OFFSET_CAPTURE);
        if($qtd>0){
          $arrMatchesXss=array();
          $qtdXss=preg_match_all($pregHref,$strHtmlXss,$arrMatchesXss,PREG_OFFSET_CAPTURE);

          if($qtd===$qtdXss){
            for($i=0;$i<$qtd;$i++){
              if($arrMatchesOriginal[$i][0][0]!==$arrMatchesXss[$i][0][0]){
                $pos=$arrMatchesXss[$i][0][1];
                $strTemp=substr($strHtmlXss,$pos);
                $strTemp=preg_replace($pregHref,'href=""',$strTemp,1);
                $strHtmlXss=substr($strHtmlXss,0,$pos).$strTemp;
              }
            }
          }
        }
        $strHtmlOriginal=utf8_decode(InfraXSS::prepararTexto($strHtml));

        $strComparacao = InfraHTML::comparar($strHtmlOriginal, $strHtmlXss);

        $strComparacao = str_replace('<style type="text/css">', '<style type="text/css">'."\n".InfraHTML::getCssComparacao(), $strComparacao);

        $objAnexoRN = new AnexoRN();
        $strArquivoTemp = $objAnexoRN->gerarNomeArquivoTemporario().'.html';

        if (file_put_contents(DIR_SEI_TEMP.'/'.$strArquivoTemp, $strComparacao) === false) {
          throw new InfraException('Erro criando arquivo HTML temporário para comparação.');
        }

        $parObjEditorDTO->setStrArquivoComparacaoXss($strArquivoTemp);

        throw $excXss;
      }

    }catch(Throwable $e){
      throw new InfraException('Erro comparando XSS.', $e);
    }
  }

  public static function definirIdioma($strDominio, &$arrIdiomas, &$locale){
    if(!empty($strDominio)) {
      define('LANG', 'pt_BR');
      $locale = LANG;
      $textdomain = $strDominio;
      $strCaminhoArquivo = "/LC_MESSAGES/" . $strDominio . ".mo";
      $locales_dir = dirname(__FILE__) . '/../i18n';

      $arrIdiomas = array();
      if (file_exists($locales_dir . '/pt_BR' . $strCaminhoArquivo)) $arrIdiomas['pt_BR'] = array('Português', 'PT');
      if (file_exists($locales_dir . '/es_ES' . $strCaminhoArquivo)) $arrIdiomas['es_ES'] = array('Español', 'ES');
      if (file_exists($locales_dir . '/fr_FR' . $strCaminhoArquivo)) $arrIdiomas['fr_FR'] = array('Français', 'FR');
      if (file_exists($locales_dir . '/en_US' . $strCaminhoArquivo)) $arrIdiomas['en_US'] = array('English', 'EN');

      if (isset($_GET['lang']) && !empty($_GET['lang']) && isset($arrIdiomas[$_GET['lang']])) {
        $locale = $_GET['lang'];
      }

      putenv('LANGUAGE=' . $locale);
      putenv('LANG=' . $locale);
      putenv('LC_ALL=' . $locale);
      putenv('LC_MESSAGES=' . $locale);
      setlocale(LC_ALL, $locale);
      setlocale(LC_CTYPE, $locale);
      bindtextdomain($textdomain, $locales_dir);
      bind_textdomain_codeset($textdomain, 'iso-8859-1');
      textdomain($textdomain);
    }
  }

  public static function montarCabecalhoConteudo($strIdentificacao, $strAcoes, $strLinkConteudo, &$strCss, &$strJsInicializar, &$strJsCorpo, &$strHtml, $bolAutoRedimensionar = true){

    $strCss = ' 
      body {margin:0;overflow:hidden}
      #divCabecalho {position:fixed; width:100%;height:48px;z-index:1900;border-bottom:3px solid #666;padding-top:2px;}
      #divIdentificacao label, #divIdentificacao a {font-size:20px;position:absolute;left:1%;top:20%;}
      #divIdentificacao a {text-decoration:none;}
      #divAcoes {float:right;padding:0 10px;}
      #divAcoes img{float:left;opacity:1;}
      #divAcoes img:hover{opacity:0.3;};
      #divConteudo {box-sizing: border-box;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;}
      #ifrConteudo {width:100%;border:0;top:56px;position:absolute;overflow:auto;}
      ';

    $strJsInicializar = '';

    if ($bolAutoRedimensionar){
      $strJsInicializar .= '  
        infraAdicionarEvento(window,\'resize\',redimensionar);
        ';

    }

    $strJsInicializar .= '  
        redimensionar();
        ';

    $strJsCorpo = '  
      function redimensionar() {
        setTimeout(function(){
          var tamCabecalho = document.getElementById(\'divCabecalho\').offsetHeight;
          var ifrConteudo = document.getElementById(\'ifrConteudo\');
          if (tamCabecalho > ifrConteudo.offsetHeight) tamCabecalho -= ifrConteudo.offsetHeight;
          var tamConteudo = infraClientHeight() - tamCabecalho;
          ifrConteudo.style.height = (tamConteudo > 0 ? tamConteudo : 1) + \'px\';
        },0);
      }'
    ;


    $strHtml = '<body onload="inicializar()">
      <div id="divCabecalho" style="background-color: #f4f4f4;">
  
        <div id="divIdentificacao">
          '.$strIdentificacao.'
        </div>
  
        <div id="divAcoes">
          '.$strAcoes.'
        </div>
  
      </div>
      
      <div id="divConteudo">
        <iframe id="ifrConteudo" src="'.$strLinkConteudo.'"></iframe>
      </div>
    </body>
    ';
  }

  public static function montarMensagemErroFederacao($arrObjDTO, $strMsgSingular, $strMsgPlural){
    $strRet = '';
    $arrSiglasErro = array();

    foreach($arrObjDTO as $objDTO){
      if ($objDTO->getObjInfraException()!=null){
        $arrSiglasErro[] = '<a alt="' . PaginaSEI::tratarHTML($objDTO->getStrDescricao()) . '" title="' . PaginaSEI::tratarHTML($objDTO->getStrDescricao()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($objDTO->getStrSigla()).'</a>';
      }
    }

    $numErros = count($arrSiglasErro);

    if ($numErros){
      if ($numErros == 1){
        $strRet = $strMsgSingular.': '.$arrSiglasErro[0].'.';
      }else{
        $strRet = $strMsgPlural.': ';
        for($i=0;$i<$numErros-1;$i++){
          if ($i){
            $strRet .= ', ';
          }
          $strRet .= $arrSiglasErro[$i];
        }
        $strRet .= ' e '.$arrSiglasErro[$numErros-1].'.';
      }
    }

    if ($strRet != ''){
      $strRet = '<div id="divErroFederacao" class="msgErroFederacao"><label>'.$strRet.'</label></div>';
    }

    return $strRet;
  }

  public static function formatarNomeSocial($strNome, $strNomeSocial){
    if ($strNomeSocial==null || $strNome == $strNomeSocial){
      return $strNome;
    }else{
      return $strNomeSocial.' registrado(a) civilmente como '.$strNome;
    }
  }

  public static function montarItemCelula($strTexto,$strTitulo){
    return '<div class="divItemCelula"><div class="divIconeItemCelula"><img src="'.Icone::TABELA_ITEM_CELULA.'" height="16" width="16" title="'.$strTitulo.'" />'.'</div><div class="divRotuloItemCelula">'.PaginaSEI::tratarHTML($strTexto).'</div></div>';
  }

}
?>