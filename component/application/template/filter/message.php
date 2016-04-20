<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Message Template Filter
 *
 * Filter will render the response flash messages.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class TemplateFilterMessage extends Library\TemplateFilterAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_LOW
        ));

        parent::_initialize($config);
    }

    public function filter(&$text)
    {
        if (strpos($text, '<ktml:messages>') !== false)
        {
            $output   = '';
            $messages = $this->getObject('response')->getMessages();

            foreach ($messages as $type => $message)
            {
                $output .= '<div class="alert alert-'.strtolower($type).'">';
                foreach ($message as $line) {
                    $output .= '<div class="alert__text">'.$line.'</div>';
                }
                $output .= '</div>';
            }

            $text = str_replace('<ktml:messages>', $output, $text);
        }
    }
}