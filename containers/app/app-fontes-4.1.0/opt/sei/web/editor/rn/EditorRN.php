<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 02/12/2011 - criado por bcu
 * 01/06/2018 - cjy - adicao de variaveis observacao_documento e observacao_processo
 *
 * Versão no CVS: $Id: EditorRN.php 10339 2015-09-21 18:51:22Z bcu $
 */

require_once __DIR__ . '/../../SEI.php';

class EditorRN extends InfraRN
{
  public static $VERSAO_CK = '15092021E';

  public static $REGEXP_LINK_ASSINADO = "@<a[^>]*href=\"[^>]*controlador\\.php\\?acao=([^&]*)&(?:amp;)?.*?id_pro(?:tocolo|cedimento)=(\\d+)&.*?infra_sistema=(\\d+)&(?:amp;)?infra_unidade_atual=\\d+&(?:amp;)?infra_hash=[^\"]*\"[^>]*>(.*?)<\\/a>@i";
  public static $REGEXP_LINK_ASSINADO_SIMPLES = '@(<a[^>]*href=")([^"]*_sistema=\d+&(?:amp;)?infra_unidade_atual=\d+&(?:amp;)?infra_hash=[^"]*)"([^>]*>)([^<]*)</a>@i';
  public static $REGEXP_SPAN_LINKSEI = '@(?><span[^>]*>)?<a[^>]*id="lnkSei(\d+)[^>]*>([^<]+)<\/a>(?><\/span>)?@i';
  public static $REGEXP_ATRIB_VALOR = "%'[^']*':'[^']*'%";
  public static $REGEXP_VARIAVEL_EDITOR = '/md_([a-z0-9]+)_[a-z0-9]+(?>_[a-z0-9]+)*]*/';
  public static $REGEX_SPAN_SCAYT = '@(<span[^<>]*)(data-scayt-word="[^<>]*")([^<>]*>)([^<]*)</span>@i';
  public static $REGEX_SPAN_SCAYT_SELECTION ='@<span[^<>]*class="rangySelectionBoundary"[^<>]*>[^<]*</span>@i';
  public static $REGEXP_SPAN_LINKSEI_FEDERACAO = '@(?><span[^>]*>)?<a([^>]*data-fi="[0-9A-HJKLMNPQRSTVWXYZ]{26}"[^>]*)>([^<]+)<\/a>(?><\/span>)?@i';

  public static $VE_NENHUM=0;
  public static $VE_CK4=1;
  public static $VE_CK5=2;

  private static $arrTags;
  private $arrProtocolos;

  public function __construct()
  {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  protected function montarBasicoConectado(EditorDTO $objEditorDTO)
  {

    $ret = new EditorDTO();
    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retStrServidorCorretorOrtografico();
    $objOrgaoDTO->retStrStaCorretorOrtografico();
    $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());

    $objOrgaoRN = new OrgaoRN();
    $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

    $arrImagemPermitida = EditorINT::getArrImagensPermitidas();

    $includePlugins = array('simpleLink', 'notification', 'stylesheetparser', 'tableresize', 'tableclean', 'font');
    $removePlugins = array('resize', 'maximize', 'link', 'wsc', 'assinatura', 'save', 'autogrow');

    $scayt = "";
    if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_LICENCIADO) {
      try {
        $scayt = InfraUtil::isBolUrlValida($objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt3/ckscayt/ckscayt.js") ? "scayt3" : "scayt";
      } catch (Exception $e) {
        $scayt = "";
        LogSEI::getInstance()->gravar("Falha na conexão com o servidor de correção ortográfica:\n".InfraException::inspecionar($e));
      }
    }
    if ($scayt != "") {
      $includePlugins[] = $scayt;
    }

    $ie = PaginaSEI::getInstance()->isBolNavegadorIE();
    if ($ie) $ie = PaginaSEI::getInstance()->getNumVersaoInternetExplorer();
    if (InfraArray::contar($arrImagemPermitida) > 0 && !PaginaSEI::getInstance()->isBolNavegadorSafariIpad() && (!$ie || $ie > 7)) {
      $includePlugins[] = 'base64image';
    } else {
      $removePlugins[] = 'base64image';
    }
    $strInicializacao = '<script type="text/javascript" charset="utf-8" src="editor/ck/ckeditor.js?t='.self::$VERSAO_CK.'"></script>';
    $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_imagem_upload');
    $strLinkAjax = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=upload_buscar');

    $strUsuario = strtolower(SessaoSEI::getInstance()->getStrSiglaUsuario().'.'.SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario());

    $arrConfig = ConfiguracaoSEI::getInstance()->getArrConfiguracoes();
    $strRegexSistema = str_replace('.', '\.', $arrConfig['SEI']['URL']).'.*infra_hash=.*';
    $strRegexSistema = preg_replace("@http[s]?://@", '', $strRegexSistema);

    $strInicializacao .= '<style type="text/css" >';
    $strInicializacao .= "\n.cke_combo__styles .cke_combo_text {width:230px !important;}\n";
    $strInicializacao .= ".cke_button__save_label {display:inline !important;}\n";
    $strInicializacao .= ".cke_button__autotexto_label {display:inline !important;}\n";
    $strInicializacao .= ".cke_combopanel__styles {width:400px !important;}\n";
    $strInicializacao .= '</style>';
    $strInicializacao .= '<script type="text/javascript">';
    $strInicializacao .= "CKEDITOR.config.url_sei_re='".$strRegexSistema."';\n";
    $strInicializacao .= "CKEDITOR.config.removePlugins='".implode(',', $removePlugins)."';\n";
    $strInicializacao .= "CKEDITOR.config.extraPlugins='".implode(',', $includePlugins)."';\n";
    $strInicializacao .= "CKEDITOR.config.base64image_filetypes='".strtolower(implode('|', $arrImagemPermitida))."';\n";
    $strInicializacao .= "CKEDITOR.config.base64imageUploadUrl='".$strLinkAnexos."';\n";
    $strInicializacao .= "CKEDITOR.config.base64imageAjaxUrl='".$strLinkAjax."';\n";
    $strInicializacao .= "CKEDITOR.config.scayt_userDictionaryName = '".$strUsuario."';\n";
    $strInicializacao .= "CKEDITOR.config.wsc_userDictionaryName = '".$strUsuario."';\n";

    if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_LICENCIADO) {
      $strInicializacao .= "CKEDITOR.config.wsc_customLoaderScript = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/22/js/wsc_fck2plugin.js';\n";
      if ($scayt == "scayt") {
        $strInicializacao .= "CKEDITOR.config.scayt_srcUrl = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt/scayt.js?".self::$VERSAO_CK."';\n";
      } elseif ($scayt == "scayt3") {
        $strInicializacao .= "CKEDITOR.config.scayt_srcUrl = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt3/ckscayt/ckscayt.js?".self::$VERSAO_CK."';\n";
      }
    }
    $strInicializacao .= "CKEDITOR.config.height=";
    if ($objEditorDTO->isSetNumTamanhoEditor()) {
      $strInicializacao .= $objEditorDTO->getNumTamanhoEditor();
    } else {
      $strInicializacao .= "500";
    }
    $strInicializacao .= ";\n";

    if ($objEditorDTO->getStrSinSomenteLeitura() == 'S') {
      $strInicializacao .= "CKEDITOR.config.readOnly=true;\n";
    }
    //$ret->setStrCss($this->jsEncode($this->montarCssEditor(0)));
    $strInicializacao .= "</script>\n";
    $ret->setStrInicializacao($strInicializacao);

    $strEditor = "CKEDITOR.replace('".$objEditorDTO->getStrNomeCampo()."',{ 'toolbar':";

    $arrBotoes = array();
    if (SessaoSEI::getInstance()->verificarPermissao('editor_visualizar_codigo_fonte')) {
      $arrBotoes[] = array('Source');
    }

    $arrBotoes[] = array('Font', 'FontSize');
    $arrBotoes[]=array( 'Outdent', 'Indent','-','JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock');

    $arrBotoes[] = array('Bold', 'Italic', 'Underline', 'TextColor', 'BGColor', 'RemoveFormat');


    $temp = array('Cut', 'Copy', 'PasteFromWord', 'PasteText', '-', 'Undo', 'Redo');
    if ($scayt != '') $temp[] = 'SpellChecker';
    $temp[] = 'Scayt';
    $arrBotoes[] = $temp;

    $arrBotoes[] = array('NumberedList', 'BulletedList', '-',  'base64image');

    $strEditor .= $this->jsEncode($arrBotoes);

