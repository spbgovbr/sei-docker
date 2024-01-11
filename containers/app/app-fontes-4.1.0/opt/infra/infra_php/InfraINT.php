<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/06/2006 - criado por MGA
 *
 * @package infra_php
 */

abstract class InfraINT
{

    private static function montarItensIniciais(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $varValorItemSelecionado,
        $bolPrimeiroItemDesabilitado = false
    ) {
        $strRet = '';

        if ($strPrimeiroItemValor !== null && $strPrimeiroItemDescricao !== null) {
            // Se for TODOS adiciona um item vazio antes
            if ($strPrimeiroItemValor === '') {
                $strRet .= '<option value="null" ';
                if ($varValorItemSelecionado === null) {
                    $strRet .= 'selected="selected"';
                }
                if ($bolPrimeiroItemDesabilitado) {
                    $strRet .= ' disabled hidden ';
                }
                $strRet .= '>&nbsp;</option>' . "\n";
            }

            $strRet .= '<option value="' . $strPrimeiroItemValor . '"';

            if ($varValorItemSelecionado === null) { // se $varValorItemSelecionado é null, o primeiro item é necessariamente o selecionado
                $strRet .= ' selected="selected"';
            } else { // senão, verificar se é um dos selecionados
                foreach ($varValorItemSelecionado as $strValorItemSelecionado) {
                    if ($strValorItemSelecionado === $strPrimeiroItemValor) {
                        $strRet .= ' selected="selected"';
                        break;
                    }
                }
                if ($bolPrimeiroItemDesabilitado) {
                    $strRet .= ' disable hidden ';
                }
            }

            $strRet .= '>' . $strPrimeiroItemDescricao . '</option>' . "\n";
        }

        return $strRet;
    }

    /*
    *  Atenção : Alterações no retorno deste método dvem ser testados tanto na aplicação como nas telas do editor web.
    */
    public static function montarSelectArrInfraDTO(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $varValorItemSelecionado,
        $arrObjInfraDTO,
        $varAtributoChave,
        $strAtributoDescricao,
        $bolPrimeiroItemDesabilitado = false,
        $arrAtributosAdicionais = array()
    ) {
        $strRet = '';

        $varValorItemSelecionado = (!is_array($varValorItemSelecionado) && $varValorItemSelecionado !== null) ? array(
            $varValorItemSelecionado
        ) : $varValorItemSelecionado; // se não for array e não for null: transforma em array

        if (InfraArray::contar($arrObjInfraDTO)) {
            $strRet .= self::montarItensIniciais(
                $strPrimeiroItemValor,
                $strPrimeiroItemDescricao,
                $varValorItemSelecionado,
                $bolPrimeiroItemDesabilitado
            );

            foreach ($arrObjInfraDTO as $dto) {
                $strAtributoChave = '';
                if (!is_array($varAtributoChave)) {
                    $strAtributoChave = $dto->get($varAtributoChave);
                } else {
                    foreach ($varAtributoChave as $strChave) {
                        if ($strAtributoChave != '') {
                            $strAtributoChave .= '#';
                        }
                        $strAtributoChave .= $dto->get($strChave);
                    }
                }

                $strSelected = '';
                if ($varValorItemSelecionado !== null) {
                    // no eproc numeros grandes quando comparados davam problema sem o cast forçado
                    foreach ($varValorItemSelecionado as $strValorItemSelecionado) {
                        if ('#' . $strValorItemSelecionado . '#' == '#' . $strAtributoChave . '#') {
                            $strSelected .= ' selected="selected"';
                            break;
                        }
                    }
                }

                $strAtributosAdicionais = '';

                if (is_array($arrAtributosAdicionais) && count($arrAtributosAdicionais) > 0) {
                    foreach ($arrAtributosAdicionais as $strAtributo) {
                        $strValorAtributo = '';
                        if (!empty($strAtributo) && $dto->isBolExisteAtributo($strAtributo) && $dto->isSetAtributo(
                                $strAtributo
                            )) {
                            $strValorAtributo = $dto->get($strAtributo);
                        }
                        $strAtributosAdicionais .= ' ' . $strAtributo . '="' . InfraString::formatarXML(
                                $strValorAtributo
                            ) . '"';
                    }
                }

                $strRet .= '<option value="' . $strAtributoChave . '"' . $strSelected . $strAtributosAdicionais . '>' . InfraString::formatarXML(
                        $dto->get($strAtributoDescricao)
                    ) . '</option>' . "\n";
            }
        }
        return $strRet;
    }

