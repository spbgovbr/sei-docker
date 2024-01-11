<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdOrgao(CpadDTO $objCpadDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadDTO->getNumIdOrgao())){
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }else{
      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      // testa se tem cpad para esse orgao, pois pode apenas 1 cpad por orgao
      $objCpadDTO_Consulta = new CpadDTO();
      $objCpadDTO_Consulta->setNumIdOrgao($objCpadDTO->getNumIdOrgao());
      //teste para quando for alteracao
      if($objCpadDTO->isSetNumIdCpad()){
        $objCpadDTO_Consulta->setNumIdCpad($objCpadDTO->getNumIdCpad(),InfraDTO::$OPER_DIFERENTE);
      }
      if($objCpadBD->contar($objCpadDTO_Consulta) > 0){
        $objInfraException->adicionarValidacao('Já existe uma comissão para o Órgão informado.');
      }
    }
  }

  private function validarStrSigla(CpadDTO $objCpadDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objCpadDTO->setStrSigla(trim($objCpadDTO->getStrSigla()));

      if (strlen($objCpadDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }else{
        $objCpadBD = new CpadBD($this->getObjInfraIBanco());
        // testa se tem cpad com essa sigla, independente do orgao
        $objCpadDTO_Consulta = new CpadDTO();
        $objCpadDTO_Consulta->setStrSigla($objCpadDTO->getStrSigla());
        //teste para quando for alteracao
        if($objCpadDTO->isSetNumIdCpad()){
          $objCpadDTO_Consulta->setNumIdCpad($objCpadDTO->getNumIdCpad(),InfraDTO::$OPER_DIFERENTE);
        }
        if($objCpadBD->contar($objCpadDTO_Consulta) > 0){
          $objInfraException->adicionarValidacao('Já existe uma comissão com a Sigla informada.');
        }
      }
    }
  }

  private function validarStrDescricao(CpadDTO $objCpadDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objCpadDTO->setStrDescricao(trim($objCpadDTO->getStrDescricao()));

      if (strlen($objCpadDTO->getStrDescricao())>100){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(CpadDTO $objCpadDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objCpadDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objCpadDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  //////////////////////////////////////////////////////////////////////////////
  ///nao pode haver mais de 1 cpad por orgao
  /// nao pode haver 2 ou mais cpad com a mesma sigla
  /// quando cadastra um cpad, cadastra tambem uma versao e a composicao conforme os usuarios informados na tela
  //////////////////////////////////////////////////////////////////////////////
  protected function cadastrarControlado(CpadDTO $objCpadDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_cadastrar',__METHOD__,$objCpadDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objCpadDTO, $objInfraException);
      $this->validarStrSigla($objCpadDTO, $objInfraException);
      $this->validarStrDescricao($objCpadDTO, $objInfraException);
      $this->validarStrSinAtivo($objCpadDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      $objCpadDTO = $objCpadBD->cadastrar($objCpadDTO);

      //após cadastrar a cpad, é cadastrada a versao
      $objCpadVersaoDTO = $objCpadDTO->getObjCpadVersaoAtual();
      //id do cpad cadastrado
      $objCpadVersaoDTO->setNumIdCpad($objCpadDTO->getNumIdCpad());
      //sigla e descricao iguais ao da cpad
      $objCpadVersaoDTO->setStrSigla($objCpadDTO->getStrSigla());
      $objCpadVersaoDTO->setStrDescricao($objCpadDTO->getStrDescricao());
      //hora atual
      $objCpadVersaoDTO->setDthVersao(InfraData::getStrDataHoraAtual());
      //editavel, pois pode ser alterada
      // nao é mais editavel após ter havido pelo menos 1 avaliacao cpad
      $objCpadVersaoDTO->setStrSinEditavel("S");
      //versao ativa
      $objCpadVersaoDTO->setStrSinAtivo("S");
      //usuario atual
      $objCpadVersaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      //unidade atual do usuario
      $objCpadVersaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objCpadVersaoRN = new CpadVersaoRN();
      $objCpadVersaoRN->cadastrar($objCpadVersaoDTO);

      //cadastra os usuarios informados na tela na versao recem cadastrada
      $objCpadComposicaoRN = new CpadComposicaoRN();
      foreach ($objCpadVersaoDTO->getArrObjCpadComposicao() as $objCpadComposicaoDTO){
        //id da versao cadastrada agora
        $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO->getNumIdCpadVersao());
        $objCpadComposicaoRN->cadastrar($objCpadComposicaoDTO);
      }

      return $objCpadDTO;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  //////////////////////////////////////////////////////////////////////////////
  ///-nao pode haver mais de 1 cpad por orgao
  // -nao pode haver 2 ou mais cpad com a mesma sigla
  // -quando altera um cpad, há as seguintes situacoes que podem ocorrer com a versao e composicao:
  //    1. a sigla, descricao, presidente e composicao sao mantidas, entao nada é feito
  //    2. caso uma dessas 4 informacoes seja alterada:
  //      2.1. caso o usuario que realizou a alteracao seja diferente do usuario que gerou a ultima versão
  //        2.1.1. se a ultima versao for editavel, altera para nao editavel
  //        2.1.2. cadastra uma nova versao com a composicao da tela;
  //      2.2. caso o usuario que realizou a alteracao seja o mesmo que gerou a ultima versão
  //        2.2.1. caso a ultima versao nao seja editavel:
  //          2.2.1.1. cadastra uma nova versao com a composicao da tela;
  //        2.2.2. caso a ultima versao seja editavel:
  //          2.2.2.1. altera a composicao da versao conforme a composicao da tela;
  //          2.2.2.2. altera os dados da versao (sigla, descricao, data) conforme a tela;
  // obs.: quando um usuario da composicao faz uma avaliacao cpad (de uma avaliacao documental), a ultima versao deixa de ser editavel para nao editavel
  // obs.: as avaliacoes cpad (de avaliacoes documentais) da ultima versao sao sempre ativas, enquanto avaliacoes cpad de versoes anteriores sao sempre nao ativas
  // obs.: sempre que é gerada uma nova, em relação à composicao:
  //    1. as avaliacoes cpad (de avaliacoes documentais) realizadas pelos componentes cpad devem ser migradas da versao antiga para a versao nova
  //    2. caso todas as avaliacoes cpad (de avaliacoes documentais) dos todos os usuarios da nova composicao sejam 'concordo', a avaliacao documental de mudar a situacao para 'comissao'

  //////////////////////////////////////////////////////////////////////////////
  protected function alterarControlado(CpadDTO $objCpadDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_alterar',__METHOD__,$objCpadDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objCpadDTO->isSetNumIdOrgao()){
        $this->validarNumIdOrgao($objCpadDTO, $objInfraException);
      }
      if ($objCpadDTO->isSetStrSigla()){
        $this->validarStrSigla($objCpadDTO, $objInfraException);
      }
      if ($objCpadDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objCpadDTO, $objInfraException);
      }
      if ($objCpadDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objCpadDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objCpadVersaoRN = new CpadVersaoRN();
      $objCpadComposicaoRN = new CpadComposicaoRN();

      //busca a ultima versao (ativa)
      $objCpadVersaoDTO_Ultima = new CpadVersaoDTO();
      $objCpadVersaoDTO_Ultima->retNumIdCpadVersao();
      $objCpadVersaoDTO_Ultima->retNumIdUsuario();
      $objCpadVersaoDTO_Ultima->retNumIdUnidade();
      $objCpadVersaoDTO_Ultima->retStrSigla();
      $objCpadVersaoDTO_Ultima->retStrDescricao();
      $objCpadVersaoDTO_Ultima->retStrSinEditavel();
      $objCpadVersaoDTO_Ultima->setNumIdCpad($objCpadDTO->getNumIdCpad());
      $objCpadVersaoDTO_Ultima = $objCpadVersaoRN->consultar($objCpadVersaoDTO_Ultima);

      //busca a composicao da ultima versao
      $objCpadComposicaoDTO_Ultima = new CpadComposicaoDTO();
      $objCpadComposicaoDTO_Ultima->retNumIdUsuario();
      $objCpadComposicaoDTO_Ultima->retStrSinPresidente();
      $objCpadComposicaoDTO_Ultima->retNumOrdem();
      $objCpadComposicaoDTO_Ultima->retNumIdCpadComposicao();
      $objCpadComposicaoDTO_Ultima->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
      $objCpadComposicaoDTO_Ultima->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
      $arrObjCpadComposicaoDTO_Ultima = $objCpadComposicaoRN->listar($objCpadComposicaoDTO_Ultima);

      //indexa pelo id usuario, para depois migrar as avaliacoes cpad da composicao antiga para a nova, caso necessario
      $arrObjCpadComposicao_UltimaIndexadoUsuario = InfraArray::indexarArrInfraDTO($arrObjCpadComposicaoDTO_Ultima, "IdUsuario");
      //composicao atual
      $arrIdUsuarioCpadComposicao_Ultima = InfraArray::converterArrInfraDTO($arrObjCpadComposicaoDTO_Ultima, "IdUsuario");
      //composicao da tela
      $arrIdUsuarioCpadComposicao_Tela = InfraArray::converterArrInfraDTO($objCpadDTO->getObjCpadVersaoAtual()->getArrObjCpadComposicao(), "IdUsuario");
      //gera array com os ids dos usuarios ordenados
      $arrIdUsuario_Ultima = InfraArray::converterArrInfraDTO($arrObjCpadComposicaoDTO_Ultima, "IdUsuario");
      //gera array com os ids dos usuarios ordenados
      $arrIdUsuario_Tela = InfraArray::converterArrInfraDTO($objCpadDTO->getObjCpadVersaoAtual()->getArrObjCpadComposicao(), "IdUsuario");
      // testa se os arrays sao iguais e seta um bool indicando se as composicoes sao iguais ou se mudou
      //serão diferentes se tiverem ids de usuarios diferentes (ou seja, usuarios foram adicionados/excluidos)
      //obs.: "==" testa se os arrays são iguais, comparando as chaves/valoes deles, independente da ordem
      $bolComposicaoIgual = $arrIdUsuario_Ultima == $arrIdUsuario_Tela;
      //bol que indica se a ordem está da composicao está igual
      $bolOrdemIgual = $bolComposicaoIgual;
      //se a composicao é igual
      if($bolComposicaoIgual){
        //testa se a ordem é igual
        //obs.: "===" testa se os arrays são iguais, comparando as chaves/valoes deles, considerando a ordem tambem
        $bolOrdemIgual = $arrIdUsuario_Ultima === $arrIdUsuario_Tela;
      }
      //variavel usada caso o presidente seja alterado
      $numIdUsuario_NovoPresidente = null;
      //busca o presidente no banco
      $numIdCpadComposicao_PresidenteUltima = $this->buscarPresidenteComposicao($arrObjCpadComposicaoDTO_Ultima);
      //busca o presidente selecionado na tela
      $numIdCpadComposicao_PresidenteTela = $this->buscarPresidenteComposicao($objCpadDTO->getObjCpadVersaoAtual()->getArrObjCpadComposicao());
      //compara
      if ($numIdCpadComposicao_PresidenteUltima != $numIdCpadComposicao_PresidenteTela){
        $numIdUsuario_NovoPresidente = $numIdCpadComposicao_PresidenteTela;
      }
      //testa se mudou a sigla, descricao ou a composicao da cpad, comparando com a ultima versao
      if($objCpadVersaoDTO_Ultima->getStrSigla() != $objCpadDTO->getStrSigla() || $objCpadVersaoDTO_Ultima->getStrDescricao() != $objCpadDTO->getStrDescricao() || !$bolComposicaoIgual || !$bolOrdemIgual || $numIdUsuario_NovoPresidente != null) {
        //testa se o usuario que está fazendo alteracao é diferente do usuario que gerou a ultima versao
        if ($objCpadVersaoDTO_Ultima->getNumIdUsuario() != SessaoSEI::getInstance()->getNumIdUsuario() ) {
          // testa se a ultima versao é editavel
          if($objCpadVersaoDTO_Ultima->getStrSinEditavel() == "S"){
            //se for, deve ser alterada para nao editavel, pois será cadastrada uma nova versao
            $objCpadVersaoDTO_Ultima->setStrSinEditavel("N");
            $objCpadVersaoDTO_Ultima = $objCpadVersaoRN->alterar($objCpadVersaoDTO_Ultima);
          }
          //cadastra a nova versao
          $objCpadVersaoDTO_Ultima = $this->cadastrarCpadVersaoNova($objCpadDTO, $objCpadVersaoRN);
          //cadastra a composicao nova conforme os usuarios informados na tela
          $this->cadastrarCpadComposicaoNova($objCpadDTO,  $objCpadVersaoDTO_Ultima, $arrObjCpadComposicao_UltimaIndexadoUsuario);
        //o usuario que está fazendo a alteracao é o mesmo que gerou a ultima versao
        }else{
          // testa se a ultima versao é nao editavel
          if($objCpadVersaoDTO_Ultima->getStrSinEditavel() == "N"){
            //se nao for, apenas cadastra a nova versao
            $objCpadVersaoDTO_Ultima = $this->cadastrarCpadVersaoNova($objCpadDTO, $objCpadVersaoRN);
            //cadastra a composicao nova conforme os usuarios informados na tela
            $this->cadastrarCpadComposicaoNova($objCpadDTO,  $objCpadVersaoDTO_Ultima, $arrObjCpadComposicao_UltimaIndexadoUsuario);
          }else {
            //atualiza a composicao, caso o usuario tenha adicionado/removido componentes
            $this->atualizarCpadComposicao($arrIdUsuarioCpadComposicao_Ultima, $arrIdUsuarioCpadComposicao_Tela, $objCpadDTO,  $objCpadVersaoDTO_Ultima, $objCpadComposicaoRN, $numIdUsuario_NovoPresidente, $bolOrdemIgual);
            //senao, apenas altera os dados da versao (sigla, descricao, data)
            $objCpadVersaoDTO_Ultima = $this->alterarDadosCpadVersaoAtual( $objCpadDTO,  $objCpadVersaoDTO_Ultima,  $objCpadVersaoRN);
          }
        }
      }
      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      //altera o cpad
      $objCpadBD->alterar($objCpadDTO);
    }catch(Exception $e){
      throw new InfraException('Erro alterando Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  private function buscarPresidenteComposicao(array $arrObjCpadComposicaoDTO){
    if(InfraArray::contar($arrObjCpadComposicaoDTO) > 0){
      foreach ($arrObjCpadComposicaoDTO as $objCpadComposicaoDTO){
        if($objCpadComposicaoDTO->getStrSinPresidente() == "S"){
          return $objCpadComposicaoDTO->getNumIdUsuario();
        }
      }
    }
  }

  //altera os dados sigla, descricao e data da ultima versao
  private function alterarDadosCpadVersaoAtual(CpadDTO $objCpadDTO, CpadVersaoDTO $objCpadVersaoDTO_Ultima, CpadVersaoRN $objCpadVersaoRN){
    $objCpadVersaoDTO_Ultima->setStrSigla($objCpadDTO->getStrSigla());
    $objCpadVersaoDTO_Ultima->setStrDescricao($objCpadDTO->getStrDescricao());
    $objCpadVersaoDTO_Ultima->setDthVersao(InfraData::getStrDataHoraAtual());
    $objCpadVersaoDTO_Ultima = $objCpadVersaoRN->alterar($objCpadVersaoDTO_Ultima);
    return $objCpadVersaoDTO_Ultima;
  }

  //cadastra uma nova versao
  private function cadastrarCpadVersaoNova(CpadDTO $objCpadDTO, CpadVersaoRN $objCpadVersaoRN){
    $objCpadVersaoDTO_Novo = new CpadVersaoDTO();
    $objCpadVersaoDTO_Novo->setNumIdCpad($objCpadDTO->getNumIdCpad());
    $objCpadVersaoDTO_Novo->setStrSigla($objCpadDTO->getStrSigla());
    $objCpadVersaoDTO_Novo->setStrDescricao($objCpadDTO->getStrDescricao());
    $objCpadVersaoDTO_Novo->setDthVersao(InfraData::getStrDataHoraAtual());
    $objCpadVersaoDTO_Novo->setStrSinEditavel("S");
    $objCpadVersaoDTO_Novo->setStrSinAtivo("S");
    $objCpadVersaoDTO_Novo->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
    $objCpadVersaoDTO_Novo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    $objCpadVersaoDTO_Ultima = $objCpadVersaoRN->cadastrar($objCpadVersaoDTO_Novo);
    return $objCpadVersaoDTO_Ultima;
  }

  //cadastra uma nova composicao
  // parametros:
  // $objCpadDTO o cpad que está sendo alterado
  // $objCpadVersaoDTO_Ultima a ultima versao gerada
  // $arrObjCpadComposicao_UltimaIndexado: array da composicao da ultima versao de antes da alteracao, indexado pelo id usuario. Será usado para migrar as avaliacoes cpad
  // $bolVersaoNova: bool indicando se foi cadastrada uma nova versao
  private function cadastrarCpadComposicaoNova(CpadDTO $objCpadDTO, CpadVersaoDTO $objCpadVersaoDTO_Ultima,  array $arrObjCpadComposicao_UltimaIndexado){

    $objCpadComposicaoRN = new CpadComposicaoRN();
    $objCpadAvaliacaoRN = new CpadAvaliacaoRN();

    //array que conterá os ids dos novos componentes (da composicao) cadastrados
    //Será usado para migrar as avaliacoes cpad
    $arrIdComposicao = array();
    //percorre a composicao informada na tela
    foreach ($objCpadDTO->getObjCpadVersaoAtual()->getArrObjCpadComposicao() as $objCpadComposicaoDTO){
      //seta o id da ultima versao em cada componente (da composicao)
      $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
      //cadastra o componente
      $objCpadComposicaoDTO = $objCpadComposicaoRN->cadastrar($objCpadComposicaoDTO);
      //retorna o id desse componente cadastrado
      $arrIdComposicao[] = $objCpadComposicaoDTO->getNumIdCpadComposicao();

      // retorna o componente da ultima versao de antes da alteracao
      $objCpadComposicao_Antigo = $arrObjCpadComposicao_UltimaIndexado[$objCpadComposicaoDTO->getNumIdUsuario()];
      //testa se existe o componente, pois se for um novo componente inserido na tabela, nao existia antes na composicao
      if($objCpadComposicao_Antigo != null) {
        $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
        $objCpadAvaliacaoDTO->retNumIdCpadAvaliacao();
        $objCpadAvaliacaoDTO->retNumIdAvaliacaoDocumental();
        $objCpadAvaliacaoDTO->retDthAvaliacao();
        $objCpadAvaliacaoDTO->retStrStaCpadAvaliacao();
        $objCpadAvaliacaoDTO->retStrMotivo();
        $objCpadAvaliacaoDTO->retStrJustificativa();
        $objCpadAvaliacaoDTO->retStrSinAtivo();
        $objCpadAvaliacaoDTO->retNumIdCpadComposicao();
        $objCpadAvaliacaoDTO->setNumIdCpadComposicao($objCpadComposicao_Antigo->getNumIdCpadComposicao());
        $arrObjCpadAvaliacaoDTO = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO);
        if (InfraArray::contar($arrObjCpadAvaliacaoDTO) > 0) {
          foreach ($arrObjCpadAvaliacaoDTO as $objCpadAvaliacaoDTO) {
            $objCpadAvaliacaoDTO->setNumIdCpadComposicao($objCpadComposicaoDTO->getNumIdCpadComposicao());
            $objCpadAvaliacaoRN->alterar($objCpadAvaliacaoDTO);
          }
        }
      }
    }
    //caso todas as avaliacoes cpad de todos os componentes (da composicao) sejam 'acordo', a avaliacao documental terá a situacao alterada para 'comissao'
    // primeiro busca as avaliacoes cpad da composicao atual, mas com o objetivo de buscar os ids de avaliacoes documentais
    $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
    $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
    //busca todos os ids avaliacoes documentais de avaliacoes cpad realiazadas pela composicao, mas podem ser repetidas, por isso o distinct
    $objCpadAvaliacaoDTO->setDistinct(true);
    $objCpadAvaliacaoDTO->retNumIdAvaliacaoDocumental();
    //busca apenas as avaliacoes cpad realizadas pela composicao atual
    $objCpadAvaliacaoDTO->setNumIdCpadComposicao($arrIdComposicao,InfraDTO::$OPER_IN);
    //busca apenas as avaliacoes cpad ativas
    $objCpadAvaliacaoDTO->setStrSinAtivo("S");

    //busca apenas as avaliacoes cpad 'avaliado' como um filtro a mais, pois nao tem necessidade de trazer as 'negado', já que se tiver pelo menos umas 'negado', a situacao da avaliacao documental nao será alterada para 'comissao'
    $objCpadAvaliacaoDTO->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_AVALIADO);

    //somente as avaliacoes que são iniciais
    $objCpadAvaliacaoDTO->setStrStaAvaliacaoAvaliacaoDocumental(AvaliacaoDocumentalRN::$TA_AVALIADO);

    $arrObjCpadAvaliacaoDTO_AvaliacoesDocumentais = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO);
    //se retornou avaliacoes cpad com esses filtros, pois pode nao haver nenhuma
    if(InfraArray::contar($arrObjCpadAvaliacaoDTO_AvaliacoesDocumentais) > 0){
      //percorre as avaliacoes cpad, mas com o objetivo de pegar o id avaliacao documental
      foreach ($arrObjCpadAvaliacaoDTO_AvaliacoesDocumentais as $objCpadAvaliacaoDTO_AvaliacaoDocumental) {
        //cria objeto da avaliacao cpad para buscar todas as avaliacoes cpad dessa avaliacao documental
        $objCpadAvaliacaoDTO_AvaliadoCpad = new CpadAvaliacaoDTO();
        $objCpadAvaliacaoDTO_AvaliadoCpad->setNumIdAvaliacaoDocumental($objCpadAvaliacaoDTO_AvaliacaoDocumental->getNumIdAvaliacaoDocumental());
        //filtra apenas pelas avaliacoes cpad ativas, para nao pegar as anteriores
        $objCpadAvaliacaoDTO_AvaliadoCpad->setStrSinAtivo("S");
        //filtra apenas pelas avaliacoes cpad 'avaliado', para nao pegar as 'negado'
        $objCpadAvaliacaoDTO_AvaliadoCpad->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_AVALIADO);
        //testa se as avalicoes cpad ativas e 'avaliado' sao em mesmo numero dos componentes da composicao, o que indica que todos os componentes avaliaram de acordo, logo a avaliacao documental pode ter a situacao alterada para 'comissao'
        if($objCpadAvaliacaoRN->contar($objCpadAvaliacaoDTO_AvaliadoCpad) == InfraArray::contar($arrIdComposicao)){
          //busca a avaliacao documental
          $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
          //altera a situacao para 'comissao'
          $objAvaliacaoDocumentalDTO->setStrStaAvaliacao(AvaliacaoDocumentalRN::$TA_COMISSAO);
          //filtra apenas pelo id da que importa
          $objAvaliacaoDocumentalDTO->setNumIdAvaliacaoDocumental($objCpadAvaliacaoDTO_AvaliacaoDocumental->getNumIdAvaliacaoDocumental());
          //altera
          $objAvaliacaoDocumentalRN->alterar($objAvaliacaoDocumentalDTO);
        }
      }
    }

  }

  //atualiza a composicao conforme a tela
  private function atualizarCpadComposicao( array $arrIdUsuarioCpadComposicao_Ultima,array $arrIdUsuarioCpadComposicao_Tela,CpadDTO $objCpadDTO, CpadVersaoDTO $objCpadVersaoDTO_Ultima, CpadComposicaoRN $objCpadComposicaoRN, $numIdUsuario_NovoPresidente, $bolOrdemIgual){
    //verifica quais componentes foram removidos da composicao
    //se nao encontra o id do componente na composicao atual da tela, exclui
    $arrIdUsuarioComposicao_Exclusao = array_diff($arrIdUsuarioCpadComposicao_Ultima, $arrIdUsuarioCpadComposicao_Tela);
    if(InfraArray::contar($arrIdUsuarioComposicao_Exclusao) > 0) {
      $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
      foreach ($arrIdUsuarioComposicao_Exclusao as $idUsuarioComposicao_Exclusao) {
        //busca as avaliacoes cpad desse componente na ultima versao e as exclui
        $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
        $objCpadAvaliacaoDTO->retNumIdCpadAvaliacao();
        $objCpadAvaliacaoDTO->setNumIdUsuario($idUsuarioComposicao_Exclusao);
        $objCpadAvaliacaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
        $objArrCpadAvaliacao = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO);
        if (InfraArray::contar($objArrCpadAvaliacao) > 0) {
          $objCpadAvaliacaoRN->excluir($objArrCpadAvaliacao);
        }

        //seta os dados do componente e o exclui na ultima versao
        $objCpadComposicaoDTO = new CpadComposicaoDTO();
        $objCpadComposicaoDTO->retNumIdCpadComposicao();
        $objCpadComposicaoDTO->setNumIdUsuario($idUsuarioComposicao_Exclusao);
        $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
        $objCpadComposicaoRN->excluir($objCpadComposicaoRN->listar($objCpadComposicaoDTO));
      }
    }
  //verifica quais componentes foram adicionados da composicao
   //se nao encontra o id do componente na composicao atual cadastrada, adiciona
    $arrIdUsuarioComposicao_Adicao = array_diff($arrIdUsuarioCpadComposicao_Tela, $arrIdUsuarioCpadComposicao_Ultima);
    if(InfraArray::contar($arrIdUsuarioComposicao_Adicao) > 0) {
      //composicao da tela indexado (será usado para a inserção de novos componentes)
      $arrObjCpadComposicao_IndexadoUsuario = InfraArray::indexarArrInfraDTO($objCpadDTO->getObjCpadVersaoAtual()->getArrObjCpadComposicao(), "IdUsuario");
      foreach ($arrIdUsuarioComposicao_Adicao as $idUsuarioComposicao_Adicao){
        //busca o dto do componente
        $objCpadComposicaoDTO = $arrObjCpadComposicao_IndexadoUsuario[$idUsuarioComposicao_Adicao];
        //seta id da ultima versao
        $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
        //cadastra
        $objCpadComposicaoRN->cadastrar($objCpadComposicaoDTO);
      }
    }
    //verifica se houve alteracao de presidente
    //caso necessario quando nao houve adicao/remocao de componentes, mas apenas alteracao do presidente
    if($numIdUsuario_NovoPresidente != null){
      $objCpadComposicaoRN = new CpadComposicaoRN();
      //dto para buscar o presidente atual
      $objCpadComposicaoDTO = new CpadComposicaoDTO();
      $objCpadComposicaoDTO->retNumIdCpadComposicao();
      //filtro do presidente
      $objCpadComposicaoDTO->setStrSinPresidente("S");
      //da versao atual
      $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
      //id do usuario diferente do presidente selecionado na tela
      $objCpadComposicaoDTO->setNumIdUsuario($numIdUsuario_NovoPresidente, InfraDTO::$OPER_DIFERENTE);
      //consulta
      $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);

      //se presidente antigo continua na CPAD
      if ($objCpadComposicaoDTO!=null) {
        //altera para nao ser presidente
        $objCpadComposicaoDTO->setStrSinPresidente("N");
        //altera
        $objCpadComposicaoRN->alterar($objCpadComposicaoDTO);
      }

      //dto do novo presidente
      $objCpadComposicaoDTO = new CpadComposicaoDTO();
      $objCpadComposicaoDTO->retNumIdCpadComposicao();
      //id do usuario
      $objCpadComposicaoDTO->setNumIdUsuario($numIdUsuario_NovoPresidente);
      //versao
      $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
      //consulta
      $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);
      //altera para ser o novo presidente
      $objCpadComposicaoDTO->setStrSinPresidente("S");
      //altera
      $objCpadComposicaoRN->alterar($objCpadComposicaoDTO);
    }

    //verifica se houve alteracao na ordem
    //caso necessario quando nao houve adicao/remocao de componentes, mas a ordem da composicao foi alterada
    if(!$bolOrdemIgual){
      $objCpadComposicaoRN = new CpadComposicaoRN();

      foreach ($arrIdUsuarioCpadComposicao_Tela as $i => $idUsuarioCpadComposicao){
        //dto para buscar o usuario da composicao
        $objCpadComposicaoDTO = new CpadComposicaoDTO();
        $objCpadComposicaoDTO->retNumIdCpadComposicao();
        //filtro do presidente
        $objCpadComposicaoDTO->setNumIdUsuario($idUsuarioCpadComposicao);
        //da versao atual
        $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO_Ultima->getNumIdCpadVersao());
        //consulta
        $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);
        //seta ordem
        $objCpadComposicaoDTO->setNumOrdem($i+1);
        //altera
        $objCpadComposicaoRN->alterar($objCpadComposicaoDTO);
      }
    }
  }

  //ao excluir uma composicao, devem ser excluidas as suas versoes e sua composicao
  //se existir avaliacoes cpad, a cpad nao pode ser excluida
  protected function excluirControlado($arrObjCpadDTO){
    try {
      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_excluir',__METHOD__,$arrObjCpadDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $arrIdCpad = InfraArray::converterArrInfraDTO($arrObjCpadDTO,"IdCpad");

      //verifica se existe avaliacao cpad dessa cpad
      $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
      $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
      $objCpadAvaliacaoDTO->setNumIdCpad($arrIdCpad,InfraDTO::$OPER_IN);
      if($objCpadAvaliacaoRN->contar($objCpadAvaliacaoDTO) > 0){
        $objInfraException->lancarValidacao("Existem avaliações realizadas por essa comissão.");
      }

      //exclui as versoes
      $objCpadVersaoDTO = new CpadVersaoDTO();
      $objCpadVersaoDTO->retNumIdCpadVersao();
      $objCpadVersaoDTO->setNumIdCpad($arrIdCpad,InfraDTO::$OPER_IN);
      $objCpadVersaoDTO->setBolExclusaoLogica(false);
      $objCpadVersaoRN = new CpadVersaoRN();
      //a exclusao das composicoes é realizada na exclusao da versao
      $objCpadVersaoRN->excluir($objCpadVersaoRN->listar($objCpadVersaoDTO));

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadDTO);$i++){
        $objCpadBD->excluir($arrObjCpadDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  //////////////////////////////////////////////////////////////////////////////
  ///o CpadDTO possui os dados da tabela e a informacao de orgao
  /// tambem possui um atributo referente a ultima versao da cpad
  /// é necessario buscar a ultima versao para tambem buscar os componentes da ultima versao da cpad, que serao exibidos na tela
  //////////////////////////////////////////////////////////////////////////////
  protected function consultarConectado(CpadDTO $objCpadDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_consultar',__METHOD__,$objCpadDTO);

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      //busca dados basicos da CpadDTO
      $objCpadDTO->setBolExclusaoLogica(false);
      $objCpadDTO = $objCpadBD->consultar($objCpadDTO);

      if($objCpadDTO != null) {

        // RN da versao e da composicao, para buscar a versao atual e os componentes da versao atual
        $objCpadVersaoRN = new CpadVersaoRN();
        $objCpadComposicaoRN = new CpadComposicaoRN();

        //busca ultima versao (ativa)
        $objCpadVersaoDTO = new CpadVersaoDTO();
        $objCpadVersaoDTO->retNumIdCpadVersao();
        $objCpadVersaoDTO->retNumIdUsuario();
        $objCpadVersaoDTO->setNumIdCpad($objCpadDTO->getNumIdCpad());
        $objCpadVersaoDTO = $objCpadVersaoRN->consultar($objCpadVersaoDTO);
        //seta no objeto cpad
        $objCpadDTO->setObjCpadVersaoAtual($objCpadVersaoDTO);

        //busca a composicao dessa versao
        $objCpadComposicaoDTO = new CpadComposicaoDTO();
        $objCpadComposicaoDTO->retNumIdCpadComposicao();
        $objCpadComposicaoDTO->retNumIdCpadVersao();
        $objCpadComposicaoDTO->retNumIdUsuarioVersao();
        $objCpadComposicaoDTO->retNumIdUsuario();
        $objCpadComposicaoDTO->retNumIdCargo();
        $objCpadComposicaoDTO->retStrNomeUsuario();
        $objCpadComposicaoDTO->retStrExpressaoCargo();
        $objCpadComposicaoDTO->retStrSinPresidente();
        $objCpadComposicaoDTO->setNumIdCpadVersao($objCpadVersaoDTO->getNumIdCpadVersao());
        //ordenacao apenas para exibir sempre na mesma ordem na tela
        $objCpadComposicaoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
        $arrObjCpadComposicaoDTO = $objCpadComposicaoRN->listar($objCpadComposicaoDTO);
        $objCpadVersaoDTO->setArrObjCpadComposicao($arrObjCpadComposicaoDTO);
      }

      return $objCpadDTO;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function listarConectado(CpadDTO $objCpadDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_listar',__METHOD__,$objCpadDTO);

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      $ret = $objCpadBD->listar($objCpadDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Comissões Permanentes de Avaliação de Documentos.',$e);
    }
  }

  protected function contarConectado(CpadDTO $objCpadDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_listar',__METHOD__,$objCpadDTO);

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      $ret = $objCpadBD->contar($objCpadDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Comissões Permanentes de Avaliação de Documentos.',$e);
    }
  }

  protected function desativarControlado($arrObjCpadDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_desativar',__METHOD__,$arrObjCpadDTO);

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadDTO);$i++){
        $objCpadBD->desativar($arrObjCpadDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function reativarControlado($arrObjCpadDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_reativar',__METHOD__,$arrObjCpadDTO);

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjCpadDTO);$i++){
        $objCpadBD->reativar($arrObjCpadDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }

  protected function bloquearControlado(CpadDTO $objCpadDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('cpad_consultar',__METHOD__,$objCpadDTO);

      $objCpadBD = new CpadBD($this->getObjInfraIBanco());
      $ret = $objCpadBD->bloquear($objCpadDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Comissão Permanente de Avaliação de Documentos.',$e);
    }
  }


}
