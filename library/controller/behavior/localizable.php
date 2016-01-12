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
 * Localizable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerBehaviorLocalizable extends ControllerBehaviorAbstract
{
    /**
     * Load the language if the controller has not been dispatched
     *
     * @param   ControllerContextInterface $context A controller context object
     * @return  void
     */
    protected function _beforeRender(ControllerContextInterface $context)
    {
        $controller = $context->getSubject();

        if (!$controller->isDispatched()) {
            $controller->loadLanguage();
        }
    }

    /**
	 * Load the language
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