<?php

namespace TRF4\UI\Twig;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

class UIFormNode extends Node
{
    protected const ATTR_FORM = ["id", "method", "action"];

    public function __construct($params, $lineno = 0, $tag = null)
    {
        parent::__construct(array('params' => $params), array(), $lineno, $tag);
    }

    public function setAttrFormInline(array $params)
    {
        $formInlineParams = "";
        foreach ($params as $i => $param) {
            $formInlineParams .= " ".self::ATTR_FORM[$i]."=\"".$param."\"";             
        }
        return $formInlineParams;
    }

    public function compile(Compiler $compiler)
    {
        $count = count($this->getNode('params'));

        $tag = UIFormTokenParser::getStartTag();

        $compiler->addDebugInfo($this);

        for ($i = 0; ($i < $count); $i++) {
            // argument is not an expression (such as, a \Twig_Node_Textbody)
            // we should trick with output buffering to get a valid argument to pass
            // to the functionToCall() function.
            if (!($this->getNode('params')->getNode($i) instanceof AbstractExpression)) {
                $compiler
                    ->write('ob_start();')
                    ->raw(PHP_EOL);
                
                $compiler
                    ->subcompile($this->getNode('params')->getNode($i));

                $compiler
                    ->write('$_' . $tag . '[] = ob_get_clean();')
                    ->raw(PHP_EOL);
            } else {
                $compiler
                    ->write('$_' . $tag . '[] = ')
                    ->subcompile($this->getNode('params')->getNode($i))
                    ->raw(';')
                    ->raw(PHP_EOL);
            }
        }

        $compiler
            ->write('call_user_func_array(')
            ->raw('[' . self::class . '::class, "functionToCall"]')
            ->raw(', $_' . $tag . ');')
            ->raw(PHP_EOL);

        $compiler
            ->write('unset($_' . $tag . ');')
            ->raw(PHP_EOL);
    }


    public static function functionToCall()
    {
        $params = func_get_args();
        $body = array_shift($params);
        $body = rtrim($body, PHP_EOL);
        $formParams = self::setAttrFormInline($params);

        echo <<<html
<form$formParams novalidate class="needs-validation">
$body
</form>
html;
        //echo "body = {$body}", PHP_EOL;
        //echo "params = ", implode(', ', $params), PHP_EOL;
    }
}


