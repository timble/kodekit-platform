<?php

class ComLanguagesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function languages($config = array())
    {
        $config = new KConfig($config);
		$config->append(array(
			'model' => 'languages',
			'name'  => 'language',
			'value'	=> 'iso_code',
			'text'	=> 'name'
		));
		
		$options   = array();
		$view      = $this->getTemplate()->getView();
		$languages = JFactory::getApplication()->getLanguages();
		$active    = $languages->getActive();
		
		foreach($languages as $language)
		{
		    $route = $view->getRoute('language='.$language->slug);
		    $options[] = $this->option(array('text' => $language->name, 'value' => $route));
		    
		    if($language->iso_code == $active->iso_code) {
		        $config->selected = $route;
		    }
		}
		
		$config->options = $options;

		return parent::optionlist($config);
    }
}