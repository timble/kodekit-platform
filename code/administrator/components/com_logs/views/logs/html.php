<?php
/** $Id$ */

class ComLogsViewLogsHtml extends ComDefaultViewHtml
{
	public function display()
	{
        $package = 'logs';
        $title = 'Logs';
        
        if ($this->getModel()->getState()->package) 
        {
            $package = $this->getModel()->getState()->package;
            $title = ucfirst($package) . ' Logs';
        }
        
		KFactory::get('admin::com.'.$package.'.toolbar.logs')
            ->setTitle($title)
			->reset()
			->append(KFactory::get('admin::com.logs.toolbar.button.delete'));
		
		return parent::display();
	}
}