    if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_NATIVO_NAVEGADOR || ($objOrgaoDTO->getStrStaCorretorOrtografico() != OrgaoRN::$TCO_NENHUM && $objOrgaoDTO->getStrStaCorretorOrtografico() != OrgaoRN::$TCO_LICENCIADO)) {
      $strEditor .= ",disableNativeSpellChecker:false";
    }
    $strEditor .= ",on:{instanceReady:function(ev){this.focus();}}";
    $strEditor .= "});";
    $ret->setStrEditores($strEditor);

    return $ret;
  }

  protected function montarSimplesConectado(EditorDTO $objEditorDTO)
  {

    $ret = new EditorDTO();
    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retStrServidorCorretorOrtografico();
    $objOrgaoDTO->retStrStaCorretorOrtografico();
    $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());


    if ($objEditorDTO->isSetStrSinEstilos()) {
      $strEstilos = $objEditorDTO->getStrSinEstilos();
    } else {
      $strEstilos = 'S';
    }
    if ($objEditorDTO->isSetStrSinImagens()) {
      $bolImagens = ($objEditorDTO->getStrSinImagens() == 'S');
    } else {
      $bolImagens = true;
    }
    if ($objEditorDTO->isSetStrSinCodigoFonte()) {
      $strCodigoFonte = $objEditorDTO->getStrSinCodigoFonte();
    } else {
      $strCodigoFonte = '';
    }
    if ($objEditorDTO->isSetStrSinAutoTexto()) {
      $bolAutotexto = ($objEditorDTO->getStrSinAutoTexto() == 'S');
    } else {
      $bolAutotexto = false;
    }
    if ($objEditorDTO->isSetStrSinLinkSei()) {
      $bolLinkSei = ($objEditorDTO->getStrSinLinkSei() == 'S');
    } else {
      $bolLinkSei = false;
    }
    $objOrgaoRN = new OrgaoRN();
    $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

    $arrImagemPermitida = EditorINT::getArrImagensPermitidas();

    $includePlugins = array('simpleLink', 'notification', 'extenso', 'maiuscula', 'stylesheetparser', 'tableresize', 'tableclean', 'symbol');
    if ($bolAutotexto) $includePlugins[] = 'autotexto';
    $removePlugins = array('resize', 'maximize', 'link', 'wsc', 'assinatura', 'save', 'autogrow');

    if ($bolLinkSei) {
      $includePlugins[] = 'linksei';
    } else {
      $removePlugins[] = 'linksei';
    }

    $scayt = "";
    if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_LICENCIADO) {
      try {
        $scayt = InfraUtil::isBolUrlValida($objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt3/ckscayt/ckscayt.js") ? "scayt3" : "scayt";
      } catch (Exception $e) {
        $scayt = "";
        LogSEI::getInstance()->gravar("Falha na conexão com o servidor de correção ortográfica:\n".InfraException::inspecionar($e));
      }
    }
    if ($scayt != "") {
      $includePlugins[] = $scayt;
    }

    $ie = PaginaSEI::getInstance()->isBolNavegadorIE();
    if ($ie) $ie = PaginaSEI::getInstance()->getNumVersaoInternetExplorer();
    if ($bolImagens && InfraArray::contar($arrImagemPermitida) > 0 && !PaginaSEI::getInstance()->isBolNavegadorSafariIpad() && (!$ie || $ie > 7)) {
      $includePlugins[] = 'base64image';
    } else {
      $removePlugins[] = 'base64image';
    }
    $strInicializacao = '<script type="text/javascript" charset="utf-8" src="editor/ck/ckeditor.js?t='.self::$VERSAO_CK.'"></script>';
    $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_imagem_upload');
    $strLinkAjax = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=upload_buscar');

    $strUsuario = strtolower(SessaoSEI::getInstance()->getStrSiglaUsuario().'.'.SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario());

    $arrConfig = ConfiguracaoSEI::getInstance()->getArrConfiguracoes();
    $strRegexSistema = str_replace('.', '\.', $arrConfig['SEI']['URL']).'.*infra_hash=.*';
    $strRegexSistema = preg_replace("@http[s]?://@", '', $strRegexSistema);

    $strInicializacao .= '<style type="text/css" >';
    $strInicializacao .= "\n.cke_combo__styles .cke_combo_text {width:230px !important;}\n";
    $strInicializacao .= ".cke_button__save_label {display:inline !important;}\n";
    $strInicializacao .= ".cke_button__autotexto_label {display:inline !important;}\n";
    $strInicializacao .= ".cke_combopanel__styles {width:400px !important;}\n";
    $strInicializacao .= '</style>';
    $strInicializacao .= '<script type="text/javascript">';
    $strInicializacao .= "CKEDITOR.config.url_sei_re='".$strRegexSistema."';\n";
    $strInicializacao .= "CKEDITOR.config.removePlugins='".implode(',', $removePlugins)."';\n";
    $strInicializacao .= "CKEDITOR.config.extraPlugins='".implode(',', $includePlugins)."';\n";
    $strInicializacao .= "CKEDITOR.config.base64image_filetypes='".strtolower(implode('|', $arrImagemPermitida))."';\n";
    $strInicializacao .= "CKEDITOR.config.base64imageUploadUrl='".$strLinkAnexos."';\n";
    $strInicializacao .= "CKEDITOR.config.base64imageAjaxUrl='".$strLinkAjax."';\n";
    $strInicializacao .= "CKEDITOR.config.scayt_userDictionaryName = '".$strUsuario."';\n";
    $strInicializacao .= "CKEDITOR.config.wsc_userDictionaryName = '".$strUsuario."';\n";

    if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_LICENCIADO) {
      $strInicializacao .= "CKEDITOR.config.wsc_customLoaderScript = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/22/js/wsc_fck2plugin.js';\n";
      if ($scayt == "scayt") {
        $strInicializacao .= "CKEDITOR.config.scayt_srcUrl = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt/scayt.js?".self::$VERSAO_CK."';\n";
      } elseif ($scayt == "scayt3") {
        $strInicializacao .= "CKEDITOR.config.scayt_srcUrl = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt3/ckscayt/ckscayt.js?".self::$VERSAO_CK."';\n";
      }
    }
    $strInicializacao .= "CKEDITOR.config.height=";
    if ($objEditorDTO->isSetNumTamanhoEditor()) {
      $strInicializacao .= $objEditorDTO->getNumTamanhoEditor();
    } else {
      $strInicializacao .= "500";
    }
    $strInicializacao .= ";\n";

    if ($objEditorDTO->getStrSinSomenteLeitura() == 'S') {
      $strInicializacao .= "CKEDITOR.config.readOnly=true;\n";
    }
    $ret->setStrCss($this->jsEncode($this->montarCssEditor(0)));
    if ($strEstilos == 'S') {
      $strInicializacao .= "CKEDITOR.config.contentsCss=".$ret->getStrCss().";\n";
    }
    $strInicializacao .= "</script>\n";
    $ret->setStrInicializacao($strInicializacao);

    $strEditor = "CKEDITOR.replace('".$objEditorDTO->getStrNomeCampo()."',{ 'toolbar':";
    $strEditor .= $this->jsEncode($this->montarBarraFerramentas($bolAutotexto, false, ($scayt != ""), $strCodigoFonte, $strEstilos));

    if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_NATIVO_NAVEGADOR || ($objOrgaoDTO->getStrStaCorretorOrtografico() != OrgaoRN::$TCO_NENHUM && $objOrgaoDTO->getStrStaCorretorOrtografico() != OrgaoRN::$TCO_LICENCIADO)) {
      $strEditor .= ",disableNativeSpellChecker:false";
    }
    $strEditor .= "});";
    $ret->setStrEditores($strEditor);


    return $ret;
  }

  private function montarBarraFerramentas($bolAdicionarTextoPadrao, $bolBtnWSC, $bolBtnScayt, $strBtnSource = '', $strEstilos = '')
  {

    $arrGrupoEstilos = array();

    if ($bolAdicionarTextoPadrao) {
      $arrGrupoEstilos[] = 'autotexto';
    }
    if ($strEstilos == '' || $strEstilos == 'S') {
      $arrGrupoEstilos[] = 'Styles';
    }

    $arrRetorno = array();
    if ($strBtnSource != 'N' && ($strBtnSource == 'S' || SessaoSEI::getInstance()->verificarPermissao('editor_visualizar_codigo_fonte'))) {
      $arrRetorno[] = ["name" => "Código Fonte", "items" => ['Source']];
    }

    $arrRetorno[] = ["name" => "Salvar", "items" => array('Save')];
    if (SessaoSEI::getInstance()->verificarPermissao('documento_assinar')) {
      $arrRetorno[] = ["name" => "Assinatura", "items" => array('assinatura')];
    }

    $arrRetorno[] = ["name" => "Formatação Básica", "items" => array('Find', 'Replace', '-', 'RemoveFormat', 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'Maiuscula', 'Minuscula', 'TextColor', 'BGColor' /*,'PageBreak'*/)];


    $temp = array('Cut', 'Copy', 'CopyFormatting', 'PasteFromWord', 'PasteText', '-', 'Undo', 'Redo', 'ShowBlocks', 'Symbol');
    if ($bolBtnWSC) $temp[] = 'SpellChecker';
    if ($bolBtnScayt) $temp[] = 'Scayt';
    $arrRetorno[] = ["name" => "Área de Transferência", "items" => $temp];

    $arrRetorno[] = ["name" => "Listas e Identação", "items" => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'base64image')];
    $arrRetorno[] = ["name" => "Outros", "items" => array('Table', 'SpecialChar', 'SimpleLink', 'linksei', 'Extenso', 'Zoom')];
    $arrRetorno[] = ["name" => "Estilos", "items" => $arrGrupoEstilos];

    return $arrRetorno;
  }

  protected function montarControlado(EditorDTO $parObjEditorDTO)
  {
    try {

      //gerar nova versao igual a anterior
      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoDTO->retNumIdSecaoModeloSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSinAssinaturaSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSinSomenteLeituraSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSinPrincipalSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSinDinamicaSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSinCabecalhoSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSinRodapeSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrConteudo();
      $objVersaoSecaoDocumentoDTO->retStrNomeSecaoModelo();
      $objVersaoSecaoDocumentoDTO->retNumVersao();
      $objVersaoSecaoDocumentoDTO->setDblIdDocumentoSecaoDocumento($parObjEditorDTO->getDblIdDocumento());
      $objVersaoSecaoDocumentoDTO->setNumIdBaseConhecimentoSecaoDocumento($parObjEditorDTO->getNumIdBaseConhecimento());
      $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');
      $objVersaoSecaoDocumentoDTO->setOrdNumOrdemSecaoDocumento(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
      $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

      $arrObjSecaoDocumentoDTO = array();
      $bolDinamica = false;
      $numVersaoAnterior = 0;
      foreach ($arrObjVersaoSecaoDocumentoDTO as $objVersaoSecaoDocumentoDTO2) {

        if ($numVersaoAnterior < $objVersaoSecaoDocumentoDTO2->getNumVersao()){
          $numVersaoAnterior = $objVersaoSecaoDocumentoDTO2->getNumVersao();
        }

        if ($objVersaoSecaoDocumentoDTO2->getStrSinAssinaturaSecaoDocumento() == 'N') {
          $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
          $objSecaoDocumentoDTO->setNumIdSecaoModelo($objVersaoSecaoDocumentoDTO2->getNumIdSecaoModeloSecaoDocumento());
          $objSecaoDocumentoDTO->setStrConteudo($objVersaoSecaoDocumentoDTO2->getStrConteudo());
          $arrObjSecaoDocumentoDTO[] = $objSecaoDocumentoDTO;
        }

        if ($objVersaoSecaoDocumentoDTO2->getStrSinDinamicaSecaoDocumento() == 'N') {
          $bolDinamica = true;
        }
      }

      $bolValidacao = false;
      $strValidacoes = '';
      try {

        $parObjEditorDTO->setArrObjSecaoDocumentoDTO($arrObjSecaoDocumentoDTO);
        $numVersao = $this->adicionarVersao($parObjEditorDTO);

      } catch (InfraException $e) {
        if ($e->contemValidacoes()) {
          $ret = new EditorDTO();
          $ret->setNumVersao(null);
          $ret->setStrSinAlterouVersao('N');
          $ret->setStrToolbar('[]');
          $ret->setStrTextareas(null);
          $ret->setStrCss(null);
          $ret->setStrInicializacao(null);
          $ret->setStrEditores(null);
          $ret->setStrValidacao(true);
          $ret->setStrMensagens($e->__toString());
          return $ret;
        } else {
          throw $e;
        }
      }

      //se possui secoes dinamicas entao lista novamente para exibir o conteudo atualizado na ultima versao
      if ($bolDinamica) {
        $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
        $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);
      }

      if (!$parObjEditorDTO->isSetNumIdConjuntoEstilos()) {
        $objConjuntoEstilosDTO = new ConjuntoEstilosDTO();
        $objConjuntoEstilosRN = new ConjuntoEstilosRN();
        $objConjuntoEstilosDTO->setStrSinUltimo('S');
        $objConjuntoEstilosDTO->retNumIdConjuntoEstilos();
        $objConjuntoEstilosDTO = $objConjuntoEstilosRN->consultar($objConjuntoEstilosDTO);
        $parObjEditorDTO->setNumIdConjuntoEstilos($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());
      }
      $strConteudoCss = $this->montarCssEditor($parObjEditorDTO->getNumIdConjuntoEstilos());
      $strEditores = '';
      $strTextareas = '';

      //busca os estilos permitidos por seção-modelo
      $objRelSecaoModCjEstilosItemDTO = new RelSecaoModCjEstilosItemDTO();
      $objRelSecaoModCjEstilosItemDTO->retNumIdSecaoModelo();
      $objRelSecaoModCjEstilosItemDTO->retStrNomeEstilo();
      $objRelSecaoModCjEstilosItemDTO->retStrFormatacao();
      $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo(InfraArray::converterArrInfraDTO($arrObjVersaoSecaoDocumentoDTO, 'IdSecaoModeloSecaoDocumento'), InfraDTO::$OPER_IN);
      $objRelSecaoModCjEstilosItemDTO->setOrdStrNomeEstilo(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
      $objRelSecaoModCjEstilosItemRN = new RelSecaoModCjEstilosItemRN();
      $arrObjRelSecaoModCjEstilosItemDTO = InfraArray::indexarArrInfraDTO($objRelSecaoModCjEstilosItemRN->listar($objRelSecaoModCjEstilosItemDTO), 'IdSecaoModelo', true);

      $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_upload_anexo');

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retStrServidorCorretorOrtografico();
      $objOrgaoDTO->retStrStaCorretorOrtografico();
      $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      $numTamanhoTotalEditavel=0;
      foreach ($arrObjVersaoSecaoDocumentoDTO as $objVersaoSecaoDocumentoDTO) {
        if ($objVersaoSecaoDocumentoDTO->getStrSinAssinaturaSecaoDocumento() == 'N') {

          $strFormatos = "";
          if (isset($arrObjRelSecaoModCjEstilosItemDTO[$objVersaoSecaoDocumentoDTO->getNumIdSecaoModeloSecaoDocumento()])) {
            foreach ($arrObjRelSecaoModCjEstilosItemDTO[$objVersaoSecaoDocumentoDTO->getNumIdSecaoModeloSecaoDocumento()] as $objRelSecaoModCjEstilosItemDTO) {
              $strFormatos .= $objRelSecaoModCjEstilosItemDTO->getStrNomeEstilo()."|";
            }
          }
          $strFormatos = rtrim($strFormatos, '|');

          $strTextareas .= '<textarea name="txaEditor_'.$objVersaoSecaoDocumentoDTO->getNumIdSecaoModeloSecaoDocumento().'" style="display:none;">';
          $strConteudoTxa=PaginaSEI::tratarHTML($this->filtrarTags($objVersaoSecaoDocumentoDTO->getStrConteudo()));
          $strTextareas .= $strConteudoTxa;
          $strTextareas .= '</textarea>';


          $strEditores .= "CKEDITOR.replace('txaEditor_".$objVersaoSecaoDocumentoDTO->getNumIdSecaoModeloSecaoDocumento()."',";
          $strEditores .= '{filebrowserUploadUrl:"'.$strLinkAnexos.'","toolbar":toolbar,"stylesheetParser_validSelectors":/^(p)\.(';
          $strEditores .= $strFormatos.')$/i,';

          if ($objVersaoSecaoDocumentoDTO->getStrSinDinamicaSecaoDocumento() === 'S') {
            $strEditores .= '"dinamico":true,';
          }

            $strEditores.='title:"'.$objVersaoSecaoDocumentoDTO->getStrNomeSecaoModelo().'",';
          if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_NATIVO_NAVEGADOR || ($objOrgaoDTO->getStrStaCorretorOrtografico() != OrgaoRN::$TCO_NENHUM && $objOrgaoDTO->getStrStaCorretorOrtografico() != OrgaoRN::$TCO_LICENCIADO)) {
            $strEditores .= "disableNativeSpellChecker:false,";
          }

          $strEditores .= '"readOnly":';

          if ($objVersaoSecaoDocumentoDTO->getStrSinSomenteLeituraSecaoDocumento() === 'S' || $bolValidacao) {
            $strEditores .= 'true});'."\n";
          } else {
            $numTamanhoTotalEditavel+=strlen($strConteudoTxa);
            $strEditores .= 'false,autoGrow_bottomSpace:0});'."\n";
          }
        }
      }


      $arrImagemPermitida = EditorINT::getArrImagensPermitidas();


      $includePlugins = array('autogrow', 'notification', 'linksei', 'sharedspace', 'autotexto', 'simpleLink', 'extenso', 'maiuscula', 'stylesheetparser', 'stylesdefault', 'tableresize', 'symbol', 'tableclean', 'widget','autocomplete','textmatch','tags');
      $removePlugins = array('resize', 'maximize', 'link', 'wsc');

      if (SessaoSEI::getInstance()->verificarPermissao('documento_assinar') && $parObjEditorDTO->getNumIdBaseConhecimento() == null) {
        $includePlugins[] = 'assinatura';
      } else {
        $removePlugins[] = 'assinatura';
      }

      $ie = PaginaSEI::getInstance()->isBolNavegadorIE();
      if ($ie) {
        $ie = PaginaSEI::getInstance()->getNumVersaoInternetExplorer();
      }
      if (InfraArray::contar($arrImagemPermitida) > 0 && !PaginaSEI::getInstance()->isBolNavegadorSafariIpad() && (!$ie || $ie > 7)) {
        $includePlugins[] = 'base64image';
      } else {
        $removePlugins[] = 'base64image';
      }

      $scayt = "";
      if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_LICENCIADO) {
        try {
          $scayt = InfraUtil::isBolUrlValida($objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt3/ckscayt/ckscayt.js") ? "scayt3" : "scayt";
        } catch (Exception $e) {
          $scayt = "";
          LogSEI::getInstance()->gravar("Falha na conexão com o servidor de correção ortográfica:\n".InfraException::inspecionar($e));
        }
      }
      $bolDesabilitarCorretor=false;
      if ($scayt != "") {
        $includePlugins[] = $scayt;
        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $numTamanhoDesabilitarEditor=$objInfraParametro->getValor('SEI_TAM_MB_CORRETOR_DESABILITADO', false);
        if($numTamanhoDesabilitarEditor===null){
          $numTamanhoDesabilitarEditor=2;
        }
        $numTamanhoDesabilitarEditor*=1048576;
        if($numTamanhoTotalEditavel>=$numTamanhoDesabilitarEditor){
          $bolDesabilitarCorretor=true;
          $objProtocoloDTO=new ProtocoloDTO();
          $objProtocoloRN=new ProtocoloRN();
          $objProtocoloDTO->setDblIdProtocolo($parObjEditorDTO->getDblIdDocumento());
          $objProtocoloDTO->retStrProtocoloFormatado();
          $objProtocoloDTO=$objProtocoloRN->consultarRN0186($objProtocoloDTO);
          LogSEI::getInstance()->gravar("Corretor desabilitado. \n".$objProtocoloDTO->getStrProtocoloFormatado().' '.SessaoSEI::getInstance()->getStrSiglaUsuario().'/'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()."\nTamanho: $numTamanhoTotalEditavel bytes");
        }
      }
      //desabilita zoom devido a bug do firefox <16
      if (!PaginaSEI::getInstance()->isBolNavegadorFirefox() || PaginaSEI::getInstance()->getNumVersaoFirefox() >= 16) {
        $includePlugins[] = 'zoom';
      }

      $jsonTags='[';
      $strConcat='';
      $arrTags=$this->obterListaTags();
      ksort($arrTags);
      unset($arrTags['link_acesso_externo_processo']);
      foreach ($arrTags as $chave=>$tag) {
        $jsonTags.=$strConcat."{'name':'$tag','id':'$chave'}";
        $strConcat=',';
      }
      $jsonTags.=']';



      $strUsuarioDicionario = strtolower(SessaoSEI::getInstance()->getStrSiglaUsuario().'.'.SessaoSEI::getInstance()->getStrSiglaOrgaoUsuario());
      $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_imagem_upload');
      $strLinkAjax = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=upload_buscar');
      $strLinkAjaxTags = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=processar_tag&id_documento='.$parObjEditorDTO->getDblIdDocumento());
      $arrConfig = ConfiguracaoSEI::getInstance()->getArrConfiguracoes();
      $strRegexSistema = str_replace('.', '\.', $arrConfig['SEI']['URL']).'.*infra_hash=.*';
      $strRegexSistema = preg_replace("@http[s]?://@", '', $strRegexSistema);

      $strInicializacao = '<script type="text/javascript" charset="utf-8" src="editor/ck/ckeditor.js?t='.self::$VERSAO_CK.'"></script>';//prod
//      $strInicializacao = '<script type="text/javascript" charset="utf-8" src="http://infra-php-desenv.trf4.jus.br/bcu/ck/ckeditor.js?t=' . self::$VERSAO_CK . '"></script>';//dev-source
//      $strInicializacao = '<script type="text/javascript" charset="utf-8" src="http://infra-php-desenv.trf4.jus.br/bcu/ck/dev/builder/release/ckeditor/ckeditor.js?t=' . self::$VERSAO_CK . '"></script>';//dev-compilado
      $strInicializacao .= '<script type="text/javascript">';
      $strInicializacao .= "CKEDITOR.config.url_sei_re='".$strRegexSistema."';\n";
      $strInicializacao .= "CKEDITOR.config.removePlugins='".implode(',', $removePlugins)."';\n";
      $strInicializacao .= "CKEDITOR.config.extraPlugins='".implode(',', $includePlugins)."';\n";
      $strInicializacao .= "CKEDITOR.config.base64image_filetypes='".implode('|', $arrImagemPermitida)."';\n";
      $strInicializacao .= "CKEDITOR.config.base64imageUploadUrl='".$strLinkAnexos."';\n";
      $strInicializacao .= "CKEDITOR.config.base64imageAjaxUrl='".$strLinkAjax."';\n";
      $strInicializacao .= "CKEDITOR.config.height=100;\n";
      $strInicializacao .= "CKEDITOR.config.scayt_userDictionaryName = '".$strUsuarioDicionario."';\n";
      $strInicializacao .= "CKEDITOR.config.wsc_userDictionaryName = '".$strUsuarioDicionario."';\n";
      $strInicializacao .= "CKEDITOR.config.readOnly=true;\n";
      $strInicializacao .= "CKEDITOR.config.tags=$jsonTags;\n";
      $strInicializacao .= "CKEDITOR.config.tag_url='$strLinkAjaxTags';\n";
//      $strInicializacao .= "CKEDITOR.config.pasteFromWordRemoveFontStyles=true;\n";
      $strInicializacao .= "CKEDITOR.config.pasteFromWord_keepZeroMargins=true;\n";
      if($bolDesabilitarCorretor){
        $strInicializacao .= "CKEDITOR.config.scayt_autoStartup=false;\n";
        $strInicializacao .= "alert('Devido ao tamanho deste documento a correção ortográfica foi desabilitada.');\n";
      } else {
        $strInicializacao .= "CKEDITOR.config.scayt_autoStartup=true;\n";
      }

      if ($objOrgaoDTO->getStrStaCorretorOrtografico() == OrgaoRN::$TCO_LICENCIADO) {
        $strInicializacao .= "CKEDITOR.config.wsc_customLoaderScript = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/22/js/wsc_fck2plugin.js';\n";
        if ($scayt === "scayt") {
          $strInicializacao .= "CKEDITOR.config.scayt_srcUrl = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt/scayt.js?".self::$VERSAO_CK."';\n";
        } elseif ($scayt === "scayt3") {
          $strInicializacao .= "CKEDITOR.config.scayt_srcUrl = '".$objOrgaoDTO->getStrServidorCorretorOrtografico()."/spellcheck/lf/scayt3/ckscayt/ckscayt.js?".self::$VERSAO_CK."';\n";
        }
      }

      $strInicializacao .= "</script>\n";

      $ret = new EditorDTO();

      $ret->setNumVersao($numVersao);
      $ret->setStrSinAlterouVersao(($numVersao!=$numVersaoAnterior)?'S':'N');
      $ret->setStrToolbar($this->jsEncode($this->montarBarraFerramentas(true, false, ($scayt != ""))));
      $ret->setStrTextareas($strTextareas);
      $ret->setStrCss($strConteudoCss);
      $ret->setStrInicializacao($strInicializacao);
      $ret->setStrEditores($strEditores);
      $ret->setStrValidacao($bolValidacao);
      $ret->setStrMensagens($strValidacoes);

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro montando editor.', $e);
    }
  }

  protected function montarTesteModeloControlado(EditorDTO $parObjEditorDTO)
  {
    $strTextareas='';
    $strEditores = '';

    try {
      $objSecaoModeloDTO=new SecaoModeloDTO();
      $objSecaoModeloRN=new SecaoModeloRN();

      $objSecaoModeloDTO->setNumIdModelo($parObjEditorDTO->getNumIdModelo());
      $objSecaoModeloDTO->retTodos();
      $arrObjSecaoModeloDTO=$objSecaoModeloRN->listar($objSecaoModeloDTO);

      if(count($arrObjSecaoModeloDTO)===0){
        throw new InfraException('Modelo de documento não possui seções.');
      }

      $strConteudoCss = $this->montarCssEditor(null);


      //busca os estilos permitidos por seção-modelo
      $objRelSecaoModCjEstilosItemDTO = new RelSecaoModCjEstilosItemDTO();
      $objRelSecaoModCjEstilosItemDTO->retNumIdSecaoModelo();
      $objRelSecaoModCjEstilosItemDTO->retStrNomeEstilo();
      $objRelSecaoModCjEstilosItemDTO->retStrFormatacao();
      $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo(InfraArray::converterArrInfraDTO($arrObjSecaoModeloDTO, 'IdSecaoModelo'), InfraDTO::$OPER_IN);
      $objRelSecaoModCjEstilosItemDTO->setOrdStrNomeEstilo(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objRelSecaoModCjEstilosItemDTO->retStrSinPadrao();
      $objRelSecaoModCjEstilosItemDTO->setStrSinUltimoConjuntoEstilos('S');
      $objRelSecaoModCjEstilosItemRN = new RelSecaoModCjEstilosItemRN();
      $arrObjRelSecaoModCjEstilosItemDTO = InfraArray::indexarArrInfraDTO($objRelSecaoModCjEstilosItemRN->listar($objRelSecaoModCjEstilosItemDTO), 'IdSecaoModelo', true);

      $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_upload_anexo');


      foreach ($arrObjSecaoModeloDTO as $objSecaoModeloDTO) {
        $numIdSecaoModelo = $objSecaoModeloDTO->getNumIdSecaoModelo();
        if ($objSecaoModeloDTO->getStrSinAssinatura()=='N') {

          $strFormatos = '';
          $strEstiloPadrao = '';
          if (isset($arrObjRelSecaoModCjEstilosItemDTO[$numIdSecaoModelo])) {
            foreach ($arrObjRelSecaoModCjEstilosItemDTO[$numIdSecaoModelo] as $objRelSecaoModCjEstilosItemDTO) {
              $strFormatos .= $objRelSecaoModCjEstilosItemDTO->getStrNomeEstilo() . '|';
              if($objRelSecaoModCjEstilosItemDTO->getStrSinPadrao()=='S'){
                $strEstiloPadrao = 'class="'.$objRelSecaoModCjEstilosItemDTO->getStrNomeEstilo().'"';
              }
            }
          }
          $strFormatos = rtrim($strFormatos, '|');

          $strConteudo = PaginaSEI::tratarHTML($this->filtrarTags($objSecaoModeloDTO->getStrConteudo()));
          $strConteudo = $this->substituirTagsInterno($parObjEditorDTO, $strConteudo,true);

          if ($objSecaoModeloDTO->getStrSinHtml() == 'N') {
            $arrConteudo = explode("\n", $strConteudo);
            $strConteudo='';
            foreach ($arrConteudo as $strItemConteudo) {
              $strConteudo .= '<p '.$strEstiloPadrao.'>'.$strItemConteudo.'</p>'."\r\n";
            }
          }
          $strTextareas .= '<textarea name="txaEditor_' . $numIdSecaoModelo . '" style="display:none;">';
          $strTextareas .= $strConteudo;
          $strTextareas .= '</textarea>';

          $strEditores .= "CKEDITOR.replace('txaEditor_" . $numIdSecaoModelo . "',";
          $strEditores .= '{filebrowserUploadUrl:"' . $strLinkAnexos . '","toolbar":toolbar,"stylesheetParser_validSelectors":/^(p)\.(';
          $strEditores .= $strFormatos . ')$/i,';

          if ($objSecaoModeloDTO->getStrSinDinamica()==='S') {
            $strEditores .= '"dinamico":true,';
          }

          $strEditores .= 'disableNativeSpellChecker:false,';
          $strEditores .= '"readOnly":';

          if ($objSecaoModeloDTO->getStrSinSomenteLeitura()==='S') {
            $strEditores .= 'true});' . "\n";
          } else {
            $strEditores .= 'false,autoGrow_bottomSpace:0});' . "\n";
          }
        }
      }

      $includePlugins = array('autogrow', 'notification', 'linksei', 'sharedspace', 'autotexto', 'simpleLink', 'extenso', 'maiuscula', 'stylesheetparser', 'stylesdefault', 'tableresize', 'symbol', 'tableclean', 'widget');
      $removePlugins = array('resize', 'maximize', 'link', 'wsc','assinatura','base64image','save');

      if (!PaginaSEI::getInstance()->isBolNavegadorFirefox() || PaginaSEI::getInstance()->getNumVersaoFirefox() >= 16) {
        $includePlugins[] = 'zoom';
      }

      $arrConfig = ConfiguracaoSEI::getInstance()->getArrConfiguracoes();
      $strRegexSistema = str_replace('.', '\.', $arrConfig['SEI']['URL']).'.*infra_hash=.*';
      $strRegexSistema = preg_replace('@http[s]?://@', '', $strRegexSistema);

      $strInicializacao = '<script type="text/javascript" charset="utf-8" src="editor/ck/ckeditor.js?t='.self::$VERSAO_CK.'"></script>';//prod
      $strInicializacao .= '<script type="text/javascript">';
      $strInicializacao .= "CKEDITOR.config.url_sei_re='".$strRegexSistema."';\n";
      $strInicializacao .= "CKEDITOR.config.removePlugins='".implode(',', $removePlugins)."';\n";
      $strInicializacao .= "CKEDITOR.config.extraPlugins='".implode(',', $includePlugins)."';\n";
      $strInicializacao .= "CKEDITOR.config.height=100;\n";
      $strInicializacao .= "CKEDITOR.config.readOnly=true;\n";
//      $strInicializacao .= "CKEDITOR.config.pasteFromWordRemoveFontStyles=true;\n";
      $strInicializacao .= "CKEDITOR.config.pasteFromWord_keepZeroMargins=true;\n";

      $strInicializacao .= "</script>\n";

      $ret = new EditorDTO();

      $ret->setNumVersao(0);
      $ret->setStrSinAlterouVersao('N');
      $ret->setStrToolbar($this->jsEncode($this->montarBarraFerramentas(true, false,false)));
      $ret->setStrTextareas($strTextareas);
      $ret->setStrCss($strConteudoCss);
      $ret->setStrInicializacao($strInicializacao);
      $ret->setStrEditores($strEditores);
      $ret->setStrValidacao(null);
      $ret->setStrMensagens(null);

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro montando editor.', $e);
    }
  }
  public function jsEncode($val)
  {
    if (null===$val) {
      return 'null';
    }
    if (is_bool($val)) {
      return $val ? 'true' : 'false';
    }
    if (is_int($val)) {
      return $val;
    }
    if (is_float($val)) {
      return str_replace(',', '.', $val);
    }
    if (is_array($val) && (array_keys($val) === range(0, InfraArray::contar($val) - 1))) {
      return '['.implode(',', array_map(array($this, 'jsEncode'), $val)).']';
    }
    if (is_array($val) || is_object($val)) {
      $temp = array();
      foreach ($val as $k => $v) {
        $temp[] = $this->jsEncode((string)$k).':'.$this->jsEncode($v);
      }
      return '{'.implode(',', $temp).'}';
    }
    // String otherwise
    /** @var string $val */
    if (strpos($val, '@@') === 0) {
      return substr($val, 2);
    }
    if (0===stripos($val, 'CKEDITOR.')) {
      return $val;
    }

    return '"'.str_replace(array("\\", '/', "\n", "\t", "\r", "\x08", "\x0c", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'), $val).'"';
  }

  protected function montarCssEditorConectado($numIdConjuntoEstilos)
  {
    $objConjuntoEstilosDTO = new ConjuntoEstilosDTO();
    $objConjuntoEstilosRN = new ConjuntoEstilosRN();
    $objConjuntoEstilosItemDTO = new ConjuntoEstilosItemDTO();
    $objConjuntoEstilosItemRN = new ConjuntoEstilosItemRN();
    if ($numIdConjuntoEstilos == 0 || $numIdConjuntoEstilos == null) {
      $objConjuntoEstilosDTO->setStrSinUltimo('S');
      $objConjuntoEstilosDTO->retNumIdConjuntoEstilos();
      $objConjuntoEstilosDTO = $objConjuntoEstilosRN->consultar($objConjuntoEstilosDTO);
      $objConjuntoEstilosItemDTO->setNumIdConjuntoEstilos($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());
    } else {
      $objConjuntoEstilosItemDTO->setNumIdConjuntoEstilos($numIdConjuntoEstilos);
    }
    $objConjuntoEstilosItemDTO->retTodos();
    $objConjuntoEstilosItemDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    $arrObjConjuntoEstilosItemDTO = $objConjuntoEstilosItemRN->listar($objConjuntoEstilosItemDTO);

    $strCssEditor = '';
    //converte estilos do formato antigo para css
    foreach ($arrObjConjuntoEstilosItemDTO as $objConjuntoEstilosItemDTO) {
      $strCssEditor .= 'p.' .$objConjuntoEstilosItemDTO->getStrNome(). ' {';
      $strFormatacao = $objConjuntoEstilosItemDTO->getStrFormatacao();
      preg_match_all(self::$REGEXP_ATRIB_VALOR, $strFormatacao, $arrStrConteudo);
      foreach ($arrStrConteudo[0] as $value) {
        $value = str_replace("'", '', $value);
        $strCssEditor .= $value. ';';
      }
      $strCssEditor .= '} ';
    }

    return $strCssEditor;
  }

  public function filtrarTags($strConteudo)
  {
    $strConteudo = preg_replace("%<font[^>]*>%si", "", $strConteudo);
    //$strConteudo = preg_replace("%<span style=\"[^(color|backgroung)][^>]*>%si", "", $strConteudo);
    $strConteudo = preg_replace("%</font>%si", "", $strConteudo);
    //$strConteudo = preg_replace("%</span>%si", "", $strConteudo);
    return str_replace(array('<o:p>', '</o:p>'), '', $strConteudo);
  }

  protected function gerarVersaoInicialControlado(EditorDTO $parObjEditorDTO)
  {
    try {

      //$objParametrosEditorDTO = $this->obterParametros($parObjEditorDTO);

      self::$arrTags = array();
      self::$arrTags['versao'] = 1;

      $objSecaoModeloRN = new SecaoModeloRN();
      $objSecaoDocumentoRN = new SecaoDocumentoRN();
      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
      //$objRelSecaoModeloEstiloRN = new RelSecaoModeloEstiloRN();
      $objDocumentoRN = new DocumentoRN();
      //$objSerieRN = new SerieRN();
      $dthAtual = InfraData::getStrDataHoraAtual();

      $parObjEditorDTO->setNumIdConjuntoEstilos(null);

      if ($parObjEditorDTO->isSetDblIdDocumentoBase() || $parObjEditorDTO->isSetDblIdDocumentoTextoBase()) {
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retNumIdConjuntoEstilos();
        if ($parObjEditorDTO->isSetDblIdDocumentoBase()) {
          $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumentoBase());
        } else {
          $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumentoTextoBase());
        }
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);
        $parObjEditorDTO->setNumIdConjuntoEstilos($objDocumentoDTO->getNumIdConjuntoEstilos());

      } else if ($parObjEditorDTO->isSetNumIdBaseConhecimentoBase()) {
        $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
        $objBaseConhecimentoDTO->retNumIdConjuntoEstilos();
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimentoBase());
        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);
        $parObjEditorDTO->setNumIdConjuntoEstilos($objBaseConhecimentoDTO->getNumIdConjuntoEstilos());
      } else if ($parObjEditorDTO->isSetNumIdTextoPadraoInterno()) {
        $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
        $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
        $objTextoPadraoInternoDTO->retNumIdConjuntoEstilos();
        $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($parObjEditorDTO->getNumIdTextoPadraoInterno());
        $objTextoPadraoInternoDTO = $objTextoPadraoInternoRN->consultar($objTextoPadraoInternoDTO);
        $parObjEditorDTO->setNumIdConjuntoEstilos($objTextoPadraoInternoDTO->getNumIdConjuntoEstilos());
      }

      if ($parObjEditorDTO->getNumIdConjuntoEstilos() == null) {
        $objConjuntoEstilosDTO = new ConjuntoEstilosDTO();
        $objConjuntoEstilosDTO->setStrSinUltimo('S');
        $objConjuntoEstilosDTO->retNumIdConjuntoEstilos();
        $objConjuntoEstilosRN = new ConjuntoEstilosRN();
        $objConjuntoEstilosDTO = $objConjuntoEstilosRN->consultar($objConjuntoEstilosDTO);
        $parObjEditorDTO->setNumIdConjuntoEstilos($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());
      }

      if ($parObjEditorDTO->getNumIdBaseConhecimento() != null) {
        $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
        $objBaseConhecimentoDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
        $objBaseConhecimentoRN->configurarEstilos($objBaseConhecimentoDTO);
      } else {
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
        $objDocumentoDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
        $objDocumentoRN->configurarEstilos($objDocumentoDTO);
      }

      $arrConteudoInicialSecoes = null;
      if ($parObjEditorDTO->isSetArrConteudoInicialSecoes()) {
        if($parObjEditorDTO->isSetDblIdDocumentoBase()
            || $parObjEditorDTO->isSetDblIdDocumentoTextoBase()
            //|| $parObjEditorDTO->isSetNumIdBaseConhecimentoBase()
            || $parObjEditorDTO->isSetNumIdTextoPadraoInterno()
            || $parObjEditorDTO->isSetStrConteudoSecaoPrincipal()){
          throw new InfraException('Não é permitido informar o conteúdo das seções junto com um conteúdo base.');
        }
        $arrConteudoInicialSecoes = $parObjEditorDTO->getArrConteudoInicialSecoes();
      }

      //não é clonagem
      if (!$parObjEditorDTO->isSetDblIdDocumentoBase() && !$parObjEditorDTO->isSetNumIdBaseConhecimentoBase()) {

        //recupera seções do modelo
        $objSecaoModeloDTO = new SecaoModeloDTO();
        $objSecaoModeloDTO->retNumIdSecaoModelo();
        $objSecaoModeloDTO->retStrNome();
        $objSecaoModeloDTO->retStrSinSomenteLeitura();
        $objSecaoModeloDTO->retStrSinAssinatura();
        $objSecaoModeloDTO->retStrSinPrincipal();
        $objSecaoModeloDTO->retStrSinDinamica();
        $objSecaoModeloDTO->retStrSinCabecalho();
        $objSecaoModeloDTO->retStrSinRodape();
        $objSecaoModeloDTO->retStrSinHtml();
        $objSecaoModeloDTO->retStrConteudo();
        $objSecaoModeloDTO->retNumOrdem();
        $objSecaoModeloDTO->setNumIdModelo($parObjEditorDTO->getNumIdModelo());
        $objSecaoModeloDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

        /** @var SecaoModeloDTO[] $arrObjSecaoModeloDTO */
        $arrObjSecaoModeloDTO = $objSecaoModeloRN->listar($objSecaoModeloDTO);

        if (count($arrObjSecaoModeloDTO) == 0) {
          throw new InfraException('Modelo do documento não contém seções.');
        }


        //recupera estilos padrão das seções do modelo
        $objRelSecaoModCjEstilosItemDTO = new RelSecaoModCjEstilosItemDTO();
        $objRelSecaoModCjEstilosItemDTO->retNumIdSecaoModelo();
        $objRelSecaoModCjEstilosItemDTO->retStrNomeEstilo();
        $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo(InfraArray::converterArrInfraDTO($arrObjSecaoModeloDTO, 'IdSecaoModelo'), InfraDTO::$OPER_IN);
        $objRelSecaoModCjEstilosItemDTO->setStrSinPadrao('S');
        $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
        $objRelSecaoModCjEstilosItemRN = new RelSecaoModCjEstilosItemRN();
        $arrObjRelSecaoModCjEstilosItemDTO = InfraArray::indexarArrInfraDTO($objRelSecaoModCjEstilosItemRN->listar($objRelSecaoModCjEstilosItemDTO), 'IdSecaoModelo');

        $arrImagemPermitida = EditorINT::getArrImagensPermitidas();

        //gera copia das secoes do modelo, ja formatando o conteudo com a formatacao padrao
        foreach ($arrObjSecaoModeloDTO as $objSecaoModeloDTO) {

          $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
          $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
          $objSecaoDocumentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
          $objSecaoDocumentoDTO->setNumIdSecaoModelo($objSecaoModeloDTO->getNumIdSecaoModelo());
          $objSecaoDocumentoDTO->setNumOrdem($objSecaoModeloDTO->getNumOrdem());
          $objSecaoDocumentoDTO->setStrSinSomenteLeitura($objSecaoModeloDTO->getStrSinSomenteLeitura());
          $objSecaoDocumentoDTO->setStrSinAssinatura($objSecaoModeloDTO->getStrSinAssinatura());
          $objSecaoDocumentoDTO->setStrSinPrincipal($objSecaoModeloDTO->getStrSinPrincipal());
          $objSecaoDocumentoDTO->setStrSinDinamica($objSecaoModeloDTO->getStrSinDinamica());
          $objSecaoDocumentoDTO->setStrSinHtml($objSecaoModeloDTO->getStrSinHtml());
          $objSecaoDocumentoDTO->setStrSinCabecalho($objSecaoModeloDTO->getStrSinCabecalho());
          $objSecaoDocumentoDTO->setStrSinRodape($objSecaoModeloDTO->getStrSinRodape());
          $objSecaoDocumentoDTO->setStrConteudo($objSecaoModeloDTO->getStrConteudo());

          $objSecaoDocumentoDTO = $objSecaoDocumentoRN->cadastrar($objSecaoDocumentoDTO);

          if ($objSecaoModeloDTO->getStrSinAssinatura() == 'N') {

            //cadastra primeiro registro de versão da seção
            $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
            $objVersaoSecaoDocumentoDTO->setDblIdVersaoSecaoDocumento(null);
            $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());

            $strEstiloPadrao = '';
            if (isset($arrObjRelSecaoModCjEstilosItemDTO[$objSecaoModeloDTO->getNumIdSecaoModelo()])) {
              $strEstiloPadrao = 'class="'.$arrObjRelSecaoModCjEstilosItemDTO[$objSecaoModeloDTO->getNumIdSecaoModelo()]->getStrNomeEstilo().'"';
            }

            $strConteudo = '';
            $bolConteudoEdoc = false;
            $bolConteudoSecaoPrincipal = false;
            $bolConteudoTextoPadrao = false;
            $bolConteudoTextoBase = false;

            //conteúdo informado especificamente para esta seção
            if ($arrConteudoInicialSecoes != null && isset($arrConteudoInicialSecoes[$objSecaoModeloDTO->getStrNome()])) {
              if($objSecaoModeloDTO->getStrSinSomenteLeitura()=='S'
                  || $objSecaoModeloDTO->getStrSinAssinatura()=='S'
                  || $objSecaoModeloDTO->getStrSinCabecalho()=='S'
                  || $objSecaoModeloDTO->getStrSinRodape()=='S') {
                throw new InfraException('Seção do documento "'.$objSecaoModeloDTO->getStrNome().'" não permite alteração do seu conteúdo.');
              }
              $strConteudo = $arrConteudoInicialSecoes[$objSecaoModeloDTO->getStrNome()];
              unset($arrConteudoInicialSecoes[$objSecaoModeloDTO->getStrNome()]);

              //se deve copiar o conteúdo de um documento do eDoc então aplica na seção principal do documento
            } else if ($objSecaoModeloDTO->getStrSinPrincipal() == 'S' && $parObjEditorDTO->isSetDblIdDocumentoEdocBase()) {

              $objDocumentoDTO = new DocumentoDTO();
              $objDocumentoDTO->setDblIdDocumentoEdoc($parObjEditorDTO->getDblIdDocumentoEdocBase());

              $objEDocRN = new EDocRN();
              $strConteudo = EDocINT::converterParaEditorInterno($objEDocRN->consultarHTMLDocumentoRN1204($objDocumentoDTO));
              $bolConteudoEdoc = true;

              //configurar conteudo da seção editável com o conteúdo da mesma seção no documento usado para texto base
            } else if ($objSecaoModeloDTO->getStrSinSomenteLeitura() == 'N' && $parObjEditorDTO->isSetDblIdDocumentoTextoBase()) {

              $objVersaoSecaoDocumentoDTOTextoBase = new VersaoSecaoDocumentoDTO();
              $objVersaoSecaoDocumentoDTOTextoBase->retStrConteudo();
              $objVersaoSecaoDocumentoDTOTextoBase->setStrSinUltima('S');
              $objVersaoSecaoDocumentoDTOTextoBase->setDblIdDocumentoSecaoDocumento($parObjEditorDTO->getDblIdDocumentoTextoBase());
              $objVersaoSecaoDocumentoDTOTextoBase->setStrNomeSecaoModelo($objSecaoModeloDTO->getStrNome());

              $objVersaoSecaoDocumentoDTOTextoBase = $objVersaoSecaoDocumentoRN->consultar($objVersaoSecaoDocumentoDTOTextoBase);

              if ($objVersaoSecaoDocumentoDTOTextoBase != null) {
                $strConteudo = $objVersaoSecaoDocumentoDTOTextoBase->getStrConteudo();
                $bolConteudoTextoBase = true;
              }

              //conteudo informado para seção principal
            } else if ($objSecaoModeloDTO->getStrSinPrincipal() == 'S' && $parObjEditorDTO->isSetStrConteudoSecaoPrincipal()) {
              $strConteudo = $parObjEditorDTO->getStrConteudoSecaoPrincipal();
              $bolConteudoSecaoPrincipal = true;

              //texto padrão deve ser aplicado na seção principal
            } else if ($objSecaoModeloDTO->getStrSinPrincipal() == 'S' && $parObjEditorDTO->isSetNumIdTextoPadraoInterno()) {

              $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
              $objTextoPadraoInternoDTO->retStrConteudo();
              $objTextoPadraoInternoDTO->retNumIdConjuntoEstilos();
              $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($parObjEditorDTO->getNumIdTextoPadraoInterno());

              $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
              $objTextoPadraoInternoDTO = $objTextoPadraoInternoRN->consultar($objTextoPadraoInternoDTO);

              $strConteudo = $objTextoPadraoInternoDTO->getStrConteudo();
              $bolConteudoTextoPadrao = true;

              //coloca conteúdo inicial definido no modelo
            } else {
              $strConteudo = $objSecaoModeloDTO->getStrConteudo();
            }

            if (trim($strConteudo) == '') {
              if ($objSecaoModeloDTO->getStrSinSomenteLeitura() == 'S') {
                $objVersaoSecaoDocumentoDTO->setStrConteudo(null);
              } else {
                $objVersaoSecaoDocumentoDTO->setStrConteudo('<p '.$strEstiloPadrao.'>&nbsp;</p>'."\r\n");
              }
            } else {

              //efetua limpeza de tags para documentos gerados com conteudo inicial
              //$strConteudo = $this->limparTagsCriticas($strConteudo);
              $this->validarTagsCriticas($arrImagemPermitida, $strConteudo);
              $strConteudo = $this->processarLinksSei($strConteudo);

              $strConteudo = $this->substituirTagsInterno($parObjEditorDTO, $strConteudo);

              if ($bolConteudoTextoBase) {

                $objVersaoSecaoDocumentoDTO->setStrConteudo($strConteudo);

              } else {

                if ($bolConteudoEdoc || $bolConteudoTextoPadrao || $bolConteudoSecaoPrincipal) {
                  $objVersaoSecaoDocumentoDTO->setStrConteudo($strConteudo);
                } else { //conteúdo inicial de seção (ex.: nome da base de conhecimento passada para a seção de título) ou conteúdo definido nas seções do modelo

                  if ($objSecaoModeloDTO->getStrSinHtml() == 'N') {

                    $strConteudoFormatado = '';
                    $arrConteudo = explode("\n", $strConteudo);
                    foreach ($arrConteudo as $strItemConteudo) {
                      $strConteudoFormatado .= '<p '.$strEstiloPadrao.'>'.$strItemConteudo.'</p>'."\r\n";
                    }

                    $objVersaoSecaoDocumentoDTO->setStrConteudo($strConteudoFormatado);

                  } else {
                    $objVersaoSecaoDocumentoDTO->setStrConteudo($strConteudo);
                  }
                }
              }
            }

            $objVersaoSecaoDocumentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            $objVersaoSecaoDocumentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objVersaoSecaoDocumentoDTO->setDthAtualizacao($dthAtual);
            $objVersaoSecaoDocumentoDTO->setNumVersao(1);

            $objVersaoSecaoDocumentoRN->cadastrar($objVersaoSecaoDocumentoDTO);
          }
        }
        if(InfraArray::contar($arrConteudoInicialSecoes)){
          $strNomeSecao=array_key_first($arrConteudoInicialSecoes);
          throw new InfraException('Conteúdo informado para a seção "'.$strNomeSecao.'" que não existe no modelo do documento.');
        }
      } else { //clonando documento ou base de conhecimento

        $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
        $objSecaoDocumentoDTO->retStrNomeSecaoModelo();
        $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
        $objSecaoDocumentoDTO->retNumIdSecaoModelo();
        $objSecaoDocumentoDTO->retNumOrdem();
        $objSecaoDocumentoDTO->retStrSinSomenteLeitura();
        $objSecaoDocumentoDTO->retStrSinAssinatura();
        $objSecaoDocumentoDTO->retStrSinPrincipal();
        $objSecaoDocumentoDTO->retStrSinDinamica();
        $objSecaoDocumentoDTO->retStrSinHtml();
        $objSecaoDocumentoDTO->retStrSinCabecalho();
        $objSecaoDocumentoDTO->retStrSinRodape();
        $objSecaoDocumentoDTO->retStrConteudo();

        if ($parObjEditorDTO->isSetDblIdDocumentoBase()) {
          $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumentoBase());
        }

        if ($parObjEditorDTO->isSetNumIdBaseConhecimentoBase()) {
          $objSecaoDocumentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimentoBase());
        }

        $arrObjSecaoDocumentoDTOBase = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);

        //bloquear registros de versão
        $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
        $objVersaoSecaoDocumentoDTO->retDblIdVersaoSecaoDocumento();
        $objVersaoSecaoDocumentoDTO->retNumIdSecaoDocumento();
        $objVersaoSecaoDocumentoDTO->retStrConteudo();
        $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento(InfraArray::converterArrInfraDTO($arrObjSecaoDocumentoDTOBase, 'IdSecaoDocumento'), InfraDTO::$OPER_IN);
        $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');

        $arrObjVersaoSecaoDocumentoDTOBase = InfraArray::indexarArrInfraDTO($objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO), 'IdSecaoDocumento');

        //busca estilo padrao da secao
        $objRelSecaoModCjEstilosItemDTO = new RelSecaoModCjEstilosItemDTO();
        $objRelSecaoModCjEstilosItemDTO->retNumIdSecaoModelo();
        $objRelSecaoModCjEstilosItemDTO->retStrNomeEstilo();
        $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo(InfraArray::converterArrInfraDTO($arrObjSecaoDocumentoDTOBase, 'IdSecaoModelo'), InfraDTO::$OPER_IN);
        $objRelSecaoModCjEstilosItemDTO->setStrSinPadrao('S');
        $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
        $objRelSecaoModCjEstilosItemRN = new RelSecaoModCjEstilosItemRN();
        $arrObjRelSecaoModCjEstilosItemDTO = InfraArray::indexarArrInfraDTO($objRelSecaoModCjEstilosItemRN->listar($objRelSecaoModCjEstilosItemDTO), 'IdSecaoModelo');

        foreach ($arrObjSecaoDocumentoDTOBase as $objSecaoDocumentoDTOBase) {

          $objSecaoDocumentoDTO = clone($objSecaoDocumentoDTOBase);
          $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
          $objSecaoDocumentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
          $objSecaoDocumentoDTO = $objSecaoDocumentoRN->cadastrar($objSecaoDocumentoDTO);

          if ($objSecaoDocumentoDTOBase->getStrSinAssinatura() == 'N') {

            $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
            $objVersaoSecaoDocumentoDTO->setDblIdVersaoSecaoDocumento(null);
            $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());


            $strEstiloPadrao = '';
            if (isset($arrObjRelSecaoModCjEstilosItemDTO[$objSecaoDocumentoDTOBase->getNumIdSecaoModelo()])) {
              $strEstiloPadrao = 'class="'.$arrObjRelSecaoModCjEstilosItemDTO[$objSecaoDocumentoDTOBase->getNumIdSecaoModelo()]->getStrNomeEstilo().'"';
            }

            if ($objSecaoDocumentoDTOBase->getStrSinSomenteLeitura() == 'S' || $objSecaoDocumentoDTOBase->getStrSinDinamica() == 'S') {

              $strConteudo = $objSecaoDocumentoDTOBase->getStrConteudo();

              $strConteudo = $this->substituirTagsInterno($parObjEditorDTO, $strConteudo);

              if ($objSecaoDocumentoDTOBase->getStrSinHtml() == 'N') {
                $strConteudoFormatado = '';
                $arrConteudo = explode("\n", $strConteudo);
                foreach ($arrConteudo as $strItemConteudo) {
                  $strConteudoFormatado .= '<p '.$strEstiloPadrao.'>'.$strItemConteudo.'</p>'."\r\n";
                }
                $strConteudo = $strConteudoFormatado;
              }

            } else {
              $strConteudo = $this->substituirTagsInterno($parObjEditorDTO, $arrObjVersaoSecaoDocumentoDTOBase[$objSecaoDocumentoDTOBase->getNumIdSecaoDocumento()]->getStrConteudo());
            }

            $objVersaoSecaoDocumentoDTO->setStrConteudo($strConteudo);
            $objVersaoSecaoDocumentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            $objVersaoSecaoDocumentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objVersaoSecaoDocumentoDTO->setDthAtualizacao($dthAtual);
            $objVersaoSecaoDocumentoDTO->setNumVersao(1);

            $objVersaoSecaoDocumentoRN->cadastrar($objVersaoSecaoDocumentoDTO);
          }
        }
      }

      //cadastrar conjunto de estilos
      //print_r($parObjEditorDTO); die;
      $this->atualizarConteudo($parObjEditorDTO);

    } catch (Exception $e) {
      throw new InfraException('Erro gerando versão inicial documento.', $e);
    }
  }

  public function adicionarVersao(EditorDTO $parObjEditorDTO){
    try {

      return $this->adicionarVersaoInterno($parObjEditorDTO);

    }catch(Exception $e){

      if ($parObjEditorDTO->isSetStrSinProcessandoEditor() && $parObjEditorDTO->getStrSinProcessandoEditor()=='S') {

        if (strpos($e->__toString(), SeiINT::$MSG_ERRO_XSS) !== false) {

          //LogSEI::getInstance()->gravar(InfraException::inspecionar($e)."\n\n".file_get_contents(DIR_SEI_TEMP.'/'.$parObjEditorDTO->getStrArquivoComparacaoXss()));

          throw new InfraException('COMPARACAO '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=exibir_arquivo&nome_arquivo='.$parObjEditorDTO->getStrArquivoComparacaoXss().'&original=1'));
        }
      }

      throw $e;
    }
  }

  protected function adicionarVersaoInternoControlado(EditorDTO $parObjEditorDTO)
  {
    try {
      $objInfraException = new InfraException();

      if ($parObjEditorDTO->getDblIdDocumento()!=null) {

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->retNumIdConjuntoEstilos();
        $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO==null) {
          $objInfraException->lancarValidacao('Documento não encontrado.');
        } else {

          $numIdConjuntoEstilos = $objDocumentoDTO->getNumIdConjuntoEstilos();

          $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
          $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
          $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_TODOS);
          $objPesquisaProtocoloDTO->setDblIdProtocolo($parObjEditorDTO->getDblIdDocumento());

          $objProtocoloRN = new ProtocoloRN();
          $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

          if (count($arrObjProtocoloDTO) == 0){
            $objInfraException->lancarValidacao('Protocolo não encontrado.');
          }

          $objProtocoloDTO = $arrObjProtocoloDTO[0];

          if ($objProtocoloDTO->getNumCodigoAcesso() < 0) {
            if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO) {
              $objInfraException->lancarValidacao('Usuário sem acesso para alteração do documento.');
            }else{
              $objInfraException->lancarValidacao('Unidade sem acesso para alteração do documento.');
            }
          }

          if ($objProtocoloDTO->getNumIdUnidadeGeradora()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {
            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
            $objAtividadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTO->setDthConclusao(null);
            $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

            $objAtividadeRN = new AtividadeRN();
            if ($objAtividadeRN->contarRN0035($objAtividadeDTO) == 0) {
              $objInfraException->lancarValidacao('Processo não está aberto na unidade.');
            }
          }

          $objProcedimentoDTO = new ProcedimentoDTO();
          $objProcedimentoDTO->setDblIdProcedimento($objProtocoloDTO->getDblIdProcedimentoDocumento());
          $objProcedimentoDTO->setStrSinDocTodos('S');
          $objProcedimentoDTO->setArrDblIdProtocoloAssociado(array($parObjEditorDTO->getDblIdDocumento()));

          $objProcedimentoRN = new ProcedimentoRN();
          $arrObjProcedimentoDTO = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

          if (count($arrObjProcedimentoDTO) == 0){
            $objInfraException->lancarValidacao('Processo não encontrado.');
          }

          $objProcedimentoDTO = $arrObjProcedimentoDTO[0];

          $objProcedimentoRN->verificarEstadoProcedimento($objProcedimentoDTO);

          $arrObjRelProtocoloProtocoloDTO = $objProcedimentoDTO->getArrObjRelProtocoloProtocoloDTO();

          if (InfraArray::contar($arrObjRelProtocoloProtocoloDTO) == 0){
            $objInfraException->lancarValidacao('Documento não encontrado.');
          }

          $objDocumentoDTO = $arrObjRelProtocoloProtocoloDTO[0]->getObjProtocoloDTO2();

          if ($objDocumentoDTO->getStrSinPublicado() == 'S'){
            $objInfraException->lancarValidacao('Documento foi publicado.');
          }

          if ($objDocumentoDTO->getStrSinBloqueado() == 'S'){
            $objInfraException->lancarValidacao('Documento foi assinado e não pode mais ser alterado.');
          }

          if (SessaoSEI::getInstance()->getNumIdUnidadeAtual() != $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo()){

            if ($objDocumentoDTO->getStrSinAssinadoPorOutraUnidade() == 'S') {
              $objInfraException->lancarValidacao('Documento foi assinado em outra unidade.');
            }

          }else {

            if ((!$parObjEditorDTO->isSetStrSinMontandoEditor() || $parObjEditorDTO->getStrSinMontandoEditor()=='N') && $objProtocoloDTO->getStrSinAssinado() == 'S'){
              $objInfraException->lancarValidacao('Documento foi assinado.');
            }

            if ($objProtocoloDTO->getStrSinDisponibilizadoParaOutraUnidade() == 'S'){
              $objInfraException->lancarValidacao('Documento disponibilizado em bloco de assinatura.');
            }

          }

          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->retNumIdBloco();
          $objRelBlocoProtocoloDTO->setDblIdProtocolo($parObjEditorDTO->getDblIdDocumento());

          $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
          $arrObjRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO);

          if (count($arrObjRelBlocoProtocoloDTO)){
            $objBlocoRN = new BlocoRN();
            $objBlocoRN->removerRevisao(InfraArray::gerarArrInfraDTO('BlocoDTO','IdBloco',InfraArray::converterArrInfraDTO($arrObjRelBlocoProtocoloDTO,'IdBloco')));
          }

          if ($objDocumentoDTO->getStrSinAssinado() == 'S') {
            $parObjEditorDTO->setStrSinForcarNovaVersao('S');
          }

          if ($numIdConjuntoEstilos==null || ($parObjEditorDTO->isSetStrSinForcarNovaVersao() && $parObjEditorDTO->getStrSinForcarNovaVersao()=='S')){
            $this->converterDocumento($parObjEditorDTO);
          } else {
            $parObjEditorDTO->setNumIdConjuntoEstilos($numIdConjuntoEstilos);
          }
        }

      } else {
        $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
        $objBaseConhecimentoDTO->retNumIdConjuntoEstilos();

        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);
        if ($objBaseConhecimentoDTO==null) {
          $objInfraException->lancarValidacao('Base de conhecimento não encontrada.');
        } else {
          if ($objBaseConhecimentoDTO->getNumIdConjuntoEstilos()==null ||
              ($parObjEditorDTO->isSetStrSinForcarNovaVersao() && $parObjEditorDTO->getStrSinForcarNovaVersao()=='S')
          ) {
            $this->converterDocumento($parObjEditorDTO);
          } else {
            $parObjEditorDTO->setNumIdConjuntoEstilos($objBaseConhecimentoDTO->getNumIdConjuntoEstilos());
          }
        }
      }

      $dthAtual = InfraData::getStrDataHoraAtual();

      $arrObjSecaoDocumentoDTO = $parObjEditorDTO->getArrObjSecaoDocumentoDTO();

      if (InfraArray::contar($arrObjSecaoDocumentoDTO)==0) {
        throw new InfraException('Documento sem seções.');
      }

      $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
      $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
      $objSecaoDocumentoDTO->retNumIdSecaoModelo();
      $objSecaoDocumentoDTO->retStrSinDinamica();
      $objSecaoDocumentoDTO->retStrSinSomenteLeitura();
      $objSecaoDocumentoDTO->retStrSinHtml();
      $objSecaoDocumentoDTO->retStrSinCabecalho();
      $objSecaoDocumentoDTO->retStrSinRodape();
      $objSecaoDocumentoDTO->retStrConteudo();
      $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
      $objSecaoDocumentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
      $objSecaoDocumentoDTO->setStrSinAssinatura('N');

      $objSecaoDocumentoRN = new SecaoDocumentoRN();
      $arrObjSecaoDocumentoDTOBanco = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);

      $numSecoesDocumento = InfraArray::contar($arrObjSecaoDocumentoDTO);
      $numSecoesDocumentoBanco = count($arrObjSecaoDocumentoDTOBanco);

      if ($numSecoesDocumentoBanco!=$numSecoesDocumento) {
        if ($numSecoesDocumentoBanco > $numSecoesDocumento){
          throw new InfraException('Conteúdo do documento incompleto.', null, null, false);
        }else {
          throw new InfraException('Número de seções do documento inconsistente.');
        }
      }

      for ($i = 0; $i<$numSecoesDocumentoBanco; $i++) {
        for ($j = 0; $j<$numSecoesDocumento; $j++) {
          if ($arrObjSecaoDocumentoDTOBanco[$i]->getNumIdSecaoModelo()==$arrObjSecaoDocumentoDTO[$j]->getNumIdSecaoModelo()) {
            $arrObjSecaoDocumentoDTO[$j]->setNumIdSecaoDocumento($arrObjSecaoDocumentoDTOBanco[$i]->getNumIdSecaoDocumento());
            $arrObjSecaoDocumentoDTO[$j]->setStrSinDinamica($arrObjSecaoDocumentoDTOBanco[$i]->getStrSinDinamica());
            $arrObjSecaoDocumentoDTO[$j]->setStrSinSomenteLeitura($arrObjSecaoDocumentoDTOBanco[$i]->getStrSinSomenteLeitura());
            $arrObjSecaoDocumentoDTO[$j]->setStrSinHtml($arrObjSecaoDocumentoDTOBanco[$i]->getStrSinHtml());
            $arrObjSecaoDocumentoDTO[$j]->setStrSinCabecalho($arrObjSecaoDocumentoDTOBanco[$i]->getStrSinCabecalho());
            $arrObjSecaoDocumentoDTO[$j]->setStrSinRodape($arrObjSecaoDocumentoDTOBanco[$i]->getStrSinRodape());
            $arrObjSecaoDocumentoDTO[$j]->setStrConteudoOriginal($arrObjSecaoDocumentoDTOBanco[$i]->getStrConteudo());
            break;
          }
        }
        if ($j==$numSecoesDocumento) {
          throw new InfraException('Seção [' . $arrObjSecaoDocumentoDTOBanco[$i]->getNumIdSecaoModelo() . '] do documento não encontrada.');
        }
      }

