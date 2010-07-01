<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesHelperChart extends KTemplateHelperAbstract
{
	public function pie($name)
 	{
		/*$model = KFactory::tmp('admin::com.profiles.model.' . $name);
		$items = $model->getList();
 		
		$c = KChartGoogle::getInstance(KChartGoogle::PIE);
    	
    	$data    = array();
    	foreach ($items as $item)
    	{
            $data[$item->title]   = $item->people;
        }
		
        // ucfirst labels and send through JText
        $labels = array_keys($data);
        array_walk($labels, array($this, '_cleanLabels'));

		// add Data, labels, and colors
        $c->addData($data)
          ->setValueLabels($labels)
          ->setWidth('450');

          return $c->getUrl();*/
 	}
 	
    protected function _cleanLabels(&$label)
    {
    	$label = JText::_($label);
    }
}