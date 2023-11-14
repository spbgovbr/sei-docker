<?php

class InfraHTML
{
    public static $REGEXP_IMAGEM = '@<img[^>]*>@i';
    public static $REGEX_IS_TAG = "#</?[^>]+>\\s*#iU";

    public static function getCssComparacao()
    {
        return "ins.diffmod {color:blue;} del.diffmod {color:red;} ins.mod {color:blue;} del.mod {color:red;} .diffins {color:blue;} .diffdel {color:red;}\n";
    }

    public static function comparar($str1, $str2)
    {
        $tags = array(
            '<o:p>' => '', '</o:p>' => '', chr(160) => ' '
        );

        foreach ($tags as $find => $replace) {
            $str1 = str_replace($find, $replace, $str1);
            $str2 = str_replace($find, $replace, $str2);
        }

        $str1 = self::removerImagens($str1);
        $str1 = InfraString::removerAcentosHTML($str1);

        $str2 = self::removerImagens($str2);
        $str2 = InfraString::removerAcentosHTML($str2);

        $diff = new HtmlDiff($str1, $str2);
        $diff->build();
        $ret = InfraString::fromUTF8($diff->getDifference());
        $subst = '<del class="diffmod">$1</del><ins class="diffmod">(link inválido)</ins>';
        $ret = preg_replace('/<a.*href=""[^>]*>(.*)<\/a>/', $subst, $ret);
        return $ret;
    }

    /**
     * Faz parser de conteúdo HTML retornando um DOMDocument
     * se houver erro de parser que não esteja no array de erros permitidos, retorna null
     * @param $strConteudoHTML
     * @param array $arrayErrosPermitidos
     * @return null|DOMDocument
     */
    public static function parseHtml($strConteudoHTML, $arrayErrosPermitidos = null)
    {
        if ($arrayErrosPermitidos === null) {
            $arrayErrosPermitidos = array();
            $arrayErrosPermitidos[] = 23;  //htmlParseEntityRef: expecting ';'   & -> &amp;
            $arrayErrosPermitidos[] = 68;  //XML_ERR_NAME_REQUIRED   & vazio -> &amp;
            $arrayErrosPermitidos[] = 513; //XML_DTD_ID_REDEFINED id duplicado (linksei)
            //    $arrayErrosPermitidos[]=76;  //XML_ERR_TAG_NAME_MISMATCH  unexpected end tag / Opening and ending tag mismatch
            //    $arrayErrosPermitidos[]=801; //tag invalida
            //    $arrayErrosPermitidos[]=800; //XML_HTML_STRUCURE_ERROR  htmlParseStartTag
            //    $arrayErrosPermitidos[]=201; //namespace not defined
        }
        $dom = new DOMDocument();
        $previous = libxml_use_internal_errors();
        libxml_clear_errors();
        libxml_use_internal_errors(true);
        $dom->recover = true;
        $dom->loadHTML($strConteudoHTML);
        $dom->formatOutput = true;
        $errors = libxml_get_errors();
        $bolAbortar = false;
        if (count($errors)) {
            foreach ($errors as $erro) {
                if ($erro->code == 801) {
                    $strTag = substr($erro->message, 4, strpos($erro->message, ' ', 4) - 4);
                    if (in_array(
                        $strTag,
                        ['figure', 'article', 'aside', 'mark', 'meter', 'nav', 'section', 'time', 'wbr']
                    )) {
                        continue;
                    }
                }
                if ($erro->code == 76 && strpos($erro->message, 'Unexpected end tag :') === 0) {
                    $strTag = substr($erro->message, 21, -1);
                    if (in_array($strTag, ['p', 'span', 'div', 'td', 'table', 'tr', 'th'])) {
                        continue;
                    }
                }
                if (!in_array($erro->code, $arrayErrosPermitidos)) {
                    $bolAbortar = true;
                    InfraDebug::getInstance()->gravarInfra(
                        'ParseHTML: linha: ' . $erro->line . ' coluna: ' . $erro->column . ' descrição: [' . $erro->code . ']' . $erro->message
                    );
                }
            }
        }
        libxml_use_internal_errors($previous);
        if ($bolAbortar) {
            unset($dom);
            return null;
        }

        return $dom;
    }

