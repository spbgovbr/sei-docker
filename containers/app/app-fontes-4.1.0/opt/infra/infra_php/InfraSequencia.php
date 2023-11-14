<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/05/2006 - criado por MGA
 *
 * @package infra_php
 */

/*

CREATE TABLE infra_sequencia
(
nome_tabela  VARCHAR(30)  NOT NULL ,
qtd_incremento  bigint  NOT NULL ,
num_atual  bigint  NOT NULL ,
num_maximo  bigint  NOT NULL
);


ALTER TABLE infra_sequencia
ADD CONSTRAINT  pk_infra_sequencia PRIMARY KEY (nome_tabela);

*/

class InfraSequencia extends InfraRN
{

    public function __construct(InfraIBanco $objInfraIBanco)
    {
        BancoInfra::setObjInfraIBanco($objInfraIBanco);
    }

    protected function inicializarObjInfraIBanco()
    {
        return BancoInfra::getInstance();
    }

    protected function obterProximaSequenciaControlado($strTabela)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraSequencia->obterProximaSequencia] ' . $strTabela);
        }

        $objInfraSequenciaRN = new InfraSequenciaRN();

        if (BancoInfra::getInstance() instanceof InfraSqlServer) {
            $objInfraSequenciaDTO = new InfraSequenciaDTO();
            $objInfraSequenciaDTO->retDblNumMaximo();
            $objInfraSequenciaDTO->setStrNome($strTabela);

            $objInfraSequenciaDTO = $objInfraSequenciaRN->consultar($objInfraSequenciaDTO);

            if ($objInfraSequenciaDTO == null) {
                throw new InfraException('Sequência ' . $strTabela . ' não encontrada.');
            }

            $sql = 'update infra_sequencia set num_atual = num_atual + qtd_incremento OUTPUT Inserted.num_atual where nome_tabela = ' . BancoInfra::getInstance(
                )->formatarGravacaoStr($strTabela);
            $rsProx = BancoInfra::getInstance()->consultarSql($sql);

            if ($rsProx[0]['num_atual'] > $objInfraSequenciaDTO->getDblNumMaximo()) {
                throw new InfraException('Sequência ' . $strTabela . ' tentou ultrapassar o valor máximo.');
            }

            return $rsProx[0]['num_atual'];
        } else {
            $objInfraSequenciaDTO = new InfraSequenciaDTO();
            $objInfraSequenciaDTO->retDblNumAtual();
            $objInfraSequenciaDTO->retDblQtdIncremento();
            $objInfraSequenciaDTO->retDblNumMaximo();
            $objInfraSequenciaDTO->setStrNome($strTabela);
            $objInfraSequenciaDTO = $objInfraSequenciaRN->bloquear($objInfraSequenciaDTO);

            if ($objInfraSequenciaDTO == null) {
                throw new InfraException('Sequência ' . $strTabela . ' não encontrada.');
            }

            $numProxSeq = $objInfraSequenciaDTO->getDblNumAtual() + $objInfraSequenciaDTO->getDblQtdIncremento();

            if ($numProxSeq > $objInfraSequenciaDTO->getDblNumMaximo()) {
                throw new InfraException('Sequência ' . $strTabela . ' tentou ultrapassar o valor máximo.');
            }

            $objInfraSequenciaDTO = new InfraSequenciaDTO();
            $objInfraSequenciaDTO->setDblNumAtual($numProxSeq);
            $objInfraSequenciaDTO->setStrNome($strTabela);
            $objInfraSequenciaRN->alterar($objInfraSequenciaDTO);

            return $numProxSeq;
        }
    }

    public function verificarSequencia($strTabela)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraSequencia->verificarSequencia] ' . $strTabela);
        }

        $objInfraSequenciaDTO = new InfraSequenciaDTO();
        $objInfraSequenciaDTO->retDblNumAtual();
        $objInfraSequenciaDTO->setStrNome($strTabela);

        $objInfraSequenciaRN = new InfraSequenciaRN();
        $objInfraSequenciaDTO = $objInfraSequenciaRN->consultar($objInfraSequenciaDTO);

        return ($objInfraSequenciaDTO != null);
    }

    public function reiniciarSequencia($strTabela, $numAtual = 0)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraSequencia->reiniciarSequencia] ' . $strTabela);
        }

        if (!$this->verificarSequencia($strTabela)) {
            throw new InfraException('Sequência ' . $strTabela . ' não encontrada.');
        }

        $objInfraSequenciaDTO = new InfraSequenciaDTO();
        $objInfraSequenciaDTO->setDblNumAtual($numAtual);
        $objInfraSequenciaDTO->setStrNome($strTabela);

        $objInfraSequenciaRN = new InfraSequenciaRN();
        $objInfraSequenciaRN->alterar($objInfraSequenciaDTO);
    }

    public function criarSequencia($strTabela, $numIncremento, $numAtual, $numMaximo)
    {
        if (InfraDebug::isBolProcessar()) {
            InfraDebug::getInstance()->gravarInfra('[InfraSequencia->criarSequencia] ' . $strTabela);
        }

        if ($this->verificarSequencia($strTabela)) {
            throw new InfraException('Sequência ' . $strTabela . ' já existe.');
        }

        $objInfraSequenciaDTO = new InfraSequenciaDTO();
        $objInfraSequenciaDTO->setDblNumAtual($numAtual);
        $objInfraSequenciaDTO->setDblQtdIncremento($numIncremento);
        $objInfraSequenciaDTO->setDblNumMaximo($numMaximo);
        $objInfraSequenciaDTO->setStrNome($strTabela);

        $objInfraSequenciaRN = new InfraSequenciaRN();
        $objInfraSequenciaRN->cadastrar($objInfraSequenciaDTO);
    }
}

