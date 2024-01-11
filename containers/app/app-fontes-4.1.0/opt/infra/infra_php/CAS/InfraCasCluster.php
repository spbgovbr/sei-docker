<?php

require_once("InfraCasNode.php");

class InfraCasCluster
{
    private $nodes = array();
    var $nodeindex = -1;
    var $selectedNode;

    /**
     * Construtor indicando a URL para este nó assim como o timeout (minutos) antes de tentar de tirar do estado de falha.
     *
     * @param InfraCasNode $nodes Array de 'InfraCasNode' para este cluster
     *
     **/
    function __construct(array $nodes)
    {
        $this->nodes = $nodes;
        $this->shuffle();
    }

    /**
     * Tenta obter o próximo nó disponível neste cluster
     *
     * @return InfraCasNode Retorna um InfraCasNode, caso não existam mais nós ativos retorna 'null'.
     *
     **/
    private function nextNode()
    {
        $try = 0;
        $count = count($this->nodes);

        do {
            $this->nodeindex += 1;
            if ($this->nodeindex >= $count) {
                $this->nodeindex = 0;
            }

            $cnode = $this->nodes[$this->nodeindex];

            if ($cnode->isValid()) {
                return $cnode;
            }
        } while (++$try < $count);

        return null;
    }

    /**
     * Retorna o nó atual para ser utilizado nas operações desta API
     *
     * @return InfraCasNode Retorna um InfraCasNode, caso não existam mais nós ativos retorna 'null'.
     *
     **/
    function getNode()
    {
        if ((!empty($this->selectedNode)) && ($this->selectedNode->active)) {
            return $this->selectedNode;
        }

        $this->selectedNode = $this->nextNode();

        if (empty($this->selectedNode)) {
            return null;
        }

        return $this->selectedNode;
    }

    /**
     * Sinaliza que houve um erro de conexão no nó atual.
     *
     **/
    function failNode()
    {
        if (empty($this->selectedNode)) {
            return;
        }

        $this->selectedNode->fail();
    }

    /**
     * Executa uma operação de ordernar de forma aleatória os nós deste cluster para evitar acessos sequenciais ao mesmo nó físico do Swarm
     *
     **/
    function shuffle()
    {
        if (isset($this->nodes)) {
            shuffle($this->nodes);
        }
    }

    public function getActiveNodes()
    {
        $arrStrActiveNodes = array();

        foreach ($this->nodes as $node) {
            if ((!empty($node)) && ($node->active)) {
                $arrStrActiveNodes[] = $node;
            }
        }
        return $arrStrActiveNodes;
    }
}