    /**
     * Faz parser de conteúdo XML retornando um DOMDocument
     * se houver erro de parser que não esteja no array de erros permitidos, retorna null
     * @param $strConteudoXML
     * @param array $arrayErrosPermitidos
     * @return null|DOMDocument
     */
    public static function parseXml($strConteudoXML, $arrayErrosPermitidos = null)
    {
        if ($arrayErrosPermitidos === null) {
            $arrayErrosPermitidos = array();
            $arrayErrosPermitidos[] = 23;  //htmlParseEntityRef: expecting ';'   & -> &amp;
            $arrayErrosPermitidos[] = 68;  //XML_ERR_NAME_REQUIRED   & vazio -> &amp;
            $arrayErrosPermitidos[] = 513; //XML_DTD_ID_REDEFINED id duplicado (linksei)
            //    $arrayErrosPermitidos[]=76;  //XML_ERR_TAG_NAME_MISMATCH  unexpected end tag / Opening and ending tag mismatch
            //    $arrayErrosPermitidos[]=801; //tag invalida
            //    $arrayErrosPermitidos[]=800; //XML_HTML_STRUCURE_ERROR  htmlParseStartTag
            //    $arrayErrosPermitidos[]=201; //namespace not defined
        }
        $dom = new DOMDocument();
        $previous = libxml_use_internal_errors();
        libxml_clear_errors();
        libxml_use_internal_errors(true);
        $dom->recover = true;
        $dom->loadXML($strConteudoXML);
        $dom->formatOutput = true;
        $errors = libxml_get_errors();
        $bolAbortar = false;
        if (count($errors)) {
            foreach ($errors as $erro) {
                if (!in_array($erro->code, $arrayErrosPermitidos, false)) {
                    $bolAbortar = true;
                }
            }
        }
        libxml_use_internal_errors($previous);
        if ($bolAbortar) {
            unset($dom);
            return null;
        }

        return $dom;
    }

    /**
     * Remove todos os nós de texto de um documento ou fragmento de HTML
     * @param DOMNode $node
     */
    public static function removerTextNodes(DOMNode $node)
    {
        $arrRemocao = array();
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $arrRemocao[] = $child;
                } else {
                    self::removerTextNodes($child);
                }
            }
        }
        foreach ($arrRemocao as $child) {
            $node->removeChild($child);
            unset($child);
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $node->parentNode->removeChild($node);
            return;
        }
    }

    public static function removerImagens($strHtml)
    {
        return preg_replace_callback(self::$REGEXP_IMAGEM, 'InfraHTML::md5Imagem', $strHtml);
    }

    /**
     * @param $matches  origem da REGEXP_IMAGEM ([0]=match)
     * @return string
     */
    public static function md5Imagem($matches)
    {
        return '[Imagem:' . md5($matches[0]) . ']';
    }

    /**
     * transforma string de atributos html (de dentro da tag) em um array associativo
     * se tiver atributo duplicado irá sobrescrever o anterior
     * se a string estiver mal formatada retorna false
     * @param $str
     * @return array|false
     */
    public static function parseAtributosHtml($str)
    {
        $strAtributos = $str;
        $arrAtributos = [];

        do {
            $strUltimoAtributo = null;
            $strAtributos = preg_replace_callback(
                '/^\s*([a-zA-Z-]+)\s*/',
                static function ($match) use (&$strUltimoAtributo) {
                    $strUltimoAtributo = $match[1];
                    return '';
                },
                $strAtributos
            );
            if ($strUltimoAtributo == null) {
                break;
            }
            if ($strAtributos == '') {
                $arrAtributos[$strUltimoAtributo] = null;
                break;
            }
            if ($strAtributos[0] === '=') {
                $strAtributos = trim(substr($strAtributos, 1));
                $strValor = null;
                $strAtributos = preg_replace_callback(
                    '/^([a-zA-Z0-9\.-]+|\'[^\']*\'|"[^"]*")/',
                    static function ($match) use (&$strValor) {
                        $strValor = $match[0];
                        if ($strValor[0] === '"' || $strValor[0] === '\'') {
                            $strValor = substr($strValor, 1, -1);
                        }
                        return '';
                    },
                    $strAtributos
                );
                if ($strValor == null) {
                    break;
                }
                $arrAtributos[$strUltimoAtributo] = $strValor;
            } else {
                $arrAtributos[$strUltimoAtributo] = null;
            }
        } while ($strUltimoAtributo != null);

        if ($strAtributos != '') {
            return false;
        }

        return $arrAtributos;
    }
}


