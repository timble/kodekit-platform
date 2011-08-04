<?php
class ComGroupsTemplateHelperSelect extends KTemplateHelperSelect
{
	public function groups(array $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
		    'name' => 'group'
		));
		
		$groups  = KFactory::tmp('admin::com.groups.model.groups')
            ->set('core', true)
            ->set('limit', 0)
		    ->getList();
		
		if($config->exclude instanceof KDatabaseRowInterface && $config->exclude->id) {
			foreach(clone $groups as $group) {
				if($group->lft >= $config->exclude->lft && $group->rgt <= $config->exclude->rgt) {
					$groups->extract($group);
				}
			}
		}
		
		foreach($groups as $group) {
			$checked = $config->selected == $group->id ? ' checked' : '';
			
			$html[] = '<div style="padding-left: '.($group->depth * 15).'px">';
		    $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$group->id.'" value="'.$group->id.'"'.$checked.' />';
		    $html[] = '<label for="'.$config->name.$group->id.'">'.$group->name.'</label>';
            $html[] = '</div>';
		}
		
		return implode(PHP_EOL, $html);
	}
}