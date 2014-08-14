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
 * User File Session Handler
 *
 * Native session handler using PHP's built in file storage.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
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