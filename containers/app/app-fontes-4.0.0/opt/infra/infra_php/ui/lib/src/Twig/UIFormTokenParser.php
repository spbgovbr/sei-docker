<?php

namespace TRF4\UI\Twig;

use Twig\Error\SyntaxError;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class UIFormTokenParser extends AbstractTokenParser
{


    public static function getStartTag()
    {
        return 'form';
    }

    public static function getEndTag()
    {
        return 'endform';
    }

    public function parse(\Twig\Token $token)
    {
        $lineno = $token->getLine();

        $stream = $this->parser->getStream();

        // recovers all inline parameters close to your tag name
        $params = array_merge(array(), $this->getInlineParams($token));

        $endTag = self::getEndTag();
        $startTag = self::getStartTag();

        $continue = true;
        while ($continue) {
            // create subtree until the decideMyTagFork() callback returns true
            $body = $this->parser->subparse(array($this, 'decideMyTagFork'));

            // I like to put a switch here, in case you need to add middle tags, such
            // as: {% mytag %}, {% nextmytag %}, {% endmytag %}.
            $tag = $stream->next()->getValue();

            switch ($tag) {
                case $endTag:
                    $continue = false;
                    break;
                default:
                    throw new SyntaxError(sprintf("Unexpected end of template. Twig was looking for the following tags '$endTag' to close the '$startTag' block started at line %d)", $lineno), -1);
            }

            // you want $body at the beginning of your arguments
            array_unshift($params, $body);

            // if your endmytag can also contains params, you can uncomment this line:
            // $params = array_merge($params, $this->getInlineParams($token));
            // and comment this one:
            $stream->expect(Token::BLOCK_END_TYPE);
        }
        return new UIFormNode(new \Twig\Node\Node($params), $lineno, $this->getTag());
    }

    /**
     * Recovers all tag parameters until we find a BLOCK_END_TYPE ( %} )
     *
     * @param Token $token
     * @return array
     */
    protected function getInlineParams(Token $token)
    {
        $stream = $this->parser->getStream();
        $params = array();
        while (!$stream->test(Token::BLOCK_END_TYPE)) {
            $params[] = $this->parser->getExpressionParser()->parseExpression();
        }
        $stream->expect(Token::BLOCK_END_TYPE);
        return $params;
    }

    /**
     * Callback called at each tag name when subparsing, must return
     * true when the expected end tag is reached.
     *
     * @param Token $token
     * @return bool
     */
    public function decideMyTagFork(Token $token)
    {
        return $token->test([self::getEndTag()]);
    }

    /**
     * Your tag name: if the parsed tag match the one you put here, your parse()
     * method will be called.
     *
     * @return string
     */
    public function getTag()
    {
        return self::getStartTag();
    }
}