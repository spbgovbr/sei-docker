<?php

/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 16/05/2006 - criado por MGA
 *
 * @package infra_php
 * @see InfraDTO
 * @author MGA
 */
class InfraArray
{

    /**
     * Constante utilizada como parâmetro nos métodos de ordenação.
     * @access public
     * @name $TIPO_ORDENACAO_ASC
     */
    public static $TIPO_ORDENACAO_ASC = 'ASC';

    /**
     * Constante utilizada como parâmetro nos métodos de ordenação.
     * @access public
     * @name $TIPO_ORDENACAO_DESC
     */
    public static $TIPO_ORDENACAO_DESC = 'DESC';

    public static function arraySearch($mixValor, $arrLista)
    {
        if (empty($mixValor)) {
            return false;
        }

        if (!is_array($arrLista)) {
            return false;
        }

        $bolIsInt = false;
        if (is_numeric($mixValor)) {
            $bolIsInt = true;
        }

        $key = 0;
        foreach ($arrLista as $valor) {
            if (is_numeric($valor) && $bolIsInt) {
                if (bccomp($mixValor, $valor) == 0) {
                    return $key;
                }
            } else {
                if (strcmp($mixValor, $valor) == 0) {
                    return $key;
                }
            }
            $key++;
        }
        return false;
    }

    /**
     * Função para ordenação de um array por um ou mais índices
     * <code>
     * InfraArray::ordenarArrayMultiplo(&$arr,array('data','sequencia'),array('DTH','INT'),array(SORT_DESC,SORT_DESC));
     * </code>
     *
     * @param Array $arr - array que será ordenado (passado por referência)
     * @param Array $arrIndice - array de indices pelos quais deve ser feita a ordenação
     * @param Array $arrTipoIndice - array com os tipos de indices informados acima ('DTA','DTH','STR','INT')
     * @param Array $arrTipoOrdenacao - Array de opções de ordenação para cada indice (constantes possíveis: SORT_ASC, SORT_DESC, SORT_REGULAR, SORT_NUMERIC, SORT_STRING)
     * @throws Exception
     */
    public static function ordenarArrayMultiplo(&$arr, $arrIndice, $arrTipoIndice, $arrTipoOrdenacao)
    {
        $arrOrdenavel = array();
        $arrAux = array();
        $arrAuxIndice = array();
        $arrMultSort = array();
        $numCount = count($arr);
        $numCountIndice = count($arrIndice);

        if (count($arrTipoIndice) != $numCountIndice) {
            throw new InfraException('O número de tipos de índices deve corresponder ao número total de índices.');
        }

        if (count($arrTipoOrdenacao) != $numCountIndice) {
            throw new InfraException(
                'O número de critérios de ordenação deve corresponder ao número total de índices.'
            );
        }

        for ($i = 0; $i < $numCount; $i++) {
            for ($j = 0; $j < $numCountIndice; $j++) {
                $strIndice = $arrIndice[$j];
                $strTipoOrdenacao = $arrTipoOrdenacao[$j];
                $strTipoIndice = $arrTipoIndice[$j];

                $strTipoIndice = strtoupper($strTipoIndice);

                if ($strTipoIndice === 'DTA') {
                    $arrAux = explode("/", $arr[$i][$strIndice]);
                    $strValor = $arrAux[2] . str_pad($arrAux[1], 2, '0', STR_PAD_LEFT) . str_pad(
                            $arrAux[0],
                            2,
                            '0',
                            STR_PAD_LEFT
                        );
                } else {
                    if ($strTipoIndice === 'DTH') {
                        $arrAux = explode("/", $arr[$i][$strIndice]);
                        $strValor = substr($arrAux[2], 0, 4) . str_pad($arrAux[1], 2, '0', STR_PAD_LEFT) . str_pad(
                                $arrAux[0],
                                2,
                                '0',
                                STR_PAD_LEFT
                            ) . str_replace(':', '', substr($arrAux[2], 5));
                    } else {
                        $strValor = InfraString::excluirAcentos($arr[$i][$strIndice]);
                    }
                }

                $arrOrdenavel[$i][$strIndice] = $strValor;
                $arrAuxIndice[$j][] = $strValor;
            }
            $arrOrdenavel[$i]['Conteudo'] = $arr[$i];
        }

        for ($w = 0; $w < $numCountIndice; $w++) {
            $arrMultSort[] = &$arrAuxIndice[$w];

            $arrMultSort[] = &$arrTipoOrdenacao[$w];
        }

        $arrMultSort[] =& $arrOrdenavel;

        call_user_func_array('array_multisort', $arrMultSort);

        $i = 0;
        foreach ($arrOrdenavel as $item) {
            $arr[$i++] = $item['Conteudo'];
        }
    }

