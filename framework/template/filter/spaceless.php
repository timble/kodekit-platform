<?php
/**
 * @package      Koowa_Template
 * @subpackage    Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Template write filter which removes all spaces from the template output
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
class TemplateFilterSpaceless extends TemplateFilterAbstract implements TemplateFilterWrite
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Config object with configuration options
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'priority' => Command::PRIORITY_LOWEST,
        ));

        parent::_initialize($config);
    }

    /**
     * Remove all spaces from the template output
     *
     * @param string
     * @return TemplateFilterForm
     */
    public function write(&$text)
    {
        $text = trim(preg_replace('/>\s+</', '><', $text));
        return $this;
    }
}