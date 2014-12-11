<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'identifiers' => array(

        'dispatcher.response'       => array(
            'transports' => array('com:varnish.dispatcher.response.transport.esi')
        ),

        'com:varnish.model.sockets' => array(
            'secret' => 'c19f50ae-a113-46dd-9baa-c728060b0d3a',
        ),
    )

);