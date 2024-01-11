<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/06/2008 - criado por mga
*
* Versão do Gerador de Código: 1.18.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AnexoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function obterDiretorio(AnexoDTO $objAnexoDTO){
    try{
      return ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/'.substr($objAnexoDTO->getDthInclusao(),6,4).'/'.substr($objAnexoDTO->getDthInclusao(),3,2) .'/' .substr($objAnexoDTO->getDthInclusao(),0,2);
    }catch(Exception $e){
      throw new InfraException('Erro obtendo diretório do anexo.',$e);
    }
  }
  
  public function obterLocalizacao(AnexoDTO $objAnexoDTO){
    try{
      return $this->obterDiretorio($objAnexoDTO).'/'.$objAnexoDTO->getNumIdAnexo();
    }catch(Exception $e){
      throw new InfraException('Erro obtendo localização do anexo.',$e);
    }
  }

  protected function gerarNomeArquivoTemporarioConectado($strSufixo = null){
    try{
      //retonar sempre pelo menos um caracter no nome do arquivo (em alguns lugares é testado se o IdAnexo não é numérico)
      return BancoSEI::getInstance()->getValorSequencia('seq_upload').'_'.InfraUtil::formatarNomeArquivo(md5($strSufixo . '_' . uniqid(mt_rand())) . $strSufixo);
    }catch(Exception $e){
      throw new InfraException('Erro gerando nome de arquivo temporário.',$e);
    }
  }

  protected function montarNomeUploadConectado(AnexoDTO $objAnexoDTO){
    try{
      return '['.$this->getObjInfraIBanco()->getValorSequencia('seq_upload').']'.InfraUtil::montarNomeArquivoUpload($objAnexoDTO->getStrSiglaUsuario(), time(), $objAnexoDTO->getStrNome());
    }catch(Exception $e){
      throw new InfraException('Erro montando nome de arquivo para upload.',$e);
    }
  }

  protected function cadastrarRN0172Controlado(AnexoDTO $objAnexoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_cadastrar',__METHOD__,$objAnexoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeRN0228($objAnexoDTO, $objInfraException);
      $this->validarProtocoloBaseConhecimento($objAnexoDTO, $objInfraException);
      $this->validarNumIdUnidadeRN0834($objAnexoDTO, $objInfraException);
      $this->validarNumIdUsuarioRN0866($objAnexoDTO, $objInfraException);
      $this->validarNumTamanhoRN0867($objAnexoDTO, $objInfraException);
      $this->validarDthInclusaoRN0868($objAnexoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0886($objAnexoDTO, $objInfraException);
            
      $strNomeUpload = $objAnexoDTO->getNumIdAnexo();
      $strNomeUploadCompleto = DIR_SEI_TEMP.'/'.$strNomeUpload;
      
      if (!file_exists($strNomeUploadCompleto)){
        $objInfraException->lancarValidacao('Anexo '.$objAnexoDTO->getStrNome().' não encontrado.');
      }
      
      if (filesize($strNomeUploadCompleto)==0){
        $objInfraException->lancarValidacao('Anexo '.$objAnexoDTO->getStrNome().' vazio.');
      }

      $this->validarConteudo($strNomeUploadCompleto, $objAnexoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();           
      
      $objAnexoDTO->setStrHash(hash_file('md5',$strNomeUploadCompleto));
           
      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());      
      $ret = $objAnexoBD->cadastrar($objAnexoDTO);
      
      $strDiretorio = $this->obterDiretorio($objAnexoDTO);
       
      if (is_dir($strDiretorio) === false){
        if (mkdir($strDiretorio,0755,true) === false){
          throw new InfraException('Erro criando diretório "' .$strDiretorio.'".');
        }
      }
       
      if (copy($strNomeUploadCompleto, $strDiretorio.'/'.$ret->getNumIdAnexo()) === false){
        throw new InfraException('Falha copiando anexo para o repositório de arquivos.');
      }

      if ($objAnexoDTO->getStrHash() != hash_file('md5',$strDiretorio.'/'.$ret->getNumIdAnexo())){
        throw new InfraException('Cópia do anexo no repositório de arquivos corrompida.');
      }

      if (!$objAnexoDTO->isSetStrSinExclusaoAutomatica() || $objAnexoDTO->getStrSinExclusaoAutomatica() == 'S' ) {
        unlink($strNomeUploadCompleto);
      }

      return $ret;
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Anexo.',$e);
    }
  }

