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
 * Dispatcher Response Singleton
 *
 * Force the user object to a singleton with identifier alias 'response'.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherResponse extends DispatcherResponseAbstract implements ObjectSingleton
{
    /**
     * Constructor
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     * @return Object
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Add a global object alias
        $this->getObject('manager')->registerAlias($this->getIdentifier(), 'response');
    }
}