<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/04/2021 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.0
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraCaptchaRN extends InfraRN
{


    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoInfra::getInstance();
    }

    private function validarNumDia(InfraCaptchaDTO $objInfraCaptchaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraCaptchaDTO->getNumDia())) {
            $objInfraException->adicionarValidacao('Dia não informado.');
        }
    }

    private function validarNumMes(InfraCaptchaDTO $objInfraCaptchaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraCaptchaDTO->getNumMes())) {
            $objInfraException->adicionarValidacao('Mês não informado.');
        }
    }

    private function validarNumAno(InfraCaptchaDTO $objInfraCaptchaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraCaptchaDTO->getNumAno())) {
            $objInfraException->adicionarValidacao('Ano não informado.');
        }
    }

    private function validarStrIdentificacao(InfraCaptchaDTO $objInfraCaptchaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraCaptchaDTO->getStrIdentificacao())) {
            $objInfraException->adicionarValidacao('Identificação não informada.');
        } else {
            $objInfraCaptchaDTO->setStrIdentificacao(trim($objInfraCaptchaDTO->getStrIdentificacao()));

            if (strlen($objInfraCaptchaDTO->getStrIdentificacao()) > 50) {
                $objInfraException->adicionarValidacao('Identificação possui tamanho superior a 50 caracteres.');
            }
        }
    }

    private function validarDblAcertos(InfraCaptchaDTO $objInfraCaptchaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraCaptchaDTO->getDblAcertos())) {
            $objInfraException->adicionarValidacao('Acertos não informado.');
        }
    }

    private function validarDblErros(InfraCaptchaDTO $objInfraCaptchaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraCaptchaDTO->getDblErros())) {
            $objInfraException->adicionarValidacao('Erros não informado.');
        }
    }

    protected function registrarControlado(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            //SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_registrar', __METHOD__, $objInfraCaptchaDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();
            $this->validarStrIdentificacao($objInfraCaptchaDTO, $objInfraException);
            $this->validarDblAcertos($objInfraCaptchaDTO, $objInfraException);
            $this->validarDblErros($objInfraCaptchaDTO, $objInfraException);
            $objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            $objInfraCaptchaBD->registrar($objInfraCaptchaDTO);
        } catch (Exception $e) {
            throw new InfraException('Erro registrando Acesso Captcha.', $e);
        }
    }

    protected function cadastrarControlado(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            //SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_cadastrar', __METHOD__, $objInfraCaptchaDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarNumDia($objInfraCaptchaDTO, $objInfraException);
            $this->validarNumMes($objInfraCaptchaDTO, $objInfraException);
            $this->validarNumAno($objInfraCaptchaDTO, $objInfraException);
            $this->validarStrIdentificacao($objInfraCaptchaDTO, $objInfraException);
            $this->validarDblAcertos($objInfraCaptchaDTO, $objInfraException);
            $this->validarDblErros($objInfraCaptchaDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            $ret = $objInfraCaptchaBD->cadastrar($objInfraCaptchaDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Acesso Captcha.', $e);
        }
    }

    protected function alterarControlado(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            SessaoInfra::getInstance()->validarAuditarPermissao(
                'infra_captcha_alterar',
                __METHOD__,
                $objInfraCaptchaDTO
            );

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objInfraCaptchaDTO->isSetNumDia()) {
                $this->validarNumDia($objInfraCaptchaDTO, $objInfraException);
            }

            if ($objInfraCaptchaDTO->isSetNumMes()) {
                $this->validarNumMes($objInfraCaptchaDTO, $objInfraException);
            }

            if ($objInfraCaptchaDTO->isSetNumAno()) {
                $this->validarNumAno($objInfraCaptchaDTO, $objInfraException);
            }

            if ($objInfraCaptchaDTO->isSetStrIdentificacao()) {
                $this->validarStrIdentificacao($objInfraCaptchaDTO, $objInfraException);
            }

            if ($objInfraCaptchaDTO->isSetDblAcertos()) {
                $this->validarDblAcertos($objInfraCaptchaDTO, $objInfraException);
            }

            if ($objInfraCaptchaDTO->isSetDblErros()) {
                $this->validarDblErros($objInfraCaptchaDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            $objInfraCaptchaBD->alterar($objInfraCaptchaDTO);
        } catch (Exception $e) {
            throw new InfraException('Erro alterando Acesso Captcha.', $e);
        }
    }

    protected function excluirControlado($arrObjInfraCaptchaDTO)
    {
        try {
            SessaoInfra::getInstance()->validarAuditarPermissao(
                'infra_captcha_excluir',
                __METHOD__,
                $arrObjInfraCaptchaDTO
            );

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjInfraCaptchaDTO); $i++) {
                $objInfraCaptchaBD->excluir($arrObjInfraCaptchaDTO[$i]);
            }
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Acesso Captcha.', $e);
        }
    }

    protected function consultarConectado(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            //SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_consultar', __METHOD__, $objInfraCaptchaDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            $ret = $objInfraCaptchaBD->consultar($objInfraCaptchaDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Acesso Captcha.', $e);
        }
    }

    protected function listarConectado(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            //SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_listar', __METHOD__, $objInfraCaptchaDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            $ret = $objInfraCaptchaBD->listar($objInfraCaptchaDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando Acessos Captcha.', $e);
        }
    }

    protected function contarConectado(InfraCaptchaDTO $objInfraCaptchaDTO)
    {
        try {
            SessaoInfra::getInstance()->validarAuditarPermissao(
                'infra_captcha_listar',
                __METHOD__,
                $objInfraCaptchaDTO
            );

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
            $ret = $objInfraCaptchaBD->contar($objInfraCaptchaDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Acessos Captcha.', $e);
        }
    }
    /*
      protected function desativarControlado($arrObjInfraCaptchaDTO){
        try {

          SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_desativar', __METHOD__, $arrObjInfraCaptchaDTO);

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjInfraCaptchaDTO);$i++){
            $objInfraCaptchaBD->desativar($arrObjInfraCaptchaDTO[$i]);
          }

        }catch(Exception $e){
          throw new InfraException('Erro desativando Acesso Captcha.',$e);
        }
      }

      protected function reativarControlado($arrObjInfraCaptchaDTO){
        try {

          SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_reativar', __METHOD__, $arrObjInfraCaptchaDTO);

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjInfraCaptchaDTO);$i++){
            $objInfraCaptchaBD->reativar($arrObjInfraCaptchaDTO[$i]);
          }

        }catch(Exception $e){
          throw new InfraException('Erro reativando Acesso Captcha.',$e);
        }
      }

      protected function bloquearControlado(InfraCaptchaDTO $objInfraCaptchaDTO){
        try {

          SessaoInfra::getInstance()->validarAuditarPermissao('infra_captcha_consultar', __METHOD__, $objInfraCaptchaDTO);

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraCaptchaBD = new InfraCaptchaBD($this->getObjInfraIBanco());
          $ret = $objInfraCaptchaBD->bloquear($objInfraCaptchaDTO);

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando Acesso Captcha.',$e);
        }
      }

     */
}
