<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Library Object Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object\Library
 */
class ObjectLocatorLibrary extends ObjectLocatorAbstract
{
    /**
     * The locator names
     *
     * @var string
     */
    protected static $_name = 'lib';

    /**
     * Get the list of location templates for an identifier
     *
     * @param string $identifier The package identifier
     * @return string The class location templates for the identifier
     */
    public function getClassTemplates($identifier)
    {
        $templates = array(
            __NAMESPACE__.'\<Package><Class>',
            __NAMESPACE__.'\<Package><Path>Default',
        );

        return $templates;
    }
}