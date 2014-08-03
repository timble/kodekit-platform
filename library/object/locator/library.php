<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Library Object Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectLocatorLibrary extends ObjectLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'lib';

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'sequence' => array(
                '<Domain>\Library\<Package><Class>',
                '<Domain>\Library\<Package><Path>Default',
            )
        ));
    }
}