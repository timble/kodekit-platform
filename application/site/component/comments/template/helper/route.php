<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Route Template Helper Class
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Component\Comments
 */
class CommentsTemplateHelperRoute extends PagesTemplateHelperRoute
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