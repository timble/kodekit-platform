<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Module Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ModPagesHtml extends ModDefaultHtml
{
    public function display()
    {
        $tree = array();
        $components = $this->getService('com://admin/extensions.model.components')
            ->enabled(true)
            ->sort(array('ordering', 'name'))
            ->getList();
        
        foreach($components as $component)
        {
            $flat[$component->id] = array(
                'data' => $component,
                'children' => array()
            );
            
            if(array_key_exists($component->parent, $flat)) {
                $flat[$component->parent]['children'][] = &$flat[$component->id];
            }
            
            if($component->parent == 0) {
                $tree[] = &$flat[$component->id];
            }
        }
        
        $this->components = $tree;
        $this->user       = JFactory::getUser();

        return parent::display();
    }
}