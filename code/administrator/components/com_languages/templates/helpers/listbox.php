<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Template Helper Listbox Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComLanguagesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function languages($config = array())
    {
        $config = new KConfig($config);
		$config->append(array(
			'name'      => 'language',
		    'component' => null
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
			
		return $result;
    }
}