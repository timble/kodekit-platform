<?php
/** $Id: html.php 419 2010-06-13 14:32:12Z johanjanssens $ */

class ComLogsViewLogsHtml extends ComDefaultViewHtml
{
	public function display()
	{
        $package = (!$this->getModel()->getState()->package) ? 'logs' : $this->getModel()->getState()->package;
        
		KFactory::get('admin::com.'.$package.'.toolbar.logs')
			->reset()
			->append(KFactory::get('admin::com.logs.toolbar.button.delete'));
		
		return parent::display();
	}
}