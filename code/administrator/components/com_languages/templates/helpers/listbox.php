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
		
		$result      = '';
		
		if($this->getService('application')->getCfg('multilanguage'))
		{
    		$components = $this->getService('application.components');
    		if($components->{$config->component}->isTranslatable())
    		{
    		    $result = '
        		    <script>
                        window.addEvent(\'domready\', function() {
                            document.getElement(\'select[name='.$config->name.']\').addEvent(\'change\', function() {
                                window.location = this.value;
                            });
                        });
                    </script>
        		';
    		    
        		$options   = array();
        		$languages = $this->getService('application.languages');
        		$active    = $languages->getActive();
        		
        		foreach($languages as $language)
        		{
        		    $route = $this->getTemplate()->getView()->getRoute('language='.$language->slug);
        		    $options[] = $this->option(array('text' => $language->name, 'value' => $route));
        		    
        		    if($language->iso_code == $active->iso_code) {
        		        $config->selected = $route;
        		    }
        		}
        		
        		$config->options = $options;
        		$result .= parent::optionlist($config);
    		}
		}	
			
		return $result;
    }
}