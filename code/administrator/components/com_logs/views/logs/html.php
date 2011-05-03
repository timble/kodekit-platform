<?php
/** $Id: html.php 419 2010-06-13 14:32:12Z johanjanssens $ */

class ComLogsViewLogsHtml extends ComDefaultViewHtml
{
	public function display()
	{
		KFactory::get('admin::com.logs.toolbar.logs')->reset();
		
		return parent::display();
	}
}