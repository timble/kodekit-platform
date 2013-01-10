<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
		
		$attribs = $this->_buildAttributes($config->attribs);
		
		$groups  = $this->getService('com://admin/groups.model.groups')
            ->set('core', is_null($config->core) ? null : $config->core)
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
			$style   = $group->depth > 1 ? 'style="padding-left: '.($group->depth * 15).'px"' : '';
			
			if($group->depth) {
			    $html[] = '<label '.$style.' class="radio" for="'.$config->name.$group->id.'">';
			    $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$group->id.'" value="'.$group->id.'"'.$checked.' '.$attribs.'/>';
			    $html[] = $group->name;
			    $html[] = '</label>';

			} else {
				$html[] = '<h3>'.$group->name.'</h3>';
			}
		}
		
		return implode(PHP_EOL, $html);
	}
}