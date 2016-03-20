<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Translator Helper Class
 *
 * Adds translation keys used in JavaScript to the translator object
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Kodekit\Library\Template
 */
class TemplateHelperTranslator extends TemplateHelperAbstract
{
    public function script($config = array())
    {
        $config = new ObjectConfigJson($config);
        $config->append(array(
            'strings' => array()
        ));

        $strings    = ObjectConfig::unbox($config->strings);
        $translator = $this->getObject('translator');

        $translations = array();
        foreach ($strings as $string) {
            $translations[$string] = $translator($string);
        }

        $html  = '';
        $html .= $this->getTemplate()->helper('behavior.koowa') .
            "<script>
            if (typeof Koowa === 'object' && Koowa !== null) {
                if (typeof Koowa.translator === 'object' && Koowa.translator !== null) {
                    Koowa.translator.loadTranslations(".json_encode($translations).");
                }
            }
            </script>
            ";

        return $html;
    }
}
