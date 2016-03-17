<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Library Object Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object\Library
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