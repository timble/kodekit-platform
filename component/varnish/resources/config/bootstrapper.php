<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-varnish for the canonical source repository
 */

return array(

    'identifiers' => array(

        'dispatcher.fragment' => array(
            'behaviors' => array(
                'com:varnish.dispatcher.behavior.includable' => array (
                    'secret' => 'c19f50ae-a113-46dd-9baa-c728060b0d3a',
                )
            )
        ),

        'com:varnish.controller.cache' => array(
            'debug'  => true,
            'esi'    => true,
            'secret' => 'c19f50ae-a113-46dd-9baa-c728060b0d3a',
        ),
    )
);