    public static function ordenarArrInfraDTO(&$arrObjInfraDTO, $varAtributo, $varTipoOrdenacao)
    {
        //SE OPERANDO SOBRE ARRAYS DE PARÂMETROS, FAZ
        if (is_array($varAtributo) || is_array($varTipoOrdenacao)) {
            if (!is_array($varAtributo) || !is_array($varTipoOrdenacao)) {
                throw new InfraException('Atributo(s) e ordenação(ões) devem ser ambos escalares ou ambos arrays.');
            }
            if (!count($varAtributo) != !count($varTipoOrdenacao)) {
                throw new InfraException('A quantidade de atributos deve ser igual à quantidade de ordenações.');
            }

            //executa cada uma das ordenações na ordem inversa do que foi passado como parâmetro (para que o resultado seja o esperado)
            $arrAtributo = array_reverse($varAtributo);
            $arrTipoOrdenacao = array_reverse($varTipoOrdenacao);
            $numCount = count($varAtributo);
            for ($i = 0; $i < $numCount; $i++) {
                self::ordenarArrInfraDTO($arrObjInfraDTO, $arrAtributo[$i], $arrTipoOrdenacao[$i]);
            }
        } else {
            //SE OS PARÂMETROS FOREM ESCALARES, FAZ
            $arrObjs = array();

            if (count($arrObjInfraDTO)) {
                $strPrefixo = $arrObjInfraDTO[0]->getPrefixo($varAtributo);
            }

            $todosNumericos = true;
            $peloMenosUmGrande = false;

            foreach ($arrObjInfraDTO as $dto) {
                $strValorAtributo = $dto->get($varAtributo);

                if ($strValorAtributo != '') {
                    if ($strPrefixo == InfraDTO::$PREFIXO_DTA) {
                        $arrAux = explode("/", $strValorAtributo);
                        $strValorAtributo = $arrAux[2] . str_pad($arrAux[1], 2, '0', STR_PAD_LEFT) . str_pad(
                                $arrAux[0],
                                2,
                                '0',
                                STR_PAD_LEFT
                            );
                    } else {
                        if ($strPrefixo == InfraDTO::$PREFIXO_DTH) {
                            $arrAux = explode("/", $strValorAtributo);
                            $strValorAtributo = substr($arrAux[2], 0, 4) . str_pad(
                                    $arrAux[1],
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) . str_pad($arrAux[0], 2, '0', STR_PAD_LEFT) . str_replace(
                                    ':',
                                    '',
                                    substr($arrAux[2], 5)
                                );
                        } else {
                            if ($strPrefixo == InfraDTO::$PREFIXO_STR) {
                                $strValorAtributo = InfraString::transformarCaixaAlta(
                                    InfraString::excluirAcentos($strValorAtributo)
                                );
                            } else {
                                if ($strPrefixo == InfraDTO::$PREFIXO_DIN) {
                                    $strValorAtributo = InfraUtil::prepararDin($strValorAtributo);
                                }
                            }
                        }
                    }
                }

                if (!is_numeric($strValorAtributo)) {
                    $todosNumericos = false;
                }
                if (strlen($strValorAtributo) > 8) {
                    $peloMenosUmGrande = true;
                }

                if (!isset($arrObjs[$strValorAtributo])) {
                    $arrObjs[$strValorAtributo] = $dto;
                } else {
                    if (!is_array($arrObjs[$strValorAtributo])) {
                        $arrObjs[$strValorAtributo] = array($arrObjs[$strValorAtributo], $dto);
                    } else {
                        $arrObjs[$strValorAtributo][] = $dto;
                    }
                }
            }

            //SE FOR UM NUMERO MUITO ALTO, NAO USAR KSORT, POIS TEM UM BUG DO PHP
            if ($todosNumericos && $peloMenosUmGrande) {
                if ($varTipoOrdenacao == InfraArray::$TIPO_ORDENACAO_ASC) {
                    $arrObjs = InfraArray::ksortNumerosGrandes($arrObjs);
                } else {
                    $arrObjs = InfraArray::krsortNumerosGrandes($arrObjs);
                }
            } else {
                if ($varTipoOrdenacao == InfraArray::$TIPO_ORDENACAO_ASC) {
                    ksort($arrObjs);
                } else {
                    krsort($arrObjs);
                }
            }

            $i = 0;
            foreach ($arrObjs as $item) {
                if (!is_array($item)) {
                    $arrObjInfraDTO[$i++] = $item;
                } else {
                    foreach ($item as $obj) {
                        $arrObjInfraDTO[$i++] = $obj;
                    }
                }
            }
        }
    }

