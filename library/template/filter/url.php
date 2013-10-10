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
 * Url Template Filter
 *
 * Filter allows to create url aliases that are replaced on compile and render. A default assets:// alias is
 * added that is rewritten to '/media/'.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterUrl extends TemplateFilterAbstract implements TemplateFilterCompiler, TemplateFilterRenderer
{
    /**
     * The alias map
     *
     * @var array
     */
    protected $_aliases;

    /**
     * Constructor.
     *
     * @param   ObjectConfig $config Configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        foreach($config->aliases as $alias => $path) {
            $this->addAlias($alias, $path);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'aliases' => array('assets://' => '/assets/'),
        ));

        parent::_initialize($config);
    }

    /**
     * Add a path alias
     *
     * @param array $alias An array of aliases to be appended
     * @param int  $mode   The template mode
     * @return TemplateFilterUrl
     */
    public function addAlias($alias, $path)
    {
        $this->_aliases[$alias] = $path;
        return $this;
    }

    /**
     * Convert the schemas to their real paths
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function compile(&$text)
    {
        $text = str_replace(
            array_keys($this->_aliases),
            array_values($this->_aliases),
            $text);
    }

    /**
     * Convert the schemas to their real paths
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function render(&$text)
    {
        $text = str_replace(
            array_keys($this->_aliases),
            array_values($this->_aliases),
            $text);
    }
}