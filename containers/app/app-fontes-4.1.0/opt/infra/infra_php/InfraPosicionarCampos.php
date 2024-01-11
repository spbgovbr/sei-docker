<?php

/**
 * @package infra_php
 *
 */
class InfraPosicionarCampos
{
    //os campos conforme foram informados no lançamento
    private static $POS_ID_CAMPO_SUPERIOR = 0;
    private static $POS_ID_CAMPO_INFERIOR = 1;
    private static $POS_BOL_VISIVEL_SUPERIOR = 2;
    private static $POS_BOL_VISIVEL_INFERIOR = 3;
    private static $POS_CSS_ADICIONAL_SUPERIOR = 4;
    private static $POS_CSS_ADICIONAL_INFERIOR = 5;
    private static $POS_PERCENTUAL_HORIZONTAL_SUPERIOR = 6;
    private static $POS_PERCENTUAL_HORIZONTAL_INFERIOR = 7;
    private static $POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR = 8;
    private static $POS_ID_CAMPO_COMPLEMENTAR_INFERIOR = 9;
    private static $POS_BOL_FORCAR_NOVA_LINHA = 10;
    private static $POS_LINHAS_BRANCAS_ABAIXO = 11;
    private static $POS_DISTANCIA_VERTICAL_INFERIOR = 12;
    private static $POS_BOL_LINHA_SIMPLES = 13;

    private $dblPercentualHorizontalMaximo; //qual a ocupação máxima (percentual) da DIV permitida
    private $dblPercentualHorizontalDefault; //o percentual horizontal default da tela ocupado por cada campo (pode mudar em cada caso)
    private $dblPercentualVerticalLocalCampoInferiorDefault; //o percentual da linha a partir do qual deve ser posicionado o campo inferior (0 = sobre o campo superior, 100 = na linha de baixo)
    private $dblPercentualEspacoEntreCampos; //o percentual da linha que fica de espaço entre os campos
    private $dblQuantidadeLinhas; //a quantidade de linhas armazenadas dentro do objeto (atualizado quando é executado o método "obterTabelaEstruturada")

    private $arrCamposEstrutura; //array de pares de campos (todos os informados, na ordem em que foram informados)
    private $arrLinhasSimples; //marcação de quais linhas são simples e quais são duplas (linha simples conta como "meia linha" , pois o padrão é que sejam duplas
    private $arrCamposExtra; //arrayd e campos que não estão na estrutura, mas terão seu CSS definido por aqui; formato: array(id_campo1=>CSS1, id_campo2=>CSS2...)


    public function __construct()
    {
        $this->dblPercentualHorizontalMaximo = 95;
        $this->dblPercentualHorizontalDefault = 25;
        $this->dblPercentualVerticalLocalCampoInferiorDefault = 40;
        $this->dblPercentualEspacoEntreCampos = 3;
        $this->dblQuantidadeLinhas = 0;

        $this->arrCamposEstrutura = array();
        $this->arrLinhasSimples = array();
        $this->arrCamposExtra = array();
    }

    public function getDblQuantidadeLinhas()
    {
        $this->obterTabelaEstruturada(
        ); //roda a análise estrutural apenas para atualizar a quantidade de linhas no objeto
        return $this->dblQuantidadeLinhas;
    }

    public function getDblPercentualHorizontalDefault()
    {
        return $this->dblPercentualHorizontalDefault;
    }

    public function setDblPercentualHorizontalDefault($dblPercentualHorizontalDefault)
    {
        $this->dblPercentualHorizontalDefault = $dblPercentualHorizontalDefault;
    }

    public function getDblPercentualEspacoEntreCampos()
    {
        return $this->dblPercentualEspacoEntreCampos;
    }

    public function setDblPercentualEspacoEntreCampos($dblPercentualEspacoEntreCampos)
    {
        $this->dblPercentualEspacoEntreCampos = $dblPercentualEspacoEntreCampos;
    }

