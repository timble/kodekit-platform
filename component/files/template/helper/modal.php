<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Modal Helper
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class TemplateHelperModal extends Library\TemplateHelperAbstract
{
    public function select($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'      => '',
            'visible'   => true,
            'link'      => '',
            'link_text' => $this->getObject('translator')->translate('Select'),
            'link_selector' => 'modal'
        ))->append(array(
            'value' => $config->name
        ));

        $input = '<input name="%1$s" id="%1$s" value="%2$s" %3$s size="40" />';

        $link = '<a class="%s"
                    rel="{\'ajaxOptions\': {\'method\': \'get\'}, \'handler\': \'iframe\', \'size\': {\'x\': 700}}"
                    href="%s">%s</a>';

        $html  = sprintf($input, $config->name, $config->value, $config->visible ? 'type="text" readonly' : 'type="hidden"');
        $html .= sprintf($link, $config->link_selector, $config->link, $config->link_text);

        return $html;
    }
}