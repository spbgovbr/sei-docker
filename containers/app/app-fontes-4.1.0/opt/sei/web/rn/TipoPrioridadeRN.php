<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/01/2023 - criado por cas84
 *
 * Versão do Gerador de Código: 1.43.2
 */

require_once dirname(__FILE__) . '/../SEI.php';

class TipoPrioridadeRN extends InfraRN
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoSEI::getInstance();
    }

    private function validarStrNome(TipoPrioridadeDTO $objTipoPrioridadeDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objTipoPrioridadeDTO->getStrNome())) {
            $objInfraException->adicionarValidacao('Nome não informado.');
        } else {
            $objTipoPrioridadeDTO->setStrNome(trim($objTipoPrioridadeDTO->getStrNome()));

            if (strlen($objTipoPrioridadeDTO->getStrNome()) > 100) {
                $objInfraException->adicionarValidacao('Nome possui tamanho superior a 100 caracteres.');
            } else {
                $objTipoPrioridadeDTOBanco = new TipoPrioridadeDTO();
                $objTipoPrioridadeDTOBanco->setStrNome($objTipoPrioridadeDTO->getStrNome());
                if ($objTipoPrioridadeDTO->isSetNumIdTipoPrioridade()) {
                    $objTipoPrioridadeDTOBanco->setNumIdTipoPrioridade(
                        $objTipoPrioridadeDTO->getNumIdTipoPrioridade(),
                        InfraDTO::$OPER_DIFERENTE
                    );
                }
                if ($this->contar($objTipoPrioridadeDTOBanco) > 0) {
                    $objInfraException->adicionarValidacao('Já existe tipo de prioridade com este nome.');
                }
            }
        }
    }

    private function validarStrDescricao(TipoPrioridadeDTO $objTipoPrioridadeDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objTipoPrioridadeDTO->getStrDescricao())) {
            $objTipoPrioridadeDTO->setStrDescricao(null);
        } else {
            $objTipoPrioridadeDTO->setStrDescricao(trim($objTipoPrioridadeDTO->getStrDescricao()));

            if (strlen($objTipoPrioridadeDTO->getStrDescricao()) > 500) {
                $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 500 caracteres.');
            }
        }
    }

    private function validarStrSinAtivo(TipoPrioridadeDTO $objTipoPrioridadeDTO, InfraException $objInfraException)
    {
        if (InfraString::isBolVazia($objTipoPrioridadeDTO->getStrSinAtivo())) {
            $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
        } else {
            if (!InfraUtil::isBolSinalizadorValido($objTipoPrioridadeDTO->getStrSinAtivo())) {
                $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
            }
        }
    }

    protected function cadastrarControlado(TipoPrioridadeDTO $objTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_cadastrar',__METHOD__,$objTipoPrioridadeDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $this->validarStrNome($objTipoPrioridadeDTO, $objInfraException);
            $this->validarStrDescricao($objTipoPrioridadeDTO, $objInfraException);
            $this->validarStrSinAtivo($objTipoPrioridadeDTO, $objInfraException);

            $objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            $ret = $objTipoPrioridadeBD->cadastrar($objTipoPrioridadeDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro cadastrando Tipo de Prioridade.', $e);
        }
    }

    protected function alterarControlado(TipoPrioridadeDTO $objTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_alterar',__METHOD__,$objTipoPrioridadeDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            if ($objTipoPrioridadeDTO->isSetStrNome()) {
                $this->validarStrNome($objTipoPrioridadeDTO, $objInfraException);
            }
            if ($objTipoPrioridadeDTO->isSetStrDescricao()) {
                $this->validarStrDescricao($objTipoPrioridadeDTO, $objInfraException);
            }
            if ($objTipoPrioridadeDTO->isSetStrSinAtivo()) {
                $this->validarStrSinAtivo($objTipoPrioridadeDTO, $objInfraException);
            }

            $objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            $objTipoPrioridadeBD->alterar($objTipoPrioridadeDTO);
        } catch (Exception $e) {
            throw new InfraException('Erro alterando Tipo de Prioridade.', $e);
        }
    }

    protected function excluirControlado($arrObjTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_excluir',__METHOD__,$arrObjTipoPrioridadeDTO);

            //Regras de Negocio
            $objInfraException = new InfraException();

            $objProcessoRN = new ProcedimentoRN();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjTipoPrioridadeDTO); $i++) {
                $numIdTipoPrioridade = $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade();
                $objProcessoDTO = new ProcedimentoDTO();
                $objProcessoDTO->retDblIdProcedimento();
                $objProcessoDTO->retNumIdTipoPrioridade();
                $objProcessoDTO->setBolExclusaoLogica(false);
                $objProcessoDTO->setNumIdTipoPrioridade($numIdTipoPrioridade);
                $qtdProcessos = $objProcessoRN->contarRN0279($objProcessoDTO);
                if($qtdProcessos > 0){
                    $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
                    $objTipoPrioridadeDTO->retStrNome();
                    $objTipoPrioridadeDTO->setNumIdTipoPrioridade($numIdTipoPrioridade);
                    $objTipoPrioridadeDTO = $objTipoPrioridadeBD->consultar($objTipoPrioridadeDTO);
                    if ($qtdProcessos == 1) {
                        $objInfraException->adicionarValidacao('Existe 1 Processo utilizando o Tipo de Prioridade ' . $objTipoPrioridadeDTO->getStrNome() . '.');
                    } else {
                        $objInfraException->adicionarValidacao('Existem ' . $qtdProcessos . ' Processos utilizando o Tipo de Prioridade ' . $objTipoPrioridadeDTO->getStrNome() . '.');
                    }
                }else{
                    $objTipoPrioridadeBD->excluir($arrObjTipoPrioridadeDTO[$i]);
                }
            }
            $objInfraException->lancarValidacoes();
        } catch (Exception $e) {
            throw new InfraException('Erro excluindo Tipo de Prioridade.', $e);
        }
    }

    protected function consultarConectado(TipoPrioridadeDTO $objTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_consultar',__METHOD__,$objTipoPrioridadeDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());

            /** @var TipoPrioridadeDTO $ret */
            $ret = $objTipoPrioridadeBD->consultar($objTipoPrioridadeDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro consultando Tipo de Prioridade.', $e);
        }
    }

    protected function listarConectado(TipoPrioridadeDTO $objTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_listar',__METHOD__,$objTipoPrioridadeDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());

            /** @var TipoPrioridadeDTO[] $ret */
            $ret = $objTipoPrioridadeBD->listar($objTipoPrioridadeDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro listando Tipos de Prioridades.', $e);
        }
    }

    protected function contarConectado(TipoPrioridadeDTO $objTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_listar',__METHOD__,$objTipoPrioridadeDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            $ret = $objTipoPrioridadeBD->contar($objTipoPrioridadeDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro contando Tipos de Prioridades.', $e);
        }
    }

    protected function desativarControlado($arrObjTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_desativar',__METHOD__,$arrObjTipoPrioridadeDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjTipoPrioridadeDTO); $i++) {
                $objTipoPrioridadeBD->desativar($arrObjTipoPrioridadeDTO[$i]);
            }
        } catch (Exception $e) {
            throw new InfraException('Erro desativando Tipo de Prioridade.', $e);
        }
    }

    protected function reativarControlado($arrObjTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_reativar',__METHOD__,$arrObjTipoPrioridadeDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            for ($i = 0; $i < count($arrObjTipoPrioridadeDTO); $i++) {
                $objTipoPrioridadeBD->reativar($arrObjTipoPrioridadeDTO[$i]);
            }
        } catch (Exception $e) {
            throw new InfraException('Erro reativando Tipo de Prioridade.', $e);
        }
    }

    protected function bloquearControlado(TipoPrioridadeDTO $objTipoPrioridadeDTO)
    {
        try {
          SessaoSEI::getInstance()->validarAuditarPermissao('tipo_prioridade_consultar',__METHOD__,$objTipoPrioridadeDTO);

            //Regras de Negocio
            //$objInfraException = new InfraException();

            //$objInfraException->lancarValidacoes();

            $objTipoPrioridadeBD = new TipoPrioridadeBD($this->getObjInfraIBanco());
            $ret = $objTipoPrioridadeBD->bloquear($objTipoPrioridadeDTO);

            return $ret;
        } catch (Exception $e) {
            throw new InfraException('Erro bloqueando Tipo de Prioridade.', $e);
        }
    }


}