class HtmlDiff
{
    private $content;
    private $oldText;
    private $newText;
    private $oldWords = array();
    private $newWords = array();
    private $wordIndices;
    private $specialCaseOpeningTags = array(
        '/<strong[^>]*/i',
        '/<b[^>]*/i',
        '/<i[^>]*/i',
        '/<big[^>]*/i',
        '/<small[^>]*/i',
        '/<u[^>]*/i',
        '/<sub[^>]*/i',
        '/<sup[^>]*/i',
        '/<strike[^>]*/i',
        '/<s[^>]*/i',
        '/<p[^>]*/i'
    );
    private $specialCaseClosingTags = array(
        '</strong>',
        '</b>',
        '</i>',
        '</big>',
        '</small>',
        '</u>',
        '</sub>',
        '</sup>',
        '</strike>',
        '</s>',
        '</p>'
    );


    private static $MODE_CHARACTER = 1;
    private static $MODE_TAG = 2;
    private static $MODE_WHITESPACE = 3;

    protected $resetCache = false;

    public function __construct($oldText, $newText)
    {
        $this->oldText = $this->purifyHtml($oldText);
        $this->newText = $this->purifyHtml($newText);
        $this->content = '';
    }

    public function getOldHtml()
    {
        return $this->oldText;
    }

    public function getNewHtml()
    {
        return $this->newText;
    }

    public function getDifference()
    {
        return $this->content;
    }


    private function purifyHtml($html, $tags = null)
    {
        return InfraString::toUTF8(trim($html));
    }

    public function build()
    {
        $this->SplitInputsToWords();
        $this->IndexNewWords();
        $operations = $this->Operations();
        foreach ($operations as $item) {
            $this->PerformOperation($item);
        }
        return $this->content;
    }

    private function IndexNewWords()
    {
        $this->wordIndices = array();
        foreach ($this->newWords as $i => $word) {
            if (preg_match(InfraHTML::$REGEX_IS_TAG, $word)) {
                $word = $this->StripTagAttributes($word);
            }
            if (isset($this->wordIndices[$word])) {
                $this->wordIndices[$word][] = $i;
            } else {
                $this->wordIndices[$word] = array($i);
            }
        }
    }

    private function SplitInputsToWords()
    {
        $this->oldWords = $this->ConvertHtmlToListOfWords($this->Explode($this->oldText));
        $this->newWords = $this->ConvertHtmlToListOfWords($this->Explode($this->newText));
    }

    private function ConvertHtmlToListOfWords($characterString)
    {
        $mode = self::$MODE_CHARACTER;
        $current_word = '';
        $words = array();
        foreach ($characterString as $character) {
            switch ($mode) {
                case self::$MODE_CHARACTER: //61864
                    if ($character === '<') { //911
                        if ($current_word !== '') { //910
                            $words[] = $current_word;
                        }
                        $current_word = '<';
                        $mode = self::$MODE_TAG;
                    } elseif (preg_match('[^\s]', $character)) { //9342
                        if ($current_word !== '') { //9342
                            $words[] = $current_word;
                        }
                        $current_word = preg_replace('/\s+/Su', ' ', $character);
                        $mode = self::$MODE_WHITESPACE;
                    } else { //51611
                        if (preg_match("/^[a-zA-Z0-9\pL]+$/u", $character) && ('' === $current_word || preg_match(
                                    '/[\p{L}\p{N}]+/u',
                                    $current_word
                                ))) { //47256
                            $current_word .= $character;
                        } else { //4355
                            $words[] = $current_word;
                            $current_word = $character;
                        }
                    }
                    break;
                case self::$MODE_TAG ://42471
                    if ($character === '>') {//3292
                        $current_word .= '>';
                        $words[] = $current_word;
                        $current_word = '';
                        if (!preg_match('[^\s]u', $character)) {//3292
                            $mode = self::$MODE_WHITESPACE;
                        } else {//0
                            $mode = self::$MODE_CHARACTER;
                        }
                    } else {//39179
                        $current_word .= $character;
                    }
                    break;
                case self::$MODE_WHITESPACE://22318
                    if ($character === '<') {//2381
                        if ($current_word !== '') {//1987
                            $words[] = $current_word;
                        }
                        $current_word = '<';
                        $mode = self::$MODE_TAG;
                    } elseif (preg_match('/\s/u', $character)) {//9684
                        $current_word .= $character;
                        $current_word = preg_replace('/\s+/Su', ' ', $current_word);
                    } else {//10253
                        if ($current_word !== '') {//9329
                            $words[] = $current_word;
                        }
                        $current_word = $character;
                        $mode = self::$MODE_CHARACTER;
                    }
                    break;
                default:
                    break;
            }
        }
        if ($current_word !== '') {
            $words[] = $current_word;
        }
        return $words;
    }


