<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Theme;

use Kodekit\Library;

/**
 * Behavior Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
 */
class TemplateHelperBehavior extends Library\TemplateHelperBehavior
{
    /**
     * Drag and Drop Sortables Behavior
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function sortable($config = array())
    {
        $config = new Library\ObjectConfigJson($config);
        $config->append(array(
            'component' => $this->getIdentifier()->getPackage(),
            'view'      => Library\StringInflector::singularize($this->getTemplate()->getIdentifier()->getName()),
            'selector'  => 'table tbody.sortable',
            'direction' => 'asc',
            'url'       => '?format=json'
        ))->append(array(
            'options'   => array(
                'handle'    => 'td.handle',
                'numcolumn' => '.grid-count',
                'direction' => $config->direction,
                'adapter'   => array(
                    'type'      => 'kodekit',
                    'options'   => array(
                        'url'   => $config->url,
                        'data'  => array(
                            'csrf_token'    => $this->getObject('user')->getSession()->getToken(),
                            '_action'       => 'edit'
                        ),
                        'key'       => 'order',
                        'offset'    => 'relative'
                    )
                )
            )
        ));

        $html = '';

        $signature = md5(serialize(array($config->selector,$config->options)));
        if (!isset($this->_loaded[$signature]))
        {
            $options = !empty($config->options) ? $config->options->toArray() : array();

            $html .= '<ktml:script src="assets://theme/js/sortables.js" />';
            $html .= '<ktml:style src="assets://theme/css/sortables.css" />';
            $html .= "
                <script>
                (function(){
                    var sortable = function() {
                        $$('".$config->selector."').sortable(".json_encode($options).");
                    };
                    window.addEvents({domready: sortable, request: sortable});
                })();
                </script>
            ";

            $this->_loaded[$signature] = true;
        }

        return $html;
    }
}