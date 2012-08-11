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
			'text'	=> 'name',
		));
		
		$options   = array();
		$view      = $this->getTemplate()->getView();
		$languages = $this->getService('com://admin/languages.model.languages')->getList();
		$active    = $this->getService('com://admin/languages.config.language')->getActive();
		
		foreach($languages as $language)
		{
		    $route = $view->getRoute('lang='.$language->slug);
		    $options[] = $this->option(array('text' => $language->name, 'value' => $route));
		    
		    if($language->iso_code == $active->iso_code) {
		        $config->selected = $route;
		    }
		}
		
		$config->options = $options;

		return parent::optionlist($config);
    } 
    
    public function langpacks($config = array())
    {
        $config = new KConfig($config);
		$config->append(array(
			'model' => 'langpacks',
			'name'  => 'name',
			'value'	=> 'name',
			'text'	=> 'title',
		));

		return parent::_listbox($config);
    } 
}