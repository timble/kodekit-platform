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
 * User File Session Handler
 *
 * Native session handler using PHP's built in file storage.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\User
 * @see     http://www.php.net/manual/en/function.session-set-save-handler.php
 */
class UserSessionHandlerFile extends UserSessionHandlerAbstract
{
    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return UserSessionHandlerFile
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if ($config->save_path && !is_dir($config->save_path)) {
            mkdir($config->save_path, 0755, true);
        }

        ini_set('session.save_handler', 'files');
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'save_path' => ini_get('session.save_path'),
        ));

        parent::_initialize($config);
    }
}