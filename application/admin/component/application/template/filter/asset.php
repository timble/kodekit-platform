<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Url Template Filter
 *
 * Filter allows to define asset url schemes that are replaced on compile and render. A default assets:// alias is
 * added that is rewritten to '<baseurl>/assets/'.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
class ApplicationTemplateFilterAsset extends Library\TemplateFilterAsset
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        //Make asset paths absolute
        $base = $this->getObject('request')->getBaseUrl();
        $path = $this->getObject('request')->getBaseUrl()->getPath().'/assets/';

        $config->append(array(
            'priority' => self::PRIORITY_LOW,
            'schemes'  => array('assets://' => $path, '"/assets/' => '"'.$path),
        ));

        parent::_initialize($config);
    }

    /**
     * Convert the schemes to their real paths
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function filter(&$text)
    {
        $text = str_replace(
            array_keys($this->_schemes),
            array_values($this->_schemes),
            $text);
    }
}