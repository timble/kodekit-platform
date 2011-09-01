<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Select Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 */

class ComGroupsTemplateHelperSelect extends KTemplateHelperSelect
{
	public function groups(array $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
		    'name' => 'group',
			'core' => null
		));
		
		$groups  = KFactory::get('com://admin/groups.model.groups')
            ->set('core', is_null($config->core) ? null : $config->core)
            ->set('limit', 0)
		    ->getList();
		
		if($config->exclude instanceof KDatabaseRowInterface && $config->exclude->id) 
		{
			foreach(clone $groups as $group) 
			{
				if($group->lft >= $config->exclude->lft && $group->rgt <= $config->exclude->rgt) {
					$groups->extract($group);
				}
			}
		}
		
		foreach($groups as $group) 
		{
			$checked = $config->selected == $group->id ? ' checked' : '';
			
			$html[] = '<div style="padding-left: '.($group->depth * 15).'px">';
		    $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$group->id.'" value="'.$group->id.'"'.$checked.' />';
		    $html[] = '<label for="'.$config->name.$group->id.'">'.$group->name.'</label>';
            $html[] = '</div>';
		}
		
		return implode(PHP_EOL, $html);
	}
}