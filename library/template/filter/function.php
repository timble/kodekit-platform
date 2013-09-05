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
        'helper('    => '$this->renderHelper(',
    	'object('    => '$this->getObject(',
        'date('      => '$this->renderHelper(\'date.format\',',
        'overlay('   => '$this->renderHelper(\'behavior.overlay\', ',
        'translate(' => '$this->translate(',
        'import('    => '$this->loadFile(',
        'route('     => '$this->getView()->getRoute(',
        'escape('    => '$this->escape(',
        'url('       => '$this->getView()->getUrl()->toString(',
        'title('     => '$this->getView()->getTitle('
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
        $this->_functions[$name.'('] = $rewrite;
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
        $text = str_replace(
            array_keys($this->_functions),
            array_values($this->_functions),
            $text);
    }
}