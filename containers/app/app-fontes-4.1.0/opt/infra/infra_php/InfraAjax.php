<?php

/**
 * @package infra_php
 */
class InfraAjax
{

    private function __construct()
    {
    }

    public static function enviarXML($xml)
    {
        InfraPagina::montarHeaderDownload(null, null, 'Content-type:application/xml; charset=iso-8859-1');
        echo '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
        echo $xml;
    }

    public static function enviarJSON($json)
    {
        InfraPagina::montarHeaderDownload(null, null, 'Content-type:application/json');
        echo $json;
    }

    public static function enviarTexto($txt)
    {
        InfraPagina::montarHeaderDownload(null, null, 'Content-type:text/html; charset=iso-8859-1');
        echo $txt;
    }

    public static function gerarXMLSelect($strOptions)
    {
        $strOptions = str_replace('> </option>', '>%20</option>', $strOptions);
        //$strOptions = str_replace('&','&amp;',$strOptions);
        return "<select>\n" . $strOptions . "</select>";
    }

    public static function gerarXMLItensArrInfraDTO(
        $arr,
        $strAtributoId,
        $strAtributoDescricao,
        $strAtributoComplemento = null,
        $strAtributoGrupo = null
    ) {
        $xml = '';
        $xml .= '<itens>';
        if ($arr !== null) {
            foreach ($arr as $dto) {
                $xml .= '<item id="' . self::formatarXMLAjax($dto->get($strAtributoId)) . '"';
                $xml .= ' descricao="' . self::formatarXMLAjax($dto->get($strAtributoDescricao)) . '"';

                if ($strAtributoComplemento !== null) {
                    $xml .= ' complemento="' . self::formatarXMLAjax($dto->get($strAtributoComplemento)) . '"';
                }

                if ($strAtributoGrupo !== null) {
                    $xml .= ' grupo="' . self::formatarXMLAjax($dto->get($strAtributoGrupo)) . '"';
                }

                $xml .= '></item>';
            }
        }
        $xml .= '</itens>';
        return $xml;
    }

    public static function gerarXMLComplementosArray($arr)
    {
        $xml = '';
        $xml .= "<complementos>\n";
        if ($arr !== null) {
            $keys = array_keys($arr);
            foreach ($keys as $key) {
                $xml .= '<complemento nome="' . $key . '">' . self::formatarXMLAjax(
                        $arr[$key]
                    ) . '</complemento>' . "\n";
            }
        }
        $xml .= '</complementos>';
        return $xml;
    }

    public static function processarExcecao($e)
    {
        if ($e != null) {
            $strDescricao = $e->__toString();
        } else {
            $strDescricao = 'AJAX: erro não identificado.';
        }
        $strDescricao = InfraString::formatarXML($strDescricao);

        $xml = '';
        $xml .= '<erros>';
        $xml .= '<erro descricao="' . $strDescricao . '"></erro>';
        $xml .= '</erros>';
        self::enviarXML($xml);
        die;
    }

    public static function decodificarPost()
    {
        array_walk_recursive($_POST, function (&$val) {
            $val = InfraString::fromUTF8($val);
        });
    }

    private static function formatarXMLAjax($str)
    {
        if (!is_numeric($str)) {
            $str = str_replace('&', '&amp;', $str);
            $str = str_replace('<', '&amp;lt;', $str);
            $str = str_replace('>', '&amp;gt;', $str);
            $str = str_replace('\"', '&amp;quot;', $str);
            $str = str_replace('"', '&amp;quot;', $str);
        }
        return $str;
    }

    public static function gerarXMLComplementosArrInfraDTO($dto, $arrAtributos = null)
    {
        $arr = array();
        if ($dto !== null) {
            if ($arrAtributos !== null) {
                foreach ($arrAtributos as $atributo) {
                    $arr[$atributo] = $dto->get($atributo);
                }
            } else {
                $arrAtributos = $dto->getArrAtributos();
                foreach ($arrAtributos as $atributo) {
                    if ($atributo[InfraDTO::$POS_ATRIBUTO_FLAGS] & InfraDTO::$FLAG_SET) {
                        $arr[$atributo[InfraDTO::$POS_ATRIBUTO_NOME]] = $atributo[InfraDTO::$POS_ATRIBUTO_VALOR];
                    }
                }
            }
        }
        return self::gerarXMLComplementosArray($arr);
    }

    public static function gerarXMLCheckbox($strItens)
    {
        return "<itens>\n" . $strItens . "</itens>";
    }
}