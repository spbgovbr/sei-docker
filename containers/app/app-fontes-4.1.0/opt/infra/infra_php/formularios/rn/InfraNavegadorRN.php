<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 08/08/2012 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraNavegadorRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoInfra::getInstance();
    }

    protected function pesquisarConectado(InfraNavegadorDTO $objInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_listar');

            $objInfraException = new InfraException();

            if ($objInfraNavegadorDTO->isSetDthInicial() || $objInfraNavegadorDTO->isSetDthFinal()) {
                if (!$objInfraNavegadorDTO->isSetDthInicial()) {
                    $objInfraException->lancarValidacao('Data/Hora inicial do período de busca não informada.');
                } else {
                    if (strlen($objInfraNavegadorDTO->getDthInicial()) == '16') {
                        $objInfraNavegadorDTO->setDthInicial($objInfraNavegadorDTO->getDthInicial() . ':00');
                    }
                }

                if (!InfraData::validarDataHora($objInfraNavegadorDTO->getDthInicial())) {
                    $objInfraException->lancarValidacao('Data/Hora inicial do período de busca inválida.');
                }

                if (!$objInfraNavegadorDTO->isSetDthFinal()) {
                    $objInfraNavegadorDTO->setDthFinal($objInfraNavegadorDTO->getDthInicial());
                } else {
                    if (strlen($objInfraNavegadorDTO->getDthFinal()) == '16') {
                        $objInfraNavegadorDTO->setDthFinal($objInfraNavegadorDTO->getDthFinal() . ':59');
                    }

                    if (!InfraData::validarDataHora($objInfraNavegadorDTO->getDthFinal())) {
                        $objInfraException->lancarValidacao('Data/Hora final do período de busca inválida.');
                    }
                }

                if (InfraData::compararDatas(
                        $objInfraNavegadorDTO->getDthInicial(),
                        $objInfraNavegadorDTO->getDthFinal()
                    ) < 0) {
                    $objInfraException->lancarValidacao('Período de datas/horas inválido.');
                }

                if (strlen($objInfraNavegadorDTO->getDthInicial()) == '10') {
                    $objInfraNavegadorDTO->setDthInicial($objInfraNavegadorDTO->getDthInicial() . ' 00:00:00');
                }

                if (strlen($objInfraNavegadorDTO->getDthFinal()) == '10') {
                    $objInfraNavegadorDTO->setDthFinal($objInfraNavegadorDTO->getDthFinal() . ' 23:59:59');
                }
            }

            if (!InfraUtil::isBolSinalizadorValido($objInfraNavegadorDTO->getStrSinIgnorarVersao())) {
                $objInfraException->lancarValidacao('Sinalizador de versão inválido.');
            }

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            $arrObjInfraNavegadorDTO = $objInfraNavegadorBD->pesquisar($objInfraNavegadorDTO);

            if (count($arrObjInfraNavegadorDTO)) {
                $numTotal = 0;
                foreach ($arrObjInfraNavegadorDTO as $objInfraNavegadorDTO) {
                    $numTotal += $objInfraNavegadorDTO->getDblTotalAcessos();
                }

                foreach ($arrObjInfraNavegadorDTO as $objInfraNavegadorDTO) {
                    $objInfraNavegadorDTO->setStrTotalFormatado(
                        InfraUtil::formatarMilhares($objInfraNavegadorDTO->getDblTotalAcessos()) . ' (' . round(
                            ($objInfraNavegadorDTO->getDblTotalAcessos() / $numTotal * 100),
                            1
                        ) . '%)'
                    );
                }
            }
            return $arrObjInfraNavegadorDTO;
        } catch (Exception $e) {
            throw new InfraException('Erro pesquisando navegadores', $e);
        }
    }

    private function validarStrIdentificacao(InfraNavegadorDTO $objInfraNavegadorDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraNavegadorDTO->getStrIdentificacao())) {
            $objInfraException->adicionarValidacao('Identificação não informada.');
        } else {
            $objInfraNavegadorDTO->setStrIdentificacao(trim($objInfraNavegadorDTO->getStrIdentificacao()));

            if (strlen($objInfraNavegadorDTO->getStrIdentificacao()) > 50) {
                $objInfraException->adicionarValidacao('Identificação possui tamanho superior a 50 caracteres.');
            }
        }
    }

    private function validarStrVersao(InfraNavegadorDTO $objInfraNavegadorDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraNavegadorDTO->getStrVersao())) {
            $objInfraNavegadorDTO->setStrVersao(null);
        } else {
            $objInfraNavegadorDTO->setStrVersao(trim($objInfraNavegadorDTO->getStrVersao()));

            if (strlen($objInfraNavegadorDTO->getStrVersao()) > 20) {
                $objInfraException->adicionarValidacao('Versão possui tamanho superior a 20 caracteres.');
            }
        }
    }

    private function validarStrUserAgent(InfraNavegadorDTO $objInfraNavegadorDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraNavegadorDTO->getStrUserAgent())) {
            $objInfraException->adicionarValidacao('User Agent não informado.');
        } else {
            $objInfraNavegadorDTO->setStrUserAgent(trim($objInfraNavegadorDTO->getStrUserAgent()));
        }
    }

    private function validarStrIp(InfraNavegadorDTO $objInfraNavegadorDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraNavegadorDTO->getStrIp())) {
            $objInfraException->adicionarValidacao('IP não informado.');
        } else {
            $objInfraNavegadorDTO->setStrIp(trim($objInfraNavegadorDTO->getStrIp()));

            if (strlen($objInfraNavegadorDTO->getStrIp()) > 39) {
                $objInfraException->adicionarValidacao('IP possui tamanho superior a 39 caracteres.');
            }
        }
    }

    private function validarDthAcesso(InfraNavegadorDTO $objInfraNavegadorDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraNavegadorDTO->getDthAcesso())) {
            $objInfraException->adicionarValidacao('Data/Hora não informada.');
        } else {
            if (!InfraData::validarDataHora($objInfraNavegadorDTO->getDthAcesso())) {
                $objInfraException->adicionarValidacao('Data/Hora inválida.');
            }
        }
    }

    protected function cadastrarControlado(InfraNavegadorDTO $objInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrIdentificacao($objInfraNavegadorDTO, $objInfraException);
            $this->validarStrVersao($objInfraNavegadorDTO, $objInfraException);
            $this->validarStrUserAgent($objInfraNavegadorDTO, $objInfraException);
            $this->validarStrIp($objInfraNavegadorDTO, $objInfraException);
            $this->validarDthAcesso($objInfraNavegadorDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            $ret = $objInfraNavegadorBD->cadastrar($objInfraNavegadorDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Navegador.', $e);
        }
    }

    protected function alterarControlado(InfraNavegadorDTO $objInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objInfraNavegadorDTO->isSetStrIdentificacao()) {
                $this->validarStrIdentificacao($objInfraNavegadorDTO, $objInfraException);
            }
            if ($objInfraNavegadorDTO->isSetStrVersao()) {
                $this->validarStrVersao($objInfraNavegadorDTO, $objInfraException);
            }
            if ($objInfraNavegadorDTO->isSetStrUserAgent()) {
                $this->validarStrUserAgent($objInfraNavegadorDTO, $objInfraException);
            }
            if ($objInfraNavegadorDTO->isSetStrIp()) {
                $this->validarStrIp($objInfraNavegadorDTO, $objInfraException);
            }
            if ($objInfraNavegadorDTO->isSetDthAcesso()) {
                $this->validarDthAcesso($objInfraNavegadorDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            $objInfraNavegadorBD->alterar($objInfraNavegadorDTO);
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Navegador.', $e);
        }
    }

    protected function excluirControlado($arrObjInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjInfraNavegadorDTO); $i++) {
                $objInfraNavegadorBD->excluir($arrObjInfraNavegadorDTO[$i]);
            }
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Navegador.', $e);
        }
    }

    protected function consultarConectado(InfraNavegadorDTO $objInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            $ret = $objInfraNavegadorBD->consultar($objInfraNavegadorDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Navegador.', $e);
        }
    }

    protected function listarConectado(InfraNavegadorDTO $objInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            $ret = $objInfraNavegadorBD->listar($objInfraNavegadorDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando Navegadores.', $e);
        }
    }

    protected function contarConectado(InfraNavegadorDTO $objInfraNavegadorDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_navegador_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
            $ret = $objInfraNavegadorBD->contar($objInfraNavegadorDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Navegadores.', $e);
        }
    }
    /*
      protected function desativarControlado($arrObjInfraNavegadorDTO){
        try {

          //Valida Permissao
          SessaoInfra::getInstance()->validarPermissao('infra_navegador_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjInfraNavegadorDTO);$i++){
            $objInfraNavegadorBD->desativar($arrObjInfraNavegadorDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando Navegador.',$e);
        }
      }

      protected function reativarControlado($arrObjInfraNavegadorDTO){
        try {

          //Valida Permissao
          SessaoInfra::getInstance()->validarPermissao('infra_navegador_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjInfraNavegadorDTO);$i++){
            $objInfraNavegadorBD->reativar($arrObjInfraNavegadorDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando Navegador.',$e);
        }
      }

      protected function bloquearControlado(InfraNavegadorDTO $objInfraNavegadorDTO){
        try {

          //Valida Permissao
          SessaoInfra::getInstance()->validarPermissao('infra_navegador_consultar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraNavegadorBD = new InfraNavegadorBD($this->getObjInfraIBanco());
          $ret = $objInfraNavegadorBD->bloquear($objInfraNavegadorDTO);

          //Auditoria

          return $ret;
        }catch(Exception $e){
          throw new InfraException('Erro bloqueando Navegador.',$e);
        }
      }

     */
}

