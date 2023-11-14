<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/01/2008 - criado por marcio_db*
 * 04/06/2018 - cjy - adicao dos campos numero_passaporte e id_pais_passaporte
 * 12/06/2018 - cjy - insercao de estado e cidade textualmente, para paises estrangeiros
 *
 * Versão do Gerador de Código: 1.12.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class ContatoRN extends InfraRN {

  public static $TN_PESSOA_FISICA = 'F';
  public static $TN_PESSOA_JURIDICA = 'J';

  public static $TG_MASCULINO = 'M';
  public static $TG_FEMININO = 'F';

  //TAC = Tipo Acesso Contato
  public static $TAC_NENHUM = 0;
  public static $TAC_SOMENTE_ASSOCIADO = 1;
  public static $TAC_SOMENTE_CONTATO = 2;
  public static $TAC_AMBOS = 3;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function verificarXss($strConteudo, $strRotulo, $objInfraException){
    $objInfraXSS = new InfraXSS();
    if ($objInfraXSS->verificacaoAvancada($strConteudo)){
      $objInfraException->adicionarValidacao($strRotulo.' possui conteúdo inválido.');
    }
  }

  protected function cadastrarRN0322Controlado(ContatoDTO $objContatoDTO) {
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_cadastrar', __METHOD__, $objContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (!$objContatoDTO->isSetNumIdContatoAssociado()) {
        $objContatoDTO->setNumIdContatoAssociado(null);
      }

      if (!$objContatoDTO->isSetStrSigla()) {
        $objContatoDTO->setStrSigla(null);
      }

      if (!$objContatoDTO->isSetStrNomeRegistroCivil()) {
        $objContatoDTO->setStrNomeRegistroCivil(null);
      }

      if (!$objContatoDTO->isSetStrNomeSocial()) {
        $objContatoDTO->setStrNomeSocial(null);
      }

      if (!$objContatoDTO->isSetDtaNascimento()) {
        $objContatoDTO->setDtaNascimento(null);
      }

      if (!$objContatoDTO->isSetStrStaGenero()) {
        $objContatoDTO->setStrStaGenero(null);
      }

      if (!$objContatoDTO->isSetDblCpf()) {
        $objContatoDTO->setDblCpf(null);
      }

      if (!$objContatoDTO->isSetDblRg()) {
        $objContatoDTO->setDblRg(null);
      }

      if (!$objContatoDTO->isSetStrOrgaoExpedidor()) {
        $objContatoDTO->setStrOrgaoExpedidor(null);
      }

      if (!$objContatoDTO->isSetDblCnpj()) {
        $objContatoDTO->setDblCnpj(null);
      }

      if (!$objContatoDTO->isSetStrMatricula()) {
        $objContatoDTO->setStrMatricula(null);
      }

      if (!$objContatoDTO->isSetStrMatriculaOab()) {
        $objContatoDTO->setStrMatriculaOab(null);
      }

      if (!$objContatoDTO->isSetStrTelefoneComercial()) {
        $objContatoDTO->setStrTelefoneComercial(null);
      }

      if (!$objContatoDTO->isSetStrTelefoneResidencial()) {
        $objContatoDTO->setStrTelefoneResidencial(null);
      }

      if (!$objContatoDTO->isSetStrTelefoneCelular()) {
        $objContatoDTO->setStrTelefoneCelular(null);
      }

      if (!$objContatoDTO->isSetStrEmail()) {
        $objContatoDTO->setStrEmail(null);
      }

      if (!$objContatoDTO->isSetStrSitioInternet()) {
        $objContatoDTO->setStrSitioInternet(null);
      }

      if (!$objContatoDTO->isSetStrEndereco()) {
        $objContatoDTO->setStrEndereco(null);
      }

      if (!$objContatoDTO->isSetStrComplemento()) {
        $objContatoDTO->setStrComplemento(null);
      }

      if (!$objContatoDTO->isSetStrBairro()) {
        $objContatoDTO->setStrBairro(null);
      }

      if (!$objContatoDTO->isSetNumIdUf()) {
        $objContatoDTO->setNumIdUf(null);
      }

      if (!$objContatoDTO->isSetNumIdCidade()) {
        $objContatoDTO->setNumIdCidade(null);
      }

      if (!$objContatoDTO->isSetNumIdPais()) {
        $objContatoDTO->setNumIdPais(null);
      }

      if (!$objContatoDTO->isSetStrCep()) {
        $objContatoDTO->setStrCep(null);
      }

      if (!$objContatoDTO->isSetStrObservacao()) {
        $objContatoDTO->setStrObservacao(null);
      }

      if (!$objContatoDTO->isSetNumIdPaisPassaporte()) {
        $objContatoDTO->setNumIdPaisPassaporte(null);
      }

      if (!$objContatoDTO->isSetStrNumeroPassaporte()) {
        $objContatoDTO->setStrNumeroPassaporte(null);
      }

      if (!$objContatoDTO->isSetStrConjuge()) {
        $objContatoDTO->setStrConjuge(null);
      }

      if (!$objContatoDTO->isSetStrFuncao()) {
        $objContatoDTO->setStrFuncao(null);
      }

      if (!$objContatoDTO->isSetNumIdCargo()) {
        $objContatoDTO->setNumIdCargo(null);
      }

      if (!$objContatoDTO->isSetNumIdTitulo()) {
        $objContatoDTO->setNumIdTitulo(null);
      }

      if (!$objContatoDTO->isSetNumIdCategoria()) {
        $objContatoDTO->setNumIdCategoria(null);
      }

      $this->validarNumIdTipoContatoRN0367($objContatoDTO, $objInfraException);
      $this->validarNumIdContatoAssociadoRN0729($objContatoDTO, $objInfraException);
      $this->validarStrStaNatureza($objContatoDTO, $objInfraException);
      $this->validarStrSinEnderecoAssociadoRN0894($objContatoDTO, $objInfraException);
      $this->validarStrStaGeneroRN0433($objContatoDTO, $objInfraException);
      $this->validarDblCpfRN0435($objContatoDTO, $objInfraException);
      $this->validarDblRg($objContatoDTO, $objInfraException);
      $this->validarStrOrgaoExpedidor($objContatoDTO, $objInfraException);
      $this->validarStrMatriculaRN0436($objContatoDTO, $objInfraException);
      $this->validarStrMatriculaOabRN0434($objContatoDTO, $objInfraException);
      $this->validarDtaNascimentoRN0569($objContatoDTO, $objInfraException);
      $this->validarDblCnpjRN0372($objContatoDTO, $objInfraException);
      $this->validarNumIdCargoRN0427($objContatoDTO, $objInfraException);
      $this->validarStrSiglaRN0430($objContatoDTO, $objInfraException);
      $this->validarStrNomeRN0431($objContatoDTO, $objInfraException);
      $this->validarStrNomeSocial($objContatoDTO, $objInfraException);
      $this->validarStrTelefoneResidencial($objContatoDTO, $objInfraException);
      $this->validarStrTelefoneCelular($objContatoDTO, $objInfraException);
      $this->validarStrTelefoneComercial($objContatoDTO, $objInfraException);
      $this->validarStrEmailRN0439($objContatoDTO, $objInfraException);
      $this->validarStrSitioInternetRN0440($objContatoDTO, $objInfraException);
      $this->validarStrEnderecoRN0441($objContatoDTO, $objInfraException);
      $this->validarStrComplemento($objContatoDTO, $objInfraException);
      $this->validarStrBairroRN0442($objContatoDTO, $objInfraException);
      $this->validarStrCepRN0446($objContatoDTO, $objInfraException);
      $this->validarStrObservacaoRN0447($objContatoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0449($objContatoDTO, $objInfraException);
      $this->validarNumIdPais($objContatoDTO, $objInfraException);
      $this->validarNumIdUf($objContatoDTO, $objInfraException);
      $this->validarNumIdCidade($objContatoDTO, $objInfraException);
      $this->validarStrNumeroPassaporte($objContatoDTO, $objInfraException);
      $this->validarNumIdPaisPassaporte($objContatoDTO, $objInfraException);
      $this->validarNumIdTitulo($objContatoDTO, $objInfraException);
      $this->validarStrFuncao($objContatoDTO, $objInfraException);
      $this->validarStrConjuge($objContatoDTO, $objInfraException);
      $this->validarNumIdCategoria($objContatoDTO, $objInfraException);


      //$this->validarSiglaNomeUnicosRN1221($objContatoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTipoContatoDTO = new TipoContatoDTO();
      $objTipoContatoDTO->setBolExclusaoLogica(false);
      $objTipoContatoDTO->retStrSinSistema();
      $objTipoContatoDTO->setNumIdTipoContato($objContatoDTO->getNumIdTipoContato());

      $objTipoContatoRN = new TipoContatoRN();
      $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);

      if ($objTipoContatoDTO == null) {
        $objInfraException->lancarValidacao('Tipo do contato não encontrado.');
      }

      if ($objTipoContatoDTO->getStrSinSistema() == 'S' && (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao() != 'REPLICACAO')) {
        $objInfraException->lancarValidacao('Não é possível cadastrar o contato em um tipo reservado do sistema.');
      }

      $numProxSeq = $this->getObjInfraIBanco()->getValorSequencia('seq_contato');

      $objContatoDTO->setNumIdContato($numProxSeq);

      if ($objContatoDTO->getNumIdContatoAssociado() == null) {
        $objContatoDTO->setNumIdContatoAssociado($numProxSeq);
      }

      $objContatoDTO->setStrIdxContato(null);

      $objContatoDTO->setNumIdUsuarioCadastro(SessaoSEI::getInstance()->getNumIdUsuario());
      $objContatoDTO->setNumIdUnidadeCadastro(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objContatoDTO->setDthCadastro(InfraData::getStrDataHoraAtual());

      if ($objContatoDTO->getStrStaNatureza() == self::$TN_PESSOA_JURIDICA) {
        $objContatoDTO->setStrNomeRegistroCivil(null);
        $objContatoDTO->setStrNomeSocial(null);
      }else{
        $objContatoDTO->setStrNomeRegistroCivil($objContatoDTO->getStrNome());
        if ($objContatoDTO->getStrNomeSocial()!=null){
          $objContatoDTO->setStrNome($objContatoDTO->getStrNomeSocial());
        }
      }

      $objContatoAPI = new ContatoAPI();
      $objContatoAPI->setIdContato(null);
      $objContatoAPI->setIdTipoContato($objContatoDTO->getNumIdTipoContato());
      $objContatoAPI->setIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado() != $objContatoDTO->getNumIdContato() ? $objContatoDTO->getNumIdContatoAssociado() : null);
      $objContatoAPI->setStaNatureza($objContatoDTO->getStrStaNatureza());
      $objContatoAPI->setSinEnderecoAssociado($objContatoDTO->getStrSinEnderecoAssociado());
      $objContatoAPI->setStaGenero($objContatoDTO->getStrStaGenero());
      $objContatoAPI->setCpf($objContatoDTO->getDblCpf());
      $objContatoAPI->setRg($objContatoDTO->getDblRg());
      $objContatoAPI->setOrgaoExpedidor($objContatoDTO->getStrOrgaoExpedidor());
      $objContatoAPI->setMatricula($objContatoDTO->getStrMatricula());
      $objContatoAPI->setMatriculaOab($objContatoDTO->getStrMatriculaOab());
      $objContatoAPI->setDataNascimento($objContatoDTO->getDtaNascimento());
      $objContatoAPI->setCnpj($objContatoDTO->getDblCnpj());
      $objContatoAPI->setIdCargo($objContatoDTO->getNumIdCargo());
      $objContatoAPI->setSigla($objContatoDTO->getStrSigla());

      if ($objContatoDTO->getStrStaNatureza()==self::$TN_PESSOA_JURIDICA){
        $objContatoAPI->setNome($objContatoDTO->getStrNome());
        $objContatoAPI->setNomeSocial(null);
      }else{
        $objContatoAPI->setNome($objContatoDTO->getStrNomeRegistroCivil());
        $objContatoAPI->setNomeSocial($objContatoDTO->getStrNomeSocial());
      }

      $objContatoAPI->setTelefoneComercial($objContatoDTO->getStrTelefoneComercial());
      $objContatoAPI->setTelefoneResidencial($objContatoDTO->getStrTelefoneResidencial());
      $objContatoAPI->setTelefoneCelular($objContatoDTO->getStrTelefoneCelular());
      $objContatoAPI->setEmail($objContatoDTO->getStrEmail());
      $objContatoAPI->setSitioInternet($objContatoDTO->getStrSitioInternet());
      $objContatoAPI->setEndereco($objContatoDTO->getStrEndereco());
      $objContatoAPI->setComplemento($objContatoDTO->getStrComplemento());
      $objContatoAPI->setBairro($objContatoDTO->getStrBairro());
      $objContatoAPI->setCep($objContatoDTO->getStrCep());
      $objContatoAPI->setObservacao($objContatoDTO->getStrObservacao());
      $objContatoAPI->setSinAtivo($objContatoDTO->getStrSinAtivo());
      $objContatoAPI->setIdPais($objContatoDTO->getNumIdPais());
      $objContatoAPI->setIdEstado($objContatoDTO->getNumIdUf());
      $objContatoAPI->setIdCidade($objContatoDTO->getNumIdCidade());
      $objContatoAPI->setIdPaisPassaporte($objContatoDTO->getNumIdPaisPassaporte());
      $objContatoAPI->setNumeroPassaporte($objContatoDTO->getStrNumeroPassaporte());
      $objContatoAPI->setIdTitulo($objContatoDTO->getNumIdTitulo());
      $objContatoAPI->setIdCategoria($objContatoDTO->getNumIdCategoria());
      $objContatoAPI->setFuncao($objContatoDTO->getStrFuncao());
      $objContatoAPI->setConjuge($objContatoDTO->getStrConjuge());
      if ($objContatoDTO->isSetStrNomeUf()) {
        $objContatoAPI->setNomeEstado($objContatoDTO->getStrNomeUf());
      }else{
        $objContatoAPI->setNomeEstado(null);
      }

      if ($objContatoDTO->isSetStrNomeCidade()){
        $objContatoAPI->setNomeCidade($objContatoDTO->getStrNomeCidade());
      }else{
        $objContatoAPI->setNomeCidade(null);
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('validarContato', $objContatoAPI);
      }

      $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
      $ret = $objContatoBD->cadastrar($objContatoDTO);

      $objContatoAPI->setIdContato($ret->getNumIdContato());

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('cadastrarContato', $objContatoAPI);
      }

      $this->montarIndexacao($objContatoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Contato.',$e);
    }
  }

  protected function alterarRN0323Controlado(ContatoDTO $objContatoDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_alterar',__METHOD__,$objContatoDTO);

      $objContatoDTO = clone($objContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objContatoDTOBanco = new ContatoDTO();
      $objContatoDTOBanco->setBolExclusaoLogica(false);
      $objContatoDTOBanco->retNumIdTipoContato();
      $objContatoDTOBanco->retStrStaNatureza();
      $objContatoDTOBanco->retStrSigla();
      $objContatoDTOBanco->retStrNome();
      $objContatoDTOBanco->retStrNomeRegistroCivil();
      $objContatoDTOBanco->retStrNomeSocial();
      $objContatoDTOBanco->retNumIdCargo();
      $objContatoDTOBanco->retStrStaGenero();
      $objContatoDTOBanco->retNumIdCargo();
      $objContatoDTOBanco->retDblCpf();
      $objContatoDTOBanco->retDblRg();
      $objContatoDTOBanco->retStrOrgaoExpedidor();
      $objContatoDTOBanco->retDtaNascimento();
      $objContatoDTOBanco->retStrMatricula();
      $objContatoDTOBanco->retStrMatriculaOab();
      $objContatoDTOBanco->retStrTelefoneComercial();
      $objContatoDTOBanco->retStrTelefoneResidencial();
      $objContatoDTOBanco->retStrTelefoneCelular();
      $objContatoDTOBanco->retStrSitioInternet();
      $objContatoDTOBanco->retDblCnpj();
      $objContatoDTOBanco->retStrSinAtivo();
      $objContatoDTOBanco->retStrEmail();
      $objContatoDTOBanco->retStrEndereco();
      $objContatoDTOBanco->retStrComplemento();
      $objContatoDTOBanco->retStrBairro();
      $objContatoDTOBanco->retNumIdPais();
      $objContatoDTOBanco->retNumIdUf();
      $objContatoDTOBanco->retNumIdCidade();
      $objContatoDTOBanco->retStrCep();
      $objContatoDTOBanco->retStrObservacao();
      $objContatoDTOBanco->retNumIdContatoAssociado();
      $objContatoDTOBanco->retStrSinEnderecoAssociado();
      $objContatoDTOBanco->retStrSinSistemaTipoContato();
      $objContatoDTOBanco->setNumIdContato($objContatoDTO->getNumIdContato());
      $objContatoDTOBanco->retNumIdPaisPassaporte();
      $objContatoDTOBanco->retStrNumeroPassaporte();
      $objContatoDTOBanco->retStrNomeUf();
      $objContatoDTOBanco->retStrNomeCidade();
      $objContatoDTOBanco->retStrConjuge();
      $objContatoDTOBanco->retStrFuncao();
      $objContatoDTOBanco->retNumIdTitulo();
      $objContatoDTOBanco->retNumIdCategoria();
      $objContatoDTOBanco->retStrNomeCategoria();

      $objContatoDTOBanco = $this->consultarRN0324($objContatoDTOBanco);

      if ($objContatoDTOBanco==null){
        throw new InfraException('Contato não encontrado ['.$objContatoDTO->getNumIdContato().'].');
      }

      if ($objContatoDTO->isSetNumIdTipoContato()) {
        if ($objContatoDTO->getNumIdTipoContato()!=$objContatoDTOBanco->getNumIdTipoContato() && (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao()!='REPLICACAO')) {
          if ($objContatoDTOBanco->getStrSinSistemaTipoContato()=='S'){
            $objInfraException->lancarValidacao('Não é possível alterar o Tipo deste contato.');
          }else{
            $objTipoContatoDTO = new TipoContatoDTO();
            $objTipoContatoDTO->setBolExclusaoLogica(false);
            $objTipoContatoDTO->retStrSinSistema();
            $objTipoContatoDTO->setNumIdTipoContato($objContatoDTO->getNumIdTipoContato());

            $objTipoContatoRN = new TipoContatoRN();
            $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);

            if ($objTipoContatoDTO->getStrSinSistema()=='S'){
              $objInfraException->lancarValidacao('Não é possível alterar o tipo do contato para um tipo reservado do sistema.');
            }
          }
        }

      }else{
        $objContatoDTO->setNumIdTipoContato($objContatoDTOBanco->getNumIdTipoContato());
      }

      if (!$objContatoDTO->isSetNumIdContatoAssociado()){
        $objContatoDTO->setNumIdContatoAssociado($objContatoDTOBanco->getNumIdContatoAssociado());
      }else if ($objContatoDTO->getNumIdContatoAssociado()==null){
        $objContatoDTO->setNumIdContatoAssociado($objContatoDTO->getNumIdContato());
      }

      if ($objContatoDTO->isSetStrStaNatureza() && $objContatoDTO->getStrStaNatureza()!=$objContatoDTOBanco->getStrStaNatureza()) {

        if ($objContatoDTOBanco->getStrSinSistemaTipoContato()=='S'){
          $objInfraException->lancarValidacao('Não é possível alterar a Natureza deste contato.');
        }

        if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_FISICA){

          $dto = new ContatoDTO();
          $dto->setBolExclusaoLogica(false);
          $dto->retNumIdContato();
          $dto->setNumIdContatoAssociado($objContatoDTO->getNumIdContato());
          $dto->setNumIdContato($objContatoDTO->getNumIdContato(),InfraDTO::$OPER_DIFERENTE);
          $dto->setNumMaxRegistrosRetorno(1);

          if ($this->consultarRN0324($dto) != null){
            $objInfraException->lancarValidacao('Não é possível alterar a natureza porque existem contatos associados com esta Pessoa Jurídica.');
          }

        }else{

          if (!$objContatoDTO->isSetStrNome()){
            $objContatoDTO->setStrNome($objContatoDTOBanco->getStrNomeRegistroCivil());
          }

          $objContatoDTO->setNumIdContatoAssociado($objContatoDTO->getNumIdContato());
          $objContatoDTO->setStrSinEnderecoAssociado('N');
        }
      }else{
        $objContatoDTO->setStrStaNatureza($objContatoDTOBanco->getStrStaNatureza());
      }

      $this->validarStrStaNatureza($objContatoDTO, $objInfraException);

      if (!$objContatoDTO->isSetStrSigla()){
        $objContatoDTO->setStrSigla($objContatoDTOBanco->getStrSigla());
      }

      if (!$objContatoDTO->isSetStrNome()){
        $objContatoDTO->setStrNome($objContatoDTOBanco->getStrNome());
      }

      if (!$objContatoDTO->isSetStrNomeRegistroCivil()){
        $objContatoDTO->setStrNomeRegistroCivil($objContatoDTOBanco->getStrNomeRegistroCivil());
      }

      if (!$objContatoDTO->isSetStrNomeSocial()){
        $objContatoDTO->setStrNomeSocial($objContatoDTOBanco->getStrNomeSocial());
      }

      if (!$objContatoDTO->isSetStrStaGenero()){
        $objContatoDTO->setStrStaGenero($objContatoDTOBanco->getStrStaGenero());
      }

      if (!$objContatoDTO->isSetNumIdCargo()){
        $objContatoDTO->setNumIdCargo($objContatoDTOBanco->getNumIdCargo());
      }

      if (!$objContatoDTO->isSetDblCpf()) {
        $objContatoDTO->setDblCpf($objContatoDTOBanco->getDblCpf());
      }

      if (!$objContatoDTO->isSetDblRg()) {
        $objContatoDTO->setDblRg($objContatoDTOBanco->getDblRg());
      }

      if (!$objContatoDTO->isSetStrOrgaoExpedidor()) {
        $objContatoDTO->setStrOrgaoExpedidor($objContatoDTOBanco->getStrOrgaoExpedidor());
      }

      if (!$objContatoDTO->isSetDtaNascimento()) {
        $objContatoDTO->setDtaNascimento($objContatoDTOBanco->getDtaNascimento());
      }

      if (!$objContatoDTO->isSetStrMatricula()) {
        $objContatoDTO->setStrMatricula($objContatoDTOBanco->getStrMatricula());
      }

      if (!$objContatoDTO->isSetStrMatriculaOab()) {
        $objContatoDTO->setStrMatriculaOab($objContatoDTOBanco->getStrMatriculaOab());
      }

      if (!$objContatoDTO->isSetStrTelefoneCelular()) {
        $objContatoDTO->setStrTelefoneCelular($objContatoDTOBanco->getStrTelefoneCelular());
      }

      if (!$objContatoDTO->isSetStrTelefoneComercial()){
        $objContatoDTO->setStrTelefoneComercial($objContatoDTOBanco->getStrTelefoneComercial());
      }

      if (!$objContatoDTO->isSetStrTelefoneResidencial()){
        $objContatoDTO->setStrTelefoneResidencial($objContatoDTOBanco->getStrTelefoneResidencial());
      }

      if (!$objContatoDTO->isSetDblCnpj()) {
        $objContatoDTO->setDblCnpj($objContatoDTOBanco->getDblCnpj());
      }

      if (!$objContatoDTO->isSetStrSitioInternet()) {
        $objContatoDTO->setStrSitioInternet($objContatoDTOBanco->getStrSitioInternet());
      }

      if (!$objContatoDTO->isSetStrEmail()){
        $objContatoDTO->setStrEmail($objContatoDTOBanco->getStrEmail());
      }

      if (!$objContatoDTO->isSetStrEndereco()){
        $objContatoDTO->setStrEndereco($objContatoDTOBanco->getStrEndereco());
      }

      if (!$objContatoDTO->isSetStrComplemento()){
        $objContatoDTO->setStrComplemento($objContatoDTOBanco->getStrComplemento());
      }

      if (!$objContatoDTO->isSetStrBairro()){
        $objContatoDTO->setStrBairro($objContatoDTOBanco->getStrBairro());
      }

      if (!$objContatoDTO->isSetNumIdUf()){
        $objContatoDTO->setNumIdUf($objContatoDTOBanco->getNumIdUf());
      }

      if (!$objContatoDTO->isSetNumIdCidade()){
        $objContatoDTO->setNumIdCidade($objContatoDTOBanco->getNumIdCidade());
      }

      if (!$objContatoDTO->isSetNumIdPais()){
        $objContatoDTO->setNumIdPais($objContatoDTOBanco->getNumIdPais());
      }

      if (!$objContatoDTO->isSetStrCep()){
        $objContatoDTO->setStrCep($objContatoDTOBanco->getStrCep());
      }

      if (!$objContatoDTO->isSetStrObservacao()){
        $objContatoDTO->setStrObservacao($objContatoDTOBanco->getStrObservacao());
      }

      if (!$objContatoDTO->isSetStrSinAtivo()){
        $objContatoDTO->setStrSinAtivo($objContatoDTOBanco->getStrSinAtivo());
      }

      if (!$objContatoDTO->isSetStrSinEnderecoAssociado()){
        $objContatoDTO->setStrSinEnderecoAssociado($objContatoDTOBanco->getStrSinEnderecoAssociado());
      }

      if ($objContatoDTO->getStrSigla()!=$objContatoDTOBanco->getStrSigla() && $objContatoDTOBanco->getStrSinSistemaTipoContato()=='S' && (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao()!='REPLICACAO')) {
        $objInfraException->lancarValidacao('Não é possível alterar a Sigla deste contato.');
      }

      if ($objContatoDTO->getStrStaNatureza() == self::$TN_PESSOA_JURIDICA) {
        if ($objContatoDTO->getStrNome() != $objContatoDTOBanco->getStrNome() && $objContatoDTOBanco->getStrSinSistemaTipoContato() == 'S' && (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao() != 'REPLICACAO')) {
          $objInfraException->lancarValidacao('Não é possível alterar o Nome deste contato.');
        }
      }else {
        if ($objContatoDTO->getStrNomeRegistroCivil() != $objContatoDTOBanco->getStrNomeRegistroCivil() && $objContatoDTOBanco->getStrSinSistemaTipoContato() == 'S' && (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao() != 'REPLICACAO')) {
          $objInfraException->lancarValidacao('Não é possível alterar o Nome Registro Civil deste contato.');
        }

        if ($objContatoDTO->getStrNomeSocial() != $objContatoDTOBanco->getStrNomeSocial() && $objContatoDTOBanco->getStrSinSistemaTipoContato() == 'S' && (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao() != 'REPLICACAO')) {
          $objInfraException->lancarValidacao('Não é possível alterar o Nome Social deste contato.');
        }
      }

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->setNumMaxRegistrosRetorno(1);
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->setStrStaTipo(array(UsuarioRN::$TU_EXTERNO, UsuarioRN::$TU_EXTERNO_PENDENTE), InfraDTO::$OPER_IN);
      $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());

      $objUsuarioRN = new UsuarioRN();
      if ($objUsuarioRN->consultarRN0489($objUsuarioDTO)!=null && !SessaoSEI::getInstance()->verificarPermissao('usuario_externo_alterar')){

        if ($objContatoDTO->getStrEndereco()!=$objContatoDTOBanco->getStrEndereco() ||
            $objContatoDTO->getStrComplemento()!=$objContatoDTOBanco->getStrComplemento() ||
            $objContatoDTO->getStrBairro()!=$objContatoDTOBanco->getStrBairro() ||
            $objContatoDTO->getNumIdPais()!=$objContatoDTOBanco->getNumIdPais() ||
            $objContatoDTO->getNumIdUf()!=$objContatoDTOBanco->getNumIdUf() ||
            ($objContatoDTO->isSetStrNomeUf() && !InfraString::isBolVazia($objContatoDTO->getStrNomeUf()) && $objContatoDTO->getStrNomeUf()!=$objContatoDTOBanco->getStrNomeUf()) ||
            $objContatoDTO->getNumIdCidade()!=$objContatoDTOBanco->getNumIdCidade() ||
            ($objContatoDTO->isSetStrNomeCidade() && !InfraString::isBolVazia($objContatoDTO->getStrNomeCidade()) && $objContatoDTO->getStrNomeCidade()!=$objContatoDTOBanco->getStrNomeCidade()) ||
            $objContatoDTO->getStrCep()!=$objContatoDTOBanco->getStrCep() ||
            $objContatoDTO->getDblCpf()!=$objContatoDTOBanco->getDblCpf() ||
            $objContatoDTO->getDblRg()!=$objContatoDTOBanco->getDblRg() ||
            $objContatoDTO->getStrOrgaoExpedidor()!=$objContatoDTOBanco->getStrOrgaoExpedidor() ||
            $objContatoDTO->getStrNumeroPassaporte()!=$objContatoDTOBanco->getStrNumeroPassaporte() ||
            $objContatoDTO->getNumIdPaisPassaporte()!=$objContatoDTOBanco->getNumIdPaisPassaporte() ||
            $objContatoDTO->getStrTelefoneComercial()!=$objContatoDTOBanco->getStrTelefoneComercial() ||
            $objContatoDTO->getStrTelefoneCelular()!=$objContatoDTOBanco->getStrTelefoneCelular() ||
            $objContatoDTO->getStrTelefoneResidencial()!=$objContatoDTOBanco->getStrTelefoneResidencial()) {
          $objInfraException->lancarValidacao('Sem permissão para alterar dados informados pelo Usuário Externo.');
        }
      }

      if (!$objContatoDTO->isSetStrNumeroPassaporte()){
        $objContatoDTO->setStrNumeroPassaporte($objContatoDTOBanco->getStrNumeroPassaporte());
      }

      if (!$objContatoDTO->isSetNumIdPaisPassaporte()){
        $objContatoDTO->setNumIdPaisPassaporte($objContatoDTOBanco->getNumIdPaisPassaporte());
      }

      if (!$objContatoDTO->isSetStrNomeCidade()){
        $objContatoDTO->setStrNomeCidade(null);
      }

      if (!$objContatoDTO->isSetStrNomeUf()){
        $objContatoDTO->setStrNomeUf(null);
      }

      if (!$objContatoDTO->isSetStrFuncao()){
        $objContatoDTO->setStrFuncao($objContatoDTOBanco->getStrFuncao());
      }

      if (!$objContatoDTO->isSetStrConjuge()){
        $objContatoDTO->setStrConjuge($objContatoDTOBanco->getStrConjuge());
      }

      if (!$objContatoDTO->isSetNumIdTitulo()){
        $objContatoDTO->setNumIdTitulo($objContatoDTOBanco->getNumIdTitulo());
      }

      if (!$objContatoDTO->isSetNumIdCategoria()){
        $objContatoDTO->setNumIdCategoria($objContatoDTOBanco->getNumIdCategoria());
      }

      $this->validarNumIdContatoAssociadoRN0729($objContatoDTO, $objInfraException);
      $this->validarNumIdTipoContatoRN0367($objContatoDTO, $objInfraException);
      $this->validarStrSinEnderecoAssociadoRN0894($objContatoDTO, $objInfraException);
      $this->validarStrSiglaRN0430($objContatoDTO, $objInfraException);
      $this->validarStrNomeRN0431($objContatoDTO, $objInfraException);
      $this->validarStrNomeSocial($objContatoDTO, $objInfraException);
      $this->validarStrStaGeneroRN0433($objContatoDTO, $objInfraException);
      $this->validarNumIdCargoRN0427($objContatoDTO, $objInfraException);
      $this->validarDblCpfRN0435($objContatoDTO, $objInfraException);
      $this->validarDblRg($objContatoDTO, $objInfraException);
      $this->validarStrOrgaoExpedidor($objContatoDTO, $objInfraException);
      $this->validarDtaNascimentoRN0569($objContatoDTO, $objInfraException);
      $this->validarStrMatriculaRN0436($objContatoDTO, $objInfraException);
      $this->validarStrMatriculaOabRN0434($objContatoDTO, $objInfraException);
      $this->validarStrTelefoneCelular($objContatoDTO, $objInfraException);
      $this->validarDblCnpjRN0372($objContatoDTO, $objInfraException);
      $this->validarStrSitioInternetRN0440($objContatoDTO, $objInfraException);
      $this->validarStrTelefoneComercial($objContatoDTO, $objInfraException);
      $this->validarStrTelefoneResidencial($objContatoDTO, $objInfraException);
      $this->validarStrEmailRN0439($objContatoDTO, $objInfraException);
      $this->validarStrEnderecoRN0441($objContatoDTO, $objInfraException);
      $this->validarStrComplemento($objContatoDTO, $objInfraException);
      $this->validarStrBairroRN0442($objContatoDTO, $objInfraException);
      $this->validarNumIdPais($objContatoDTO, $objInfraException);
      $this->validarNumIdUf($objContatoDTO, $objInfraException);
      $this->validarNumIdCidade($objContatoDTO, $objInfraException);
      $this->validarStrCepRN0446($objContatoDTO, $objInfraException);
      $this->validarStrObservacaoRN0447($objContatoDTO, $objInfraException);
      $this->validarStrSinAtivoRN0449($objContatoDTO, $objInfraException);
      $this->validarNumIdPaisPassaporte($objContatoDTO, $objInfraException);
      $this->validarStrNumeroPassaporte($objContatoDTO, $objInfraException);
      $this->validarStrConjuge($objContatoDTO, $objInfraException);
      $this->validarStrFuncao($objContatoDTO, $objInfraException);
      $this->validarNumIdTitulo($objContatoDTO, $objInfraException);
      $this->validarNumIdCategoria($objContatoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if ($objContatoDTO->getStrStaNatureza() == self::$TN_PESSOA_JURIDICA) {
        $objContatoDTO->setStrNomeRegistroCivil(null);
        $objContatoDTO->setStrNomeSocial(null);
      }else{
        $objContatoDTO->setStrNomeRegistroCivil($objContatoDTO->getStrNome());
        if ($objContatoDTO->getStrNomeSocial()!=null){
          $objContatoDTO->setStrNome($objContatoDTO->getStrNomeSocial());
        }
      }

      $objContatoAPI = new ContatoAPI();
      $objContatoAPI->setIdContato($objContatoDTO->getNumIdContato());
      $objContatoAPI->setIdTipoContato($objContatoDTO->getNumIdTipoContato());
      $objContatoAPI->setIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado()!=$objContatoDTO->getNumIdContato()?$objContatoDTO->getNumIdContatoAssociado():null);
      $objContatoAPI->setStaNatureza($objContatoDTO->getStrStaNatureza());
      $objContatoAPI->setSinEnderecoAssociado($objContatoDTO->getStrSinEnderecoAssociado());
      $objContatoAPI->setStaGenero($objContatoDTO->getStrStaGenero());
      $objContatoAPI->setCpf($objContatoDTO->getDblCpf());
      $objContatoAPI->setRg($objContatoDTO->getDblRg());
      $objContatoAPI->setOrgaoExpedidor($objContatoDTO->getStrOrgaoExpedidor());
      $objContatoAPI->setMatricula($objContatoDTO->getStrMatricula());
      $objContatoAPI->setMatriculaOab($objContatoDTO->getStrMatriculaOab());
      $objContatoAPI->setDataNascimento($objContatoDTO->getDtaNascimento());
      $objContatoAPI->setCnpj($objContatoDTO->getDblCnpj());
      $objContatoAPI->setIdCargo($objContatoDTO->getNumIdCargo());
      $objContatoAPI->setSigla($objContatoDTO->getStrSigla());

      if ($objContatoDTO->getStrStaNatureza()==self::$TN_PESSOA_JURIDICA){
        $objContatoAPI->setNome($objContatoDTO->getStrNome());
        $objContatoAPI->setNomeSocial(null);
      }else{
        $objContatoAPI->setNome($objContatoDTO->getStrNomeRegistroCivil());
        $objContatoAPI->setNomeSocial($objContatoDTO->getStrNomeSocial());
      }

      $objContatoAPI->setTelefoneComercial($objContatoDTO->getStrTelefoneComercial());
      $objContatoAPI->setTelefoneResidencial($objContatoDTO->getStrTelefoneResidencial());
      $objContatoAPI->setTelefoneCelular($objContatoDTO->getStrTelefoneCelular());
      $objContatoAPI->setEmail($objContatoDTO->getStrEmail());
      $objContatoAPI->setSitioInternet($objContatoDTO->getStrSitioInternet());
      $objContatoAPI->setEndereco($objContatoDTO->getStrEndereco());
      $objContatoAPI->setComplemento($objContatoDTO->getStrComplemento());
      $objContatoAPI->setBairro($objContatoDTO->getStrBairro());
      $objContatoAPI->setCep($objContatoDTO->getStrCep());
      $objContatoAPI->setObservacao($objContatoDTO->getStrObservacao());
      $objContatoAPI->setSinAtivo($objContatoDTO->getStrSinAtivo());
      $objContatoAPI->setIdPais($objContatoDTO->getNumIdPais());
      $objContatoAPI->setIdEstado($objContatoDTO->getNumIdUf());
      $objContatoAPI->setIdCidade($objContatoDTO->getNumIdCidade());
      $objContatoAPI->setIdPaisPassaporte($objContatoDTO->getNumIdPaisPassaporte());
      $objContatoAPI->setNumeroPassaporte($objContatoDTO->getStrNumeroPassaporte());
      $objContatoAPI->setNomeEstado($objContatoDTO->getStrNomeUf());
      $objContatoAPI->setNomeCidade($objContatoDTO->getStrNomeCidade());
      $objContatoAPI->setConjuge($objContatoDTO->getStrConjuge());
      $objContatoAPI->setFuncao($objContatoDTO->getStrFuncao());
      $objContatoAPI->setIdTitulo($objContatoDTO->getNumIdTitulo());
      $objContatoAPI->setIdCategoria($objContatoDTO->getNumIdCategoria());

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('validarContato', $objContatoAPI);
      }

      $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
      $objContatoBD->alterar($objContatoDTO);

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('alterarContato', $objContatoAPI);
      }

      $this->montarIndexacao($objContatoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Contato.',$e);
    }
  }

  protected function consultarRN0324Conectado(ContatoDTO $objContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_consultar',__METHOD__,$objContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
      $ret = $objContatoBD->consultar($objContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Contato.',$e);
    }
  }

  protected function listarRN0325Conectado(ContatoDTO $objContatoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_listar',__METHOD__,$objContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
      $ret = $objContatoBD->listar($objContatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Contatos.',$e);
    }
  }

  protected function contarRN0327Conectado(ContatoDTO $objContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_listar',__METHOD__,$objContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
      $ret = $objContatoBD->contar($objContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Contatos.',$e);
    }
  }

  protected function excluirRN0326Controlado($arrObjContatoDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_excluir',__METHOD__,$arrObjContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      //complementa ocorrências com sinalizador de contexto
      $dto = new ContatoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retNumIdContato();
      $dto->retNumIdTipoContato();
      $dto->retNumIdContatoAssociado();
      $dto->retStrNome();
      $dto->retStrNomeSocial();
      $dto->retStrSigla();
      $dto->setNumIdContato(InfraArray::converterArrInfraDTO($arrObjContatoDTO,'IdContato'),InfraDTO::$OPER_IN);

      $arrObjContatoDTO = $this->listarRN0325($dto);

      $objRelGrupoContatoRN = new RelGrupoContatoRN();
      $objParticipanteRN = new ParticipanteRN();
      $objUsuarioRN = new UsuarioRN();
      $objUnidadeRN = new UnidadeRN();
      $objOrgaoRN = new OrgaoRN();

      foreach($arrObjContatoDTO as $objContatoDTO){

        $objUsuarioDTO = new UsuarioDTO();
        $objUsuarioDTO->setBolExclusaoLogica(false);
        $objUsuarioDTO->retStrStaTipo();
        $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());

        $arrObjUsuarioDTO = $objUsuarioRN->listarRN0490($objUsuarioDTO);

        foreach($arrObjUsuarioDTO as $objUsuarioDTO){
          if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE || $objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_EXTERNO){
            $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" está associado com registro de Usuário Externo.');
          }else if ($objUsuarioDTO->getStrStaTipo()==UsuarioRN::$TU_SISTEMA){
            $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" está associado com registro de Usuário de Sistema.');
          }else{
            $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" está associado com registro de Usuário.');
          }
        }

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retStrSigla();
        $objUnidadeDTO->retStrSiglaOrgao();
        $objUnidadeDTO->setNumIdContato($objContatoDTO->getNumIdContato());

        $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);

        foreach($arrObjUnidadeDTO as $objUnidadeDTO){
          $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" está associado com o registro da unidade '.$objUnidadeDTO->getStrSigla().' / '.$objUnidadeDTO->getStrSiglaOrgao().'.');
        }

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setBolExclusaoLogica(false);
        $objOrgaoDTO->retStrSigla();
        $objOrgaoDTO->setNumIdContato($objContatoDTO->getNumIdContato());

        $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

        foreach($arrObjOrgaoDTO as $objOrgaoDTO){
          $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" está associado com registro de órgao '.$objOrgaoDTO->getStrSigla().'.');
        }

        $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
        $objRelGrupoContatoDTO->retNumIdGrupoContato();
        $objRelGrupoContatoDTO->setNumIdContato($objContatoDTO->getNumIdContato());
        $objRelGrupoContatoDTO->setNumMaxRegistrosRetorno(1);

        if ($objRelGrupoContatoRN->consultarRN0482($objRelGrupoContatoDTO)!=null){
          $objInfraException->adicionarValidacao('Existem grupos utilizando o contato "'.$objContatoDTO->getStrNome().'".');
        }

        $objParticipanteDTO = new ParticipanteDTO();
        $objParticipanteDTO->retStrProtocoloFormatadoProtocolo();
        $objParticipanteDTO->setNumIdContato($objContatoDTO->getNumIdContato());
        $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);
        if (count($arrObjParticipanteDTO)>0){

          if (count($arrObjParticipanteDTO)==1){
            $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" é utilizado no protocolo '.$arrObjParticipanteDTO[0]->getStrProtocoloFormatadoProtocolo().'.');
          }else{
            $strProtocolos = '';
            for($i=0;$i<count($arrObjParticipanteDTO);$i++){

              if ($i==10){
                $strProtocolos .= '\n...';
                break;
              }

              if ($strProtocolos!=''){
                $strProtocolos .= '\n';
              }
              $strProtocolos .= $arrObjParticipanteDTO[$i]->getStrProtocoloFormatadoProtocolo();

            }

            $objInfraException->adicionarValidacao('O contato "'.$objContatoDTO->getStrNome().'" é utilizado em '.count($arrObjParticipanteDTO).' protocolos:\n'.$strProtocolos);
          }
        }

        $objContatoDTO2 = new ContatoDTO();
        $objContatoDTO2->setBolExclusaoLogica(false);
        $objContatoDTO2->setNumMaxRegistrosRetorno(1);
        $objContatoDTO2->retNumIdContato();
        $objContatoDTO2->setNumIdContato($objContatoDTO->getNumIdContato(),InfraDTO::$OPER_DIFERENTE);
        $objContatoDTO2->setNumIdContatoAssociado($objContatoDTO->getNumIdContato());
        $objContatoDTO2->setStrSinAtivo('S');

        if ($this->consultarRN0324($objContatoDTO2)!=null){
          $objInfraException->adicionarValidacao('Existem contatos associados com o contato "'.$objContatoDTO->getStrNome().'".');
        }

        $objContatoDTO2->setStrSinAtivo('N');
        if ($this->consultarRN0324($objContatoDTO2)!=null){
          $objInfraException->adicionarValidacao('Existem contatos inativos associados com o contato "'.$objContatoDTO->getStrNome().'".');
        }
      }

      $objInfraException->lancarValidacoes();

      $arrObjContatoAPI = array();
      foreach ($arrObjContatoDTO as $objContatoDTO) {
        $objContatoAPI = new ContatoAPI();
        $objContatoAPI->setIdContato($objContatoDTO->getNumIdContato());
        $objContatoAPI->setIdTipoContato($objContatoDTO->getNumIdTipoContato());
        $objContatoAPI->setIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado()!=$objContatoDTO->getNumIdContato()?$objContatoDTO->getNumIdContatoAssociado():null);
        $objContatoAPI->setSigla($objContatoDTO->getStrSigla());
        $objContatoAPI->setNome($objContatoDTO->getStrNome());
        $objContatoAPI->setNomeSocial($objContatoDTO->getStrNomeSocial());
        $arrObjContatoAPI[] = $objContatoAPI;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('excluirContato', $arrObjContatoAPI);
      }

      $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjContatoDTO);$i++){
        $objContatoBD->excluir($arrObjContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Contato.',$e);
    }
  }

  protected function desativarRN0451Controlado($arrObjContatoDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_desativar',__METHOD__,$arrObjContatoDTO);

      if (count($arrObjContatoDTO)) {

        //Regras de Negocio
        $objInfraException = new InfraException();

        $dtoRN = new ContatoRN();
        for ($i = 0; $i < count($arrObjContatoDTO); $i++) {
          $dto = new ContatoDTO();
          $dto->setBolExclusaoLogica(true);
          $dto->setNumIdContato($arrObjContatoDTO[$i]->getNumIdContato(), InfraDTO::$OPER_DIFERENTE);
          $dto->setNumIdContatoAssociado($arrObjContatoDTO[$i]->getNumIdContato());
          if ($dtoRN->contarRN0327($dto)) {
            $objInfraException->adicionarValidacao('Existem contatos associados.');
          }
        }

        $objInfraException->lancarValidacoes();

        $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjContatoDTO); $i++) {
          $objContatoBD->desativar($arrObjContatoDTO[$i]);
        }

        $dto = new ContatoDTO();
        $dto->setBolExclusaoLogica(false);
        $dto->retNumIdContato();
        $dto->retNumIdTipoContato();
        $dto->retNumIdContatoAssociado();
        $dto->retStrNome();
        $dto->retStrNomeSocial();
        $dto->retStrSigla();
        $dto->setNumIdContato(InfraArray::converterArrInfraDTO($arrObjContatoDTO, 'IdContato'), InfraDTO::$OPER_IN);

        $arrObjContatoDTO = $this->listarRN0325($dto);

        $arrObjContatoAPI = array();
        foreach ($arrObjContatoDTO as $objContatoDTO) {
          $objContatoAPI = new ContatoAPI();
          $objContatoAPI->setIdContato($objContatoDTO->getNumIdContato());
          $objContatoAPI->setIdTipoContato($objContatoDTO->getNumIdTipoContato());
          $objContatoAPI->setIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado() != $objContatoDTO->getNumIdContato() ? $objContatoDTO->getNumIdContatoAssociado() : null);
          $objContatoAPI->setSigla($objContatoDTO->getStrSigla());
          $objContatoAPI->setNome($objContatoDTO->getStrNome());
          $objContatoAPI->setNomeSocial($objContatoDTO->getStrNomeSocial());
          $arrObjContatoAPI[] = $objContatoAPI;
        }

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('desativarContato', $arrObjContatoAPI);
        }

        $dtoRN = new RelGrupoContatoRN();
        $dto = new RelGrupoContatoDTO();
        $dto->retNumIdGrupoContato();
        $dto->retNumIdContato();
        for ($i = 0; $i < count($arrObjContatoDTO); $i++) {
            $dto->setNumIdContato($arrObjContatoDTO[$i]->getNumIdContato());
            $dtoRN->excluirRN0464($dtoRN->listarRN0463($dto));
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Contato.',$e);
    }
  }

  protected function reativarRN0452Controlado($arrObjContatoDTO){
    try {

      global $SEI_MODULOS;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_reativar',__METHOD__,$arrObjContatoDTO);

      if (count($arrObjContatoDTO)) {

        //Regras de Negocio
        //$objInfraException = new InfraException();
        //$objInfraException->lancarValidacoes();

        $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjContatoDTO); $i++) {
          $objContatoBD->reativar($arrObjContatoDTO[$i]);
        }

        $dto = new ContatoDTO();
        $dto->setBolExclusaoLogica(false);
        $dto->retNumIdContato();
        $dto->retNumIdTipoContato();
        $dto->retNumIdContatoAssociado();
        $dto->retStrNome();
        $dto->retStrNomeSocial();
        $dto->retStrSigla();
        $dto->setNumIdContato(InfraArray::converterArrInfraDTO($arrObjContatoDTO, 'IdContato'), InfraDTO::$OPER_IN);

        $arrObjContatoDTO = $this->listarRN0325($dto);

        $arrObjContatoAPI = array();
        foreach ($arrObjContatoDTO as $objContatoDTO) {
          $objContatoAPI = new ContatoAPI();
          $objContatoAPI->setIdContato($objContatoDTO->getNumIdContato());
          $objContatoAPI->setIdTipoContato($objContatoDTO->getNumIdTipoContato());
          $objContatoAPI->setIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado() != $objContatoDTO->getNumIdContato() ? $objContatoDTO->getNumIdContatoAssociado() : null);
          $objContatoAPI->setSigla($objContatoDTO->getStrSigla());
          $objContatoAPI->setNome($objContatoDTO->getStrNome());
          $objContatoAPI->setNomeSocial($objContatoDTO->getStrNomeSocial());
          $arrObjContatoAPI[] = $objContatoAPI;
        }

        foreach ($SEI_MODULOS as $seiModulo) {
          $seiModulo->executar('reativarContato', $arrObjContatoAPI);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Contato.',$e);
    }
  }

  public static function listarNaturezas(){
    $arr = array();

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(ContatoRN::$TN_PESSOA_FISICA);
    $objTipoDTO->setStrDescricao('Pessoa Física');
    $arr[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(ContatoRN::$TN_PESSOA_JURIDICA);
    $objTipoDTO->setStrDescricao('Pessoa Jurídica');
    $arr[] = $objTipoDTO;

    return $arr;
  }

  private function validarStrStaNatureza(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrStaNatureza())){
      $objInfraException->lancarValidacao('Natureza não informada.');
    }else{
      if (!in_array($objContatoDTO->getStrStaNatureza(),InfraArray::converterArrInfraDTO(ContatoRN::listarNaturezas(),'StaTipo'))){
        $objInfraException->lancarValidacao('Natureza inválida.');
      }
    }
  }

  private function validarNumIdCargoRN0427(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdCargo())){
      $objContatoDTO->setNumIdCargo(null);
    }else {
      if ($objContatoDTO->getStrStaNatureza() == ContatoRN::$TN_PESSOA_JURIDICA) {
        $objInfraException->adicionarValidacao('Não é possível informar o Cargo para Pessoa Jurídica.');
      }
    }
  }

  private function validarNumIdCategoria(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdCategoria())){
      $objContatoDTO->setNumIdCategoria(null);
    }else {
      if ($objContatoDTO->getStrStaNatureza() == ContatoRN::$TN_PESSOA_JURIDICA) {
        $objInfraException->adicionarValidacao('Não é possível informar o Categoria para Pessoa Jurídica.');
      }
    }
  }

  private function validarStrSiglaRN0430(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrSigla())){
      $objContatoDTO->setStrSigla(null);
    }else{
      $objContatoDTO->setStrSigla(trim($objContatoDTO->getStrSigla()));

      if (strlen($objContatoDTO->getStrSigla())>100){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 100 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrSigla(), 'Sigla', $objInfraException);
    }
  }

  private function validarStrNomeRN0431(ContatoDTO $objContatoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objContatoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objContatoDTO->setStrNome(trim($objContatoDTO->getStrNome()));

      if (strlen($objContatoDTO->getStrNome())>250){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 250 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrNome(), 'Nome', $objInfraException);
    }
  }

  private function validarStrNomeSocial(ContatoDTO $objContatoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objContatoDTO->getStrNomeSocial())){
      $objContatoDTO->setStrNomeSocial(null);
    }else{
      $objContatoDTO->setStrNomeSocial(trim($objContatoDTO->getStrNomeSocial()));

      if (strlen($objContatoDTO->getStrNomeSocial())>250){
        $objInfraException->adicionarValidacao('Nome Social possui tamanho superior a 250 caracteres.');
      }

      if ($objContatoDTO->getStrNomeSocial()==$objContatoDTO->getStrNome()){
        $objInfraException->lancarValidacao('Nome Social igual ao Nome do contato.');
      }

      $this->verificarXss($objContatoDTO->getStrNomeSocial(), 'Nome Social', $objInfraException);
    }
  }

  private function validarDtaNascimentoRN0569(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getDtaNascimento())){
      $objContatoDTO->setDtaNascimento(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar a Data de Nascimento para Pessoa Jurídica.');
      }

      if (!InfraData::validarData($objContatoDTO->getDtaNascimento())){
        $objInfraException->adicionarValidacao('Data de Nascimento inválida.');
      }
    }
  }

  private function validarStrStaGeneroRN0433(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrStaGenero())){
      $objContatoDTO->setStrStaGenero(null);
    }else{

//      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
//        $objInfraException->adicionarValidacao('Não é possível informar o Gênero para Pessoa Jurídica.');
//      }

      if ($objContatoDTO->getStrStaGenero()!=self::$TG_MASCULINO && $objContatoDTO->getStrStaGenero()!=self::$TG_FEMININO){
        $objInfraException->adicionarValidacao('Gênero inválido.');
      }
    }
  }

  private function validarDblCpfRN0435(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getDblCpf())){
      $objContatoDTO->setDblCpf(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar o CPF para Pessoa Jurídica.');
      }

      if(!InfraUtil::validarCpf($objContatoDTO->getDblCpf())){
        $objInfraException->adicionarValidacao('Número de CPF inválido.');
      }
      $objContatoDTO->setDblCpf(InfraUtil::retirarFormatacao($objContatoDTO->getDblCpf()));
    }
  }

  private function validarDblRg(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getDblRg())){
      $objContatoDTO->setDblRg(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar o RG para Pessoa Jurídica.');
      }

      $objContatoDTO->setDblRg(InfraUtil::retirarFormatacao($objContatoDTO->getDblRg()));
    }
  }

  private function validarStrOrgaoExpedidor(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrOrgaoExpedidor())){
      $objContatoDTO->setStrOrgaoExpedidor(null);
    }else {

      if ($objContatoDTO->getStrStaNatureza() == ContatoRN::$TN_PESSOA_JURIDICA) {
        $objInfraException->adicionarValidacao('Não é possível informar o Órgão Expedidor para Pessoa Jurídica.');
      }

      $objContatoDTO->setStrOrgaoExpedidor(trim($objContatoDTO->getStrOrgaoExpedidor()));

      if (strlen($objContatoDTO->getStrOrgaoExpedidor()) > 50) {
        $objInfraException->adicionarValidacao('Órgão Expedidor possui tamanho superior a 50 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrOrgaoExpedidor(), 'Órgão Expedidor', $objInfraException);
    }
  }

  private function validarStrMatriculaRN0436(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrMatricula())){
      $objContatoDTO->setStrMatricula(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar a Matrícula para Pessoa Jurídica.');
      }

      $objContatoDTO->setStrMatricula(trim($objContatoDTO->getStrMatricula()));

      if (strlen($objContatoDTO->getStrMatricula())>10){
        $objInfraException->adicionarValidacao('Matrícula possui tamanho superior a 10 caracteres.');
      }
    }
  }

  private function validarStrMatriculaOabRN0434(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrMatriculaOab())){
      $objContatoDTO->setStrMatriculaOab(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar Matrícula OAB para Pessoa Jurídica.');
      }

      $objContatoDTO->setStrMatriculaOab(trim($objContatoDTO->getStrMatriculaOab()));

      if (strlen($objContatoDTO->getStrMatriculaOab())>10){
        $objInfraException->adicionarValidacao('Número da OAB possui tamanho superior a 10 caracteres.');
      }
    }
  }
  private function validarStrConjuge(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrConjuge())){
      $objContatoDTO->setStrConjuge(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar Cônjuge para Pessoa Jurídica.');
      }

      $objContatoDTO->setStrConjuge(trim($objContatoDTO->getStrConjuge()));

      if (strlen($objContatoDTO->getStrConjuge())>100){
        $objInfraException->adicionarValidacao('Cônjuge possui tamanho superior a 100 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrConjuge(), 'Cônjuge', $objInfraException);
    }
  }
  private function validarStrFuncao(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrFuncao())){
      $objContatoDTO->setStrFuncao(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar Função para Pessoa Jurídica.');
      }

      $objContatoDTO->setStrFuncao(trim($objContatoDTO->getStrFuncao()));

      if (strlen($objContatoDTO->getStrFuncao())>100){
        $objInfraException->adicionarValidacao('Função possui tamanho superior a 10 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrFuncao(), 'Função', $objInfraException);
    }
  }
  private function validarNumIdTitulo(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdTitulo())){
      $objContatoDTO->setNumIdTitulo(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar Título para Pessoa Jurídica.');
      }
    }
  }

  private function validarStrTelefoneComercial(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrTelefoneComercial())){
      $objContatoDTO->setStrTelefoneComercial(null);
    }else{
      $objContatoDTO->setStrTelefoneComercial(trim($objContatoDTO->getStrTelefoneComercial()));

      if (strlen($objContatoDTO->getStrTelefoneComercial())>100){
        $objInfraException->adicionarValidacao('Telefone Comercial possui tamanho superior a 100 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrTelefoneComercial(), 'Telefone Comercial', $objInfraException);
    }
  }
  private function validarStrTelefoneResidencial(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrTelefoneResidencial())){
      $objContatoDTO->setStrTelefoneResidencial(null);
    }else{
      $objContatoDTO->setStrTelefoneResidencial(trim($objContatoDTO->getStrTelefoneResidencial()));

      if (strlen($objContatoDTO->getStrTelefoneResidencial())>50){
        $objInfraException->adicionarValidacao('Telefone Residencial possui tamanho superior a 50 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrTelefoneResidencial(), 'Telefone Residencial', $objInfraException);
    }
  }

  private function validarStrTelefoneCelular(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrTelefoneCelular())){
      $objContatoDTO->setStrTelefoneCelular(null);
    }else{

      $objContatoDTO->setStrTelefoneCelular(trim($objContatoDTO->getStrTelefoneCelular()));

      if (strlen($objContatoDTO->getStrTelefoneCelular())>50){
        $objInfraException->adicionarValidacao('Telefone Celular possui tamanho superior a 50 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrTelefoneCelular(), 'Telefone Celular', $objInfraException);
    }
  }

  private function validarStrEmailRN0439(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrEmail())){
      $objContatoDTO->setStrEmail(null);
    }else{
      $objContatoDTO->setStrEmail(trim($objContatoDTO->getStrEmail()));
      if (strlen($objContatoDTO->getStrEmail())>100){
        $objInfraException->adicionarValidacao('E-mail possui tamanho superior a 100 caracteres.');
      }
      if (strpos($objContatoDTO->getStrEmail(), ';') !== false) {
        $arrStrEmail = explode(";",$objContatoDTO->getStrEmail());
        foreach ($arrStrEmail as $strEmail){
          $strEmail = trim($strEmail);
          if(!InfraString::isBolVazia($strEmail)){
            if (!InfraUtil::validarEmail($strEmail)){
              $objInfraException->adicionarValidacao('E-mail '.$strEmail.' inválido.');
            }
          }
        }
      }else{
        if (!InfraUtil::validarEmail($objContatoDTO->getStrEmail())){
          $objInfraException->adicionarValidacao('E-mail inválido.');
        }
      }
    }
  }

  private function validarStrSitioInternetRN0440(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrSitioInternet())){
      $objContatoDTO->setStrSitioInternet(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_FISICA){
        $objInfraException->adicionarValidacao('Não é possível informar Sítio na Internet para Pessoa Física.');
      }

      $objContatoDTO->setStrSitioInternet(trim($objContatoDTO->getStrSitioInternet()));

      if (strlen($objContatoDTO->getStrSitioInternet())>50){
        $objInfraException->adicionarValidacao('Sítio na Internet possui tamanho superior a 50 caracteres');
      }

      $this->verificarXss($objContatoDTO->getStrSitioInternet(), 'Sítio na Internet', $objInfraException);
    }
  }

  private function validarStrEnderecoRN0441(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrEndereco())){
      $objContatoDTO->setStrEndereco(null);
    }else{
      $objContatoDTO->setStrEndereco(trim($objContatoDTO->getStrEndereco()));

      if (strlen($objContatoDTO->getStrEndereco())>130){
        $objInfraException->adicionarValidacao('Endereço possui tamanho superior a 130 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrEndereco(), 'Endereço', $objInfraException);
    }
  }

  private function validarStrComplemento(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrComplemento())){
      $objContatoDTO->setStrComplemento(null);
    }else{
      $objContatoDTO->setStrComplemento(trim($objContatoDTO->getStrComplemento()));

      if (strlen($objContatoDTO->getStrComplemento())>130){
        $objInfraException->adicionarValidacao('Complemento do endereço possui tamanho superior a 130 caracteres.');
      }
      $this->verificarXss($objContatoDTO->getStrComplemento(), 'Complemento do endereço', $objInfraException);
    }
  }

  private function validarStrBairroRN0442(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrBairro())){
      $objContatoDTO->setStrBairro(null);
    }else{
      $objContatoDTO->setStrBairro(trim($objContatoDTO->getStrBairro()));

      if (strlen($objContatoDTO->getStrBairro()) > 70){
        $objInfraException->adicionarValidacao('Bairro possui tamanho superior a 70 caracteres.');
      }
      $this->verificarXss($objContatoDTO->getStrBairro(), 'Bairro', $objInfraException);
    }
  }

  private function validarNumIdUf(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdUf())){
      $objContatoDTO->setNumIdUf(null);

      if($objContatoDTO->getNumIdPais() != PaisINT::buscarIdPaisBrasil() && $objContatoDTO->isSetStrNomeUf() && !InfraString::isBolVazia($objContatoDTO->getStrNomeUf())) {
        $objUfDTO = new UfDTO();
        $objUfDTO->setNumIdPais($objContatoDTO->getNumIdPais());
        $objUfDTO->adicionarCriterio(array('Nome','Sigla'),
                                      array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                      array($objContatoDTO->getStrNomeUf(),$objContatoDTO->getStrNomeUf()),
                                      InfraDTO::$OPER_LOGICO_OR);

        $objUfRN = new UfRN ();

        $objUfDTO->retNumIdUf();
        $objUfDTO_Consulta = $objUfRN->listarRN0401($objUfDTO);
        $numRegistros = count($objUfDTO_Consulta);
        if ($numRegistros == 0) {
          $objUfDTO->setStrSigla(null);
          $objUfDTO->setStrNome($objContatoDTO->getStrNomeUf());
          $objUfDTO->setNumIdUf(null);
          $objUfDTO_Cadastro = $objUfRN->cadastrarRN0398($objUfDTO);
          $objContatoDTO->setNumIdUf($objUfDTO_Cadastro->getNumIdUf());
        }else{
          $objContatoDTO->setNumIdUf($objUfDTO_Consulta[0]->getNumIdUf());
        }

      }
    }else if ($objContatoDTO->getNumIdPais()==null){
      $objUfDTO = new UfDTO();
      $objUfDTO->setNumIdUf($objContatoDTO->getNumIdUf());
      $objUfDTO->setNumIdPais(PaisINT::buscarIdPaisBrasil());

      $objUfRN = new UfRN();
      if ($objUfRN->contarRN0402($objUfDTO) > 0) {
        $objContatoDTO->setNumIdPais(PaisINT::buscarIdPaisBrasil());
      }else {
        $objInfraException->lancarValidacao('País associado com o Estado não informado.');
      }
    }else if ($objContatoDTO->getNumIdPais()==PaisINT::buscarIdPaisBrasil()) {

      $objUfDTO = new UfDTO();
      $objUfDTO->setNumIdUf($objContatoDTO->getNumIdUf());
      $objUfDTO->setNumIdPais($objContatoDTO->getNumIdPais());

      $objUfRN = new UfRN();
      if ($objUfRN->contarRN0402($objUfDTO) == 0) {
        $objInfraException->lancarValidacao('Estado não pertence ao País do contato.');
      }
    }
  }

  private function validarNumIdCidade(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdCidade())){
      $objContatoDTO->setNumIdCidade(null);

      if($objContatoDTO->getNumIdPais() != PaisINT::buscarIdPaisBrasil() && $objContatoDTO->isSetStrNomeCidade() && !InfraString::isBolVazia($objContatoDTO->getStrNomeCidade())) {
        $objCidadeDTO = new CidadeDTO();
        $objCidadeDTO->setNumIdPais($objContatoDTO->getNumIdPais());
        $objCidadeDTO->setNumIdUf($objContatoDTO->getNumIdUf());
        $objCidadeDTO->setStrNome($objContatoDTO->getStrNomeCidade());

        $objCidadeRN = new CidadeRN ();

        $objCidadeDTO->retNumIdCidade();
        $objCidadeDTO_Consulta = $objCidadeRN->consultarRN0409($objCidadeDTO);
        if ($objCidadeDTO_Consulta == null) {
          $objCidadeDTO->setNumIdCidade(null);
          $objCidadeDTO->setStrSinCapital("N");
          $objCidadeDTO->setDblLatitude(null);
          $objCidadeDTO->setDblLongitude(null);
          $objCidadeDTO->setNumCodigoIbge(null);
          $objCidadeDTO_Consulta = $objCidadeRN->cadastrarRN0407($objCidadeDTO);
        }
        $objContatoDTO->setNumIdCidade($objCidadeDTO_Consulta->getNumIdCidade());
      }
    }else if ($objContatoDTO->getNumIdPais()==null){
      $objInfraException->lancarValidacao('País associado com essa Cidade não informado.');
    }else if ($objContatoDTO->getNumIdPais() == PaisINT::buscarIdPaisBrasil() && $objContatoDTO->getNumIdUf()==null){
      $objInfraException->lancarValidacao('Estado associado com essa Cidade não informado.');
    }else {
      $objCidadeRN = new CidadeRN();

      $objCidadeDTO = new CidadeDTO();
      $objCidadeDTO->setNumIdCidade($objContatoDTO->getNumIdCidade());
      $objCidadeDTO->setNumIdPais($objContatoDTO->getNumIdPais());
      if ($objCidadeRN->contarRN0414($objCidadeDTO) == 0) {
        $objInfraException->lancarValidacao('Cidade não pertence ao País do contato.');
      }
      if ($objContatoDTO->getNumIdUf() != null) {
        $objCidadeDTO->setNumIdUf($objContatoDTO->getNumIdUf());
        if ($objCidadeRN->contarRN0414($objCidadeDTO) == 0) {
          $objInfraException->lancarValidacao('Cidade não pertence ao Estado do contato.');
        }
      }
    }
  }

  private function validarNumIdPais(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdPais())){
      $objContatoDTO->setNumIdPais(null);
    }
  }

  private function validarStrCepRN0446(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrCep())){
      $objContatoDTO->setStrCep(null);
    }else{
      $objContatoDTO->setStrCep(trim($objContatoDTO->getStrCep()));

      if (strlen($objContatoDTO->getStrCep())>15){
        $objInfraException->adicionarValidacao('CEP possui tamanho superior a 15 caracteres.');
      }
    }
  }

  private function validarStrObservacaoRN0447(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrObservacao())){
      $objContatoDTO->setStrObservacao(null);
    }else{
      $objContatoDTO->setStrObservacao(trim($objContatoDTO->getStrObservacao()));

      if (strlen($objContatoDTO->getStrObservacao())>250){
        $objInfraException->adicionarValidacao('Observação possui tamanho superior a 250 caracteres.');
      }

      $this->verificarXss($objContatoDTO->getStrObservacao(), 'Observação', $objInfraException);
    }
  }

  private function validarStrIdxContatoRN0448(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrIdxContato())){
      $objContatoDTO->setStrIdxContato(null);
    }else{
      $objContatoDTO->setStrIdxContato(trim($objContatoDTO->getStrIdxContato()));

      if (strlen($objContatoDTO->getStrIdxContato())>1000){
        $objInfraException->adicionarValidacao('Indexação possui tamanho superior a 1000 caracteres.');
      }
    }
  }

  private function validarStrSinEnderecoAssociadoRN0894(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrSinEnderecoAssociado())){
      $objInfraException->adicionarValidacao('Sinalizador de uso do endereço do contato associado não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objContatoDTO->getStrSinEnderecoAssociado())){
        $objInfraException->adicionarValidacao('Sinalizador de uso do endereço do contato associado inválido.');
      }else{
        if ($objContatoDTO->getStrSinEnderecoAssociado()=='S' && $objContatoDTO->getNumIdContato()==$objContatoDTO->getNumIdContatoAssociado()){
          $objInfraException->adicionarValidacao('Não é possível usar o endereço associado pois não existe Pessoa Jurídica associada.');
        }
      }
    }
  }

  private function validarStrSinAtivoRN0449(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objContatoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarNumIdContatoAssociadoRN0729(ContatoDTO $objContatoDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objContatoDTO->getNumIdContatoAssociado())){
      $objContatoDTO->setNumIdContatoAssociado(null);
    }

    if ($objContatoDTO->getNumIdContato()!=$objContatoDTO->getNumIdContatoAssociado()) {

      $dto = new ContatoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retNumIdContato();
      $dto->retNumIdContatoAssociado();
      $dto->retNumIdTipoContato();
      $dto->retStrStaNatureza();
      $dto->setNumIdContato($objContatoDTO->getNumIdContatoAssociado());
      $dto = $this->consultarRN0324($dto);

      if ($dto == null) {
        throw new InfraException('Contato associado não encontrado.');
      }

      if ($dto->getStrStaNatureza() == ContatoRN::$TN_PESSOA_FISICA) {
        $objInfraException->lancarValidacao('Não é possível realizar associação com uma Pessoa Física.');
      }

      //if ($dto->getNumIdContatoAssociado()!=$dto->getNumIdContato()){
      //  $objInfraException->lancarValidacao('Não é possível realizar associação com uma Pessoa Jurídica que já está associada com outra Pessoa Jurídica.');
      //}

      if ($objContatoDTO->getNumIdContato()!=null){

        $objOrgaoDTO = new OrgaoDTO();
        $objOrgaoDTO->setBolExclusaoLogica(false);
        $objOrgaoDTO->retNumIdOrgao();
        $objOrgaoDTO->setNumIdContato($objContatoDTO->getNumIdContato());
        $objOrgaoDTO->setNumMaxRegistrosRetorno(1);

        $objOrgaoRN = new OrgaoRN();
        if ($objOrgaoRN->consultarRN1352($objOrgaoDTO)!=null && $objContatoDTO->getNumIdContatoAssociado()!=null){
          $objInfraException->lancarValidacao('Não é possível associar uma Pessoa Jurídica com um Órgão.');
        }

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->setNumIdContatoOrgao($objContatoDTO->getNumIdContatoAssociado(),InfraDTO::$OPER_DIFERENTE);
        $objUnidadeDTO->setNumIdContato($objContatoDTO->getNumIdContato());
        $objUnidadeDTO->setNumMaxRegistrosRetorno(1);

        $objUnidadeRN = new UnidadeRN();
        if ($objUnidadeRN->consultarRN0125($objUnidadeDTO)!=null){
          $objInfraException->lancarValidacao('Não é possível alterar a Pessoa Jurídica associada com uma Unidade.');
        }

        if (!$objContatoDTO->isSetStrStaOperacao() || $objContatoDTO->getStrStaOperacao()!='REPLICACAO') {
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setBolExclusaoLogica(false);
          $objUsuarioDTO->retNumIdUsuario();
          $objUsuarioDTO->setNumIdContatoOrgao($objContatoDTO->getNumIdContatoAssociado(), InfraDTO::$OPER_DIFERENTE);
          $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());
          $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);
          $objUsuarioDTO->setNumMaxRegistrosRetorno(1);

          $objUsuarioRN = new UsuarioRN();
          if ($objUsuarioRN->consultarRN0489($objUsuarioDTO)!=null) {
            $objInfraException->lancarValidacao('Não é possível alterar a Pessoa Jurídica associada com um Usuário.');
          }
        }
      }
    }
  }

  private function validarNumIdPaisPassaporte(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdPaisPassaporte()) || $objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
      $objContatoDTO->setNumIdPaisPassaporte(null);
    }
  }

  private function validarStrNumeroPassaporte(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getStrNumeroPassaporte())){
      $objContatoDTO->setStrNumeroPassaporte(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA){
        $objInfraException->adicionarValidacao('Não é possível informar Número do Passaporte para Pessoa Jurídica.');
      }

      $objContatoDTO->setStrNumeroPassaporte(trim($objContatoDTO->getStrNumeroPassaporte()));

      if (preg_match("/[^0-9A-Z-\s]/",$objContatoDTO->getStrNumeroPassaporte())){
        $objInfraException->adicionarValidacao('Número do Passaporte possui caracteres inválidos');
      }
    }
  }

  protected function montarIndexacaoControlado(ContatoDTO $objContatoDTO){

    $dto = new ContatoDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retNumIdContato();
    $dto->retStrSigla();
    $dto->retStrNome();
    $dto->retStrNomeRegistroCivil();
    $dto->retStrNomeSocial();
    $dto->retDblCpf();
    $dto->retDblCnpj();
    $dto->retStrMatricula();
    $dto->retStrNumeroPassaporte();

    if (is_array($objContatoDTO->getNumIdContato())){
      $dto->setNumIdContato($objContatoDTO->getNumIdContato(),InfraDTO::$OPER_IN);
    }else{
      $dto->setNumIdContato($objContatoDTO->getNumIdContato());
    }

    $objInfraException = new InfraException();
    $objContatoBD = new ContatoBD($this->getObjInfraIBanco());
    $objContatoDTOIdx = new ContatoDTO();

    $arrObjContatoDTO = $this->listarRN0325($dto);

    foreach($arrObjContatoDTO as $dto) {

      $strCpf = InfraUtil::formatarCpf($dto->getDblCpf());
      $strCnpj = InfraUtil::formatarCnpj($dto->getDblCnpj());

      $objContatoDTOIdx->setNumIdContato($dto->getNumIdContato());

      $strIndexacao = '';
      $strIndexacao .= ' '.$dto->getStrSigla();
      $strIndexacao .= ' '.$dto->getStrNome();

      if ($dto->getStrNomeRegistroCivil()!=$dto->getStrNome()){
        $strIndexacao .= ' '.$dto->getStrNomeRegistroCivil();
      }

      if ($dto->getStrNomeSocial()!=$dto->getStrNome()){
        $strIndexacao .= ' '.$dto->getStrNomeSocial();
      }

      $strIndexacao .= ' '.InfraUtil::retirarFormatacao($strCpf);
      $strIndexacao .= ' '.InfraUtil::retirarFormatacao($strCnpj);
      $strIndexacao .= ' '.$dto->getStrMatricula();
      $strIndexacao .= ' '.InfraUtil::retirarFormatacao($dto->getStrNumeroPassaporte(),false);
      $strIndexacao = InfraString::prepararIndexacao($strIndexacao);
      $strIndexacao .= ' '.$strCpf;
      $strIndexacao .= ' '.$strCnpj;
      $strIndexacao .= ' '.$dto->getStrNumeroPassaporte();

      $objContatoDTOIdx->setStrIdxContato($strIndexacao);

      $this->validarStrIdxContatoRN0448($objContatoDTOIdx, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objContatoBD->alterar($objContatoDTOIdx);
    }
  }

  protected function pesquisarRN0471Conectado(ContatoDTO $objContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('contato_listar',__METHOD__,$objContatoDTO);

      LimiteSEI::getInstance()->configurarNivel2();

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objContatoDTO->isSetNumIdGrupoContato()){
        if ($objContatoDTO->getNumIdGrupoContato()==null) {
          $objContatoDTO->unSetNumIdGrupoContato();
        }else{
          $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
          $objRelGrupoContatoRN = new RelGrupoContatoRN();

          $objRelGrupoContatoDTO->retNumIdContato();
          $objRelGrupoContatoDTO->setNumIdGrupoContato($objContatoDTO->getNumIdGrupoContato());
          $arrIdContatoGrupo = InfraArray::converterArrInfraDTO($objRelGrupoContatoRN->listarRN0463($objRelGrupoContatoDTO),'IdContato');

          if (count($arrIdContatoGrupo)){
            $objContatoDTO->setNumIdContato($arrIdContatoGrupo,InfraDTO::$OPER_IN);
          }else{
            $objContatoDTO->setNumIdContato(null);
          }
        }
      }

      $objContatoDTO = InfraString::prepararPesquisaDTO($objContatoDTO,"PalavrasPesquisa", "IdxContato");

      if ($objContatoDTO->isSetNumIdTipoContato()){
        if ($objContatoDTO->getNumIdTipoContato()==null) {
          $objContatoDTO->unSetNumIdTipoContato();
        }
      }

      if ($objContatoDTO->isSetNumIdCategoria()){
        if ($objContatoDTO->getNumIdCategoria()==null) {
          $objContatoDTO->unSetNumIdCategoria();
        }
      }

      if ($objContatoDTO->isSetNumIdCargo()){
        if ($objContatoDTO->getNumIdCargo()==null) {
          $objContatoDTO->unSetNumIdCargo();
        }
      }

      //Se informou pelo menos uma data
      if ($objContatoDTO->isSetDtaNascimentoInicio() || $objContatoDTO->isSetDtaNascimentoFim()){

        if (!$objContatoDTO->isSetDtaNascimentoInicio() || InfraString::isBolVazia($objContatoDTO->getDtaNascimentoInicio())){
          $objInfraException->lancarValidacao('Data inicial do período de nascimento não informada.');
        }

        if (!$objContatoDTO->isSetDtaNascimentoFim() || InfraString::isBolVazia($objContatoDTO->getDtaNascimentoFim())){
          $objInfraException->lancarValidacao('Data final do período de nascimento não informada.');
        }

        $strAnoAtual = Date("Y");
        $strDataInicio = $objContatoDTO->getDtaNascimentoInicio().'/'.$strAnoAtual;

        if (!InfraData::validarData($strDataInicio)){
          $objInfraException->lancarValidacao('Data inicial do período de nascimento inválida.');
        }

        $strDataFim = $objContatoDTO->getDtaNascimentoFim().'/'.$strAnoAtual;
        if (!InfraData::validarData($strDataFim)){
          $objInfraException->lancarValidacao('Data final do período de nascimento inválida.');
        }

        if (InfraData::compararDatas($strDataInicio,$strDataFim)<0){
          $objInfraException->lancarValidacao('Período de datas de nascimento inválido.');
        }

        $objContatoDTO->setDtaNascimento(null,InfraDTO::$OPER_DIFERENTE);

        $dto = new ContatoDTO();
        $dto->setDistinct(true);
        $dto->retDtaNascimento();
        $dto->setDtaNascimento(null,InfraDTO::$OPER_DIFERENTE);
        $arr = $this->listarRN0325($dto);


        $arrCriterios = array();
        foreach($arr as $dto){
          $strAno = substr($dto->getDtaNascimento(),6,4);
          if (!in_array($strAno,$arrCriterios)){
            //Adiciona critério com o nome igual ao do ano

            $strDataIni = $objContatoDTO->getDtaNascimentoInicio().'/'.$strAno;
            $strDataFim = $objContatoDTO->getDtaNascimentoFim().'/'.$strAno;

            if (!InfraData::validarData($strDataIni)){
              if (substr($strDataIni,0,5)=='29/02'){
                $strDataIni = '01/03/'.$strAno;
              }else{
                throw new InfraException('Data inicial inválida.');
              }
            }
            if (!InfraData::validarData($strDataFim)){
              if (substr($strDataFim,0,5)=='29/02'){
                $strDataFim = '28/02/'.$strAno;
              }else{
                throw new InfraException('Data final inválida.');
              }
            }

            $objContatoDTO->adicionarCriterio(array('Nascimento','Nascimento'),
              array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
              array($strDataIni,$strDataFim),
              array(InfraDTO::$OPER_LOGICO_AND),
              $strAno);
            $arrCriterios[] = $strAno;
          }
        }

        $arrOperadores = array_fill(0,count($arrCriterios)-1,InfraDTO::$OPER_LOGICO_OR);
        $objContatoDTO->agruparCriterios($arrCriterios,$arrOperadores);
      }

      $objInfraException->lancarValidacoes();

      return $this->listarRN0325($objContatoDTO);


      //Auditoria
    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Contato.',$e);
    }
  }

  private function validarDblCnpjRN0372(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getDblCnpj())){
      $objContatoDTO->setDblCnpj(null);
    }else{

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_FISICA){
        $objInfraException->adicionarValidacao('Não é possível informar CNPJ para Pessoa Física.');
      }

      if(!InfraUtil::validarCnpj($objContatoDTO->getDblCnpj())){
        $objInfraException->adicionarValidacao('Número de CNPJ inválido.');
      }
      $objContatoDTO->setDblCnpj(InfraUtil::retirarFormatacao($objContatoDTO->getDblCnpj()));
    }
  }

  private function validarNumIdTipoContatoRN0367(ContatoDTO $objContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objContatoDTO->getNumIdTipoContato())){
      $objInfraException->adicionarValidacao('Tipo do Contato não informado.');
    }
  }

  protected function cadastrarContextoTemporarioControlado(ContatoDTO $parObjContatoDTO){
    try{

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $numIdTipoContato = $objInfraParametro->getValor('ID_TIPO_CONTATO_TEMPORARIO');

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->setStrNome(trim($parObjContatoDTO->getStrNome()));
      $objContatoDTO->setNumIdTipoContato($numIdTipoContato);

      $arrObjContatoDTO = $this->listarRN0325($objContatoDTO);

      if (count($arrObjContatoDTO)){
        return $arrObjContatoDTO[0];
      }

      //cadastra contato
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setNumIdContato(null);
      $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
      $objContatoDTO->setNumIdContatoAssociado(null);
      $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_FISICA);
      $objContatoDTO->setStrNome($parObjContatoDTO->getStrNome());

      if ($parObjContatoDTO->isSetStrSigla() && !InfraString::isBolVazia($parObjContatoDTO->getStrSigla())){
        $objContatoDTO->setStrSigla($parObjContatoDTO->getStrSigla());
      }

      if ($parObjContatoDTO->isSetDblCpf() && !InfraString::isBolVazia($parObjContatoDTO->getDblCpf())){
        $objContatoDTO->setDblCpf($parObjContatoDTO->getDblCpf());
      }

      if ($parObjContatoDTO->isSetDblCnpj() && !InfraString::isBolVazia($parObjContatoDTO->getDblCnpj())){
        $objContatoDTO->setDblCnpj($parObjContatoDTO->getDblCnpj());
        $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_JURIDICA);
      }

      $objContatoDTO->setStrSinEnderecoAssociado('N');
      $objContatoDTO->setStrSinAtivo('S');

      $objContatoDTO = $this->cadastrarRN0322($objContatoDTO);

      return $objContatoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando contexto temporário.',$e);
    }

  }

  protected function substituirConectado(ContatoSubstituirDTO $objContatoSubstituirDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('contato_substituir_temporario',__METHOD__,$objContatoSubstituirDTO);

      // Faz a alteracao de banco para os Contextos
      $arrIdProtocolos = $this->substituirInterno($objContatoSubstituirDTO);

      $objIndexacaoRN  = new IndexacaoRN();
      $objIndexacaoDTO = new IndexacaoDTO();

      $objIndexacaoDTO->setArrIdProtocolos($arrIdProtocolos);
      $objIndexacaoDTO->setStrStaOperacao(IndexacaoRN::$TO_PROTOCOLO_METADADOS);
      $objIndexacaoRN->indexarProtocolo($objIndexacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro substituindo contexto temporário.',$e);
    }

  }

  protected function substituirInternoControlado(ContatoSubstituirDTO $objContatoSubstituirDTO){
    try{

      global $SEI_MODULOS;

      LimiteSEI::getInstance()->configurarNivel2();

      $objInfraException = new InfraException();

      $arrIdContato = InfraArray::converterArrInfraDTO($objContatoSubstituirDTO->getArrObjContato(),'IdContato');
      $numIdContato = $objContatoSubstituirDTO->getNumIdContato();

      if (in_array($numIdContato,$arrIdContato)){
        $objInfraException->lancarValidacao('Contato consta na lista para substituição.');
      }

      $objParticipanteDTO = new ParticipanteDTO();
      $objParticipanteDTO->retNumIdParticipante();
      $objParticipanteDTO->retDblIdProtocolo();
      $objParticipanteDTO->retStrStaParticipacao();
      $objParticipanteDTO->setNumIdContato($arrIdContato,InfraDTO::$OPER_IN);

      $objParticipanteRN 	= new ParticipanteRN();
      $arrObjParticipanteDTO = $objParticipanteRN->listarRN0189($objParticipanteDTO);

      foreach ($arrObjParticipanteDTO as $objParticipanteDTO) {

        $dto = new ParticipanteDTO();
        $dto->setNumMaxRegistrosRetorno(1);
        $dto->retNumIdParticipante();
        $dto->setNumIdContato($numIdContato);
        $dto->setStrStaParticipacao($objParticipanteDTO->getStrStaParticipacao());
        $dto->setDblIdProtocolo($objParticipanteDTO->getDblIdProtocolo());

        if ($objParticipanteRN->consultarRN1008($dto)==null){
          $dto = new ParticipanteDTO();
          $dto->setNumIdContato($numIdContato);
          $dto->setNumIdParticipante($objParticipanteDTO->getNumIdParticipante());
          $objParticipanteRN->alterarRN0889($dto);
        }else{
          $objParticipanteRN->excluirRN0223(array($objParticipanteDTO));
        }
      }

      $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
      $objRelGrupoContatoDTO->retNumIdGrupoContato();
      $objRelGrupoContatoDTO->retNumIdContato();
      $objRelGrupoContatoDTO->setNumIdContato($arrIdContato,InfraDTO::$OPER_IN);

      $objRelGrupoContatoRN 	= new RelGrupoContatoRN();
      $arrObjRelGrupoContatoDTO = $objRelGrupoContatoRN->listarRN0463($objRelGrupoContatoDTO);
      $arrIdGrupoContato = array_unique(InfraArray::converterArrInfraDTO($arrObjRelGrupoContatoDTO,'IdGrupoContato'));

      $objRelGrupoContatoRN->excluirRN0464($arrObjRelGrupoContatoDTO);

      foreach($arrIdGrupoContato as $numIdGrupoContato){

        $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
        $objRelGrupoContatoDTO->setNumMaxRegistrosRetorno(1);
        $objRelGrupoContatoDTO->retNumIdGrupoContato();
        $objRelGrupoContatoDTO->setNumIdGrupoContato($numIdGrupoContato);
        $objRelGrupoContatoDTO->setNumIdContato($numIdContato);

        if ($objRelGrupoContatoRN->consultarRN0482($objRelGrupoContatoDTO)==null){
          $objRelGrupoContatoRN->cadastrarRN0462($objRelGrupoContatoDTO);
        }
      }

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retNumIdTipoContato();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrNome();
      $objContatoDTO->setNumIdContato(array_merge(array($numIdContato), $arrIdContato), InfraDTO::$OPER_IN);

      $arrObjContatoDTO = InfraArray::indexarArrInfraDTO($this->listarRN0325($objContatoDTO),'IdContato');

      $objContatoAPI = new ContatoAPI();
      $objContatoAPI->setIdContato($numIdContato);
      $objContatoAPI->setIdTipoContato($arrObjContatoDTO[$numIdContato]->getNumIdTipoContato());
      $objContatoAPI->setSigla($arrObjContatoDTO[$numIdContato]->getStrSigla());
      $objContatoAPI->setNome($arrObjContatoDTO[$numIdContato]->getStrNome());

      $arrObjContatoAPI = array();
      foreach($arrIdContato as $numIdContatoSubstituicao){
        $objContatoAPISubstituicao = new ContatoAPI();
        $objContatoAPISubstituicao->setIdContato($numIdContatoSubstituicao);
        $objContatoAPISubstituicao->setIdTipoContato($arrObjContatoDTO[$numIdContatoSubstituicao]->getNumIdTipoContato());
        $objContatoAPISubstituicao->setSigla($arrObjContatoDTO[$numIdContatoSubstituicao]->getStrSigla());
        $objContatoAPISubstituicao->setNome($arrObjContatoDTO[$numIdContatoSubstituicao]->getStrNome());
        $arrObjContatoAPI[] = $objContatoAPISubstituicao;
      }

      foreach ($SEI_MODULOS as $seiModulo) {
        $seiModulo->executar('substituirContato', $objContatoAPI, $arrObjContatoAPI);
      }

      foreach ($arrIdContato as $numIdContatoAtual) {

        $objContatoDTO 	= new ContatoDTO();
        $objContatoDTO->setNumIdContato($numIdContatoAtual);

        try{
          $this->excluirRN0326(array($objContatoDTO));
        }catch(Exception $e2){
          $this->desativarRN0451(array($objContatoDTO));
        }
      }

      return array_unique(InfraArray::converterArrInfraDTO($arrObjParticipanteDTO,'IdProtocolo'));

    }catch(Exception $e){
      throw new InfraException('Erro substituindo internamente contexto temporário.',$e);
    }
  }

  protected function removerDadosPrivadosConectado($arrObjContatoDTO){
    try {

      $bolAcessoContato = true;
      $bolAcessoContatoAssociado = true;

      if (count($arrObjContatoDTO)) {

        $arrIdTipoContato = array();
        foreach($arrObjContatoDTO as $objContatoDTO){
          $arrIdTipoContato[$objContatoDTO->getNumIdTipoContato()] = 0;
          if ($objContatoDTO->getNumIdTipoContatoAssociado()!=null){
            $arrIdTipoContato[$objContatoDTO->getNumIdTipoContatoAssociado()] = 0;
          }
        }

        $objPesquisaTipoContatoDTO = new PesquisaTipoContatoDTO();
        $objPesquisaTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_COMPLETA);
        $objPesquisaTipoContatoDTO->setArrIdTipoContato(array_keys($arrIdTipoContato));

        $objTipoContatoRN = new TipoContatoRN();
        $arrNumIdTipoContato = $objTipoContatoRN->pesquisarAcessoUnidade($objPesquisaTipoContatoDTO);

        foreach($arrObjContatoDTO as $objContatoDTO) {
          if (!in_array($objContatoDTO->getNumIdTipoContato(),$arrNumIdTipoContato)) {

            $objContatoDTO->setStrEndereco(null);
            $objContatoDTO->setStrComplemento(null);
            $objContatoDTO->setStrBairro(null);
            $objContatoDTO->setNumIdUf(null);
            $objContatoDTO->setStrSiglaUf(null);
            $objContatoDTO->setNumIdCidade(null);
            $objContatoDTO->setStrNomeCidade(null);
            $objContatoDTO->setNumIdPais(null);
            $objContatoDTO->setStrNomePais(null);
            $objContatoDTO->setStrCep(null);
            $objContatoDTO->setDblCpf(null);
            $objContatoDTO->setDblRg(null);
            $objContatoDTO->setStrOrgaoExpedidor(null);
            $objContatoDTO->setDtaNascimento(null);
            $objContatoDTO->setStrObservacao(null);
            $objContatoDTO->setStrNumeroPassaporte(null);
            $objContatoDTO->setNumIdPaisPassaporte(null);
            $objContatoDTO->setStrTelefoneCelular(null);
            $objContatoDTO->setStrTelefoneResidencial(null);
            $objContatoDTO->setStrConjuge(null);

            $bolAcessoContato = false;
          }

          if (!in_array($objContatoDTO->getNumIdTipoContatoAssociado(),$arrNumIdTipoContato)) {
            $objContatoDTO->setStrEnderecoContatoAssociado(null);
            $objContatoDTO->setStrComplementoContatoAssociado(null);
            $objContatoDTO->setStrBairroContatoAssociado(null);
            $objContatoDTO->setNumIdUfContatoAssociado(null);
            $objContatoDTO->setStrSiglaUfContatoAssociado(null);
            $objContatoDTO->setNumIdCidadeContatoAssociado(null);
            $objContatoDTO->setStrNomeCidadeContatoAssociado(null);
            $objContatoDTO->setNumIdPaisContatoAssociado(null);
            $objContatoDTO->setStrNomePaisContatoAssociado(null);
            $objContatoDTO->setStrCepContatoAssociado(null);
            $objContatoDTO->setStrTelefoneCelularContatoAssociado(null);
            $objContatoDTO->setStrTelefoneResidencialContatoAssociado(null);

            $bolAcessoContatoAssociado = false;
          }
        }
      }

      if (!$bolAcessoContato && $bolAcessoContatoAssociado){
        $ret = self::$TAC_SOMENTE_ASSOCIADO;
      }else if ($bolAcessoContato && !$bolAcessoContatoAssociado){
        $ret = self::$TAC_SOMENTE_CONTATO;
      }else if ($bolAcessoContato && $bolAcessoContatoAssociado){
        $ret = self::$TAC_AMBOS;
      }else{
        $ret = self::$TAC_NENHUM;
      }

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro removendo dados privados.', $e);
    }
  }

  protected function listarComEnderecoConectado(ContatoDTO $parObjContatoDTO){
    try{

      $objContatoDTO = clone($parObjContatoDTO);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retNumIdContatoAssociado();
      $objContatoDTO->retStrSinEnderecoAssociado();
      $objContatoDTO->retStrSinEnderecoAssociadoAssociado();
      $objContatoDTO->retNumIdTipoContato();
      $objContatoDTO->retNumIdTipoContatoAssociado();
      $objContatoDTO->retStrEndereco();
      $objContatoDTO->retStrComplemento();
      $objContatoDTO->retStrBairro();
      $objContatoDTO->retNumIdCidade();
      $objContatoDTO->retStrNomeCidade();
      $objContatoDTO->retDblLatitudeCidade();
      $objContatoDTO->retDblLongitudeCidade();
      $objContatoDTO->retNumIdUf();
      $objContatoDTO->retStrSiglaUf();
      $objContatoDTO->retStrNomeUf();
      $objContatoDTO->retNumIdPais();
      $objContatoDTO->retStrNomePais();
      $objContatoDTO->retStrCep();
      $objContatoDTO->retStrEnderecoContatoAssociado();
      $objContatoDTO->retStrComplementoContatoAssociado();
      $objContatoDTO->retStrBairroContatoAssociado();
      $objContatoDTO->retNumIdCidadeContatoAssociado();
      $objContatoDTO->retStrNomeCidadeContatoAssociado();
      $objContatoDTO->retDblLatitudeCidadeContatoAssociado();
      $objContatoDTO->retDblLongitudeCidadeContatoAssociado();
      $objContatoDTO->retNumIdUfContatoAssociado();
      $objContatoDTO->retStrSiglaUfContatoAssociado();
      $objContatoDTO->retStrNomeUfContatoAssociado();
      $objContatoDTO->retNumIdPaisContatoAssociado();
      $objContatoDTO->retStrNomePaisContatoAssociado();
      $objContatoDTO->retStrCepContatoAssociado();
      $objContatoDTO->retStrFuncao();
      $objContatoDTO->retStrConjuge();
      $objContatoDTO->retNumIdTitulo();
      $objContatoDTO->retStrAbreviaturaTituloContato();
      $objContatoDTO->retStrExpressaoTituloContato();
      $objContatoDTO->retNumIdCategoria();
      $objContatoDTO->retStrNomeCategoria();

      if ($objContatoDTO->isSetStrSigla()){
        if (!InfraString::isBolVazia($objContatoDTO->getStrSigla())) {
          if (strpos($objContatoDTO->getStrSigla(),'%')!==false) {
            $objContatoDTO->setStrSigla(trim($objContatoDTO->getStrSigla()), InfraDTO::$OPER_LIKE);
          }
        }else{
          $objContatoDTO->unSetStrSigla();
        }
      }

      if ($objContatoDTO->isSetStrNome()){
        if (!InfraString::isBolVazia($objContatoDTO->getStrNome())) {
          if (strpos($objContatoDTO->getStrNome(),'%')!==false) {
            $strPalavrasPesquisa = InfraString::prepararPesquisa($objContatoDTO->getStrNome());
            $arrPalavrasPesquisa = explode(' ', $strPalavrasPesquisa);
            if (count($arrPalavrasPesquisa) == 1) {
              $objContatoDTO->setStrNome($arrPalavrasPesquisa[0], InfraDTO::$OPER_LIKE);
            } else {
              $objContatoDTO->unSetStrNome();
              $a = array_fill(0, count($arrPalavrasPesquisa), 'Nome');
              $b = array_fill(0, count($arrPalavrasPesquisa), InfraDTO::$OPER_LIKE);
              $d = array_fill(0, count($arrPalavrasPesquisa) - 1, InfraDTO::$OPER_LOGICO_AND);
              $objContatoDTO->adicionarCriterio($a, $b, $arrPalavrasPesquisa, $d);
            }
          }
        }else{
          $objContatoDTO->unSetStrNome();
        }
      }

      $arrObjContatoDTO = InfraArray::indexarArrInfraDTO($this->listarRN0325($objContatoDTO),'IdContato');

      $this->removerDadosPrivados($arrObjContatoDTO);

      $arrObjContatoDTOAssociado = array();

      foreach ($arrObjContatoDTO as $objContatoDTO) {

        if ($objContatoDTO->getStrSinEnderecoAssociado() == 'S' && $objContatoDTO->getNumIdContatoAssociado() != $objContatoDTO->getNumIdContato()) {

          if ($objContatoDTO->getStrSinEnderecoAssociadoAssociado() == 'N') {

            $objContatoDTO->setStrEndereco($objContatoDTO->getStrEnderecoContatoAssociado());
            $objContatoDTO->setStrComplemento($objContatoDTO->getStrComplementoContatoAssociado());
            $objContatoDTO->setStrBairro($objContatoDTO->getStrBairroContatoAssociado());
            $objContatoDTO->setNumIdCidade($objContatoDTO->getNumIdCidadeContatoAssociado());
            $objContatoDTO->setStrNomeCidade($objContatoDTO->getStrNomeCidadeContatoAssociado());
            $objContatoDTO->setDblLatitudeCidade($objContatoDTO->getDblLatitudeCidadeContatoAssociado());
            $objContatoDTO->setDblLongitudeCidade($objContatoDTO->getDblLongitudeCidadeContatoAssociado());
            $objContatoDTO->setNumIdUf($objContatoDTO->getNumIdUfContatoAssociado());
            $objContatoDTO->setStrSiglaUf($objContatoDTO->getStrSiglaUfContatoAssociado());
            $objContatoDTO->setNumIdPais($objContatoDTO->getNumIdPaisContatoAssociado());
            $objContatoDTO->setStrNomePais($objContatoDTO->getStrNomePaisContatoAssociado());
            $objContatoDTO->setStrCep($objContatoDTO->getStrCepContatoAssociado());


          } else {

            $objContatoDTOAssociado = new ContatoDTO();
            $objContatoDTOAssociado->setNumIdContatoAssociado($objContatoDTO->getNumIdContatoAssociado());

            do {

              $dto = new ContatoDTO();
              $dto->setBolExclusaoLogica(false);
              $dto->retNumIdContatoAssociado();
              $dto->retStrSinEnderecoAssociado();
              $dto->retNumIdTipoContato();
              $dto->retNumIdTipoContatoAssociado();
              $dto->retNumIdContato();
              $dto->retStrNome();
              $dto->retStrEndereco();
              $dto->retStrComplemento();
              $dto->retStrBairro();
              $dto->retNumIdUf();
              $dto->retStrSiglaUf();
              $dto->retNumIdCidade();
              $dto->retStrNomeCidade();
              $dto->retDblLatitudeCidade();
              $dto->retDblLongitudeCidade();
              $dto->retNumIdPais();
              $dto->retStrNomePais();
              $dto->retStrCep();
              $dto->setNumIdContato($objContatoDTOAssociado->getNumIdContatoAssociado());

              $objContatoDTOAssociado = $this->consultarRN0324($dto);

            } while ($objContatoDTOAssociado != null && $objContatoDTOAssociado->getStrSinEnderecoAssociado() == 'S' && $objContatoDTOAssociado->getNumIdContatoAssociado() != $objContatoDTOAssociado->getNumIdContato());

            if ($objContatoDTOAssociado != null) {

              $objContatoDTOAssociado->setNumIdTipoContato($objContatoDTO->getNumIdTipoContato());
              $objContatoDTOAssociado->setNumIdTipoContatoAssociado($objContatoDTO->getNumIdTipoContato());

              $arrObjContatoDTOAssociado[$objContatoDTO->getNumIdContato()] = $objContatoDTOAssociado;

            }
          }
        }
      }

      if (count($arrObjContatoDTOAssociado)){

        $this->removerDadosPrivados($arrObjContatoDTOAssociado);

        foreach ($arrObjContatoDTOAssociado as $numIdContato => $objContatoDTOAssociado){

          $objContatoDTO = $arrObjContatoDTO[$numIdContato];

          $objContatoDTO->setStrEndereco($objContatoDTOAssociado->getStrEndereco());
          $objContatoDTO->setStrComplemento($objContatoDTOAssociado->getStrComplemento());
          $objContatoDTO->setStrBairro($objContatoDTOAssociado->getStrBairro());
          $objContatoDTO->setNumIdCidade($objContatoDTOAssociado->getNumIdCidade());
          $objContatoDTO->setStrNomeCidade($objContatoDTOAssociado->getStrNomeCidade());
          $objContatoDTO->setDblLatitudeCidade($objContatoDTOAssociado->getDblLatitudeCidade());
          $objContatoDTO->setDblLongitudeCidade($objContatoDTOAssociado->getDblLongitudeCidade());
          $objContatoDTO->setNumIdUf($objContatoDTOAssociado->getNumIdUf());
          $objContatoDTO->setStrSiglaUf($objContatoDTOAssociado->getStrSiglaUf());
          $objContatoDTO->setNumIdPais($objContatoDTOAssociado->getNumIdPais());
          $objContatoDTO->setStrNomePais($objContatoDTOAssociado->getStrNomePais());
          $objContatoDTO->setStrCep($objContatoDTOAssociado->getStrCep());

          $arrObjContatoDTO[$numIdContato] = $objContatoDTO;
        }
      }

      foreach($arrObjContatoDTO as $objContatoDTO){
        $objContatoDTO->unSetStrEnderecoContatoAssociado();
        $objContatoDTO->unSetStrComplementoContatoAssociado();
        $objContatoDTO->unSetStrBairroContatoAssociado();
        $objContatoDTO->unSetNumIdCidadeContatoAssociado();
        $objContatoDTO->unSetStrNomeCidadeContatoAssociado();
        $objContatoDTO->unSetDblLatitudeCidadeContatoAssociado();
        $objContatoDTO->unSetDblLongitudeCidadeContatoAssociado();
        $objContatoDTO->unSetNumIdUfContatoAssociado();
        $objContatoDTO->unSetStrSiglaUfContatoAssociado();
        $objContatoDTO->unSetNumIdPaisContatoAssociado();
        $objContatoDTO->unSetStrNomePaisContatoAssociado();
        $objContatoDTO->unSetStrCepContatoAssociado();
      }

      return array_values($arrObjContatoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro listando com endereço associado.',$e);
    }
  }


}
?>