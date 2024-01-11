<?php
/**
 * 23/05/06
 * @package infra_php
 */

class InfraMenuXML
{
    public $parser = "";
    public $valor = false;
    public $vetor = array();
    public $nivel = "";
    public $id = "";
    public $destino = "";
    public $titulo = "";

    //VERIFICA SE HÁ DADOS A SEREM ARMAZENADOS
    function tagAbrir($parser, $name, $attrs)
    {
        //ARMAZENA O NÍVEL DO MENU
        if (substr($name, 0, 5) == "NIVEL") {
            $this->nivel = substr($name, 5, 1);
        }
        //RECUPERA OS DADOS E OS ATRIBUTOS
        if (substr($name, 0, 4) == "ITEM") {
            foreach ($attrs as $key => $value) {
                switch ($key) {
                    case "ID":
                        $this->id = $value;
                        break;
                    case "DESTINO":
                        $this->destino = $value;
                        break;
                    case "TITULO":
                        $this->titulo = $value;
                        break;
                }
            }
            $this->valor = true;
        }
    }

    //NADA PARA FAZER AO FECHAR AS TAGS
    function tagFechar($parser, $name)
    {
    }

    //ADICIONA MAIS UM ITEM AO VETOR
    function adicionarItem($parser, $data)
    {
        if ($this->valor) {
            $this->vetor[0][] = $this->nivel;
            $this->vetor[1][] = $this->id;
            $this->vetor[2][] = $this->destino;
            $this->vetor[3][] = $this->titulo;
            $this->vetor[4][] = $data;
            $this->valor = false;
            $this->id = "";
            $this->destino = "";
            $this->titulo = "";
        }
    }

    //BUSCA INFORMAÇÕES SOBRE O PAI DE UM ITEM
    function determinarPai($id, $vetor, $posicao)
    {
        $resultado = array(3);
        $posicao_pai = 0;
        while ($posicao >= 0) {
            if ($vetor[2][$posicao] == $id) {
                //ACHOU O PAI: GUARDA AS INFORMAÇÕES DELE PARA REPASSAR À FUNÇÃO CHAMADORA
                $resultado[1] = $posicao;
                $resultado[2] = $vetor[1][$posicao];
                for ($i = $posicao - 1; $i >= 0; $i--) {
                    if ($resultado[2] == $vetor[1][$i]) {
                        //HÁ ITENS ACIMA DELE COM MESMO ID: O PAI NÃO É O ÚNICO COM AQUELE ID
                        $posicao_pai++;
                    }
                }
                $resultado[0] = $posicao_pai;
                return $resultado;
            }
            $posicao--;
        }
    }

    //CALCULA O DESLOCAMENTO VERTICAL DO MENU
    function deslocarItem($id, $vetor, $posicao)
    {
        $linhas = -1;
        //O WHILE ANDA PARA CIMA NO VETOR ATÉ CHEGAR NO PAI RAIZ
        while ($vetor[0][$posicao] != 1) {
            //A POSIÇÃO DO FILHO NA TELA DEPENDE DE QUANTOS IGUAIS AO PAI ESTÃO ACIMA DELE
            $resultado_determinar_pai = $this->determinarPai($id, $vetor, $posicao);
            $linhas += $resultado_determinar_pai[0];
            $posicao = $resultado_determinar_pai[1];
            $id = $resultado_determinar_pai[2];
        }
        //A LINHA DO FILHO PRECISA SER SOMADA COM A DO PAI RAIZ
        $linhas += substr($vetor[1][$posicao], 3, 1);
        //21px É A ALTURA DE CADA LINHA DO MENU (20px DA ALTURA DA CÉLULA MAIS 1px DA LINHA PONTILHADA)
        return $linhas * 21;
    }

    //EXECUTA O PARSE DOS DADOS XML
    function parse($dados)
    {
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($this->parser, "tagAbrir", "tagFechar");
        xml_set_character_data_handler($this->parser, "adicionarItem");
        if (!xml_parse($this->parser, $dados)) {
            echo "<b>Erro na interpretação do XML: " .
                xml_get_error_code($this->parser) . " - " .
                xml_error_string(xml_get_error_code($this->parser)) . " (linha " .
                xml_get_current_line_number($this->parser) . ")</b>";
            die;
        }
        xml_parser_free($this->parser);
        return $this->vetor;
    }
}

