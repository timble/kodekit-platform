<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Whitespace Template Filter
 *
 * Filter which removes all spaces from the template output
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Template
 */
class TemplateFilterWhitespace extends TemplateFilterAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_LOWEST,
        ));

        parent::_initialize($config);
    }

    /**
     * Remove all spaces from the template output
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function filter(&$text)
    {
        $text = trim(preg_replace('/>\s+</', '><', $text));
    }
}