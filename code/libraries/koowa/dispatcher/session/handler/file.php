<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @subpackage  Session
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * File Session Handler Class
 *
 * Native session handler using PHP's built in file storage.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Session
 * @see         http://www.php.net/manual/en/function.session-set-save-handler.php
 */
class KDispatcherSessionHandlerFile extends KDispatcherSessionHandlerAbstract
{
    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KDispatcherSessionHandlerAbstract
     */
    public function __construct( KConfig $config = null )
    {
        parent::__construct($config);

        if ($config->save_path && !is_dir($config->save_path)) {
            mkdir($config->save_path, 0777, true);
        }

        ini_set('session.save_handler', 'files');
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'save_path' => ini_get('session.save_path'),
        ));

        parent::_initialize($config);
    }
}