<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MarcadorRN extends InfraRN {

  public static $TI_PRETO = '0';
  public static $TI_BRANCO = '1';
  public static $TI_CINZA = '2';
  public static $TI_VERMELHO = '3';
  public static $TI_AMARELO = '4';
  public static $TI_VERDE = '5';
  public static $TI_AZUL = '6';
  public static $TI_ROSA = '7';
  public static $TI_ROXO = '8';
  public static $TI_CIANO = '9';
  public static $TI_BEGE = '10';
  public static $TI_CHAMPAGNE = '11';
  public static $TI_CINZA_ESCURO = '2';
  public static $TI_LARANJA = '13';
  public static $TI_LILAS = '14';
  public static $TI_MARROM = '15';
  public static $TI_OURO = '16';
  public static $TI_PRATA = '17';
  public static $TI_ROSA_CLARO = '18';
  public static $TI_TIJOLO = '19';
  public static $TI_VERDE_AGUA = '20';
  public static $TI_VERDE_ESCURO = '21';
  public static $TI_VERDE_AMAZONAS = '22';
  public static $TI_AZUL_CEU = '23';
  public static $TI_BRONZE = '24';
  public static $TI_AMARELO_OURO = '25';
  public static $TI_VINHO = '26';
  public static $TI_AZUL_RIVIERA = '27';
  public static $TI_VERDE_ABACATE = '28';
  public static $TI_AMARELO_CLARO = '29';
  public static $TI_VERDE_TURQUESA = '30';
  public static $TI_AZUL_MARINHO = '31';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresIcone(){
    try {

      $arr = array();

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_PRETO);
      $objIconeMarcadorDTO->setStrDescricao('Preto');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_PRETO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_BRANCO);
      $objIconeMarcadorDTO->setStrDescricao('Branco');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_BRANCO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_CINZA);
      $objIconeMarcadorDTO->setStrDescricao('Cinza');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_CINZA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERMELHO);
      $objIconeMarcadorDTO->setStrDescricao('Vermelho');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERMELHO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AMARELO);
      $objIconeMarcadorDTO->setStrDescricao('Amarelo');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AMARELO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERDE);
      $objIconeMarcadorDTO->setStrDescricao('Verde');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERDE);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AZUL);
      $objIconeMarcadorDTO->setStrDescricao('Azul');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AZUL);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_ROSA);
      $objIconeMarcadorDTO->setStrDescricao('Rosa');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_ROSA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_ROXO);
      $objIconeMarcadorDTO->setStrDescricao('Roxo');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_ROXO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_CIANO);
      $objIconeMarcadorDTO->setStrDescricao('Ciano');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_CIANO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_BEGE);
      $objIconeMarcadorDTO->setStrDescricao('Bege');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_BEGE);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_CHAMPAGNE);
      $objIconeMarcadorDTO->setStrDescricao('Champagne');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_CHAMPAGNE);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_CINZA_ESCURO);
      $objIconeMarcadorDTO->setStrDescricao('Cinza Escuro');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_CINZA_ESCURO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_LARANJA);
      $objIconeMarcadorDTO->setStrDescricao('Laranja');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_LARANJA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_LILAS);
      $objIconeMarcadorDTO->setStrDescricao('Lilás');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_LILAS);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_MARROM);
      $objIconeMarcadorDTO->setStrDescricao('Marrom');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_MARROM);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_OURO);
      $objIconeMarcadorDTO->setStrDescricao('Ouro');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_OURO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_PRATA);
      $objIconeMarcadorDTO->setStrDescricao('Prata');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_PRATA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_ROSA_CLARO);
      $objIconeMarcadorDTO->setStrDescricao('Rosa Claro');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_ROSA_CLARO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_TIJOLO);
      $objIconeMarcadorDTO->setStrDescricao('Tijolo');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_TIJOLO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERDE_AGUA);
      $objIconeMarcadorDTO->setStrDescricao('Verde Água');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERDE_AGUA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERDE_ESCURO);
      $objIconeMarcadorDTO->setStrDescricao('Verde Escuro');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERDE_ESCURO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERDE_AMAZONAS);
      $objIconeMarcadorDTO->setStrDescricao('Verde Amazonas');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERDE_AMAZONAS);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AZUL_CEU);
      $objIconeMarcadorDTO->setStrDescricao('Azul Céu');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AZUL_CEU);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_BRONZE);
      $objIconeMarcadorDTO->setStrDescricao('Bronze');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_BRONZE);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AMARELO_OURO);
      $objIconeMarcadorDTO->setStrDescricao('Amarelo Ouro');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AMARELO_OURO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VINHO);
      $objIconeMarcadorDTO->setStrDescricao('Vinho');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VINHO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AZUL_RIVIERA);
      $objIconeMarcadorDTO->setStrDescricao('Azul Riviera');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AZUL_RIVIERA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERDE_ABACATE);
      $objIconeMarcadorDTO->setStrDescricao('Verde Abacate');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERDE_ABACATE);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AMARELO_CLARO);
      $objIconeMarcadorDTO->setStrDescricao('Amarelo Claro');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AMARELO_CLARO);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_VERDE_TURQUESA);
      $objIconeMarcadorDTO->setStrDescricao('Verde Turquesa');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_VERDE_TURQUESA);
      $arr[] = $objIconeMarcadorDTO;

      $objIconeMarcadorDTO = new IconeMarcadorDTO();
      $objIconeMarcadorDTO->setStrStaIcone(self::$TI_AZUL_MARINHO);
      $objIconeMarcadorDTO->setStrDescricao('Azul Marinho');
      $objIconeMarcadorDTO->setStrArquivo(Icone::MARCADOR_AZUL_MARINHO);
      $arr[] = $objIconeMarcadorDTO;

      return $arr;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Ícone.',$e);
    }
  }

  private function validarNumIdUnidade(MarcadorDTO $objMarcadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMarcadorDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrNome(MarcadorDTO $objMarcadorDTO, InfraException $objInfraException){

    if (InfraString::isBolVazia($objMarcadorDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objMarcadorDTO->setStrNome(trim($objMarcadorDTO->getStrNome()));

      if (strlen($objMarcadorDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }

      if ($objMarcadorDTO->getStrNome()=='[REMOVIDO]'){
        $objInfraException->adicionarValidacao('Nome informado reservado do sistema.');
        return;
      }

      $dto = new MarcadorDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();

      $dto->setNumIdMarcador($objMarcadorDTO->getNumIdMarcador(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUnidade($objMarcadorDTO->getNumIdUnidade());
      $dto->setStrNome($objMarcadorDTO->getStrNome());

      $dto = $this->consultar($dto);

      if ($dto!=null) {
        if ($dto->getStrSinAtivo()=='S') {
          $objInfraException->adicionarValidacao('Existe outro Marcador com este Nome.');
        } else {
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Marcador com este Nome.');
        }
      }
    }    
  }

  public function getNumMaxTamanhoNome(){
    return 50;
  }

  private function validarStrDescricao(MarcadorDTO $objMarcadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMarcadorDTO->getStrDescricao())){
      $objMarcadorDTO->setStrDescricao(null);
    }else{
      $objMarcadorDTO->setStrDescricao(trim($objMarcadorDTO->getStrDescricao()));

      if (strlen($objMarcadorDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrStaIcone(MarcadorDTO $objMarcadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMarcadorDTO->getStrStaIcone())){
      $objInfraException->adicionarValidacao('Ícone não informado.');
    }else{
      if (!in_array($objMarcadorDTO->getStrStaIcone(),InfraArray::converterArrInfraDTO($this->listarValoresIcone(),'StaIcone'))){
        $objInfraException->adicionarValidacao('Ícone inválido.');
      }
    }
  }

  private function validarStrSinAtivo(MarcadorDTO $objMarcadorDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMarcadorDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objMarcadorDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(MarcadorDTO $objMarcadorDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_cadastrar',__METHOD__,$objMarcadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objMarcadorDTO, $objInfraException);
      $this->validarStrNome($objMarcadorDTO, $objInfraException);
      $this->validarStrDescricao($objMarcadorDTO, $objInfraException);
      $this->validarStrStaIcone($objMarcadorDTO, $objInfraException);
      $this->validarStrSinAtivo($objMarcadorDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      $ret = $objMarcadorBD->cadastrar($objMarcadorDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Marcador.',$e);
    }
  }

  protected function alterarControlado(MarcadorDTO $objMarcadorDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('marcador_alterar',__METHOD__,$objMarcadorDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMarcadorDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objMarcadorDTO, $objInfraException);
      }
      if ($objMarcadorDTO->isSetStrNome()){
        $this->validarStrNome($objMarcadorDTO, $objInfraException);
      }
      if ($objMarcadorDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objMarcadorDTO, $objInfraException);
      }
      if ($objMarcadorDTO->isSetStrStaIcone()){
        $this->validarStrStaIcone($objMarcadorDTO, $objInfraException);
      }
      if ($objMarcadorDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objMarcadorDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      $objMarcadorBD->alterar($objMarcadorDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Marcador.',$e);
    }
  }

  protected function excluirControlado($arrObjMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_excluir',__METHOD__,$arrObjMarcadorDTO);

      if (count($arrObjMarcadorDTO)) {
        //Regras de Negocio
        $objInfraException = new InfraException();

        $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

        foreach ($arrObjMarcadorDTO as $objMarcadorDTO) {
          $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
          $objAndamentoMarcadorDTO->setBolExclusaoLogica(false);
          $objAndamentoMarcadorDTO->retStrNomeMarcador();
          $objAndamentoMarcadorDTO->setNumIdMarcador($objMarcadorDTO->getNumIdMarcador());
          $objAndamentoMarcadorDTO->setNumMaxRegistrosRetorno(1);
          $objAndamentoMarcadorDTO = $objAndamentoMarcadorRN->consultar($objAndamentoMarcadorDTO);

          if ($objAndamentoMarcadorDTO != null) {
            $objInfraException->adicionarValidacao('Marcador "'.$objAndamentoMarcadorDTO->getStrNomeMarcador().'" já foi utilizado.');
          }
        }

        $objInfraException->lancarValidacoes();

        $objRelUsuarioMarcadorDTO = new RelUsuarioMarcadorDTO();
        $objRelUsuarioMarcadorDTO->retNumIdMarcador();
        $objRelUsuarioMarcadorDTO->retNumIdUsuario();
        $objRelUsuarioMarcadorDTO->setNumIdMarcador(InfraArray::converterArrInfraDTO($arrObjMarcadorDTO,'IdMarcador'), InfraDTO::$OPER_IN);

        $objRelUsuarioMarcadorRN = new RelUsuarioMarcadorRN();
        $objRelUsuarioMarcadorRN->excluir($objRelUsuarioMarcadorRN->listar($objRelUsuarioMarcadorDTO));

        $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
        for ($i = 0; $i < count($arrObjMarcadorDTO); $i++) {
          $objMarcadorBD->excluir($arrObjMarcadorDTO[$i]);
        }
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Marcador.',$e);
    }
  }

  protected function consultarConectado(MarcadorDTO $objMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_consultar',__METHOD__,$objMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      $ret = $objMarcadorBD->consultar($objMarcadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Marcador.',$e);
    }
  }

  protected function listarConectado(MarcadorDTO $objMarcadorDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_listar',__METHOD__,$objMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objMarcadorDTO->isRetNumProcessos()){
        $objMarcadorDTO->retNumIdMarcador();
      }

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      $ret = $objMarcadorBD->listar($objMarcadorDTO);

      if (count($ret)){

        if ($objMarcadorDTO->isRetNumProcessos()) {
          $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

          $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
          $objAndamentoMarcadorDTO->setStrSinUltimo('S');

          foreach ($ret as $dto) {
            $objAndamentoMarcadorDTO->setNumIdMarcador($dto->getNumIdMarcador());
            $dto->setNumProcessos($objAndamentoMarcadorRN->contar($objAndamentoMarcadorDTO));
          }
        }
      }

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Marcadores.',$e);
    }
  }

  protected function contarConectado(MarcadorDTO $objMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_listar',__METHOD__,$objMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      $ret = $objMarcadorBD->contar($objMarcadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Marcadores.',$e);
    }
  }

  protected function desativarControlado($arrObjMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_desativar',__METHOD__,$arrObjMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMarcadorDTO);$i++){
        $objMarcadorBD->desativar($arrObjMarcadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Marcador.',$e);
    }
  }

  protected function reativarControlado($arrObjMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_reativar',__METHOD__,$arrObjMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMarcadorDTO);$i++){
        $objMarcadorBD->reativar($arrObjMarcadorDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Marcador.',$e);
    }
  }

  protected function bloquearControlado(MarcadorDTO $objMarcadorDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('marcador_consultar',__METHOD__,$objMarcadorDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMarcadorBD = new MarcadorBD($this->getObjInfraIBanco());
      $ret = $objMarcadorBD->bloquear($objMarcadorDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Marcador.',$e);
    }
  }
}
?>