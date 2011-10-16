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
		
		$attribs = KHelperArray::toString($config->attribs);
		
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
			
			if($group->depth) {
		        $html[] = '<div style="padding-left: '.($group->depth * 15).'px" class="clearfix">';
		        $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$group->id.'" value="'.$group->id.'"'.$checked.' '.$attribs.'/>';
			    $html[] = '<label for="'.$config->name.$group->id.'">'.$group->name.'</label>';
			    $html[] = '</div>';
			} else {
				$html[] = '<h4>'.$group->name.'</h4>';
			}
		}
		
		return implode(PHP_EOL, $html);
	}
}