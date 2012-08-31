<?php

class ComLanguagesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function languages($config = array())
    {
        $config = new KConfig($config);
		$config->append(array(
			'name'      => 'language',
		    'component' => null
		));
		
		$application = $this->getService('application');
		$components  = $application->getComponents();
		
		if($components->find(array('name' => 'com_'.$config->component))->top()->isTranslatable())
		{
    		$options   = array();
    		$languages = $this->getService('application')->getLanguages();
    		$active    = $languages->getActive();
    		
    		foreach($languages as $language)
    		{
    		    $route = $this->getTemplate()->getView()->getRoute('language='.$language->slug);
    		    $options[] = $this->option(array('text' => $language->name, 'value' => $route));
    		    
    		    if($language->iso_code == $active->iso_code) {
    		        $config->selected = $route;
    		    }
    		}
    
    		$result = '
    		    <script>
                    window.addEvent(\'domready\', function() {
                        document.getElement(\'select[name='.$config->name.']\').addEvent(\'change\', function(){
                            window.location = this.value;
                        });
                    });
                </script>
    		';
    		
    		$config->options = $options;
    		$result .= parent::optionlist($config);
		}
		else $result = '';
		
		return $result;
    }
}