    public static function ksortNumerosGrandes($arr)
    {
        $arrKeys = array_keys($arr);
        $arrKeys = InfraArray::sortNumerosGrandes($arrKeys);
        $arrRetorno = array();
        for ($i = 0, $iMax = count($arrKeys); $i < $iMax; $i++) {
            $arrRetorno[] = $arr[$arrKeys[$i]];
        }
        return $arrRetorno;
    }

    public static function krsortNumerosGrandes($arr)
    {
        $arrKeys = array_keys($arr);
        $arrKeys = InfraArray::sortNumerosGrandes($arrKeys);
        $arrRetorno = array();
        for ($i = count($arrKeys) - 1; $i >= 0; $i--) {
            $arrRetorno[] = $arr[$arrKeys[$i]];
        }
        return $arrRetorno;
    }

    public static function sortNumerosGrandes($arr, $left = 0, $right = null)
    {
        static $array = array();
        if ($right == null) {
            $array = $arr;
            //ULTIMO ELEMENTO DO ARRAY
            $right = count($array) - 1;
        }

        $i = $left;
        $j = $right;

        $tmp = $array[(int)(($left + $right) / 2)];

        //DIVIDE O ARRAY EM DUAS PARTES.
        //A ESQUERDA DE $TMP FICAM OS VALORES MENORES,
        //A DIREITA DE $TMP FICAM OS VALORES MAIORES
        do {
            //USA A FUNCAO BCOMP PARA COMPARAR NUMEROS GRANDES, SE USASSE O OPERADOR < DARIA BUG
            while (bccomp($array[$i], $tmp) == -1) {
                $i++;
            }

            while (bccomp($tmp, $array[$j]) == -1) {
                $j--;
            }

            //TROCA OS ELEMENTOS DOS DOIS LADOS
            if ($i <= $j) {
                $w = $array[$i];
                $array[$i] = $array[$j];
                $array[$j] = $w;

                $i++;
                $j--;
            }
        } while ($i <= $j);

        //ORDENA O LADO ESQUERDO SE TIVER MAIS DO QUE UM ELEMENTO
        if ($left < $j) {
            InfraArray::sortNumerosGrandes(null, $left, $j);
        }

        //O MESMO PARA O LADO DIREITO
        if ($i < $right) {
            InfraArray::sortNumerosGrandes(null, $i, $right);
        }

        //QUANDO TODAS AS PARTICOES TIVEREM UM ELEMENTO O ARRAY ESTA ORDENADO
        return $array;
    }

    /**
     * Função para ordenação de um array comum:
     * <code>
     * InfraArray::ordenarArray($arr,3,InfraArray::$TIPO_ORDENACAO_ASC);
     * </code>
     *
     * @access public
     * @param Array $arr - array que será ordenado (passado por referência)
     * @param String $strIndice - indice do array que possui os valores para ordenaçao
     * @param String $strTipoOrdenacao - constante da classe indicando se a ordenação será ascendente ou descendente
     * @return void
     */
    public static function ordenarArray(&$arr, $strIndice, $strTipoOrdenacao)
    {
        $arrRet = array();
        $numCount = count($arr);
        for ($i = 0; $i < $numCount; $i++) {
            $strValor = InfraString::excluirAcentos($arr[$i][$strIndice]);
            //$arrRet[$strValor."\0".$i] = $arr[$i];
            $arrRet[$strValor . str_pad($i, strlen($numCount), '0', STR_PAD_LEFT)] = $arr[$i];
        }

        if ($strTipoOrdenacao === self::$TIPO_ORDENACAO_ASC) {
            ksort($arrRet);
        } else {
            krsort($arrRet);
        }
        $i = 0;
        foreach ($arrRet as $item) {
            $arr[$i++] = $item;
        }
    }

