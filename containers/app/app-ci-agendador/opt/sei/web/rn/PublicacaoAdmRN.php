<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 21/07/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoAdmRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumLotacaocodigoRN1222(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getNumLotacaocodigo())){
      $objInfraException->adicionarValidacao('Código da Lotação não informado.');
    }
  }

  private function validarStrLotacaonomeRN1223(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrLotacaonome())){
      $objInfraException->adicionarValidacao('Nome da Lotação não informado.');
    }else{
      $objPublicacaoAdmDTO->setStrLotacaonome(trim($objPublicacaoAdmDTO->getStrLotacaonome()));

      if (strlen($objPublicacaoAdmDTO->getStrLotacaonome())>50){
        $objInfraException->adicionarValidacao('Nome da Lotação possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrLotacaosiglaRN1224(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrLotacaosigla())){
      $objInfraException->adicionarValidacao('Sigla da Lotação não informada.');
    }else{
      $objPublicacaoAdmDTO->setStrLotacaosigla(trim($objPublicacaoAdmDTO->getStrLotacaosigla()));

      if (strlen($objPublicacaoAdmDTO->getStrLotacaosigla())>20){
        $objInfraException->adicionarValidacao('Sigla da Lotação possui tamanho superior a 20 caracteres.');
      }
    }
  }

  private function validarStrNomerelatorRN1225(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrNomerelator())){
      $objInfraException->adicionarValidacao('Nome do Relator não informado.');
    }else{
      $objPublicacaoAdmDTO->setStrNomerelator(trim($objPublicacaoAdmDTO->getStrNomerelator()));

      if (strlen($objPublicacaoAdmDTO->getStrNomerelator())>50){
        $objInfraException->adicionarValidacao('Nome do Relator possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrProcessoRN1226(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrProcesso())){
      $objPublicacaoAdmDTO->setStrProcesso(null);
    }else{
      $objPublicacaoAdmDTO->setStrProcesso(trim($objPublicacaoAdmDTO->getStrProcesso()));

      if (strlen($objPublicacaoAdmDTO->getStrProcesso())>13){
        $objInfraException->adicionarValidacao('Processo possui tamanho superior a 13 caracteres.');
      }
    }
  }

  private function validarStrProcessoeditadoRN1227(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrProcessoeditado())){
      $objInfraException->adicionarValidacao('Processo Editado não informado.');
    }else{
      $objPublicacaoAdmDTO->setStrProcessoeditado(trim($objPublicacaoAdmDTO->getStrProcessoeditado()));

      if (strlen($objPublicacaoAdmDTO->getStrProcessoeditado())>30){
        $objInfraException->adicionarValidacao('Processo Editado possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrConteudoRN1228(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrConteudo())){
      $objInfraException->adicionarValidacao('Conteúdo não informado.');
    }else{
      $objPublicacaoAdmDTO->setStrConteudo(trim($objPublicacaoAdmDTO->getStrConteudo()));
    }
  }

  private function validarDthDataelaboracaoRN1229(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getDthDataelaboracao())){
      $objInfraException->adicionarValidacao('Data de Elaboração não informada.');
    }
  }

  private function validarDthDataintranetRN1230(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getDthDataintranet())){
      $objInfraException->adicionarValidacao('Data da Intranet não informada.');
    }
  }

  private function validarStrConteudotxtRN1231(PublicacaoAdmDTO $objPublicacaoAdmDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPublicacaoAdmDTO->getStrConteudotxt())){
      $objPublicacaoAdmDTO->setStrConteudotxt(null);
    }else{
      $objPublicacaoAdmDTO->setStrConteudotxt(trim($objPublicacaoAdmDTO->getStrConteudotxt()));
    }
  }

  protected function cadastrarRN1232Controlado(PublicacaoAdmDTO $objPublicacaoAdmDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_cadastrar',__METHOD__,$objPublicacaoAdmDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumLotacaocodigoRN1222($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrLotacaonomeRN1223($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrLotacaosiglaRN1224($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrNomerelatorRN1225($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrProcessoRN1226($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrProcessoeditadoRN1227($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrConteudoRN1228($objPublicacaoAdmDTO, $objInfraException);
      $this->validarDthDataelaboracaoRN1229($objPublicacaoAdmDTO, $objInfraException);
      $this->validarDthDataintranetRN1230($objPublicacaoAdmDTO, $objInfraException);
      $this->validarStrConteudotxtRN1231($objPublicacaoAdmDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoAdmBD->cadastrar($objPublicacaoAdmDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Publicação Administrativa.',$e);
    }
  }

  protected function alterarRN1233Controlado(PublicacaoAdmDTO $objPublicacaoAdmDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_alterar',__METHOD__,$objPublicacaoAdmDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objPublicacaoAdmDTO->isSetNumLotacaocodigo()){
        $this->validarNumLotacaocodigoRN1222($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrLotacaonome()){
        $this->validarStrLotacaonomeRN1223($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrLotacaosigla()){
        $this->validarStrLotacaosiglaRN1224($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrNomerelator()){
        $this->validarStrNomerelatorRN1225($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrProcesso()){
        $this->validarStrProcessoRN1226($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrProcessoeditado()){
        $this->validarStrProcessoeditadoRN1227($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrConteudo()){
        $this->validarStrConteudoRN1228($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetDthDataelaboracao()){
        $this->validarDthDataelaboracaoRN1229($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetDthDataintranet()){
        $this->validarDthDataintranetRN1230($objPublicacaoAdmDTO, $objInfraException);
      }
      if ($objPublicacaoAdmDTO->isSetStrConteudotxt()){
        $this->validarStrConteudotxtRN1231($objPublicacaoAdmDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      $objPublicacaoAdmBD->alterar($objPublicacaoAdmDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Publicação Administrativa.',$e);
    }
  }

  protected function excluirRN1234Controlado($arrObjPublicacaoAdmDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_excluir',__METHOD__,$arrObjPublicacaoAdmDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPublicacaoAdmDTO);$i++){
        $objPublicacaoAdmBD->excluir($arrObjPublicacaoAdmDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Publicação Administrativa.',$e);
    }
  }

  protected function consultarRN1235Conectado(PublicacaoAdmDTO $objPublicacaoAdmDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_consultar',__METHOD__,$objPublicacaoAdmDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoAdmBD->consultar($objPublicacaoAdmDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Publicação Administrativa.',$e);
    }
  }

  protected function listarRN1236Conectado(PublicacaoAdmDTO $objPublicacaoAdmDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_listar',__METHOD__,$objPublicacaoAdmDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoAdmBD->listar($objPublicacaoAdmDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Publicações Administrativas.',$e);
    }
  }

  protected function contarRN1237Conectado(PublicacaoAdmDTO $objPublicacaoAdmDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_listar',__METHOD__,$objPublicacaoAdmDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      $ret = $objPublicacaoAdmBD->contar($objPublicacaoAdmDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Publicações Administrativas.',$e);
    }
  }
/* 
  protected function desativarRN1238Controlado($arrObjPublicacaoAdmDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPublicacaoAdmDTO);$i++){
        $objPublicacaoAdmBD->desativar($arrObjPublicacaoAdmDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Publicação Administrativa.',$e);
    }
  }

  protected function reativarRN1239Controlado($arrObjPublicacaoAdmDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('publicacao_adm_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPublicacaoAdmBD = new PublicacaoAdmBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPublicacaoAdmDTO);$i++){
        $objPublicacaoAdmBD->reativar($arrObjPublicacaoAdmDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Publicação Administrativa.',$e);
    }
  }

 */
}
?>