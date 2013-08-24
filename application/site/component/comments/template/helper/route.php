<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Route Template Helper Class
 *
 * @author     Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package    Component\Comments
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
        $function = $config->row->getIdentifier()->name;
        $route    = $this->getTemplate()->getHelper('route')->$function($config);

        return $route;
    }
}