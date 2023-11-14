<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/10/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class AssinaturaRN extends InfraRN {

  public static $TA_CERTIFICADO_DIGITAL = 'C';
  public static $TA_SENHA = 'S';
  public static $TA_MODULO = 'M';

  public static $TA_SIMPLES = 'S';
  public static $TA_COMPLETA = 'C';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function obterDocumentosAgrupadorConectado(AssinaturaDTO $parObjAssinaturaDTO){

    try{

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setBolExclusaoLogica(false);
      $objAssinaturaDTO->retDblIdDocumento();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->setStrSinAtivo('N');
      $objAssinaturaDTO->setStrAgrupador($parObjAssinaturaDTO->getStrAgrupador());

      $objAssinaturaRN = new AssinaturaRN();
      $arrObjAssinaturasDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

      if (count($arrObjAssinaturasDTO) == 0) {
        throw new InfraException('Nenhum documento foi localizado para o código de assinatura informado.', null, null, false);
      }


      $objDocumentoDTO = new DocumentoDTO();
      $objDocumentoDTO->retDblIdDocumento();
      $objDocumentoDTO->retStrProtocoloProcedimentoFormatado();
      $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
      $objDocumentoDTO->retStrNomeSerie();
      $objDocumentoDTO->retDtaGeracaoProtocolo();
      $objDocumentoDTO->setDblIdDocumento(InfraArray::converterArrInfraDTO($arrObjAssinaturasDTO,'IdDocumento'),InfraDTO::$OPER_IN);

      $objDocumentoRN = new DocumentoRN();
      $arrObjDocumentoDTO = InfraArray::indexarArrInfraDTO($objDocumentoRN->listarRN0008($objDocumentoDTO),'IdDocumento');

      $arrParametrosDocumentos = array();

      foreach ($arrObjAssinaturasDTO as $objAssinaturaDTO) {

        if (!isset($arrObjDocumentoDTO[$objAssinaturaDTO->getDblIdDocumento()])){
          throw new InfraException('Um documento associado com este código de assinatura não foi localizado.', null, null, false);
        }

        $objDocumentoDTO = $arrObjDocumentoDTO[$objAssinaturaDTO->getDblIdDocumento()];

        $arrParametrosDocumentos[] = implode('±', array($objAssinaturaDTO->getNumIdAssinatura(),
                                                        $objDocumentoDTO->getDblIdDocumento(),
                                                        $objDocumentoDTO->getStrProtocoloProcedimentoFormatado(),
                                                        $objDocumentoDTO->getStrProtocoloDocumentoFormatado(),
                                                        $objDocumentoDTO->getStrNomeSerie(),
                                                        $objDocumentoDTO->getDtaGeracaoProtocolo()));
      }

      return implode('|', $arrParametrosDocumentos);

    }catch(Exception $e){
      throw new InfraException('Erro obtendo documentos do agrupador de assinatura.', $e);
    }
  }
 
  /*
   *
   * Retorno um booleano true indicando que a assinatura pôde ser validada, ou false indicando o contrário.
   * Cada uma das assinaturas validadas terá também uma mensagem de erro acompanhada de seu código, sua mensagem e um complemento
   * que será a mensagem original interceptada.
   * Valores possíveis para os códigos de erro e mensagens referentes a cada assinatura:
   * 1 - Algoritmo criptográfico informado no certificado é inválido.
   * 2 - O provedor de segurança é inválido.
   * 3 - O certificado não era mais válido no nomento da assinatura.
   * 4 - O certificado ainda não era válido no nomento da assinatura.
   * 5 - A verificação dos dados assinados falhou.
   * 6 - Certificado não foi emitido abaixo da ICP-Brasil.
   * 7 - Não foi possível verificar a autoridade certificadora raiz do certificado.
   *
   * Mesangens gerais, referentes ao conjunto de assinaturas.
   * - OK
   * - Pelo menos uma assinatura não pode ser verificada.
   * - Ocorreu algum problema ao acessar o repositório de certificados.
   *
   */
  protected function validarAssinaturaDocumentoControlado(AssinaturaDTO $parObjAssinaturaDTO, InfraException $objInfraException){

    $strDiretorioBase = dirname(__FILE__).'/../';
    
    $objAnexoRN = new AnexoRN();

    $objAssinaturaDTO = new AssinaturaDTO();
    $objAssinaturaDTO->setBolExclusaoLogica(false);
    $objAssinaturaDTO->retStrNome();
    $objAssinaturaDTO->retStrProtocoloDocumentoFormatado();
    $objAssinaturaDTO->retDblIdDocumento();
    $objAssinaturaDTO->retStrStaFormaAutenticacao();
    $objAssinaturaDTO->retNumIdAssinatura();
    $objAssinaturaDTO->setDblIdDocumento($parObjAssinaturaDTO->getDblIdDocumento());
    $objAssinaturaDTO->setNumIdAssinatura($parObjAssinaturaDTO->getNumIdAssinatura());

    $objAssinaturaDTO = $this->consultarRN1322($objAssinaturaDTO);

    if ($objAssinaturaDTO==null){
      $objInfraException->lancarValidacao('Assinatura não encontrada para validação.');
    }

    $objDocumentoDTO = new DocumentoDTO();
    $objDocumentoDTO->retDblIdDocumento();
    $objDocumentoDTO->retStrStaProtocoloProtocolo();
    $objDocumentoDTO->retStrConteudoAssinatura();
    $objDocumentoDTO->setDblIdDocumento($parObjAssinaturaDTO->getDblIdDocumento());

    $objDocumentoRN = new DocumentoRN();
    $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

    if ($objDocumentoDTO==null){
      throw new InfraException('Documento não encontrado para validação da assinatura.');
    }

    if($objAssinaturaDTO->getStrStaFormaAutenticacao()==AssinaturaRN::$TA_CERTIFICADO_DIGITAL){

      $nomeArquivoAssinatura = $objAnexoRN->gerarNomeArquivoTemporario();

      if (!$handleSig = fopen(DIR_SEI_TEMP.'/'.$nomeArquivoAssinatura, 'a')) {
        $objInfraException->lancarValidacao('Erro criando arquivo temporário de assinatura.');
      }

      if (fwrite($handleSig, base64_decode($parObjAssinaturaDTO->getStrP7sBase64())) === FALSE) {
        $objInfraException->lancarValidacao('Erro escrevendo arquivo temporário de assinatura.');
      }

      fclose($handleSig);


      $nomeArquivoDados = $objAnexoRN->gerarNomeArquivoTemporario();

      if ($objDocumentoDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

        if (!$handleDados = fopen(DIR_SEI_TEMP.'/'.$nomeArquivoDados, 'a')) {
          $objInfraException->lancarValidacao('Erro criando arquivo temporário do conteúdo assinado.');
        }

        if (fwrite($handleDados, $objDocumentoDTO->getStrConteudoAssinatura()) === FALSE) {
            $objInfraException->lancarValidacao('Erro escrevendo arquivo temporário do conteúdo assinado.');
        }

        fclose($handleDados);

      }else{

        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retStrNome();
        $objAnexoDTO->retDblIdProtocolo();
        $objAnexoDTO->retDthInclusao();
        $objAnexoDTO->setDblIdProtocolo($objDocumentoDTO->getDblIdDocumento());

        $objAnexoRN = new AnexoRN();
        $objAnexoDTO = $objAnexoRN->consultarRN0736($objAnexoDTO);

        if ($objAnexoDTO==null){
          throw new InfraException('Anexo do documento para assinatura não encontrado.');
        }

        copy($objAnexoRN->obterLocalizacao($objAnexoDTO), DIR_SEI_TEMP.'/'.$nomeArquivoDados);
      }

      $strCmd = 'java -Xmx2G -Dfile.encoding=ISO-8859-1 -cp '
          .$strDiretorioBase.'assinador/bcmail-jdk15-1.45.jar'
          .PATH_SEPARATOR.$strDiretorioBase.'assinador/bcprov-jdk15-1.45.jar'
          .PATH_SEPARATOR.$strDiretorioBase.'assinador/verify-1.1.jar br.gov.jfsc.util.security.certificacao.Tester '
          .DIR_SEI_TEMP.'/'.$nomeArquivoAssinatura. ' '.DIR_SEI_TEMP.'/'.$nomeArquivoDados;

      InfraDebug::getInstance()->gravar('COMANDO: '.$strCmd);

      exec($strCmd, $out);

      InfraDebug::getInstance()->gravar('SAIDA:'.print_r($out,true));

      $umaLinha = explode('=', $out[0]);
      if (trim($umaLinha[1]) != 'OK') {
        $objInfraException->lancarValidacao('Erro validando assinatura digital de "'.$objAssinaturaDTO->getStrNome().'" no documento '.$objAssinaturaDTO->getStrProtocoloDocumentoFormatado().'.'."\n".$umaLinha[1]);
      }

      $campos = array();
      $indice = -1;
      $sig = "";
      for ($i = 1; $i < InfraArray::contar($out); $i++) {
        $umaLinha = explode('=', $out[$i]);
        if ($umaLinha[0] == "RESULTADO") {
          $sig = "SIG".++$indice;
          $resultado = explode('#', $umaLinha[1]);
          $campos[$sig]["RESULTADO"] = $resultado;
        }else{
          $campos[$sig][$umaLinha[0]] = $umaLinha[1];
        }
      }
      if (InfraArray::contar($campos) > 1) {
        throw new InfraException('Mais de uma assinatura validada simultaneamente.');
      }

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      if ($objInfraParametro->getValor('SEI_HABILITAR_VALIDACAO_CPF_CERTIFICADO_DIGITAL')=='1' && $campos[$sig]['CPF'] != $parObjAssinaturaDTO->getDblCpf()){
        $objInfraException->lancarValidacao('CPF do certificado não é igual ao do usuário assinante '.$parObjAssinaturaDTO->getStrSiglaUsuario().'/'.$parObjAssinaturaDTO->getStrSiglaOrgaoUsuario().'.');
      }

      $parObjAssinaturaDTO->setStrNumeroSerieCertificado(strtoupper($campos[$sig]['SERIAL']));

      unlink(DIR_SEI_TEMP.'/'.$nomeArquivoAssinatura);
      unlink(DIR_SEI_TEMP.'/'.$nomeArquivoDados);
    }
  }

  private function validarDblIdDocumentoRN1311(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getDblIdDocumento())){
      $objInfraException->adicionarValidacao('Documento não informado.');
    }
  }

  private function validarNumIdUsuarioRN1312(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdUnidadeRN1313(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdAtividade(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getNumIdAtividade())){
      $objAssinaturaDTO->setNumIdAtividade(null);
    }
  }

  private function validarStrNomeRN1314(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objAssinaturaDTO->setStrNome(trim($objAssinaturaDTO->getStrNome()));

      if (strlen($objAssinaturaDTO->getStrNome())>100){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrTratamentoRN1315(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getStrTratamento())){
      $objInfraException->adicionarValidacao('Tratamento não informado.');
    }else{
      $objAssinaturaDTO->setStrTratamento(trim($objAssinaturaDTO->getStrTratamento()));

      if (strlen($objAssinaturaDTO->getStrTratamento())>200){
        $objInfraException->adicionarValidacao('Tratamento possui tamanho superior a 200 caracteres.');
      }
    }
  }

  private function validarDblCpfRN1316(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getDblCpf())){
      $objAssinaturaDTO->setDblCpf(null);
    }
  }

  private function validarStrStaFormaAutenticacao(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getStrStaFormaAutenticacao())){
      $objInfraException->adicionarValidacao('Forma de Autenticação não informada.');
    }else{
      if ($objAssinaturaDTO->getStrStaFormaAutenticacao()!=self::$TA_CERTIFICADO_DIGITAL && $objAssinaturaDTO->getStrStaFormaAutenticacao()!=self::$TA_SENHA && $objAssinaturaDTO->getStrStaFormaAutenticacao()!=self::$TA_MODULO){
        $objInfraException->adicionarValidacao('Forma de Autenticação inválida.');
      }
    }
  }

  private function validarStrP7sBase64(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getStrP7sBase64())){
      $objAssinaturaDTO->setStrP7sBase64(null);
    }
  }

  private function validarStrSinAtivo(AssinaturaDTO $objAssinaturaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinaturaDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAssinaturaDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function concederAcessoProcessoControlado(AssinaturaDTO $parObjAssinaturaDTO) {
    try{

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retStrStaNivelAcessoGlobalProtocoloProtocolo();
      $objAssinaturaDTO->retStrStaProtocoloProtocolo();
      $objAssinaturaDTO->retDblIdProcedimentoDocumento();
      $objAssinaturaDTO->retNumIdUnidade();
      $objAssinaturaDTO->setNumIdAssinatura($parObjAssinaturaDTO->getNumIdAssinatura());

      $objAssinaturaRN = new AssinaturaRN();
      $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

      //libera acesso para a unidade ao processo (somente para RESTRITOS e GERADOS provenientes de bloco de assinatura)
      if ($objAssinaturaDTO->getStrStaNivelAcessoGlobalProtocoloProtocolo()==ProtocoloRN::$NA_RESTRITO && 
          $objAssinaturaDTO->getStrStaProtocoloProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO){

        $objAssociarDTO = new AssociarDTO();
        $objAssociarDTO->setDblIdProcedimento($objAssinaturaDTO->getDblIdProcedimentoDocumento());
        $objAssociarDTO->setNumIdUnidade($objAssinaturaDTO->getNumIdUnidade());
        $objAssociarDTO->setNumIdUsuario(null);
        $objAssociarDTO->setStrStaNivelAcessoGlobal($objAssinaturaDTO->getStrStaNivelAcessoGlobalProtocoloProtocolo());

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloRN->associarRN0982($objAssociarDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro concedendo acesso ao processo por assinatura.',$e);
    }
  }

  protected function cadastrarRN1319Controlado(AssinaturaDTO $objAssinaturaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_cadastrar',__METHOD__,$objAssinaturaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdDocumentoRN1311($objAssinaturaDTO, $objInfraException);
      $this->validarNumIdUsuarioRN1312($objAssinaturaDTO, $objInfraException);
      $this->validarNumIdUnidadeRN1313($objAssinaturaDTO, $objInfraException);
      $this->validarNumIdAtividade($objAssinaturaDTO, $objInfraException);
      $this->validarStrStaFormaAutenticacao($objAssinaturaDTO, $objInfraException);
      $this->validarStrNomeRN1314($objAssinaturaDTO, $objInfraException);
      $this->validarStrTratamentoRN1315($objAssinaturaDTO, $objInfraException);
      $this->validarDblCpfRN1316($objAssinaturaDTO, $objInfraException);
      $this->validarStrP7sBase64($objAssinaturaDTO, $objInfraException);
      $this->validarStrSinAtivo($objAssinaturaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
      $ret = $objAssinaturaBD->cadastrar($objAssinaturaDTO);

      if ($objAssinaturaDTO->getStrSinAtivo()=='S'){
        $this->concederAcessoProcesso($objAssinaturaDTO);
      }

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Assinatura.',$e);
    }
  }

  protected function alterarRN1320Controlado(AssinaturaDTO $objAssinaturaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_alterar',__METHOD__,$objAssinaturaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAssinaturaDTO->isSetDblIdDocumento()){
        $this->validarDblIdDocumentoRN1311($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuarioRN1312($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN1313($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetNumIdAtividade()){
        $this->validarNumIdAtividade($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetStrNome()){
        $this->validarStrNomeRN1314($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetStrTratamento()){
        $this->validarStrTratamentoRN1315($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetDblCpf()){
        $this->validarDblCpfRN1316($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetStrStaFormaAutenticacao()){
        $this->validarStrStaFormaAutenticacao($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetStrP7sBase64()){
        $this->validarStrP7sBase64($objAssinaturaDTO, $objInfraException);
      }
      if ($objAssinaturaDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objAssinaturaDTO, $objInfraException);
      }
      $objInfraException->lancarValidacoes();

      $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
      $objAssinaturaBD->alterar($objAssinaturaDTO);


      if ($objAssinaturaDTO->isSetStrSinAtivo() && $objAssinaturaDTO->getStrSinAtivo()=='S'){
        $this->concederAcessoProcesso($objAssinaturaDTO);
      }


      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Assinatura.',$e);
    }
  }

  protected function excluirRN1321Controlado($arrObjAssinaturaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_excluir',__METHOD__,$arrObjAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssinaturaDTO);$i++){
        $objAssinaturaBD->excluir($arrObjAssinaturaDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Assinatura.',$e);
    }
  }

  protected function consultarRN1322Conectado(AssinaturaDTO $objAssinaturaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_consultar',__METHOD__,$objAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
      $ret = $objAssinaturaBD->consultar($objAssinaturaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Assinatura.',$e);
    }
  }

  protected function listarRN1323Conectado(AssinaturaDTO $objAssinaturaDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_listar',__METHOD__,$objAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
      $ret = $objAssinaturaBD->listar($objAssinaturaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Assinaturas.',$e);
    }
  }

  protected function contarRN1324Conectado(AssinaturaDTO $objAssinaturaDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_listar',__METHOD__,$objAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
      $ret = $objAssinaturaBD->contar($objAssinaturaDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Assinaturas.',$e);
    }
  }

  protected function montarTarjasConectado(DocumentoDTO $objDocumentoDTO) {
    try {

      $strRet = '';

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setBolExclusaoLogica(false);
      $objAssinaturaDTO->retStrNome();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->retNumIdTarjaAssinatura();
      $objAssinaturaDTO->retStrTratamento();
      $objAssinaturaDTO->retStrStaFormaAutenticacao();
      $objAssinaturaDTO->retStrNumeroSerieCertificado();
      $objAssinaturaDTO->retDthAberturaAtividade();
      $objAssinaturaDTO->retNumIdAtividade();
      $objAssinaturaDTO->retStrSinAtivo();

      $objAssinaturaDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
      $objAssinaturaDTO->setNumIdAtividade(null, InfraDTO::$OPER_DIFERENTE);
       
      $objAssinaturaDTO->setOrdNumIdAssinatura(InfraDTO::$TIPO_ORDENACAO_ASC);
       
      $arrObjAssinaturaDTO = $this->listarRN1323($objAssinaturaDTO);

      if (count($arrObjAssinaturaDTO)) {

        $objTarjaAssinaturaDTO = new TarjaAssinaturaDTO();
        $objTarjaAssinaturaDTO->setBolExclusaoLogica(false);
        $objTarjaAssinaturaDTO->retNumIdTarjaAssinatura();
        $objTarjaAssinaturaDTO->retStrStaTarjaAssinatura();
        $objTarjaAssinaturaDTO->retStrTexto();
        $objTarjaAssinaturaDTO->retStrLogo();
        $objTarjaAssinaturaDTO->setNumIdTarjaAssinatura(array_unique(InfraArray::converterArrInfraDTO($arrObjAssinaturaDTO,'IdTarjaAssinatura')),InfraDTO::$OPER_IN);

        $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
        $arrObjTarjaAssinaturaDTO = InfraArray::indexarArrInfraDTO($objTarjaAssinaturaRN->listar($objTarjaAssinaturaDTO),'IdTarjaAssinatura');

        $numAssinaturas = 0;

        foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {

          if ($objAssinaturaDTO->getStrSinAtivo()=='S' || $objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {

            if (!isset($arrObjTarjaAssinaturaDTO[$objAssinaturaDTO->getNumIdTarjaAssinatura()])) {
              throw new InfraException('Tarja associada com a assinatura "'.$objAssinaturaDTO->getNumIdAssinatura().'" não encontrada.');
            }

            $objTarjaAutenticacaoDTOAplicavel = $arrObjTarjaAssinaturaDTO[$objAssinaturaDTO->getNumIdTarjaAssinatura()];

            $strTarja = $objTarjaAutenticacaoDTOAplicavel->getStrTexto();
            $strTarja = preg_replace("/@logo_assinatura@/s", '<img alt="logotipo" src="data:image/png;base64,'.$objTarjaAutenticacaoDTOAplicavel->getStrLogo().'" />', $strTarja);
            $strTarja = preg_replace("/@nome_assinante@/s", $objAssinaturaDTO->getStrNome(), $strTarja);
            $strTarja = preg_replace("/@tratamento_assinante@/s", $objAssinaturaDTO->getStrTratamento(), $strTarja);
            $strTarja = preg_replace("/@data_assinatura@/s", substr($objAssinaturaDTO->getDthAberturaAtividade(), 0, 10), $strTarja);
            $strTarja = preg_replace("/@hora_assinatura@/s", substr($objAssinaturaDTO->getDthAberturaAtividade(), 11, 5), $strTarja);
            $strTarja = preg_replace("/@codigo_verificador@/s", $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $strTarja);
            $strTarja = preg_replace("/@crc_assinatura@/s", $objDocumentoDTO->getStrCrcAssinatura(), $strTarja);
            $strTarja = preg_replace("/@numero_serie_certificado_digital@/s", $objAssinaturaDTO->getStrNumeroSerieCertificado(), $strTarja);
            $strTarja = preg_replace("/@tipo_conferencia@/s", InfraString::transformarCaixaBaixa($objDocumentoDTO->getStrDescricaoTipoConferencia()), $strTarja);
            $strRet .= $strTarja;

            $numAssinaturas++;
          }
        }

        if ($numAssinaturas) {
          $objTarjaAssinaturaDTO = new TarjaAssinaturaDTO();
          $objTarjaAssinaturaDTO->retStrTexto();
          $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(TarjaAssinaturaRN::$TT_INSTRUCOES_VALIDACAO);

          $objTarjaAssinaturaDTO = $objTarjaAssinaturaRN->consultar($objTarjaAssinaturaDTO);

          if ($objTarjaAssinaturaDTO != null) {

            $strLinkAcessoExterno = '';
            if (strpos($objTarjaAssinaturaDTO->getStrTexto(), '@link_acesso_externo_processo@') !== false) {
              $objEditorRN = new EditorRN();
              $strLinkAcessoExterno = $objEditorRN->recuperarLinkAcessoExterno($objDocumentoDTO);
            }

            $strTarja = $objTarjaAssinaturaDTO->getStrTexto();
            $strTarja = preg_replace("/@qr_code@/s", '<img align="center" alt="QRCode Assinatura" title="QRCode Assinatura" src="data:image/png;base64,'.$objDocumentoDTO->getStrQrCodeAssinatura().'" />', $strTarja);
            $strTarja = preg_replace("/@codigo_verificador@/s", $objDocumentoDTO->getStrProtocoloDocumentoFormatado(), $strTarja);
            $strTarja = preg_replace("/@crc_assinatura@/s", $objDocumentoDTO->getStrCrcAssinatura(), $strTarja);
            $strTarja = preg_replace("/@link_acesso_externo_processo@/s", $strLinkAcessoExterno, $strTarja);
            $strRet .= $strTarja;
          }
        }
        $strRet = EditorINT::formatarNaoSelecionavel($strRet);
      }

      return EditorRN::converterHTML($strRet);

    } catch (Exception $e) {
      throw new InfraException('Erro montando tarja de assinatura.',$e);
    }
  }

  /*
   protected function desativarRN1325Controlado($arrObjAssinaturaDTO){
  try {

  //Valida Permissao
  SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_desativar');

  //Regras de Negocio
  //$objInfraException = new InfraException();

  //$objInfraException->lancarValidacoes();

  $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
  for($i=0;$i<count($arrObjAssinaturaDTO);$i++){
  $objAssinaturaBD->desativar($arrObjAssinaturaDTO[$i]);
  }

  //Auditoria

  }catch(Exception $e){
  throw new InfraException('Erro desativando Assinatura.',$e);
  }
  }
  */

  /*
   protected function reativarRN1326Controlado($arrObjAssinaturaDTO){
  try {

  //Valida Permissao
  SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_reativar');

  //Regras de Negocio
  //$objInfraException = new InfraException();

  //$objInfraException->lancarValidacoes();

  $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
  for($i=0;$i<count($arrObjAssinaturaDTO);$i++){
  $objAssinaturaBD->reativar($arrObjAssinaturaDTO[$i]);
  }

  //Auditoria

  }catch(Exception $e){
  throw new InfraException('Erro reativando Assinatura.',$e);
  }
  }
  */

  /*
   protected function bloquearRN1327Controlado(AssinaturaDTO $objAssinaturaDTO){
  try {

  //Valida Permissao
  SessaoSEI::getInstance()->validarAuditarPermissao('assinatura_consultar');

  //Regras de Negocio
  //$objInfraException = new InfraException();

  //$objInfraException->lancarValidacoes();

  $objAssinaturaBD = new AssinaturaBD($this->getObjInfraIBanco());
  $ret = $objAssinaturaBD->bloquear($objAssinaturaDTO);

  //Auditoria

  return $ret;
  }catch(Exception $e){
  throw new InfraException('Erro bloqueando Assinatura.',$e);
  }
  }
  */
  
 public function listarTiposAssinatura(){
   try {

     $objArrTipoDTO = array();

     $objTipoDTO = new TipoDTO();
     $objTipoDTO->setStrStaTipo(self::$TA_CERTIFICADO_DIGITAL);
     $objTipoDTO->setStrDescricao('Assinatura Digital');
     $objArrTipoDTO[] = $objTipoDTO;

     $objTipoDTO = new TipoDTO();
     $objTipoDTO->setStrStaTipo(self::$TA_SENHA);
     $objTipoDTO->setStrDescricao('Assinatura Eletrônica');
     $objArrTipoDTO[] = $objTipoDTO;

     $objTipoDTO = new TipoDTO();
     $objTipoDTO->setStrStaTipo(self::$TA_MODULO);
     $objTipoDTO->setStrDescricao('Assinatura por Módulo');
     $objArrTipoDTO[] = $objTipoDTO;

     return $objArrTipoDTO;

   }catch(Exception $e){
     throw new InfraException('Erro listando tipos de assinatura.',$e);
   }
 }
}
?>