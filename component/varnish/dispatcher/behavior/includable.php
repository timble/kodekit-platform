<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Dispatcher Includable Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Varnish
 */
class DispatcherBehaviorIncludable extends Library\DispatcherBehaviorAbstract
{
    protected function _beforeInclude(Library\DispatcherContextInterface $context)
    {
        $query = array(
            'component'  => 'varnish',
            'view'       => 'fragment',
            'identifier' => (string) $context->param,
            'auth_token' => $this->getObject('com:varnish.dispatcher.authenticator.jwt')->createToken()
        );

        $route           = $this->getController()->getView()->getRoute($query, true);
        $context->result = '<esi:include src="'.$route.'" />';

        return false;
    }
}