//      self::$arrTags = null;
//      foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {
//        if ($objSecaoDocumentoDTO->getStrSinDinamica()=='S') {
//          $objParametrosEditorDTO = $this->obterParametros($parObjEditorDTO);
//          $arrTags = $objParametrosEditorDTO->getArrTags();
//          break;
//        }
//      }

      //bloquear registros de versão
      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoDTO->retDblIdVersaoSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retNumIdSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retStrSiglaUsuario();
      $objVersaoSecaoDocumentoDTO->retStrNomeUsuario();
      $objVersaoSecaoDocumentoDTO->retDthAtualizacao();
      $objVersaoSecaoDocumentoDTO->retStrConteudo();
      $objVersaoSecaoDocumentoDTO->retNumVersao();
      $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento(InfraArray::converterArrInfraDTO($arrObjSecaoDocumentoDTOBanco, 'IdSecaoDocumento'), InfraDTO::$OPER_IN);
      $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');

      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();

      $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

      $numVersao = 0;
      $objVersaoSecaoDocumentoDTOUltima = null;
      foreach ($arrObjVersaoSecaoDocumentoDTO as $dto) {
        if ($dto->getNumVersao()>$numVersao) {
          $numVersao = $dto->getNumVersao();
          $objVersaoSecaoDocumentoDTOUltima = $dto;
        }
      }

      if (count($arrObjVersaoSecaoDocumentoDTO)!=$numSecoesDocumento) {
        throw new InfraException('Número de seções da última versão não corresponde ao número de seções do documento.');
      }

      if ($parObjEditorDTO->isSetNumVersao() && $parObjEditorDTO->getNumVersao()!=$numVersao) {
        if (!$parObjEditorDTO->isSetStrSinIgnorarNovaVersao() || $parObjEditorDTO->getStrSinIgnorarNovaVersao()=='N') {
          //IMPORTANTE: o texto da validacao é verificado na interface, se houver mudança deve ser refletida no ponto correspondente da interface
          $objInfraException->lancarValidacao('Existe uma nova versão (nº ' . $numVersao . ') para este documento atualizada por ' . $objVersaoSecaoDocumentoDTOUltima->getStrSiglaUsuario() . ' (' . $objVersaoSecaoDocumentoDTOUltima->getStrNomeUsuario() . ') em ' . $objVersaoSecaoDocumentoDTOUltima->getDthAtualizacao() . '.');
        }
      }


      //aplica estilo padrao da secao
      $objRelSecaoModCjEstilosItemDTO = new RelSecaoModCjEstilosItemDTO();
      $objRelSecaoModCjEstilosItemDTO->retNumIdSecaoModelo();
      $objRelSecaoModCjEstilosItemDTO->retStrNomeEstilo();
      $objRelSecaoModCjEstilosItemDTO->setNumIdSecaoModelo(InfraArray::converterArrInfraDTO($arrObjSecaoDocumentoDTO, 'IdSecaoModelo'), InfraDTO::$OPER_IN);
      $objRelSecaoModCjEstilosItemDTO->setStrSinPadrao('S');
      $objRelSecaoModCjEstilosItemDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
      $objRelSecaoModCjEstilosItemRN = new RelSecaoModCjEstilosItemRN();
      $arrObjRelSecaoModCjEstilosItemDTO = InfraArray::indexarArrInfraDTO($objRelSecaoModCjEstilosItemRN->listar($objRelSecaoModCjEstilosItemDTO), 'IdSecaoModelo');

      $arrEstilosFormatados = array();
      foreach ($arrObjRelSecaoModCjEstilosItemDTO as $objRelSecaoModCjEstilosItemDTO) {
        $strEstiloFormatado = 'class="' . $objRelSecaoModCjEstilosItemDTO->getStrNomeEstilo() . '"';
        $arrEstilosFormatados[$objRelSecaoModCjEstilosItemDTO->getNumIdSecaoModelo()] = $strEstiloFormatado;
      }

      $bolSecaoAlterada = false;

      $arrImagemPermitida = EditorINT::getArrImagensPermitidas();

      foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {
        foreach ($arrObjVersaoSecaoDocumentoDTO as $objVersaoSecaoDocumentoDTO) {
          if ($objSecaoDocumentoDTO->getNumIdSecaoDocumento()==$objVersaoSecaoDocumentoDTO->getNumIdSecaoDocumento()) {

            $strConteudo = $this->montarConteudoSecao($objSecaoDocumentoDTO, $arrEstilosFormatados, $numVersao, $parObjEditorDTO);
            $strConteudo=$this->processarLinksSei($strConteudo);
            $strConteudo = self::converterHTML($strConteudo);

            if ($objSecaoDocumentoDTO->getStrSinCabecalho()=='N' && $objSecaoDocumentoDTO->getStrSinRodape()=='N') {
              $this->validarTagsCriticas($arrImagemPermitida, $strConteudo);
            }

            if ($strConteudo!=$objVersaoSecaoDocumentoDTO->getStrConteudo()) {
              $bolSecaoAlterada = true;
            }

            break;
          }
        }
      }

      if ($bolSecaoAlterada || ($parObjEditorDTO->isSetStrSinForcarNovaVersao() && $parObjEditorDTO->getStrSinForcarNovaVersao()=='S')) {
        $numVersao++;
        foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {
          foreach ($arrObjVersaoSecaoDocumentoDTO as $objVersaoSecaoDocumentoDTO) {
            if ($objSecaoDocumentoDTO->getNumIdSecaoDocumento()==$objVersaoSecaoDocumentoDTO->getNumIdSecaoDocumento()) {

              $strConteudo = $this->montarConteudoSecao($objSecaoDocumentoDTO, $arrEstilosFormatados, $numVersao, $parObjEditorDTO);
              $strConteudo=$this->processarLinksSei($strConteudo);
              $strConteudo = self::converterHTML($strConteudo);

              if ($strConteudo!=$objVersaoSecaoDocumentoDTO->getStrConteudo()) {
                $objVersaoSecaoDocumentoRN->anular($objVersaoSecaoDocumentoDTO);
                $dto = new VersaoSecaoDocumentoDTO();
                $dto->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());
                $dto->setStrConteudo($strConteudo);
                $dto->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                $dto->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $dto->setDthAtualizacao($dthAtual);
                $dto->setNumVersao($numVersao);
                $objVersaoSecaoDocumentoRN->cadastrar($dto);
              }

              break;
            }
          }
        }
        $this->atualizarConteudo($parObjEditorDTO);
      }
      /*
      else{
        if ($_GET['acao']=='editor_salvar'){
          LogSEI::getInstance()->gravar('Nenhuma alteração foi encontrada no conteúdo do documento: '.$parObjEditorDTO->getDblIdDocumento().' ['.SessaoSEI::getInstance()->getStrSiglaUsuario().'/'.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().']');
        }
      }
      */

      return $numVersao;

    } catch (Exception $e) {
      throw new InfraException('Erro adicionando versão do documento.', $e);
    }
  }

  protected function getArrayCssConectado($numIdConjuntoEstilos)
  {
    /*
     * transforma o conjunto de estilos em um array
     * p.Texto_Centralizado {font-size:1;text-align:center;}
     *
     * -> array { [Texto_Centralizado] => array {
     *                    [font-size] => "1"
     *                    [text-align] => "center"
     *                    }
     *          }
     */
    $strCss = $this->montarCssEditor($numIdConjuntoEstilos);
    //seleciona classes p.[nome_estilo]
    preg_match_all("%p\\.([^\\s]*) {([^}]*)}%", $strCss, $arrClassesCss);

    $arrResult = array();
    //para cada classe css
    for ($i = 0, $iMax = InfraArray::contar($arrClassesCss[1]); $i<$iMax; $i++) {
      //cria item no array de resultado com nome da classe css
      $arrResult[$arrClassesCss[1][$i]] = array();
      //explode os atributos da classe (estilos)
      $arrEstilos = explode(';', $arrClassesCss[2][$i]);
      foreach ($arrEstilos as $value) {
        //se não for vazio
        if (strlen($value)>0) {
          $arrValor = explode(':', $value);
          //inclui no arrResult[nome_do_estilo][atributo]=valor_atributo;
          if ($arrValor[1]==='0 3pt 0 3pt') {
            $arrValor[1] = '0px 3pt';
          }
          $arrResult[$arrClassesCss[1][$i]][trim($arrValor[0])] = InfraString::transformarCaixaBaixa(trim($arrValor[1]));
        }
      }
    }
    return $arrResult;
  }

  private function comparaEstilo($arrEstilos, $strEstilo)
  {
    // verificar se strestilo está definida em arrestilos
    $strEstilo = str_replace(array(' 0px', ' 0pt'), ' 0', $strEstilo);
    $arrEstilos2 = array();
    $temp = explode(';', $strEstilo);
    foreach ($temp as $value) {
      //se não for vazio
      if (strlen($value)>0) {
        $arrValor = explode(':', $value);
        //inclui no arrEstilos2[atributo]=valor_atributo;
        if ($arrValor[1]==='0 3pt 0 3pt') {
          $arrValor[1] = '0 3pt';
        }
        $arrEstilos2[InfraString::transformarCaixaBaixa(trim($arrValor[0]))] = InfraString::transformarCaixaBaixa(trim($arrValor[1]));
      }
    }
    $numEstilos2 = InfraArray::contar($arrEstilos2);
    //verifica se tem atributos definidos

    if ($numEstilos2>0) {
      //compara com todos os estilos do arrEstilos
      foreach ($arrEstilos as $key => $value) {
        if (!is_array($value[0])) {
          //se tiver mesma quantidade de atributos
          if (InfraArray::contar($value)===$numEstilos2) {
            //compara as diferenças, que devem ser 0
            if (InfraArray::contar(array_diff_assoc($value, $arrEstilos2))===0 &&
                InfraArray::contar(array_diff_assoc($arrEstilos2, $value))===0
            )
              return $key;
          }
        } else {
          foreach ($value as $value2) {

            //se tiver mesma quantidade de atributos
            if (InfraArray::contar($value2)===$numEstilos2) {
              //compara as diferenças, que devem ser 0
              if (InfraArray::contar(array_diff_assoc($value2, $arrEstilos2))===0 &&
                  InfraArray::contar(array_diff_assoc($arrEstilos2, $value2))===0
              ){
                return $key;
              }
            }
          }
        }
      }
    }
    return null;
  }

  private function converterDocumento(EditorDTO $parObjEditorDTO)
  {
    try {
      if ($parObjEditorDTO->isSetNumIdConjuntoEstilos() && $parObjEditorDTO->getNumIdConjuntoEstilos()!=null) {
        $arrEstilos = $this->getArrayCss($parObjEditorDTO->getNumIdConjuntoEstilos());
      } else {
        $objConjuntoEstilosRN = new ConjuntoEstilosRN();
        $objConjuntoEstilosDTO = new ConjuntoEstilosDTO();
        $objConjuntoEstilosDTO->setStrSinUltimo('S');
        $objConjuntoEstilosDTO->retNumIdConjuntoEstilos();
        $objConjuntoEstilosDTO = $objConjuntoEstilosRN->consultar($objConjuntoEstilosDTO);
        if ($objConjuntoEstilosDTO==null) throw new InfraException('Erro consultando conjunto de estilos.');
        $arrEstilos = $this->getArrayCss($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());
        $parObjEditorDTO->setNumIdConjuntoEstilos($objConjuntoEstilosDTO->getNumIdConjuntoEstilos());
      }
      $arrObjSecaoDocumentoDTO = $parObjEditorDTO->getArrObjSecaoDocumentoDTO();
      foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {
        //converter seção_documento
        $strConteudo = $objSecaoDocumentoDTO->getStrConteudo();
        $objSecaoDocumentoDTO->setStrConteudo($this->converteTextoEstiloCss($arrEstilos, $strConteudo));
        ///////
      }
    } catch (Exception $e) {
      throw new InfraException('Erro convertendo documento.', $e);
    }

    if ($parObjEditorDTO->getDblIdDocumento()!=null) {
      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
      $objDocumentoDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
      $objDocumentoRN->configurarEstilos($objDocumentoDTO);
    } else if ($parObjEditorDTO->getNumIdBaseConhecimento()!=null) {
      $objBaseConhecimentoRN = new BaseConhecimentoRN();
      $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
      $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
      $objBaseConhecimentoDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());
      $objBaseConhecimentoRN->configurarEstilos($objBaseConhecimentoDTO);
    }
  }

  public function converteTextoEstiloCss($arrEstilosCss, $strConteudo)
  {

    //print_r($strConteudo);
    $strConteudoNovo = "";
//    $posAtual = 0;
    $posAnterior = 0;
    $cntNaoEncontrados = 0;
    $cntEncontrados = 0;
    while (($posAtual = strpos($strConteudo, 'style="', $posAnterior))!==false) {
      //copia conteudo até encontrar style
      $strConteudoNovo .= substr($strConteudo, $posAnterior, $posAtual - $posAnterior);
      $posFimEstilo = strpos($strConteudo, '"', $posAtual + 7);
      if ($posFimEstilo===false) {
        throw new InfraException('Erro localizando fim do estilo.');
      } else if ($posFimEstilo==$posAtual + 7) {
        $posAnterior = $posAtual + 8;
      } else {
        $strEstilo = substr($strConteudo, $posAtual + 7, $posFimEstilo - $posAtual - 7);
        $nomeClasse = $this->comparaEstilo($arrEstilosCss, $strEstilo);
        if ($nomeClasse==null) {
          $cntNaoEncontrados++;
          $posAnterior = $posAtual + 1;
          $strConteudoNovo .= 's';
          //InfraDebug::getInstance()->gravar("Nao encontrado estilo para: /".$strEstilo."/");
        } else {
          $posAnterior = $posFimEstilo + 1;
          $cntEncontrados++;
          $strConteudoNovo .= 'class="' . $nomeClasse . '"';
        }
      }
    }
    $strConteudoNovo .= substr($strConteudo, $posAnterior);
    //InfraDebug::getInstance()->gravar("Conversão: encontrados ".strval($cntEncontrados)." não encontrados ".strval($cntNaoEncontrados));
    return $strConteudoNovo;

  }

  private function montarConteudoSecao($objSecaoDocumentoDTO, $arrEstilosFormatados, $numVersao,$parObjEditorDTO)
  {

    self::$arrTags['versao']=$numVersao;
    $strConteudo = '';
    $strEstiloPadrao = '';
    if (isset($arrEstilosFormatados[$objSecaoDocumentoDTO->getNumIdSecaoModelo()])) {
      $strEstiloPadrao = $arrEstilosFormatados[$objSecaoDocumentoDTO->getNumIdSecaoModelo()];
    }

    if ($objSecaoDocumentoDTO->getStrSinDinamica()=='S') {

      $strConteudo = $objSecaoDocumentoDTO->getStrConteudoOriginal();

      $strConteudo=$this->substituirTagsInterno($parObjEditorDTO,$strConteudo);

      //if ($objSecaoDocumentoDTO->getStrSinSomenteLeitura()=='S') {
        if (trim($strConteudo)!='' && $objSecaoDocumentoDTO->getStrSinHtml()=='N') {
          $strConteudo = '<p ' . $strEstiloPadrao . '>'.$strConteudo . '</p>' . "\r\n";
        }
      //}

    } else {
      $strConteudo = $objSecaoDocumentoDTO->getStrConteudo();
      if (trim($strConteudo)=='' && $objSecaoDocumentoDTO->getStrSinSomenteLeitura()=='N') {
        $strConteudo = '<p ' . $strEstiloPadrao . '>'.'&nbsp;</p>' . "\r\n";
      }
    }

    return $strConteudo;
  }

  private function atualizarConteudo(EditorDTO $parObjEditorDTO)
  {
    try {

      $bolMontandoEditor = ($parObjEditorDTO->isSetStrSinMontandoEditor() && $parObjEditorDTO->getStrSinMontandoEditor()=='S');
      $bolProcessandoEditor = ($parObjEditorDTO->isSetStrSinProcessandoEditor() && $parObjEditorDTO->getStrSinProcessandoEditor()=='S');

      $objEditorDTO = new EditorDTO();
      $objEditorDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
      $objEditorDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());
      $objEditorDTO->setStrSinCabecalho('N');
      $objEditorDTO->setStrSinRodape('N');
      $objEditorDTO->setStrSinCarimboPublicacao('N');
      $objEditorDTO->setStrSinIdentificacaoVersao('N');
      $objEditorDTO->setNumIdConjuntoEstilos($parObjEditorDTO->getNumIdConjuntoEstilos());

      if (!$bolMontandoEditor && !$bolProcessandoEditor){
        $objEditorDTO->setStrSinValidarXss('S');
      }

      $strHtml = $this->consultarHtmlVersao($objEditorDTO);

      if ($bolProcessandoEditor) {
        SeiINT::compararXss($strHtml, $parObjEditorDTO);
      }

      if ($parObjEditorDTO->getDblIdDocumento()!=null) {

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setStrConteudo($strHtml);
        $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoRN->atualizarConteudoRN1205($objDocumentoDTO);

      } else {

        $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
        $objBaseConhecimentoDTO->setStrConteudo($strHtml);
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());

        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoRN->alterar($objBaseConhecimentoDTO);
      }

    } catch (Exception $e) {
      throw new InfraException('Erro atualizando conteúdo.', $e);
    }
  }

  private function consultarHtmlIdentificacaoVersao(EditorDTO $parObjEditorDTO)
  {

    $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
    $objVersaoSecaoDocumentoDTO->setDistinct(true);
    $objVersaoSecaoDocumentoDTO->retNumVersao();
    $objVersaoSecaoDocumentoDTO->retStrSiglaUsuario();
    $objVersaoSecaoDocumentoDTO->retStrNomeUsuario();
    $objVersaoSecaoDocumentoDTO->retDthAtualizacao();

    $objVersaoSecaoDocumentoDTO->setDblIdDocumentoSecaoDocumento($parObjEditorDTO->getDblIdDocumento());
    $objVersaoSecaoDocumentoDTO->setNumIdBaseConhecimentoSecaoDocumento($parObjEditorDTO->getNumIdBaseConhecimento());

    if ($parObjEditorDTO->isSetNumVersao()) {
      $objVersaoSecaoDocumentoDTO->setNumVersao($parObjEditorDTO->getNumVersao(), InfraDTO::$OPER_MENOR_IGUAL);
    }

    $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_ASC);


    $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
    $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

    $qtdVersoes = count($arrObjVersaoSecaoDocumentoDTO);
    $numVersao = 0;
    if ($qtdVersoes) {
      $strSiglaUsuarioGerador = $arrObjVersaoSecaoDocumentoDTO[0]->getStrSiglaUsuario();
      $strNomeUsuarioGerador = $arrObjVersaoSecaoDocumentoDTO[0]->getStrNomeUsuario();

      $strSiglaUsuarioVersao = $arrObjVersaoSecaoDocumentoDTO[$qtdVersoes - 1]->getStrSiglaUsuario();
      $strNomeUsuarioVersao = $arrObjVersaoSecaoDocumentoDTO[$qtdVersoes - 1]->getStrNomeUsuario();
      $numVersao = $arrObjVersaoSecaoDocumentoDTO[$qtdVersoes - 1]->getNumVersao();
      $dthVersao = $arrObjVersaoSecaoDocumentoDTO[$qtdVersoes - 1]->getDthAtualizacao();
    }

    $html = '<hr style="border:1px solid #c0c0c0;" />';
    $html .= 'Criado por ';
    $html .= '<a onclick="alert(\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUsuarioGerador) . '\')" alt="' . $strNomeUsuarioGerador . '" title="' . $strNomeUsuarioGerador . '" style="color:#0066cc;text-decoration:none;cursor:pointer;">' . $strSiglaUsuarioGerador . '</a>';
    $html .= ', versão ' . $numVersao . ' por ';
    $html .= '<a onclick="alert(\'' . PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeUsuarioVersao) . '\')" alt="' . $strNomeUsuarioVersao . '" title="' . $strNomeUsuarioVersao . '" style="color:#0066cc;text-decoration:none;cursor:pointer;">' . $strSiglaUsuarioVersao . '</a>';
    $html .= ' em ' . $dthVersao . '.' . "\n";

