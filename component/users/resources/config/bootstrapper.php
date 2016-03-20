<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

return array(

    'identifiers' => array(

        'dispatcher' => array(
            'authenticators' => array(
                'com:users.dispatcher.authenticator.form',
                'com:users.dispatcher.authenticator.cookie',
            ),
        ),

        'user.session' => array(
            'handler' => 'database'
        ),

        'user.provider'  => array(
           'model' => 'com:users.model.users',
        ),

        'lib:user.session.handler.database'  => array(
            'table' => 'com:users.database.table.sessions'
        )
    )

);