    /**
     * Filtra um array de objetos InfraDTO garantindo que o valor do atributo passado como parâmetro
     * não se repete (distinct). Para elementos repetidos retornara o primeiro encontrado no array original.
     * <code>
     * $arrRet = InfraArray::distinctArrInfraDTO($arrObjUnidadeDTO,'Sigla');
     * </code>
     *
     * ou
     * <code>
     * $arrRet = InfraArray::distinctArrInfraDTO($arrObjUnidadeDTO,array('Sigla', 'Nome'));
     * </code>
     * @access public
     * @param Array $arrObjInfraDTO - array que será filtrado
     * @param String ou String[] $varAtributoChave - nome do atributo OU array de nomes de atributos (sempre sem o tipo) existente nos objetos do array
     * @return Array o array sem repetições do(s) atributo(s) informado(s)
     */
    public static function distinctArrInfraDTO($arrObjInfraDTO, $varAtributoChave)
    {
        //INICIALIZA VAIRÁVEIS
        $arrAdicionados = array(); //contém os atributos/chaves compostas já adicionados(as) (inibe nova adição)

        $ret = array(); //array de retorno

        //TESTES BÁSICOS
        if (InfraArray::contar($arrObjInfraDTO)) {
            //EXECUTA FILTRAGEM DE DTOS
            if (!is_array($varAtributoChave)) { //se é apenas 1 atributo, faz

                if (InfraString::isBolVazia($varAtributoChave)) {
                    throw new InfraException('Nome do atributo vazio.');
                }

                foreach ($arrObjInfraDTO as $dto) {
                    if (!InfraUtil::inArray($dto->get($varAtributoChave), $arrAdicionados)) {
                        $arrAdicionados[] = $dto->get($varAtributoChave);
                        $ret[] = $dto;
                    }
                }
            } else { //se é um array de atributos, faz

                if (InfraArray::contar($varAtributoChave) == 0) {
                    throw new InfraException('Nenhum atributo informado.');
                }

                foreach ($varAtributoChave as $strValor) {
                    if (InfraString::isBolVazia($strValor)) {
                        throw new InfraException('Nome do atributo vazio.');
                    }
                }

                foreach ($arrObjInfraDTO as $dto) {
                    //cria a chave composta (com os valores dos atributos informados)
                    $strChave = '';
                    foreach ($varAtributoChave as $strValor) {
                        $strChave .= $dto->get($strValor) . '#';
                    }

                    //filtra
                    if (!isset($arrAdicionados[$strChave])) { //se a chave ainda não foi salva, faz
                        $arrAdicionados[$strChave] = true;
                        $ret[] = $dto;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Retorna o array indexado pelo atributo passado.
     *
     * @param array $arrObjInfraDTO
     * @param string|array $varAtributoChave
     * @param bool $bolAtributoRepetido
     * @return array
     * @throws InfraException
     */
    public static function indexarArrInfraDTO($arrObjInfraDTO, $varAtributoChave, $bolAtributoRepetido = false)
    {
        //INICIALIZA ARRAY DE RETORNO
        $ret = array();

        if (!empty($arrObjInfraDTO)) {
            if (!is_array($varAtributoChave)) {
                //ÍNDICE ÚNICO (ESCALAR)
                if (!$bolAtributoRepetido) {
                    foreach ($arrObjInfraDTO as $dto) {
                        $ret[$dto->get($varAtributoChave)] = $dto;
                    }
                } else {
                    foreach ($arrObjInfraDTO as $dto) {
                        if (!isset($ret[$dto->get($varAtributoChave)])) {
                            $ret[$dto->get($varAtributoChave)] = array($dto);
                        } else {
                            $ret[$dto->get($varAtributoChave)][] = $dto;
                        }
                    }
                }
            } else {
                //ARRAY DE ÍNDICES
                //MONTA COMANDO QUE SERÁ EXECUTADO
                $strComando = '$ret';
                foreach ($varAtributoChave as $strChave) {
                    if (!ctype_alnum($strChave)) {
                        throw new InfraException('Nome do atributo inválido.');
                    }
                    $strComando .= '[$dto->get(\'' . $strChave . '\')]';
                }

                if ($bolAtributoRepetido) {
                    $strComando .= '[]';
                }

                $strComando .= ' = $dto;';

                //MONTA ARRAY
                foreach ($arrObjInfraDTO as $dto) {
                    eval($strComando);
                }
            }
        }

        return $ret;
    }

    /**
     *    Gera um array com todos os elementos de array 1 que possuam correspondencia de valor em array 2.
     * <code>
     * //Retorna um array com as unidades do Fulano e do Beltrano
     * $arrRet = InfraArray::joinArrInfraDTO($arrObjUnidadeDTO_Fulano,'IdUnidade',$arrObjUnidadeDTO_Beltrano,'IdUnidade');
     * </code>
     *
     * @access public
     * @param Array $arrObjInfraDTO_1 - primeiro array para cruzamento
     * @param String $strAtributo_1 - nome do atributo (sem o tipo) existente no primeiro array
     * @param Array $arrObjInfraDTO_2 - segundo array para cruzamento
     * @param String $strAtributo_2 - nome do atributo (sem o tipo) existente no segundo array
     * @return Array array contendo os objetos encontrados em $arrObjInfraDTO_1 e que tem correspondência em $arrObjInfraDTO_2
     * @throws InfraException
     */
    public static function joinArrInfraDTO($arrObjInfraDTO_1, $strAtributo_1, $arrObjInfraDTO_2, $strAtributo_2)
    {
        $ret = array();
        //EVITAR DUPLICIDADE NO ARRAY RETORNADO
        $arrObjInfraDTO_2Distinct = InfraArray::distinctArrInfraDTO($arrObjInfraDTO_2, $strAtributo_2);
        foreach ($arrObjInfraDTO_1 as $dto_1) {
            foreach ($arrObjInfraDTO_2Distinct as $dto_2) {
                //NO EPROC NUMEROS GRANDES QUANDO COMPARADOS DAVAM PROBLEMA COM O CAST FOR?ADO
                if ('#' . $dto_1->get($strAtributo_1) . '#' == '#' . $dto_2->get($strAtributo_2) . '#') {
                    $ret[] = $dto_1;
                }
            }
        }
        return $ret;
    }

    public static function distinctArray($arr, $strIndice)
    {
        $ret = array();
        $arrAdicionados = array();
        $numCount = count($arr);

        for ($i = 0; $i < $numCount; $i++) {
            if (!isset($arrAdicionados[$arr[$i][$strIndice]])) {
                $arrAdicionados[$arr[$i][$strIndice]] = '';
                $ret[] = $arr[$i];
            }
        }
        return $ret;
    }

    /**
     *    Converte um array de DTOs em um array simples.
     */
    public static function converterArrInfraDTO(
        $arrObjInfraDTO,
        $strAtributoValor = null,
        $varAtributoChave = null,
        $bolAtributoRepetido = false
    ) {
        $ret = array();

        if ($strAtributoValor == null) { //atributo null

            $ret = array();

            if (count($arrObjInfraDTO)) {
                foreach ($arrObjInfraDTO as $dto) {
                    $arr = array();
                    foreach ($dto->getArrAtributos() as $arrAtributos) {
                        if ($arrAtributos[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET) {
                            $arr[$arrAtributos[InfraDTO::$POS_ATRIBUTO_NOME]] = $arrAtributos[InfraDTO::$POS_ATRIBUTO_VALOR];
                        }
                    }
                    $ret[] = $arr;
                }

                $ret = array(get_class($arrObjInfraDTO[0]) => $ret);
            }
        } elseif (!is_array($varAtributoChave)) { //chave única (escalar)
            if (!$bolAtributoRepetido) {
                foreach ($arrObjInfraDTO as $dto) {
                    if ($varAtributoChave == null) {
                        $ret[] = $dto->get($strAtributoValor);
                    } else {
                        $ret[$dto->get($varAtributoChave)] = $dto->get($strAtributoValor);
                    }
                }
            } else {
                if ($varAtributoChave != null) {
                    foreach ($arrObjInfraDTO as $dto) {
                        if (!isset($ret[$dto->get($varAtributoChave)])) {
                            $ret[$dto->get($varAtributoChave)] = array($dto->get($strAtributoValor));
                        } else {
                            $ret[$dto->get($varAtributoChave)][] = $dto->get($strAtributoValor);
                        }
                    }
                }
            }
        } else {
            //ARRAY DE CHAVES
            //MONTA COMANDO QUE SERÁ EXECUTADO
            $strComando = '$ret';
            foreach ($varAtributoChave as $strChave) {
                if (!ctype_alnum($strChave)) {
                    throw new InfraException('Nome do atributo inválido.');
                }
                $strComando .= '[$dto->get(\'' . $strChave . '\')]';
            }

            if ($bolAtributoRepetido) {
                $strComando .= '[]';
            }

            $strComando .= ' = $dto->get($strAtributoValor);';

            //MONTA ARRAY
            foreach ($arrObjInfraDTO as $dto) {
                eval($strComando);
            }
        }
        return $ret;
    }

    /* Retorna um array unidimensional simples a partir de um array multidimensional e uma chave
    * @param array - array multidimensional
    * @param string - atributo usado na simplifica??o
    * @return array - array unidimensional
    */
    public static function simplificarArr($arr, $chave)
    {
        $ret = array();

        foreach ($arr as $entrada) {
            $ret[] = $entrada[$chave];
        }
        return $ret;
    }

    public static function somarArrInfraDTO($arrObjInfraDTO, $strAtributo)
    {
        //INICIALIZA ARRAY DE RETORNO
        $ret = 0;

        foreach ($arrObjInfraDTO as $dto) {
            $valor = $dto->get($strAtributo);
            if (is_numeric($valor)) {
                $ret += $valor;
            } else {
                return null;
            }
        }

        return $ret;
    }

    /**
     * Retira o elemento informado do vetor, tamb?m informado
     * @param array $arrAlvo - array do qual será retirado o valor
     * @param string $strValor - valor a retirar
     * @return array - array informado sem o valor
     */
    public static function retirarElementoArray($arrAlvo, $strValor)
    {
        $arrRet = array();
        $numItens = count($arrAlvo);
        for ($i = 0; $i < $numItens; $i++) {
            if ($arrAlvo[$i] != $strValor) {
                $arrRet[] = $arrAlvo[$i];
            }
        }
        return $arrRet;
    }

    public static function retirarElementoArrInfraDTO($arrObjInfraDTO, $strAtributo, $varValor)
    {
        $ret = array();
        foreach ($arrObjInfraDTO as $dto) {
            if ($dto->get($strAtributo) != $varValor) {
                $ret[] = $dto;
            }
        }
        return $ret;
    }

    public static function mapearArrInfraDTO(
        $arrObjInfraDTO,
        $strAtributoChave,
        $strAtributoValor,
        $bolTrimChave = false,
        $bolTrimValor = false
    ) {
        $ret = array();
        foreach ($arrObjInfraDTO as $dto) {
            $varChave = $dto->get($strAtributoChave);
            $varValor = $dto->get($strAtributoValor);

            if ($bolTrimChave) {
                $varChave = trim($varChave);
            }

            if ($bolTrimValor) {
                $varValor = trim($varValor);
            }

            $ret[$varChave] = $varValor;
        }
        return $ret;
    }

    public static function implodeArrInfraDTO($arrObjInfraDTO, $strAtributo, $strSeparador = ',')
    {
        $ret = '';
        foreach ($arrObjInfraDTO as $dto) {
            if ($ret != '') {
                $ret .= $strSeparador;
            }
            $ret .= $dto->get($strAtributo);
        }
        return $ret;
    }

    public static function formatarMsgArrInfraDTO($arrObjInfraDTO, $strAtributo)
    {
        $ret = '';
        $numItens = count($arrObjInfraDTO);
        for ($i = 0; $i < $numItens; $i++) {
            if ($i) {
                $ret .= ($i == ($numItens - 1)) ? ' e ' : ', ';
            }
            $ret .= $arrObjInfraDTO[$i]->get($strAtributo);
        }
        return $ret;
    }

    public static function formatarMsgArray($arr)
    {
        $ret = '';
        $numItens = count($arr);
        for ($i = 0; $i < $numItens; $i++) {
            if ($i) {
                $ret .= ($i == ($numItens - 1)) ? ' e ' : ', ';
            }
            $ret .= $arr[$i];
        }
        return $ret;
    }

    public static function filtrarArrInfraDTO($arrObjInfraDTO, $strAtributo, $varValor)
    {
        $ret = array();

        foreach ($arrObjInfraDTO as $dto) {
            if ($dto->get($strAtributo) == $varValor) {
                $ret[] = $dto;
            }
        }
        return $ret;
    }

    /*public static function converterArrayString($arr,$strSeparador=','){
      return implode($strSeparador,$arr);
      /*
      $ret = '';
      foreach($arr as $item){
        if ($ret!=''){
          $ret .= $strSeparador;
        }
        $ret .= $item;
      }
      return $ret;
    }*/

    public static function somarArray($arr, $strIndice = null)
    {
        if (!is_array($arr) || count($arr) < 1) {
            return 0;
        }

        $ret = 0;
        foreach ($arr as $id => $data) {
            if ($strIndice != null) {
                $v = $data[$strIndice];
            } else {
                $v = $data;
            }

            if (!is_numeric($v)) {
                return null;
            }

            $ret += $v;
        }
        return $ret;
    }

    public static function gerarArrInfraDTO($strNomeClasseDTO, $strAtributo, $arrValores)
    {
        $reflectionClass = new ReflectionClass($strNomeClasseDTO);
        $ret = array();
        foreach ($arrValores as $item) {
            $dto = $reflectionClass->newInstance();
            $dto->set($strAtributo, $item);
            $ret[] = $dto;
        }
        return $ret;
    }

    /**
     * Monta um array de DTO da classe passada com base no array passado
     * @param unknown_type $strNomeClasseDTO
     * @param unknown_type $arrAtributosValoresDTOs Array de 2 dimensões.
     * Cada item da primeira dimensão corresponde a um DTO a ser criado. Cada item da segunda dimensão corresponte a um atributo do DTO.
     * O array da segunda dimensão deve ser indexado pelo nome do atributo. Pode ser o retorno de uma InfraIBanco.consultarSql.
     * @return array
     */
    public static function gerarArrInfraDTOMultiAtributos($strNomeClasseDTO, $arrAtributosValoresDTOs)
    {
        $reflectionClass = new ReflectionClass($strNomeClasseDTO);
        $ret = array();
        foreach ($arrAtributosValoresDTOs as $arrAtributosValoresDTO) {
            $dto = $reflectionClass->newInstance();
            $arrAtributosDTO = array_keys($arrAtributosValoresDTO);
            foreach ($arrAtributosDTO as $strAtributoDTO) {
                if (!is_numeric($strAtributoDTO)) {
                    $dto->set($strAtributoDTO, $arrAtributosValoresDTO[$strAtributoDTO]);
                }
            }
            $ret[] = $dto;
        }
        return $ret;
    }

    /**
     * Procura determinado valor em um array e retorna a chave em caso de sucesso
     *
     * @param $strValor Valor a ser pesquisado
     * @param $arr Array no qual será pesquisado o valor
     * @param $bolRetornaChavePrincipal boolean Se irá retornar a chave principal do array ou a chave do elemento
     * @return mixed retorna a chave e false em caso de erro
     */
    public static function retornarChaveRecursivo($strValor, $arr, $bolRetornaChavePrincipal = false)
    {
        $aIt = new RecursiveArrayIterator($arr);
        $it = new RecursiveIteratorIterator($aIt);
        $it->next();
        while ($it->valid()) {
            if ($it->current() == $strValor) {
                if ($bolRetornaChavePrincipal) {
                    return $aIt->key();
                } else {
                    return $it->key();
                }
            }
            $it->next();
        }
        return false;
    }

    /**
     * Retorna uma lista com todos os valores contidos no array passado
     *
     * @param $arr array Array para coletar os valores
     * @return array Lista com todos os valores contidos no array passado
     */
    public static function valoresArrayRecursivo($arr)
    {
        $arrSaida = array();
        $aIt = new RecursiveArrayIterator($arr);
        $it = new RecursiveIteratorIterator($aIt);
        $it->next();
        while ($it->valid()) {
            $arrSaida[] = $it->current();
            $it->next();
        }
        return $arrSaida;
    }

    /**
     * Converte um array para XML
     *
     * @param array $arr
     * @param bool $bolCabecalho
     * @param string $strChave Usada para arrays não associativos.
     * @return string
     */
    public static function converterArrayXml($arr, $bolCabecalho = false, $strChave = '')
    {
        $xml = array();

        if ($bolCabecalho) {
            $xml[] = '<?xml version="1.0" encoding="UTF-8"?>\n';
        }

        foreach ($arr as $key => $value) {
            if (!is_numeric($key)) {
                $xml[] = '<' . $key . '>';
                if (is_array($value)) {
                    if (is_numeric(key($value))) {
                        array_pop($xml);
                    }
                    $xml[] = self::converterArrayXml($value, false, $key);
                } else {
                    $xml[] = $value;
                }
                $xml[] = '</' . $key . '>';
                if (is_array($value) && is_numeric(key($value))) {
                    array_pop($xml);
                }
            } else {
                $xml[] = '<' . $strChave . '>';
                if (is_array($value)) {
                    $xml[] = self::converterArrayXml($value, false, $key);
                } else {
                    $xml[] = $value;
                }
                $xml[] = '</' . $strChave . '>';
            }
        }
        return implode("", $xml);
    }

    public static function contar($varValor)
    {
        if (is_array($varValor)) {
            return count($varValor);
        }

        if (is_object($varValor)) {
            $objReflection = new ReflectionClass(get_class($varValor));
            if ($objReflection->implementsInterface('Countable')) {
                return count($varValor);
            }
        }
        return (int)(!empty($varValor));
    }

    public static function contarArr($arr, $varValor)
    {
        $numTotal = 0;
        $numItens = count($arr);
        for ($i = 0; $i < $numItens; $i++) {
            if ($arr[$i] == $varValor) {
                $numTotal++;
            }
        }
        return $numTotal;
    }

    public static function contarArrInfraDTO($arrObjInfraDTO, $strAtributo, $varValor)
    {
        $numTotal = 0;
        foreach ($arrObjInfraDTO as $dto) {
            if ($dto->get($strAtributo) == $varValor) {
                $numTotal++;
            }
        }
        return $numTotal;
    }

    public static function sumArray($arr, $strAtributoUnico, $arrAtributosValorSoma)
    {
        $arrAdicionados = array();
        $arrNull = array();
        $novoArr = array();

        foreach ($arr as $item) {
            $id = $item[$strAtributoUnico];

            if (!in_array($id, $arrAdicionados)) {
                $arrAdicionados[] = $id;
                $novoArr[$id] = $item;
            } else {
                //SE JA ESTIVER NO ARRAY, SOMARA O VALOR DO REGISTRO DUPLICADO AO PRIMEIRO VALOR SALVO NO DTO DE IDS UNICOS
                foreach ($novoArr as $novoItem) {
                    if ($novoItem[$strAtributoUnico] == $id) {
                        foreach ($arrAtributosValorSoma as $atributo) {
                            /*if (!is_numeric($novoItem[$atributo]) || !is_numeric($item[$atributo])){
                              return null;
                            }*/
                            if (!is_numeric($novoItem[$atributo]) || !is_numeric($item[$atributo])) {
                                //return null;
                                $arrNull[$id][$atributo] = $atributo;
                            }
                            $numValorFinal = $novoItem[$atributo] + $item[$atributo];
                            $novoItem[$atributo] = $numValorFinal;
                        }
                        $novoArr[$id] = $novoItem;
                    }
                }
            }
        }

        foreach ($arrNull as $id => $arrAtributos) {
            foreach ($arrAtributos as $atributo) {
                $novoArr[$id][$atributo] = null;
            }
        }
        return array_values($novoArr);
    }

    public static function sumArrInfraDTO($arrObjInfraDTO, $strAtributoUnico, $arrAtributosValorSoma)
    {
        $arrAdicionados = array();
        $arrNull = array();
        $novoArrDto = array();

        foreach ($arrObjInfraDTO as $dto) {
            $id = $dto->get($strAtributoUnico);
            if (!in_array($id, $arrAdicionados)) {
                $arrAdicionados[] = $id;
                $novoArrDto[$id] = $dto;
            } else {
                foreach ($novoArrDto as $novoDto) {
                    if ($novoDto->get($strAtributoUnico) == $id) {
                        foreach ($arrAtributosValorSoma as $atributo) {
                            if (!is_numeric($novoDto->get($atributo)) || !is_numeric($dto->get($atributo))) {
                                $arrNull[$id][$atributo] = $atributo;
                            }
                            $numValorFinal = $novoDto->get($atributo) + $dto->get($atributo);
                            $novoDto->set($atributo, $numValorFinal);
                        }
                        $novoArrDto[$id] = $novoDto;
                    }
                }
            }
        }

        foreach ($arrNull as $id => $arrAtributos) {
            foreach ($arrAtributos as $atributo) {
                $novoArrDto[$id]->set($atributo, null);
            }
        }

        return array_values($novoArrDto);
    }

}