    private function Explode($value)
    {
        // as suggested by @onassar
        return preg_split('//u', $value);
    }

    private function PerformOperation($operation)
    {
        switch ($operation->Action) {
            case 'equal' :
                $this->ProcessEqualOperation($operation);
                break;
            case 'delete' :
                $this->ProcessDeleteOperation($operation, 'diffdel');
                break;
            case 'insert' :
                $this->ProcessInsertOperation($operation, 'diffins');
                break;
            case 'replace':
                $this->ProcessReplaceOperation($operation);
                break;
            default:
                break;
        }
    }

    private function ProcessReplaceOperation($operation)
    {
        $this->ProcessDeleteOperation($operation, 'diffmod');
        $this->ProcessInsertOperation($operation, 'diffmod');
    }

    private function ProcessInsertOperation($operation, $cssClass)
    {
        $text = array();
        foreach ($this->newWords as $pos => $s) {
            if ($pos >= $operation->StartInNew && $pos < $operation->EndInNew) {
                $text[] = $s;
            }
        }
        $this->InsertTag('ins', $cssClass, $text);
    }

    private function ProcessDeleteOperation($operation, $cssClass)
    {
        $text = array();
        foreach ($this->oldWords as $pos => $s) {
            if ($pos >= $operation->StartInOld && $pos < $operation->EndInOld) {
                $text[] = $s;
            }
        }
        $this->InsertTag('del', $cssClass, $text);
    }

    private function ProcessEqualOperation($operation)
    {
        $result = array();
        foreach ($this->newWords as $pos => $s) {
            if ($pos >= $operation->StartInNew && $pos < $operation->EndInNew) {
                $result[] = $s;
            }
        }
        $this->content .= implode('', $result);
    }

    private function InsertTag($tag, $cssClass, &$words)
    {
        while (true) {
            if (count($words) === 0) {
                break;
            }
            $nonTags = $this->ExtractConsecutiveWords($words, 'noTag');
            $specialCaseTagInjection = '';
            $specialCaseTagInjectionIsBefore = false;
            if (count($nonTags) !== 0) {
                $text = $this->WrapText(implode('', $nonTags), $tag, $cssClass);
                $this->content .= $text;
            } else {
                $firstOrDefault = false;
                foreach ($this->specialCaseOpeningTags as $x) {
                    if (preg_match($x, $words[0])) {
                        $firstOrDefault = $x;
                        break;
                    }
                }
                if ($firstOrDefault) {
                    $specialCaseTagInjection = '<ins class="mod">';
                    if ($tag === 'del') {
                        unset($words[0]);
                    }
                } elseif (in_array($words[0], $this->specialCaseClosingTags, false) !== false) {
                    $specialCaseTagInjection = '</ins>';
                    $specialCaseTagInjectionIsBefore = true;
                    if ($tag === 'del') {
                        unset($words[0]);
                    }
                }
            }
            if ((empty($words) || !is_array($words) || count($words) === 0) &&
                (empty($specialCaseTagInjection) || !is_array($specialCaseTagInjection) || count(
                        $specialCaseTagInjection
                    ) === 0)) {
                break;
            }
            if ($specialCaseTagInjectionIsBefore) {
                $this->content .= $specialCaseTagInjection . implode('', $this->ExtractConsecutiveWords($words, 'tag'));
            } else {
                $workTag = $this->ExtractConsecutiveWords($words, 'tag');
                if (isset($workTag[0]) && $this->IsOpeningTag($workTag[0]) && !$this->IsClosingTag($workTag[0])) {
                    if (strpos($workTag[0], 'class=')) {
                        $workTag[0] = str_replace('class="', 'class="diffmod ', $workTag[0]);
                        $workTag[0] = str_replace("class='", 'class="diffmod ', $workTag[0]);
                    } else {
                        $workTag[0] = str_replace('>', ' class="diffmod">', $workTag[0]);
                    }
                }
                $this->content .= implode('', $workTag) . $specialCaseTagInjection;
            }
        }
    }

