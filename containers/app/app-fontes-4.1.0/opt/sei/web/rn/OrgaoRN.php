<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoRN extends InfraRN {
  
  public static $TCO_NENHUM = 'N';
  public static $TCO_LICENCIADO = 'L';
  public static $TCO_NATIVO_NAVEGADOR = 'B';
  
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrSiglaRN1346(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objOrgaoDTO->setStrSigla(trim($objOrgaoDTO->getStrSigla()));

      if (strlen($objOrgaoDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricaoRN1347(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objOrgaoDTO->setStrDescricao(trim($objOrgaoDTO->getStrDescricao()));

      if (strlen($objOrgaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivoRN1348(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objOrgaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }
  
  private function validarStrSinEnvioProcesso(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSinEnvioProcesso())){
      $objInfraException->adicionarValidacao('Sinalizador de Envio de Processo não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objOrgaoDTO->getStrSinEnvioProcesso())){
        $objInfraException->adicionarValidacao('Sinalizador de Envio de Processo inválido.');
      }
    }
  }

  private function validarStrSinFederacaoEnvio(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSinFederacaoEnvio())){
      $objInfraException->adicionarValidacao('Sinalizador de Envio para o SEI Federação não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objOrgaoDTO->getStrSinFederacaoEnvio())){
        $objInfraException->adicionarValidacao('Sinalizador de Envio para o SEI Federação inválido.');
      }
    }
  }

  private function validarStrSinFederacaoRecebimento(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSinFederacaoRecebimento())){
      $objInfraException->adicionarValidacao('Sinalizador de Recebimento do SEI Federação não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objOrgaoDTO->getStrSinFederacaoRecebimento())){
        $objInfraException->adicionarValidacao('Sinalizador de Recebimento do SEI Federação inválido.');
      }
    }
  }

  private function validarNumIdUnidade(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getNumIdUnidade())){
      $objOrgaoDTO->setNumIdUnidade(null);
    }
  }

  private function validarStrSinPublicacao(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSinPublicacao())){
      $objInfraException->adicionarValidacao('Sinalizador de Publicação não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objOrgaoDTO->getStrSinPublicacao())){
        $objInfraException->adicionarValidacao('Sinalizador de Publicação inválido.');
      }
    }
  }

  private function validarStrSinConsultaProcessual(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrSinConsultaProcessual())){
      $objInfraException->adicionarValidacao('Sinalizador de Consulta Processual não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objOrgaoDTO->getStrSinConsultaProcessual())){
        $objInfraException->adicionarValidacao('Sinalizador de Consulta Processual inválido.');
      }
    }
  }

  private function validarStrNomeArquivo(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (!InfraString::isBolVazia($objOrgaoDTO->getStrNomeArquivo()) && $objOrgaoDTO->getStrNomeArquivo()!="*REMOVER*"){
      if (!file_exists(DIR_SEI_TEMP.'/'.$objOrgaoDTO->getStrNomeArquivo())) {
        $objInfraException->adicionarValidacao('Não foi possível abrir arquivo da imagem.');
      }
    }
  }
  
  private function validarStrNumeracao(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrNumeracao())){
      $objOrgaoDTO->setStrNumeracao(null);
    }else{
      $objOrgaoDTO->setStrNumeracao(trim($objOrgaoDTO->getStrNumeracao()));

      if (strlen($objOrgaoDTO->getStrNumeracao())>250){
        $objInfraException->adicionarValidacao('Formato da numeração possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrServidorCorretorOrtografico(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrServidorCorretorOrtografico())){
      $objOrgaoDTO->setStrServidorCorretorOrtografico(null);
    }else{
      $objOrgaoDTO->setStrServidorCorretorOrtografico(trim($objOrgaoDTO->getStrServidorCorretorOrtografico()));
  
      if (strlen($objOrgaoDTO->getStrServidorCorretorOrtografico())>250){
        $objInfraException->adicionarValidacao('Endereço do servidor de correção ortográfica possui tamanho superior a 250 caracteres.');
      }
    }
  }
  
  private function validarStrCodigoSei(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrCodigoSei())){
      $objOrgaoDTO->setStrCodigoSei(null);
    }else{
      $objOrgaoDTO->setStrCodigoSei(trim($objOrgaoDTO->getStrCodigoSei()));
  
      if (strlen($objOrgaoDTO->getStrCodigoSei())>10){
        $objInfraException->adicionarValidacao('Código SEI possui tamanho superior a 10 caracteres.');
      }
    }
  }
  
  private function validarStrStaCorretorOrtografico(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrStaCorretorOrtografico()) || (
        $objOrgaoDTO->getStrStaCorretorOrtografico()!=self::$TCO_NATIVO_NAVEGADOR &&
        $objOrgaoDTO->getStrStaCorretorOrtografico()!=self::$TCO_NENHUM &&
        $objOrgaoDTO->getStrStaCorretorOrtografico()!=self::$TCO_LICENCIADO )) {
      
        $objInfraException->adicionarValidacao('Corretor Ortográfico não informado.');
      
    } else if ($objOrgaoDTO->getStrStaCorretorOrtografico()!=self::$TCO_LICENCIADO ) {
      $objOrgaoDTO->setStrServidorCorretorOrtografico(null);
    }
  }

  private function validarStrIdxOrgao(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objOrgaoDTO->getStrIdxOrgao())){
      $objOrgaoDTO->setStrIdxOrgao(null);
    }else{
      $objOrgaoDTO->setStrIdxOrgao(trim($objOrgaoDTO->getStrIdxOrgao()));

      if (strlen($objOrgaoDTO->getStrIdxOrgao())>500){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarUnidadeRecebimentoFederacao(OrgaoDTO $objOrgaoDTO, InfraException $objInfraException){
    if ($objOrgaoDTO->getStrSinFederacaoRecebimento()=='S' && InfraString::isBolVazia($objOrgaoDTO->getNumIdUnidade())){
      $objInfraException->lancarValidacao('Unidade para recebimento de processos do SEI Federação não informada.');
    }
  }

  protected function cadastrarRN1349Controlado(OrgaoDTO $objOrgaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_cadastrar',__METHOD__,$objOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrSiglaRN1346($objOrgaoDTO, $objInfraException);
      $this->validarStrDescricaoRN1347($objOrgaoDTO, $objInfraException);
      $this->validarStrSinAtivoRN1348($objOrgaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objOrgaoDTO->setStrSinEnvioProcesso('S');
      $objOrgaoDTO->setStrIdOrgaoFederacao(null);
      $objOrgaoDTO->setStrSinFederacaoEnvio('N');
      $objOrgaoDTO->setStrSinFederacaoRecebimento('N');
      $objOrgaoDTO->setNumIdUnidade(null);
      $objOrgaoDTO->setStrSinPublicacao('N');
      $objOrgaoDTO->setStrSinConsultaProcessual('N');

      $objOrgaoDTO->setStrNumeracao(null);
      $objOrgaoDTO->setStrStaCorretorOrtografico(self::$TCO_NENHUM);
      $objOrgaoDTO->setStrCodigoSei(null);

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdTipoContato = $objInfraParametro->getValor('ID_TIPO_CONTATO_ORGAOS');

      $objContatoRN = new ContatoRN();

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrSinAtivo();
      $objContatoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
      $objContatoDTO->setStrNome($objOrgaoDTO->getStrDescricao());
      $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
      $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

      if ($objContatoDTO == null) {

        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato(null);
        $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
        $objContatoDTO->setNumIdContatoAssociado(null);
        $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_JURIDICA);
        $objContatoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
        $objContatoDTO->setStrNome($objOrgaoDTO->getStrDescricao());
        $objContatoDTO->setStrSinEnderecoAssociado('N');
        $objContatoDTO->setStrSinAtivo('S');
        $objContatoDTO->setStrStaOperacao('REPLICACAO');
        $objContatoDTO = $objContatoRN->cadastrarRN0322($objContatoDTO);
      }else{
        if ($objContatoDTO->getStrSinAtivo()=='N'){
          $objContatoRN->reativarRN0452(array($objContatoDTO));
        }
      }

      $objOrgaoDTO->setNumIdContato($objContatoDTO->getNumIdContato());
      $objOrgaoDTO->setStrIdxOrgao(null);

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->cadastrar($objOrgaoDTO);

      $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
      $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
      $objOrgaoHistoricoDTO->setNumIdOrgao($ret->getNumIdOrgao());
      $objOrgaoHistoricoDTO->setStrSigla($ret->getStrSigla());
      $objOrgaoHistoricoDTO->setStrDescricao($ret->getStrDescricao());
      $objOrgaoHistoricoDTO->setDtaInicio(InfraData::getStrDataHoraAtual());
      $objOrgaoHistoricoDTO->setDtaFim(null);
      //flag que indica que o historico é cadastrado a partir do cadastro/alteracao de um orgao em si
      $objOrgaoHistoricoDTO->setBolOrigemSIP(true);
      $objOrgaoHistoricoRN->cadastrar($objOrgaoHistoricoDTO);

      $this->montarIndexacao($ret);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Órgão.',$e);
    }
  }

  protected function alterarRN1350Controlado(OrgaoDTO $objOrgaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('orgao_alterar',__METHOD__,$objOrgaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objOrgaoDTOBanco = new OrgaoDTO();
      $objOrgaoDTOBanco->setBolExclusaoLogica(false);
      $objOrgaoDTOBanco->retNumIdContato();
      $objOrgaoDTOBanco->retStrSigla();
      $objOrgaoDTOBanco->retStrDescricao();
      $objOrgaoDTOBanco->retStrSinFederacaoRecebimento();
      $objOrgaoDTOBanco->retStrIdOrgaoFederacao();
      $objOrgaoDTOBanco->retNumIdUnidade();
      $objOrgaoDTOBanco->retStrIdOrgaoFederacao();
      $objOrgaoDTOBanco->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());

      $objOrgaoDTOBanco = $this->consultarRN1352($objOrgaoDTOBanco);

      if($objOrgaoDTO->isSetNumIdContato() && $objOrgaoDTO->getNumIdContato()!=$objOrgaoDTOBanco->getNumIdContato()){
        $objInfraException->lancarValidacao('Não é possível alterar o contato associado.');
      }else{
        $objOrgaoDTO->setNumIdContato($objOrgaoDTOBanco->getNumIdContato());
      }

      if ($objOrgaoDTO->isSetStrIdOrgaoFederacao() && $objOrgaoDTOBanco->getStrIdOrgaoFederacao()!=$objOrgaoDTO->getStrIdOrgaoFederacao()){
        $objInfraException->lancarValidacao('Não é possível alterar o identificador do órgão no SEI Federação.');
      }else{
        $objOrgaoDTO->setStrIdOrgaoFederacao($objOrgaoDTOBanco->getStrIdOrgaoFederacao());
      }

      if ($objOrgaoDTO->isSetStrSigla()){
        $this->validarStrSiglaRN1346($objOrgaoDTO, $objInfraException);
      }else{
        $objOrgaoDTO->setStrSigla($objOrgaoDTOBanco->getStrSigla());
      }

      if ($objOrgaoDTO->isSetStrDescricao()){
        $this->validarStrDescricaoRN1347($objOrgaoDTO, $objInfraException);
      }else{
        $objOrgaoDTO->setStrDescricao($objOrgaoDTOBanco->getStrDescricao());
      }

      if ($objOrgaoDTO->isSetStrSinEnvioProcesso()){
        $this->validarStrSinEnvioProcesso($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinPublicacao()){
        $this->validarStrSinPublicacao($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinConsultaProcessual()){
        $this->validarStrSinConsultaProcessual($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinFederacaoEnvio()){
        $this->validarStrSinFederacaoEnvio($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinFederacaoRecebimento()){
        $this->validarStrSinFederacaoRecebimento($objOrgaoDTO, $objInfraException);
      }else{
        $objOrgaoDTO->setStrSinFederacaoRecebimento($objOrgaoDTOBanco->getStrSinFederacaoRecebimento());
      }

      if ($objOrgaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objOrgaoDTO, $objInfraException);
      }else{
        $objOrgaoDTO->setNumIdUnidade($objOrgaoDTOBanco->getNumIdUnidade());
      }

      if ($objOrgaoDTO->isSetStrNumeracao()) {
        $this->validarStrNumeracao($objOrgaoDTO, $objInfraException);
      }
      
      if ($objOrgaoDTO->isSetStrServidorCorretorOrtografico()) {
        $this->validarStrServidorCorretorOrtografico($objOrgaoDTO, $objInfraException);
      }
      
      if ($objOrgaoDTO->isSetStrCodigoSei()) {
        $this->validarStrCodigoSei($objOrgaoDTO, $objInfraException);
      }
      
      if ($objOrgaoDTO->isSetStrNomeArquivo()) {
        $this->validarStrNomeArquivo($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrStaCorretorOrtografico()) {
        $this->validarStrStaCorretorOrtografico($objOrgaoDTO, $objInfraException);
      }

      if ($objOrgaoDTO->isSetStrSinAtivo()){
        $objOrgaoDTO->unSetStrSinAtivo();
      }

      $this->validarUnidadeRecebimentoFederacao($objOrgaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      
      if ($objOrgaoDTO->isSetStrNomeArquivo() && !InfraString::isBolVazia($objOrgaoDTO->getStrNomeArquivo())) {
        if ($objOrgaoDTO->getStrNomeArquivo()=="*REMOVER*") {
          $objOrgaoDTO->setStrTimbre(null);
        } else {
          $objOrgaoDTO->setStrTimbre(base64_encode(file_get_contents(DIR_SEI_TEMP.'/'.$objOrgaoDTO->getStrNomeArquivo())));
        }
      }

      if ($objOrgaoDTO->isSetStrIdxOrgao()){
        $objOrgaoDTO->unSetStrIdxOrgao();
      }

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $objOrgaoBD->alterar($objOrgaoDTO);

      $this->montarIndexacao($objOrgaoDTO);

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
      $objContatoDTO->setStrNome($objOrgaoDTO->getStrDescricao());
      $objContatoDTO->setNumIdContato($objOrgaoDTO->getNumIdContato());
      $objContatoDTO->setStrStaOperacao('REPLICACAO');

      $objContatoRN = new ContatoRN();
      $objContatoRN->alterarRN0323($objContatoDTO);

      $this->tratarHistoricoOrgao($objOrgaoDTO, $objOrgaoDTOBanco);

      if ($objOrgaoDTO->getStrSigla()!=$objOrgaoDTOBanco->getStrSigla()) {

        $strSiglaAntiga = $objOrgaoDTOBanco->getStrSigla();
        $strSiglaNova = $objOrgaoDTO->getStrSigla();

        $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
        $arrTiposParametros = $objInfraParametro->listarValores(array(
            $strSiglaAntiga.'_ID_TIPO_CONTATO_UNIDADES',
            $strSiglaAntiga.'_ID_TIPO_CONTATO_USUARIOS',
            $strSiglaAntiga.'_ID_TIPO_CONTATO_USUARIOS_EXTERNOS',
            $strSiglaNova.'_ID_TIPO_CONTATO_UNIDADES',
            $strSiglaNova.'_ID_TIPO_CONTATO_USUARIOS',
            $strSiglaNova.'_ID_TIPO_CONTATO_USUARIOS_EXTERNOS'), false);

        $objInfraParametroRN = new InfraParametroRN();
        $objTipoContatoRN = new TipoContatoRN();

        $arrTiposAtualizar = array(
            array('_ID_TIPO_CONTATO_UNIDADES', 'Unidades'),
            array('_ID_TIPO_CONTATO_USUARIOS', 'Usuários'),
            array('_ID_TIPO_CONTATO_USUARIOS_EXTERNOS', 'Usuários Externos')
        );

        foreach($arrTiposAtualizar as $arr) {

          $strParamAntigo = $strSiglaAntiga.$arr[0];
          $strParamNovo = $strSiglaNova.$arr[0];
          $strNomeNovo = $arr[1].' '.$strSiglaNova;

          if (isset($arrTiposParametros[$strParamAntigo]) && !isset($arrTiposParametros[$strParamNovo])) {

            $numIdTipoContato = $arrTiposParametros[$strParamAntigo];

            $objTipoContatoDTO = new TipoContatoDTO();
            $objTipoContatoDTO->setBolExclusaoLogica(false);
            $objTipoContatoDTO->retNumIdTipoContato();
            $objTipoContatoDTO->setNumIdTipoContato($numIdTipoContato);

            if ($objTipoContatoRN->consultarRN0336($objTipoContatoDTO) != null) {

              $objTipoContatoDTO = new TipoContatoDTO();
              $objTipoContatoDTO->setBolExclusaoLogica(false);
              $objTipoContatoDTO->setStrNome($strNomeNovo);

              if ($objTipoContatoRN->contarRN0353($objTipoContatoDTO) == 0) {

                $objTipoContatoDTO = new TipoContatoDTO();
                $objTipoContatoDTO->setStrNome($strNomeNovo);
                $objTipoContatoDTO->setStrDescricao(null);
                $objTipoContatoDTO->setNumIdTipoContato($numIdTipoContato);
                $objTipoContatoRN->alterarRN0335($objTipoContatoDTO);
              }
            }

            $objInfraParametro->setValor($strParamNovo, $numIdTipoContato);

            $objInfraParametroDTO = new InfraParametroDTO();
            $objInfraParametroDTO->setStrNome($strParamAntigo);
            $objInfraParametroRN->excluir(array($objInfraParametroDTO));
          }
        }
      }

      if ($objOrgaoDTO->getStrIdOrgaoFederacao()!=null){
        $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
        $objOrgaoFederacaoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
        $objOrgaoFederacaoDTO->setStrDescricao($objOrgaoDTO->getStrDescricao());
        $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($objOrgaoDTO->getStrIdOrgaoFederacao());

        $objOrgaoFederacaoRN = new OrgaoFederacaoRN();
        $objOrgaoFederacaoRN->alterar($objOrgaoFederacaoDTO);
      }

      if ($objOrgaoDTO->isSetStrSinRestricaoPesquisaOrgaos()){

        $objRelOrgaoPesquisaRN = new RelOrgaoPesquisaRN();
        $objRelOrgaoPesquisaDTO = new RelOrgaoPesquisaDTO();
        $objRelOrgaoPesquisaDTO->retNumIdOrgao1();
        $objRelOrgaoPesquisaDTO->retNumIdOrgao2();
        $objRelOrgaoPesquisaDTO->setNumIdOrgao1($objOrgaoDTO->getNumIdOrgao());
        $arrObjRelOrgaoPesquisaDTO = $objRelOrgaoPesquisaRN->listar($objRelOrgaoPesquisaDTO);

        $objRelOrgaoPesquisaRN->excluir($arrObjRelOrgaoPesquisaDTO);

        if($objOrgaoDTO->getStrSinRestricaoPesquisaOrgaos() == 'S'){
          $bolAdicionouOrgaoAtual = false;
          foreach($objOrgaoDTO->getArrObjRelOrgaoPesquisaDTO() as $dto){
            if($dto->getNumIdOrgao1() == $objOrgaoDTO->getNumIdOrgao() && $dto->getNumIdOrgao2() == $objOrgaoDTO->getNumIdOrgao()){
              $bolAdicionouOrgaoAtual = true;
            }
            $objRelOrgaoPesquisaRN->cadastrar($dto);
          }
          if(!$bolAdicionouOrgaoAtual){
            throw new InfraException('Não é possível restringir o próprio órgão.');
          }
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Órgão.',$e);
    }
  }

  protected function excluirRN1351Controlado($arrObjOrgaoDTO){
    try {
      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_excluir',__METHOD__,$arrObjOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNumeracaoRN = new NumeracaoRN(); 
      $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
      for($i=0;$i<count($arrObjOrgaoDTO);$i++){
	      $objNumeracaoDTO = new NumeracaoDTO();
	      $objNumeracaoDTO->retNumIdNumeracao();
	      $objNumeracaoDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());
	      $objNumeracaoRN->excluir($objNumeracaoRN->listar($objNumeracaoDTO));

        $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();
        $objOrgaoHistoricoDTO->retNumIdOrgaoHistorico();
        $objOrgaoHistoricoDTO->setNumIdOrgao($arrObjOrgaoDTO[$i]->getNumIdOrgao());
        $arrObjOrgaoHistoricoDTO = $objOrgaoHistoricoRN->listar($objOrgaoHistoricoDTO);
        foreach ($arrObjOrgaoHistoricoDTO as $objOrgaoHistoricoDTO_Lista){
          $objOrgaoHistoricoDTO_Lista->setBolOrigemSIP(true);
        }
        $objOrgaoHistoricoRN->excluir($arrObjOrgaoHistoricoDTO);
      }


      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdContato();
      $objOrgaoDTO->setNumIdOrgao(InfraArray::converterArrInfraDTO($arrObjOrgaoDTO,'IdOrgao'),InfraDTO::$OPER_IN);
      $arrNumIdContato = InfraArray::converterArrInfraDTO($this->listarRN1353($objOrgaoDTO),'IdContato');

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoDTO);$i++){
        $objOrgaoBD->excluir($arrObjOrgaoDTO[$i]);
      }

      $objContatoRN = new ContatoRN();
      foreach($arrNumIdContato as $numIdContato){
        $objContatoDTO = new ContatoDTO();
        $objContatoDTO->setNumIdContato($numIdContato);
        try{
          $objContatoRN->excluirRN0326(array($objContatoDTO));
        }catch(Exception $e){
          $objContatoRN->desativarRN0451(array($objContatoDTO));
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Órgão.',$e);
    }
  }

  protected function consultarRN1352Conectado(OrgaoDTO $objOrgaoDTO){
    try {

      //Valida Permissao
      //SessaoSEI::getInstance()->validarAuditarPermissao('orgao_consultar'); //nao valida para montar nos formularios

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      
      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->consultar($objOrgaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Órgão.',$e);
    }
  }

  protected function listarRN1353Conectado(OrgaoDTO $objOrgaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->listar($objOrgaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Órgãos.',$e);
    }
  }

  protected function listarPesquisaConectado(OrgaoDTO $objOrgaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrIdOrgao_Pesquisa = array();

      $objRelOrgaoPesquisaDTO = new RelOrgaoPesquisaDTO();
      $objRelOrgaoPesquisaDTO->retNumIdOrgao1();
      $objRelOrgaoPesquisaDTO->retNumIdOrgao2();

      $objRelOrgaoPesquisaRN = new RelOrgaoPesquisaRN();
      $arrObjRelOrgaoPesquisaDTO = $objRelOrgaoPesquisaRN->listar($objRelOrgaoPesquisaDTO);

      if(count($arrObjRelOrgaoPesquisaDTO)) {

        $numIdOrgaoAtual = $objOrgaoDTO->getNumIdOrgao();

        //mapeamento de orgaos com restricao
        $arrIdOrgao1RelOrgaoPesquisa_Restritos = InfraArray::converterArrInfraDTO($arrObjRelOrgaoPesquisaDTO, 'IdOrgao2', 'IdOrgao1', true);

        //se o orgao atual possui restricoes para exibicao
        if (isset($arrIdOrgao1RelOrgaoPesquisa_Restritos[$numIdOrgaoAtual])) {
          $arrIdOrgaosFiltro = $arrIdOrgao1RelOrgaoPesquisa_Restritos[$numIdOrgaoAtual];
        }else{
          $objOrgaoDTO_Todos = new OrgaoDTO();
          $objOrgaoDTO_Todos->retNumIdOrgao();
          $arrIdOrgaosFiltro = InfraArray::converterArrInfraDTO($this->listarRN1353($objOrgaoDTO_Todos), 'IdOrgao');
        }

        //para cada orgao da lista
        foreach ($arrIdOrgaosFiltro as $numIdOrgao) {
          //se o orgao não possui restricoes OU se o orgao atual esta na lista de permitidos deste orgao
          if (!isset($arrIdOrgao1RelOrgaoPesquisa_Restritos[$numIdOrgao]) || in_array($numIdOrgaoAtual, $arrIdOrgao1RelOrgaoPesquisa_Restritos[$numIdOrgao])) {
            //adiciona para pesquisa
            $arrIdOrgao_Pesquisa[] = $numIdOrgao;
          }
        }

        $arrIdOrgao_Pesquisa = array_unique($arrIdOrgao_Pesquisa);

        if (count($arrIdOrgao_Pesquisa)) {
          $objOrgaoDTO->setNumIdOrgao($arrIdOrgao_Pesquisa, InfraDTO::$OPER_IN);
        }
      }else{
        $objOrgaoDTO->unSetNumIdOrgao();
      }

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      return $objOrgaoBD->listar($objOrgaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro listando Órgãos de pesquisa.',$e);
    }
  }

  protected function contarRN1354Conectado(OrgaoDTO $objOrgaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->contar($objOrgaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Órgãos.',$e);
    }
  }

  protected function desativarRN1355Controlado($arrObjOrgaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_desativar',__METHOD__,$arrObjOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoDTO);$i++){
        $objOrgaoBD->desativar($arrObjOrgaoDTO[$i]);
      }

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdContato();
      $objOrgaoDTO->setNumIdOrgao(InfraArray::converterArrInfraDTO($arrObjOrgaoDTO,'IdOrgao'),InfraDTO::$OPER_IN);
      $objContatoRN = new ContatoRN();
      $objContatoRN->desativarRN0451(InfraArray::gerarArrInfraDTO('ContatoDTO','IdContato',InfraArray::converterArrInfraDTO($this->listarRN1353($objOrgaoDTO),'IdContato')));

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Órgão.',$e);
    }
  }

  protected function reativarRN1356Controlado($arrObjOrgaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_reativar',__METHOD__,$arrObjOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjOrgaoDTO);$i++){
        $objOrgaoBD->reativar($arrObjOrgaoDTO[$i]);
      }

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdContato();
      $objOrgaoDTO->setNumIdOrgao(InfraArray::converterArrInfraDTO($arrObjOrgaoDTO,'IdOrgao'),InfraDTO::$OPER_IN);
      $objContatoRN = new ContatoRN();
      $objContatoRN->reativarRN0452(InfraArray::gerarArrInfraDTO('ContatoDTO','IdContato',InfraArray::converterArrInfraDTO($this->listarRN1353($objOrgaoDTO),'IdContato')));

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Órgão.',$e);
    }
  }

  protected function bloquearRN1357Controlado(OrgaoDTO $objOrgaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_consultar',__METHOD__,$objOrgaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $ret = $objOrgaoBD->bloquear($objOrgaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Órgão.',$e);
    }
  }

  protected function montarIndexacaoControlado(OrgaoDTO $parObjOrgaoDTO){
    try{

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->setNumIdOrgao($parObjOrgaoDTO->getNumIdOrgao());

      $objOrgaoDTO = $this->consultarRN1352($objOrgaoDTO);

      $strIndexacao = InfraString::prepararIndexacao($objOrgaoDTO->getStrSigla().' '.$objOrgaoDTO->getStrDescricao());

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setStrIdxOrgao($strIndexacao);
      $objOrgaoDTO->setNumIdOrgao($parObjOrgaoDTO->getNumIdOrgao());

      $objInfraException = new InfraException();
      $this->validarStrIdxOrgao($objOrgaoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
      $objOrgaoBD->alterar($objOrgaoDTO);


    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de Órgao.',$e);
    }
  }

  protected function pesquisarConectado(OrgaoDTO $objOrgaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('orgao_listar',__METHOD__,$objOrgaoDTO);

      $objOrgaoDTO = InfraString::tratarPalavrasPesquisaDTO($objOrgaoDTO,"Sigla");
      $objOrgaoDTO = InfraString::tratarPalavrasPesquisaDTO($objOrgaoDTO,"Descricao");
      $objOrgaoDTO = InfraString::prepararPesquisaDTO($objOrgaoDTO,"PalavrasPesquisa","IdxOrgao");

      return $this->listarRN1353($objOrgaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Órgãos.',$e);
    }
  }

  private function tratarHistoricoOrgao(OrgaoDTO $objOrgaoDTO, OrgaoDTO $objOrgaoDTOBanco){
    //testa se alterou a sigla ou descrição do orgao, o que ocasiona cadastro no historico
    if($objOrgaoDTOBanco->getStrSigla() != $objOrgaoDTO->getStrSigla() || $objOrgaoDTOBanco->getStrDescricao() != $objOrgaoDTO->getStrDescricao()){
      $objOrgaoHistoricoRN = new OrgaoHistoricoRN();

      $objOrgaoHistoricoDTO_Atual = new OrgaoHistoricoDTO();
      $objOrgaoHistoricoDTO_Atual->retNumIdOrgaoHistorico();
      $objOrgaoHistoricoDTO_Atual->retNumIdOrgao();
      $objOrgaoHistoricoDTO_Atual->retStrSigla();
      $objOrgaoHistoricoDTO_Atual->retStrDescricao();
      $objOrgaoHistoricoDTO_Atual->retDtaInicio();
      $objOrgaoHistoricoDTO_Atual->retDtaFim();
      $objOrgaoHistoricoDTO_Atual->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
      $objOrgaoHistoricoDTO_Atual->setDtaFim(null);
      //busca o historico vigente (com data final nula) do orgao
      $objOrgaoHistoricoDTO_Atual = $objOrgaoHistoricoRN->consultar($objOrgaoHistoricoDTO_Atual);
      //flag que indica que o historico é cadastrado a partir do cadastro/alteracao de um orgao em si
      $objOrgaoHistoricoDTO_Atual->setBolOrigemSIP(true);
      //se a data inicial do historico vigente for hoje, nao será cadastrado um novo registro de historico; apenas as informacoes de sigla e descricao serao atualizadas no historico do banco
      if(InfraData::compararDatasSimples($objOrgaoHistoricoDTO_Atual->getDtaInicio(),InfraData::getStrDataHoraAtual()) == 0){
        $objOrgaoHistoricoDTO_Atual->setStrSigla($objOrgaoDTO->getStrSigla());
        $objOrgaoHistoricoDTO_Atual->setStrDescricao($objOrgaoDTO->getStrDescricao());
        $objOrgaoHistoricoRN->alterar($objOrgaoHistoricoDTO_Atual);
      //senao, o historico vigente terá a data final setada como ontem, e um novo historico, que será o vigente (com data final nula), será cadastrado
      }else{
        $objOrgaoHistoricoDTO_Atual->setDtaFim(InfraData::calcularData(1,InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ATRAS,InfraData::getStrDataHoraAtual()));
        $objOrgaoHistoricoRN->alterar($objOrgaoHistoricoDTO_Atual);

        $objOrgaoHistoricoDTO_Novo = new OrgaoHistoricoDTO();
        $objOrgaoHistoricoDTO_Novo->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
        $objOrgaoHistoricoDTO_Novo->setStrSigla($objOrgaoDTO->getStrSigla());
        $objOrgaoHistoricoDTO_Novo->setStrDescricao($objOrgaoDTO->getStrDescricao());
        $objOrgaoHistoricoDTO_Novo->setDtaInicio(InfraData::getStrDataHoraAtual());
        $objOrgaoHistoricoDTO_Novo->setDtaFim(null);
        $objOrgaoHistoricoDTO_Novo->setBolOrigemSIP(true);
        $objOrgaoHistoricoRN->cadastrar($objOrgaoHistoricoDTO_Novo);
      }
    }
  }

  protected function gerarIdentificadorFederacaoControlado(OrgaoDTO $parObjOrgaoDTO) {
    try {

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->setNumIdOrgao($parObjOrgaoDTO->getNumIdOrgao());
      $objOrgaoDTO = $this->bloquearRN1357($objOrgaoDTO);

      if ($objOrgaoDTO->getStrIdOrgaoFederacao()==null){

        $strIdOrgaoFederacao = InfraULID::gerar();

        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();

        $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
        $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($strIdOrgaoFederacao);
        $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal());
        $objOrgaoFederacaoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
        $objOrgaoFederacaoDTO->setStrDescricao($objOrgaoDTO->getStrDescricao());

        $objOrgaoFederacaoRN = new OrgaoFederacaoRN();
        $objOrgaoFederacaoRN->cadastrar($objOrgaoFederacaoDTO);

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setStrIdOrgaoFederacao($strIdOrgaoFederacao);
        $objOrgaoDTO->setNumIdOrgao($parObjOrgaoDTO->getNumIdOrgao());

        $objOrgaoBD = new OrgaoBD($this->getObjInfraIBanco());
        $objOrgaoBD->alterar($objOrgaoDTO);
      }

      $parObjOrgaoDTO->setStrIdOrgaoFederacao($objOrgaoDTO->getStrIdOrgaoFederacao());

    }catch(Exception $e){
      throw new InfraException('Erro gerando identificador do SEI Federação para o Órgão.',$e);
    }
  }
}
?>