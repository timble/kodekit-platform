<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Script;

class TranslationsNodeVisitor extends \PHPParser_NodeVisitorAbstract
{
    protected $_generator;

    public function __construct($generator) {
        $this->_generator = $generator;
    }

    public function enterNode(\PHPParser_Node $node)
    {
        if (($node instanceof \PHPParser_Node_Expr_MethodCall || $node instanceof \PHPParser_Node_Expr_FuncCall) && $node->name == 'translate')
        {
            $this->logNode($node->args[0]->value);

            // Check for $this->getObject('translator')->translate('string')
            // (for template helpers)
            /*if ((string)$node->var->name === 'this') {
                $this->logNode($node->args[0]->value);
            }

            // Check for $this->getObject('translator')->getTranslator('identifier')->translate('string')
            if ((string)$node->var->name === 'getTranslator') {
                $this->logNode($node->args[0]->value);
            }

            // Check for $this->getObject('translator')->translate('string')
            if ((string)$node->var->name === 'getObject'
                && $node->var->args[0]->value instanceof PHPParser_Node_Scalar_String
                && ($node->var->args[0]->value->value === 'translator'
                    || strpos($node->var->args[0]->value->value, '.translator'))
             ) {
                $this->logNode($node->args[0]->value);
            }*/
        }

        if ($node instanceof \PHPParser_Node_Expr_MethodCall && is_string($node->name) && $node->name === 'choose') {
            $this->logNode($node->args[0]->value);
        }
    }

    public function logNode($node)
    {
        $this->_generator->logNode($node);
    }
}