    private function checkCondition($word, $condition)
    {
        return $condition === 'tag' ? preg_match(InfraHTML::$REGEX_IS_TAG, $word) : !preg_match(
            InfraHTML::$REGEX_IS_TAG,
            $word
        );
    }

    private function WrapText($text, $tagName, $cssClass)
    {
        return sprintf('<%1$s class="%2$s">%3$s</%1$s>', $tagName, $cssClass, $text);
    }

    private function ExtractConsecutiveWords(&$words, $condition)
    {
        $indexOfFirstTag = null;
        foreach ($words as $i => $word) {
            if (!$this->checkCondition($word, $condition)) {
                $indexOfFirstTag = $i;
                break;
            }
        }
        if ($indexOfFirstTag !== null) {
            $items = array();
            foreach ($words as $pos => $s) {
                if ($pos >= 0 && $pos < $indexOfFirstTag) {
                    $items[] = $s;
                }
            }
            if ($indexOfFirstTag > 0) {
                array_splice($words, 0, $indexOfFirstTag);
            }
            return $items;
        }
        $items = array();
        foreach ($words as $pos => $s) {
            if ($pos >= 0 && $pos <= count($words)) {
                $items[] = $s;
            }
        }
        array_splice($words, 0, count($words));
        return $items;
    }

//  private function IsTag($item)
//  {
//    return preg_match(InfraHTML::$REGEX_IS_TAG, $item);
//  }

    private function IsOpeningTag($item)
    {
        return preg_match("#<[^>]+>\\s*#iU", $item);
    }

    private function IsClosingTag($item)
    {
        return preg_match("#</[^>]+>\\s*#iU", $item);
    }

    private function Operations()
    {
        $positionInOld = 0;
        $positionInNew = 0;
        $operations = array();
        $matches = $this->MatchingBlocks();
        $matches[] = new Matcher(count($this->oldWords), count($this->newWords), 0);
        foreach ($matches as $i => $match) {
            $matchStartsAtCurrentPositionInOld = ($positionInOld === $match->StartInOld);
            $matchStartsAtCurrentPositionInNew = ($positionInNew === $match->StartInNew);
            $action = 'none';
            if ($matchStartsAtCurrentPositionInOld === false && $matchStartsAtCurrentPositionInNew === false) {
                $action = 'replace';
            } elseif ($matchStartsAtCurrentPositionInOld === true && $matchStartsAtCurrentPositionInNew === false) {
                $action = 'insert';
            } elseif ($matchStartsAtCurrentPositionInOld === false && $matchStartsAtCurrentPositionInNew === true) {
                $action = 'delete';
            }
            if ($action !== 'none') {// This occurs if the first few words are the same in both versions
                $operations[] = new Operation(
                    $action,
                    $positionInOld,
                    $match->StartInOld,
                    $positionInNew,
                    $match->StartInNew
                );
            }
            if (!empty($match)) {
                $operations[] = new Operation(
                    'equal',
                    $match->StartInOld,
                    $match->EndInOld(),
                    $match->StartInNew,
                    $match->EndInNew()
                );
            }
            $positionInOld = $match->EndInOld();
            $positionInNew = $match->EndInNew();
        }
        return $operations;
    }

    private function MatchingBlocks()
    {
        $matchingBlocks = array();
        $this->FindMatchingBlocks(0, count($this->oldWords), 0, count($this->newWords), $matchingBlocks);
        return $matchingBlocks;
    }

    private function FindMatchingBlocks($startInOld, $endInOld, $startInNew, $endInNew, &$matchingBlocks)
    {
        $match = $this->FindMatch($startInOld, $endInOld, $startInNew, $endInNew);
        if ($match !== null) {
            if ($startInOld < $match->StartInOld && $startInNew < $match->StartInNew) {
                $this->FindMatchingBlocks(
                    $startInOld,
                    $match->StartInOld,
                    $startInNew,
                    $match->StartInNew,
                    $matchingBlocks
                );
            }
            $matchingBlocks[] = $match;
            if ($match->EndInOld() < $endInOld && $match->EndInNew() < $endInNew) {
                $this->FindMatchingBlocks(
                    $match->EndInOld(),
                    $endInOld,
                    $match->EndInNew(),
                    $endInNew,
                    $matchingBlocks
                );
            }
        }
    }

