<?php
/**
 * @package        Nooku_Server
 * @subpackage     Comments
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Route Template Helper Class
 *
 * @author     Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package    Nooku_Server
 * @subpackage Comments
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
        $function = Library\StringInflector::singularize($config->row->getIdentifier()->name);
        $route    = $this->getTemplate()->getHelper('route')->$function($config);

        return $route;
    }
}