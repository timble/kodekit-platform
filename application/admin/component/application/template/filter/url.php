<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Url Template Filter
 *
 * Filter allows to create url aliases that are replaced on compile and render. A default assets:// alias is
 * added that is rewritten to '<baseurl>/assets/'.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class ApplicationTemplateFilterUrl extends Library\TemplateFilterUrl
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
            'aliases'  => array('"/assets/' => '"'.$path),
        ));

        parent::_initialize($config);
    }
}