    /**
     * Devolve tabela estruturada com base nos atributos de cada campo
     * A estrutura consiste na posição de cada par de campos dentro da linha, bem como na inserção das linhas em branco necessárias
     * IMPORTANTE: os campos invisíveis serão colocados junto nessa estrutura. Caso o par inteiro seja invisível, ele apenas não influenciará na análise horizontal da linha (e será tratado no momento adequado)
     * IMPORTANTE: este método é que atualiza a quantidade de linhas dentro do objeto
     * @param boolean $bolRetornarCssAdicional - se o código retornado deve incluir o CSS adicional ou apenas a parte relativa ao posicionamento
     * @return array - tabela estruturada contendo os campos em suas posições finais e as linhas em branco
     */
    private function obterTabelaEstruturada()
    {
        //DESCOBRE A QUANTIDADE TOTAL DE LINHAS E MONTA ESTRUTURA BÁSICA
        $numLinhasEmBranco = 0; //a quantidade de linhas em branco que devem ser acrescentadas depois que a próxima linha terminar
        $dblEspacoDisponivel = $this->dblPercentualHorizontalMaximo;

        $arrTabelaEstruturada = array(); //o resultado final
        $arrCamposLinha = array();

        //ADICIONA CAMPOS PROGRESSIVAMENTE
        foreach ($this->arrCamposEstrutura as $arrAtributos) {
            //descobre o espaço horizontal necessário para os campos
            $dblEspacoNecessario = bccomp(
                $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR],
                $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                2
            ) > 0 ? $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR] : $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR];

            //verifica se é possível adicionar linha na tabela estruturada
            if (bccomp($dblEspacoDisponivel, $dblEspacoNecessario, 2) >= 0) { //se há espaço disponível, faz
                //ADICIONA CAMPOS NA LINHA CORRENTE
                //diminui o espaço disponível na linha (desconta campo e espaço entre campos)
                $dblEspacoDisponivel = bcsub($dblEspacoDisponivel, $dblEspacoNecessario, 2);
                $dblEspacoDisponivel = bcsub($dblEspacoDisponivel, $this->dblPercentualEspacoEntreCampos, 2);

                //adiciona os campos
                $arrCamposLinha[] = $arrAtributos;

                //atualiza a quantidade de linhas brancas adicionais (se necessário)
                $numLinhasEmBranco = max($numLinhasEmBranco, $arrAtributos[self::$POS_LINHAS_BRANCAS_ABAIXO]);
            } else { //senão, fecha a linha atual e inicia outra
                //ADICIONA A LINHA ANTIGA
                //adiciona linha na tabela estruturada
                $arrTabelaEstruturada[] = $arrCamposLinha;

                //adiciona linhas em branco
                for ($i = $numLinhasEmBranco; $i--; $i > 0) {
                    $arrTabelaEstruturada[] = array();
                }

                //reseta espaço disponível
                $dblEspacoDisponivel = $this->dblPercentualHorizontalMaximo;

                //ADICIONA CAMPOS NA NOVA LINHA
                //zera linha
                $arrCamposLinha = array();

                //adiciona os campos
                $arrCamposLinha[] = $arrAtributos;

                //diminui o espaço disponível na linha (desconta campo e espaço entre campos)
                $dblEspacoDisponivel = bcsub($dblEspacoDisponivel, $dblEspacoNecessario, 2);
                $dblEspacoDisponivel = bcsub($dblEspacoDisponivel, $this->dblPercentualEspacoEntreCampos, 2);

                //atualiza a quantidade de linhas brancas adicionais (se necessário)
                $numLinhasEmBranco = $arrAtributos[self::$POS_LINHAS_BRANCAS_ABAIXO];
            }

            //se marcado para encerrar a linha, zera espaço disponível (vai adicionar efetivamente na próxima iteração)
            if ($arrAtributos[self::$POS_BOL_FORCAR_NOVA_LINHA]) {
                $dblEspacoDisponivel = 0;
            }
        }

        //INSERE OS ÚLTIMOS CAMPOS E LINHAS EM BRANCO
        //adiciona linha na tabela estruturada
        $arrTabelaEstruturada[] = $arrCamposLinha;

        //adiciona linhas em branco
        for ($i = $numLinhasEmBranco; $i--; $i > 0) {
            $arrTabelaEstruturada[] = array();
        }

        //SETA A QUANTIDADE DE LINHAS DA ESTRUTURA
        //descobre as linhas simples
        $this->dblQuantidadeLinhas = 0; //inicializa quantidade de linhas
        $this->arrLinhasSimples = array(); //inicializa controle de linhas simples
        foreach ($arrTabelaEstruturada as $numLinha => $arrCamposLinha) {
            if (count($arrCamposLinha) == 0) { //se for linha em branco, é dupla
                $this->dblQuantidadeLinhas = bcadd($this->dblQuantidadeLinhas, 1, 1); //adiciona linha como dupla
                $this->arrLinhasSimples[$numLinha] = false;
                continue;
            } else { //se não for linha em branco, faz
                foreach ($arrCamposLinha as $arrAtributosCampo) {
                    if (!$arrAtributosCampo[self::$POS_BOL_LINHA_SIMPLES]) { //encontrou um elemento de linha dupla
                        $this->arrLinhasSimples[$numLinha] = false;
                        $this->dblQuantidadeLinhas = bcadd(
                            $this->dblQuantidadeLinhas,
                            1,
                            1
                        ); //adiciona linha como dupla
                        break;
                    }
                }
                if (!isset($this->arrLinhasSimples[$numLinha])) {
                    $this->arrLinhasSimples[$numLinha] = true; //se não setou como dupla, então é simples
                    $this->dblQuantidadeLinhas = bcadd(
                        $this->dblQuantidadeLinhas,
                        0.5,
                        1
                    ); //adiciona linha como simples
                }
            }
        }

        return $arrTabelaEstruturada;
    }

    /**
     * Analisa a estrutura de campos e devolve uma listagem contendo a posição de cada campo, bem como a largura e os demais atributos
     * Depois analisa os campos-extra (fora da estrutura) e adiciona-os na estrutura
     * @param boolean $bolRetornarCssAdicional - se o código retornado deve incluir o CSS adicional ou apenas a parte relativa ao posicionamento
     * @return array - lista contendo o nome do campo como chave e os atributos a serem setados como valores; formato = array(campo1 => array(nome_atributo1 => valor_atributo1, nome_atributo2 => valor_atributo2 ...), campo2 => array(...))
     */
    private function listarValoresCampos()
    {
        $arrResultado = array(); //o resultado final (uma lista de campos e seus atributos finais)

        //obtém estrutura a ser analisada
        $arrTabelaEstruturada = $this->obterTabelaEstruturada();

        //descobre o percentual adequado a cada linha (default)
        $dblPercentuaTamanholLinhaDupla = bcdiv('100', $this->dblQuantidadeLinhas, 2); //100% / quantidade de linhas
        $dblPercentuaTamanholLinhaSimples = bcdiv(
            $dblPercentuaTamanholLinhaDupla,
            2,
            2
        ); //(100% / quantidade de linhas) / 2

        //a descida vertical acumulada (i.e., o início do campo na vertical)
        $dblInicioVertical = 0;

        //ANALISA CADA LINHA
        foreach ($arrTabelaEstruturada as $numLinha => $arrCamposLinha) {
            //define o tamanho percentual desta linha a partir da análise se é dupla ou simples
            $dblPercentuaTamanholLinha = ($this->arrLinhasSimples[$numLinha]) ? $dblPercentuaTamanholLinhaSimples : $dblPercentuaTamanholLinhaDupla;
            $dblInicioHorizontal = 0; //o início horizontal dos próximos campos

            foreach ($arrCamposLinha as $arrAtributosCampo) {
                //ANALISA O CAMPO SUPERIOR
                if (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_SUPERIOR])) { //se foi setado o campo superior, faz
                    if (!$arrAtributosCampo[self::$POS_BOL_VISIVEL_SUPERIOR]) { //se for invisível
                        $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_SUPERIOR]] = array('display' => 'none');
                        //se possui elemento complementar, torna-o invisível
                        if (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR])) {
                            $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR]] = array('display' => 'none');
                        }
                    } else { //se for visível
                        //acumula atributos CSS do campo
                        $arrResultadoAtributosCssCampo = array();

                        //lança posições, largura e visibilidade
                        $arrResultadoAtributosCssCampo['display'] = 'block';
                        $arrResultadoAtributosCssCampo['left'] = $dblInicioHorizontal . '%';
                        $arrResultadoAtributosCssCampo['top'] = $dblInicioVertical . '%';

                        $strLargura = (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR])) ? bcsub(
                            $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR],
                            2,
                            2
                        ) : $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR];

                        $arrResultadoAtributosCssCampo['width'] = $strLargura . '%';

                        //lança CSS complementar no array de atributos CSS do campo (faz por último, para poder sobrescrever um atributo calculado, se quiser)
                        if (!InfraString::isBolVazia(trim($arrAtributosCampo[self::$POS_CSS_ADICIONAL_SUPERIOR]))) {
                            $arrAtributosCssCampo = explode(
                                ';',
                                trim($arrAtributosCampo[self::$POS_CSS_ADICIONAL_SUPERIOR])
                            );
                            foreach ($arrAtributosCssCampo as $strAtributoCssCampo) {
                                $arrDadosAtributoCssCampo = explode(':', trim($strAtributoCssCampo));
                                $arrResultadoAtributosCssCampo[trim(
                                    $arrDadosAtributoCssCampo[0]
                                )] = $arrDadosAtributoCssCampo[1];
                            }
                        }

                        //adiciona na lista de resultados
                        $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_SUPERIOR]] = $arrResultadoAtributosCssCampo;

                        //se houver campo complementar, faz
                        if (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR])) {
                            //descobre o inicio horizontal do complemento
                            $dblInicioHorizontalComplemento = bcadd(
                                $dblInicioHorizontal,
                                $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                                2
                            );
                            $dblInicioHorizontalComplemento = bcsub($dblInicioHorizontalComplemento, 1, 2);

                            $arrResultadoAtributosCssCampo = array();
                            $arrResultadoAtributosCssCampo['display'] = 'block';
                            $arrResultadoAtributosCssCampo['left'] = $dblInicioHorizontalComplemento . '%'; //posição + largura do original - 1%
                            $arrResultadoAtributosCssCampo['top'] = $dblInicioVertical . '%';
                            $arrResultadoAtributosCssCampo['position'] = 'absolute';

                            //adiciona na lista de resultados
                            $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR]] = $arrResultadoAtributosCssCampo;
                        }
                    }
                }

                //ANALISA O CAMPO INFERIOR
                if (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_INFERIOR])) { //se foi setado o campo superior, faz
                    if (!$arrAtributosCampo[self::$POS_BOL_VISIVEL_INFERIOR]) { //se for invisível
                        $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_INFERIOR]] = array('display' => 'none');
                        //se possui elemento complementar, torna-o invisível
                        if (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR])) {
                            $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR]] = array('display' => 'none');
                        }
                    } else { //se for visível
                        //acumula atributos CSS do campo
                        $arrResultadoAtributosCssCampo = array();

                        //lança posições, largura e visibilidade
                        $arrResultadoAtributosCssCampo['display'] = 'block';
                        $arrResultadoAtributosCssCampo['left'] = $dblInicioHorizontal . '%';

                        $dblAjusteVertical = bcdiv(
                            $arrAtributosCampo[self::$POS_DISTANCIA_VERTICAL_INFERIOR],
                            100,
                            2
                        ); //transforma "60%" em "0,6"
                        $dblAjusteVertical = bcmul(
                            $dblPercentuaTamanholLinha,
                            $dblAjusteVertical,
                            2
                        ); //descobre quanto é o percentual em relação ao tamanho da linha
                        $arrResultadoAtributosCssCampo['top'] = bcadd(
                                $dblInicioVertical,
                                $dblAjusteVertical,
                                2
                            ) . '%'; //adiciona o ajuste sobre o top original (início da linha)

                        $strLargura = (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR])) ? bcsub(
                            $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                            2,
                            2
                        ) : $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR];
                        $strLargura = (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR])) ? bcsub(
                            $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                            2,
                            2
                        ) : $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR];
                        $arrResultadoAtributosCssCampo['width'] = $strLargura . '%';

                        //lança CSS complementar no array de atributos CSS do campo
                        if (!InfraString::isBolVazia(trim($arrAtributosCampo[self::$POS_CSS_ADICIONAL_INFERIOR]))) {
                            $arrAtributosCssCampo = explode(
                                ';',
                                trim($arrAtributosCampo[self::$POS_CSS_ADICIONAL_INFERIOR])
                            );

                            foreach ($arrAtributosCssCampo as $strAtributoCssCampo) {
                                $arrDadosAtributoCssCampo = explode(':', trim($strAtributoCssCampo));
                                $arrResultadoAtributosCssCampo[trim(
                                    $arrDadosAtributoCssCampo[0]
                                )] = $arrDadosAtributoCssCampo[1];
                            }
                        }

                        //adiciona na lista de resultados
                        $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_INFERIOR]] = $arrResultadoAtributosCssCampo;

                        //se houver campo complementar, faz
                        if (!empty($arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR])) {
                            //descobre o inicio horizontal do complemento
                            $dblInicioHorizontalComplemento = bcadd(
                                $dblInicioHorizontal,
                                $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                                2
                            );
                            $dblInicioHorizontalComplemento = bcsub($dblInicioHorizontalComplemento, 1, 2);

                            $arrResultadoAtributosCssCampo = array();
                            $arrResultadoAtributosCssCampo['display'] = 'block';
                            $arrResultadoAtributosCssCampo['left'] = $dblInicioHorizontalComplemento . '%'; //posição + largura do original - 1%
                            $arrResultadoAtributosCssCampo['top'] = bcadd(
                                    $dblInicioVertical,
                                    $dblAjusteVertical,
                                    2
                                ) . '%'; //adiciona o ajuste sobre o top original (início da linha)
                            $arrResultadoAtributosCssCampo['position'] = 'absolute';

                            //adiciona na lista de resultados
                            $arrResultado[$arrAtributosCampo[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR]] = $arrResultadoAtributosCssCampo;
                        }
                    }
                }

                //ajusta o início horizontal
                $dblMaiorLargura = bccomp(
                    $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR],
                    $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                    2
                ) > 0 ? $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR] : $arrAtributosCampo[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR];
                if (bccomp($dblMaiorLargura, 0, 2) > 0) { //se algum dos campos era visível (largura > 0), faz
                    $dblInicioHorizontal = bcadd($dblInicioHorizontal, $dblMaiorLargura);
                    $dblInicioHorizontal = bcadd($dblInicioHorizontal, $this->dblPercentualEspacoEntreCampos);
                }
            }

            //terminou de inserir linha ajusta o início vertical para a próxima (somando o tamanho da linha atual no total percorrido)
            $dblInicioVertical = bcadd($dblInicioVertical, $dblPercentuaTamanholLinha, 2);
        }

        //PROCESSA CAMPOS-EXTRA (FORA DA ESTRUTURA)
        foreach ($this->arrCamposExtra as $strIdCampo => $strCssCampo) {
            $arrAtributosCssCampo = explode(';', trim($strCssCampo));
            $arrResultadoAtributosCssCampo = array();
            foreach ($arrAtributosCssCampo as $strAtributoCssCampo) {
                $arrDadosAtributoCssCampo = explode(':', trim($strAtributoCssCampo));
                $arrResultadoAtributosCssCampo[trim($arrDadosAtributoCssCampo[0])] = $arrDadosAtributoCssCampo[1];
            }
            $arrResultado[$strIdCampo] = $arrResultadoAtributosCssCampo;
        }

        return $arrResultado;
    }

    /**
     * Imprime o código relativo ao CSS resultante da estruturação dos campos armazenados
     * @return string
     */
    public function obterCss()
    {
        //busca campos e atributos (inclui os da extrutura e os extra, fora da estrutura)
        $arrCamposAtributos = $this->listarValoresCampos();

        //cria resultado CSS dos campos da estrutura
        $strResultado = '';
        foreach ($arrCamposAtributos as $strIdCampo => $arrAtributosCampo) {
            //consolida pares de atributos
            $arrStrParesAtributos = array();
            foreach ($arrAtributosCampo as $strChave => $strValor) {
                $arrStrParesAtributos[] = $strChave . ':' . $strValor;
            }
            //associa campo e atributos
            $strResultado .= '     #' . $strIdCampo . ' {' . implode('; ', $arrStrParesAtributos) . '}' . "\r\n";
        }

        //retorna resultado
        return $strResultado;
    }

    /**
     * Imprime o código relativo aos comandos jQuery que resultam na estruturação dos campos armazenados (e aplicação do CSS complementar)
     * @return string
     */
    public function obterJquery()
    {
        //busca campos e atributos (inclui os da extrutura e os extra, fora da estrutura)
        $arrCamposAtributos = $this->listarValoresCampos();

        //cria resultado jQuery
        $strResultado = '';
        foreach ($arrCamposAtributos as $strIdCampo => $arrAtributosCampo) {
            //consolida pares de atributos
            $arrStrParesAtributos = array();
            foreach ($arrAtributosCampo as $strChave => $strValor) {
                $arrStrParesAtributos[] = '\'' . $strChave . '\':\'' . $strValor . '\'';
            }
            //associa campo e atributos
            $strResultado .= '     $("#' . $strIdCampo . '").css({' . implode(
                    ', ',
                    $arrStrParesAtributos
                ) . '});' . "\r\n";
        }

        //retorna resultado
        return $strResultado;
    }

    /**
     * Adiciona campo na estrutura
     * @param string $strIdCampoSuperior - o nome do campo (html) que será adicionado na estrutura na parte SUPERIOR da linha (opcional)
     * @param string $strIdCampoInferior - o nome do campo (html) que será adicionado na estrutura na parte INFERIOR da linha (opcional)
     * @param boolean $bolVisivelCampoSuperior - se o campo SUPERIOR deve ficar visível ou invisível (opcional; default = true = visível)
     * @param boolean $bolVisivelCampoInferior - se o campo INFERIOR deve ficar visível ou invisível (opcional; default = true = visível)
     * @param double $dblPercentualHorizontalCampoSuperior - o percentual horizontal que deve ser reservado ao campo SUPERIOR (opcional; default = $this->dblPercentualHorizontalBase <== será aplicado o valor definido no momento da adição do campo [pode mudar ser redefinido a qualquer tempo]); PS1: se houver campo complementar SUPERIOR, ele diminuirá 2% do espaço horizontal deste campo;
     * @param double $dblPercentualHorizontalCampoInferior - o percentual horizontal que deve ser reservado ao campo INFERIOR (opcional; default = $this->dblPercentualHorizontalBase <== será aplicado o valor definido no momento da adição do campo [pode mudar ser redefinido a qualquer tempo]); PS1: se houver campo complementar INFERIOR, ele diminuirá 2% do espaço horizontal deste campo
     * @param boolean $bolForcarNovaLinha - interrompe a composição da linha e começa abaixo  (opcional)
     * @param string $strIdCampoComplementarCampoSuperior - define um campo (de imagem) complementar: tooltip (ajuda)/calendário/ação; ocupará os 2% finais do espaço destinado ao campo SUPERIOR (opcional)
     * @param string $strIdCampoComplementarCampoInferior - define um campo (de imagem) complementar: tooltip (ajuda)/calendário/ação; ocupará os 2% finais do espaço destinado ao campo INFERIOR (opcional)
     * @param string $strCssAdicionalCampoSuperior - seta outros parâmetros CSS do campo SUPERIOR quando o campo está/fica visível (opcional; default = "position:absolute"); permite sobrescrever os parâmetros calculados automaticamente (top, left e width)
     * @param string $strCssAdicionalCampoInferior - seta outros parâmetros CSS do campo INFERIOR quando o campo está/fica visível (opcional; default = "position:absolute"); permite sobrescrever os parâmetros calculados automaticamente (top, left e width)
     * @param int $numLinhasEmBrancoAbaixo - quando a linha corrente for interrompida (por esgotamento horizontal ou uso do $bolForcarNovaLinha), adicionará linhas em branco  (opcional; default = 0)
     * @param double $dblDistanciaVerticalCampoInferior - o percentual horizontal que deve ser reservado ao campo SUPERIOR (opcional; default = $this->dblPercentualVerticalLocalCampoInferiorDefault)
     * @param boolean $bolLinhaSimples - se a linha, que costuma ser dupla, deve ser simples (opcional; default = false); se houver algum elemento na linha que tenha $bolLinhaSimples = false, ela será dupla; se uma linha é marcada como simples, o elemento inferior será marcado como invisível
     * @return void
     */
    public function adicionarCampoEstrutura(
        $strIdCampoSuperior = null,
        $strIdCampoInferior = null,
        $bolVisivelCampoSuperior = true,
        $bolVisivelCampoInferior = true,
        $dblPercentualHorizontalCampoSuperior = null,
        $dblPercentualHorizontalCampoInferior = null,
        $bolForcarNovaLinha = false,
        $strIdCampoComplementarCampoSuperior = null,
        $strIdCampoComplementarCampoInferior = null,
        $strCssAdicionalCampoSuperior = 'position:absolute',
        $strCssAdicionalCampoInferior = 'position:absolute',
        $numLinhasEmBrancoAbaixo = 0,
        $dblDistanciaVerticalCampoInferior = null,
        $bolLinhaSimples = false
    ) {
        //seta atributos
        $arrAtributos = array();
        $arrAtributos[self::$POS_ID_CAMPO_SUPERIOR] = $strIdCampoSuperior;
        $arrAtributos[self::$POS_ID_CAMPO_INFERIOR] = $strIdCampoInferior;
        $arrAtributos[self::$POS_BOL_VISIVEL_SUPERIOR] = InfraString::isBolVazia(
            $arrAtributos[self::$POS_ID_CAMPO_SUPERIOR]
        ) ? false : $bolVisivelCampoSuperior;
        $arrAtributos[self::$POS_BOL_VISIVEL_INFERIOR] = InfraString::isBolVazia(
            $arrAtributos[self::$POS_ID_CAMPO_INFERIOR]
        ) ? false : $bolVisivelCampoInferior;
        $arrAtributos[self::$POS_CSS_ADICIONAL_SUPERIOR] = $strCssAdicionalCampoSuperior;
        $arrAtributos[self::$POS_CSS_ADICIONAL_INFERIOR] = $strCssAdicionalCampoInferior;

        if ($arrAtributos[self::$POS_BOL_VISIVEL_SUPERIOR]) { //se o campo SUPERIOR está visível, seta o percentual horizontal ocupado
            $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR] = InfraString::isBolVazia(
                $dblPercentualHorizontalCampoSuperior
            ) ? $this->getDblPercentualHorizontalDefault() : $dblPercentualHorizontalCampoSuperior;
            //garante que o valor não extrapola o limite máximo
            $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR] = bccomp(
                $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR],
                $this->dblPercentualHorizontalMaximo,
                2
            ) > 0 ? $this->dblPercentualHorizontalMaximo : $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR];
        } else { //se o campo SUPERIOR está invisível, o percentual horizontal será 0
            $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_SUPERIOR] = 0;
        }

        $arrAtributos[self::$POS_BOL_LINHA_SIMPLES] = $bolLinhaSimples;
        if ($bolLinhaSimples) { //se a linha é simples, o campo de baixo necessariamente é invisível
            $arrAtributos[self::$POS_BOL_VISIVEL_INFERIOR] = false;
        }

        if ($arrAtributos[self::$POS_BOL_VISIVEL_INFERIOR]) { //se o campo INFERIOR está visível, seta o percentual horizontal ocupado
            $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR] = InfraString::isBolVazia(
                $dblPercentualHorizontalCampoInferior
            ) ? $this->getDblPercentualHorizontalDefault() : $dblPercentualHorizontalCampoInferior;
            //garante que o valor não extrapola o limite máximo
            $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR] = bccomp(
                $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR],
                $this->dblPercentualHorizontalMaximo,
                2
            ) > 0 ? $this->dblPercentualHorizontalMaximo : $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR];
        } else { //se o campo INFERIOR está invisível, o percentual horizontal será 0
            $arrAtributos[self::$POS_PERCENTUAL_HORIZONTAL_INFERIOR] = 0;
        }

        $arrAtributos[self::$POS_ID_CAMPO_COMPLEMENTAR_SUPERIOR] = $strIdCampoComplementarCampoSuperior;
        $arrAtributos[self::$POS_ID_CAMPO_COMPLEMENTAR_INFERIOR] = $strIdCampoComplementarCampoInferior;
        $arrAtributos[self::$POS_BOL_FORCAR_NOVA_LINHA] = $bolForcarNovaLinha;
        $arrAtributos[self::$POS_LINHAS_BRANCAS_ABAIXO] = $numLinhasEmBrancoAbaixo;
        if ($arrAtributos[self::$POS_BOL_VISIVEL_INFERIOR]) { //se o campo INFERIOR está visível, seta o percentual vertical de início
            $arrAtributos[self::$POS_DISTANCIA_VERTICAL_INFERIOR] = InfraString::isBolVazia(
                $dblDistanciaVerticalCampoInferior
            ) ? $this->dblPercentualVerticalLocalCampoInferiorDefault : $dblDistanciaVerticalCampoInferior;
        } else { //se o campo INFERIOR está invisível, o percentual vertical será 0
            $arrAtributos[self::$POS_DISTANCIA_VERTICAL_INFERIOR] = 0;
        }

        //acumula na estrutura
        $this->arrCamposEstrutura[] = $arrAtributos;
    }

    /**
     * Adiciona campo-extra (um campo que não participa da estrutura sendo mondada, mas que deve ser incluído no CSS/jQuery
     * @param string $strIdCampo - o nome do campo-extra (html)
     * @param string $strCss - seta os parâmetros CSS do campo-extra
     * @return void
     */
    public function adicionarCampoExtra($strIdCampo, $strCss)
    {
        $this->arrCamposExtra[$strIdCampo] = $strCss;
    }
}