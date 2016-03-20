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
 * Localizable View Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\View
 */
class ViewBehaviorLocalizable extends ViewBehaviorAbstract
{
    /**
     * Load the language
     *
     * @param   ViewContextInterface $context A view context object
     * @return  void
     */
    protected function _beforeRender(ViewContextInterface $context)
    {
        $context->getSubject()->loadLanguage();
    }

    /**
	 * Load the translations
	 *
	 * @return 	void
	 */
	public function loadLanguage()
	{
        $package = $this->getIdentifier()->package;
        $domain  = $this->getIdentifier()->domain;

        if($domain) {
            $identifier = 'com://'.$domain.'/'.$package;
        } else {
            $identifier = 'com:'.$package;
        }

        $this->getObject('translator')->load($identifier);
	}
}