    private function StripTagAttributes($word)
    {
        $word = explode(' ', trim($word, '<>'));
        return '<' . $word[0] . '>';
    }

    private function FindMatch($startInOld, $endInOld, $startInNew, $endInNew)
    {
        $bestMatchInOld = $startInOld;
        $bestMatchInNew = $startInNew;
        $bestMatchSize = 0;

        if ($this->oldWords[$startInOld] === $this->newWords[$startInNew]) {
            ++$bestMatchSize;
            $io = $startInOld + 1;
            $in = $startInNew + 1;
            while ($io < $endInOld && $in < $endInNew && $this->oldWords[$io] === $this->newWords[$in]) {
                ++$bestMatchSize;
                ++$io;
                ++$in;
            }
            return new Matcher($startInOld, $startInNew, $bestMatchSize);
        }

        if ($this->oldWords[$endInOld - 1] === $this->newWords[$endInNew - 1]) {
            ++$bestMatchSize;
            $io = $endInOld - 2;
            $in = $endInNew - 2;
            while ($io >= $startInOld && $in >= $startInNew && $this->oldWords[$io] === $this->newWords[$in]) {
                ++$bestMatchSize;
                --$io;
                --$in;
            }
            return new Matcher($endInOld - $bestMatchSize, $endInNew - $bestMatchSize, $bestMatchSize);
        }

        $matchLengthAt = array();
        for ($indexInOld = $startInOld; $indexInOld < $endInOld; $indexInOld++) {
            $newMatchLengthAt = array();
            $index = $this->oldWords[$indexInOld];

            if ($index === ' ') {
                foreach ($matchLengthAt as $match => $lenght) {
                    if ($this->newWords[$match + 1] === ' ') {
                        $newMatchLengthAt[$match + 1] = ++$lenght;
                    }
                    if ($lenght > $bestMatchSize) {
                        $bestMatchInOld = $indexInOld - $lenght + 1;
                        $bestMatchInNew = $match - $lenght + 2;
                        $bestMatchSize = $lenght;
                    }
                }
            }
            if (count($newMatchLengthAt) === 0) {
                if (preg_match(InfraHTML::$REGEX_IS_TAG, $index)) {
                    $index = $this->StripTagAttributes($index);
                }
                if (!isset($this->wordIndices[$index])) {
                    $matchLengthAt = $newMatchLengthAt;
                    continue;
                }
                foreach ($this->wordIndices[$index] as $indexInNew) {
                    if ($indexInNew < $startInNew) {
                        continue;
                    }
                    if ($indexInNew >= $endInNew) {
                        break;
                    }
                    $newMatchLength = (isset($matchLengthAt[$indexInNew - 1]) ? $matchLengthAt[$indexInNew - 1] : 0) + 1;
                    $newMatchLengthAt[$indexInNew] = $newMatchLength;
                    if ($newMatchLength > $bestMatchSize) {
                        $bestMatchInOld = $indexInOld - $newMatchLength + 1;
                        $bestMatchInNew = $indexInNew - $newMatchLength + 1;
                        $bestMatchSize = $newMatchLength;
                    }
                }
            }


            $matchLengthAt = $newMatchLengthAt;
        }

        if ($bestMatchSize === 1 && $this->oldWords[$bestMatchInOld] === ' ') {
            return null;
        }
        return $bestMatchSize !== 0 ? new Matcher($bestMatchInOld, $bestMatchInNew, $bestMatchSize) : null;
    }
}

class Matcher
{
    public $StartInOld;
    public $StartInNew;
    public $Size;

    public function __construct($startInOld, $startInNew, $size)
    {
        $this->StartInOld = $startInOld;
        $this->StartInNew = $startInNew;
        $this->Size = $size;
    }

    public function EndInOld()
    {
        return $this->StartInOld + $this->Size;
    }

    public function EndInNew()
    {
        return $this->StartInNew + $this->Size;
    }
}

class Operation
{
    public $Action;
    public $StartInOld;
    public $EndInOld;
    public $StartInNew;
    public $EndInNew;

    public function __construct($action, $startInOld, $endInOld, $startInNew, $endInNew)
    {
        $this->Action = $action;
        $this->StartInOld = $startInOld;
        $this->EndInOld = $endInOld;
        $this->StartInNew = $startInNew;
        $this->EndInNew = $endInNew;
    }
}