//    $html = '<hr style="border:1px solid #c0c0c0;" />';
//    $html .= 'Criado por  '. $strSiglaUsuarioGerador . ', versão ' . $numVersao . ' por '.$strSiglaUsuarioVersao . ' em ' . $dthVersao . '.' . "\n";

    $html = EditorINT::formatarNaoSelecionavel($html);

    return $html;
  }

  protected function consultarHtmlVersaoConectado(EditorDTO $parObjEditorDTO)
  {

    $dblIdProcedimento=null;
    if ($parObjEditorDTO->getDblIdDocumento()!=null) {
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrCrcAssinatura();
      $objDocumentoDTO->retStrQrCodeAssinatura();
      $objDocumentoDTO->retObjPublicacaoDTO();
      $objDocumentoDTO->retNumIdConjuntoEstilos();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrDescricaoTipoConferencia();

      $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO==null) {
        throw new InfraException('Documento não encontrado.');
      }

      $dblIdProcedimento=$objDocumentoDTO->getDblIdProcedimento();
      if ($objDocumentoDTO->getNumIdConjuntoEstilos()!=null) {
        $strConteudoCss = $this->montarCssEditor($objDocumentoDTO->getNumIdConjuntoEstilos());
      } else {
        $strConteudoCss = "";
      }
      $strTitulo = DocumentoINT::montarTitulo($objDocumentoDTO);

      $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

    } else {
      $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
      $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
      $objBaseConhecimentoDTO->retStrDescricao();
      $objBaseConhecimentoDTO->retStrSiglaUnidade();
      $objBaseConhecimentoDTO->retNumIdConjuntoEstilos();
      $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());

      $objBaseConhecimentoRN = new BaseConhecimentoRN();
      $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);

      if ($objBaseConhecimentoDTO==null) {
        throw new InfraException('Base de conhecimento não encontrada.');
      }

      if ($objBaseConhecimentoDTO->getNumIdConjuntoEstilos()!=null) {
        $strConteudoCss = $this->montarCssEditor($objBaseConhecimentoDTO->getNumIdConjuntoEstilos());
      } else {
        $strConteudoCss = "";
      }
      $strTitulo = BaseConhecimentoINT::montarTitulo($objBaseConhecimentoDTO);
    }

    //regex reset de contadores
    $qtd=preg_match_all('/p\.(\S*) \{[^}]*counter-increment:([^;]*);/',$strConteudoCss,$arrCssContadores);
    if ($qtd>0){
      $arrCssContadores=array_combine($arrCssContadores[1],$arrCssContadores[2]);
    } else {
      $arrCssContadores=null;
    }

    $strHtml = '';
    $strHtml .= '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . "\n";
    $strHtml .= '<html lang="pt-br" >' . "\n";
    $strHtml .= '<head>' . "\n";
    $strHtml .= '<meta http-equiv="Pragma" content="no-cache" />' . "\n";
    $strHtml .= '<meta name="robots" content="noindex" />'."\n";
    $strHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />' . "\n";
    $strHtml .= '<title>' . $strTitulo . '</title>' . "\n";
    if ($strConteudoCss!="") {
      $strHtml .= '<style type="text/css">' . "\n";
      $strHtml .= $strConteudoCss;
      $strHtml .= "\n</style>";
    }
    $strHtml .= '</head>' . "\n";
    $strHtml .= '<body>' . "\n";

    if ($parObjEditorDTO->getStrSinCarimboPublicacao()=='S' && $objDocumentoDTO != null) {
      $strTextoPublicacao = PublicacaoINT::obterTextoInformativoPublicacao($objDocumentoDTO);
      if ($strTextoPublicacao != null) {
        $strHtml .= $this->montarCarimboPublicacao($strTextoPublicacao)."\n";
      }
    }

    $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
    $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
    $objSecaoDocumentoDTO->retStrSinAssinatura();
    $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
    $objSecaoDocumentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());

    if ($parObjEditorDTO->getStrSinCabecalho()=='N') {
      $objSecaoDocumentoDTO->setStrSinCabecalho('N');
    }

    if ($parObjEditorDTO->getStrSinRodape()=='N') {
      $objSecaoDocumentoDTO->setStrSinRodape('N');
    }

    $objSecaoDocumentoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSecaoDocumentoRN = new SecaoDocumentoRN();
    $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();

    $arrObjSecaoDocumentoDTO = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);

    $numVersao = null;

    if (!$parObjEditorDTO->isSetNumVersao()) {
      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoDTO->retNumIdSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retNumVersao();
      $objVersaoSecaoDocumentoDTO->retStrConteudo();
      $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento(InfraArray::converterArrInfraDTO($arrObjSecaoDocumentoDTO, 'IdSecaoDocumento'), InfraDTO::$OPER_IN);
      $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');
      $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

      if (count($arrObjVersaoSecaoDocumentoDTO)) {
        $numVersao = $arrObjVersaoSecaoDocumentoDTO[0]->getNumVersao();
        $arrObjVersaoSecaoDocumentoDTO = InfraArray::indexarArrInfraDTO($arrObjVersaoSecaoDocumentoDTO, 'IdSecaoDocumento');
      }
    }

    foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {
      if ($objSecaoDocumentoDTO->getStrSinAssinatura()=='N') {

        if (!$parObjEditorDTO->isSetNumVersao()) {

          if (isset($arrObjVersaoSecaoDocumentoDTO[$objSecaoDocumentoDTO->getNumIdSecaoDocumento()])) {
            $strHtml .= $this->resetContadoresCss($arrObjVersaoSecaoDocumentoDTO[$objSecaoDocumentoDTO->getNumIdSecaoDocumento()]->getStrConteudo(), $arrCssContadores);
          }

        } else {

          $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
          $objVersaoSecaoDocumentoDTO->retStrConteudo();
          $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());
          $objVersaoSecaoDocumentoDTO->setNumVersao($parObjEditorDTO->getNumVersao(), InfraDTO::$OPER_MENOR_IGUAL);
          $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);
          $objVersaoSecaoDocumentoDTO->setNumMaxRegistrosRetorno(1);

          $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);
          $strHtml .= $this->resetContadoresCss($arrObjVersaoSecaoDocumentoDTO[0]->getStrConteudo(),$arrCssContadores);
        }

      } else {

        if ($parObjEditorDTO->isSetStrSinAssinaturas() && $parObjEditorDTO->getStrSinAssinaturas()=='N') {
          continue;
        }

        //só mostrar a tarja se consultando a última versão
        if ($parObjEditorDTO->isSetNumVersao()) {

          $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
          $objVersaoSecaoDocumentoDTO->retNumVersao();
          $objVersaoSecaoDocumentoDTO->setDblIdDocumentoSecaoDocumento($parObjEditorDTO->getDblIdDocumento());
          $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');
          $objVersaoSecaoDocumentoDTO->setNumMaxRegistrosRetorno(1);
          $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);

          $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

          if ($arrObjVersaoSecaoDocumentoDTO[0]->getNumVersao()!=$parObjEditorDTO->getNumVersao()) {
            continue;
          }
        }

        if ($objDocumentoDTO!=null) {
          $objAssinaturaRN = new AssinaturaRN();
          $strHtml .= $objAssinaturaRN->montarTarjas($objDocumentoDTO);
        }
      }
    }


    if ($parObjEditorDTO->getStrSinIdentificacaoVersao()=='S') {
      $strHtml .= $this->consultarHtmlIdentificacaoVersao($parObjEditorDTO);
    }

    $strHtml .= '</body>' . "\n";
    $strHtml .= '</html>' . "\n";

    if (!$parObjEditorDTO->isSetNumVersao()) {
      $parObjEditorDTO->setNumVersao($numVersao);
    }

    if ($parObjEditorDTO->isSetStrSinProcessarLinks() && $parObjEditorDTO->getStrSinProcessarLinks()=='S') {

      $strHtml=$this->processarLinksSei($strHtml);
//      $strHtml=preg_replace(self::$REGEXP_LINK_ASSINADO,'$4',$strHtml);

      $strHtml=$this->assinarLinksSei($strHtml, $dblIdProcedimento);
      $strHtml=$this->assinarLinkFederacao($strHtml);

    } else {
      $strHtml=preg_replace(self::$REGEXP_LINK_ASSINADO,'$4',$strHtml);
    }

    if ($parObjEditorDTO->isSetStrSinValidarXss() && $parObjEditorDTO->getStrSinValidarXss()=='S') {

      if ($objDocumentoDTO!=null){
        $dblIdDocumento = $objDocumentoDTO->getDblIdDocumento();
        $strIdentificacao = $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
      }else{
        $dblIdDocumento = null;
        $strIdentificacao = 'Base de Conhecimento '.$objBaseConhecimentoDTO->getStrDescricao().'/'.$objBaseConhecimentoDTO->getStrSiglaUnidade();
      }

      SeiINT::validarXss($strHtml, false, false, $strIdentificacao, $dblIdDocumento);
    }

    return $strHtml;
  }

  protected function compararHtmlVersaoConectado(EditorDTO $parObjEditorDTO)
  {

    $dblIdProcedimento = null;

    if ($parObjEditorDTO->getDblIdDocumento()!=null) {
      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrCrcAssinatura();
      $objDocumentoDTO->retStrQrCodeAssinatura();
      $objDocumentoDTO->retObjPublicacaoDTO();
      $objDocumentoDTO->retNumIdConjuntoEstilos();
      $objDocumentoDTO->retStrSinBloqueado();
      $objDocumentoDTO->retStrStaDocumento();
      $objDocumentoDTO->retStrStaProtocoloProtocolo();
      $objDocumentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objDocumentoDTO->retStrDescricaoTipoConferencia();

      $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());

      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      if ($objDocumentoDTO==null) {
        throw new InfraException('Documento não encontrado.');
      }

      $dblIdProcedimento = $objDocumentoDTO->getDblIdProcedimento();

      if ($objDocumentoDTO->getNumIdConjuntoEstilos()!=null) {
        $strConteudoCss = $this->montarCssEditor($objDocumentoDTO->getNumIdConjuntoEstilos());
      } else {
        $strConteudoCss = "";
      }
      $strTitulo = DocumentoINT::montarTitulo($objDocumentoDTO);

      $objDocumentoRN->bloquearConsultado($objDocumentoDTO);

    } else {
      $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
      $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
      $objBaseConhecimentoDTO->retStrDescricao();
      $objBaseConhecimentoDTO->retStrSiglaUnidade();
      $objBaseConhecimentoDTO->retNumIdConjuntoEstilos();
      $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());

      $objBaseConhecimentoRN = new BaseConhecimentoRN();
      $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);

      if ($objBaseConhecimentoDTO==null) {
        throw new InfraException('Base de conhecimento não encontrada.');
      }

      if ($objBaseConhecimentoDTO->getNumIdConjuntoEstilos()!=null) {
        $strConteudoCss = $this->montarCssEditor($objBaseConhecimentoDTO->getNumIdConjuntoEstilos());
      } else {
        $strConteudoCss = "";
      }
      $strTitulo = BaseConhecimentoINT::montarTitulo($objBaseConhecimentoDTO);
    }

    //regex reset de contadores
    $qtd=preg_match_all('/p\.(\S*) \{[^}]*counter-increment:([^;]*);/',$strConteudoCss,$arrCssContadores);
    if ($qtd>0){
      $arrCssContadores=array_combine($arrCssContadores[1],$arrCssContadores[2]);
    } else {
      $arrCssContadores=null;
    }

    $strHtml = '';
    $strHtml .= '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . "\n";
    $strHtml .= '<html lang="pt-br" >' . "\n";
    $strHtml .= '<head>' . "\n";
    $strHtml .= '<meta http-equiv="Pragma" content="no-cache" />' . "\n";
    $strHtml .= '<meta name="robots" content="noindex" />'."\n";
    $strHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />' . "\n";
    if ($strConteudoCss!="") {
      $strHtml .= '<style type="text/css"><!--/*--><![CDATA[/*><!--*/' . "\n";
      $strHtml .= $strConteudoCss."\n";
      $strHtml .= InfraHTML::getCssComparacao();
      $strHtml .= "\n/*]]>*/-->\n</style>";
    }
    $strHtml .= '<title>' . $strTitulo . ' - Comparando versões '.$parObjEditorDTO->getNumVersao().' e '.$parObjEditorDTO->getNumVersaoComparacao().'</title>' . "\n";
    $strHtml .= '</head>' . "\n";
    $strHtml .= '<body>' . "\n";

    if ($parObjEditorDTO->getStrSinCarimboPublicacao()=='S' && $objDocumentoDTO != null) {
      $strTextoPublicacao = PublicacaoINT::obterTextoInformativoPublicacao($objDocumentoDTO);
      if ($strTextoPublicacao != null) {
        $strHtml .= $this->montarCarimboPublicacao($strTextoPublicacao)."\n";
      }
    }

    $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
    $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
    $objSecaoDocumentoDTO->retStrSinAssinatura();
    $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
    $objSecaoDocumentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());

    if ($parObjEditorDTO->getStrSinCabecalho()=='N') {
      $objSecaoDocumentoDTO->setStrSinCabecalho('N');
    }

    if ($parObjEditorDTO->getStrSinRodape()=='N') {
      $objSecaoDocumentoDTO->setStrSinRodape('N');
    }

    $objSecaoDocumentoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objSecaoDocumentoRN = new SecaoDocumentoRN();
    $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();

    $arrObjSecaoDocumentoDTO = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);

    $numVersao = $parObjEditorDTO->getNumVersao();
    $numVersaoComparacao = $parObjEditorDTO->getNumVersaoComparacao();


    $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
    $objVersaoSecaoDocumentoDTO->retStrConteudo();
    $objVersaoSecaoDocumentoDTO->retNumVersao();
    $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objVersaoSecaoDocumentoDTO->setNumMaxRegistrosRetorno(1);

    foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {
      if ($objSecaoDocumentoDTO->getStrSinAssinatura()=='N') {
        $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());
        $objVersaoSecaoDocumentoDTO->setNumVersao($numVersao, InfraDTO::$OPER_MENOR_IGUAL);

        $objVersaoSecaoDocumentoDTO1 = $objVersaoSecaoDocumentoRN->consultar($objVersaoSecaoDocumentoDTO);

        $objVersaoSecaoDocumentoDTO->setNumVersao($numVersaoComparacao, InfraDTO::$OPER_MENOR_IGUAL);

        $objVersaoSecaoDocumentoDTO2 = $objVersaoSecaoDocumentoRN->consultar($objVersaoSecaoDocumentoDTO);

        if ($objVersaoSecaoDocumentoDTO1->getNumVersao() == $objVersaoSecaoDocumentoDTO2->getNumVersao() ||
            $objVersaoSecaoDocumentoDTO1->getStrConteudo() == $objVersaoSecaoDocumentoDTO2->getStrConteudo()
        ) {
          $strConteudo = $objVersaoSecaoDocumentoDTO1->getStrConteudo();
        } else {
          $str1=$objVersaoSecaoDocumentoDTO1->getStrConteudo();
          $str2=$objVersaoSecaoDocumentoDTO2->getStrConteudo();
          $strConteudo = InfraHTML::comparar($str1,$str2);
        }

        $strHtml .= $this->resetContadoresCss($strConteudo, $arrCssContadores);
      }

    }

    $strHtml .= '</body>' . "\n";
    $strHtml .= '</html>' . "\n";


    if ($parObjEditorDTO->isSetStrSinProcessarLinks() && $parObjEditorDTO->getStrSinProcessarLinks()=='S') {

      $strHtml=$this->processarLinksSei($strHtml);
//      $strHtml=preg_replace(self::$REGEXP_LINK_ASSINADO,'$4',$strHtml);

      $strHtml=$this->assinarLinksSei($strHtml, $dblIdProcedimento);
      $strHtml=$this->assinarLinkFederacao($strHtml);

    } else {
      $strHtml=preg_replace(self::$REGEXP_LINK_ASSINADO,'$4',$strHtml);
    }

    return $strHtml;
  }

  private function resetContadoresCss($strConteudoHtml,$arrClasses)
  {
    if(InfraString::isBolVazia($strConteudoHtml)){
      return '';
    }
    if(is_array($arrClasses) && count($arrClasses)>1){
      $arrContadoresUsados=array();
      $qtd=preg_match_all('/<p\w*\s*class="([^"]*)/',$strConteudoHtml,$arrMatches);
      if ($qtd>0){
        $arrClassesUsadas=array_unique($arrMatches[1]);
        foreach ($arrClassesUsadas as $strClasse) {
          if(isset($arrClasses[$strClasse])){
            $arrContadoresUsados[]=$arrClasses[$strClasse];
          }
        }
        if (count($arrContadoresUsados)>0){
          $arrContadoresUsados=array_unique($arrContadoresUsados);
          $strDiv="\n<div style=\"counter-reset:";
          foreach ($arrContadoresUsados as $strContador) {
            $strDiv.=" ".$strContador;
          }
          $strDiv.=';"></div>'."\n";
          return $strConteudoHtml.$strDiv;
        }
      }
    }
    return $strConteudoHtml;
  }

  private function substituirTagsInterno(EditorDTO $parObjEditorDTO,$strConteudo,$bolTesteModelo=false){
    if($bolTesteModelo){
      self::$arrTags['#numIdUnidadeResponsavel']=SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      self::$arrTags['#numIdUsuarioGerador']=SessaoSEI::getInstance()->getNumIdUsuario();
      $dtaGeracao=InfraData::getStrDataAtual();
      self::$arrTags['#dtaGeracao']=$dtaGeracao;
      self::$arrTags['dia']=substr($dtaGeracao, 0, 2);
      self::$arrTags['mes']=substr($dtaGeracao, 3, 2);
      self::$arrTags['ano']=substr($dtaGeracao, 6, 4);
      self::$arrTags['mes_extenso']=strtolower(InfraData::descreverMes(substr($dtaGeracao, 3, 2)));
    }

    $strConteudo=preg_replace_callback('/@([a-zA-Z0-9_-]+)@/',function($match) use ($parObjEditorDTO,$bolTesteModelo) {
      if (isset(self::$arrTags[$match[1]])) return self::$arrTags[$match[1]];
      $this->obterParametros($parObjEditorDTO, $match[1],$bolTesteModelo);
      if (isset(self::$arrTags[$match[1]])) {
        return self::$arrTags[$match[1]];
      }
      return $match[0];
    },$strConteudo);
    return $strConteudo;
  }

  /**
   * Processa a substituição de uma única tag, processamento para o ajax do editor
   * @param EditorDTO $parObjEditorDTO
   * @return string
   * @throws InfraException
   */
  public function processarTag(EditorDTO $parObjEditorDTO){

    $tag=$parObjEditorDTO->getStrNomeTag();
    $this->obterParametros($parObjEditorDTO,$tag);

    if (isset(self::$arrTags[$tag])) {
      return self::$arrTags[$tag];
    }
    return $tag;
  }
  public function obterListaTags(){
    global $SEI_MODULOS;

    $arr=array('processo',
      'tipo_processo',
      'especificacao_processo',
      'codigo_barras_processo',
      'documento',
      'codigo_barras_documento',
      'descricao_documento',
      'serie',
      'numeracao_serie',
      'dia',
      'mes',
      'ano',
      'mes_extenso',
      'observacao_documento',
      'observacao_processo',
      'sigla_usuario',
      'nome_usuario',
      'nome_autor',
      'cargo_usuario',
      'email_usuario',
      'sigla_orgao_origem',
      'descricao_orgao_origem',
      'descricao_orgao_maiusculas',
      'artigo_orgao_minuscula',
      'artigo_orgao_maiuscula',
      'cnpj_orgao',
      'endereco_orgao',
      'cep_orgao',
      'sigla_uf_orgao',
      'hifen_bairro_orgao',
      'complemento_endereco_orgao',
      'cidade_orgao',
      'timbre_orgao',
      'endereco_unidade',
      'telefone_unidade',
      'telefone_fixo_unidade',
      'telefone_comercial_unidade',
      'telefone_celular_unidade',
      'cep_unidade',
      'sigla_uf_unidade',
      'hifen_bairro_unidade',
      'cidade_unidade',
      'hifen_sitio_internet_orgao',
      'complemento_endereco_unidade',
      'sigla_unidade',
      'descricao_unidade',
      'descricao_unidade_maiusculas',
      'observacao_unidade',
      'email_unidade',
      'email_unidade_2',
      'email_unidade_3',
      'hierarquia_unidade',
      'hierarquia_unidade_invertida',
      'hierarquia_unidade_descricao_quebra_linha',
      'hierarquia_unidade_invertida_descricao_quebra_linha',
      'hierarquia_unidade_raiz_sigla',
      'hierarquia_unidade_raiz_descricao',
      'hierarquia_unidade_superior_sigla',
      'hierarquia_unidade_superior_descricao',

      'destinatarios',
      'destinatarios_virgula_espaco',
      'destinatarios_virgula_espaco_maiusculas',
      'destinatarios_quebra_linha',
      'destinatarios_quebra_linha_maiusculas',

      'artigo_destinatario_minuscula',
      'artigo_destinatario_maiuscula',
      'nome_destinatario',
      'nome_destinatario_maiusculas',
      'tratamento_destinatario',
      'vocativo_destinatario',
      'cargo_destinatario',
      'origem_destinatario',
      'nome_contexto_destinatario',
      'nome_pessoa_juridica_associada_destinatario',
      'endereco_destinatario',
      'complemento_endereco_destinatario',
      'bairro_destinatario',
      'cidade_destinatario',
      'cep_destinatario',
      'hifen_uf_destinatario',
      'sigla_uf_destinatario',
      'pais_destinatario',
      'cpf_destinatario',
      'cnpj_destinatario',
      'cnpj_pessoa_juridica_associada_destinatario',
      'sitio_internet_destinatario',
      'rg_destinatario',
      'orgao_expedidor_rg_destinatario',
      'matricula_destinatario',
      'matricula_oab_destinatario',
      'email_destinatario',
      'telefone_fixo_destinatario',
      'telefone_residencial_destinatario',
      'telefone_comercial_destinatario',
      'telefone_celular_destinatario',
      'numero_passaporte_destinatario',
      'pais_passaporte_destinatario',
      'titulo_destinatario',
      'titulo_abreviatura_destinatario',
      'funcao_destinatario',
      'categoria_destinatario',

      'artigo_destinatario_2_minuscula',
      'artigo_destinatario_2_maiuscula',
      'nome_destinatario_2',
      'nome_destinatario_2_maiusculas',
      'tratamento_destinatario_2',
      'vocativo_destinatario_2',
      'cargo_destinatario_2',
      'origem_destinatario_2',
      'nome_contexto_destinatario_2',
      'nome_pessoa_juridica_associada_destinatario_2',
      'endereco_destinatario_2',
      'complemento_endereco_destinatario_2',
      'bairro_destinatario_2',
      'cidade_destinatario_2',
      'cep_destinatario_2',
      'hifen_uf_destinatario_2',
      'sigla_uf_destinatario_2',
      'pais_destinatario_2',
      'cpf_destinatario_2',
      'cnpj_destinatario_2',
      'cnpj_pessoa_juridica_associada_destinatario_2',
      'sitio_internet_destinatario_2',
      'rg_destinatario_2',
      'orgao_expedidor_rg_destinatario_2',
      'matricula_destinatario_2',
      'matricula_oab_destinatario_2',
      'email_destinatario_2',
      'telefone_fixo_destinatario_2',
      'telefone_residencial_destinatario_2',
      'telefone_comercial_destinatario_2',
      'telefone_celular_destinatario_2',
      'numero_passaporte_destinatario_2',
      'pais_passaporte_destinatario_2',
      'titulo_destinatario_2',
      'titulo_abreviatura_destinatario_2',
      'funcao_destinatario_2',
      'categoria_destinatario_2',

      'artigo_destinatario_3_minuscula',
      'artigo_destinatario_3_maiuscula',
      'nome_destinatario_3',
      'nome_destinatario_3_maiusculas',
      'tratamento_destinatario_3',
      'vocativo_destinatario_3',
      'cargo_destinatario_3',
      'origem_destinatario_3',
      'nome_contexto_destinatario_3',
      'nome_pessoa_juridica_associada_destinatario_3',
      'endereco_destinatario_3',
      'complemento_endereco_destinatario_3',
      'bairro_destinatario_3',
      'cidade_destinatario_3',
      'cep_destinatario_3',
      'hifen_uf_destinatario_3',
      'sigla_uf_destinatario_3',
      'pais_destinatario_3',
      'cpf_destinatario_3',
      'cnpj_destinatario_3',
      'cnpj_pessoa_juridica_associada_destinatario_3',
      'sitio_internet_destinatario_3',
      'rg_destinatario_3',
      'orgao_expedidor_rg_destinatario_3',
      'matricula_destinatario_3',
      'matricula_oab_destinatario_3',
      'email_destinatario_3',
      'telefone_fixo_destinatario_3',
      'telefone_residencial_destinatario_3',
      'telefone_comercial_destinatario_3',
      'telefone_celular_destinatario_3',
      'numero_passaporte_destinatario_3',
      'pais_passaporte_destinatario_3',
      'titulo_destinatario_3',
      'titulo_abreviatura_destinatario_3',
      'funcao_destinatario_3',
      'categoria_destinatario_3',

      'interessados',
      'interessados_virgula_espaco',
      'interessados_virgula_espaco_maiusculas',
      'interessados_quebra_linha',
      'interessados_quebra_linha_maiusculas',

      'artigo_interessado_minuscula',
      'artigo_interessado_maiuscula',
      'nome_interessado',
      'nome_interessado_maiusculas',
      'tratamento_interessado',
      'vocativo_interessado',
      'cargo_interessado',
      'origem_interessado',
      'nome_contexto_interessado',
      'nome_pessoa_juridica_associada_interessado',
      'endereco_interessado',
      'complemento_endereco_interessado',
      'bairro_interessado',
      'cidade_interessado',
      'cep_interessado',
      'hifen_uf_interessado',
      'sigla_uf_interessado',
      'pais_interessado',
      'cpf_interessado',
      'cnpj_interessado',
      'cnpj_pessoa_juridica_associada_interessado',
      'sitio_internet_interessado',
      'rg_interessado',
      'orgao_expedidor_rg_interessado',
      'matricula_interessado',
      'matricula_oab_interessado',
      'email_interessado',
      'telefone_fixo_interessado',
      'telefone_residencial_interessado',
      'telefone_comercial_interessado',
      'telefone_celular_interessado',
      'numero_passaporte_interessado',
      'pais_passaporte_interessado',
      'titulo_interessado',
      'titulo_abreviatura_interessado',
      'funcao_interessado',
      'categoria_interessado',

      'artigo_interessado_2_minuscula',
      'artigo_interessado_2_maiuscula',
      'nome_interessado_2',
      'nome_interessado_2_maiusculas',
      'tratamento_interessado_2',
      'vocativo_interessado_2',
      'cargo_interessado_2',
      'origem_interessado_2',
      'nome_contexto_interessado_2',
      'nome_pessoa_juridica_associada_interessado_2',
      'endereco_interessado_2',
      'complemento_endereco_interessado_2',
      'bairro_interessado_2',
      'cidade_interessado_2',
      'cep_interessado_2',
      'hifen_uf_interessado_2',
      'sigla_uf_interessado_2',
      'pais_interessado_2',
      'cpf_interessado_2',
      'cnpj_interessado_2',
      'cnpj_pessoa_juridica_associada_interessado_2',
      'sitio_internet_interessado_2',
      'rg_interessado_2',
      'orgao_expedidor_rg_interessado_2',
      'matricula_interessado_2',
      'matricula_oab_interessado_2',
      'email_interessado_2',
      'telefone_fixo_interessado_2',
      'telefone_residencial_interessado_2',
      'telefone_comercial_interessado_2',
      'telefone_celular_interessado_2',
      'numero_passaporte_interessado_2',
      'pais_passaporte_interessado_2',
      'titulo_interessado_2',
      'titulo_abreviatura_interessado_2',
      'funcao_interessado_2',
      'categoria_interessado_2',

      'artigo_interessado_3_minuscula',
      'artigo_interessado_3_maiuscula',
      'nome_interessado_3',
      'nome_interessado_3_maiusculas',
      'tratamento_interessado_3',
      'vocativo_interessado_3',
      'cargo_interessado_3',
      'origem_interessado_3',
      'nome_contexto_interessado_3',
      'nome_pessoa_juridica_associada_interessado_3',
      'endereco_interessado_3',
      'complemento_endereco_interessado_3',
      'bairro_interessado_3',
      'cidade_interessado_3',
      'cep_interessado_3',
      'hifen_uf_interessado_3',
      'sigla_uf_interessado_3',
      'pais_interessado_3',
      'cpf_interessado_3',
      'cnpj_interessado_3',
      'cnpj_pessoa_juridica_associada_interessado_3',
      'sitio_internet_interessado_3',
      'rg_interessado_3',
      'orgao_expedidor_rg_interessado_3',
      'matricula_interessado_3',
      'matricula_oab_interessado_3',
      'email_interessado_3',
      'telefone_fixo_interessado_3',
      'telefone_residencial_interessado_3',
      'telefone_comercial_interessado_3',
      'telefone_celular_interessado_3',
      'numero_passaporte_interessado_3',
      'pais_passaporte_interessado_3',
      'titulo_interessado_3',
      'titulo_abreviatura_interessado_3',
      'funcao_interessado_3',
      'categoria_interessado_3',

      'link_acesso_externo_processo'
    );

    $arr=array_fill_keys($arr,'');

    foreach($SEI_MODULOS as $seiModulo){
      if (($arrVariaveisModulo = $seiModulo->executar('obterRelacaoVariaveisEditor'))!=null){
        foreach ($arrVariaveisModulo as $chave=>$valor) {
          $arr[$chave]=$valor;
        }
      }
    }
    return $arr;
  }
  private function obterParametros(EditorDTO $parObjEditorDTO,$parStrTag,$bolTesteModelo=false){

    global $SEI_MODULOS;

    $arrTags=$this->obterListaTags();
    if(!isset($arrTags[$parStrTag])){
      return;
    }

    $bolDocumento=!isset(self::$arrTags['#numIdUnidadeResponsavel']);
    $bolOrgao = false;
    $bolUnidade=false;
    $bolEmailUnidade=false;
    $bolUsuario=false;
    $bolHierarquia=false;
    $bolParticipante=false;
    $bolModulo=false;

    switch ($parStrTag){
      case 'processo':
      case 'tipo_processo':
      case 'especificacao_processo':
      case 'codigo_barras_processo':
      case 'documento':
      case 'codigo_barras_documento':
      case 'descricao_documento':
      case 'serie':
      case 'numeracao_serie':
      case 'dia':
      case 'mes':
      case 'ano':
      case 'mes_extenso':
      case 'observacao_documento':
      case 'observacao_processo':
        $bolDocumento=true;
        break;

      case 'sigla_usuario':
      case 'nome_usuario':
      case 'nome_autor':
      case 'cargo_usuario':
      case 'email_usuario':
        $bolUsuario=!isset(self::$arrTags['sigla_usuario']);
        break;

      case 'artigo_orgao_minuscula':
      case 'artigo_orgao_maiuscula':
      case 'cnpj_orgao':
      case 'endereco_orgao':
      case 'cep_orgao':
      case 'sigla_uf_orgao':
      case 'hifen_bairro_orgao':
      case 'cidade_orgao':
      case 'complemento_endereco_orgao':
        $bolOrgao=!isset(self::$arrTags['artigo_orgao_minuscula']);
        break;

      case 'sigla_orgao_origem':
      case 'descricao_orgao_origem':
      case 'descricao_orgao_maiusculas':
      case 'timbre_orgao':
      case 'endereco_unidade':
      case 'telefone_unidade':
      case 'telefone_fixo_unidade':
      case 'telefone_comercial_unidade':
      case 'telefone_celular_unidade':
      case 'cep_unidade':
      case 'sigla_uf_unidade':
      case 'hifen_bairro_unidade':
      case 'cidade_unidade':
      case 'hifen_sitio_internet_orgao':
      case 'complemento_endereco_unidade':
      case 'sigla_unidade':
      case 'descricao_unidade':
      case 'descricao_unidade_maiusculas':
      case 'observacao_unidade':
        $bolUnidade=!isset(self::$arrTags['sigla_orgao_origem']);
        break;

      case 'email_unidade':
      case 'email_unidade_2':
      case 'email_unidade_3':
          $bolEmailUnidade = !isset(self::$arrTags['email_unidade']);

      case 'hierarquia_unidade':
      case 'hierarquia_unidade_invertida':
      case 'hierarquia_unidade_descricao_quebra_linha':
      case 'hierarquia_unidade_invertida_descricao_quebra_linha':
      case 'hierarquia_unidade_raiz_sigla':
      case 'hierarquia_unidade_raiz_descricao':
      case 'hierarquia_unidade_superior_sigla':
      case 'hierarquia_unidade_superior_descricao':
        $bolUnidade=!isset(self::$arrTags['sigla_orgao_origem']);
        $bolHierarquia=!isset(self::$arrTags['hierarquia_unidade']);
        break;

      case 'destinatarios':
      case 'destinatarios_virgula_espaco':
      case 'destinatarios_virgula_espaco_maiusculas':
      case 'destinatarios_quebra_linha':
      case 'destinatarios_quebra_linha_maiusculas':

      case 'artigo_destinatario_minuscula':
      case 'artigo_destinatario_maiuscula':
      case 'nome_destinatario':
      case 'nome_destinatario_maiusculas':
      case 'tratamento_destinatario':
      case 'vocativo_destinatario':
      case 'cargo_destinatario':
      case 'origem_destinatario':
      case 'nome_contexto_destinatario':
      case 'nome_pessoa_juridica_associada_destinatario':
      case 'endereco_destinatario':
      case 'complemento_endereco_destinatario':
      case 'bairro_destinatario':
      case 'cidade_destinatario':
      case 'cep_destinatario':
      case 'hifen_uf_destinatario':
      case 'sigla_uf_destinatario':
      case 'pais_destinatario':
      case 'cpf_destinatario':
      case 'cnpj_destinatario':
      case 'cnpj_pessoa_juridica_associada_destinatario':
      case 'sitio_internet_destinatario':
      case 'rg_destinatario':
      case 'orgao_expedidor_rg_destinatario':
      case 'matricula_destinatario':
      case 'matricula_oab_destinatario':
      case 'email_destinatario':
      case 'telefone_fixo_destinatario':
      case 'telefone_residencial_destinatario':
      case 'telefone_comercial_destinatario':
      case 'telefone_celular_destinatario':
      case 'numero_passaporte_destinatario':
      case 'pais_passaporte_destinatario':
      case 'titulo_abreviatura_destinatario':
      case 'titulo_destinatario':
      case 'funcao_destinatario':
      case 'categoria_destinatario':

      case 'artigo_destinatario_2_minuscula':
      case 'artigo_destinatario_2_maiuscula':
      case 'nome_destinatario_2':
      case 'nome_destinatario_2_maiusculas':
      case 'tratamento_destinatario_2':
      case 'vocativo_destinatario_2':
      case 'cargo_destinatario_2':
      case 'origem_destinatario_2':
      case 'nome_contexto_destinatario_2':
      case 'nome_pessoa_juridica_associada_destinatario_2':
      case 'endereco_destinatario_2':
      case 'complemento_endereco_destinatario_2':
      case 'bairro_destinatario_2':
      case 'cidade_destinatario_2':
      case 'cep_destinatario_2':
      case 'hifen_uf_destinatario_2':
      case 'sigla_uf_destinatario_2':
      case 'pais_destinatario_2':
      case 'cpf_destinatario_2':
      case 'cnpj_destinatario_2':
      case 'cnpj_pessoa_juridica_associada_destinatario_2':
      case 'sitio_internet_destinatario_2':
      case 'rg_destinatario_2':
      case 'orgao_expedidor_rg_destinatario_2':
      case 'matricula_destinatario_2':
      case 'matricula_oab_destinatario_2':
      case 'email_destinatario_2':
      case 'telefone_fixo_destinatario_2':
      case 'telefone_residencial_destinatario_2':
      case 'telefone_comercial_destinatario_2':
      case 'telefone_celular_destinatario_2':
      case 'numero_passaporte_destinatario_2':
      case 'pais_passaporte_destinatario_2':
      case 'titulo_abreviatura_destinatario_2':
      case 'titulo_destinatario_2':
      case 'funcao_destinatario_2':
      case 'categoria_destinatario_2':

      case 'artigo_destinatario_3_minuscula':
      case 'artigo_destinatario_3_maiuscula':
      case 'nome_destinatario_3':
      case 'nome_destinatario_3_maiusculas':
      case 'tratamento_destinatario_3':
      case 'vocativo_destinatario_3':
      case 'cargo_destinatario_3':
      case 'origem_destinatario_3':
      case 'nome_contexto_destinatario_3':
      case 'nome_pessoa_juridica_associada_destinatario_3':
      case 'endereco_destinatario_3':
      case 'complemento_endereco_destinatario_3':
      case 'bairro_destinatario_3':
      case 'cidade_destinatario_3':
      case 'cep_destinatario_3':
      case 'hifen_uf_destinatario_3':
      case 'sigla_uf_destinatario_3':
      case 'pais_destinatario_3':
      case 'cpf_destinatario_3':
      case 'cnpj_destinatario_3':
      case 'cnpj_pessoa_juridica_associada_destinatario_3':
      case 'sitio_internet_destinatario_3':
      case 'rg_destinatario_3':
      case 'orgao_expedidor_rg_destinatario_3':
      case 'matricula_destinatario_3':
      case 'matricula_oab_destinatario_3':
      case 'email_destinatario_3':
      case 'telefone_fixo_destinatario_3':
      case 'telefone_residencial_destinatario_3':
      case 'telefone_comercial_destinatario_3':
      case 'telefone_celular_destinatario_3':
      case 'numero_passaporte_destinatario_3':
      case 'pais_passaporte_destinatario_3':
      case 'titulo_abreviatura_destinatario_3':
      case 'titulo_destinatario_3':
      case 'funcao_destinatario_3':
      case 'categoria_destinatario_3':

      case 'interessados':
      case 'interessados_virgula_espaco':
      case 'interessados_virgula_espaco_maiusculas':
      case 'interessados_quebra_linha':
      case 'interessados_quebra_linha_maiusculas':

      case 'artigo_interessado_minuscula':
      case 'artigo_interessado_maiuscula':
      case 'nome_interessado':
      case 'nome_interessado_maiusculas':
      case 'tratamento_interessado':
      case 'vocativo_interessado':
      case 'cargo_interessado':
      case 'origem_interessado':
      case 'nome_contexto_interessado':
      case 'nome_pessoa_juridica_associada_interessado':
      case 'endereco_interessado':
      case 'complemento_endereco_interessado':
      case 'bairro_interessado':
      case 'cidade_interessado':
      case 'cep_interessado':
      case 'hifen_uf_interessado':
      case 'sigla_uf_interessado':
      case 'pais_interessado':
      case 'cpf_interessado':
      case 'cnpj_interessado':
      case 'cnpj_pessoa_juridica_associada_interessado':
      case 'sitio_internet_interessado':
      case 'rg_interessado':
      case 'orgao_expedidor_rg_interessado':
      case 'matricula_interessado':
      case 'matricula_oab_interessado':
      case 'email_interessado':
      case 'telefone_fixo_interessado':
      case 'telefone_residencial_interessado':
      case 'telefone_comercial_interessado':
      case 'telefone_celular_interessado':
      case 'numero_passaporte_interessado':
      case 'pais_passaporte_interessado':
      case 'titulo_interessado':
      case 'titulo_abreviatura_interessado':
      case 'funcao_interessado':
      case 'categoria_interessado':

      case 'artigo_interessado_2_minuscula':
      case 'artigo_interessado_2_maiuscula':
      case 'nome_interessado_2':
      case 'nome_interessado_2_maiusculas':
      case 'tratamento_interessado_2':
      case 'vocativo_interessado_2':
      case 'cargo_interessado_2':
      case 'origem_interessado_2':
      case 'nome_contexto_interessado_2':
      case 'nome_pessoa_juridica_associada_interessado_2':
      case 'endereco_interessado_2':
      case 'complemento_endereco_interessado_2':
      case 'bairro_interessado_2':
      case 'cidade_interessado_2':
      case 'cep_interessado_2':
      case 'hifen_uf_interessado_2':
      case 'sigla_uf_interessado_2':
      case 'pais_interessado_2':
      case 'cpf_interessado_2':
      case 'cnpj_interessado_2':
      case 'cnpj_pessoa_juridica_associada_interessado_2':
      case 'sitio_internet_interessado_2':
      case 'rg_interessado_2':
      case 'orgao_expedidor_rg_interessado_2':
      case 'matricula_interessado_2':
      case 'matricula_oab_interessado_2':
      case 'email_interessado_2':
      case 'telefone_fixo_interessado_2':
      case 'telefone_residencial_interessado_2':
      case 'telefone_comercial_interessado_2':
      case 'telefone_celular_interessado_2':
      case 'numero_passaporte_interessado_2':
      case 'pais_passaporte_interessado_2':
      case 'titulo_interessado_2':
      case 'titulo_abreviatura_interessado_2':
      case 'funcao_interessado_2':
      case 'categoria_interessado_2':

      case 'artigo_interessado_3_minuscula':
      case 'artigo_interessado_3_maiuscula':
      case 'nome_interessado_3':
      case 'nome_interessado_3_maiusculas':
      case 'tratamento_interessado_3':
      case 'vocativo_interessado_3':
      case 'cargo_interessado_3':
      case 'origem_interessado_3':
      case 'nome_contexto_interessado_3':
      case 'nome_pessoa_juridica_associada_interessado_3':
      case 'endereco_interessado_3':
      case 'complemento_endereco_interessado_3':
      case 'bairro_interessado_3':
      case 'cidade_interessado_3':
      case 'cep_interessado_3':
      case 'hifen_uf_interessado_3':
      case 'sigla_uf_interessado_3':
      case 'pais_interessado_3':
      case 'cpf_interessado_3':
      case 'cnpj_interessado_3':
      case 'cnpj_pessoa_juridica_associada_interessado_3':
      case 'sitio_internet_interessado_3':
      case 'rg_interessado_3':
      case 'orgao_expedidor_rg_interessado_3':
      case 'matricula_interessado_3':
      case 'matricula_oab_interessado_3':
      case 'email_interessado_3':
      case 'telefone_fixo_interessado_3':
      case 'telefone_residencial_interessado_3':
      case 'telefone_comercial_interessado_3':
      case 'telefone_celular_interessado_3':
      case 'numero_passaporte_interessado_3':
      case 'pais_passaporte_interessado_3':
      case 'titulo_interessado_3':
      case 'titulo_abreviatura_interessado_3':
      case 'funcao_interessado_3':
      case 'categoria_interessado_3':

        $bolParticipante=!(isset(self::$arrTags['interessados_virgula_espaco'])||isset(self::$arrTags['destinatarios_virgula_espaco']));
        break;
      case 'link_acesso_externo_processo':

        if($bolTesteModelo){
          break;
        }
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
        self::$arrTags[$parStrTag] = $this->recuperarLinkAcessoExterno($objDocumentoDTO);
        break;
      default:
        if(preg_match(EditorRN::$REGEXP_VARIAVEL_EDITOR,$parStrTag)===1){
          $bolModulo=true;
          break;
        }
        return;
    }
    try {

      $objInfraException = new InfraException();

      if ($bolDocumento && !$bolTesteModelo){
        if ($parObjEditorDTO->getDblIdDocumento()!=null) {

          $objDocumentoDTO = new DocumentoDTO();
          $objDocumentoDTO->retDblIdDocumento();
          $objDocumentoDTO->retDblIdProcedimento();
          $objDocumentoDTO->retDblIdDocumentoEdoc();
          $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
          $objDocumentoDTO->retStrEspecificacaoProcedimento();
          $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
          $objDocumentoDTO->retStrNomeTipoProcedimentoProcedimento();
          $objDocumentoDTO->retStrNomeSerie();
          $objDocumentoDTO->retNumIdModeloSerie();
          $objDocumentoDTO->retNumIdModeloEdocSerie();
          $objDocumentoDTO->retNumIdUnidadeResponsavel();
          $objDocumentoDTO->retNumIdUsuarioGeradorProtocolo();
          $objDocumentoDTO->retDtaGeracaoProtocolo();
          $objDocumentoDTO->retStrNumero();
          $objDocumentoDTO->retStrDescricaoProtocolo();
          $objDocumentoDTO->retStrCodigoBarrasProcedimento();
          $objDocumentoDTO->retStrCodigoBarrasDocumento();

          $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());

          $objDocumentoRN = new DocumentoRN();
          $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

          if ($objDocumentoDTO==null) {
            $objInfraException->lancarValidacao('Documento não encontrado.');
          }

          self::$arrTags['#dblIdProcedimento']=$objDocumentoDTO->getDblIdProcedimento();
          self::$arrTags['processo']=$objDocumentoDTO->getStrProtocoloProcedimentoFormatado();
          self::$arrTags['tipo_processo']= $objDocumentoDTO->getStrNomeTipoProcedimentoProcedimento();
          self::$arrTags['especificacao_processo']= $objDocumentoDTO->getStrEspecificacaoProcedimento();
          self::$arrTags['codigo_barras_processo']= '<img alt="Código de Barras do Processo" src="data:image/png;base64,' . $objDocumentoDTO->getStrCodigoBarrasProcedimento() . '" />';
          self::$arrTags['documento']= $objDocumentoDTO->getStrProtocoloDocumentoFormatado();
          self::$arrTags['codigo_barras_documento']='<img alt="Código de Barras do Documento" src="data:image/png;base64,' . $objDocumentoDTO->getStrCodigoBarrasDocumento() . '" />';
          self::$arrTags['descricao_documento']=$objDocumentoDTO->getStrDescricaoProtocolo();
          self::$arrTags['serie']=$objDocumentoDTO->getStrNomeSerie();

          if (!InfraString::isBolVazia($objDocumentoDTO->getStrNumero())) {
            self::$arrTags['numeracao_serie']= $objDocumentoDTO->getStrNumero();
          } else {
            self::$arrTags['numeracao_serie']=$objDocumentoDTO->getStrProtocoloDocumentoFormatado();
          }
          ///////////////////////////////
          /// variaveis observacao_processo e observacao_documento
          $objObservacaoRN = new ObservacaoRN();

          $objObservacaoDTO_Processo = new ObservacaoDTO();
          $objObservacaoDTO_Processo->retStrDescricao();
          $objObservacaoDTO_Processo->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
          $objObservacaoDTO_Processo->setNumIdUnidade($objDocumentoDTO->getNumIdUnidadeResponsavel());

          $objObservacaoDTO_Processo  = $objObservacaoRN->consultarRN0221($objObservacaoDTO_Processo );

          $objObservacaoDTO_Documento = new ObservacaoDTO();
          $objObservacaoDTO_Documento->retStrDescricao();
          $objObservacaoDTO_Documento->setDblIdProtocolo($parObjEditorDTO->getDblIdDocumento());
          $objObservacaoDTO_Documento->setNumIdUnidade($objDocumentoDTO->getNumIdUnidadeResponsavel());

          $objObservacaoDTO_Documento  = $objObservacaoRN->consultarRN0221($objObservacaoDTO_Documento );

          if ($objObservacaoDTO_Processo!=null) {
            self::$arrTags['observacao_processo'] = $objObservacaoDTO_Processo->getStrDescricao();
          }
          if ($objObservacaoDTO_Documento!=null) {
            self::$arrTags['observacao_documento'] = $objObservacaoDTO_Documento->getStrDescricao();
          }
          ///////////////////////////////
          $numIdUnidadeResponsavel = $objDocumentoDTO->getNumIdUnidadeResponsavel();
          $numIdUsuarioGerador = $objDocumentoDTO->getNumIdUsuarioGeradorProtocolo();
          $dtaGeracao = $objDocumentoDTO->getDtaGeracaoProtocolo();

        } else if ($parObjEditorDTO->getNumIdBaseConhecimento()!=null) {

          $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
          $objBaseConhecimentoDTO->retNumIdUnidade();
          $objBaseConhecimentoDTO->retNumIdUsuarioGerador();
          $objBaseConhecimentoDTO->retDthGeracao();
          $objBaseConhecimentoDTO->setNumIdBaseConhecimento($parObjEditorDTO->getNumIdBaseConhecimento());

          $objBaseConhecimentoRN = new BaseConhecimentoRN();
          $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);

          $numIdUnidadeResponsavel = $objBaseConhecimentoDTO->getNumIdUnidade();
          $numIdUsuarioGerador = $objBaseConhecimentoDTO->getNumIdUsuarioGerador();
          $dtaGeracao = substr($objBaseConhecimentoDTO->getDthGeracao(), 0, 10);
        }

        self::$arrTags['#numIdUnidadeResponsavel']=$numIdUnidadeResponsavel;
        self::$arrTags['#numIdUsuarioGerador']=$numIdUsuarioGerador;
        self::$arrTags['#dtaGeracao']=$dtaGeracao;
        //usa data de geracao do protocolo, nas republicacoes, retificações, apostilamentos de atos, portarias... deve manter a data do original
        //para os outros casos o uso da data de geracao do protocolo ou do dia atual não faz diferença já que são iguais
        self::$arrTags['dia']=substr($dtaGeracao, 0, 2);
        self::$arrTags['mes']=substr($dtaGeracao, 3, 2);
        self::$arrTags['ano']=substr($dtaGeracao, 6, 4);
        self::$arrTags['mes_extenso']=strtolower(InfraData::descreverMes(substr($dtaGeracao, 3, 2)));
      }

        if ($bolOrgao) {

            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->setBolExclusaoLogica(false);
            $objContatoDTO->retStrStaGenero();
            $objContatoDTO->retStrEndereco();
            $objContatoDTO->retStrComplemento();
            $objContatoDTO->retStrBairro();
            $objContatoDTO->retStrCep();
            $objContatoDTO->retStrNomeCidade();
            $objContatoDTO->retStrSiglaUf();
            $objContatoDTO->retStrObservacao();
            $objContatoDTO->retDblCnpj();

            if (isset(self::$arrTags['#numIdContatoOrgao'])){
                $objContatoDTO->setNumIdContato(self::$arrTags['#numIdContatoOrgao']);
            }else{
                $objUnidadeDTO = new UnidadeDTO();
                $objUnidadeDTO->retNumIdContatoOrgao();
                $objUnidadeDTO->setNumIdUnidade(self::$arrTags['#numIdUnidadeResponsavel']);

                $objUnidadeRN = new UnidadeRN();
                $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

                if ($objUnidadeDTO==null){
                    throw new InfraException('Unidade não encontrada.');
                }
                $objContatoDTO->setNumIdContato($objUnidadeDTO->getNumIdContato());
            }

            $objContatoRN = new ContatoRN();
            $objContatoDTOOrgao = $objContatoRN->consultarRN0324($objContatoDTO);

            if ($objContatoDTOOrgao == null) {
                throw new InfraException('Contato associado com o órgão da unidade não encontrado.');
            }

            if ($objContatoDTOOrgao->getStrStaGenero() == ContatoRN::$TG_MASCULINO) {
                self::$arrTags['artigo_orgao_minuscula'] = 'o';
                self::$arrTags['artigo_orgao_maiuscula'] = 'O';
            } else {
                if ($objContatoDTOOrgao->getStrStaGenero() == ContatoRN::$TG_FEMININO) {
                    self::$arrTags['artigo_orgao_minuscula'] = 'a';
                    self::$arrTags['artigo_orgao_maiuscula'] = 'A';
                }
            }

            self::$arrTags['cnpj_orgao'] = InfraUtil::formatarCnpj($objContatoDTOOrgao->getDblCnpj());
            self::$arrTags['endereco_orgao'] = $objContatoDTOOrgao->getStrEndereco();
            self::$arrTags['cep_orgao'] = 'CEP '.$objContatoDTOOrgao->getStrCep();
            self::$arrTags['sigla_uf_orgao'] = $objContatoDTOOrgao->getStrSiglaUf();

            $strTag = '';
            if (!InfraString::isBolVazia($objContatoDTOOrgao->getStrBairro())) {
                $strTag = ' - Bairro '.$objContatoDTOOrgao->getStrBairro();
            }
            self::$arrTags['hifen_bairro_orgao'] = $strTag;

            if ($objContatoDTOOrgao->getStrNomeCidade() != '') {
                self::$arrTags['cidade_orgao'] = $objContatoDTOOrgao->getStrNomeCidade();
            }

            $strTag = '';
            if (!InfraString::isBolVazia($objContatoDTOOrgao->getStrComplemento())) {
                $strTag .= $objContatoDTOOrgao->getStrComplemento();
            }
            self::$arrTags['complemento_endereco_orgao'] = $strTag;
        }

      if ($bolUnidade) {
        /* Unidade Responsável ************************************************************************************/
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdOrgao();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->retNumIdContato();
        $objUnidadeDTO->retNumIdContatoOrgao();
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrDescricao();
        $objUnidadeDTO->retStrSiglaOrgao();
        $objUnidadeDTO->retStrDescricaoOrgao();
        $objUnidadeDTO->retStrTimbreOrgao();
        $objUnidadeDTO->retStrSitioInternetOrgaoContato();

        //$objUnidadeDTO->retStrEnderecoContato();
        //$objUnidadeDTO->retStrComplementoContato();
        //$objUnidadeDTO->retStrNomeCidadeContato();
        //$objUnidadeDTO->retStrBairroContato();
        //$objUnidadeDTO->retStrTelefoneFixoContato();
        //$objUnidadeDTO->retStrTelefoneCelularContato();
        //$objUnidadeDTO->retStrCepContato();
        //$objUnidadeDTO->retStrSiglaUfContato();


        $objUnidadeDTO->setNumIdUnidade(self::$arrTags['#numIdUnidadeResponsavel']);

        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

        if ($objUnidadeDTO==null){
          throw new InfraException('Unidade não encontrada.');
        }

        self::$arrTags['#numIdContatoOrgao'] = $objUnidadeDTO->getNumIdContatoOrgao();

        self::$arrTags['sigla_unidade'] = InfraString::transformarCaixaAlta($objUnidadeDTO->getStrSigla());
        self::$arrTags['sigla_orgao_origem'] = $objUnidadeDTO->getStrSiglaOrgao();
        self::$arrTags['descricao_unidade'] = $objUnidadeDTO->getStrDescricao();
        self::$arrTags['descricao_unidade_maiusculas'] = InfraString::transformarCaixaAlta($objUnidadeDTO->getStrDescricao());
        self::$arrTags['descricao_orgao_origem'] = $objUnidadeDTO->getStrDescricaoOrgao();
        self::$arrTags['descricao_orgao_maiusculas'] = InfraString::transformarCaixaAlta($objUnidadeDTO->getStrDescricaoOrgao());
        self::$arrTags['timbre_orgao'] = '<img alt="Timbre" src="data:image/png;base64,'.$objUnidadeDTO->getStrTimbreOrgao().'" />';

        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setBolExclusaoLogica(false);
        $objContatoDTO->retStrTelefoneComercial();
        $objContatoDTO->retStrTelefoneResidencial();
        $objContatoDTO->retStrTelefoneCelular();
        $objContatoDTO->retStrSitioInternet();
        $objContatoDTO->retStrObservacao();
        $objContatoDTO->setNumIdContato($objUnidadeDTO->getNumIdContato());

        $objContatoRN = new ContatoRN();
        $arrObjContatoDTOUnidade = $objContatoRN->listarComEndereco($objContatoDTO);

        if (count($arrObjContatoDTOUnidade)==0){
          throw new InfraException('Contato associado com a unidade não encontrado.');
        }

        $objContatoDTOUnidade = $arrObjContatoDTOUnidade[0];

        self::$arrTags['telefone_unidade']= $objContatoDTOUnidade->getStrTelefoneComercial();
        self::$arrTags['telefone_fixo_unidade']= $objContatoDTOUnidade->getStrTelefoneComercial();
        self::$arrTags['telefone_celular_unidade']= $objContatoDTOUnidade->getStrTelefoneCelular();
        self::$arrTags['telefone_residencial_unidade']= $objContatoDTOUnidade->getStrTelefoneResidencial();
        self::$arrTags['telefone_comercial_unidade']= $objContatoDTOUnidade->getStrTelefoneComercial();
        self::$arrTags['observacao_unidade']= nl2br($objContatoDTOUnidade->getStrObservacao());

        $strTag = '';
        if (!InfraString::isBolVazia($objUnidadeDTO->getStrSitioInternetOrgaoContato())) {
          $strTag = ' - ' . $objUnidadeDTO->getStrSitioInternetOrgaoContato();
        }
        self::$arrTags['hifen_sitio_internet_orgao']= $strTag;

        self::$arrTags['endereco_unidade'] = $objContatoDTOUnidade->getStrEndereco();

        if (InfraString::isBolVazia(self::$arrTags['endereco_unidade'])) {
          throw new InfraException('Unidade ' . $objUnidadeDTO->getStrSigla() . ' não possui endereço cadastrado.',null,null,true,InfraLog::$AVISO);
        }

        self::$arrTags['cep_unidade'] = 'CEP ' . $objContatoDTOUnidade->getStrCep();
        self::$arrTags['sigla_uf_unidade'] = $objContatoDTOUnidade->getStrSiglaUf();

        $strTag = '';
        if (!InfraString::isBolVazia($objContatoDTOUnidade->getStrBairro())) {
          $strTag = ' - Bairro ' . $objContatoDTOUnidade->getStrBairro();
        }
        self::$arrTags['hifen_bairro_unidade']= $strTag;

        if ($objContatoDTOUnidade->getStrNomeCidade() != '') {
          self::$arrTags['cidade_unidade'] = $objContatoDTOUnidade->getStrNomeCidade();
        }

        $strTag = '';
        if (!InfraString::isBolVazia($objContatoDTOUnidade->getStrComplemento())) {
          $strTag .= $objContatoDTOUnidade->getStrComplemento();
        }
        self::$arrTags['complemento_endereco_unidade']= $strTag;

      }

      if ($bolEmailUnidade){

          $objEmailUnidadeDTO = new EmailUnidadeDTO();
          $objEmailUnidadeDTO->setNumMaxRegistrosRetorno(3);
          $objEmailUnidadeDTO->retStrEmail();
          $objEmailUnidadeDTO->setNumIdUnidade(self::$arrTags['#numIdUnidadeResponsavel']);
          $objEmailUnidadeDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);
          $objEmailUnidadeDTO->setOrdStrEmail(InfraDTO::$TIPO_ORDENACAO_ASC);

          $objEmailUnidadeRN = new EmailUnidadeRN();
          $arrObjEmailUnidadeDTO = $objEmailUnidadeRN->listar($objEmailUnidadeDTO);

          if (isset($arrObjEmailUnidadeDTO[0])){
              self::$arrTags['email_unidade'] = $arrObjEmailUnidadeDTO[0]->getStrEmail();
          }

          if (isset($arrObjEmailUnidadeDTO[1])){
              self::$arrTags['email_unidade_2'] = $arrObjEmailUnidadeDTO[1]->getStrEmail();
          }

          if (isset($arrObjEmailUnidadeDTO[2])){
              self::$arrTags['email_unidade_3'] = $arrObjEmailUnidadeDTO[2]->getStrEmail();
          }
      }

      if ($bolUsuario) {
        /* Usuário Gerador ****************************************************************************************/
        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setBolExclusaoLogica(false);
        $objUsuarioDTO->retStrSigla();
        $objUsuarioDTO->retStrNome();
        $objUsuarioDTO->retStrEmailContato();
        $objUsuarioDTO->retStrExpressaoCargoContato();
        $objUsuarioDTO->setNumIdUsuario(self::$arrTags['#numIdUsuarioGerador']);

        $objUsuarioRN = new UsuarioRN();
        $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

        self::$arrTags['sigla_usuario']= $objUsuarioDTO->getStrSigla();
        self::$arrTags['nome_usuario']= $objUsuarioDTO->getStrNome();
        self::$arrTags['nome_autor']= $objUsuarioDTO->getStrNome();

        if ($objUsuarioDTO->getStrExpressaoCargoContato() != '') {
          self::$arrTags['cargo_usuario']= $objUsuarioDTO->getStrExpressaoCargoContato();
        }

        if ($objUsuarioDTO->getStrEmailContato()){
            self::$arrTags['email_usuario']= $objUsuarioDTO->getStrEmailContato();
        }

      }

      if ($bolHierarquia){
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setNumIdUnidade(self::$arrTags['#numIdUnidadeResponsavel']);
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrSiglaOrgao();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO=$objUnidadeRN->consultarRN0125($objUnidadeDTO);

        $arrDadosHierarquia = $objUnidadeRN->obterDadosHierarquia($objUnidadeDTO);

        self::$arrTags['hierarquia_unidade'] = $arrDadosHierarquia['RAMIFICACAO'];
        $arrHierarquiaUnidade = explode('/', $arrDadosHierarquia['RAMIFICACAO']);
        $strHierarquiaUnidade = '';
        for ($i = InfraArray::contar($arrHierarquiaUnidade) - 1; $i>=0; $i--) {
          if ($strHierarquiaUnidade!='') {
            $strHierarquiaUnidade .= '/';
          }
          $strHierarquiaUnidade .= $arrHierarquiaUnidade[$i];
        }
        self::$arrTags['hierarquia_unidade_invertida']= $strHierarquiaUnidade;

        self::$arrTags['hierarquia_unidade_descricao_quebra_linha']= $arrDadosHierarquia['DESCRICAO'];
        $arrHierarquiaDescricao = explode('<br />', $arrDadosHierarquia['DESCRICAO']);
        $strHierarquiaDescricao = '';
        for ($i = InfraArray::contar($arrHierarquiaDescricao) - 1; $i>=0; $i--) {
          if ($strHierarquiaDescricao!='') {
            $strHierarquiaDescricao .= '<br />';
          }
          $strHierarquiaDescricao .= $arrHierarquiaDescricao[$i];
        }
        self::$arrTags['hierarquia_unidade_invertida_descricao_quebra_linha']= $strHierarquiaDescricao;

        self::$arrTags['hierarquia_unidade_raiz_sigla']= $arrDadosHierarquia['RAIZ_SIGLA'];
        self::$arrTags['hierarquia_unidade_raiz_descricao']= $arrDadosHierarquia['RAIZ_DESCRICAO'];
        self::$arrTags['hierarquia_unidade_superior_sigla']= $arrDadosHierarquia['SUPERIOR_SIGLA'];
        self::$arrTags['hierarquia_unidade_superior_descricao']= $arrDadosHierarquia['SUPERIOR_DESCRICAO'];
      }

      if (!$bolTesteModelo && $bolModulo && $parObjEditorDTO->getDblIdDocumento()!=null) {
        $objDocumentoAPI=new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($parObjEditorDTO->getDblIdDocumento());
        $objDocumentoAPI->setIdProcedimento(self::$arrTags['#dblIdProcedimento']);

        foreach($SEI_MODULOS as $strChaveModulo => $seiModulo){
          $strTagModulo='#Modulo_'.$strChaveModulo;
          if(!isset(self::$arrTags[$strTagModulo])){
            $arrVariaveisModulo = $seiModulo->executar('obterRelacaoVariaveisEditor');
            if($arrVariaveisModulo==null){
              self::$arrTags[$strTagModulo]=true;
              continue;
            }
            if(isset($arrVariaveisModulo[$parStrTag])){
              self::$arrTags[$strTagModulo]=true;
              $arrModulo = $seiModulo->executar('processarVariaveisEditor',$objDocumentoAPI);
              if ($arrModulo!=null){
                foreach ($arrModulo as $strVariavel=>$strValor) {
                  if(isset(self::$arrTags[$strVariavel])) {
                    throw new InfraException('Tentativa de sobrescrever variavel [' . $strVariavel . '] no módulo ' . $seiModulo->getNome());
                  }
                  if(!isset($arrVariaveisModulo[$strVariavel])){
                    throw new InfraException('Variável [' . $strVariavel . '] não relacionada no módulo ' . $seiModulo->getNome());
                  }
                  if(preg_match(EditorRN::$REGEXP_VARIAVEL_EDITOR,$strVariavel)!==1){
                    throw new InfraException('Tentativa de definir variavel inválida [' . $strVariavel . '] no módulo ' . $seiModulo->getNome());
                  }
                  self::$arrTags[$strVariavel] = $strValor ?? '';
                }
              }
              break;
            }
          }
        }
      }

      if (!$bolTesteModelo && $parObjEditorDTO->getDblIdDocumento()!=null && $bolParticipante) {

        /* Participantes ******************************************************************************************/
        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retNumIdContato();
        $objParticipanteDTO->retStrStaParticipacao();
        $objParticipanteDTO->retNumSequencia();
        $objParticipanteDTO->setStrStaParticipacao(array(ParticipanteRN::$TP_INTERESSADO, ParticipanteRN::$TP_DESTINATARIO), InfraDTO::$OPER_IN);
        $objParticipanteDTO->setDblIdProtocolo($parObjEditorDTO->getDblIdDocumento());
        $objParticipanteDTO->setOrdStrStaParticipacao(InfraDTO::$TIPO_ORDENACAO_ASC);
        $objParticipanteDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objParticipanteRN = new ParticipanteRN();
        $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

        if (count($arrObjParticipanteDTO)) {

          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setBolExclusaoLogica(false);
          $objContatoDTO->retNumIdTipoContato();
          $objContatoDTO->retNumIdTipoContatoAssociado();
          $objContatoDTO->retNumIdContato();
          $objContatoDTO->retStrNome();
          $objContatoDTO->retStrNomeSocial();
          $objContatoDTO->retStrNomeRegistroCivil();
          $objContatoDTO->retStrNomeContatoAssociado();
          $objContatoDTO->retStrSigla();
          $objContatoDTO->retStrSiglaContatoAssociado();
          $objContatoDTO->retStrExpressaoCargo();
          $objContatoDTO->retStrExpressaoTratamentoCargo();
          $objContatoDTO->retStrExpressaoVocativoCargo();
          $objContatoDTO->retStrAbreviaturaTituloContato();
          $objContatoDTO->retStrExpressaoTituloContato();
          $objContatoDTO->retStrFuncao();
          $objContatoDTO->retStrNomeCategoria();
          $objContatoDTO->retStrStaGenero();
          $objContatoDTO->retDblCpf();
          $objContatoDTO->retStrNumeroPassaporte();
          $objContatoDTO->retStrNomePaisPassaporte();
          $objContatoDTO->retDblCpf();
          $objContatoDTO->retDblCnpj();
          $objContatoDTO->retDblCnpjContatoAssociado();
          $objContatoDTO->retDblRg();
          $objContatoDTO->retStrOrgaoExpedidor();
          $objContatoDTO->retStrMatricula();
          $objContatoDTO->retStrMatriculaOab();
          $objContatoDTO->retStrEmail();
          $objContatoDTO->retStrTelefoneComercial();
          $objContatoDTO->retStrTelefoneResidencial();
          $objContatoDTO->retStrTelefoneCelular();
          $objContatoDTO->retStrSitioInternet();

          $objContatoDTO->setNumIdContato(InfraArray::converterArrInfraDTO($arrObjParticipanteDTO, 'IdContato'), InfraDTO::$OPER_IN);

          $objContatoRN = new ContatoRN();
          $arrObjContatoDTO = InfraArray::indexarArrInfraDTO($objContatoRN->listarComEndereco($objContatoDTO), 'IdContato');

          foreach ($arrObjContatoDTO as $objContatoDTO) {
            if ($objContatoDTO->getStrNomeSocial() != null) {
              $objContatoDTO->setStrNome(SeiINT::formatarNomeSocial($objContatoDTO->getStrNomeRegistroCivil(), $objContatoDTO->getStrNomeSocial()));
            }
          }
        }

        /* Interessados *******************************************************************************************/
        $arr = InfraArray::converterArrInfraDTO(InfraArray::filtrarArrInfraDTO($arrObjParticipanteDTO, 'StaParticipacao', ParticipanteRN::$TP_INTERESSADO), 'IdContato');
        $arrObjContatoDTOInteressados = array();
        if (count($arr)) {
          //manter ordem realizada no cadastro
          foreach($arr as $numIdContatoInteressado){
            if (isset($arrObjContatoDTO[$numIdContatoInteressado])){
              $arrObjContatoDTOInteressados[] = $arrObjContatoDTO[$numIdContatoInteressado];
            }
          }
        }

        /* Destinatários ******************************************************************************************/
        $arr = InfraArray::converterArrInfraDTO(InfraArray::filtrarArrInfraDTO($arrObjParticipanteDTO, 'StaParticipacao', ParticipanteRN::$TP_DESTINATARIO), 'IdContato');
        $arrObjContatoDTODestinatarios = array();
        if (count($arr)) {
          //manter ordem realizada no cadastro
          foreach($arr as $numIdContatoDestinatario){
            if (isset($arrObjContatoDTO[$numIdContatoDestinatario])){
              $arrObjContatoDTODestinatarios[] = $arrObjContatoDTO[$numIdContatoDestinatario];
            }
          }
        }


        $numDestinatarios = InfraArray::contar($arrObjContatoDTODestinatarios);

        if ($numDestinatarios) {

          $strTag = '';
          for ($i = 0; $i<$numDestinatarios; $i++) {
            if ($strTag!='') {
              $strTag .= ', ';
            }
            $strTag .= $arrObjContatoDTODestinatarios[$i]->getStrNome();
          }

          if ($strTag!='') {
            self::$arrTags['destinatarios'] = $strTag; //deprecated
            self::$arrTags['destinatarios_virgula_espaco'] = $strTag;
            self::$arrTags['destinatarios_virgula_espaco_maiusculas'] = InfraString::transformarCaixaAlta($strTag);
          }

          $strTag = '';
          for ($i = 0; $i<$numDestinatarios; $i++) {
            if ($strTag!='') {
              $strTag .= '<br />';
            }
            $strTag .= $arrObjContatoDTODestinatarios[$i]->getStrNome();
          }

          if ($strTag!='') {
            self::$arrTags['destinatarios_quebra_linha'] = $strTag;
          }

          $strTag = '';
          for ($i = 0; $i<$numDestinatarios; $i++) {
            if ($strTag!='') {
              $strTag .= '<br />';
            }
            $strTag .= InfraString::transformarCaixaAlta($arrObjContatoDTODestinatarios[$i]->getStrNome());
          }

          if ($strTag!='') {
            self::$arrTags['destinatarios_quebra_linha_maiusculas'] = $strTag;
          }

          $this->montarTagsContato($arrObjContatoDTODestinatarios[0], 'destinatario');

          if ($numDestinatarios > 1){
            $this->montarTagsContato($arrObjContatoDTODestinatarios[1], 'destinatario_2');
          }

          if ($numDestinatarios > 2){
            $this->montarTagsContato($arrObjContatoDTODestinatarios[2], 'destinatario_3');
          }

        }

        $numInteressados = InfraArray::contar($arrObjContatoDTOInteressados);

        if ($numInteressados) {

          $strTag = '';
          for ($i = 0; $i<$numInteressados; $i++) {
            if ($strTag!='') {
              $strTag .= ', ';
            }
            $strTag .= $arrObjContatoDTOInteressados[$i]->getStrNome();
          }

          if ($strTag!='') {
            self::$arrTags['interessados'] = $strTag; //deprecated
            self::$arrTags['interessados_virgula_espaco'] = $strTag;
            self::$arrTags['interessados_virgula_espaco_maiusculas'] = InfraString::transformarCaixaAlta($strTag);
          }

          $strTag = '';
          for ($i = 0; $i<$numInteressados; $i++) {
            if ($strTag!='') {
              $strTag .= '<br />';
            }
            $strTag .= $arrObjContatoDTOInteressados[$i]->getStrNome();
          }

          if ($strTag!='') {
            self::$arrTags['interessados_quebra_linha']=$strTag;
          }

          $strTag = '';
          for ($i = 0; $i<$numInteressados; $i++) {
            if ($strTag!='') {
              $strTag .= '<br />';
            }
            $strTag .= InfraString::transformarCaixaAlta($arrObjContatoDTOInteressados[$i]->getStrNome());
          }

          if ($strTag!='') {
            self::$arrTags['interessados_quebra_linha_maiusculas']=$strTag;
          }

          $this->montarTagsContato($arrObjContatoDTOInteressados[0], 'interessado');

          if ($numInteressados > 1){
            $this->montarTagsContato($arrObjContatoDTOInteressados[1], 'interessado_2');
          }

          if ($numInteressados > 2){
            $this->montarTagsContato($arrObjContatoDTOInteressados[2], 'interessado_3');
          }
        }
      }

      if (array_key_exists($parStrTag,self::$arrTags) && self::$arrTags[$parStrTag]==null){
        self::$arrTags[$parStrTag]='';
      }

    } catch (Exception $e) {
      throw new InfraException('Erro obtendo parâmetros do editor.', $e);
    }
  }

  public function buscarImagemUpload($nomeArquivo)
  {
    $arrImagemPermitida = EditorINT::getArrImagensPermitidas();

    $ext = pathinfo(DIR_SEI_TEMP . '/' . $nomeArquivo);

    if (!in_array($ext['extension'], $arrImagemPermitida)) return 'Tipo de Arquivo não permitido.';

    return 'data:image/' . $ext['extension'] . ';base64,' . base64_encode(file_get_contents(DIR_SEI_TEMP . '/' . $nomeArquivo));
  }

  protected function recuperarVersaoControlado(EditorDTO $parObjEditorDTO)
  {
    try {
      if ($parObjEditorDTO->getDblIdDocumento()!=null) {
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retStrNomeSerie();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
        $objDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO==null) {
          throw new InfraException('Documento não encontrado.');
        }
      }

      $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
      $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
      $objSecaoDocumentoDTO->retNumIdSecaoModelo();
      $objSecaoDocumentoDTO->retStrSinAssinatura();
      $objSecaoDocumentoDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
      $objSecaoDocumentoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objSecaoDocumentoDTO->setStrSinAssinatura('N');

      $objSecaoDocumentoRN = new SecaoDocumentoRN();
      $arrObjSecaoDocumentoDTO = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);
      $arrNovoObjSecaoDocumentoDTO = array();

      foreach ($arrObjSecaoDocumentoDTO as $objSecaoDocumentoDTO) {

        $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
        $objVersaoSecaoDocumentoDTO->retStrConteudo();
        $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento($objSecaoDocumentoDTO->getNumIdSecaoDocumento());
        $objVersaoSecaoDocumentoDTO->setNumVersao($parObjEditorDTO->getNumVersao(), InfraDTO::$OPER_MENOR_IGUAL);
        $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);
        $objVersaoSecaoDocumentoDTO->setNumMaxRegistrosRetorno(1);

        $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
        $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

        if (count($arrObjVersaoSecaoDocumentoDTO)>0) {
          $objNovoSecaoDocumentoDTO = new SecaoDocumentoDTO();
          $objNovoSecaoDocumentoDTO->setNumIdSecaoModelo($objSecaoDocumentoDTO->getNumIdSecaoDocumento());
          $objNovoSecaoDocumentoDTO->setNumIdSecaoModelo($objSecaoDocumentoDTO->getNumIdSecaoModelo());
          $objNovoSecaoDocumentoDTO->setStrConteudo($arrObjVersaoSecaoDocumentoDTO[0]->getStrConteudo());
          $arrNovoObjSecaoDocumentoDTO[] = $objNovoSecaoDocumentoDTO;
        }
      }
      $objEditorDTO = new EditorDTO();
      $objEditorDTO->setDblIdDocumento($parObjEditorDTO->getDblIdDocumento());
      $objEditorDTO->setNumIdBaseConhecimento(null);
      $objEditorDTO->setArrObjSecaoDocumentoDTO($arrNovoObjSecaoDocumentoDTO);
      $objEditorDTO->setStrSinForcarNovaVersao('S');
      $this->adicionarVersao($objEditorDTO);

    } catch (Exception $e) {
      throw new InfraException('Erro recuperando versão.', $e);
    }
  }

  public function validarTagsCriticas($arrImagemPermitida, $str)
  {

    $objInfraException = new InfraException();

    $arrRemoverTags = array('img', 'script', 'iframe', 'frame', 'embed', 'object', 'param', 'video', 'audio', 'button', 'input', 'select');

    foreach ($arrRemoverTags as $tag) {
      if ($str!=preg_replace("%<" . $tag . "[^>]*>(.*?)<\\/" . $tag . ">%si", "", $str) || $str!=preg_replace("%<" . $tag . "[^>]*\\/>%si", "", $str)) {
        switch ($tag) {
          case 'script':
            $objInfraException->lancarValidacao('Documento possui código de script oculto no conteúdo.');
            break;

          case 'img':

            if (InfraArray::contar($arrImagemPermitida)==0) {
              $objInfraException->lancarValidacao('Documento possui imagem no conteúdo.');
            }

            $arrImagensConteudo = array();
            preg_match_all('/src="([^"]*)"/i', $str, $arrImagensConteudo);

            foreach ($arrImagensConteudo[1] as $strImagem) {
              $posIni = strpos($strImagem, '/');
              $posFim = strpos($strImagem, ';', $posIni);
              if ($posIni!==false && $posFim!==false) {
                $posIni = $posIni + 1;
                if (!in_array(InfraString::transformarCaixaBaixa(substr($strImagem, $posIni, ($posFim - $posIni))), $arrImagemPermitida)) {
                  $objInfraException->lancarValidacao('Documento possui imagem no formato "' . substr($strImagem, $posIni, ($posFim - $posIni)) . '" não permitido no conteúdo.');
                }
              } else {
                $objInfraException->lancarValidacao('Documento possui imagem não permitida no conteúdo.');
              }
            }
            break;

          case 'button':
          case 'input':
          case 'select':
            $objInfraException->lancarValidacao('Documento possui componente HTML não permitido no conteúdo.');
            break;
          case 'iframe':
            $objInfraException->lancarValidacao('Documento possui formulário oculto no conteúdo.');
            break;

          case 'frame':
            $objInfraException->lancarValidacao('Documento possui formulário no conteúdo.');
            break;
          case 'embed':
          case 'object':
          case 'param':
            $objInfraException->lancarValidacao('Documento possui um objeto não autorizado no conteúdo.');
            break;
          case 'video':
            $objInfraException->lancarValidacao('Documento possui vídeo no conteúdo.');
            break;
          case 'audio':
            $objInfraException->lancarValidacao('Documento possui áudio no conteúdo.');
        }
      }
    }

  }

  public function processarLinksSei($str)
  {
    if ($str==null) return null;
    //remover sujeira scayt
    $ret=preg_replace(self::$REGEX_SPAN_SCAYT,'$4', $str);
    if($ret!==null){
      $str=$ret;
    } else {
      LogSEI::getInstance()->gravar('[processarLinksSei] REGEXP_SPAN_SCAYT: '.preg_last_error(),InfraLog::$DEBUG);
    }
    $ret = preg_replace_callback(self::$REGEXP_LINK_ASSINADO, "self::validarLink", $str);
    if($ret!==null){
      $str=$ret;
    } else {
      LogSEI::getInstance()->gravar('[processarLinksSei] REGEXP_LINK_ASSINADO: '.preg_last_error(),InfraLog::$DEBUG);
    }

    if (preg_match_all(self::$REGEXP_SPAN_LINKSEI, $str, $matches)>0){
      $arrIdProtocolo=array_unique($matches[1]);
      if (InfraArray::contar($arrIdProtocolo)>0) {
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO->setDblIdProtocolo($arrIdProtocolo, InfraDTO::$OPER_IN);
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

        foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
          $this->arrProtocolos[$objProtocoloDTO->getDblIdProtocolo()]=$objProtocoloDTO->getStrProtocoloFormatado();
        }
      }

    } else if(preg_last_error()!=0) {
      LogSEI::getInstance()->gravar('[processarLinksSei] Match_all REGEXP_SPAN_LINKSEI: '.preg_last_error(),InfraLog::$DEBUG);
    }
    $ret= preg_replace_callback(self::$REGEXP_SPAN_LINKSEI,'self::processarLinkProtocolo',$str);
    if($ret!==null){
      $str=$ret;
    } else {
      LogSEI::getInstance()->gravar('[processarLinksSei] REGEXP_SPAN_LINKSEI: '.preg_last_error(),InfraLog::$DEBUG);
    }
    $ret= preg_replace_callback(self::$REGEXP_SPAN_LINKSEI_FEDERACAO,'self::processarLinkFederacao',$str);
    if($ret!==null){
      $str=$ret;
    } else {
      LogSEI::getInstance()->gravar('[processarLinksSei] REGEXP_SPAN_LINKSEI_FEDERACAO: '.preg_last_error(),InfraLog::$DEBUG);
    }
    return $str;
  }

  /**
   * @param $matches  origem da REGEXP_LINK_ASSINADO ([0]=match [1]=acao [2]=id_protocolo [3]=id_sistema [4]=texto do link)
   * @return string
   */
  private function validarLink($matches)
  {
//    LogSEI::getInstance()->gravar('M3='.$matches[3].'  - '.SessaoSEI::getInstance()->getNumIdSistema());
    if($matches[3]!=SessaoSEI::getInstance()->getNumIdSistema()){
      return $matches[0];
    }
    if($matches[1]=='protocolo_visualizar'){
      return '<a class="ancora_sei" id="lnkSei'.$matches[2].'" style="text-indent:0;">'.$matches[4].'</a>';
    }
    return $matches[4];
  }


  /**
   * @param $matches  origem da REGEXP_SPAN_LINKSEI ([0]=match [1]=id_protocolo [2]==texto do link)
   * @return string
   */
  private function processarLinkProtocolo($matches)
  {
    if(!isset($this->arrProtocolos[$matches[1]]) || $this->arrProtocolos[$matches[1]]!=$matches[2] ) {
      //não foi encontrado protocolo correspondente, retorna somente o texto
      return $matches[2];
    }

    return '<span contenteditable="false" style="text-indent:0;"><a class="ancora_sei" id="lnkSei'.$matches[1].'" style="text-indent:0;">'.$matches[2].'</a></span>';
  }
  /**
   * @param $matches  origem da REGEXP_SPAN_LINKSEI_FEDERACAO ([0]=match [1]=atributos da ancora [2]==texto do link)
   * @return string
   */
  private function processarLinkFederacao($matches)
  {
    $bolErro=false;
    $strAtributosLink=$matches[1];
    $strTextoLink=$matches[2];
    //verificar se tem ft (tipo),fi (instalacao),fp (processo), fa (processo_anexado) ou fd (documento)
    //filtrar outros dados
    //caso não tenha, deixar somente texto
    if(preg_match('/data-ft\s?=\s?(\'|")([pda])\1/',$strAtributosLink,$match)!==1){
      return $strTextoLink;
    }
    $staTipoLinkFederacao=$match[2];

    preg_match_all('/data-f([ipda])\s?=\s?(\'|")([0-9A-HJKLMNPQRSTVWXYZ]{26})\2/', $strAtributosLink, $atributos,PREG_SET_ORDER);
    //valida parametros t,i,p (para documentos)
    foreach ($atributos as $arr) {
      $data[$arr[1]]=$arr[3];
    }
    

    if (!isset($data['i'], $data['p'], $data[$staTipoLinkFederacao])) {
      $bolErro=true;
    }
    $ret='<span contenteditable="false" style="text-indent:0;">';
    $ret.='<a class="ancora_sei" style="text-indent:0;" ';
    $ret.='data-ft="'.$staTipoLinkFederacao.'" ';
    $ret.='data-fi="'.$data['i'].'" ';
    $ret.='data-fp="'.$data['p'].'"';
    switch($staTipoLinkFederacao){
      case 'd':
        $ret.=' data-fd="'.$data['d'].'">';
        break;
      case 'a':
        $ret.=' data-fa="'.$data['a'].'">';
        break;
      case 'p':
        $ret.='>';
    }
    $ret.=$strTextoLink.'</a></span>';
    if($bolErro){
      return $strTextoLink;
    }
    return $ret;
  }

  private function assinarLinkFederacao($strHtml)
  {

    $objSessaoSEI=SessaoSEI::getInstance();
    $strUrl=SeiINT::obterURL();

    return preg_replace_callback('/<a([^>]*data-fi="[0-9A-HJKLMNPQRSTVWXYZ]{26}"[^>]*)>/', static function($matches) use($objSessaoSEI,$strUrl){
      $strAtributos=$matches[1];

      if(preg_match('/data-ft\s?=\s?(\'|")([pda])\1/',$strAtributos,$match)!==1){
        return $matches[0];
      }
      $staTipoLinkFederacao=$match[2];
      preg_match_all('/data-f([ipda])\s?=\s?(\'|")([0-9A-HJKLMNPQRSTVWXYZ]{26})\2/', $strAtributos, $arrAtributos,PREG_SET_ORDER);
      //valida parametros t,i,p (para documentos)
      foreach ($arrAtributos as $arr) {
        $data[$arr[1]]=$arr[3];
      }

      if (!isset($data['i'], $data['p'], $data[$staTipoLinkFederacao])) {
        return $matches[0];
      }
      $strLink='controlador.php?acao=';
      $strParametros='&id_instalacao_federacao='.$data['i'];
      $strParametros.='&id_procedimento_federacao='.$data['p'];

      switch ($staTipoLinkFederacao){
        case 'a':
          $strLink.='processo_consulta_federacao';
          $strParametros.='&id_procedimento_federacao_anexado='.$data['a'];
          break;
        case 'd':
          $strLink.='documento_consulta_federacao';
          $strParametros.='&id_documento_federacao='.$data['d'];
          break;
        case 'p':
          $strLink.='processo_consulta_federacao';
          break;
      }
      $strLink = $objSessaoSEI->assinarLink($strUrl.$strLink.$strParametros);
      return '<a class="ancora_sei" href="' . $strLink . '" target="_blank">';
    },$strHtml);




    return $strHtml;
  }
  private function assinarLinksSei($strHtml, $dblIdProcedimentoAtual){

    $objSessaoSEI=SessaoSEI::getInstance();
    $strUrl=SeiINT::obterURL();
    return preg_replace_callback('/id="lnkSei(\d+)"/', static function($matches) use($objSessaoSEI,$strUrl,$dblIdProcedimentoAtual){
      $strLink= $objSessaoSEI->assinarLink($strUrl.'controlador.php?acao=protocolo_visualizar&id_protocolo='.$matches[1].'&id_procedimento_atual='.$dblIdProcedimentoAtual);
      return $matches[0].' href="' . $strLink . '" target="_blank"';
    },$strHtml);

  }

  private function limparTagsCriticas($str)
  {
    //remove tags mas deixa conteúdo
    $arrRemoverTags = array('html', 'body');
    foreach ($arrRemoverTags as $tag) {
      $str = preg_replace("%<" . $tag . "[^>]*>%si", "", $str);
      $str = preg_replace("%</" . $tag . "[^>]*>%si", "", $str);
    }
    //remove tags e todo o seu conteúdo
    $arrRemoverTags = array('img', 'script', 'iframe', 'frame', 'embed', 'object', 'param', 'video', 'audio', 'button', 'input', 'select', 'link', 'head', 'title');
    foreach ($arrRemoverTags as $tag) {
      $str = preg_replace("%<" . $tag . "[^>]*>(.*?)<\\/" . $tag . ">%si", "", $str);
      $str = preg_replace("%<" . $tag . "[^>]*\\/>%si", "", $str);
    }
    return $str;
  }

  protected function obterNumeroUltimaVersaoConectado(DocumentoDTO $objDocumentoDTO)
  {
    try {

      $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
      $objSecaoDocumentoDTO->retNumIdSecaoDocumento();
      $objSecaoDocumentoDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());

      $objSecaoDocumentoRN = new SecaoDocumentoRN();
      $arrObjSecaoDocumentoDTO = $objSecaoDocumentoRN->listar($objSecaoDocumentoDTO);

      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoDTO->retNumVersao();
      $objVersaoSecaoDocumentoDTO->setNumIdSecaoDocumento(InfraArray::converterArrInfraDTO($arrObjSecaoDocumentoDTO, 'IdSecaoDocumento'), InfraDTO::$OPER_IN);
      $objVersaoSecaoDocumentoDTO->setStrSinUltima('S');
      $objVersaoSecaoDocumentoDTO->setNumMaxRegistrosRetorno(1);
      $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
      $objVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->consultar($objVersaoSecaoDocumentoDTO);

      if ($objVersaoSecaoDocumentoDTO!=null) {
        return $objVersaoSecaoDocumentoDTO->getNumVersao();
      }

      return null;

    } catch (Exception $e) {
      throw new InfraException('Erro obtendo número da última versão.', $e);
    }
  }

  protected function recuperarLinkAcessoExternoControlado(DocumentoDTO $parObjDocumentoDTO){
    try {
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->setNumIdUsuario($objInfraParametro->getValor('ID_USUARIO_SEI'));

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoRN = new DocumentoRN();
      $objDocumentoDTO->setDblIdDocumento($parObjDocumentoDTO->getDblIdDocumento());
      $objDocumentoDTO->retDblIdProcedimento();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->retNumIdAcessoExterno();
      $objAcessoExternoDTO->setDblIdProtocoloAtividade($objDocumentoDTO->getDblIdProcedimento());
      $objAcessoExternoDTO->setNumIdContatoParticipante($objUsuarioDTO->getNumIdContato());
      $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_SISTEMA);

      $objAcessoExternoRN = new AcessoExternoRN();
      $objAcessoExternoDTO = $objAcessoExternoRN->consultar($objAcessoExternoDTO);

      if ($objAcessoExternoDTO == null) {

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdProcedimento());
        $objParticipanteDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
        $objParticipanteDTO->setStrStaParticipacao(ParticipanteRN::$TP_ACESSO_EXTERNO);
        $objParticipanteDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objParticipanteDTO->setNumSequencia(0);

        $objParticipanteRN = new ParticipanteRN();
        $objParticipanteDTO = $objParticipanteRN->cadastrarRN0170($objParticipanteDTO);

        $objAcessoExternoDTO = new AcessoExternoDTO();
        $objAcessoExternoDTO->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
        $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_SISTEMA);

        $objAcessoExternoDTO->setStrSinInclusao('N');

        $objAcessoExternoRN = new AcessoExternoRN();
        $objAcessoExternoDTO = $objAcessoExternoRN->cadastrar($objAcessoExternoDTO);
      }

      $numIdAcessoExterno = SessaoSEIExterna::getInstance()->getNumIdAcessoExterno();
      SessaoSEIExterna::getInstance()->configurarAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());
      $ret = '<a target="_blank" href="' . SessaoSEIExterna::getInstance()->assinarLink(ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL') . '/processo_acesso_externo_consulta.php?id_acesso_externo=' . $objAcessoExternoDTO->getNumIdAcessoExterno()) . '" style="text-decoration:none">' . $objDocumentoDTO->getStrProtocoloProcedimentoFormatado() . '</a>';
      SessaoSEIExterna::getInstance()->configurarAcessoExterno($numIdAcessoExterno);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro gerando link de acesso externo.', $e);
    }

  }
  
  private function montarTagsContato(ContatoDTO $objContatoDTO, $strTipo){

    if ($objContatoDTO->getStrStaGenero()==ContatoRN::$TG_MASCULINO){
      self::$arrTags['artigo_'.$strTipo.'_minuscula'] = 'o';
      self::$arrTags['artigo_'.$strTipo.'_maiuscula'] = 'O';
    }else if ($objContatoDTO->getStrStaGenero()==ContatoRN::$TG_FEMININO){
      self::$arrTags['artigo_'.$strTipo.'_minuscula'] = 'a';
      self::$arrTags['artigo_'.$strTipo.'_maiuscula'] = 'A';
    }

    if (($strTag = $objContatoDTO->getStrNome())!=''){
      self::$arrTags['nome_'.$strTipo] = $strTag;
    }

    if (($strTag = InfraString::transformarCaixaAlta($objContatoDTO->getStrNome()))!=''){
      self::$arrTags['nome_'.$strTipo.'_maiusculas'] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrExpressaoTratamentoCargo())!=''){
      self::$arrTags['tratamento_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrExpressaoVocativoCargo())!=''){
      self::$arrTags['vocativo_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrExpressaoCargo())!=''){
      self::$arrTags['cargo_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrNomeContatoAssociado())!=''){
      self::$arrTags['origem_'.$strTipo] = $strTag;
      self::$arrTags['nome_contexto_'.$strTipo] = $strTag;
      self::$arrTags['nome_pessoa_juridica_associada_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrEndereco();
    if ($strTag != '') {
      self::$arrTags['endereco_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrComplemento();
    if ($strTag != '') {
      self::$arrTags['complemento_endereco_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrBairro();
    if ($strTag!='') {
      self::$arrTags['bairro_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrNomeCidade();
    if ($strTag!='') {
      self::$arrTags['cidade_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrCep();
    if ($strTag!='') {
      self::$arrTags['cep_'.$strTipo] = $strTag;
    }

    $strTag = '';
    if ($objContatoDTO->getStrSiglaUf()!='') {
      $strTag = ' - ' . $objContatoDTO->getStrSiglaUf();
    }

    if ($strTag!='') {
      self::$arrTags['hifen_uf_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrSiglaUf();
    if ($strTag!='') {
      self::$arrTags['sigla_uf_'.$strTipo] = $strTag;
    }

    $strTag = $objContatoDTO->getStrNomePais();
    if ($strTag!='') {
      self::$arrTags['pais_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getDblCpf())!=''){
      self::$arrTags['cpf_'.$strTipo] = InfraUtil::formatarCpf($strTag);
    }

    if (($strTag = $objContatoDTO->getStrNumeroPassaporte())!=''){
      self::$arrTags['numero_passaporte_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrNomePaisPassaporte())!=''){
      self::$arrTags['pais_passaporte_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrExpressaoTituloContato())!=''){
      self::$arrTags['titulo_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrAbreviaturaTituloContato()) !=''){
      self::$arrTags['titulo_abreviatura_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrFuncao())!=''){
      self::$arrTags['funcao_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrNomeCategoria())!=''){
      self::$arrTags['categoria_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getDblCnpj())!=''){
      self::$arrTags['cnpj_'.$strTipo] = InfraUtil::formatarCnpj($strTag);
    }

    if (($strTag = $objContatoDTO->getDblCnpjContatoAssociado())!=''){
      self::$arrTags['cnpj_pessoa_juridica_associada_'.$strTipo] = InfraUtil::formatarCnpj($strTag);
    }

    if (($strTag = $objContatoDTO->getStrSitioInternet())!=''){
      self::$arrTags['sitio_internet_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getDblRg())!=''){
      self::$arrTags['rg_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrOrgaoExpedidor())!=''){
      self::$arrTags['orgao_expedidor_rg_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrMatricula())!=''){
      self::$arrTags['matricula_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrMatriculaOab())!=''){
      self::$arrTags['matricula_oab_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrEmail())!=''){
      self::$arrTags['email_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrTelefoneComercial())!=''){
      self::$arrTags['telefone_fixo_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrTelefoneResidencial())!=''){
      self::$arrTags['telefone_residencial_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrTelefoneComercial())!=''){
      self::$arrTags['telefone_comercial_'.$strTipo] = $strTag;
    }

    if (($strTag = $objContatoDTO->getStrTelefoneCelular())!=''){
      self::$arrTags['telefone_celular_'.$strTipo] = $strTag;
    }
    
  }
  
  public static function converterHTML($strConteudo){
    return str_replace(array('°','º','ª','¹','²','³','£','¢','§','¬'), 
                       array('&deg;','&ordm;','&ordf;','&sup1;','&sup2;','&sup3;','&pound;','&cent;','&sect;','&not;'), 
                       InfraString::acentuarHTML($strConteudo));
  }

  private function montarCarimboPublicacao($strTextoPublicacao){
    return '<div style="font-weight: 500; text-align: left; font-size: 9pt; border: 2px solid #777; position: absolute; left: 67%; padding: 4px;">' . nl2br($strTextoPublicacao) . '</div>';
  }

}
