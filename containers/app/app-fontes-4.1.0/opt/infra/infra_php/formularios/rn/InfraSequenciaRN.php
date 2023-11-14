<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/08/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.27.1
 *
 * Versão no CVS: $Id$
 */

//require_once 'Infra.php';

class InfraSequenciaRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoInfra::getInstance();
    }

    private function validarDblQtdIncremento(InfraSequenciaDTO $objInfraSequenciaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraSequenciaDTO->getDblQtdIncremento())) {
            $objInfraException->adicionarValidacao('Incremento não informado.');
        }
    }

    private function validarDblNumAtual(InfraSequenciaDTO $objInfraSequenciaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraSequenciaDTO->getDblNumAtual())) {
            $objInfraException->adicionarValidacao('Valor Atual não informado.');
        }
    }

    private function validarDblNumMaximo(InfraSequenciaDTO $objInfraSequenciaDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objInfraSequenciaDTO->getDblNumMaximo())) {
            $objInfraException->adicionarValidacao('Valor Máximo não informado.');
        }
    }

    protected function cadastrarControlado(InfraSequenciaDTO $objInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_cadastrar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarDblQtdIncremento($objInfraSequenciaDTO, $objInfraException);
            $this->validarDblNumAtual($objInfraSequenciaDTO, $objInfraException);
            $this->validarDblNumMaximo($objInfraSequenciaDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            $ret = $objInfraSequenciaBD->cadastrar($objInfraSequenciaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Sequência.', $e);
        }
    }

    protected function alterarControlado(InfraSequenciaDTO $objInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_alterar');

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objInfraSequenciaDTO->isSetDblQtdIncremento()) {
                $this->validarDblQtdIncremento($objInfraSequenciaDTO, $objInfraException);
            }
            if ($objInfraSequenciaDTO->isSetDblNumAtual()) {
                $this->validarDblNumAtual($objInfraSequenciaDTO, $objInfraException);
            }
            if ($objInfraSequenciaDTO->isSetDblNumMaximo()) {
                $this->validarDblNumMaximo($objInfraSequenciaDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            $objInfraSequenciaBD->alterar($objInfraSequenciaDTO);
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro alterando Sequência.', $e);
        }
    }

    protected function excluirControlado($arrObjInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_excluir');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjInfraSequenciaDTO); $i++) {
                $objInfraSequenciaBD->excluir($arrObjInfraSequenciaDTO[$i]);
            }
            //Auditoria

        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Sequência.', $e);
        }
    }

    protected function consultarConectado(InfraSequenciaDTO $objInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            $ret = $objInfraSequenciaBD->consultar($objInfraSequenciaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Sequência.', $e);
        }
    }

    protected function listarConectado(InfraSequenciaDTO $objInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            $ret = $objInfraSequenciaBD->listar($objInfraSequenciaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando Sequências.', $e);
        }
    }

    protected function contarConectado(InfraSequenciaDTO $objInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_listar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            $ret = $objInfraSequenciaBD->contar($objInfraSequenciaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Sequências.', $e);
        }
    }

    protected function bloquearControlado(InfraSequenciaDTO $objInfraSequenciaDTO)
    {
        try {
            //Valida Permissao
            //SessaoInfra::getInstance()->validarPermissao('infra_sequencia_consultar');

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
            $ret = $objInfraSequenciaBD->bloquear($objInfraSequenciaDTO);

            //Auditoria

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando Sequência.', $e);
        }
    }
    /*
      protected function desativarControlado($arrObjInfraSequenciaDTO){
        try {

          //Valida Permissao
          SessaoInfra::getInstance()->validarPermissao('infra_sequencia_desativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjInfraSequenciaDTO);$i++){
            $objInfraSequenciaBD->desativar($arrObjInfraSequenciaDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro desativando Sequência.',$e);
        }
      }

      protected function reativarControlado($arrObjInfraSequenciaDTO){
        try {

          //Valida Permissao
          SessaoInfra::getInstance()->validarPermissao('infra_sequencia_reativar');

          //Regras de Negocio
          //$objInfraException = new InfraException();

          //$objInfraException->lancarValidacoes();

          $objInfraSequenciaBD = new InfraSequenciaBD($this->getObjInfraIBanco());
          for($i=0;$i<count($arrObjInfraSequenciaDTO);$i++){
            $objInfraSequenciaBD->reativar($arrObjInfraSequenciaDTO[$i]);
          }

          //Auditoria

        }catch(Exception $e){
          throw new InfraException('Erro reativando Sequência.',$e);
        }
      }

     */
}

