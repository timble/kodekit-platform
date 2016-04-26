<?php
/**
 * Kodekit - http://timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Decorator Template Filter
 *
 * Replace <ktml:content> with the view contents allowing to the template to act as a view decorator.
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class TemplateFilterDecorator extends Library\TemplateFilterDecorator
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'attributes' => array('decorator' => 'body'),
        ));

        parent::_initialize($config);
    }

    public function filter(&$text, Library\TemplateInterface $template)
    {
        $attributes = array();
        foreach($template->getParameters() as $name => $value) {
            $this->_attributes['data-'.$name] = $value;
        }

        parent::filter($text, $template);
    }
}
