<?php
class ComLanguagesControllerInstall extends KControllerAbstract
{
	/**
	 * Tables that are to be activated during install or on first run
	 */
	protected $_default_tables = array('categories', 'content', 'menu', 'modules', 'sections');
	
	public function _actionFinish(KCommandContext $context)
	{
	    $model  = $this->getService('com://admin/languages.model.tables');
		$tables = $model->getTranslated();
		
		if(!count($tables)) 
		{
		    foreach($this->_default_tables as $table)
		    {
		        $model->getTable()->getRow()->setData(array(
		            'table_name' => $table
		        ))->save();
		    }
		}
		
		//unlink(JPATH_COMPONENT.'/install/FIRST_RUN');
		
		JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_languages&view=dashboard', false));
	}
}