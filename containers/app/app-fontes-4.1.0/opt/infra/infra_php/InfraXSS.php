<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/04/2018 - criado por MGA
 *
 * @package infra_php
 */


class InfraXSS
{

    private $arrImagens = array();
    private $strDiferenca = '';
    private $bolProcessarXML = false;

    /**
     * @return bool
     */
    public function isBolProcessarXML()
    {
        return $this->bolProcessarXML;
    }

    /**
     * @param bool $bolProcessarXML
     */
    public function setBolProcessarXML($bolProcessarXML)
    {
        $this->bolProcessarXML = $bolProcessarXML;
    }


    public function __construct()
    {
    }

    public function getStrDiferenca()
    {
        return $this->strDiferenca;
    }

    public function verificacaoBasica($strConteudo, $arrValoresNaoPermitidos = null)
    {
        $ret = null;

        if ($arrValoresNaoPermitidos == null) {
            $arrValoresNaoPermitidos = array(
                'XMLHttpRequest',
                'setRequestHeader',
                'onload',
                'decodeURIComponent',
                'document.cookie',
                'document.write',
                'parentNode',
                'innerHTML',
                'appendChild'
            );
        }

        $strConteudoVerificacao = strtolower($strConteudo);
        foreach ($arrValoresNaoPermitidos as $strNaoPermitido) {
            if (strpos($strConteudoVerificacao, strtolower(trim($strNaoPermitido))) !== false) {
                if ($ret == null) {
                    $ret = array();
                }

                $ret[] = $strNaoPermitido;
            }
        }

        return $ret;
    }

