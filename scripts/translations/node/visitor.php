<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Script;

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