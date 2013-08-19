<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
namespace Nooku\Library;

/**
 * Prettyprint Template Filter
 *
 * Filter which runs the output through Tidy
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
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
            'priority' => TemplateFilter::PRIORITY_LOWEST,
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