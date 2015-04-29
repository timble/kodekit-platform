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

        'dispatcher.fragment' => array(
            'behaviors' => array(
                'com:varnish.dispatcher.behavior.includable'
            )
        ),

        'com:varnish.controller.cache' => array(
            'debug'  => true,
            'esi'    => true,
            'secret' => 'c19f50ae-a113-46dd-9baa-c728060b0d3a',
        ),
    )
);