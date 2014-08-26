<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
namespace Nooku\Library;

/**
 * Prettyprint Template Filter
 *
 * Filter which runs the output through Tidy
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateFilterPrettyprint extends TemplateFilterAbstract
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
     * Prettyprint the template output
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function filter(&$text)
    {
        $config = array('options' => array(
            'clean'          => false,
            'show-body-only' => false,
            'bare'           => false,
            'word-2000'      => false,
            'indent'         => true,
            'vertical-space' => true,
            'drop-proprietary-attributes' => false,
        ));

        $text = $this->getObject('lib:filter.tidy', $config)->sanitize($text);
        return $this;
    }
}