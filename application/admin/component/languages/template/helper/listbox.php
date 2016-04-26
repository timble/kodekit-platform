<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Languages;

use Kodekit\Library;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Languages
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function languages($config = array(), Library\TemplateInterface $template)
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
            $route = $template->route('language='.$language->slug);
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