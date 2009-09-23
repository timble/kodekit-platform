<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerHelperChart extends KObject
{
	public function pie($name)
 	{
		$model = KFactory::get('admin::com.beer.model.' . $name);
		$items = $model->getAll();
 		
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

          return $c->getUrl();
 	}
 	
    /**
     * Callback function to clean up labels
     *
     * @param	string Label
     */
    protected function _cleanLabels(&$label)
    {
    	$label = JText::_(ucfirst(strtolower($label)));
    }
}