    public function verificacaoAvancada(
        &$strConteudo,
        $arrTagsPermitidas = null,
        $arrTagsAtributosPermitidos = null,
        $bolDiferenca = true
    ) {
        if ($arrTagsPermitidas === null) {
            $arrTagsPermitidas = array('html', 'body', 'head', 'style', 'link', 'title', 'input');
        }

        if ($arrTagsAtributosPermitidos === null) {
            $arrTagsAtributosPermitidos = array('style');
        }


        $objAntiXSS = new voku\helper\AntiXSS();
        $objAntiXSS->removeEvilHtmlTags($arrTagsPermitidas);
        $objAntiXSS->removeEvilAttributes($arrTagsAtributosPermitidos);
        //$objAntiXSS->setReplacement('[removed]');

        $dom = null;
        InfraDebug::getInstance()->gravarInfra('InfraXSS: Iniciar parse do DOM');
        if ($this->bolProcessarXML) {
            $dom = InfraHTML::parseXml($strConteudo);
        } elseif (strpos($strConteudo, '<st') !== false) {
            InfraDebug::getInstance()->gravarInfra('InfraXSS: Removendo smartTags do office');
            $re = '/<(st\d:\w+)\s([^>]*>.*)<\/\1>/misU';
            $strConteudo2 = preg_replace($re, '<span $2</span>', $strConteudo);
            if ($strConteudo2 != null) {
                $dom = InfraHTML::parseHtml($strConteudo2);
            } else {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: ERRO Removendo smartTags do office, ');
            }
        } else {
            $dom = InfraHTML::parseHtml($strConteudo);
        }
        if ($dom) {
            InfraDebug::getInstance()->gravarInfra('InfraXSS: Remover text nodes');
            InfraHTML::removerTextNodes($dom);
            if ($this->bolProcessarXML) {
                $strConteudoSemTexto = $dom->saveXML();
            } else {
                $strConteudoSemTexto = $dom->saveHTML();
            }
            $objAntiXSS->setXssDiffProcessing(false);

            $size = strlen($strConteudoSemTexto);
            InfraDebug::getInstance()->gravarInfra('Tamanho do arquivo sem texto: ' . InfraUtil::formatarMilhares($size));
            if ($size > 2000000) {
                $strConteudoSemTexto = preg_replace(
                    '/([^-]|^)(width|text-indent|height|font-size)\s*:\s*\d+(.\d+)?(px|pt|in|%);?/',
                    "$1",
                    $strConteudoSemTexto
                );
                $size = strlen($strConteudoSemTexto);
                InfraDebug::getInstance()->gravarInfra(
                    'Tamanho do arquivo sem width/heigh/text-indent/font-size: ' . InfraUtil::formatarMilhares($size)
                );
            }
            if ($size > 2000000) {
                $strConteudoSemTexto = preg_replace(
                    '/\s?((?>margin|padding|border)(?>-(?>left|right|top|bottom))?)\s*:\s*(0|(?>\d+(?>px|pt|in|%)));?/',
                    '',
                    $strConteudoSemTexto
                );
                $size = strlen($strConteudoSemTexto);
                InfraDebug::getInstance()->gravarInfra(
                    'Tamanho do arquivo sem margin/padding: ' . InfraUtil::formatarMilhares($size)
                );
            }
            $arrAtributos = [];
            $strConteudoSemTexto = preg_replace_callback(
                '/style="([^"]+)"/',
                static function ($matches) use (&$arrAtributos) {
                    $arrCss = explode(';', $matches[1]);
                    foreach ($arrCss as $key => $css) {
                        $css = trim($css);
                        if (preg_match('/^([^:]+):(.*)/', $css, $attr)) {
                            $strPropriedadeCss = $attr[1];
                            switch ($strPropriedadeCss) {
                                case 'text-indent':
                                case 'width':
                                case 'height':
                                case 'font-size':
                                case 'border-width':
                                    if (preg_match('/\s*(0|(?>\d+(.\d+)?(?>px|pt|in|%)))/', trim($attr[2]))) {
                                        unset($arrCss[$key]);
                                        continue 2;
                                    }
                                    break;
                                case 'text-decoration':
                                case 'text-underline':
                                case 'text-decoration-thickness':
                                    if (in_array(trim($attr[2]), ['none', 'initial', 'inherit'])) {
                                        unset($arrCss[$key]);
                                        continue 2;
                                    }
                                    break;
                            }
                            if (!isset($arrAtributos[$attr[1]])) {
                                $arrAtributos[$attr[1]] = 0;
                            }
                            $arrAtributos[$attr[1]]++;
                        }
                    }
                    if (count($arrCss)) {
                        return 'style="' . implode(";", $arrCss) . ';"';
                    }
                    return '';
                },
                $strConteudoSemTexto
            );
            $size = strlen($strConteudoSemTexto);
            InfraDebug::getInstance()->gravarInfra(
                'Tamanho do arquivo reduzindo o css: ' . InfraUtil::formatarMilhares($size)
            );

            $result = $this->verificacaoAvancadaInterno($strConteudoSemTexto, $objAntiXSS);
            if ($result === false) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: DOM sem XSS. Retornando OK;');
                return false;
            }
        } else {
            InfraDebug::getInstance()->gravarInfra('InfraXSS: Parse do DOM sem sucesso.');
        }
        InfraDebug::getInstance()->gravarInfra('InfraXSS: Avaliando conteúdo completo.');
        $objAntiXSS->setXssDiffProcessing($bolDiferenca);
        return $this->verificacaoAvancadaInterno($strConteudo, $objAntiXSS);
    }

    private function verificacaoAvancadaInterno(&$strConteudo, $objAntiXSS)
    {
        //exclui tag <!DOCTYPE >
        $count = 0;
        $bolDebug = InfraDebug::isBolProcessar();

        while (($posIni = strpos($strConteudo, '<!DOCTYPE')) !== false) {
            $posFechaTag = strpos($strConteudo, '>', $posIni);
            if ($posFechaTag !== false) {
                $strConteudo = substr_replace($strConteudo, '', $posIni, $posFechaTag - $posIni + 1);
                ++$count;
            }
        }
        if ($bolDebug) {
            InfraDebug::getInstance()->gravarInfra('InfraXSS: DOCTYPE removido - ' . $count);
            $strBackup = $strConteudo;
        }

      $count = 0;
      while (($posIni = stripos($strConteudo, '<meta')) !== false) {
        $posFechaTag = strpos($strConteudo, '>', $posIni);
        //validar se meta é permitida
        if ($posFechaTag !== false) {
          $strMeta=substr($strConteudo,$posIni,$posFechaTag - $posIni + 1);
          //bloqueia refresh ou redirects
          if(stripos($strMeta,'refresh')!==false || stripos($strMeta,'http:')!==false|| stripos($strMeta,'https:')!==false){
            break;
          }
          //se a tag estiver mal formada também deixa para a AntiXSS
          $strAtributos = substr($strMeta, 6, -1);
          if (substr($strAtributos, -1) === '/') {
            $strAtributos = substr($strAtributos, 0, -1);
          }
          $arrAtributos=InfraHTML::parseAtributosHtml($strAtributos);
          if($arrAtributos===false){
            break;
          }
          $strConteudo = substr_replace($strConteudo, '', $posIni, $posFechaTag - $posIni + 1);
        }
      }
      if ($bolDebug) {
        InfraDebug::getInstance()->gravarInfra('InfraXSS: meta removido - ' . $count);
        $strBackup = $strConteudo;
      }


        $strConteudo = str_replace('<!--/*--><![CDATA[/*><!--*/', '', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removido comentario CDATA ');
            }
            $strBackup = $strConteudo;
        }
        $strConteudo = str_replace('/*]]>*/-->', '', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removido fim comentario CDATA ');
            }
            $strBackup = $strConteudo;
        }
        $strConteudo = str_replace('<!--[if-->', '', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removido comentario condicional ');
            }
            $strBackup = $strConteudo;
        }


        $strConteudo = str_replace('href="javascript:void(0);"', '', $strConteudo);
        $strConteudo = str_replace('href="javascript:void(0)"', '', $strConteudo);
        $strConteudo = str_replace('href="javascript:;"', '', $strConteudo);
        $strConteudo = str_replace('href="javascript:"', '', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removido href=javascript ');
            }
            $strBackup = $strConteudo;
        }

        $strConteudo = str_replace('xmlns="http://www.w3.org/1999/xhtml"', '', $strConteudo);
        $strConteudo = str_replace('xmlns="http://www.w3.org/TR/REC-html40"', '', $strConteudo);
        $strConteudo = str_replace('<?xml version="1.0"?>', '', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removido xmlns/xml version ');
            }
            $strBackup = $strConteudo;
        }

        //substitui sequencia de espacos maior que 4 por um unico espaco
        $strConteudo = preg_replace('/\s{5,}/', ' ', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removida sequencia de espaços');
            }
            $strBackup = $strConteudo;
        }


        //remove href de telefones
        $strConteudo = preg_replace_callback('#href="callto:([^"]*)"#', array($this, 'validarTelefone'), $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removido href Telefone ');
            }
            $strBackup = $strConteudo;
        }


        //retirar imagens base64 antes do filtro
        $strConteudo = preg_replace_callback(
            '#data:\s*image/[a-z\-\+]+\s*;base64,[a-zA-Z0-9\/\+]*=*#',
            array($this, 'substituirConteudoHash'),
            $strConteudo
        );
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: tratados BASE64 ');
            }
            $strBackup = $strConteudo;
        }

        //remove comentarios condicionais
        while (($posIniComentario = strpos($strConteudo, '<!--[if ')) !== false) {
            if (($posFinalComentario = strpos($strConteudo, 'endif]-->', $posIniComentario)) === false) {
                break;
            }
            $strConteudo = substr_replace(
                $strConteudo,
                '',
                $posIniComentario,
                $posFinalComentario - $posIniComentario + 9
            );
        }
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removidos comentarios condicionais ');
            }
            $strBackup = $strConteudo;
        }

        //remove comentarios simples
        $strConteudo = preg_replace('/<!--([\s\S]*?)-->/', '', $strConteudo);
        if ($bolDebug) {
            if ($strBackup != $strConteudo) {
                InfraDebug::getInstance()->gravarInfra('InfraXSS: removidos comentarios simples ');
            }
            $strBackup = $strConteudo;
        }


        $strConteudo = $objAntiXSS->xss_clean($strConteudo);

        //recolocar imagens base64 após filtro
        $strConteudo = preg_replace_callback(
            '#data-infra-hash-([a-f0-9]{32}).jpg#',
            array($this, 'substituirHashConteudo'),
            $strConteudo
        );

        //ini_set('default_charset','ISO-8859-1');

        if ($objAntiXSS->isXssFound()) {
            $this->strDiferenca = $objAntiXSS->getXssDiff();
            return true;
        }

        return false;
    }

    private function substituirConteudoHash($match)
    {
        $strHash = hash('md5', $match[0]);
        $this->arrImagens[$strHash] = $match[0];
        return 'data-infra-hash-' . $strHash . '.jpg';
    }

    private function substituirHashConteudo($match)
    {
        return $this->arrImagens[$match[1]];
    }

    private function validarTelefone($match)
    {
        $str = urldecode($match[1]);
        if (preg_match('/[\(\)0-9\-+ ]*/', $str) === 1) {
            return 'href=""';
        }
        return $match[0];
    }

    public static function prepararTexto($str)
    {
        $objAntiXSS = new voku\helper\AntiXSS();
        return $objAntiXSS->prepareText($str);
    }
}

