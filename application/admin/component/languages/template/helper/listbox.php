<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Languages
 */
class LanguagesTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function languages($config = array())
    {
        $config = new Library\ObjectConfig($config);
		$config->append(array(
			'name'  => 'language'
		));
		
		$result = '';
		
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
		$languages = $this->getObject('application.languages');
		$active    = $languages->getActive();
		
		foreach($languages as $language)
		{
		    $route = $this->getTemplate()->route('language='.$language->slug);
		    $options[] = $this->option(array('label' => $language->name, 'value' => $route));
		    
		    if($language->iso_code == $active->iso_code) {
		        $config->selected = $route;
		    }
		}
		
		$config->options = $options;
		$result .= parent::optionlist($config);
			
		return $result;
    }
}