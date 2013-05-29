<?php
/**
* @package      Koowa_Template
* @subpackage   Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link         http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Template read filter for aliases such as @template, @text, @helper, @route etc
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
class TemplateFilterAlias extends TemplateFilterAbstract implements TemplateFilterCompiler, TemplateFilterRenderer
{
    /**
     * The alias read map
     *
     * @var array
     */
    protected $_alias_read = array(
        '@helper('      => '$this->renderHelper(',
    	'@object('     => '$this->getObject(',
        '@date('        => '$this->renderHelper(\'date.format\',',
        '@overlay('     => '$this->renderHelper(\'behavior.overlay\', ',
        '@text('        => '\JText::_(',
        '@template('    => '$this->loadFile(',
        '@route('       => '$this->getView()->getRoute(',
        '@escape('      => '$this->getView()->escape(',
        '@url('         => '$this->getView()->getBaseUrl()->toString('
    );

    /**
     * The alias write map
     *
     * @var array
     */
    protected $_alias_write = array();

    /**
     * Append an alias
     *
     * @param array $alias An array of aliases to be appended
     * @param int  $mode   The template mode
     * @return TemplateFilterAlias
     */
    public function addAlias(array $alias, $mode = TemplateFilter::MODE_COMPILE)
    {
        if($mode & TemplateFilter::MODE_COMPILE) {
            $this->_alias_read = array_merge($this->_alias_read, $alias);
        }

        if($mode & TemplateFilter::MODE_RENDER) {
            $this->_alias_write = array_merge($this->_alias_write, $alias);
        }

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
            array_keys($this->_alias_read),
            array_values($this->_alias_read),
            $text);
    }

    /**
     * Convert the alias
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function render(&$text)
    {
        $text = str_replace(
            array_keys($this->_alias_write),
            array_values($this->_alias_write),
            $text);
    }
}