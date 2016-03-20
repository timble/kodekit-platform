<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Comments;

use Kodekit\Library;
use Kodekit\Platform\Pages;

/**
 * Route Template Helper Class
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Platform\Comments
 */
class TemplateHelperRoute extends Pages\TemplateHelperRoute
{
    /**
     * Comment route helper
     *
     * This function will forward to the appropriate router based on the name of the row.
     *
     * @param array $config An array of configuration options
     * @return string The route
     */
    public function comment($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'view'  => 'comments',
        ));

        //Forward the route call
        $function = Library\StringInflector::singularize($config->entity->getIdentifier()->name);
        $route    = $this->getTemplate()->createHelper('route')->$function($config);

        return $route;
    }
}