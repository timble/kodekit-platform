<?php
/**
 * @package      Koowa_Template
 * @subpackage    Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Template write filter which runs the output through Tidy
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
class TemplateFilterPrettyprint extends TemplateFilterAbstract implements TemplateFilterRenderer
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
            'priority' => TemplateFilterChain::PRIORITY_LOWEST,
        ));

        parent::_initialize($config);
    }

    /**
     * Prettyprint the template output
     *
     * @param string $text  The text to parse
     * @return void
     */
    public function render(&$text)
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