/*
  protected function alterarControlado(AnexoDTO $objAnexoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('anexo_alterar');

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAnexoDTO->isSetStrNome()){
        $this->validarStrNome($objAnexoDTO, $objInfraException);
      }
      if ($objAnexoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objAnexoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      $objAnexoBD->alterar($objAnexoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Anexo.',$e);
    }
  }
*/
  protected function excluirRN0226Controlado($arrObjAnexoDTO){
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_excluir',__METHOD__,$arrObjAnexoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAnexoDTO);$i++){       
        $objAnexoBD->excluir($arrObjAnexoDTO[$i]);
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Anexo.',$e);
    }
  }

  protected function consultarRN0736Conectado(AnexoDTO $objAnexoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_consultar',__METHOD__,$objAnexoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      $ret = $objAnexoBD->consultar($objAnexoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Anexo.',$e);
    }
  }

  protected function listarRN0218Conectado(AnexoDTO $objAnexoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_listar',__METHOD__,$objAnexoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      $ret = $objAnexoBD->listar($objAnexoDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Anexos.',$e);
    }
  }

  protected function contarRN0734Conectado(AnexoDTO $objAnexoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_listar',__METHOD__,$objAnexoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      $ret = $objAnexoBD->contar($objAnexoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Anexos.',$e);
    }
  }

  protected function desativarRN0745Controlado($arrObjAnexoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_desativar',__METHOD__,$arrObjAnexoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAnexoDTO);$i++){
        $objAnexoBD->desativar($arrObjAnexoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Anexo.',$e);
    }
  }

  protected function reativarRN0746Controlado($arrObjAnexoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_reativar',__METHOD__,$arrObjAnexoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAnexoDTO);$i++){
        $objAnexoBD->reativar($arrObjAnexoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Anexo.',$e);
    }
  }
 
  private function validarStrNomeRN0228(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnexoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome do anexo não informado.');
    }else{

      $objAnexoDTO->setStrNome(trim($objAnexoDTO->getStrNome()));
      
      if (strpos($objAnexoDTO->getStrNome(),'&#')!==false){
        $objInfraException->adicionarValidacao('Nome do anexo possui caracteres especiais.');
      }
      
      if (strlen($objAnexoDTO->getStrNome())>255){
        $objInfraException->adicionarValidacao('Nome do anexo possui tamanho superior a 255 caracteres.');
      }
      
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      
      if ($objInfraParametro->getValor('SEI_HABILITAR_VALIDACAO_EXTENSAO_ARQUIVOS')=='1' && (!$objAnexoDTO->isSetStrSinDuplicando() || $objAnexoDTO->getStrSinDuplicando()=='N')){
        
        $arrStrNome = explode(".", $objAnexoDTO->getStrNome());
        
        if (count($arrStrNome) < 2){

          $objInfraException->adicionarValidacao('Nome do arquivo não possui extensão.');

        }else {

          $strExtensao = str_replace(' ', '', InfraString::transformarCaixaBaixa($arrStrNome[count($arrStrNome) - 1]));

          if (in_array($strExtensao, array('php', 'php3', 'php4', 'phtml', 'sh', 'cgi'))) {
            $objInfraException->adicionarValidacao('Extensão do arquivo não permitida por restrição de segurança.');
          }

          $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
          $objArquivoExtensaoDTO->retNumIdArquivoExtensao();
          $objArquivoExtensaoDTO->setStrExtensao($strExtensao);

          if (SessaoSEI::getInstance()->isBolHabilitada()){
            $objArquivoExtensaoDTO->setStrSinInterface('S');
          }else{
            $objArquivoExtensaoDTO->setStrSinServico('S');
          }

          $objArquivoExtensaoDTO->setNumMaxRegistrosRetorno(1);

          $objArquivoExtensaoRN = new ArquivoExtensaoRN();
          if ($objArquivoExtensaoRN->consultar($objArquivoExtensaoDTO) == null) {
            if (SessaoSEI::getInstance()->isBolHabilitada()){
              $objInfraException->adicionarValidacao('Tipo do arquivo ".'.$strExtensao.'" não autorizado para inclusão via interface.');
            }else{
              $objInfraException->adicionarValidacao('Tipo do arquivo ".'.$strExtensao.'" não autorizado para inclusão via serviços.');
            }
          }
        }
      }
    }    
  }

  private function validarProtocoloBaseConhecimento(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (!$objAnexoDTO->isSetDblIdProtocolo() && !$objAnexoDTO->isSetNumIdBaseConhecimento()){
      $objInfraException->adicionarValidacao('Protocolo ou Base de Conhecimento do anexo não informado.');
    }
  }

  private function validarNumIdUnidadeRN0834(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnexoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade do anexo não informada.');
    }
  }
  
  private function validarNumIdUsuarioRN0866(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnexoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário do anexo não informado.');
    }
  }

  private function validarNumTamanhoRN0867(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnexoDTO->getNumTamanho())){
      $objInfraException->adicionarValidacao('Tamanho do anexo não informado.');
    }

    $arrStrNome = explode(".", $objAnexoDTO->getStrNome());

    if (count($arrStrNome) < 2){
      $objInfraException->adicionarValidacao('Nome do arquivo não possui extensão.');
    }else {

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numTamDocExterno = $objInfraParametro->getValor('SEI_TAM_MB_DOC_EXTERNO');

      $objArquivoExtensaoDTO = new ArquivoExtensaoDTO();
      $objArquivoExtensaoDTO->retStrExtensao();
      $objArquivoExtensaoDTO->retNumTamanhoMaximo();
      $objArquivoExtensaoDTO->setStrExtensao(str_replace(' ', '', InfraString::transformarCaixaBaixa($arrStrNome[count($arrStrNome) - 1])));
      $objArquivoExtensaoDTO->setNumTamanhoMaximo(null,InfraDTO::$OPER_DIFERENTE);
      $objArquivoExtensaoDTO->setNumMaxRegistrosRetorno(1);

      $objArquivoExtensaoRN = new ArquivoExtensaoRN();
      $objArquivoExtensaoDTO = $objArquivoExtensaoRN->consultar($objArquivoExtensaoDTO);
      
      if ($objArquivoExtensaoDTO!=null) {
        if ($objArquivoExtensaoDTO->getNumTamanhoMaximo() > $numTamDocExterno){
          $objInfraException->adicionarValidacao('Limite de tamanho '.$objArquivoExtensaoDTO->getNumTamanhoMaximo().'Mb configurado para a extensão '.InfraString::transformarCaixaAlta($objArquivoExtensaoDTO->getStrExtensao()).' é maior que o limite geral de '.$numTamDocExterno.'Mb.');
        }else {

          if ($objAnexoDTO->getNumTamanho() > ($objArquivoExtensaoDTO->getNumTamanhoMaximo() * 1024 * 1024)) {
            $objInfraException->adicionarValidacao('O tamanho máximo permitido para arquivos ' . InfraString::transformarCaixaAlta($objArquivoExtensaoDTO->getStrExtensao()) . ' é ' . $objArquivoExtensaoDTO->getNumTamanhoMaximo() . 'Mb.');
          }
        }
      }else {
        if ($objAnexoDTO->getNumTamanho() > ($numTamDocExterno * 1024 * 1024)) {
          $objInfraException->adicionarValidacao('O tamanho máximo geral permitido para documentos externos é ' . $numTamDocExterno . 'Mb.');
        }
      }
    }
  }
  
  private function validarDthInclusaoRN0868(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnexoDTO->getDthInclusao())){
      $objInfraException->adicionarValidacao('Data de inclusão do anexo não informada.');
    }else{
      if (!InfraData::validarDataHora($objAnexoDTO->getDthInclusao())){
        $objInfraException->adicionarValidacao('Data de inclusão do anexo inválida.');
      }
    }
  }
  
  private function validarStrSinAtivoRN0886(AnexoDTO $objAnexoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAnexoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objAnexoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function adicionarControlado(AnexoDTO $parObjAnexoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_cadastrar',__METHOD__,$parObjAnexoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNomeRN0228($parObjAnexoDTO, $objInfraException);
      $this->validarNumIdUnidadeRN0834($parObjAnexoDTO, $objInfraException);
      $this->validarNumIdUsuarioRN0866($parObjAnexoDTO, $objInfraException);
      $this->validarNumTamanhoRN0867($parObjAnexoDTO, $objInfraException);
      $this->validarDthInclusaoRN0868($parObjAnexoDTO, $objInfraException);

      $strNomeUpload = $parObjAnexoDTO->getNumIdAnexo();
      $strNomeUploadCompleto = DIR_SEI_TEMP.'/'.$strNomeUpload;

      if (!file_exists($strNomeUploadCompleto)){
        $objInfraException->lancarValidacao('Arquivo '.$parObjAnexoDTO->getStrNome().' não encontrado.');
      }

      if (filesize($strNomeUploadCompleto)==0){
        $objInfraException->lancarValidacao('Arquivo '.$parObjAnexoDTO->getStrNome().' vazio.');
      }

      $objInfraException->lancarValidacoes();

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setDblIdProtocolo(null);
      $objAnexoDTO->setNumIdBaseConhecimento(null);
      $objAnexoDTO->setNumIdProjeto(null);
      $objAnexoDTO->setStrNome($parObjAnexoDTO->getStrNome());
      $objAnexoDTO->setNumIdUnidade($parObjAnexoDTO->getNumIdUnidade());
      $objAnexoDTO->setNumIdUsuario($parObjAnexoDTO->getNumIdUsuario());
      $objAnexoDTO->setNumTamanho($parObjAnexoDTO->getNumTamanho());
      $objAnexoDTO->setDthInclusao($parObjAnexoDTO->getDthInclusao());
      $objAnexoDTO->setStrHash($parObjAnexoDTO->getStrHash());

      if (filesize($strNomeUploadCompleto) == $parObjAnexoDTO->getNumTamanho()){

        if (hash_file('md5',$strNomeUploadCompleto) != $parObjAnexoDTO->getStrHash()){
          $objInfraException->lancarValidacao('Conteúdo do arquivo corrompido.');
        }

        $this->validarConteudo($strNomeUploadCompleto, $objAnexoDTO, $objInfraException);

        $objAnexoDTO->setStrSinAtivo('S');

      }else{

        $objAnexoDTO->setStrSinAtivo('N');

      }


      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      $ret = $objAnexoBD->cadastrar($objAnexoDTO);

      $strDiretorio = $this->obterDiretorio($objAnexoDTO);

      if (is_dir($strDiretorio) === false){
        if (mkdir($strDiretorio,0755,true) === false){
          throw new InfraException('Erro criando diretório "' .$strDiretorio.'".');
        }
      }

      copy($strNomeUploadCompleto, $strDiretorio.'/'.$ret->getNumIdAnexo());

      unlink($strNomeUploadCompleto);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro adicionando arquivo.',$e);
    }
  }

  protected function adicionarConteudoControlado(AnexoDTO $parObjAnexoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_cadastrar',__METHOD__,$parObjAnexoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setBolExclusaoLogica(false);
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retDblIdProtocolo();
      $objAnexoDTO->retNumIdBaseConhecimento();
      $objAnexoDTO->retNumIdProjeto();
      $objAnexoDTO->retNumIdUnidade();
      $objAnexoDTO->retNumIdUsuario();
      $objAnexoDTO->retStrSinAtivo();
      $objAnexoDTO->retNumTamanho();
      $objAnexoDTO->retStrHash();
      $objAnexoDTO->retDthInclusao();
      $objAnexoDTO->setNumIdAnexo($parObjAnexoDTO->getNumIdAnexoOrigem());
      $objAnexoDTO = $this->consultarRN0736($objAnexoDTO);

      if ($objAnexoDTO==null){
        $objInfraException->lancarValidacao('Arquivo não encontrado na base de dados.');
      }

      $strDiretorio = $this->obterDiretorio($objAnexoDTO);

      if (!file_exists($strDiretorio.'/'.$objAnexoDTO->getNumIdAnexo())){
        $objInfraException->lancarValidacao('Arquivo não encontrado no repositório.');
      }

      if ($objAnexoDTO->getDblIdProtocolo()!=null){
        $objInfraException->lancarValidacao('Arquivo já está associado com um protocolo.');
      }

      if ($objAnexoDTO->getNumIdBaseConhecimento()!=null){
        $objInfraException->lancarValidacao('Arquivo já está associado com uma base de conhecimento.');
      }

      if ($objAnexoDTO->getNumIdProjeto()!=null){
        $objInfraException->lancarValidacao('Arquivo já está associado com um projeto.');
      }

      if ($objAnexoDTO->getNumIdUnidade() <> $parObjAnexoDTO->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Arquivo não foi adicionado pela unidade.');
      }

      if ($objAnexoDTO->getNumIdUsuario() <> $parObjAnexoDTO->getNumIdUsuario()){
        $objInfraException->lancarValidacao('Arquivo não foi adicionado pelo usuário.');
      }

      if ($objAnexoDTO->getStrSinAtivo()=='S'){
        $objInfraException->lancarValidacao('Arquivo já foi ativado.');
      }

      $strNomeUpload = $parObjAnexoDTO->getNumIdAnexo();
      $strNomeUploadCompleto = DIR_SEI_TEMP.'/'.$strNomeUpload;

      if (!file_exists($strNomeUploadCompleto)){
        $objInfraException->lancarValidacao('Conteúdo para adição no arquivo não encontrado.');
      }

      if (filesize($strNomeUploadCompleto)==0){
        $objInfraException->lancarValidacao('Conteúdo para adição no arquivo vazio.');
      }

      $objInfraException->lancarValidacoes();

      $fp = fopen($strDiretorio.'/'.$objAnexoDTO->getNumIdAnexo(),'ab');
      fwrite($fp,file_get_contents($strNomeUploadCompleto),filesize($strNomeUploadCompleto));
      fclose($fp);

      $numTamanhoArquivo = filesize($strDiretorio.'/'.$objAnexoDTO->getNumIdAnexo());

      if ($numTamanhoArquivo > $objAnexoDTO->getNumTamanho()){
        $objInfraException->lancarValidacao('Conteúdo do arquivo excede o tamanho definido.');
      }

      if ($numTamanhoArquivo == $objAnexoDTO->getNumTamanho()){

        if (hash_file('md5',$strDiretorio.'/'.$objAnexoDTO->getNumIdAnexo()) != $objAnexoDTO->getStrHash()){
          $objInfraException->lancarValidacao('Conteúdo do arquivo corrompido.');
        }

        $this->validarConteudo($strDiretorio.'/'.$objAnexoDTO->getNumIdAnexo(), $objAnexoDTO, $objInfraException);

        $dto = new AnexoDTO();
        $dto->setStrSinAtivo('S');
        $dto->setNumIdAnexo($objAnexoDTO->getNumIdAnexo());

        $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
        $objAnexoBD->alterar($dto);

        return true;
      }

      unlink($strNomeUploadCompleto);

      return false;

    }catch(Exception $e){
      throw new InfraException('Erro adicionando conteúdo no arquivo.',$e);
    }
  }

  protected function associarControlado(AnexoDTO $parObjAnexoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('anexo_cadastrar',__METHOD__,$parObjAnexoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setBolExclusaoLogica(false);
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->retStrNome();
      $objAnexoDTO->retDblIdProtocolo();
      $objAnexoDTO->retNumIdBaseConhecimento();
      $objAnexoDTO->retNumIdProjeto();
      $objAnexoDTO->retNumIdUnidade();
      $objAnexoDTO->retNumIdUsuario();
      $objAnexoDTO->retDthInclusao();
      $objAnexoDTO->retStrSinAtivo();
      $objAnexoDTO->setNumIdAnexo($parObjAnexoDTO->getNumIdAnexoOrigem());
      $objAnexoDTO = $this->consultarRN0736($objAnexoDTO);

      if ($objAnexoDTO==null){
        $objInfraException->lancarValidacao('Arquivo não encontrado na base de dados.');
      }

      $strDiretorio = $this->obterDiretorio($objAnexoDTO);

      if (!file_exists($strDiretorio.'/'.$objAnexoDTO->getNumIdAnexo())){
        $objInfraException->lancarValidacao('Arquivo não encontrado no repositório.');
      }

      if ($objAnexoDTO->getDblIdProtocolo()!=null){
        $objInfraException->lancarValidacao('Arquivo já está associado com um protocolo.');
      }

      if ($objAnexoDTO->getNumIdBaseConhecimento()!=null){
        $objInfraException->lancarValidacao('Arquivo já está associado com uma base de conhecimento.');
      }

      if ($objAnexoDTO->getNumIdProjeto()!=null){
        $objInfraException->lancarValidacao('Arquivo já está associado com um projeto.');
      }

      if ($objAnexoDTO->getNumIdUnidade() <> $parObjAnexoDTO->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Arquivo não foi adicionado pela unidade.');
      }

      if ($objAnexoDTO->getNumIdUsuario() <> $parObjAnexoDTO->getNumIdUsuario()){
        $objInfraException->lancarValidacao('Arquivo não foi adicionado pelo usuário.');
      }

      if ($objAnexoDTO->getStrSinAtivo()=='N'){
        $objInfraException->lancarValidacao('Arquivo não foi ativado.');
      }

      $objInfraException->lancarValidacoes();

      $dto = new AnexoDTO();
      $dto->setDblIdProtocolo($parObjAnexoDTO->getDblIdProtocolo());
      $dto->setNumIdAnexo($objAnexoDTO->getNumIdAnexo());

      $objAnexoBD = new AnexoBD($this->getObjInfraIBanco());
      $objAnexoBD->alterar($dto);

      $parObjAnexoDTO->setNumIdAnexo($objAnexoDTO->getNumIdAnexo());
      $parObjAnexoDTO->setStrNome($objAnexoDTO->getStrNome());

    }catch(Exception $e){
      throw new InfraException('Erro associando arquivo.',$e);
    }
  }

  protected function verificarRepositorioArquivosConectado(AnexoDTO $parObjAnexoDTO){
    try{

      $objInfraException = new InfraException();

      LimiteSEI::getInstance()->configurarNivel3();

      $numSeg = InfraUtil::verificarTempoProcessamento();

      $this->logar('Verificação Repositório Arquivos - Iniciando...');

      if (!InfraData::validarData($parObjAnexoDTO->getDthInclusao())){
        $objInfraException->lancarValidacao('Data ['.$parObjAnexoDTO->getDthInclusao().'] inválida.');
      }

      $objAnexoRN = new AnexoRN();
      $objProtocoloRN = new ProtocoloRN();
      $objBaseConhecimentoRN = new BaseConhecimentoRN();

      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->setBolExclusaoLogica(false);
      $objAnexoDTO->retDthInclusao();

      if ($parObjAnexoDTO->getDthInclusao()!=null){
        $objAnexoDTO->setDthInclusao($parObjAnexoDTO->getDthInclusao(),InfraDTO::$OPER_MAIOR_IGUAL);
      }

      $objAnexoDTO->setNumMaxRegistrosRetorno(1);

      $objAnexoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objAnexoDTOMinimo = $objAnexoRN->consultarRN0736($objAnexoDTO);

      $objAnexoDTO->setOrdDthInclusao(InfraDTO::$TIPO_ORDENACAO_DESC);
      $objAnexoDTOMaximo = $objAnexoRN->consultarRN0736($objAnexoDTO);

      $dtaAtual = substr($objAnexoDTOMinimo->getDthInclusao(),0,10);
      $dtaFinal = substr($objAnexoDTOMaximo->getDthInclusao(),0,10);

      $numAno = null;
      $numMes = null;

      $numArquivosTotal = 0;
      $numArquivosErro = 0;

      while (InfraData::compararDatas($dtaAtual, $dtaFinal)>=0){

        $numAnoAtual = substr($dtaAtual,6,4);
        $numMesAtual = substr($dtaAtual,3,2);
        $numDiaAtual = substr($dtaAtual,0,2);

        if ($numMes!=$numMesAtual || $numAno!=$numAnoAtual) {
          $this->logar('Verificação Repositório Arquivos - ' . $numMesAtual . '/' . $numAnoAtual . '...');
          $numAno = $numAnoAtual;
          $numMes = $numMesAtual;
        }

        $objAnexoDTO = new AnexoDTO();
        $objAnexoDTO->setBolExclusaoLogica(false);
        $objAnexoDTO->retNumIdAnexo();
        $objAnexoDTO->retDblIdProtocolo();
        $objAnexoDTO->retNumIdBaseConhecimento();
        $objAnexoDTO->retStrHash();
        $objAnexoDTO->retNumTamanho();
        $objAnexoDTO->adicionarCriterio(array('Inclusao','Inclusao'),
                                        array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
                                        array($dtaAtual.' 00:00:00', $dtaAtual.' 23:59:59'),
                                        InfraDTO::$OPER_LOGICO_AND);
        $objAnexoDTO->setOrdNumIdAnexo(InfraDTO::$TIPO_ORDENACAO_ASC);

        $arrObjAnexoDTO = $objAnexoRN->listarRN0218($objAnexoDTO);

        foreach($arrObjAnexoDTO as $objAnexoDTO){

          $strCaminhoArquivo = ConfiguracaoSEI::getInstance()->getValor('SEI','RepositorioArquivos').'/'.$numAnoAtual.'/'.$numMesAtual.'/'.$numDiaAtual.'/'.$objAnexoDTO->getNumIdAnexo();

          $strMsg = '';
          if (!file_exists($strCaminhoArquivo)){
            $strMsg = $strCaminhoArquivo.' não encontrado ';
          }else if (filesize($strCaminhoArquivo) != $objAnexoDTO->getNumTamanho()){
            $strMsg = $strCaminhoArquivo.' tamanho diferente ';
          }else if (md5_file($strCaminhoArquivo) != $objAnexoDTO->getStrHash()){
            $strMsg = $strCaminhoArquivo.' conteúdo corrompido ';
          }

          if ($strMsg!=''){

            if ($objAnexoDTO->getDblIdProtocolo()!=null){
              $objProtocoloDTO = new ProtocoloDTO();
              $objProtocoloDTO->retStrProtocoloFormatado();
              $objProtocoloDTO->setDblIdProtocolo($objAnexoDTO->getDblIdProtocolo());
              $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

              if ($objProtocoloDTO==null){
                $strMsg .= '(protocolo associado ID '.$objAnexoDTO->getDblIdProtocolo().' não encontrado)';
              }else{
                $strMsg .= '(protocolo associado '.$objProtocoloDTO->getStrProtocoloFormatado().')';
              }


            }else if ($objAnexoDTO->getNumIdBaseConhecimento()!=null){
              $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
              $objBaseConhecimentoDTO->retStrDescricao();
              $objBaseConhecimentoDTO->retStrSiglaUnidade();
              $objBaseConhecimentoDTO->setNumIdBaseConhecimento($objAnexoDTO->getNumIdBaseConhecimento());
              $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);

              if ($objBaseConhecimentoDTO==null){
                $strMsg .= '(base de conhecimento associada ID '.$objAnexoDTO->getNumIdBaseConhecimento().' não encontrada)';
              }else{
                $strMsg .= '(base de conhecimento associada "'.$objBaseConhecimentoDTO->getStrDescricao().'" da unidade '.$objBaseConhecimentoDTO->getStrSiglaUnidade().')';
              }
            }

            $this->logar('Verificação Repositório Arquivos - '.$strMsg, InfraLog::$ERRO);

            $numArquivosErro++;
          }
          $numArquivosTotal++;
        }

        $dtaAtual = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtaAtual);
      }

      $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

      $this->logar('Verificação Repositório Arquivos - '.InfraUtil::formatarMilhares($numArquivosTotal).' arquivos verificados em '.InfraData::formatarTimestamp($numSeg). ' ('.InfraUtil::formatarMilhares($numArquivosErro).' erros)');

      $objInfraException->lancarValidacao('Operação Finalizada.');

    }catch(Exception $e){
      throw new InfraException('Erro verificando repositório de arquivos.',$e);
    }
  }

  private function logar($strTexto, $strTipoLog='I'){
    InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($strTexto));
    LogSEI::getInstance()->gravar($strTexto,$strTipoLog);
  }

  private function validarConteudo($strArquivo, AnexoDTO $objAnexoDTO, $objInfraException){

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $strMime = finfo_file($finfo, $strArquivo);
    finfo_close($finfo);

    if (strpos($strMime, 'text/x-php') !== false || strpos($strMime, 'text/x-shellscript') !== false) {
      $objInfraException->lancarValidacao('Conteúdo do anexo não permitido por restrição de segurança.');
    }else if (strpos($strMime, 'text/html') !== false){

      $dblIdDocumento = null;
      if ($objAnexoDTO->isSetDblIdProtocolo() && $objAnexoDTO->getDblIdProtocolo()!=null){
        $dblIdDocumento = $objAnexoDTO->getDblIdProtocolo();
      }

      $strConteudo = file_get_contents($strArquivo);

      SeiINT::validarXss($strConteudo, false, false, null, $dblIdDocumento);
    }

  }
}
?>