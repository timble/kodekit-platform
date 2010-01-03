<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default Csv View. Automatically uses the default model and turns it into a CSV,
 * using the model's states
.*
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultViewCsv extends KViewCsv
{
	public function display()
	{
		$model = KFactory::get($this->getModel());

		$data = array();
		
		// table header
		$data[] = array_keys(KFactory::get($model->getTable())->getFields());
		
		// data
		foreach($model->getList() as $item)
		{
			$row = $item->getData();
			unset($row['id']);
			$data[] = $row;
		}

		$this->assign('data', $data);		
		$name = $this->getIdentifier()->path;
		$this->assign('filename', array_pop($name).'.csv');

	 	parent::display();
	}
}