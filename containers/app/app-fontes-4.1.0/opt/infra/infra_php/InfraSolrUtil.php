<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 26/03/2019 - criado por mga@trf4.jus.br
 *
 * @package infra_php
 */

class InfraSolrUtil
{

    public static function formatarCaracteresEspeciais($q)
    {
        //Solr queries require escaping special characters that are part of the query syntax.
        // Special characters are: +, -, &&, ||, !, (, ), ", ~, *, ?, and :.
        // To escape these characters, use a slash (\) before the character to escape.
        // For example, to search for a literal double quotation mark (") character, escape the " for Solr with \".

        $arrSolrExc = array(chr(92), '/', '+', '-', '&', '|', '!', '(', ')', '{', '}', '[', ']', '^', '~', '?', ':');

        foreach ($arrSolrExc as $solrExc) {
            $q = str_replace($solrExc, chr(92) . $solrExc, $q);
        }

        return $q;
    }

    public static function formatarOperadores($q, $tag = null)
    {
        $q = InfraString::excluirAcentos(InfraString::transformarCaixaBaixa($q));

        //remove aspas repetidas
        while (strpos($q, '""') !== false) {
            $q = str_replace('""', '"', $q);
        }

        $arrPalavrasQ = InfraString::agruparItens($q);

        //print_r($arrPalavrasQ);
        //die;

        for ($i = 0; $i < count($arrPalavrasQ); $i++) {
            //número de aspas ímpar, remover do token que ficar com apenas uma
            $arrPalavrasQ[$i] = self::formatarCaracteresEspeciais(str_replace('"', '', $arrPalavrasQ[$i]));

            if (strpos($arrPalavrasQ[$i], ' ') !== false) {
                if ($tag == null) {
                    $arrPalavrasQ[$i] = '"' . $arrPalavrasQ[$i] . '"';
                } else {
                    $arrPalavrasQ[$i] = $tag . ':"' . $arrPalavrasQ[$i] . '"';
                }
            } elseif ($arrPalavrasQ[$i] == 'e') {
                $arrPalavrasQ[$i] = "AND";
            } elseif ($arrPalavrasQ[$i] == 'ou') {
                $arrPalavrasQ[$i] = "OR";
            } elseif ($arrPalavrasQ[$i] == 'nao') {
                $arrPalavrasQ[$i] = "AND NOT";
            } else {
                if ($tag != null) {
                    $arrPalavrasQ[$i] = $tag . ':' . $arrPalavrasQ[$i];
                }
            }
        }

        $ret = '';
        for ($i = 0; $i < count($arrPalavrasQ); $i++) {
            //Adiciona operador and como padrão se não informado
            if ($i > 0) {
                if (!in_array($arrPalavrasQ[$i - 1], array('AND', 'OR', 'AND NOT', '(')) && !in_array(
                        $arrPalavrasQ[$i],
                        array('AND', 'OR', 'AND NOT', ')')
                    )) {
                    $ret .= " AND";
                }
            }
            $ret .= ' ' . $arrPalavrasQ[$i];
        }

        $ret = str_replace(" AND AND NOT ", " AND NOT ", $ret);

        if (substr($ret, 0, strlen(" AND NOT ")) == " AND NOT ") {
            $ret = substr($ret, strlen(" AND NOT "));
            $ret = 'NOT ' . $ret;
        }

        if (substr($ret, 0, strlen(" AND ")) == " AND ") {
            $ret = substr($ret, strlen(" AND "));
        }

        if (substr($ret, 0, strlen(" OR ")) == " OR ") {
            $ret = substr($ret, strlen(" OR "));
        }

        if (substr($ret, strlen(" AND") * -1) == " AND") {
            $ret = substr($ret, 0, strlen(" AND") * -1);
        }

        if (substr($ret, strlen(" OR") * -1) == " OR") {
            $ret = substr($ret, 0, strlen(" OR") * -1);
        }

        if (substr($ret, strlen(" AND NOT") * -1) == " AND NOT") {
            $ret = substr($ret, 0, strlen(" AND NOT") * -1);
        }

        return trim($ret);
    }

    public static function obterTag($reg, $tag, $tipo)
    {
        $ret = $reg->xpath($tipo . '[@name=\'' . $tag . '\']');
        if (isset($ret[0])) {
            $ret = InfraString::fromUTF8($ret[0]);
            $ret = (strtoupper(trim(strip_tags($ret))) == "NULL" ? null : $ret);
        } else {
            $ret = null;
        }
        return $ret;
    }
}

