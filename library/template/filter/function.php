<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Function Template Filter
 *
 * Compile filter for template functions such as template(), text(), helper(), route() etc
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterFunction extends TemplateFilterAbstract implements TemplateFilterCompiler
{
    /**
     * The functions map.
     *
     * @var array
     */
    protected $_functions = array(
        'helper'    => '$this->renderHelper(',
    	'object'    => '$this->getObject(',
        'date'      => '$this->renderHelper(\'date.format\',',
        'overlay'   => '$this->renderHelper(\'behavior.overlay\', ',
        'translate' => '$this->translate(',
        'import'    => '$this->loadFile(',
        'route'     => '$this->getView()->getRoute(',
        'escape'    => '$this->escape(',
        'url'       => '$this->getView()->getUrl()->toString(',
        'title'     => '$this->getView()->getTitle('
    );

    /**
     * Append an alias
     *
     * @param string $name      The function name
     * @param string $rewrite   The function will be rewritten too
     * @return TemplateFilterFunction
     */
    public function addFunction($name, $rewrite)
    {
        $this->_functions[$name] = $rewrite;
        return $this;
    }

    /**
     * Convert the alias
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function compile(&$text)
    {
        //Compile to valid PHP
        $tokens   = token_get_all($text);

        $result = '';
        for ($i = 0; $i < sizeof($tokens); $i++)
        {
            if(is_array($tokens[$i]))
            {
                list($token, $content) = $tokens[$i];

                switch ($token)
                {
                    //Convert registered functions to full function syntax
                    case T_STRING :

                        //Ensure function exists. Old style function names included (, catering for BC here
                        if(isset($this->_functions[$content]) || isset($this->_functions[$content.'(']) )
                        {
                            $prev = (array) $tokens[$i-1];
                            $next = (array) $tokens[$i+1];

                            if($next[0] == '(' && $prev[0] !== T_OBJECT_OPERATOR) {
                                $result .= isset($this->_functions[$content]) ? $this->_functions[$content] : $this->_functions[$content.'('];
                                $tokens[$i+1] = ''; //Remove the opening parentheses
                                break;
                            }
                        }

                        $result .= $content;
                        break;

                    default:
                        $result .= $content;
                        break;
                }
            }
            else $result .= $tokens[$i] ;
        }

        $text = $result;
    }
}