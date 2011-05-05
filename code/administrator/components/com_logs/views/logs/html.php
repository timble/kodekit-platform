<?php
/** $Id$ */

class ComLogsViewLogsHtml extends ComDefaultViewHtml
{
	public function display()
	{
        $package = (!$this->getModel()->getState()->package) ? 'logs' : $this->getModel()->getState()->package;
        
		KFactory::get('admin::com.'.$package.'.toolbar.logs')
            ->setTitle(ucfirst($package). ' Logs')
			->reset()
			->append(KFactory::get('admin::com.logs.toolbar.button.delete'));
		
		return parent::display();
	}
}