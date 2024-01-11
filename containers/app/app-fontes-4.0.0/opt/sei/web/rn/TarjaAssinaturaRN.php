<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/06/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class TarjaAssinaturaRN extends InfraRN
{

  public static $TT_ASSINATURA_CERTIFICADO_DIGITAL = 'C';
  public static $TT_ASSINATURA_SENHA = 'S';
  public static $TT_AUTENTICACAO_CERTIFICADO_DIGITAL = 'A';
  public static $TT_AUTENTICACAO_SENHA = 'H';
  public static $TT_INSTRUCOES_VALIDACAO = 'V';

  public function __construct()
  {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  public function listarTiposTarjaAssinatura()
  {
    try {

      $objArrTipoDTO = array();

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TT_ASSINATURA_CERTIFICADO_DIGITAL);
      $objTipoDTO->setStrDescricao('Assinatura com Certificado Digital');
      $objArrTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TT_ASSINATURA_SENHA);
      $objTipoDTO->setStrDescricao('Assinatura Eletrônica');
      $objArrTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TT_AUTENTICACAO_CERTIFICADO_DIGITAL);
      $objTipoDTO->setStrDescricao('Autenticação com Certificado Digital');
      $objArrTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TT_AUTENTICACAO_SENHA);
      $objTipoDTO->setStrDescricao('Autenticação Eletrônica');
      $objArrTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TT_INSTRUCOES_VALIDACAO);
      $objTipoDTO->setStrDescricao('Instruções de Validação');
      $objArrTipoDTO[] = $objTipoDTO;

      //TODO: obter tipos de tarja adicionais via pontos de extensão (temporario)
      global $SEI_MODULOS;

      foreach ($SEI_MODULOS as $seiModulo) {

        if (($arrIntegracao = $seiModulo->executar('montarTipoTarjaAssinaturaCustomizada')) != null) {

          foreach ($arrIntegracao as $objTipoDTO) {
            $objArrTipoDTO[] = $objTipoDTO;
          }

        }

      }

      return $objArrTipoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando tipos de tarja de assinatura.', $e);
    }
  }


  private function validarStrStaTarjaAssinatura(TarjaAssinaturaDTO $objTarjaAssinaturaDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objTarjaAssinaturaDTO->getStrStaTarjaAssinatura())) {
      $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(null);
      //$objInfraException->adicionarValidacao('Forma de Autenticação não informada.');
    } else {
      $objAssinaturaRN = new AssinaturaRN();
      if (!in_array($objTarjaAssinaturaDTO->getStrStaTarjaAssinatura(), InfraArray::converterArrInfraDTO($this->listarTiposTarjaAssinatura(), 'StaTipo'))) {
        $objInfraException->adicionarValidacao('Forma de Autenticação inválida.');
      }
    }
  }

  private function validarStrTexto(TarjaAssinaturaDTO $objTarjaAssinaturaDTO, InfraException $objInfraException)
  {
    if (InfraString::isBolVazia($objTarjaAssinaturaDTO->getStrTexto())) {
      $objInfraException->adicionarValidacao('Texto não informado.');
    } else {
      $objTarjaAssinaturaDTO->setStrTexto(trim($objTarjaAssinaturaDTO->getStrTexto()));
    }
  }

  private function validarStrNomeArquivo(TarjaAssinaturaDTO $objTarjaAssinaturaDTO, InfraException $objInfraException)
  {
    if (!InfraString::isBolVazia($objTarjaAssinaturaDTO->getStrNomeArquivo()) && $objTarjaAssinaturaDTO->getStrNomeArquivo() != "*REMOVER*") {
      if (!file_exists(DIR_SEI_TEMP.'/'.$objTarjaAssinaturaDTO->getStrNomeArquivo())){
        $objInfraException->adicionarValidacao('Não foi possível abrir arquivo da imagem.');
      }
    }
  }

  /*
  protected function cadastrarControlado(TarjaAssinaturaDTO $objTarjaAssinaturaDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_cadastrar',__METHOD__,$objTarjaAssinaturaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrStaTarjaAssinatura($objTarjaAssinaturaDTO, $objInfraException);
      $this->validarStrTexto($objTarjaAssinaturaDTO, $objInfraException);
      $this->validarStrLogo($objTarjaAssinaturaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
      $ret = $objTarjaAssinaturaBD->cadastrar($objTarjaAssinaturaDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tarja de Assinatura.',$e);
    }
  }
  */

  protected function alterarControlado(TarjaAssinaturaDTO $objTarjaAssinaturaDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_alterar', __METHOD__, $objTarjaAssinaturaDTO);

      $objTarjaAssinaturaDTOBanco = new TarjaAssinaturaDTO();
      $objTarjaAssinaturaDTOBanco->retStrStaTarjaAssinatura();
      $objTarjaAssinaturaDTOBanco->retStrTexto();
      $objTarjaAssinaturaDTOBanco->retStrLogo();
      $objTarjaAssinaturaDTOBanco->setNumIdTarjaAssinatura($objTarjaAssinaturaDTO->getNumIdTarjaAssinatura());
      $objTarjaAssinaturaDTOBanco = $this->consultar($objTarjaAssinaturaDTOBanco);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objTarjaAssinaturaDTO->getStrStaTarjaAssinatura() != $objTarjaAssinaturaDTOBanco->getStrStaTarjaAssinatura()) {
        $objInfraException->adicionarValidacao('Não é possível alterar o tipo da tarja de assinatura.');
      }

      $this->validarStrTexto($objTarjaAssinaturaDTO, $objInfraException);
      $this->validarStrNomeArquivo($objTarjaAssinaturaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if (!InfraString::isBolVazia($objTarjaAssinaturaDTO->getStrNomeArquivo())) {
        if ($objTarjaAssinaturaDTO->getStrNomeArquivo() == "*REMOVER*") {
          $objTarjaAssinaturaDTO->setStrLogo(null);
        } else {
          $dadosArquivo = file_get_contents(DIR_SEI_TEMP.'/'.$objTarjaAssinaturaDTO->getStrNomeArquivo());
          $objTarjaAssinaturaDTO->setStrLogo(base64_encode($dadosArquivo));
        }
      } else {
        $objTarjaAssinaturaDTO->setStrLogo($objTarjaAssinaturaDTOBanco->getStrLogo());
      }

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());

      if ($objTarjaAssinaturaDTO->getStrTexto() != $objTarjaAssinaturaDTOBanco->getStrTexto() ||
          $objTarjaAssinaturaDTO->getStrLogo() != $objTarjaAssinaturaDTOBanco->getStrLogo()
      ) {

        $dto = new TarjaAssinaturaDTO();
        $dto->setStrSinAtivo('N');
        $dto->setNumIdTarjaAssinatura($objTarjaAssinaturaDTO->getNumIdTarjaAssinatura());
        $objTarjaAssinaturaBD->alterar($dto);

        $objTarjaAssinaturaDTO->setStrSinAtivo('S');
        $objTarjaAssinaturaBD->cadastrar($objTarjaAssinaturaDTO);

      } else {
        $objTarjaAssinaturaBD->alterar($objTarjaAssinaturaDTO);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro alterando Tarja de Assinatura.', $e);
    }
  }

  protected function excluirControlado($arrObjTarjaAssinaturaDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_excluir', __METHOD__, $arrObjTarjaAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjTarjaAssinaturaDTO); $i++) {
        $objTarjaAssinaturaBD->excluir($arrObjTarjaAssinaturaDTO[$i]);
      }

      //Auditoria

    } catch (Exception $e) {
      throw new InfraException('Erro excluindo Tarja de Assinatura.', $e);
    }
  }

  protected function consultarConectado(TarjaAssinaturaDTO $objTarjaAssinaturaDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_consultar', __METHOD__, $objTarjaAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
      $ret = $objTarjaAssinaturaBD->consultar($objTarjaAssinaturaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro consultando Tarja de Assinatura.', $e);
    }
  }

  protected function listarConectado(TarjaAssinaturaDTO $objTarjaAssinaturaDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_listar', __METHOD__, $objTarjaAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
      $ret = $objTarjaAssinaturaBD->listar($objTarjaAssinaturaDTO);

      //Auditoria

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro listando Tarjas de Assinatura.', $e);
    }
  }

  protected function contarConectado(TarjaAssinaturaDTO $objTarjaAssinaturaDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_listar', __METHOD__, $objTarjaAssinaturaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
      $ret = $objTarjaAssinaturaBD->contar($objTarjaAssinaturaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro contando Tarjas de Assinatura.', $e);
    }
  }

  /*
    protected function desativarControlado($arrObjTarjaAssinaturaDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_desativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjTarjaAssinaturaDTO);$i++){
          $objTarjaAssinaturaBD->desativar($arrObjTarjaAssinaturaDTO[$i]);
        }

        //Auditoria

      }catch(Exception $e){
        throw new InfraException('Erro desativando Tarja de Assinatura.',$e);
      }
    }

    protected function reativarControlado($arrObjTarjaAssinaturaDTO){
      try {

        //Valida Permissao
        SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_reativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjTarjaAssinaturaDTO);$i++){
          $objTarjaAssinaturaBD->reativar($arrObjTarjaAssinaturaDTO[$i]);
        }

        //Auditoria

      }catch(Exception $e){
        throw new InfraException('Erro reativando Tarja de Assinatura.',$e);
      }
    }

  protected function bloquearControlado(TarjaAssinaturaDTO $objTarjaAssinaturaDTO)
  {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('tarja_assinatura_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objTarjaAssinaturaBD = new TarjaAssinaturaBD($this->getObjInfraIBanco());
      $ret = $objTarjaAssinaturaBD->bloquear($objTarjaAssinaturaDTO);

      //Auditoria

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro bloqueando Tarja de Assinatura.', $e);
    }
  }
  */
}
?>