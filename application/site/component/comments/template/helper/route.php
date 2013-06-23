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
    public function comment($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'access' => null,
            'layout' => null,
            'row'   => null,
            'table' => null,
        ));

        $route = array(
            'view'   => 'comment',
            'layout' => $config->layout,
            'row'   => $config->row,
            'table' => $config->table,
        );

        $needles = array(
            'extensions_component_id' => $this->getObject('application.components')
                ->getComponent($this->getIdentifier()->package)->id,
            'link'                    => array(
                array('view' => 'comments'))
        );

        if (isset($config->access)) {
            $needles['access'] = $config->access;
        }

        if ($page = $this->getObject('application.pages')->find($needles)) {
            $route['Itemid'] = $page->id;
        }

        return $this->getTemplate()->getView()->getRoute($route);
    }

}