    public static function montarSelectArray(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $varValorItemSelecionado,
        $arrOption,
        $bolPrimeiroItemDesabilitado = false,
        $arrAtributosAdicionais = array()
    ) {
        $varValorItemSelecionado = (!is_array($varValorItemSelecionado) && $varValorItemSelecionado !== null) ? array(
            $varValorItemSelecionado
        ) : $varValorItemSelecionado; // se não for array e não for null: transforma em array
        $strRet = self::montarItensIniciais(
            $strPrimeiroItemValor,
            $strPrimeiroItemDescricao,
            $varValorItemSelecionado,
            $bolPrimeiroItemDesabilitado
        );

        foreach ($arrOption as $chave => $descricao) {
            $bolSelecionado = false;
            if ($varValorItemSelecionado !== null) {
                foreach ($varValorItemSelecionado as $strValorItemSelecionado) { // verifica se o item é um dos selecionados
                    if ($strValorItemSelecionado !== null && '#' . $strValorItemSelecionado . '#' == '#' . $chave . '#') {
                        $bolSelecionado = true;
                        break;
                    }
                }
            }

            $arr = null;
            if (isset($arrAtributosAdicionais[$chave])) {
                $arr = $arrAtributosAdicionais[$chave];
            }

            $strRet .= InfraINT::montarItemSelect($chave, $descricao, $bolSelecionado, $arr);
        }
        return $strRet;
    }

    public static function montarItemSelect($strValor, $strDescricao, $bolSelecionado, $arrAtributosAdicionais = null)
    {
        $strSelecionado = ' ';
        $strAtributos = ' ';
        if ($bolSelecionado) {
            $strSelecionado .= ' selected="selected" ';
        }

        if (is_array($arrAtributosAdicionais)) {
            foreach ($arrAtributosAdicionais as $atributo => $valor) {
                $strAtributos .= $atributo . '="' . InfraString::formatarXML($valor) . '" ';
            }
        }

        return '<option value="' . $strValor . '" ' . $strSelecionado . $strAtributos . '>' . InfraString::formatarXML(
                $strDescricao
            ) . '</option>' . "\n";
    }

    public static function montarItemCheckbox($strValor, $strDescricao, $bolSelecionado)
    {
        $checked = ($bolSelecionado ? ' checked="checked"' : '');
        //$strItem = '<item name="selPrazo[]" value="' . $strValor . '" ' . $checked . '>' . $strDescricao . '</item>';
        $strItem = '<item name="selPrazo[]" value="' . $strValor . '" ' . $checked . '>' . htmlspecialchars(
                $strDescricao,
                ENT_COMPAT,
                'ISO-8859-1'
            ) . '</item>';
        return $strItem;
    }

    public static function montarCheckboxArray($arr, $strValorItensSelecionados)
    {
        $strRet = '';
        if (is_array($arr)) {
            foreach ($arr as $valor => $descricao) {
                $strRet .= self::montarItemCheckbox(
                    $valor,
                    $descricao,
                    InfraUtil::inArray($valor, $strValorItensSelecionados)
                );
            }
        }
        return $strRet;
    }

    public static function montarSelectSimNao(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $varValorItemSelecionado
    ) {
        return self::montarSelectArray(
            $strPrimeiroItemValor,
            $strPrimeiroItemDescricao,
            $varValorItemSelecionado,
            array('S' => 'Sim', 'N' => 'Não')
        );
    }

    public static function montarInputPassword($strNome, $strValor = '', $strAtributos = '')
    {
        return '<input type="password" id="' . $strNome . '" name="' . $strNome . '" class="infraText" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" value="' . $strValor . '" maxlength="100" ' . $strAtributos . ' />';
    }

}

