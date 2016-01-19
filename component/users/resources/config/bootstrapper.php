<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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